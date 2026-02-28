@extends('layouts.admin')

@section('title', 'Global Order Monitor')

@push('styles')
<style>
    :root {
        --om-bg: #f8fafc;
        --om-border: #e2e8f0;
        --om-primary: #3b82f6;
    }

    /* MONITORING CARDS */
    .monitor-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; margin-bottom: 1.5rem; }
    .m-card { background: white; border: 1px solid var(--om-border); border-radius: 16px; padding: 1.25rem; display: flex; align-items: center; justify-content: space-between; position: relative; overflow: hidden; }
    .m-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--om-primary); }
    .m-card.warning::before { background: #f59e0b; }
    .m-card.danger::before { background: #ef4444; }
    .m-info h4 { margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b; }
    .m-info span { font-size: 0.8rem; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
    .m-icon { width: 48px; height: 48px; border-radius: 12px; background: var(--om-bg); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--om-primary); }

    /* MODERN TABLE */
    .order-board { background: white; border-radius: 20px; border: 1px solid var(--om-border); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .order-table { width: 100%; border-collapse: collapse; }
    .order-table th { background: #fcfcfd; padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid var(--om-border); text-align: left; }
    .order-table td { padding: 1.25rem 1.5rem; vertical-align: top; border-bottom: 1px solid #f1f5f9; }
    .order-table tbody tr:hover { background: #f8fafc; }

    /* COMPONENTS */
    .invoice-badge { display: inline-flex; align-items: center; gap: 6px; background: #eff6ff; color: #1d4ed8; padding: 6px 12px; border-radius: 8px; font-family: monospace; font-size: 14px; font-weight: 700; text-decoration: none; transition: 0.2s; }
    .invoice-badge:hover { background: #dbeafe; }
    
    .status-pill { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
    .st-pending { background: #fef3c7; color: #b45309; }
    .st-diproses { background: #e0e7ff; color: #1d4ed8; }
    .st-dikirim { background: #fef08a; color: #92400e; }
    .st-selesai { background: #dcfce7; color: #15803d; }
    .st-batal { background: #fee2e2; color: #b91c1c; }
    .st-komplain { background: #fce7f3; color: #a21caf; border: 1px solid #fbcfe8; } /* Untuk Fitur Resolusi */

    .logistics-info { border: 1px dashed #cbd5e1; padding: 8px 12px; border-radius: 8px; background: #f8fafc; font-size: 12px; margin-top: 8px; }
    
    .btn-action { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; border: 1px solid var(--om-border); background: white; color: #475569; transition: 0.2s; }
    .btn-action:hover { background: var(--om-primary); color: white; border-color: var(--om-primary); }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4">
    <h2 class="fw-bold text-dark mb-1">Live Order Monitor</h2>
    <p class="text-muted small">Pantau seluruh pergerakan transaksi, logistik, dan aliran dana di platform Pondasikita.</p>
</div>

{{-- 1. LIVE MONITORING CARDS --}}
<div class="monitor-grid">
    <div class="m-card">
        <div class="m-info">
            <span>Total Transaksi</span>
            <h4>{{ number_format($stats['total']) }}</h4>
        </div>
        <div class="m-icon"><i class="mdi mdi-shopping-outline"></i></div>
    </div>
    <div class="m-card warning">
        <div class="m-info">
            <span>Toko Perlu Kirim</span>
            <h4>{{ number_format($stats['perlu_dikirim']) }}</h4>
        </div>
        <div class="m-icon text-warning" style="background:#fffbeb;"><i class="mdi mdi-package-variant"></i></div>
    </div>
    <div class="m-card">
        <div class="m-info">
            <span>Sedang di Jalan</span>
            <h4>{{ number_format($stats['sedang_dikirim']) }}</h4>
        </div>
        <div class="m-icon"><i class="mdi mdi-truck-fast-outline"></i></div>
    </div>
    <div class="m-card danger">
        <div class="m-info">
            <span class="text-danger">Dispute / Komplain</span>
            <h4 class="text-danger">{{ number_format($stats['komplain']) }}</h4>
        </div>
        <div class="m-icon text-danger" style="background:#fef2f2;"><i class="mdi mdi-alert-octagon-outline"></i></div>
    </div>
</div>

{{-- 2. ORDER BOARD --}}
<div class="order-board">
    <div class="p-4 border-bottom bg-white d-flex flex-column flex-md-row justify-content-between gap-3">
        <div class="btn-group p-1 bg-light rounded-3">
            @php
                $tabs = [
                    'semua' => 'Semua', 'pending' => 'Belum Bayar', 
                    'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 
                    'selesai' => 'Selesai', 'komplain' => 'Komplain'
                ];
            @endphp
            @foreach($tabs as $val => $label)
                <a href="{{ route('admin.orders.index', ['status' => $val, 'search' => $search]) }}" 
                   class="btn btn-sm border-0 {{ $status == $val ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-3">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.orders.index') }}" method="GET" style="min-width: 300px;">
            <input type="hidden" name="status" value="{{ $status }}">
            <div class="input-group">
                <span class="input-group-text bg-light border-0"><i class="mdi mdi-magnify"></i></span>
                <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari No. Invoice / Toko..." value="{{ $search }}">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Detail Transaksi</th>
                    <th>Pembeli & Toko</th>
                    <th>Total & Pembayaran</th>
                    <th>Informasi Logistik</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="invoice-badge">
                            <i class="mdi mdi-receipt-text-outline"></i> {{ $order->kode_invoice }}
                        </a>
                        <div class="mt-2">
                            <span class="status-pill st-{{ strtolower($order->status_pesanan) }}">{{ $order->status_pesanan }}</span>
                        </div>
                        <div class="text-muted small mt-2">{{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d M Y, H:i') }} WIB</div>
                    </td>
                    <td>
                        <div class="mb-2">
                            <span class="d-block text-muted" style="font-size: 10px; text-transform: uppercase; font-weight:700;">Pembeli</span>
                            <span class="fw-bold text-dark">{{ $order->nama_pembeli }}</span>
                        </div>
                        <div>
                            <span class="d-block text-muted" style="font-size: 10px; text-transform: uppercase; font-weight:700;">Penjual (Vendor)</span>
                            <span class="text-primary fw-bold"><i class="mdi mdi-storefront-outline"></i> {{ $order->nama_toko }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark fs-6">Rp {{ number_format($order->total_final, 0, ',', '.') }}</div>
                        <div class="mt-1">
                            @if($order->status_pembayaran == 'paid')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success"><i class="mdi mdi-check-circle"></i> LUNAS (Midtrans)</span>
                            @else
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning"><i class="mdi mdi-clock-outline"></i> Menunggu Bayar</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="logistics-info">
                            <strong class="d-block text-dark"><i class="mdi mdi-truck-delivery"></i> {{ $order->kurir_pengiriman ?? 'Belum dipilih' }}</strong>
                            <span class="text-muted">Resi: <span class="fw-bold text-dark">{{ $order->nomor_resi ?? 'Belum ada resi' }}</span></span>
                        </div>
                        @if($order->kurir_pengiriman == 'Armada Toko')
                            <span class="badge bg-dark mt-1"><i class="mdi mdi-truck-flatbed"></i> Custom Fleet</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn-action" title="Pantau Detail">
                            <i class="mdi mdi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <i class="mdi mdi-clipboard-text-off-outline text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted fw-bold mt-2">Tidak ada transaksi ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-3 bg-light border-top">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection