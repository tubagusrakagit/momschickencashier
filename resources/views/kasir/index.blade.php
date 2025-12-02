@extends('layouts.app')

@section('title', 'Kasir - Transaksi Baru')

@section('content')
<div class="flex flex-col lg:flex-row h-[calc(100vh-100px)] gap-6">

    <!-- KOLOM KIRI: DAFTAR PRODUK (65%) -->
    <div class="lg:w-[65%] flex flex-col">
        
        <!-- Header: Pencarian -->
        <div class="bg-white p-4 rounded-2xl shadow-sm mb-4 flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Daftar Menu</h2>
            <div class="relative w-64">
                <input type="text" id="searchProduct" placeholder="Cari menu..." 
                    class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-brand-red text-sm">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                </div>
            </div>
        </div>

        <!-- Grid Produk -->
        <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar">
            <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4" id="productGrid">
                @forelse($products as $product)
                    <div class="product-card bg-white p-4 rounded-2xl shadow-sm border border-transparent hover:border-brand-gold transition cursor-pointer flex flex-col h-full group"
                         data-id="{{ $product->id }}"
                         data-name="{{ $product->name }}"
                         data-price="{{ $product->price }}"
                         data-image="{{ $product->image_url ?? '' }}"
                         data-stock="{{ $product->stock }}"
                         onclick="addToCart(this)">
                        
                        <!-- Gambar -->
                        <div class="h-28 bg-gray-100 rounded-xl mb-3 flex items-center justify-center overflow-hidden relative">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" class="w-full h-full object-cover">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>
                            @endif
                            
                            <div class="absolute top-2 right-2 bg-black/60 text-white text-[10px] px-2 py-0.5 rounded-full backdrop-blur-sm">
                                Stok: {{ $product->stock }}
                            </div>
                        </div>

                        <!-- Info -->
                        <h3 class="font-bold text-gray-800 text-sm mb-1 leading-tight">{{ $product->name }}</h3>
                        <p class="text-brand-red font-extrabold mt-auto">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500">
                        <p>Tidak ada produk tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- KOLOM KANAN: KERANJANG (35%) -->
    <div class="lg:w-[35%] bg-white rounded-3xl shadow-lg flex flex-col h-full border-t-4 border-brand-red">
        
        <!-- Header Keranjang -->
        <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-3xl">
            <div>
                <h3 class="font-bold text-lg text-gray-800">Keranjang</h3>
                <p class="text-xs text-gray-500">No: <span class="font-mono font-bold text-brand-red">{{ $transactionCode }}</span></p>
            </div>
            <button onclick="clearCart(true)" class="text-xs text-red-500 hover:text-red-700 font-semibold flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                Reset
            </button>
        </div>

        <!-- List Item (Scrollable) -->
        <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar" id="cartItemsContainer">
            <!-- State Kosong -->
            <div id="emptyCartMessage" class="h-full flex flex-col items-center justify-center text-gray-400 opacity-60">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                <p class="text-sm">Keranjang masih kosong</p>
            </div>
        </div>

        <!-- Summary & Metode Pembayaran -->
        <div class="p-5 bg-gray-50 rounded-b-3xl border-t border-gray-200">
            <div class="space-y-2 mb-4 text-sm">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal</span>
                    <span class="font-semibold" id="labelSubtotal">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Pajak (10%)</span>
                    <span class="font-semibold" id="labelTax">Rp 0</span>
                </div>
                <div class="flex justify-between text-gray-800 text-lg font-black pt-2 border-t border-gray-200 mt-2">
                    <span>Total</span>
                    <span class="text-brand-red" id="labelTotal">Rp 0</span>
                </div>
            </div>

            <!-- Pilihan Metode Pembayaran -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                <div class="grid grid-cols-3 gap-2" id="paymentMethodContainer">
                    <button type="button" data-method="Tunai" onclick="selectPaymentMethod('Tunai')" class="payment-btn active bg-brand-red text-white py-2 rounded-xl font-semibold text-xs shadow-md shadow-brand-red/20 transition">
                        Tunai
                    </button>
                    <button type="button" data-method="QRIS" onclick="selectPaymentMethod('QRIS')" class="payment-btn bg-white text-gray-700 border border-gray-300 py-2 rounded-xl font-semibold text-xs transition">
                        QRIS
                    </button>
                    <button type="button" data-method="Transfer" onclick="selectPaymentMethod('Transfer')" class="payment-btn bg-white text-gray-700 border border-gray-300 py-2 rounded-xl font-semibold text-xs transition">
                        Transfer
                    </button>
                </div>
            </div>

            <!-- Form Hidden untuk Checkout -->
            <form id="checkoutForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="payment_method" id="inputPaymentMethod" value="Tunai">
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" class="w-full py-2.5 rounded-xl border border-gray-300 text-gray-600 font-semibold hover:bg-gray-100 text-sm">
                        Simpan
                    </button>
                    <button type="button" onclick="processCheckout()" class="w-full py-2.5 rounded-xl bg-brand-red text-white font-bold hover:bg-red-700 shadow-lg shadow-brand-red/30 transition text-sm flex justify-center items-center">
                        Bayar
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="ml-2"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- BAGIAN MODAL UMUM (ALERTS/CONFIRMS) -->
<div id="defaultModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="bg-white p-6 rounded-2xl shadow-2xl max-w-sm w-full transform transition-transform duration-300 scale-95">
        <div class="flex items-center space-x-3 mb-4">
            <svg id="defaultModalIcon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D93630" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            <h3 id="defaultModalTitle" class="text-lg font-bold text-gray-800">Perhatian!</h3>
        </div>
        <p id="defaultModalMessage" class="text-gray-600 mb-6"></p>
        <div class="flex justify-end space-x-3">
            <button id="defaultModalConfirm" onclick="closeDefaultModal(true)" class="hidden bg-brand-red hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-xl transition duration-150">Ya</button>
            <button onclick="closeDefaultModal(false)" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-xl transition duration-150">Tutup</button>
        </div>
    </div>
