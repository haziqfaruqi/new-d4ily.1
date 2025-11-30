<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Management - d4ily.1</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-zinc-50">
    <div class="flex h-screen">
        @include('admin.partials.sidebar', ['active' => 'inventory'])

        <!-- Main Content -->
        <main class="flex-1 overflow-auto">
            <header class="h-16 bg-white border-b border-zinc-200 flex items-center justify-between px-8">
                <h1 class="text-base font-medium text-zinc-900">Inventory Management</h1>
                <button onclick="openAddModal()" class="px-4 py-2 text-sm font-medium bg-zinc-900 text-white rounded-md hover:bg-zinc-800 flex items-center gap-2">
                    <i data-lucide="plus" class="w-4 h-4"></i>
                    Add Product
                </button>
            </header>

            <div class="p-8">
                @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200">
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
                @endif

                <!-- Products Table -->
                <div class="bg-white rounded-lg border border-zinc-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-5 py-3 text-left font-medium">Product</th>
                                    <th class="px-5 py-3 text-left font-medium">Category</th>
                                    <th class="px-5 py-3 text-left font-medium">Price</th>
                                    <th class="px-5 py-3 text-left font-medium">Stock</th>
                                    <th class="px-5 py-3 text-left font-medium">Condition</th>
                                    <th class="px-5 py-3 text-right font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($products as $product)
                                <tr class="hover:bg-zinc-50">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/40' }}" class="w-12 h-12 rounded object-cover">
                                            <div>
                                                <p class="font-medium text-zinc-900">{{ $product->name }}</p>
                                                <p class="text-zinc-500 text-sm">{{ $product->brand }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3 text-zinc-700">{{ $product->category->name }}</td>
                                    <td class="px-5 py-3 font-medium text-zinc-900">RM{{ number_format($product->price, 2) }}</td>
                                    <td class="px-5 py-3">
                                        <span class="px-2 py-1 rounded text-xs font-medium {{ $product->stock > 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                            {{ $product->stock }} units
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-zinc-700 capitalize">{{ $product->condition }}</td>
                                    <td class="px-5 py-3 text-right">
                                        <button onclick='editProduct(@json($product))' class="text-zinc-600 hover:text-zinc-900 mr-2">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </button>
                                        <form action="{{ route('admin.inventory.delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-8 text-center text-zinc-500">No products found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-4 border-t border-zinc-100">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Modal -->
    <div id="productModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-zinc-200">
                <h2 id="modalTitle" class="text-lg font-semibold text-zinc-900">Add Product</h2>
            </div>
            <form id="productForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="productId" name="_method" value="">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Product Name</label>
                        <input type="text" name="name" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Brand</label>
                        <input type="text" name="brand" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Description</label>
                    <textarea name="description" rows="3" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900"></textarea>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Category</label>
                        <select name="category_id" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Price</label>
                        <input type="number" name="price" step="0.01" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Stock</label>
                        <input type="number" name="stock" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Condition</label>
                        <select name="condition" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            <option value="like new">Like New</option>
                            <option value="good">Good</option>
                            <option value="fair">Fair</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Size</label>
                        <input type="text" name="size" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Color</label>
                        <input type="text" name="color" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Product Image</label>
                    <div class="flex items-center gap-4">
                        <div id="imagePreview" class="hidden w-24 h-24 rounded-lg border border-zinc-200 overflow-hidden">
                            <img id="previewImg" src="" class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1">
                            <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(this)" class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            <p class="text-xs text-zinc-500 mt-1">Upload JPG, PNG or WebP (max 2MB)</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" name="featured" id="featured" class="rounded border-zinc-300">
                    <label for="featured" class="text-sm text-zinc-700">Featured Product</label>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 px-4 py-2 text-sm bg-zinc-900 text-white rounded-md hover:bg-zinc-800">Save Product</button>
                    <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm border border-zinc-300 rounded-md hover:bg-zinc-50">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productForm').action = '{{ route("admin.inventory.store") }}';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('productModal').classList.remove('hidden');
        }

        function editProduct(product) {
            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productForm').action = `/admin/inventory/${product.id}`;
            document.getElementById('productId').value = 'PUT';
            
            const form = document.getElementById('productForm');
            form.querySelector('[name="name"]').value = product.name;
            form.querySelector('[name="brand"]').value = product.brand;
            form.querySelector('[name="description"]').value = product.description;
            form.querySelector('[name="category_id"]').value = product.category_id;
            form.querySelector('[name="price"]').value = product.price;
            form.querySelector('[name="stock"]').value = product.stock;
            form.querySelector('[name="condition"]').value = product.condition;
            form.querySelector('[name="size"]').value = product.size;
            form.querySelector('[name="color"]').value = product.color;
            form.querySelector('[name="featured"]').checked = product.featured;
            
            // Show existing image
            if (product.images && product.images[0]) {
                document.getElementById('previewImg').src = product.images[0];
                document.getElementById('imagePreview').classList.remove('hidden');
            }
            
            document.getElementById('productModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }
    </script>
</body>
</html>