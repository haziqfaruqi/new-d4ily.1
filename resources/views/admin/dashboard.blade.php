<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - d4ily.1</title>
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
        @include('admin.partials.sidebar', ['active' => 'dashboard'])

        <main class="flex-1 overflow-auto">
            <header class="relative overflow-hidden px-8 py-8" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <div class="px-3 py-1 bg-white/30 backdrop-blur rounded-full">
                                    <span class="text-xs font-bold text-white">COMMAND CENTER</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-xs text-white/80">Live Dashboard</span>
                                </div>
                            </div>
                            <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                            <p class="text-sm text-white/80">Here's what's happening with your thrift shop today</p>
                        </div>
                        <a href="{{ route('admin.inventory') }}"
                            class="hidden lg:flex px-6 py-3 text-sm font-bold bg-white text-stone-800 rounded-xl hover:bg-white/90 shadow-lg flex items-center gap-2 transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add Product
                        </a>
                    </div>
                </div>
            </header>

            <div class="p-8">
                <!-- Statistics Cards with Brand Colors -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="relative overflow-hidden rounded-2xl shadow-xl p-6 hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-white/90">Total Revenue</span>
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur">
                                    <i data-lucide="dollar-sign" class="w-6 h-6 text-white"></i>
                                </div>
                            </div>
                            <p class="text-4xl font-extrabold text-white">RM{{ number_format($stats['total_revenue'], 2) }}</p>
                            <p class="text-xs text-white/80 mt-2 flex items-center gap-1">
                                <i data-lucide="trending-up" class="w-3 h-3"></i>
                                Lifetime earnings
                            </p>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl shadow-xl p-6 hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #a6af89 0%, #8a966d 100%);">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-white/90">Active Orders</span>
                                <div class="p-3 bg-white/20 rounded-xl backdrop-blur">
                                    <i data-lucide="shopping-bag" class="w-6 h-6 text-white"></i>
                                </div>
                            </div>
                            <p class="text-4xl font-extrabold text-white">{{ $stats['active_orders'] }}</p>
                            <p class="text-xs text-white/80 mt-2 flex items-center gap-1">
                                <i data-lucide="clock" class="w-3 h-3"></i>
                                Pending & processing
                            </p>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl shadow-xl p-6 hover:scale-105 transition-transform" style="background: linear-gradient(135deg, #d5fdff 0%, #b8e5f5 100%);">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-stone-700/90">Total Products</span>
                                <div class="p-3 bg-white/40 rounded-xl backdrop-blur">
                                    <i data-lucide="package" class="w-6 h-6" style="color: #c53131;"></i>
                                </div>
                            </div>
                            <p class="text-4xl font-extrabold" style="color: #292524;">{{ $stats['total_products'] }}</p>
                            <p class="text-xs text-stone-600/80 mt-2 flex items-center gap-1">
                                <i data-lucide="box" class="w-3 h-3"></i>
                                In inventory
                            </p>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-2xl shadow-xl p-6 hover:scale-105 transition-transform bg-gradient-to-br from-stone-800 to-stone-900">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-sm font-semibold text-stone-300">Total Customers</span>
                                <div class="p-3 bg-white/10 rounded-xl backdrop-blur">
                                    <i data-lucide="users" class="w-6 h-6 text-stone-200"></i>
                                </div>
                            </div>
                            <p class="text-4xl font-extrabold text-white">{{ $stats['total_customers'] }}</p>
                            <p class="text-xs text-stone-400 mt-2 flex items-center gap-1">
                                <i data-lucide="user-plus" class="w-3 h-3"></i>
                                Registered users
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white rounded-2xl border border-zinc-200 shadow-lg overflow-hidden">
                    <div class="px-6 py-5 border-b border-zinc-200 flex items-center justify-between" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-white/30 rounded-lg backdrop-blur">
                                <i data-lucide="shopping-bag" class="w-5 h-5 text-stone-800"></i>
                            </div>
                            <h3 class="text-lg font-bold text-stone-800">Recent Orders</h3>
                        </div>
                        <a href="{{ route('admin.orders') }}" class="px-4 py-2 text-sm font-bold bg-white text-stone-800 rounded-lg hover:bg-white/90 shadow-md flex items-center gap-1 transition-all">
                            View All
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gradient-to-r from-zinc-50 to-[#fafaf9]">
                                <tr>
                                    <th class="px-6 py-4 text-left font-bold text-zinc-700">Order ID</th>
                                    <th class="px-6 py-4 text-left font-bold text-zinc-700">Customer</th>
                                    <th class="px-6 py-4 text-left font-bold text-zinc-700">Status</th>
                                    <th class="px-6 py-4 text-right font-bold text-zinc-700">Total</th>
                                    <th class="px-6 py-4 text-right font-bold text-zinc-700">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($stats['recent_orders'] as $order)
                                    <tr class="hover:bg-gradient-to-r hover:from-[#fafaf9] hover:to-transparent transition-all">
                                        <td class="px-6 py-4">
                                            <span class="font-mono font-bold" style="color: #c53131;">#{{ $order->id }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                                    style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                                    {{ substr($order->user->name, 0, 1) }}
                                                </div>
                                                <span class="font-semibold text-zinc-900">{{ $order->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($order->status === 'delivered')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                    ✓ Delivered
                                                </span>
                                            @elseif($order->status === 'pending')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700 border border-yellow-200">
                                                    ⏳ Pending
                                                </span>
                                            @elseif($order->status === 'processing')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                    ⚙️ Processing
                                                </span>
                                            @elseif($order->status === 'shipped')
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200">
                                                    🚚 Shipped
                                                </span>
                                            @else
                                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    ✕ {{ ucfirst($order->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-bold text-zinc-900">RM{{ number_format($order->total_price, 2) }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right text-zinc-500 text-xs">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="w-16 h-16 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                                    <i data-lucide="shopping-bag" class="w-8 h-8 text-white"></i>
                                                </div>
                                                <p class="text-zinc-500 font-medium">No recent orders</p>
                                                <p class="text-xs text-zinc-400">Orders will appear here once customers start shopping</p>
                                            </div>
                                        </td>
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