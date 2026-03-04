@extends('layouts.admin')

@section('title', 'Admin Command Center')

@push('styles')
<style>
    /* ========================================= */
    /* ==  PREMIUM ADMIN DASHBOARD CSS        == */
    /* ========================================= */
    :root {
        --admin-bg: #f4f7f8;
        --admin-surface: #ffffff;
        --admin-primary: #4f46e5;
        --admin-text-dark: #1e293b;
        --admin-secondary: #64748b;
        --admin-border: #e2e8f0;
        --c-success: #10b981;
        --c-warning: #f59e0b;
        --c-danger: #ef4444;
        --c-info: #3b82f6;
    }

    .dashboard-header { display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 24px; }
    .welcome-text h2 { font-size: 24px; font-weight: 800; color: var(--admin-text-dark); margin-bottom: 4px; }
    .date-badge { background: #fff; padding: 10px 16px; border-radius: 10px; border: 1px solid var(--admin-border); font-size: 14px; font-weight: 600; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }

    /* TASK CARDS */
    .task-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .task-card {
        background: #fff; border-radius: 16px; padding: 20px; border: 1px solid var(--admin-border);
        display: flex; align-items: center; gap: 16px; transition: all 0.3s ease; text-decoration: none; position: relative; overflow: hidden;
    }
    .task-card::before { content: ''; position: absolute; left: 0; bottom: 0; width: 100%; height: 3px; background: transparent; transition: all 0.3s ease; }
    .task-card:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(0,0,0,0.04); }
    .t-warning:hover::before { background: var(--c-warning); }
    .t-info:hover::before { background: var(--c-info); }
    .t-success:hover::before { background: var(--c-success); }
    .t-danger:hover::before { background: var(--c-danger); }
    
    .task-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .task-info h4 { font-size: 22px; font-weight: 800; margin: 0; color: var(--admin-text-dark); }
    .task-info p { font-size: 12px; color: var(--admin-secondary); margin: 0; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;}

    .t-warning .task-icon { background: #fff7ed; color: var(--c-warning); }
    .t-info .task-icon { background: #eff6ff; color: var(--c-info); }
    .t-success .task-icon { background: #ecfdf5; color: var(--c-success); }
    .t-danger .task-icon { background: #fef2f2; color: var(--c-danger); }

    /* KPI STATS */
    .section-title { font-size: 15px; font-weight: 700; color: var(--admin-secondary); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; display: flex; align-items: center; gap: 8px;}
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid var(--admin-border); position: relative; overflow: hidden;}
    .kpi-card::after { content: ''; position: absolute; left: 0; top: 0; height: 100%; width: 4px; background: linear-gradient(to bottom, var(--admin-primary), #818cf8); }
    .kpi-label { font-size: 13px; font-weight: 600; color: var(--admin-secondary); margin-bottom: 10px; display: flex; justify-content: space-between; text-transform: uppercase; letter-spacing: 0.5px; }
    .kpi-value { font-size: 26px; font-weight: 800; color: var(--admin-text-dark); }
    .kpi-trend { font-size: 12px; font-weight: 700; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
    .trend-up { color: var(--c-success); }
    .trend-neutral { color: var(--admin-secondary); }

    /* LAYOUT ROW */
    .dashboard-row { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px; }
    .panel-card { background: #fff; border-radius: 16px; border: 1px solid var(--admin-border); padding: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .panel-title { font-size: 16px; font-weight: 700; color: var(--admin-text-dark); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    
    /* LOGISTICS WIDGET */
    .log-item { padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9; margin-bottom: 12px; transition: 0.2s;}
    .log-item:hover { border-color: var(--admin-border); background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,0.02);}
    .log-val { font-size: 20px; font-weight: 800; color: var(--admin-text-dark); margin-top: 4px;}

    /* TABLE */
    .table-responsive { border-radius: 12px; overflow: hidden; border: 1px solid var(--admin-border);}
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { background: #f8fafc; padding: 14px 16px; font-size: 11px; font-weight: 800; color: var(--admin-secondary); text-transform: uppercase; text-align: left; border-bottom: 1px solid var(--admin-border); letter-spacing: 0.5px;}
    .modern-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; vertical-align: middle;}
    .modern-table tbody tr:hover { background: #f8fafc; }
    .store-name { font-weight: 800; color: var(--admin-text-dark); display: block; margin-bottom: 2px;}
    .store-meta { font-size: 11px; color: var(--admin-secondary); display: flex; align-items: center; gap: 4px;}

    /* EMPTY STATE */
    .empty-state { text-align: center; padding: 40px 20px; color: var(--admin-secondary); }
    .empty-state i { font-size: 48px; opacity: 0.5; margin-bottom: 10px; display: block; }

    @media (max-width: 992px) { .dashboard-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <div class="dashboard-header">
        <div class="welcome-text">
            <h2>Command Center Admin</h2>
            <p class="text-muted mb-0">Monitoring operasional Pondasikita secara Real-time.</p>
        </div>
        <div class="date-badge text-primary">
            <i class="mdi mdi-calendar me-2"></i> {{ date('d F Y') }}
        </div>
    </div>

    <h4 class="section-title"><i class="mdi mdi-inbox-arrow-down fs-5"></i> Antrean Tindakan</h4>
    <div class="task-grid">
        <a href="{{ route('admin.stores.index', ['status' => 'pending']) }}" class="task-card t-warning">
            <div class="task-icon"><i class="mdi mdi-store-alert"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['toko_pending'] ?? 0 }}</h4>
                <p>Verifikasi Toko</p>
            </div>
        </a>
        <a href="{{ route('admin.products.index', ['status' => 'pending']) }}" class="task-card t-info">
            <div class="task-icon"><i class="mdi mdi-cube-send"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['produk_pending'] ?? 0 }}</h4>
                <p>Moderasi Material</p>
            </div>
        </a>
        <a href="{{ route('admin.payouts.index') }}" class="task-card t-success">
            <div class="task-icon"><i class="mdi mdi-cash-sync"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['payout_pending'] ?? 0 }}</h4>
                <p>Pencairan Dana</p>
            </div>
        </a>
        <a href="{{ route('admin.disputes.index', ['status' => 'aktif']) }}" class="task-card t-danger">
            <div class="task-icon"><i class="mdi mdi-alert-decagram"></i></div>
            <div class="task-info">
                <h4 class="{{ ($tugas['komplain_aktif'] ?? 0) > 0 ? 'text-danger' : '' }}">{{ $tugas['komplain_aktif'] ?? 0 }}</h4>
                <p>Komplain Aktif</p>
            </div>
        </a>
    </div>

    <h4 class="section-title mt-2"><i class="mdi mdi-chart-box-outline fs-5"></i> Statistik Platform</h4>
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL GMV <span><i class="mdi mdi-wallet-outline fs-5"></i></span></div>
            <div class="kpi-value text-primary">Rp {{ number_format($statistik['total_penjualan'] ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-trend trend-up"><i class="mdi mdi-arrow-up"></i> Pertumbuhan Positif</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">PENGGUNA AKTIF <span><i class="mdi mdi-account-group fs-5"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_pengguna'] ?? 0) }}</div>
            <div class="kpi-trend trend-neutral"><i class="mdi mdi-minus"></i> Total Keseluruhan</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">TOKO MATERIAL <span><i class="mdi mdi-storefront fs-5"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_toko'] ?? 0) }}</div>
            <div class="kpi-trend trend-neutral"><i class="mdi mdi-check-circle-outline"></i> Mitra Terverifikasi</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">TOTAL MATERIAL <span><i class="mdi mdi-hard-hat fs-5"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_produk'] ?? 0) }}</div>
            <div class="kpi-trend trend-neutral"><i class="mdi mdi-package-variant"></i> Katalog Aktif</div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="panel-card">
            <div class="panel-title">
                Pertumbuhan Pengguna (7 Hari)
            </div>
            <div style="height: 280px; width: 100%;">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-title">Logistik & Armada Global</div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted text-uppercase">Dalam Pengiriman</span>
                    <i class="mdi mdi-truck-delivery text-success fs-5"></i>
                </div>
                <div class="log-val text-success">-- <small class="text-muted fw-normal" style="font-size: 12px;">Surat Jalan</small></div>
            </div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted text-uppercase">Menunggu Pickup</span>
                    <i class="mdi mdi-package-variant text-warning fs-5"></i>
                </div>
                <div class="log-val text-warning">-- <small class="text-muted fw-normal" style="font-size: 12px;">Order</small></div>
            </div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted text-uppercase">Estimasi Muatan Hari Ini</span>
                    <i class="mdi mdi-weight-kilogram text-info fs-5"></i>
                </div>
                <div class="log-val text-info">-- <small class="text-muted fw-normal" style="font-size: 12px;">Tonase</small></div>
            </div>
        </div>
    </div>

    <div class="panel-card mb-5">
        <div class="panel-title mb-3">Top Performance Toko Bangunan</div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Informasi Toko</th>
                        <th>GMV (Total Penjualan)</th>
                        <th>Total Transaksi</th>
                        <th>Rating Rata-rata</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topToko ?? [] as $index => $toko)
                    <tr>
                        <td class="text-center" style="width: 80px;">
                            @if($index == 0)
                                <i class="mdi mdi-medal text-warning fs-3"></i>
                            @elseif($index == 1)
                                <i class="mdi mdi-medal text-secondary fs-4"></i>
                            @elseif($index == 2)
                                <i class="mdi mdi-medal" style="color: #cd7f32; font-size: 20px;"></i>
                            @else
                                <span class="fw-bold text-muted">{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="store-name">{{ $toko->nama_toko }}</span>
                            <span class="store-meta"><i class="mdi mdi-map-marker-outline"></i> {{ $toko->nama_kota ?? 'Lokasi Belum Diatur' }}</span>
                        </td>
                        <td class="fw-bold text-success fs-6">Rp {{ number_format($toko->total_gmv, 0, ',', '.') }}</td>
                        <td class="fw-bold text-dark">{{ number_format($toko->total_order) }} <span class="text-muted fw-normal small">Pesanan</span></td>
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <i class="mdi mdi-star text-warning"></i>
                                <span class="fw-bold">4.9</span> </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state">
                                <i class="mdi mdi-store-off-outline"></i>
                                <h6 class="fw-bold text-dark mb-1">Belum ada data penjualan.</h6>
                                <p class="small mb-0">Toko dengan penjualan tertinggi akan otomatis muncul di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const canvas = document.getElementById('mainChart');
    if(!canvas) return; // Guard clause

    const ctx = canvas.getContext('2d');
    
    // Create Gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    // Data dari Controller Laravel
    const labels = {!! json_encode($chart_labels ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']) !!};
    const values = {!! json_encode($chart_values ?? [0,0,0,0,0,0,0]) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendaftaran Baru',
                data: values,
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13 },
                    bodyFont: { size: 14, weight: 'bold' },
                    padding: 12,
                    displayColors: false,
                    cornerRadius: 8,
                }
            },
            scales: {
                x: { 
                    grid: { display: false, drawBorder: false }, 
                    ticks: { color: '#64748b', font: { weight: '600' } } 
                },
                y: { 
                    beginAtZero: true, 
                    grid: { borderDash: [5, 5], color: '#f1f5f9', drawBorder: false },
                    ticks: { 
                        color: '#64748b', 
                        stepSize: 1,
                        precision: 0 // Pastikan tidak ada angka desimal untuk jumlah orang
                    }
                }
            }
        }
    });
});
</script>
@endpush