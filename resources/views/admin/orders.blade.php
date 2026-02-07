<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - d4ily.1</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-zinc-50">
    <div class="flex h-screen">
        @include('admin.partials.sidebar', ['active' => 'orders'])

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <header class="relative overflow-hidden px-8 py-8" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="max-w-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="px-3 py-1 bg-white/30 backdrop-blur rounded-full">
                                    <span class="text-xs font-bold text-white">ORDERS HUB</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-xs text-white/80">Live Updates</span>
                                </div>
                            </div>
                            <h1 class="text-3xl font-bold text-white mb-2">Order Command Center</h1>
                            <p class="text-sm text-white/80">Monitor fulfillment, track shipments, and delight customers</p>
                        </div>
                        <div class="hidden lg:flex items-center gap-4">
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">{{ $stats['total_orders'] ?? 0 }}</p>
                                <p class="text-xs text-white/70">Total</p>
                            </div>
                            <div class="w-px h-12 bg-white/30"></div>
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">{{ $stats['pending_orders'] ?? 0 }}</p>
                                <p class="text-xs text-white/70">Pending</p>
                            </div>
                            <div class="w-px h-12 bg-white/30"></div>
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">RM{{ number_format($stats['total_revenue'] ?? 0, 0) }}</p>
                                <p class="text-xs text-white/70">Revenue</p>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Total Orders</p>
                                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $stats['total_orders'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Pending</p>
                                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending_orders'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Delivered</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['delivered_orders'] ?? 0 }}</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl border border-zinc-200 p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Total Revenue</p>
                                <p class="text-2xl font-bold text-stone-900 mt-1">RM{{ number_format($stats['total_revenue'] ?? 0, 2) }}</p>
                            </div>
                            <div class="w-12 h-12 bg-[#c53131]/10 rounded-xl flex items-center justify-center">
                                <i data-lucide="dollar-sign" class="w-6 h-6" style="color: #c53131;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders Table -->
                <div class="bg-white rounded-xl border border-zinc-200 shadow-sm">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-zinc-50 to-[#fafaf9]">
                                <tr>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Order ID</th>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Customer</th>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Items</th>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Total</th>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Status</th>
                                    <th class="px-5 py-4 text-left font-semibold text-zinc-700">Date</th>
                                    <th class="px-5 py-4 text-right font-semibold text-zinc-700">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($orders as $order)
                                                            <tr class="hover:bg-[#fafaf9] transition-colors">
                                                                <td class="px-5 py-4">
                                                                    <span class="font-mono text-sm font-semibold" style="color: #c53131;">#{{ $order->id }}</span>
                                                                </td>
                                                                <td class="px-5 py-4">
                                                                    <div class="flex items-center gap-3">
                                                                        <div class="h-10 w-10 rounded-full flex items-center justify-center text-sm font-bold text-white"
                                                                            style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                                                            {{ substr($order->user->name, 0, 1) }}
                                                                        </div>
                                                                        <div>
                                                                            <p class="font-semibold text-zinc-900">{{ $order->user->name }}</p>
                                                                            <p class="text-xs text-zinc-500">{{ $order->user->email }}</p>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-5 py-4">
                                                                    <div class="flex items-center gap-1">
                                                                        <i data-lucide="package" class="w-4 h-4 text-zinc-400"></i>
                                                                        <span class="text-zinc-700">{{ $order->items->count() }} items</span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-5 py-4">
                                                                    <span class="text-lg font-bold text-zinc-900">RM{{ number_format($order->total_price, 2) }}</span>
                                                                </td>
                                                                <td class="px-5 py-4">
                                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}"
                                                                        method="POST" class="inline">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <select name="status" onchange="this.form.submit()"
                                                                            class="px-3 py-2 rounded-lg text-xs font-semibold border cursor-pointer hover:shadow-md transition-shadow @if($order->status === 'delivered') bg-emerald-100 text-emerald-700 border-emerald-200 @elseif($order->status === 'pending') bg-yellow-100 text-yellow-700 border-yellow-200 @elseif($order->status === 'processing') bg-blue-100 text-blue-700 border-blue-200 @elseif($order->status === 'shipped') bg-purple-100 text-purple-700 border-purple-200 @else bg-red-100 text-red-700 border-red-200 @endif">
                                                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>✓ Delivered</option>
                                                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>✕ Cancelled</option>
                                                                        </select>
                                                                    </form>
                                                                </td>
                                                                <td class="px-5 py-4 text-sm text-zinc-500">{{ $order->created_at->format('M d, Y') }}</td>
                                                                <td class="px-5 py-4 text-right">
                                                                    <button onclick='viewOrder(@json($order))'
                                                                        class="p-2 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 rounded-lg transition-colors">
                                                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                                                    </button>
                                                                </td>
                                                            </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-5 py-8 text-center text-zinc-500">No orders found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 border-t border-zinc-100 bg-zinc-50 rounded-b-xl">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl">
            <div class="p-6 border-b border-zinc-200 flex items-center justify-between" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
                <h2 class="text-lg font-semibold text-white">Order Details</h2>
                <button onclick="closeModal()" class="text-white/80 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="orderDetails" class="p-6">
                <!-- Order details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function viewOrder(order) {
            const statusColors = {
                'pending': 'bg-yellow-100 text-yellow-700',
                'processing': 'bg-blue-100 text-blue-700',
                'shipped': 'bg-purple-100 text-purple-700',
                'delivered': 'bg-emerald-100 text-emerald-700',
                'cancelled': 'bg-red-100 text-red-700'
            };

            const statusLabel = order.status.charAt(0).toUpperCase() + order.status.slice(1);

            const itemsHtml = order.items.map(item => {
                const imageSrc = item.product.images && item.product.images.length > 0 ? item.product.images[0] : 'https://via.placeholder.com/64';
                return `
                    <div class="flex items-center justify-between p-4 bg-zinc-50 rounded-xl hover:bg-zinc-100 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0 border border-zinc-200">
                                <img src="${imageSrc}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-zinc-900">${item.product.name}</p>
                                <p class="text-xs text-zinc-500">Quantity: ${item.quantity}</p>
                            </div>
                        </div>
                        <p class="text-lg font-bold text-zinc-900">RM${parseFloat(item.price).toFixed(2)}</p>
                    </div>
                `;
            }).join('');

            const details = `
                <div class="space-y-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-zinc-50 rounded-xl">
                            <p class="text-xs text-zinc-500 mb-1">Order ID</p>
                            <p class="font-mono text-lg font-bold" style="color: #c53131;">#${order.id}</p>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-xl">
                            <p class="text-xs text-zinc-500 mb-1">Status</p>
                            <span class="px-3 py-1 rounded-lg text-xs font-semibold ${statusColors[order.status] || statusColors['pending']}">
                                ${statusLabel}
                            </span>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-xl">
                            <p class="text-xs text-zinc-500 mb-1">Customer</p>
                            <p class="text-sm font-semibold text-zinc-900">${order.user.name}</p>
                        </div>
                        <div class="p-4 bg-zinc-50 rounded-xl">
                            <p class="text-xs text-zinc-500 mb-1">Email</p>
                            <p class="text-sm text-zinc-700">${order.user.email}</p>
                        </div>
                    </div>

                    <div class="p-4 bg-gradient-to-r from-[#a6af89]/10 to-[#d5fdff]/10 rounded-xl">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-zinc-500 mb-1">Total Amount</p>
                                <p class="text-2xl font-bold text-zinc-900">RM${parseFloat(order.total_price).toFixed(2)}</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                                <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-zinc-900 mb-3">Order Items</h3>
                        <div class="space-y-3">
                            ${itemsHtml}
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('orderDetails').innerHTML = details;
            document.getElementById('orderModal').classList.remove('hidden');
            lucide.createIcons();
        }

        function closeModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }
    </script>
</body>

</html>