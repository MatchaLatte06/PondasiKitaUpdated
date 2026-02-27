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
    .user-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    .stat-mini-card {
        background: white;
        padding: 1.25rem;
        border-radius: 16px;
        border: 1px solid var(--border-color);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.2s;
    }
    .stat-mini-card:hover { border-color: var(--primary-indigo); transform: translateY(-2px); }
    .stat-icon-box {
        width: 48px; height: 48px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem;
    }

    /* TABLE CUSTOMIZATION */
    .main-card { background: white; border-radius: 20px; border: 1px solid var(--border-color); overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); }
    .table thead th {
        background: var(--soft-bg);
        text-transform: uppercase;
        font-size: 11px;
        letter-spacing: 0.5px;
        font-weight: 700;
        color: #64748b;
        padding: 1rem 1.5rem;
        border: none;
    }
    .table tbody td { padding: 1rem 1.5rem; vertical-align: middle; border-bottom: 1px solid var(--soft-bg); }
    
    /* USER PROFILE CELL */
    .user-avatar-stack { position: relative; width: 42px; height: 42px; }
    .user-avatar-stack .avatar-img { width: 100%; height: 100%; border-radius: 12px; object-fit: cover; }
    .status-indicator {
        position: absolute; bottom: -2px; right: -2px;
        width: 12px; height: 12px; border-radius: 50%;
        border: 2px solid white;
    }
    .indicator-online { background: #10b981; }
    .indicator-offline { background: #94a3b8; }

    /* BADGES */
    .badge-premium {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-admin { background: #fee2e2; color: #b91c1c; }
    .badge-seller { background: #fef3c7; color: #92400e; }
    .badge-customer { background: #dbeafe; color: #1e40af; }
    
    .status-pill {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-active { background: #d1fae5; color: #065f46; }
    .status-banned { background: #f1f5f9; color: #475569; text-decoration: line-through; }

    /* HOVER ACTIONS */
    .action-btn-group .btn {
        width: 34px; height: 34px; padding: 0;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<div class="dashboard-header mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold text-dark mb-1">User Directory</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengguna</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary border-dashed px-3">
                <i class="mdi mdi-download me-1"></i> Export CSV
            </button>
            <a href="#" class="btn btn-primary px-4 shadow-sm">
                <i class="mdi mdi-plus-thick me-1"></i> Add New User
            </a>
        </div>
    </div>
</div>

<div class="user-stats-grid">
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #eef2ff; color: #4f46e5;">
            <i class="mdi mdi-account-group"></i>
        </div>
        <div>
            <div class="text-muted small fw-bold">Total User</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['total']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #fff7ed; color: #ea580c;">
            <i class="mdi mdi-storefront"></i>
        </div>
        <div>
            <div class="text-muted small fw-bold">Penjual (Seller)</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['seller']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #f0fdf4; color: #16a34a;">
            <i class="mdi mdi-account-check"></i>
        </div>
        <div>
            <div class="text-muted small fw-bold">Customer Aktif</div>
            <div class="h4 fw-bold mb-0">{{ number_format($stats['customer']) }}</div>
        </div>
    </div>
    <div class="stat-mini-card">
        <div class="stat-icon-box" style="background: #fef2f2; color: #dc2626;">
            <i class="mdi mdi-account-off"></i>
        </div>
        <div>
            <div class="text-muted small fw-bold">Banned</div>
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
                       class="btn btn-sm border-0 {{ $level_filter == $lv ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-3">
                        {{ ucfirst($lv) }}
                    </a>
                @endforeach
            </div>

            <form action="{{ route('admin.users.index') }}" method="GET" class="position-relative" style="min-width: 320px;">
                <input type="hidden" name="level" value="{{ $level_filter }}">
                <i class="mdi mdi-magnify position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" name="search" class="form-control ps-5 rounded-pill bg-light border-0" 
                       placeholder="Search by name, email or ID..." value="{{ $search }}">
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th><input type="checkbox" class="form-check-input"></th>
                    <th>User Identification</th>
                    <th>Engagement Info</th>
                    <th>Account Role</th>
                    <th>Status</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td><input type="checkbox" class="form-check-input"></td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="user-avatar-stack">
                                <img src="{{ $user->profile_picture_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->nama).'&background=random' }}" class="avatar-img shadow-sm">
                                <span class="status-indicator {{ $user->status_online == 'online' ? 'indicator-online' : 'indicator-offline' }}"></span>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">{{ $user->nama }}</div>
                                <div class="text-muted" style="font-size: 12px;">ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }} | @ {{ $user->username }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="small fw-medium text-dark"><i class="mdi mdi-email-outline me-1"></i>{{ $user->email }}</span>
                            <span class="small text-muted"><i class="mdi mdi-phone-outline me-1"></i>{{ $user->no_telepon ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-premium badge-{{ $user->level }}">
                            <i class="mdi {{ $user->level == 'seller' ? 'mdi-store' : ($user->level == 'admin' ? 'mdi-shield-check' : 'mdi-account') }}"></i>
                            {{ strtoupper($user->level) }}
                        </span>
                    </td>
                    <td>
                        @if($user->is_banned)
                            <span class="status-pill status-banned">Restricted</span>
                        @else
                            <span class="status-pill status-active">Active</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="action-btn-group">
                            <a href="#" class="btn btn-light border text-info" title="View Profile">
                                <i class="mdi mdi-eye"></i>
                            </a>
                            <a href="#" class="btn btn-light border text-primary" title="Edit Data">
                                <i class="mdi mdi-pencil"></i>
                            </a>
                            @if ($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggleBan', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-light border {{ $user->is_banned ? 'text-success' : 'text-danger' }}" 
                                            onclick="return confirm('Change status for this user?')">
                                        <i class="mdi {{ $user->is_banned ? 'mdi-account-check' : 'mdi-account-off' }}"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <img src="{{ asset('assets/images/no-data.svg') }}" style="width: 120px;" class="mb-3 opacity-50">
                        <p class="text-muted fw-medium">No matching users found in our database.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-light py-3 px-4 d-flex justify-content-between align-items-center">
        <div class="text-muted small">
            Showing <b>{{ $users->firstItem() }}</b> to <b>{{ $users->lastItem() }}</b> of <b>{{ $users->total() }}</b> results
        </div>
        <div>
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection