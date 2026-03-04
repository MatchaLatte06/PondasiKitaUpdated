@extends('layouts.admin')

@section('title', 'Laporan & Analitik Platform')

@push('styles')
<style>
    :root {
        --rp-bg: #f8fafc;
        --rp-border: #e2e8f0;
        --rp-primary: #4f46e5;
        --rp-success: #10b981;
        --rp-text: #1e293b;
        --rp-muted: #64748b;
        --rp-danger: #e11d48;
    }

    /* HEADER & FILTER BAR */
    .report-toolbar { background: white; border-radius: 16px; padding: 20px; border: 1px solid var(--rp-border); display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); }
    .date-picker-group { display: flex; align-items: center; gap: 10px; background: var(--rp-bg); padding: 5px 10px; border-radius: 10px; border: 1px solid var(--rp-border); }
    .date-picker-group input[type="date"] { border: none; background: transparent; color: var(--rp-text); font-weight: 600; font-size: 13px; outline: none; }
    
    /* KPI CARDS (ENTERPRISE STYLE) */
    .kpi-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 24px; }
    .kpi-card { background: white; border-radius: 20px; padding: 24px; border: 1px solid var(--rp-border); position: relative; overflow: hidden; transition: all 0.3s ease; }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 20px -8px rgba(0,0,0,0.08); }
    .kpi-icon { position: absolute; top: 24px; right: 24px; width: 48px; height: 48px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
    
    .kpi-title { font-size: 13px; font-weight: 700; color: var(--rp-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 12px; display: block; }
    .kpi-amount { font-size: 28px; font-weight: 800; color: var(--rp-text); margin-bottom: 8px; letter-spacing: -0.5px; }
    .kpi-trend { font-size: 12px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px; padding: 4px 8px; border-radius: 6px; }

    /* SPECIFIC KPI COLORS */
    .kpi-gmv .kpi-icon { background: #eff6ff; color: #3b82f6; }
    .kpi-cost .kpi-icon { background: #fff1f2; color: var(--rp-danger); }
    .kpi-rev .kpi-icon { background: #ecfdf5; color: var(--rp-success); }
    .kpi-aov .kpi-icon { background: #fff7ed; color: #f59e0b; }

    /* CHART PANEL */
    .chart-panel { background: white; border-radius: 20px; border: 1px solid var(--rp-border); padding: 24px; margin-bottom: 24px; }
    .panel-heading { font-size: 16px; font-weight: 800; color: var(--rp-text); margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }

    /* HIGH-DENSITY DATA TABLE */
    .data-table-wrapper { background: white; border-radius: 20px; border: 1px solid var(--rp-border); overflow: hidden; }
    .table-enterprise { width: 100%; border-collapse: collapse; }
    .table-enterprise thead th { background: #f8fafc; color: var(--rp-muted); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 14px 20px; border-bottom: 1px solid var(--rp-border); }
    .table-enterprise tbody td { padding: 16px 20px; font-size: 13px; color: var(--rp-text); border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-enterprise tbody tr:hover { background: #fcfcfc; }
    
    .badge-status { padding: 5px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-block; text-transform: uppercase; }
    .bg-paid { background: #dcfce7; color: #15803d; }
    .bg-pending { background: #fef3c7; color: #b45309; }

    .btn-export { background: white; border: 1px solid var(--rp-border); color: var(--rp-text); font-weight: 600; padding: 8px 16px; border-radius: 8px; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
    .btn-export:hover { background: #f8fafc; border-color: #cbd5e1; }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-3">
    <h2 class="fw-bold text-dark mb-1">Laporan Finansial Platform</h2>
    <p class="text-muted small">Transparansi arus kas, biaya potongan Midtrans, dan laba bersih admin Pondasikita.</p>
</div>

{{-- 1. TOOLBAR & FILTER --}}
<form action="{{ route('admin.reports.index') }}" method="GET" class="report-toolbar shadow-sm">
    <div class="d-flex flex-wrap align-items-center gap-3">
        <div class="date-picker-group">
            <i class="mdi mdi-calendar-range text-muted ms-2"></i>
            <input type="date" name="start_date" value="{{ $start_date }}" required>
            <span class="text-muted">s/d</span>
            <input type="date" name="end_date" value="{{ $end_date }}" required>
        </div>
        <button type="submit" class="btn btn-primary btn-sm px-4 rounded-3 fw-bold shadow-sm">
            <i class="mdi mdi-filter-variant me-1"></i> Terapkan Filter
        </button>
    </div>
    <div class="d-flex gap-2">
        <button type="button" class="btn-export shadow-sm"><i class="mdi mdi-file-excel text-success"></i> Export Excel</button>
        <button type="button" class="btn-export shadow-sm"><i class="mdi mdi-file-pdf-box text-danger"></i> PDF</button>
    </div>
</form>

{{-- 2. KPI METRICS --}}
<div class="kpi-container">
    {{-- GMV --}}
    <div class="kpi-card kpi-gmv shadow-sm">
        <div class="kpi-icon"><i class="mdi mdi-shopping-outline"></i></div>
        <span class="kpi-title">Gross Merchandise Value</span>
        <div class="kpi-amount">Rp {{ number_format($stats['gmv'], 0, ',', '.') }}</div>
        <div class="kpi-trend bg-light text-primary"><i class="mdi mdi-trending-up"></i> Total omzet masuk</div>
    </div>

    {{-- BIAYA MIDTRANS --}}
    <div class="kpi-card kpi-cost shadow-sm">
        <div class="kpi-icon"><i class="mdi mdi-bank-minus"></i></div>
        <span class="kpi-title">Biaya Midtrans (Gateway)</span>
        <div class="kpi-amount text-danger">- Rp {{ number_format($stats['midtrans_costs'], 0, ',', '.') }}</div>
        <div class="kpi-trend bg-light text-danger"><i class="mdi mdi-alert-circle-outline"></i> Potongan resmi</div>
    </div>

    {{-- NET REVENUE --}}
    <div class="kpi-card kpi-rev shadow-sm">
        <div class="kpi-icon"><i class="mdi mdi-cash-check"></i></div>
        <span class="kpi-title">Laba Bersih Platform</span>
        <div class="kpi-amount text-success">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}</div>
        <div class="kpi-trend bg-success text-white fw-bold"><i class="mdi mdi-shield-check"></i> Pendapatan admin</div>
    </div>

    {{-- AOV --}}
    <div class="kpi-card kpi-aov shadow-sm">
        <div class="kpi-icon"><i class="mdi mdi-calculator"></i></div>
        <span class="kpi-title">Avg. Order Value</span>
        <div class="kpi-amount">Rp {{ number_format($stats['aov'], 0, ',', '.') }}</div>
        <div class="kpi-trend bg-light text-muted"><i class="mdi mdi-chart-bubble"></i> Rata-rata belanja</div>
    </div>
</div>

{{-- 3. CHART ANALYTICS --}}
<div class="chart-panel shadow-sm">
    <div class="panel-heading">
        <span>Tren Pertumbuhan Penjualan</span>
        <div class="dropdown">
            <i class="mdi mdi-dots-vertical text-muted fs-4" style="cursor:pointer;"></i>
        </div>
    </div>
    <div style="height: 380px; width: 100%;">
        <canvas id="salesChart"></canvas>
    </div>
</div>

{{-- 4. DETAILED TRANSACTION TABLE --}}
<div class="data-table-wrapper shadow-sm mb-5">
    <div class="panel-heading px-4 pt-4 pb-2 border-bottom">
        <span>Rincian Transaksi & Potongan Gateway</span>
    </div>
    <div class="table-responsive">
        <table class="table-enterprise">
            <thead>
                <tr>
                    <th>Invoice & Pelanggan</th>
                    <th>Waktu Transaksi</th>
                    <th>Total Transaksi</th>
                    <th>Fee Midtrans</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_transactions as $trx)
                <tr>
                    <td>
                        <span class="fw-bold text-primary">{{ $trx->kode_invoice }}</span>
                        <div class="small text-muted mt-1 fw-bold">{{ $trx->nama_pembeli }}</div>
                    </td>
                    <td>
                        <span class="fw-bold">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('d M Y') }}</span><br>
                        <span class="text-muted" style="font-size:11px;">{{ \Carbon\Carbon::parse($trx->tanggal_transaksi)->format('H:i:s') }} WIB</span>
                    </td>
                    <td>
                        <span class="fw-bold text-dark fs-6">Rp {{ number_format($trx->total_final, 0, ',', '.') }}</span>
                    </td>
                    <td class="text-danger fw-bold">
                        - Rp {{ number_format($trx->midtrans_fee, 0, ',', '.') }}
                    </td>
                    <td>
                        <span class="badge-status bg-{{ $trx->status_pembayaran == 'paid' || $trx->status_pembayaran == 'dp_paid' ? 'paid' : 'pending' }}">
                            {{ $trx->status_pembayaran == 'paid' ? 'LUNAS' : ($trx->status_pembayaran == 'dp_paid' ? 'DP LUNAS' : strtoupper($trx->status_pembayaran)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        <i class="mdi mdi-text-box-search-outline fs-1 mb-2 d-block"></i>
                        Tidak ada transaksi pada rentang tanggal ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 bg-light border-top d-flex justify-content-center">
        {{ $recent_transactions->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Create an elegant indigo gradient
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(79, 70, 229, 0.5)'); 
    gradient.addColorStop(1, 'rgba(79, 70, 229, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_labels) !!},
            datasets: [{
                label: 'Total GMV',
                data: {!! json_encode($chart_values) !!},
                borderColor: '#4f46e5',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
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
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, family: 'Inter' },
                    bodyFont: { size: 14, weight: 'bold', family: 'Inter' },
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                x: { 
                    grid: { display: false, drawBorder: false },
                    ticks: { color: '#64748b', font: { family: 'Inter', weight: '600' } }
                },
                y: { 
                    beginAtZero: true,
                    grid: { color: '#f1f5f9', drawBorder: false, borderDash: [5, 5] },
                    ticks: { 
                        color: '#64748b', 
                        font: { family: 'Inter' },
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp ' + (value / 1000000) + 'jt';
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush