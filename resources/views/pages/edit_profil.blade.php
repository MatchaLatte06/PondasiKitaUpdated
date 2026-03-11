<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Profil & Alamat - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CSS CDN + Config Dewa --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        surface: '#fcfcfd',
                    },
                    boxShadow: {
                        'soft': '0 4px 40px -4px rgba(0,0,0,0.03)',
                        'float': '0 10px 30px -5px rgba(0,0,0,0.08)',
                        'glow': '0 0 20px rgba(37,99,235,0.3)',
                        'sticky-bottom': '0 -10px 40px rgba(0,0,0,0.05)',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
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

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }

        /* Remove arrows from number input */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Custom Select styling */
        select { -webkit-appearance: none; -moz-appearance: none; appearance: none; }

        /* Smooth Scrollbar for textareas/dropdowns */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px] pb-32">

    {{-- Include Navbar --}}
    @include('partials.navbar')

    {{-- TOP NAVIGATION BAR (Sticky Back Button) --}}
    <div class="bg-white border-b border-zinc-200 sticky top-[80px] z-30 shadow-sm hidden md:block">
        <div class="max-w-[1100px] mx-auto px-4 sm:px-6 py-3 flex items-center justify-between">
            <a href="{{ route('profil.index') }}" class="flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-black transition-colors group">
                <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center group-hover:bg-zinc-200 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                </div>
                Kembali ke Profil
            </a>
            <span class="text-xs font-black tracking-widest uppercase text-zinc-400">Pengaturan Akun</span>
        </div>
    </div>

    {{-- Mobile Back Button --}}
    <div class="md:hidden px-4 pt-6 pb-2">
        <a href="{{ route('profil.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-600">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <main class="max-w-[1100px] mx-auto px-4 sm:px-6 py-6 md:py-10">

        <div class="mb-8 md:mb-12">
            <h1 class="text-3xl font-black text-black tracking-tight">Perbarui Profil</h1>
            <p class="text-sm font-medium text-zinc-500 mt-1">Pastikan data diri dan alamat pengiriman Anda selalu valid dan terkini.</p>
        </div>

        <form action="{{ route('profil.update') }}" method="POST" enctype="multipart/form-data" id="editProfileForm">
            @csrf

            {{-- LAYOUT GRID 12 KOLOM (Logika Rata Kanan) --}}
            <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 xl:gap-12 items-start">

                {{-- ========================================== --}}
                {{-- KOLOM KIRI (Col-span-4): FOTO PROFIL --}}
                {{-- ========================================== --}}
                <div class="w-full lg:col-span-4 lg:sticky lg:top-40 animate-fade-in">
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-8 flex flex-col items-center text-center relative overflow-hidden group">

                        {{-- Ornamen Background --}}
                        <div class="absolute top-0 inset-x-0 h-24 bg-gradient-to-b from-blue-50 to-white"></div>

                        <h3 class="text-xs font-black text-zinc-400 uppercase tracking-widest mb-6 relative z-10">Foto Profil</h3>

                        {{-- Avatar Upload Area --}}
                        <div class="relative z-10 w-40 h-40 rounded-full p-2 bg-white shadow-float border border-zinc-100 mb-6">
                            <img src="{{ asset('assets/uploads/avatars/' . ($user->profile_picture_url ?? 'person.png')) }}"
                                 id="preview-img"
                                 class="w-full h-full rounded-full object-cover"
                                 onerror="this.src='{{ asset('assets/uploads/avatars/person-icon-1680.png') }}'">

                            {{-- Invisible File Input --}}
                            <input type="file" name="foto" id="foto-input" class="hidden" accept="image/jpeg, image/png, image/jpg">

                            {{-- Hover Overlay Trigger --}}
                            <button type="button" onclick="document.getElementById('foto-input').click()" class="absolute inset-2 bg-black/60 rounded-full flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm cursor-pointer border-2 border-dashed border-white/50">
                                <i class="fas fa-camera text-2xl mb-1"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Unggah Baru</span>
                            </button>
                        </div>

                        <p class="text-xs text-zinc-500 leading-relaxed font-medium">
                            Format yang didukung: <strong class="text-zinc-800">JPG, JPEG, PNG</strong>.<br>
                            Ukuran file maksimal: <strong class="text-red-500">2 MB</strong>.
                        </p>

                        <button type="button" onclick="document.getElementById('foto-input').click()" class="mt-6 w-full bg-zinc-100 hover:bg-zinc-200 text-black font-bold py-3 rounded-xl transition-colors text-sm">
                            Pilih Gambar
                        </button>
                    </div>
                </div>

                {{-- ========================================== --}}
                {{-- KOLOM KANAN (Col-span-8): FORM INPUT --}}
                {{-- ========================================== --}}
                <div class="w-full lg:col-span-8 flex flex-col gap-8 animate-fade-in" style="animation-delay: 0.1s;">

                    {{-- CARD 1: INFORMASI DASAR --}}
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-6 sm:p-10 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>

                        <div class="mb-8 border-b border-zinc-100 pb-4">
                            <h2 class="text-xl font-black text-black">Informasi Dasar</h2>
                            <p class="text-sm text-zinc-500 mt-1">Data identitas utama untuk akun Anda.</p>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            {{-- Nama Lengkap --}}
                            <div class="relative group sm:col-span-2">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" value="{{ old('nama', $user->nama) }}" required class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- Nomor Telepon --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Nomor Handphone</label>
                                <input type="number" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}" placeholder="081234567890" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', empty($user->tanggal_lahir) ? '' : \Carbon\Carbon::parse($user->tanggal_lahir)->format('Y-m-d')) }}" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- Jenis Kelamin --}}
                            <div class="relative group sm:col-span-2">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Jenis Kelamin</label>
                                <div class="relative">
                                    <select name="jenis_kelamin" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Jenis Kelamin...</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none">
                                        <i class="fas fa-chevron-down text-zinc-400 text-xs"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: ALAMAT PENGIRIMAN --}}
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-6 sm:p-10 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-black"></div>

                        <div class="mb-8 border-b border-zinc-100 pb-4 flex justify-between items-end">
                            <div>
                                <h2 class="text-xl font-black text-black">Alamat Utama</h2>
                                <p class="text-sm text-zinc-500 mt-1">Lokasi default untuk penerimaan pesanan material Anda.</p>
                            </div>
                            <div class="hidden sm:flex w-12 h-12 bg-zinc-100 rounded-full items-center justify-center text-zinc-400">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                            {{-- Nama Penerima --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Nama Penerima</label>
                                <input type="text" name="nama_penerima" value="{{ old('nama_penerima', $alamatUtama->nama_penerima ?? '') }}" placeholder="Budi Santoso" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- No HP Penerima --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">No. Handphone Penerima</label>
                                <input type="number" name="telepon_penerima" value="{{ old('telepon_penerima', $alamatUtama->telepon_penerima ?? '') }}" placeholder="0812..." class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- Label Alamat & Kode Pos --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Label (Cth: Proyek / Rumah)</label>
                                <input type="text" name="label_alamat" value="{{ old('label_alamat', $alamatUtama->label_alamat ?? 'Rumah') }}" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Kode Pos</label>
                                <input type="number" name="kode_pos" value="{{ old('kode_pos', $alamatUtama->kode_pos ?? '') }}" placeholder="12345" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none">
                            </div>

                            {{-- Provinsi --}}
                            <div class="relative group sm:col-span-2">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Provinsi</label>
                                <div class="relative">
                                    <select name="province_id" id="province_id" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Provinsi...</option>
                                        @foreach($provinces as $prov)
                                            <option value="{{ $prov->id }}" {{ old('province_id', $alamatUtama->province_id ?? '') == $prov->id ? 'selected' : '' }}>
                                                {{ $prov->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><i class="fas fa-chevron-down text-zinc-400 text-xs"></i></div>
                                </div>
                            </div>

                            {{-- Kota --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Kota / Kabupaten</label>
                                <div class="relative">
                                    <select name="city_id" id="city_id" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Kota...</option>
                                        @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('city_id', $alamatUtama->city_id ?? '') == $city->id ? 'selected' : '' }}>
                                                {{ $city->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><i class="fas fa-chevron-down text-zinc-400 text-xs"></i></div>
                                </div>
                            </div>

                            {{-- Kecamatan --}}
                            <div class="relative group">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Kecamatan</label>
                                <div class="relative">
                                    <select name="district_id" id="district_id" class="w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none cursor-pointer">
                                        <option value="">Pilih Kecamatan...</option>
                                        @foreach($districts as $dist)
                                            <option value="{{ $dist->id }}" {{ old('district_id', $alamatUtama->district_id ?? '') == $dist->id ? 'selected' : '' }}>
                                                {{ $dist->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-0 pr-5 flex items-center pointer-events-none"><i class="fas fa-chevron-down text-zinc-400 text-xs"></i></div>
                                </div>
                            </div>

                            {{-- Alamat Lengkap --}}
                            <div class="relative group sm:col-span-2">
                                <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Alamat Lengkap (Jalan, RT/RW, Patokan)</label>
                                <textarea name="alamat_lengkap" rows="3" class="custom-scrollbar w-full bg-zinc-50 border-2 border-zinc-200 text-black text-sm font-semibold rounded-2xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-5 py-3.5 transition-all outline-none resize-none" placeholder="Masukkan detail jalan, nomor rumah, dan patokan...">{{ old('alamat_lengkap', $alamatUtama->alamat_lengkap ?? '') }}</textarea>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================== --}}
            {{-- FLOATING BOTTOM BAR (TOMBOL SIMPAN) --}}
            {{-- ========================================== --}}
            <div class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-zinc-200 p-4 sm:p-5 shadow-sticky-bottom z-40 transform transition-transform duration-300">
                <div class="max-w-[1100px] mx-auto flex items-center justify-between gap-4">
                    <div class="hidden sm:block">
                        <h4 class="font-bold text-black text-sm">Belum Disimpan</h4>
                        <p class="text-xs text-zinc-500">Pastikan semua data terisi dengan benar.</p>
                    </div>
                    <div class="flex items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('profil.index') }}" class="flex-1 sm:flex-none bg-zinc-100 hover:bg-zinc-200 text-black font-bold py-3.5 px-6 rounded-xl transition-colors text-center text-sm">
                            Batal
                        </a>
                        <button type="submit" id="btnSubmit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white font-black py-3.5 px-8 rounded-xl shadow-glow hover:-translate-y-1 transition-all duration-300 text-sm flex items-center justify-center gap-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </main>

    @include('partials.footer')

    {{-- SCRIPT INTERAKSI --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // 1. Image Preview & Visual Feedback
            const fotoInput = document.getElementById('foto-input');
            const previewImg = document.getElementById('preview-img');

            fotoInput.addEventListener('change', function(event) {
                const [file] = event.target.files;
                if (file) {
                    // Animasi pop out / pop in
                    previewImg.style.opacity = 0;
                    previewImg.style.transform = 'scale(0.9)';
                    setTimeout(() => {
                        previewImg.src = URL.createObjectURL(file);
                        previewImg.style.opacity = 1;
                        previewImg.style.transform = 'scale(1)';
                    }, 200);
                }
            });

            // 2. Submit Button Loading State
            const form = document.getElementById('editProfileForm');
            const btnSubmit = document.getElementById('btnSubmit');

            form.addEventListener('submit', function() {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');
                btnSubmit.classList.remove('hover:-translate-y-1', 'shadow-glow');
            });

            // 3. Dynamic Dropdown Wilayah (API Flow)
            const provSelect = document.getElementById('province_id');
            const citySelect = document.getElementById('city_id');
            const distSelect = document.getElementById('district_id');

            provSelect.addEventListener('change', function() {
                let provId = this.value;
                citySelect.innerHTML = '<option value="">Memuat...</option>';
                distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';

                if (provId) {
                    fetch(`/api/cities/${provId}`)
                    .then(res => res.json())
                    .then(data => {
                        citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
                        data.forEach(city => {
                            citySelect.innerHTML += `<option value="${city.id}">${city.name}</option>`;
                        });
                    });
                } else {
                    citySelect.innerHTML = '<option value="">Pilih Kota...</option>';
                }
            });

            citySelect.addEventListener('change', function() {
                let cityId = this.value;
                distSelect.innerHTML = '<option value="">Memuat...</option>';

                if (cityId) {
                    fetch(`/api/districts/${cityId}`)
                    .then(res => res.json())
                    .then(data => {
                        distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                        data.forEach(dist => {
                            distSelect.innerHTML += `<option value="${dist.id}">${dist.name}</option>`;
                        });
                    });
                } else {
                    distSelect.innerHTML = '<option value="">Pilih Kecamatan...</option>';
                }
            });

        });
    </script>
</body>
</html>
