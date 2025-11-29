<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name ?? 'Product' }} - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="selection:bg-zinc-900 selection:text-white text-zinc-900 bg-white pb-20">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 border-b backdrop-blur-md border-zinc-100 bg-white/80">
        <div class="mx-auto max-w-7xl px-6 h-14 flex items-center justify-between">
            <div class="flex items-center gap-8">
                <a href="#" class="text-lg font-semibold tracking-tighter text-zinc-900">d1ily.1</a>
                <div class="hidden md:flex items-center gap-6 text-sm font-medium text-zinc-500">
                    <a href="#" class="transition-colors hover:text-zinc-900">New Arrivals</a>
                    <a href="#" class="transition-colors hover:text-zinc-900">Vintage</a>
                    <a href="#" class="transition-colors hover:text-zinc-900">Brands</a>
                    <a href="#" class="transition-colors hover:text-zinc-900">Sale</a>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="relative hidden sm:block group">
                    <i data-lucide="search" class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                    <input type="text" placeholder="Search thrift..."
                        class="h-9 w-64 rounded-md border pl-9 pr-4 text-xs focus:border-zinc-300 focus:bg-white focus:outline-none focus:ring-0 transition-all placeholder:text-zinc-400 border-zinc-200 bg-zinc-100">
                </div>
                <button class="relative p-1 text-zinc-500 hover:text-zinc-900">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                </button>
                <button class="relative p-1 text-zinc-500 hover:text-zinc-900">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    <span class="absolute top-0 right-0 flex h-3 w-3 items-center justify-center rounded-full text-[10px] bg-zinc-900 text-white">2</span>
                </button>
                <div class="h-8 w-8 overflow-hidden rounded-full border border-zinc-200 bg-zinc-100">
                    <img src="https://api.dicebear.com/7.x/avataaars/svg?seed=Felix" alt="User" class="h-full w-full object-cover opacity-90">
                </div>
            </div>
        </div>
    </nav>

    <!-- Product Content -->
    <main class="mx-auto max-w-7xl px-6 py-10">
        <!-- Breadcrumbs -->
        <div class="flex items-center gap-2 text-xs text-zinc-500 mb-8">
            <a href="#" class="hover:text-zinc-900">Home</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <a href="#" class="hover:text-zinc-900">{{ $product->category->name ?? 'Collection' }}</a>
            <i data-lucide="chevron-right" class="w-3 h-3"></i>
            <span class="font-medium text-zinc-900">{{ $product->name }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            <!-- Left: Gallery -->
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="relative aspect-[4/3] w-full overflow-hidden rounded-lg border group border-zinc-100 bg-zinc-100">
                    <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/800' }}" alt="{{ $product->name }}"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105">
                    @if($product->stock == 1)
                        <div class="absolute bottom-4 left-4 inline-flex items-center rounded-full backdrop-blur px-3 py-1 text-xs font-medium shadow-sm border bg-white/90 text-zinc-800 border-zinc-200">
                            Only 1 available
                        </div>
                    @endif
                </div>
                <div class="grid grid-cols-4 gap-4">
                    @if(isset($product->images) && is_array($product->images))
                        @foreach(array_slice($product->images, 0, 4) as $image)
                            <button class="aspect-square overflow-hidden rounded-md border border-zinc-200 hover:border-zinc-400">
                                <img src="{{ $image }}" class="h-full w-full object-cover">
                            </button>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Right: Details -->
            <div class="lg:col-span-5 flex flex-col h-full">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="text-2xl font-semibold tracking-tight text-zinc-900">{{ $product->name }}</h1>
                        <p class="mt-1 text-sm text-zinc-500">{{ $product->brand }} · {{ $product->category->name ?? 'Vintage' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-medium tracking-tight text-zinc-900">${{ number_format($product->price, 2) }}</p>
                    </div>
                </div>

                <div class="mt-6 space-y-4 border-t py-6 border-zinc-100">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="block text-xs text-zinc-500">Condition</span>
                            <span class="block text-sm font-medium text-zinc-900 capitalize">{{ $product->condition }}</span>
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
                            <span class="block text-sm font-medium text-zinc-900">{{ $product->stock > 0 ? 'In Stock' : 'Sold Out' }}</span>
                        </div>
                    </div>
                </div>

                <div class="prose prose-sm leading-relaxed text-zinc-600">
                    <p>{{ $product->description }}</p>
                </div>

                <div class="mt-auto pt-8 space-y-3">
                    <div class="flex gap-3">
                        <button id="add-to-cart-btn"
                            class="flex-1 transition-colors flex hover:bg-zinc-800 text-sm font-medium text-white bg-zinc-900 h-11 rounded-md shadow-sm gap-x-2 gap-y-2 items-center justify-center">
                            Add to Cart
                        </button>
                        <button class="h-11 w-11 flex items-center justify-center rounded-md border text-zinc-500 hover:text-red-500 transition-colors border-zinc-200 hover:bg-zinc-50">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- RECOMMENDER SYSTEM TRIGGER -->
                    <button id="find-similar-btn"
                        class="w-full h-11 border text-sm font-medium rounded-md flex items-center justify-center gap-2 transition-colors bg-indigo-50 text-indigo-700 border-indigo-100 hover:bg-indigo-100">
                        <i data-lucide="sparkles" class="w-4 h-4"></i>
                        Find Similar Items
                    </button>
                    <p class="text-[10px] text-center text-zinc-400">AI-powered suggestions based on visual similarity</p>
                </div>
            </div>
        </div>

        <!-- Recommendation Section (Triggered State) -->
        <div id="recommendations-section" class="mt-24 hidden">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-indigo-600"></i>
                    <h2 class="text-lg font-semibold tracking-tight text-zinc-900">Similar Thrift Finds</h2>
                </div>
            </div>

            <div id="recommendations-grid" class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <!-- Items will be injected here -->
            </div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        const productId = {{ $product->id ?? 'null' }};
        const apiBase = '/api';

        // Add to Cart
        async function addToCart() {
            if (!productId) return;
            
            const btn = document.getElementById('add-to-cart-btn');
            const originalText = btn.innerText;
            btn.innerText = 'Adding...';
            btn.disabled = true;

            try {
                // In a real app, handle auth token here
                const response = await fetch(`${apiBase}/cart`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        // 'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: 1
                    })
                });

                if (response.ok) {
                    alert('Added to cart!');
                } else {
                    const data = await response.json();
                    if(response.status === 401) {
                        alert('Please login to add items to cart.');
                    } else {
                        alert('Failed to add to cart: ' + (data.message || 'Unknown error'));
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong.');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        // Fetch Recommendations
        async function fetchRecommendations() {
            if (!productId) return;

            const container = document.getElementById('recommendations-grid');
            const section = document.getElementById('recommendations-section');
            const btn = document.getElementById('find-similar-btn');
            
            const originalContent = btn.innerHTML;
            btn.innerHTML = `<span class="animate-spin mr-2">⏳</span> Finding...`;
            btn.disabled = true;

            try {
                const response = await fetch(`${apiBase}/recommendations/similar/${productId}`);
                const products = await response.json();

                if (Array.isArray(products) && products.length > 0) {
                    container.innerHTML = products.map(product => `
                        <div class="group cursor-pointer" onclick="window.location.href='/customer/product/${product.id}'">
                            <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100">
                                <img src="${product.images && product.images[0] ? product.images[0] : 'https://via.placeholder.com/400'}" alt="${product.name}"
                                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                <div class="absolute bottom-2 left-2 inline-flex items-center rounded px-2 py-0.5 text-[10px] font-medium backdrop-blur bg-indigo-600/90 text-white">
                                    Similar
                                </div>
                            </div>
                            <div class="mt-3">
                                <h3 class="text-sm font-medium group-hover:text-indigo-600 truncate text-zinc-900">${product.name}</h3>
                                <p class="text-xs text-zinc-500">${product.brand}</p>
                                <p class="mt-1 text-sm font-medium text-zinc-900">$${Number(product.price).toFixed(2)}</p>
                            </div>
                        </div>
                    `).join('');
                    
                    section.classList.remove('hidden');
                    section.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('No similar items found right now.');
                }
            } catch (error) {
                console.error('Error fetching recommendations:', error);
                alert('Failed to fetch recommendations.');
            } finally {
                btn.innerHTML = originalContent;
                btn.disabled = false;
                lucide.createIcons();
            }
        }

        document.getElementById('add-to-cart-btn').addEventListener('click', addToCart);
        document.getElementById('find-similar-btn').addEventListener('click', fetchRecommendations);
    </script>

</body>
</html>