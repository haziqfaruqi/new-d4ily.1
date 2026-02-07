<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
            <header class="relative overflow-hidden px-8 py-8" style="background: linear-gradient(135deg, #a6af89 0%, #d5fdff 50%, #c53131 100%);">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div class="max-w-2xl">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="px-3 py-1 bg-white/30 backdrop-blur rounded-full">
                                    <span class="text-xs font-bold text-white">INVENTORY HUB</span>
                                </div>
                                <div class="flex items-center gap-1">
                                    <div class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></div>
                                    <span class="text-xs text-white/80">Stock Levels</span>
                                </div>
                            </div>
                            <h1 class="text-3xl font-bold text-white mb-2">Product Command Center</h1>
                            <p class="text-sm text-white/80">Manage your thrift shop inventory and track stock levels</p>
                        </div>
                        <div class="hidden lg:flex items-center gap-4">
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">{{ $stats['total_products'] }}</p>
                                <p class="text-xs text-white/70">Total</p>
                            </div>
                            <div class="w-px h-12 bg-white/30"></div>
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">{{ $stats['available_products'] }}</p>
                                <p class="text-xs text-white/70">Available</p>
                            </div>
                            <div class="w-px h-12 bg-white/30"></div>
                            <div class="text-center px-6 py-3 bg-white/20 backdrop-blur rounded-xl">
                                <p class="text-2xl font-bold text-white">{{ $stats['sold_products'] }}</p>
                                <p class="text-xs text-white/70">Sold</p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button onclick="openAddModal()" class="px-6 py-3 text-sm font-bold bg-white text-stone-800 rounded-xl hover:bg-white/90 shadow-lg flex items-center gap-2 transition-all">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            Add New Product
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-8">
                @if(session('success'))
                <div class="mb-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center gap-2">
                    <i data-lucide="check-circle" class="w-5 h-5 text-emerald-600"></i>
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
                @endif

                @if(session('error'))
                <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-200 flex items-center gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-red-600"></i>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
                @endif

                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white rounded-lg border border-zinc-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Total Products</p>
                                <p class="text-2xl font-bold text-zinc-900 mt-1">{{ $stats['total_products'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-zinc-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Available</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['available_products'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="check-circle" class="w-6 h-6 text-emerald-600"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg border border-zinc-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-zinc-600">Sold</p>
                                <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['sold_products'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center">
                                <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Category Overview -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Quick Actions -->
                    <div class="bg-white rounded-lg border border-zinc-200 p-6">
                        <h3 class="text-base font-semibold text-zinc-900 mb-4">Quick Actions</h3>
                        <div class="space-y-3">
                            <button onclick="openAddModal()" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium bg-zinc-900 text-white rounded-lg hover:bg-zinc-800 transition-colors">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add New Product
                            </button>
                            <a href="{{ route('admin.orders') }}" class="block w-full flex items-center gap-3 px-4 py-3 text-sm font-medium bg-zinc-100 text-zinc-700 rounded-lg hover:bg-zinc-200 transition-colors">
                                <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                                Manage Orders
                            </a>
                            <button onclick="exportInventory()" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium bg-zinc-100 text-zinc-700 rounded-lg hover:bg-zinc-200 transition-colors">
                                <i data-lucide="download" class="w-4 h-4"></i>
                                Export Inventory
                            </button>
                        </div>
                    </div>

                    <!-- Category Overview -->
                    <div class="lg:col-span-2 bg-white rounded-lg border border-zinc-200 p-6">
                        <h3 class="text-base font-semibold text-zinc-900 mb-4">Category Overview</h3>
                        <div class="space-y-3">
                            @foreach($stats['low_stock_categories'] as $category)
                            <div class="flex items-center justify-between p-3 bg-zinc-50 rounded-lg hover:bg-zinc-100 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="tag" class="w-5 h-5 text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-zinc-900">{{ $category['name'] }}</p>
                                        <p class="text-xs text-zinc-500">{{ $category['available'] }} available</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-zinc-900">{{ $category['count'] }}</p>
                                    <p class="text-xs text-zinc-500">total</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Recently Added -->
                @if(isset($recentProducts) && $recentProducts->count() > 0)
                <div class="bg-white rounded-lg border border-zinc-200 p-6 mb-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-base font-semibold text-zinc-900">Recently Added Products</h3>
                        <span class="text-xs text-zinc-500">Last 5 products</span>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        @foreach($recentProducts as $product)
                        <a href="{{ route('shop.product', $product->id) }}" target="_blank" class="group">
                            <div class="relative aspect-[3/4] overflow-hidden rounded-lg border border-zinc-200 bg-zinc-100 mb-2">
                                <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/300' }}"
                                     alt="{{ $product->name }}"
                                     class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                @if(!$product->is_available)
                                    <div class="absolute top-2 left-2 px-2 py-1 text-xs font-medium rounded bg-red-500 text-white">
                                        Sold
                                    </div>
                                @endif
                            </div>
                            <p class="text-xs font-medium text-zinc-900 group-hover:text-indigo-600 transition-colors line-clamp-1">{{ $product->name }}</p>
                            <p class="text-xs font-semibold text-zinc-900 mt-0.5">RM{{ number_format($product->price, 2) }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Search and Filters -->
                <div class="bg-white rounded-lg border border-zinc-200 p-4 mb-6">
                    <div class="flex flex-wrap gap-4 items-center">
                        <div class="flex-1 min-w-[250px]">
                            <div class="relative">
                                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400"></i>
                                <input type="text"
                                       id="searchInput"
                                       placeholder="Search products..."
                                       class="w-full pl-10 pr-4 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900 focus:border-transparent"
                                       onkeyup="filterTable()">
                            </div>
                        </div>
                        <select id="categoryFilter" onchange="filterTable()" class="px-4 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        <select id="statusFilter" onchange="filterTable()" class="px-4 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            <option value="">All Status</option>
                            <option value="available">Available</option>
                            <option value="sold">Sold</option>
                        </select>
                        <button onclick="clearFilters()" class="px-4 py-2 text-sm border border-zinc-300 rounded-md hover:bg-zinc-50 transition-colors">
                            Clear Filters
                        </button>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="bg-white rounded-lg border border-zinc-200">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-zinc-50 text-zinc-500">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium">Product</th>
                                    <th class="px-3 py-3 text-left font-medium">Category</th>
                                    <th class="px-3 py-3 text-left font-medium">Price</th>
                                    <th class="px-3 py-3 text-left font-medium">Condition</th>
                                    <th class="px-3 py-3 text-left font-medium">Status</th>
                                    <th class="px-2 py-3 text-center font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($products as $product)
                                <tr class="hover:bg-zinc-50 {{ !$product->is_available ? 'bg-red-50/50' : '' }}">
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/40' }}" class="w-12 h-12 rounded object-cover {{ !$product->is_available ? 'opacity-50' : '' }}">
                                            <div>
                                                <p class="font-medium text-zinc-900">{{ $product->name }}</p>
                                                <p class="text-zinc-500 text-sm">{{ $product->brand }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-3 text-zinc-700" data-category-id="{{ $product->category_id }}">{{ $product->category->name }}</td>
                                    <td class="px-3 py-3 font-medium text-zinc-900">RM{{ number_format($product->price, 2) }}</td>
                                    <td class="px-3 py-3 text-zinc-700 capitalize text-xs">{{ $product->condition }}</td>
                                    <td class="px-3 py-3">
                                        @if($product->is_available)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-2 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <button onclick="toggleAvailability({{ $product->id }}, {{ $product->is_available ? 'false' : 'true' }})" class="p-1.5 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 rounded" title="{{ $product->is_available ? 'Mark as Sold' : 'Mark as Available' }}">
                                                @if($product->is_available)
                                                    <i data-lucide="package" class="w-3.5 h-3.5"></i>
                                                @else
                                                    <i data-lucide="package-open" class="w-3.5 h-3.5"></i>
                                                @endif
                                            </button>
                                            <button onclick="editProduct({{ $product->id }})" class="p-1.5 text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100 rounded" title="Edit">
                                                <i data-lucide="edit-2" class="w-3.5 h-3.5"></i>
                                            </button>
                                            <form action="{{ route('admin.inventory.delete', $product->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="page" value="{{ request('page', 1) }}">
                                                <button type="submit" class="p-1.5 text-red-600 hover:text-red-700 hover:bg-red-50 rounded" title="Delete">
                                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                </button>
                                            </form>
                                        </div>
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
            <form id="productForm" method="POST" enctype="multipart/form-data" onsubmit="return validateFormAndSubmit()" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="productId" name="_method" value="">
                <input type="hidden" id="currentPage" name="page" value="">

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

                <div class="grid grid-cols-2 gap-4">
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
                        <select name="size" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900" id="sizeSelect">
                            <option value="XS">XS</option>
                            <option value="S">S</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="XXXL">XXXL</option>
                            <option value="One Size">One Size</option>
                            <option value="Adjustable">Adjustable</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-zinc-700 mb-2">Color</label>
                        <input type="text" name="color" required class="w-full px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-zinc-700 mb-2">Product Images (up to 4)</label>
                    <div class="space-y-3">
                        <!-- Image Previews Container -->
                        <div id="imagePreviews" class="flex flex-wrap gap-2">
                            <!-- Previews will be added here dynamically -->
                        </div>
                        <!-- File Inputs Container -->
                        <div id="imageInputs" class="space-y-2">
                            <div class="flex items-center gap-2">
                                <input type="file" name="images[]" accept="image/*" onchange="previewImages(this)" class="flex-1 px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                                <button type="button" onclick="addImageInput()" class="px-3 py-2 text-sm bg-zinc-100 text-zinc-700 rounded-md hover:bg-zinc-200 flex items-center gap-1">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                    Add More
                                </button>
                            </div>
                        </div>
                        <p class="text-xs text-zinc-500">Upload JPG, PNG or WebP (max 2MB each, up to 4 images)</p>
                    </div>
                    <!-- Hidden input to track existing images for edit -->
                    <input type="hidden" id="existingImages" name="existing_images" value="">
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

        let imageCount = 0;
        const MAX_IMAGES = 4;
        let existingImages = [];

        function addImageInput() {
            const container = document.getElementById('imageInputs');
            const currentInputs = container.querySelectorAll('input[type="file"]');

            if (currentInputs.length >= MAX_IMAGES) {
                alert(`Maximum ${MAX_IMAGES} images allowed`);
                return;
            }

            const inputDiv = document.createElement('div');
            inputDiv.className = 'flex items-center gap-2';
            inputDiv.innerHTML = `
                <input type="file" name="images[]" accept="image/*" onchange="previewImages(this)" class="flex-1 px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                <button type="button" onclick="removeImageInput(this)" class="px-3 py-2 text-sm bg-red-100 text-red-700 rounded-md hover:bg-red-200 flex items-center gap-1">
                    <i data-lucide="minus" class="w-4 h-4"></i>
                    Remove
                </button>
            `;
            container.appendChild(inputDiv);
            lucide.createIcons();
        }

        function removeImageInput(button) {
            const inputDiv = button.parentElement;
            const fileInput = inputDiv.querySelector('input[type="file"]');

            // Remove preview if exists
            if (fileInput.dataset.previewIndex) {
                const preview = document.getElementById(`preview-${fileInput.dataset.previewIndex}`);
                if (preview) preview.remove();
            }

            inputDiv.remove();
        }

        function previewImages(input) {
            const container = document.getElementById('imagePreviews');

            if (input.files && input.files[0]) {
                const file = input.files[0];

                // Validate file size
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    input.value = '';
                    return;
                }

                // Check total images
                const allInputs = document.querySelectorAll('input[type="file"]');
                const totalFiles = Array.from(allInputs).filter(i => i.files.length > 0).length;

                if (totalFiles > MAX_IMAGES) {
                    alert(`Maximum ${MAX_IMAGES} images allowed`);
                    input.value = '';
                    return;
                }

                // Remove existing preview for this input
                if (input.dataset.previewIndex) {
                    const existingPreview = document.getElementById(`preview-${input.dataset.previewIndex}`);
                    if (existingPreview) existingPreview.remove();
                }

                // Create preview index
                const previewIndex = 'preview-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
                input.dataset.previewIndex = previewIndex;

                // Create preview element
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewDiv = document.createElement('div');
                    previewDiv.id = previewIndex;
                    previewDiv.className = 'relative w-20 h-20 rounded-lg border border-zinc-200 overflow-hidden';
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <button type="button" onclick="clearImageInput('${previewIndex}')" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                            <i data-lucide="x" class="w-3 h-3"></i>
                        </button>
                    `;
                    container.appendChild(previewDiv);
                    lucide.createIcons();
                };
                reader.readAsDataURL(file);
            }
        }

        function clearImageInput(previewIndex) {
            const preview = document.getElementById(previewIndex);
            if (preview) preview.remove();

            // Find and clear the associated input
            const input = document.querySelector(`input[data-preview-index="${previewIndex}"]`);
            if (input) {
                input.value = '';
                delete input.dataset.previewIndex;
            }
        }

        function showExistingImages(images) {
            const container = document.getElementById('imagePreviews');
            container.innerHTML = '';
            existingImages = images || [];

            existingImages.forEach((imgSrc, index) => {
                const previewDiv = document.createElement('div');
                previewDiv.className = `relative w-20 h-20 rounded-lg border border-zinc-200 overflow-hidden existing-preview`;
                previewDiv.dataset.imageIndex = index;
                previewDiv.innerHTML = `
                    <img src="${imgSrc}" class="w-full h-full object-cover">
                    <button type="button" onclick="removeExistingImage(${index})" class="absolute top-1 right-1 w-5 h-5 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600">
                        <i data-lucide="x" class="w-3 h-3"></i>
                    </button>
                `;
                container.appendChild(previewDiv);
            });
            lucide.createIcons();
        }

        function removeExistingImage(index) {
            const preview = document.querySelector(`[data-image-index="${index}"]`);
            if (preview) preview.remove();
            existingImages = existingImages.filter((_, i) => i !== index);
            document.getElementById('existingImages').value = JSON.stringify(existingImages);
        }

        function getRemainingImageSlots() {
            const existingCount = existingImages.length;
            const newFiles = Array.from(document.querySelectorAll('input[type="file"]'))
                .filter(i => i.files.length > 0).length;
            return MAX_IMAGES - existingCount - newFiles;
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Product';
            document.getElementById('productForm').action = '{{ route("admin.inventory.store") }}';
            document.getElementById('productForm').reset();
            document.getElementById('productId').value = '';
            document.getElementById('imagePreviews').innerHTML = '';
            document.getElementById('existingImages').value = '';

            // Reset file inputs to just one
            const container = document.getElementById('imageInputs');
            container.innerHTML = `
                <div class="flex items-center gap-2">
                    <input type="file" name="images[]" accept="image/*" onchange="previewImages(this)" class="flex-1 px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                    <button type="button" onclick="addImageInput()" class="px-3 py-2 text-sm bg-zinc-100 text-zinc-700 rounded-md hover:bg-zinc-200 flex items-center gap-1">
                        <i data-lucide="plus" class="w-4 h-4"></i>
                        Add More
                    </button>
                </div>
            `;
            existingImages = [];
            lucide.createIcons();
            document.getElementById('productModal').classList.remove('hidden');
        }

        function editProduct(productId) {
            console.log('=== EDITING PRODUCT ID:', productId, '===');

            // Fetch product data from server
            fetch(`/admin/inventory/${productId}/edit`)
                .then(response => response.json())
                .then(product => {
                    console.log('Product data received:', product);

                    // Get current page from URL
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentPage = urlParams.get('page') || '1';

                    document.getElementById('modalTitle').textContent = 'Edit Product';
                    document.getElementById('productForm').action = `/admin/inventory/${product.id}`;
                    document.getElementById('productId').name = '_method';
                    document.getElementById('productId').value = 'PUT';
                    document.getElementById('currentPage').value = currentPage;

                    const form = document.getElementById('productForm');
                    form.querySelector('[name="name"]').value = product.name;
                    form.querySelector('[name="brand"]').value = product.brand;
                    form.querySelector('[name="description"]').value = product.description;
                    form.querySelector('[name="category_id"]').value = product.category_id;
                    form.querySelector('[name="price"]').value = product.price;
                    form.querySelector('[name="condition"]').value = product.condition;
                    form.querySelector('[name="color"]').value = product.color;

                    // Set size dropdown value
                    const sizeSelect = document.getElementById('sizeSelect');
                    if (sizeSelect) {
                        // Try to set the value, if it doesn't exist in options, add it
                        let sizeExists = false;
                        for (let i = 0; i < sizeSelect.options.length; i++) {
                            if (sizeSelect.options[i].value === product.size) {
                                sizeSelect.selectedIndex = i;
                                sizeExists = true;
                                break;
                            }
                        }
                        // If size doesn't exist in dropdown, add it as an option
                        if (!sizeExists && product.size) {
                            const option = document.createElement('option');
                            option.value = product.size;
                            option.textContent = product.size;
                            option.selected = true;
                            sizeSelect.appendChild(option);
                        }
                    }

                    // Show existing images
                    showExistingImages(product.images || []);
                    document.getElementById('existingImages').value = JSON.stringify(product.images || []);

                    // Reset file inputs
                    const container = document.getElementById('imageInputs');
                    container.innerHTML = `
                        <div class="flex items-center gap-2">
                            <input type="file" name="images[]" accept="image/*" onchange="previewImages(this)" class="flex-1 px-3 py-2 text-sm border border-zinc-300 rounded-md focus:outline-none focus:ring-2 focus:ring-zinc-900">
                            <button type="button" onclick="addImageInput()" class="px-3 py-2 text-sm bg-zinc-100 text-zinc-700 rounded-md hover:bg-zinc-200 flex items-center gap-1">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Add More
                            </button>
                        </div>
                    `;
                    lucide.createIcons();
                    document.getElementById('productModal').classList.remove('hidden');
                    console.log('Modal opened with form data');
                })
                .catch(error => {
                    console.error('Error fetching product data:', error);
                    alert('Failed to load product data. Please try again.');
                });
        }

        function validateForm() {
            const form = document.getElementById('productForm');
            const name = form.querySelector('[name="name"]').value;
            const brand = form.querySelector('[name="brand"]').value;
            const category = form.querySelector('[name="category_id"]').value;
            const price = form.querySelector('[name="price"]').value;
            const condition = form.querySelector('[name="condition"]').value;
            const size = form.querySelector('[name="size"]').value;
            const color = form.querySelector('[name="color"]').value;

            console.log('Form validation:', { name, brand, category, price, condition, size, color });

            if (!name || !brand || !category || !price || !condition || !size || !color) {
                alert('Please fill in all required fields.\nName: ' + (name ? 'OK' : 'Missing') +
                       '\nBrand: ' + (brand ? 'OK' : 'Missing') +
                       '\nCategory: ' + (category ? 'OK' : 'Missing') +
                       '\nPrice: ' + (price ? 'OK' : 'Missing') +
                       '\nCondition: ' + (condition ? 'OK' : 'Missing') +
                       '\nSize: ' + (size ? 'OK' : 'Missing') +
                       '\nColor: ' + (color ? 'OK' : 'Missing'));
                return false;
            }

            return true;
        }

    function validateFormAndSubmit() {
            if (validateForm()) {
                return confirm('Save product changes?');
            }
            return false;
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
        }

        function toggleAvailability(productId, makeAvailable) {
            const action = makeAvailable ? 'mark as available' : 'mark as sold';
            if (!confirm(`Are you sure you want to ${action} this product?`)) {
                return;
            }

            fetch(`/admin/inventory/${productId}/toggle-availability`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                },
                body: JSON.stringify({ is_available: makeAvailable })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload the page, preserving the current page number
                    const urlParams = new URLSearchParams(window.location.search);
                    const currentPage = urlParams.get('page') || '1';
                    window.location.href = `/admin/inventory?page=${currentPage}`;
                } else {
                    alert('Error: ' + (data.message || 'Failed to update product status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product status');
            });
        }

        function filterTable() {
            const searchValue = document.getElementById('searchInput').value.toLowerCase();
            const categoryValue = document.getElementById('categoryFilter').value;
            const statusValue = document.getElementById('statusFilter').value;

            const table = document.querySelector('tbody');
            const rows = table.getElementsByTagName('tr');

            for (let row of rows) {
                const nameCell = row.cells[0].textContent.toLowerCase();
                const categoryCell = row.cells[1];
                const statusCell = row.cells[4];

                let matchesSearch = nameCell.includes(searchValue);
                let matchesCategory = !categoryValue || categoryCell.dataset.categoryId === categoryValue;
                let matchesStatus = true;

                if (statusValue === 'available') {
                    matchesStatus = statusCell.querySelector('.bg-emerald-50') !== null;
                } else if (statusValue === 'sold') {
                    matchesStatus = statusCell.querySelector('.bg-red-50') !== null;
                }

                row.style.display = (matchesSearch && matchesCategory && matchesStatus) ? '' : 'none';
            }
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('categoryFilter').value = '';
            document.getElementById('statusFilter').value = '';
            filterTable();
        }

        function exportInventory() {
            alert('Export functionality coming soon! This will export all products to CSV.');
        }
    </script>
</body>
</html>