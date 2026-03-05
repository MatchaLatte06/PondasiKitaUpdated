<header class="navbar-fixed">
    <div class="navbar-container">
        
        {{-- BAGIAN KIRI NAVBAR --}}
        <div class="navbar-left" style="display: flex; align-items: center; gap: 15px;">
            
            {{-- Tombol Buka Sidebar Menu (DIPINDAH KE KIRI SINI) --}}
            <button class="action-btn" id="btn-open-sidebar" style="background: transparent; border: none; font-size: 1.4rem; color: #334155; cursor: pointer; padding: 0; display: flex; align-items: center;">
                <i class="fas fa-bars"></i>
            </button>

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="navbar-logo" style="margin-right: 10px;">
                <h3>Pondasikita</h3>
            </a>
            
            {{-- Search Bar --}}
            <form action="{{ route('search') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="query" placeholder="Cari produk, toko..." value="{{ request('query') }}">
            </form>
        </div>

        {{-- BAGIAN KANAN NAVBAR --}}
        <nav class="navbar-right">
            <ul class="nav-links js-nav-links">
                <li><a href="{{ route('produk.index') }}" class="nav-link">Produk</a></li>
                <li><a href="{{ route('toko.index') }}" class="nav-link">Toko</a></li>
            </ul>

            <div class="nav-actions">
                {{-- Tombol Keranjang (Sekarang sendirian di kanan) --}}
                <a href="{{ route('keranjang.index') }}" class="action-btn cart-btn">
                    <i class="fas fa-shopping-cart"></i>
                    @if(isset($total_item_keranjang) && $total_item_keranjang > 0)
                        <span class="cart-badge">{{ $total_item_keranjang }}</span>
                    @endif
                </a>
            </div>
        </nav>
        
    </div>
</header>

{{-- ========================================================
     MODERN SIDEBAR (IKEA STYLE)
     ======================================================== --}}

