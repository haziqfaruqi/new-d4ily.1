<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50">
@include('partials.navigation')

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-20 right-4 z-50 flex flex-col gap-2"></div>

    <div class="mx-auto max-w-[1200px] px-8 py-8">
        <!-- Page Header -->
        <div class="relative overflow-hidden p-8 rounded-2xl shadow-xl mb-8" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="px-3 py-1 bg-white/30 backdrop-blur rounded-full">
                            <span class="text-xs font-bold text-white">CART</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                            <span class="text-xs text-white/80">Active</span>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">Shopping Cart</h1>
                    <p class="text-sm text-white/90 flex items-center gap-2">
                        <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        {{ $cart ? $cart->items->count() : 0 }} item{{ ($cart && $cart->items->count() !== 1) ? 's' : '' }} in your cart
                    </p>
                </div>
                <div class="hidden sm:block">
                    <div class="w-16 h-16 rounded-2xl bg-white/30 backdrop-blur flex items-center justify-center shadow-lg">
                        <i data-lucide="shopping-cart" class="w-8 h-8 text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    @foreach($cart->items as $item)
                        <div class="bg-white rounded-2xl border-2 border-zinc-200 shadow-lg hover:shadow-xl transition-all duration-300 mb-4 overflow-hidden" data-item-id="{{ $item->id }}">
                            <div class="p-6">
                                <div class="flex gap-6">
                                    <!-- Product Image -->
                                    <div class="relative flex-shrink-0">
                                        <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/200' }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-28 h-36 object-cover rounded-xl border-2 border-zinc-200 shadow-md">
                                        <div class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                                            <span class="text-xs font-bold text-white">{{ $item->quantity }}</span>
                                        </div>
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-zinc-100 text-zinc-700">
                                                        {{ $item->product->brand }}
                                                    </span>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold" style="background: #d5fdff; color: #1f2937;">
                                                        {{ $item->product->category->name }}
                                                    </span>
                                                </div>
                                                <h3 class="text-lg font-bold text-zinc-900">{{ $item->product->name }}</h3>
                                                <div class="flex items-center gap-3 mt-2 text-sm text-zinc-600">
                                                    <span class="flex items-center gap-1">
                                                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i>
                                                        {{ ucfirst($item->product->condition) }}
                                                    </span>
                                                    @if($item->product->size)
                                                        <span class="flex items-center gap-1">
                                                            <i data-lucide="ruler" class="w-3.5 h-3.5"></i>
                                                            {{ $item->product->size }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button onclick="removeFromCart({{ $item->id }})"
                                                    class="p-2 rounded-lg border-2 border-red-200 text-red-500 hover:bg-red-50 hover:border-red-300 transition-all hover:shadow-md">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>

                                        <!-- Price Section -->
                                        <div class="flex items-center justify-between mt-4 p-3 bg-zinc-50 rounded-xl">
                                            <div>
                                                <p class="text-xs text-zinc-500 mb-1">Unit Price</p>
                                                <span class="text-lg font-bold text-zinc-900">RM{{ number_format($item->price, 2) }}</span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xs text-zinc-500 mb-1">Subtotal</p>
                                                <div class="text-xl font-bold" style="color: #c53131;">RM{{ number_format($item->price * $item->quantity, 2) }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl border-2 border-zinc-200 shadow-xl p-6 sticky top-24">
                        <div class="flex items-center gap-2 mb-6">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                <i data-lucide="receipt" class="w-4 h-4 text-stone-800"></i>
                            </div>
                            <h2 class="text-lg font-bold text-zinc-900">Order Summary</h2>
                        </div>

                        @php
                            $subtotal = $cart->items->sum(fn($item) => $item->price * $item->quantity);
                            $shipping = 5.00;
                            $tax = $cart->items->count() * 2.00;
                            $total = $subtotal + $shipping + $tax;
                        @endphp

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm p-3 bg-zinc-50 rounded-xl">
                                <span class="font-medium text-zinc-600">Subtotal</span>
                                <span class="font-bold text-zinc-900">RM{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm p-3 bg-zinc-50 rounded-xl">
                                <span class="font-medium text-zinc-600">Shipping</span>
                                <span class="font-bold text-zinc-900">RM{{ number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm p-3 bg-zinc-50 rounded-xl">
                                <span class="font-medium text-zinc-600">Tax (RM2/item)</span>
                                <span class="font-bold text-zinc-900">RM{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="border-t-2 border-zinc-200 pt-3 mt-4">
                                <div class="flex justify-between p-4 rounded-xl" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                                    <span class="font-bold text-stone-800">Total</span>
                                    <span class="font-extrabold text-xl text-stone-800">RM{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}"
                           class="w-full bg-stone-900 text-white py-4 px-4 rounded-xl font-bold hover:bg-stone-800 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl mb-3">
                            <i data-lucide="lock" class="w-5 h-5"></i>
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('shop.index') }}"
                           class="w-full py-3 px-4 rounded-xl font-bold transition-all border-2 border-zinc-200 flex items-center justify-center gap-2 hover:bg-zinc-50 hover:border-zinc-300">
                            <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-24 h-24 rounded-full mx-auto mb-6 flex items-center justify-center shadow-lg" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 100%);">
                    <i data-lucide="shopping-cart" class="w-12 h-12 text-white"></i>
                </div>
                <h3 class="text-2xl font-bold text-zinc-900 mb-3">Your cart is empty</h3>
                <p class="text-base text-zinc-600 mb-8">Add some items to get started on your sustainable fashion journey</p>
                <a href="{{ route('shop.index') }}"
                   class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-bold text-white shadow-lg hover:shadow-xl transition-all" style="background: linear-gradient(135deg, #c53131 0%, #D65A48 100%);">
                    <i data-lucide="shopping-bag" class="w-5 h-5"></i>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

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

            setTimeout(() => {
                toast.classList.remove('translate-x-full', 'opacity-0');
            }, 10);

            setTimeout(() => {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        async function removeFromCart(itemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                console.log('Removing item:', itemId);

                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                console.log('Response status:', response.status);

                if (response.ok) {
                    const data = await response.json();
                    console.log('Response data:', data);

                    // Immediately reload the page to show updated cart
                    showToast('Item removed from cart', 'success');

                    // Small delay to show the toast before reload
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    console.error('Response not ok:', response.status);
                    const errorText = await response.text();
                    console.error('Error response:', errorText);
                    showToast('Failed to remove item from cart', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Something went wrong', 'error');
            }
        }

        // Update cart count on page load
        async function updateCartCount() {
            try {
                const response = await fetch('/api/cart', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                if (response.ok) {
                    const cart = await response.json();
                    const totalItems = cart.items ? cart.items.reduce((total, item) => total + item.quantity, 0) : 0;
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = totalItems;
                        cartCountElement.style.display = totalItems > 0 ? 'flex' : 'none';
                    }
                }
            } catch (error) {
                // Silently fail - cart count will be updated by server-side rendering
                console.debug('Cart count update skipped (using server value)');
            }
        }

        // Update cart count on page load
        updateCartCount();

        // Update cart count after changes
        window.addEventListener('cartUpdated', updateCartCount);
    </script>
</body>
</html>
