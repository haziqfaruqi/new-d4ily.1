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
        $query = Product::with('category')->where('stock', '>', 0);

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
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::all();

        return view('shop.index', compact('products', 'categories'));
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

        return view('shop.product', compact('product', 'similarProducts', 'isWishlisted'));
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

    protected function getSimilarProducts($productId, $limit = 8)
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
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $productId)
            ->where('stock', '>', 0)
            ->limit($limit)
            ->get();
    }
}
