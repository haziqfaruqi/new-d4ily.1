<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function product($id = null)
    {
        if ($id) {
            $product = Product::with('category')->findOrFail($id);
        } else {
            // Fallback for demo: get first product or create dummy
            $product = Product::with('category')->first();
            if (!$product) {
                // Create a dummy product for visualization if none exists
                $product = new Product([
                    'name' => 'Vintage Carhartt Detroit Jacket',
                    'description' => 'Authentic vintage workwear piece. Features a corduroy collar, blanket lining, and beautiful natural fading.',
                    'price' => 145.00,
                    'condition' => 'good',
                    'size' => 'L',
                    'brand' => 'Carhartt',
                    'color' => 'Moss Green',
                    'stock' => 1,
                    'images' => ['https://hoirqrkdgbmvpwutwuwj.supabase.co/storage/v1/object/public/assets/assets/917d6f93-fb36-439a-8c48-884b67b35381_1600w.jpg'],
                    'category_id' => 1 // Dummy
                ]);
                // We don't save it to DB here to avoid side effects in GET, 
                // but in a real app we'd expect DB data.
                // Let's just return the view with this dummy object if DB is empty.
            }
        }

        return view('customer.product', compact('product'));
    }

    public function adminDashboard()
    {
        // In a real app, we'd pass stats here.
        // For now, we'll return the view and let it (potentially) fetch data via API 
        // or we can pass some initial counts.
        $stats = [
            'total_revenue' => Order::sum('total_price'),
            'active_orders' => Order::whereIn('status', ['pending', 'confirmed', 'shipped'])->count(),
            'total_items' => Product::count(),
            'low_stock' => Product::where('stock', '<', 5)->count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get()
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
