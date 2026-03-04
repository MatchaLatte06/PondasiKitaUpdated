@extends('layouts.admin')

@section('title', 'Manajemen Mitra Toko')

@push('styles')
<style>
    :root {
        --tier-official: #8b5cf6; /* Purple */
        --tier-power: #10b981;    /* Emerald Green */
        --tier-regular: #64748b;  /* Slate Gray */
        --sm-border: #e2e8f0;
    }

    /* STATS HEADER */
    .tier-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px; }
    .t-card { background: white; border-radius: 16px; padding: 20px; border: 1px solid var(--sm-border); display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .t-icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; color: white; }
    .t-info h4 { margin: 0; font-size: 24px; font-weight: 800; color: #1e293b; }
    .t-info span { font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }

    /* BADGES DESIGNS */
    .badge-tier { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 800; letter-spacing: 0.5px; text-transform: uppercase; }
    .badge-tier i { font-size: 14px; }
    
    .tier-official { background: #ede9fe; color: var(--tier-official); border: 1px solid #ddd6fe; }
    .tier-power { background: #ecfdf5; color: var(--tier-power); border: 1px solid #a7f3d0; }
    .tier-regular { background: #f8fafc; color: var(--tier-regular); border: 1px solid var(--sm-border); }

    /* TABLE MODERN */
    .store-table-wrapper { background: white; border-radius: 20px; border: 1px solid var(--sm-border); overflow: hidden; }
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern th { background: #fcfcfd; padding: 16px 20px; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; border-bottom: 1px solid var(--sm-border); text-align: left; }
    .table-modern td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-modern tr:hover { background: #f8fafc; }

    .store-logo { width: 48px; height: 48px; border-radius: 10px; object-fit: cover; border: 1px solid var(--sm-border); background: #f8fafc; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #94a3b8; }
    
    /* MODAL RADIO BUTTONS */
    .tier-radio { display: none; }
    .tier-label { display: flex; align-items: center; gap: 16px; padding: 16px; border: 2px solid var(--sm-border); border-radius: 12px; cursor: pointer; transition: 0.2s; margin-bottom: 12px; background: white; }
    .tier-label:hover { border-color: #cbd5e1; background: #f8fafc; }
    .tier-radio:checked + .tier-label { border-color: var(--tier-official); background: #ede9fe; }
    .tier-radio:checked + .tier-label.power { border-color: var(--tier-power); background: #ecfdf5; }
    .tier-radio:checked + .tier-label.regular { border-color: var(--tier-regular); background: #f1f5f9; }
    
    .tier-label i { font-size: 28px; }
    .tier-label strong { display: block; font-size: 15px; color: #1e293b; margin-bottom: 2px; }
    .tier-label span { font-size: 12px; color: #64748b; }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4">
    <h2 class="fw-bold text-dark mb-1">Manajemen Mitra Toko</h2>
    <p class="text-muted small">Kelola kasta (tier) vendor untuk membedakan distributor resmi dan toko reguler.</p>
</div>

{{-- 1. STATS CARDS --}}
<div class="tier-stats">
    <div class="t-card">
        <div class="t-icon bg-dark"><i class="mdi mdi-storefront-outline"></i></div>
        <div class="t-info"><span>Total Mitra</span><h4>{{ number_format($stats['total']) }}</h4></div>
    </div>
    <div class="t-card" style="border-color: #ddd6fe;">
        <div class="t-icon" style="background: var(--tier-official);"><i class="mdi mdi-check-decagram"></i></div>
        <div class="t-info"><span class="text-purple">Official Store</span><h4 style="color: var(--tier-official);">{{ number_format($stats['official']) }}</h4></div>
    </div>
    <div class="t-card" style="border-color: #a7f3d0;">
        <div class="t-icon" style="background: var(--tier-power);"><i class="mdi mdi-lightning-bolt"></i></div>
        <div class="t-info"><span class="text-success">Power Merchant</span><h4 style="color: var(--tier-power);">{{ number_format($stats['power']) }}</h4></div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success fw-bold border-0 shadow-sm rounded-3 mb-4"><i class="mdi mdi-check-circle me-2"></i> {{ session('success') }}</div>
@endif

{{-- 2. TABEL MITRA --}}
<div class="store-table-wrapper shadow-sm">
    <div class="d-flex flex-wrap justify-content-between align-items-center p-4 border-bottom bg-white gap-3">
        <div class="btn-group p-1 bg-light rounded-3 shadow-sm">
            <a href="{{ route('admin.stores.index', ['tier' => 'semua']) }}" class="btn btn-sm border-0 {{ $tier_filter == 'semua' ? 'bg-white fw-bold shadow-sm' : 'text-muted' }} px-3">Semua</a>
            <a href="{{ route('admin.stores.index', ['tier' => 'official_store']) }}" class="btn btn-sm border-0 {{ $tier_filter == 'official_store' ? 'bg-white fw-bold shadow-sm text-purple' : 'text-muted' }} px-3"><i class="mdi mdi-check-decagram"></i> Official</a>
            <a href="{{ route('admin.stores.index', ['tier' => 'power_merchant']) }}" class="btn btn-sm border-0 {{ $tier_filter == 'power_merchant' ? 'bg-white fw-bold shadow-sm text-success' : 'text-muted' }} px-3"><i class="mdi mdi-lightning-bolt"></i> Power</a>
            <a href="{{ route('admin.stores.index', ['tier' => 'regular']) }}" class="btn btn-sm border-0 {{ $tier_filter == 'regular' ? 'bg-white fw-bold shadow-sm' : 'text-muted' }} px-3">Reguler</a>
        </div>

        <form action="{{ route('admin.stores.index') }}" method="GET">
            <input type="hidden" name="tier" value="{{ $tier_filter }}">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama toko / pemilik..." value="{{ $search }}">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Informasi Toko</th>
                    <th>Pemilik & Kontak</th>
                    <th>Kasta / Tier Saat Ini</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $s)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            @if($s->logo_toko)
                                <img src="{{ asset('storage/'.$s->logo_toko) }}" class="store-logo" alt="Logo">
                            @else
                                <div class="store-logo">{{ substr($s->nama_toko, 0, 1) }}</div>
                            @endif
                            <div>
                                <strong class="d-block text-dark fs-6">{{ $s->nama_toko }}</strong>
                                <span class="text-muted small"><i class="mdi mdi-map-marker-outline"></i> {{ $s->kota_kabupaten ?? 'Kota belum diatur' }}</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <strong class="d-block text-dark">{{ $s->nama_pemilik }}</strong>
                        <span class="text-muted small"><i class="mdi mdi-phone-outline"></i> {{ $s->telepon_pemilik ?? '-' }}</span>
                    </td>
                    <td>
                        @if($s->tier_toko == 'official_store')
                            <span class="badge-tier tier-official"><i class="mdi mdi-check-decagram"></i> Official Store</span>
                        @elseif($s->tier_toko == 'power_merchant')
                            <span class="badge-tier tier-power"><i class="mdi mdi-lightning-bolt"></i> Power Merchant</span>
                        @else
                            <span class="badge-tier tier-regular"><i class="mdi mdi-storefront-outline"></i> Regular</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-dark px-3 fw-bold rounded-3 btn-upgrade"
                            data-bs-toggle="modal" data-bs-target="#modalTier"
                            data-id="{{ $s->id }}"
                            data-nama="{{ $s->nama_toko }}"
                            data-tier="{{ $s->tier_toko }}">
                            <i class="mdi mdi-star-circle-outline me-1"></i> Ubah Kasta
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="mdi mdi-store-off-outline text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted fw-bold mt-2">Tidak ada data toko ditemukan.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3 bg-light border-top">
        {{ $stores->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- 3. MODAL UBAH TIER --}}
<div class="modal fade" id="modalTier" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form id="formTier" method="POST" action="">
                @csrf
                <div class="modal-header border-bottom bg-light rounded-top-4 p-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="mdi mdi-star-circle text-warning me-2"></i> Pengaturan Kasta Mitra</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Pilih tingkatan baru untuk toko <strong id="mdl-nama-toko" class="text-dark"></strong>. Perubahan ini akan memengaruhi badge toko di sisi pembeli.</p>

                    <input type="radio" name="tier_toko" value="official_store" id="t_official" class="tier-radio">
                    <label class="tier-label" for="t_official">
                        <i class="mdi mdi-check-decagram" style="color: var(--tier-official);"></i>
                        <div>
                            <strong>Official Store (Distributor Resmi)</strong>
                            <span>Dikhususkan untuk principal merk atau distributor besar berbadan hukum (PT/CV).</span>
                        </div>
                    </label>

                    <input type="radio" name="tier_toko" value="power_merchant" id="t_power" class="tier-radio">
                    <label class="tier-label power" for="t_power">
                        <i class="mdi mdi-lightning-bolt" style="color: var(--tier-power);"></i>
                        <div>
                            <strong>Power Merchant</strong>
                            <span>Toko dengan reputasi sangat baik, penjualan tinggi, dan pelayanan responsif.</span>
                        </div>
                    </label>

                    <input type="radio" name="tier_toko" value="regular" id="t_regular" class="tier-radio">
                    <label class="tier-label regular" for="t_regular">
                        <i class="mdi mdi-storefront-outline" style="color: var(--tier-regular);"></i>
                        <div>
                            <strong>Toko Reguler</strong>
                            <span>Tingkat standar untuk semua penjual baru yang mendaftar di platform.</span>
                        </div>
                    </label>
                </div>
                <div class="modal-footer border-top p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-dark px-4 fw-bold">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.btn-upgrade').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const nama = this.getAttribute('data-nama');
                const currentTier = this.getAttribute('data-tier');
                
                // Set nama toko di modal
                document.getElementById('mdl-nama-toko').innerText = nama;
                
                // Set form action
                document.getElementById('formTier').action = `/portal-rahasia-pks/stores/${id}/tier`;

                // Centang radio button sesuai tier saat ini
                if(currentTier === 'official_store') document.getElementById('t_official').checked = true;
                else if(currentTier === 'power_merchant') document.getElementById('t_power').checked = true;
                else document.getElementById('t_regular').checked = true;
            });
        });
    });
</script>
@endpush