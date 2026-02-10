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
        .order-card {
            transition: all 0.2s ease-in-out;
        }
        .order-card:hover {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .status-badge {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.8; }
        }
        /* Custom scrollbar styles */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        /* Force horizontal pagination */
        .pagination-container {
            display: flex !important;
            flex-wrap: nowrap !important;
            overflow-x: auto !important;
            overflow-y: hidden !important;
            width: 100% !important;
        }
        .pagination-container ul {
            display: flex !important;
            flex-wrap: nowrap !important;
            gap: 0.25rem !important;
            padding: 0.25rem 0.5rem !important;
            min-width: min-content !important;
        }
        .pagination-container li {
            flex-shrink: 0 !important;
        }
    </style>
</head>
@include('partials.navigation')

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-white">
        <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 max-w-7xl">
            <!-- Header Section -->
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-zinc-900 mb-4">Order History</h1>
                <p class="text-lg text-zinc-600">Track and manage your purchases</p>
                <div class="w-20 h-1 bg-gradient-to-r from-purple-600 to-purple-700 mx-auto mt-6 rounded-full"></div>
            </div>

            <!-- Stats Cards -->
            @if($orders->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-zinc-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="shopping-bag" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Total Orders</p>
                                <p class="text-2xl font-bold text-zinc-900">{{ $orders->total() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-zinc-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Completed</p>
                                <p class="text-2xl font-bold text-zinc-900">{{ $orders->where('status', 'delivered')->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl p-6 shadow-sm border border-zinc-100">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Processing</p>
                                <p class="text-2xl font-bold text-zinc-900">{{ $orders->whereIn('status', ['pending', 'processing'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Orders Section -->
            @if($orders->count() > 0)
                <div class="space-y-6">
                    @foreach($orders as $order)
                        <div class="order-card bg-white rounded-2xl shadow-lg border border-zinc-100 overflow-hidden">
                            <!-- Order Header -->
                            <div class="p-8">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center">
                                            <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-zinc-900">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                            <div class="flex items-center gap-3 mt-2">
                                                <?php
                                                    $statusClass = match($order->status) {
                                                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                                        'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                                                        'delivered' => 'bg-green-100 text-green-800 border-green-200',
                                                        'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                                        default => 'bg-gray-100 text-gray-800 border-gray-200'
                                                    };
                                                ?>
                                                <span class="status-badge px-4 py-2 text-xs font-semibold rounded-full border-2 {{ $statusClass }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                                <span class="text-sm text-zinc-500">•</span>
                                                <span class="text-sm text-zinc-600">{{ $order->created_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-zinc-900">RM{{ number_format($order->total_price, 2) }}</p>
                                        <p class="text-sm text-zinc-500">{{ $order->items->count() }} item{{ $order->items->count() !== 1 ? 's' : '' }}</p>
                                    </div>
                                </div>

                                <!-- Order Items Grid -->
                                <div class="mb-6">
                                    <h4 class="text-sm font-semibold text-zinc-700 mb-4">Order Items</h4>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                                        @foreach($order->items as $index => $item)
                                            <div class="flex items-center gap-3 bg-zinc-50 rounded-lg p-3">
                                                <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/60' }}"
                                                     alt="{{ $item->product->name }}"
                                                     class="w-12 h-16 object-cover rounded-lg border border-zinc-200">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-zinc-900 truncate">{{ $item->product->name }}</p>
                                                    <p class="text-xs text-zinc-500">× {{ $item->quantity }}</p>
                                                    <p class="text-xs font-medium text-zinc-700">RM{{ number_format($item->price, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Order Actions & Info -->
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 pt-6 border-t border-zinc-100">
                                    <div class="flex items-center gap-4 flex-wrap">
                                        <a href="{{ route('order.confirmation', $order->id) }}"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white text-sm font-medium rounded-lg hover:from-indigo-700 hover:to-indigo-800 transition-all shadow-sm">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                            View Details
                                        </a>
                                        <button class="inline-flex items-center gap-2 px-4 py-2 bg-zinc-50 text-zinc-600 text-sm font-medium rounded-lg hover:bg-zinc-100 transition-colors border border-zinc-200">
                                            <i data-lucide="download" class="w-4 h-4"></i>
                                            Invoice
                                        </button>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <i data-lucide="credit-card" class="w-4 h-4 text-zinc-400"></i>
                                            <span class="text-zinc-600">Payment:</span>
                                            <?php
                                                $paymentMethod = match($order->payment_method) {
                                                    'credit_card' => 'Credit Card',
                                                    'paypal' => 'PayPal',
                                                    'bank_transfer' => 'Bank Transfer',
                                                    'toyyibpay' => 'ToyyibPay',
                                                    default => $order->payment_method
                                                };
                                                echo e($paymentMethod);
                                            ?>
                                        </div>
                                        @if($order->payment_method === 'toyyibpay' && $order->bill_code)
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="tag" class="w-4 h-4 text-zinc-400"></i>
                                                <span class="text-purple-600 text-xs font-mono">{{ $order->bill_code }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Enhanced Pagination -->
                <div class="mt-12 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    {{-- <div class="text-sm text-zinc-600">
                        Showing {{ $orders->firstItem() }} - {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </div> --}}
                    <div class="flex items-center gap-2 overflow-x-auto">
                        {{ $orders->appends(request()->query())->links('pagination::tailwind') }}
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-24">
                    <div class="w-24 h-24 mx-auto mb-8 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-12 h-12 text-purple-600"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-zinc-900 mb-4">No orders yet</h2>
                    <p class="text-lg text-zinc-600 mb-8 max-w-md mx-auto">You haven't placed any orders yet. Start shopping and discover amazing vintage items!</p>
                    <a href="{{ route('shop.index') }}"
                       class="inline-flex items-center gap-2 px-8 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-medium rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg">
                        <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                        Start Shopping
                    </a>
                </div>
            @endif
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

        updateCartCount();

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>