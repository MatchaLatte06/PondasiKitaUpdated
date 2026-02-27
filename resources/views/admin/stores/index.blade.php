@extends('layouts.admin')

@section('title', 'Manajemen Mitra Toko')

@push('styles')
<style>
    /* UTILS */
    .bg-soft-orange { background: #fff7ed; color: #ea580c; }
    .bg-soft-green { background: #f0fdf4; color: #16a34a; }
    .bg-soft-red { background: #fef2f2; color: #dc2626; }
    .bg-soft-blue { background: #eff6ff; color: #2563eb; }

    /* STORE CARDS */
    .stat-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card-premium {
        background: white; border-radius: 20px; padding: 1.5rem;
        border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 1.25rem;
        transition: all 0.3s ease;
    }
    .stat-card-premium:hover { transform: translateY(-5px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }
    .icon-box-lg { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.75rem; }

    /* TABLE UI */
    .main-table-card { background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); }
    .store-identity { display: flex; align-items: center; gap: 12px; }
    .store-logo-box { width: 44px; height: 44px; border-radius: 12px; overflow: hidden; background: #f1f5f9; border: 1px solid #e2e8f0; display:flex; align-items:center; justify-content:center; }
    .store-logo-box img { width: 100%; height: 100%; object-fit: cover; }
    
    .table thead th { background: #f8fafc; text-transform: uppercase; font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px; padding: 1.25rem 1.5rem; border: none; }
    .table tbody td { padding: 1.25rem 1.5rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9; }

    /* BADGE STATUS */
    .badge-status { padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-active { background: #dcfce7; color: #15803d; }
    .badge-suspended { background: #fee2e2; color: #b91c1c; }

    /* ACTION BUTTONS */
    .btn-verify-group { display: flex; gap: 8px; }
    .btn-action-circle { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; transition: 0.2s; border: 1px solid #e2e8f0; background: white; color: #64748b; }
    .btn-action-circle:hover { background: #f1f5f9; color: var(--admin-primary); }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">Mitra Toko Bangunan</h2>
            <p class="text-muted small">Kelola pendaftaran, verifikasi berkas, dan status operasional merchant.</p>
        </div>
        <div class="date-badge">
            <i class="mdi mdi-store-cog-outline me-2"></i> Store Management
        </div>
    </div>
</div>

<div class="stat-grid">
    <div class="stat-card-premium">
        <div class="icon-box-lg bg-soft-blue"><i class="mdi mdi-store"></i></div>
        <div>
            <div class="text-muted small fw-bold">Total Mitra</div>
            <div class="h3 fw-bold mb-0">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="stat-card-premium">
        <div class="icon-box-lg bg-soft-orange"><i class="mdi mdi-progress-clock"></i></div>
        <div>
            <div class="text-muted small fw-bold">Perlu Verifikasi</div>
            <div class="h3 fw-bold mb-0 text-warning">{{ number_format($stats['pending']) }}</div>
        </div>
    </div>
    <div class="stat-card-premium">
        <div class="icon-box-lg bg-soft-green"><i class="mdi mdi-store-check"></i></div>
        <div>
            <div class="text-muted small fw-bold">Toko Aktif</div>
            <div class="h3 fw-bold mb-0 text-success">{{ number_format($stats['active']) }}</div>
        </div>
    </div>
    <div class="stat-card-premium">
        <div class="icon-box-lg bg-soft-red"><i class="mdi mdi-store-remove"></i></div>
        <div>
            <div class="text-muted small fw-bold">Ditangguhkan</div>
            <div class="h3 fw-bold mb-0 text-danger">{{ number_format($stats['suspended']) }}</div>
        </div>
    </div>
</div>

<div class="main-table-card">
    <div class="px-4 py-4 border-bottom bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
            {{-- Tabs Modern --}}
            <div class="btn-group p-1 bg-light rounded-3 shadow-inner" style="width: fit-content;">
                @foreach(['semua', 'pending', 'active', 'suspended'] as $st)
                    <a href="{{ route('admin.stores.index', ['status' => $st, 'search' => $search]) }}" 
                       class="btn btn-sm border-0 {{ $status_filter == $st ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-4">
                        {{ $st == 'pending' ? 'Perlu Verifikasi' : ucfirst($st) }}
                    </a>
                @endforeach
            </div>

            {{-- Search --}}
            <form action="{{ route('admin.stores.index') }}" method="GET" style="min-width: 300px;">
                <input type="hidden" name="status" value="{{ $status_filter }}">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="mdi mdi-magnify"></i></span>
                    <input type="text" name="search" class="form-control bg-light border-0" placeholder="Cari nama toko atau pemilik..." value="{{ $search }}">
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>Identitas Toko</th>
                    <th>Pemilik & Kontak</th>
                    <th>Lokasi (Kota)</th>
                    <th>Tgl Daftar</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($stores as $toko)
                <tr>
                    <td>
                        <div class="store-identity">
                            <div class="store-logo-box">
                                @if($toko->logo_toko)
                                    <img src="{{ asset('assets/uploads/toko/'.$toko->logo_toko) }}">
                                @else
                                    <i class="mdi mdi-store-outline text-muted fs-4"></i>
                                @endif
                            </div>
                            <div>
                                <span class="fw-bold text-dark d-block">{{ $toko->nama_toko }}</span>
                                <small class="text-muted">Slug: {{ $toko->slug }}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark">{{ $toko->nama_pemilik }}</div>
                        <div class="small text-muted">{{ $toko->email_pemilik }}</div>
                    </td>
                    <td><span class="text-dark"><i class="mdi mdi-map-marker-outline me-1"></i>{{ $toko->nama_kota ?? '-' }}</span></td>
                    <td class="small text-muted">{{ date('d M Y', strtotime($toko->created_at)) }}</td>
                    <td>
                        <span class="badge-status badge-{{ $toko->status }}">
                            <i class="mdi mdi-circle-medium"></i> {{ strtoupper($toko->status) }}
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            @if($toko->status == 'pending')
                                <form action="{{ route('admin.stores.verify', $toko->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="setujui">
                                    <button type="submit" class="btn btn-success btn-sm px-3 rounded-3 shadow-sm" onclick="return confirm('Aktifkan toko ini?')">Setujui</button>
                                </form>
                                <form action="{{ route('admin.stores.verify', $toko->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <input type="hidden" name="action" value="tolak">
                                    <button type="submit" class="btn btn-outline-danger btn-sm px-3 rounded-3" onclick="return confirm('Tolak pendaftaran ini?')">Tolak</button>
                                </form>
                            @else
                                <a href="#" class="btn-action-circle" title="Lihat Profil"><i class="mdi mdi-eye"></i></a>
                                <a href="#" class="btn-action-circle" title="Edit Data"><i class="mdi mdi-pencil-outline"></i></a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="py-4">
                            <i class="mdi mdi-store-remove-outline text-muted" style="font-size: 5rem;"></i>
                            <p class="text-muted fw-bold mt-3">Tidak ada toko dengan kriteria ini.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 py-3 bg-light d-flex justify-content-between align-items-center">
        <small class="text-muted">Menampilkan {{ $stores->count() }} toko dari total {{ $stores->total() }}</small>
        {{ $stores->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection