<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $toko->nama_toko }} - Official Store | Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'sans-serif'] },
                    colors: { brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' } },
                    boxShadow: { 'card': '0 1px 6px 0 rgba(49,53,59,0.12)', 'card-hover': '0 4px 12px 0 rgba(49,53,59,0.2)' }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f8; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .pagination-wrap nav { display: flex; justify-content: center; width: 100%; margin: 3rem 0; }
        .pagination-wrap .pagination { display: flex; gap: 0.25rem; background: white; padding: 0.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .pagination-wrap .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; border-radius: 0.25rem; font-weight: 600; color: #4b5563; padding: 0 0.5rem; transition: all 0.2s; }
        .pagination-wrap .page-item:not(.active) .page-link:hover { background: #f3f4f6; color: #111827; }
        .pagination-wrap .page-item.active .page-link { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body class="text-gray-800 antialiased pt-[70px] lg:pt-[80px]">

    @include('partials.navbar')

    @php
        // 1. Data Identitas Toko Dasar
        $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
        $bgBanner = (!empty($toko->banner_toko) && file_exists(public_path($bannerPath))) ? asset($bannerPath) : 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=2000&auto=format&fit=crop';
        
        $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
        $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
        
        $colors = ['#18181b', '#27272a', '#3f3f46', '#09090b', '#1e3a8a'];
        $storeColor = $colors[crc32($toko->nama_toko) % count($colors)];
        $acronym = ""; foreach (explode(" ", $toko->nama_toko) as $w) { $acronym .= mb_substr($w, 0, 1); }
        $storeInitials = strtoupper(substr($acronym, 0, 2)) ?: "TK";

        // 2. Decode Dekorasi JSON dari Database
        $dekorasi = !empty($toko->dekorasi_desktop) ? json_decode($toko->dekorasi_desktop) : null;
        
        // Cek apakah pakai header kustom dari Editor
        if ($dekorasi && isset($dekorasi->header) && $dekorasi->header !== 'Custom Image' && str_starts_with($dekorasi->header, 'bg-')) {
            $headerColorClass = $dekorasi->header;
            $useCustomBanner = false;
        } else {
            $headerColorClass = '';
            $useCustomBanner = true;
        }
    @endphp

    <main class="max-w-[1200px] mx-auto px-0 sm:px-4 lg:px-8 py-0 sm:py-6">

        {{-- ======================================================= --}}
        {{-- HEADER TOKO --}}
        {{-- ======================================================= --}}
        <div class="bg-white sm:rounded-2xl shadow-card overflow-hidden border-b sm:border border-gray-200 relative z-10">
            {{-- Area Banner Belakang --}}
            <div class="w-full h-40 sm:h-56 lg:h-[300px] relative group {{ $headerColorClass }}">
                @if($useCustomBanner)
                    <img src="{{ $bgBanner }}" alt="Banner Toko" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                @else
                    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                @endif
            </div>

            {{-- Profil Panel --}}
            <div class="px-4 sm:px-8 pb-6 relative">
                <div class="flex flex-col md:flex-row items-center md:items-start md:justify-between gap-6">
                    <div class="flex flex-col md:flex-row items-center md:items-end gap-4 md:gap-6 -mt-16 md:-mt-12 relative z-10 w-full md:w-auto">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full border-4 border-white shadow-lg bg-white overflow-hidden shrink-0 relative">
                            @if($hasLogo)
                                <img src="{{ asset($logoPath) }}" alt="Logo" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-4xl font-black text-white" style="background-color: {{ $storeColor }};">{{ $storeInitials }}</div>
                            @endif
                        </div>
                        <div class="text-center md:text-left pt-2">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                                <i class="fas fa-crown text-purple-600 text-lg"></i>
                                <h1 class="text-2xl font-black text-gray-900 tracking-tight">{{ $toko->nama_toko }}</h1>
                            </div>
                            <div class="text-sm font-semibold text-gray-500 flex items-center justify-center md:justify-start gap-3 mb-2">
                                <span><i class="fas fa-map-marker-alt text-brand-600"></i> {{ $toko->kota ?? 'Lokasi Nasional' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Aksi --}}
                    <div class="flex flex-col items-center md:items-end w-full md:w-auto mt-4 md:mt-6 gap-4">
                        <div class="flex items-center gap-3 w-full sm:w-auto">
                            <button class="flex-1 sm:flex-none bg-white border border-brand-600 text-brand-600 font-bold px-6 py-2.5 rounded-lg hover:bg-brand-50 transition-colors"><i class="fas fa-comment-dots"></i> Chat</button>
                            <button class="flex-1 sm:flex-none bg-brand-600 hover:bg-brand-700 text-white font-bold px-8 py-2.5 rounded-lg shadow-md transition-colors"><i class="fas fa-plus"></i> Ikuti</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STICKY TAB NAVIGATION --}}
            <div class="sticky top-[70px] lg:top-[80px] bg-white border-t border-gray-200 z-30 px-4 sm:px-8">
                <div class="flex overflow-x-auto no-scrollbar gap-8">
                    <a href="#" class="whitespace-nowrap py-4 border-b-[3px] border-brand-600 text-brand-600 font-black text-[15px]">Halaman Depan</a>
                    <a href="#" class="whitespace-nowrap py-4 border-b-[3px] border-transparent text-gray-500 hover:text-gray-900 font-bold text-[15px] transition-colors">Semua Produk</a>
                    <a href="#" class="whitespace-nowrap py-4 border-b-[3px] border-transparent text-gray-500 hover:text-gray-900 font-bold text-[15px] transition-colors">Profil Toko</a>
                </div>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- DYNAMIC ENGINE: RENDER DEKORASI TOKO --}}
        {{-- ======================================================= --}}
        @if($dekorasi && isset($dekorasi->layout) && count($dekorasi->layout) > 0)
            <div class="w-full flex flex-col gap-6 mt-6 mb-8 px-4 sm:px-0">
                @foreach($dekorasi->layout as $item)
                    @php $config = $item->config; @endphp

                    {{-- 1. RENDER BANNER --}}
                    @if($item->type === 'banner')
                        @php 
                            $aspectClass = 'aspect-[4/1]';
                            if(isset($config->ratio) && $config->ratio == '16:9') $aspectClass = 'aspect-video';
                            elseif(isset($config->ratio) && $config->ratio == '3:1') $aspectClass = 'aspect-[3/1]';
                        @endphp
                        <div class="w-full rounded-2xl overflow-hidden relative shadow-sm {{ $aspectClass }} bg-slate-900 flex items-center justify-center">
                            @if(!empty($config->images) && count($config->images) > 0)
                                {{-- Jika ada gambar, tampilkan gambar pertama (Bisa di-upgrade pakai library swiper.js nanti) --}}
                                <img src="{{ $config->images[0] }}" class="w-full h-full object-cover">
                            @else
                                <h2 class="text-3xl md:text-5xl font-black px-8 text-center italic drop-shadow-lg" style="color: {{ $config->textColor ?? '#ffffff' }}">{{ $config->title ?? '' }}</h2>
                            @endif
                        </div>

                    {{-- 2. RENDER GRID FOTO (CAROUSEL) --}}
                    @elseif($item->type === 'carousel')
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            @if(!empty($config->title))
                                <h4 class="text-lg font-black mb-5 border-l-4 border-brand-500 pl-3" style="color: {{ $config->textColor ?? '#1e293b' }}">{{ $config->title }}</h4>
                            @endif
                            @php $grid = $config->gridType ?? '5'; @endphp
                            <div class="grid grid-cols-2 md:grid-cols-{{ $grid }} gap-3">
                                @if(!empty($config->images))
                                    @foreach($config->images as $img)
                                        <div class="aspect-[4/3] rounded-xl overflow-hidden bg-gray-100">
                                            <img src="{{ $img }}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300">
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                    {{-- 3. RENDER VIDEO YOUTUBE --}}
                    @elseif($item->type === 'video')
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            @if(!empty($config->title))
                                <h4 class="text-lg font-black mb-5 border-l-4 border-brand-500 pl-3" style="color: {{ $config->textColor ?? '#1e293b' }}">{{ $config->title }}</h4>
                            @endif
                            <div class="w-full aspect-[21/9] bg-slate-900 rounded-xl overflow-hidden relative">
                                @php
                                    $ytUrl = '';
                                    if(isset($config->videoSource) && $config->videoSource == 'youtube' && !empty($config->videoUrl)) {
                                        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $config->videoUrl, $match);
                                        if(isset($match[1])) {
                                            $ytUrl = "https://www.youtube.com/embed/".$match[1]."?autoplay=1&mute=1&loop=1&playlist=".$match[1]."&controls=0&showinfo=0&rel=0";
                                        }
                                    }
                                @endphp
                                @if($ytUrl)
                                    <iframe src="{{ $ytUrl }}" class="w-full h-full border-0 pointer-events-none" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                @else
                                    <div class="absolute inset-0 flex items-center justify-center text-slate-500"><i class="fas fa-video-slash text-4xl"></i></div>
                                @endif
                            </div>
                        </div>

                    {{-- 4. RENDER MENU KATEGORI --}}
                    @elseif($item->type === 'kategori')
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            @if(!empty($config->title))
                                <h4 class="text-lg font-black mb-6 border-l-4 border-brand-500 pl-3" style="color: {{ $config->textColor ?? '#1e293b' }}">{{ $config->title }}</h4>
                            @endif
                            <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
                                {{-- Kategori Statis Sesuai Editor --}}
                                <div class="flex flex-col items-center gap-2 cursor-pointer group"><div class="w-16 h-16 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-all"><i class="fas fa-tshirt text-2xl"></i></div><span class="text-xs font-bold text-gray-600">Pakaian</span></div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer group"><div class="w-16 h-16 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center group-hover:bg-rose-500 group-hover:text-white transition-all"><i class="fas fa-shoe-prints text-2xl"></i></div><span class="text-xs font-bold text-gray-600">Sepatu</span></div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer group"><div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-all"><i class="fas fa-clock text-2xl"></i></div><span class="text-xs font-bold text-gray-600">Aksesoris</span></div>
                                <div class="flex flex-col items-center gap-2 cursor-pointer group"><div class="w-16 h-16 rounded-full bg-amber-50 text-amber-500 flex items-center justify-center group-hover:bg-amber-500 group-hover:text-white transition-all"><i class="fas fa-shopping-bag text-2xl"></i></div><span class="text-xs font-bold text-gray-600">Tas</span></div>
                            </div>
                        </div>

                    {{-- 5. RENDER PRODUK PILIHAN --}}
                    @elseif($item->type === 'produk')
                        <div class="bg-white py-6 px-4 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <h4 class="text-lg font-black border-l-4 border-brand-500 pl-3 uppercase" style="color: {{ $config->textColor ?? '#1e293b' }}">{{ $config->title ?? 'Etalase' }}</h4>
                                <a href="#" class="text-sm font-bold text-brand-600 hover:text-brand-800">Lihat Semua <i class="fas fa-chevron-right ml-1 text-xs"></i></a>
                            </div>

                            @php
                                $isAuto = ($config->productSource ?? 'auto') === 'auto';
                                $layoutStyle = $config->layout ?? 'horizontal';
                                // Jika Auto, ambil 10 dari database, jika manual, ambil dari JSON
                                $renderProducts = $isAuto ? $products->take(10) : ($config->selectedProducts ?? []);
                            @endphp

                            <div class="{{ $layoutStyle === 'horizontal' ? 'flex overflow-x-auto gap-4 pb-4 no-scrollbar snap-x' : 'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4' }}">
                                @foreach($renderProducts as $prod)
                                    @php
                                        // Handle objek dari DB maupun JSON
                                        $pId = is_object($prod) ? $prod->id : ($prod->id ?? '#');
                                        $pName = is_object($prod) && isset($prod->nama_barang) ? $prod->nama_barang : ($prod->name ?? 'Produk');
                                        $pPrice = is_object($prod) && isset($prod->harga) ? 'Rp'.number_format($prod->harga,0,',','.') : ($prod->price ?? '0');
                                        
                                        $pImg = 'https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=400';
                                        if(is_object($prod) && !empty($prod->gambar_utama)) $pImg = asset('assets/uploads/products/'.$prod->gambar_utama);
                                        elseif(is_object($prod) && isset($prod->img)) $pImg = $prod->img;
                                    @endphp

                                    <a href="{{ route('produk.detail', $pId) }}" class="{{ $layoutStyle === 'horizontal' ? 'snap-start min-w-[160px] sm:min-w-[180px] w-[160px] sm:w-[180px] flex-shrink-0' : 'w-full' }} bg-white rounded-lg shadow-card hover:shadow-card-hover transition-shadow duration-200 overflow-hidden flex flex-col group border border-transparent hover:border-brand-500">
                                        <div class="w-full pt-[100%] relative bg-gray-100 border-b border-gray-100 overflow-hidden">
                                            <img src="{{ $pImg }}" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                        </div>
                                        <div class="p-3 flex flex-col flex-1">
                                            <h3 class="text-xs sm:text-sm font-semibold text-gray-800 line-clamp-2 leading-[1.3] mb-1.5 group-hover:text-brand-600">{{ $pName }}</h3>
                                            <div class="mt-auto pt-1">
                                                <div class="text-[15px] font-black text-gray-900 mb-1.5">{{ $pPrice }}</div>
                                                <div class="flex items-center text-[10px] text-emerald-600 font-bold"><i class="fas fa-check-circle mr-1"></i> Stok Tersedia</div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                @endforeach
            </div>
        @endif

        {{-- ======================================================= --}}
        {{-- DAFTAR SEMUA PRODUK (DEFAULT BAWAAN TOKO) --}}
        {{-- ======================================================= --}}
        <div class="px-4 sm:px-0 mb-6 mt-8 flex items-center justify-between">
            <h2 class="text-xl font-black text-gray-900">Semua Produk</h2>
        </div>

        @if($products->count() > 0)
            <div class="px-4 sm:px-0 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-3 sm:gap-4">
                @foreach($products as $p)
                    @php $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg'; @endphp
                    <a href="{{ route('produk.detail', $p->id) }}" class="bg-white rounded-lg shadow-card hover:shadow-card-hover transition-shadow duration-200 overflow-hidden flex flex-col group border border-transparent hover:border-brand-500 relative">
                        <div class="w-full pt-[100%] relative bg-white border-b border-gray-100 overflow-hidden">
                            <img src="{{ asset($img) }}" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=400'" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <div class="p-3 flex flex-col flex-1">
                            <h3 class="text-[13px] sm:text-sm font-normal text-gray-800 line-clamp-2 leading-[1.3] mb-1.5">{{ $p->nama_barang }}</h3>
                            <div class="mt-auto pt-1">
                                <div class="text-[15px] sm:text-[17px] font-bold text-gray-900 leading-none mb-1.5">Rp{{ number_format($p->harga, 0, ',', '.') }}</div>
                                <div class="flex items-center text-[11px] text-emerald-600 mt-1 font-bold"><i class="fas fa-check-circle mr-1"></i> Stok Tersedia</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="pagination-wrap px-4 sm:px-0">{{ $products->links() }}</div>
        @else
            <div class="px-4 sm:px-0">
                <div class="flex flex-col items-center justify-center py-24 bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <img src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/zeus/kratos/60454a86.png" class="w-40 sm:w-48 mb-4 opacity-80 filter grayscale">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Etalase Masih Kosong</h3>
                    <p class="text-gray-500 text-sm text-center max-w-sm">Penjual ini belum menambahkan produk ke dalam etalasenya.</p>
                </div>
            </div>
        @endif

    </main>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>