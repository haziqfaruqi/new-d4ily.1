<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use App\Models\Order;
use App\Services\ToyyibPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $cart = Cart::with(['items.product'])->where('user_id', auth()->id())->first();

        // Fix cart items without prices
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->each(function ($item) {
                    $item->price = $item->product->price;
                    $item->save();
                });
        }

        return view('shop.cart', compact('cart'));
    }

    public function checkout()
    {
        $cart = Cart::with(['items.product'])->where('user_id', auth()->id())->first();

        // Fix cart items without prices
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->each(function ($item) {
                    $item->price = $item->product->price;
                    $item->save();
                });
        }

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        return view('shop.checkout', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $cart = Cart::with('items.product')->where('user_id', auth()->id())->first();

        // Fix cart items without prices
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->each(function ($item) {
                    $item->price = $item->product->price;
                    $item->save();
                });
        }

        if (!$cart || $cart->items->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $request->validate([
            'shipping_address' => 'required|string',
            'payment_method' => 'required|string|in:toyyibpay,bank_transfer',
        ]);

        try {
            // Calculate totals
            $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
            $shipping = 5.00;
            $tax = $subtotal * 0.08;
            $total = $subtotal + $shipping + $tax;

            // Create order
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => $request->payment_method === 'toyyibpay' ? 'pending' : 'confirmed',
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

            if ($request->payment_method === 'toyyibpay') {
                // Create ToyyibPay bill
                $toyyibPay = new ToyyibPayService();

                // Ensure total is valid and convert to sen (cents)
                $priceInSen = max(1, (int)round($total * 100)); // Minimum 1 sen

                Log::info('ToyyibPay Price Calculation:', [
                    'total' => $total,
                    'price_in_sen' => $priceInSen,
                    'formatted' => number_format($total, 2)
                ]);

                $billData = [
                    'bill_name' => 'Order #' . $order->id,
                    'bill_description' => 'Payment for your order at Vintage Thrift Shop',
                    'bill_price' => $priceInSen,
                    'bill_email' => auth()->user()->email ?? 'customer@example.com',
                    'bill_phone' => auth()->user()->phone ?? '60123456789',
                    'bill_amount' => $priceInSen, // The actual amount in sen (cents)
                    'bill_content_email' => "Thank you for your purchase! Order #" . $order->id,
                    'bill_reference_no' => $order->id,
                    'bill_to' => auth()->user()->name ?? 'Customer',
                    'order_id' => $order->id // Pass order ID for return URL
                ];

                $billResult = $toyyibPay->createBill($billData);

                if (!$billResult['success']) {
                    return redirect()->back()->with('error', 'Failed to create payment: ' . $billResult['message']);
                }

                // Update order with ToyyibPay details
                $order->update([
                    'bill_code' => $billResult['bill_code'],
                    'payment_status' => 'pending'
                ]);

                // Redirect to ToyyibPay for payment
                return redirect()->away($billResult['bill_url']);
            } else {
                // For bank transfer, mark as confirmed and show confirmation
                $order->update([
                    'status' => 'confirmed',
                    'payment_status' => 'pending'
                ]);

                // Clear cart
                $cart->items()->delete();
                $cart->delete();

                return redirect()->route('order.confirmation', $order->id);
            }

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
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
        $orders = \App\Models\Order::with('items.product')->where('user_id', auth()->id())->orderBy('created_at', 'desc')->paginate(5);
        return view('shop.order-history', compact('orders'));
    }

    public function toyyibPayCallback(Request $request)
    {
        try {
            $billCode = $request->input('billcode');
            $status = $request->input('status_code');

            if (!$billCode) {
                Log::error('ToyyibPay Callback: Missing bill code');
                return redirect()->route('shop.index')->with('error', 'Invalid payment notification');
            }

            // Find the order by bill code
            $order = Order::where('bill_code', $billCode)->first();

            if (!$order) {
                Log::error('ToyyibPay Callback: Order not found for bill code ' . $billCode);
                return redirect()->route('shop.index')->with('error', 'Order not found');
            }

            // Verify payment with ToyyibPay
            $toyyibPay = new ToyyibPayService();
            $verification = $toyyibPay->verifyPayment($billCode, $status);

            if ($verification['success'] && $verification['status'] === 'paid') {
                // Update order status
                $order->update([
                    'status' => 'completed',
                    'payment_status' => 'paid',
                    'transaction_id' => $verification['transaction_id'] ?? null
                ]);

                Log::info('Payment successful for order ' . $order->id);

                return redirect()->route('order.confirmation', $order->id)
                    ->with('success', 'Payment successful! Thank you for your order.');
            } else {
                // Payment failed or was cancelled
                $order->update([
                    'status' => 'failed',
                    'payment_status' => 'failed',
                    'transaction_id' => $verification['transaction_id'] ?? null
                ]);

                Log::warning('Payment failed for order ' . $order->id . ': ' . ($verification['message'] ?? 'Unknown error'));

                return redirect()->route('checkout')
                    ->with('error', 'Payment failed or was cancelled. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('ToyyibPay Callback Error: ' . $e->getMessage());
            return redirect()->route('shop.index')->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function cancelCheckout()
    {
        return redirect()->route('cart.index')->with('info', 'Payment was cancelled. You can return to your cart and try again.');
    }
}
