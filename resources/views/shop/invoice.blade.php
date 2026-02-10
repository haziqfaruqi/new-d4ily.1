<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }} - D4ily.1</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-break {
                page-break-inside: avoid;
            }
            body {
                background: white !important;
            }
            .invoice-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8">

    <!-- Action Buttons (No Print) -->
    <div class="no-print max-w-4xl mx-auto mb-4 px-4 flex justify-end gap-3">
        <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-3 bg-zinc-900 text-white font-medium rounded-lg hover:bg-zinc-800 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            Print Invoice
        </button>
        <a href="{{ route('order.history') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-zinc-700 font-medium rounded-lg hover:bg-zinc-50 transition-colors border border-zinc-300">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
            Back to Orders
        </a>
    </div>

    <!-- Invoice Container -->
    <div class="invoice-container max-w-4xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-zinc-900 to-zinc-800 px-8 py-6">
            <div class="flex justify-between items-start">
                <div class="text-white">
                    <h1 class="text-3xl font-bold">INVOICE</h1>
                    <p class="text-zinc-300 mt-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="text-right">
                    <img src="{{ asset('logo/logo.png') }}" alt="D4ily.1" class="h-16 w-auto mb-2">
                    <p class="text-white text-sm font-medium">Vintage Thrift Shop</p>
                </div>
            </div>
        </div>

        <!-- Invoice Content -->
        <div class="px-8 py-8">
            <!-- Order Info Grid -->
            <div class="grid grid-cols-2 gap-8 mb-8 print-break">
                <!-- From -->
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-3">From</h3>
                    <div class="text-zinc-900">
                        <p class="font-semibold text-lg">D4ily.1</p>
                        <p class="text-zinc-600">Vintage Thrift Shop</p>
                        <p class="text-zinc-600 mt-2">Email: d4ily.1@gmail.com</p>
                    </div>
                </div>
                <!-- To -->
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-3">Bill To</h3>
                    <div class="text-zinc-900">
                        <p class="font-semibold text-lg">{{ $order->user->name }}</p>
                        <p class="text-zinc-600">{{ $order->user->email }}</p>
                        @if($order->user->phone)
                            <p class="text-zinc-600">{{ $order->user->phone }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="grid grid-cols-3 gap-8 mb-8 print-break">
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-2">Order Date</h3>
                    <p class="text-zinc-900 font-medium">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-2">Order Status</h3>
                    <?php
                        $statusColors = [
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'processing' => 'bg-blue-100 text-blue-800',
                            'shipped' => 'bg-purple-100 text-purple-800',
                            'delivered' => 'bg-green-100 text-green-800',
                            'cancelled' => 'bg-red-100 text-red-800',
                            'confirmed' => 'bg-emerald-100 text-emerald-800'
                        ];
                        $statusClass = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="inline-block px-3 py-1 text-sm font-medium rounded-full {{ $statusClass }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-2">Payment Method</h3>
                    <p class="text-zinc-900 font-medium">
                        @if($order->payment_method === 'toyyibpay')
                            ToyyibPay
                        @elseif($order->payment_method === 'bank_transfer')
                            Bank Transfer
                        @else
                            {{ ucfirst($order->payment_method) }}
                        @endif
                    </p>
                    @if($order->payment_method === 'toyyibpay' && $order->bill_code)
                        <p class="text-zinc-600 text-sm">Bill Code: {{ $order->bill_code }}</p>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="mb-8 print-break">
                <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-3">Shipping Address</h3>
                @php($shippingAddress = json_decode($order->shipping_address, true))
                @if($shippingAddress)
                    <div class="bg-zinc-50 rounded-lg px-4 py-3">
                        <p class="font-medium text-zinc-900">{{ $shippingAddress['name'] ?? '' }}</p>
                        <p class="text-zinc-600">{{ $shippingAddress['street'] ?? '' }}</p>
                        <p class="text-zinc-600">{{ ($shippingAddress['city'] ?? '') . ' ' . ($shippingAddress['postcode'] ?? '') }}</p>
                        <p class="text-zinc-600">{{ $shippingAddress['state'] ?? '' }}</p>
                        <p class="text-zinc-600">{{ $shippingAddress['country'] ?? 'Malaysia' }}</p>
                        <p class="text-zinc-600 mt-1">{{ $shippingAddress['phone'] ?? '' }}</p>
                    </div>
                @endif
            </div>

            <!-- Order Items Table -->
            <div class="mb-8 print-break">
                <h3 class="text-sm font-semibold text-zinc-500 uppercase tracking-wide mb-4">Order Items</h3>
                <div class="border border-zinc-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-zinc-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-zinc-600 uppercase tracking-wider">Item</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-zinc-600 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-600 uppercase tracking-wider">Price</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-zinc-600 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $item->product->images[0] ?? 'https://via.placeholder.com/60' }}"
                                                 alt="{{ $item->product->name }}"
                                                 class="w-12 h-12 object-cover rounded border border-zinc-200">
                                            <div>
                                                <p class="font-medium text-zinc-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-zinc-500">{{ $item->product->brand ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-4 text-center text-zinc-600">{{ $item->quantity }}</td>
                                    <td class="px-4 py-4 text-right text-zinc-600">RM{{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 py-4 text-right font-medium text-zinc-900">RM{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Totals -->
            <div class="flex justify-end print-break">
                <div class="w-full max-w-xs">
                    <div class="space-y-3">
                        <div class="flex justify-between text-zinc-600">
                            <span>Subtotal</span>
                            <span>RM{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Shipping</span>
                            <span>RM{{ number_format($order->shipping, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-zinc-600">
                            <span>Tax</span>
                            <span>RM{{ number_format($order->tax, 2) }}</span>
                        </div>
                        <div class="border-t border-zinc-200 pt-3 flex justify-between">
                            <span class="text-lg font-bold text-zinc-900">Total</span>
                            <span class="text-lg font-bold text-zinc-900">RM{{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="mt-8 pt-6 border-t border-zinc-200">
                <p class="text-center text-sm text-zinc-500">
                    Thank you for your purchase! If you have any questions, please contact us at d4ily.1@gmail.com
                </p>
                <p class="text-center text-xs text-zinc-400 mt-2">
                    Generated on {{ $order->created_at->format('M d, Y \a\t g:i A') }}
                </p>
            </div>
        </div>
    </div>

</body>
</html>
