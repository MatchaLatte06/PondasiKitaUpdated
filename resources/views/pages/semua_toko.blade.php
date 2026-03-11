<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Direktori Mitra Toko - Pondasikita</title>
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
                        'card': '0 4px 20px -2px rgba(0,0,0,0.05)',
                        'card-hover': '0 20px 40px -4px rgba(37,99,235,0.15)',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }

        /* Hilangkan panah default pada select option */
        select.custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        /* Pagination Override Laravel */
        .pagination-wrap nav { display: flex; justify-content: center; width: 100%; margin-top: 3rem; margin-bottom: 2rem; }
        .pagination-wrap .pagination { display: flex; gap: 0.5rem; background: white; padding: 0.5rem; border-radius: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #e4e4e7; }
        .pagination-wrap .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 700; color: #52525b; padding: 0 0.75rem; transition: all 0.3s; }
        .pagination-wrap .page-item:not(.active) .page-link:hover { background: #f4f4f5; color: #000; }
        .pagination-wrap .page-item.active .page-link { background: #2563eb; color: white; box-shadow: 0 4px 15px rgba(37,99,235,0.4); }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px]">

    {{-- Include Navbar --}}
    @include('partials.navbar')

    {{-- HERO SECTION DIREKTORI TOKO (B&W Theme) --}}
    <div class="bg-[#09090b] relative overflow-hidden">
        {{-- Abstract Glow --}}
        <div class="absolute top-0 right-1/4 w-96 h-96 bg-blue-600/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-[0.05] pointer-events-none"></div>

        <div class="max-w-[1250px] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20 relative z-10 flex flex-col items-center text-center">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/5 border border-white/10 text-blue-400 text-[10px] font-black tracking-widest uppercase mb-6 shadow-lg backdrop-blur-md">
                <i class="fas fa-shield-check text-blue-500"></i> Partner Resmi
            </div>
            <h1 class="text-4xl lg:text-6xl font-black text-white tracking-tight mb-6">
                Jaringan Mitra <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-white">Terpercaya</span>
            </h1>
            <p class="text-zinc-400 max-w-2xl text-base lg:text-lg font-medium leading-relaxed">
                Eksplorasi ribuan distributor dan toko material bangunan tersertifikasi dari seluruh Indonesia untuk menyuplai proyek skala kecil hingga mega-konstruksi Anda.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-[1250px] mx-auto px-4 sm:px-6 lg:px-8 py-8 relative -mt-10 z-20">

        {{-- FILTER BAR (Floating style) --}}
        <div class="bg-white/90 backdrop-blur-xl p-3 sm:p-4 rounded-2xl border border-white shadow-[0_8px_30px_rgba(0,0,0,0.06)] flex flex-col sm:flex-row items-center justify-between gap-4 mb-10">

            <div class="flex items-center gap-3 text-zinc-800 font-bold px-2">
                <div class="w-10 h-10 rounded-xl bg-zinc-100 flex items-center justify-center text-blue-600">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span>Filter Wilayah</span>
            </div>

            <form action="{{ route('toko.index') }}" method="GET" class="w-full sm:w-auto flex items-center gap-2">
                <div class="relative w-full sm:w-[300px]">
                    <select name="lokasi" class="custom-select w-full bg-zinc-50 border border-zinc-200 text-zinc-700 text-sm font-semibold rounded-xl focus:bg-white focus:border-blue-600 focus:ring-2 focus:ring-blue-600/20 block pl-4 pr-10 py-3 transition-all outline-none cursor-pointer">
                        <option value="semua">Nasional (Seluruh Kota)</option>
                        @foreach($locations as $lokasi)
                            <option value="{{ $lokasi->city_id }}" {{ ($filter_lokasi ?? '') == $lokasi->city_id ? 'selected' : '' }}>
                                {{ $lokasi->city_name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                        <i class="fas fa-chevron-down text-zinc-400 text-xs"></i>
                    </div>
                </div>
                <button type="submit" class="bg-black hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold transition-colors shadow-md shrink-0">
                    Cari
                </button>
            </form>

        </div>

        {{-- INFO COUNT --}}
        <div class="mb-6 flex items-center justify-between">
            <h2 class="text-xl font-black text-zinc-900 tracking-tight">Direktori Toko</h2>
            <span class="text-sm font-semibold text-zinc-500 bg-zinc-200/50 px-3 py-1 rounded-full">{{ $stores->total() }} Toko Aktif</span>
        </div>

        {{-- DAFTAR TOKO GRID --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 lg:gap-8">

            @forelse($stores as $toko)
                @php
                    // Logika Inisial
                    $words = explode(" ", $toko->nama_toko);
                    $acronym = "";
                    foreach ($words as $w) { $acronym .= mb_substr($w, 0, 1); }
                    $storeInitials = strtoupper(substr($acronym, 0, 2)) ?: "TK";

                    // Logika Background / Banner
                    $colors = ['#18181b', '#27272a', '#3f3f46', '#09090b']; // Monochrome base
                    $storeColor = $colors[crc32($toko->nama_toko) % count($colors)];

                    $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
                    $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                    $bannerStyle = $hasBanner
                        ? "background-image: url('".asset($bannerPath)."');"
                        : "background-color: $storeColor;";

                    // Logika Logo
                    $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
                    $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                @endphp

                <a href="{{ route('toko.detail', ['slug' => $toko->slug]) }}" class="group bg-white rounded-[2rem] shadow-card hover:shadow-card-hover overflow-hidden transition-all duration-500 hover:-translate-y-2 border border-zinc-100 flex flex-col relative">

                    {{-- Banner (Grayscale reveal effect) --}}
                    <div class="h-32 bg-cover bg-center relative transition-transform duration-700 group-hover:scale-105 filter grayscale opacity-90 group-hover:grayscale-0 group-hover:opacity-100" style="{{ $bannerStyle }}">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent group-hover:from-black/40 transition-colors"></div>

                        {{-- Verified Badge --}}
                        <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-full text-[10px] font-black text-white flex items-center gap-1.5 border border-white/20 shadow-lg">
                            <i class="fas fa-check-circle text-blue-400"></i> TERVERIFIKASI
                        </div>
                    </div>

                    {{-- Body Content --}}
                    <div class="pt-12 pb-6 px-6 flex-1 flex flex-col relative bg-white z-10 rounded-t-[2rem] -mt-6">

                        {{-- Logo Avatar (Overlap) --}}
                        <div class="absolute -top-12 left-6 transition-transform duration-500 group-hover:-translate-y-1">
                            @if($hasLogo)
                                <img src="{{ asset($logoPath) }}" alt="{{ $toko->nama_toko }}" class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-lg bg-white filter grayscale group-hover:grayscale-0 transition-all duration-500">
                            @else
                                <div class="w-20 h-20 rounded-2xl border-4 border-white shadow-lg flex items-center justify-center font-black text-2xl text-white transition-colors duration-500 group-hover:bg-blue-600" style="background-color: {{ $storeColor }};">
                                    {{ $storeInitials }}
                                </div>
                            @endif
                        </div>

                        {{-- Text Info --}}
                        <h4 class="font-black text-xl text-zinc-900 group-hover:text-blue-600 transition-colors line-clamp-1 mb-1">{{ $toko->nama_toko }}</h4>
                        <p class="text-zinc-500 text-xs font-semibold flex items-center gap-1.5 mb-6">
                            <i class="fas fa-map-pin text-zinc-300"></i> {{ $toko->city_name }}
                        </p>

                        {{-- Stats Grid --}}
                        <div class="mt-auto grid grid-cols-2 gap-3 bg-zinc-50 rounded-xl p-3 border border-zinc-100">
                            <div class="flex flex-col items-center justify-center text-center border-r border-zinc-200">
                                <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-0.5">Katalog</span>
                                <span class="text-sm font-black text-zinc-800">{{ number_format($toko->jumlah_produk) }}</span>
                            </div>
                            <div class="flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] uppercase font-bold text-zinc-400 tracking-wider mb-0.5">Reputasi</span>
                                <div class="flex items-center gap-1 text-sm font-black text-zinc-800">
                                    <i class="fas fa-star text-yellow-500 text-[10px] mb-0.5"></i> {{ number_format($toko->rating, 1) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                {{-- EMPTY STATE --}}
                <div class="col-span-full flex flex-col items-center justify-center py-24 bg-white rounded-3xl border border-dashed border-zinc-300 shadow-sm">
                    <div class="w-24 h-24 bg-zinc-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-store-slash text-4xl text-zinc-400"></i>
                    </div>
                    <h3 class="text-2xl font-black text-zinc-900 mb-2">Toko Tidak Ditemukan</h3>
                    <p class="text-zinc-500 font-medium text-center max-w-md mb-8">Belum ada mitra toko yang terdaftar di lokasi pencarian ini. Silakan perluas area pencarian Anda.</p>

                    @if(($filter_lokasi ?? 'semua') !== 'semua')
                        <a href="{{ route('toko.index') }}" class="bg-black hover:bg-blue-600 text-white font-bold py-3 px-8 rounded-xl transition-all shadow-lg flex items-center gap-2">
                            <i class="fas fa-globe-asia"></i> Tampilkan Semua Kota
                        </a>
                    @endif
                </div>
            @endforelse

        </div>

        {{-- PAGINASI --}}
        <div class="pagination-wrap">
            {{ $stores->links() }}
        </div>

    </div>

    {{-- Include Footer --}}
    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>
