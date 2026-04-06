@extends('layouts.seller')

@section('title', 'Performa Toko')

@push('styles')
<style>
    /* Hindari canvas resize bug di Chart.js */
    .chart-donut-wrapper { width: 180px; height: 180px; flex-shrink: 0; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans">

    {{-- Header --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center gap-2">
                <i class="mdi mdi-chart-box text-blue-600"></i> Performa Toko
            </h1>
            <div class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                <a href="{{ route('seller.dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <i class="mdi mdi-chevron-right text-xs"></i>
                <span class="text-slate-700 font-medium">Analitik</span>
            </div>
        </div>

        {{-- Filter Cepat --}}
        <div class="flex flex-wrap items-center gap-3 bg-white p-2 rounded-xl shadow-sm border border-slate-200">
            <div class="flex items-center px-3 border-r border-slate-200">
                <i class="mdi mdi-calendar text-slate-400 mr-2"></i>
                <input type="date" class="bg-transparent text-sm font-semibold text-slate-700 focus:outline-none" value="{{ date('Y-m-d') }}">
            </div>
            <select class="bg-transparent text-sm font-semibold text-slate-700 focus:outline-none px-3 border-r border-slate-200 cursor-pointer">
                <option>Semua Pesanan</option>
                <option>Selesai Saja</option>
            </select>
            <div class="flex gap-2 px-2">
                <button class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold hover:bg-blue-600 hover:text-white transition-colors flex items-center gap-1">
                    <i class="mdi mdi-flash"></i> Real-Time
                </button>
                <button class="px-4 py-2 bg-slate-900 text-white rounded-lg text-sm font-bold hover:bg-slate-800 transition-colors flex items-center gap-1">
                    <i class="mdi mdi-download"></i> Laporan
                </button>
            </div>
        </div>
    </div>

    {{-- Navigasi Tab --}}
    <div class="flex gap-8 border-b border-slate-200 mb-6">
        <button onclick="appLogic.switchTab('tinjauan')" id="tab-tinjauan" class="pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors">Ringkasan Utama</button>
        <button onclick="appLogic.switchTab('produk')" id="tab-produk" class="pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors">Analitik Produk</button>
    </div>

    <div id="tab-content">
        {{-- TAB: TINJAUAN --}}
        <div id="tinjauan-content" class="block space-y-6 animate-[fadeIn_0.3s_ease-in-out]">

            {{-- KPI Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
                @php
                    $kpis = [
                        ['title' => 'Total Penjualan', 'value' => 'Rp ' . number_format($kriteria['penjualan']['nilai'], 0, ',', '.'), 'vs' => $kriteria['penjualan']['perbandingan'], 'icon' => 'mdi-cash-multiple', 'color' => 'blue'],
                        ['title' => 'Jumlah Pesanan', 'value' => number_format($kriteria['pesanan']['nilai'], 0, ',', '.'), 'vs' => $kriteria['pesanan']['perbandingan'], 'icon' => 'mdi-receipt', 'color' => 'indigo'],
                        ['title' => 'Tingkat Konversi', 'value' => $kriteria['tingkat_konversi']['nilai'] . '%', 'vs' => $kriteria['tingkat_konversi']['perbandingan'], 'icon' => 'mdi-percent-circle-outline', 'color' => 'emerald'],
                        ['title' => 'Total Pengunjung', 'value' => number_format($kriteria['pengunjung']['nilai'], 0, ',', '.'), 'vs' => $kriteria['pengunjung']['perbandingan'], 'icon' => 'mdi-account-group-outline', 'color' => 'orange']
                    ];
                @endphp

                @foreach($kpis as $kpi)
                <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md hover:border-slate-300 transition-all group">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-10 h-10 rounded-xl bg-{{ $kpi['color'] }}-50 flex items-center justify-center text-{{ $kpi['color'] }}-600 group-hover:scale-110 transition-transform">
                            <i class="mdi {{ $kpi['icon'] }} text-xl"></i>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-bold {{ $kpi['vs'] >= 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600' }}">
                            <i class="mdi {{ $kpi['vs'] >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }} mr-1"></i> {{ abs($kpi['vs']) }}%
                        </span>
                    </div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">{{ $kpi['title'] }}</h3>
                    <div class="text-2xl font-black text-slate-900">{{ $kpi['value'] }}</div>
                </div>
                @endforeach
            </div>

            {{-- Chart Utama --}}
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <h2 class="text-lg font-bold text-slate-900">Tren Performa Berjalan</h2>
                    <div class="flex flex-wrap gap-4" id="chartToggles">
                        @foreach(['penjualan' => 'Penjualan', 'pesanan' => 'Pesanan', 'pengunjung' => 'Pengunjung'] as $val => $label)
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" value="{{ $val }}" class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500" {{ $val != 'pesanan' ? 'checked' : '' }} onchange="appLogic.updateMainChart()">
                            <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900">{{ $label }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="w-full h-[350px] relative">
                    <canvas id="mainPerformanceChart"></canvas>
                </div>
            </div>

            {{-- Grid Bawah: Saluran & Demografi --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- Saluran Penjualan --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Sumber Pendapatan</h2>
                    <div class="space-y-4">
                        @php
                            $channels = [
                                ['label' => 'Halaman Produk', 'icon' => 'mdi-cube-outline', 'val' => $saluran['halaman_produk']['nilai'], 'vs' => $saluran['halaman_produk']['perbandingan']],
                                ['label' => 'Live Streaming', 'icon' => 'mdi-video-wireless-outline', 'val' => $saluran['live']['nilai'], 'vs' => $saluran['live']['perbandingan']],
                                ['label' => 'Video Promosi', 'icon' => 'mdi-play-circle-outline', 'val' => $saluran['video']['nilai'], 'vs' => $saluran['video']['perbandingan']],
                            ]
                        @endphp
                        @foreach($channels as $ch)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 rounded-xl transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-100 rounded-lg text-slate-500"><i class="mdi {{ $ch['icon'] }} text-lg"></i></div>
                                <span class="font-bold text-slate-700">{{ $ch['label'] }}</span>
                            </div>
                            <div class="text-right">
                                <div class="font-black text-slate-900">Rp {{ number_format($ch['val'], 0, ',', '.') }}</div>
                                <div class="text-xs font-bold {{ $ch['vs'] >= 0 ? 'text-emerald-500' : 'text-red-500' }}">{{ $ch['vs'] >= 0 ? '+' : '' }}{{ $ch['vs'] }}%</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Retensi Pembeli --}}
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">Retensi & Akuisisi</h2>
                    <div class="flex flex-col sm:flex-row items-center gap-6 flex-1">

                        <div class="relative chart-donut-wrapper">
                            <canvas id="buyerDonutChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-2xl font-black text-slate-900">{{ $pembeli['pembeli_saat_ini_persen'] }}%</span>
                                <span class="text-[10px] font-bold text-slate-500 uppercase">Setia</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3 w-full">
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-xs font-bold text-slate-500 mb-1">TOTAL PEMBELI</div>
                                <div class="text-lg font-black text-slate-900">{{ number_format($pembeli['total_pembeli']) }}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-xs font-bold text-slate-500 mb-1">PEMBELI BARU</div>
                                <div class="text-lg font-black text-slate-900">{{ number_format($pembeli['pembeli_baru']) }}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-xs font-bold text-slate-500 mb-1">POTENSI KEMBALI</div>
                                <div class="text-lg font-black text-slate-900">{{ number_format($pembeli['potensi_pembeli']) }}</div>
                            </div>
                            <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="text-xs font-bold text-slate-500 mb-1">RETENTION RATE</div>
                                <div class="text-lg font-black text-blue-600">{{ $pembeli['tingkat_pembeli_berulang'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- TAB: PRODUK --}}
        <div id="produk-content" class="hidden">
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm py-24 px-6 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-slate-100 rounded-full mb-4">
                    <i class="mdi mdi-cube-scan text-4xl text-slate-400"></i>
                </div>
                <h2 class="text-xl font-black text-slate-900 mb-2">Belum Ada Data Produk</h2>
                <p class="text-slate-500 font-medium max-w-md mx-auto">Kumpulkan lebih banyak metrik transaksi untuk mengaktifkan heat-map dan analisis performa per-SKU.</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const appLogic = {
    charts: {},
    data: {
        labels: @json($chart_labels),
        metrics: {
            penjualan: @json($chart_data['penjualan']),
            pesanan: @json($chart_data['pesanan']),
            pengunjung: @json($chart_data['pengunjung'])
        },
        configs: {
            penjualan: { label: 'Penjualan (Rp)', borderColor: '#2563eb', backgroundColor: 'rgba(37, 99, 235, 0.1)' },
            pesanan: { label: 'Pesanan', borderColor: '#4f46e5', backgroundColor: 'rgba(79, 70, 229, 0.1)' },
            pengunjung: { label: 'Pengunjung', borderColor: '#0f172a', backgroundColor: 'rgba(15, 23, 42, 0.1)' }
        }
    },

    init() {
        this.initMainChart();
        this.initDonutChart();
    },

    switchTab(tab) {
        ['tinjauan', 'produk'].forEach(t => {
            document.getElementById(`${t}-content`).classList.toggle('hidden', t !== tab);
            const btn = document.getElementById(`tab-${t}`);
            if(t === tab) {
                btn.className = 'pb-3 text-sm font-bold border-b-2 border-blue-600 text-blue-600 transition-colors';
            } else {
                btn.className = 'pb-3 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-slate-800 transition-colors';
            }
        });
    },

    initMainChart() {
        const ctx = document.getElementById('mainPerformanceChart').getContext('2d');
        this.charts.main = new Chart(ctx, {
            type: 'line',
            data: { labels: this.data.labels, datasets: [] },
            options: {
                responsive: true, maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', align: 'end', labels: { usePointStyle: true, boxWidth: 8, font: { family: 'inherit', weight: '600' } } },
                    tooltip: { backgroundColor: '#0f172a', padding: 12, cornerRadius: 8, titleFont: { size: 13 }, bodyFont: { size: 14, weight: 'bold' } }
                },
                scales: {
                    x: { grid: { display: false }, border: { display: false } },
                    y: { border: { display: false }, grid: { color: '#f1f5f9' }, beginAtZero: true }
                },
                elements: { line: { tension: 0.4, borderWidth: 3 }, point: { radius: 0, hoverRadius: 6, backgroundColor: '#fff', borderWidth: 2 } }
            }
        });
        this.updateMainChart();
    },

    updateMainChart() {
        const active = Array.from(document.querySelectorAll('#chartToggles input:checked')).map(cb => cb.value);
        this.charts.main.data.datasets = active.map(key => ({
            ...this.data.configs[key],
            data: this.data.metrics[key],
            fill: true,
            pointBorderColor: this.data.configs[key].borderColor
        }));
        this.charts.main.update();
    },

    initDonutChart() {
        const ctx = document.getElementById('buyerDonutChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Baru', 'Setia'],
                datasets: [{
                    data: [{{ $pembeli_donut_chart['baru'] }}, {{ $pembeli_donut_chart['berulang'] }}],
                    backgroundColor: ['#e2e8f0', '#2563eb'],
                    borderWidth: 0, cutout: '75%', borderRadius: 4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false }, tooltip: { backgroundColor: '#0f172a', padding: 10, bodyFont: { weight: 'bold' } } }
            }
        });
    }
};

document.addEventListener("DOMContentLoaded", () => appLogic.init());
</script>
@endpush
