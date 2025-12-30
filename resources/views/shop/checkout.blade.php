<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - D4ily.1</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">Full Name</label>
                                <input type="text" name="shipping_name"
                                    placeholder="Enter your full name"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm"
                                    required value="{{ old('shipping_name', auth()->user()->name ?? '') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">Phone Number</label>
                                <input type="tel" name="shipping_phone"
                                    placeholder="e.g., 012-3456789"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm"
                                    required value="{{ old('shipping_phone', auth()->user()->phone ?? '') }}">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-zinc-700 mb-1">Street Address</label>
                                <input type="text" name="shipping_street"
                                    placeholder="House/Unit number, Street name"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm"
                                    required value="{{ old('shipping_street') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">City</label>
                                <input type="text" name="shipping_city"
                                    placeholder="e.g., Kuala Lumpur"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm"
                                    required value="{{ old('shipping_city') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">Postcode</label>
                                <input type="text" name="shipping_postcode"
                                    placeholder="e.g., 50000"
                                    pattern="[0-9]{5}"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm"
                                    required value="{{ old('shipping_postcode') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">State</label>
                                <select name="shipping_state"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm bg-white"
                                    required>
                                    <option value="">Select State</option>
                                    <option value="Johor" {{ old('shipping_state') == 'Johor' ? 'selected' : '' }}>Johor</option>
                                    <option value="Kedah" {{ old('shipping_state') == 'Kedah' ? 'selected' : '' }}>Kedah</option>
                                    <option value="Kelantan" {{ old('shipping_state') == 'Kelantan' ? 'selected' : '' }}>Kelantan</option>
                                    <option value="Melaka" {{ old('shipping_state') == 'Melaka' ? 'selected' : '' }}>Melaka</option>
                                    <option value="Negeri Sembilan" {{ old('shipping_state') == 'Negeri Sembilan' ? 'selected' : '' }}>Negeri Sembilan</option>
                                    <option value="Pahang" {{ old('shipping_state') == 'Pahang' ? 'selected' : '' }}>Pahang</option>
                                    <option value="Penang" {{ old('shipping_state') == 'Penang' ? 'selected' : '' }}>Penang</option>
                                    <option value="Perak" {{ old('shipping_state') == 'Perak' ? 'selected' : '' }}>Perak</option>
                                    <option value="Perlis" {{ old('shipping_state') == 'Perlis' ? 'selected' : '' }}>Perlis</option>
                                    <option value="Sabah" {{ old('shipping_state') == 'Sabah' ? 'selected' : '' }}>Sabah</option>
                                    <option value="Sarawak" {{ old('shipping_state') == 'Sarawak' ? 'selected' : '' }}>Sarawak</option>
                                    <option value="Selangor" {{ old('shipping_state') == 'Selangor' ? 'selected' : '' }}>Selangor</option>
                                    <option value="Terengganu" {{ old('shipping_state') == 'Terengganu' ? 'selected' : '' }}>Terengganu</option>
                                    <option value="Kuala Lumpur" {{ old('shipping_state') == 'Kuala Lumpur' ? 'selected' : '' }}>Kuala Lumpur</option>
                                    <option value="Labuan" {{ old('shipping_state') == 'Labuan' ? 'selected' : '' }}>Labuan</option>
                                    <option value="Putrajaya" {{ old('shipping_state') == 'Putrajaya' ? 'selected' : '' }}>Putrajaya</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-zinc-700 mb-1">Country</label>
                                <input type="text" name="shipping_country"
                                    value="Malaysia"
                                    class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm bg-zinc-100"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <h3 class="text-lg font-semibold text-zinc-900 mb-4 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-5 h-5"></i>
                            Payment Method
                        </h3>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 p-4 border border-zinc-200 rounded-md cursor-pointer hover:bg-zinc-50">
                                <input type="radio" name="payment_method" value="toyyibpay" class="text-zinc-600" required>
                                <div class="flex items-center gap-2">
                                    <i data-lucide="wallet" class="w-5 h-5"></i>
                                    <span class="font-medium">ToyyibPay (Online Payment)</span>
                                    <span class="text-xs text-green-600 bg-green-50 px-2 py-1 rounded">Recommended</span>
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
                            class="w-full bg-purple-600 text-white py-3 px-4 rounded-md font-medium hover:bg-purple-700 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="lock" class="w-4 h-4"></i>
                        <span id="submit-text">Place Order</span>
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
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const response = await fetch('/api/cart', {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                });
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

        // Handle form submission for ToyyibPay
        document.querySelector('form').addEventListener('submit', async function(e) {
            const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked');
            if (selectedPaymentMethod && selectedPaymentMethod.value === 'toyyibpay') {
                e.preventDefault();
                const submitText = document.getElementById('submit-text');
                const button = e.target.querySelector('button[type="submit"]');

                // Show loading state
                submitText.textContent = 'Processing...';
                button.disabled = true;
                button.classList.add('opacity-75', 'cursor-not-allowed');

                // Continue with normal form submission (Laravel handles CSRF automatically)
                this.submit();
            }
        });
    </script>
</body>
</html>