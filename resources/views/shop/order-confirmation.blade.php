<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Order Confirmation - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50">
@include('partials.navigation')

    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        @if($order->payment_method === 'toyyibpay')
        <!-- Success Banner -->
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-lg p-6 mb-8">
            <div class="flex items-center justify-center gap-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full">
                    <i data-lucide="check-circle" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2">Payment Successful!</h1>
                    <p class="text-purple-100">Thank you for your purchase. Payment processed via ToyyibPay.</p>
                </div>
            </div>
        </div>
        @else
        <!-- Order Confirmation Banner -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg p-6 mb-8">
            <div class="flex items-center justify-center gap-4 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full">
                    <i data-lucide="shopping-bag" class="w-8 h-8"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-bold mb-2">Order Confirmed!</h1>
                    <p class="text-green-100">Thank you for your purchase. Please complete bank transfer payment.</p>
                </div>
            </div>
        </div>
        @endif

        <div class="text-center mb-12">
            <h2 class="text-2xl font-bold text-zinc-900 mb-2">Order Confirmed</h2>
            <p class="text-lg text-zinc-600">Your order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} has been placed successfully.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
            <!-- Order Details -->
            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                    <i data-lucide="receipt" class="w-5 h-5"></i>
                    Order Details
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Order Number</span>
                        <span class="font-medium text-zinc-900">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Order Date</span>
                        <span class="font-medium text-zinc-900">{{ $order->created_at->format('F j, Y') }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Status</span>
                        @if($order->status === 'completed' && $order->payment_status === 'paid')
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Paid</span>
                        @else
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Processing</span>
                        @endif
                    </div>

                    <div class="flex justify-between items-center pb-4 border-b">
                        <span class="text-sm text-zinc-600">Payment Method</span>
                        <span class="font-medium text-zinc-900">
                            @if($order->payment_method === 'toyyibpay')
                                ToyyibPay
                            @else
                                Bank Transfer
                            @endif
                        </span>
                    </div>

                    @if($order->payment_method === 'toyyibpay')
                        @if($order->bill_code)
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-sm text-zinc-600">Payment Reference</span>
                            <span class="font-mono text-sm text-purple-600">{{ $order->bill_code }}</span>
                        </div>
                        @endif

                        @if($order->transaction_id)
                        <div class="flex justify-between items-center pb-4 border-b">
                            <span class="text-sm text-zinc-600">Transaction ID</span>
                            <span class="font-mono text-sm text-zinc-900">{{ $order->transaction_id }}</span>
                        </div>
                        @endif
                    @else
                        <!-- Bank Transfer Details -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mt-4">
                            <h4 class="font-semibold text-blue-900 mb-3 flex items-center gap-2">
                                <i data-lucide="building-2" class="w-4 h-4"></i>
                                Bank Transfer Details
                            </h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-medium text-blue-700">Bank Name:</span>
                                    <span class="text-blue-900">Maybank</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-blue-700">Account Name:</span>
                                    <span class="text-blue-900">D4ily Thrift Shop</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium text-blue-700">Account Number:</span>
                                    <span class="text-blue-900 font-mono">1234567890123</span>
                                </div>
                            </div>
                            @if($order->payment_proof)
                            <div class="mt-4 pt-4 border-t border-blue-200">
                                <p class="text-sm text-blue-900 font-medium mb-2">
                                    <i data-lucide="check-circle" class="w-4 h-4 inline mr-1"></i>
                                    Payment Receipt Uploaded
                                </p>
                                <a href="{{ asset($order->payment_proof) }}" target="_blank"
                                   class="text-sm text-blue-600 hover:text-blue-800 underline flex items-center gap-1">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    View Uploaded Receipt
                                </a>
                            </div>
                            @endif
                            <p class="text-xs text-blue-700 mt-3">
                                <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
                                Your order will be processed once the payment is verified.
                            </p>
                        </div>
                    @endif

                    <div class="pb-4 border-b">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm text-zinc-600">Shipping Address</span>
                            @if(in_array($order->status, ['processing', 'pending', 'confirmed']))
                                <button onclick="openEditAddressModal()" class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                    <i data-lucide="edit-2" class="w-3 h-3"></i>
                                    Edit
                                </button>
                            @endif
                        </div>
                        <div class="text-zinc-900">
                            @php
                                // Parse the JSON-encoded shipping address
                                $addressData = json_decode($order->shipping_address, true);

                                // Handle legacy comma-separated format for old orders
                                if (!$addressData) {
                                    $parts = explode(', ', $order->shipping_address);

                                    // Handle case where street address contains commas
                                    // Expected format: name, phone, street, city, postcode, state, country
                                    // But if street has comma: name, phone, street_part1, street_part2, city, postcode, state, country
                                    $partCount = count($parts);

                                    if ($partCount === 8) {
                                        // Street had a comma - merge parts 2 and 3
                                        $addressData = [
                                            'name' => $parts[0] ?? '',
                                            'phone' => $parts[1] ?? '',
                                            'street' => ($parts[2] ?? '') . ', ' . ($parts[3] ?? ''),
                                            'city' => $parts[4] ?? '',
                                            'postcode' => $parts[5] ?? '',
                                            'state' => $parts[6] ?? '',
                                            'country' => $parts[7] ?? 'Malaysia',
                                        ];
                                    } elseif ($partCount === 7) {
                                        // Standard format without comma in street
                                        $addressData = [
                                            'name' => $parts[0] ?? '',
                                            'phone' => $parts[1] ?? '',
                                            'street' => $parts[2] ?? '',
                                            'city' => $parts[3] ?? '',
                                            'postcode' => $parts[4] ?? '',
                                            'state' => $parts[5] ?? '',
                                            'country' => $parts[6] ?? 'Malaysia',
                                        ];
                                    } else {
                                        // Fallback for any other format
                                        $addressData = [
                                            'name' => $parts[0] ?? '',
                                            'phone' => $parts[1] ?? '',
                                            'street' => $parts[2] ?? '',
                                            'city' => $parts[3] ?? '',
                                            'postcode' => $parts[4] ?? '',
                                            'state' => $parts[5] ?? '',
                                            'country' => 'Malaysia',
                                        ];
                                    }
                                }

                                $name = $addressData['name'] ?? '';
                                $phone = $addressData['phone'] ?? '';
                                $street = $addressData['street'] ?? '';
                                $city = $addressData['city'] ?? '';
                                $postcode = $addressData['postcode'] ?? '';
                                $state = $addressData['state'] ?? '';
                                $country = $addressData['country'] ?? 'Malaysia';
                            @endphp

                            <p class="font-medium text-base mb-1">{{ $name }}</p>
                            <p class="text-sm text-zinc-600 mb-3">{{ $phone }}</p>
                            <p class="text-sm">{{ $street }}</p>
                            <p class="text-sm">{{ $city }}, {{ $postcode }} {{ $state }}</p>
                            <p class="text-sm text-zinc-600">{{ $country }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-lg border border-zinc-200 p-6">
                <h2 class="text-xl font-semibold text-zinc-900 mb-6 flex items-center gap-2">
                    <i data-lucide="calculator" class="w-5 h-5"></i>
                    Order Summary
                </h2>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Subtotal</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Shipping</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->shipping, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-zinc-600">Tax</span>
                        <span class="font-medium text-zinc-900">RM{{ number_format($order->tax, 2) }}</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="font-semibold text-zinc-900">Total Paid</span>
                            <span class="font-bold text-lg text-zinc-900">RM{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div>
                    <h3 class="text-lg font-semibold text-zinc-900 mb-4">Order Items ({{ $order->items->count() }})</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-3 p-3 bg-zinc-50 rounded-md">
                                <img src="{{ asset($item->product->images[0] ?? '') ?: 'https://via.placeholder.com/60x80?text=No+Image' }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-12 h-16 object-cover rounded border border-zinc-200">
                                <div class="flex-1">
                                    <h4 class="font-medium text-zinc-900 text-sm">{{ $item->product->name }}</h4>
                                    <p class="text-xs text-zinc-500">{{ $item->product->brand }}</p>
                                    <p class="text-xs text-zinc-500">{{ $item->quantity }} × RM{{ number_format($item->price, 2) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- @if($order->payment_method === 'toyyibpay' && $order->payment_status !== 'paid')
            <!-- ToyyibPay Manual Return Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
                    <div>
                        <h3 class="font-medium text-blue-900 mb-1">Payment Instructions</h3>
                        <p class="text-sm text-blue-800">
                            If you're not automatically redirected back after completing your payment, please click the button below to return to your order confirmation.
                        </p>
                        <a href="{{ route('order.confirmation', $order->id) }}"
                           class="inline-flex items-center gap-2 mt-2 px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Return to Order Confirmation
                        </a>
                    </div>
                </div>
            </div>
        @endif --}}

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-12 justify-center">
            <a href="{{ route('shop.index') }}"
               class="flex items-center justify-center gap-2 px-6 py-3 bg-zinc-900 text-white rounded-md font-medium hover:bg-zinc-800 transition-colors">
                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                Continue Shopping
            </a>
            <a href="{{ route('order.history') }}"
               class="flex items-center justify-center gap-2 px-6 py-3 border border-zinc-300 text-zinc-700 rounded-md font-medium hover:bg-zinc-50 transition-colors">
                <i data-lucide="clock" class="w-4 h-4"></i>
                View Order History
            </a>
        </div>
    </div>

    {{-- Edit Address Modal --}}
    @if(in_array($order->status, ['processing', 'pending', 'confirmed']))
    <div id="editAddressModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-lg max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-zinc-900">Edit Shipping Address</h3>
                <button onclick="closeEditAddressModal()" class="text-zinc-400 hover:text-zinc-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="editAddressForm" class="space-y-4">
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Full Name</label>
                    <input type="text" name="shipping_name" id="edit_name" required
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Phone Number</label>
                    <input type="tel" name="shipping_phone" id="edit_phone" required
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">Street Address</label>
                    <input type="text" name="shipping_street" id="edit_street" required
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">City</label>
                        <input type="text" name="shipping_city" id="edit_city" required
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-1">Postcode</label>
                        <input type="text" name="shipping_postcode" id="edit_postcode" required
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-1">State</label>
                    <select name="shipping_state" id="edit_state" required
                        class="w-full px-4 py-2.5 border border-zinc-200 rounded-md focus:outline-none focus:border-zinc-300 focus:ring-0 transition-colors text-sm bg-white">
                        <option value="">Select State</option>
                        <option value="Johor">Johor</option>
                        <option value="Kedah">Kedah</option>
                        <option value="Kelantan">Kelantan</option>
                        <option value="Melaka">Melaka</option>
                        <option value="Negeri Sembilan">Negeri Sembilan</option>
                        <option value="Pahang">Pahang</option>
                        <option value="Penang">Penang</option>
                        <option value="Perak">Perak</option>
                        <option value="Perlis">Perlis</option>
                        <option value="Sabah">Sabah</option>
                        <option value="Sarawak">Sarawak</option>
                        <option value="Selangor">Selangor</option>
                        <option value="Terengganu">Terengganu</option>
                        <option value="Kuala Lumpur">Kuala Lumpur</option>
                        <option value="Labuan">Labuan</option>
                        <option value="Putrajaya">Putrajaya</option>
                    </select>
                </div>

                <div id="formError" class="hidden bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-sm text-red-800" id="formErrorText"></p>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditAddressModal()"
                        class="flex-1 px-4 py-2.5 border border-zinc-200 text-zinc-700 rounded-md font-medium hover:bg-zinc-50 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2.5 bg-indigo-600 text-white rounded-md font-medium hover:bg-indigo-700 transition-colors">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        // Ensure DOM is fully loaded before creating icons
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });

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

        // Edit Address Modal functionality
        const modal = document.getElementById('editAddressModal');
        @php
            $decodedAddress = json_decode($order->shipping_address, true);

            // Handle legacy comma-separated format for old orders
            if (!$decodedAddress) {
                $parts = explode(', ', $order->shipping_address);
                $partCount = count($parts);

                if ($partCount === 8) {
                    // Street had a comma - merge parts 2 and 3
                    $decodedAddress = [
                        'name' => $parts[0] ?? '',
                        'phone' => $parts[1] ?? '',
                        'street' => ($parts[2] ?? '') . ', ' . ($parts[3] ?? ''),
                        'city' => $parts[4] ?? '',
                        'postcode' => $parts[5] ?? '',
                        'state' => $parts[6] ?? '',
                        'country' => $parts[7] ?? 'Malaysia',
                    ];
                } elseif ($partCount === 7) {
                    // Standard format without comma in street
                    $decodedAddress = [
                        'name' => $parts[0] ?? '',
                        'phone' => $parts[1] ?? '',
                        'street' => $parts[2] ?? '',
                        'city' => $parts[3] ?? '',
                        'postcode' => $parts[4] ?? '',
                        'state' => $parts[5] ?? '',
                        'country' => $parts[6] ?? 'Malaysia',
                    ];
                } else {
                    // Fallback
                    $decodedAddress = [
                        'name' => $parts[0] ?? '',
                        'phone' => $parts[1] ?? '',
                        'street' => $parts[2] ?? '',
                        'city' => $parts[3] ?? '',
                        'postcode' => $parts[4] ?? '',
                        'state' => $parts[5] ?? '',
                        'country' => 'Malaysia',
                    ];
                }
            }
        @endphp
        const addressData = @json($decodedAddress);

        function openEditAddressModal() {
            // Pre-fill form with current address
            document.getElementById('edit_name').value = addressData.name || '';
            document.getElementById('edit_phone').value = addressData.phone || '';
            document.getElementById('edit_street').value = addressData.street || '';
            document.getElementById('edit_city').value = addressData.city || '';
            document.getElementById('edit_postcode').value = addressData.postcode || '';
            document.getElementById('edit_state').value = addressData.state || '';

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            lucide.createIcons();
        }

        function closeEditAddressModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.getElementById('formError').classList.add('hidden');
        }

        // Handle form submission
        document.getElementById('editAddressForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const errorDiv = document.getElementById('formError');
            const errorText = document.getElementById('formErrorText');
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Saving...';
            errorDiv.classList.add('hidden');

            try {
                const response = await fetch('{{ route('order.update-shipping', $order->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    // Show success state on button
                    submitBtn.innerHTML = '<i data-lucide="check" class="w-4 h-4 inline"></i> Saved!';
                    lucide.createIcons();

                    // Close modal and reload after brief delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 800);
                } else {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                    errorText.textContent = data.error || 'Failed to update address. Please try again.';
                    errorDiv.classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error updating address:', error);
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
                errorText.textContent = 'An error occurred. Please try again.';
                errorDiv.classList.remove('hidden');
            }
        });

        // Close modal on outside click
        modal?.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeEditAddressModal();
            }
        });
    </script>
</body>
</html>