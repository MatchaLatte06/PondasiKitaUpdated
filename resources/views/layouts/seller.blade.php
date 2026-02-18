<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pondasikita Seller Center</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/template/spica/template/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}"> 
    
   <style>
   html, body {
    margin: 0;
    padding: 0;
    overflow-x: hidden; /* Mencegah goyang ke kanan-kiri */
    height: calc(100vh - 60px); 
}

/* Pastikan container scroller tidak memiliki transform yang merusak koordinat fixed */
.container-scroller {
    position: relative;
    width: 100%;
}

.page-body-wrapper {
    display: flex;
    min-height: 100%;
    box-sizing: border-box;
}
.main-panel {
    flex: 2;
    margin-left: 250px; /* Ruang untuk sidebar */
   
    background-color: #f4f4f4;
    min-height: 100vh;
    box-sizing: border-box;
}

/* Responsif Mobile */
@media (max-width: 991px) {
    .main-panel {
        margin-left: 0;
        width: 100%;
    }
}
</style>
    @stack('styles') {{-- Untuk CSS tambahan dari child page --}}
</head>

<body>
<div class="container-scroller">
    
    {{-- Memanggil partial sidebar --}}
    @include('seller.partials.sidebar')

    <div class="page-body-wrapper">
        {{-- Top Navbar --}}
        <nav class="top-navbar">
            <div class="navbar-left">
                <button class="sidebar-toggle-btn d-lg-none"><i class="mdi mdi-menu"></i></button>
            </div>
            <div class="navbar-right">
                <a href="#" class="navbar-icon"><i class="mdi mdi-bell-outline"></i></a>
                <a href="#" class="navbar-icon"><i class="mdi mdi-help-circle-outline"></i></a>
                <div class="navbar-profile">
                    <span class="profile-name">{{ Auth::user()->nama ?? 'Seller' }}</span>
                    <i class="mdi mdi-chevron-down profile-arrow"></i>
                </div>
            </div>
        </nav>

        <main class="main-panel">
            <div class="content-wrapper">
                {{-- Bagian ini yang akan diisi oleh halaman Dashboard --}}
                @yield('content')
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('assets/js/sidebar.js') }}"></script>

@stack('scripts') {{-- Untuk JS tambahan dari child page --}}
</body>
</html>