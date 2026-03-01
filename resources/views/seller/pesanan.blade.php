@extends('layouts.seller')

@section('title', 'Manajemen Pesanan')

@section('content')
<style>
    /* === B2B INDUSTRIAL SELLER THEME === */
    :root {
        --b2b-primary: #1e293b;
        --b2b-accent: #2563eb;
        --b2b-border: #e2e8f0;
        --b2b-bg: #f8fafc;
        --text-main: #0f172a;
        --text-muted: #64748b;
    }

    body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }

    /* HEADER */
    .page-title-box { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .icon-wrapper { background: var(--b2b-primary); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .page-title h3 { margin: 0; font-size: 22px; font-weight: 800; color: var(--text-main); }
    .page-title p { margin: 0; font-size: 14px; color: var(--text-muted); font-weight: 500; }

    /* FILTER TABS (MODERN PILL) */
    .filter-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 10px; margin-bottom: 20px; border-bottom: 2px solid var(--b2b-border); scrollbar-width: none; }
    .filter-tabs::-webkit-scrollbar { display: none; }
    .f-tab { padding: 10px 20px; border-radius: 8px; color: var(--text-muted); font-weight: 600; font-size: 14px; text-decoration: none; transition: 0.2s; white-space: nowrap; border: 1px solid transparent; }
    .f-tab:hover { background: var(--b2b-bg); color: var(--text-main); }
    .f-tab.active { background: var(--b2b-primary); color: white; border-color: var(--b2b-primary); box-shadow: 0 4px 6px rgba(30, 41, 59, 0.2); }

    /* TOOLBAR (SEARCH & MASS ACTION) */
    .order-toolbar { background: white; padding: 16px; border-radius: 12px; border: 1px solid var(--b2b-border); display: flex; justify-content: space-between; flex-wrap: wrap; gap: 16px; margin-bottom: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .search-box { display: flex; flex-grow: 1; max-width: 450px; }
    .search-box input { border-radius: 8px 0 0 8px; border: 1px solid var(--b2b-border); border-right: none; box-shadow: none; font-weight: 500; }
    .search-box input:focus { border-color: var(--b2b-accent); outline: none; }
    .search-box button { border-radius: 0 8px 8px 0; background: var(--b2b-bg); border: 1px solid var(--b2b-border); color: var(--text-muted); padding: 0 20px; transition: 0.2s; }
    .search-box button:hover { background: var(--b2b-border); color: var(--text-main); }
    
    .btn-mass { background: var(--b2b-primary); color: white; font-weight: 700; border: none; padding: 10px 24px; border-radius: 8px; transition: 0.2s; display: flex; align-items: center; gap: 8px; }
    .btn-mass:hover:not(:disabled) { background: #0f172a; transform: translateY(-2px); }
    .btn-mass:disabled { background: #cbd5e1; cursor: not-allowed; }

    /* ORDER CARD (PENGGANTI TABEL KUNO) */
    .order-card { background: white; border-radius: 16px; border: 1px solid var(--b2b-border); margin-bottom: 20px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); transition: 0.2s; }
    .order-card:hover { border-color: #94a3b8; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    .oc-header { background: var(--b2b-bg); padding: 16px 24px; border-bottom: 1px solid var(--b2b-border); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; }
    .oc-inv { font-family: monospace; font-size: 16px; font-weight: 800; color: var(--b2b-accent); display: flex; align-items: center; gap: 8px; }
    .oc-buyer { font-weight: 700; color: var(--text-main); font-size: 14px; display: flex; align-items: center; gap: 6px; }
    .oc-date { font-size: 13px; color: var(--text-muted); font-weight: 500; }

    .oc-body { display: flex; flex-direction: column; }
    .oc-item { display: flex; padding: 20px 24px; border-bottom: 1px dashed var(--b2b-border); gap: 20px; align-items: center; flex-wrap: wrap; }
    .oc-item:last-child { border-bottom: none; }
    
    /* Custom B2B Checkbox */
    .b2b-checkbox { width: 22px; height: 22px; cursor: pointer; accent-color: var(--b2b-accent); }
    
    .item-product { display: flex; gap: 16px; flex: 2; min-width: 250px; }
    .item-img { width: 70px; height: 70px; border-radius: 8px; object-fit: cover; border: 1px solid var(--b2b-border); }
    .item-detail h6 { font-size: 15px; font-weight: 700; color: var(--text-main); margin: 0 0 6px 0; line-height: 1.4; }
    .item-qty { font-size: 13px; font-weight: 600; color: var(--text-muted); background: var(--b2b-bg); padding: 4px 10px; border-radius: 6px; display: inline-block; border: 1px solid var(--b2b-border); }

    .item-price { flex: 1; text-align: center; min-width: 120px; }
    .price-label { font-size: 11px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px; display: block; }
    .price-val { font-size: 16px; font-weight: 800; color: var(--text-main); }

    .item-status { flex: 1; text-align: center; min-width: 150px; }
    .badge-status { padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; border: 1px solid transparent; }
    .st-bayar { background: #fef3c7; color: #b45309; border-color: #fde68a; }
    .st-proses { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .st-siap { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .st-kirim { background: #ede9fe; color: #6d28d9; border-color: #ddd6fe; }
    .st-selesai { background: #f1f5f9; color: #334155; border-color: #cbd5e1; }
    .st-batal { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

    .item-action { flex: 1; min-width: 180px; display: flex; flex-direction: column; gap: 8px; }
    .action-select { border-radius: 8px; font-size: 13px; font-weight: 600; border-color: var(--b2b-border); color: var(--text-main); padding: 8px; cursor: pointer; }
    .action-select:focus { border-color: var(--b2b-accent); box-shadow: none; }
    .btn-update { background: white; border: 1px solid var(--b2b-primary); color: var(--b2b-primary); font-weight: 700; font-size: 13px; border-radius: 8px; padding: 8px; transition: 0.2s; }
    .btn-update:hover { background: var(--b2b-primary); color: white; }
    .btn-detail { background: var(--b2b-bg); border: 1px solid var(--b2b-border); color: var(--text-muted); font-weight: 700; font-size: 13px; border-radius: 8px; padding: 8px; text-decoration: none; text-align: center; transition: 0.2s; display: block; }
    .btn-detail:hover { background: var(--b2b-border); color: var(--text-main); }
</style>

{{-- Notifikasi --}}
@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 fw-bold" style="background: #ecfccb; color: #b45309;">
        <i class="mdi mdi-check-decagram me-2 fs-5 align-middle"></i> {{ session('success') }}
    </div>
@endif

<div class="page-title-box">
    <div class="icon-wrapper"><i class="mdi mdi-clipboard-text-outline"></i></div>
    <div class="page-title">
        <h3>Manajemen Pesanan</h3>
        <p>Proses invoice pembeli, atur pengiriman, dan kelola logistik toko material Anda.</p>
    </div>
</div>

{{-- TABS FILTER --}}
<div class="filter-tabs" id="statusTabs">
    <a href="#" class="f-tab active" data-status="">Semua Pesanan</a>
    <a href="#" class="f-tab" data-status="menunggu_pembayaran">Belum Dibayar</a>
    <a href="#" class="f-tab" data-status="diproses">Perlu Diproses</a>
    <a href="#" class="f-tab" data-status="siap_kirim">Siap Kirim / Angkut</a>
    <a href="#" class="f-tab" data-status="dikirim">Sedang Dikirim</a>
    <a href="#" class="f-tab" data-status="sampai_tujuan">Selesai</a>
    <a href="#" class="f-tab" data-status="dibatalkan">Dibatalkan</a>
</div>

{{-- TOOLBAR --}}
<div class="order-toolbar">
    <div class="search-box">
        <input type="text" id="orderSearchInput" class="form-control" placeholder="Cari No. Invoice atau Nama Pembeli...">
        <button type="button"><i class="mdi mdi-magnify fs-5"></i></button>
    </div>
    
    <form action="{{ route('seller.orders.massUpdate') }}" method="POST" id="mass-shipping-form" class="m-0">
        @csrf
        <div class="d-flex align-items-center gap-3">
            <label class="d-flex align-items-center gap-2 fw-bold text-dark" style="cursor: pointer;">
                <input type="checkbox" id="select-all-orders" class="b2b-checkbox"> Pilih Semua
            </label>
            <button type="button" id="btn-mass-shipping" class="btn-mass" disabled>
                <i class="mdi mdi-truck-fast"></i> Proses Kirim (<span id="selected-count">0</span>)
            </button>
        </div>
    </form>
</div>

{{-- KONTEN PESANAN --}}
<div id="orders-container">
    @if($groupedOrders->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 border">
            <i class="mdi mdi-package-variant-closed mb-3" style="font-size: 5rem; color: #cbd5e1;"></i>
            <h4 class="fw-bold text-dark">Gudang Sedang Sepi</h4>
            <p class="text-muted">Belum ada pesanan yang masuk ke toko Anda.</p>
        </div>
    @else
        <div id="dynamic-empty-state" style="display: none;" class="text-center py-5 bg-white rounded-4 border mb-4">
            <i class="mdi mdi-clipboard-search-outline mb-3" style="font-size: 4rem; color: #cbd5e1;"></i>
            <h5 class="fw-bold text-dark">Tidak Ada Data</h5>
            <p class="text-muted" id="empty-state-text">Tidak ada pesanan pada status ini.</p>
        </div>

        @foreach($groupedOrders as $invoice => $items)
            <div class="order-card order-group" data-invoice="{{ $invoice }}">
                {{-- Header Invoice --}}
                <div class="oc-header">
                    <div class="d-flex flex-wrap gap-4 align-items-center">
                        <div class="oc-inv"><i class="mdi mdi-receipt-text"></i> {{ $invoice }}</div>
                        <div class="oc-buyer"><i class="mdi mdi-account-hard-hat"></i> {{ $items[0]->nama_pelanggan }}</div>
                    </div>
                    <div class="oc-date"><i class="mdi mdi-calendar-clock me-1"></i> {{ date('d M Y, H:i', strtotime($items[0]->tanggal_transaksi)) }} WIB</div>
                </div>

                {{-- Isi Item --}}
                <div class="oc-body">
                    @foreach($items as $item)
                        @php
                            $status = $item->status_pesanan_item;
                            $badgeClass = 'st-proses'; $statusText = 'Diproses';
                            
                            if($status == 'menunggu_pembayaran') { $badgeClass = 'st-bayar'; $statusText = 'Belum Bayar'; }
                            elseif($status == 'siap_kirim') { $badgeClass = 'st-siap'; $statusText = 'Siap Angkut'; }
                            elseif($status == 'dikirim') { $badgeClass = 'st-kirim'; $statusText = 'Dikirim'; }
                            elseif($status == 'sampai_tujuan') { $badgeClass = 'st-selesai'; $statusText = 'Selesai'; }
                            elseif(in_array($status, ['dibatalkan', 'ditolak'])) { $badgeClass = 'st-batal'; $statusText = 'Batal'; }
                        @endphp

                        <div class="oc-item order-item-row" data-invoice="{{ $invoice }}" data-status="{{ $status }}">
                            
                            {{-- Checkbox --}}
                            <div style="width: 30px;">
                                @if($status == 'siap_kirim')
                                    <input type="checkbox" name="detail_ids[]" value="{{ $item->detail_id }}" class="b2b-checkbox order-checkbox" form="mass-shipping-form">
                                @endif
                            </div>

                            {{-- Info Barang --}}
                            <div class="item-product">
                                <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" class="item-img" alt="Material">
                                <div class="item-detail">
                                    <h6>{{ $item->nama_barang }}</h6>
                                    <div class="item-qty">Qty: {{ $item->jumlah }}</div>
                                </div>
                            </div>

                            {{-- Harga --}}
                            <div class="item-price">
                                <span class="price-label">Subtotal</span>
                                <span class="price-val">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>

                            {{-- Status --}}
                            <div class="item-status">
                                <span class="badge-status {{ $badgeClass }}">{{ $statusText }}</span>
                            </div>

                            {{-- Aksi --}}
                            <div class="item-action">
                                @if(in_array($status, ['diproses', 'siap_kirim']))
                                    <form action="{{ route('seller.orders.updateStatus') }}" method="POST" class="update-status-form m-0 d-flex flex-column gap-2">
                                        @csrf
                                        <input type="hidden" name="detail_id" value="{{ $item->detail_id }}">
                                        <select name="status_baru" class="form-select action-select">
                                            <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>1. Siapkan Barang</option>
                                            <option value="siap_kirim" {{ $status == 'siap_kirim' ? 'selected' : '' }}>2. Siap Diangkut</option>
                                            <option value="dikirim" {{ $status == 'dikirim' ? 'selected' : '' }}>3. Kirim via Armada</option>
                                            <option value="ditolak">Tolak Pesanan (Habis)</option>
                                        </select>
                                        <button type="button" class="btn-update w-100 btn-submit-single"><i class="mdi mdi-check-circle-outline"></i> Simpan Status</button>
                                    </form>
                                @else
                                    {{-- Nanti ganti # dengan rute detail pesanan jika sudah dibuat --}}
                                    <a href="#" class="btn-detail w-100"><i class="mdi mdi-file-find-outline"></i> Lihat Rincian</a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. FILTER TABS LOGIC ---
    const tabs = document.querySelectorAll('.f-tab');
    const orderGroups = document.querySelectorAll('.order-group');
    const emptyState = document.getElementById('dynamic-empty-state');
    const emptyText = document.getElementById('empty-state-text');
    const selectAllCb = document.getElementById('select-all-orders');
    const checkboxes = document.querySelectorAll('.order-checkbox');

    // Auto-klik tab berdasarkan URL parameter (?status=...)
    const currentUrlParams = new URLSearchParams(window.location.search);
    const activeStatus = currentUrlParams.get('status') || '';
    if(activeStatus) {
        let targetTab = document.querySelector(`.f-tab[data-status="${activeStatus}"]`);
        if(targetTab) targetTab.click();
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');

            let filterStatus = this.getAttribute('data-status');
            let visibleCount = 0;

            // Reset Checkboxes
            if(selectAllCb) selectAllCb.checked = false;
            checkboxes.forEach(cb => cb.checked = false);
            updateMassBtn();

            orderGroups.forEach(group => {
                let items = group.querySelectorAll('.order-item-row');
                let groupHasVisibleItem = false;

                items.forEach(item => {
                    let itemStatus = item.getAttribute('data-status');
                    if (filterStatus === '' || itemStatus === filterStatus) {
                        item.style.display = 'flex';
                        groupHasVisibleItem = true;
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Sembunyikan Header Invoice jika semua item didalamnya tersembunyi
                group.style.display = groupHasVisibleItem ? 'block' : 'none';
            });

            // Tampilkan Empty State Jika Kosong
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                    emptyText.innerHTML = `Gudang bersih! Tidak ada pesanan dengan status <strong>${this.innerText}</strong>.`;
                } else {
                    emptyState.style.display = 'none';
                }
            }
        });
    });

    // --- 2. PENCARIAN (SEARCH) INVOICE/NAMA ---
    const searchInput = document.getElementById('orderSearchInput');
    if(searchInput) {
        searchInput.addEventListener('keyup', function() {
            let keyword = this.value.toLowerCase();
            orderGroups.forEach(group => {
                // Cari di text header (Invoice & Nama) atau di nama barang
                let textContent = group.textContent.toLowerCase();
                group.style.display = textContent.includes(keyword) ? 'block' : 'none';
            });
        });
    }

    // --- 3. LOGIKA CHECKBOX KIRIM MASSAL ---
    const massBtn = document.getElementById('btn-mass-shipping');
    const countSpan = document.getElementById('selected-count');

    function updateMassBtn() {
        let checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
        if(countSpan) countSpan.textContent = checkedCount;
        if(massBtn) massBtn.disabled = checkedCount === 0;
    }

    if(selectAllCb) {
        selectAllCb.addEventListener('change', function() {
            // Hanya ceklis item yang VISIBLE di layar
            checkboxes.forEach(cb => {
                let row = cb.closest('.order-item-row');
                if(row.style.display !== 'none') {
                    cb.checked = this.checked;
                }
            });
            updateMassBtn();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if(!this.checked && selectAllCb) selectAllCb.checked = false;
            updateMassBtn();
        });
    });

    // --- 4. SWEETALERT CONFIRMATION ---
    
    // Konfirmasi Kirim Massal
    if(massBtn) {
        massBtn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Proses Pengiriman?',
                text: "Pastikan truk/armada sudah siap mengangkut pesanan yang dipilih.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e293b',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Angkut!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mass-shipping-form').submit();
                }
            });
        });
    }

    // Konfirmasi Update Satuan
    document.querySelectorAll('.btn-submit-single').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            let select = form.querySelector('select').options[form.querySelector('select').selectedIndex].text;
            
            Swal.fire({
                title: 'Update Status?',
                text: `Ubah status item ini menjadi: ${select}?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Simpan'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>
@endpush