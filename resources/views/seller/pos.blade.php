@extends('layouts.seller')

@section('title', 'Point of Sale (Kasir)')

@push('styles')
<style>
    /* --- STYLE POS MONOCHROME --- */
    .pos-container { margin-top: -1rem; }
    
    /* Product Card */
    .product-card { 
        cursor: pointer; 
        border: 1px solid #e5e7eb; 
        border-radius: 12px; 
        transition: all 0.2s ease;
        background: #fff;
        height: 100%;
        overflow: hidden;
    }
    .product-card:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); 
        border-color: #111827;
    }
    .product-card img { 
        width: 100%; 
        height: 140px; 
        object-fit: cover; 
    }
    .product-info { padding: 12px; }
    .product-title { font-size: 0.9rem; font-weight: 600; color: #111827; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price { font-size: 1.05rem; font-weight: 700; color: #111827; }
    .product-stock { font-size: 0.75rem; color: #6b7280; }

    /* Transaction Panel */
    .transaction-panel { 
        position: sticky; 
        top: 80px; 
        height: calc(100vh - 100px); 
        display: flex; 
        flex-direction: column;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
    }
    .cart-container { 
        flex-grow: 1; 
        overflow-y: auto; 
        padding-right: 5px;
    }
    .cart-container::-webkit-scrollbar { width: 4px; }
    .cart-container::-webkit-scrollbar-thumb { background-color: #d1d5db; border-radius: 10px; }
    
    /* Cart Item */
    .cart-item { 
        display: flex; 
        align-items: center; 
        justify-content: space-between; 
        padding: 12px 0; 
        border-bottom: 1px dashed #e5e7eb; 
    }
    .cart-item-title { font-size: 0.9rem; font-weight: 600; color: #111827; }
    .cart-item-price { font-size: 0.85rem; color: #6b7280; }
    .qty-control { display: flex; align-items: center; border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; }
    .qty-btn { background: #f9fafb; border: none; padding: 2px 10px; font-weight: bold; color: #374151; cursor: pointer; transition: 0.2s;}
    .qty-btn:hover { background: #e5e7eb; }
    .qty-input { width: 40px; text-align: center; border: none; font-size: 0.9rem; font-weight: 600; padding: 2px 0; }
    .qty-input:focus { outline: none; }
    .remove-btn { color: #ef4444; background: none; border: none; cursor: pointer; padding: 5px; font-size: 1.2rem; }

    /* Bottom Panel */
    .checkout-panel { 
        background: #f9fafb; 
        padding: 1.5rem; 
        border-top: 2px dashed #e5e7eb; 
        border-radius: 0 0 16px 16px;
    }
    
    /* Tombol Uang Pas */
    .quick-cash-btn { font-weight: 600; border-color: #d1d5db; color: #374151; }
    .quick-cash-btn:hover { background-color: #111827; color: white; border-color: #111827; }

    .btn-mono { background: #111827; color: white; border-radius: 8px; font-weight: 600; padding: 12px; }
    .btn-mono:hover { background: #374151; color: white; }
    .btn-mono-outline { background: transparent; border: 1px solid #111827; color: #111827; border-radius: 8px; font-weight: 600; padding: 12px; }
    .btn-mono-outline:hover { background: #f3f4f6; }
</style>
@endpush

@section('content')
<div class="pos-container">
    <div class="row g-4">
        
        {{-- KIRI: KATALOG PRODUK --}}
        <div class="col-lg-7 col-xl-8">
            <div class="page-header mb-4">
                <h3 class="page-title d-flex align-items-center m-0">
                    <div class="page-title-icon-mono me-3">
                        <i class="mdi mdi-point-of-sale"></i>
                    </div> 
                    <div class="d-flex align-items-center" style="font-size: 1.6rem;">
                        <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
                        <i class="mdi mdi-chevron-right header-path-separator"></i>
                        <span class="header-path-current">Point of Sale</span>
                    </div>
                </h3>
            </div>

            <div class="card shadow-sm border-0" style="border-radius: 16px;">
                <div class="card-body">
                    
                    {{-- Filter & Search --}}
                    <div class="row g-2 mb-4">
                        <div class="col-md-7">
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="mdi mdi-magnify text-muted"></i></span>
                                <input type="text" id="search-input" class="form-control border-start-0 ps-0" placeholder="Cari nama produk...">
                            </div>
                        </div>
                        <div class="col-md-5">
                            <select id="category-filter" class="form-select">
                                <option value="all">Semua Kategori</option>
                                </select>
                        </div>
                    </div>

                    {{-- Grid Produk --}}
                    <div id="product-grid" class="row g-3" style="min-height: 50vh;">
                        <div id="loading-spinner" class="col-12 text-center py-5">
                            <div class="spinner-border text-dark" role="status"></div>
                            <p class="mt-2 text-muted">Memuat produk...</p>
                        </div>
                        </div>

                </div>
            </div>
        </div>

        {{-- KANAN: PANEL TRANSAKSI --}}
        <div class="col-lg-5 col-xl-4">
            <div class="card shadow-sm transaction-panel bg-white">
                
                {{-- Header Cart --}}
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0" style="color: #111827;">Rincian Pesanan</h5>
                    <button id="clear-cart-btn" class="btn btn-sm btn-light text-danger border-0"><i class="mdi mdi-trash-can-outline"></i> Kosongkan</button>
                </div>

                {{-- Body Cart --}}
                <div class="p-3 d-flex flex-column" style="flex-grow: 1; overflow: hidden;">
                    <input type="text" id="customer-name" class="form-control mb-3" placeholder="Nama Pelanggan (Opsional)">
                    
                    <div class="cart-container" id="cart-items">
                        <div id="empty-cart-message" class="text-center py-5 text-muted">
                            <i class="mdi mdi-cart-outline" style="font-size: 3rem; color: #d1d5db;"></i>
                            <p class="mt-2">Keranjang kosong.<br>Klik produk untuk menambahkan.</p>
                        </div>
                        </div>
                </div>

                {{-- Checkout Panel --}}
                <div class="checkout-panel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted fw-bold">TOTAL BAYAR</span>
                        <span id="total-price" class="fs-3 fw-bold" style="color: #111827;">Rp 0</span>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-12">
                            <select id="payment-method" class="form-select">
                                <option value="Tunai" selected>Tunai (Cash)</option>
                                <option value="QRIS">QRIS / E-Wallet</option>
                                <option value="Transfer">Transfer Bank</option>
                            </select>
                        </div>
                        <div class="col-12" id="cash-input-group">
                            <input type="number" id="amount-paid" class="form-control form-control-lg text-end fw-bold" placeholder="Uang Diterima (Rp)">
                            <div class="d-flex gap-1 mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn flex-grow-1" data-amount="exact">Uang Pas</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn flex-grow-1" data-amount="50000">50K</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary quick-cash-btn flex-grow-1" data-amount="100000">100K</button>
                            </div>
                            <div class="d-flex justify-content-between mt-3 px-1">
                                <span class="text-muted fw-bold">KEMBALIAN</span>
                                <span id="change-due" class="fw-bold fs-5 text-success">Rp 0</span>
                            </div>
                        </div>
                    </div>

                    <button id="process-payment-btn" class="btn-mono w-100 mt-2" disabled>
                        <i class="mdi mdi-cash-register me-1"></i> Proses Transaksi
                    </button>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let allProducts = [];
    let cart = [];
    let currentTotal = 0;
    
    const formatRupiah = (number) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);

    // --- 1. LOAD DATA DARI API LARAVEL ---
    function loadProducts() {
        fetch("{{ route('seller.pos.api.products') }}")
            .then(res => res.json())
            .then(data => {
                allProducts = data;
                renderProducts(allProducts);
                document.getElementById('loading-spinner').style.display = 'none';
            });
    }

    function loadCategories() {
        fetch("{{ route('seller.pos.api.categories') }}")
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('category-filter');
                data.forEach(cat => {
                    select.insertAdjacentHTML('beforeend', `<option value="${cat.id}">${cat.nama_kategori}</option>`);
                });
            });
    }

    // --- 2. RENDER PRODUK ---
    function renderProducts(products) {
        const grid = document.getElementById('product-grid');
        grid.innerHTML = ''; // Clear existing
        
        if(products.length === 0) {
            grid.innerHTML = `<div class="col-12 text-center py-5 text-muted">Produk tidak ditemukan.</div>`;
            return;
        }

        products.forEach(p => {
            let imgUrl = p.gambar_utama ? `{{ asset('assets/uploads/products') }}/${p.gambar_utama}` : 'https://placehold.co/150?text=No+Image';
            let html = `
                <div class="col-6 col-md-4 col-xl-3">
                    <div class="product-card" onclick="addToCart(${p.id})">
                        <img src="${imgUrl}" alt="${p.nama_barang}">
                        <div class="product-info">
                            <div class="product-title" title="${p.nama_barang}">${p.nama_barang}</div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="product-price">${formatRupiah(p.harga)}</span>
                                <span class="product-stock">Stok: ${p.stok}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            grid.insertAdjacentHTML('beforeend', html);
        });
    }

    // --- 3. FILTER & SEARCH ---
    document.getElementById('search-input').addEventListener('input', filterProducts);
    document.getElementById('category-filter').addEventListener('change', filterProducts);

    function filterProducts() {
        let keyword = document.getElementById('search-input').value.toLowerCase();
        let categoryId = document.getElementById('category-filter').value;

        let filtered = allProducts.filter(p => {
            let matchCat = categoryId === 'all' || p.kategori_id == categoryId;
            let matchKey = p.nama_barang.toLowerCase().includes(keyword);
            return matchCat && matchKey;
        });
        renderProducts(filtered);
    }

    // --- 4. LOGIKA KERANJANG (CART) ---
    window.addToCart = function(productId) {
        let product = allProducts.find(p => p.id === productId);
        if(!product) return;

        let existing = cart.find(item => item.id === productId);
        if(existing) {
            if(existing.qty < product.stok) existing.qty++;
            else alert('Maksimal stok tercapai!');
        } else {
            cart.push({ id: product.id, nama_barang: product.nama_barang, harga: product.harga, qty: 1, stok: product.stok });
        }
        updateCartDisplay();
    };

    window.updateQty = function(productId, change) {
        let item = cart.find(i => i.id === productId);
        if(!item) return;

        let newQty = item.qty + change;
        if(newQty > 0 && newQty <= item.stok) {
            item.qty = newQty;
        } else if (newQty === 0) {
            cart = cart.filter(i => i.id !== productId);
        }
        updateCartDisplay();
    };

    window.removeCartItem = function(productId) {
        cart = cart.filter(i => i.id !== productId);
        updateCartDisplay();
    };

    document.getElementById('clear-cart-btn').addEventListener('click', () => {
        cart = [];
        updateCartDisplay();
    });

    function updateCartDisplay() {
        const container = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-message');
        
        document.querySelectorAll('.cart-item').forEach(e => e.remove()); // Bersihkan list
        
        currentTotal = 0;

        if(cart.length === 0) {
            emptyMsg.style.display = 'block';
            document.getElementById('process-payment-btn').disabled = true;
        } else {
            emptyMsg.style.display = 'none';
            document.getElementById('process-payment-btn').disabled = false;

            cart.forEach(item => {
                currentTotal += (item.harga * item.qty);
                let html = `
                    <div class="cart-item">
                        <div style="flex-grow: 1; padding-right: 10px;">
                            <div class="cart-item-title">${item.nama_barang}</div>
                            <div class="cart-item-price">${formatRupiah(item.harga)}</div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="qty-control">
                                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                                <input type="text" class="qty-input" value="${item.qty}" readonly>
                                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                            </div>
                            <button class="remove-btn" onclick="removeCartItem(${item.id})"><i class="mdi mdi-close-circle"></i></button>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        }

        document.getElementById('total-price').innerText = formatRupiah(currentTotal);
        calculateChange();
    }

    // --- 5. LOGIKA PEMBAYARAN & KEMBALIAN ---
    const amountInput = document.getElementById('amount-paid');
    const changeDisplay = document.getElementById('change-due');
    const paymentMethod = document.getElementById('payment-method');
    const cashInputGroup = document.getElementById('cash-input-group');

    paymentMethod.addEventListener('change', function() {
        if(this.value === 'Tunai') {
            cashInputGroup.style.display = 'block';
        } else {
            cashInputGroup.style.display = 'none';
            amountInput.value = '';
            changeDisplay.innerText = 'Rp 0';
        }
    });

    amountInput.addEventListener('input', calculateChange);

    document.querySelectorAll('.quick-cash-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            let val = this.getAttribute('data-amount');
            if(val === 'exact') amountInput.value = currentTotal;
            else amountInput.value = val;
            calculateChange();
        });
    });

    function calculateChange() {
        let paid = parseInt(amountInput.value) || 0;
        let change = paid - currentTotal;
        if(change < 0) {
            changeDisplay.innerText = "Uang Kurang!";
            changeDisplay.classList.replace('text-success', 'text-danger');
        } else {
            changeDisplay.innerText = formatRupiah(change);
            changeDisplay.classList.replace('text-danger', 'text-success');
        }
    }

    // --- 6. PROSES CHECKOUT AJAX ---
    document.getElementById('process-payment-btn').addEventListener('click', function() {
        let method = paymentMethod.value;
        let paid = parseInt(amountInput.value) || 0;

        if(method === 'Tunai' && paid < currentTotal) {
            Swal.fire('Peringatan', 'Jumlah uang pembayaran kurang dari Total Bayar!', 'warning');
            return;
        }

        Swal.fire({
            title: 'Proses Transaksi?',
            text: "Pastikan data pesanan sudah benar.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#111827',
            cancelButtonColor: '#d1d5db',
            confirmButtonText: 'Ya, Proses!'
        }).then((result) => {
            if (result.isConfirmed) {
                
                let payload = {
                    customer_name: document.getElementById('customer-name').value,
                    payment_method: method,
                    total: currentTotal,
                    cart: cart
                };

                fetch("{{ route('seller.pos.api.checkout') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(payload)
                })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire('Berhasil!', `Transaksi sukses. Invoice: ${data.invoice}`, 'success');
                        cart = []; // Kosongkan keranjang
                        document.getElementById('customer-name').value = '';
                        amountInput.value = '';
                        updateCartDisplay();
                        loadProducts(); // Refresh stok
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
                    }
                });
            }
        });
    });

    // Inisialisasi awal
    loadProducts();
    loadCategories();
});
</script>
@endpush