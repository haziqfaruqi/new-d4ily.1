<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - d4ily.1</title>
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
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-8">
                <h1 class="text-sm font-medium text-zinc-900">Orders Management</h1>
            </header>

            <div class="p-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
                        <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Orders Table -->
                <div class="bg-white rounded-lg border border-zinc-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-5 py-3 text-left font-medium">Order ID</th>
                                    <th class="px-5 py-3 text-left font-medium">Customer</th>
                                    <th class="px-5 py-3 text-left font-medium">Items</th>
                                    <th class="px-5 py-3 text-left font-medium">Total</th>
                                    <th class="px-5 py-3 text-left font-medium">Status</th>
                                    <th class="px-5 py-3 text-left font-medium">Date</th>
                                    <th class="px-5 py-3 text-right font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($orders as $order)
                                                            <tr class="hover:bg-zinc-50">
                                                                <td class="px-5 py-3 font-mono text-zinc-500">#{{ $order->id }}</td>
                                                                <td class="px-5 py-3">
                                                                    <div class="flex items-center gap-2">
                                                                        <div
                                                                            class="h-6 w-6 rounded-full bg-zinc-200 flex items-center justify-center text-[10px] font-bold">
                                                                            {{ substr($order->user->name, 0, 1) }}
                                                                        </div>
                                                                        <span class="font-medium text-zinc-900">{{ $order->user->name }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="px-5 py-3 text-zinc-700">{{ $order->items->count() }} items</td>
                                                                <td class="px-5 py-3 font-medium text-zinc-900">
                                                                    RM{{ number_format($order->total_price, 2) }}</td>
                                                                <td class="px-5 py-3">
                                                                    <form action="{{ route('admin.orders.update-status', $order->id) }}"
                                                                        method="POST" class="inline">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <select name="status" onchange="this.form.submit()"
                                                                            class="px-2 py-1 rounded text-[10px] font-medium border-0
                                                                            {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' :
                                                                            ($order->status === 'pending' ? 'bg-yellow-50 text-yellow-700' :
                                                                            ($order->status === 'cancelled' ? 'bg-red-50 text-red-700' : 'bg-blue-50 text-blue-700')) }}">
                                                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                                                            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                                                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                                        </select>
                                                                    </form>
                                                                </td>
                                                                <td class="px-5 py-3 text-zinc-500">{{ $order->created_at->format('M d, Y H:i') }}
                                                                </td>
                                                                <td class="px-5 py-3 text-right">
                                                                    <button onclick='viewOrder(@json($order))'
                                                                        class="text-zinc-600 hover:text-zinc-900">
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
                    <div class="px-5 py-4 border-t border-zinc-100">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-zinc-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-zinc-900">Order Details</h2>
                <button onclick="closeModal()" class="text-zinc-400 hover:text-zinc-600">
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
            const details = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-zinc-500">Order ID</p>
                            <p class="font-mono text-sm font-medium">#${order.id}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Customer</p>
                            <p class="text-sm font-medium">${order.user.name}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Email</p>
                            <p class="text-sm">${order.user.email}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Total Amount</p>
                            <p class="text-sm font-medium">RM${parseFloat(order.total_price).toFixed(2)}</p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <p class="text-xs text-zinc-500 mb-3">Order Items</p>
                        <div class="space-y-2">
                            ${order.items.map(item => `
                                <div class="flex items-center justify-between p-3 bg-zinc-50 rounded">
                                    <div class="flex items-center gap-3">
                                        <img src="${item.product.images[0] || 'https://via.placeholder.com/40'}" class="w-10 h-10 rounded object-cover">
                                        <div>
                                            <p class="text-sm font-medium">${item.product.name}</p>
                                            <p class="text-xs text-zinc-500">Qty: ${item.quantity}</p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium">RM${parseFloat(item.price).toFixed(2)}</p>
                                </div>
                            `).join('')}
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