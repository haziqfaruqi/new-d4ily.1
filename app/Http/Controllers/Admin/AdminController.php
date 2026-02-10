<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::all();

        // Get inventory statistics
        $stats = [
            'total_products' => Product::count(),
            'available_products' => Product::where('is_available', true)->count(),
            'sold_products' => Product::where('is_available', false)->count(),
            'total_value' => Product::where('is_available', true)->sum('price'),
            'low_stock_categories' => Category::withCount('products')
                ->get()
                ->map(function($category) {
                    return [
                        'name' => $category->name,
                        'count' => $category->products_count,
                        'available' => Product::where('category_id', $category->id)
                            ->where('is_available', true)
                            ->count()
                    ];
                })
                ->sortByDesc('count')
                ->values(), // Show all categories, not just top 5
        ];

        // Get recent products
        $recentProducts = Product::with('category')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.inventory', compact('products', 'categories', 'stats', 'recentProducts'));
    }

    public function orders()
    {
        $orders = Order::with('user', 'items.product')->latest()->paginate(15);

        // Get order statistics
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'delivered_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status', '!=', 'cancelled')->sum('total_price'),
        ];

        return view('admin.orders', compact('orders', 'stats'));
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
        Log::info('=== STORE PRODUCT START ===');
        Log::info('Request data:', $request->all());
        Log::info('Has files:', ['has_file' => $request->hasFile('images')]);

        // Log file details before validation
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file) {
                    Log::info("File $index details:", [
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'extension' => $file->getClientOriginalExtension(),
                        'size' => $file->getSize(),
                        'valid' => $file->isValid(),
                        'error' => $file->getError()
                    ]);
                }
            }
        }

        try {
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
                'images.*' => 'nullable|file|max:5120', // Max 5MB, just check if it's a valid file
            ]);

            Log::info('Validation passed');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed:', ['errors' => $e->errors()]);
            throw $e;
        }

        // Handle multiple image uploads - filter out null/empty values
        $images = [];
        if ($request->hasFile('images')) {
            $uploadedFiles = array_filter($request->file('images'), function($file) {
                return $file !== null && $file->isValid();
            });

            Log::info('Uploaded files count:', ['count' => count($uploadedFiles)]);

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

        Log::info('Creating product with data:', $validated);

        $product = Product::create($validated);

        Log::info('Product created:', ['id' => $product->id, 'name' => $product->name]);
        Log::info('Total products in DB:', ['count' => Product::count()]);

        return redirect()->route('admin.inventory')->with('success', 'Product added successfully!');
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            Log::info('=== UPDATE PRODUCT START ===');
            Log::info('Request all data:', $request->all());

            $product = Product::findOrFail($id);
            Log::info('Product found:', ['id' => $product->id, 'name' => $product->name]);

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
                'existing_images' => 'nullable|string',
            ]);

            Log::info('Validation passed. Validated data:', $validated);

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

            Log::info('About to update product with:', $validated);
            $product->update($validated);
            Log::info('Product updated successfully');

            // Preserve page number if provided
            $page = $request->input('page', 1);
            return redirect()->route('admin.inventory', ['page' => $page])->with('success', 'Product updated successfully!');
        } catch (\Exception $e) {
            Log::error('Product update failed: ' . $e->getMessage());
            Log::error('Exception trace:', ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        // Preserve page number if provided
        $page = request()->input('page', 1);
        return redirect()->route('admin.inventory', ['page' => $page])->with('success', 'Product deleted successfully!');
    }

    public function editProduct($id)
    {
        $product = Product::with('category')->findOrFail($id);

        return response()->json([
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'description' => $product->description,
            'category_id' => $product->category_id,
            'price' => $product->price,
            'condition' => $product->condition,
            'size' => $product->size,
            'color' => $product->color,
            'images' => $product->images ?? [],
        ]);
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

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);

        // Delete order items first
        $order->items()->delete();

        // Delete the order
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully!');
    }
}
