@extends('layouts.seller')

@section('title', 'Profil Toko')

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    /* Animasi Upload Hover */
    .upload-hover:hover .upload-overlay { opacity: 1; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 pb-32">

    {{-- SETUP SWEETALERT TOAST --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            customClass: { popup: 'rounded-2xl shadow-lg border border-slate-100' }
        });
    </script>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.fire({icon: 'success', title: '{{ session('success') }}'}));</script>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <i class="mdi mdi-alert-circle text-xl mt-0.5"></i>
            <div>
                <h5 class="font-bold text-sm mb-1">Gagal Menyimpan!</h5>
                <ul class="list-disc list-inside text-xs font-medium space-y-0.5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
            <i class="mdi mdi-store-cog-outline text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Profil Toko</h1>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Atur identitas, logo, dan informasi kontak toko Anda agar terlihat profesional.</p>
        </div>
    </div>

    <form action="{{ route('seller.shop.profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- KOLOM KIRI: TAMPILAN VISUAL (Logo & Banner) --}}
            <div class="lg:col-span-4 space-y-6">

                {{-- LOGO TOKO --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-center">
                    <h3 class="text-sm font-black text-slate-800 mb-4 text-left"><i class="mdi mdi-image-outline text-blue-500 me-1"></i> Logo Toko</h3>

                    <div class="relative w-40 h-40 mx-auto rounded-full border-4 border-slate-50 shadow-md overflow-hidden group upload-hover cursor-pointer" onclick="document.getElementById('logoInput').click()">
                        @php $logoUrl = $toko->logo_toko ? asset('assets/uploads/shop/'.$toko->logo_toko) : 'https://placehold.co/200x200?text=Logo'; @endphp
                        <img id="logoPreview" src="{{ $logoUrl }}" class="w-full h-full object-cover" alt="Logo Toko">

                        {{-- Overlay Edit --}}
                        <div class="upload-overlay absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center text-white opacity-0 transition-opacity duration-300">
                            <i class="mdi mdi-camera-plus text-3xl mb-1"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Ubah Logo</span>
                        </div>
                    </div>
                    <input type="file" id="logoInput" name="logo_toko" class="hidden" accept="image/png, image/jpeg, image/webp" onchange="previewImage(this, 'logoPreview')">

                    <p class="text-xs font-medium text-slate-500 mt-4 leading-relaxed">
                        Format: JPG, PNG, WEBP.<br>Ukuran maksimal: 2MB.<br>Rekomendasi: 300x300 pixel.
                    </p>
                </div>

                {{-- BANNER TOKO --}}
                <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                    <h3 class="text-sm font-black text-slate-800 mb-4"><i class="mdi mdi-panorama-variant-outline text-indigo-500 me-1"></i> Banner Toko</h3>

                    <div class="relative w-full h-32 rounded-2xl border-2 border-dashed border-slate-300 overflow-hidden group upload-hover cursor-pointer bg-slate-50 flex flex-col items-center justify-center" onclick="document.getElementById('bannerInput').click()">
                        @if($toko->banner_toko)
                            <img id="bannerPreview" src="{{ asset('assets/uploads/shop/'.$toko->banner_toko) }}" class="absolute inset-0 w-full h-full object-cover z-0" alt="Banner">
                        @else
                            <img id="bannerPreview" src="" class="absolute inset-0 w-full h-full object-cover z-0 hidden" alt="Banner">
                            <div id="bannerPlaceholder" class="relative z-10 text-center text-slate-400 group-hover:text-indigo-500 transition-colors">
                                <i class="mdi mdi-cloud-upload-outline text-3xl block mb-1"></i>
                                <span class="text-xs font-bold">Upload Banner</span>
                            </div>
                        @endif

                        {{-- Overlay Edit --}}
                        <div class="upload-overlay absolute inset-0 bg-slate-900/50 flex flex-col items-center justify-center text-white opacity-0 transition-opacity duration-300 z-20">
                            <i class="mdi mdi-camera-retake text-2xl mb-1"></i>
                            <span class="text-[10px] font-bold uppercase tracking-widest">Ubah Banner</span>
                        </div>
                    </div>
                    <input type="file" id="bannerInput" name="banner_toko" class="hidden" accept="image/png, image/jpeg, image/webp" onchange="previewImage(this, 'bannerPreview', 'bannerPlaceholder')">

                    <p class="text-xs font-medium text-slate-500 mt-3 leading-relaxed text-center">
                        Maksimal 5MB. Rasio optimal 16:9 (Cth: 1200x400px).
                    </p>
                </div>

            </div>

            {{-- KOLOM KANAN: INFORMASI TEKS --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- IDENTITAS TOKO --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="mdi mdi-store-edit text-blue-600 text-lg leading-none"></i>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Identitas Toko</h3>
                    </div>
                    <div class="p-6 space-y-5">

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="text-sm font-bold text-slate-700">Nama Toko <span class="text-red-500">*</span></label>
                                <span class="text-[10px] font-bold text-slate-400" id="countNama">0/50</span>
                            </div>
                            <input type="text" name="nama_toko" id="inputNama" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all" value="{{ old('nama_toko', $toko->nama_toko) }}" maxlength="50" required>
                        </div>

                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <label class="text-sm font-bold text-slate-700">Slogan / Tagline</label>
                                <span class="text-[10px] font-bold text-slate-400" id="countSlogan">0/100</span>
                            </div>
                            <input type="text" name="slogan" id="inputSlogan" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-semibold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all" value="{{ old('slogan', $toko->slogan) }}" placeholder="Cth: Material Berkualitas, Harga Pantas" maxlength="100">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Toko</label>
                            <textarea name="deskripsi" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all min-h-[120px] resize-none" placeholder="Ceritakan tentang toko, spesialisasi material, atau keunggulan Anda...">{{ old('deskripsi', $toko->deskripsi) }}</textarea>
                        </div>

                    </div>
                </div>

                {{-- KONTAK & ALAMAT --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="bg-slate-50/80 px-6 py-4 border-b border-slate-100 flex items-center gap-2">
                        <i class="mdi mdi-map-marker-radius text-emerald-600 text-lg leading-none"></i>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Alamat Pengiriman</h3>
                    </div>
                    <div class="p-6 space-y-5">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Nomor WhatsApp/Telepon <span class="text-red-500">*</span></label>
                                <div class="flex border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-emerald-500 transition-all">
                                    <span class="bg-slate-100 px-4 py-3 text-slate-500 font-black border-r border-slate-200">+62</span>
                                    <input type="text" name="no_telepon" class="w-full bg-slate-50 px-4 py-3 text-sm font-bold outline-none" value="{{ old('no_telepon', ltrim($toko->no_telepon, '0')) }}" placeholder="8123456789" pattern="[0-9]+" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kota / Kabupaten <span class="text-red-500">*</span></label>
                                <input type="text" name="kota" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" value="{{ old('kota', $toko->kota) }}" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap Toko/Gudang <span class="text-red-500">*</span></label>
                            <textarea name="alamat_lengkap" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all min-h-[80px] resize-none" placeholder="Nama Jalan, RT/RW, Patokan..." required>{{ old('alamat_lengkap', $toko->alamat_lengkap) }}</textarea>
                            <p class="text-[10px] font-bold text-slate-400 mt-1">Alamat ini akan digunakan sebagai titik penjemputan oleh kurir ekspedisi.</p>
                        </div>

                        <div class="w-full md:w-1/2">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kode Pos <span class="text-red-500">*</span></label>
                            <input type="text" name="kode_pos" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition-all" value="{{ old('kode_pos', $toko->kode_pos) }}" pattern="[0-9]{5,6}" required>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- STICKY ACTION BAR BAWAH --}}
        <div class="fixed bottom-0 left-0 lg:left-[260px] right-0 bg-white/80 backdrop-blur-md border-t border-slate-200 px-6 py-4 flex items-center justify-between z-40 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
            <div class="hidden sm:block">
                <p class="text-xs font-bold text-slate-500 m-0"><i class="mdi mdi-information text-blue-500"></i> Pastikan perubahan sudah benar sebelum menyimpan.</p>
            </div>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="{{ route('seller.dashboard') }}" class="flex-1 sm:flex-none text-center px-6 py-2.5 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition-colors">Kembali</a>
                <button type="submit" id="btnSubmitProfile" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm shadow-blue-600/20 transition-all">
                    <i class="mdi mdi-content-save"></i> Simpan Profil
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // 1. LIVE PREVIEW IMAGE (Logo & Banner)
    function previewImage(input, previewId, placeholderId = null) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById(previewId);
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden'); // Jika sebelumnya hidden (kasus banner kosong)

                if(placeholderId) {
                    document.getElementById(placeholderId).classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // 2. CHARACTER COUNTER
    function initCounter(inputId, counterId, maxLen) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(counterId);

        const updateCount = () => {
            let len = input.value.length;
            counter.textContent = `${len}/${maxLen}`;
            if(len >= maxLen) counter.classList.replace('text-slate-400', 'text-red-500');
            else counter.classList.replace('text-red-500', 'text-slate-400');
        };

        input.addEventListener('input', updateCount);
        updateCount(); // Run on load
    }

    initCounter('inputNama', 'countNama', 50);
    initCounter('inputSlogan', 'countSlogan', 100);

    // 3. LOADING STATE SUBMIT
    document.getElementById('profileForm').addEventListener('submit', function() {
        const btn = document.getElementById('btnSubmitProfile');
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Menyimpan...';
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
    });
</script>
@endpush
