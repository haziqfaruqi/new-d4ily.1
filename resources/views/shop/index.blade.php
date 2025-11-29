<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D4ily.1 - Vintage Thrift Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50">
    <nav class="sticky top-0 z-50 border-b backdrop-blur-md border-zinc-200 bg-white/90">
        <div class="mx-auto max-w-[1600px] px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('shop.index') }}" class="text-2xl font-bold tracking-tighter text-zinc-900">d4ily.1</a>
                <div class="hidden md:flex items-center gap-6 text-base font-medium text-zinc-600">
                    <a href="{{ route('shop.index') }}" class="transition-colors hover:text-zinc-900">Shop</a>
                    @auth
                        <a href="{{ route('shop.recommendations') }}" class="transition-colors hover:text-zinc-900 flex items-center gap-1.5">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                            For You
                        </a>
                    @endauth
                </div>
            </div>
            <div class="flex items-center gap-4">
                <form action="{{ route('shop.index') }}" method="GET" class="relative hidden sm:block">
                    <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vintage items..."
                        class="h-10 w-80 rounded-md border pl-10 pr-4 text-sm focus:border-zinc-300 focus:bg-white focus:outline-none focus:ring-0 transition-all placeholder:text-zinc-400 border-zinc-200 bg-zinc-50">
                </form>
                @auth
                    <div class="relative group">
                        <button class="flex items-center gap-2 px-3 py-2 rounded-md hover:bg-zinc-100 transition-colors">
                            <div class="h-8 w-8 rounded-full bg-zinc-900 flex items-center justify-center text-sm font-bold text-white">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <span class="text-sm font-medium text-zinc-900 hidden lg:block">{{ auth()->user()->name }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-zinc-600"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-zinc-200 py-1 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                                    Admin Dashboard
                                </a>
                            @endif
                            <a href="{{ route('shop.recommendations') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
                                <i data-lucide="sparkles" class="w-4 h-4"></i>
                                Recommendations
                            </a>
                            <hr class="my-1 border-zinc-200">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i data-lucide="log-out" class="w-4 h-4"></i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-zinc-600 hover:text-zinc-900">Login</a>
                    <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800">Sign up</a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="mx-auto max-w-[1600px] px-8 py-8">
        <div class="flex gap-8">
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24 space-y-6">
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 mb-3">Categories</h3>
                        <div class="space-y-2">
                            <a href="{{ route('shop.index') }}" class="block text-sm {{ !request('category') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">All Items</a>
                            @foreach($categories as $category)
                                <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="block text-sm {{ request('category') == $category->id ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">{{ $category->name }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 mb-3">Condition</h3>
                        <div class="space-y-2">
                            @foreach(['like new', 'good', 'fair'] as $cond)
                                <label class="flex items-center gap-2 text-sm text-zinc-600 cursor-pointer hover:text-zinc-900">
                                    <input type="checkbox" class="rounded border-zinc-300" onchange="window.location.href='{{ route('shop.index', array_merge(request()->except('condition'), ['condition' => $cond])) }}'" {{ request('condition') == $cond ? 'checked' : '' }}>
                                    <span class="capitalize">{{ $cond }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-zinc-900 mb-3">Price Range</h3>
                        <form action="{{ route('shop.index') }}" method="GET" class="space-y-2">
                            @foreach(request()->except(['min_price', 'max_price']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div class="flex gap-2">
                                <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full px-3 py-2 text-sm border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300">
                                <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full px-3 py-2 text-sm border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300">
                            </div>
                            <button type="submit" class="w-full px-3 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800">Apply</button>
                        </form>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-zinc-900">
                            @if(request('search'))
                                Search results for "{{ request('search') }}"
                            @elseif(request('category'))
                                {{ $categories->find(request('category'))->name ?? 'Products' }}
                            @else
                                All Vintage Items
                            @endif
                        </h1>
                        <p class="text-base text-zinc-600 mt-1">{{ $products->total() }} items found</p>
                    </div>
                    <select onchange="window.location.href=this.value" class="px-4 py-2 text-sm border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300">
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    </select>
                </div>

                @if($products->isEmpty())
                    <div class="text-center py-16">
                        <i data-lucide="package-x" class="w-16 h-16 mx-auto text-zinc-300 mb-4"></i>
                        <h3 class="text-lg font-semibold text-zinc-900 mb-2">No items found</h3>
                        <p class="text-sm text-zinc-600">Try adjusting your filters or search terms</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                        @foreach($products as $product)
                            <a href="{{ route('shop.product', $product->id) }}" class="group">
                                <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 mb-3">
                                    <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @if($product->featured)
                                        <div class="absolute top-2 left-2 px-2 py-1 text-xs font-medium rounded bg-indigo-600 text-white">Featured</div>
                                    @endif
                                    <div class="absolute bottom-2 right-2 px-2 py-1 text-xs font-medium rounded backdrop-blur bg-white/90 text-zinc-900">{{ ucfirst($product->condition) }}</div>
                                </div>
                                <h3 class="text-sm font-medium text-zinc-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-sm text-zinc-500 mt-0.5">{{ $product->brand }}</p>
                                <p class="text-base font-semibold text-zinc-900 mt-1">${{ number_format($product->price, 2) }}</p>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </main>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
