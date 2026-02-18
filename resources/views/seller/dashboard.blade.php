@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
    <div class="page-header">
        <h3 class="page-title">Ringkasan Bisnis</h3>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Yang Perlu Dilakukan</h4>
            <div class="action-items">
                <div>
                    <p class="action-value">0</p>
                    <p class="action-label">Pesanan Baru</p>
                </div>
                <div>
                    <p class="action-value">0</p>
                    <p class="action-label">Perlu Dikirim</p>
                </div>
                <div>
                    <p class="action-value">0</p>
                    <p class="action-label">Pembatalan</p>
                </div>
                <div>
                    <p class="action-value">{{ $stats['total_produk_aktif'] }}</p>
                    <p class="action-label">Produk Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Performa Penjualan
                        <a href="#" class="text-link">Analitik <i class="mdi mdi-chevron-right"></i></a>
                    </h4>
                    <div class="performance-metrics">
                        <div>
                            <p class="metric-label">Total Penjualan</p>
                            <p class="metric-value">Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="metric-label">Pesanan Selesai</p>
                            <p class="metric-value">{{ $stats['total_pesanan'] }}</p>
                        </div>
                    </div>
                    <div class="chart-container mt-4">
                        <canvas id="penjualanBulananChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h4 class="card-title">Berita Seller</h4>
                    <div class="news-item">
                        <img src="https://placehold.co/100x60/4F46E5/FFFFFF?text=7.7" alt="News" class="news-img">
                        <div class="news-content">
                            <p class="news-title">Persiapan Kampanye 7.7</p>
                            <p class="news-desc">Maksimalkan stok produk Anda sekarang.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Iklan Pondasikita</h4>
                    <div class="empty-state">
                        <i class="mdi mdi-rocket-launch-outline"></i>
                        <p>Naikkan traffic tokomu dengan fitur Iklan.</p>
                        <button class="btn-outline-primary mt-3">Mulai Iklan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const ctx = document.getElementById("penjualanBulananChart");
    new Chart(ctx, {
        type: "line",
        data: {
            labels: {!! json_encode($labels_bulan) !!},
            datasets: [{
                label: "Pendapatan (Rp)",
                data: {!! json_encode($penjualan_tahunan) !!},
                backgroundColor: "rgba(79, 70, 229, 0.1)",
                borderColor: "#4F46E5",
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 4,
                pointBackgroundColor: "#4F46E5"
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: "#F3F4F6" }
                },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush