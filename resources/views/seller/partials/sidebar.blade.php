<aside class="sidebar">
    <div class="sidebar-brand-logo">
        <a href="{{ route('home') }}" class="brand-link">PONDASIKITA</a>
        <span class="brand-subtext">Seller Center</span>
    </div>

    <div class="sidebar-profile">
        <div class="profile-card">
            <div class="profile-avatar">
                {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
            </div>
            <div class="profile-info">
                <p class="profile-welcome">Selamat datang,</p>
                <p class="profile-name">{{ Auth::user()->nama }}</p>
            </div>
        </div>
    </div>

    <ul class="nav">
        <li class="nav-item-header">Menu Utama</li>
        <li class="nav-item {{ Request::is('seller/dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('seller.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>

        <li class="nav-item-header">Manajemen Produk</li>
        <li class="nav-item {{ Request::is('seller/products*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('seller.products.index') }}">
                <i class="mdi mdi-archive menu-icon"></i>
                <span class="menu-title">Produk Saya</span>
            </a>
        </li>

        <li class="nav-item-header">Manajemen Penjualan</li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <i class="mdi mdi-cart menu-icon"></i>
                <span class="menu-title">Pesanan</span>
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="nav-link" style="border:none; cursor:pointer;">
                <i class="mdi mdi-logout menu-icon"></i>
                <span class="menu-title">Keluar Akun</span>
            </button>
        </form>
    </div>
</aside>