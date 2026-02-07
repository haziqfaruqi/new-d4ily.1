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

        // Get recommended products based on viewed/clicked items (for logged-in users)
        $recommendedProducts = collect();
        $personalizedProductIds = [];
        $personalizedProducts = [];
        $showPersonalizedLabel = false;

        if (auth()->check() && !$request->hasAny(['search', 'category', 'condition', 'min_price', 'max_price'])) {
            // Single call to get personalized products - use this for both recommendations and sorting
            $personalizedProducts = $this->getProductsByAttributeMatching(auth()->id(), 20);
            $personalizedProductIds = $personalizedProducts->pluck('id')->toArray();
            $showPersonalizedLabel = !empty($personalizedProductIds);

            // Use top 8 from personalized for "recommended products" section
            $recommendedProducts = $personalizedProducts->take(8);
        }

        // Sort
        $sort = $request->get('sort', 'newest'); // Changed default from 'recommended' to 'newest'
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
            default:
                // For newest, boost personalized products to the top, then show by date
                if (!empty($personalizedProductIds)) {
                    $query->orderByRaw("FIELD(id, " . implode(',', $personalizedProductIds) . ") DESC")
                          ->orderBy('created_at', 'desc');
                } else {
                    $query->orderBy('created_at', 'desc');
                }
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
        }

        $products = $query->paginate(10);
        $categories = Category::all();

        // Attach matched attributes to products for display
        if (!empty($personalizedProducts) && auth()->check()) {
            $matchedAttributesMap = [];
            foreach ($personalizedProducts as $p) {
                if (isset($p->matched_attributes)) {
                    $matchedAttributesMap[$p->id] = $p->matched_attributes;
                }
            }

            // Add matched attributes to paginated products
            $products->getCollection()->transform(function ($product) use ($matchedAttributesMap) {
                if (isset($matchedAttributesMap[$product->id])) {
                    $product->matched_attributes = $matchedAttributesMap[$product->id];
                }
                return $product;
            });
        }

        return view('shop.index', compact('products', 'categories', 'showPersonalizedLabel', 'personalizedProducts', 'recommendedProducts'));
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

        // Get products from the same brand (prioritized by category match)
        $sameBrandProducts = $this->getSameBrandProducts($product);

        // Get products from the same category
        $sameCategoryProducts = $this->getSameCategoryProducts($product);

        $isWishlisted = false;
        if (auth()->check()) {
            $isWishlisted = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('shop.product', compact('product', 'sameBrandProducts', 'sameCategoryProducts', 'isWishlisted'));
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

    /**
     * Get products from the same brand, prioritized by category match
     */
    protected function getSameBrandProducts($product, $limit = 10)
    {
        // Get all products from the same brand
        $brandProducts = Product::with('category')
            ->available()
            ->where('brand', $product->brand)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->get();

        // Prioritize products from the same category
        $scoredProducts = $brandProducts->map(function ($p) use ($product) {
            $score = 0;

            // Priority 1: Same category (highest priority)
            if ($p->category_id === $product->category_id) {
                $score += 100;
            }

            // Priority 2: Similar condition
            if ($p->condition === $product->condition) {
                $score += 20;
            }

            // Priority 3: Similar price range (within 30%)
            $priceDiff = abs($p->price - $product->price) / $product->price;
            if ($priceDiff < 0.3) {
                $score += 10;
            }

            $p->priority_score = $score;
            return $p;
        });

        // Sort by priority score and return top results
        return $scoredProducts
            ->sortByDesc('priority_score')
            ->take($limit)
            ->values();
    }

    /**
     * Get products from the same category (excluding current product and same brand products)
     */
    protected function getSameCategoryProducts($product, $limit = 10)
    {
        $categoryProducts = Product::with('category')
            ->available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->get();

        // Score category products for similarity percentage
        $scoredProducts = $categoryProducts->map(function ($p) use ($product) {
            $score = 0;

            // Same category gives a base score
            $score += 50;

            // Bonus for similar condition
            if ($p->condition === $product->condition) {
                $score += 15;
            }

            // Bonus for similar price range (within 30%)
            $priceDiff = abs($p->price - $product->price) / $product->price;
            if ($priceDiff < 0.3) {
                $score += 10;
            }

            $p->priority_score = $score;
            return $p;
        });

        // Sort by priority score
        return $scoredProducts
            ->sortByDesc('priority_score')
            ->values();
    }

    protected function getSimilarProducts($productId, $limit = 10)
    {
        // Try content-based recommendations first
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
            // Continue to brand/category fallback
        }

        // Fallback 1: Try brand and category-based recommendations
        try {
            $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
            $response = Http::timeout(5)->post("{$aiServiceUrl}/recommend/brand-category", [
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
            // Continue to same category fallback
        }

        // Fallback 2: Get products from same category (local database query)
        $product = Product::find($productId);
        if (!$product) {
            return collect();
        }

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
     * Uses weighted scoring from interaction history with emphasis on brand, name, and color
     * Optimized with eager loading and single query
     */
    protected function getProductsByAttributeMatching($userId, $limit = 8)
    {
        // Use a cache key for this user's recommendations
        $cacheKey = "user_recommendations_{$userId}";

        // Try to get from cache first (5 minute cache)
        $cached = cache()->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }

        // Get user's interaction history with weights - single optimized query
        $interactions = Interaction::where('user_id', $userId)
            ->withWeights()
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get();

        if ($interactions->isEmpty()) {
            // No history, return latest products
            $products = Product::with('category')
                ->available()
                ->where('stock', '>', 0)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();

            cache()->put($cacheKey, $products, 300); // Cache for 5 minutes
            return $products;
        }

        // Get all product IDs in one query
        $productIds = $interactions->pluck('product_id')->unique()->filter()->toArray();

        // Get all referenced products in one query with eager loading
        $viewedProducts = Product::with('category')
            ->whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        // Extract preference weights from interactions
        $categoryScores = [];
        $brandScores = [];
        $colorScores = [];
        $sizeScores = [];
        $conditionScores = [];
        $nameKeywords = []; // Track keywords from product names

        $viewedProductIds = [];

        foreach ($interactions as $interaction) {
            $product = $viewedProducts->get($interaction->product_id);
            if (!$product) continue;

            $viewedProductIds[] = $product->id;
            $weight = $interaction->weight;

            // Score categories (lower priority)
            if ($product->category_id) {
                $categoryScores[$product->category_id] = ($categoryScores[$product->category_id] ?? 0) + $weight;
            }

            // Score brands (HIGH PRIORITY - 3x weight)
            if ($product->brand) {
                $brandScores[$product->brand] = ($brandScores[$product->brand] ?? 0) + ($weight * 3);
            }

            // Score colors (HIGH PRIORITY - 2.5x weight)
            if ($product->color) {
                $colorScores[$product->color] = ($colorScores[$product->color] ?? 0) + ($weight * 2.5);
            }

            // Score sizes (medium priority)
            if ($product->size) {
                $sizeScores[$product->size] = ($sizeScores[$product->size] ?? 0) + ($weight * 0.8);
            }

            // Score conditions (lower priority)
            if ($product->condition) {
                $conditionScores[$product->condition] = ($conditionScores[$product->condition] ?? 0) + ($weight * 0.5);
            }

            // Extract keywords from product name (HIGH PRIORITY)
            $words = explode(' ', strtolower($product->name));
            foreach ($words as $word) {
                if (strlen($word) > 3) { // Only consider meaningful words
                    $nameKeywords[$word] = ($nameKeywords[$word] ?? 0) + ($weight * 2);
                }
            }
        }

        // Get candidate products (exclude already viewed)
        $candidates = Product::with('category')
            ->available()
            ->where('stock', '>', 0)
            ->whereNotIn('id', array_unique($viewedProductIds))
            ->get();

        // Score each candidate based on attribute matching
        $scoredProducts = $candidates->map(function ($product) use ($categoryScores, $brandScores, $colorScores, $sizeScores, $conditionScores, $nameKeywords) {
            $score = 0;
            $matchedAttributes = [];

            // Brand matching (HIGHEST WEIGHT)
            if ($product->brand && isset($brandScores[$product->brand])) {
                $brandScore = $brandScores[$product->brand];
                $score += $brandScore;
                $matchedAttributes['brand'] = $brandScore;
            }

            // Color matching (VERY HIGH WEIGHT)
            if ($product->color && isset($colorScores[$product->color])) {
                $colorScore = $colorScores[$product->color];
                $score += $colorScore;
                $matchedAttributes['color'] = $colorScore;
            }

            // Product name keyword matching (HIGH WEIGHT)
            $words = explode(' ', strtolower($product->name));
            foreach ($words as $word) {
                if (strlen($word) > 3 && isset($nameKeywords[$word])) {
                    $nameScore = $nameKeywords[$word];
                    $score += $nameScore;
                    if (!isset($matchedAttributes['name'])) {
                        $matchedAttributes['name'] = 0;
                    }
                    $matchedAttributes['name'] += $nameScore;
                }
            }

            // Category matching (medium weight)
            $categoryMatchScore = $categoryScores[$product->category_id] ?? 0;
            if ($categoryMatchScore > 0) {
                $score += $categoryMatchScore;
                $matchedAttributes['category'] = $categoryMatchScore;
            }

            // Size matching (lower weight)
            if ($product->size && isset($sizeScores[$product->size])) {
                $score += $sizeScores[$product->size];
            }

            // Condition matching (lowest weight)
            if ($product->condition && isset($conditionScores[$product->condition])) {
                $score += $conditionScores[$product->condition];
            }

            // Small boost for newer products
            $newnessBoost = max(0, (30 - $product->created_at->diffInDays()) / 10);
            $score += $newnessBoost;

            $product->recommendation_score = $score;
            $product->matched_attributes = $matchedAttributes;
            return $product;
        });

        // Sort by score and return top results
        $result = $scoredProducts
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();

        // Cache the result for 5 minutes
        cache()->put($cacheKey, $result, 300);

        return $result;
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

    /**
     * Get recommended products based on recently viewed/clicked items
     * Uses AI service to find similar products to what the user has viewed
     */
    protected function getRecommendedFromViewedProducts($userId, $limit = 8)
    {
        // Get recently viewed/clicked products (last 10 interactions)
        $recentInteractions = Interaction::where('user_id', $userId)
            ->whereIn('type', ['view', 'click'])
            ->with('product')
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        if ($recentInteractions->isEmpty()) {
            return collect();
        }

        $recommendedProductIds = collect();

        // Try AI service for each viewed product
        foreach ($recentInteractions->take(3) as $interaction) {
            try {
                $aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
                $response = Http::timeout(5)->post("{$aiServiceUrl}/recommend/similar-items", [
                    'product_id' => $interaction->product_id,
                    'limit' => 3
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $productIds = $data['product_ids'] ?? [];
                    $recommendedProductIds = $recommendedProductIds->concat($productIds);
                }
            } catch (\Exception $e) {
                // Continue to next product
                continue;
            }
        }

        // Remove already viewed products
        $viewedProductIds = $recentInteractions->pluck('product_id')->unique();
        $recommendedProductIds = $recommendedProductIds->diff($viewedProductIds)->unique();

        if ($recommendedProductIds->isEmpty()) {
            return collect();
        }

        // Fetch the recommended products
        return Product::with('category')
            ->whereIn('id', $recommendedProductIds->take($limit))
            ->where('stock', '>', 0)
            ->get()
            ->sortBy(function ($product) use ($recommendedProductIds) {
                return array_search($product->id, $recommendedProductIds->toArray());
            })
            ->take($limit)
            ->values();
    }
}
