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
    <main class="mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10 max-w-7xl">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-8">
            <a href="{{ route('shop.index') }}" class="hover:text-zinc-900">Shop</a>
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
                    <div class="relative aspect-square w-full overflow-hidden rounded-2xl border-2 group border-zinc-200 bg-zinc-100 shadow-lg">
                        <img id="main-image" src="{{ $product->images[0] ?? 'https://via.placeholder.com/600' }}"
                            alt="{{ $product->name }}"
                            class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                        @if($product->stock == 1)
                            <div
                                class="absolute bottom-4 left-4 inline-flex items-center rounded-full backdrop-blur px-4 py-2 text-sm font-bold shadow-md border-2 bg-white/90 text-zinc-800 border-zinc-200">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1.5" style="color: #c53131;"></i>
                                Only 1 available
                            </div>
                        @endif
                        @if($product->featured)
                            <div class="absolute top-4 left-4 px-3 py-1.5 text-sm font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%); color: white;">
                                ⭐ Featured
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
                <!-- Brand Badge -->
                <div class="mb-3">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-zinc-100 text-zinc-700">
                        {{ $product->brand }}
                    </span>
                </div>

                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold tracking-tight text-zinc-900">{{ $product->name }}</h1>
                        <p class="mt-2 text-sm text-zinc-600 flex items-center gap-1">
                            <i data-lucide="tag" class="w-4 h-4"></i>
                            {{ $product->category->name }}
                        </p>
                    </div>
                </div>

                <!-- Price Card -->
                <div class="relative overflow-hidden rounded-2xl p-6 mb-6 shadow-lg" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-semibold text-stone-700 mb-1">Price</p>
                            <p class="text-4xl font-extrabold text-stone-800">
                                RM{{ number_format($product->price, 2) }}
                            </p>
                        </div>
                        <div class="w-12 h-12 bg-white/30 backdrop-blur rounded-xl flex items-center justify-center">
                            <i data-lucide="banknote" class="w-6 h-6 text-stone-800"></i>
                        </div>
                    </div>
                </div>

                <!-- Product Details -->
                <div class="space-y-4 border-t border-b border-zinc-100 py-6 mb-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-zinc-50 rounded-xl">
                            <span class="block text-xs text-zinc-500 mb-1 flex items-center gap-1">
                                <i data-lucide="sparkles" class="w-3 h-3"></i>
                                Condition
                            </span>
                            <span class="block text-sm font-bold text-zinc-900 capitalize">{{ $product->condition }}</span>
                        </div>
                        <div class="p-3 bg-zinc-50 rounded-xl">
                            <span class="block text-xs text-zinc-500 mb-1 flex items-center gap-1">
                                <i data-lucide="ruler" class="w-3 h-3"></i>
                                Size
                            </span>
                            <span class="block text-sm font-bold text-zinc-900">{{ $product->size }}</span>
                        </div>
                        @if($product->material)
                            <div class="p-3 bg-zinc-50 rounded-xl">
                                <span class="block text-xs text-zinc-500 mb-1 flex items-center gap-1">
                                    <i data-lucide="layers" class="w-3 h-3"></i>
                                    Material
                                </span>
                                <span class="block text-sm font-bold text-zinc-900">{{ $product->material }}</span>
                            </div>
                        @endif
                        <div class="p-3 bg-zinc-50 rounded-xl">
                            <span class="block text-xs text-zinc-500 mb-1 flex items-center gap-1">
                                <i data-lucide="package" class="w-3 h-3"></i>
                                Availability
                            </span>
                            @if($product->stock > 0)
                                <span class="block text-sm font-bold text-emerald-700">
                                    {{ $product->stock }} available
                                </span>
                            @else
                                <span class="block text-sm font-bold text-red-700">
                                    Sold out
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($product->description)
                        <div class="bg-zinc-50 rounded-xl p-4">
                            <span class="block text-xs text-zinc-500 mb-2 flex items-center gap-1">
                                <i data-lucide="file-text" class="w-3 h-3"></i>
                                Description
                            </span>
                            <p class="text-sm text-zinc-700 leading-relaxed">{{ $product->description }}</p>
                        </div>
                    @endif
                </div>

                <div class="mt-auto pt-4 space-y-3">
                    <div class="flex gap-3">
                        <button id="add-to-cart-btn" onclick="addToCart()"
                            class="flex-1 transition-all flex hover:bg-stone-800 text-sm font-bold text-white bg-stone-900 h-12 rounded-xl shadow-lg hover:shadow-xl gap-x-2 gap-y-2 items-center justify-center">
                            <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                            Add to Cart
                        </button>
                        <button id="wishlist-btn" onclick="toggleWishlist()"
                            class="h-12 w-12 flex items-center justify-center rounded-xl border-2 {{ $isWishlisted ? 'text-red-500 border-red-200 bg-red-50' : 'text-zinc-500 hover:text-red-500 border-zinc-200 hover:bg-zinc-50' }} transition-all hover:shadow-lg">
                            <i data-lucide="heart" class="w-5 h-5" {{ $isWishlisted ? 'fill="currentColor"' : '' }}></i>
                        </button>
                    </div>

                    <!-- Find Similar Item Button -->
                    <button id="find-similar-btn" onclick="toggleSimilarItems()"
                        class="w-full transition-all flex items-center justify-center gap-2 text-sm font-bold text-white h-12 rounded-xl shadow-lg hover:shadow-xl" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                        <i data-lucide="sparkles" class="w-5 h-5"></i>
                        <span id="similar-btn-text">Find Similar Items</span>
                    </button>
                </div>
        </div>
    </main>

    <!-- More from This Brand Section -->
    @if(isset($sameBrandProducts) && $sameBrandProducts->count() > 0)
        <section id="similar-items-section" class="mt-12 hidden">
            <div class="border-t-2 border-zinc-200"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
                <div class="max-w-7xl mx-auto">
                    <!-- Section Header -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                <i data-lucide="award" class="w-5 h-5 text-stone-800"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-zinc-900">More from {{ $product->brand }}</h2>
                        </div>
                        <p class="text-sm text-zinc-600 ml-13">Explore more items from this brand</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($sameBrandProducts as $brandProduct)
                            @php
                                // Calculate similarity percentage
                                // Same brand + same category = 100% match
                                // Same brand only = 60-80% based on condition/price match
                                if ($brandProduct->category_id === $product->category_id) {
                                    $similarityScore = 100;
                                } else {
                                    // Score ranges from 0-30 (condition + price match)
                                    // Map to 60-80% range
                                    $similarityScore = 60 + min(20, ($brandProduct->priority_score ?? 0) / 30 * 20);
                                }
                            @endphp
                            <a href="{{ route('shop.product', $brandProduct->id) }}" class="group transition-all duration-500 transform hover:-translate-y-2">
                                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl border-2 border-zinc-200 bg-white mb-4 shadow-md hover:shadow-xl transition-all duration-500">
                                    <img src="{{ $brandProduct->images[0] ?? 'https://via.placeholder.com/300' }}"
                                        alt="{{ $brandProduct->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">

                                    <!-- Similarity Badge -->
                                    <div class="absolute top-3 left-3 px-2.5 py-1 text-xs font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%); color: white;">
                                        {{ round($similarityScore) }}% Match
                                    </div>

                                    @if($brandProduct->category_id === $product->category_id)
                                        <div class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%); color: white;">
                                            ✓ Same Category
                                        </div>
                                    @endif

                                    @if($brandProduct->featured)
                                        <div class="absolute bottom-14 right-3 px-2.5 py-1 text-xs font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%); color: white;">
                                            ⭐ Featured
                                        </div>
                                    @endif

                                    <div class="absolute bottom-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg backdrop-blur bg-white/95 text-stone-800 border-2 border-white shadow-sm">
                                        {{ ucfirst($brandProduct->condition) }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-bold text-zinc-900 group-hover:text-[#c53131] transition-colors line-clamp-2 mb-1">{{ $brandProduct->name }}</h3>
                                <p class="text-xs text-zinc-600 mb-1 font-medium">{{ $brandProduct->brand }}</p>
                                <p class="text-xs text-zinc-500 mb-2">{{ $brandProduct->category->name }}</p>
                                <p class="text-base font-bold text-zinc-900">RM{{ number_format($brandProduct->price, 2) }}</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- More from This Category Section -->
    @if(isset($sameCategoryProducts) && $sameCategoryProducts->count() > 0)
        <section id="similar-category-section" class="mt-12 hidden">
            <div class="border-t-2 border-zinc-200"></div>
            <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
                <div class="max-w-7xl mx-auto">
                    <!-- Section Header -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                <i data-lucide="layout-grid" class="w-5 h-5 text-stone-800"></i>
                            </div>
                            <h2 class="text-2xl font-bold text-zinc-900">More {{ $product->category->name }}</h2>
                        </div>
                        <p class="text-sm text-zinc-600 ml-13">Explore more items from this category</p>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                        @foreach($sameCategoryProducts as $categoryProduct)
                            <a href="{{ route('shop.product', $categoryProduct->id) }}" class="group transition-all duration-500 transform hover:-translate-y-2">
                                <div class="relative aspect-[3/4] overflow-hidden rounded-2xl border-2 border-zinc-200 bg-white mb-4 shadow-md hover:shadow-xl transition-all duration-500">
                                    <img src="{{ $categoryProduct->images[0] ?? 'https://via.placeholder.com/300' }}"
                                        alt="{{ $categoryProduct->name }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110">

                                    @if($categoryProduct->featured)
                                        <div class="absolute top-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg shadow-md" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%); color: white;">
                                            ⭐ Featured
                                        </div>
                                    @endif

                                    <div class="absolute bottom-3 right-3 px-2.5 py-1 text-xs font-bold rounded-lg backdrop-blur bg-white/95 text-stone-800 border-2 border-white shadow-sm">
                                        {{ ucfirst($categoryProduct->condition) }}
                                    </div>
                                </div>
                                <h3 class="text-sm font-bold text-zinc-900 group-hover:text-[#c53131] transition-colors line-clamp-2 mb-1">{{ $categoryProduct->name }}</h3>
                                <p class="text-xs text-zinc-600 mb-1 font-medium">{{ $categoryProduct->brand }}</p>
                                <p class="text-xs text-zinc-500 mb-2">{{ $categoryProduct->category->name }}</p>
                                <p class="text-base font-bold text-zinc-900">RM{{ number_format($categoryProduct->price, 2) }}</p>
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

        // Toggle similar items visibility
        function toggleSimilarItems() {
            const brandSection = document.getElementById('similar-items-section');
            const categorySection = document.getElementById('similar-category-section');
            const btnText = document.getElementById('similar-btn-text');

            if (brandSection && categorySection) {
                const isHidden = brandSection.classList.contains('hidden');

                if (isHidden) {
                    brandSection.classList.remove('hidden');
                    categorySection.classList.remove('hidden');
                    btnText.textContent = 'Hide Similar Items';
                } else {
                    brandSection.classList.add('hidden');
                    categorySection.classList.add('hidden');
                    btnText.textContent = 'Find Similar Items';
                }
            }
        }

        // Update cart count on page load
        updateCartCount();

        // Update cart count after adding items
        document.addEventListener('cartUpdated', updateCartCount);

        const productId = {{ $product->id }};

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
