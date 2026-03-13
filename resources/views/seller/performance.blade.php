@extends('layouts.seller')

@section('title', 'Performa Toko')

@push('styles')
<style>
    /* ========================================= */
    /* ==  STYLE PERFORMA TOKO (MONOCHROME)   == */
    /* ========================================= */
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

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

    .main-performance-tabs {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    .main-performance-tabs .nav-link {
        color: #6b7280;
        font-weight: 600;
        border: none;
        border-bottom: 3px solid transparent;
        padding: 0.75rem 1.5rem;
        background: transparent;
    }

    .main-performance-tabs .nav-link:hover { color: #111827; }

    .main-performance-tabs .nav-link.active {
        color: #111827;
        border-bottom-color: #111827;
    }

    /* Kriteria Card */
    .key-criteria-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .criteria-box {
        background-color: #ffffff;
        padding: 1.5rem;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .criteria-box:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        border-color: #111827;
    }
    .criteria-box .title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.75rem;
    }
    .criteria-box .value {
        font-size: 1.8rem;
        font-weight: 800;
        color: #111827;
        margin: 0 0 0.5rem 0;
    }
    .criteria-box .comparison {
        font-size: 0.85rem;
        color: #6b7280;
        border-top: 1px dashed #e5e7eb;
        padding-top: 0.75rem;
        margin-top: 0.5rem;
        display: flex;
        justify-content: space-between;
    }

    /* Checkbox Kustom Monokrom */
    .criteria-selection .form-check-input { cursor: pointer; }
    .criteria-selection .form-check-input:checked {
        background-color: #111827;
        border-color: #111827;
    }

    /* List Card Saluran */
    .channel-list-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.1rem 0;
        border-bottom: 1px dashed #e5e7eb;
    }
    .channel-list-item:last-child { border-bottom: none; padding-bottom: 0; }
    .channel-list-item span { font-weight: 600; color: #111827; }

    /* Grid Metrik Pembeli */
    .buyer-metrics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        flex-grow: 1;
    }
    .metric-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    .metric-item span { color: #6b7280; font-size: 0.8rem; font-weight: 600; text-transform: uppercase; }
    .metric-item p { font-weight: 800; font-size: 1.25rem; margin: 0; color: #111827; }

    /* ========================================= */
    /* PERBAIKAN CHART DONUT AGAR TIDAK ERROR    */
    /* ========================================= */
    .donut-wrapper {
        position: relative;
        width: 180px !important;  /* KUNCI 1: Paksa Ukuran */
        height: 180px !important; /* KUNCI 1: Paksa Ukuran */
        flex: 0 0 180px;          /* KUNCI 2: Mencegah Flexbox melarkan elemen */
        margin: 0 auto;
    }
    .donut-wrapper canvas {
        width: 180px !important;
        height: 180px !important;
    }

    .donut-center-text {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        width: 100%;
        pointer-events: none; /* KUNCI 3: Agar tidak menghalangi tooltip chart saat dihover */
    }
    .donut-center-text .value { font-size: 1.5rem; font-weight: 800; color: #111827; line-height: 1; margin-bottom: 4px;}
    .donut-center-text .label { font-size: 0.75rem; color: #6b7280; line-height: 1.2; font-weight: 600; }

    /* Tombol Outline */
    .btn-mono-outline {
        background: transparent; color: #111827; border: 1px solid #d1d5db;
        border-radius: 8px; font-weight: 600; padding: 0.5rem 1rem;
        text-decoration: none; display: inline-block; transition: 0.2s;
    }
    .btn-mono-outline:hover { background: #111827; color: white; border-color: #111827; }

    @media (max-width: 768px) {
        .filter-bar { flex-direction: column; align-items: stretch; }
        .filter-bar .ms-auto { margin-left: 0 !important; text-align: center;}
        .buyer-stats-container { flex-direction: column; text-align: center; gap: 1.5rem; }
    }
</style>
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-chart-box-outline"></i>
        </div>
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Performa Toko</span>
        </div>
    </h3>
</div>

{{-- Filter & Navigasi --}}
<div class="filter-bar shadow-sm">
    <span class="fw-bold text-secondary"><i class="mdi mdi-calendar text-muted me-1"></i> Periode Data:</span>
    <input type="date" class="form-control fw-bold" value="{{ date('Y-m-d') }}">
    <select class="form-select fw-bold text-secondary">
        <option>Semua Status Pesanan</option>
        <option>Pesanan Selesai Saja</option>
    </select>
    <div class="ms-auto d-flex gap-2">
        <a href="#" class="btn-mono-outline"><i class="mdi mdi-flash text-warning me-1"></i> Data Real-Time</a>
        <a href="#" class="btn-mono-outline"><i class="mdi mdi-download me-1"></i> Unduh Laporan</a>
    </div>
</div>

<ul class="nav nav-tabs main-performance-tabs" id="performanceTab" role="tablist">
    <li class="nav-item" role="presentation"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tinjauan-content" type="button">Ringkasan Utama</button></li>
    <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#produk-content" type="button">Analitik Produk</button></li>
</ul>

<div class="tab-content" id="performanceTabContent">

    {{-- TAB 1: TINJAUAN UTAMA --}}
    <div class="tab-pane fade show active" id="tinjauan-content" role="tabpanel">

        {{-- Kotak Kriteria --}}
        <div class="key-criteria-grid">
            <div class="criteria-box">
                <div class="title"><i class="mdi mdi-cash-multiple me-1"></i> Total Penjualan</div>
                <h3 class="value">Rp {{ number_format($kriteria['penjualan']['nilai'], 0, ',', '.') }}</h3>
                <div class="comparison">vs Kemarin <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">+{{ $kriteria['penjualan']['perbandingan'] }}%</span></div>
            </div>
            <div class="criteria-box">
                <div class="title"><i class="mdi mdi-receipt me-1"></i> Jumlah Pesanan</div>
                <h3 class="value">{{ number_format($kriteria['pesanan']['nilai'], 0, ',', '.') }}</h3>
                <div class="comparison">vs Kemarin <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">+{{ $kriteria['pesanan']['perbandingan'] }}%</span></div>
            </div>
            <div class="criteria-box">
                <div class="title"><i class="mdi mdi-percent-circle-outline me-1"></i> Tingkat Konversi</div>
                <h3 class="value">{{ $kriteria['tingkat_konversi']['nilai'] }}%</h3>
                <div class="comparison">vs Kemarin <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">+{{ $kriteria['tingkat_konversi']['perbandingan'] }}%</span></div>
            </div>
            <div class="criteria-box">
                <div class="title"><i class="mdi mdi-account-group-outline me-1"></i> Total Pengunjung</div>
                <h3 class="value">{{ number_format($kriteria['pengunjung']['nilai'], 0, ',', '.') }}</h3>
                <div class="comparison">vs Kemarin <span class="badge bg-success bg-opacity-10 text-success rounded-pill fw-bold">+{{ $kriteria['pengunjung']['perbandingan'] }}%</span></div>
            </div>
        </div>

        {{-- Area Chart Utama --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap mb-4 border-bottom pb-3">
                    <h5 class="card-title fw-bold m-0" style="color: #111827;">Grafik Tren Performa</h5>
                    <div class="criteria-selection d-flex gap-3 mt-3 mt-md-0" id="chartCriteriaCheckboxes">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="penjualan" id="checkPenjualan" checked>
                            <label class="form-check-label fw-bold text-secondary" for="checkPenjualan">Penjualan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="pesanan" id="checkPesanan">
                            <label class="form-check-label fw-bold text-secondary" for="checkPesanan">Pesanan</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="pengunjung" id="checkPengunjung" checked>
                            <label class="form-check-label fw-bold text-secondary" for="checkPengunjung">Pengunjung</label>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="mainPerformanceChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Area Saluran & Pembeli --}}
        <div class="row g-4 mb-4">

            {{-- Saluran Penjualan --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4">
                        <h5 class="card-title fw-bold border-bottom pb-3" style="color: #111827;">Saluran Penjualan Utama</h5>
                        <div class="mt-3">
                            <div class="channel-list-item">
                                <span><i class="mdi mdi-cube-outline me-2 text-muted fs-5 align-middle"></i> Halaman Produk</span>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">Rp {{ number_format($saluran['halaman_produk']['nilai'], 0, ',', '.') }}</div>
                                    <span class="text-success small fw-bold">+{{ $saluran['halaman_produk']['perbandingan'] }}%</span>
                                </div>
                            </div>
                            <div class="channel-list-item">
                                <span><i class="mdi mdi-video-wireless-outline me-2 text-muted fs-5 align-middle"></i> Live Streaming</span>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">Rp {{ number_format($saluran['live']['nilai'], 0, ',', '.') }}</div>
                                    <span class="text-danger small fw-bold">{{ $saluran['live']['perbandingan'] }}%</span>
                                </div>
                            </div>
                            <div class="channel-list-item">
                                <span><i class="mdi mdi-play-circle-outline me-2 text-muted fs-5 align-middle"></i> Video Promosi</span>
                                <div class="text-end">
                                    <div class="fw-bold text-dark">Rp {{ number_format($saluran['video']['nilai'], 0, ',', '.') }}</div>
                                    <span class="text-success small fw-bold">+{{ $saluran['video']['perbandingan'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Statistik Pembeli --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px;">
                    <div class="card-body p-4 d-flex flex-column justify-content-center">
                        <h5 class="card-title fw-bold border-bottom pb-3 mb-4 w-100 text-start" style="color: #111827;">Statistik Pembeli</h5>

                        <div class="buyer-stats-container d-flex w-100 align-items-center gap-4">

                            {{-- Donut Chart Wrapper --}}
                            <div class="donut-wrapper">
                                <canvas id="buyerDonutChart"></canvas>
                                <div class="donut-center-text">
                                    <div class="value">{{ $pembeli['pembeli_saat_ini_persen'] }}%</div>
                                    <div class="label">Pembeli<br>Setia</div>
                                </div>
                            </div>

                            {{-- Metrik Pembeli Grid --}}
                            <div class="buyer-metrics-grid">
                                <div class="metric-item"><span>Total Pembeli</span><p>{{ number_format($pembeli['total_pembeli']) }}</p></div>
                                <div class="metric-item"><span>Pembeli Baru</span><p>{{ number_format($pembeli['pembeli_baru']) }}</p></div>
                                <div class="metric-item"><span>Potensi Balik</span><p>{{ number_format($pembeli['potensi_pembeli']) }}</p></div>
                                <div class="metric-item"><span>Retention</span><p>{{ $pembeli['tingkat_pembeli_berulang'] }}%</p></div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- TAB 2: ANALITIK PRODUK --}}
    <div class="tab-pane fade" id="produk-content" role="tabpanel">
        <div class="card border-0 shadow-sm" style="border-radius: 16px;">
            <div class="card-body p-5 text-center text-muted">
                <i class="mdi mdi-cube-scan" style="font-size: 5rem; color: #e5e7eb;"></i>
                <h5 class="mt-4 fw-bold text-dark">Data Analitik Produk Belum Tersedia</h5>
                <p>Kumpulkan lebih banyak data penjualan untuk melihat performa per-produk di sini.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- Load Chart.js --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {

    // --- 1. SETUP DATA CHART UTAMA (LINE) ---
    const chartLabels = @json($chart_labels);
    const chartData = {
        penjualan: @json($chart_data['penjualan']),
        pesanan: @json($chart_data['pesanan']),
        pengunjung: @json($chart_data['pengunjung'])
    };

    const datasetConfigs = {
        penjualan: { label: 'Total Penjualan (Rp)', borderColor: '#111827', backgroundColor: 'rgba(17, 24, 39, 0.08)' },
        pesanan: { label: 'Pesanan', borderColor: '#9ca3af', backgroundColor: 'rgba(156, 163, 175, 0.1)' },
        pengunjung: { label: 'Pengunjung', borderColor: '#4f46e5', backgroundColor: 'rgba(79, 70, 229, 0.05)' }
    };

    const ctxMain = document.getElementById('mainPerformanceChart').getContext('2d');
    let mainPerformanceChart = new Chart(ctxMain, {
        type: 'line',
        data: { labels: chartLabels, datasets: [] },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            tension: 0.4,
            fill: true,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                tooltip: { backgroundColor: '#111827', padding: 12, cornerRadius: 8 }
            },
            scales: {
                y: { beginAtZero: true, border: {display: false}, grid: { color: '#f3f4f6', drawTicks: false } },
                x: { border: {display: false}, grid: { display: false } }
            }
        }
    });

    function updateMainChart() {
        const activeDatasets = [];
        document.querySelectorAll('#chartCriteriaCheckboxes input:checked').forEach(checkbox => {
            const key = checkbox.value;
            if (chartData[key] && datasetConfigs[key]) {
                activeDatasets.push({
                    label: datasetConfigs[key].label,
                    data: chartData[key],
                    borderColor: datasetConfigs[key].borderColor,
                    backgroundColor: datasetConfigs[key].backgroundColor,
                    borderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: datasetConfigs[key].borderColor,
                    pointBorderWidth: 2,
                });
            }
        });
        mainPerformanceChart.data.datasets = activeDatasets;
        mainPerformanceChart.update();
    }

    document.querySelectorAll('#chartCriteriaCheckboxes input').forEach(checkbox => {
        checkbox.addEventListener('change', updateMainChart);
    });
    updateMainChart();


    // --- 2. SETUP DATA DONUT CHART PEMBELI ---
    const ctxDonut = document.getElementById('buyerDonutChart').getContext('2d');
    new Chart(ctxDonut, {
        type: 'doughnut',
        data: {
            labels: ['Pembeli Baru', 'Pembeli Setia'],
            datasets: [{
                data: [{{ $pembeli_donut_chart['baru'] }}, {{ $pembeli_donut_chart['berulang'] }}],
                backgroundColor: ['#e5e7eb', '#111827'],
                borderWidth: 0,
                borderRadius: 4,
                cutout: '75%',
                hoverOffset: 4
            }]
        },
        options: {
            responsive: false, // KUNCI UTAMA: Matikan responsive auto-resize agar tidak membesar
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#111827',
                    padding: 10,
                    callbacks: {
                        label: function(context) { return " " + context.label + ": " + context.raw; }
                    }
                }
            }
        }
    });
});
</script>
@endpush
