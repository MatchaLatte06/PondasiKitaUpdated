@extends('layouts.seller')

@section('title', 'Kesehatan Toko')

@push('styles')
<style>
    /* ========================================= */
    /* == STYLE KESEHATAN TOKO (MONOCHROME)   == */
    /* ========================================= */
    
    /* --- Banner Status Atas dengan Chart --- */
    .page-status-header {
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        gap: 2rem;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .status-content-wrapper {
        flex: 1;
        min-width: 300px;
    }

    .health-chart-wrapper {
        width: 250px;
        height: 250px;
        position: relative;
        flex-shrink: 0;
        margin: 0 auto;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        background-color: #111827;
        color: #ffffff;
        padding: 0.6rem 1.5rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        margin-bottom: 1rem;
        letter-spacing: 0.5px;
    }
    .status-badge i { font-size: 1.4rem; margin-right: 0.5rem; }
    .page-status-header p { color: #4b5563; font-size: 1rem; margin-bottom: 1.5rem; line-height: 1.5; }
    
    /* --- Top Metrics Grid --- */
    .top-metrics {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
    }
    .top-metrics .metric-item {
        background-color: #f9fafb;
        border: 1px solid #e5e7eb;
        padding: 1rem;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    .top-metrics .metric-item:hover {
        background-color: #ffffff;
        border-color: #d1d5db;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }
    .top-metrics .metric-item span { 
        font-size: 0.8rem; 
        color: #6b7280; 
        font-weight: 700; 
        text-transform: uppercase; 
        display: block; 
        margin-bottom: 8px; 
        letter-spacing: 0.5px;
    }
    .top-metrics .metric-item p { margin: 0; font-size: 1.15rem; font-weight: 800; }

    /* --- Filter Bar --- */
    .filter-bar {
        display: flex;
        align-items: center;
        gap: 1rem;
        background-color: #f9fafb;
        padding: 1rem 1.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .filter-bar .form-select, 
    .filter-bar .form-control {
        width: auto;
        border-color: #d1d5db;
        border-radius: 8px;
    }

    /* ========================================= */
    /* == DESAIN BARU: LIST METRIK KESEHATAN  == */
    /* ========================================= */
    
    .metric-group {
        margin-bottom: 1.5rem;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
    }
    
    .metric-group-header {
        background-color: #f9fafb;
        padding: 1.2rem 1.5rem;
        font-size: 1.05rem;
        font-weight: 800;
        color: #111827;
        border-bottom: 1px solid #e5e7eb;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .metric-list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border-bottom: 1px dashed #e5e7eb;
        transition: background-color 0.2s;
    }
    .metric-list-item:hover {
        background-color: #fcfcfd;
    }
    .metric-list-item:last-child {
        border-bottom: none;
    }

    /* Bagian Kiri: Nama & Target */
    .metric-info {
        flex: 2;
        padding-right: 1rem;
    }
    .metric-info h6 {
        font-size: 1rem;
        font-weight: 700;
        color: #111827;
        margin: 0 0 0.4rem 0;
        line-height: 1.4;
    }
    .metric-target-badge {
        display: inline-block;
        background-color: #ffffff;
        color: #4b5563;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        border: 1px solid #d1d5db;
        margin-top: 0.2rem;
    }

    /* Bagian Tengah: Nilai Saat Ini & Sebelumnya */
    .metric-stats {
        flex: 1;
        text-align: center;
        border-left: 1px solid #e5e7eb;
        border-right: 1px solid #e5e7eb;
        padding: 0 1rem;
    }
    .metric-stats .current-val {
        font-size: 1.4rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 0.2rem 0;
        line-height: 1;
    }
    .metric-stats .past-val {
        font-size: 0.8rem;
        color: #6b7280;
        margin: 0;
    }

    /* Bagian Kanan: Tombol Aksi */
    .metric-action {
        flex: 0.5;
        text-align: right;
        padding-left: 1rem;
    }
    .btn-rincian {
        background: transparent;
        color: #111827;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: 0.2s;
    }
    .btn-rincian:hover {
        background: #111827;
        color: #ffffff;
        border-color: #111827;
    }
    
    /* --- Widget Penalti --- */
    .penalty-list { list-style: none; padding: 0; margin: 0; }
    .penalty-list li {
        display: flex; justify-content: space-between; padding: 1rem 0;
        border-bottom: 1px dashed #e5e7eb; font-size: 0.95rem;
    }
    .penalty-list li:last-child { border-bottom: none; padding-bottom: 0; }
    .penalty-list li span:first-child { color: #4b5563; }
    .penalty-list li span:last-child { font-weight: 700; color: #111827; }
    
    /* --- Widget Masalah --- */
    .issue-box {
        background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px;
        padding: 1.25rem 1.5rem; display: flex; justify-content: space-between;
        align-items: center; margin-bottom: 1rem; transition: border-color 0.2s;
    }
    .issue-box:hover { border-color: #d1d5db; }
    .issue-box:last-child { margin-bottom: 0; }
    .issue-box .issue-title { color: #4b5563; font-weight: 600; font-size: 0.95rem; margin: 0; display: flex; align-items: center;}
    .issue-box .issue-title i { font-size: 1.2rem; margin-right: 0.5rem; }
    .issue-box .value { font-size: 1.8rem; font-weight: 800; color: #111827; margin: 0; line-height: 1; }

    /* --- Utilities --- */
    .text-link { color: #111827; font-weight: 600; text-decoration: none; border-bottom: 1px solid transparent; transition: 0.2s;}
    .text-link:hover { color: #4f46e5; border-bottom-color: #4f46e5; }
    
    .btn-dark { background-color: #111827; color: #ffffff; border: none; padding: 0.6rem 1.2rem; font-weight: 600; transition: 0.2s; }
    .btn-dark:hover { background-color: #374151; color: #ffffff; }

    .btn-outline-mono {
        background: transparent; color: #111827; border: 1px solid #d1d5db;
        border-radius: 8px; font-weight: 600; padding: 0.5rem 1rem; transition: 0.2s; text-decoration: none;
    }
    .btn-outline-mono:hover { background: #111827; color: white; border-color: #111827; }

    .alert-box { background-color: #f9fafb; border: 1px dashed #d1d5db; border-radius: 12px; padding: 1.5rem; text-align: center; color: #6b7280; }

    @media (max-width: 992px) {
        .metric-list-item {
            flex-direction: column;
            align-items: flex-start;
            gap: 1.2rem;
        }
        .metric-stats {
            text-align: left;
            border: none;
            padding: 0;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .metric-action {
            width: 100%;
            text-align: left;
            padding: 0;
        }
        .btn-rincian { width: 100%; justify-content: center; }
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-shield-check-outline"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Kesehatan Toko</span>
        </div>
    </h3>
</div>

{{-- Bar Filter --}}
<div class="filter-bar shadow-sm">
    <span class="fw-bold text-secondary"><i class="mdi mdi-filter-variant text-muted me-1"></i> Tinjau Data:</span>
    <select class="form-select fw-bold">
        <option>7 Hari Terakhir</option>
        <option>30 Hari Terakhir</option>
        <option>Kuartal Ini (Q3)</option>
    </select>
    <div class="ms-auto d-flex gap-2 mt-3 mt-md-0">
        <a href="#" class="btn-outline-mono"><i class="mdi mdi-download me-1"></i> Unduh Laporan</a>
    </div>
</div>

<div class="row g-4">
    
    {{-- KOLOM UTAMA (METRIK) --}}
    <div class="col-lg-8">
        
        {{-- Banner Status + Radar Chart --}}
        <div class="page-status-header">
            <div class="status-content-wrapper">
                <div class="status-badge"><i class="mdi mdi-thumb-up-outline"></i> {{ $status_kesehatan }}</div>
                <p>Toko Anda berada pada kondisi prima. Terus pertahankan kecepatan pengiriman dan kualitas produk untuk menarik lebih banyak pembeli!</p>
                <div class="top-metrics">
                    <div class="metric-item">
                        <span>Pesanan</span>
                        <p class="{{ $top_summary['pesanan_terselesaikan'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $top_summary['pesanan_terselesaikan'] }} pelanggaran
                        </p>
                    </div>
                    <div class="metric-item">
                        <span>Produk</span>
                        <p class="{{ $top_summary['produk_dilarang'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $top_summary['produk_dilarang'] }} pelanggaran
                        </p>
                    </div>
                    <div class="metric-item">
                        <span>Pelayanan</span>
                        <p class="{{ $top_summary['pelayanan_pembeli'] > 0 ? 'text-danger' : 'text-success' }}">
                            {{ $top_summary['pelayanan_pembeli'] }} pelanggaran
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Wrapper Untuk Diagram Kesehatan --}}
            <div class="health-chart-wrapper">
                <canvas id="healthRadarChart"></canvas>
            </div>
        </div>

        {{-- LIST METRIK DETAIL (Ganti Tabel menjadi Card List Flexbox) --}}
        <div class="metric-container mt-4">
            
            @foreach ($metrics as $category => $items)
            <div class="metric-group">
                {{-- Header Kategori --}}
                <div class="metric-group-header">
                    <i class="mdi mdi-format-list-checks text-muted me-1"></i> {{ $category }}
                </div>
                
                {{-- Daftar Item Metrik --}}
                <div class="metric-list-body">
                    @foreach ($items as $item)
                    <div class="metric-list-item">
                        
                        {{-- Kiri: Informasi Metrik & Target --}}
                        <div class="metric-info">
                            <h6>{{ $item['nama'] }}</h6>
                            <span class="metric-target-badge">
                                <i class="mdi mdi-flag-triangle text-muted"></i> Target: <strong>{{ $item['target'] }}</strong>
                            </span>
                        </div>
                        
                        {{-- Tengah: Nilai Pencapaian --}}
                        <div class="metric-stats">
                            <p class="current-val">{{ $item['sekarang'] }}</p>
                            <p class="past-val">Bulan lalu: {{ $item['sebelumnya'] }}</p>
                        </div>
                        
                        {{-- Kanan: Aksi --}}
                        <div class="metric-action">
                            <a href="#" class="btn-rincian">
                                Rincian <i class="mdi mdi-arrow-right"></i>
                            </a>
                        </div>
                        
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
            
        </div>
    </div>

    {{-- KOLOM SAMPING (WIDGETS) --}}
    <div class="col-lg-4">
        
        {{-- Widget Penjual Star --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4 text-center">
                <div class="d-flex align-items-center justify-content-center mb-3">
                    <i class="mdi mdi-shield-star-outline" style="font-size: 4rem; color: #d1d5db;"></i>
                </div>
                <h5 class="fw-bold text-uppercase" style="color: #6b7280; font-size: 0.85rem; letter-spacing: 0.5px;">Status Saat Ini</h5>
                <h4 class="fw-bold mt-1 mb-3" style="color: #111827;">Penjual Reguler</h4>
                <p class="text-muted small px-2 mb-4">Penuhi 3 kriteria tambahan performa toko untuk meningkatkan status menjadi Penjual Star.</p>
                <a href="#" class="btn btn-dark w-100" style="border-radius: 8px;">Lihat Kriteria <i class="mdi mdi-arrow-right ms-1"></i></a>
            </div>
        </div>

        {{-- Widget Penalti --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
                    <h5 class="card-title fw-bold m-0" style="color: #111827;">Penalti Saya</h5>
                    <a href="#" class="text-link text-muted small">Riwayat <i class="mdi mdi-chevron-right"></i></a>
                </div>
                
                <div class="mb-4">
                    <span class="text-secondary fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Poin Kuartal Ini</span>
                    <h2 class="fw-bold mt-1" style="color: #111827; letter-spacing: -1px;">{{ $poin_penalti_kuartal_ini }} <span style="font-size: 1.1rem; color: #6b7280; font-weight: 500; letter-spacing: normal;">poin</span></h2>
                </div>
                
                <ul class="penalty-list mb-4">
                    @foreach($pelanggaran_penalti as $pelanggaran => $poin)
                        <li><span>{{ $pelanggaran }}</span> <span>{{ $poin }} poin</span></li>
                    @endforeach
                </ul>
                
                <h6 class="fw-bold mb-3" style="color: #111827;">Penalti Berjalan</h6>
                <div class="alert-box">
                    <i class="mdi mdi-gavel fs-2 mb-2"></i>
                    <p class="mb-0 small fw-bold">Hebat! Tidak ada penalti aktif.</p>
                </div>
            </div>
        </div>

        {{-- Widget Masalah --}}
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="border-bottom pb-3 mb-4">
                     <h5 class="card-title fw-bold mb-2" style="color: #111827;">Masalah Perlu Diselesaikan</h5>
                     <p class="text-muted small m-0">Ada {{ count(array_filter($masalah_perlu_diselesaikan)) }} masalah yang perlu segera ditangani.</p>
                </div>
               
                <div class="issue-box">
                    <p class="issue-title"><i class="mdi mdi-alert-circle-outline" style="color: #f59e0b;"></i> Produk bermasalah</p>
                    <p class="value">{{ $masalah_perlu_diselesaikan['produk_bermasalah'] }}</p>
                </div>
                <div class="issue-box">
                    <p class="issue-title"><i class="mdi mdi-clock-alert-outline" style="color: #ef4444;"></i> Keterlambatan kirim</p>
                    <p class="value">{{ $masalah_perlu_diselesaikan['keterlambatan_pengiriman'] }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('scripts')
{{-- Include Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Konfigurasi Radar Chart untuk Kesehatan Toko
    const ctx = document.getElementById('healthRadarChart').getContext('2d');
    
    // Nilai Dummy. Nanti ambil dari persentase skor masing-masing area. (Maks 100)
    const dataSkor = [95, 100, 80, 100]; 
    
    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: ['Kecepatan', 'Produk', 'Pelayanan', 'Stok'],
            datasets: [{
                label: 'Skor Kesehatan',
                data: dataSkor,
                backgroundColor: 'rgba(17, 24, 39, 0.2)', // Hitam transparan
                borderColor: '#111827', // Hitam pekat
                pointBackgroundColor: '#111827',
                pointBorderColor: '#fff',
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#111827',
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                r: {
                    angleLines: { color: '#e5e7eb' },
                    grid: { color: '#e5e7eb' },
                    pointLabels: {
                        color: '#374151',
                        font: { size: 11, weight: 'bold' }
                    },
                    ticks: {
                        display: false, // Sembunyikan angka skala di tengah
                        min: 0,
                        max: 100
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: { backgroundColor: '#111827', padding: 10 }
            }
        }
    });

});
</script>
@endpush