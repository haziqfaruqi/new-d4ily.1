<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers Management - d4ily.1</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-zinc-50">
    <div class="flex h-screen">
        @include('admin.partials.sidebar', ['active' => 'customers'])

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-8">
                <h1 class="text-sm font-medium text-zinc-900">Customers Management</h1>
            </header>

            <div class="p-8">
                <!-- Customers Table -->
                <div class="bg-white rounded-lg border border-zinc-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-5 py-3 text-left font-medium">Customer</th>
                                    <th class="px-5 py-3 text-left font-medium">Email</th>
                                    <th class="px-5 py-3 text-left font-medium">Interactions</th>
                                    <th class="px-5 py-3 text-left font-medium">Joined</th>
                                    <th class="px-5 py-3 text-right font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($customers as $customer)
                                    <tr class="hover:bg-zinc-50">
                                        <td class="px-5 py-3">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-xs font-bold text-indigo-700">
                                                    {{ substr($customer->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="font-medium text-zinc-900">{{ $customer->name }}</p>
                                                    <p class="text-zinc-500">ID: {{ $customer->id }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-5 py-3 text-zinc-700">{{ $customer->email }}</td>
                                        <td class="px-5 py-3">
                                            <span
                                                class="px-2 py-0.5 rounded bg-zinc-100 text-zinc-700 text-xs font-medium">
                                                {{ $customer->interactions_count }} views
                                            </span>
                                        </td>
                                        <td class="px-5 py-3 text-zinc-500">{{ $customer->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-5 py-3 text-right">
                                            <button onclick='viewCustomer(@json($customer))'
                                                class="text-zinc-600 hover:text-zinc-900">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-8 text-center text-zinc-500">No customers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 border-t border-zinc-100">
                        {{ $customers->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Customer Details Modal -->
    <div id="customerModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-lg w-full">
            <div class="p-6 border-b border-zinc-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-zinc-900">Customer Details</h2>
                <button onclick="closeModal()" class="text-zinc-400 hover:text-zinc-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div id="customerDetails" class="p-6">
                <!-- Customer details will be populated here -->
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function viewCustomer(customer) {
            const details = `
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <div class="h-16 w-16 rounded-full bg-indigo-100 flex items-center justify-center text-2xl font-bold text-indigo-700">
                            ${customer.name.charAt(0)}
                        </div>
                        <div>
                            <p class="text-lg font-semibold text-zinc-900">${customer.name}</p>
                            <p class="text-sm text-zinc-500">${customer.email}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t">
                        <div>
                            <p class="text-xs text-zinc-500">Customer ID</p>
                            <p class="text-sm font-medium">#${customer.id}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Role</p>
                            <p class="text-sm font-medium capitalize">${customer.role}</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Total Interactions</p>
                            <p class="text-sm font-medium">${customer.interactions_count} views</p>
                        </div>
                        <div>
                            <p class="text-xs text-zinc-500">Member Since</p>
                            <p class="text-sm font-medium">${new Date(customer.created_at).toLocaleDateString()}</p>
                        </div>
                    </div>
                </div>
            `;

            document.getElementById('customerDetails').innerHTML = details;
            document.getElementById('customerModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('customerModal').classList.add('hidden');
        }
    </script>
</body>

</html>