{{-- Overlay Gelap --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

{{-- Sidebar Container --}}
<div class="modern-sidebar" id="modern-sidebar">
    
    {{-- Header Sidebar (Profil User & Tombol Close) --}}
    <div class="sidebar-header">
        @auth
            <div class="user-profile-widget">
                <div class="user-avatar">
                    {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                </div>
                <div class="user-info">
                    <h4>Halo, {{ explode(' ', Auth::user()->nama)[0] }}!</h4>
                    <span class="user-role">{{ ucfirst(Auth::user()->level) }} Pondasikita</span>
                </div>
            </div>
        @else
            <div class="user-profile-widget">
                <div class="user-avatar" style="background: #cbd5e1; color: white;">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-info">
                    <h4>Selamat Datang</h4>
                    <a href="{{ route('login') }}" style="color: #2563eb; font-size: 0.85rem; font-weight: 600; text-decoration: none;">Masuk atau Daftar</a>
                </div>
            </div>
        @endauth

        <button class="close-sidebar-btn" id="btn-close-sidebar">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Body Sidebar (Menu Navigasi) --}}
    <div class="sidebar-body">
        
        <div class="menu-section">
            <h5 class="menu-label">Jelajahi</h5>
            <ul class="sidebar-menu">
                <li><a href="{{ route('produk.index') }}"><i class="fas fa-box-open"></i> Semua Produk</a></li>
                <li><a href="{{ route('toko.index') }}"><i class="fas fa-store"></i> Semua Toko</a></li>
            </ul>
        </div>

        @auth
            <div class="menu-section">
                <h5 class="menu-label">Akun Saya</h5>
                <ul class="sidebar-menu">
                    <li><a href="{{ route('profil.index') }}"><i class="fas fa-user-circle"></i> Profil User</a></li>
                    <li><a href="{{ route('pesanan.index') }}"><i class="fas fa-clipboard-list"></i> Status Pesanan</a></li>
                    <li><a href="#" onclick="alert('Fitur Lacak Pengiriman Segera Hadir!')"><i class="fas fa-shipping-fast"></i> Melacak Pengiriman</a></li>
                    
                    {{-- Menu Khusus Admin/Seller (Jika ada) --}}
                    @if(Auth::user()->level === 'admin')
                        <li><a href="{{ url('/app_admin/dashboard_mimin.php') }}"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</a></li>
                    @elseif(Auth::user()->level === 'seller')
                        <li><a href="{{ url('/app_seller/dashboard.php') }}"><i class="fas fa-store-alt"></i> Dashboard Toko</a></li>
                    @endif
                </ul>
            </div>
        @endauth
    </div>

    {{-- Footer Sidebar (Tombol Keluar) --}}
    @auth
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    @endauth
</div>

{{-- CSS KHUSUS SIDEBAR --}}
<style>
    /* OVERLAY */
    .sidebar-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(2px); z-index: 9998; opacity: 0; visibility: hidden; transition: all 0.3s ease; }
    .sidebar-overlay.active { opacity: 1; visibility: visible; }

    /* SIDEBAR CONTAINER */
   .modern-sidebar { 
        position: fixed; 
        top: 0; 
        left: -400px; /* Diubah: Awalnya tersembunyi di luar layar sebelah kiri */
        width: 100%; 
        max-width: 360px; 
        height: 100vh; 
        background: #ffffff; 
        z-index: 9999; 
        display: flex; 
        flex-direction: column; 
        transition: left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Diubah: Animasi meluncur dari kiri */
        box-shadow: 5px 0 25px rgba(0,0,0,0.1); /* Diubah: Arah bayangan jatuh ke kanan */
    }
    .modern-sidebar.active { 
        left: 0; /* Diubah: Merapat ke ujung kiri layar saat aktif */
    }

    /* HEADER */
    .sidebar-header { padding: 30px 25px 20px 25px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: flex-start; }
    .user-profile-widget { display: flex; align-items: center; gap: 15px; }
    .user-avatar { width: 50px; height: 50px; border-radius: 50%; background: #2563eb; color: white; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; font-weight: 800; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
    .user-info h4 { margin: 0 0 3px 0; font-size: 1.1rem; color: #0f172a; font-weight: 800; }
    .user-role { font-size: 0.8rem; color: #64748b; background: #f1f5f9; padding: 3px 8px; border-radius: 4px; font-weight: 600;}
    
    .close-sidebar-btn { background: #f8fafc; border: none; width: 35px; height: 35px; border-radius: 50%; color: #64748b; cursor: pointer; transition: 0.2s; display: flex; justify-content: center; align-items: center; font-size: 1.1rem; }
    .close-sidebar-btn:hover { background: #ef4444; color: white; transform: rotate(90deg); }

    /* BODY & MENU */
    .sidebar-body { flex: 1; overflow-y: auto; padding: 20px 25px; }
    .sidebar-body::-webkit-scrollbar { width: 6px; }
    .sidebar-body::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    
    .menu-section { margin-bottom: 30px; }
    .menu-label { font-size: 0.75rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 15px 0; }
    
    .sidebar-menu { list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 5px; }
    .sidebar-menu a { display: flex; align-items: center; gap: 15px; color: #334155; text-decoration: none; font-size: 1rem; font-weight: 600; padding: 12px 15px; border-radius: 8px; transition: 0.2s; }
    .sidebar-menu a i { font-size: 1.2rem; color: #64748b; width: 24px; text-align: center; transition: 0.2s; }
    .sidebar-menu a:hover { background: #f8fafc; color: #2563eb; transform: translateX(5px); }
    .sidebar-menu a:hover i { color: #2563eb; }

    /* FOOTER (LOGOUT) */
    .sidebar-footer { padding: 20px 25px; border-top: 1px solid #f1f5f9; background: #f8fafc; }
    .logout-btn { width: 100%; display: flex; justify-content: center; align-items: center; gap: 10px; background: white; border: 1px solid #e2e8f0; color: #ef4444; padding: 14px; border-radius: 8px; font-size: 1rem; font-weight: 700; cursor: pointer; transition: 0.2s; }
    .logout-btn:hover { background: #fee2e2; border-color: #fca5a5; }

    @media (max-width: 768px) {
        .modern-sidebar { max-width: 85%; }
    }
</style>

{{-- JAVASCRIPT SIDEBAR --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnOpen = document.getElementById('btn-open-sidebar');
        const btnClose = document.getElementById('btn-close-sidebar');
        const sidebar = document.getElementById('modern-sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        // Buka Sidebar
        if(btnOpen) {
            btnOpen.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.add('active');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Kunci scroll halaman belakang
            });
        }

        // Tutup Sidebar (Fungsi Helper)
        const closeSidebar = () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = ''; // Kembalikan scroll
        };

        if(btnClose) btnClose.addEventListener('click', closeSidebar);
        if(overlay) overlay.addEventListener('click', closeSidebar);
    });
</script>