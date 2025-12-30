<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Interaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->available()->where('stock', '>', 0);

        // Track user's search for personalization
        if ($request->filled('search') && auth()->check()) {
            Interaction::create([
                'user_id' => auth()->id(),
                'product_id' => null,
                'type' => 'search',
                'session_id' => session()->getId(),
                'search_query' => $request->input('search'),
            ]);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Filter by condition
        if ($request->has('condition') && $request->condition) {
            $query->where('condition', $request->condition);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Content-based filtering: if logged in and no active filters, boost personalized products
        $personalizedProductIds = [];
        if (auth()->check() && !$request->hasAny(['search', 'category', 'condition', 'min_price', 'max_price'])) {
            $personalizedProductIds = $this->getPersonalizedProductIds(auth()->id());
        }

        // Sort
        $sort = $request->get('sort', 'recommended');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->withCount('interactions')->orderBy('interactions_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'recommended':
            default:
                // Personalized sort - boost products based on user history
                if (!empty($personalizedProductIds)) {
                    // Order by personalized relevance first, then by created_at
                    $query->orderByRaw("FIELD(id, " . implode(',', $personalizedProductIds) . ") DESC")
                          ->orderBy('created_at', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
        }

        $products = $query->paginate(10);
        $categories = Category::all();

        // Get cart data for navigation
        $cart = null;
        if (auth()->check()) {
            $cart = \App\Models\Cart::with('items.product')->where('user_id', auth()->id())->first();
        }

        $showPersonalizedLabel = auth()->check() && !$request->hasAny(['search', 'category', 'condition', 'min_price', 'max_price']) && !empty($personalizedProductIds);

        return view('shop.index', compact('products', 'categories', 'cart', 'showPersonalizedLabel'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);

        // Log interaction
        Interaction::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'type' => 'view',
            'session_id' => session()->getId(),
        ]);

        // Get similar products
        $similarProducts = $this->getSimilarProducts($product->id);

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        // Get cart data for navigation
        $cart = null;
        if (auth()->check()) {
            $cart = \App\Models\Cart::with('items.product')->where('user_id', auth()->id())->first();
        }

        return view('shop.product', compact('product', 'similarProducts', 'isWishlisted', 'cart'));
    }
    public function recommendations(Request $request)
    {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->route('shop.index')->with('error', 'Please login to see personalized recommendations');
        }

        // Get personalized recommendations from AI service
        try {
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
            $response = Http::timeout(10)->post("{$aiServiceUrl}/recommend/for-user", [
                'user_id' => $userId,
                'limit' => 20
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $productIds = $data['product_ids'] ?? [];

                if (!empty($productIds)) {
                    $products = Product::with('category')
                        ->whereIn('id', $productIds)
                        ->where('stock', '>', 0)
                        ->get()
                        ->sortBy(function ($product) use ($productIds) {
                            return array_search($product->id, $productIds);
                        });
                } else {
                    $products = collect();
                }
            } else {
                $products = collect();
            }
        } catch (\Exception $e) {
            $products = collect();
        }

        // Fallback to popular products if no recommendations
        if ($products->isEmpty()) {
            $products = Product::with('category')
                ->where('stock', '>', 0)
                ->withCount('interactions')
                ->orderBy('interactions_count', 'desc')
                ->limit(20)
                ->get();
        }

        return view('shop.recommendations', compact('products'));
    }

    protected function getSimilarProducts($productId, $limit = 10)
    {
        try {
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
            $response = Http::timeout(5)->post("{$aiServiceUrl}/recommend/similar-items", [
                'product_id' => $productId,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $productIds = $data['product_ids'] ?? [];

                if (!empty($productIds)) {
                    return Product::with('category')
                        ->whereIn('id', $productIds)
                        ->where('stock', '>', 0)
                        ->get();
                }
            }
        } catch (\Exception $e) {
            // Fallback to same category
        }

        // Fallback: get products from same category
        $product = Product::find($productId);
        return Product::with('category')
            ->available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $productId)
            ->where('stock', '>', 0)
            ->limit($limit)
            ->get();
    }

    /**
     * Get personalized products based on user's interaction history
     */
    protected function getPersonalizedProducts($userId, $limit = 8)
    {
        // Try AI service first
        try {
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
            $response = Http::timeout(10)->post("{$aiServiceUrl}/recommend/for-user", [
                'user_id' => $userId,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $productIds = $data['product_ids'] ?? [];

                if (!empty($productIds)) {
                    return Product::with('category')
                        ->whereIn('id', $productIds)
                        ->available()
                        ->where('stock', '>', 0)
                        ->limit($limit)
                        ->get()
                        ->sortBy(function ($product) use ($productIds) {
                            return array_search($product->id, $productIds);
                        })
                        ->values();
                }
            }
        } catch (\Exception $e) {
            // Fall back to enhanced content-based filtering
        }

        // Enhanced Fallback: Content-based filtering with weighted scoring
        return $this->getProductsByAttributeMatching($userId, $limit);
    }

    /**
     * Get products based on attribute matching (color, size, brand, category)
     * Uses weighted scoring from interaction history
     */
    protected function getProductsByAttributeMatching($userId, $limit = 8)
    {
        // Get user's interaction history with weights
        $interactions = Interaction::where('user_id', $userId)
            ->withWeights()
            ->with('product')
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        if ($interactions->isEmpty()) {
            // No history, return latest products
            return Product::with('category')
                ->available()
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }

        // Extract preference weights from interactions
        $categoryScores = [];
        $brandScores = [];
        $colorScores = [];
        $sizeScores = [];
        $conditionScores = [];

        $viewedProductIds = [];

        foreach ($interactions as $interaction) {
            $product = $interaction->product;
            if (!$product) continue;

            $viewedProductIds[] = $product->id;
            $weight = $interaction->weight;

            // Score categories
            if ($product->category_id) {
                $categoryScores[$product->category_id] = ($categoryScores[$product->category_id] ?? 0) + $weight;
            }

            // Score brands
            if ($product->brand) {
                $brandScores[$product->brand] = ($brandScores[$product->brand] ?? 0) + $weight;
            }

            // Score colors
            if ($product->color) {
                $colorScores[$product->color] = ($colorScores[$product->color] ?? 0) + $weight;
            }

            // Score sizes
            if ($product->size) {
                $sizeScores[$product->size] = ($sizeScores[$product->size] ?? 0) + $weight;
            }

            // Score conditions
            if ($product->condition) {
                $conditionScores[$product->condition] = ($conditionScores[$product->condition] ?? 0) + $weight;
            }
        }

        // Get candidate products (exclude already viewed)
        $candidates = Product::with('category')
            ->available()
            ->where('stock', '>', 0)
            ->whereNotIn('id', array_unique($viewedProductIds))
            ->get();

        // Score each candidate based on attribute matching
        $scoredProducts = $candidates->map(function ($product) use ($categoryScores, $brandScores, $colorScores, $sizeScores, $conditionScores) {
            $score = 0;

            // Category matching
            $score += $categoryScores[$product->category_id] ?? 0;

            // Brand matching
            if ($product->brand && isset($brandScores[$product->brand])) {
                $score += $brandScores[$product->brand] * 1.5; // Boost brand matches
            }

            // Color matching
            if ($product->color && isset($colorScores[$product->color])) {
                $score += $colorScores[$product->color] * 1.2;
            }

            // Size matching
            if ($product->size && isset($sizeScores[$product->size])) {
                $score += $sizeScores[$product->size] * 0.8;
            }

            // Condition matching
            if ($product->condition && isset($conditionScores[$product->condition])) {
                $score += $conditionScores[$product->condition] * 0.5;
            }

            // Small boost for newer products
            $score += max(0, (30 - $product->created_at->diffInDays()) / 10);

            $product->recommendation_score = $score;
            return $product;
        });

        // Sort by score and return top results
        return $scoredProducts
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }

    /**
     * Get personalized product IDs for sorting in main grid
     */
    protected function getPersonalizedProductIds($userId, $limit = 20)
    {
        // Try AI service first
        try {
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
            $response = Http::timeout(10)->post("{$aiServiceUrl}/recommend/for-user", [
                'user_id' => $userId,
                'limit' => $limit
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $productIds = $data['product_ids'] ?? [];
                if (!empty($productIds)) {
                    return array_slice($productIds, 0, $limit);
                }
            }
        } catch (\Exception $e) {
            // Fall back to enhanced content-based filtering
        }

        // Enhanced Fallback: Use attribute matching
        $products = $this->getProductsByAttributeMatching($userId, $limit);
        return $products->pluck('id')->toArray();
    }
}
