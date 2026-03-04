@extends('layouts.seller')

@section('title', 'Mesin Kasir (POS)')

@section('content')
<style>
    /* CSS LANGSUNG DI BODY AGAR ANTI GAGAL */
    :root {
        --pos-dark: #0f172a;
        --pos-light: #f8fafc;
        --pos-border: #cbd5e1;
        --pos-primary: #0284c7;
        --pos-success: #059669;
    }

    .pos-wrapper {
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        height: calc(100vh - 100px);
        display: flex;
        gap: 20px;
        overflow: hidden;
    }

    /* PANEL KIRI: KATALOG BARANG */
    .pos-catalog {
        flex: 2;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--pos-border);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    
    .catalog-header {
        padding: 16px 20px;
        background: #f1f5f9;
        border-bottom: 2px solid var(--pos-border);
    }
    
    .search-barcode {
        font-family: monospace;
        font-size: 16px;
        font-weight: 700;
        border: 2px solid #94a3b8;
        border-radius: 8px;
        padding: 12px 16px;
    }
    .search-barcode:focus {
        border-color: var(--pos-primary);
        box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.2);
        outline: none;
    }

    .table-container { flex: 1; overflow-y: auto; }
    .pos-table { width: 100%; border-collapse: collapse; }
    .pos-table th { 
        position: sticky; 
        top: 0; 
        background: #e2e8f0; 
        color: #334155; 
        font-size: 11px; 
        text-transform: uppercase; 
        padding: 12px 16px; 
        text-align: left; 
        z-index: 10;
        border-bottom: 2px solid #cbd5e1;
    }
    .pos-table td { padding: 12px 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .pos-table tbody tr { cursor: pointer; transition: 0.1s; }
    .pos-table tbody tr:hover { background-color: #eff6ff; }
    
    .sku-badge { background: white; border: 1px solid #cbd5e1; padding: 4px 8px; border-radius: 4px; font-family: monospace; font-size: 13px; font-weight: bold; color: #475569; }
    .p-name { font-weight: 700; color: var(--pos-dark); font-size: 14px; margin-bottom: 2px; }
    .p-price { font-weight: 800; color: var(--pos-primary); font-size: 15px; }

    /* PANEL KANAN: KERANJANG TRANSAKSI */
    .pos-cart {
        flex: 1;
        min-width: 360px;
        max-width: 400px;
        background: white;
        border-radius: 12px;
        border: 1px solid var(--pos-border);
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }
    
    .cart-header { background: var(--pos-dark); color: white; padding: 16px 20px; border-radius: 12px 12px 0 0; display: flex; justify-content: space-between; align-items: center; }
    .cart-items { flex: 1; overflow-y: auto; background: #fafafa; padding: 12px; }
    
    .cart-item-card { background: white; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center; }
    .qty-group { display: flex; align-items: center; background: #f1f5f9; border-radius: 6px; border: 1px solid #cbd5e1; overflow: hidden; }
    .qty-btn { border: none; background: transparent; width: 28px; height: 28px; font-weight: bold; cursor: pointer; color: #475569; }
    .qty-btn:hover { background: #e2e8f0; }
    .qty-input { width: 35px; border: none; background: transparent; text-align: center; font-weight: bold; font-size: 14px; outline: none; pointer-events: none; }
    
    .cart-footer { background: white; border-top: 2px dashed #cbd5e1; padding: 20px; border-radius: 0 0 12px 12px; }
    .total-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 15px; margin-bottom: 15px; }
    
    .btn-cash { background: white; border: 1px solid #cbd5e1; color: #475569; font-weight: 700; font-size: 14px; padding: 10px; border-radius: 6px; cursor: pointer; transition: 0.2s; width: 100%; }
    .btn-cash:hover { background: var(--pos-dark); color: white; border-color: var(--pos-dark); }
    
    .btn-checkout { background: var(--pos-success); color: white; font-size: 16px; font-weight: 800; padding: 16px; border-radius: 8px; border: none; width: 100%; text-transform: uppercase; cursor: pointer; transition: 0.2s; }
    .btn-checkout:hover { background: #047857; }
    .btn-checkout:disabled { background: #94a3b8; cursor: not-allowed; }

    /* Custom Scrollbar */
    .table-container::-webkit-scrollbar, .cart-items::-webkit-scrollbar { width: 8px; }
    .table-container::-webkit-scrollbar-thumb, .cart-items::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
</style>

<div class="pos-wrapper">
    {{-- KIRI: KATALOG BARANG --}}
    <div class="pos-catalog">
        <div class="catalog-header">
            <div class="row g-2">
                <div class="col-md-8">
                    <input type="text" id="search-input" class="form-control search-barcode w-100" placeholder="Klik disini & Scan Barcode (F2)..." autocomplete="off" autofocus>
                </div>
                <div class="col-md-4">
                    <select id="category-filter" class="form-select form-select-lg fw-bold w-100" style="height: 100%;">
                        <option value="all">SEMUA KATEGORI</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="pos-table">
                <thead>
                    <tr>
                        <th width="15%">KODE / SKU</th>
                        <th width="50%">NAMA MATERIAL</th>
                        <th width="15%" class="text-center">STOK</th>
                        <th width="20%" class="text-end">HARGA (Rp)</th>
                    </tr>
                </thead>
                <tbody id="product-table-body">
                    <tr><td colspan="4" class="text-center py-5 fw-bold text-muted">Memuat data gudang...</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- KANAN: KASIR TRANSAKSI --}}
    <div class="pos-cart">
        <div class="cart-header">
            <h5 class="m-0 fw-bold fs-6"><i class="mdi mdi-cash-register me-2"></i>Transaksi Kasir</h5>
            <button id="clear-cart-btn" class="btn btn-sm btn-outline-light border-0 py-0"><i class="mdi mdi-trash-can"></i> Reset</button>
        </div>
        
        <div class="p-3 bg-white border-bottom">
            <input type="text" id="customer-name" class="form-control fw-bold bg-light" placeholder="Nama Pelanggan (Umum)">
        </div>

        <div class="cart-items" id="cart-items">
            <div id="empty-cart-message" class="text-center py-5 text-muted mt-4">
                <i class="mdi mdi-barcode-scan" style="font-size: 3rem; opacity: 0.2;"></i>
                <p class="mt-2 fw-bold" style="font-size: 13px;">Belum ada barang masuk.<br>Gunakan Scanner / Klik dari daftar.</p>
            </div>
        </div>

        <div class="cart-footer">
            <div class="total-box d-flex justify-content-between align-items-center">
                <span class="fw-bold text-muted">TOTAL TAGIHAN</span>
                <span id="total-price" class="fs-2 fw-bold" style="color: var(--pos-dark);">0</span>
            </div>

            <div class="mb-3">
                <div class="input-group mb-2">
                    <span class="input-group-text bg-light fw-bold border-secondary">Rp</span>
                    <input type="number" id="amount-paid" class="form-control form-control-lg fw-bold fs-4 text-end border-secondary" placeholder="0">
                </div>
                <div class="row g-2">
                    <div class="col-4"><button type="button" class="btn-cash" data-amount="exact">Uang Pas</button></div>
                    <div class="col-4"><button type="button" class="btn-cash" data-amount="50000">50 Rb</button></div>
                    <div class="col-4"><button type="button" class="btn-cash" data-amount="100000">100 Rb</button></div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 px-1 border-bottom pb-3">
                <span class="fw-bold text-muted">KEMBALIAN</span>
                <span id="change-due" class="fs-4 fw-bold text-danger">Rp 0</span>
            </div>

            <button id="process-payment-btn" class="btn-checkout" disabled>
                <i class="mdi mdi-printer me-1"></i> CETAK STRUK (F9)
            </button>
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
    
    const formatRp = (num) => new Intl.NumberFormat('id-ID').format(num);

    // 1. Fetch API Data
    function loadProducts() {
        fetch("{{ route('seller.pos.api.products') }}")
            .then(res => res.json())
            .then(data => {
                allProducts = data;
                renderProducts(allProducts);
            });
    }

    function loadCategories() {
        fetch("{{ route('seller.pos.api.categories') }}")
            .then(res => res.json())
            .then(data => {
                const select = document.getElementById('category-filter');
                data.forEach(cat => select.insertAdjacentHTML('beforeend', `<option value="${cat.id}">${cat.nama_kategori}</option>`));
            });
    }

    // 2. Render Daftar Barang (Padat & Rapi)
    function renderProducts(products) {
        const tbody = document.getElementById('product-table-body');
        tbody.innerHTML = ''; 
        
        if(products.length === 0) {
            tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-danger fw-bold">Barang tidak ditemukan</td></tr>`;
            return;
        }

        products.forEach(p => {
            let sku = p.kode_barang ? p.kode_barang : 'SKU-'+String(p.id).padStart(4, '0');
            let stockClass = p.stok <= 5 ? 'text-danger' : 'text-success';
            
            let html = `
                <tr onclick="addToCart(${p.id})">
                    <td><span class="sku-badge">${sku}</span></td>
                    <td>
                        <div class="p-name">${p.nama_barang}</div>
                    </td>
                    <td class="text-center fw-bold ${stockClass}">${p.stok}</td>
                    <td class="text-end p-price">${formatRp(p.harga)}</td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', html);
        });
    }

    // 3. Sistem Pencarian & Scanner Barcode (Sangat Responsif)
    const searchInput = document.getElementById('search-input');
    
    searchInput.addEventListener('input', filterProducts);
    
    // Logika Scanner Barcode (Scanner otomatis menekan 'Enter' setelah scan)
    searchInput.addEventListener('keypress', function(e) {
        if(e.key === 'Enter') {
            e.preventDefault();
            let keyword = this.value.toLowerCase().trim();
            
            // Cari kecocokan persis pada KODE BARANG terlebih dahulu
            let matchedProduct = allProducts.find(p => (p.kode_barang && p.kode_barang.toLowerCase() === keyword));
            
            // Jika tidak ada di kode, cari kecocokan persis di nama
            if(!matchedProduct) {
                matchedProduct = allProducts.find(p => p.nama_barang.toLowerCase() === keyword);
            }

            if(matchedProduct) {
                addToCart(matchedProduct.id);
                this.value = ''; // Kosongkan input setelah sukses scan
                filterProducts(); 
            } else {
                // Jika barcode tidak ditemukan di database
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: 'Barcode/Barang tidak ditemukan!',
                    showConfirmButton: false, timer: 1500
                });
                this.value = '';
            }
        }
    });

    document.getElementById('category-filter').addEventListener('change', filterProducts);

    function filterProducts() {
        let keyword = searchInput.value.toLowerCase();
        let categoryId = document.getElementById('category-filter').value;
        
        let filtered = allProducts.filter(p => {
            let matchCat = categoryId === 'all' || p.kategori_id == categoryId;
            let kode = p.kode_barang ? p.kode_barang.toLowerCase() : '';
            let matchKey = p.nama_barang.toLowerCase().includes(keyword) || kode.includes(keyword);
            return matchCat && matchKey;
        });
        renderProducts(filtered);
    }

    // Hotkeys Kasir
    document.addEventListener('keydown', function(e) {
        if(e.key === 'F2') { e.preventDefault(); searchInput.focus(); }
        if(e.key === 'F9') { e.preventDefault(); document.getElementById('process-payment-btn').click(); }
    });

    // 4. Manajemen Keranjang
    window.addToCart = function(productId) {
        let product = allProducts.find(p => p.id === productId);
        if(!product) return;

        let existing = cart.find(item => item.id === productId);
        if(existing) {
            if(existing.qty < product.stok) {
                existing.qty++;
                playBeep();
            } else {
                Swal.fire({toast: true, position: 'top-end', icon: 'warning', title: 'Stok Fisik Habis!', showConfirmButton: false, timer: 1500});
            }
        } else {
            cart.push({ id: product.id, nama_barang: product.nama_barang, harga: product.harga, qty: 1, stok: product.stok });
            playBeep();
        }
        updateCartDisplay();
    };

    function playBeep() {
        // Efek suara scan barcode (opsional jika ingin tambah audio file)
        // new Audio('beep.mp3').play().catch(e => {}); 
    }

    window.updateQty = function(productId, change) {
        let item = cart.find(i => i.id === productId);
        if(!item) return;
        let newQty = item.qty + change;
        if(newQty > 0 && newQty <= item.stok) item.qty = newQty;
        else if (newQty === 0) cart = cart.filter(i => i.id !== productId);
        updateCartDisplay();
    };

    document.getElementById('clear-cart-btn').addEventListener('click', () => { cart = []; updateCartDisplay(); amountInput.value = ''; calculateChange(); });

    function updateCartDisplay() {
        const container = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('empty-cart-message');
        document.querySelectorAll('.cart-item-card').forEach(e => e.remove());
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
                    <div class="cart-item-card">
                        <div style="flex-grow: 1; padding-right: 10px;">
                            <h6 style="font-size:13px; font-weight:800; margin:0 0 4px 0; color:#0f172a;">${item.nama_barang}</h6>
                            <span style="font-size:13px; color:#0284c7; font-weight:700;">Rp ${formatRp(item.harga)}</span>
                        </div>
                        <div class="d-flex flex-column align-items-end">
                            <div class="qty-group mb-1">
                                <button class="qty-btn" onclick="updateQty(${item.id}, -1)">-</button>
                                <input type="text" class="qty-input" value="${item.qty}" readonly>
                                <button class="qty-btn" onclick="updateQty(${item.id}, 1)">+</button>
                            </div>
                            <span class="fw-bold text-dark" style="font-size:14px;">${formatRp(item.harga * item.qty)}</span>
                        </div>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', html);
            });
        }
        document.getElementById('total-price').innerText = formatRp(currentTotal);
        calculateChange();
    }

    // 5. Pembayaran & Kembalian
    const amountInput = document.getElementById('amount-paid');
    const changeDisplay = document.getElementById('change-due');

    amountInput.addEventListener('input', calculateChange);
    document.querySelectorAll('.btn-cash').forEach(btn => {
        btn.addEventListener('click', function() {
            let val = this.getAttribute('data-amount');
            amountInput.value = val === 'exact' ? currentTotal : val;
            calculateChange();
        });
    });

    function calculateChange() {
        let paid = parseInt(amountInput.value) || 0;
        let change = paid - currentTotal;
        if(currentTotal === 0) { changeDisplay.innerText = "Rp 0"; return; }
        
        if(change < 0) {
            changeDisplay.innerText = "UANG KURANG";
            changeDisplay.className = "fs-4 fw-bold text-danger";
        } else {
            changeDisplay.innerText = "Rp " + formatRp(change);
            changeDisplay.className = "fs-4 fw-bold text-success";
        }
    }

    // 6. Checkout ke Database
    document.getElementById('process-payment-btn').addEventListener('click', function() {
        let paid = parseInt(amountInput.value) || 0;
        if(paid < currentTotal) {
            Swal.fire('Pembayaran Gagal', 'Jumlah uang tunai kurang dari total tagihan.', 'error');
            return;
        }

        // Tampilkan loading button
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> MEMPROSES...';
        this.disabled = true;

        let payload = {
            customer_name: document.getElementById('customer-name').value || 'Pelanggan Walk-in',
            payment_method: 'Tunai Kasir',
            total: currentTotal,
            cart: cart
        };

        fetch("{{ route('seller.pos.api.checkout') }}", {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    title: 'TRANSAKSI LUNAS!',
                    html: `Kembalian: <b class="text-success fs-2 d-block my-2">Rp ${formatRp(paid - currentTotal)}</b><span class="text-muted">No. Struk: ${data.invoice}</span>`,
                    icon: 'success',
                    confirmButtonText: '<i class="mdi mdi-printer"></i> Selesai & Cetak Baru',
                    confirmButtonColor: '#0f172a',
                    allowOutsideClick: false
                }).then(() => {
                    // Reset POS untuk pelanggan berikutnya
                    cart = []; amountInput.value = ''; document.getElementById('customer-name').value = '';
                    updateCartDisplay(); loadProducts(); searchInput.focus();
                    document.getElementById('process-payment-btn').innerHTML = '<i class="mdi mdi-printer me-1"></i> CETAK STRUK (F9)';
                });
            } else {
                Swal.fire('Error Database', data.message, 'error');
                document.getElementById('process-payment-btn').innerHTML = '<i class="mdi mdi-printer me-1"></i> CETAK STRUK (F9)';
                document.getElementById('process-payment-btn').disabled = false;
            }
        }).catch(err => {
            Swal.fire('Koneksi Terputus', 'Periksa koneksi internet Anda.', 'error');
            document.getElementById('process-payment-btn').innerHTML = '<i class="mdi mdi-printer me-1"></i> CETAK STRUK (F9)';
            document.getElementById('process-payment-btn').disabled = false;
        });
    });

    // Inisialisasi awal
    loadProducts();
    loadCategories();
});
</script>
@endpush