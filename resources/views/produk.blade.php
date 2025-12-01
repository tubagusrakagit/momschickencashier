@extends('layouts.app')
@section('title', 'Master Produk - Admin')

@section('content')
<div>
    <!-- Custom Styles -->
    <style>
        :root {
            --color-primary-red: #D93630;
            --color-secondary-gold: #FFC300;
        }
        .card-shadow {
            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .add-button {
            background: linear-gradient(145deg, var(--color-primary-red), #E04B46);
            transition: all 0.3s ease;
        }
        .add-button:hover {
            box-shadow: 0 8px 25px -8px rgba(217, 54, 48, 0.7);
            transform: translateY(-2px);
        }
        /* Modal Styles */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        .modal-content {
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
    </style>

    <!-- Main Content Area -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <h1 class="text-4xl font-extrabold text-gray-800 mb-8">Manajemen Produk</h1>

        {{-- Alert Success --}}
        @if(session('success'))
            <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <button type="button" onclick="this.parentElement.remove()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
                    <svg class="fill-current h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                    </svg>
                </button>
            </div>
        @endif

        {{-- Stats Cards --}}
        @if(isset($stats))
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Produk</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['total_products'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Stok</p>
                        <p class="text-2xl font-bold text-gray-800">{{ number_format($stats['total_stock']) }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Stok Rendah</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $stats['low_stock'] }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl card-shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Nilai</p>
                        <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($stats['total_value']) }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Search & Add Button --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
            {{-- Search Form --}}
            <form action="{{ route('produk.index') }}" method="GET" class="w-full md:w-auto">
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ $search ?? '' }}" 
                               placeholder="Cari produk, SKU, kategori..."
                               class="w-full md:w-80 pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                    <button type="submit" class="px-6 py-3 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition">
                        Cari
                    </button>
                    @if($search ?? false)
                        <a href="{{ route('produk.index') }}" class="px-4 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            {{-- Add Button - Trigger Modal --}}
            <button type="button" onclick="openModal()"
               class="add-button text-white font-bold py-3 px-8 rounded-full shadow-lg flex items-center space-x-2 uppercase tracking-wider">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Produk</span>
            </button>
        </div>

        {{-- Product Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
                <div class="bg-white rounded-xl card-shadow overflow-hidden flex flex-col transition duration-300 hover:shadow-xl hover:scale-[1.01]">
                    {{-- Product Image --}}
                    <div class="h-40 bg-gray-100 overflow-hidden">
                        @if($product->image_url)
                            <img src="{{ Storage::url($product->image_url) }}" 
                                 alt="{{ $product->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-red-500 to-yellow-400">
                                <span class="text-white font-bold text-lg">{{ Str::limit($product->name, 15) }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-grow">
                        {{-- Category Badge --}}
                        @if($product->category)
                            <span class="inline-block px-2 py-1 text-xs font-semibold bg-yellow-100 text-yellow-800 rounded-full mb-2">
                                {{ $product->category->name }}
                            </span>
                        @endif

                        {{-- Product Name --}}
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                        
                        {{-- SKU --}}
                        <p class="text-sm text-gray-500 mb-2">SKU: {{ $product->sku }}</p>
                        
                        {{-- Product Price --}}
                        <p class="text-3xl font-extrabold mb-3" style="color: var(--color-primary-red);">
                            Rp {{ number_format($product->price) }}
                        </p>
                        
                        {{-- Stock Status --}}
                        <div class="flex items-center space-x-2 text-sm">
                            <span class="font-medium text-gray-500">Stock:</span>
                            <span class="font-semibold {{ $product->stock > 10 ? 'text-green-500' : 'text-orange-500' }}">
                                {{ $product->stock }} Unit
                            </span>
                            @if($product->stock < 10)
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-600 rounded-full">Low Stock</span>
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="p-5 pt-0 flex space-x-3 border-t border-gray-100">
                        {{-- Edit Button - Trigger Modal with Data --}}
                        <button type="button" 
                                onclick="openEditModal({{ json_encode($product) }})"
                                class="flex-1 flex items-center justify-center space-x-2 px-4 py-2 bg-yellow-100 text-yellow-600 font-semibold rounded-lg hover:bg-yellow-400 hover:text-white transition duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span>Edit</span>
                        </button>
                        <form action="{{ route('produk.destroy', $product) }}" method="POST" class="flex-1"
                              onsubmit="return confirm('Yakin ingin menghapus produk {{ $product->name }}?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full flex items-center justify-center space-x-2 px-4 py-2 bg-red-100 text-red-600 font-semibold rounded-lg hover:bg-red-500 hover:text-white transition duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                <span>Hapus</span>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="bg-white rounded-xl card-shadow p-12 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Belum Ada Produk</h3>
                        <p class="text-gray-400 mb-6">
                            @if($search ?? false)
                                Tidak ditemukan produk dengan kata kunci "{{ $search }}"
                            @else
                                Mulai tambahkan produk pertama Anda
                            @endif
                        </p>
                        <button type="button" onclick="openModal()" class="add-button inline-flex items-center space-x-2 text-white font-bold py-3 px-6 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            <span>Tambah Produk</span>
                        </button>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="mt-8 flex justify-center">
                <nav class="flex items-center space-x-2">
                    @if($products->onFirstPage())
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">&laquo; Prev</span>
                    @else
                        <a href="{{ $products->previousPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 card-shadow">&laquo; Prev</a>
                    @endif

                    @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if($page == $products->currentPage())
                            <span class="px-4 py-2 text-white rounded-lg" style="background: var(--color-primary-red);">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 card-shadow">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="px-4 py-2 bg-white text-gray-700 rounded-lg hover:bg-gray-100 card-shadow">Next &raquo;</a>
                    @else
                        <span class="px-4 py-2 bg-gray-100 text-gray-400 rounded-lg cursor-not-allowed">Next &raquo;</span>
                    @endif
                </nav>
            </div>
            <div class="mt-4 text-center text-sm text-gray-500">
                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
            </div>
        @endif
    </div>

    {{-- ======================= MODAL FORM ======================= --}}
    <div id="productModal" class="fixed inset-0 z-50 hidden">
        {{-- Backdrop --}}
        <div class="modal-backdrop absolute inset-0" onclick="closeModal()"></div>
        
        {{-- Modal Content --}}
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="modal-content bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 max-h-[90vh] overflow-y-auto">
                {{-- Modal Header --}}
                <div class="sticky top-0 bg-white px-6 py-4 border-b border-gray-200 rounded-t-2xl">
                    <div class="flex items-center justify-between">
                        <h2 id="modalTitle" class="text-2xl font-bold text-gray-800">Tambah Produk Baru</h2>
                        <button type="button" onclick="closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body - Form --}}
                <form id="productForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    {{-- Alert Error dalam Modal --}}
                    @if($errors->any())
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Image Preview --}}
                    <div class="flex justify-center">
                        <div id="imagePreviewContainer" class="w-32 h-32 rounded-xl overflow-hidden bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center cursor-pointer hover:border-red-400 transition"
                             onclick="document.getElementById('image').click()">
                            <div id="imagePlaceholder" class="text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs text-gray-500 mt-1 block">Upload Foto</span>
                            </div>
                            <img id="imagePreview" src="" alt="Preview" class="w-full h-full object-cover hidden">
                        </div>
                    </div>
                    <input type="file" name="image" id="image" class="hidden" accept="image/*" onchange="previewImage(this)">

                    {{-- Nama Produk --}}
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                               placeholder="Contoh: Ayam Goreng Komplit"
                               value="{{ old('name') }}">
                    </div>

                    {{-- SKU --}}
                    <div>
                        <label for="sku" class="block text-sm font-semibold text-gray-700 mb-2">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" id="sku" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                               placeholder="Contoh: AGK-001"
                               value="{{ old('sku') }}">
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" id="category_id" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Price & Stock Row --}}
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Harga --}}
                        <div>
                            <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">Harga <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                                <input type="number" name="price" id="price" required min="0"
                                       class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                       placeholder="0"
                                       value="{{ old('price') }}">
                            </div>
                        </div>

                        {{-- Stok --}}
                        <div>
                            <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">Stok <span class="text-red-500">*</span></label>
                            <input type="number" name="stock" id="stock" required min="0"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition"
                                   placeholder="0"
                                   value="{{ old('stock') }}">
                        </div>
                    </div>

                    {{-- Submit Buttons --}}
                    <div class="flex space-x-3 pt-4">
                        <button type="button" onclick="closeModal()"
                                class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 add-button px-6 py-3 text-white font-semibold rounded-lg">
                            <span id="submitBtnText">Simpan Produk</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- JavaScript untuk Modal --}}
