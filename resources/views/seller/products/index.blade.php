@extends('layouts.seller')

@section('title', 'Katalog Material (Etalase)')

@section('content')
<style>
    /* CSS ISOLATED & ENTERPRISE GRADE */
    :root {
        --cat-dark: #0f172a;
        --cat-primary: #2563eb;
        --cat-border: #e2e8f0;
        --cat-bg: #f8fafc;
        --cat-muted: #64748b;
    }

    body { background-color: #f1f5f9; font-family: 'Inter', sans-serif; }

    /* HEADER */
    .page-title-box { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px; margin-bottom: 24px; }
    .title-left { display: flex; align-items: center; gap: 16px; }
    .icon-wrapper { background: var(--cat-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .page-title-text h3 { margin: 0; font-size: 22px; font-weight: 800; color: var(--cat-dark); }
    .page-title-text p { margin: 0; font-size: 14px; color: var(--cat-muted); font-weight: 500; }
    
    .btn-add { background: var(--cat-primary); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); text-decoration: none; }
    .btn-add:hover { background: #1d4ed8; color: white; transform: translateY(-2px); }

    /* MAIN CARD & TOOLBAR */
    .catalog-card { background: white; border-radius: 16px; border: 1px solid var(--cat-border); box-shadow: 0 4px 6px rgba(0,0,0,0.02); overflow: hidden; }
    .catalog-toolbar { background: var(--cat-bg); padding: 20px; border-bottom: 1px solid var(--cat-border); display: flex; gap: 15px; flex-wrap: wrap; }
    
    .search-group { flex: 1; min-width: 250px; position: relative; }
    .search-group i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem; }
    .search-input { width: 100%; padding: 12px 16px 12px 45px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 14px; font-weight: 500; transition: 0.2s; }
    .search-input:focus { border-color: var(--cat-primary); outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    
    .filter-select { padding: 12px 16px; border-radius: 10px; border: 1px solid #cbd5e1; font-size: 14px; font-weight: 600; color: var(--cat-dark); outline: none; min-width: 180px; cursor: pointer; }
    .filter-select:focus { border-color: var(--cat-primary); }

    /* TABLE ENTERPRISE */
    .table-responsive { overflow-x: auto; }
    .cat-table { width: 100%; border-collapse: collapse; }
    .cat-table th { background: white; color: #475569; font-size: 12px; font-weight: 800; text-transform: uppercase; padding: 16px 20px; border-bottom: 2px solid var(--cat-border); white-space: nowrap; text-align: left; }
    .cat-table td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .cat-table tr:hover td { background-color: #f8fafc; }

    /* PRODUCT INFO CELL */
    .prod-info-cell { display: flex; align-items: center; gap: 15px; min-width: 250px; }
    .prod-img { width: 60px; height: 60px; border-radius: 8px; object-fit: cover; border: 1px solid var(--cat-border); }
    .prod-details h6 { margin: 0 0 4px 0; font-size: 14px; font-weight: 800; color: var(--cat-dark); line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .prod-sku { font-family: monospace; font-size: 12px; font-weight: 600; color: #64748b; background: #f1f5f9; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0; display: inline-block; }

    .price-text { font-size: 15px; font-weight: 800; color: var(--cat-primary); }
    .stock-text { font-size: 14px; font-weight: 700; color: var(--cat-dark); }
    .stock-text.low { color: #ef4444; }

    /* BADGES MODERASI */
    .badge-mod { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; display: inline-block; text-align: center; }
    .mod-pending { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .mod-rejected { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

    /* IOS TOGGLE SWITCH (ETALASE AKTIF) */
    .ios-switch { position: relative; display: inline-block; width: 46px; height: 24px; margin: 0; }
    .ios-switch input { opacity: 0; width: 0; height: 0; }
    .ios-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .ios-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .ios-switch input:checked + .ios-slider { background-color: #10b981; }
    .ios-switch input:checked + .ios-slider:before { transform: translateX(22px); }
    .ios-switch input:disabled + .ios-slider { background-color: #f1f5f9; cursor: not-allowed; }
    .ios-switch input:disabled + .ios-slider:before { box-shadow: none; background-color: #e2e8f0; }

    /* ACTIONS */
    .action-group { display: flex; gap: 8px; justify-content: flex-end; }
    .btn-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 16px; border: 1px solid transparent; transition: 0.2s; cursor: pointer; text-decoration: none; }
    .btn-edit { background: #eff6ff; color: #2563eb; border-color: #bfdbfe; }
    .btn-edit:hover { background: #2563eb; color: white; }
    .btn-delete { background: #fef2f2; color: #ef4444; border-color: #fecaca; }
    .btn-delete:hover { background: #ef4444; color: white; }
</style>

{{-- Notifikasi SweetAlert --}}
@if(session('success'))
    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000}));</script>
@endif
@if(session('error'))
    <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'error', title: '{{ session('error') }}', showConfirmButton: false, timer: 3000}));</script>
@endif

{{-- HEADER HALAMAN --}}
<div class="page-title-box">
    <div class="title-left">
        <div class="icon-wrapper"><i class="mdi mdi-package-variant-closed"></i></div>
        <div class="page-title-text">
            <h3>Katalog Material</h3>
            <p>Kelola daftar produk, stok gudang, dan visibilitas etalase toko Anda.</p>
        </div>
    </div>
    <div>
        <a href="{{ route('seller.products.create') }}" class="btn-add">
            <i class="mdi mdi-plus-box-outline fs-5"></i> Tambah Material
        </a>
    </div>
</div>

<div class="catalog-card">
    
    {{-- TOOLBAR PENCARIAN & FILTER --}}
    <form action="{{ route('seller.products.index') }}" method="GET" class="catalog-toolbar m-0">
        <div class="search-group">
            <i class="mdi mdi-magnify"></i>
            <input type="text" name="search" class="search-input" placeholder="Cari nama produk atau SKU..." value="{{ request('search') }}">
        </div>
        <select name="status" class="filter-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Etalase Aktif</option>
            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Diarsipkan (Nonaktif)</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Moderasi</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak Pusat</option>
        </select>
        @if(request('search') || request('status'))
            <a href="{{ route('seller.products.index') }}" class="btn btn-light border fw-bold px-3 py-2 rounded-3 text-muted" style="display: flex; align-items: center;">Reset</a>
        @endif
    </form>

    {{-- TABEL PRODUK --}}
    <div class="table-responsive">
        <table class="cat-table">
            <thead>
                <tr>
                    <th width="35%">Informasi Material</th>
                    <th width="15%">Harga Satuan</th>
                    <th width="10%" class="text-center">Sisa Stok</th>
                    <th width="15%" class="text-center">Moderasi Admin</th>
                    <th width="15%" class="text-center">Tampil di Toko</th>
                    <th width="10%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $produk)
                    <tr>
                        {{-- 1. Info Produk --}}
                        <td>
                            <div class="prod-info-cell">
                                @php $img = !empty($produk->gambar_utama) ? 'assets/uploads/products/'.$produk->gambar_utama : 'assets/image/default-product.png'; @endphp
                                <img src="{{ asset($img) }}" alt="img" class="prod-img" onerror="this.src='{{ asset('assets/image/default-product.png') }}'">
                                <div class="prod-details">
                                    <h6>{{ $produk->nama_barang }}</h6>
                                    <span class="prod-sku">{{ $produk->kode_barang ?? 'Tanpa SKU' }}</span>
                                </div>
                            </div>
                        </td>

                        {{-- 2. Harga --}}
                        <td>
                            <div class="price-text">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                            <div class="text-muted small fw-bold mt-1">/ {{ $produk->satuan_unit ?? 'pcs' }}</div>
                        </td>

                        {{-- 3. Stok --}}
                        <td class="text-center">
                            <div class="stock-text {{ $produk->stok <= 5 ? 'low' : '' }}">{{ $produk->stok }}</div>
                        </td>

                        {{-- 4. Status Moderasi --}}
                        <td class="text-center">
                            @if($produk->status_moderasi == 'approved')
                                <i class="mdi mdi-check-decagram text-success fs-4" title="Disetujui Admin"></i>
                            @elseif($produk->status_moderasi == 'pending')
                                <span class="badge-mod mod-pending"><i class="mdi mdi-timer-sand"></i> Menunggu</span>
                            @elseif($produk->status_moderasi == 'rejected')
                                <span class="badge-mod mod-rejected" title="{{ $produk->alasan_penolakan ?? 'Melanggar aturan' }}"><i class="mdi mdi-close-octagon"></i> Ditolak</span>
                            @endif
                        </td>

                        {{-- 5. Toggle Etalase (Hanya bisa diklik jika Approved) --}}
                        <td class="text-center">
                            @php $isApproved = $produk->status_moderasi == 'approved'; @endphp
                            <label class="ios-switch" title="{{ $isApproved ? 'Klik untuk On/Off Etalase' : 'Selesaikan moderasi dulu' }}">
                                <input type="checkbox" class="toggle-etalase" data-id="{{ $produk->id }}" {{ $produk->is_active ? 'checked' : '' }} {{ !$isApproved ? 'disabled' : '' }}>
                                <span class="ios-slider"></span>
                            </label>
                        </td>

                        {{-- 6. Aksi Edit/Delete --}}
                        <td>
                            <div class="action-group">
                                <a href="{{ route('seller.products.edit', $produk->id) }}" class="btn-icon btn-edit" title="Edit Data">
                                    <i class="mdi mdi-pencil-outline"></i>
                                </a>
                                
                                <form action="{{ route('seller.products.destroy', $produk->id) }}" method="POST" class="m-0 delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-icon btn-delete btn-delete-confirm" title="Hapus Produk">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center opacity-50">
                                <i class="mdi mdi-package-variant-closed" style="font-size: 4rem; color: #94a3b8;"></i>
                                <h5 class="fw-bold text-dark mt-3 mb-1">Gudang Digital Kosong</h5>
                                <p class="text-muted">Tidak ada produk yang sesuai dengan filter pencarian.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- PAGINATION --}}
    @if($products->hasPages())
        <div class="p-3 border-top bg-light d-flex justify-content-center">
            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // 1. LIVE TOGGLE ETALASE (AJAX Tanpa Reload)
    document.querySelectorAll('.toggle-etalase').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let productId = this.dataset.id;
            let isActive = this.checked ? 1 : 0;
            let checkbox = this;
            
            fetch("{{ route('seller.products.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: isActive ? 'Produk Ditampilkan di Etalase' : 'Produk Disembunyikan', 
                        showConfirmButton: false, timer: 2000
                    });
                } else {
                    throw new Error('Gagal update');
                }
            })
            .catch(error => {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Koneksi gagal!', showConfirmButton: false, timer: 2000});
                checkbox.checked = !isActive; // Kembalikan posisi jika error
            });
        });
    });

    // 2. KONFIRMASI HAPUS (SweetAlert2)
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Material?',
                text: "Data produk ini beserta foto-fotonya akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

});
</script>
@endpush