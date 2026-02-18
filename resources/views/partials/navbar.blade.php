<header class="navbar-fixed">
    <div class="navbar-container">
        <div class="navbar-left">
            {{-- Menggunakan route() atau url() --}}
            <a href="{{ url('/') }}" class="navbar-logo">
                <h3>Pondasikita</h3>
            </a>
            <form action="{{ route('search') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="query" placeholder="Cari produk, toko, atau merek...">
            </form>
        </div>

        <nav class="navbar-right">
            <ul class="nav-links js-nav-links">
                <li><a href="{{ route('produk.index') }}" class="nav-link">Produk</a></li>
                <li><a href="{{ route('toko.index') }}" class="nav-link">Toko</a></li>
            </ul>

            <div class="nav-actions">
                <a href="{{ route('keranjang.index') }}" class="action-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    {{-- Variabel $total_item_keranjang dikirim otomatis dari AppServiceProvider --}}
                    @if(isset($total_item_keranjang) && $total_item_keranjang > 0)
                        <span class="cart-badge">{{ $total_item_keranjang }}</span>
                    @endif
                </a>

                {{-- Cek Login menggunakan Auth Laravel --}}
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

                            @if(Auth::user()->level === 'admin')
                                <a href="{{ url('/app_admin/dashboard_mimin.php') }}">Admin Dashboard</a>
                                <a href="{{ url('/app_admin/kelola_toko.php') }}">Verifikasi Toko</a>
                            @elseif(Auth::user()->level === 'seller')
                                <a href="{{ url('/app_seller/dashboard.php') }}">Dashboard Toko</a>
                                <a href="{{ url('/app_seller/produk.php') }}">Produk Saya</a>
                                <a href="{{ url('/app_seller/pesanan.php') }}">Pesanan Masuk</a>
                            @else
                                {{-- Customer --}}
                                <a href="{{ route('profil.index') }}">Profil Saya</a>
                                <a href="{{ route('pesanan.index') }}">Pesanan Saya</a>
                            @endif
                            
                            {{-- Logout butuh Form POST di Laravel untuk keamanan --}}
                            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="logout-link" style="background:none; border:none; width:100%; text-align:left; cursor:pointer;">Keluar</button>
                            </form>
                        </div>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Daftar</a>
                @endguest
            </div>

            <div class="hamburger-menu js-hamburger">
                <i class="fas fa-bars"></i>
            </div>
        </nav>
    </div>
</header>

<script>
    // Script JS tetap sama, sebaiknya dipindah ke file .js terpisah
    document.addEventListener('DOMContentLoaded', function () {
        const profileBtn = document.querySelector('.profile-btn');
        const dropdown = document.querySelector('.js-dropdown-content');

        if (profileBtn && dropdown) {
            profileBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('show-dropdown');
            });

            document.addEventListener('click', function () {
                dropdown.classList.remove('show-dropdown');
            });
        }
    });
</script>