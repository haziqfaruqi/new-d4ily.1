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
        $sort = $request->get('sort', 'newest');
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
            case 'recommended':
                // Personalized sort - boost products based on user history
                if (!empty($personalizedProductIds)) {
                    // Order by personalized relevance first, then by created_at
                    $query->orderByRaw("FIELD(id, " . implode(',', $personalizedProductIds) . ") DESC")
                          ->orderBy('created_at', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
                break;
            default:
                // For default view with logged-in users, boost personalized products
                if (!empty($personalizedProductIds)) {
                    $query->orderByRaw("FIELD(id, " . implode(',', $personalizedProductIds) . ") DESC")
                          ->orderBy('created_at', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
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
            // Fall back to content-based filtering
        }

        // Fallback: Content-based filtering using user's interaction history
        // Get products user has viewed or searched for
        $viewedProductIds = Interaction::where('user_id', $userId)
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->pluck('product_id')
            ->unique()
            ->take(10)
            ->toArray();

        // Get products from same categories as viewed products
        $categoriesFromViews = [];
        if (!empty($viewedProductIds)) {
            $categoriesFromViews = Product::whereIn('id', $viewedProductIds)
                ->pluck('category_id')
                ->unique()
                ->toArray();
        }

        // Get last search query
        $lastSearch = Interaction::where('user_id', $userId)
            ->where('type', 'search')
            ->whereNotNull('search_query')
            ->orderBy('created_at', 'desc')
            ->value('search_query');

        $query = Product::with('category')
            ->available()
            ->where('stock', '>', 0);

        // Exclude already viewed products
        if (!empty($viewedProductIds)) {
            $query->whereNotIn('id', $viewedProductIds);
        }

        // If user has searched before, prioritize matching products
        if ($lastSearch) {
            $query->where(function ($q) use ($lastSearch, $categoriesFromViews) {
                $q->where('name', 'like', "%{$lastSearch}%")
                    ->orWhere('description', 'like', "%{$lastSearch}%")
                    ->orWhere('brand', 'like', "%{$lastSearch}%");
            });
        } elseif (!empty($categoriesFromViews)) {
            // Otherwise show products from categories they've browsed
            $query->whereIn('category_id', $categoriesFromViews);
        }

        return $query->orderBy('created_at', 'desc')->limit($limit)->get();
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
            // Fall back to content-based filtering
        }

        // Fallback: Content-based filtering using user's interaction history
        // Get last search query
        $lastSearch = Interaction::where('user_id', $userId)
            ->where('type', 'search')
            ->whereNotNull('search_query')
            ->orderBy('created_at', 'desc')
            ->value('search_query');

        // Get products matching last search
        if ($lastSearch) {
            $searchMatches = Product::select('id')
                ->available()
                ->where('stock', '>', 0)
                ->where(function ($q) use ($lastSearch) {
                    $q->where('name', 'like', "%{$lastSearch}%")
                        ->orWhere('description', 'like', "%{$lastSearch}%")
                        ->orWhere('brand', 'like', "%{$lastSearch}%");
                })
                ->limit($limit)
                ->pluck('id')
                ->toArray();

            if (!empty($searchMatches)) {
                return $searchMatches;
            }
        }

        // Get categories from viewed products
        $viewedProductIds = Interaction::where('user_id', $userId)
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->pluck('product_id')
            ->unique()
            ->take(10)
            ->toArray();

        if (!empty($viewedProductIds)) {
            $categoriesFromViews = Product::whereIn('id', $viewedProductIds)
                ->pluck('category_id')
                ->unique()
                ->toArray();

            if (!empty($categoriesFromViews)) {
                $categoryMatches = Product::select('id')
                    ->available()
                    ->where('stock', '>', 0)
                    ->whereIn('category_id', $categoriesFromViews)
                    ->whereNotIn('id', $viewedProductIds)
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->pluck('id')
                    ->toArray();

                return $categoryMatches;
            }
        }

        return [];
    }
}
