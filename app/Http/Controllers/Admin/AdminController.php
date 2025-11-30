<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_price'),
            'active_orders' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'total_products' => Product::count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function inventory()
    {
        $products = Product::with('category')->paginate(10);
        $categories = Category::all();

        return view('admin.inventory', compact('products', 'categories'));
    }

    public function orders()
    {
        $orders = Order::with('user', 'items.product')->latest()->paginate(15);

        return view('admin.orders', compact('orders'));
    }

    public function customers()
    {
        $customers = User::where('role', 'customer')
            ->withCount('interactions')
            ->latest()
            ->paginate(20);

        return view('admin.customers', compact('customers'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:new,like new,good,fair,poor',
            'size' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $filename);
            $validated['images'] = ['/uploads/products/' . $filename];
        } else {
            $validated['images'] = [];
        }

        $validated['featured'] = $request->has('featured');

        Product::create($validated);

        return redirect()->route('admin.inventory')->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'condition' => 'required|in:new,like new,good,fair,poor',
            'size' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if (!empty($product->images[0]) && file_exists(public_path($product->images[0]))) {
                unlink(public_path($product->images[0]));
            }

            $image = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/products'), $filename);
            $validated['images'] = ['/uploads/products/' . $filename];
        } else {
            $validated['images'] = $product->images;
        }

        $validated['featured'] = $request->has('featured');

        $product->update($validated);

        return redirect()->route('admin.inventory')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.inventory')->with('success', 'Product deleted successfully!');
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders')->with('success', 'Order status updated successfully!');
    }
}
