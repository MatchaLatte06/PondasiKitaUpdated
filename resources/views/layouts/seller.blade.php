<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Pondasikita Seller</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
    
    <style>
        body { background-color: #f5f7ff; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
        .container-scroller { display: flex; width: 100%; min-height: 100vh; }
        .page-body-wrapper { display: flex; flex-direction: column; flex-grow: 1; width: calc(100% - 260px); } /* Sisa lebar */
        .top-navbar { height: 70px; background: white; border-bottom: 1px solid #e3e3e3; display: flex; align-items: center; padding: 0 20px; }
        .main-panel { flex-grow: 1; padding: 2rem; overflow-y: auto; }
        
        /* Mobile Fix */
        @media(max-width: 992px) {
            .page-body-wrapper { width: 100%; margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="container-scroller">
        
        {{-- INCLUDE SIDEBAR BARU --}}
        @include('seller.partials.sidebar')

        <div class="page-body-wrapper">
            {{-- NAVBAR --}}
            @include('seller.partials.navbar')

            {{-- KONTEN --}}
            <main class="main-panel">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Logic Sidebar Mobile dari CSS Anda
        $('.sidebar-toggle-btn').on('click', function() {
            $('.sidebar').toggleClass('active');
        });
    </script>
    @stack('scripts')
</body>
</html>