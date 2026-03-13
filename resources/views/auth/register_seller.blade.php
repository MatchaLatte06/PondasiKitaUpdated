<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buka Toko - Pondasikita Enterprise</title>

    {{-- Tailwind CSS CDN + Config Dewa --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 900: '#1e3a8a' },
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(37,99,235,0.3)',
                        'soft': '0 4px 40px -4px rgba(0,0,0,0.03)',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeInUp: { '0%': { opacity: 0, transform: 'translateY(20px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        /* Custom Scrollbar untuk area form */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Glassmorphism Panel */
        .glass-panel {
            background: linear-gradient(145deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.01) 100%);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* ========================================================
           HACK SELECT2 AGAR MENYATU DENGAN TAILWIND ENTERPRISE
           ======================================================== */
        .select2-container--default .select2-selection--single {
            background-color: #f8fafc !important; /* zinc-50 */
            border: 2px solid #e2e8f0 !important; /* zinc-200 */
            border-radius: 1rem !important; /* rounded-2xl */
            height: 54px !important;
            display: flex;
            align-items: center;
            padding: 0 10px 0 20px;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            color: #0f172a;
            transition: all 0.3s ease;
        }
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #2563eb !important; /* blue-600 */
            background-color: #ffffff !important;
            box-shadow: 0 0 0 4px rgba(37,99,235,0.1) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #0f172a !important;
            padding-left: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 50px !important;
            right: 15px !important;
        }
        .select2-dropdown {
            border: 2px solid #e2e8f0 !important;
            border-radius: 1rem !important;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1) !important;
            overflow: hidden;
            font-family: 'Inter', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            margin-top: 5px;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #2563eb !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #94a3b8 !important; /* zinc-400 */
            font-weight: 500;
        }

        /* Hilangkan panah di input number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-white font-sans text-zinc-900 antialiased flex flex-col lg:flex-row min-h-screen overflow-hidden">

    {{-- ======================================================= --}}
    {{-- KIRI: SISI VISUAL (STICKY BRANDING) --}}
    {{-- ======================================================= --}}
    <div class="hidden lg:flex w-5/12 bg-zinc-950 relative flex-col justify-between p-12 z-0">

        {{-- Ambient Light FX --}}
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-0 mix-blend-screen">
            <div class="absolute top-0 left-0 w-[600px] h-[600px] bg-blue-600/20 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2"></div>
            <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-indigo-600/20 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3"></div>
        </div>

        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.05] pointer-events-none z-10"></div>

        <div class="relative z-20 animate-fade-in-up">
            <a href="{{ url('/') }}" class="inline-flex items-center justify-center p-3 glass-panel rounded-2xl shadow-2xl hover:scale-105 transition-transform duration-500 mb-12">
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo" class="h-8 w-auto object-contain drop-shadow-md" onerror="this.outerHTML='<div class=\'text-white font-black text-xl px-2\'>P<span class=\'text-blue-600\'>.</span></div>'">
            </a>

            <h1 class="text-4xl xl:text-5xl font-black text-white leading-[1.1] tracking-tight mb-6">
                Ekspansi Bisnis<br>Material Anda<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Dimulai Dari Sini.</span>
            </h1>

            <p class="text-zinc-400 text-base font-medium leading-relaxed max-w-sm mb-12">
                Bergabunglah dengan ekosistem B2B terbesar. Capai ribuan kontraktor, kelola invoice digital, dan pantau logistik dalam satu platform pintar.
            </p>

            <div class="space-y-4">
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0 border border-blue-500/20">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm">Akses Pasar B2B</h4>
                    </div>
                </div>
                <div class="glass-panel p-4 rounded-2xl flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center shrink-0 border border-emerald-500/20">
                        <i class="fas fa-shield-check"></i>
                    </div>
                    <div>
                        <h4 class="text-white font-bold text-sm">Pembayaran Terjamin</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative z-20 text-zinc-600 text-[10px] font-black uppercase tracking-widest">
            © {{ date('Y') }} Pondasikita Enterprise
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- KANAN: SISI FORMULIR (SCROLLABLE) --}}
    {{-- ======================================================= --}}
    <div class="w-full lg:w-7/12 overflow-y-auto h-screen custom-scrollbar relative bg-white z-10 shadow-[-20px_0_40px_rgba(0,0,0,0.05)]">
        <div class="p-6 sm:p-12 lg:p-16 max-w-2xl mx-auto animate-fade-in-up">

            {{-- Logo Mobile --}}
            <div class="lg:hidden mb-8">
                <img src="{{ asset('assets/image/Pondasikita.com.png') }}" alt="Logo" class="h-8 w-auto" onerror="this.outerHTML='<div class=\'text-black font-black text-2xl\'>Pondasikita<span class=\'text-blue-600\'>.</span></div>'">
            </div>

            <div class="mb-10">
                <h2 class="text-3xl font-black text-black tracking-tight mb-2">Form Registrasi Toko</h2>
                <p class="text-zinc-500 font-medium text-sm">
                    Sudah memiliki akun toko? <a href="{{ route('seller.login') }}" class="text-blue-600 font-bold hover:underline transition-all">Masuk ke Dashboard</a>
                </p>
            </div>

            {{-- ALERT VALIDASI ERROR (SweetAlert via JS & Native Fallback) --}}
            @if ($errors->any())
                <div class="mb-8 bg-red-50 border border-red-200 p-5 rounded-2xl">
                    <div class="flex items-center gap-2 text-red-600 font-black text-sm mb-2 uppercase tracking-wide">
                        <i class="fas fa-exclamation-triangle"></i> Periksa Kembali Input Anda
                    </div>
                    <ul class="list-disc pl-5 text-xs text-red-500 font-medium space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('seller.register.process') }}" method="POST" enctype="multipart/form-data" id="registerSellerForm" class="space-y-10">
                @csrf
                <input type="hidden" name="level" value="seller">

                {{-- SECTION 1: INFO PEMILIK --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 border-b border-zinc-100 pb-3">
                        <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 font-black flex items-center justify-center text-sm">1</div>
                        <h3 class="text-lg font-black text-black">Informasi Pemilik</h3>
                    </div>

                    <div class="space-y-5">
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap (Sesuai KTP) <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_pemilik" required value="{{ old('nama_pemilik') }}" placeholder="Budi Santoso" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Username Login <span class="text-red-500">*</span></label>
                                <input type="text" name="username" required value="{{ old('username') }}" placeholder="budimaterial" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none lowercase">
                            </div>
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Email Profesional <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required value="{{ old('email') }}" placeholder="budi@perusahaan.com" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Kata Sandi <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="password" name="password" id="password" required placeholder="Min. 6 Karakter" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 pl-5 pr-12 py-4 transition-all outline-none">
                                    <button type="button" onclick="toggleVisibility('password', 'eye1')" class="absolute inset-y-0 right-0 pr-4 flex items-center text-zinc-400 hover:text-black">
                                        <i class="fas fa-eye" id="eye1"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">No. Handphone Pribadi <span class="text-red-500">*</span></label>
                                <input type="number" name="no_telepon" required value="{{ old('no_telepon') }}" placeholder="0812..." class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: INFO TOKO --}}
                <div>
                    <div class="flex items-center gap-3 mb-6 border-b border-zinc-100 pb-3">
                        <div class="w-8 h-8 rounded-full bg-zinc-900 text-white font-black flex items-center justify-center text-sm">2</div>
                        <h3 class="text-lg font-black text-black">Profil Bisnis / Toko</h3>
                    </div>

                    <div class="space-y-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Nama Toko Material <span class="text-red-500">*</span></label>
                                <input type="text" name="nama_toko" required value="{{ old('nama_toko') }}" placeholder="TB. Makmur Jaya" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none">
                            </div>
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Telepon / WA Toko <span class="text-red-500">*</span></label>
                                <input type="number" name="telepon_toko" required value="{{ old('telepon_toko') }}" placeholder="0821..." class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none">
                            </div>
                        </div>

                        {{-- Select2 Wrappers --}}
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Provinsi <span class="text-red-500">*</span></label>
                            <select id="provinsi" name="province_id" class="select2-theme" required style="width: 100%;">
                                <option value="">Pilih Provinsi...</option>
                                @foreach(DB::table('provinces')->orderBy('name')->get() as $prov)
                                    <option value="{{ $prov->id }}">{{ $prov->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Kota / Kabupaten <span class="text-red-500">*</span></label>
                                <select id="kota" name="city_id" class="select2-theme" required disabled style="width: 100%;">
                                    <option value="">Pilih Provinsi Dulu</option>
                                </select>
                            </div>
                            <div class="relative group">
                                <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Kecamatan <span class="text-red-500">*</span></label>
                                <select id="kecamatan" name="district_id" class="select2-theme" required disabled style="width: 100%;">
                                    <option value="">Pilih Kota Dulu</option>
                                </select>
                            </div>
                        </div>

                        <div class="relative group">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Alamat Fisik Toko <span class="text-red-500">*</span></label>
                            <textarea name="alamat_toko" rows="3" required placeholder="Nama Jalan, Nomor Bangunan, RT/RW, Patokan..." class="custom-scrollbar w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-4 transition-all outline-none resize-none">{{ old('alamat_toko') }}</textarea>
                        </div>

                        {{-- Custom File Upload --}}
                        <div class="relative group">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1.5 ml-1">Logo Toko (Opsional)</label>
                            <div class="w-full bg-zinc-50 border-2 border-dashed border-zinc-300 rounded-2xl p-4 flex items-center gap-4 transition-colors hover:border-blue-500 relative">
                                <input type="file" name="logo_toko" id="logo_toko" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                                <div class="w-12 h-12 rounded-xl bg-white border border-zinc-200 flex items-center justify-center shrink-0 overflow-hidden" id="logo-preview-container">
                                    <i class="fas fa-image text-zinc-300 text-lg" id="logo-icon"></i>
                                    <img id="logo-preview" class="w-full h-full object-cover hidden">
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-zinc-700" id="file-name">Unggah Logo Anda</h4>
                                    <p class="text-[10px] font-medium text-zinc-400 mt-0.5">Klik area ini. Maksimal 2MB (JPG/PNG).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action Area --}}
                <div class="pt-4 pb-10">
                    <button type="submit" id="btnSubmit" class="w-full bg-black hover:bg-blue-600 text-white font-black py-4 rounded-2xl transition-all duration-300 shadow-[0_8px_20px_rgba(0,0,0,0.15)] hover:shadow-glow hover:-translate-y-1 text-base flex items-center justify-center gap-2">
                        Kirim Pendaftaran Toko <i class="fas fa-arrow-right"></i>
                    </button>
                    <p class="text-[10px] text-zinc-400 text-center mt-5 font-medium leading-relaxed max-w-sm mx-auto">
                        Dengan mendaftar, Anda menyetujui Perjanjian Kemitraan dan Kebijakan Privasi Penjual Pondasikita.
                    </p>
                </div>

            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // 1. Password Toggle
        function toggleVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text'; icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password'; icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // 2. Custom Image Preview
        function previewImage(input) {
            const preview = document.getElementById('logo-preview');
            const icon = document.getElementById('logo-icon');
            const fileName = document.getElementById('file-name');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    icon.classList.add('hidden');
                    fileName.innerText = input.files[0].name;
                    fileName.classList.add('text-blue-600');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // 3. Loading State
        document.getElementById('registerSellerForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnSubmit');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses Data...';
            btn.classList.add('opacity-80', 'cursor-not-allowed');
            btn.classList.remove('hover:-translate-y-1', 'hover:bg-blue-600', 'hover:shadow-glow');
        });

        // 4. Select2 & Region Logic
        $(document).ready(function() {
            // Init
            $('.select2-theme').select2({
                width: '100%',
                placeholder: function(){ $(this).data('placeholder'); }
            });

            // Logic Provinsi -> Kota
            $('#provinsi').on('change', function() {
                let provinceId = $(this).val();
                $('#kota').empty().append('<option value="">Memuat...</option>').prop('disabled', true).trigger('change');
                $('#kecamatan').empty().append('<option value="">Pilih Kota Dulu</option>').prop('disabled', true).trigger('change');

                if(provinceId) {
                    $.ajax({
                        url: '/api/cities/' + provinceId, type: 'GET', dataType: 'json',
                        success: function(data) {
                            $('#kota').empty().append('<option value="">Pilih Kota/Kabupaten</option>');
                            $.each(data, function(key, value) {
                                $('#kota').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                            $('#kota').prop('disabled', false).trigger('change');
                        },
                        error: function() { $('#kota').empty().append('<option value="">Gagal Memuat</option>'); }
                    });
                }
            });

            // Logic Kota -> Kecamatan
            $('#kota').on('change', function() {
                let cityId = $(this).val();

                if(cityId) {
                    $('#kecamatan').empty().append('<option value="">Memuat...</option>').prop('disabled', true).trigger('change');
                    $.ajax({
                        url: '/api/districts/' + cityId, type: 'GET', dataType: 'json',
                        success: function(data) {
                            $('#kecamatan').empty().append('<option value="">Pilih Kecamatan</option>');
                            $.each(data, function(key, value) {
                                $('#kecamatan').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            });
                            $('#kecamatan').prop('disabled', false).trigger('change');
                        },
                        error: function() { $('#kecamatan').empty().append('<option value="">Gagal Memuat</option>'); }
                    });
                } else {
                    $('#kecamatan').empty().append('<option value="">Pilih Kota Dulu</option>').prop('disabled', true).trigger('change');
                }
            });
        });

        // 5. SweetAlert Flash Messages
        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Pendaftaran Berhasil!', text: "{{ session('success') }}",
                confirmButtonColor: '#000000', confirmButtonText: 'Login Sekarang', allowOutsideClick: false,
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-8 py-3 font-bold' }
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "{{ route('seller.login') }}";
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", confirmButtonColor: '#000000',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-8 py-3 font-bold' }
            });
        @endif

        @if($errors->any())
            let errorMessages = {!! json_encode($errors->all()) !!};
            Swal.fire({
                icon: 'warning', title: 'Periksa Data Anda',
                html: `<div style="text-align: left; font-size: 0.85rem; color: #dc2626; background: #fef2f2; padding: 15px; border-radius: 12px; border: 1px solid #fecaca; margin-top: 10px;">
                        <ul style="padding-left: 20px; margin: 0; font-weight: 500; line-height: 1.5;">
                            ${errorMessages.map(err => `<li>${err}</li>`).join('')}
                        </ul></div>`,
                confirmButtonColor: '#000000',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-8 py-3 font-bold' }
            });
        @endif
    </script>
</body>
</html>
