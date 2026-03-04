@extends('layouts.admin')

@section('title', 'Detail Transaksi #' . $order->kode_invoice)

@push('styles')
<style>
    .order-status-tracker { display: flex; justify-content: space-between; position: relative; margin-bottom: 3rem; padding-top: 20px; }
    .order-status-tracker::before { content: ''; position: absolute; top: 40px; left: 5%; width: 90%; height: 4px; background: #e2e8f0; z-index: 1; }
    .step { z-index: 2; text-align: center; width: 20%; }
    .step-icon { width: 45px; height: 45px; border-radius: 50%; background: white; border: 4px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; transition: 0.3s; font-size: 1.2rem; }
    .step.active .step-icon { border-color: #3b82f6; color: #3b82f6; transform: scale(1.1); }
    .step.completed .step-icon { background: #3b82f6; border-color: #3b82f6; color: white; }
    .step-label { font-size: 11px; font-weight: 800; text-transform: uppercase; color: #64748b; }
    .step.active .step-label { color: #1e293b; }

    .detail-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem; }
    .info-card { background: white; border-radius: 16px; border: 1px solid #e2e8f0; padding: 1.5rem; margin-bottom: 1.5rem; }
    .info-title { font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; display: flex; align-items: center; gap: 8px; }
    
    .item-row { display: flex; align-items: center; gap: 1rem; padding: 12px 0; border-bottom: 1px solid #f8fafc; }
    .item-img { width: 60px; height: 60px; border-radius: 10px; object-fit: cover; background: #f1f5f9; }
    
    .summary-row { display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 14px; }
    .total-highlight { background: #f8fafc; padding: 15px; border-radius: 12px; margin-top: 10px; border: 1px solid #e2e8f0; }
</style>
@endpush

@section('content')
<div class="mb-4">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light border fw-bold px-3">
        <i class="mdi mdi-arrow-left"></i> Kembali ke Monitor
    </a>
</div>

{{-- 1. TRACKER STATUS --}}
<div class="info-card">
    <div class="order-status-tracker">
        @php
            $s = strtolower($order->status_pesanan_item);
            $steps = [
                ['id' => 'menunggu_pembayaran', 'icon' => 'mdi-wallet', 'label' => 'Bayar'],
                ['id' => 'diproses', 'icon' => 'mdi-store-clock', 'label' => 'Diproses'],
                ['id' => 'dikirim', 'icon' => 'mdi-truck-fast', 'label' => 'Dikirim'],
                ['id' => 'sampai', 'icon' => 'mdi-package-variant-closed', 'label' => 'Sampai'],
                ['id' => 'selesai', 'icon' => 'mdi-check-decagram', 'label' => 'Selesai']
            ];
            $currentStepIndex = array_search($s, array_column($steps, 'id'));
        @endphp

        @foreach($steps as $index => $step)
            <div class="step {{ $index < $currentStepIndex ? 'completed' : ($index == $currentStepIndex ? 'active' : '') }}">
                <div class="step-icon"><i class="mdi {{ $step['icon'] }}"></i></div>
                <div class="step-label">{{ $step['label'] }}</div>
            </div>
        @endforeach
    </div>
</div>

<div class="detail-grid">
    {{-- KOLOM KIRI: RINCIAN BARANG --}}
    <div>
        <div class="info-card">
            <div class="info-title text-primary">
                <i class="mdi mdi-package-variant-closed"></i> Barang dari Toko: {{ $order->nama_toko }}
            </div>
            @foreach($items as $item)
            <div class="item-row">
                <img src="{{ asset('storage/' . $item->foto_barang) }}" class="item-img" onerror="this.src='https://placehold.co/100x100?text=Produk'">
                <div class="flex-grow-1">
                    <h6 class="fw-bold mb-0 text-dark">{{ $item->nama_barang }}</h6>
                    <small class="text-muted">{{ $item->jumlah_item }} unit x Rp {{ number_format($item->harga_saat_ini, 0, ',', '.') }}</small>
                </div>
                <div class="fw-bold text-dark">
                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                </div>
            </div>
            @endforeach
        </div>

        <div class="info-card">
            <div class="info-title"><i class="mdi mdi-truck-delivery-outline"></i> Informasi Pengiriman</div>
            <div class="row">
                <div class="col-md-6 border-end">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Metode Pengiriman</small>
                    <p class="fw-bold text-dark mb-0">{{ $order->kurir_terpilih ?? 'Belum ditentukan' }}</p>
                    <small class="text-muted">Nomor Resi: <span class="text-primary fw-bold">{{ $order->resi_pengiriman ?? 'Belum terbit' }}</span></small>
                </div>
                <div class="col-md-6 px-4">
                    <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Lokasi Gudang Toko</small>
                    <p class="small text-dark mb-0"><i class="mdi mdi-store-marker text-danger"></i> Hubungi Toko: {{ $order->telp_toko }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: FINANCIAL & PEMBELI --}}
    <div>
        <div class="info-card border-primary" style="background: #f0f7ff;">
            <div class="info-title text-primary"><i class="mdi mdi-cash-register"></i> Ringkasan Pembayaran</div>
            
            <div class="summary-row text-muted">
                <span>Subtotal Barang</span>
                <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row text-muted">
                <span>Ongkos Kirim</span>
                <span>Rp {{ number_format($order->biaya_pengiriman_item, 0, ',', '.') }}</span>
            </div>
            <div class="summary-row text-muted">
                <span>Biaya Layanan</span>
                <span>Rp {{ number_format($order->customer_service_fee + $order->customer_handling_fee, 0, ',', '.') }}</span>
            </div>

            <div class="total-highlight">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="fw-bold text-dark">TOTAL INVOICE</span>
                    <span class="h4 fw-bold text-primary mb-0">Rp {{ number_format($order->subtotal + $order->biaya_pengiriman_item, 0, ',', '.') }}</span>
                </div>
                
                @if($order->tipe_pembayaran == 'DP')
                    <hr>
                    <div class="summary-row text-success fw-bold">
                        <span>SUDAH DIBAYAR (DP)</span>
                        <span>Rp {{ number_format($order->jumlah_dp, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row text-danger fw-bold">
                        <span>TAGIHAN CASH (COD)</span>
                        <span>Rp {{ number_format($order->sisa_tagihan, 0, ',', '.') }}</span>
                    </div>
                @else
                    <div class="badge bg-success w-100 mt-2">DIBAYAR LUNAS (FULL)</div>
                @endif
            </div>
        </div>

        <div class="info-card">
            <div class="info-title"><i class="mdi mdi-account-circle-outline"></i> Informasi Pembeli</div>
            <h6 class="fw-bold text-dark mb-1">{{ $order->nama_pembeli }}</h6>
            <p class="small text-muted mb-0"><i class="mdi mdi-email-outline"></i> {{ $order->email_pembeli }}</p>
            <p class="small text-muted"><i class="mdi mdi-calendar-clock"></i> Transaksi: {{ \Carbon\Carbon::parse($order->tanggal_transaksi)->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection