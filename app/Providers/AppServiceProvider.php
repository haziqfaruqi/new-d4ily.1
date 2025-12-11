<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Cart;
use App\Models\CartItem;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Function to get cart count that filters unavailable products
        if (!function_exists('getCartCount')) {
            function getCartCount()
            {
                if (!auth()->check()) {
                    return 0;
                }

                $cart = Cart::with(['items.product' => function($query) {
                    $query->where('is_available', true);
                }])->where('user_id', auth()->id())->first();

                return $cart ? $cart->items->sum('quantity') : 0;
            }
        }
    }
}
