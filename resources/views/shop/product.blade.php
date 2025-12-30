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

@include('partials.navigation')

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 flex flex-col gap-2"></div>

    <!-- Product Content -->
    <main class="mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-8">
            <a href="{{ route('shop.index') }}" class="hover:text-zinc-900">Home</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <a href="{{ route('shop.index', ['category' => $product->category_id]) }}"
                class="hover:text-zinc-900">{{ $product->category->name }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="font-medium text-zinc-900">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">
            <!-- Left: Gallery -->
            <div class="lg:col-span-7 flex flex-col justify-center h-full">
                <!-- Main Image -->
                <div class="max-w-lg mx-auto w-full">
                    <div class="relative aspect-square w-full overflow-hidden rounded-lg border group border-zinc-200 bg-zinc-100">
                        <img id="main-image" src="{{ $product->images[0] ?? 'https://via.placeholder.com/600' }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @if($product->stock == 1)
                            <div
                                class="absolute bottom-4 left-4 inline-flex items-center rounded-full backdrop-blur px-3 py-1 text-xs font-medium shadow-sm border bg-white/90 text-zinc-800 border-zinc-200">
                                Only 1 available
                            </div>
                        @endif
                    </div>

                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 gap-4 mt-4">
                        @if(isset($product->images) && is_array($product->images) && count($product->images) > 0)
                            @foreach($product->images as $index => $image)
                                <button
                                    onclick="document.getElementById('main-image').src='{{ $image }}'"
                                    class="relative aspect-square overflow-hidden rounded-md border transition-all duration-200 {{ $index === 0 ? 'ring-2 ring-black' : 'border-zinc-200 hover:border-zinc-400' }} {{ $index === 0 ? 'cursor-default' : 'cursor-pointer' }}">
                                    <img src="{{ $image }}" class="h-full w-full object-cover">
                                </button>
                            @endforeach

                            <!-- Duplicate to fill 4 thumbnails if needed -->
                            @if(count($product->images) < 4)
                                @for ($i = count($product->images); $i < 4; $i++)
                                    <button disabled
                                        class="relative aspect-square overflow-hidden rounded-md border border-zinc-200 opacity-50 cursor-not-allowed">
                                        <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/200' }}"
                                            class="h-full w-full object-cover opacity-50">
                                    </button>
                                @endfor
                            @endif
                        @else
                            <!-- No images - show 4 placeholders -->
                            @for ($i = 0; $i < 4; $i++)
                                <button disabled
                                    class="relative aspect-square overflow-hidden rounded-md border border-zinc-200 opacity-50 cursor-not-allowed">
                                    <img src="https://via.placeholder.com/200"
                                        class="h-full w-full object-cover opacity-50">
                                </button>
                            @endfor
                        @endif
                    </div>
                </div>
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
                            RM{{ number_format($product->price, 2) }}</p>
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
                        @if($product->material)
                            <div>
                                <span class="block text-xs text-zinc-500">Material</span>
                                <span class="block text-sm font-medium text-zinc-900">{{ $product->material }}</span>
                            </div>
                        @endif
                        <div>
                            <span class="block text-xs text-zinc-500">Stock</span>
                            <span class="block text-sm font-medium text-zinc-900">{{ $product->stock }} available</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs text-zinc-500 mb-2">Description</span>
                        <p class="text-sm text-zinc-700 leading-relaxed">{{ $product->description }}</p>
                    </div>
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

                    @if(isset($similarProducts) && $similarProducts->count() > 0)
                        <button id="find-similar-btn" onclick="toggleSimilarItems()"
                            class="w-full transition-colors flex hover:bg-indigo-600 text-sm font-medium text-white bg-indigo-500 h-11 rounded-md shadow-sm gap-x-2 gap-y-2 items-center justify-center">
                            <i data-lucide="sparkles" class="w-4 h-4"></i>
                            <span id="similar-btn-text">Find Similar Items</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" id="similar-btn-icon"></i>
                        </button>
                    @endif
                </div>
        </div>
    </main>

    <!-- Similar Items Results Section - Enhanced -->
    @if(isset($similarProducts) && $similarProducts->count() > 0)
        <section id="similar-items-section" class="hidden transition-all duration-500 bg-zinc-50">
            <div class="border-t border-zinc-200"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
                <div class="max-w-7xl mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-2xl font-bold text-zinc-900">Similar Items</h2>
                        <button onclick="toggleSimilarItems()"
                                class="text-sm font-medium text-zinc-600 hover:text-zinc-900 transition-colors hidden">
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($similarProducts as $similarProduct)
                            <a href="{{ route('shop.product', $similarProduct->id) }}" class="group transition-all duration-500 transform hover:-translate-y-1">
                                <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-white mb-4 shadow-sm hover:shadow-md transition-all duration-500">
                                    <img src="{{ $similarProduct->images[0] ?? 'https://via.placeholder.com/300' }}"
                                        alt="{{ $similarProduct->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    @if($similarProduct->featured)
                                        <div class="absolute top-2 left-2 px-2 py-1 text-xs font-medium rounded bg-indigo-600 text-white">Featured</div>
                                    @endif
                                    <div class="absolute bottom-2 right-2 px-2 py-1 text-xs font-medium rounded backdrop-blur bg-white/90 text-zinc-900 border border-zinc-200">{{ ucfirst($similarProduct->condition) }}</div>
                                </div>
                                <h3 class="text-sm font-medium text-zinc-900 group-hover:text-indigo-600 transition-colors line-clamp-2 mb-1">{{ $similarProduct->name }}</h3>
                                <p class="text-xs text-zinc-500 mb-2">{{ $similarProduct->brand }}</p>
                                <p class="text-sm font-semibold text-zinc-900">RM{{ number_format($similarProduct->price, 2) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <script>
        lucide.createIcons();

        // Toast notification function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-emerald-50 border-emerald-200' : 'bg-red-50 border-red-200';
            const iconColor = type === 'success' ? 'text-emerald-600' : 'text-red-600';
            const icon = type === 'success' ? 'check-circle' : 'alert-circle';
            const textColor = type === 'success' ? 'text-emerald-800' : 'text-red-800';

            toast.className = `flex items-center gap-2 px-4 py-3 rounded-lg border shadow-lg ${bgColor} min-w-[300px] transform transition-all duration-300 translate-x-full opacity-0`;
            toast.innerHTML = `
                <i data-lucide="${icon}" class="w-5 h-5 ${iconColor}"></i>
                <span class="text-sm font-medium ${textColor}">${message}</span>
            `;

            container.appendChild(toast);
            lucide.createIcons();

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            // Auto remove after 3 seconds
            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
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

        const productId = {{ $product->id }};

        function toggleSimilarItems() {
            const section = document.getElementById('similar-items-section');
            const btnText = document.getElementById('similar-btn-text');
            const btnIcon = document.getElementById('similar-btn-icon');
            const hideButton = document.querySelector('#similar-items-section button');

            if (section.classList.contains('hidden')) {
                section.classList.remove('hidden');
                btnText.textContent = 'Hide Similar Items';
                btnIcon.style.transform = 'rotate(180deg)';
                hideButton.classList.remove('hidden');
                section.scrollIntoView({ behavior: 'smooth' });
            } else {
                section.classList.add('hidden');
                btnText.textContent = 'Find Similar Items';
                btnIcon.style.transform = 'rotate(0deg)';
                hideButton.classList.add('hidden');
            }
        }

        async function addToCart() {
            const btn = document.getElementById('add-to-cart-btn');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i data-lucide="loader" class="w-4 h-4 animate-spin"></i> Adding...';
            btn.disabled = true;
            lucide.createIcons();

            try {
                const response = await fetch(`{{ route('cart.add', $product->id) }}`, {
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
                    const data = await response.json();
                    btn.innerHTML = '<i data-lucide="check" class="w-4 h-4"></i> Added!';
                    lucide.createIcons();

                    // Show success toast
                    showToast('Item added to cart successfully!', 'success');

                    // Update cart count immediately
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count;
                        cartCountElement.style.display = data.cart_count > 0 ? 'flex' : 'none';
                    }

                    setTimeout(() => {
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }, 2000);
                } else {
                    if (response.status === 401) {
                        showToast('Please login to add items to cart', 'error');
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 1500);
                    } else if (response.status === 409) {
                        const data = await response.json();
                        showToast(data.error || 'This item is already in your cart', 'error');
                        btn.innerHTML = '<i data-lucide="shopping-cart" class="w-4 h-4"></i> In Cart';
                        btn.disabled = true;
                        lucide.createIcons();
                    } else {
                        const data = await response.json();
                        showToast(data.error || 'Failed to add to cart', 'error');
                        btn.innerHTML = originalHTML;
                        btn.disabled = false;
                        lucide.createIcons();
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
                btn.innerHTML = originalHTML;
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        async function toggleWishlist() {
            const btn = document.getElementById('wishlist-btn');
            const originalHTML = btn.innerHTML;

            try {
                const response = await fetch(`{{ route('wishlist.toggle', $product->id) }}`, {
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
                        btn.innerHTML = '<i data-lucide="heart" class="w-5 h-5" fill="currentColor"></i>';
                        btn.classList.remove('text-zinc-500', 'hover:text-red-500', 'border-zinc-200', 'hover:bg-zinc-50');
                        btn.classList.add('text-red-500', 'border-red-200', 'bg-red-50');
                        showToast('Added to wishlist!', 'success');
                    } else {
                        btn.innerHTML = '<i data-lucide="heart" class="w-5 h-5"></i>';
                        btn.classList.remove('text-red-500', 'border-red-200', 'bg-red-50');
                        btn.classList.add('text-zinc-500', 'hover:text-red-500', 'border-zinc-200', 'hover:bg-zinc-50');
                        showToast('Removed from wishlist', 'success');
                    }
                    lucide.createIcons();
                } else {
                    if (response.status === 401) {
                        showToast('Please login to add items to wishlist', 'error');
                        setTimeout(() => {
                            window.location.href = '{{ route("login") }}';
                        }, 1500);
                    } else {
                        showToast('Failed to toggle wishlist', 'error');
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
            }
        }
    </script>
</body>
</html>