@extends('layouts.seller')

@section('title', 'Manajemen Voucher Toko')

@section('content')
<style>
    /* CSS ISOLATED UNTUK VOUCHER ENTERPRISE */
    :root {
        --vch-dark: #0f172a;
        --vch-primary: #2563eb;
        --vch-warning: #f59e0b;
        --vch-success: #10b981;
        --vch-danger: #ef4444;
        --vch-border: #e2e8f0;
        --vch-bg: #f8fafc;
        --text-mut: #64748b;
    }
    .vch-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* HEADER & STATS */
    .vch-header-row { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 24px; }
    .vch-title-box { display: flex; align-items: center; gap: 15px; }
    .vch-icon { background: var(--vch-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    .stats-card { background: white; border: 1px solid var(--vch-border); padding: 16px 24px; border-radius: 12px; display: flex; gap: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .stat-item h6 { font-size: 12px; font-weight: 700; color: var(--text-mut); text-transform: uppercase; margin-bottom: 4px; }
    .stat-item h3 { font-size: 24px; font-weight: 800; color: var(--vch-primary); margin: 0; }

    /* TABS & TOOLBAR */
    .vch-nav-tabs { display: flex; gap: 8px; border-bottom: 2px solid var(--vch-border); margin-bottom: 24px; overflow-x: auto; scrollbar-width: none; }
    .vch-nav-tabs::-webkit-scrollbar { display: none; }
    .vch-tab { padding: 12px 20px; font-weight: 700; color: var(--text-mut); text-decoration: none; font-size: 14px; border-bottom: 3px solid transparent; white-space: nowrap; transition: 0.2s; }
    .vch-tab:hover { color: var(--vch-dark); }
    .vch-tab.active { color: var(--vch-primary); border-bottom-color: var(--vch-primary); }

    .vch-toolbar { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
    .search-box { position: relative; flex-grow: 1; max-width: 400px; }
    .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem; }
    .search-box input { width: 100%; padding: 12px 16px 12px 45px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 500; font-size: 14px; }
    .search-box input:focus { border-color: var(--vch-primary); outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    
    .btn-create { background: var(--vch-primary); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2); cursor: pointer;}
    .btn-create:hover { background: #1d4ed8; transform: translateY(-2px); }

    /* TABLE VOUCHER (HIGH DENSITY) */
    .vch-card { background: white; border: 1px solid var(--vch-border); border-radius: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow-x: auto; }
    .table-vch { width: 100%; min-width: 900px; border-collapse: collapse; }
    .table-vch th { background: var(--vch-bg); color: #334155; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 16px 20px; border-bottom: 2px solid var(--vch-border); text-align: left; }
    .table-vch td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-vch tr:hover td { background-color: #f8fafc; }

    .vch-code-box { background: #fffbeb; border: 1px dashed #f59e0b; color: #b45309; padding: 4px 10px; border-radius: 6px; font-family: monospace; font-weight: 800; font-size: 14px; display: inline-block; margin-bottom: 6px; }
    .vch-desc { font-size: 13px; color: var(--vch-dark); font-weight: 600; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    
    .val-main { font-size: 16px; font-weight: 800; color: var(--vch-primary); }
    .val-sub { font-size: 11px; font-weight: 700; color: var(--text-mut); margin-top: 4px; text-transform: uppercase; }
    
    /* Progress Bar Kuota */
    .progress-container { width: 100%; background: #e2e8f0; border-radius: 10px; height: 8px; margin-bottom: 6px; overflow: hidden; }
    .progress-bar { height: 100%; border-radius: 10px; transition: 0.4s; }
    .quota-text { font-size: 11px; font-weight: 700; color: var(--text-mut); display: flex; justify-content: space-between; }

    /* Badges & Actions */
    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; }
    .s-aktif { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .s-nonaktif { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .s-habis { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }

    .action-group { display: flex; gap: 12px; align-items: center; justify-content: flex-end; }
    .btn-del { color: var(--vch-danger); font-size: 1.3rem; cursor: pointer; background: none; border: none; transition: 0.2s; }
    .btn-del:hover { transform: scale(1.1); }

    /* Custom iOS Toggle */
    .ios-switch { position: relative; display: inline-block; width: 44px; height: 24px; margin: 0; }
    .ios-switch input { opacity: 0; width: 0; height: 0; }
    .ios-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .ios-slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .ios-switch input:checked + .ios-slider { background-color: var(--vch-success); }
    .ios-switch input:checked + .ios-slider:before { transform: translateX(20px); }

    /* MODAL FORM CSS - EKSTRA LEBAR */
    /* Menimpa kelas bawaan Bootstrap agar lebih lebar di layar besar */
    @media (min-width: 992px) {
        .modal-xl-custom {
            max-width: 1000px !important; /* Memperlebar modal dari default 800px */
        }
    }
    .modal-content-custom { border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header-custom { background: var(--vch-bg); border-bottom: 1px solid var(--vch-border); border-radius: 16px 16px 0 0; padding: 20px 24px; }
    
    /* Layout Form dalam Modal */
    .modal-form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    @media (max-width: 768px) {
        .modal-form-grid { grid-template-columns: 1fr; }
    }

    .fm-label { font-weight: 700; color: #475569; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: block; }
    .fm-input { border-radius: 10px; border: 2px solid var(--vch-border); padding: 12px 16px; font-weight: 600; font-size: 14px; width: 100%; transition: 0.2s; outline: none; }
    .fm-input:focus { border-color: var(--vch-primary); }
    
    .fm-input-group { display: flex; border: 2px solid var(--vch-border); border-radius: 10px; overflow: hidden; transition: 0.2s; }
    .fm-input-group:focus-within { border-color: var(--vch-primary); }
    .fm-input-group span { background: #f1f5f9; padding: 12px 16px; font-weight: 800; color: #475569; border-right: 1px solid var(--vch-border); }
    .fm-input-group input { border: none; padding: 12px 16px; font-weight: 600; width: 100%; outline: none; }
</style>

<div class="vch-wrapper">

    {{-- Notifikasi SweetAlert --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Gagal!', '{{ session('error') }}', 'error'));</script>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 fw-bold">
            <i class="mdi mdi-alert-circle me-2"></i> Form tidak valid. Cek kembali isian Anda.
        </div>
    @endif

    {{-- 1. HEADER & STATS --}}
    <div class="vch-header-row">
        <div class="vch-title-box">
            <div class="vch-icon"><i class="mdi mdi-ticket-percent-outline"></i></div>
            <div>
                <h3 class="m-0 fw-bold fs-4">Manajemen Voucher</h3>
                <p class="m-0 text-muted" style="font-size: 13px;">Buat kode kupon potongan harga khusus untuk pelanggan setia Anda.</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stat-item border-end pe-4">
                <h6>Voucher Aktif</h6>
                <h3>{{ $stats['aktif'] }}</h3>
            </div>
            <div class="stat-item">
                <h6>Total Diklaim</h6>
                <h3>{{ $stats['terpakai'] }}</h3>
            </div>
        </div>
    </div>

    {{-- 2. TABS & TOOLBAR --}}
    <div class="vch-nav-tabs">
        <a href="?tab=semua" class="vch-tab {{ $currentTab == 'semua' ? 'active' : '' }}">Semua Voucher</a>
        <a href="?tab=aktif" class="vch-tab {{ $currentTab == 'aktif' ? 'active' : '' }}">Sedang Berjalan</a>
        <a href="?tab=habis" class="vch-tab {{ $currentTab == 'habis' ? 'active' : '' }}">Kuota Habis</a>
        <a href="?tab=nonaktif" class="vch-tab {{ $currentTab == 'nonaktif' ? 'active' : '' }}">Berakhir / Nonaktif</a>
    </div>

    <div class="vch-toolbar">
        <form action="{{ route('seller.promotion.vouchers') }}" method="GET" class="search-box m-0">
            <input type="hidden" name="tab" value="{{ $currentTab }}">
            <i class="mdi mdi-magnify"></i>
            <input type="text" name="search" placeholder="Cari Kode atau Nama Voucher..." value="{{ request('search') }}">
        </form>
        <button type="button" class="btn-create" data-bs-toggle="modal" data-bs-target="#modalAddVoucher">
            <i class="mdi mdi-plus-thick fs-5"></i> Buat Voucher Baru
        </button>
    </div>

    {{-- 3. TABEL VOUCHER --}}
    <div class="vch-card">
        <table class="table-vch">
            <thead>
                <tr>
                    <th width="25%">Kode & Rincian</th>
                    <th width="20%">Skema Diskon</th>
                    <th width="20%">Syarat Penggunaan</th>
                    <th width="15%">Pemakaian Kuota</th>
                    <th width="10%" class="text-center">Status</th>
                    <th width="10%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($voucher_list as $vch)
                    @php
                        // Logika Status
                        $isActive = $vch->status == 'AKTIF' && strtotime($vch->tanggal_berakhir) >= time();
                        $isHabis = $vch->kuota_terpakai >= $vch->kuota;
                        $badgeClass = 's-aktif'; $statusText = 'Aktif';
                        
                        if($isHabis) { $badgeClass = 's-habis'; $statusText = 'Habis'; $isActive = false; }
                        elseif(!$isActive || $vch->status == 'TIDAK_AKTIF') { $badgeClass = 's-nonaktif'; $statusText = 'Nonaktif'; }

                        // Kalkulasi Progress Kuota
                        $progress = ($vch->kuota_terpakai / $vch->kuota) * 100;
                        $progColor = $progress >= 80 ? '#ef4444' : ($progress >= 50 ? '#f59e0b' : '#2563eb');
                    @endphp
                    <tr>
                        {{-- Info & Kode --}}
                        <td>
                            <div class="vch-code-box"><i class="mdi mdi-content-copy me-1" style="cursor:pointer" title="Copy"></i> {{ $vch->kode_voucher }}</div>
                            <div class="vch-desc" title="{{ $vch->deskripsi }}">{{ $vch->deskripsi }}</div>
                            <div class="text-muted mt-2" style="font-size: 11px;">
                                <i class="mdi mdi-clock-outline"></i> {{ date('d M', strtotime($vch->tanggal_mulai)) }} - {{ date('d M Y', strtotime($vch->tanggal_berakhir)) }}
                            </div>
                        </td>

                        {{-- Skema Diskon --}}
                        <td>
                            @if($vch->tipe_diskon == 'PERSEN')
                                <div class="val-main">Diskon {{ $vch->nilai_diskon }}%</div>
                                <div class="val-sub">S/D Rp {{ number_format($vch->maks_diskon, 0, ',', '.') }}</div>
                            @else
                                <div class="val-main">Rp {{ number_format($vch->nilai_diskon, 0, ',', '.') }}</div>
                                <div class="val-sub">Potongan Langsung</div>
                            @endif
                        </td>

                        {{-- Syarat --}}
                        <td>
                            <div style="font-size: 13px; font-weight: 700; color: var(--vch-dark);">Min. Belanja:</div>
                            <div style="font-size: 14px; font-weight: 600; color: #475569;">Rp {{ number_format($vch->min_pembelian, 0, ',', '.') }}</div>
                        </td>

                        {{-- Kuota Tracker --}}
                        <td>
                            <div class="progress-container">
                                <div class="progress-bar" style="width: {{ $progress }}%; background-color: {{ $progColor }};"></div>
                            </div>
                            <div class="quota-text">
                                <span>Terpakai: {{ $vch->kuota_terpakai }}</span>
                                <span>Total: {{ $vch->kuota }}</span>
                            </div>
                        </td>

                        {{-- Status Badge --}}
                        <td class="text-center">
                            <span class="status-badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </td>

                        {{-- Aksi (Toggle & Hapus) --}}
                        <td>
                            <div class="action-group">
                                {{-- Hanya bisa ditoggle jika belum habis dan belum expired jauh --}}
                                @if(!$isHabis)
                                    <label class="ios-switch" title="{{ $vch->status == 'AKTIF' ? 'Matikan Voucher' : 'Aktifkan Voucher' }}">
                                        <input type="checkbox" class="toggle-status" data-id="{{ $vch->id }}" {{ $vch->status == 'AKTIF' ? 'checked' : '' }}>
                                        <span class="ios-slider"></span>
                                    </label>
                                @endif

                                <form action="{{ route('seller.promotion.vouchers.destroy', $vch->id) }}" method="POST" class="m-0 form-delete">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-del btn-delete-confirm" title="Hapus Permanen"><i class="mdi mdi-trash-can-outline"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="mdi mdi-ticket-outline d-block mb-2" style="font-size: 4rem; color: #cbd5e1;"></i>
                            <h5 class="fw-bold text-dark">Data Voucher Kosong</h5>
                            <p class="text-muted">Tidak ada data yang cocok dengan kriteria filter Anda.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $voucher_list->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL BUAT VOUCHER BARU (DIPERLEBAR) --}}
<div class="modal fade" id="modalAddVoucher" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    {{-- MENGGUNAKAN CLASS CUSTOM UNTUK MODAL EKSTRA LEBAR --}}
    <div class="modal-dialog modal-dialog-centered modal-xl-custom">
        <div class="modal-content modal-content-custom">
            <form action="{{ route('seller.promotion.vouchers.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title fw-bold" style="color: #0f172a;"><i class="mdi mdi-ticket-percent me-2 text-primary"></i> Buat Voucher Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4" style="max-height: 75vh; overflow-y: auto;">
                    
                    {{-- KITA BAGI MENJADI DUA KOLOM BESAR --}}
                    <div class="modal-form-grid">
                        
                        {{-- KOLOM KIRI: INFO DASAR & PERIODE --}}
                        <div class="form-col-left">
                            <div class="bg-light p-4 rounded-4 border mb-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="mdi mdi-information text-primary"></i> Informasi Dasar Voucher</h6>
                                
                                <div class="mb-3">
                                    <label class="fm-label">Kode Voucher <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_voucher" class="fm-input" placeholder="Cth: TOKOHEMAT99" maxlength="12" style="text-transform: uppercase;" required>
                                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">Maksimal 12 Karakter (Tanpa Spasi). Ini adalah kode yang akan diketik pembeli.</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="fm-label">Nama / Deskripsi Promo <span class="text-danger">*</span></label>
                                    <input type="text" name="deskripsi" class="fm-input" placeholder="Cth: Diskon Akhir Tahun Khusus Kontraktor" required>
                                </div>
                                
                                <div>
                                    <label class="fm-label">Total Kuota (Pcs) <span class="text-danger">*</span></label>
                                    <input type="number" name="kuota" class="fm-input" placeholder="Cth: 100" min="1" required>
                                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">Berapa banyak pembeli yang bisa mengklaim voucher ini.</small>
                                </div>
                            </div>

                            <div class="p-4 rounded-4 border border-info" style="background: #eff6ff;">
                                <h6 class="fw-bold text-dark mb-3"><i class="mdi mdi-calendar-clock text-info"></i> Periode Masa Berlaku</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="fm-label">Waktu Mulai <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="tanggal_mulai" class="fm-input border-info" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fm-label">Waktu Berakhir <span class="text-danger">*</span></label>
                                        <input type="datetime-local" name="tanggal_berakhir" class="fm-input border-info" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN: PENGATURAN DISKON & SYARAT --}}
                        <div class="form-col-right">
                            <div class="p-4 rounded-4 border" style="background: #fffbeb; border-color: #fde68a !important; height: 100%;">
                                <h6 class="fw-bold text-dark mb-3"><i class="mdi mdi-calculator text-warning"></i> Skema Diskon & Syarat</h6>
                                
                                <div class="mb-4">
                                    <label class="fm-label">Tipe Diskon <span class="text-danger">*</span></label>
                                    <select name="tipe_diskon" id="tipe_diskon" class="fm-input border-warning" style="background: white;" required>
                                        <option value="RUPIAH">Potongan Nominal (Rp)</option>
                                        <option value="PERSEN">Potongan Persentase (%)</option>
                                    </select>
                                </div>
                                
                                <div class="mb-4">
                                    <label class="fm-label">Nilai Diskon <span class="text-danger">*</span></label>
                                    <div class="fm-input-group" style="border-color: #f59e0b;">
                                        <span id="symbol_diskon" style="background: #fef3c7; border-color: #fde68a;">Rp</span>
                                        <input type="number" name="nilai_diskon" id="nilai_diskon" placeholder="Cth: 50000" min="1" required>
                                    </div>
                                </div>
                                
                                {{-- Field Maksimal Diskon (Hanya Muncul Jika Persen) --}}
                                <div class="mb-4" id="box_maks_diskon" style="display: none;">
                                    <label class="fm-label text-danger">Maksimal Potongan (Batas Kerugian) <span class="text-danger">*</span></label>
                                    <div class="fm-input-group" style="border-color: #ef4444;">
                                        <span style="background: #fef2f2; color: #ef4444; border-color: #fecaca;">Rp</span>
                                        <input type="number" name="maks_diskon" id="maks_diskon" placeholder="Cth: 50000">
                                    </div>
                                    <small class="text-danger fw-bold mt-1 d-block" style="font-size: 11px;">Wajib diisi agar toko tidak rugi jika pembeli memborong barang.</small>
                                </div>

                                <hr style="border-color: #fcd34d;">

                                <div>
                                    <label class="fm-label">Minimal Belanja (Rp) <span class="text-danger">*</span></label>
                                    <div class="fm-input-group" style="border-color: #cbd5e1;">
                                        <span style="background: white;">Rp</span>
                                        <input type="number" name="min_pembelian" placeholder="Syarat nilai pesanan (Cth: 100000)" min="0" required>
                                    </div>
                                    <small class="text-muted mt-1 d-block" style="font-size: 11px;">Pembeli harus belanja minimal nominal ini agar kode voucher bisa dipakai.</small>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
                
                <div class="modal-footer p-4 bg-white border-top rounded-bottom-4 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary fw-bold px-4 py-2 rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-create"><i class="mdi mdi-check-circle-outline"></i> Terbitkan Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. DYNAMIC FORM LOGIC (Persen vs Rupiah) ---
    const tipeDiskonSelect = document.getElementById('tipe_diskon');
    const symbolDiskon = document.getElementById('symbol_diskon');
    const inputNilaiDiskon = document.getElementById('nilai_diskon');
    const boxMaksDiskon = document.getElementById('box_maks_diskon');
    const inputMaksDiskon = document.getElementById('maks_diskon');

    tipeDiskonSelect.addEventListener('change', function() {
        if (this.value === 'PERSEN') {
            symbolDiskon.textContent = '%';
            inputNilaiDiskon.max = "100"; 
            inputNilaiDiskon.placeholder = "Cth: 10";
            
            boxMaksDiskon.style.display = 'block';
            inputMaksDiskon.setAttribute('required', 'required');
        } else {
            symbolDiskon.textContent = 'Rp';
            inputNilaiDiskon.removeAttribute('max');
            inputNilaiDiskon.placeholder = "Cth: 50000";
            
            boxMaksDiskon.style.display = 'none';
            inputMaksDiskon.removeAttribute('required');
            inputMaksDiskon.value = ''; 
        }
    });

    // --- 2. AJAX LIVE TOGGLE STATUS (iOS Switch) ---
    document.querySelectorAll('.toggle-status').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let voucherId = this.dataset.id;
            let isActive = this.checked ? 1 : 0;
            let checkbox = this;
            
            fetch("{{ route('seller.promotion.vouchers.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ voucher_id: voucherId, is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status === 'success') {
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: isActive ? 'Voucher diaktifkan' : 'Voucher dinonaktifkan', 
                        showConfirmButton: false, timer: 1500
                    }).then(() => location.reload()); 
                } else {
                    throw new Error('Update failed');
                }
            })
            .catch(error => {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Gagal update status!', showConfirmButton: false, timer: 2000});
                checkbox.checked = !isActive; 
            });
        });
    });

    // --- 3. SWEETALERT HAPUS VOUCHER ---
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.form-delete');
            Swal.fire({
                title: 'Hapus Voucher?',
                text: "Voucher ini akan dihapus permanen. Pembeli yang sudah mengklaim mungkin tidak bisa menggunakannya lagi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

});
</script>
@endpush