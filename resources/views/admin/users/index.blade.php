@extends('layouts.admin')

@section('title', 'User Management Center')

@push('styles')
<style>
    :root {
        --primary-indigo: #4f46e5;
        --soft-bg: #f8fafc;
        --border-color: #e2e8f0;
    }

    /* QUICK STATS CARDS */
    .user-stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-mini-card { background: white; padding: 1.25rem; border-radius: 16px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem; transition: all 0.2s; }
    .stat-mini-card:hover { border-color: var(--primary-indigo); transform: translateY(-2px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.05);}
    .stat-icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }

    /* TABLE CUSTOMIZATION */
    .main-card { background: white; border-radius: 20px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .table thead th { background: var(--soft-bg); text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; font-weight: 800; color: #64748b; padding: 1.2rem 1.5rem; border: none; }
    .table tbody td { padding: 1.2rem 1.5rem; vertical-align: middle; border-bottom: 1px solid var(--soft-bg); }
    .table tbody tr:hover { background-color: #f8fafc; }
    
    /* USER PROFILE CELL */
    .user-avatar-stack { position: relative; width: 42px; height: 42px; flex-shrink: 0; }
    .user-avatar-stack .avatar-img { width: 100%; height: 100%; border-radius: 12px; object-fit: cover; }
    .status-indicator { position: absolute; bottom: -2px; right: -2px; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; }
    .indicator-online { background: #10b981; }
    .indicator-offline { background: #94a3b8; }

    /* BADGES */
    .badge-premium { padding: 0.4rem 0.75rem; border-radius: 8px; font-weight: 700; font-size: 11px; display: inline-flex; align-items: center; gap: 4px; }
    .badge-admin { background: #fee2e2; color: #b91c1c; }
    .badge-seller { background: #fef3c7; color: #92400e; }
    .badge-customer { background: #dbeafe; color: #1e40af; }
    
    .status-pill { padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap:4px; }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-banned { background: #f1f5f9; color: #475569; text-decoration: line-through; }

    /* HOVER ACTIONS */
    .action-btn-group .btn { width: 34px; height: 34px; padding: 0; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: all 0.2s; }
    .action-btn-group .btn:hover { transform: translateY(-2px); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
</style>
@endpush

@section('content')

{{-- Menampilkan Error Validasi Form Modal --}}
@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong><i class="mdi mdi-alert-circle"></i> Proses Gagal:</strong>
    <ul class="mb-0 mt-1">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="dashboard-header mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">User Directory</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none text-muted">Dashboard</a></li>
                    <li class="breadcrumb-item active fw-bold text-primary">Kelola Pengguna</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.export', ['level' => $level_filter]) }}" class="btn btn-outline-secondary border-dashed px-3 fw-bold">
                <i class="mdi mdi-file-excel-outline me-1"></i> Export CSV
            </a>
            
            {{-- TOMBOL HANYA UNTUK SUPER ADMIN --}}
            @if(auth()->user()->admin_role === 'super')
                <button class="btn btn-primary px-4 shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#modalAddAdmin">
                    <i class="mdi mdi-shield-plus-outline me-1"></i> Admin Baru
                </button>
            @endif
        </div>
    </div>
</div>

<div class="user-stats-grid">
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #eef2ff; color: #4f46e5;"><i class="mdi mdi-account-group"></i></div>
        <div>
            <div class="text-muted small fw-bold text-uppercase">Total User</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #fff7ed; color: #ea580c;"><i class="mdi mdi-storefront"></i></div>
        <div>
            <div class="text-muted small fw-bold text-uppercase">Penjual (Seller)</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['seller']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #f0fdf4; color: #16a34a;"><i class="mdi mdi-account-check"></i></div>
        <div>
            <div class="text-muted small fw-bold text-uppercase">Customer Aktif</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['customer']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #fef2f2; color: #dc2626;"><i class="mdi mdi-account-off"></i></div>
        <div>
            <div class="text-muted small fw-bold text-uppercase">Akun Diblokir</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['banned']) }}</div>
        </div>
    </div>
</div>

<div class="main-card">
    <div class="card-header bg-white py-3 px-4 border-bottom-0">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="btn-group p-1 bg-light rounded-3">
                @foreach(['semua', 'admin', 'seller', 'customer'] as $lv)
                    <a href="{{ route('admin.users.index', ['level' => $lv, 'search' => $search]) }}" 
                       class="btn btn-sm border-0 {{ $level_filter == $lv ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-3" style="text-transform: capitalize;">
                        {{ $lv }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="position-relative" style="min-width: 320px;">
                <input type="hidden" name="level" value="{{ $level_filter }}">
                <i class="mdi mdi-magnify position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" name="search" class="form-control ps-5 rounded-pill bg-light border-0" 
                       placeholder="Cari Nama, Email, atau Username..." value="{{ $search }}">
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox" class="form-check-input"></th>
                    <th>Identitas Pengguna</th>
                    <th>Kontak & Info</th>
                    <th>Kasta Akun</th>
                    <th>Bergabung</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar-stack">
                                <img src="{{ $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=random&color=fff' }}" class="avatar-img shadow-sm">
                                <span class="status-indicator {{ $user->status_online == 'online' ? 'indicator-online' : 'indicator-offline' }}" title="{{ ucfirst($user->status_online) }}"></span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 14px;">{{ $user->nama }}</div>
                                <div class="text-muted" style="font-size: 12px;">ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }} <span class="mx-1">•</span> <span class="text-primary">@ {{ $user->username }}</span></div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column gap-1">
                            <span class="small fw-bold text-dark"><i class="mdi mdi-email-outline text-muted me-1"></i>{{ $user->email }}</span>
                            <span class="small text-muted"><i class="mdi mdi-phone-outline me-1"></i>{{ $user->no_telepon ?? 'Tidak ada nomor' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-premium badge-{{ $user->level }}">
                            <i class="mdi {{ $user->level == 'seller' ? 'mdi-storefront-outline' : ($user->level == 'admin' ? 'mdi-shield-crown-outline' : 'mdi-account-outline') }}"></i>
                            {{ strtoupper($user->level) }}
                            @if($user->level == 'admin' && $user->admin_role)
                                ({{ strtoupper($user->admin_role) }})
                            @endif
                        </span>
                    </td>
                    <td>
                        <span class="small text-dark fw-bold">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</span>
                    </td>
                    <td>
                        @if($user->is_banned)
                            <span class="status-pill status-banned"><i class="mdi mdi-cancel"></i> Diblokir</span>
                        @else
                            <span class="status-pill status-active"><i class="mdi mdi-check-decagram"></i> Aktif</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-btn-group">
                            <button type="button" class="btn btn-light border text-info btn-detail" 
                                    data-nama="{{ $user->nama }}" 
                                    data-username="{{ $user->username }}"
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->no_telepon ?? '-' }}"
                                    data-level="{{ strtoupper($user->level) }} {{ $user->level == 'admin' ? '('.strtoupper($user->admin_role).')' : '' }}"
                                    data-join="{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y, H:i') }}"
                                    data-img="{{ $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=random&color=fff' }}"
                                    data-bs-toggle="modal" data-bs-target="#modalDetailUser" data-bs-placement="top" title="Lihat Profil">
                                <i class="mdi mdi-eye"></i>
                            </button>
                            
                            <button type="button" class="btn btn-light border text-primary btn-edit" 
                                    data-url="{{ route('admin.users.update', $user->id) }}"
                                    data-nama="{{ $user->nama }}" 
                                    data-email="{{ $user->email }}"
                                    data-phone="{{ $user->no_telepon ?? '' }}"
                                    data-level="{{ $user->level }}"
                                    data-role="{{ $user->admin_role }}"
                                    data-bs-toggle="modal" data-bs-target="#modalEditUser" data-bs-placement="top" title="Edit Pengguna">
                                <i class="mdi mdi-pencil"></i>
                            </button>

                            @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggleBan', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light border {{ $user->is_banned ? 'text-success' : 'text-danger' }}" 
                                            onclick="return confirm('Apakah Anda yakin ingin mengubah status pemblokiran pengguna ini?')" 
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $user->is_banned ? 'Aktifkan Akun' : 'Blokir Akun' }}">
                                        <i class="mdi {{ $user->is_banned ? 'mdi-account-check-outline' : 'mdi-account-cancel-outline' }}"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="mdi mdi-account-search-outline text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        <p class="text-muted fw-bold mt-2 mb-0">Tidak ada pengguna yang cocok dengan filter.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-light py-3 px-4 border-top d-flex justify-content-between align-items-center">
        <div class="text-muted small fw-bold">
            Menampilkan <span class="text-primary">{{ $users->firstItem() ?? 0 }}</span> - <span class="text-primary">{{ $users->lastItem() ?? 0 }}</span> dari <span class="text-primary">{{ $users->total() }}</span> pengguna
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

{{-- MODAL TAMBAH ADMIN BARU --}}
<div class="modal fade" id="modalAddAdmin" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="mdi mdi-shield-account-outline text-primary me-2"></i> Tambah Administrator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" placeholder="Cth: Budiman Santoso" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted small">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Cth: budiman" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted small">Email Resmi</label>
                            <input type="email" name="email" class="form-control" placeholder="Cth: budiman@pondasikita.com" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Otoritas / Kasta Admin</label>
                        <select name="admin_role" class="form-select" required>
                            <option value="" disabled selected>-- Pilih Hak Akses --</option>
                            <option value="cs">Customer Service (Kelola Pengguna & Komplain)</option>
                            <option value="finance">Finance (Keuangan, Payout & Laporan)</option>
                            <option value="super">Super Admin (Akses Penuh Semua Sistem)</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Password Akun</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 Karakter" required minlength="6">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Buat Akun Admin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT PENGGUNA --}}
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-light border-bottom-0 pb-3">
                <h5 class="fw-bold text-dark mb-0"><i class="mdi mdi-account-edit-outline text-primary me-2"></i> Edit Data Pengguna</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="formEditUser" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small">Nama Lengkap</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted small">Email Resmi</label>
                            <input type="email" name="email" id="editEmail" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold text-muted small">No Telepon</label>
                            <input type="text" name="no_telepon" id="editPhone" class="form-control">
                        </div>
                    </div>
                    
                    {{-- FORM PILIHAN ROLE HANYA BISA DIAKSES OLEH SUPER ADMIN --}}
                    @if(auth()->user()->admin_role === 'super')
                    <div class="mb-3" id="editRoleContainer" style="display: none;">
                        <label class="form-label fw-bold text-muted small">Ubah Otoritas Admin</label>
                        <select name="admin_role" id="editRole" class="form-select">
                            <option value="cs">Customer Service</option>
                            <option value="finance">Finance</option>
                            <option value="super">Super Admin</option>
                        </select>
                        <div class="help-text text-warning mt-1" style="font-size: 11px;"><i class="mdi mdi-alert-circle"></i> Mengubah ini akan mencabut/menambah akses menu mereka.</div>
                    </div>
                    @endif

                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small">Reset Password (Opsional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak ingin mengubah sandi">
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL PENGGUNA --}}
<div class="modal fade" id="modalDetailUser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered border-0">
        <div class="modal-content border-0 rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 bg-light pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center pt-0 pb-4 bg-light">
                <img id="detImg" src="" class="rounded-circle shadow mb-3" style="width: 80px; height: 80px; object-fit: cover; border: 3px solid white;">
                <h4 class="fw-bold text-dark mb-0" id="detNama">Nama User</h4>
                <p class="text-muted mb-2">@<span id="detUsername">username</span></p>
                <span class="badge bg-primary px-3 py-2 rounded-pill" id="detLevel">ROLE</span>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Email Address</small>
                        <span class="fw-bold text-dark" id="detEmail">email@domain.com</span>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">No Telepon</small>
                        <span class="fw-bold text-dark" id="detPhone">08xxx</span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 10px;">Bergabung Sejak</small>
                        <span class="fw-bold text-dark" id="detJoin">1 Jan 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Aktifkan Bootstrap Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"], [title]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Logic Modal Detail User
        const detailButtons = document.querySelectorAll('.btn-detail');
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                document.getElementById('detImg').src = this.getAttribute('data-img');
                document.getElementById('detNama').innerText = this.getAttribute('data-nama');
                document.getElementById('detUsername').innerText = this.getAttribute('data-username');
                document.getElementById('detEmail').innerText = this.getAttribute('data-email');
                document.getElementById('detPhone').innerText = this.getAttribute('data-phone');
                document.getElementById('detLevel').innerText = this.getAttribute('data-level');
                document.getElementById('detJoin').innerText = this.getAttribute('data-join');
            });
        });

        // Logic Modal Edit User Dinamis
        const editButtons = document.querySelectorAll('.btn-edit');
        const formEdit = document.getElementById('formEditUser');
        const roleContainer = document.getElementById('editRoleContainer');
        const roleSelect = document.getElementById('editRole');

        editButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // 1. Tembak URL Action Form secara dinamis
                formEdit.action = this.getAttribute('data-url');
                
                // 2. Isi value inputan dari data-attribute tombol
                document.getElementById('editNama').value = this.getAttribute('data-nama');
                document.getElementById('editEmail').value = this.getAttribute('data-email');
                
                let phone = this.getAttribute('data-phone');
                document.getElementById('editPhone').value = (phone && phone !== '-') ? phone : '';

                // 3. Logika pintar untuk Otoritas Admin (Hanya Super Admin yang bisa lihat)
                let level = this.getAttribute('data-level');
                if(roleContainer) {
                    if (level === 'admin') {
                        roleContainer.style.display = 'block';
                        roleSelect.value = this.getAttribute('data-role');
                    } else {
                        roleContainer.style.display = 'none';
                    }
                }
            });
        });
    });
</script>
@endpush