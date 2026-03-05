@extends('layouts.seller')

@section('title', 'Penilaian & Kepuasan Pelanggan')

@section('content')
<style>
    /* === CSS ISOLATED ENTERPRISE REVIEWS === */
    :root {
        --rev-dark: #0f172a;
        --rev-primary: #2563eb;
        --rev-star: #f59e0b; /* Warna Emas Bintang */
        --rev-border: #e2e8f0;
        --rev-bg: #f8fafc;
        --text-mut: #64748b;
    }
    .rev-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* HEADER */
    .rev-header-box { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .rev-icon-box { background: var(--rev-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* ANALYTICS SUMMARY CARD */
    .summary-card { background: white; border-radius: 16px; border: 1px solid var(--rev-border); padding: 24px; margin-bottom: 24px; display: grid; grid-template-columns: auto 1fr auto; gap: 40px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    @media (max-width: 992px) { .summary-card { grid-template-columns: 1fr; gap: 20px; } }

    /* 1. Nilai Rata-rata */
    .avg-box { text-align: center; padding-right: 40px; border-right: 1px dashed var(--rev-border); display: flex; flex-direction: column; justify-content: center; }
    @media (max-width: 992px) { .avg-box { border-right: none; border-bottom: 1px dashed var(--rev-border); padding-right: 0; padding-bottom: 20px; } }
    .avg-score { font-size: 3.5rem; font-weight: 900; color: var(--rev-dark); line-height: 1; margin-bottom: 5px; }
    .avg-score span { font-size: 1.2rem; color: #94a3b8; font-weight: 600; }
    .avg-stars { color: var(--rev-star); font-size: 1.5rem; margin-bottom: 5px; }
    .avg-total { font-size: 12px; font-weight: 700; color: var(--text-mut); text-transform: uppercase; letter-spacing: 0.5px; }

    /* 2. Rating Breakdown (Progress Bars) */
    .breakdown-box { display: flex; flex-direction: column; justify-content: center; gap: 8px; }
    .bd-row { display: flex; align-items: center; gap: 12px; font-size: 13px; font-weight: 700; color: var(--text-mut); }
    .bd-star { width: 60px; display: flex; justify-content: flex-end; align-items: center; gap: 4px; }
    .bd-star i { color: var(--rev-star); font-size: 1.1rem; }
    .bd-bar-bg { flex-grow: 1; height: 8px; background: #f1f5f9; border-radius: 10px; overflow: hidden; }
    .bd-bar-fill { height: 100%; background: var(--rev-star); border-radius: 10px; }
    .bd-count { width: 40px; text-align: right; }

    /* 3. Metrics Lanjutan */
    .metrics-box { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; border-left: 1px dashed var(--rev-border); padding-left: 40px; }
    @media (max-width: 992px) { .metrics-box { border-left: none; border-top: 1px dashed var(--rev-border); padding-left: 0; padding-top: 20px; } }
    .metric-item { display: flex; flex-direction: column; justify-content: center; }
    .metric-item label { font-size: 11px; font-weight: 700; color: var(--text-mut); text-transform: uppercase; margin-bottom: 4px; }
    .metric-item span { font-size: 18px; font-weight: 800; color: var(--rev-dark); }

    /* FILTER TABS */
    .rev-tabs { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 5px; margin-bottom: 24px; border-bottom: 2px solid var(--rev-border); scrollbar-width: none; }
    .rev-tabs::-webkit-scrollbar { display: none; }
    .rt-item { padding: 10px 20px; font-weight: 600; font-size: 14px; color: var(--text-mut); text-decoration: none; border-radius: 8px 8px 0 0; border-bottom: 3px solid transparent; transition: 0.2s; white-space: nowrap; }
    .rt-item:hover { color: var(--rev-dark); background: var(--rev-bg); }
    .rt-item.active { color: var(--rev-primary); border-bottom-color: var(--rev-primary); background: #eff6ff; }

    /* LIST ULASAN */
    .review-list { background: white; border-radius: 16px; border: 1px solid var(--rev-border); box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow: hidden; }
    .review-card { padding: 24px; border-bottom: 1px solid var(--rev-border); display: flex; gap: 20px; transition: 0.2s; }
    .review-card:hover { background: #fdfdfd; }
    .review-card:last-child { border-bottom: none; }

    .r-avatar { width: 45px; height: 45px; border-radius: 50%; background: #e2e8f0; color: #475569; display: flex; justify-content: center; align-items: center; font-weight: 800; font-size: 1.2rem; flex-shrink: 0; }
    .r-content { flex-grow: 1; }
    
    .r-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
    .r-name { font-weight: 800; font-size: 15px; color: var(--rev-dark); margin: 0; }
    .r-date { font-size: 12px; font-weight: 600; color: #94a3b8; }
    
    .r-stars { color: var(--rev-star); font-size: 14px; margin-bottom: 12px; }
    .r-stars i.empty { color: #cbd5e1; }
    
    .r-text { font-size: 14px; color: #334155; line-height: 1.6; margin-bottom: 16px; }

    .r-product-box { background: var(--rev-bg); border: 1px solid var(--rev-border); padding: 10px 15px; border-radius: 8px; display: inline-flex; align-items: center; gap: 12px; margin-bottom: 16px; }
    .r-prod-img { width: 35px; height: 35px; object-fit: cover; border-radius: 4px; border: 1px solid #cbd5e1; }
    .r-prod-name { font-size: 13px; font-weight: 600; color: var(--text-mut); }

    /* Seller Reply Section */
    .r-reply-box { background: #f1f5f9; border-left: 4px solid var(--rev-dark); padding: 16px; border-radius: 0 8px 8px 0; margin-top: 10px; }
    .r-reply-title { font-size: 12px; font-weight: 800; color: var(--rev-dark); text-transform: uppercase; margin-bottom: 6px; display: flex; align-items: center; gap: 6px; }
    .r-reply-text { font-size: 13px; color: #475569; line-height: 1.5; margin: 0; }

    /* Form Balasan */
    .form-reply { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
    .reply-input { border: 1px solid #cbd5e1; border-radius: 10px; padding: 12px; font-size: 13px; font-family: 'Inter', sans-serif; resize: vertical; min-height: 80px; transition: 0.2s; outline: none; background: #f8fafc; }
    .reply-input:focus { border-color: var(--rev-primary); background: white; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    .btn-reply { background: var(--rev-dark); color: white; border: none; font-weight: 700; padding: 10px 20px; border-radius: 8px; font-size: 13px; align-self: flex-end; transition: 0.2s; display: flex; align-items: center; gap: 6px; }
    .btn-reply:hover { background: #1e293b; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(15,23,42,0.2); }
</style>

<div class="rev-wrapper">

    {{-- Notifikasi --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000}));</script>
    @endif

    {{-- HEADER --}}
    <div class="rev-header-box">
        <div class="rev-icon-box"><i class="mdi mdi-star-circle-outline"></i></div>
        <div>
            <h3 class="m-0 fw-bold fs-4" style="color: #0f172a;">Penilaian & Ulasan Pelanggan</h3>
            <p class="m-0 text-muted" style="font-size: 13px;">Tinjau kepuasan pembeli dan berikan respons yang baik untuk menjaga reputasi toko.</p>
        </div>
    </div>

    {{-- KARTU SUMMARY ANALYTICS --}}
    <div class="summary-card">
        {{-- 1. Avg Rating --}}
        <div class="avg-box">
            <div class="avg-score">{{ number_format($summary->avg_rating ?? 0, 1) }}<span>/5.0</span></div>
            <div class="avg-stars">
                @for($i=1; $i<=5; $i++)
                    <i class="mdi mdi-star{{ $i <= round($summary->avg_rating ?? 0) ? '' : '-outline' }}"></i>
                @endfor
            </div>
            <div class="avg-total">{{ number_format($summary->total_reviews ?? 0) }} Penilaian Diterima</div>
        </div>

        {{-- 2. Breakdown Progress Bars --}}
        <div class="breakdown-box">
            @php $totalRevs = $summary->total_reviews > 0 ? $summary->total_reviews : 1; @endphp
            @for($i=5; $i>=1; $i--)
                @php
                    $count = $ratingCounts[$i] ?? 0;
                    $percent = ($count / $totalRevs) * 100;
                @endphp
                <div class="bd-row">
                    <div class="bd-star">{{ $i }} <i class="mdi mdi-star"></i></div>
                    <div class="bd-bar-bg">
                        <div class="bd-bar-fill" style="width: {{ $percent }}%;"></div>
                    </div>
                    <div class="bd-count">{{ number_format($count) }}</div>
                </div>
            @endfor
        </div>

        {{-- 3. Metrics Lainnya --}}
        <div class="metrics-box">
            <div class="metric-item">
                <label>Respons Chat</label>
                <span class="text-success">{{ $performa['chat_response_rate'] }}</span>
            </div>
            <div class="metric-item">
                <label>Waktu Respons</label>
                <span>{{ $performa['chat_response_time'] }}</span>
            </div>
            <div class="metric-item">
                <label>Tingkat Batal</label>
                <span class="text-danger">{{ $performa['cancellation_rate'] }}</span>
            </div>
            <div class="metric-item">
                <label>Keterlambatan</label>
                <span>{{ $performa['late_shipment_rate'] }}</span>
            </div>
        </div>
    </div>

    {{-- TABS FILTER --}}
    <div class="rev-tabs">
        <a href="{{ route('seller.service.reviews') }}" class="rt-item {{ $starFilter == 'all' ? 'active' : '' }}">Semua Ulasan</a>
        @for($i=5; $i>=1; $i--)
            <a href="{{ route('seller.service.reviews', ['star' => $i]) }}" class="rt-item {{ $starFilter == $i ? 'active' : '' }}">{{ $i }} Bintang</a>
        @endfor
    </div>

    {{-- DAFTAR ULASAN --}}
    <div class="review-list">
        @if ($reviews->count() > 0)
            @foreach($reviews as $review)
                <div class="review-card">
                    <div class="r-avatar">
                        {{ strtoupper(substr($review->nama_user, 0, 1)) }}
                    </div>
                    
                    <div class="r-content">
                        <div class="r-header">
                            <h4 class="r-name">{{ $review->nama_user }}</h4>
                            <span class="r-date">{{ \Carbon\Carbon::parse($review->created_at)->format('d M Y, H:i') }} WIB</span>
                        </div>
                        
                        <div class="r-stars">
                            @for($i=1; $i<=5; $i++)
                                <i class="mdi mdi-star {{ $i > $review->rating ? 'empty' : '' }}"></i>
                            @endfor
                        </div>
                        
                        @if(!empty($review->ulasan))
                            <div class="r-text">"{!! nl2br(e($review->ulasan)) !!}"</div>
                        @else
                            <div class="r-text text-muted" style="font-style:italic;">Pembeli tidak meninggalkan ulasan tertulis.</div>
                        @endif
                        
                        @if(!empty($review->nama_barang))
                            <div class="r-product-box">
                                <img src="{{ asset('assets/uploads/products/' . ($review->gambar_barang ?? 'default.jpg')) }}" class="r-prod-img" onerror="this.src='https://placehold.co/50'">
                                <span class="r-prod-name">Varian/Produk: {{ $review->nama_barang }}</span>
                            </div>
                        @endif
                        
                        {{-- Logika Balasan Penjual --}}
                        @if(!empty($review->balasan_penjual))
                            <div class="r-reply-box">
                                <div class="r-reply-title"><i class="mdi mdi-store"></i> Balasan Toko Anda</div>
                                <p class="r-reply-text">{!! nl2br(e($review->balasan_penjual)) !!}</p>
                            </div>
                        @else
                            <form action="{{ route('seller.service.reviews.reply') }}" method="POST" class="form-reply">
                                @csrf
                                <input type="hidden" name="review_id" value="{{ $review->id }}">
                                <textarea name="balasan" class="reply-input" placeholder="Tulis balasan publik yang sopan untuk ulasan ini..." required></textarea>
                                <button type="button" class="btn-reply btn-submit-reply"><i class="mdi mdi-send"></i> Kirim Balasan</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
            
            {{-- Pagination --}}
            @if($reviews->hasPages())
                <div class="p-3 bg-light border-top d-flex justify-content-center">
                    {{ $reviews->appends(request()->query())->links('pagination::bootstrap-5') }}
                </div>
            @endif
            
        @else
            <div class="text-center py-5">
                <i class="mdi mdi-star-off-outline d-block mb-3" style="font-size: 5rem; color: #cbd5e1;"></i>
                <h4 class="fw-bold text-dark">Belum Ada Penilaian</h4>
                <p class="text-muted">Tidak ada ulasan pembeli yang sesuai dengan filter saat ini.</p>
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Konfirmasi SweetAlert sebelum mengirim balasan
    document.querySelectorAll('.btn-submit-reply').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            let text = form.querySelector('textarea').value.trim();
            
            if(text === '') {
                Swal.fire('Peringatan', 'Balasan tidak boleh kosong!', 'warning');
                return;
            }

            Swal.fire({
                title: 'Publikasikan Balasan?',
                text: "Balasan Anda akan terlihat oleh seluruh pengunjung toko. Pastikan bahasa yang digunakan sopan dan profesional.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Publikasikan'
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mengirim...';
                    btn.disabled = true;
                    form.submit();
                }
            });
        });
    });

});
</script>
@endpush