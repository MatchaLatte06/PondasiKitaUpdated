<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login Pelanggan - Pondasikita</title>
    {{-- Pastikan file CSS sudah ada di public/assets/css/ --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/auth_style_customer.css') }}" />
</head>
<body>
    <div class="auth-container customer-theme">
        <div class="auth-sidebar">
            <div>
                {{-- Gunakan asset() untuk gambar --}}
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo Pondasikita" class="logo" style="border-radius: 10px;"/>
                <h1>Lebih Hemat, Lebih Cepat</h1>
                <p>Temukan semua kebutuhan proyek Anda di sini.</p>
            </div>
            <span>© Pondasikita {{ date('Y') }}</span>
        </div>
        <div class="auth-main">
            <div class="form-wrapper">
                <h2>Log in</h2>
                {{-- Gunakan route() untuk link --}}
                <p>Baru di Pondasikita? <a href="{{ route('register') }}">Daftar</a></p>

                {{-- ALERT ERROR (Dari Controller) --}}
                @if (session('error'))
                    <div class="alert alert-danger" style="margin-bottom: 15px; color: red; background: #f8d7da; padding: 10px; border-radius: 5px;">
                        {{ session('error') }}
                        @if(isset($sisaDetik) && $sisaDetik > 0)
                             <span id="timer">{{ $sisaDetik }}</span>
                        @endif
                    </div>
                @endif

                {{-- ALERT VALIDATION ERROR --}}
                @if ($errors->any())
                    <div class="alert alert-danger" style="margin-bottom: 15px; color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('login.process') }}" method="POST">
                    @csrf {{-- WAJIB DI LARAVEL --}}
                    
                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Email atau Username" required value="{{ old('username') }}" {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }} />
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" placeholder="Kata Sandi" required {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }} />
                    </div>
                    
                    {{-- Input Hidden tidak diperlukan lagi karena logic dihandle Controller --}}
                    
                    <button type="submit" class="btn-submit" {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }}>LOG IN</button>

                    <center>
                        {{-- Link Google Login (Nanti setup Socialite) --}}
                        <a href="{{ url('auth/google') }}">
                            <img src="https://developers.google.com/identity/images/btn_google_signin_dark_normal_web.png" alt="Login with Google" />
                        </a>
                    </center>
                    
                    <a href="{{ url('lupa-password') }}" class="forgot-password">Lupa Password</a>
                </form>
            </div>
        </div>
    </div>

    {{-- Script Hitung Mundur (Jika user terblokir) --}}
    @if (($sisaDetik ?? 0) > 0)
    <script>
        let countdown = {{ $sisaDetik }};
        const timerElem = document.getElementById('timer');
        const inputs = document.querySelectorAll('input, button');

        if (countdown > 0) {
            const interval = setInterval(() => {
                countdown--;
                if (timerElem) timerElem.innerText = countdown;
                
                if (countdown <= 0) {
                    clearInterval(interval);
                    // Reload halaman agar form aktif kembali
                    location.href = location.pathname;
                }
            }, 1000);
        }
    </script>
    @endif
</body>
</html>