<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout Aman - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
                        'sticky': '0 -10px 40px rgba(0,0,0,0.08)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: 0, transform: 'translateY(15px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }

        /* Remove Number Input Arrows */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

        /* Smooth Hide/Show for Manual Form */
        .manual-form-wrapper {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows 0.4s ease-out;
        }
        .manual-form-wrapper.active {
            grid-template-rows: 1fr;
        }
        .manual-form-inner { overflow: hidden; }

        /* Card Address Active State */
        .address-card.selected { border-color: #2563eb; background-color: #eff6ff; }
        .address-card.selected .check-icon { opacity: 1; transform: scale(1); }
        .address-card .check-icon { opacity: 0; transform: scale(0.5); transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px] pb-32 lg:pb-12">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- BREADCRUMB MINIMALIS --}}
    <div class="bg-white border-b border-zinc-200 hidden md:block relative z-10 shadow-sm">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-3">
            <nav class="flex text-xs font-semibold text-zinc-500 items-center gap-3">
                <a href="{{ route('keranjang.index') }}" class="hover:text-black transition-colors flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i> Kembali ke Keranjang
                </a>
                <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                <span class="text-zinc-900 font-bold"><i class="fas fa-lock text-emerald-500 mr-1"></i> Checkout Aman</span>
            </nav>
        </div>
    </div>

    {{-- MAIN FORM --}}
    <form id="checkout-form">
        @csrf
        {{-- Hidden Data --}}
        <input type="hidden" name="user_email" value="{{ $userEmail }}">
        <input type="hidden" name="total_produk_subtotal" value="{{ $totalProduk }}">
        <input type="hidden" name="grand_total" id="input_grand_total" value="{{ $totalProduk }}">

        <input type="hidden" name="shipping_label_alamat" id="final_label">
        <input type="hidden" name="shipping_nama_penerima" id="final_nama">
        <input type="hidden" name="shipping_telepon_penerima" id="final_telepon">
        <input type="hidden" name="shipping_alamat_lengkap" id="final_alamat">
        <input type="hidden" name="shipping_kecamatan" id="final_kecamatan">
        <input type="hidden" name="shipping_kota_kabupaten" id="final_kota">
        <input type="hidden" name="shipping_provinsi" id="final_provinsi">
        <input type="hidden" name="shipping_kode_pos" id="final_kodepos">

        @if($isDirectPurchase)
            <input type="hidden" name="direct_purchase" value="1">
            <input type="hidden" name="product_id" value="{{ request('product_id') }}">
            <input type="hidden" name="jumlah" value="{{ request('jumlah') }}">
        @else
            @php
                $rawItems = request('selected_items', '');
                $itemArray = is_string($rawItems) && $rawItems !== '' ? explode(',', $rawItems) : (is_array($rawItems) ? $rawItems : []);
            @endphp
            @foreach($itemArray as $itemId)
                <input type="hidden" name="selected_items[]" value="{{ trim($itemId) }}">
            @endforeach
        @endif

        <main class="max-w-[1200px] mx-auto px-4 sm:px-6 py-6 lg:py-10">

            <div class="mb-8">
                <h1 class="text-3xl font-black text-black tracking-tight flex items-center gap-3">
                    Konfirmasi Pesanan
                </h1>
                <p class="text-sm font-medium text-zinc-500 mt-1">Periksa kembali rincian pengiriman sebelum membuat pesanan.</p>
            </div>

            <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 xl:gap-10 items-start">

                {{-- KOLOM KIRI --}}
                <div class="w-full lg:col-span-8 flex flex-col gap-8 animate-fade-in">

                    {{-- 1. KARTU ALAMAT --}}
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-6 sm:p-8 relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
                        <h2 class="text-xl font-black text-black mb-6">1. Alamat Tujuan</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label id="card-saved" class="address-card selected relative flex flex-col p-5 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all duration-300 group">
                                <input type="radio" name="address_type" value="saved" checked class="peer sr-only">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center gap-2 text-zinc-900 font-bold">
                                        <i class="fas fa-home text-blue-500 bg-blue-50 p-2 rounded-lg"></i> Alamat Profil
                                    </div>
                                    <i class="fas fa-check-circle text-blue-600 text-xl check-icon"></i>
                                </div>

                                @if($alamatUser && !$isAlamatIncomplete)
                                    <div class="text-sm text-zinc-600 space-y-1">
                                        <p class="font-bold text-black">{{ $alamatUser->nama_penerima }} <span class="text-zinc-400 font-medium">({{ $alamatUser->telepon_penerima }})</span></p>
                                        <p class="line-clamp-2">{{ $alamatUser->alamat_lengkap }}</p>
                                        <p class="font-medium">{{ $alamatUser->district_name }}, {{ $alamatUser->city_name }}</p>
                                    </div>
                                @else
                                    <div class="text-sm text-red-500 bg-red-50 p-3 rounded-xl mt-2 flex items-start gap-2">
                                        <i class="fas fa-exclamation-triangle mt-0.5"></i>
                                        <span>Data alamat profil Anda belum lengkap. Silakan pilih alamat manual.</span>
                                    </div>
                                @endif
                            </label>

                            <label id="card-manual" class="address-card relative flex flex-col p-5 border-2 border-zinc-200 rounded-2xl cursor-pointer transition-all duration-300 group hover:border-blue-300 hover:bg-zinc-50">
                                <input type="radio" name="address_type" value="manual" class="peer sr-only">
                                <div class="flex items-start justify-between mb-2">
                                    <div class="flex items-center gap-2 text-zinc-900 font-bold">
                                        <i class="fas fa-map-marker-alt text-zinc-500 bg-zinc-100 p-2 rounded-lg group-hover:text-blue-500 group-hover:bg-blue-50 transition-colors"></i> Kirim ke Alamat Lain
                                    </div>
                                    <i class="fas fa-check-circle text-blue-600 text-xl check-icon"></i>
                                </div>
                                <p class="text-xs text-zinc-500 font-medium mt-1">Masukkan alamat spesifik untuk pengiriman ini.</p>
                            </label>
                        </div>

                        <div id="manual-address-form" class="manual-form-wrapper mt-4">
                            <div class="manual-form-inner bg-zinc-50 border border-zinc-200 rounded-2xl p-5 sm:p-6">
                                <h4 class="text-sm font-black text-zinc-400 uppercase tracking-widest mb-4">Form Alamat Baru</h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Nama Penerima</label>
                                        <input type="text" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_nama" placeholder="Budi Santoso">
                                    </div>
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">No. Telepon Aktif</label>
                                        <input type="number" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_telepon" placeholder="081234567890">
                                    </div>
                                </div>
                                <div class="relative group mb-5">
                                    <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Alamat Lengkap</label>
                                    <textarea class="manual-input custom-scrollbar w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none resize-none" id="manual_alamat" rows="2" placeholder="Nama Jalan, RT/RW..."></textarea>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Kecamatan</label>
                                        <input type="text" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_kecamatan">
                                    </div>
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Kota / Kabupaten</label>
                                        <input type="text" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_kota">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Provinsi</label>
                                        <input type="text" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_provinsi">
                                    </div>
                                    <div class="relative group">
                                        <label class="block text-[10px] font-black text-zinc-500 uppercase tracking-widest mb-1.5 ml-1">Kode Pos</label>
                                        <input type="number" class="manual-input w-full bg-white border border-zinc-300 text-black text-sm font-semibold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" id="manual_kodepos">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. KARTU DAFTAR PRODUK --}}
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-6 sm:p-8 overflow-hidden">
                        <div class="flex items-center justify-between mb-6 pb-4 border-b border-zinc-100">
                            <h2 class="text-xl font-black text-black">2. Detail Pesanan</h2>
                            <span class="bg-zinc-100 text-zinc-600 px-3 py-1 rounded-full text-xs font-bold">{{ count($itemsPerToko) }} Toko</span>
                        </div>

                        <div class="mb-8">
                            <label class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1">Metode Pengiriman Global</label>
                            <div class="relative max-w-sm">
                                <select name="tipe_pengambilan" id="tipe_pengambilan" class="w-full bg-blue-50 border border-blue-200 text-blue-700 text-sm font-bold rounded-xl focus:border-blue-600 focus:ring-4 focus:ring-blue-600/20 px-4 py-3.5 transition-all outline-none cursor-pointer appearance-none">
                                    <option value="pengiriman">Diantar Kurir ke Alamat</option>
                                    <option value="ambil_di_toko">Ambil Sendiri di Toko Fisik</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none"><i class="fas fa-chevron-down text-blue-500"></i></div>
                            </div>
                        </div>

                        <div class="space-y-8">
                            @foreach($itemsPerToko as $tokoId => $toko)
                                <div class="bg-zinc-50 border border-zinc-200 rounded-2xl overflow-hidden">
                                    <div class="bg-zinc-100 border-b border-zinc-200 px-5 py-3 flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-store text-emerald-600 bg-white p-1.5 rounded-md shadow-sm text-xs"></i>
                                            <h4 class="font-black text-sm text-zinc-900">{{ $toko['nama_toko'] }}</h4>
                                        </div>
                                        <span class="text-[10px] font-bold text-zinc-500 bg-white px-2 py-1 rounded-md border border-zinc-200">{{ $toko['kota_toko'] }}</span>
                                    </div>

                                    <div class="p-5 flex flex-col gap-4">
                                        @foreach($toko['items'] as $item)
                                            @php $subtotal = $item->harga * $item->jumlah; @endphp
                                            <div class="flex gap-4">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl bg-white border border-zinc-200 overflow-hidden shrink-0">
                                                    <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" class="w-full h-full object-cover mix-blend-multiply" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                                </div>
                                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                                    <h5 class="text-sm font-bold text-zinc-800 line-clamp-1 mb-1">{{ $item->nama_barang }}</h5>
                                                    <p class="text-xs font-semibold text-zinc-500 mb-2">{{ $item->jumlah }} x <span class="text-zinc-700">Rp{{ number_format($item->harga, 0, ',', '.') }}</span></p>
                                                    <div class="text-sm font-black text-black">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="px-5 pb-5 shipping-box-wrapper" id="shipping-box-{{ $tokoId }}">
                                        <div class="bg-white border border-blue-100 rounded-xl p-4">
                                            <label class="block text-[10px] font-black text-blue-600 uppercase tracking-widest mb-2">Pilih Kurir Toko Ini</label>
                                            <div class="relative">
                                                <select name="shipping[{{ $tokoId }}]" class="shipping-select w-full bg-transparent border-b-2 border-zinc-200 text-black text-sm font-bold pb-2 focus:border-blue-600 transition-all outline-none cursor-pointer appearance-none">
                                                    <option value="reguler_15000">Reguler (2-3 Hari) — Rp 15.000</option>
                                                    <option value="kargo_30000">Kargo Truk (Material Berat) — Rp 30.000</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-0 pb-2 flex items-center pointer-events-none"><i class="fas fa-chevron-down text-zinc-400 text-xs"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 border-t border-zinc-100 pt-6">
                            <label class="block text-[11px] font-black text-zinc-400 uppercase tracking-widest mb-2 ml-1"><i class="fas fa-comment-alt mr-1"></i> Catatan Untuk Penjual</label>
                            <input type="text" name="catatan" class="w-full bg-zinc-50 border border-zinc-200 text-black text-sm font-medium rounded-xl focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 px-4 py-3 transition-all outline-none" placeholder="Tinggalkan instruksi khusus pengiriman...">
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="w-full lg:col-span-4 lg:sticky lg:top-28 z-20 animate-fade-in" style="animation-delay: 0.1s;">
                    <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 overflow-hidden">
                        <div class="bg-emerald-50 border-b border-emerald-100 px-6 py-3 flex items-center justify-center gap-2">
                            <i class="fas fa-shield-alt text-emerald-600 text-sm"></i>
                            <span class="text-xs font-bold text-emerald-700 tracking-wide">Checkout Aman Terenkripsi 256-bit</span>
                        </div>

                        <div class="p-6 sm:p-8">
                            <h3 class="text-lg font-black text-black mb-6">Ringkasan Pembayaran</h3>
                            <div class="space-y-4 text-sm border-b border-dashed border-zinc-200 pb-6 mb-6">
                                <div class="flex justify-between items-center text-zinc-500 font-medium">
                                    <span>Total Harga Barang</span>
                                    <span class="font-bold text-black">Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-zinc-500 font-medium">
                                    <span>Total Ongkos Kirim</span>
                                    <span id="shipping-total-display" class="font-bold text-black">Rp0</span>
                                </div>
                                <div class="flex justify-between items-center text-zinc-500 font-medium">
                                    <span>Biaya Layanan & Asuransi</span>
                                    <span class="font-bold text-emerald-500">Gratis</span>
                                </div>
                            </div>

                            <div class="flex justify-between items-end mb-8">
                                <span class="text-xs font-black text-zinc-400 uppercase tracking-widest">Total Tagihan</span>
                                <span id="grand-total-display" class="text-3xl font-black text-black tracking-tight leading-none text-right">
                                    Rp{{ number_format($totalProduk, 0, ',', '.') }}
                                </span>
                            </div>

                            <button type="submit" id="btn-submit-desktop" class="hidden lg:flex w-full bg-black hover:bg-blue-600 text-white font-black py-4 rounded-2xl transition-all duration-300 shadow-[0_4px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.3)] hover:-translate-y-1 items-center justify-center gap-2">
                                <i class="fas fa-file-invoice text-sm"></i> Buat Pesanan Sekarang
                            </button>

                            <p class="text-[10px] text-zinc-400 text-center mt-4 font-medium leading-relaxed hidden lg:block">
                                Dengan memproses pesanan, Anda menyetujui Syarat & Ketentuan Pondasikita.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        {{-- MOBILE STICKY --}}
        <div class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-zinc-200 p-4 pb-safe shadow-sticky z-50 lg:hidden flex items-center justify-between gap-4">
            <div class="flex flex-col flex-1 min-w-0">
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total Pembayaran</span>
                <span id="mobile-grand-total" class="text-xl font-black text-black truncate">Rp0</span>
            </div>
            <button type="submit" id="btn-submit-mobile" class="w-auto px-8 bg-black text-white font-black py-3.5 rounded-xl active:scale-95 transition-transform flex items-center justify-center gap-2 text-sm shadow-lg">
                <i class="fas fa-check text-xs"></i> Pesan
            </button>
        </div>
    </form>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const savedAddress = @json($addressData);
            const isProfileIncomplete = @json($isAlamatIncomplete);
            const totalProduk = {{ $totalProduk }};

            const radioAddress = document.querySelectorAll('input[name="address_type"]');
            const cardSaved = document.getElementById('card-saved');
            const cardManual = document.getElementById('card-manual');
            const manualFormDiv = document.getElementById('manual-address-form');
            const manualInputs = document.querySelectorAll('.manual-input');

            const btnSubmitDesktop = document.getElementById('btn-submit-desktop');
            const btnSubmitMobile = document.getElementById('btn-submit-mobile');

            const final = {
                label: document.getElementById('final_label'), nama: document.getElementById('final_nama'),
                telepon: document.getElementById('final_telepon'), alamat: document.getElementById('final_alamat'),
                kecamatan: document.getElementById('final_kecamatan'), kota: document.getElementById('final_kota'),
                provinsi: document.getElementById('final_provinsi'), kodepos: document.getElementById('final_kodepos')
            };

            function updateAddressUI() {
                const selected = document.querySelector('input[name="address_type"]:checked').value;
                if (selected === 'saved') {
                    cardSaved.classList.add('selected'); cardManual.classList.remove('selected');
                    manualFormDiv.classList.remove('active');
                    if (savedAddress) {
                        final.label.value = savedAddress.label; final.nama.value = savedAddress.nama;
                        final.telepon.value = savedAddress.telepon; final.alamat.value = savedAddress.alamat;
                        final.kecamatan.value = savedAddress.kecamatan; final.kota.value = savedAddress.kota;
                        final.provinsi.value = savedAddress.provinsi; final.kodepos.value = savedAddress.kodepos;
                    }
                    if (isProfileIncomplete) {
                        [btnSubmitDesktop, btnSubmitMobile].forEach(btn => {
                            if(btn) { btn.disabled = true; btn.innerText = 'Pilih Alamat Manual'; }
                        });
                    } else {
                        [btnSubmitDesktop, btnSubmitMobile].forEach(btn => { if(btn) btn.disabled = false; });
                    }
                } else {
                    cardSaved.classList.remove('selected'); cardManual.classList.add('selected');
                    manualFormDiv.classList.add('active');
                    final.label.value = "Alamat Baru Manual";
                    syncManualToHidden();
                    [btnSubmitDesktop, btnSubmitMobile].forEach(btn => { if(btn) btn.disabled = false; });
                }
            }

            function syncManualToHidden() {
                if (document.querySelector('input[name="address_type"]:checked').value !== 'manual') return;
                final.nama.value = document.getElementById('manual_nama').value;
                final.telepon.value = document.getElementById('manual_telepon').value;
                final.alamat.value = document.getElementById('manual_alamat').value;
                final.kecamatan.value = document.getElementById('manual_kecamatan').value;
                final.kota.value = document.getElementById('manual_kota').value;
                final.provinsi.value = document.getElementById('manual_provinsi').value;
                final.kodepos.value = document.getElementById('manual_kodepos').value;
            }

            radioAddress.forEach(radio => radio.addEventListener('change', updateAddressUI));
            manualInputs.forEach(input => input.addEventListener('input', syncManualToHidden));
            updateAddressUI();

            const shippingSelects = document.querySelectorAll('.shipping-select');
            const tipePengambilan = document.getElementById('tipe_pengambilan');

            function calculateTotal() {
                let shippingCost = 0;
                if (tipePengambilan.value === 'pengiriman') {
                    document.querySelectorAll('.shipping-box-wrapper').forEach(el => el.style.display = 'block');
                    shippingSelects.forEach(sel => {
                        let valParts = sel.value.split('_');
                        if (valParts.length > 1) shippingCost += parseInt(valParts[1]);
                    });
                } else {
                    document.querySelectorAll('.shipping-box-wrapper').forEach(el => el.style.display = 'none');
                    shippingCost = 0;
                }

                let grandTotal = totalProduk + shippingCost;
                document.getElementById('shipping-total-display').innerText = 'Rp' + shippingCost.toLocaleString('id-ID');
                document.getElementById('grand-total-display').innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
                if(document.getElementById('mobile-grand-total')) document.getElementById('mobile-grand-total').innerText = 'Rp' + grandTotal.toLocaleString('id-ID');
                document.getElementById('input_grand_total').value = grandTotal;
            }

            tipePengambilan.addEventListener('change', calculateTotal);
            shippingSelects.forEach(sel => sel.addEventListener('change', calculateTotal));
            calculateTotal();

            // AJAX SUBMIT & REDIRECT
            document.getElementById('checkout-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                if (document.querySelector('input[name="address_type"]:checked').value === 'manual') {
                    if (!final.nama.value || !final.telepon.value || !final.alamat.value) {
                        Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Mohon isi Nama, No. Telepon, dan Alamat Lengkap.' });
                        return;
                    }
                }

                const originalDesktopText = btnSubmitDesktop.innerHTML;
                [btnSubmitDesktop, btnSubmitMobile].forEach(btn => {
                    if(btn) { btn.disabled = true; btn.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Memproses...'; }
                });

                try {
                    const formData = new FormData(this);
                    const response = await fetch("{{ route('checkout.process') }}", {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: formData
                    });

                    const result = await response.json();

                    if (result.status === 'success') {
                        // REDIRECT KE DETAIL PESANAN
                        const invoiceUrl = "{{ url('/pesanan') }}/" + result.kode_invoice; 
                        Swal.fire({
                            icon: 'success',
                            title: 'Pesanan Dibuat!',
                            text: 'Mengarahkan ke halaman rincian pembayaran...',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => { window.location.href = invoiceUrl; });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: result.message });
                        restoreButtons();
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({ icon: 'error', title: 'Koneksi Terputus', text: 'Coba lagi nanti.' });
                    restoreButtons();
                }

                function restoreButtons() {
                    btnSubmitDesktop.innerHTML = originalDesktopText;
                    btnSubmitDesktop.disabled = false;
                    if(btnSubmitMobile) { btnSubmitMobile.innerHTML = 'Pesan'; btnSubmitMobile.disabled = false; }
                }
            });
        });
    </script>
</body>
</html>