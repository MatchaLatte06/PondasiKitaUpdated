<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Pondasikita Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    
    <link rel="stylesheet" href="{{ asset('assets/css/admin_style.css') }}">
    
    <style>
        body {
            background-color: #f4f7f6; /* Warna background khas admin */
            margin: 0;
            padding: 0;
            font-family: 'Inter', -apple-system, sans-serif;
            overflow-x: hidden;
        }

        .container-scroller {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Area kanan (sebelah sidebar) */
        .page-body-wrapper {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            min-width: 0; /* Mencegah overflow horizontal */
        }

        .main-panel {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .content-wrapper {
            padding: 1.5rem 2rem;
            flex-grow: 1;
        }

        /* Responsive Mobile */
        @media (max-width: 991px) {
            .content-wrapper {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        
        {{-- 1. INCLUDE SIDEBAR ADMIN --}}
        @include('admin.partials.sidebar')
        
        <div class="page-body-wrapper">
            <main class="main-panel w-100">
                <div class="content-wrapper">
                    
                    {{-- 2. INCLUDE NAVBAR ADMIN --}}
                    @include('admin.partials.navbar')
                    
                    {{-- Menampilkan Flash Message (Success / Error) secara global --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- 3. KONTEN UTAMA DARI SETIAP HALAMAN --}}
                    @yield('content')
                    
                </div>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('scripts')
</body>
</html>