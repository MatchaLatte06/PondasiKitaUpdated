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
    .help-text { font-size: 12px; color: var(--st-muted); margin-top: 6px; line-height: 1.4; }

    /* INFO ICON TOOLTIP - CLICKABLE */
    .info-btn { font-size: 16px; color: #919eab; cursor: pointer; transition: 0.2s; background: none; border: none; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; width: 24px; height: 24px; }
    .info-btn:hover { color: var(--st-primary); background: #eef3fb; }

    /* CUSTOM TOGGLE SWITCH */
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
            
            {{-- PANEL: IDENTITAS & UMUM --}}
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

            {{-- PANEL: KEUANGAN & BIAYA --}}
            <div class="tab-pane fade" id="panel-finance" role="tabpanel">
                
                {{-- BIAYA MITRA / SKEMA KOMISI PROGRESIF --}}
                <div class="st-card border-primary">
                    <div class="st-card-header">
                        <h3 class="st-card-title text-primary"><i class="mdi mdi-percent-circle-outline"></i> Skema Komisi Mitra (Seller)</h3>
                        <p class="st-card-desc">Potongan persentase yang dibebankan kepada penjual setiap transaksi berhasil, dibedakan berdasarkan kasta/tier toko.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted d-flex justify-content-between align-items-center">
                                <span><i class="mdi mdi-storefront-outline"></i> Toko Reguler</span>
                                <button type="button" class="info-btn btn-show-info" data-title="Toko Reguler" data-desc="Toko baru atau penjual standar tanpa legalitas perusahaan. Disarankan diberi komisi sangat rendah (0% - 0.5%) sebagai strategi 'Bakar Uang' untuk menarik minat seller bergabung ke platform Anda.">
                                    <i class="mdi mdi-help-circle-outline"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" name="commission_regular_percent" class="form-control" value="{{ $settings['commission_regular_percent'] ?? '0.5' }}" step="0.1" min="0">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="help-text">Fase akuisisi/Toko baru. Tampil standar di pencarian tanpa badge khusus.</div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label text-success d-flex justify-content-between align-items-center">
                                <span><i class="mdi mdi-lightning-bolt"></i> Power Merchant</span>
                                <button type="button" class="info-btn btn-show-info" data-title="Power Merchant" data-desc="Toko yang sudah laris dan punya reputasi baik. Disarankan komisi menengah (1.5% - 2%). Seller bersedia dipotong komisi lebih tinggi karena mereka mendapat 'Badge Hijau' yang meningkatkan kepercayaan pembeli dan posisi pencarian.">
                                    <i class="mdi mdi-help-circle-outline"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" name="commission_power_percent" class="form-control border-success" value="{{ $settings['commission_power_percent'] ?? '2.0' }}" step="0.1" min="0">
                                <span class="input-group-text bg-success text-white border-success">%</span>
                            </div>
                            <div class="help-text">Toko laris terpilih. Mendapat badge Hijau & prioritas naik di pencarian.</div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label d-flex justify-content-between align-items-center" style="color: #8b5cf6;">
                                <span><i class="mdi mdi-check-decagram"></i> Official Store</span>
                                <button type="button" class="info-btn btn-show-info" data-title="Official Store" data-desc="Distributor resmi bersertifikat (PT/CV) yang menjual partai besar. Disarankan komisi tertinggi (3% - 5%) karena platform memberikan mereka akses langsung ke target pasar kelas kakap (Kontraktor B2B / Proyek).">
                                    <i class="mdi mdi-help-circle-outline"></i>
                                </button>
                            </label>
                            <div class="input-group">
                                <input type="number" name="commission_official_percent" class="form-control" style="border-color: #8b5cf6;" value="{{ $settings['commission_official_percent'] ?? '4.0' }}" step="0.1" min="0">
                                <span class="input-group-text text-white" style="background: #8b5cf6; border-color: #8b5cf6;">%</span>
                            </div>
                            <div class="help-text">Distributor resmi berbadan hukum. Monopoli proyek besar & B2B.</div>
                        </div>

                        <div class="col-12 mt-3 pt-3 border-top">
                            <label class="form-label">Biaya Tetap per Transaksi (Opsional)</label>
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="seller_fixed_fee" class="form-control" value="{{ $settings['seller_fixed_fee'] ?? '0' }}">
                            </div>
                            <div class="help-text">Dipotong flat dari semua kasta toko di luar komisi persentase di atas.</div>
                        </div>
                    </div>
                </div>

                {{-- BIAYA PELANGGAN: ANTI-BANGKRUT LOGIC --}}
                <div class="st-card border-info">
                    <div class="st-card-header">
                        <h3 class="st-card-title text-info"><i class="mdi mdi-credit-card-outline"></i> Biaya Pelanggan & Payment Gateway</h3>
                        <p class="st-card-desc">Atur biaya penanganan (Handling Fee) yang dibebankan ke pembeli sesuai metode pembayaran. Sistem telah mengunci batas bawah agar platform Anda tidak rugi bayar Midtrans.</p>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Biaya Handling QRIS (%)</label>
                            <div class="input-group">
                                <input type="number" name="fee_qris_percent" class="form-control" value="{{ $settings['fee_qris_percent'] ?? '1.5' }}" step="0.1" min="0.8">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="help-text text-danger fw-bold"><i class="mdi mdi-alert-circle"></i> Wajib di atas 0.7% (Potongan asli Midtrans).</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Biaya Handling Bank Transfer (VA)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="fee_va_flat" class="form-control" value="{{ $settings['fee_va_flat'] ?? '5000' }}" min="4500">
                            </div>
                            <div class="help-text text-danger fw-bold"><i class="mdi mdi-alert-circle"></i> Wajib di atas Rp 4.000 (Potongan asli Midtrans).</div>
                        </div>

                        <div class="col-12 mt-3 pt-3 border-top">
                            <label class="form-label text-success">Biaya Layanan Jasa (Keuntungan Bersih Platform)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-success">Rp</span>
                                <input type="number" name="customer_service_fee" class="form-control border-success" value="{{ $settings['customer_service_fee'] ?? '1000' }}">
                            </div>
                            <div class="help-text">Biaya flat yang selalu ditambahkan di setiap transaksi sebagai untung bersih Anda (Misal: Rp 1.000/transaksi).</div>
                        </div>
                    </div>
                </div>

                {{-- PANEL BARU: SISTEM DP B2B --}}
                <div class="st-card border-warning mt-4">
                    <div class="st-card-header">
                        <h3 class="st-card-title text-warning"><i class="mdi mdi-handshake"></i> Sistem B2B & Uang Muka (DP)</h3>
                        <p class="st-card-desc">Atur syarat pembelian skala besar dengan sistem DP. Pelunasan dilakukan secara tunai (CASH/COD) kepada pihak toko saat barang sampai.</p>
                    </div>
                    
                    <div class="custom-switch mb-3">
                        <div>
                            <div class="switch-label">Aktifkan Sistem DP</div>
                            <div class="switch-desc">Jika aktif, pembeli partai besar bisa membayar DP via web dan melunasi sisanya di tempat.</div>
                        </div>
                        <div>
                            <input type="checkbox" class="toggle-checkbox" id="dpToggle" name="enable_dp_system" value="1" {{ ($settings['enable_dp_system'] ?? '0') == '1' ? 'checked' : '' }}>
                            <label for="dpToggle" class="toggle-label"></label>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Minimal Belanja (Syarat DP)</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="min_nominal_dp" class="form-control" value="{{ $settings['min_nominal_dp'] ?? '10000000' }}">
                            </div>
                            <div class="help-text">Minimal total harga agar opsi DP muncul saat checkout.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Persentase DP Awal</label>
                            <div class="input-group">
                                <input type="number" name="dp_percent" class="form-control" value="{{ $settings['dp_percent'] ?? '50' }}" max="99">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="help-text">Berapa persen yang harus dibayar via Midtrans.</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PANEL: LOGISTIK --}}
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

            {{-- PANEL: API & INTEGRASI --}}
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

            {{-- PANEL: KATALOG & MODERASI --}}
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

