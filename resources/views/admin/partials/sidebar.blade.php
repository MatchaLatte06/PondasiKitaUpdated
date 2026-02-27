<link rel="stylesheet" href="{{ asset('assets/css/sidebar_admin.css') }}">

<nav class="modern-sidebar" id="adminSidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="brand-wrapper" style="text-decoration: none;">
            <div class="brand-icon">P</div>
            <div class="brand-text">
                <span>Pondasikita</span>
                <small>ADMIN PANEL</small>
            </div>
        </a>
    </div>

    <div class="sidebar-body">
        
        <div class="admin-profile-card">
            <div class="admin-avatar">
                {{ strtoupper(substr(Auth::user()->nama ?? 'A', 0, 1)) }}
            </div>
            <div class="admin-info">
                <span class="name">{{ Auth::user()->nama ?? 'Administrator' }}</span>
                <span class="role">Super Admin</span>
            </div>
        </div>

        <ul class="nav-list">
            <li class="nav-header">UTAMA</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="mdi mdi-view-dashboard-outline nav-icon"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-header">MANAJEMEN</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <i class="mdi mdi-account-group-outline nav-icon"></i>
                    <span class="nav-text">Kelola Pengguna</span>
                </a>
            </li>

            @php 
                $isStoreActive = request()->routeIs('admin.stores.*') || request()->routeIs('admin.products.*'); 
            @endphp
            
            <li class="nav-item has-sub {{ $isStoreActive ? 'active' : '' }}">
                <a href="javascript:void(0)" class="nav-link dropdown-toggle" aria-expanded="{{ $isStoreActive ? 'true' : 'false' }}">
                    <i class="mdi mdi-store-outline nav-icon"></i>
                    <span class="nav-text">Toko & Produk</span>
                    <i class="mdi mdi-chevron-right chevron"></i>
                </a>
                <div class="sub-menu {{ $isStoreActive ? 'show' : '' }}">
                    <ul>
                        <li>
                            <a href="{{ route('admin.stores.index') }}" class="{{ request()->routeIs('admin.stores.*') ? 'active' : '' }}">
                                <span class="dot"></span> Kelola Toko
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.products.index') }}" class="{{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                <span class="dot"></span> Moderasi Produk
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-header">KEUANGAN & LAPORAN</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.payouts.index') }}" class="nav-link {{ request()->routeIs('admin.payouts.*') ? 'active' : '' }}">
                    <i class="mdi mdi-wallet-outline nav-icon"></i>
                    <span class="nav-text">Payout</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                    <i class="mdi mdi-file-chart-outline nav-icon"></i>
                    <span class="nav-text">Laporan</span>
                </a>
            </li>

            <li class="nav-header">SISTEM</li>
            
            <li class="nav-item">
                <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="mdi mdi-cog-outline nav-icon"></i>
                    <span class="nav-text">Pengaturan</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" id="form-logout-sidebar" style="width: 100%;">
            @csrf
            <a href="#" class="logout-link" onclick="event.preventDefault(); document.getElementById('form-logout-sidebar').submit();">
                <i class="mdi mdi-logout"></i>
                <span>Keluar</span>
            </a>
        </form>
    </div>
</nav>

<script src="{{ asset('assets/js/sidebar_admin.js') }}"></script>