@extends('layouts.seller')

@section('title', 'Pusat Resolusi Komplain')

@section('content')
<style>
    /* CSS ISOLATED - Hanya berlaku di halaman ini agar tidak merusak layout luar */
    .ret-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }
    
    /* Title Icon */
    .ret-icon-box { background: #0f172a; color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* Filter Tabs Modern */
    .ret-tabs { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 5px; border-bottom: 2px solid #e2e8f0; margin-bottom: 1.5rem; scrollbar-width: none; }
    .ret-tabs::-webkit-scrollbar { display: none; }
    .ret-tab-item { padding: 10px 20px; font-weight: 600; font-size: 14px; color: #64748b; text-decoration: none; border-bottom: 3px solid transparent; white-space: nowrap; transition: 0.2s; }
    .ret-tab-item:hover { color: #0f172a; background: #f8fafc; border-radius: 8px 8px 0 0; }
    .ret-tab-item.active { color: #2563eb; border-bottom-color: #2563eb; font-weight: 700; }

    /* Order Card Design */
    .ret-card { border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden; margin-bottom: 1.5rem; background: #ffffff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); transition: 0.2s; }
    .ret-card:hover { border-color: #cbd5e1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    
    /* Header Card */
    .ret-card-header { background: #f8fafc; padding: 16px 24px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .ret-invoice-title { font-family: 'Courier New', Courier, monospace; font-weight: 800; font-size: 16px; color: #0f172a; margin: 0; }
    
    /* Badges */
    .ret-badge { padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; }
    .badge-tunggu { background: #fffbeb; color: #b45309; border-color: #fde68a; }
    .badge-setuju { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .badge-tolak { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }

    /* Product Image */
    .ret-prod-img { width: 80px; height: 80px; object-fit: cover; border-radius: 10px; border: 1px solid #e2e8f0; }
    
    /* Reason Box */
    .ret-reason-box { background: #fff8f1; border: 1px dashed #fdba74; border-radius: 12px; padding: 20px; height: 100%; display: flex; flex-direction: column; }
    .ret-reason-text { font-size: 14px; color: #78350f; font-style: italic; line-height: 1.5; word-wrap: break-word; margin-bottom: 15px; }
    .ret-proof-img { width: 70px; height: 70px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; cursor: pointer; transition: 0.2s; }
    .ret-proof-img:hover { transform: scale(1.05); border-color: #2563eb; }

    /* Custom Border Column (Responsive) */
    .ret-border-end { border-right: 1px solid #e2e8f0; }
    @media (max-width: 991px) {
        .ret-border-end { border-right: none; border-bottom: 1px solid #e2e8f0; }
    }

    /* Buttons */
    .ret-btn { font-weight: 600; font-size: 14px; padding: 10px 20px; border-radius: 8px; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; border: none; }
    .btn-outline-dark-custom { background: white; border: 1px solid #cbd5e1; color: #475569; }
    .btn-outline-dark-custom:hover { background: #0f172a; color: white; border-color: #0f172a; }
    .btn-reject-custom { background: white; border: 1px solid #ef4444; color: #ef4444; }
    .btn-reject-custom:hover { background: #fef2f2; }
    .btn-approve-custom { background: #10b981; color: white; border: 1px solid #10b981; }
    .btn-approve-custom:hover { background: #059669; border-color: #059669; }
</style>

<div class="ret-wrapper">
    
    {{-- Notifikasi SweetAlert --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Berhasil!', '{{ session('success') }}', 'success'));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Gagal!', '{{ session('error') }}', 'error'));</script>
    @endif

    {{-- HEADER HALAMAN --}}
    <div class="d-flex align-items-center gap-3 mb-4">
        <div class="ret-icon-box"><i class="mdi mdi-keyboard-return"></i></div>
        <div>
            <h3 class="m-0 fw-bold" style="font-size: 1.5rem; color: #0f172a;">Pusat Resolusi Komplain</h3>
            <p class="m-0 text-muted" style="font-size: 0.9rem;">Kelola permintaan retur material dan pengembalian dana dari pembeli.</p>
        </div>
    </div>

    {{-- TABS FILTER --}}
    <div class="ret-tabs">
        <a href="?status=" class="ret-tab-item {{ $currentFilter == '' ? 'active' : '' }}">Semua Komplain</a>
        <a href="?status=menunggu_respon" class="ret-tab-item {{ $currentFilter == 'menunggu_respon' ? 'active' : '' }}">Perlu Ditinjau</a>
        <a href="?status=disetujui" class="ret-tab-item {{ $currentFilter == 'disetujui' ? 'active' : '' }}">Disetujui (Refund)</a>
        <a href="?status=ditolak" class="ret-tab-item {{ $currentFilter == 'ditolak' ? 'active' : '' }}">Ditolak</a>
    </div>

    {{-- KONTEN PENGEMBALIAN --}}
    @if(empty($returns) || count($returns) == 0)
        <div class="text-center py-5 bg-white border rounded-4 shadow-sm">
            <i class="mdi mdi-shield-check-outline mb-3" style="font-size: 5rem; color: #10b981; opacity: 0.4;"></i>
            <h4 class="fw-bold text-dark mb-2">Toko Anda Sangat Aman!</h4>
            <p class="text-muted mb-0">Tidak ada keluhan, retur, atau permintaan pengembalian dana saat ini.</p>
        </div>
    @else
        @foreach($returns as $ret)
            @php
                $badgeClass = 'badge-tunggu'; $statusText = 'Perlu Keputusan';
                if($ret->status == 'disetujui') { $badgeClass = 'badge-setuju'; $statusText = 'Selesai (Direfund)'; }
                if($ret->status == 'ditolak') { $badgeClass = 'badge-tolak'; $statusText = 'Ditolak'; }
            @endphp

            <div class="ret-card">
                {{-- Card Header --}}
                <div class="ret-card-header">
                    <div class="d-flex align-items-center flex-wrap gap-3">
                        <span class="ret-invoice-title text-primary"><i class="mdi mdi-ticket-confirmation-outline me-1"></i>{{ $ret->id_return }}</span>
                        <span class="text-muted fw-bold" style="font-size: 14px;"><i class="mdi mdi-receipt-text me-1"></i>{{ $ret->kode_invoice }}</span>
                        <span class="text-muted" style="font-size: 13px;"><i class="mdi mdi-calendar-clock me-1"></i>{{ date('d M Y, H:i', strtotime($ret->tanggal_pengajuan)) }}</span>
                    </div>
                    <span class="ret-badge {{ $badgeClass }}">{{ $statusText }}</span>
                </div>

                {{-- Card Body (Menggunakan Bootstrap Grid Asli) --}}
                <div class="row g-0">
                    
                    {{-- SISI KIRI: Info Produk (Col-lg-6) --}}
                    <div class="col-lg-6 p-4 ret-border-end">
                        <span class="d-block fw-bold text-muted text-uppercase mb-3" style="font-size: 11px; letter-spacing: 1px;">Rincian Material & Pembeli</span>
                        
                        <div class="d-flex gap-3 mb-4">
                            <img src="{{ asset('assets/uploads/products/' . $ret->gambar_utama) }}" class="ret-prod-img" onerror="this.src='https://placehold.co/100x100?text=No+Img'">
                            <div>
                                <h6 class="fw-bold text-dark mb-1" style="font-size: 15px; line-height: 1.4;">{{ $ret->nama_barang }}</h6>
                                <div class="mb-2"><span class="badge bg-light text-secondary border px-2 py-1">Qty: {{ $ret->jumlah }} Pcs</span></div>
                                <div class="text-muted" style="font-size: 13px;"><i class="mdi mdi-account-hard-hat me-1"></i>Pembeli: <strong class="text-dark">{{ $ret->nama_pelanggan }}</strong></div>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 border d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-dark" style="font-size: 13px;">Tuntutan Pengembalian Dana</span>
                            <span class="fw-bold text-danger fs-5">Rp {{ number_format($ret->total_pengembalian, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    {{-- SISI KANAN: Alasan & Bukti (Col-lg-6) --}}
                    <div class="col-lg-6 p-4">
                        <div class="ret-reason-box">
                            <span class="d-flex align-items-center gap-2 fw-bold text-uppercase mb-2" style="font-size: 11px; letter-spacing: 1px; color: #b45309;">
                                <i class="mdi mdi-alert-circle-outline fs-6"></i> Kendala Pelanggan
                            </span>
                            
                            {{-- Teks Alasan --}}
                            <div class="ret-reason-text flex-grow-1">"{{ $ret->alasan }}"</div>
                            
                            {{-- Foto Bukti --}}
                            <div class="mt-auto border-top pt-3" style="border-color: #fdba74 !important;">
                                <span class="d-block fw-bold text-dark mb-2" style="font-size: 12px;">Bukti Foto / Unboxing:</span>
                                <div class="d-flex gap-2">
                                    <img src="{{ asset('assets/uploads/returns/' . $ret->bukti_foto) }}" class="ret-proof-img" onclick="showProofImage(this.src)" onerror="this.src='https://placehold.co/80x80?text=Bukti'" title="Klik untuk perbesar">
                                    {{-- Anda bisa menambah loop foto di sini jika bukti foto lebih dari 1 --}}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Card Footer (Actions) --}}
                <div class="bg-white p-3 border-top d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <div>
                        <button type="button" class="ret-btn btn-outline-dark-custom"><i class="mdi mdi-forum-outline fs-5"></i> Diskusi Pembeli</button>
                    </div>
                    
                    @if($ret->status == 'menunggu_respon')
                        <div class="d-flex gap-2 flex-wrap">
                            <form action="{{ route('seller.orders.return.process') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id_return" value="{{ $ret->id_return }}">
                                <input type="hidden" name="action" value="reject">
                                <button type="button" class="ret-btn btn-reject-custom btn-action-reject"><i class="mdi mdi-close-octagon fs-5"></i> Tolak Komplain</button>
                            </form>
                            
                            <form action="{{ route('seller.orders.return.process') }}" method="POST" class="m-0">
                                @csrf
                                <input type="hidden" name="id_return" value="{{ $ret->id_return }}">
                                <input type="hidden" name="action" value="approve">
                                <button type="button" class="ret-btn btn-approve-custom btn-action-approve"><i class="mdi mdi-check-decagram fs-5"></i> Setujui & Refund</button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fitur Zoom Bukti Foto (Sangat penting untuk mengecek cacat material)
    function showProofImage(url) {
        Swal.fire({
            imageUrl: url,
            imageAlt: 'Bukti Komplain Pembeli',
            showConfirmButton: false,
            showCloseButton: true,
            background: 'transparent',
            backdrop: 'rgba(15, 23, 42, 0.95)' // Latar belakang gelap fokus ke foto
        });
    }

    // Konfirmasi Tolak Komplain
    document.querySelectorAll('.btn-action-reject').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: 'Tolak Komplain?',
                text: "Apakah Anda yakin menolak klaim ini? Pastikan Anda telah berdiskusi dengan pembeli mengenai kendala material.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Konfirmasi Setuju Refund (Peringatan Dana)
    document.querySelectorAll('.btn-action-approve').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('form');
            Swal.fire({
                title: 'Setujui Refund?',
                html: "Dana transaksi ini akan <b>dikembalikan sepenuhnya ke pembeli</b>. Keputusan ini final dan uang akan dipotong dari estimasi saldo Anda.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Setujui Refund',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });
</script>
@endpush