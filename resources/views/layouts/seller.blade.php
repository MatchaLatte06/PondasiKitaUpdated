<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Pondasikita Seller Center</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@latest/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/template/spica/template/vendors/mdi/css/materialdesignicons.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="container-scroller">
        @include('seller.partials.sidebar')

        <div class="page-body-wrapper">
            @include('seller.partials.navbar')

            <main class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
                
                <footer class="footer" style="padding: 20px; text-align: center; color: var(--text-secondary); font-size: 0.8rem;">
                    © Pondasikita {{ date('Y') }} - Seller Center Premium
                </footer>
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('assets/js/sidebar.js') }}"></script>
    
    @stack('scripts')
    @push('scripts')
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    @if(session('success'))
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    @endif

    @if(session('error'))
        Toast.fire({
            icon: 'error',
            title: "{{ session('error') }}"
        });
    @endif

    @if($errors->any())
        Toast.fire({
            icon: 'warning',
            title: "Ada kesalahan input data!"
        });
    @endif
</script>
@endpush
</body>
</html>