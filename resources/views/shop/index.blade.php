<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D4ily.1 - Vintage Thrift Shop</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
            <aside class="hidden lg:block w-56 flex-shrink-0">
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
                            <a href="{{ route('shop.index') }}" class="block text-sm {{ !request('condition') ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">All Conditions</a>
                            <a href="{{ route('shop.index', ['condition' => 'excellent']) }}" class="block text-sm {{ request('condition') == 'excellent' ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">Excellent</a>
                            <a href="{{ route('shop.index', ['condition' => 'good']) }}" class="block text-sm {{ request('condition') == 'good' ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">Good</a>
                            <a href="{{ route('shop.index', ['condition' => 'fair']) }}" class="block text-sm {{ request('condition') == 'fair' ? 'text-zinc-900 font-medium' : 'text-zinc-600 hover:text-zinc-900' }}">Fair</a>
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
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-6">
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
                                <p class="text-base font-semibold text-zinc-900 mt-1">RM{{ number_format($product->price, 2) }}</p>
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