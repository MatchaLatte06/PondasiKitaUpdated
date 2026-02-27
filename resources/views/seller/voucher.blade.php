@extends('layouts.seller')

@section('title', 'Voucher Toko Saya')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
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
            <span class="header-path-current">Voucher Toko</span>
        </div>
    </h3>
</div>

{{-- HERO BANNER --}}
<div class="hero-card-mono mb-4">
    <div class="hero-text">
        <h2>Buat Voucher & Tingkatkan Pesanan!</h2>
        <p>Tahukah Anda? Penjual yang rajin membuat voucher melihat peningkatan konversi penjualan hingga 25%. Mulai tarik perhatian pembeli sekarang.</p>
        <a href="#" class="btn-hero-white">
            Buat Voucher Sekarang <i class="mdi mdi-arrow-right"></i>
        </a>
    </div>
    <div class="hero-image d-none d-md-block">
        <i class="mdi mdi-ticket-percent-outline" style="font-size: 8rem; color: rgba(255,255,255,0.15); transform: rotate(-15deg);"></i>
    </div>
</div>

{{-- PILIHAN TIPE VOUCHER --}}
<div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h4 class="card-title fw-bold" style="color: #111827;">Pilih Tipe Voucher</h4>
        
        <div class="row mt-4">
            {{-- Voucher Toko --}}
            <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-store-outline"></i></div>
                    <h3>Voucher Toko</h3>
                    <p>Voucher dapat diklaim pembeli dan berlaku untuk semua produk di toko Anda.</p>
                    <div class="mt-auto">
                        <a href="#" class="btn-mono-outline">Buat</a>
                    </div>
                </div>
            </div>
            
            {{-- Voucher Produk --}}
            <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-cube-outline"></i></div>
                    <h3>Voucher Produk</h3>
                    <p>Voucher yang hanya bisa digunakan untuk produk-produk tertentu pilihan Anda.</p>
                    <div class="mt-auto">
                        <a href="#" class="btn-mono-outline">Buat</a>
                    </div>
                </div>
            </div>
            
            {{-- Voucher Terbatas/Khusus --}}
             <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-lock-outline"></i></div>
                    <h3>Voucher Terbatas</h3>
                    <p>Voucher rahasia. Hanya berlaku untuk pembeli yang Anda beri kode vouchernya.</p>
                    <div class="mt-auto">
                        <a href="#" class="btn-mono-outline">Buat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DAFTAR VOUCHER AKTIF --}}
<div class="card shadow-sm border-0" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h4 class="card-title fw-bold mb-4" style="color: #111827;">Daftar Voucher Aktif</h4>
        
        <div class="table-responsive">
            <table class="table-voucher">
                <thead>
                    <tr>
                        <th>Nama / Kode Voucher</th>
                        <th>Tipe</th>
                        <th>Nominal Diskon</th>
                        <th>Periode Validitas</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($voucher_list))
                        <tr>
                            <td colspan="6" class="p-0 border-0">
                                <div class="empty-state-voucher mt-3">
                                    <i class="mdi mdi-ticket-confirmation-outline"></i>
                                    <h5 class="fw-bold text-dark mt-2">Tidak Ada Voucher</h5>
                                    <p>Toko Anda belum memiliki voucher aktif saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        {{-- Logika Foreach Laravel nanti di sini --}}
                    @endif
                </tbody>
            </table>
        </div>
        
    </div>
</div>
@endsection