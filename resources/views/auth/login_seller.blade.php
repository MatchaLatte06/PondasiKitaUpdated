<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Penjual - Pondasikita Seller Centre</title>
    
    {{-- Menggunakan asset() agar link CSS tidak error --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/auth_style.css') }}">
     <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/auth_style_customer.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <style>
        /* Tambahan style khusus seller jika diperlukan */
        .seller-theme .auth-sidebar { background-color: #ff9800; /* Contoh warna oranye */ }
    </style>
</head>
<body>
    <div class="auth-container seller-theme">
        <div class="auth-sidebar">
            <div>
                {{-- Menggunakan asset() untuk gambar --}}
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo Pondasikita" class="logo"> 
                <h1>Jadilah Penjual Terbaik!</h1>
                <p>Kelola toko Anda secara efisien di Pondasikita Seller Centre.</p>
            </div>
            <span>© Pondasikita {{ date('Y') }}</span>
        </div>
        <div class="auth-main">
            <div class="form-wrapper">
                <h2>Login Penjual</h2>
                {{-- Menggunakan route() untuk link yang dinamis --}}
                <p>Ingin mulai berjualan? <a href="{{ route('seller.register') }}">Daftar sebagai Penjual</a></p>

                {{-- Form Action mengarah ke Route Laravel --}}
                <form action="{{ route('login.process') }}" method="POST">
                    
                    {{-- WAJIB: Token keamanan Laravel --}}
                    @csrf 

                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Email atau Username Toko" required value="{{ old('username') }}">
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Kata Sandi" required>
                    </div>
                    
                    {{-- Input hidden user_type tidak wajib jika logika login menyatu, 
                         tapi boleh disimpan jika controller membutuhkannya --}}
                    {{-- <input type="hidden" name="user_type" value="seller"> --}}
                    
                    <button type="submit" class="btn-submit">LOG IN</button>
                    
                    <a href="{{ url('/lupa-password') }}" class="forgot-password">Lupa Password</a>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Tangkap alert dari Controller
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ff6f00'
            });
        @endif
    </script>
</body>
</body>
</html>