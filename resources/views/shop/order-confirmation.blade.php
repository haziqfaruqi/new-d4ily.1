<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
@include('partials.navigation')

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        @if($order->payment_method === 'toyyibpay')
        <!-- Success Banner -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg p-6 mb-8">
            <div class="flex items-center justify-center gap-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full">
                    <i data-lucide="check-circle" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2">Payment Successful!</h1>
                    <p class="text-purple-100">Thank you for your purchase. Payment processed via ToyyibPay.</p>
                </div>
            </div>
        </div>
        @else
        <!-- Order Confirmation Banner -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-6 mb-8">
            <div class="flex items-center justify-center gap-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2">Order Confirmed!</h1>
                    <p class="text-green-100">Thank you for your purchase. Please complete bank transfer payment.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-zinc-900 mb-2">Order Confirmed</h2>
            <p class="text-lg text-zinc-600">Your order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} has been placed successfully.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
            <!-- Order Details -->
            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                    Order Details
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Order Number</span>
                        <span class="font-medium text-zinc-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Order Date</span>
                        <span class="font-medium text-zinc-900">{{ $order->created_at->format('F j, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Status</span>
                        @if($order->status === 'completed' && $order->payment_status === 'paid')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Processing</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Payment Method</span>
                        <span class="font-medium text-zinc-900">
                            @if($order->payment_method === 'toyyibpay')
                                ToyyibPay
                            @else
                                Bank Transfer
                            @endif
                        </span>
                    </div>

                    @if($order->payment_method === 'toyyibpay')
                        @if($order->bill_code)
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-sm text-zinc-600">Payment Reference</span>
                            <span class="font-mono text-sm text-purple-600">{{ $order->bill_code }}</span>
                        </div>
                        @endif

                        @if($order->transaction_id)
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-sm text-zinc-600">Transaction ID</span>
                            <span class="font-mono text-sm text-zinc-900">{{ $order->transaction_id }}</span>
                        </div>
                        @endif
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mt-4">
                            <p class="text-sm text-yellow-800">
                                <i data-lucide="info" class="w-4 h-4 inline mr-2"></i>
                                Please complete your payment via bank transfer. The order will be processed once payment is confirmed.
                            </p>
                        </div>
                    @endif

                    <div class="pb-4 border-b">
                        <span class="text-sm text-zinc-600 block mb-2">Shipping Address</span>
                        <span class="font-medium text-zinc-900">{{ $order->shipping_address }}</span>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-5 h-5"></i>
                    Order Summary
                </h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Subtotal</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Shipping</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Tax</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-zinc-900">Total Paid</span>
                            <span class="font-bold text-lg text-zinc-900">RM{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-4">Order Items ({{ $order->items->count() }})</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3 p-3 bg-zinc-50 rounded-md">
                                <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/60' }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-12 h-16 object-cover rounded border border-zinc-200">
                                <div class="flex-1">
                                    <h4 class="font-medium text-zinc-900 text-sm">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-zinc-500">{{ $item->product->brand }}</p>
                                    <p class="text-xs text-zinc-500">{{ $item->quantity }} × RM{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- @if($order->payment_method === 'toyyibpay' && $order->payment_status !== 'paid')
            <!-- ToyyibPay Manual Return Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-medium text-blue-900 mb-1">Payment Instructions</h3>
                        <p class="text-sm text-blue-800">
                            If you're not automatically redirected back after completing your payment, please click the button below to return to your order confirmation.
                        </p>
                        <a href="{{ route('order.confirmation', $order->id) }}"
                           class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Return to Order Confirmation
                        </a>
                    </div>
                </div>
            </div>
        @endif --}}

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-12 justify-center">
            <a href="{{ route('shop.index') }}"
               class="flex items-center justify-center gap-2 px-6 py-3 bg-zinc-900 text-white rounded-md font-medium hover:bg-zinc-800 transition-colors">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                Continue Shopping
            </a>
            <a href="{{ route('order.history') }}"
               class="flex items-center justify-center gap-2 px-6 py-3 border border-zinc-300 text-zinc-700 rounded-md font-medium hover:bg-zinc-50 transition-colors">
                <i data-lucide="clock" class="w-4 h-4"></i>
                View Order History
            </a>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // Update cart count on page load
        async function updateCartCount() {
            try {
                const response = await fetch('/api/cart');
                if (response.ok) {
                    const cart = await response.json();
                    const totalItems = cart.items.reduce((total, item) => total + item.quantity, 0);
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = totalItems;
                        cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
                    }
                }
            } catch (error) {
                console.error('Error updating cart count:', error);
            }
        }

        // Update cart count on page load
        updateCartCount();
    </script>
</body>
</html>