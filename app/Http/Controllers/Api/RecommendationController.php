<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Interaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class RecommendationController extends Controller
{
    protected $aiServiceUrl;

    public function __construct()
    {
        $this->aiServiceUrl = env('AI_SERVICE_URL', 'http://localhost:8000');
    }

    public function similarItems(Request $request, $productId)
    {
        try {
            $response = Http::post("{$this->aiServiceUrl}/recommend/similar-items", [
                'product_id' => (int) $productId,
                'limit' => $request->input('limit', 4)
            ]);

            if ($response->successful()) {
                $productIds = $response->json()['product_ids'];
                $products = Product::whereIn('id', $productIds)->get();

                // Maintain order
                $sortedProducts = $products->sortBy(function ($model) use ($productIds) {
                    return array_search($model->id, $productIds);
                })->values();

                return response()->json($sortedProducts);
            }

            return response()->json(['message' => 'Failed to fetch recommendations'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'AI Service unavailable'], 503);
        }
    }

    public function forUser(Request $request)
    {
        try {
            $response = Http::post("{$this->aiServiceUrl}/recommend/for-user", [
                'user_id' => $request->user()->id,
                'limit' => $request->input('limit', 4)
            ]);

            if ($response->successful()) {
                $productIds = $response->json()['product_ids'];

                if (empty($productIds)) {
                    // Fallback to latest products
                    return response()->json(Product::latest()->take(4)->get());
                }

                $products = Product::whereIn('id', $productIds)->get();

                $sortedProducts = $products->sortBy(function ($model) use ($productIds) {
                    return array_search($model->id, $productIds);
                })->values();

                return response()->json($sortedProducts);
            }

            return response()->json(['message' => 'Failed to fetch recommendations'], 500);
        } catch (\Exception $e) {
            return response()->json(['message' => 'AI Service unavailable'], 503);
        }
    }

    /**
     * Get personalized recommendations based on recently viewed/clicked items
     * Uses content-based filtering with fallback to attribute matching
     */
    public function personalized(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userId = Auth::id();
        $limit = $request->input('limit', 8);

        // Get recently viewed/clicked products
        $recentInteractions = Interaction::where('user_id', $userId)
            ->whereIn('type', ['view', 'click'])
            ->with('product')
            ->whereNotNull('product_id')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        if ($recentInteractions->isEmpty()) {
            // No history, return latest products
            return response()->json(
                Product::with('category')
                    ->available()
                    ->where('stock', '>', 0)
                    ->orderBy('created_at', 'desc')
                    ->take($limit)
                    ->get()
            );
        }

        $viewedProductIds = $recentInteractions->pluck('product_id')->unique()->toArray();

        // Use attribute-based recommendations (fallback method)
        $recommendations = $this->getAttributeBasedRecommendations($viewedProductIds, $limit, $viewedProductIds);

        return response()->json($recommendations);
    }

    /**
     * Get similar products based on a specific product's category and brand
     * Used when user clicks/views a product to show related items immediately
     */
    public function similarProducts(Request $request, $productId)
    {
        $limit = $request->input('limit', 8);

        // Get the viewed product
        $viewedProduct = Product::with('category')->find($productId);

        if (!$viewedProduct) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Build query for similar products (same brand OR same category)
        $query = Product::with('category')
            ->available()
            ->where('stock', '>', 0)
            ->where('id', '!=', $productId)
            ->where(function ($q) use ($viewedProduct) {
                // Same brand OR same category
                $q->where('brand', $viewedProduct->brand)
                  ->orWhere('category_id', $viewedProduct->category_id);
            });

        $similarProducts = $query->get();

        // Score and prioritize products
        $scoredProducts = $similarProducts->map(function ($product) use ($viewedProduct) {
            $score = 0;

            // Same brand + same category (highest priority)
            if ($product->brand === $viewedProduct->brand && $product->category_id === $viewedProduct->category_id) {
                $score += 100;
            }
            // Same brand only
            elseif ($product->brand === $viewedProduct->brand) {
                $score += 50;
            }
            // Same category only
            elseif ($product->category_id === $viewedProduct->category_id) {
                $score += 40;
            }

            // Same color
            if ($product->color && $product->color === $viewedProduct->color) {
                $score += 20;
            }

            // Same condition
            if ($product->condition === $viewedProduct->condition) {
                $score += 10;
            }

            // Similar price (within 30%)
            $priceDiff = abs($product->price - $viewedProduct->price) / $viewedProduct->price;
            if ($priceDiff < 0.3) {
                $score += 15;
            }

            // Newness boost
            $newnessBoost = max(0, (30 - $product->created_at->diffInDays()) / 10);
            $score += $newnessBoost;

            $product->similarity_score = $score;
            return $product;
        });

        // Sort by score and return top results
        $result = $scoredProducts
            ->sortByDesc('similarity_score')
            ->take($limit)
            ->values();

        return response()->json($result);
    }

    /**
     * Get recommendations based on attributes of viewed products
     */
    protected function getAttributeBasedRecommendations(array $viewedProductIds, int $limit, array $excludeIds = [])
    {
        // Get the viewed products to extract their attributes
        $viewedProducts = Product::with('category')
            ->whereIn('id', $viewedProductIds)
            ->get();

        if ($viewedProducts->isEmpty()) {
            return collect();
        }

        // Extract attributes from viewed products
        $brands = $viewedProducts->pluck('brand')->filter()->unique()->toArray();
        $categoryIds = $viewedProducts->pluck('category_id')->filter()->unique()->toArray();
        $colors = $viewedProducts->pluck('color')->filter()->unique()->toArray();

        // Find similar products based on attributes
        $candidates = Product::with('category')
            ->available()
            ->where('stock', '>', 0)
            ->whereNotIn('id', $excludeIds)
            ->where(function ($query) use ($brands, $categoryIds, $colors) {
                $query->whereIn('brand', $brands)
                    ->orWhereIn('category_id', $categoryIds);
                if (!empty($colors)) {
                    $query->orWhereIn('color', $colors);
                }
            })
            ->get();

        // Score candidates based on attribute matches
        $scoredProducts = $candidates->map(function ($product) use ($brands, $categoryIds, $colors) {
            $score = 0;

            // Brand match (highest priority)
            if (in_array($product->brand, $brands)) {
                $score += 30;
            }

            // Category match
            if (in_array($product->category_id, $categoryIds)) {
                $score += 20;
            }

            // Color match
            if (!empty($colors) && in_array($product->color, $colors)) {
                $score += 15;
            }

            // Newness boost
            $newnessBoost = max(0, (30 - $product->created_at->diffInDays()) / 10);
            $score += $newnessBoost;

            $product->recommendation_score = $score;
            return $product;
        });

        return $scoredProducts
            ->sortByDesc('recommendation_score')
            ->take($limit)
            ->values();
    }
}
