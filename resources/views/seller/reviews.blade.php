@extends('layouts.seller')

@section('title', 'Penilaian Toko')

@push('styles')
<style>
    /* ========================================= */
    /* ==   STYLE PENILAIAN TOKO (MONOCHROME) == */
    /* ========================================= */
    
    .performance-summary-card .card-body {
        display: flex;
        align-items: center;
        gap: 2rem;
        flex-wrap: wrap;
    }
    
    .main-rating {
        text-align: center;
        padding-right: 2rem;
        border-right: 1px solid #e5e7eb;
        min-width: 200px;
    }
    
    .main-rating h2 {
        font-size: 3rem;
        font-weight: 800;
        color: #111827;
        margin: 0.5rem 0;
    }
    
    .main-rating h2 small {
        font-size: 1.2rem;
        color: #9ca3af;
    }
    
    .main-rating .stars .mdi {
        font-size: 1.5rem;
        color: #111827; /* Bintang Hitam Monokrom */
    }
    .main-rating .stars .mdi-star-outline {
        color: #d1d5db; /* Bintang kosong abu-abu */
    }
    
    .other-metrics {
        display: flex;
        gap: 3rem;
        flex-grow: 1;
        flex-wrap: wrap;
    }
    
    .metric-item span {
        color: #6b7280;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
        margin-bottom: 0.2rem;
    }
    
    .metric-item p {
        font-weight: 700;
        font-size: 1.2rem;
        color: #111827;
        margin-bottom: 1rem;
    }

    /* Filter Tabs */
    .filter-tabs {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
        gap: 10px;
    }
    
    .filter-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 600;
        padding: 0.75rem 1.25rem;
        transition: all 0.2s ease;
    }
    
    .filter-tabs .nav-link:hover { color: #111827; }
    
    .filter-tabs .nav-link.active {
        color: #111827;
        border-bottom-color: #111827;
    }

    /* Review Item */
    .review-item {
        display: flex;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }
    .review-item:last-child { border-bottom: none; padding-bottom: 0; }
    
    .review-avatar {
        width: 50px; height: 50px;
        background-color: #f3f4f6;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; font-weight: bold; color: #6b7280;
        flex-shrink: 0;
    }
    
    .review-content { flex-grow: 1; }
    
    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.25rem;
    }
    
    .user-name { font-weight: 700; color: #111827; }
    .review-date { font-size: 0.8rem; color: #9ca3af; }
    
    .review-stars .mdi { color: #111827; font-size: 1.1rem; }
    .review-stars .mdi-star-outline { color: #d1d5db; }
    
    .review-comment { margin-top: 0.75rem; font-size: 0.95rem; color: #374151; line-height: 1.5; }
    
    .reviewed-product {
        background-color: #f9fafb;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        margin-top: 1rem;
        font-size: 0.85rem;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .reviewed-product-img {
        width: 30px; height: 30px; object-fit: cover; border-radius: 4px; border: 1px solid #d1d5db;
    }
    
    .seller-reply {
        background-color: #f9fafb;
        border-left: 4px solid #111827;
        padding: 1rem 1.25rem;
        margin-top: 1rem;
        border-radius: 0 8px 8px 0;
    }
    .reply-header { font-weight: 700; color: #111827; margin-bottom: 0.5rem; font-size: 0.9rem; }
    
    .btn-mono {
        background-color: #111827; color: white; border: none; border-radius: 8px;
        padding: 0.5rem 1.5rem; font-weight: 600; transition: 0.2s;
    }
    .btn-mono:hover { background-color: #374151; }

    @media (max-width: 768px) {
        .main-rating { border-right: none; border-bottom: 1px solid #e5e7eb; padding-right: 0; padding-bottom: 1rem; width: 100%; }
        .other-metrics { gap: 1rem; }
        .review-item { flex-direction: column; gap: 1rem; }
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-star-circle-outline"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Penilaian Toko</span>
        </div>
    </h3>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0" style="background-color: #f0fdf4; color: #166534;" role="alert">
        <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- KARTU RINGKASAN PERFORMA --}}
<div class="card performance-summary-card mb-4 border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        <div class="main-rating">
            <h5 class="fw-bold" style="color: #6b7280; font-size: 0.9rem; text-transform: uppercase;">Rata-rata Rating</h5>
            <h2>{{ number_format($summary->avg_rating ?? 0, 1) }}<small>/5.0</small></h2>
            <div class="stars">
                @for($i=1; $i<=5; $i++)
                    <i class="mdi mdi-star{{ $i <= round($summary->avg_rating ?? 0) ? '' : '-outline' }}"></i>
                @endfor
            </div>
            <small class="text-secondary fw-bold mt-1 d-block">Dari {{ $summary->total_reviews ?? 0 }} Penilaian</small>
        </div>
        
        <div class="other-metrics">
            <div class="metric-item">
                <span>Tingkat Respons Chat</span><p>{{ $performa['chat_response_rate'] }}</p>
                <span>Waktu Respons Chat</span><p class="mb-0">{{ $performa['chat_response_time'] }}</p>
            </div>
            <div class="metric-item">
                <span>Tingkat Pembatalan</span><p>{{ $performa['cancellation_rate'] }}</p>
                <span>Keterlambatan Pengiriman</span><p class="mb-0">{{ $performa['late_shipment_rate'] }}</p>
            </div>
        </div>
    </div>
</div>

{{-- KARTU DAFTAR ULASAN --}}
<div class="card border-0 shadow-sm" style="border-radius: 16px;">
    <div class="card-body p-4">
        
        {{-- Tabs Filter (Memanggil ulang halaman dengan parameter GET ?star=...) --}}
        <ul class="nav filter-tabs">
            <li class="nav-item">
                <a class="nav-link {{ $starFilter == 'all' ? 'active' : '' }}" href="{{ route('seller.service.reviews') }}">Semua</a>
            </li>
            @for($i=5; $i>=1; $i--)
            <li class="nav-item">
                <a class="nav-link {{ $starFilter == $i ? 'active' : '' }}" href="{{ route('seller.service.reviews', ['star' => $i]) }}">{{ $i }} Bintang</a>
            </li>
            @endfor
        </ul>

        <div id="review-list-container">
            @if ($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="review-item">
                        <div class="review-avatar">
                            {{ strtoupper(substr($review->nama_user, 0, 1)) }}
                        </div>
                        <div class="review-content">
                            <div class="review-header">
                                <span class="user-name">{{ $review->nama_user }}</span>
                                <span class="review-date">{{ date('d M Y, H:i', strtotime($review->created_at)) }}</span>
                            </div>
                            
                            <div class="review-stars">
                                @for($i=1; $i<=5; $i++)
                                    <i class="mdi mdi-star{{ $i <= $review->rating ? '' : '-outline' }}"></i>
                                @endfor
                            </div>
                            
                            <p class="review-comment">{!! nl2br(e($review->ulasan)) !!}</p>
                            
                            @if(!empty($review->nama_barang))
                                <div class="reviewed-product">
                                    <i class="mdi mdi-package-variant text-muted fs-5"></i>
                                    <span>Produk dibeli: <strong>{{ $review->nama_barang }}</strong></span>
                                </div>
                            @endif
                            
                            {{-- Jika sudah dibalas --}}
                            @if(!empty($review->balasan_penjual))
                                <div class="seller-reply">
                                    <p class="reply-header">Balasan Anda</p>
                                    <p class="mb-0 text-secondary">{!! nl2br(e($review->balasan_penjual)) !!}</p>
                                </div>
                            {{-- Jika belum dibalas, tampilkan form --}}
                            @else
                                <form action="{{ route('seller.service.reviews.reply') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="review_id" value="{{ $review->id }}">
                                    <textarea name="balasan" class="form-control mb-2 bg-light" rows="2" placeholder="Tulis balasan publik untuk pelanggan ini..." required style="border-radius: 8px;"></textarea>
                                    <button type="submit" class="btn-mono">Kirim Balasan</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5 empty-state">
                    <i class="mdi mdi-star-off" style="font-size: 4rem; color: #e5e7eb;"></i>
                    <h5 class="mt-3 fw-bold" style="color: #111827;">Tidak Ada Ulasan</h5>
                    <p class="text-muted">Belum ada penilaian pelanggan di kategori ini.</p>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection