@extends('layouts.seller')

@section('title', 'Pengembalian Pesanan')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
             <i class="mdi mdi-package-variant"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Pengembalian</span>
        </div>
    </h3>
</div>

<div class="card shadow-sm border-0" style="border-radius: 16px; background: transparent;">
    <div class="card-body p-0">
        
        {{-- TABS FILTER --}}
        <div class="nav-tabs-seller mb-4 bg-white p-3 pb-0 rounded shadow-sm">
            <a href="?status=" class="filter-tab {{ $currentFilter == '' ? 'active' : '' }}">Semua</a>
            <a href="?status=menunggu_respon" class="filter-tab {{ $currentFilter == 'menunggu_respon' ? 'active' : '' }}">Menunggu Respon</a>
            <a href="?status=disetujui" class="filter-tab {{ $currentFilter == 'disetujui' ? 'active' : '' }}">Disetujui</a>
            <a href="?status=ditolak" class="filter-tab {{ $currentFilter == 'ditolak' ? 'active' : '' }}">Ditolak</a>
        </div>

        @if(empty($returns))
            <div class="text-center py-5 text-muted bg-white rounded" style="border: 1px dashed var(--mono-border);">
                <i class="mdi mdi-clipboard-text-outline" style="font-size: 3rem; color: var(--mono-gray);"></i>
                <h5 class="mt-3 fw-bold" style="color: var(--mono-black);">Belum ada permintaan pengembalian</h5>
                <p class="mb-0">Toko Anda aman dari komplain saat ini.</p>
            </div>
        @else
            
            @foreach($returns as $ret)
                @if($currentFilter == '' || $currentFilter == $ret->status)
                    
                    @php
                        $badgeClass = 'bg-menunggu';
                        $statusText = 'Menunggu Respon';
                        if($ret->status == 'disetujui') { $badgeClass = 'bg-disetujui'; $statusText = 'Disetujui'; }
                        if($ret->status == 'ditolak') { $badgeClass = 'bg-ditolak'; $statusText = 'Ditolak'; }
                    @endphp

                    <div class="return-card">
                        {{-- HEADER --}}
                        <div class="return-header">
                            <div>
                                <span class="fw-bold" style="font-family: monospace; font-size: 1.05rem;">
                                    <i class="mdi mdi-ticket-confirmation-outline me-1"></i> {{ $ret->id_return }}
                                </span>
                                <span class="text-muted ms-3 small"><i class="mdi mdi-file-document-outline"></i> {{ $ret->kode_invoice }}</span>
                            </div>
                            <span class="badge-mono {{ $badgeClass }}">{{ $statusText }}</span>
                        </div>

                        {{-- BODY (SPLIT 2 KOLOM) --}}
                        <div class="return-body">
                            {{-- Kolom Kiri: Produk --}}
                            <div class="product-section">
                                <h6 class="fw-bold mb-3" style="font-size: 0.85rem; color: var(--mono-gray); text-transform: uppercase;">Informasi Produk</h6>
                                <div class="d-flex gap-3 mb-3">
                                    <img src="{{ asset('assets/uploads/products/' . $ret->gambar_utama) }}" alt="Produk" class="product-thumb" onerror="this.src='https://placehold.co/80x80?text=Produk'">
                                    <div>
                                        <div class="fw-bold" style="color: var(--mono-black); font-size: 0.95rem;">{{ $ret->nama_barang }}</div>
                                        <div class="text-muted small mt-1">Pembeli: <strong>{{ $ret->nama_pelanggan }}</strong></div>
                                        <div class="text-muted small">Qty: {{ $ret->jumlah }}x</div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-3 pt-3" style="border-top: 1px solid var(--mono-border);">
                                    <span class="text-muted small">Jumlah Pengembalian Dana</span>
                                    <span class="fw-bold fs-5" style="color: var(--mono-black);">Rp {{ number_format($ret->total_pengembalian, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            {{-- Kolom Kanan: Alasan & Bukti --}}
                            <div class="reason-section">
                                <h6 class="fw-bold mb-2" style="font-size: 0.85rem; color: var(--mono-gray); text-transform: uppercase;">Alasan & Bukti Pelanggan</h6>
                                <div class="text-muted small mb-2"><i class="mdi mdi-calendar-clock"></i> Diajukan pada: {{ date('d M Y, H:i', strtotime($ret->tanggal_pengajuan)) }}</div>
                                
                                <div class="reason-box">
                                    <i class="mdi mdi-format-quote-open" style="color: var(--mono-gray); font-size: 1.2rem;"></i>
                                    {{ $ret->alasan }}
                                </div>

                                <div class="mt-3">
                                    <span class="text-muted small d-block mb-2">Lampiran Bukti:</span>
                                    <div class="d-flex gap-2">
                                        <img src="{{ asset('assets/uploads/returns/' . $ret->bukti_foto) }}" class="proof-thumb" onerror="this.src='https://placehold.co/80x80?text=Bukti'" onclick="alert('Fitur zoom gambar bisa ditambahkan di sini')">
                                        </div>
                                </div>
                            </div>
                        </div>

                        {{-- FOOTER (ACTION BUTTONS) --}}
                        <div class="return-footer">
                            <button class="btn-mono-outline">Diskusi dengan Pembeli</button>
                            
                            @if($ret->status == 'menunggu_respon')
                                {{-- Tombol Tolak --}}
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-reject" onclick="return confirm('Anda yakin ingin MENOLAK permintaan pengembalian ini?')">Tolak</button>
                                </form>
                                
                                {{-- Tombol Terima --}}
                                <form action="#" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-mono" onclick="return confirm('Anda yakin ingin MENYETUJUI pengembalian dana ini?')">Setujui Pengembalian</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            @endforeach

        @endif

    </div>
</div>
@endsection