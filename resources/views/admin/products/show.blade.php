@extends('layouts.admin')

@section('title', 'Moderasi Material: ' . $produk->nama_barang)

@push('styles')
<style>
    :root {
        --glass-bg: rgba(255, 255, 255, 0.95);
        --p-indigo: #4f46e5;
    }

    /* HEADER STYLE */
    .product-hero {
        background: white; border-radius: 24px; padding: 30px;
        border: 1px solid #e2e8f0; display: flex; gap: 30px;
        margin-bottom: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);
    }
    .main-preview-wrapper { width: 280px; height: 280px; border-radius: 20px; overflow: hidden; border: 1px solid #f1f5f9; flex-shrink: 0; }
    .main-preview-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    
    .hero-info h1 { font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 10px; }
    .meta-pill { background: #f1f5f9; padding: 6px 14px; border-radius: 100px; font-size: 13px; font-weight: 600; color: #64748b; }
    
    /* BADGES */
    .mod-badge { padding: 6px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .bg-pending { background: #fef3c7; color: #d97706; }
    .bg-approved { background: #dcfce7; color: #15803d; }
    .bg-rejected { background: #fee2e2; color: #b91c1c; }

    /* GRID LAYOUT */
    .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    .detail-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px; margin-bottom: 24px; }
    .card-label { font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px; display: block; }

    /* INFO LIST */
    .spec-list { list-style: none; padding: 0; margin: 0; }
    .spec-item { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f1f5f9; }
    .spec-item:last-child { border-bottom: none; }
    .spec-label { color: #64748b; font-size: 14px; }
    .spec-value { color: #1e293b; font-weight: 700; font-size: 14px; }

    /* GALLERY */
    .gallery-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 12px; }
    .gallery-thumb { aspect-ratio: 1/1; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; cursor: pointer; transition: 0.2s; }
    .gallery-thumb:hover { border-color: var(--p-indigo); transform: scale(1.05); }
    .gallery-thumb img { width: 100%; height: 100%; object-fit: cover; }

    /* ACTION STICKY */
    .action-panel { position: sticky; top: 100px; }
    .btn-approve { background: #10b981; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; width: 100%; transition: 0.3s; }
    .btn-approve:hover { background: #059669; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(16,185,129,0.3); }
    
    .btn-reject { background: #fff; color: #ef4444; border: 1px solid #fee2e2; padding: 12px; border-radius: 12px; font-weight: 700; width: 100%; margin-top: 10px; }
    .btn-reject:hover { background: #fef2f2; }

    @media (max-width: 992px) { .content-grid { grid-template-columns: 1fr; } .product-hero { flex-direction: column; } .main-preview-wrapper { width: 100%; height: 350px; } }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4 d-flex justify-content-between">
    <div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-light border mb-2 text-muted">
            <i class="mdi mdi-arrow-left"></i> Kembali ke Daftar
        </a>
        <h2 class="fw-bold text-dark">Detail Moderasi Produk</h2>
    </div>
    <div class="date-badge">Material ID: #{{ str_pad($produk->id, 6, '0', STR_PAD_LEFT) }}</div>
</div>

{{-- HERO SECTION --}}
<div class="product-hero">
    <div class="main-preview-wrapper">
        <img src="{{ asset('assets/uploads/products/' . ($produk->gambar_utama ?? 'default.jpg')) }}" alt="Product">
    </div>
    <div class="hero-info">
        <div class="mb-2">
            <span class="mod-badge bg-{{ $produk->status_moderasi }}">
                <i class="mdi mdi-circle-medium"></i> {{ $produk->status_moderasi }}
            </span>
        </div>
        <h1>{{ $produk->nama_barang }}</h1>
        <div class="d-flex flex-wrap gap-2 mt-3">
            <span class="meta-pill"><i class="mdi mdi-storefront-outline me-1"></i> {{ $produk->nama_toko }}</span>
            <span class="meta-pill"><i class="mdi mdi-tag-outline me-1"></i> {{ $produk->nama_kategori ?? 'Tanpa Kategori' }}</span>
            <span class="meta-pill"><i class="mdi mdi-barcode me-1"></i> SKU: {{ $produk->kode_barang ?? '-' }}</span>
        </div>
    </div>
</div>

<div class="content-grid">
    {{-- COLUMN LEFT: DETAILS --}}
    <div class="main-content">
        <div class="detail-card">
            <span class="card-label">Deskripsi Material</span>
            <div style="color: #475569; line-height: 1.8; font-size: 15px; white-space: pre-line;">
                {!! nl2br(e($produk->deskripsi)) !!}
            </div>
        </div>

        <div class="detail-card">
            <span class="card-label">Galeri Foto Lainnya</span>
            <div class="gallery-grid">
                {{-- Foto Utama Juga Masuk Galeri --}}
                <div class="gallery-thumb"><img src="{{ asset('assets/uploads/products/' . ($produk->gambar_utama ?? 'default.jpg')) }}"></div>
                @foreach($gallery as $img)
                    <div class="gallery-thumb"><img src="{{ asset('assets/uploads/products/' . $img->nama_file) }}"></div>
                @endforeach
                @if($gallery->isEmpty())
                    <p class="text-muted small italic">Tidak ada foto tambahan.</p>
                @endif
            </div>
        </div>
    </div>

    {{-- COLUMN RIGHT: PRICING & ACTIONS --}}
    <div class="side-content">
        <div class="detail-card">
            <span class="card-label">Informasi Komersial</span>
            <ul class="spec-list">
                <li class="spec-item">
                    <span class="spec-label">Harga Satuan</span>
                    <span class="spec-value text-primary fs-5">Rp {{ number_format($produk->harga, 0, ',', '.') }}</span>
                </li>
                <li class="spec-item">
                    <span class="spec-label">Stok Ready</span>
                    <span class="spec-value">{{ $produk->stok }} {{ $produk->satuan_unit }}</span>
                </li>
                <li class="spec-item">
                    <span class="spec-label">Berat Logistik</span>
                    <span class="spec-value text-danger">{{ $produk->berat_kg }} Kg</span>
                </li>
                <li class="spec-item">
                    <span class="spec-label">Diskon Aktif</span>
                    <span class="spec-value">{{ $produk->nilai_diskon ? ($produk->tipe_diskon == 'PERSEN' ? $produk->nilai_diskon.'%' : 'Rp'.number_format($produk->nilai_diskon)) : '-' }}</span>
                </li>
            </ul>
        </div>

        @if($produk->status_moderasi == 'pending')
        <div class="action-panel">
            <div class="detail-card shadow-lg border-primary">
                <span class="card-label text-primary">Keputusan Admin</span>
                <p class="small text-muted mb-4">Pastikan konten material tidak melanggar aturan dan foto terlihat jelas.</p>
                
                <form action="{{ route('admin.products.process', $produk->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="action" value="approve">
                    <button type="submit" class="btn-approve" onclick="return confirm('Setujui produk ini?')">
                        <i class="mdi mdi-check-decagram me-1"></i> SETUJUI MATERIAL
                    </button>
                </form>

                <button type="button" class="btn-reject" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="mdi mdi-close-octagon-outline me-1"></i> TOLAK PENGAJUAN
                </button>
            </div>
        </div>
        @elseif($produk->status_moderasi == 'rejected')
        <div class="detail-card bg-light">
            <span class="card-label text-danger">Alasan Penolakan</span>
            <p class="text-dark fw-bold mb-0">"{{ $produk->alasan_penolakan }}"</p>
        </div>
        @endif
    </div>
</div>

{{-- MODAL REJECT --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form action="{{ route('admin.products.process', $produk->id) }}" method="POST">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold">Kenapa Material Ditolak?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <textarea name="alasan_penolakan" class="form-control rounded-3" rows="4" required placeholder="Contoh: Foto produk buram atau deskripsi kurang jelas..."></textarea>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Kirim Penolakan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection