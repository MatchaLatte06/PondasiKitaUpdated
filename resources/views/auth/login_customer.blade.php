<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Masuk - Pondasikita B2B</title>

    {{-- Tailwind CSS CDN + Config Dewa --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: { brand: { 50: '#eff6ff', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a' } },
                    animation: {
                        'blob': 'blob 10s infinite alternate',
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        blob: { '0%': { transform: 'translate(0px, 0px) scale(1)' }, '100%': { transform: 'translate(30px, -50px) scale(1.1)' } },
                        fadeInUp: { '0%': { opacity: 0, transform: 'translateY(20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        input:-webkit-autofill { -webkit-box-shadow: 0 0 0 50px white inset; }
    </style>
</head>
<body class="bg-white font-sans text-zinc-900 antialiased flex min-h-screen overflow-hidden">

    {{-- KIRI: SISI VISUAL (CINEMATIC DARK) --}}
    <div class="hidden lg:flex w-1/2 bg-[#09090b] relative items-center justify-center overflow-hidden p-12">
        {{-- Animated Abstract Glow --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute -top-20 -left-20 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-10 right-10 w-[400px] h-[400px] bg-indigo-600/20 rounded-full blur-[100px] animate-blob" style="animation-delay: 2s;"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03]"></div>
        </div>

        {{-- Cinematic Branding --}}
        <div class="relative z-10 w-full max-w-lg animate-fade-in-up">
            <div class="mb-10 inline-flex items-center justify-center p-3 bg-white/5 border border-white/10 rounded-2xl backdrop-blur-xl shadow-2xl">
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo" class="h-12 w-auto object-contain drop-shadow-lg" onerror="this.outerHTML='<div class=\'text-white font-black text-2xl px-2\'>P<span class=\'text-blue-500\'>.</span></div>'">
            </div>
            <h1 class="text-5xl font-black text-white leading-[1.1] tracking-tight mb-6">
                Gerbang Utama<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Suplai Proyek Anda.</span>
            </h1>
            <p class="text-zinc-400 text-lg font-medium leading-relaxed mb-12">
                Akses ribuan material dari distributor terpercaya. Manajemen RAB, lacak pesanan, dan dapatkan harga khusus B2B dalam satu dashboard pintar.
            </p>

            {{-- Glassmorphism Testimonial/Info --}}
            <div class="bg-white/5 border border-white/10 backdrop-blur-md p-6 rounded-3xl flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-xl shadow-lg shrink-0">
                    <i class="fas fa-shield-check"></i>
                </div>
                <div>
                    <h4 class="text-white font-bold text-sm">Transaksi 100% Aman</h4>
                    <p class="text-zinc-400 text-xs mt-1">Sistem pembayaran escrow terenkripsi.</p>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-12 text-zinc-500 text-xs font-semibold">
            © {{ date('Y') }} Pondasikita Enterprise.
        </div>
    </div>

    {{-- KANAN: SISI FORM LOGIN --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-y-auto">
        <div class="w-full max-w-md animate-fade-in-up" style="animation-delay: 0.1s;">

            {{-- Logo Mobile --}}
            <div class="lg:hidden mb-8">
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo" class="h-10 w-auto" onerror="this.outerHTML='<div class=\'text-black font-black text-3xl\'>Pondasikita<span class=\'text-blue-600\'>.</span></div>'">
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-black text-black tracking-tight mb-2">Selamat Datang Kembali</h2>
                <p class="text-zinc-500 font-medium text-sm">
                    Belum punya akun B2B? <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline transition-all">Daftar sekarang</a>
                </p>
            </div>

            {{-- ALERT ERROR --}}
            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-xl flex items-start gap-3 animate-fade-in-up">
                    <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                    <div>
                        <h4 class="text-sm font-bold text-red-800">Akses Ditolak</h4>
                        <p class="text-xs text-red-600 mt-1 font-medium">
                            {{ session('error') }}
                            @if(isset($sisaDetik) && $sisaDetik > 0)
                                Silakan coba lagi dalam <span id="timer" class="font-black underline">{{ $sisaDetik }}</span> detik.
                            @endif
                        </p>
                    </div>
                </div>
            @endif

            {{-- ALERT VALIDATION --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 p-4 rounded-2xl animate-fade-in-up">
                    <ul class="list-disc pl-5 text-xs text-red-600 font-medium space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.process') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Input Username --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-user text-zinc-400 group-focus-within:text-blue-600 transition-colors"></i>
                    </div>
                    <input type="text" id="username" name="username" placeholder="Email atau Username" required value="{{ old('username') }}" {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }} class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block pl-11 pr-4 py-4 transition-all outline-none placeholder:text-zinc-400 placeholder:font-medium disabled:opacity-50 disabled:cursor-not-allowed">
                </div>

                {{-- Input Password --}}
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="fas fa-lock text-zinc-400 group-focus-within:text-blue-600 transition-colors"></i>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Kata Sandi" required {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }} class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block pl-11 pr-12 py-4 transition-all outline-none placeholder:text-zinc-400 placeholder:font-medium disabled:opacity-50 disabled:cursor-not-allowed">

                    {{-- Toggle Password Visibility --}}
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-400 hover:text-black transition-colors focus:outline-none">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>

                <div class="flex items-center justify-end pt-1">
                    <a href="{{ url('lupa-password') }}" class="text-xs font-bold text-zinc-500 hover:text-blue-600 transition-colors">Lupa Kata Sandi?</a>
                </div>

                {{-- Submit Button --}}
                <button type="submit" {{ ($sisaDetik ?? 0) > 0 ? 'disabled' : '' }} class="w-full bg-black hover:bg-blue-600 text-white font-black py-4 rounded-2xl transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.3)] hover:-translate-y-1 disabled:opacity-50 disabled:hover:translate-y-0 disabled:hover:bg-black disabled:cursor-not-allowed mt-2">
                    Masuk ke Dashboard
                </button>

                <div class="relative flex items-center py-4">
                    <div class="flex-grow border-t border-zinc-200"></div>
                    <span class="flex-shrink-0 mx-4 text-zinc-400 text-xs font-bold uppercase tracking-widest">Atau masuk dengan</span>
                    <div class="flex-grow border-t border-zinc-200"></div>
                </div>

                {{-- Google SSO Button --}}
                <a href="{{ url('auth/google') }}" class="w-full bg-white border-2 border-zinc-200 hover:bg-zinc-50 text-black font-bold py-3.5 rounded-2xl transition-all duration-300 flex items-center justify-center gap-3 shadow-sm group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
                    Google
                </a>
            </form>
        </div>
    </div>

    {{-- Script Hitung Mundur & Toggle Password --}}
    <script>
        function togglePassword() {
            const pwd = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (pwd.type === 'password') {
                pwd.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                pwd.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        @if (($sisaDetik ?? 0) > 0)
            let countdown = {{ $sisaDetik }};
            const timerElem = document.getElementById('timer');

            if (countdown > 0) {
                const interval = setInterval(() => {
                    countdown--;
                    if (timerElem) timerElem.innerText = countdown;
                    if (countdown <= 0) {
                        clearInterval(interval);
                        location.href = location.pathname;
                    }
                }, 1000);
            }
        @endif
    </script>
</body>
</html>
