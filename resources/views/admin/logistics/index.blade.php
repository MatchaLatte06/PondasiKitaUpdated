@extends('layouts.admin')

@section('title', 'Regulasi Logistik & Pengiriman')

@push('styles')
<style>
    :root {
        --log-primary: #2563eb;
        --log-surface: #ffffff;
        --log-bg: #f8fafc;
        --log-border: #e2e8f0;
    }

    .logistic-header { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 20px; padding: 30px; color: white; margin-bottom: 24px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; }
    .logistic-header h2 { margin: 0; font-weight: 800; font-size: 24px; }
    .logistic-header p { margin: 8px 0 0 0; color: #94a3b8; font-size: 14px; }
    
    .log-card { background: var(--log-surface); border: 1px solid var(--log-border); border-radius: 16px; padding: 24px; margin-bottom: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .log-card-title { font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 6px; display: flex; align-items: center; gap: 10px; }
    .log-card-desc { font-size: 13px; color: #64748b; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px dashed var(--log-border); }

    /* COURIER GRID (API) */
    .courier-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
    .courier-box { border: 1px solid var(--log-border); border-radius: 12px; padding: 16px; display: flex; align-items: flex-start; gap: 12px; transition: 0.2s; cursor: pointer; background: var(--log-bg); }
    .courier-box:hover { border-color: var(--log-primary); background: #eff6ff; }
    .courier-box .form-check-input { margin-top: 4px; cursor: pointer; }
    .c-icon { width: 40px; height: 40px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px; color: var(--log-primary); box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
    
    /* CUSTOM FLEET SECTION (ARMADA TOKO) */
    .fleet-banner { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; margin-bottom: 24px; display: flex; gap: 16px; align-items: flex-start; }
    .fleet-banner i { font-size: 32px; color: #d97706; }
    
    .input-group-text { background: #f1f5f9; border-color: var(--log-border); font-weight: 600; color: #475569; }
    .form-control { border-color: var(--log-border); font-weight: 500; }
    .form-control:focus { border-color: var(--log-primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }

    /* MODERN TOGGLE */
    .switch-wrapper { display: flex; justify-content: space-between; align-items: center; padding: 16px; border: 1px solid var(--log-border); border-radius: 12px; margin-bottom: 16px; background: white; }
    .switch-info strong { display: block; font-size: 15px; color: #1e293b; }
    .switch-info span { font-size: 12px; color: #64748b; }
    .toggle-checkbox { display: none; }
    .toggle-label { width: 50px; height: 26px; background: #cbd5e1; border-radius: 30px; position: relative; cursor: pointer; transition: 0.3s; }
    .toggle-label::after { content: ''; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .toggle-checkbox:checked + .toggle-label { background: #10b981; }
    .toggle-checkbox:checked + .toggle-label::after { transform: translateX(24px); }

    .btn-save-floating { position: fixed; bottom: 30px; right: 40px; background: var(--log-primary); color: white; border: none; padding: 14px 28px; border-radius: 100px; font-weight: 700; font-size: 15px; box-shadow: 0 10px 20px -5px rgba(37,99,235,0.4); transition: 0.3s; z-index: 1000; display: flex; align-items: center; gap: 8px; }
    .btn-save-floating:hover { transform: translateY(-3px); box-shadow: 0 15px 25px -5px rgba(37,99,235,0.5); }
</style>
@endpush

@section('content')
<form action="{{ route('admin.logistics.update') }}" method="POST">
    @csrf

    <div class="logistic-header">
        <div>
            <h2>Logistik & Distribusi Platform</h2>
            <p>Atur ekspedisi pihak ketiga dan regulasi armada mandiri untuk mitra toko bangunan Anda.</p>
        </div>
        <div>
            <i class="mdi mdi-map-marker-path" style="font-size: 48px; opacity: 0.5;"></i>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success fw-bold border-0 shadow-sm rounded-3 mb-4">
            <i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="log-card border-warning" style="border-width: 2px;">
                <div class="log-card-title text-warning"><i class="mdi mdi-truck-flatbed"></i> Regulasi Armada Mandiri (Toko)</div>
                <div class="log-card-desc">Fitur krusial untuk e-commerce material. Izinkan seller menggunakan mobil bak/truk mereka sendiri untuk mengirim barang (semen, besi, pasir).</div>

                <div class="fleet-banner shadow-sm" style="background: #eff6ff; border-color: #bfdbfe;">
                    <i class="mdi mdi-set-all text-primary"></i>
                    <div>
                        <strong class="d-block text-dark">Multi-Option Logistics (Berdampingan)</strong>
                        <span class="text-muted small">Pembeli diberikan kebebasan penuh! Tarif Armada Toko (jika disetting oleh seller) akan tampil berdampingan dengan kurir JNE/J&T. Pembeli bisa bebas memilih antara Ekspedisi Reguler atau Armada Mandiri Toko sesuai kebutuhan dan budget mereka.</span>
                    </div>
                </div>

                <div class="switch-wrapper shadow-sm">
                    <div class="switch-info">
                        <strong>Aktifkan Fitur Armada Toko (Global)</strong>
                        <span>Izinkan seller mengatur tarif per-KM untuk pengiriman lokal.</span>
                    </div>
                    <div>
                        <input type="checkbox" class="toggle-checkbox" id="fleetToggle" name="enable_custom_fleet" value="1" {{ ($settings['enable_custom_fleet'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label for="fleetToggle" class="toggle-label"></label>
                    </div>
                </div>

                <div class="switch-wrapper shadow-sm">
                    <div class="switch-info">
                        <strong>Pengiriman Darurat (Sameday Toko)</strong>
                        <span>Beri opsi seller untuk melayani pengiriman CITO (Langsung Kirim) dengan tarif ekstra.</span>
                    </div>
                    <div>
                        <input type="checkbox" class="toggle-checkbox" id="emergencyToggle" name="enable_emergency_delivery" value="1" {{ ($settings['enable_emergency_delivery'] ?? '0') == '1' ? 'checked' : '' }}>
                        <label for="emergencyToggle" class="toggle-label"></label>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Batas Maksimal Jarak (KM)</label>
                        <div class="input-group">
                            <input type="number" name="max_custom_fleet_distance" class="form-control" value="{{ $settings['max_custom_fleet_distance'] ?? '50' }}" placeholder="Contoh: 50">
                            <span class="input-group-text">KM</span>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">Maksimal jarak yang diizinkan sistem dari toko ke pembeli.</small>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-bold small text-muted text-uppercase">Batas Berat Minimum Armada</label>
                        <div class="input-group">
                            <input type="number" name="min_heavy_cargo_weight" class="form-control" value="{{ $settings['min_heavy_cargo_weight'] ?? '0' }}">
                            <span class="input-group-text">KG</span>
                        </div>
                        <small class="text-muted" style="font-size: 11px;">Isi "0" agar armada toko bisa digunakan untuk barang seringan apapun (bebas dipilih pembeli).</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="log-card">
                <div class="log-card-title"><i class="mdi mdi-api text-primary"></i> Ekspedisi Sistem (API)</div>
                <div class="log-card-desc">Pilih kurir pihak ketiga yang tersedia untuk mengirim barang-barang ringan (keran, paku, kuas) ke seluruh Indonesia.</div>

                @php $active_api_couriers = json_decode($settings['api_active_couriers'] ?? '[]', true); @endphp
                @if(!is_array($active_api_couriers)) @php $active_api_couriers = []; @endphp @endif

                <div class="courier-grid mt-3">
                    @foreach($api_couriers as $code => $kurir)
                    <label class="courier-box w-100" for="api_{{ $code }}">
                        <div class="c-icon"><i class="mdi {{ $kurir['icon'] }}"></i></div>
                        <div class="flex-grow-1">
                            <strong class="d-block text-dark">{{ $kurir['name'] }}</strong>
                            <span class="small text-muted">{{ $kurir['type'] }}</span>
                        </div>
                        <div class="form-check m-0">
                            <input class="form-check-input fs-5" type="checkbox" name="couriers[]" value="{{ $code }}" id="api_{{ $code }}" {{ in_array($code, $active_api_couriers) ? 'checked' : '' }}>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                <div class="mt-4 p-3 bg-light rounded-3 border">
                    <span class="d-block fw-bold text-dark mb-1"><i class="mdi mdi-information text-primary"></i> Info Sinkronisasi</span>
                    <span class="small text-muted">Tarif kurir API di atas diambil otomatis secara real-time berdasarkan jarak titik koordinat (Komerce/RajaOngkir).</span>
                </div>
            </div>
        </div>
    </div>

    <button type="submit" class="btn-save-floating">
        <i class="mdi mdi-content-save"></i> Terapkan Regulasi Logistik
    </button>
</form>
@endsection