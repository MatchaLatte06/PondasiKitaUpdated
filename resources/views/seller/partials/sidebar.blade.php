<nav class="sidebar sidebar-offcanvas" id="sidebar">
    {{-- 1. BRAND LOGO --}}
    <div class="sidebar-brand-logo">
        <a class="brand-link" href="{{ route('home') }}" title="Lihat Tampilan Toko">Pondasikita</a>
        <span class="brand-subtext">Seller Center</span>
    </div>

    {{-- 2. USER PROFILE CARD --}}
    <div class="sidebar-profile">
        <div class="profile-card">
            <div class="profile-avatar">
                {{-- Ambil huruf depan nama --}}
                {{ strtoupper(substr(Auth::user()->nama ?? 'S', 0, 1)) }}
            </div>
            <div class="profile-info">
                <div class="profile-welcome">Selamat Datang,</div>
                <div class="profile-name" title="{{ Auth::user()->nama ?? 'Seller' }}">
                    {{ Str::limit(Auth::user()->nama ?? 'Seller', 15) }}
                </div>
            </div>
        </div>
    </div>

    {{-- 3. NAVIGATION MENU --}}
    <ul class="nav">
        {{-- DASHBOARD --}}
        <li class="nav-item {{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('seller.dashboard') }}">
                <i class="mdi mdi-view-dashboard menu-icon"></i>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        
        {{-- MANAJEMEN PENJUALAN (DROPDOWN) --}}
        <li class="nav-item-header">Manajemen Penjualan</li>
        
        {{-- Cek apakah salah satu child route aktif --}}
        @php $isOrderActive = request()->routeIs('seller.orders.*'); @endphp
        
        <li class="nav-item {{ $isOrderActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#pesanan-menu" 
               aria-expanded="{{ $isOrderActive ? 'true' : 'false' }}" 
               aria-controls="pesanan-menu">
                <i class="mdi mdi-receipt menu-icon"></i>
                <span class="menu-title">Pesanan</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isOrderActive ? 'show' : '' }}" id="pesanan-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.orders.index') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.orders.index') }}">Pesanan Saya</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.orders.return') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.orders.return') }}">Pengembalian</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.orders.shipping') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.orders.shipping') }}">Pengaturan Pengiriman</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- PRODUK (DROPDOWN) --}}
        <li class="nav-item-header">Produk</li>
        @php $isProductActive = request()->routeIs('seller.products.*'); @endphp

        <li class="nav-item {{ $isProductActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#produk-menu" 
               aria-expanded="{{ $isProductActive ? 'true' : 'false' }}" 
               aria-controls="produk-menu">
                <i class="mdi mdi-cube-unfolded menu-icon"></i>
                <span class="menu-title">Produk</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isProductActive ? 'show' : '' }}" id="produk-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.products.index') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.products.index') }}">Produk Saya</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.products.create') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.products.create') }}">Tambah Produk</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- PUSAT PROMOSI --}}
        <li class="nav-item-header">Pusat Promosi</li>
        @php $isPromoActive = request()->routeIs('seller.promotion.*'); @endphp

        <li class="nav-item {{ $isPromoActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#promosi-menu" 
               aria-expanded="{{ $isPromoActive ? 'true' : 'false' }}" aria-controls="promosi-menu">
                <i class="mdi mdi-ticket-percent menu-icon"></i>
                <span class="menu-title">Pusat Promosi</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isPromoActive ? 'show' : '' }}" id="promosi-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.promotion.discounts') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.promotion.discounts') }}">Diskon Produk</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.promotion.vouchers') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.promotion.vouchers') }}">Voucher Toko</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- LAYANAN PEMBELI --}}
        <li class="nav-item-header">Layanan Pembeli</li>
        @php $isServiceActive = request()->routeIs('seller.service.*'); @endphp

        <li class="nav-item {{ $isServiceActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#layanan-pembeli-menu" 
               aria-expanded="{{ $isServiceActive ? 'true' : 'false' }}" aria-controls="layanan-pembeli-menu">
                <i class="mdi mdi-headset-mic menu-icon"></i>
                <span class="menu-title">Layanan Pembeli</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isServiceActive ? 'show' : '' }}" id="layanan-pembeli-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.service.chat') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.service.chat') }}">Manajemen Chat</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.service.reviews') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.service.reviews') }}">Penilaian Toko</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- KEUANGAN --}}
        <li class="nav-item-header">Keuangan</li>
        @php $isFinanceActive = request()->routeIs('seller.finance.*'); @endphp

        <li class="nav-item {{ $isFinanceActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#keuangan-menu" 
               aria-expanded="{{ $isFinanceActive ? 'true' : 'false' }}" aria-controls="keuangan-menu">
                <i class="mdi mdi-currency-usd menu-icon"></i>
                <span class="menu-title">Keuangan</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isFinanceActive ? 'show' : '' }}" id="keuangan-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.finance.income') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.finance.income') }}">Penghasilan Toko</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.finance.bank') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.finance.bank') }}">Rekening Bank</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- DATA --}}
        <li class="nav-item-header">Data</li>
        @php $isDataActive = request()->routeIs('seller.data.*'); @endphp

        <li class="nav-item {{ $isDataActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#data-menu" 
               aria-expanded="{{ $isDataActive ? 'true' : 'false' }}" aria-controls="data-menu">
                <i class="mdi mdi-chart-bar menu-icon"></i>
                <span class="menu-title">Data</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isDataActive ? 'show' : '' }}" id="data-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.data.performance') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.data.performance') }}">Performa Toko</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.data.health') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.data.health') }}">Kesehatan Toko</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- TOKO --}}
        <li class="nav-item-header">Toko</li>
        @php $isShopActive = request()->routeIs('seller.shop.*'); @endphp

        <li class="nav-item {{ $isShopActive ? 'active' : '' }}">
            <a class="nav-link" data-bs-toggle="collapse" href="#toko-menu" 
               aria-expanded="{{ $isShopActive ? 'true' : 'false' }}" aria-controls="toko-menu">
                <i class="mdi mdi-store menu-icon"></i>
                <span class="menu-title">Toko</span>
                <i class="mdi mdi-chevron-right menu-arrow"></i>
            </a>
            <div class="collapse {{ $isShopActive ? 'show' : '' }}" id="toko-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item {{ request()->routeIs('seller.shop.profile') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.shop.profile') }}">Profil Toko</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.shop.decoration') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.shop.decoration') }}">Dekorasi Toko</a>
                    </li>
                    <li class="nav-item {{ request()->routeIs('seller.shop.settings') ? 'active' : '' }}"> 
                        <a class="nav-link" href="{{ route('seller.shop.settings') }}">Pengaturan Toko</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>

    {{-- 5. FOOTER (LOGOUT) --}}
    <div class="sidebar-footer">
        <ul class="nav">
            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST" id="sidebar-logout-form">
                    @csrf
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                        <i class="mdi mdi-logout menu-icon"></i>
                        <span class="menu-title">Keluar</span>
                    </a>
                </form>
            </li>
        </ul>
    </div>
</nav>