@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
    
    {{-- HEADER DASHBOARD --}}
    <div class="page-header mb-4">
        <h3 class="page-title d-flex align-items-center">
            <span class="page-title-icon bg-seller-primary text-white me-2 p-2 rounded">
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo Pondasikita" class="logo" style="width: 50px; height: 50px;">
            </span> 
            Dashboard Toko
        </h3>
    </div>

    {{-- CARD: YANG PERLU DILAKUKAN --}}
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-body">
            <h4 class="card-title mb-4 pb-2 border-bottom">Yang Perlu Dilakukan</h4>
            <div class="row text-center action-items">
                <div class="col-md-3 col-6 mb-3 mb-md-0 position-relative">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.8rem;">0</p>
                    <p class="action-label text-muted mb-0"><i class="mdi mdi-package-variant me-1"></i>Perlu Diproses</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0 position-relative">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.8rem;">0</p>
                    <p class="action-label text-muted mb-0"><i class="mdi mdi-truck-delivery me-1"></i>Telah Diproses</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0 position-relative">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.8rem;">0</p>
                    <p class="action-label text-muted mb-0"><i class="mdi mdi-keyboard-return me-1"></i>Pengembalian</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value text-danger font-weight-bold" style="font-size: 1.8rem;">0</p>
                    <p class="action-label text-muted mb-0"><i class="mdi mdi-cancel me-1"></i>Ditolak/Diturunkan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: PERFORMA TOKO (GRAFIK) --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="card-title mb-1">Performa Toko</h4>
                            <p class="card-subtitle text-muted mb-0" style="font-size: 0.8rem;">Waktu update terakhir: {{ date('H:i') }}</p>
                        </div>
                        
                        {{-- DROPDOWN FILTER GRAFIK --}}
                        <select id="chartFilter" class="form-select form-select-sm w-auto shadow-none border-primary text-primary fw-bold">
                            <option value="minggu">7 Hari Terakhir</option>
                            <option value="bulan">Bulan Ini</option>
                            <option value="tahun" selected>Tahun Ini</option>
                        </select>
                    </div>
                    
                    <div class="row text-center performance-metrics bg-light rounded p-3 mb-4 mx-0">
                        <div class="col-md-3 col-6 mb-3 mb-md-0 border-end border-white">
                            <p class="metric-label text-muted mb-1">Penjualan</p>
                            <p class="metric-value font-weight-bold mb-0" style="font-size: 1.2rem;">Rp {{ number_format($total_penjualan ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0 border-end border-white">
                            <p class="metric-label text-muted mb-1">Pesanan</p>
                            <p class="metric-value font-weight-bold mb-0" style="font-size: 1.2rem;">{{ $total_pesanan ?? 0 }}</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0 border-end border-white">
                            <p class="metric-label text-muted mb-1">Produk Aktif</p>
                            <p class="metric-value font-weight-bold mb-0" style="font-size: 1.2rem;">{{ $total_produk_aktif ?? 0 }}</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label text-muted mb-1">Konversi</p>
                            <p class="metric-value font-weight-bold mb-0 text-success" style="font-size: 1.2rem;">0,00%</p>
                        </div>
                    </div>
                    
                    {{-- GRAFIK CANVAS --}}
                    <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                        <canvas id="penjualanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: BERITA & MISI --}}
        <div class="col-lg-4 mb-4">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        Info & Berita
                        <a href="#" class="text-primary text-decoration-none small">Semua <i class="mdi mdi-arrow-right"></i></a>
                    </h4>
                    <div class="news-item d-flex align-items-start mt-3">
                        <img src="https://placehold.co/100x60/4f46e5/ffffff?text=Promo" alt="News" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                        <div class="news-content">
                            <p class="news-title font-weight-bold mb-1" style="font-size: 0.9rem;">BANJIR ORDER 7.7</p>
                            <p class="news-desc text-muted small lh-sm mb-0">Dapatkan diskon voucher dan buat tokomu lebih menjangkau.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-3 pb-2 border-bottom">Misi Penjual</h4>
                    <div class="empty-state text-center py-4 text-muted bg-light rounded">
                        <i class="mdi mdi-flag-checkered" style="font-size: 3rem; color:#cbd5e1;"></i>
                        <p class="mt-2 mb-0 fw-medium">Tidak ada misi yang aktif saat ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AFFILIATE & LIVESTREAM --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-3 border-primary">
                <div class="card-body">
                    <h4 class="card-title mb-4"><i class="mdi mdi-account-group text-primary me-2"></i>Affiliate Marketing</h4>
                    <div class="row text-center mt-2">
                        <div class="col-4 border-end">
                            <p class="text-muted small mb-1">Penjualan</p>
                            <p class="font-weight-bold fs-5 mb-0">Rp0</p>
                        </div>
                        <div class="col-4 border-end">
                            <p class="text-muted small mb-1">Pembeli Baru</p>
                            <p class="font-weight-bold fs-5 mb-0">0</p>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">ROI</p>
                            <p class="font-weight-bold fs-5 mb-0">0%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100 shadow-sm border-0 border-top border-3 border-danger">
                <div class="card-body">
                    <h4 class="card-title mb-4"><i class="mdi mdi-video-outline text-danger me-2"></i>Livestream</h4>
                    <div class="row text-center mt-2">
                        <div class="col-6 border-end">
                            <p class="text-muted small mb-1">Penjualan</p>
                            <p class="font-weight-bold fs-5 mb-0">Rp0</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Pesanan</p>
                            <p class="font-weight-bold fs-5 mb-0">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        
        // Data Dummy untuk Minggu & Bulan (Nanti bisa diganti dengan data real dari Controller)
        const dataChart = {
            minggu: {
                labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                data: [150000, 200000, 50000, 300000, 250000, 400000, 350000]
            },
            bulan: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                data: [1200000, 1500000, 900000, 2100000]
            },
            tahun: {
                // Mengambil data real tahunan dari Controller Anda
                labels: {!! json_encode($labels_bulan ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                data: {!! json_encode(isset($penjualan_tahunan) ? array_values($penjualan_tahunan) : [0,0,0,0,0,0,0,0,0,0,0,0]) !!}
            }
        };

        // Konfigurasi Chart
        const ctx = document.getElementById('penjualanChart').getContext('2d');
        
        // Buat Gradient untuk Background Area Chart (Biar lebih modern)
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataChart.tahun.labels,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: dataChart.tahun.data,
                    backgroundColor: gradient,
                    borderColor: '#4f46e5', // Indigo modern
                    borderWidth: 3,
                    tension: 0.4, // Membuat garis melengkung halus
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) { label += ': '; }
                                if (context.parsed.y !== null) {
                                    label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [4, 4], color: '#e5e7eb' },
                        ticks: {
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp' + (value/1000000) + 'Jt';
                                if(value >= 1000) return 'Rp' + (value/1000) + 'k';
                                return 'Rp' + value;
                            }
                        }
                    },
                    x: { grid: { display: false } }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        // Event Listener untuk Filter (Minggu / Bulan / Tahun)
        document.getElementById('chartFilter').addEventListener('change', function(e) {
            const selectedPeriod = e.target.value;
            
            // Update data dan label chart
            myChart.data.labels = dataChart[selectedPeriod].labels;
            myChart.data.datasets[0].data = dataChart[selectedPeriod].data;
            
            // Animasi update
            myChart.update();
        });
    });
</script>
@endpush