<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondasikita - Premium B2B Material</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#2563eb', // Electric Blue 600
                        primaryGlow: '#3b82f6', // Blue 500
                        secondary: '#000000', // Pure Black
                        surface: '#09090b', // Zinc 950
                        accent: '#ffffff', // Pure White
                    },
                    animation: {
                        'blink': 'blink 0.7s infinite',
                        'blob': 'blob 10s infinite alternate',
                        'pulse-glow': 'pulse-glow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        blink: { '0%, 100%': { opacity: 1 }, '50%': { opacity: 0 } },
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '100%': { transform: 'translate(20px, -30px) scale(1.1)' }
                        },
                        'pulse-glow': {
                            '0%, 100%': { opacity: 1, boxShadow: '0 0 0 0 rgba(37, 99, 235, 0.7)' },
                            '50%': { opacity: .8, boxShadow: '0 0 0 15px rgba(37, 99, 235, 0)' }
                        }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Tambahkan AlpineJS untuk Fitur View More --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
        .glass-panel { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.6); }
        .glass-dark { background: rgba(9, 9, 11, 0.6); backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.08); }
        .text-gradient { background: linear-gradient(135deg, #ffffff 0%, #a1a1aa 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .typing-cursor { display: inline-block; width: 4px; background: #3b82f6; margin-left: 6px; border-radius: 2px; }

        /* Hide scrollbar for slider */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

        /* Smooth Scrollbar General */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #f4f4f5; }
        ::-webkit-scrollbar-thumb { background: #3b82f6; border-radius: 10px; }
        
        /* Utility untuk Animasi AlpineJS */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="text-zinc-900 antialiased selection:bg-primary selection:text-white">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- HERO SECTION : B&W + BLUE PREMIUM EDITION --}}
    <section class="relative bg-zinc-950 pt-24 pb-20 lg:pt-36 lg:pb-32 overflow-hidden border-b border-zinc-800">
        {{-- Ambient Glow --}}
        <div class="absolute top-0 left-1/4 w-[600px] h-[500px] bg-blue-600/10 rounded-full mix-blend-screen filter blur-[120px] animate-blob"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-blue-800/10 rounded-full mix-blend-screen filter blur-[120px] animate-blob" style="animation-delay: 2s;"></div>

        <div class="container mx-auto px-4 relative z-10 grid lg:grid-cols-12 gap-12 lg:gap-16 items-start">

            {{-- TEXT AREA --}}
            <div class="lg:col-span-5 space-y-8 text-center lg:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-blue-400 text-[10px] font-black tracking-widest uppercase">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Sistem V2.0 Aktif
                </div>

                <div class="space-y-2">
                    <h1 class="text-4xl lg:text-6xl font-black text-white leading-tight tracking-tight">
                        Ekosistem Material,
                    </h1>
                    {{-- AREA TEKS MENGETIK: Kursor nempel di akhir teks --}}
                    <div class="h-[80px] md:h-[100px] lg:h-[120px] flex items-start justify-center lg:justify-start">
                        <span class="text-3xl lg:text-5xl font-black">
                            <span class="typing-text text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-blue-600"></span><span class="typing-cursor animate-blink text-blue-500">&nbsp;</span>
                        </span>
                    </div>
                </div>

                <p class="text-base text-zinc-400 max-w-lg mx-auto lg:mx-0 font-medium leading-relaxed">
                    Arsitektur pengadaan B2B masa depan. Temukan ribuan supplier dengan transparansi harga dan manajemen RAB.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="{{ url('pages/produk') }}" class="group bg-white text-zinc-900 font-black py-4 px-8 rounded-xl transition-all hover:shadow-[0_0_30px_rgba(37,99,235,0.3)] flex items-center justify-center gap-3">
                        <i class="fas fa-layer-group text-blue-600"></i> Eksplorasi Katalog
                    </a>
                    <a href="#toko" class="bg-transparent hover:bg-white/5 text-white font-semibold py-4 px-8 rounded-xl transition-all flex items-center justify-center gap-3 border border-zinc-700 hover:border-blue-500">
                        <i class="fas fa-store text-blue-400"></i> Direktori Mitra
                    </a>
                </div>
            </div>

            {{-- DYNAMIC BANNER: KLIK KIRI/KANAN UNTUK SLIDE --}}
            <div class="lg:col-span-7 relative w-full">
                <div class="absolute top-8 left-12 flex gap-2 z-40" id="slider-dots"></div>
                <div class="relative w-full aspect-video lg:aspect-[16/10] rounded-[2.5rem] overflow-hidden shadow-[0_30px_70px_rgba(0,0,0,0.7)] border border-white/10 group bg-zinc-900 cursor-pointer">
                    
                    {{-- Invisibile Click Zones --}}
                    <div class="absolute inset-y-0 left-0 w-1/2 z-30" onclick="moveSlider(-1)"></div>
                    <div class="absolute inset-y-0 right-0 w-1/2 z-30" onclick="moveSlider(1)"></div>

                    {{-- Slider Track --}}
                    <div id="hero-slider" class="flex w-full h-full transition-transform duration-700 ease-in-out">
                        @forelse($promoBanners ?? [] as $banner)
                            <div class="min-w-full h-full relative flex-shrink-0">
                                <img src="{{ $banner->img ?? $banner['img'] }}" class="w-full h-full object-cover" alt="Banner">
                                <div class="absolute inset-0 bg-gradient-to-t from-zinc-950 via-zinc-950/20 to-transparent"></div>

                                <div class="absolute bottom-0 left-0 right-0 p-8 lg:p-12 transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-lg uppercase tracking-widest mb-4">
                                        <i class="fas fa-bolt"></i> Promo Spesial
                                    </div>
                                    <h3 class="text-3xl lg:text-4xl font-black text-white mb-3 drop-shadow-xl">{{ $banner->title ?? $banner['title'] }}</h3>
                                    <p class="text-zinc-300 text-sm lg:text-base font-medium line-clamp-2 max-w-xl">{{ $banner->desc ?? $banner['desc'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="min-w-full h-full bg-zinc-900 flex items-center justify-center">
                                <p class="text-zinc-500">Belum ada promo aktif</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Bottom Navigation Dots --}}
                    
                </div>
            </div>
        </div>
    </section>

    <main class="container mx-auto px-4 py-6 lg:py-10 space-y-10 lg:space-y-16 relative z-20">
        {{-- ========================================================
             KATEGORI UTAMA (DIROMBAK DENGAN ALPINEJS + VIEW MORE)
             ======================================================== --}}
        <section x-data="{ showAll: false }">
            <div class="text-center max-w-2xl mx-auto mb-10">
                <h2 class="text-[10px] font-black tracking-[0.3em] text-blue-600 uppercase mb-3 relative inline-block">
                    <span class="absolute top-1/2 -left-12 w-8 h-px bg-blue-600/30"></span>
                    Direktori Material
                    <span class="absolute top-1/2 -right-12 w-8 h-px bg-blue-600/30"></span>
                </h2>
                <h3 class="text-3xl lg:text-4xl font-black text-black tracking-tight mt-1">Kategori Utama</h3>
            </div>

            {{-- Grid Kategori --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 md:gap-8 relative pb-6">
                
                @forelse($categories ?? [] as $index => $cat)
                    {{-- Wrapper group/card untuk mendeteksi 2 level hover --}}
                    <div class="relative group/card h-full hover:z-50"
                         @if($index >= 6) 
                             x-show="showAll" 
                             x-transition:enter="transition ease-out duration-500"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-cloak
                         @endif>
                         
                        @if(isset($cat->subkategori) && count($cat->subkategori) > 0)
                            {{-- BACK CARD (Menu Sub Kategori) --}}
                            {{-- Tahap 1: Peek (group-hover) -> Kartu ngintip ke kanan bawah dengan rotasi --}}
                            {{-- Tahap 2: Reveal (hover) -> Kartu lompat ke depan (z-[60]), membesar, dan menutupi kartu utama --}}
                            <div class="absolute inset-0 bg-white border border-blue-200 shadow-xl rounded-[1.5rem] z-20 
                                        transition-all duration-300 ease-[cubic-bezier(0.34,1.56,0.64,1)] origin-center 
                                        opacity-0 pointer-events-none 
                                        group-hover/card:opacity-100 group-hover/card:translate-x-8 group-hover/card:translate-y-3 group-hover/card:rotate-[10deg] group-hover/card:pointer-events-auto
                                        hover:!translate-x-2 hover:!translate-y-2 hover:!rotate-0 hover:!scale-105 hover:!z-[60] hover:!shadow-[0_30px_60px_rgba(37,99,235,0.25)] hover:!border-blue-500
                                        flex flex-col p-4">
                                
                                <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-2 border-b border-blue-100 pb-1.5 shrink-0 flex items-center">
                                    <i class="fas fa-list-ul mr-1.5"></i> Sub Kategori
                                </span>
                                
                                <div class="flex-1 overflow-y-auto scrollbar-hide flex flex-col gap-1">
                                    @foreach($cat->subkategori as $sub)
                                        <a href="{{ url('pages/produk?kategori=' . $sub->id) }}" class="block px-2 py-2 text-[11px] font-bold text-zinc-500 hover:text-white hover:bg-blue-600 rounded-lg transition-colors truncate">
                                            {{ $sub->nama_kategori }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- MAIN CARD (Kategori Utama) --}}
                        <a href="{{ url('pages/produk?kategori=' . $cat->id) }}" 
                           class="block relative w-full h-full bg-white p-5 md:p-6 rounded-[1.5rem] shadow-[0_2px_10px_rgb(0,0,0,0.02)] transition-all duration-300 ease-out z-30 
                                  group-hover/card:shadow-2xl group-hover/card:-translate-y-3 group-hover/card:-translate-x-3 group-hover/card:-rotate-[5deg] 
                                  border border-zinc-100/80 group-hover/card:border-blue-300 flex flex-col items-center justify-center gap-4 bg-clip-padding">
                           
                            {{-- Ikon --}}
                            <div class="relative w-14 h-14 md:w-16 md:h-16 rounded-2xl bg-zinc-50 text-zinc-600 flex items-center justify-center text-xl md:text-2xl group-hover/card:bg-blue-600 group-hover/card:text-white transition-colors duration-300 shadow-inner">
                                <i class="{{ $cat->icon_class ?? 'fas fa-tools' }}"></i>
                            </div>
                            
                            {{-- Nama Kategori --}}
                            <p class="font-bold text-zinc-700 text-center text-xs md:text-sm group-hover/card:text-blue-600 transition-colors leading-tight line-clamp-2 px-2">
                                {{ $cat->nama_kategori }}
                            </p>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center text-zinc-400 py-16 border border-dashed border-zinc-200 rounded-3xl bg-white/50">
                        <i class="fas fa-folder-open text-4xl mb-4 text-zinc-300"></i>
                        <span class="font-medium text-sm">Kategori belum tersedia.</span>
                    </div>
                @endforelse

                {{-- Overlay Fade Effect (Muncul kalau ada lebih dari 12 kategori dan belum di-expand) --}}
                @if(isset($categories) && count($categories) > 6)
                    <div x-show="!showAll" class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-[#fafafa] to-transparent pointer-events-none z-10 translate-y-2"></div>
                @endif
            </div>

            {{-- Tombol View More --}}
           {{-- TOMBOL VIEW MORE: PREMIUM INTERACTIVE VERSION --}}
            @if(isset($categories) && count($categories) > 6)
            <div class="mt-8 flex justify-center relative z-20">
                <button @click="showAll = !showAll" 
                        class="group relative inline-flex items-center justify-center px-10 py-4 font-black tracking-tighter text-zinc-700 bg-white rounded-2xl border border-zinc-200 overflow-hidden transition-all duration-500 hover:border-blue-500 hover:text-blue-600 hover:shadow-[0_20px_40px_rgba(37,99,235,0.2)] hover:-translate-y-1 active:scale-95">
                    
                    {{-- Efek Kilatan Cahaya (Shimmer) --}}
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-blue-500/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] pointer-events-none"></div>
                    
                    {{-- Background Glow saat Hover --}}
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 bg-[radial-gradient(circle_at_center,_rgba(37,99,235,0.05)_0%,_transparent_70%)]"></div>

                    <div class="relative flex items-center gap-3">
                        <span class="text-xs uppercase tracking-[0.2em]" x-text="showAll ? 'Ringkas Kategori' : 'Lihat Semua Kategori'"></span>
                        
                        {{-- Icon Container dengan Animasi --}}
                        <div class="relative flex items-center justify-center w-6 h-6 rounded-lg bg-zinc-50 group-hover:bg-blue-50 transition-colors duration-500">
                            <i class="fas text-[10px] transition-transform duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)]" 
                               :class="showAll ? 'fa-minus rotate-180' : 'fa-plus group-hover:rotate-90'"></i>
                        </div>
                    </div>
                </button>
            </div>
            @endif

            <style>
                @keyframes shimmer {
                    100% { transform: translateX(100%); }
                }
            </style>
        </section>

        {{-- SECTION: FLOATING TECH VALUES (PENGISI GAP) --}}
<section class="relative py-0 overflow-hidden rounded-[3rem] bg-zinc-50/50 border border-zinc-100">
    {{-- Background Pattern: Blueprint Grid --}}
    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" 
         style="background-image: radial-gradient(#2563eb 0.5px, transparent 0.5px); background-size: 24px 24px;"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">
            
            {{-- Card 1: Real-Time Inventory --}}
            <div class="group relative p-8 rounded-[2rem] bg-white/50 backdrop-blur-sm border border-white hover:border-blue-200 transition-all duration-500 hover:shadow-[0_20px_50px_rgba(37,99,235,0.05)] hover:-translate-y-2 animate-float">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-700 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-blue-500/20 group-hover:scale-110 transition-transform duration-500">
                    <i class="fas fa-microchip"></i>
                </div>
                <h4 class="text-xl font-black text-zinc-800 mb-3 tracking-tight">Smart Inventory</h4>
                <p class="text-sm text-zinc-500 font-medium leading-relaxed">Pantau ketersediaan stok material di ribuan gudang supplier secara <span class="text-blue-600">real-time</span> tanpa perlu telepon satu-persatu.</p>
                {{-- Decorative Line --}}
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-blue-500/20 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
            </div>

            {{-- Card 2: Transparency (Floating with different delay) --}}
            <div class="group relative p-8 rounded-[2rem] bg-white/50 backdrop-blur-sm border border-white hover:border-blue-200 transition-all duration-500 hover:shadow-[0_20_50px_rgba(37,99,235,0.05)] hover:-translate-y-2 animate-float" style="animation-delay: 1.5s;">
                <div class="w-14 h-14 rounded-2xl bg-zinc-900 text-white flex items-center justify-center text-2xl mb-6 shadow-lg shadow-zinc-900/20 group-hover:bg-blue-600 transition-colors duration-500">
                    <i class="fas fa-handshake-angle"></i>
                </div>
                <h4 class="text-xl font-black text-zinc-800 mb-3 tracking-tight">Transparansi B2B</h4>
                <p class="text-sm text-zinc-500 font-medium leading-relaxed">Dapatkan kontrak digital yang mengikat antara pembeli dan supplier. <span class="text-blue-600">No Hidden Cost</span>, semua biaya terbuka di awal.</p>
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-blue-500/20 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
            </div>

            {{-- Card 3: Logistics (Floating with different delay) --}}
            <div class="group relative p-8 rounded-[2rem] bg-white/50 backdrop-blur-sm border border-white hover:border-blue-200 transition-all duration-500 hover:shadow-[0_20_50px_rgba(37,99,235,0.05)] hover:-translate-y-2 animate-float" style="animation-delay: 3s;">
                <div class="w-14 h-14 rounded-2xl bg-zinc-100 text-zinc-400 flex items-center justify-center text-2xl mb-6 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <i class="fas fa-route"></i>
                </div>
                <h4 class="text-xl font-black text-zinc-800 mb-3 tracking-tight">Optimal Logistics</h4>
                <p class="text-sm text-zinc-500 font-medium leading-relaxed">Algoritma kami memilihkan armada terdekat untuk menghemat <span class="text-blue-600">ongkos kirim</span> hingga 30% dari tarif normal.</p>
                <div class="absolute bottom-0 left-8 right-8 h-1 bg-gradient-to-r from-transparent via-blue-500/20 to-transparent scale-x-0 group-hover:scale-x-100 transition-transform duration-700"></div>
            </div>

        </div>
    </div>
</section>

<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
        100% { transform: translateY(0px); }
    }
    .animate-float {
        animation: float 6s ease-in-out infinite;
    }
</style>    
        {{-- ========================================================
             MODERN BENTO GRID FEATURE HIGHLIGHT (Pengganti Trust Badge)
             ======================================================== --}}
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6 relative z-20">
            
            {{-- KOLOM BESAR: Promo AI POTA (Dark Theme untuk Kontras) --}}
            <div class="lg:col-span-2 relative overflow-hidden bg-zinc-950 rounded-[2.5rem] p-8 md:p-12 flex flex-col justify-center group border border-zinc-800 shadow-2xl">
                {{-- Efek Glow Latar --}}
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-600/20 rounded-full mix-blend-screen filter blur-[80px] group-hover:bg-blue-500/30 transition-colors duration-700 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600/10 rounded-full mix-blend-screen filter blur-[80px] pointer-events-none"></div>
                
                {{-- Ornamen UI Transparan di Kanan --}}
                <div class="absolute -right-10 -bottom-10 opacity-10 pointer-events-none">
                    <i class="fas fa-robot text-[250px] text-white"></i>
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-500/10 border border-blue-500/20 text-blue-400 text-xs font-black tracking-widest uppercase mb-6 shadow-[0_0_15px_rgba(37,99,235,0.1)]">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                        </span>
                        Mandor POTA AI
                    </div>
                    
                    <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-5 leading-[1.1] tracking-tight">
                        Asisten Proyek Cerdas,<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-400">Siap 24 Jam.</span>
                    </h3>
                    
                    <p class="text-zinc-400 max-w-md text-sm md:text-base font-medium leading-relaxed">
                        Tinggalkan cara lama. Biarkan AI kami menghitung kebutuhan RAB Anda, mencari supplier termurah, dan merekomendasikan material secara real-time.
                    </p>
                </div>
                
                <div class="relative z-10 mt-8 md:mt-10">
                    <button onclick="toggleChat()" class="group/btn inline-flex items-center gap-3 bg-white text-zinc-900 font-black px-6 py-3.5 rounded-xl transition-all hover:bg-blue-50 hover:text-blue-600 shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:-translate-y-1">
                        Ngobrol Sekarang 
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover/btn:translate-x-1"></i>
                    </button>
                </div>
            </div>

            {{-- KOLOM KANAN: Value Proposition (Ditumpuk Atas-Bawah) --}}
            <div class="flex flex-col gap-6">
                
                {{-- Kotak Atas (Warna Biru Sangat Lembut) --}}
                <div class="flex-1 bg-gradient-to-br from-blue-50 to-white rounded-[2.5rem] p-8 border border-blue-100 flex flex-col justify-center relative overflow-hidden group shadow-sm hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-[0_4px_15px_rgba(37,99,235,0.1)] flex items-center justify-center text-blue-600 text-2xl mb-5 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <i class="fas fa-hand-holding-dollar"></i>
                    </div>
                    <h4 class="font-black text-xl text-zinc-800 mb-2 tracking-tight">Harga Transparan</h4>
                    <p class="text-xs md:text-sm text-zinc-500 font-medium leading-relaxed">Bandingkan harga langsung dari tangan pertama tanpa perantara yang rumit.</p>
                </div>

                {{-- Kotak Bawah (Warna Netral) --}}
                <div class="flex-1 bg-gradient-to-br from-zinc-50 to-white rounded-[2.5rem] p-8 border border-zinc-200 flex flex-col justify-center relative overflow-hidden group shadow-sm hover:shadow-xl hover:shadow-zinc-500/10 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-[0_4px_15px_rgba(0,0,0,0.05)] flex items-center justify-center text-zinc-800 text-2xl mb-5 group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                        <i class="fas fa-truck-fast"></i>
                    </div>
                    <h4 class="font-black text-xl text-zinc-800 mb-2 tracking-tight">Logistik B2B</h4>
                    <p class="text-xs md:text-sm text-zinc-500 font-medium leading-relaxed">Sistem terintegrasi untuk pengiriman material skala besar (truk/tronton).</p>
                </div>

            </div>
        </section>

        {{-- TOKO POPULER (B&W Edition) --}}
        {{-- SECTION: MITRA TOKO POPULER (PREMIUM RE-DESIGN) --}}
        <section id="toko" class="relative">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-zinc-100 pb-8 gap-6">
                <div>
                    <h2 class="text-[10px] font-black tracking-[0.3em] text-blue-600 uppercase mb-3">Partner Resmi</h2>
                    <h3 class="text-3xl lg:text-4xl font-black text-black tracking-tight">{{ $tokoSectionTitle ?? 'Mitra Terverifikasi' }}</h3>
                </div>

                {{-- TOMBOL SEMUA MITRA: CYBER-INTERACTIVE VERSION --}}
                <a href="{{ url('pages/semua_toko') }}" 
                   class="group relative inline-flex items-center justify-center px-8 py-3.5 font-black tracking-tighter text-zinc-700 bg-white rounded-2xl border border-zinc-200 overflow-hidden transition-all duration-500 hover:border-blue-500 hover:text-blue-600 hover:shadow-[0_20px_40px_rgba(37,99,235,0.15)] hover:-translate-y-1 active:scale-95">
                    
                    {{-- Efek Kilatan Cahaya (Shimmer) --}}
                    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-blue-500/10 to-transparent -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] pointer-events-none"></div>
                    
                    <div class="relative flex items-center gap-3">
                        <span class="text-[10px] uppercase tracking-[0.2em]">Semua Mitra</span>
                        <div class="relative flex items-center justify-center w-5 h-5 rounded-lg bg-zinc-50 group-hover:bg-blue-50 transition-colors duration-500">
                            <i class="fas fa-arrow-right text-[9px] transition-transform duration-500 group-hover:translate-x-0.5"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($listToko ?? [] as $toko)
                    @php
                        $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
                        $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                        $initials = strtoupper(substr($toko->nama_toko ?? 'TK', 0, 2));
                        $bgStyle = $hasBanner ? "background-image: url(" . asset($bannerPath) . ");" : "background-color: #09090b;";
                        $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
                        $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                    @endphp

                    <a href="{{ url('pages/toko?slug=' . ($toko->slug ?? '#')) }}" 
                       class="group relative bg-white rounded-[2.5rem] shadow-[0_2px_15px_rgba(0,0,0,0.02)] hover:shadow-[0_30px_60px_rgba(37,99,235,0.1)] overflow-hidden transition-all duration-500 hover:-translate-y-2 border border-zinc-100 flex flex-col">
                        
                        {{-- Banner Area dengan Grayscale-to-Color Effect --}}
                        <div class="h-36 bg-cover bg-center relative transition-all duration-700 grayscale group-hover:grayscale-0 scale-100 group-hover:scale-105" style="{{ $bgStyle }}">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent transition-opacity group-hover:opacity-60"></div>
                            
                            {{-- Premium Badge --}}
                            <div class="absolute top-4 right-4 bg-white/10 backdrop-blur-md border border-white/20 px-3 py-1.5 rounded-full flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                                <span class="text-[9px] font-black text-white uppercase tracking-widest">Verified</span>
                            </div>
                        </div>

                        {{-- Content Area --}}
                        <div class="pt-12 pb-8 px-8 flex-1 bg-white relative rounded-t-[2.5rem] -mt-6 z-10">
                            {{-- Logo Float --}}
                            <div class="absolute -top-12 left-8">
                                <div class="relative">
                                    @if($hasLogo)
                                        <img src="{{ asset($logoPath) }}" class="w-20 h-20 rounded-2xl object-cover border-[6px] border-white shadow-xl transition-transform duration-500 group-hover:scale-105" alt="Logo">
                                    @else
                                        <div class="w-20 h-20 rounded-2xl text-white flex items-center justify-center font-black text-2xl border-[6px] border-white shadow-xl bg-zinc-950 group-hover:bg-blue-600 transition-all duration-500">
                                            {{ $initials }}
                                        </div>
                                    @endif
                                    {{-- Mini Icon Overlay --}}
                                    <div class="absolute -bottom-1 -right-1 w-7 h-7 bg-blue-600 rounded-lg border-4 border-white flex items-center justify-center text-[10px] text-white shadow-lg">
                                        <i class="fas fa-store"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-1">
                                <h4 class="font-black text-xl text-zinc-900 group-hover:text-blue-600 transition-colors truncate tracking-tight">
                                    {{ $toko->nama_toko ?? 'Nama Toko' }}
                                </h4>
                                <p class="text-zinc-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="fas fa-location-dot text-blue-500/50"></i> {{ $toko->kota ?? 'Indonesia' }}
                                </p>
                            </div>

                            {{-- Footer Card --}}
                            <div class="mt-8 pt-6 border-t border-zinc-50 flex items-center justify-between">
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-black text-zinc-300 uppercase tracking-widest leading-none">Koleksi</span>
                                    <span class="text-sm font-black text-zinc-700 mt-1">{{ $toko->jumlah_produk_aktif ?? 0 }} Produk</span>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-zinc-50 flex items-center justify-center text-zinc-400 group-hover:bg-blue-600 group-hover:text-white group-hover:shadow-[0_10px_20px_rgba(37,99,235,0.3)] transition-all duration-500">
                                    <i class="fas fa-arrow-right -rotate-45 group-hover:rotate-0 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full py-20 text-center bg-zinc-50 rounded-[3rem] border border-dashed border-zinc-200">
                        <i class="fas fa-store-slash text-4xl text-zinc-200 mb-4"></i>
                        <p class="text-zinc-400 font-bold uppercase tracking-widest text-xs">Belum ada mitra di wilayah ini</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- ========================================================
             PRODUK GRID: ELEGANT PREMIUM EDITION (NO GRAYSCALE)
             ======================================================== --}}
        @foreach(['listProdukLokal' => ['title' => 'Rekomendasi Area Anda', 'badge' => 'TERDEKAT'], 'listProdukNasional' => ['title' => 'Tren Nasional', 'badge' => 'TERLARIS']] as $varName => $config)
            @if(isset($$varName) && count($$varName) > 0)
            <section class="relative">
                {{-- Header Section yang Lebih Minimalis --}}
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-1.5 h-8 bg-blue-600 rounded-full"></div>
                    <div>
                        <span class="text-[9px] font-black tracking-[0.4em] text-blue-600/60 uppercase leading-none">{{ $config['badge'] }}</span>
                        <h2 class="text-2xl lg:text-3xl font-black text-zinc-900 tracking-tight mt-0.5">{{ $config['title'] }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6 lg:gap-8">
                    @foreach($$varName as $p)
    @php
        $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
        
        // INTEGRASI DATA REAL DARI tb_review_produk
        $statUlasan = DB::table('tb_review_produk')
                        ->where('barang_id', $p->id)
                        ->selectRaw('COUNT(id) as total, AVG(rating) as rata')
                        ->first();
        
        $avg_rating = $statUlasan->rata ?? 0;
        $jumlah_ulasan = $statUlasan->total ?? 0;
    @endphp
    
    <a href="{{ route('produk.detail', $p->id) }}" 
       class="group relative bg-white rounded-[2.5rem] border border-zinc-100 transition-all duration-500 ease-[cubic-bezier(0.34,1.56,0.64,1)] hover:-translate-y-2 hover:shadow-[0_30px_60px_rgba(37,99,235,0.1)] flex flex-col overflow-hidden">
        
        {{-- 1. Image Container (Full Color & Clean) --}}
        <div class="aspect-square bg-zinc-50 overflow-hidden relative">
            <img src="{{ asset($img) }}" 
                 onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?q=80&w=600'" 
                 class="w-full h-full object-cover scale-100 group-hover:scale-110 transition-transform duration-700 ease-out" 
                 alt="{{ $p->nama_barang }}">
            
            {{-- Aksen Overlay Tipis di Bawah Gambar --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/[0.03] to-transparent"></div>
        </div>

        {{-- 2. Info Content Area --}}
        <div class="p-6 flex flex-col flex-1 relative bg-white border-t border-zinc-50">
            {{-- Garis Aksen Biru (Slide In) --}}
            <div class="absolute left-0 top-6 bottom-6 w-1 bg-blue-600 rounded-r-full scale-y-0 group-hover:scale-y-100 transition-transform duration-500 origin-center"></div>

            {{-- Nama Produk --}}
            <h3 class="text-sm font-bold text-zinc-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-all duration-500 min-h-[2.5rem] group-hover:pl-3">
                {{ $p->nama_barang }}
            </h3>

            {{-- Rating & Ulasan (Real Integration) --}}
            <div class="flex items-center gap-2 mt-1 mb-4 group-hover:pl-3 transition-all duration-500">
                <div class="flex text-amber-400 text-[10px]">
                    <i class="fas fa-star"></i>
                </div>
                <span class="text-xs font-black text-zinc-900">{{ number_format($avg_rating, 1) }}</span>
                <span class="text-[10px] font-bold text-zinc-400">({{ $jumlah_ulasan }} Ulasan)</span>
            </div>

            {{-- 3. Bottom Section (Price & Store) --}}
            <div class="mt-auto">
                <div class="flex flex-col mb-4 group-hover:pl-3 transition-all duration-500">
                    <span class="text-[9px] font-black text-zinc-400 uppercase tracking-widest leading-none mb-1.5">Harga Satuan</span>
                    <div class="text-xl font-black text-zinc-950 tracking-tight flex items-baseline gap-0.5">
                        <span class="text-xs font-bold text-blue-600">Rp</span>
                        <span>{{ number_format($p->harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Store Footer --}}
                <div class="pt-4 border-t border-zinc-50 flex items-center justify-between group-hover:border-blue-50 transition-colors">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-7 h-7 rounded-lg bg-zinc-50 flex items-center justify-center shrink-0 border border-zinc-100 group-hover:bg-blue-600 group-hover:border-blue-600 transition-all duration-500">
                            <i class="fas fa-store text-[10px] text-zinc-400 group-hover:text-white"></i>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[10px] font-black text-zinc-900 truncate uppercase leading-none">{{ $p->nama_toko }}</span>
                            <span class="text-[9px] font-bold text-zinc-400 truncate mt-0.5">Verified Mitra</span>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right text-[10px] text-zinc-300 -rotate-45 group-hover:rotate-0 group-hover:text-blue-600 transition-all duration-500"></i>
                </div>
            </div>
        </div>
    </a>
@endforeach
                </div>
            </section>
            @endif
        @endforeach

    </main>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>

    {{-- ===================== CHATBOT POTA : BLACK & WHITE + BLUE GLOW ===================== --}}

    {{-- Toggle Button --}}
    <button id="live-chat-toggle" class="fixed bottom-6 right-6 bg-black text-white p-1 pr-5 rounded-full shadow-[0_10px_30px_rgba(0,0,0,0.4)] hover:shadow-[0_15px_40px_rgba(37,99,235,0.4)] transition-all duration-300 z-50 flex items-center gap-3 group border border-zinc-800 hover:border-blue-500 overflow-hidden" onclick="toggleChat()">
        <div class="bg-blue-600 w-12 h-12 rounded-full relative flex items-center justify-center">
            <div class="absolute inset-0 rounded-full animate-pulse-glow opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <i class="fas fa-robot text-xl relative z-10"></i>
        </div>
        <div class="flex flex-col text-left hidden md:flex">
            <span class="font-black text-sm tracking-wide">POTA Asisten</span>
            <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest">AI Proyek</span>
        </div>
    </button>

    {{-- Chat Window --}}
    <div id="live-chat-window" class="fixed bottom-24 right-6 w-[380px] h-[580px] bg-white rounded-3xl shadow-[0_30px_60px_rgba(0,0,0,0.12)] border border-zinc-200 flex-col overflow-hidden z-50 transition-all duration-500 opacity-0 translate-y-10 scale-95 pointer-events-none hidden origin-bottom-right">

        {{-- Header (Black) --}}
        <div class="bg-black text-white p-5 flex justify-between items-center shrink-0 border-b border-zinc-800">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-10 h-10 bg-zinc-900 rounded-xl flex items-center justify-center border border-zinc-800">
                        <i class="fas fa-hard-hat text-blue-500"></i>
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-blue-500 border-2 border-black rounded-full animate-pulse"></div>
                </div>
                <div>
                    <h4 class="font-black tracking-wide text-sm">Mandor POTA</h4>
                    <p class="text-[10px] text-zinc-400 font-bold tracking-wider uppercase">Siap Melayani</p>
                </div>
            </div>
            <div class="flex items-center gap-1">
                <button onclick="startVoiceCallMode()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 text-zinc-400 hover:text-blue-400 transition-all"><i class="fas fa-phone"></i></button>
                <button onclick="toggleFullScreen()" class="w-8 h-8 rounded-lg hover:bg-zinc-800 text-zinc-400 hover:text-white transition-all"><i id="icon-resize" class="fas fa-expand"></i></button>
                <button onclick="toggleChat()" class="w-8 h-8 rounded-lg hover:bg-red-500/20 text-zinc-400 hover:text-red-500 transition-all"><i class="fas fa-xmark"></i></button>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 p-5 overflow-y-auto bg-zinc-50 flex flex-col gap-4 chat-messages relative" id="chat-messages">
            <div class="text-[10px] text-center text-zinc-400 font-bold uppercase tracking-widest mb-2">Hari ini</div>

            {{-- Bot Initial Message --}}
            <div class="flex gap-2 max-w-[85%]">
                <div class="w-8 h-8 rounded-xl bg-black flex-shrink-0 flex items-center justify-center text-white text-xs mt-auto"><i class="fas fa-robot text-blue-500"></i></div>
                <div class="bg-white border border-zinc-200 text-zinc-800 p-3.5 rounded-2xl rounded-bl-sm text-sm shadow-sm relative group font-medium leading-relaxed">
                    Sistem siap, {{ auth()->user()?->nama ?? 'Juragan' }}! Cari material baja, hitung semen, atau lacak pesanan B2B?
                </div>
            </div>
        </div>

        {{-- Input Area --}}
        <div class="p-3 bg-white border-t border-zinc-200 flex items-center gap-2 shrink-0">
            <button id="voice-btn" onclick="toggleVoice()" class="w-10 h-10 rounded-xl bg-zinc-100 text-zinc-500 hover:bg-black hover:text-white flex items-center justify-center transition-all flex-shrink-0">
                <i class="fas fa-microphone"></i>
            </button>
            <div class="flex-1 relative">
                <input type="text" id="chat-input" placeholder="Tanya POTA..." class="w-full bg-zinc-100 text-sm font-medium rounded-xl pl-4 pr-10 py-3 outline-none focus:ring-1 focus:ring-black border border-transparent transition-all placeholder:text-zinc-400" onkeypress="handleEnter(event)">
                <button id="send-chat-btn" onclick="sendMessage()" class="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-8 rounded-lg bg-black text-white hover:bg-blue-600 flex items-center justify-center transition-colors">
                    <i class="fas fa-arrow-up text-xs"></i>
                </button>
            </div>
        </div>

        {{-- Voice Call Overlay (Black Theme) --}}
        <div id="voice-call-overlay" class="absolute inset-0 bg-black/95 backdrop-blur-md z-[100] hidden flex-col items-center justify-center text-white">
            <div class="text-xs font-black tracking-widest text-zinc-500 uppercase mb-16" id="voice-status-text">Menyambungkan...</div>

            <div class="relative w-32 h-32 flex items-center justify-center mb-16">
                <div class="absolute inset-0 bg-blue-600/20 rounded-full animate-ping duration-1000"></div>
                <div id="voice-visualizer" class="w-24 h-24 rounded-full bg-blue-600 flex items-center justify-center text-white text-3xl shadow-[0_0_30px_rgba(37,99,235,0.4)] z-10 transition-all duration-500">
                    <i class="fas fa-microphone"></i>
                </div>
            </div>

            <button onclick="endVoiceCallMode()" class="bg-zinc-900 border border-zinc-800 text-white hover:text-red-500 hover:border-red-500 px-8 py-3 rounded-full font-bold flex items-center gap-2 transition-all group text-sm">
                <i class="fas fa-phone-slash group-hover:animate-bounce"></i> Tutup Panggilan
            </button>
        </div>
    </div>

    {{-- Scripts untuk Banner Slider & Chatbot --}}
    <script>
        /* === DYNAMIC BANNER SLIDER LOGIC === */
        const slider = document.getElementById('hero-slider');
        const dotsContainer = document.getElementById('slider-dots');
        let currentSlide = 0;
        let totalSlides = slider ? slider.children.length : 0;
        let slideInterval;

        function initSlider() {
            if(totalSlides <= 1) return; // No need to slide if 1 or 0

            // Create dots
            for(let i=0; i<totalSlides; i++) {
                const dot = document.createElement('button');
                dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${i === 0 ? 'w-6 bg-blue-500' : 'bg-white/40 hover:bg-white'}`;
                dot.onclick = () => goToSlide(i);
                dotsContainer.appendChild(dot);
            }
            startSlideShow();
        }

        function updateDots() {
            if(!dotsContainer) return;
            Array.from(dotsContainer.children).forEach((dot, index) => {
                dot.className = `w-2 h-2 rounded-full transition-all duration-300 ${index === currentSlide ? 'w-6 bg-blue-500' : 'bg-white/40 hover:bg-white'}`;
            });
        }

        function goToSlide(index) {
            currentSlide = index;
            slider.style.transform = `translateX(-${currentSlide * 100}%)`;
            updateDots();
            resetSlideShow();
        }

        function moveSlider(direction) {
            currentSlide = (currentSlide + direction + totalSlides) % totalSlides;
            goToSlide(currentSlide);
        }

        function startSlideShow() {
            slideInterval = setInterval(() => moveSlider(1), 5000); // Slide setiap 5 detik
        }

        function resetSlideShow() {
            clearInterval(slideInterval);
            startSlideShow();
        }

        document.addEventListener("DOMContentLoaded", () => {
            initSlider();
            typeEffect(); // Panggil typewriter effect
        });

        /* === TYPEWRITER EFFECT === */
        const typingText = document.querySelector(".typing-text");
        const phrases = ["Material Terlengkap", "Harga Pabrik Langsung", "Logistik Real-Time"];
        let phraseIndex = 0, charIndex = 0, isDeleting = false, typeSpeed = 100;

        function typeEffect() {
            if (!typingText) return;
            const currentPhrase = phrases[phraseIndex];
            if (isDeleting) {
                typingText.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--; typeSpeed = 30;
            } else {
                typingText.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++; typeSpeed = 80;
            }
            if (!isDeleting && charIndex === currentPhrase.length) {
                isDeleting = true; typeSpeed = 2500;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false; phraseIndex = (phraseIndex + 1) % phrases.length; typeSpeed = 500;
            }
            setTimeout(typeEffect, typeSpeed);
        }

        /* === CHATBOT LOGIC (POTA) === */
        const chatWindow = document.getElementById('live-chat-window');
        const messagesContainer = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const callOverlay = document.getElementById('voice-call-overlay');
        const voiceStatus = document.getElementById('voice-status-text');
        const voiceVisualizer = document.getElementById('voice-visualizer');
        const voiceBtn = document.getElementById('voice-btn');

        let chatHistory = [];
        let isCallMode = false;
        let recognition = null;
        let voices = [];

        function loadVoices() { voices = window.speechSynthesis.getVoices(); }
        window.speechSynthesis.onvoiceschanged = loadVoices;

        if (window.SpeechRecognition || window.webkitSpeechRecognition) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.interimResults = false;

            recognition.onresult = (event) => {
                const text = event.results[0][0].transcript;
                if(isCallMode) {
                    voiceStatus.innerText = "Menganalisa...";
                    voiceVisualizer.classList.remove('animate-pulse');
                    sendMessage(text);
                } else {
                    chatInput.value = text;
                    stopRecordingUI();
                }
            };
            recognition.onerror = (e) => { stopRecordingUI(); if(isCallMode) { voiceStatus.innerText = "Tidak terdengar."; setTimeout(startListening, 2000); } };
            recognition.onend = () => { if(!isCallMode) stopRecordingUI(); };
        }

        function toggleChat() {
            if(chatWindow.classList.contains('hidden')) {
                chatWindow.classList.remove('hidden', 'opacity-0', 'translate-y-10', 'scale-95', 'pointer-events-none');
                chatWindow.classList.add('flex', 'opacity-100', 'translate-y-0', 'scale-100');
                setTimeout(() => chatInput.focus(), 100);
            } else {
                chatWindow.classList.add('opacity-0', 'translate-y-10', 'scale-95', 'pointer-events-none');
                chatWindow.classList.remove('opacity-100', 'translate-y-0', 'scale-100');
                setTimeout(() => chatWindow.classList.add('hidden'), 500);
                endVoiceCallMode();
            }
        }

        function toggleFullScreen() {
            chatWindow.classList.toggle('w-[380px]'); chatWindow.classList.toggle('h-[580px]');
            chatWindow.classList.toggle('w-[90vw]'); chatWindow.classList.toggle('h-[85vh]');
            chatWindow.classList.toggle('bottom-24'); chatWindow.classList.toggle('bottom-10');
            const icon = document.getElementById('icon-resize');
            icon.className = chatWindow.classList.contains('w-[90vw]') ? 'fas fa-compress' : 'fas fa-expand';
        }

        function handleEnter(e) { if(e.key === 'Enter') sendMessage(); }

        function toggleVoice() {
            if(!recognition) return alert("Browser tidak mendukung mic.");
            if(voiceBtn.classList.contains('text-white') && voiceBtn.classList.contains('bg-blue-600')) { recognition.stop(); stopRecordingUI(); }
            else { recognition.start(); startRecordingUI(); }
        }

        function startRecordingUI() {
            voiceBtn.classList.add('text-white', 'bg-blue-600', 'animate-pulse');
            voiceBtn.classList.remove('text-zinc-500', 'bg-zinc-100');
        }

        function stopRecordingUI() {
            voiceBtn.classList.remove('text-white', 'bg-blue-600', 'animate-pulse');
            voiceBtn.classList.add('text-zinc-500', 'bg-zinc-100');
        }

        function startVoiceCallMode() {
            if(!recognition) return alert("Browser tidak mendukung.");
            isCallMode = true;
            callOverlay.classList.remove('hidden'); callOverlay.classList.add('flex');
            voiceStatus.innerText = "Mandor Standby...";
            voiceVisualizer.classList.add('animate-pulse');
            speakText("Halo! Ada proyek apa hari ini?", true);
        }

        function endVoiceCallMode() {
            isCallMode = false;
            callOverlay.classList.add('hidden'); callOverlay.classList.remove('flex');
            window.speechSynthesis.cancel();
            if(recognition) recognition.stop();
        }

        function startListening() {
            if(!isCallMode) return;
            try {
                recognition.start();
                voiceStatus.innerText = "Silakan bicara...";
                voiceVisualizer.classList.add('animate-pulse');
            } catch(e) {}
        }

        function appendMessage(text, sender) {
            const div = document.createElement('div');
            if(sender === 'bot') {
                div.className = "flex gap-2 max-w-[85%] origin-bottom-left animate-[scale-in-bl_0.3s_both]";
                const clean = text.replace(/"/g, "'").replace(/\n/g, " ").replace(/<[^>]*>?/gm, '');
                div.innerHTML = `
                    <div class="w-8 h-8 rounded-xl bg-black flex-shrink-0 flex items-center justify-center text-white text-xs mt-auto"><i class="fas fa-robot text-blue-500"></i></div>
                    <div class="bg-white border border-zinc-200 text-zinc-800 p-3.5 rounded-2xl rounded-bl-sm text-sm shadow-sm relative group font-medium leading-relaxed">
                        ${text}
                        <button onclick="speakText('${clean}')" class="absolute -right-8 bottom-1 w-6 h-6 rounded-full text-zinc-400 hover:text-blue-500 opacity-0 group-hover:opacity-100 transition-all"><i class="fas fa-volume-up"></i></button>
                    </div>`;
            } else {
                div.className = "flex max-w-[85%] self-end origin-bottom-right animate-[scale-in-br_0.3s_both]";
                div.innerHTML = `<div class="bg-black text-white p-3.5 rounded-2xl rounded-br-sm text-sm font-medium shadow-md border border-zinc-800">${text}</div>`;
            }
            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        async function sendMessage(textOverride = null) {
            const text = textOverride || chatInput.value.trim();
            if(!text) return;
            if(!textOverride) { appendMessage(text, 'user'); chatInput.value = ''; }
            chatHistory.push({sender:'user', text:text});

            if(!isCallMode) {
                const loadDiv = document.createElement('div');
                loadDiv.id = 'loading';
                loadDiv.className = 'flex gap-1.5 ml-10 items-center text-blue-500 mt-2 mb-4';
                loadDiv.innerHTML = '<span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce"></span><span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.2s"></span><span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-bounce" style="animation-delay: 0.4s"></span>';
                messagesContainer.appendChild(loadDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            try {
                // Gunakan path '/api/chat' langsung, dan tambahkan header 'Accept'
                const res = await fetch('/api/chat', {
                    method: 'POST', 
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json', // PENTING: Agar Laravel tidak mereturn HTML jika error
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({message: text, history: chatHistory.slice(-6)})
                });
                
                // TANGKAP ERROR DARI CONTROLLER LARAVEL KITA
                if (!res.ok) {
                    const errorData = await res.json();
                    throw new Error(errorData.reply || "Gagal terhubung ke otak POTA.");
                }
                
                const data = await res.json();

                if(!isCallMode && document.getElementById('loading')) document.getElementById('loading').remove();
                appendMessage(data.reply, 'bot');
                chatHistory.push({sender:'bot', text: data.reply.replace(/<[^>]*>?/gm, '')});
                if(isCallMode) speakText(data.reply, true);
                
            } catch(e) {
                if(document.getElementById('loading')) document.getElementById('loading').remove();
                console.error("ERROR POTA:", e);
                
                // MUNCULKAN PESAN ERROR ASLINYA KE LAYAR CHAT PELANGGAN
                appendMessage("⚠️ " + e.message, 'bot');
            }
        }
            function speakText(text, autoListen = false) {
                window.speechSynthesis.cancel();
                const u = new SpeechSynthesisUtterance(text.replace(/<[^>]*>?/gm, '').replace(/[*_#]/g, ''));
                u.lang = 'id-ID'; u.pitch = 0.9; u.rate = 1.0;

                const indoVoice = voices.find(v => v.lang === 'id-ID' && v.name.includes('Google'));
                if (indoVoice) u.voice = indoVoice;

                u.onstart = () => {
                    if(isCallMode) {
                        voiceVisualizer.classList.remove('animate-pulse');
                        voiceStatus.innerText = "Mandor Menjawab...";
                    }
                };
                u.onend = () => { if(isCallMode && autoListen) setTimeout(startListening, 500); };
                window.speechSynthesis.speak(u);
            }

        // CSS Animation for chat bubbles
        const style = document.createElement('style');
        style.innerHTML = `
            @keyframes scale-in-bl { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
            @keyframes scale-in-br { 0% { transform: scale(0); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>