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

    <div class="mx-auto max-w-[1200px] px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-900">Shopping Cart</h1>
            <p class="text-base text-zinc-600 mt-2">{{ $cart ? $cart->items->count() : 0 }} item{{ ($cart && $cart->items->count() !== 1) ? 's' : '' }} in your cart</p>
        </div>

        @if($cart && $cart->items->count() > 0)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-zinc-200 divide-y divide-zinc-100">
                        @foreach($cart->items as $item)
                            <div class="p-6" data-item-id="{{ $item->id }}">
                                <div class="flex gap-4">
                                    <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/200' }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-24 h-32 object-cover rounded-lg border border-zinc-200">

                                    <div class="flex-1">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <h3 class="text-lg font-medium text-zinc-900">{{ $item->product->name }}</h3>
                                                <p class="text-sm text-zinc-500">{{ $item->product->brand }}</p>
                                                <p class="text-sm text-zinc-500">{{ $item->product->category->name }}</p>
                                                <div class="flex items-center gap-4 mt-2">
                                                    <span class="text-sm text-zinc-500">Condition: {{ ucfirst($item->product->condition) }}</span>
                                                    @if($item->product->size)
                                                        <span class="text-sm text-zinc-500">Size: {{ $item->product->size }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <button onclick="removeFromCart({{ $item->id }})"
                                                    class="text-red-500 hover:text-red-700 transition-colors">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </div>

                                        <div class="flex items-center justify-between mt-4">
                                            <div class="flex items-center gap-3">
                                                <span class="text-lg font-semibold text-zinc-900">RM{{ number_format($item->price, 2) }}</span>
                                                <span class="text-sm text-zinc-500">× {{ $item->quantity }}</span>
                                            </div>
                                            <div class="text-lg font-bold text-zinc-900">
                                                RM{{ number_format($item->price * $item->quantity, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg border border-zinc-200 p-6 sticky top-24">
                        <h2 class="text-lg font-semibold text-zinc-900 mb-4">Order Summary</h2>

                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600">Subtotal</span>
                                <span class="font-medium text-zinc-900">RM{{ number_format($cart->items->sum(fn($item) => $item->price * $item->quantity), 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600">Shipping</span>
                                <span class="font-medium text-zinc-900">RM5.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-zinc-600">Tax</span>
                                <span class="font-medium text-zinc-900">RM{{ number_format($cart->items->sum(fn($item) => $item->price * $item->quantity) * 0.08, 2) }}</span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-zinc-900">Total</span>
                                    <span class="font-bold text-lg text-zinc-900">
                                        RM{{ number_format($cart->items->sum(fn($item) => $item->price * $item->quantity) + 5 + ($cart->items->sum(fn($item) => $item->price * $item->quantity) * 0.08), 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('checkout') }}"
                           class="w-full bg-zinc-900 text-white py-3 px-4 rounded-md font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                            <i data-lucide="lock" class="w-4 h-4"></i>
                            Proceed to Checkout
                        </a>

                        <a href="{{ route('shop.index') }}"
                           class="w-full mt-3 py-3 px-4 rounded-md font-medium hover:bg-zinc-100 transition-colors border border-zinc-200 flex items-center justify-center gap-2">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-16">
                <i data-lucide="shopping-cart" class="w-16 h-16 mx-auto text-zinc-300 mb-4"></i>
                <h3 class="text-lg font-semibold text-zinc-900 mb-2">Your cart is empty</h3>
                <p class="text-sm text-zinc-600 mb-6">Add some vintage items to get started</p>
                <a href="{{ route('shop.index') }}"
                   class="bg-zinc-900 text-white py-2 px-6 rounded-md font-medium hover:bg-zinc-800 transition-colors">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <script>
        lucide.createIcons();

        async function removeFromCart(itemId) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                return;
            }

            try {
                const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
                    itemElement.remove();

                    // Update cart count immediately
                    const cartCountElement = document.getElementById('cart-count');
                    if (cartCountElement) {
                        cartCountElement.textContent = data.cart_count || '0';
                        cartCountElement.style.display = (data.cart_count || 0) > 0 ? 'flex' : 'none';
                    }

                    // Refresh page to update totals
                    setTimeout(() => {
                        window.location.reload();
                    }, 300);
                } else {
                    alert('Failed to remove item from cart');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Something went wrong');
            }
        }

        // Update cart count on page load
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

        // Update cart count after changes
        window.addEventListener('cartUpdated', updateCartCount);
    </script>
</body>
</html>