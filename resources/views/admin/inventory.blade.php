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
                                    <th class="px-5 py-3 text-left font-medium">Status</th>
                                    <th class="px-5 py-3 text-right font-medium">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-100">
                                @forelse($products as $product)
                                <tr class="hover:bg-zinc-50 {{ !$product->is_available ? 'bg-red-50/50' : '' }}">
                                    <td class="px-5 py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $product->images[0] ?? 'https://via.placeholder.com/40' }}" class="w-12 h-12 rounded object-cover {{ !$product->is_available ? 'opacity-50' : '' }}">
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
                                    <td class="px-5 py-3">
                                        @if($product->is_available)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700">
                                                <i data-lucide="check-circle" class="w-3 h-3"></i>
                                                Available
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700">
                                                <i data-lucide="x-circle" class="w-3 h-3"></i>
                                                Sold
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <button onclick="toggleAvailability({{ $product->id }}, {{ $product->is_available ? 'false' : 'true' }})" class="text-zinc-600 hover:text-zinc-900 mr-2" title="{{ $product->is_available ? 'Mark as Sold' : 'Mark as Available' }}">
                                            @if($product->is_available)
                                                <i data-lucide="package" class="w-4 h-4"></i>
                                            @else
                                                <i data-lucide="package-open" class="w-4 h-4"></i>
                                            @endif
                                        </button>
                                        <button onclick='editProduct({
                    id: {{ $product->id }},
                    name: "{{ $product->name }}",
                    brand: "{{ $product->brand }}",
                    description: "{{ $product->description }}",
                    category_id: {{ $product->category_id }},
                    price: {{ $product->price }},
                    condition: "{{ $product->condition }}",
                    size: "{{ $product->size }}",
                    color: "{{ $product->color }}",
                    images: {{ json_encode($product->images) }}
                })' class="text-zinc-600 hover:text-zinc-900 mr-2">
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
                                    <td colspan="7" class="px-5 py-8 text-center text-zinc-500">No products found</td>
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

        function editProduct(product) {
            console.log('=== EDITING PRODUCT ===');
            console.log('Product ID:', product.id);
            console.log('Product Name:', product.name);
            console.log('Product Size:', product.size);
            console.log('Product Data:', product);

            document.getElementById('modalTitle').textContent = 'Edit Product';
            document.getElementById('productForm').action = `/admin/inventory/${product.id}`;
            document.getElementById('productId').name = '_method';
            document.getElementById('productId').value = 'PUT';

            const form = document.getElementById('productForm');
            form.querySelector('[name="name"]').value = product.name;
            form.querySelector('[name="brand"]').value = product.brand;
            form.querySelector('[name="description"]').value = product.description;
            form.querySelector('[name="category_id"]').value = product.category_id;
            form.querySelector('[name="price"]').value = product.price;
            form.querySelector('[name="condition"]').value = product.condition;

            // Set size dropdown value
            console.log('Setting size to:', product.size);
            const sizeSelect = document.getElementById('sizeSelect');
            console.log('Size select element:', sizeSelect);
            if (sizeSelect) {
                sizeSelect.value = product.size;
                console.log('Size select value set to:', sizeSelect.value);
                console.log('Available options:', Array.from(sizeSelect.options).map(opt => opt.value));
            } else {
                console.error('Size select element not found!');
            }

            form.querySelector('[name="color"]').value = product.color;

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
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update product status'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while updating the product status');
            });
        }
    </script>
</body>
</html>