</div>

<!-- BAGIAN MODAL CHECKOUT KUSTOM (FINAL) -->
<div id="checkoutModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="bg-white p-8 rounded-2xl shadow-2xl max-w-md w-full transform transition-transform duration-300 scale-95">
        <h3 class="text-2xl font-black text-gray-800 border-b pb-2 mb-4">Konfirmasi Pembayaran</h3>
        
        <div class="space-y-4">
            <div class="flex justify-between text-gray-600">
                <span>Metode Pembayaran</span>
                <span id="checkoutMethod" class="font-bold text-brand-red"></span>
            </div>
            <div class="flex justify-between text-gray-800 font-extrabold text-xl pt-2">
                <span>Total Bayar</span>
                <span id="checkoutTotalDisplay" class="text-brand-red"></span>
            </div>

            <!-- KONTEN DINAMIS KHUSUS TUNAI/NON-TUNAI -->
            <div id="cashInputContainer" class="mt-4 border-t pt-4 space-y-3">
                <div class="mt-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Uang Diterima (Rp)</label>
                    <!-- Input Cash Wajib Ada -->
                    <input type="number" id="cashReceived" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl bg-gray-50 focus:border-brand-gold text-lg font-bold text-gray-800"
                           placeholder="0" oninput="calculateChange()">
                </div>
                
                <div class="flex justify-between text-gray-800 text-lg font-black pt-2 border-t mt-2">
                    <span>Kembalian</span>
                    <span id="changeDue" class="text-green-600">Rp 0</span>
                </div>
            </div>

            <div id="nonCashMessage" class="hidden text-center bg-blue-50 border border-blue-200 p-3 rounded-xl text-sm text-blue-700">
                Mohon konfirmasi pembayaran non-tunai dari pelanggan.
            </div>
            
        </div>
        
        <div class="flex justify-end space-x-3 border-t pt-4 mt-6">
            <button onclick="closeCheckoutModal()" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-xl transition duration-150">
                Batal
            </button>
            <button id="confirmCheckoutButton" onclick="confirmCheckout()" class="bg-brand-red hover:bg-red-700 text-white font-bold py-2 px-6 rounded-xl transition duration-150 shadow-md shadow-brand-red/40" disabled>
                Lanjut Bayar
            </button>
        </div>
    </div>
</div>


