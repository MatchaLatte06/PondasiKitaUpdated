<header class="navbar-fixed">
    <div class="navbar-container">
        <div class="navbar-left">
            <a href="{{ url('/') }}" class="navbar-logo">
                <h3>Pondasikita</h3>
            </a>
            <form action="{{ url('pages/search') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="query" placeholder="Cari produk, toko, atau merek...">
            </form>
        </div>

        <nav class="navbar-right">
            <ul class="nav-links js-nav-links">
                <li><a href="{{ url('pages/produk') }}" class="nav-link">Produk</a></li>
                <li><a href="{{ url('pages/semua_toko') }}" class="nav-link">Toko</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ url('pages/keranjang') }}" class="action-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    {{-- Variabel $total_item_keranjang ini otomatis dikirim dari AppServiceProvider --}}
                    @if ($total_item_keranjang > 0)
                        <span class="cart-badge">{{ $total_item_keranjang }}</span>
                    @endif
                </a>

                @auth
                    <div class="dropdown js-dropdown">
                        <button class="action-btn profile-btn">
                            <i class="fas fa-user"></i>
                        </button>
                        <div class="dropdown-content js-dropdown-content">
                            <div class="dropdown-header">
                                Halo, <strong>{{ Auth::user()->nama }}</strong>
                                <small>{{ ucfirst(Auth::user()->level) }}</small>
                            </div>

                            @if (Auth::user()->level === 'admin')
                                <a href="{{ url('admin/dashboard') }}">Admin Dashboard</a>
                            @elseif (Auth::user()->level === 'seller')
                                <a href="{{ url('seller/dashboard') }}">Dashboard Toko</a>
                            @else
                                <a href="{{ url('customer/profil') }}">Profil Saya</a>
                                <a href="{{ url('customer/pesanan') }}">Pesanan Saya</a>
                            @endif
                            
                            {{-- Logout Form --}}
                            <form action="{{ url('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="logout-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer; padding: 10px 15px;">Keluar</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ url('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ url('register') }}" class="btn btn-primary">Daftar</a>
                @endauth
            </div>

            <div class="hamburger-menu js-hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>