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
        display: flex; align-items: center; gap: 16px; transition: all 0.3s ease; text-decoration: none;
    }
    .task-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); border-color: var(--admin-primary); }
    .task-icon { width: 52px; height: 52px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    .task-info h4 { font-size: 22px; font-weight: 800; margin: 0; color: var(--admin-text-dark); }
    .task-info p { font-size: 12px; color: var(--admin-secondary); margin: 0; font-weight: 600; }

    .t-warning .task-icon { background: #fff7ed; color: var(--c-warning); }
    .t-info .task-icon { background: #eff6ff; color: var(--c-info); }
    .t-success .task-icon { background: #ecfdf5; color: var(--c-success); }
    .t-danger .task-icon { background: #fef2f2; color: var(--c-danger); }

    /* KPI STATS */
    .section-title { font-size: 15px; font-weight: 700; color: var(--admin-secondary); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 1px; }
    .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px; }
    .kpi-card { background: #fff; border-radius: 16px; padding: 24px; border: 1px solid var(--admin-border); position: relative; }
    .kpi-card::after { content: ''; position: absolute; left: 0; top: 25%; height: 50%; width: 4px; border-radius: 0 4px 4px 0; background: var(--admin-primary); }
    .kpi-label { font-size: 13px; font-weight: 600; color: var(--admin-secondary); margin-bottom: 10px; display: flex; justify-content: space-between; }
    .kpi-value { font-size: 26px; font-weight: 800; color: var(--admin-text-dark); }
    .kpi-trend { font-size: 12px; font-weight: 700; margin-top: 8px; display: flex; align-items: center; gap: 4px; }
    .trend-up { color: var(--c-success); }

    /* LAYOUT ROW */
    .dashboard-row { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; margin-bottom: 30px; }
    .panel-card { background: #fff; border-radius: 16px; border: 1px solid var(--admin-border); padding: 24px; }
    .panel-title { font-size: 16px; font-weight: 700; color: var(--admin-text-dark); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
    
    /* LOGISTICS WIDGET */
    .log-item { padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px solid #f1f5f9; margin-bottom: 12px; }
    .log-val { font-size: 18px; font-weight: 800; color: var(--admin-text-dark); }

    /* TABLE */
    .table-responsive { border-radius: 12px; overflow: hidden; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { background: #f8fafc; padding: 12px 16px; font-size: 11px; font-weight: 700; color: var(--admin-secondary); text-transform: uppercase; text-align: left; }
    .modern-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .store-name { font-weight: 700; color: var(--admin-text-dark); display: block; }
    .store-meta { font-size: 11px; color: var(--admin-secondary); }

    @media (max-width: 992px) { .dashboard-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
    <div class="dashboard-header">
        <div class="welcome-text">
            <h2>Command Center Admin</h2>
            <p>Monitoring operasional Pondasikita secara Real-time.</p>
        </div>
        <div class="date-badge">
            <i class="mdi mdi-calendar text-primary me-2"></i> {{ date('d F Y') }}
        </div>
    </div>

    <h4 class="section-title">Antrean Moderasi</h4>
    <div class="task-grid">
        <a href="#" class="task-card t-warning">
            <div class="task-icon"><i class="mdi mdi-store-alert"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['toko_pending'] ?? 0 }}</h4>
                <p>Verifikasi Toko</p>
            </div>
        </a>
        <a href="#" class="task-card t-info">
            <div class="task-icon"><i class="mdi mdi-cube-send"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['produk_pending'] ?? 0 }}</h4>
                <p>Moderasi Material</p>
            </div>
        </a>
        <a href="#" class="task-card t-success">
            <div class="task-icon"><i class="mdi mdi-cash-sync"></i></div>
            <div class="task-info">
                <h4>{{ $tugas['payout_pending'] ?? 0 }}</h4>
                <p>Pencairan Dana</p>
            </div>
        </a>
        <a href="#" class="task-card t-danger">
            <div class="task-icon"><i class="mdi mdi-alert-decagram"></i></div>
            <div class="task-info">
                <h4>0</h4>
                <p>Komplain Aktif</p>
            </div>
        </a>
    </div>

    <h4 class="section-title">Statistik Utama Platform</h4>
    <div class="kpi-grid">
        <div class="kpi-card">
            <div class="kpi-label">TOTAL GMV <span><i class="mdi mdi-wallet-outline"></i></span></div>
            <div class="kpi-value">Rp {{ number_format($statistik['total_penjualan'] ?? 0, 0, ',', '.') }}</div>
            <div class="kpi-trend trend-up"><i class="mdi mdi-arrow-up"></i> +12%</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">PENGGUNA AKTIF <span><i class="mdi mdi-account-group"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_pengguna'] ?? 0) }}</div>
            <div class="kpi-trend trend-up"><i class="mdi mdi-arrow-up"></i> +5%</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">TOKO MATERIAL <span><i class="mdi mdi-storefront"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_toko'] ?? 0) }}</div>
            <div class="kpi-trend">Stabil</div>
        </div>
        <div class="kpi-card">
            <div class="kpi-label">TOTAL MATERIAL <span><i class="mdi mdi-hard-hat"></i></span></div>
            <div class="kpi-value">{{ number_format($statistik['total_produk'] ?? 0) }}</div>
            <div class="kpi-trend trend-up"><i class="mdi mdi-arrow-up"></i> +8%</div>
        </div>
    </div>

    <div class="dashboard-row">
        <div class="panel-card">
            <div class="panel-title">
                Pertumbuhan Pengguna
                <button class="btn btn-sm btn-light border small">Download Report</button>
            </div>
            <div style="height: 300px;">
                <canvas id="mainChart"></canvas>
            </div>
        </div>

        <div class="panel-card">
            <div class="panel-title">Logistik & Armada</div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">DALAM PENGIRIMAN</span>
                    <i class="mdi mdi-truck-delivery text-success"></i>
                </div>
                <div class="log-val">124 <small class="text-muted" style="font-size: 11px;">Surat Jalan</small></div>
            </div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">MENUNGGU PICKUP</span>
                    <i class="mdi mdi-package-variant text-warning"></i>
                </div>
                <div class="log-val">42 <small class="text-muted" style="font-size: 11px;">Order</small></div>
            </div>
            <div class="log-item">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">TOTAL MUATAN HARI INI</span>
                    <i class="mdi mdi-weight-kilogram text-info"></i>
                </div>
                <div class="log-val">12.5 <small class="text-muted" style="font-size: 11px;">Ton</small></div>
            </div>
        </div>
    </div>

    <div class="panel-card mb-4">
        <div class="panel-title">Top Performance Toko Bangunan</div>
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>GMV (Penjualan)</th>
                        <th>Order Selesai</th>
                        <th>Rating</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <span class="store-name">TB. Sinar Abadi Jaya</span>
                            <span class="store-meta">Subang, Jawa Barat</span>
                        </td>
                        <td class="fw-bold text-dark">Rp 45.200.000</td>
                        <td>128 Pesanan</td>
                        <td><i class="mdi mdi-star text-warning"></i> 4.9</td>
                        <td><span class="badge bg-light text-success border">Aktif</span></td>
                    </tr>
                    <tr>
                        <td>
                            <span class="store-name">Material Maju Bersama</span>
                            <span class="store-meta">Bandung, Jawa Barat</span>
                        </td>
                        <td class="fw-bold text-dark">Rp 32.150.000</td>
                        <td>94 Pesanan</td>
                        <td><i class="mdi mdi-star text-warning"></i> 4.8</td>
                        <td><span class="badge bg-light text-success border">Aktif</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('mainChart').getContext('2d');
    
    // Create Gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.4)');
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    // Data dari Controller Laravel (Perbaikan Syntax)
    const labels = {!! json_encode($chart_labels ?? ['Sen','Sel','Rab','Kam','Jum','Sab','Min']) !!};
    const values = {!! json_encode($chart_values ?? [0,0,0,0,0,0,0]) !!};

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pengguna Baru',
                data: values,
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    displayColors: false
                }
            },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#64748b' } },
                y: { 
                    beginAtZero: true, 
                    grid: { borderDash: [5, 5], color: '#f1f5f9' },
                    ticks: { color: '#64748b', stepSize: 5 }
                }
            }
        }
    });
});
</script>
@endpush