{{-- FORM TERPISAH UNTUK SYNC KOMERCE --}}
<form id="syncForm" action="{{ route('admin.settings.syncKomerce') }}" method="POST">
    @csrf
</form>

{{-- STICKY BOTTOM ACTION BAR --}}
<div class="save-bar">
    <button type="submit" form="mainSettingsForm" class="btn-save shadow-sm">
        <i class="mdi mdi-content-save-outline me-1"></i> Simpan Perubahan
    </button>
</div>

{{-- MODAL INFO KLIK --}}
<div class="modal fade" id="infoTierModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold text-primary" id="infoModalTitle"><i class="mdi mdi-information text-primary"></i> Info Kasta Toko</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-2 text-dark" style="line-height: 1.6;" id="infoModalDesc">
                </div>
            <div class="modal-footer border-top-0 pt-0">
                <button type="button" class="btn btn-light fw-bold w-100 rounded-3" data-bs-dismiss="modal">Paham</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script untuk Modal Info (Bisa dipencet)
        const infoButtons = document.querySelectorAll('.btn-show-info');
        const modalTitle = document.getElementById('infoModalTitle');
        const modalDesc = document.getElementById('infoModalDesc');
        const infoModal = new bootstrap.Modal(document.getElementById('infoTierModal'));

        infoButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Ambil text dari data atribut
                const title = this.getAttribute('data-title');
                const desc = this.getAttribute('data-desc');
                
                // Masukkan ke dalam modal
                modalTitle.innerHTML = `<i class="mdi mdi-information-outline me-1"></i> Strategi ${title}`;
                modalDesc.innerHTML = desc;
                
                // Tampilkan Modal
                infoModal.show();
            });
        });
    });
</script>
@endpush