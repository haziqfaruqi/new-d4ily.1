<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Cart;

class CartComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        // Only share cart for authenticated users
        if (auth()->check()) {
            // Cache cart for 2 minutes to reduce queries
            $cacheKey = 'user_cart_' . auth()->id();
            $cart = cache()->remember($cacheKey, 120, function () {
                return Cart::with('items.product')->where('user_id', auth()->id())->first();
            });
            $view->with('cart', $cart);
        } else {
            $view->with('cart', null);
        }
    }
}
