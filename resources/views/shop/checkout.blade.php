<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - D4ily.1</title>
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

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-zinc-900">Checkout</h1>
            <p class="text-base text-zinc-600 mt-2">Review your order and enter your details</p>
        </div>

        <form method="POST" action="{{ route('checkout.submit') }}" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            <!-- Checkout Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg border border-zinc-200 p-6 space-y-6">
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 rounded-md p-4">
                            <p class="text-red-800 text-sm">{{ session('error') }}</p>
                        </div>
                    @endif

                    <!-- Shipping Address -->
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-5 h-5"></i>
                            Shipping Address
                        </h3>
                        <textarea name="shipping_address"
                                  placeholder="Enter your full shipping address including street, city, state, and zip code"
                                  class="w-full px-4 py-3 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors"
                                  rows="4"
                                  required>{{ old('shipping_address') }}</textarea>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Payment Method
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-50">
                                <input type="radio" name="payment_method" value="credit_card" class="text-zinc-600" required>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="credit-card" class="w-5 h-5"></i>
                                    <span class="font-medium">Credit Card</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-50">
                                <input type="radio" name="payment_method" value="paypal" class="text-zinc-600">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="wallet" class="w-5 h-5"></i>
                                    <span class="font-medium">PayPal</span>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-4 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-50">
                                <input type="radio" name="payment_method" value="bank_transfer" class="text-zinc-600">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="building-2" class="w-5 h-5"></i>
                                    <span class="font-medium">Bank Transfer</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Order Items Summary -->
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                            <i data-lucide="package" class="w-5 h-5"></i>
                            Order Items
                        </h3>
                        <div class="space-y-3">
                            @foreach($cart->items as $item)
                                <div class="flex items-center justify-between p-3 bg-zinc-50 rounded-md">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/60' }}"
                                             alt="{{ $item->product->name }}"
                                             class="w-12 h-16 object-cover rounded border border-zinc-200">
                                        <div>
                                            <h4 class="font-medium text-zinc-900 text-sm">{{ $item->product->name }}</h4>
                                            <p class="text-xs text-zinc-500">{{ $item->product->brand }}</p>
                                            <p class="text-xs text-zinc-500">{{ $item->quantity }} × RM{{ number_format($item->price, 2) }}</p>
                                        </div>
                                    </div>
                                    <span class="font-medium text-zinc-900">RM{{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg border border-zinc-200 p-6 sticky top-24">
                    <h2 class="text-lg font-semibold text-zinc-900 mb-4">Order Summary</h2>

                    <!-- Debug: Check if cart items are loaded -->
                    @if(config('app.debug'))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3 mb-4">
                            <p class="text-xs text-yellow-800">Cart items: {{ $cart->items->count() }}</p>
                            @if($cart->items->count() > 0)
                                <p class="text-xs text-yellow-800">First item price: {{ $cart->items->first()->price }}</p>
                                <p class="text-xs text-yellow-800">First item product: {{ $cart->items->first()->product->name }}</p>
                            @endif
                        </div>
                    @endif

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

                    <button type="submit"
                            class="w-full bg-zinc-900 text-white py-3 px-4 rounded-md font-medium hover:bg-zinc-800 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        Place Order
                    </button>

                    <a href="{{ route('cart.index') }}"
                       class="w-full mt-3 py-3 px-4 rounded-md font-medium hover:bg-zinc-100 transition-colors border border-zinc-200 flex items-center justify-center gap-2">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back to Cart
                    </a>
                </div>
            </div>
        </form>
    </div>

    <script>
        lucide.createIcons();

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
    </script>
</body>
</html>