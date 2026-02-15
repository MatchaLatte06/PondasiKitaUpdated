@extends('layouts.seller')

@section('title', 'Dashboard')

@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h4 class="card-title">Yang Perlu Dilakukan</h4>
            <div class="row text-center action-items">
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <p class="action-value">0</p>
                    <p class="action-label">Pengiriman Perlu Diproses</p>
                </div>
                {{-- ... Item lainnya ... --}}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h4 class="card-title d-flex justify-content-between align-items-center">
                        Performa Toko
                        <a href="#" class="text-link">Lainnya <i class="mdi mdi-chevron-right"></i></a>
                    </h4>
                    <p class="card-subtitle">Waktu update terakhir: {{ now()->format('H:i') }}</p>
                    <div class="row text-center performance-metrics">
                        <div class="col-md-3 col-6 mb-3 mb-md-0">
                            <p class="metric-label">Penjualan</p>
                            <p class="metric-value">Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</p>
                        </div>
                        {{-- ... Metrics lainnya ... --}}
                    </div>
                    <div class="chart-container mt-4" style="height: 300px;">
                        <canvas id="penjualanBulananChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Sidebar kanan: Berita & Misi --}}
        <div class="col-lg-4">
            {{-- Isi Card Berita --}}
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
                label: "Pendapatan",
                data: {!! json_encode($penjualan_tahunan) !!},
                backgroundColor: "rgba(79, 70, 229, 0.1)",
                borderColor: "rgba(79, 70, 229, 1)",
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});
</script>
@endpush