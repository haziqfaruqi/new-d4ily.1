<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Filters
        if ($request->has('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        if ($request->has('size')) {
            $query->where('size', $request->input('size'));
        }

        if ($request->has('condition')) {
            $query->where('condition', $request->input('condition'));
        }

        if ($request->has('color')) {
            $query->where('color', $request->input('color'));
        }

        if ($request->has('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        // Sorting
        $sort = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');
        $query->orderBy($sort, $direction);

        return $query->paginate(12);
    }

    public function show($id)
    {
        return Product::with('category')->findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:new,like new,good,fair,poor',
            'size' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'images' => 'required|array', // In real app, handle file uploads
            'featured' => 'boolean',
        ]);

        $product = Product::create($request->all());

        return response()->json($product, 201);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric|min:0',
            'stock' => 'integer|min:0',
            'condition' => 'in:new,like new,good,fair,poor',
            'size' => 'string',
            'brand' => 'string',
            'color' => 'string',
            'images' => 'array',
            'featured' => 'boolean',
        ]);

        $product->update($request->all());

        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(['message' => 'Product deleted']);
    }
}
