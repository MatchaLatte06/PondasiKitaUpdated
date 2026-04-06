@extends('layouts.seller')

@section('title', isset($product) ? 'Kelola Produk' : 'Tambah Produk')

@push('styles')
<style>
    /* Menyembunyikan scrollbar agar tampilan HP Preview persis seperti HP asli */
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Animasi Upload Zone */
    .upload-zone-active { border-color: #3b82f6 !important; background-color: #eff6ff !important; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900">

    <form id="productForm" action="{{ isset($product) ? route('seller.products.update', $product->id) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        {{-- ERROR ALERT --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-2xl mb-6 shadow-sm">
                <div class="font-bold flex items-center gap-2 mb-2">
                    <i class="mdi mdi-alert-circle text-xl"></i> Gagal Menyimpan!
                </div>
                <ul class="list-disc list-inside text-sm ml-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm">
                    <i class="mdi mdi-package-variant-closed text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h1>
                    <div class="text-sm font-medium text-slate-500 flex items-center gap-2 mt-1">
                        <a href="{{ route('seller.dashboard') }}" class="hover:text-blue-600 transition-colors">Dashboard</a>
                        <i class="mdi mdi-chevron-right text-xs"></i>
                        <span>Katalog</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <a href="{{ route('seller.products.index') }}" class="flex-1 md:flex-none text-center px-6 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition-colors shadow-sm">Batal</a>
                <button type="submit" class="flex-1 md:flex-none flex items-center justify-center gap-2 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm shadow-blue-600/20 transition-all">
                    <i class="mdi mdi-check-circle-outline text-lg"></i> Simpan
                </button>
            </div>
        </div>

        {{-- MAIN GRID --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- ========================================== --}}
            {{-- KOLOM KIRI (FORM INPUT)                    --}}
            {{-- ========================================== --}}
            <div class="lg:col-span-8 space-y-6">

                {{-- 1. INFORMASI DASAR --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="p-1.5 bg-blue-50 text-blue-600 rounded-lg"><i class="mdi mdi-information-variant leading-none"></i></div>
                        <h2 class="font-bold text-slate-800">Informasi Dasar</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Produk <span class="text-red-500">*</span></label>
                            <input type="text" id="inputNama" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="Contoh: Semen Gresik 50kg" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                                <select name="kategori_id" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('kategori_id', $product->kategori_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Etalase Toko</label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 bg-slate-100 border border-r-0 border-slate-200 text-slate-500 rounded-l-xl"><i class="mdi mdi-storefront-outline"></i></span>
                                    <input type="text" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-r-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="Contoh: Promo Material">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. UPLOAD FOTO --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="p-1.5 bg-indigo-50 text-indigo-600 rounded-lg"><i class="mdi mdi-image-multiple leading-none"></i></div>
                        <h2 class="font-bold text-slate-800">Foto Produk</h2>
                    </div>
                    <div class="p-6">
                        <input type="file" id="realFileInput" name="gambar[]" multiple accept="image/*" class="hidden">

                        <div id="uploadZone" class="border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-colors p-10 text-center cursor-pointer group">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">
                                <i class="mdi mdi-cloud-upload-outline text-3xl text-blue-500"></i>
                            </div>
                            <h6 class="text-base font-black text-slate-800 mb-1">Klik atau Tarik Foto ke Sini</h6>
                            <p class="text-xs font-medium text-slate-500">Format: JPG, PNG (Maksimal 5 Foto. Foto pertama menjadi cover)</p>
                        </div>

                        {{-- Tempat Preview Gambar --}}
                        <div id="previewGrid" class="flex flex-wrap gap-4 mt-6 empty:mt-0"></div>
                    </div>
                </div>

                {{-- 3. HARGA & STOK --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="p-1.5 bg-emerald-50 text-emerald-600 rounded-lg"><i class="mdi mdi-cash-multiple leading-none"></i></div>
                        <h2 class="font-bold text-slate-800">Harga & Stok</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Harga Satuan <span class="text-red-500">*</span></label>
                                <div class="flex">
                                    <span class="inline-flex items-center px-4 bg-slate-100 border border-r-0 border-slate-200 text-slate-600 font-bold rounded-l-xl">Rp</span>
                                    <input type="text" id="viewHarga" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-r-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="0" required>
                                    <input type="hidden" name="harga" id="rawHarga" value="{{ old('harga', $product->harga ?? '') }}">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Stok Tersedia <span class="text-red-500">*</span></label>
                                <input type="number" name="stok" value="{{ old('stok', $product->stok ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="0" required>
                            </div>
                        </div>

                        {{-- Diskon --}}
                        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" id="checkDiskon" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="text-sm font-bold text-slate-800 group-hover:text-blue-600 transition-colors">Aktifkan Harga Coret / Diskon</span>
                            </label>

                            <div id="boxDiskon" class="hidden mt-4 pt-4 border-t border-slate-200">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <select id="typeDiskon" name="tipe_diskon" class="w-full sm:w-1/3 bg-white border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                        <option value="PERSEN">Diskon (%)</option>
                                        <option value="NOMINAL">Potongan (Rp)</option>
                                    </select>
                                    <input type="number" id="valDiskon" name="nilai_diskon" class="w-full sm:w-2/3 bg-white border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-500 outline-none placeholder-slate-400" placeholder="Masukkan nilai diskon...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. DETAIL & PENGIRIMAN --}}
                <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3 bg-slate-50/50">
                        <div class="p-1.5 bg-amber-50 text-amber-600 rounded-lg"><i class="mdi mdi-text-box-outline leading-none"></i></div>
                        <h2 class="font-bold text-slate-800">Detail Spesifikasi</h2>
                    </div>
                    <div class="p-6 space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Merek</label>
                                <input type="text" id="inputMerk" name="merk_barang" value="{{ old('merk_barang', $product->merk_barang ?? '') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="Tidak ada merek">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Kondisi</label>
                                <select id="inputKondisi" name="kondisi" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas">Bekas</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi Lengkap <span class="text-red-500">*</span></label>
                            <textarea id="inputDeskripsi" name="deskripsi" rows="6" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400 leading-relaxed" placeholder="Jelaskan spesifikasi, ukuran, dan kegunaan material ini..." required>{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Berat (Gram) <span class="text-red-500">*</span></label>
                            <div class="flex w-full md:w-1/2">
                                <input type="number" id="inputBerat" name="berat_gram" value="{{ isset($product->berat_kg) ? $product->berat_kg * 1000 : '' }}" class="w-full bg-slate-50 border border-slate-200 border-r-0 text-slate-900 text-sm rounded-l-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all placeholder-slate-400" placeholder="1000" required>
                                <span class="inline-flex items-center px-4 bg-slate-100 border border-slate-200 text-slate-500 text-sm font-bold rounded-r-xl">Gram</span>
                            </div>
                            <p class="text-xs text-slate-400 mt-2">Pastikan berat akurat untuk perhitungan ongkir otomatis.</p>
                            <input type="hidden" name="berat_kg" id="beratKgRaw">
                        </div>
                    </div>
                </div>

            </div>

            {{-- ========================================== --}}
            {{-- KOLOM KANAN (LIVE MOBILE PREVIEW)          --}}
            {{-- ========================================== --}}
            <div class="hidden lg:block lg:col-span-4 relative">
                <div class="sticky top-24 w-[320px] mx-auto">
                    <div class="text-center mb-3 text-xs font-black text-slate-400 uppercase tracking-widest flex items-center justify-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span> Live Preview
                    </div>

                    {{-- Mockup HP --}}
                    <div class="w-full h-[650px] bg-slate-50 border-[10px] border-slate-900 rounded-[40px] relative shadow-2xl overflow-hidden">
                        {{-- Notch / Kamera --}}
                        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-slate-900 rounded-b-2xl z-50"></div>

                        {{-- Layar HP --}}
                        <div class="w-full h-full overflow-y-auto hide-scrollbar pb-20 bg-slate-100 relative">

                            {{-- Navbar HP Mengambang --}}
                            <div class="absolute top-0 left-0 w-full p-4 flex justify-between items-center z-40">
                                <div class="w-8 h-8 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center text-white"><i class="mdi mdi-arrow-left"></i></div>
                                <div class="w-8 h-8 rounded-full bg-black/30 backdrop-blur-md flex items-center justify-center text-white"><i class="mdi mdi-cart-outline"></i></div>
                            </div>

                            {{-- Carousel Gambar --}}
                            <div class="relative w-full h-[320px] bg-slate-200">
                                <div id="hpCarousel" class="flex w-full h-full overflow-x-auto snap-x snap-mandatory hide-scrollbar">
                                    {{-- Placeholder Default --}}
                                    <img src="https://placehold.co/400x400/e2e8f0/94a3b8?text=Foto+Produk" class="min-w-full h-full object-cover snap-start">
                                </div>
                                <div id="hpIndicators" class="absolute bottom-3 right-3 bg-black/40 backdrop-blur-sm px-2.5 py-1.5 rounded-full flex gap-1.5 hidden">
                                    {{-- Titik indicator akan di-inject JS --}}
                                </div>
                            </div>

                            {{-- Info Harga & Judul --}}
                            <div class="bg-white p-4 mb-2">
                                <div class="text-xl font-black text-orange-500" id="hpPrice">Rp0</div>
                                <div id="hpDiskon" class="hidden mt-1 flex items-center gap-2">
                                    <span class="text-xs text-slate-400 line-through font-semibold" id="hpOldPrice">Rp0</span>
                                    <span class="text-[10px] font-black bg-red-100 text-red-600 px-1.5 py-0.5 rounded">DISKON</span>
                                </div>
                                <h1 class="text-sm font-bold text-slate-800 mt-2 leading-snug" id="hpTitle">Nama produk akan tampil di sini...</h1>
                            </div>

                            {{-- Info Toko --}}
                            <div class="bg-white p-3 mb-2 flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-200 border border-slate-300 flex items-center justify-center text-slate-600 font-black text-lg">
                                    {{ substr(Auth::user()->nama ?? 'T', 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-xs font-black text-slate-900">{{ Auth::user()->nama ?? 'Nama Toko' }}</div>
                                    <div class="text-[10px] font-bold text-emerald-500 flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Online
                                    </div>
                                </div>
                            </div>

                            {{-- Spesifikasi & Deskripsi --}}
                            <div class="bg-white p-4 min-h-[200px]">
                                <h3 class="text-xs font-black text-slate-900 mb-3 pb-2 border-b border-slate-100">Detail Produk</h3>
                                <div class="text-[11px] font-medium text-slate-500 space-y-1 mb-4" id="hpSpec">
                                    <div class="grid grid-cols-3"><span class="text-slate-400">Merek</span><span class="col-span-2 text-slate-800 font-bold">-</span></div>
                                    <div class="grid grid-cols-3"><span class="text-slate-400">Kondisi</span><span class="col-span-2 text-slate-800 font-bold">Baru</span></div>
                                    <div class="grid grid-cols-3"><span class="text-slate-400">Berat</span><span class="col-span-2 text-slate-800 font-bold">0 gr</span></div>
                                </div>
                                <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-line" id="hpDesc">Deskripsi lengkap produk Anda akan muncul di bagian ini.</div>
                            </div>

                        </div>

                        {{-- Footer Aksi Beli (Fixed Bottom) --}}
                        <div class="absolute bottom-0 left-0 w-full h-[60px] bg-white border-t border-slate-100 flex items-center px-3 gap-2 z-40">
                            <div class="w-10 h-10 flex items-center justify-center text-slate-500 border border-slate-200 rounded-lg"><i class="mdi mdi-chat-processing-outline text-lg"></i></div>
                            <div class="flex-1 h-10 flex items-center justify-center text-blue-600 border border-blue-600 rounded-lg text-xs font-bold bg-blue-50">+ Keranjang</div>
                            <div class="flex-1 h-10 flex items-center justify-center text-white bg-blue-600 rounded-lg text-xs font-bold">Beli Langsung</div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // FUNGSI FORMAT RUPIAH
    function formatRupiah(angka) {
        if(!angka) return '';
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }

    $(document).ready(function() {
        const fileInput = $('#realFileInput');
        const uploadZone = $('#uploadZone');
        const previewGrid = $('#previewGrid');

        // Element Carousel HP
        const hpCarousel = $('#hpCarousel');
        const hpIndicators = $('#hpIndicators');

        let dt = new DataTransfer();

        // 1. UPLOAD GAMBAR LOGIC
        uploadZone.on('click', () => fileInput.click());
        uploadZone.on('dragover', function(e) {
            e.preventDefault();
            $(this).addClass('upload-zone-active');
        });
        uploadZone.on('dragleave', function(e) {
            e.preventDefault();
            $(this).removeClass('upload-zone-active');
        });
        uploadZone.on('drop', function(e) {
            e.preventDefault();
            $(this).removeClass('upload-zone-active');
            let files = e.originalEvent.dataTransfer.files;
            handleFiles(files);
        });

        fileInput.on('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            for(let file of files) {
                if(dt.items.length < 5 && file.type.startsWith('image/')) {
                    dt.items.add(file);
                }
            }
            updateFiles();
        }

        function updateFiles() {
            fileInput[0].files = dt.files;
            previewGrid.empty();
            hpCarousel.empty();
            hpIndicators.empty();

            if (dt.files.length > 0) {
                if(dt.files.length > 1) {
                    hpIndicators.removeClass('hidden');
                } else {
                    hpIndicators.addClass('hidden');
                }

                Array.from(dt.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        // Grid Editor Form (Tailwind)
                        let badge = index === 0 ? '<div class="absolute bottom-0 left-0 w-full bg-blue-600/90 text-white text-[10px] font-bold text-center py-0.5">FOTO UTAMA</div>' : '';
                        let html = `
                            <div class="relative w-24 h-24 rounded-xl overflow-hidden border border-slate-200 group shadow-sm">
                                <img src="${e.target.result}" class="w-full h-full object-cover">
                                ${badge}
                                <div class="absolute top-1.5 right-1.5 w-6 h-6 bg-white/90 hover:bg-red-50 text-red-500 rounded-full flex items-center justify-center cursor-pointer shadow-sm opacity-0 group-hover:opacity-100 transition-opacity btn-del" data-idx="${index}">
                                    <i class="mdi mdi-close text-sm"></i>
                                </div>
                            </div>
                        `;
                        previewGrid.append(html);

                        // Carousel HP
                        let slide = `<img src="${e.target.result}" class="min-w-full h-full object-cover snap-start">`;
                        hpCarousel.append(slide);

                        // Dot Indicator
                        let activeClass = index === 0 ? 'bg-white' : 'bg-white/40';
                        let dot = `<div class="w-1.5 h-1.5 rounded-full transition-colors ${activeClass}"></div>`;
                        hpIndicators.append(dot);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                // Gambar Default
                hpCarousel.html('<img src="https://placehold.co/400x400/e2e8f0/94a3b8?text=Foto+Produk" class="min-w-full h-full object-cover snap-start">');
                hpIndicators.addClass('hidden');
            }
        }

        // Hapus Gambar
        $(document).on('click', '.btn-del', function(e) {
            e.stopPropagation();
            let idx = $(this).data('idx');
            dt.items.remove(idx);
            updateFiles();
        });

        // 2. LIVE TEXT PREVIEW LOGIC
        $('#inputNama').on('input', function() { $('#hpTitle').text($(this).val() || 'Nama produk akan tampil di sini...'); });
        $('#inputDeskripsi').on('input', function() { $('#hpDesc').text($(this).val() || 'Deskripsi lengkap produk Anda akan muncul di bagian ini.'); });

        function updateSpecs() {
            let m = $('#inputMerk').val() || '-';
            let k = $('#inputKondisi').val();
            let b = $('#inputBerat').val() || '0';

            let specHtml = `
                <div class="grid grid-cols-3"><span class="text-slate-400">Merek</span><span class="col-span-2 text-slate-800 font-bold">${m}</span></div>
                <div class="grid grid-cols-3"><span class="text-slate-400">Kondisi</span><span class="col-span-2 text-slate-800 font-bold">${k}</span></div>
                <div class="grid grid-cols-3"><span class="text-slate-400">Berat</span><span class="col-span-2 text-slate-800 font-bold">${b} gr</span></div>
            `;
            $('#hpSpec').html(specHtml);

            // Konversi gram ke Kg untuk database
            $('#beratKgRaw').val(parseFloat(b)/1000);
        }
        $('#inputMerk, #inputKondisi, #inputBerat').on('input change', updateSpecs);

        // 3. LOGIKA HARGA & DISKON
        const viewHarga = $('#viewHarga');
        const rawHarga = $('#rawHarga');

        viewHarga.on('keyup', function() {
            let val = $(this).val().replace(/[^0-9]/g, '');
            rawHarga.val(val);
            $(this).val(formatRupiah(val).replace('Rp ', ''));
            calcPrice();
        });

        $('#checkDiskon').on('change', function() {
            // Gunakan jQuery slide untuk animasi expand/collapse di Tailwind
            if(this.checked) {
                $('#boxDiskon').slideDown(200);
            } else {
                $('#boxDiskon').slideUp(200);
            }
            calcPrice();
        });

        $('#typeDiskon, #valDiskon').on('input change', calcPrice);

        function calcPrice() {
            let price = parseFloat(rawHarga.val()) || 0;
            let final = price;
            let active = $('#checkDiskon').is(':checked');
            let type = $('#typeDiskon').val();
            let val = parseFloat($('#valDiskon').val()) || 0;

            if(active && val > 0) {
                $('#hpDiskon').removeClass('hidden');
                $('#hpOldPrice').text(formatRupiah(price));

                if(type === 'PERSEN') {
                    final = price - (price * (val/100));
                } else {
                    final = price - val;
                }
            } else {
                $('#hpDiskon').addClass('hidden');
            }

            if(final < 0) final = 0;
            $('#hpPrice').text(formatRupiah(final));
        }

        // Trigger inisial jika ada old value (saat mode edit)
        if(rawHarga.val()) viewHarga.trigger('keyup');
    });
</script>
@endpush
