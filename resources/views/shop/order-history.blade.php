<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
@include('partials.navigation')

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-900">Order History</h1>
            <p class="text-base text-zinc-600 mt-2">View and track your past orders</p>
        </div>

        @if($orders->count() > 0)
            <div class="bg-white rounded-lg border border-zinc-200 divide-y divide-zinc-100">
                @foreach($orders as $order)
                    <div class="p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-zinc-900">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                    <span class="px-3 py-1 text-xs font-medium rounded-full
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           $order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                           $order->status === 'shipped' ? 'bg-green-100 text-green-800' :
                                           $order->status === 'delivered' ? 'bg-emerald-100 text-emerald-800' :
                                           $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                                    }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-zinc-600">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-lg font-semibold text-zinc-900">RM{{ number_format($order->total_price, 2) }}</p>
                                <p class="text-sm text-zinc-600">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</p>
                            </div>
                        </div>

                        <!-- Order Items Preview -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                            @foreach($order->items->take(4) as $index => $item)
                                <div class="relative">
                                    <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/80' }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-20 object-cover rounded border border-zinc-200">
                                    @if($index === 3 && $order->items->count() > 4)
                                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded flex items-center justify-center text-white text-sm font-medium">
                                            +{{ $order->items->count() - 4 }} more
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Order Actions -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <a href="{{ route('order.confirmation', $order->id) }}"
                                   class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                                    View Details
                                </a>
                                @if($order->status === 'pending')
                                    <button class="text-sm font-medium text-red-600 hover:text-red-700">
                                        Cancel Order
                                    </button>
                                @endif
                            </div>
                            <div class="text-sm text-zinc-500">
                                {{ $order->payment_method === 'credit_card' ? 'Credit Card' :
                                   $order->payment_method === 'paypal' ? 'PayPal' :
                                   $order->payment_method === 'bank_transfer' ? 'Bank Transfer' : $order->payment_method }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @else
            <div class="text-center py-16">
                <i data-lucide="package-x" class="w-16 h-16 mx-auto text-zinc-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-900 mb-2">No orders found</h3>
                <p class="text-sm text-zinc-600 mb-6">You haven't placed any orders yet</p>
                <a href="{{ route('shop.index') }}"
                   class="bg-zinc-900 text-white py-2 px-6 rounded-md font-medium hover:bg-zinc-800 transition-colors">
                    Start Shopping
                </a>
            </div>
        @endif
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