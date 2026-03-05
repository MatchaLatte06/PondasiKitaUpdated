@extends('layouts.app')

<style>
    .timeline-container { position: relative; padding-left: 40px; }
    .timeline-container::before { content: ''; position: absolute; left: 15px; top: 0; width: 2px; height: 100%; background: #e2e8f0; }
    .timeline-item { position: relative; margin-bottom: 30px; }
    .timeline-dot { position: absolute; left: -32px; top: 0; width: 16px; height: 16px; border-radius: 50%; background: #cbd5e1; border: 3px solid white; box-shadow: 0 0 0 3px #f1f5f9; }
    .timeline-item.active .timeline-dot { background: #3b82f6; box-shadow: 0 0 0 3px #eff6ff; }
    .timeline-item.active .status-title { color: #3b82f6; }
</style>

@section('content')
<div class="container mt-5" style="max-width: 700px;">
    <a href="{{ route('pesanan.index') }}" class="text-decoration-none small mb-3 d-inline-block">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pesanan
    </a>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <h5 class="fw-bold mb-1">Lacak Pengiriman</h5>
            <p class="text-muted small">Nomor Resi: <strong>{{ $order->kode_pesanan }}</strong></p>
            <hr class="my-4" style="border-style: dashed;">

            <div class="timeline-container">
                @foreach($trackingLogs as $index => $log)
                <div class="timeline-item {{ $index == 0 ? 'active' : '' }}">
                    <div class="timeline-dot"></div>
                    <h6 class="fw-bold mb-1 status-title">{{ $log['status'] }}</h6>
                    <p class="mb-1 small">{{ $log['desc'] }}</p>
                    <span class="text-muted" style="font-size: 0.75rem;">{{ $log['time'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card border-0 bg-primary text-white rounded-4">
        <div class="card-body p-4 d-flex align-items-center gap-3">
            <i class="fas fa-info-circle fa-2x"></i>
            <div>
                <small class="d-block opacity-75">Butuh bantuan?</small>
                <span class="fw-bold">Hubungi Customer Service Pondasikita</span>
            </div>
        </div>
    </div>
</div>
@endsection