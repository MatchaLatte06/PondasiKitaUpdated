@extends('layouts.admin')

@section('title', 'Pusat Moderasi Material')

@push('styles')
<style>
    /* FILTER BAR */
    .filter-wrapper { background: white; border-radius: 16px; padding: 1.5rem; border: 1px solid #e2e8f0; margin-bottom: 2rem; }
    
    /* PRODUCT GRID */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem; }
    
    .product-mod-card {
        background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); display: flex; flex-direction: column;
    }
    .product-mod-card:hover { transform: translateY(-8px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); border-color: var(--admin-primary); }

    .img-container { width: 100%; aspect-ratio: 4/3; position: relative; background: #f1f5f9; overflow: hidden; }
    .img-container img { width: 100%; height: 100%; object-fit: cover; }
    
    .status-floating {
        position: absolute; top: 12px; left: 12px; padding: 4px 10px; border-radius: 8px;
        font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
    .bg-pending { background: #fef3c7; color: #d97706; }
    .bg-approved { background: #dcfce7; color: #15803d; }
    .bg-rejected { background: #fee2e2; color: #b91c1c; }

    .card-body-content { padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column; }
    .cat-label { font-size: 11px; font-weight: 700; color: var(--admin-primary); text-transform: uppercase; margin-bottom: 4px; }
    .prod-name { font-size: 15px; font-weight: 700; color: #1e293b; line-height: 1.4; margin-bottom: 12px; min-height: 42px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .store-info { display: flex; align-items: center; gap: 8px; padding: 10px 0; border-top: 1px solid #f1f5f9; margin-top: auto; }
    .store-icon { width: 24px; height: 24px; border-radius: 6px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 12px; }

    .btn-review { width: 100%; background: #f8fafc; color: #1e293b; border: 1px solid #e2e8f0; border-radius: 12px; padding: 10px; font-weight: 600; font-size: 13px; transition: 0.2s; }
    .btn-review:hover { background: var(--admin-primary); color: white; border-color: var(--admin-primary); }

    /* STATS HEADER */
    .mod-stat-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 20px; display: flex; align-items: center; gap: 15px; }
    .stat-circle { width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold text-dark mb-1">Moderasi Material & Produk</h2>
        <p class="text-muted small">Tinjau kelayakan produk yang diunggah oleh mitra seller sebelum tayang ke publik.</p>
    </div>
    <div class="d-flex gap-3">
        <div class="mod-stat-card">
            <div class="stat-circle bg-light text-warning"><i class="mdi mdi-timer-sand"></i></div>
            <div>
                <div class="small text-muted fw-bold">Menunggu</div>
                <div class="fw-bold h5 mb-0">{{ $stats['pending'] }}</div>
            </div>
        </div>
    </div>
</div>

{{-- FILTER & SEARCH --}}
<div class="filter-wrapper shadow-sm">
    <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
        <div class="btn-group p-1 bg-light rounded-3">
            @foreach(['semua', 'pending', 'approved', 'rejected'] as $st)
                <a href="{{ route('admin.products.index', ['status' => $st, 'search' => $search]) }}" 
                   class="btn btn-sm border-0 {{ $status_filter == $st ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-4">
                    {{ $st == 'pending' ? 'Perlu Tinjauan' : ucfirst($st) }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.products.index') }}" method="GET" style="min-width: 350px;">
            <input type="hidden" name="status" value="{{ $status_filter }}">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="mdi mdi-magnify"></i></span>
                <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari nama barang atau toko..." value="{{ $search }}">
            </div>
        </form>
    </div>
</div>

{{-- PRODUCT GRID --}}
<div class="product-grid">
    @forelse($products as $prod)
    <div class="product-mod-card shadow-sm">
        <div class="img-container">
            <img src="{{ asset('assets/uploads/products/' . ($prod->gambar_utama ?? 'default.jpg')) }}" alt="Material">
            <span class="status-floating bg-{{ $prod->status_moderasi }}">
                {{ $prod->status_moderasi }}
            </span>
        </div>
        <div class="card-body-content">
            <span class="cat-label">{{ $prod->nama_kategori ?? 'Umum' }}</span>
            <h5 class="prod-name">{{ $prod->nama_barang }}</h5>
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="text-primary fw-bold">Rp {{ number_format($prod->harga, 0, ',', '.') }}</div>
                <div class="small text-muted">Stok: {{ $prod->stok }}</div>
            </div>

            <a href="{{ route('admin.products.show', $prod->id) }}" class="btn-review text-center text-decoration-none mb-2">
                <i class="mdi mdi-file-search-outline me-1"></i> Tinjau Detail
            </a>

            <div class="store-info">
                <div class="store-icon"><i class="mdi mdi-store"></i></div>
                <div class="small fw-bold text-muted">{{ $prod->nama_toko }}</div>
                <div class="ms-auto small text-muted" style="font-size: 10px;">{{ date('d/m/y', strtotime($prod->created_at)) }}</div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <i class="mdi mdi-cube-off-outline text-muted" style="font-size: 5rem;"></i>
        <h5 class="text-muted mt-3">Tidak ada produk yang perlu dimoderasi saat ini.</h5>
    </div>
    @endforelse
</div>

<div class="mt-5 d-flex justify-content-center">
    {{ $products->links('pagination::bootstrap-5') }}
</div>
@endsection