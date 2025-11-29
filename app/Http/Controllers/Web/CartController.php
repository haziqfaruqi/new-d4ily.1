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

        // Get updated cart count
        $cart = Cart::where('user_id', auth()->id())->first();
        $cartCount = $cart ? $cart->items()->sum('quantity') : 0;

        return response()->json([
            'success' => true,
            'message' => 'Removed from cart',
            'cart_count' => $cartCount
        ]);
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

    public function checkout()
    {
        $cart = Cart::with(['items.product'])->where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('shop.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string',
        ]);

        try {
            // Calculate totals
            $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
            $shipping = 5.00;
            $tax = $subtotal * 0.08;
            $total = $subtotal + $shipping + $tax;

            // Create order
            $order = \App\Models\Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'total_price' => $total,
                'shipping_address' => $request->shipping_address,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax' => $tax,
                'shipping' => $shipping,
            ]);

            // Create order items
            foreach ($cart->items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            // Clear cart
            $cart->items()->delete();
            $cart->delete();

            return redirect()->route('order.confirmation', $order->id);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Checkout failed. Please try again.');
        }
    }

    public function orderConfirmation($orderId)
    {
        $order = \App\Models\Order::with('items.product')->where('id', $orderId)->where('user_id', auth()->id())->first();

        if (!$order) {
            return redirect()->route('shop.index')->with('error', 'Order not found');
        }

        return view('shop.order-confirmation', compact('order'));
    }

    public function orderHistory()
    {
        $orders = \App\Models\Order::with('items.product')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(10);
        return view('shop.order-history', compact('orders'));
    }
}
