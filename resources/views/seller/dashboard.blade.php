@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
    
    {{-- CARD: YANG PERLU DILAKUKAN --}}
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Yang Perlu Dilakukan</h4>
            <div class="row text-center action-items">
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.5rem;">0</p>
                    <p class="action-label text-muted">Pengiriman Perlu Diproses</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.5rem;">0</p>
                    <p class="action-label text-muted">Pengiriman Telah Diproses</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.5rem;">0</p>
                    <p class="action-label text-muted">Pengembalian/Pembatalan</p>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value text-primary font-weight-bold" style="font-size: 1.5rem;">0</p>
                    <p class="action-label text-muted">Produk Ditolak/Diturunkan</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- KOLOM KIRI: PERFORMA TOKO (GRAFIK) --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Performa Toko
                        <a href="#" class="text-primary text-decoration-none small">Lainnya <i class="mdi mdi-chevron-right"></i></a>
                    </h4>
                    <p class="card-subtitle text-muted mb-4">Waktu update terakhir: {{ date('H:i') }} (Perubahan vs Kemarin)</p>
                    
                    <div class="row text-center performance-metrics">
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label text-muted mb-1">Penjualan</p>
                            <p class="metric-value font-weight-bold">Rp {{ number_format($total_penjualan, 0, ',', '.') }}</p>
                            <p class="metric-change text-success small">-- 0,00%</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label text-muted mb-1">Total Pesanan</p>
                            <p class="metric-value font-weight-bold">{{ $total_pesanan }}</p>
                            <p class="metric-change text-success small">-- 0,00%</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label text-muted mb-1">Produk Aktif</p>
                            <p class="metric-value font-weight-bold">{{ $total_produk_aktif }}</p>
                            <p class="metric-change text-success small">-- 0,00%</p>
                        </div>
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label text-muted mb-1">Konversi</p>
                            <p class="metric-value font-weight-bold">0,00%</p>
                            <p class="metric-change text-success small">-- 0,00%</p>
                        </div>
                    </div>
                    
                    {{-- GRAFIK CANVAS --}}
                    <div class="chart-container mt-4" style="position: relative; height: 300px;">
                        <canvas id="penjualanBulananChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: BERITA & MISI --}}
        <div class="col-lg-4 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Berita
                        <a href="#" class="text-primary text-decoration-none small">Lainnya <i class="mdi mdi-chevron-right"></i></a>
                    </h4>
                    <div class="news-item d-flex align-items-start mt-3">
                        <img src="https://placehold.co/100x60/E0F2F7/000000?text=News" alt="News" class="rounded me-3" style="width: 80px; height: 50px; object-fit: cover;">
                        <div class="news-content">
                            <p class="news-title font-weight-bold mb-1" style="font-size: 0.9rem;">BANJIR ORDER 7.7</p>
                            <p class="news-desc text-muted small lh-sm">Dapatkan diskon voucher dan buat tokomu lebih menjangkau.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Misi Penjual
                        <a href="#" class="text-primary text-decoration-none small">Lainnya <i class="mdi mdi-chevron-right"></i></a>
                    </h4>
                    <div class="empty-state text-center py-4 text-muted">
                        <i class="mdi mdi-check-circle-outline" style="font-size: 3rem;"></i>
                        <p class="mt-2">Tidak ada misi yang aktif saat ini.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- IKLAN --}}
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Iklan Pondasikita</h4>
            <p class="card-subtitle text-muted">Maksimalkan penjualanmu dengan Iklan Pondasikita</p>
            <div class="d-flex align-items-center justify-content-between flex-wrap mt-3">
                <p class="text-secondary mb-2 mb-sm-0 small">Pelajari lebih lanjut tentang cara terbaik mengiklankan produk.</p>
                <button class="btn btn-outline-primary btn-sm">Pelajari Lebih Lanjut</button>
            </div>
        </div>
    </div>

    {{-- AFFILIATE & LIVESTREAM --}}
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title">Affiliate Marketing</h4>
                    <div class="row text-center mt-4">
                        <div class="col-4 border-end">
                            <p class="text-muted small mb-1">Penjualan</p>
                            <p class="font-weight-bold">Rp0</p>
                        </div>
                        <div class="col-4 border-end">
                            <p class="text-muted small mb-1">Pembeli Baru</p>
                            <p class="font-weight-bold">0</p>
                        </div>
                        <div class="col-4">
                            <p class="text-muted small mb-1">ROI</p>
                            <p class="font-weight-bold">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title">Livestream</h4>
                    <div class="row text-center mt-4">
                        <div class="col-6 border-end">
                            <p class="text-muted small mb-1">Penjualan</p>
                            <p class="font-weight-bold">0</p>
                        </div>
                        <div class="col-6">
                            <p class="text-muted small mb-1">Pesanan</p>
                            <p class="font-weight-bold">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // --- GRAFIK PENJUALAN ---
    document.addEventListener("DOMContentLoaded", function() {
        var ctx = document.getElementById('penjualanBulananChart').getContext('2d');
        var penjualanChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels_bulan),
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: @json(array_values($penjualan_tahunan)),
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgba(79, 70, 229, 1)', // Warna Ungu seperti di Native
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: 'rgba(79, 70, 229, 1)',
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 4] } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush