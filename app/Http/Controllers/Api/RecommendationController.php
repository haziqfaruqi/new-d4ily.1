<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
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
}