<!-- SCRIPT LOGIC -->
<script>
    let cart = [];
    const taxRate = 0.10;
    let selectedPaymentMethod = 'Tunai';
    let modalCallback = null;
    let currentTotal = 0; // Menyimpan nilai total murni (angka)

    // --- FUNGSI CUSTOM MODAL (Default Modal) ---
    function showModal(message, type = 'alert', callback = null) {
        // Logika showModal tetap sama
        const modal = document.getElementById('defaultModal');
        const modalTitle = document.getElementById('defaultModalTitle');
        const modalMessage = document.getElementById('defaultModalMessage');
        const modalConfirm = document.getElementById('defaultModalConfirm');
        const modalIcon = document.getElementById('defaultModalIcon');
        
        modalCallback = callback;

        modalMessage.innerText = message;
        
        if (type === 'confirm') {
            modalTitle.innerText = 'Konfirmasi Tindakan';
            modalConfirm.classList.remove('hidden');
            modalIcon.setAttribute('stroke', '#FFC300');
        } else {
            modalTitle.innerText = 'Perhatian';
            modalConfirm.classList.add('hidden');
            modalIcon.setAttribute('stroke', '#D93630');
        }
        
        modalIcon.innerHTML = '<path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>'; 

        setTimeout(() => {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeDefaultModal(result) {
        const modal = document.getElementById('defaultModal');
        
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('div').classList.add('scale-95');

        if (modalCallback && typeof modalCallback === 'function') {
            modalCallback(result);
            modalCallback = null;
        }
    }
    
    // --- FUNGSI BARU: KALKULASI KEMBALIAN ---
    function calculateChange() {
        const cashInput = document.getElementById('cashReceived');
        const changeDisplay = document.getElementById('changeDue');
        const confirmBtn = document.getElementById('confirmCheckoutButton');
        
        const received = Number(cashInput.value);
        const total = currentTotal; 
        
        let change = 0;

        if (received >= total) {
            change = received - total;
            confirmBtn.disabled = false; // Uang cukup, tombol aktif
        } else {
            change = 0;
            confirmBtn.disabled = true; // Uang kurang, tombol non-aktif
        }
        
        changeDisplay.innerText = formatRupiah(change);
        // Menandai dengan warna merah jika uang diterima kurang
        changeDisplay.classList.toggle('text-red-500', received < total);
        changeDisplay.classList.toggle('text-green-600', received >= total);
    }

    // --- FUNGSI MODAL CHECKOUT BARU ---
    function showCheckoutModal() {
        if (cart.length === 0) {
            showModal('Keranjang kosong! Silakan pilih produk terlebih dahulu.', 'alert');
            return;
        }
        
        const totals = calculateTotals(true);
        currentTotal = totals.total; // Simpan nilai total angka murni
        
        const modal = document.getElementById('checkoutModal');
        
        // Isi detail summary di modal
        document.getElementById('checkoutMethod').innerText = selectedPaymentMethod;
        document.getElementById('checkoutTotalDisplay').innerText = formatRupiah(currentTotal);
        
        const cashContainer = document.getElementById('cashInputContainer');
        const nonCashMsg = document.getElementById('nonCashMessage');
        const confirmBtn = document.getElementById('confirmCheckoutButton');
        
        // Atur tampilan modal berdasarkan metode pembayaran
        if (selectedPaymentMethod === 'Tunai') {
            cashContainer.classList.remove('hidden');
            nonCashMsg.classList.add('hidden');
            
            // Reset input cash dan hitung kembalian awal
            const cashInput = document.getElementById('cashReceived');
            cashInput.value = ''; 
            calculateChange();
            
            // Auto focus ke input cash
            setTimeout(() => cashInput.focus(), 300);
            
        } else {
            cashContainer.classList.add('hidden');
            nonCashMsg.classList.remove('hidden');
            nonCashMsg.innerHTML = `Mohon konfirmasi pembayaran **${selectedPaymentMethod}** dari pelanggan.`;
            
            confirmBtn.disabled = false; // Non-Tunai langsung bisa lanjut
        }
        
        // Tampilkan modal
        setTimeout(() => {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeCheckoutModal() {
        const modal = document.getElementById('checkoutModal');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.querySelector('div').classList.add('scale-95');
    }

    // GANTI FUNGSI confirmCheckout DENGAN LOGIKA UANG DITERIMA
    async function confirmCheckout() {
        const cashInput = document.getElementById('cashReceived');
        let uangDiterima = 0;

        // Validasi Uang Diterima hanya untuk Tunai
        if (selectedPaymentMethod === 'Tunai') {
            uangDiterima = Number(cashInput.value);
            if (uangDiterima < currentTotal) {
                showModal('Uang diterima kurang dari total pembayaran!', 'alert');
                return;
            }
        }
        
        // 1. Tutup modal konfirmasi dulu
        closeCheckoutModal();

        // 2. Siapkan Data yang mau dikirim
        const totals = calculateTotals(true);
        const payload = {
            transaction_code: '{{ $transactionCode }}',
            payment_method: selectedPaymentMethod,
            total_amount: totals.total,
            // Tambahkan data uang diterima (opsional) untuk laporan di masa depan
            cash_received: uangDiterima, 
            cart: cart, // Array keranjang
            _token: '{{ csrf_token() }}' // Token keamanan wajib Laravel
        };

        // 3. Tampilkan Loading 
        showModal('Sedang memproses transaksi...', 'alert');

        try {
            // 4. Kirim Data ke Server (AJAX / Fetch)
            const response = await fetch('{{ route("kasir.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(payload)
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                // 5. JIKA SUKSES
                showModal('Transaksi Berhasil Disimpan! Mencetak Struk...', 'alert');
                
                // --- Buka Struk di Tab Baru ---
                if (result.transaction_id) {
                    const printUrl = `/kasir/struk/${result.transaction_id}`;
                    window.open(printUrl, '_blank');
                }
                
                // Reset Keranjang & Reload Halaman
                setTimeout(() => {
                    window.location.reload(); 
                }, 1000); // 1 detik jeda

            } else {
                // 6. JIKA GAGAL DARI SERVER
                console.error(result);
                showModal('Gagal: ' + result.message, 'alert');
            }

        } catch (error) {
            // 7. JIKA ERROR JARINGAN / CODING
            console.error(error);
            showModal('Terjadi kesalahan sistem. Cek console.', 'alert');
        }
    }


    // --- FUNGSI UTAMA LAINNYA (TIDAK DIUBAH) ---

    function addToCart(element) {
        try {
            const id = parseInt(element.getAttribute('data-id'));
            const name = element.getAttribute('data-name');
            const price = Number(element.getAttribute('data-price'));
            const image = element.getAttribute('data-image');
            const maxStock = parseInt(element.getAttribute('data-stock'));

            if (isNaN(price) || isNaN(maxStock)) {
                showModal("Kesalahan data: Harga atau Stok tidak valid. Hubungi Admin.", 'alert');
                return;
            }

            const existingItem = cart.find(item => item.id === id);

            if (existingItem) {
                if (existingItem.qty < maxStock) {
                    existingItem.qty++;
                } else {
                    showModal('Stok tidak mencukupi!', 'alert');
                    return;
                }
            } else {
                cart.push({ id: id, name: name, price: price, image: image, qty: 1, maxStock: maxStock });
            }
            
            updateCartUI();
        } catch (error) {
            console.error("Critical Error in addToCart:", error);
            showModal("Terjadi kesalahan fatal saat menambahkan produk. Coba refresh halaman.", 'alert');
        }
    }

    function selectPaymentMethod(method) {
        selectedPaymentMethod = method;
        document.getElementById('inputPaymentMethod').value = method;
        
        document.querySelectorAll('.payment-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-brand-red', 'text-white', 'shadow-md', 'shadow-brand-red/20');
            btn.classList.add('bg-white', 'text-gray-700', 'border', 'border-gray-300');
        });
        
        const selectedBtn = document.querySelector(`[data-method="${method}"]`);
        if (selectedBtn) {
            selectedBtn.classList.remove('bg-white', 'text-gray-700', 'border', 'border-gray-300');
            selectedBtn.classList.add('active', 'bg-brand-red', 'text-white', 'shadow-md', 'shadow-brand-red/40');
        }
    }

    function updateCartUI() {
        const container = document.getElementById('cartItemsContainer');
        const emptyMsg = document.getElementById('emptyCartMessage');
        const oldHtml = container.innerHTML;

        try {
            container.innerHTML = '';
            
            if (cart.length === 0) {
                container.appendChild(emptyMsg);
                emptyMsg.style.display = 'flex';
            } else {
                if (emptyMsg) { emptyMsg.style.display = 'none'; }

                cart.forEach((item, index) => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'flex items-center bg-white p-3 rounded-xl border border-gray-100 shadow-sm animate-fade-in mb-2';
                    
                    let imgHtml = '';
                    if(item.image && item.image !== '') { imgHtml = `<img src="${item.image}" class="w-full h-full object-cover">`; } 
                    else { imgHtml = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2v0a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3Zm0 0v7"/></svg>`; }

                    itemDiv.innerHTML = `
                        <div class="w-12 h-12 bg-gray-100 rounded-lg mr-3 overflow-hidden flex-shrink-0 flex items-center justify-center">
                             ${imgHtml}
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-bold text-gray-800 line-clamp-1">${item.name}</h4>
                            <p class="text-xs text-brand-red font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(item.price)}</p>
                        </div>
                        
                        <div class="flex items-center bg-gray-100 rounded-lg p-1">
                            <button onclick="updateQty(${index}, -1)" class="w-6 h-6 flex items-center justify-center bg-white rounded-md text-gray-600 hover:text-red-500 shadow-sm transition">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/></svg>
                            </button>
                            <span class="text-xs font-bold w-6 text-center">${item.qty}</span>
                            <button onclick="updateQty(${index}, 1)" class="w-6 h-6 flex items-center justify-center bg-brand-red text-white rounded-md shadow-sm hover:bg-red-700 transition">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                        </div>
                    `;
                    container.appendChild(itemDiv);
                });
            }
            
            calculateTotals();
        } catch (error) {
            console.error("Critical Error during UI Rendering:", error);
            container.innerHTML = oldHtml;
        }
    }
    
    function updateQty(index, change) {
        const item = cart[index];
        if (change === 1) {
            if (item.qty < item.maxStock) {
                item.qty++;
            } else {
                showModal('Stok maksimum tercapai!', 'alert');
                return;
            }
        } else {
            item.qty--;
        }

        if (item.qty <= 0) {
            cart.splice(index, 1);
        }
        updateCartUI();
    }

    function calculateTotals(returnObject = false) {
        let subtotal = 0;
        cart.forEach(item => { subtotal += (Number(item.price) * item.qty); });
        const tax = subtotal * taxRate;
        const total = subtotal + tax;

        if (returnObject) {
            return { subtotal, tax, total };
        }

        try {
            document.getElementById('labelSubtotal').innerText = formatRupiah(subtotal);
            document.getElementById('labelTax').innerText = formatRupiah(tax);
            document.getElementById('labelTotal').innerText = formatRupiah(total);
        } catch(e) { console.error("Error formatting currency", e); }
    }

    function formatRupiah(number) { return 'Rp ' + new Intl.NumberFormat('id-ID').format(number); }

    function clearCart(isClicked) {
        if (!isClicked) return;
        
        if(cart.length > 0) {
            showModal('Anda yakin ingin mengosongkan keranjang?', 'confirm', (confirmed) => {
                if (confirmed) {
                    cart = [];
                    updateCartUI();
                }
            });
        }
    }

    document.getElementById('searchProduct').addEventListener('input', function(e) {
        const keyword = e.target.value.toLowerCase();
        const items = document.querySelectorAll('#productGrid > div');
        
        items.forEach(item => {
            const name = item.getAttribute('data-name').toLowerCase();
            if(name.includes(keyword)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // 7. Checkout Process (Diubah untuk panggil modal checkout baru)
    function processCheckout() {
        showCheckoutModal();
    }


    window.onload = function() {
        updateCartUI();
        selectPaymentMethod('Tunai');
    };
</script>

<style>
    /* Styling tetap sama */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fade-in 0.2s ease-out forwards; }

    /* Custom Modal CSS */
    #defaultModal.opacity-0, #checkoutModal.opacity-0 {
        z-index: -10;
    }
</style>
@endsection