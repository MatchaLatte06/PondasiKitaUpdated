@extends('layouts.seller')

@section('title', 'Dashboard Seller')

@section('content')
{{-- KITA TARUH CSS LANGSUNG DI SINI AGAR DIJAMIN TERBACA OLEH BROWSER --}}
<style>
    /* RESET & DASAR */
    .seller-dashboard-wrapper {
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        color: #1e293b;
    }
    .seller-dashboard-wrapper a { text-decoration: none !important; }
    .seller-dashboard-wrapper p, .seller-dashboard-wrapper h3, .seller-dashboard-wrapper h5 { margin: 0; padding: 0; }

    /* HEADER DASHBOARD */
    .sd-hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 24px 30px;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.2);
    }
    .sd-hero-title { font-size: 24px; font-weight: 800; margin-bottom: 4px; display: flex; align-items: center; gap: 12px; }
    .sd-hero-icon { background: rgba(255,255,255,0.1); padding: 10px; border-radius: 10px; color: #3b82f6; }
    .sd-hero-subtitle { color: #94a3b8; font-size: 14px; }
    
    .sd-tier-badge {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #10b981;
        padding: 8px 16px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* SECTION TITLE */
    .sd-section-title { font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }

    /* ACTION GRID (YANG PERLU DILAKUKAN) */
    .sd-action-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .sd-action-card {
        background: white;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        display: flex;
        flex-direction: column;
        justify-content: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .sd-action-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 5px; background: transparent; transition: 0.3s; }
    
    .sd-action-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05); }
    .sd-action-card.c-blue:hover { border-color: #3b82f6; } .sd-action-card.c-blue:hover::before { background: #3b82f6; }
    .sd-action-card.c-green:hover { border-color: #10b981; } .sd-action-card.c-green:hover::before { background: #10b981; }
    .sd-action-card.c-orange:hover { border-color: #f59e0b; } .sd-action-card.c-orange:hover::before { background: #f59e0b; }
    
    .sd-action-val { font-size: 36px; font-weight: 900; line-height: 1; margin-bottom: 8px; }
    .sd-action-label { font-size: 13px; font-weight: 700; text-transform: uppercase; color: #64748b; display: flex; align-items: center; gap: 6px; }

    /* MAIN LAYOUT GRID (KIRI & KANAN) */
    .sd-main-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    @media (max-width: 992px) { .sd-main-layout { grid-template-columns: 1fr; } }

    /* CARD UMUM */
    .sd-card { background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px; display: flex; flex-direction: column; height: 100%; }
    
    /* METRIC BOXES DALAM GRAFIK */
    .sd-metric-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
    .sd-metric-box { background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #f1f5f9; }
    .sd-metric-box .m-label { font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px; }
    .sd-metric-box .m-val { font-size: 20px; font-weight: 800; color: #0f172a; }

    /* LIST TOP PRODUK */
    .sd-list { list-style: none; padding: 0; margin: 0; }
    .sd-list-item { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px dashed #e2e8f0; }
    .sd-list-item:last-child { border-bottom: none; }
    .sd-list-name { font-size: 14px; font-weight: 700; color: #1e293b; }
    .sd-list-qty { font-size: 12px; font-weight: 700; background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 6px; }

    /* INFO BOX B2B */
    .sd-info-box { background: white; border-radius: 16px; border: 1px solid #3b82f6; overflow: hidden; margin-top: 24px; }
    .sd-info-header { background: #3b82f6; color: white; padding: 12px 20px; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 8px; }
    .sd-info-body { padding: 20px; display: flex; gap: 15px; align-items: flex-start; }
</style>

<div class="seller-dashboard-wrapper">
    
    {{-- 1. HEADER --}}
    <div class="sd-hero">
        <div>
            <h3 class="sd-hero-title">
                <div class="sd-hero-icon"><i class="mdi mdi-storefront-outline"></i></div>
                {{ $toko->nama_toko }}
            </h3>
            <p class="sd-hero-subtitle">Monitor performa operasional gudang dan penjualan material Anda secara real-time.</p>
        </div>
        <div>
            <div class="sd-tier-badge">
                <i class="mdi mdi-shield-check fs-5"></i> 
                {{ str_replace('_', ' ', strtoupper($toko->tier_toko ?? 'REGULAR')) }}
            </div>
        </div>
    </div>

    {{-- 2. STATUS OPERASIONAL --}}
    <div class="sd-section-title">
        <i class="mdi mdi-forklift text-muted fs-4"></i> Status Operasional Gudang
    </div>
    
    <div class="sd-action-grid">
        <a href="{{ route('seller.orders.index', ['status' => 'diproses']) }}" class="sd-action-card c-blue">
            <div class="sd-action-val" style="color: #3b82f6;">{{ $perlu_diproses }}</div>
            <p class="sd-action-label"><i class="mdi mdi-package-variant"></i> Pesanan Masuk</p>
        </a>
        
        <a href="{{ route('seller.orders.index', ['status' => 'dikirim']) }}" class="sd-action-card c-green">
            <div class="sd-action-val" style="color: #10b981;">{{ $telah_diproses }}</div>
            <p class="sd-action-label"><i class="mdi mdi-truck-fast"></i> Siap Angkut / Kirim</p>
        </a>
        
        <a href="{{ route('seller.orders.return') }}" class="sd-action-card c-orange">
            <div class="sd-action-val" style="color: #f59e0b;">{{ $pengembalian }}</div>
            <p class="sd-action-label"><i class="mdi mdi-alert-octagon"></i> Komplain Pembeli</p>
        </a>
        
        <div class="sd-action-card" style="background: #f8fafc; border: 1px dashed #cbd5e1; cursor: default;">
            <div class="sd-action-val" style="color: #ef4444;">{{ $dibatalkan }}</div>
            <p class="sd-action-label"><i class="mdi mdi-cancel"></i> Dibatalkan Sistem</p>
        </div>
    </div>

    {{-- 3. LAYOUT UTAMA (KIRI KANAN) --}}
    <div class="sd-main-layout">
        
        {{-- KOLOM KIRI: GRAFIK & ANALITIK --}}
        <div>
            <div class="sd-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <div class="sd-section-title" style="margin: 0;">
                        <i class="mdi mdi-finance text-success fs-4"></i> Analitik Pendapatan
                    </div>
                    <select id="chartFilter" style="padding: 6px 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-weight: bold; outline: none;">
                        <option value="tahun" selected>Tahun {{ Carbon\Carbon::now()->year }}</option>
                        <option value="bulan">Bulan Ini</option>
                    </select>
                </div>

                <div class="sd-metric-grid">
                    <div class="sd-metric-box">
                        <p class="m-label">Total Omzet (Kotor)</p>
                        <p class="m-val" style="color: #3b82f6;">Rp {{ number_format($total_penjualan ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div class="sd-metric-box">
                        <p class="m-label">Transaksi Berhasil</p>
                        <p class="m-val">{{ number_format($total_pesanan ?? 0) }} <span style="font-size:14px; font-weight:normal; color:#64748b;">Invoice</span></p>
                    </div>
                    <div class="sd-metric-box">
                        <p class="m-label">Kapasitas Produk Aktif</p>
                        <p class="m-val">{{ number_format($total_produk_aktif ?? 0) }} <span style="font-size:14px; font-weight:normal; color:#64748b;">SKU</span></p>
                    </div>
                    <div class="sd-metric-box">
                        <p class="m-label">Rasio Konversi</p>
                        <p class="m-val" style="color: #10b981;">{{ $konversi }}%</p>
                    </div>
                </div>

                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="penjualanChart"></canvas>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: TOP PRODUK & INFO --}}
        <div>
            {{-- MATERIAL TERLARIS --}}
            <div class="sd-card">
                <div class="sd-section-title">
                    <i class="mdi mdi-podium-gold text-warning fs-4"></i> Material Terlaris
                </div>
                
                @if(count($top_produk_keys) > 0)
                    <ul class="sd-list">
                        @foreach($top_produk_keys as $index => $nama)
                            <li class="sd-list-item">
                                <span class="sd-list-name">{{ Str::limit($nama, 30) }}</span>
                                <span class="sd-list-qty">{{ $top_produk_values[$index] }} Terjual</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div style="text-align: center; padding: 40px 0; color: #94a3b8;">
                        <i class="mdi mdi-package-variant-closed" style="font-size: 40px; opacity: 0.5;"></i>
                        <p style="font-size: 13px; font-weight: 600; margin-top: 10px;">Belum ada data penjualan.</p>
                    </div>
                @endif
            </div>

            {{-- INFO DISTRIBUTOR (B2B) --}}
            <div class="sd-info-box">
                <div class="sd-info-header">
                    <i class="mdi mdi-bullhorn-outline fs-5"></i> Info Keagenan
                </div>
                <div class="sd-info-body">
                    <div style="background: #eff6ff; color: #3b82f6; padding: 10px; border-radius: 10px; flex-shrink: 0;">
                        <i class="mdi mdi-truck-flatbed fs-3"></i>
                    </div>
                    <div>
                        <p style="font-weight: 800; font-size: 14px; margin-bottom: 4px; color: #0f172a;">Pengiriman Armada Sendiri</p>
                        <p style="font-size: 12px; color: #64748b; line-height: 1.5;">Pastikan Anda telah mengatur "Armada Toko" di menu Pengaturan Pengiriman agar pembeli dapat memesan material berat/curah.</p>
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
        const dataChart = {
            bulan: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                data: [0, 0, 0, 0] 
            },
            tahun: {
                labels: {!! json_encode($labels_bulan ?? ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des']) !!},
                data: {!! json_encode(isset($penjualan_tahunan) ? array_values($penjualan_tahunan) : [0,0,0,0,0,0,0,0,0,0,0,0]) !!}
            }
        };

        const ctx = document.getElementById('penjualanChart').getContext('2d');
        
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)'); 
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        let myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: dataChart.tahun.labels,
                datasets: [{
                    label: 'Omzet (Rp)',
                    data: dataChart.tahun.data,
                    backgroundColor: gradient,
                    borderColor: '#3b82f6',
                    borderWidth: 3,
                    tension: 0.4, 
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#3b82f6',
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
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [4, 4], color: '#e2e8f0' },
                        ticks: {
                            color: '#64748b',
                            callback: function(value) {
                                if(value >= 1000000) return 'Rp ' + (value/1000000) + 'Jt';
                                if(value >= 1000) return 'Rp ' + (value/1000) + 'k';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: { 
                        grid: { display: false },
                        ticks: { color: '#64748b', font: { weight: 'bold' } }
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        document.getElementById('chartFilter').addEventListener('change', function(e) {
            const selectedPeriod = e.target.value;
            myChart.data.labels = dataChart[selectedPeriod].labels;
            myChart.data.datasets[0].data = dataChart[selectedPeriod].data;
            myChart.update();
        });
    });
</script>
@endpush