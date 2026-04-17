<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Akun - Pondasikita B2B</title>

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
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
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
<body class="bg-white font-sans text-zinc-900 antialiased flex flex-col lg:flex-row-reverse min-h-screen overflow-hidden">

    {{-- KANAN: SISI VISUAL (CINEMATIC DARK) --}}
    <div class="hidden lg:flex w-1/2 bg-[#09090b] relative items-center justify-center overflow-hidden p-12">
        {{-- Animated Abstract Glow --}}
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-10 right-10 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[120px] animate-blob"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-600/20 rounded-full blur-[100px] animate-blob" style="animation-delay: 1.5s;"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.03]"></div>
        </div>

        {{-- Cinematic Branding --}}
        <div class="relative z-10 w-full max-w-lg animate-fade-in-up">
            <h1 class="text-5xl font-black text-white leading-[1.1] tracking-tight mb-6">
                Mulailah Bangun<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Kerajaan Anda.</span>
            </h1>
            <p class="text-zinc-400 text-lg font-medium leading-relaxed mb-10">
                Bergabung dengan ratusan kontraktor dan pemilik proyek yang telah mempercayakan suplai material mereka melalui ekosistem digital kami.
            </p>

            <div class="space-y-4">
                <div class="bg-white/5 border border-white/10 backdrop-blur-md p-5 rounded-2xl flex items-center gap-4 transition-transform hover:-translate-y-1">
                    <div class="w-10 h-10 rounded-full bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0"><i class="fas fa-tags"></i></div>
                    <div><h4 class="text-white font-bold text-sm">Harga Spesial B2B</h4></div>
                </div>
                <div class="bg-white/5 border border-white/10 backdrop-blur-md p-5 rounded-2xl flex items-center gap-4 transition-transform hover:-translate-y-1" style="animation-delay: 0.1s;">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0"><i class="fas fa-truck-fast"></i></div>
                    <div><h4 class="text-white font-bold text-sm">Logistik Terintegrasi</h4></div>
                </div>
            </div>
        </div>

        <div class="absolute bottom-8 left-12 text-zinc-500 text-xs font-semibold">
            © {{ date('Y') }} Pondasikita Enterprise.
        </div>
    </div>

    {{-- KIRI: SISI FORM REGISTER --}}
    <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 relative overflow-y-auto h-screen custom-scrollbar">
        <div class="w-full max-w-md my-auto animate-fade-in-up pb-10">

            <div class="mb-8 mt-4 lg:mt-0">
                <div class="inline-flex items-center justify-center p-2.5 bg-zinc-100 rounded-xl mb-6 shadow-sm lg:hidden">
                    <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo" class="h-8 w-auto object-contain" onerror="this.outerHTML='<div class=\'text-black font-black text-xl px-2\'>P<span class=\'text-blue-500\'>.</span></div>'">
                </div>
                <h2 class="text-3xl font-black text-black tracking-tight mb-2">Buat Akun Baru</h2>
                <p class="text-zinc-500 font-medium text-sm">
                    Sudah tergabung? <a href="{{ route('login') }}" class="text-blue-600 font-bold hover:underline transition-all">Masuk di sini</a>
                </p>
            </div>

            <form action="{{ route('register.process') }}" method="POST" id="registerForm" class="space-y-4">
                @csrf

                {{-- Row 1: Username & Nama --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="relative group">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Username</label>
                        <input type="text" name="username" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block px-4 py-3.5 transition-all outline-none placeholder:text-zinc-400" placeholder="johndoe" value="{{ old('username') }}" required>
                    </div>
                    <div class="relative group">
                        <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="nama" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block px-4 py-3.5 transition-all outline-none placeholder:text-zinc-400" placeholder="John Doe" value="{{ old('nama') }}" required>
                    </div>
                </div>

                {{-- Row 2: Email --}}
                <div class="relative group">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Email Profesional</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-envelope text-zinc-400 group-focus-within:text-blue-600 transition-colors"></i></div>
                        <input type="email" name="email" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block pl-11 pr-4 py-3.5 transition-all outline-none placeholder:text-zinc-400" placeholder="john@perusahaan.com" value="{{ old('email') }}" required>
                    </div>
                </div>

                {{-- Row 3: Passwords --}}
                <div class="relative group">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Kata Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-lock text-zinc-400 group-focus-within:text-blue-600 transition-colors"></i></div>
                        <input type="password" name="password" id="reg-password" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block pl-11 pr-12 py-3.5 transition-all outline-none placeholder:text-zinc-400" placeholder="Minimal 8 karakter" required>
                        <button type="button" onclick="toggleRegPassword('reg-password', 'eyeReg')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-400 hover:text-black transition-colors focus:outline-none"><i class="fas fa-eye" id="eyeReg"></i></button>
                    </div>
                </div>

                <div class="relative group">
                    <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Konfirmasi Sandi</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-shield-alt text-zinc-400 group-focus-within:text-blue-600 transition-colors"></i></div>
                        <input type="password" name="password_confirmation" id="reg-password-conf" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 block pl-11 pr-12 py-3.5 transition-all outline-none placeholder:text-zinc-400" placeholder="Ulangi kata sandi" required>
                        <button type="button" onclick="toggleRegPassword('reg-password-conf', 'eyeRegConf')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-400 hover:text-black transition-colors focus:outline-none"><i class="fas fa-eye" id="eyeRegConf"></i></button>
                    </div>
                </div>

                {{-- Submit Button --}}
                <button type="submit" id="submitBtn" class="w-full bg-black hover:bg-blue-600 text-white font-black py-4 rounded-2xl transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.3)] hover:-translate-y-1 mt-6">
                    Mulai Berbelanja
                </button>

                <p class="text-[10px] text-zinc-400 text-center mt-6 font-medium leading-relaxed">
                    Dengan mendaftar, Anda menyetujui <a href="#" class="text-black font-bold hover:underline">Syarat & Ketentuan</a> serta <a href="#" class="text-black font-bold hover:underline">Kebijakan Privasi</a> Pondasikita.
                </p>
            </form>
        </div>
    </div>

    {{-- SWEETALERT & LOGIC --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleRegPassword(inputId, iconId) {
            const pwd = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (pwd.type === 'password') {
                pwd.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                pwd.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const submitBtn = document.getElementById('submitBtn');

            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Sedang Membuat Akun...';
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');
                submitBtn.classList.remove('hover:-translate-y-1', 'hover:bg-blue-600');
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Akun Dibuat!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#000000',
                    confirmButtonText: 'Masuk Sekarang',
                    allowOutsideClick: false,
                    customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl font-bold px-6 py-3' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal',
                    html: `
                        <div style="text-align: left; font-size: 0.85rem; color: #dc2626; background: #fef2f2; padding: 15px; border-radius: 12px; border: 1px solid #fecaca; margin-top: 10px;">
                            <ul style="padding-left: 20px; margin: 0; font-weight: 500; line-height: 1.5;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    `,
                    confirmButtonColor: '#000000',
                    confirmButtonText: 'Perbaiki',
                    customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl font-bold px-6 py-3' }
                });
            @endif
        });
    </script>
</body>
</html>
