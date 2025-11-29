<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - d4ily.1</title>
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
        @include('admin.partials.sidebar', ['active' => 'dashboard'])

        <main class="flex-1 overflow-auto">
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-8">
                <h1 class="text-base font-medium text-zinc-900">Dashboard Overview</h1>
                <a href="{{ route('admin.inventory') }}"
                    class="px-4 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Product
                </a>
            </header>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-lg border border-zinc-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-zinc-500">Total Revenue</span>
                            <i data-lucide="dollar-sign" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <p class="text-2xl font-semibold text-zinc-900">${{ number_format($stats['total_revenue'], 2) }}
                        </p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border border-zinc-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-zinc-500">Active Orders</span>
                            <i data-lucide="shopping-bag" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <p class="text-2xl font-semibold text-zinc-900">{{ $stats['active_orders'] }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border border-zinc-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-zinc-500">Total Products</span>
                            <i data-lucide="package" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <p class="text-2xl font-semibold text-zinc-900">{{ $stats['total_products'] }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-lg border border-zinc-200">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm font-medium text-zinc-500">Total Customers</span>
                            <i data-lucide="users" class="w-5 h-5 text-zinc-300"></i>
                        </div>
                        <p class="text-2xl font-semibold text-zinc-900">{{ $stats['total_customers'] }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-zinc-200">
                    <div class="px-5 py-4 border-b border-zinc-100 flex items-center justify-between">
                        <h3 class="text-base font-medium text-zinc-900">Recent Orders</h3>
                        <a href="{{ route('admin.orders') }}" class="text-sm text-zinc-500 hover:text-zinc-900">View
                            All</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-5 py-3 text-left font-medium">Order ID</th>
                                    <th class="px-5 py-3 text-left font-medium">Customer</th>
                                    <th class="px-5 py-3 text-left font-medium">Status</th>
                                    <th class="px-5 py-3 text-right font-medium">Total</th>
                                    <th class="px-5 py-3 text-right font-medium">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($stats['recent_orders'] as $order)
                                    <tr class="hover:bg-zinc-50">
                                        <td class="px-5 py-3 font-mono text-zinc-500">#{{ $order->id }}</td>
                                        <td class="px-5 py-3 font-medium text-zinc-900">{{ $order->user->name }}</td>
                                        <td class="px-5 py-3">
                                            <span
                                                class="inline-flex px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-emerald-50 text-emerald-700' : ($order->status === 'pending' ? 'bg-yellow-50 text-yellow-700' : 'bg-zinc-100 text-zinc-700') }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-right font-medium text-zinc-900">
                                            ${{ number_format($order->total_price, 2) }}</td>
                                        <td class="px-5 py-3 text-right text-zinc-500">
                                            {{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-zinc-500">No recent orders</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>