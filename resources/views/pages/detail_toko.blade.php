<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $toko->nama_toko }} - Official Store | Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        }
                    },
                    boxShadow: {
                        'card': '0 1px 6px 0 rgba(49,53,59,0.12)',
                        'card-hover': '0 4px 12px 0 rgba(49,53,59,0.2)',
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f5f5f5; }

        /* Hide scrollbar for horizontal tabs */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Pagination Override */
        .pagination-wrap nav { display: flex; justify-content: center; width: 100%; margin: 3rem 0; }
        .pagination-wrap .pagination { display: flex; gap: 0.25rem; background: white; padding: 0.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .pagination-wrap .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; border-radius: 0.25rem; font-weight: 600; color: #4b5563; padding: 0 0.5rem; transition: all 0.2s; }
        .pagination-wrap .page-item:not(.active) .page-link:hover { background: #f3f4f6; color: #111827; }
        .pagination-wrap .page-item.active .page-link { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body class="text-gray-800 antialiased pt-[70px] lg:pt-[80px]">

    {{-- Include Navbar --}}
    @include('partials.navbar')

    @php
        // PHP LOGIC UNTUK IDENTITAS TOKO
        $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
        $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
        $bgBanner = $hasBanner ? asset($bannerPath) : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=2000&auto=format&fit=crop';

        $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
        $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));

        $colors = ['#18181b', '#27272a', '#3f3f46', '#09090b', '#1e3a8a'];
        $storeColor = $colors[crc32($toko->nama_toko) % count($colors)];

        $words = explode(" ", $toko->nama_toko);
        $acronym = ""; foreach ($words as $w) { $acronym .= mb_substr($w, 0, 1); }
        $storeInitials = strtoupper(substr($acronym, 0, 2)) ?: "TK";
    @endphp

    <main class="max-w-[1250px] mx-auto px-0 sm:px-4 lg:px-8 py-0 sm:py-6">

        {{-- ======================================================= --}}
        {{-- 1. ETALASE HEADER (BANNER & PROFIL TOKO) --}}
        {{-- ======================================================= --}}
        <div class="bg-white sm:rounded-2xl shadow-card overflow-hidden border-b sm:border border-gray-200 relative z-10">

            {{-- Banner Super Lebar --}}
            <div class="w-full h-40 sm:h-56 lg:h-72 bg-gray-300 relative group">
                <img src="{{ $bgBanner }}" alt="Banner Toko" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                {{-- Tombol Share (Floating di Banner) --}}
                <button class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md border border-white/40 text-white flex items-center justify-center hover:bg-white hover:text-black transition-colors">
                    <i class="fas fa-share-alt"></i>
                </button>
            </div>

            {{-- Profil Panel --}}
            <div class="px-4 sm:px-8 pb-6 relative">

                <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-6">

                    {{-- KIRI: Avatar & Identitas --}}
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-4 md:gap-6 -mt-16 md:-mt-12 relative z-10 w-full md:w-auto">
                        {{-- Avatar Nembus Banner --}}
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg bg-white overflow-hidden shrink-0 relative">
                            @if($hasLogo)
                                <img src="{{ asset($logoPath) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-black text-white" style="background-color: {{ $storeColor }};">
                                    {{ $storeInitials }}
                                </div>
                            @endif
                            <div class="absolute bottom-1 right-3 w-4 h-4 bg-green-500 border-2 border-white rounded-full" title="Online"></div>
                        </div>

                        {{-- Teks Info --}}
                        <div class="text-center md:text-left pt-2">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                                <i class="fas fa-crown text-purple-600 text-lg"></i>
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $toko->nama_toko }}</h1>
                            </div>
                            <div class="text-sm font-semibold text-gray-500 flex items-center justify-center md:justify-start gap-3 mb-2">
                                <span class="flex items-center gap-1"><i class="fas fa-map-marker-alt text-brand-600"></i> {{ $toko->kota ?? 'Lokasi Nasional' }}</span>
                                <span>•</span>
                                <span class="text-green-600 flex items-center gap-1"><i class="fas fa-clock"></i> Aktif 2 menit lalu</span>
                            </div>
                            <p class="text-xs text-gray-400 font-medium">Buka: {{ $toko->jam_buka ?? '08:00' }} - {{ $toko->jam_tutup ?? '17:00' }}</p>
                        </div>
                    </div>

                    {{-- KANAN: Statistik & Aksi (Dashboard Style) --}}
                    <div class="flex flex-col items-center md:items-end w-full md:w-auto mt-4 md:mt-6 gap-4">

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button onclick="alert('Membuka chat dengan toko...')" class="flex-1 sm:flex-none bg-white border border-brand-600 text-brand-600 font-bold px-6 py-2.5 rounded-lg hover:bg-brand-50 transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-comment-dots"></i> Chat
                            </button>
                            <button class="flex-1 sm:flex-none bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-2.5 rounded-lg shadow-md transition-colors flex items-center justify-center gap-2">
                                <i class="fas fa-plus"></i> Ikuti
                            </button>
                        </div>

                        {{-- Stats Board --}}
                        <div class="flex items-center gap-6 text-center divide-x divide-gray-200 bg-gray-50 rounded-xl px-6 py-3 border border-gray-100">
                            <div class="px-2">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Rating</div>
                                <div class="text-base font-black text-gray-800 flex items-center justify-center gap-1">
                                    <i class="fas fa-star text-yellow-400 text-xs"></i> 4.9
                                </div>
                            </div>
                            <div class="px-4">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Pengikut</div>
                                <div class="text-base font-black text-gray-800">12.4RB</div>
                            </div>
                            <div class="px-2">
                                <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Produk</div>
                                <div class="text-base font-black text-brand-600">{{ $products->total() }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- STICKY TAB NAVIGATION --}}
            <div class="sticky top-[70px] lg:top-[80px] bg-white border-t border-gray-200 z-30 px-4 sm:px-8">
                <div class="flex overflow-x-auto no-scrollbar gap-8">
                    <a href="#" class="whitespace-nowrap py-4 border-b-2 border-brand-600 text-brand-600 font-bold text-sm">
                        Beranda Toko
                    </a>
                    <a href="#" class="whitespace-nowrap py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-900 font-semibold text-sm transition-colors">
                        Semua Produk
                    </a>
                    <a href="#" class="whitespace-nowrap py-4 border-b-2 border-transparent text-gray-500 hover:text-gray-900 font-semibold text-sm transition-colors">
                        Profil & Ulasan
                    </a>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- 2. MOCKUP FITUR DEKORASI TOKO (ENTERPRISE BANNER) --}}
        {{-- ======================================================= --}}
        <div class="w-full mt-6 mb-8 px-4 sm:px-0">
            <div class="w-full rounded-2xl overflow-hidden relative group cursor-pointer shadow-sm hover:shadow-lg transition-shadow">
                {{-- Ini area di mana Toko bisa upload banner promosi memanjang mereka --}}
                <img src="https://images.unsplash.com/photo-1587293852726-70cdb56c2866?q=80&w=2000&auto=format&fit=crop" alt="Promo Toko" class="w-full h-[200px] md:h-[300px] object-cover group-hover:scale-[1.02] transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 to-transparent flex items-center">
                    <div class="p-8 md:p-12 max-w-xl">
                        <span class="bg-red-500 text-white text-[10px] font-black px-2 py-1 rounded mb-3 inline-block tracking-widest uppercase">PROMO SPESIAL</span>
                        <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-2">MEGA DISKON <br>BAJA RINGAN</h2>
                        <p class="text-gray-300 font-medium text-sm md:text-base">Klaim voucher cashback hingga Rp500.000 khusus pembelian minggu ini.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- 3. KATALOG PRODUK (TOKOPEDIA 1:1 GRID) --}}
        {{-- ======================================================= --}}
        <div class="px-4 sm:px-0 mb-6 flex items-center justify-between">
            <h2 class="text-xl font-black text-gray-900">Etalase Produk</h2>
            <div class="flex items-center gap-2">
                <span class="text-sm font-semibold text-gray-600 hidden sm:block">Urutkan:</span>
                <select class="bg-white border border-gray-300 text-gray-700 py-1.5 px-3 rounded-lg text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-brand-500 cursor-pointer shadow-sm">
                    <option>Terbaru</option>
                    <option>Terlaris</option>
                    <option>Harga Terendah</option>
                </select>
            </div>
        </div>

        @if($products->count() > 0)
            <div class="px-4 sm:px-0 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4 relative z-0">

                @foreach($products as $p)
                    @php
                        $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                    @endphp
                    <a href="{{ route('produk.detail', $p->id) }}" class="bg-white rounded-lg shadow-card hover:shadow-card-hover transition-shadow duration-200 overflow-hidden flex flex-col group border border-transparent hover:border-brand-500 relative">

                        {{-- Image Container (1:1 Ratio Strict) --}}
                        <div class="w-full pt-[100%] relative bg-white border-b border-gray-100 overflow-hidden">
                            <img src="{{ asset($img) }}" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?q=80&w=400&auto=format&fit=crop'" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-in-out" alt="{{ $p->nama_barang }}">
                        </div>

                        {{-- Info Container --}}
                        <div class="p-2.5 sm:p-3 flex flex-col flex-1">
                            <h3 class="text-[13px] sm:text-sm font-normal text-gray-800 line-clamp-2 leading-[1.3] mb-1.5">{{ $p->nama_barang }}</h3>

                            <div class="mt-auto pt-1">
                                <div class="text-[15px] sm:text-[17px] font-bold text-gray-900 leading-none mb-1.5">Rp{{ number_format($p->harga, 0, ',', '.') }}</div>

                                {{-- Lokasi & Toko (Karena ini halaman toko, kita sembunyikan nama tokonya agar tidak redundan, ganti dengan label ketersediaan) --}}
                                <div class="flex items-center text-[11px] text-emerald-600 mt-1 font-bold">
                                    <i class="fas fa-check-circle mr-1"></i> Stok Tersedia
                                </div>

                                {{-- Rating & Terjual --}}
                                <div class="flex items-center text-[10px] sm:text-[11px] text-gray-500 mt-2 pt-2 border-t border-gray-100">
                                    <i class="fas fa-star text-yellow-400 mr-1 text-[10px]"></i>
                                    <span class="font-semibold text-gray-700 mr-1">4.9</span>
                                    <span class="mx-1 text-gray-300">|</span>
                                    <span>Terjual 100+</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach

            </div>

            {{-- Paginasi --}}
            <div class="pagination-wrap px-4 sm:px-0">
                {{ $products->links() }}
            </div>

        @else
            {{-- EMPTY STATE --}}
            <div class="px-4 sm:px-0">
                <div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <img src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/zeus/kratos/60454a86.png" alt="Empty" class="w-40 sm:w-48 mb-4 opacity-80 filter grayscale">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Etalase Masih Kosong</h3>
                    <p class="text-gray-500 text-sm text-center max-w-sm">Penjual ini belum menambahkan produk ke dalam etalasenya. Silakan kembali lagi nanti.</p>
                </div>
            </div>
        @endif

    </main>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>
