@extends('layouts.admin')

@section('title', 'Konfigurasi Sistem Platform')

@push('styles')
<style>
    :root {
        --st-bg: #f4f6f8;
        --st-card: #ffffff;
        --st-border: #dfe3e8;
        --st-text: #212b36;
        --st-muted: #637381;
        --st-primary: #0052cc;
        --st-primary-hover: #0043a6;
    }

    /* LAYOUTING */
    .settings-layout { display: flex; gap: 30px; align-items: flex-start; }
    
    /* SIDEBAR NAVIGATION */
    .settings-nav { width: 260px; flex-shrink: 0; position: sticky; top: 20px; }
    .nav-pills-custom .nav-link {
        color: var(--st-muted); font-weight: 600; padding: 12px 16px; border-radius: 8px;
        margin-bottom: 5px; display: flex; align-items: center; gap: 12px; transition: 0.2s;
    }
    .nav-pills-custom .nav-link i { font-size: 20px; }
    .nav-pills-custom .nav-link:hover { background: #f4f6f8; color: var(--st-text); }
    .nav-pills-custom .nav-link.active { background: #eef3fb; color: var(--st-primary); }

    /* CONTENT AREA */
    .settings-content { flex-grow: 1; max-width: 850px; }
    .st-card { background: var(--st-card); border: 1px solid var(--st-border); border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 24px; margin-bottom: 24px; }
    .st-card-header { border-bottom: 1px solid var(--st-border); padding-bottom: 16px; margin-bottom: 20px; }
    .st-card-title { font-size: 18px; font-weight: 700; color: var(--st-text); margin: 0; }
    .st-card-desc { font-size: 13px; color: var(--st-muted); margin-top: 4px; }

    /* FORM ELEMENTS */
    .form-label { font-weight: 600; color: var(--st-text); font-size: 14px; }
    .form-control, .form-select { border: 1px solid #c4cdd5; border-radius: 8px; padding: 10px 14px; font-size: 14px; transition: 0.2s; }
    .form-control:focus, .form-select:focus { border-color: var(--st-primary); box-shadow: 0 0 0 3px rgba(0, 82, 204, 0.1); }
    .input-group-text { background: #f9fafb; border: 1px solid #c4cdd5; color: var(--st-muted); font-weight: 600; }
    .help-text { font-size: 12px; color: var(--st-muted); margin-top: 6px; }

    /* CUSTOM TOGGLE SWITCH (APPLE/SHOPIFY STYLE) */
    .custom-switch { display: flex; align-items: center; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f4f6f8; }
    .custom-switch:last-child { border-bottom: none; }
    .switch-label { font-size: 14px; font-weight: 600; color: var(--st-text); }
    .switch-desc { font-size: 12px; color: var(--st-muted); }
    
    .toggle-checkbox { display: none; }
    .toggle-label { width: 44px; height: 24px; background: #c4cdd5; border-radius: 24px; position: relative; cursor: pointer; transition: background 0.3s; }
    .toggle-label::after { content: ''; position: absolute; top: 2px; left: 2px; width: 20px; height: 20px; background: white; border-radius: 50%; transition: 0.3s; box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
    .toggle-checkbox:checked + .toggle-label { background: var(--st-primary); }
    .toggle-checkbox:checked + .toggle-label::after { transform: translateX(20px); }

    /* STICKY SAVE BAR */
    .save-bar { position: fixed; bottom: 0; right: 0; width: calc(100% - 250px); background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); border-top: 1px solid var(--st-border); padding: 16px 40px; display: flex; justify-content: flex-end; z-index: 1000; box-shadow: 0 -4px 10px rgba(0,0,0,0.03); }
    .btn-save { background: var(--st-primary); color: white; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; font-size: 14px; transition: 0.2s; }
    .btn-save:hover { background: var(--st-primary-hover); transform: translateY(-1px); }

    @media (max-width: 992px) { .settings-layout { flex-direction: column; } .settings-nav { width: 100%; position: relative; top: 0; } .save-bar { width: 100%; } }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4">
    <h2 class="fw-bold text-dark mb-1">Pengaturan Sistem</h2>
    <p class="text-muted small">Kelola seluruh aspek operasional, keuangan, dan integrasi e-commerce.</p>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" id="mainSettingsForm">
    @csrf
    <div class="settings-layout pb-5">
        
        {{-- TAB NAVIGATION KIRI --}}
        <div class="settings-nav">
            <div class="nav flex-column nav-pills-custom" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <button class="nav-link active" id="tab-general" data-bs-toggle="pill" data-bs-target="#panel-general" type="button" role="tab"><i class="mdi mdi-store-cog-outline"></i> Identitas & Umum</button>
                <button class="nav-link" id="tab-finance" data-bs-toggle="pill" data-bs-target="#panel-finance" type="button" role="tab"><i class="mdi mdi-cash-multiple"></i> Keuangan & Biaya</button>
                <button class="nav-link" id="tab-logistic" data-bs-toggle="pill" data-bs-target="#panel-logistic" type="button" role="tab"><i class="mdi mdi-truck-delivery-outline"></i> Logistik & Wilayah</button>
                <button class="nav-link" id="tab-api" data-bs-toggle="pill" data-bs-target="#panel-api" type="button" role="tab"><i class="mdi mdi-code-json"></i> API & Integrasi</button>
                <button class="nav-link" id="tab-catalog" data-bs-toggle="pill" data-bs-target="#panel-catalog" type="button" role="tab"><i class="mdi mdi-shape-outline"></i> Aturan Katalog</button>
            </div>
        </div>

        {{-- KONTEN PENGATURAN KANAN --}}
        <div class="settings-content tab-content" id="v-pills-tabContent">
            
            <div class="tab-pane fade show active" id="panel-general" role="tabpanel">
                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Profil Platform</h3>
                        <p class="st-card-desc">Informasi dasar yang akan ditampilkan ke pengguna dan mesin pencari.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama Platform</label>
                            <input type="text" name="app_name" class="form-control" value="{{ $settings['app_name'] ?? 'Pondasikita' }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email Kontak Dukungan</label>
                            <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Deskripsi Singkat (SEO Meta)</label>
                            <textarea name="seo_description" class="form-control" rows="3">{{ $settings['seo_description'] ?? '' }}</textarea>
                            <div class="help-text">Maksimal 160 karakter untuk optimasi pencarian Google.</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="panel-finance" role="tabpanel">
                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Biaya Mitra (Seller)</h3>
                        <p class="st-card-desc">Potongan yang dibebankan kepada penjual setiap transaksi berhasil.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Komisi Platform (%)</label>
                            <div class="input-group">
                                <input type="number" name="seller_commission_percent" class="form-control" value="{{ $settings['seller_commission_percent'] ?? '0' }}" step="0.1">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Biaya Tetap per Transaksi</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="seller_fixed_fee" class="form-control" value="{{ $settings['seller_fixed_fee'] ?? '0' }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Biaya Pelanggan (Customer)</h3>
                        <p class="st-card-desc">Biaya ekstra yang ditambahkan pada saat checkout pembeli.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Biaya Layanan Jasa</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="customer_service_fee" class="form-control" value="{{ $settings['customer_service_fee'] ?? '1000' }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Biaya Penanganan (Opsional)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="customer_handling_fee" class="form-control" value="{{ $settings['customer_handling_fee'] ?? '0' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="panel-logistic" role="tabpanel">
                <div class="st-card border-primary" style="background: #f0f7ff;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="st-card-title text-primary"><i class="mdi mdi-database-sync"></i> Sinkronisasi Komerce</h3>
                            <p class="st-card-desc mb-0">Tarik data Provinsi & Kota terbaru dari database ekspedisi Komerce.</p>
                            <small class="text-muted d-block mt-2">Terakhir ditarik: {{ $settings['rajaongkir_last_sync'] ?? 'Belum pernah' }}</small>
                        </div>
                        <button type="submit" form="syncForm" class="btn btn-primary shadow-sm"><i class="mdi mdi-sync"></i> Sinkronkan Sekarang</button>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Kurir Aktif Platform</h3>
                        <p class="st-card-desc">Pilih ekspedisi pengiriman yang diizinkan beroperasi di platform ini.</p>
                    </div>
                    <div class="row g-3">
                        @php 
    $active_couriers = json_decode($settings['rajaongkir_active_couriers'] ?? '[]', true);
    if (!is_array($active_couriers)) {
        $active_couriers = [];
    }
@endphp
                        @foreach($couriers as $code => $name)
                        <div class="col-md-4 col-sm-6">
                            <div class="form-check border rounded-3 p-3 bg-light d-flex align-items-center gap-2">
                                <input class="form-check-input ms-0 mt-0" type="checkbox" name="couriers[]" value="{{ $code }}" id="c_{{ $code }}" {{ in_array($code, $active_couriers) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold m-0" for="c_{{ $code }}">{{ $name }}</label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="panel-api" role="tabpanel">
                <div class="st-card">
                    <div class="st-card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="st-card-title">Midtrans Payment Gateway</h3>
                            <p class="st-card-desc">Konfigurasi jalur pembayaran online.</p>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold" style="font-size:12px;">Live Mode</span>
                            <input type="checkbox" class="toggle-checkbox" id="midtransToggle" name="midtrans_is_production" value="1" {{ ($settings['midtrans_is_production'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="midtransToggle" class="toggle-label"></label>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Client Key</label>
                            <input type="text" name="midtrans_client_key" class="form-control" value="{{ $settings['midtrans_client_key'] ?? '' }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Server Key <span class="text-danger">*RAHASIA</span></label>
                            <input type="password" name="midtrans_server_key" class="form-control" value="{{ $settings['midtrans_server_key'] ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Komerce / RajaOngkir API</h3>
                    </div>
                    <div class="form-group">
                        <label class="form-label">API Key</label>
                        <input type="password" name="rajaongkir_api_key" class="form-control" value="{{ $settings['rajaongkir_api_key'] ?? '' }}">
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="panel-catalog" role="tabpanel">
                <div class="st-card">
                    <div class="st-card-header">
                        <h3 class="st-card-title">Aturan Toko & Moderasi</h3>
                        <p class="st-card-desc">Tetapkan bagaimana seller berinteraksi dengan sistem.</p>
                    </div>
                    
                    <div class="custom-switch">
                        <div>
                            <div class="switch-label">Auto-Approve Material Baru</div>
                            <div class="switch-desc">Jika aktif, produk yang diunggah seller langsung tayang tanpa perlu moderasi Admin.</div>
                        </div>
                        <div>
                            <input type="checkbox" class="toggle-checkbox" id="autoApproveProd" name="auto_approve_products" value="1" {{ ($settings['auto_approve_products'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="autoApproveProd" class="toggle-label"></label>
                        </div>
                    </div>

                    <div class="custom-switch">
                        <div>
                            <div class="switch-label">Auto-Approve Pendaftaran Toko</div>
                            <div class="switch-desc">Izinkan pendaftar langsung berjualan tanpa menunggu verifikasi.</div>
                        </div>
                        <div>
                            <input type="checkbox" class="toggle-checkbox" id="autoApproveStore" name="auto_approve_stores" value="1" {{ ($settings['auto_approve_stores'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="autoApproveStore" class="toggle-label"></label>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</form>

{{-- FORM TERPISAH UNTUK SYNC KOMERCE AGAR TIDAK BENTROK DENGAN SAVE SETTINGS --}}
<form id="syncForm" action="{{ route('admin.settings.syncKomerce') }}" method="POST">
    @csrf
</form>

{{-- STICKY BOTTOM ACTION BAR --}}
<div class="save-bar">
    <button type="submit" form="mainSettingsForm" class="btn-save shadow-sm">
        <i class="mdi mdi-content-save-outline me-1"></i> Simpan Perubahan
    </button>
</div>
@endsection