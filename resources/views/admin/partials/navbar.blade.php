<style>
    /* Navbar Admin */
    .navbar-admin {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background-color: #1c1f2c;
        color: #fff;
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .navbar-admin .navbar-left h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
    }

    .navbar-admin .navbar-right {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .navbar-admin .navbar-right span {
        font-size: 14px;
        font-weight: 500;
    }

    .navbar-admin .btn-logout {
        background-color: #e74c3c;
        color: #fff;
        padding: 6px 12px;
        text-decoration: none;
        border-radius: 5px;
        font-size: 14px;
        transition: background 0.2s ease;
    }

    .navbar-admin .btn-logout:hover {
        background-color: #c0392b;
    }
</style>

<div class="navbar-admin">
    <div class="navbar-left">
        {{-- Kita ambil title dari @yield atau set default --}}
        <h2>@yield('title', 'Dashboard Admin')</h2>
    </div>
    <div class="navbar-right">
        {{-- Mengambil nama admin yang sedang login menggunakan Auth Laravel --}}
        <span>Halo, {{ Auth::user()->nama ?? 'Administrator' }}</span>
        
        {{-- Form logout standar Laravel (menggunakan POST untuk keamanan) --}}
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout" style="border: none; cursor: pointer;">
                Logout
            </button>
        </form>
    </div>
</div>