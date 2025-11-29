<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $productId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to add items to cart'], 401);
        }

        $product = Product::findOrFail($productId);

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);

        // Check if item already in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->input('quantity', 1);
            $cartItem->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1),
                'price' => $product->price,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Added to cart!',
            'cart_count' => $cart->items()->sum('quantity')
        ]);
    }

    public function removeFromCart($itemId)
    {
        $cartItem = CartItem::findOrFail($itemId);

        if ($cartItem->cart->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $cartItem->delete();

        return response()->json(['success' => true, 'message' => 'Removed from cart']);
    }

    public function toggleWishlist($productId)
    {
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login to add to wishlist'], 401);
        }

        $wishlist = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $productId)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['success' => true, 'wishlisted' => false, 'message' => 'Removed from wishlist']);
        } else {
            Wishlist::create([
                'user_id' => auth()->id(),
                'product_id' => $productId,
            ]);
            return response()->json(['success' => true, 'wishlisted' => true, 'message' => 'Added to wishlist']);
        }
    }

    public function viewCart()
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();
        return view('shop.cart', compact('cart'));
    }
}
