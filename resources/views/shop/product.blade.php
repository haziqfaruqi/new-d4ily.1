<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Navigation -->
    <nav class="sticky top-0 z-50 border-b backdrop-blur-md border-zinc-200 bg-white/90">
        <div class="mx-auto max-w-7xl px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="{{ route('shop.index') }}" class="text-xl font-bold tracking-tighter text-zinc-900">d4ily.1</a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-zinc-600">
                    <a href="{{ route('shop.index') }}" class="transition-colors hover:text-zinc-900">Shop</a>
                    <a href="{{ route('shop.recommendations') }}"
                        class="transition-colors hover:text-zinc-900 flex items-center gap-1">
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

    <!-- Product Content -->
    <main class="mx-auto max-w-7xl px-6 py-10">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-8">
            <a href="{{ route('shop.index') }}" class="hover:text-zinc-900">Home</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('shop.index', ['category' => $product->category_id]) }}"
                class="hover:text-zinc-900">{{ $product->category->name }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="font-medium text-zinc-900">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Gallery -->
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div
                    class="relative aspect-[4/3] w-full overflow-hidden rounded-lg border group border-zinc-200 bg-zinc-100">
                    <img id="main-image" src="{{ $product->images[0] ?? 'https://via.placeholder.com/800' }}"
                        alt="{{ $product->name }}"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @if($product->stock == 1)
                        <div
                            class="absolute bottom-4 left-4 inline-flex items-center rounded-full backdrop-blur px-3 py-1 text-xs font-medium shadow-sm border bg-white/90 text-zinc-800 border-zinc-200">
                            Only 1 available
                        </div>
                    @endif
                </div>
                @if(isset($product->images) && is_array($product->images) && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach(array_slice($product->images, 0, 4) as $image)
                            <button onclick="document.getElementById('main-image').src='{{ $image }}'"
                                class="aspect-square overflow-hidden rounded-md border border-zinc-200 hover:border-zinc-400 transition-colors">
                                <img src="{{ $image }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Details -->
            <div class="lg:col-span-5 flex flex-col h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $product->name }}</h1>
                        <p class="mt-1 text-sm text-zinc-500">{{ $product->brand }} · {{ $product->category->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold tracking-tight text-zinc-900">
                            ${{ number_format($product->price, 2) }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4 border-t py-6 border-zinc-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-zinc-500">Condition</span>
                            <span
                                class="block text-sm font-medium text-zinc-900 capitalize">{{ $product->condition }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Size</span>
                            <span class="block text-sm font-medium text-zinc-900">{{ $product->size }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Color</span>
                            <span class="block text-sm font-medium text-zinc-900">{{ $product->color }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Stock</span>
                            <span
                                class="block text-sm font-medium {{ $product->stock > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $product->stock > 0 ? 'In Stock' : 'Sold Out' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="prose prose-sm leading-relaxed text-zinc-600">
                    <p>{{ $product->description }}</p>
                </div>

                <div class="mt-auto pt-8 space-y-3">
                    <div class="flex gap-3">
                        <button id="add-to-cart-btn" onclick="addToCart()"
                            class="flex-1 transition-colors flex hover:bg-zinc-800 text-sm font-medium text-white bg-zinc-900 h-11 rounded-md shadow-sm gap-x-2 gap-y-2 items-center justify-center">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            Add to Cart
                        </button>
                        <button id="wishlist-btn" onclick="toggleWishlist()"
                            class="h-11 w-11 flex items-center justify-center rounded-md border {{ $isWishlisted ? 'text-red-500 border-red-200 bg-red-50' : 'text-zinc-500 hover:text-red-500 border-zinc-200 hover:bg-zinc-50' }} transition-colors">
                            <i data-lucide="heart" class="w-5 h-5" {{ $isWishlisted ? 'fill="currentColor"' : '' }}></i>
                        </button>
                    </div>

                    <button id="find-similar-btn" onclick="toggleSimilarItems()"
                        class="w-full h-11 border text-sm font-medium rounded-md flex items-center justify-center gap-2 transition-colors bg-indigo-50 text-indigo-700 border-indigo-100 hover:bg-indigo-100">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        Find Similar Items
                    </button>
                    <p class="text-[10px] text-center text-zinc-400">AI-powered suggestions based on visual similarity
                    </p>
                </div>
            </div>
        </div>

        <!-- Similar Items Section -->
        @if($similarProducts && $similarProducts->count() > 0)
            <div id="similar-items-section" class="mt-24 hidden">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-2">
                        <i data-lucide="sparkles" class="w-4 h-4 text-indigo-600"></i>
                        <h2 class="text-lg font-semibold tracking-tight text-zinc-900">Similar Thrift Finds</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    @foreach($similarProducts as $similar)
                        <a href="{{ route('shop.product', $similar->id) }}" class="group">
                            <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100">
                                <img src="{{ $similar->images[0] ?? 'https://via.placeholder.com/400' }}"
                                    alt="{{ $similar->name }}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div
                                    class="absolute bottom-2 left-2 inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium backdrop-blur bg-indigo-600/90 text-white">
                                    Similar
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="text-sm font-medium group-hover:text-indigo-600 truncate text-zinc-900">
                                    {{ $similar->name }}
                                </h3>
                                <p class="text-xs text-zinc-500">{{ $similar->brand }}</p>
                                <p class="mt-1 text-sm font-medium text-zinc-900">${{ number_format($similar->price, 2) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </main>

    <script>
        lucide.createIcons();

        const productId = {{ $product->id }};

        function toggleSimilarItems() {
            const section = document.getElementById('similar-items-section');
            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                section.scrollIntoView({ behavior: 'smooth' });
            } else {
                section.classList.add('hidden');
            }
        }

        async function addToCart() {
            const btn = document.getElementById('add-to-cart-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Adding...';
            btn.disabled = true;
            lucide.createIcons();

            try {
                const response = await fetch('{{ route("cart.add", $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        quantity: 1
                    })
                });

                if (response.ok) {
                    btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Added!';
                    lucide.createIcons();
                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }, 2000);
                } else {
                    if (response.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    } else {
                        alert('Failed to add to cart. Please try again.');
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong.');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        async function toggleWishlist() {
            const btn = document.getElementById('wishlist-btn');
            const icon = btn.querySelector('i');

            try {
                const response = await fetch('{{ route("wishlist.toggle", $product->id) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.wishlisted) {
                        btn.classList.add('text-red-500', 'border-red-200', 'bg-red-50');
                        btn.classList.remove('text-zinc-500', 'border-zinc-200', 'hover:bg-zinc-50');
                        icon.setAttribute('fill', 'currentColor');
                    } else {
                        btn.classList.remove('text-red-500', 'border-red-200', 'bg-red-50');
                        btn.classList.add('text-zinc-500', 'border-zinc-200', 'hover:bg-zinc-50');
                        icon.removeAttribute('fill');
                    }
                } else {
                    if (response.status === 401) {
                        window.location.href = '{{ route("login") }}';
                    }
                }
            } catch (error) {
                console.error(error);
            }
        }
    </script>
</body>

</html>