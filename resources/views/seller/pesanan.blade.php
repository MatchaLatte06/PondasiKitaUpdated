@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
    <style>
        /* Tweak tambahan agar tabel lebih lega */
        .table-mono th { vertical-align: middle; padding: 15px 20px; }
        .order-item-row td { padding: 20px; }
        .page-title-icon-mono {
            background-color: #111827;
            border-radius: 8px;
            width: 40px; height: 40px;
            display: flex; align-items: center; justify-content: center;
        }
        .page-title-icon-mono i { color: #ffffff; font-size: 1.5rem; line-height: 1; }
    </style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-package"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Daftar Pesanan</span>
        </div>
    </h3>
</div>

{{-- Notifikasi Sukses/Error --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px;">
        <i class="mdi mdi-alert-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="card shadow-sm border-0" style="border-radius: 16px; background: transparent;">
    <div class="card-body p-0">
        
        {{-- TABS FILTER STATUS --}}
        <div class="order-filter-tabs mb-4 bg-white p-3 pb-0 rounded shadow-sm">
            <a href="#" class="filter-tab active" data-status="">Semua</a>
            <a href="#" class="filter-tab" data-status="menunggu_pembayaran">Belum Dibayar</a>
            <a href="#" class="filter-tab" data-status="diproses">Perlu Diproses</a>
            <a href="#" class="filter-tab" data-status="siap_kirim">Siap Kirim</a>
            <a href="#" class="filter-tab" data-status="dikirim">Dikirim</a>
            <a href="#" class="filter-tab" data-status="sampai_tujuan">Selesai</a>
            <a href="#" class="filter-tab" data-status="dibatalkan">Dibatalkan</a>
        </div>
        
        {{-- SEARCH & MASS ACTION --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3 bg-white p-3 rounded shadow-sm">
            <div class="search-bar d-flex flex-grow-1 me-3 mb-2 mb-md-0" style="max-width: 400px;">
                <input type="text" id="orderSearchInput" class="form-control" placeholder="Cari nomor invoice atau pelanggan...">
                <button class="btn btn-secondary" type="button"><i class="mdi mdi-magnify"></i></button>
            </div>
            
            {{-- Form Terpisah untuk Update Massal --}}
            <form action="{{ route('seller.orders.massUpdate') }}" method="POST" id="mass-shipping-form">
                @csrf
                <button type="submit" id="btn-mass-shipping" class="btn-mono" disabled>
                    <i class="mdi mdi-truck-fast me-1"></i> Kirim Massal (<span id="selected-count">0</span>)
                </button>
            </form>
        </div>

        {{-- TABEL PESANAN --}}
        <div class="table-responsive bg-white rounded shadow-sm p-3">
            <table id="pesananTable" class="table-mono mt-0">
                <thead>
                    <tr>
                        <th width="5%" class="align-middle">
                            <label class="toggle-switch mb-0" style="transform: scale(0.8); transform-origin: left center;" title="Pilih Semua">
                                <input type="checkbox" id="select-all-orders">
                                <span class="toggle-slider"></span>
                            </label>
                        </th>
                        <th width="40%">Rincian Produk</th>
                        <th width="20%" class="text-center">Total Pesanan</th>
                        <th width="15%" class="text-center">Status Saat Ini</th>
                        <th width="20%" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    
                    {{-- 1. EMPTY STATE SERVER SIDE (Jika toko sama sekali belum punya pesanan) --}}
                    @if($groupedOrders->isEmpty())
                        <tr>
                            <td colspan="5" class="text-center align-middle" style="height: 50vh; vertical-align: middle !important;">
                                
                                <div class="empty-state border-0 bg-transparent d-flex flex-column justify-content-center align-items-center h-100 w-100">
                                    <i class="mdi mdi-package-variant-closed" style="font-size: 4rem; color: #d1d5db;"></i>
                                    <h5 class="mt-3 text-dark fw-bold">Belum ada pesanan yang masuk</h5>
                                    <p class="text-muted mb-0">Pesanan baru akan muncul di sini.</p>
                                </div>

                            </td>
                        </tr>
                    @else
                        
                        {{-- 2. EMPTY STATE CLIENT SIDE (Ditampilkan via JS jika Tab diklik tapi datanya 0) --}}
                        <tr id="dynamic-empty-state" style="display: none;">
                            <td colspan="5" class="text-center py-5">
                                <div class="empty-state border-0 bg-transparent">
                                    <i class="mdi mdi-text-box-search-outline" style="font-size: 4rem; color: #d1d5db;"></i>
                                    <h5 class="mt-3 text-dark fw-bold">Tidak ada pesanan</h5>
                                    <p class="text-muted" id="empty-state-text">Tidak ada pesanan pada status ini.</p>
                                </div>
                            </td>
                        </tr>

                        {{-- 3. LOOPING DATA PESANAN --}}
                        @foreach($groupedOrders as $invoice => $items)
                            
                            {{-- Header Grup Invoice --}}
                            <tr class="order-group-header" data-invoice="{{ $invoice }}">
                                <td colspan="5">
                                    <div class="order-header-info d-flex justify-content-between w-100">
                                        <div>
                                            <span class="invoice-number me-3"><i class="mdi mdi-file-document-outline text-muted me-1"></i>{{ $invoice }}</span>
                                            <span class="text-muted me-3"><i class="mdi mdi-calendar-clock text-muted me-1"></i>{{ date('d M Y, H:i', strtotime($items[0]->tanggal_transaksi)) }}</span>
                                        </div>
                                        <div>
                                            <span class="fw-bold text-dark"><i class="mdi mdi-account-circle-outline text-muted me-1"></i>{{ $items[0]->nama_pelanggan }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            {{-- Item di dalam Invoice --}}
                            @foreach($items as $item)
                                <tr class="order-item-row" data-invoice="{{ $invoice }}" data-status="{{ $item->status_pesanan_item }}">
                                    
                                    {{-- Checkbox untuk mass-update --}}
                                    <td class="align-middle">
                                        @if($item->status_pesanan_item == 'siap_kirim')
                                            <label class="toggle-switch mb-0" style="transform: scale(0.8); transform-origin: left center;">
                                                <input type="checkbox" name="detail_ids[]" value="{{ $item->detail_id }}" class="order-checkbox" form="mass-shipping-form">
                                                <span class="toggle-slider"></span>
                                            </label>
                                        @endif
                                    </td>
                                    
                                    {{-- Info Produk --}}
                                    <td class="align-middle">
                                        <div class="product-info-cell">
                                            <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" alt="Produk" class="product-thumb">
                                            <div class="d-flex flex-column justify-content-center">
                                                <span class="product-name mb-1">{{ $item->nama_barang }}</span>
                                                <div>
                                                    <span class="product-qty me-2">Qty: {{ $item->jumlah }}</span>
                                                    <span class="text-muted small">x Rp {{ number_format($item->harga ?? 0, 0, ',', '.') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Harga Subtotal --}}
                                    <td class="align-middle text-center">
                                        <span style="font-size: 0.8rem; color: #6b7280; text-transform: uppercase; display: block; margin-bottom: 4px;">Subtotal</span>
                                        <strong style="color: #111827; font-size: 1.1rem;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong>
                                    </td>
                                    
                                    {{-- Status Label --}}
                                    <td class="align-middle text-center">
                                        @php
                                            $statusText = ucwords(str_replace('_', ' ', $item->status_pesanan_item));
                                            $badgeClass = 'bg-diproses';
                                            
                                            if($item->status_pesanan_item == 'menunggu_pembayaran') { $badgeClass = 'bg-menunggu'; $statusText = 'Belum Bayar'; }
                                            elseif($item->status_pesanan_item == 'siap_kirim') { $badgeClass = 'bg-siap-kirim'; }
                                            elseif($item->status_pesanan_item == 'dikirim') { $badgeClass = 'bg-dikirim'; }
                                            elseif($item->status_pesanan_item == 'sampai_tujuan') { $badgeClass = 'bg-selesai'; $statusText = 'Selesai'; }
                                            elseif(in_array($item->status_pesanan_item, ['dibatalkan', 'ditolak'])) { $badgeClass = 'bg-batal'; }
                                        @endphp
                                        <span class="badge-mono {{ $badgeClass }} d-inline-block">{{ $statusText }}</span>
                                    </td>

                                    {{-- Aksi (Form Ubah Status Satuan) --}}
                                    <td class="align-middle text-center">
                                        @if(in_array($item->status_pesanan_item, ['menunggu_pembayaran', 'sampai_tujuan', 'dibatalkan', 'ditolak']))
                                            <button type="button" class="btn-mono-outline w-100 py-2">Lihat Detail</button>
                                        @else
                                            {{-- Form khusus untuk 1 item --}}
                                            <form action="{{ route('seller.orders.updateStatus') }}" method="POST" class="d-flex flex-column gap-2 m-0">
                                                @csrf
                                                <input type="hidden" name="detail_id" value="{{ $item->detail_id }}">
                                                <select name="status_baru" class="form-select-mono w-100">
                                                    <option value="diproses" {{ $item->status_pesanan_item == 'diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                                    <option value="siap_kirim" {{ $item->status_pesanan_item == 'siap_kirim' ? 'selected' : '' }}>Siap Dikirim</option>
                                                    <option value="dikirim" {{ $item->status_pesanan_item == 'dikirim' ? 'selected' : '' }}>Dalam Pengiriman</option>
                                                    <option value="ditolak" {{ $item->status_pesanan_item == 'ditolak' ? 'selected' : '' }}>Tolak Pesanan</option>
                                                </select>
                                                <button type="submit" class="btn-mono w-100 py-1" style="font-size: 0.85rem;">Perbarui</button>
                                            </form>
                                        @endif
                                    </td>

                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. LOGIKA PENGIRIMAN MASSAL (CHECKBOX) ---
    const selectAll = document.getElementById('select-all-orders');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    const massShippingBtn = document.getElementById('btn-mass-shipping');
    const selectedCountSpan = document.getElementById('selected-count');

    function updateButtonState() {
        const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
        if(selectedCountSpan) selectedCountSpan.textContent = checkedCount;
        if(massShippingBtn) massShippingBtn.disabled = checkedCount === 0;
    }

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            // Hanya ceklis item yang sedang terlihat (tidak di-hide oleh filter)
            checkboxes.forEach(cb => { 
                let row = cb.closest('tr');
                if(row.style.display !== 'none') {
                    cb.checked = this.checked; 
                }
            });
            updateButtonState();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked && selectAll) selectAll.checked = false;
            updateButtonState();
        });
    });

    // --- 2. LOGIKA SEARCH BAR (Vanilla JS) ---
    const searchInput = document.getElementById('orderSearchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#pesananTable tbody tr.order-group-header, #pesananTable tbody tr.order-item-row');
            
            rows.forEach(row => {
                if(row.id !== 'dynamic-empty-state') { // Abaikan baris empty state
                    let text = row.textContent.toLowerCase();
                    row.style.display = text.includes(filter) ? '' : 'none';
                }
            });
        });
    }

    // --- 3. LOGIKA FILTER TAB DENGAN DYNAMIC EMPTY STATE ---
    const tabs = document.querySelectorAll('.filter-tab');
    const emptyStateRow = document.getElementById('dynamic-empty-state');
    const emptyStateText = document.getElementById('empty-state-text');

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Ubah class active
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            let filterStatus = this.getAttribute('data-status');
            let itemRows = document.querySelectorAll('.order-item-row');
            let visibleItemCount = 0; // Menghitung item yang muncul
            
            // Uncheck semua checkbox saat pindah tab
            checkboxes.forEach(cb => cb.checked = false);
            if(selectAll) selectAll.checked = false;
            updateButtonState();
            
            // Sembunyikan/Tampilkan Item
            itemRows.forEach(row => {
                let rowStatus = row.getAttribute('data-status');
                if(filterStatus === '' || rowStatus === filterStatus) {
                    row.style.display = '';
                    visibleItemCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Sembunyikan header grup invoice jika semua item di dalamnya tersembunyi
            document.querySelectorAll('.order-group-header').forEach(header => {
                let invoice = header.getAttribute('data-invoice');
                let itemsVisible = Array.from(document.querySelectorAll(`.order-item-row[data-invoice="${invoice}"]`)).filter(el => el.style.display !== 'none').length;
                header.style.display = (itemsVisible > 0) ? '' : 'none';
            });

            // Munculkan tulisan "Tidak ada pesanan" jika hasil tab kosong
            if(emptyStateRow) {
                if (visibleItemCount === 0) {
                    emptyStateRow.style.display = '';
                    emptyStateText.innerHTML = `Tidak ada pesanan pada status <strong>"${this.innerText}"</strong>.`;
                } else {
                    emptyStateRow.style.display = 'none';
                }
            }
        });
    });
});
</script>
@endpush