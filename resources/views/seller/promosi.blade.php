@extends('layouts.seller')

@section('title', 'Pusat Promosi')

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
            <span class="header-path-current">Pusat Promosi</span>
        </div>
    </h3>
</div>

{{-- KARTU BUAT PROMOSI BARU --}}
<div class="card shadow-sm border-0 mb-4" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h4 class="card-title fw-bold" style="color: #111827;">Buat Promosi</h4>
        <p class="text-muted mb-4">Tingkatkan penjualan Anda dengan berbagai fitur promosi menarik.</p>
        
        <div class="row mt-4">
            {{-- Promo Toko --}}
            <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-tag-heart"></i></div>
                    <h3>Promo Toko</h3>
                    <p>Buat diskon untuk produk-produk di toko Anda untuk menarik lebih banyak pembeli.</p>
                    <div class="mt-auto">
                        <a href="#" class="btn-mono w-100">Buat Promo</a>
                    </div>
                </div>
            </div>
            
            {{-- Paket Diskon --}}
            <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-package-variant"></i></div>
                    <h3>Paket Diskon</h3>
                    <p>Tawarkan harga spesial untuk pembelian beberapa produk sekaligus dalam satu paket.</p>
                    <div class="mt-auto">
                        <a href="#" class="btn-mono w-100">Buat Paket</a>
                    </div>
                </div>
            </div>
            
            {{-- Kombo Hemat --}}
            <div class="col-md-4 mb-3">
                <div class="promo-type-card">
                    <div class="icon-wrapper"><i class="mdi mdi-basket-plus-outline" style="color: #9ca3af;"></i></div>
                    <h3 style="color: #6b7280;">Kombo Hemat</h3>
                    <p>Dorong pembeli untuk membeli lebih banyak dengan penawaran produk tambahan.</p>
                    <div class="mt-auto">
                        <button class="btn-mono w-100" disabled>Segera Hadir</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- KARTU DAFTAR PROMOSI --}}
<div class="card shadow-sm border-0" style="border-radius: 16px;">
    <div class="card-body p-4">
        <h4 class="card-title fw-bold mb-4" style="color: #111827;">Daftar Promosi Aktif</h4>
        
        <div class="table-responsive">
            <table class="table-promo">
                <thead>
                    <tr>
                        <th>Nama Promosi</th>
                        <th>Tipe Promosi</th>
                        <th>Periode</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if(empty($promosi_list))
                        <tr>
                            <td colspan="5" class="p-0 border-0">
                                <div class="empty-state-promo mt-3">
                                    <i class="mdi mdi-ticket-outline"></i>
                                    <h5 class="fw-bold text-dark mt-2">Belum Ada Promosi</h5>
                                    <p>Anda belum membuat promosi apapun. Buat promo sekarang untuk menarik pembeli!</p>
                                </div>
                            </td>
                        </tr>
                    @else
                        {{-- Nanti dilooping di sini jika sudah ada datanya --}}
                    @endif
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection