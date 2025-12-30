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
            'condition' => 'required|in:new,like new,good,fair,poor',
            'size' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'featured' => 'boolean',
        ]);

        // Handle multiple image uploads - filter out null/empty values
        $images = [];
        if ($request->hasFile('images')) {
            $uploadedFiles = array_filter($request->file('images'), function($file) {
                return $file !== null && $file->isValid();
            });

            if (count($uploadedFiles) > 4) {
                return redirect()->back()->with('error', 'Maximum 4 images allowed');
            }

            foreach ($uploadedFiles as $image) {
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $filename);
                $images[] = '/uploads/products/' . $filename;
            }
        }
        $validated['images'] = $images;

        $validated['stock'] = 1; // All products have stock of 1

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
            'condition' => 'required|in:new,like new,good,fair,poor',
            'size' => 'required|string',
            'brand' => 'required|string',
            'color' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'existing_images' => 'nullable|array',
            'featured' => 'boolean',
        ]);

        // Start with existing images that weren't removed
        $existingImages = $request->input('existing_images');
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true) ?? [];
        }
        $images = $existingImages ?? [];

        // Delete removed images from filesystem
        foreach ($product->images as $oldImage) {
            if (!in_array($oldImage, $images) && file_exists(public_path($oldImage))) {
                unlink(public_path($oldImage));
            }
        }

        // Add new images - filter out null/empty values
        if ($request->hasFile('images')) {
            $uploadedFiles = array_filter($request->file('images'), function($file) {
                return $file !== null && $file->isValid();
            });

            foreach ($uploadedFiles as $image) {
                if (count($images) < 4) {
                    $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move(public_path('uploads/products'), $filename);
                    $images[] = '/uploads/products/' . $filename;
                }
            }
        }
        $validated['images'] = $images;

        $validated['stock'] = 1; // All products have stock of 1

        $product->update($validated);

        return redirect()->route('admin.inventory')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.inventory')->with('success', 'Product deleted successfully!');
    }

    public function toggleAvailability(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'is_available' => 'required|boolean',
        ]);

        $product->update(['is_available' => $request->is_available]);

        return response()->json([
            'success' => true,
            'message' => $request->is_available ? 'Product marked as available' : 'Product marked as sold',
        ]);
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