<script>
    const modal = document.getElementById('productModal');
    const form = document.getElementById('productForm');
    const modalTitle = document.getElementById('modalTitle');
    const formMethod = document.getElementById('formMethod');
    const submitBtnText = document.getElementById('submitBtnText');
    const imagePreview = document.getElementById('imagePreview');
    const imagePlaceholder = document.getElementById('imagePlaceholder');

    // Open modal untuk CREATE
    function openModal() {
        resetForm();
        modalTitle.textContent = 'Tambah Produk Baru';
        form.action = "{{ route('produk.store') }}";
        formMethod.value = 'POST';
        submitBtnText.textContent = 'Simpan Produk';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Open modal untuk EDIT
    function openEditModal(product) {
        resetForm();
        modalTitle.textContent = 'Edit Produk';
        form.action = `/produk/${product.id}`;
        formMethod.value = 'PUT';
        submitBtnText.textContent = 'Update Produk';

        // Populate form fields
        document.getElementById('name').value = product.name || '';
        document.getElementById('sku').value = product.sku || '';
        document.getElementById('category_id').value = product.category_id || '';
        document.getElementById('price').value = product.price || 0;
        document.getElementById('stock').value = product.stock || 0;

        // Show existing image if available
        if (product.image_url) {
            imagePreview.src = `/storage/${product.image_url}`;
            imagePreview.classList.remove('hidden');
            imagePlaceholder.classList.add('hidden');
        }

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close modal
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Reset form
    function resetForm() {
        form.reset();
        imagePreview.src = '';
        imagePreview.classList.add('hidden');
        imagePlaceholder.classList.remove('hidden');
    }

    // Preview image before upload
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('hidden');
                imagePlaceholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Auto open modal jika ada error validation
    @if(session('openModal') || $errors->any())
        @if(session('editId'))
            // Jika error saat edit, buka modal edit
            document.addEventListener('DOMContentLoaded', function() {
                const editProduct = @json($editProduct ?? null);
                if (editProduct) {
                    openEditModal(editProduct);
                } else {
                    openModal();
                }
            });
        @else
            // Jika error saat create, buka modal create
            document.addEventListener('DOMContentLoaded', function() {
                openModal();
            });
        @endif
    @endif
</script>
@endsection