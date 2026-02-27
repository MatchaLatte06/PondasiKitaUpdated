@extends('layouts.seller')

@section('title', 'Penghasilan Saya')

@push('styles')
<style>
    /* ========================================= */
    /* ==   STYLE PENGHASILAN (MONOCHROME)    == */
    /* ========================================= */
    
    /* Info Notice Monokrom */
    .info-notice {
        font-size: 0.9rem;
        background-color: #f9fafb;
        border-left: 4px solid #111827;
        color: #374151;
        padding: 0.75rem 1rem;
        border-radius: 0 8px 8px 0;
        margin-top: -0.5rem;
        margin-bottom: 1.5rem;
    }
    
    /* Metrics Box */
    .metrics-container {
        display: flex;
        gap: 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .metric-box {
        flex: 1;
        min-width: 200px;
    }
    .metric-box .metric-label {
        color: #6b7280;
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }
    .metric-box .metric-value {
        font-size: 1.75rem;
        font-weight: 800;
        color: #111827;
        margin: 0;
    }
    
    /* Sub Metrics */
    .sub-metrics {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
        background: #f9fafb;
        padding: 10px 15px;
        border-radius: 8px;
    }
    .sub-metrics .sub-item {
        font-size: 0.85rem;
        font-weight: 600;
        color: #111827;
    }
    .sub-metrics .sub-item span {
        color: #6b7280;
        font-weight: 500;
    }

    /* Tabs Filter */
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

    /* Tombol Aksi */
    .btn-mono {
        background-color: #111827; color: white; border: none; border-radius: 8px;
        padding: 0.5rem 1.25rem; font-weight: 600; transition: 0.2s;
    }
    .btn-mono:hover { background-color: #374151; color: white; }
    
    .btn-mono-outline {
        background-color: transparent; border: 1px solid #d1d5db; color: #374151;
        border-radius: 8px; padding: 0.5rem 1.25rem; font-weight: 600; transition: 0.2s;
    }
    .btn-mono-outline:hover { background-color: #f3f4f6; color: #111827; border-color: #111827; }

    /* Side Widget Card */
    .side-widget-card .widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    .side-widget-card .widget-header a {
        font-size: 0.85rem;
        text-decoration: none;
        font-weight: 600;
        color: #6b7280;
    }
    .side-widget-card .widget-header a:hover { color: #111827; }
    
    .transaction-period-list .list-item {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        padding: 0.75rem 0;
        border-bottom: 1px dashed #e5e7eb;
        color: #374151;
    }
    .transaction-period-list .list-item:last-child { border-bottom: none; }
    .transaction-period-list a { color: #111827; font-size: 1.1rem; }
    .transaction-period-list a:hover { color: #4f46e5; }
    
    /* Tabel */
    .table-income thead th { background-color: #f9fafb; font-weight: 600; color: #6b7280; text-transform: uppercase; font-size: 0.75rem; border-bottom: 1px solid #e5e7eb; padding: 12px 15px; }
    .table-income tbody td { padding: 15px; border-bottom: 1px dashed #e5e7eb; vertical-align: middle; color: #111827; }
    
    .empty-state-box { text-align: center; padding: 3rem 1rem; }
    .empty-state-box i { font-size: 3rem; color: #d1d5db; display: block; margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-currency-usd"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Penghasilan Saya</span>
        </div>
    </h3>
</div>

<div class="row">
    {{-- KOLOM UTAMA (KIRI) --}}
    <div class="col-lg-8 mb-4">
        
        {{-- KARTU RINGKASAN SALDO --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold" style="color: #111827;">Informasi Penghasilan</h4>
                <div class="info-notice">
                    <i class="mdi mdi-information me-1"></i> Nominal "Pending" dan "Sudah Dilepas" ini belum termasuk biaya penyesuaian/pajak.
                </div>
                
                <div class="metrics-container">
                    <div class="metric-box">
                        <p class="metric-label">Dana Tertahan (Pending)</p>
                        <h3 class="metric-value">Rp {{ number_format($penghasilan_pending, 0, ',', '.') }}</h3>
                    </div>
                    <div class="metric-box" style="border-left: 1px dashed #e5e7eb; padding-left: 1.5rem;">
                        <p class="metric-label text-success">Dana Sudah Dilepas</p>
                        <h3 class="metric-value text-success">Rp {{ number_format($penghasilan_dilepas, 0, ',', '.') }}</h3>
                        
                        <div class="sub-metrics">
                            <div class="sub-item"><span>Minggu Ini:</span> <br> Rp {{ number_format($dilepas_minggu_ini, 0, ',', '.') }}</div>
                            <div class="sub-item text-end"><span>Bulan Ini:</span> <br> Rp {{ number_format($dilepas_bulan_ini, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                
                <div class="text-end mt-4">
                    <a href="#" class="btn-mono">Tarik Saldo Penjual <i class="mdi mdi-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>

        {{-- KARTU RINCIAN TRANSAKSI --}}
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <h4 class="card-title fw-bold" style="color: #111827;">Rincian Penghasilan</h4>
                
                <ul class="nav filter-tabs mt-3">
                    <li class="nav-item"><a class="nav-link" href="#">Pending</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Sudah Dilepas</a></li>
                </ul>
                
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <input type="date" class="form-control" style="max-width: 200px; border-radius: 8px;">
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Cari No. Pesanan..." style="border-radius: 8px;">
                        <button class="btn-mono-outline"><i class="mdi mdi-export"></i> Export</button>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-income">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal Dilepaskan</th>
                                <th>Status</th>
                                <th>Metode Pembayaran</th>
                                <th class="text-end">Total Penghasilan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (empty($transaksi_dilepas))
                                <tr>
                                    <td colspan="5" class="p-0 border-0">
                                        <div class="empty-state-box bg-light rounded mt-2">
                                            <i class="mdi mdi-text-box-search-outline"></i>
                                            <h6 class="fw-bold mt-2" style="color: #111827;">Tidak Ada Data Transaksi</h6>
                                            <p class="text-muted mb-0 small">Belum ada penghasilan yang masuk pada filter ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                {{-- Nanti Looping @foreach disini --}}
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM WIDGET (KANAN) --}}
    <div class="col-lg-4">
        
        {{-- Widget 1: Catatan Transaksi --}}
        <div class="card side-widget-card mb-4 border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="widget-header">
                    <h5 class="card-title fw-bold m-0" style="color: #111827;">Riwayat Laporan</h5>
                    <a href="#">Lihat Semua</a>
                </div>
                <div class="transaction-period-list">
                    <div class="list-item"><span>30 Jun - 6 Jul 2025</span><a href="#"><i class="mdi mdi-download"></i></a></div>
                    <div class="list-item"><span>23 Jun - 29 Jun 2025</span><a href="#"><i class="mdi mdi-download"></i></a></div>
                    <div class="list-item"><span>16 Jun - 22 Jun 2025</span><a href="#"><i class="mdi mdi-download"></i></a></div>
                </div>
            </div>
        </div>
        
        {{-- Widget 2: Faktur --}}
        <div class="card side-widget-card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="widget-header">
                    <h5 class="card-title fw-bold m-0" style="color: #111827;">Faktur Pajak/Layanan</h5>
                    <a href="#">Lihat Semua</a>
                </div>
                <div class="empty-state-box p-4 border rounded" style="border: 1px dashed #d1d5db !important;">
                    <i class="mdi mdi-receipt" style="font-size: 2.5rem;"></i>
                    <p class="mt-2 mb-0 fw-bold text-muted small">Tidak ada tagihan faktur bulan ini.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection