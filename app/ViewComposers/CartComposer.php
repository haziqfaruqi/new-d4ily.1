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
            // Don't cache cart - always fetch fresh data
            // Caching causes stale data when items are added/removed
            $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();
            $view->with('cart', $cart);
        } else {
            $view->with('cart', null);
        }
    }
}
