<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D4ily.1 - Thrift Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>

<body class="bg-zinc-50">
@include('partials.navigation')

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex gap-6 sm:gap-8">
            <aside class="hidden lg:block w-64 flex-shrink-0">
                <div class="sticky top-24">
                    <!-- Vibrant Sidebar -->
                    <div class="rounded-2xl shadow-lg overflow-hidden" style="background: linear-gradient(180deg, #d5fdff 0%, #a6af89 100%);">
                        <div class="p-5 border-b border-white/20">
                            <div class="flex items-center gap-2 mb-1">
                                <i data-lucide="sliders-horizontal" class="w-5 h-5 text-stone-800"></i>
                                <h3 class="text-base font-bold text-stone-800">Filters</h3>
                            </div>
                            <p class="text-xs text-stone-600">Find your perfect item</p>
                        </div>

                        <div class="p-5 space-y-6">
                            <!-- Categories -->
                            <div>
                                <h4 class="text-sm font-semibold text-stone-800 mb-3 flex items-center gap-2">
                                    <i data-lucide="layout-grid" class="w-4 h-4"></i>
                                    Categories
                                </h4>
                                <div class="space-y-1.5">
                                    <a href="{{ route('shop.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ !request('category') ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(!request('category'))
                                            <div class="flex items-center gap-2">
                                                <span>All Items</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>All Items</span>
                                        @endif
                                    </a>
                                    @foreach($categories as $category)
                                        <a href="{{ route('shop.index', ['category' => $category->id]) }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ request('category') == $category->id ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                            @if(request('category') == $category->id)
                                                <div class="flex items-center gap-2">
                                                    <span>{{ $category->name }}</span>
                                                    <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                                </div>
                                            @else
                                                <span>{{ $category->name }}</span>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Condition -->
                            <div>
                                <h4 class="text-sm font-semibold text-stone-800 mb-3 flex items-center gap-2">
                                    <i data-lucide="sparkles" class="w-4 h-4"></i>
                                    Condition
                                </h4>
                                <div class="space-y-1.5">
                                    <a href="{{ route('shop.index') }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ !request('condition') ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(!request('condition'))
                                            <div class="flex items-center gap-2">
                                                <span>All</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>All</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('shop.index', ['condition' => 'new']) }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ request('condition') == 'new' ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(request('condition') == 'new')
                                            <div class="flex items-center gap-2">
                                                <span>New</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>New</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('shop.index', ['condition' => 'like new']) }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ request('condition') == 'like new' ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(request('condition') == 'like new')
                                            <div class="flex items-center gap-2">
                                                <span>Like New</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>Like New</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('shop.index', ['condition' => 'good']) }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ request('condition') == 'good' ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(request('condition') == 'good')
                                            <div class="flex items-center gap-2">
                                                <span>Good</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>Good</span>
                                        @endif
                                    </a>
                                    <a href="{{ route('shop.index', ['condition' => 'fair']) }}" class="block px-3 py-2 text-sm rounded-lg transition-all {{ request('condition') == 'fair' ? 'bg-white shadow-md text-stone-800 font-semibold' : 'text-stone-700 hover:bg-white/40 hover:shadow-sm' }}">
                                        @if(request('condition') == 'fair')
                                            <div class="flex items-center gap-2">
                                                <span>Fair</span>
                                                <div class="ml-auto w-1.5 h-1.5 rounded-full" style="background: #c53131;"></div>
                                            </div>
                                        @else
                                            <span>Fair</span>
                                        @endif
                                    </a>
                                </div>
                            </div>

                            <!-- Price Range -->
                            <div>
                                <h4 class="text-sm font-semibold text-stone-800 mb-3 flex items-center gap-2">
                                    <i data-lucide="banknote" class="w-4 h-4"></i>
                                    Price Range
                                </h4>
                                <form action="{{ route('shop.index') }}" method="GET" class="space-y-2">
                                    @foreach(request()->except(['min_price', 'max_price']) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <div class="flex gap-2">
                                        <input type="number" name="min_price" placeholder="Min" value="{{ request('min_price') }}" class="w-full px-3 py-2 text-sm border-2 border-white/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 bg-white/80 backdrop-blur">
                                        <input type="number" name="max_price" placeholder="Max" value="{{ request('max_price') }}" class="w-full px-3 py-2 text-sm border-2 border-white/40 rounded-lg focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 bg-white/80 backdrop-blur">
                                    </div>
                                    <button type="submit" class="w-full px-3 py-2 text-sm font-bold bg-stone-800 text-white rounded-lg hover:bg-stone-700 shadow-md hover:shadow-lg transition-all flex items-center justify-center gap-2">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        Apply Filter
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <main class="flex-1">
                @if(isset($recommendedProducts) && $recommendedProducts->count() > 0 && !$request->hasAny(['search', 'category', 'condition', 'min_price', 'max_price', 'sort']))
                    <div class="mb-8 relative overflow-hidden p-6 rounded-2xl shadow-xl" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
                        <!-- Decorative Elements -->
                        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-white/30 backdrop-blur rounded-lg">
                                    <i data-lucide="sparkles" class="w-5 h-5 text-stone-800"></i>
                                </div>
                                <h2 class="text-xl font-bold text-white">Recommended For You</h2>
                            </div>
                            <p class="text-sm text-white/90 mb-5">Based on items you've recently viewed</p>
                            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($recommendedProducts as $product)
                                    <a href="{{ route('shop.product', $product->id) }}" class="group">
                                        <div class="relative aspect-[3/4] overflow-hidden rounded-xl border-2 border-white/30 bg-white mb-2 shadow-lg hover:shadow-2xl transition-all duration-300">
                                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/300' }}"
                                                 alt="{{ $product->name }}"
                                                 class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                            <div class="absolute bottom-2 right-2 px-2 py-1 text-xs font-bold rounded-lg backdrop-blur bg-white/90 text-stone-800 border border-white/50 shadow-sm">
                                                {{ ucfirst($product->condition) }}
                                            </div>
                                        </div>
                                        <h3 class="text-xs font-bold text-white group-hover:text-stone-800 transition-colors line-clamp-2 leading-tight">
                                            {{ $product->name }}
                                        </h3>
                                        <p class="text-sm font-bold text-white mt-0.5">RM{{ number_format($product->price, 2) }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Page Title -->
                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-zinc-900">
                        @if(request('search'))
                            Search results for "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ $categories->find(request('category'))->name ?? 'Products' }}
                        @else
                            All Products
                        @endif
                    </h1>
                    <p class="text-sm text-zinc-600 mt-1">{{ $products->total() }} items available</p>
                </div>

                <!-- Sort Dropdown -->
                <div class="flex items-center justify-between mb-6">
                    <div></div>
                    <select onchange="window.location.href=this.value" class="px-4 py-2 text-sm font-semibold border-2 border-zinc-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-stone-800 focus:border-stone-800 bg-white shadow-md hover:shadow-lg transition-all cursor-pointer">
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort', 'newest') === 'newest' ? 'selected' : '' }}>🕐 Newest</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'recommended'])) }}" {{ request('sort') === 'recommended' ? 'selected' : '' }}>✨ For You</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_low'])) }}" {{ request('sort') === 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'price_high'])) }}" {{ request('sort') === 'price_high' ? 'selected' : '' }}>💎 Price: High to Low</option>
                        <option value="{{ route('shop.index', array_merge(request()->except('sort'), ['sort' => 'popular'])) }}" {{ request('sort') === 'popular' ? 'selected' : '' }}>🔥 Most Popular</option>
                    </select>
                </div>

                @if($products->isEmpty())
                    <div class="text-center py-16">
                        <div class="w-20 h-20 rounded-full mx-auto mb-4 flex items-center justify-center" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                            <i data-lucide="search-x" class="w-10 h-10 text-white"></i>
                        </div>
                        <h3 class="text-lg font-bold text-zinc-900 mb-2">No items found</h3>
                        <p class="text-sm text-zinc-600">Try adjusting your filters or search terms</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
                        @foreach($products as $product)
                            <a href="{{ route('shop.product', $product->id) }}"
                               class="group"
                               onclick="trackProductClick({{ $product->id }})">
                                <div class="relative aspect-[3/4] overflow-hidden rounded-xl border-2 border-zinc-200 bg-zinc-100 mb-3 shadow-md hover:shadow-xl transition-all duration-300">
                                    <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/400' }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @if($product->featured)
                                        <div class="absolute top-2 left-2 px-2 py-1 text-xs font-bold rounded-lg" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%); color: white; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
                                            ⭐ Featured
                                        </div>
                                    @endif
                                    <div class="absolute bottom-2 right-2 px-2 py-1 text-xs font-bold rounded-lg backdrop-blur bg-white/90 text-stone-800 border border-white/50 shadow-sm">
                                        {{ ucfirst($product->condition) }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-bold text-zinc-900 group-hover:text-[#c53131] transition-colors line-clamp-1">{{ $product->name }}</h3>
                                <p class="text-sm text-zinc-600 mt-0.5">{{ $product->brand }}</p>
                                <p class="text-base font-bold text-zinc-900 mt-1">RM{{ number_format($product->price, 2) }}</p>
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

        // Track product click
        async function trackProductClick(productId) {
            try {
                await fetch('/api/interactions', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        type: 'click'
                    })
                });
            } catch (error) {
                console.error('Error tracking click:', error);
            }
        }

        // Update cart count
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

        // Update cart count after adding items
        document.addEventListener('cartUpdated', updateCartCount);
    </script>
</body>
</html>