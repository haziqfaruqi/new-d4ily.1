<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalized Recommendations - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-zinc-50">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b backdrop-blur-md border-zinc-200 bg-white/90">
        <div class="mx-auto max-w-7xl px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('shop.index') }}" class="text-xl font-bold tracking-tighter text-zinc-900">d4ily.1</a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-zinc-600">
                    <a href="{{ route('shop.index') }}" class="transition-colors hover:text-zinc-900">Shop</a>
                    <a href="{{ route('shop.recommendations') }}"
                        class="transition-colors text-zinc-900 font-semibold flex items-center gap-1">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        For You
                    </a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('shop.index') }}" class="p-1 text-zinc-600 hover:text-zinc-900">
                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                </a>
                <a href="/api/cart" class="relative p-1 text-zinc-600 hover:text-zinc-900">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                </a>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <main class="mx-auto max-w-7xl px-6 py-10">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-3">
                <div class="p-2 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600">
                    <i data-lucide="sparkles" class="w-6 h-6 text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-zinc-900">Curated Just For You</h1>
                    <p class="text-sm text-zinc-600 mt-1">AI-powered recommendations based on your browsing history and
                        preferences</p>
                </div>
            </div>
        </div>

        @if($products->isEmpty())
            <div class="text-center py-16 bg-white rounded-lg border border-zinc-200">
                <div class="p-4 rounded-full bg-indigo-50 w-20 h-20 mx-auto mb-4 flex items-center justify-center">
                    <i data-lucide="sparkles" class="w-10 h-10 text-indigo-600"></i>
                </div>
                <h3 class="text-lg font-semibold text-zinc-900 mb-2">Start Exploring to Get Recommendations</h3>
                <p class="text-sm text-zinc-600 mb-6 max-w-md mx-auto">
                    Browse our collection and interact with items you like. Our AI will learn your preferences and suggest
                    perfect vintage finds for you.
                </p>
                <a href="{{ route('shop.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-900 text-white rounded-md hover:bg-zinc-800 transition-colors">
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    Start Shopping
                </a>
            </div>
        @else
            <!-- Recommendation Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-lg border border-zinc-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-indigo-50">
                            <i data-lucide="target" class="w-5 h-5 text-indigo-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Match Score</p>
                            <p class="text-lg font-bold text-zinc-900">95%</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-zinc-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-purple-50">
                            <i data-lucide="eye" class="w-5 h-5 text-purple-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Items Viewed</p>
                            <p class="text-lg font-bold text-zinc-900">
                                {{ auth()->user()->interactions()->where('type', 'view')->count() }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-zinc-200 p-5">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg bg-emerald-50">
                            <i data-lucide="heart" class="w-5 h-5 text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Recommendations</p>
                            <p class="text-lg font-bold text-zinc-900">{{ $products->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($products as $product)
                    <a href="{{ route('shop.product', $product->id) }}" class="group">
                        <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 mb-3">
                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div
                                class="absolute top-2 left-2 px-2 py-0.5 text-[10px] font-medium rounded backdrop-blur bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
                                <i data-lucide="sparkles" class="w-3 h-3 inline mr-1"></i>
                                For You
                            </div>
                            <div
                                class="absolute bottom-2 right-2 px-2 py-0.5 text-[10px] font-medium rounded backdrop-blur bg-white/90 text-zinc-900">
                                {{ ucfirst($product->condition) }}
                            </div>
                        </div>
                        <h3
                            class="text-sm font-medium text-zinc-900 group-hover:text-indigo-600 transition-colors line-clamp-1">
                            {{ $product->name }}
                        </h3>
                        <p class="text-xs text-zinc-500 mt-0.5">{{ $product->brand }}</p>
                        <p class="text-sm font-semibold text-zinc-900 mt-1">${{ number_format($product->price, 2) }}</p>
                    </a>
                @endforeach
            </div>

            <!-- Refresh Button -->
            <div class="mt-12 text-center">
                <button onclick="window.location.reload()"
                    class="inline-flex items-center gap-2 px-6 py-3 border border-zinc-200 rounded-md hover:bg-zinc-50 transition-colors">
                    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                    Refresh Recommendations
                </button>
            </div>
        @endif
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>

</html>