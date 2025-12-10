<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Display the landing page.
     */
    public function index(Request $request)
    {
        // Get random featured products for display
        $featuredProducts = Product::inRandomOrder()->take(10)->get();

        return view('shop.landing', compact('featuredProducts'));
    }
}