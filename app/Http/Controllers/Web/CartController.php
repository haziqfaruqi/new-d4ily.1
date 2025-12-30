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
use Illuminate\Support\Facades\DB;

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
            // For thrift items, prevent adding same item multiple times
            return response()->json([
                'error' => 'This item is already in your cart. Thrift items can only be purchased once per listing.'
            ], 409); // 409 Conflict status code
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

        // Fix cart items without prices and remove items with unavailable products
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->each(function ($item) {
                    $item->price = $item->product->price;
                    $item->save();
                });

            // Remove items where the product is no longer available
            CartItem::where('cart_id', $cart->id)
                ->whereHas('product', function ($query) {
                    $query->where('is_available', false);
                })
                ->delete();
        }

        return view('shop.cart', compact('cart'));
    }

    public function checkout()
    {
        $cart = Cart::with(['items.product'])->where('user_id', auth()->id())->first();

        // Fix cart items without prices and remove items with unavailable products
        if ($cart) {
            CartItem::where('cart_id', $cart->id)
                ->where(function ($query) {
                    $query->whereNull('price')->orWhere('price', 0);
                })
                ->each(function ($item) {
                    $item->price = $item->product->price;
                    $item->save();
                });

            // Remove items where the product is no longer available
            CartItem::where('cart_id', $cart->id)
                ->whereHas('product', function ($query) {
                    $query->where('is_available', false);
                })
                ->delete();
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
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_street' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postcode' => 'required|string',
            'shipping_state' => 'required|string',
            'payment_method' => 'required|string|in:toyyibpay,bank_transfer',
        ]);

        // Store address as JSON to handle commas in street address
        $shippingAddressData = [
            'name' => $request->shipping_name,
            'phone' => $request->shipping_phone,
            'street' => $request->shipping_street,
            'city' => $request->shipping_city,
            'postcode' => $request->shipping_postcode,
            'state' => $request->shipping_state,
            'country' => $request->shipping_country ?? 'Malaysia',
        ];
        $shippingAddress = json_encode($shippingAddressData);

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
                'shipping_address' => $shippingAddress,
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

                // Mark products as unavailable for bank transfer orders
                $productIds = [];
                foreach ($order->items as $item) {
                    $productIds[] = $item->product_id;
                }

                Log::info('Marking products as unavailable for bank transfer: ' . implode(', ', $productIds));
                \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->update(['is_available' => false]);

                // Verify the update
                $updatedProducts = \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->pluck('is_available', 'id');
                Log::info('Updated products status for bank transfer: ' . $updatedProducts->toJson());

                // Clear cart after marking products as unavailable
                $cart->items()->delete();
                $cart->delete();

                return redirect()->route('order.confirmation', $order->id);
            }

        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            Log::error('Checkout Error Stack: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Checkout failed. Please try again.');
        }
    }

    public function orderConfirmation($orderId, Request $request)
    {
        $order = \App\Models\Order::with('items.product')->where('id', $orderId)->where('user_id', auth()->id())->first();

        if (!$order) {
            return redirect()->route('shop.index')->with('error', 'Order not found');
        }

        // Process ToyyibPay return URL parameters (when user returns after payment)
        $statusId = $request->query('status_id');
        $billCode = $request->query('billcode');
        $transactionId = $request->query('transaction_id');

        Log::info('orderConfirmation called', [
            'order_id' => $orderId,
            'status_id' => $statusId,
            'billcode' => $billCode,
            'transaction_id' => $transactionId
        ]);

        // If payment status is provided and payment is successful (status_id = 1 means successful)
        if ($statusId && $statusId == '1' && $order->payment_status !== 'paid') {
            Log::info('Payment successful via return URL, marking products as unavailable for order ' . $order->id);

            // Update order status to 'processing' (not delivered yet - admin will update)
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);

            if ($transactionId) {
                $order->update(['transaction_id' => $transactionId]);
            }

            // Mark products as unavailable
            $productIds = [];
            foreach ($order->items as $item) {
                $productIds[] = $item->product_id;
            }

            \DB::table('products')
                ->whereIn('id', $productIds)
                ->update(['is_available' => false]);

            Log::info('Products marked as unavailable: ' . implode(', ', $productIds));

            // Reload order with updated products
            $order = \App\Models\Order::with('items.product')->where('id', $orderId)->where('user_id', auth()->id())->first();
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
        // Log every callback regardless of status
        Log::info('ToyyibPay Callback Received: ' . json_encode($request->all()));
        Log::info('Request method: ' . $request->method());
        Log::info('Request headers: ' . json_encode($request->headers->all()));

        try {
            $billCode = $request->input('billcode');
            $paymentStatus = $request->input('billpaymentStatus');

            Log::info('Bill Code: ' . $billCode);
            Log::info('Payment Status: ' . $paymentStatus);

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

            // ToyyibPay webhooks already contain the payment status
            // Status 1, 2, or 3 means paid (according ToyyibPay documentation)
            $isPaid = $paymentStatus == '1' || $paymentStatus == '2' || $paymentStatus == '3';

            if ($isPaid) {
                // Update order status to 'processing' (not delivered yet - admin will update)
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);

                // Mark products as unavailable when payment is confirmed
                $productIds = [];
                foreach ($order->items as $item) {
                    $productIds[] = $item->product_id;
                }

                Log::info('Marking products as unavailable: ' . implode(', ', $productIds));

                // Check current status first
                $beforeUpdate = \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->pluck('is_available', 'id');
                Log::info('Products status before update: ' . $beforeUpdate->toJson());

                // Perform the update
                $updateResult = \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->update(['is_available' => false]);
                Log::info('Update result (rows affected): ' . $updateResult);

                // Verify the update
                $updatedProducts = \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->pluck('is_available', 'id');
                Log::info('Products status after update: ' . $updatedProducts->toJson());

                // Double-check with a fresh query to ensure persistence
                $doubleCheck = \DB::table('products')
                    ->whereIn('id', $productIds)
                    ->select('id', 'is_available')
                    ->get();
                Log::info('Final double-check: ' . $doubleCheck->toJson());

                Log::info('Payment successful for order ' . $order->id);

                return redirect()->route('order.confirmation', $order->id)
                    ->with('success', 'Payment successful! Thank you for your order.');
            } else {
                // Payment failed or was cancelled
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'pending'
                ]);

                Log::warning('Payment failed for order ' . $order->id . ': Payment status ' . $paymentStatus);

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

    public function updateShippingAddress(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', auth()->id())->first();

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Only allow address changes when order is in 'processing' status
        if (!in_array($order->status, ['processing', 'pending', 'confirmed'])) {
            return response()->json([
                'error' => 'Address can only be changed while order is being processed. Contact support for changes.'
            ], 403);
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_street' => 'required|string',
            'shipping_city' => 'required|string',
            'shipping_postcode' => 'required|string',
            'shipping_state' => 'required|string',
        ]);

        // Store address as JSON to handle commas in street address
        $shippingAddressData = [
            'name' => $request->shipping_name,
            'phone' => $request->shipping_phone,
            'street' => $request->shipping_street,
            'city' => $request->shipping_city,
            'postcode' => $request->shipping_postcode,
            'state' => $request->shipping_state,
            'country' => $request->shipping_country ?? 'Malaysia',
        ];
        $shippingAddress = json_encode($shippingAddressData);

        $order->update([
            'shipping_address' => $shippingAddress
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Shipping address updated successfully'
        ]);
    }
}
