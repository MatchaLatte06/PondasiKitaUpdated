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
    </style>
</head>
<body class="text-zinc-900 antialiased selection:bg-primary selection:text-white">

    {{-- Navbar (Include yours here) --}}
    @include('partials.navbar')

    {{-- HERO SECTION : B&W + BLUE PREMIUM EDITION --}}
    <section class="relative bg-secondary min-h-[90vh] flex items-center overflow-hidden">
        {{-- Elegant Ambient Background --}}
        <div class="absolute top-10 -left-20 w-[500px] h-[500px] bg-blue-600/10 rounded-full mix-blend-screen filter blur-[120px] animate-blob"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-zinc-600/10 rounded-full mix-blend-screen filter blur-[150px] animate-blob" style="animation-delay: 2s;"></div>

        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-10 mix-blend-overlay"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-secondary via-transparent to-transparent"></div>

        <div class="container mx-auto px-4 relative z-10 grid lg:grid-cols-12 gap-12 items-center">

            {{-- Text Area --}}
            <div class="lg:col-span-6 space-y-8 text-center lg:text-left pt-20 lg:pt-0">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-dark text-blue-400 text-[10px] font-black tracking-[0.2em] uppercase shadow-[0_0_20px_rgba(37,99,235,0.15)]">
                    <span class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></span> Sistem Terpusat V2.0
                </div>

                <h1 class="text-5xl lg:text-7xl font-black text-white leading-[1.1] tracking-tight">
                    Ekosistem Material,<br>
                    <span class="typing-text text-gradient"></span><span class="typing-cursor animate-blink">&nbsp;</span>
                </h1>

                <p class="text-lg text-zinc-400 max-w-xl mx-auto lg:mx-0 font-medium leading-relaxed">
                    Arsitektur pengadaan B2B masa depan. Temukan ribuan supplier dengan transparansi harga dan manajemen RAB dalam satu layar.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-6">
                    <a href="{{ url('pages/produk') }}" class="group relative overflow-hidden bg-white text-black font-black py-4 px-8 rounded-xl transition-all hover:-translate-y-1 shadow-[0_0_30px_rgba(255,255,255,0.1)] flex items-center justify-center gap-3">
                        <span class="absolute inset-0 bg-blue-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-in-out z-0"></span>
                        <i class="fas fa-layer-group relative z-10 group-hover:text-white transition-colors"></i>
                        <span class="relative z-10 group-hover:text-white transition-colors">Eksplorasi Katalog</span>
                    </a>
                    <a href="#toko" class="glass-dark hover:bg-white/5 text-white font-semibold py-4 px-8 rounded-xl transition-all hover:-translate-y-1 flex items-center justify-center gap-3 border border-zinc-800 hover:border-blue-500/50">
                        <i class="fas fa-store text-blue-500"></i> Direktori Mitra
                    </a>
                </div>
            </div>

            {{-- DYNAMIC BANNER SLIDER (Admin Ready) --}}
            <div class="lg:col-span-6 relative w-full flex justify-center lg:justify-end">
                <div class="relative w-full max-w-lg lg:max-w-[550px] h-[400px] lg:h-[500px] rounded-3xl overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.8)] border border-zinc-800 group bg-zinc-950">

                    {{-- Slider Track --}}
                    <div id="hero-slider" class="flex w-full h-full transition-transform duration-700 ease-[cubic-bezier(0.87,0,0.13,1)]">

                        {{-- Laravel Loop untuk Banner --}}
                        {{-- Ganti [1, 2, 3] dengan $promoBanners dari Controller --}}
                        @forelse([
                            ['title' => 'Pekan Diskon Baja', 'desc' => 'Dapatkan potongan harga khusus untuk pembelian baja ringan volume besar minggu ini.', 'img' => 'https://images.unsplash.com/photo-1504307651254-35680f356dfd?q=80&w=1000&auto=format&fit=crop'],
                            ['title' => 'Gratis Ongkir Se-Jawa', 'desc' => 'Subsidi ongkos kirim hingga Rp500.000 untuk minimal transaksi 50 Juta.', 'img' => 'https://images.unsplash.com/photo-1587293852726-70cdb56c2866?q=80&w=1000&auto=format&fit=crop'],
                            ['title' => 'Mitra Baru: Semen Tiga Roda', 'desc' => 'Kini tersedia semen kualitas premium langsung dari pabrik dengan harga termurah.', 'img' => 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=1000&auto=format&fit=crop']
                        ] as $index => $banner)

                            <div class="min-w-full h-full relative flex-shrink-0">
                                {{-- Background Image (Ganti $banner['img'] dengan asset path Anda) --}}
                                <img src="{{ $banner['img'] }}" class="w-full h-full object-cover opacity-80" alt="{{ $banner['title'] }}">

                                {{-- Dark Overlay Gradient --}}
                                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>

                                {{-- Banner Content --}}
                                <div class="absolute bottom-0 left-0 right-0 p-8 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                                    <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-600 text-white text-[10px] font-black rounded-full uppercase tracking-widest mb-4 shadow-[0_0_15px_rgba(37,99,235,0.5)]">
                                        <i class="fas fa-bolt text-[8px]"></i> Hot Promo
                                    </div>
                                    <h3 class="text-3xl font-black text-white mb-2 leading-tight tracking-tight drop-shadow-lg">{{ $banner['title'] }}</h3>
                                    <p class="text-zinc-300 text-sm font-medium line-clamp-2 pr-4">{{ $banner['desc'] }}</p>

                                    <div class="mt-5 opacity-0 group-hover:opacity-100 transition-opacity duration-500 delay-100">
                                        <a href="#" class="text-blue-400 font-bold text-sm flex items-center gap-2 hover:text-white transition-colors">Lihat Detail <i class="fas fa-arrow-right text-xs"></i></a>
                                    </div>
                                </div>
                            </div>

                        @empty
                            <div class="min-w-full h-full bg-zinc-900 flex items-center justify-center">
                                <p class="text-zinc-500 font-medium">Belum ada promo aktif</p>
                            </div>
                        @endforelse

                    </div>

                    {{-- Slider Controls (Arrows) --}}
                    <button onclick="moveSlider(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full glass-dark text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-blue-600 hover:scale-110 shadow-xl z-20"><i class="fas fa-chevron-left text-sm"></i></button>
                    <button onclick="moveSlider(1)" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full glass-dark text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-blue-600 hover:scale-110 shadow-xl z-20"><i class="fas fa-chevron-right text-sm"></i></button>

                    {{-- Slider Pagination (Dots) --}}
                    <div class="absolute top-6 right-6 flex gap-2 z-20" id="slider-dots">
                        {{-- Generated by JS --}}
                    </div>
                </div>
            </div>
        </div>

        {{-- Custom Shape Divider (Sharp/Tech edge) --}}
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none z-10">
            <svg class="relative block w-full h-[30px] lg:h-[60px]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M1200 120L0 120 0 0 1200 120z" class="fill-[#fafafa]"></path>
            </svg>
        </div>
    </section>

    <main class="container mx-auto px-4 py-16 lg:py-24 space-y-24">

        {{-- KATEGORI POPULER --}}
        <section>
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h2 class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase mb-3">Direktori Material</h2>
                <h3 class="text-3xl lg:text-4xl font-black text-black tracking-tight">Kategori Utama</h3>
                <div class="w-16 h-1.5 bg-black mx-auto mt-6 rounded-full"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @forelse($categories ?? [] as $cat)
                    <a href="{{ url('pages/produk?kategori=' . $cat->id) }}" class="group relative bg-white p-6 rounded-[2rem] shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_20px_40px_rgba(37,99,235,0.1)] transition-all duration-500 hover:-translate-y-2 flex flex-col items-center gap-5 border border-zinc-100">
                        <div class="relative w-16 h-16 rounded-2xl bg-zinc-50 text-black flex items-center justify-center text-2xl group-hover:bg-blue-600 group-hover:text-white transition-colors duration-500 shadow-inner">
                            <i class="{{ $cat->icon_class ?? 'fas fa-tools' }}"></i>
                        </div>
                        <p class="font-black text-zinc-800 text-center text-sm group-hover:text-blue-600 transition-colors">{{ $cat->nama_kategori }}</p>
                    </a>
                @empty
                    <div class="col-span-full text-center text-zinc-400 py-12 border border-dashed border-zinc-300 rounded-3xl font-medium">Kategori belum tersedia.</div>
                @endforelse
            </div>
        </section>

        {{-- TOKO POPULER (B&W Edition) --}}
        <section id="toko" class="relative">
            <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-zinc-200 pb-6 gap-4">
                <div>
                    <h2 class="text-xs font-black tracking-[0.2em] text-blue-600 uppercase mb-2">Partner Resmi</h2>
                    <h3 class="text-3xl lg:text-4xl font-black text-black tracking-tight">{{ $tokoSectionTitle ?? 'Mitra Terverifikasi' }}</h3>
                </div>
                <a href="{{ url('pages/semua_toko') }}" class="group inline-flex items-center gap-3 px-6 py-3 bg-zinc-950 hover:bg-blue-600 text-white font-bold rounded-xl transition-colors">
                    Semua Mitra <i class="fas fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($listToko ?? [] as $toko)
                    @php
                        $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
                        $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                        $initials = empty($toko->initials) ? strtoupper(substr(implode("", array_map(function($w){return $w[0];}, explode(" ", $toko->nama_toko ?? 'Toko Kami'))), 0, 2)) : $toko->initials;
                        // Pakai grayscale/monochrome untuk warna fallback toko
                        $bgStyle = $hasBanner ? "background-image: url(" . asset($bannerPath) . ");" : "background-color: #18181b;";
                        $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
                        $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                    @endphp

                    <a href="{{ url('pages/toko?slug=' . ($toko->slug ?? '#')) }}" class="group bg-white rounded-3xl shadow-[0_4px_15px_rgba(0,0,0,0.02)] hover:shadow-[0_20px_40px_rgba(0,0,0,0.08)] overflow-hidden transition-all duration-500 hover:-translate-y-2 border border-zinc-100 flex flex-col relative">
                        <div class="h-32 bg-cover bg-center relative transition-transform duration-700 group-hover:scale-105 filter grayscale group-hover:grayscale-0" style="{{ $bgStyle }}">
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors"></div>
                            {{-- PRO Badge --}}
                            <div class="absolute top-3 right-3 bg-black/60 backdrop-blur-md px-3 py-1.5 rounded-full text-[9px] font-black text-white flex items-center gap-1.5 border border-white/10">
                                <i class="fas fa-check-circle text-blue-500"></i> PRO
                            </div>
                        </div>

                        <div class="pt-10 pb-6 px-6 flex-1 flex flex-col relative bg-white z-10 rounded-t-[2rem] -mt-4">
                            {{-- Logo Avatar --}}
                            <div class="absolute -top-10 left-6">
                                @if($hasLogo)
                                    <img src="{{ asset($logoPath) }}" class="w-16 h-16 rounded-xl object-cover border-[3px] border-white shadow-md bg-white grayscale group-hover:grayscale-0 transition-all duration-500" alt="Logo">
                                @else
                                    <div class="w-16 h-16 rounded-xl text-white flex items-center justify-center font-black text-xl border-[3px] border-white shadow-md bg-zinc-900 transition-colors group-hover:bg-blue-600">
                                        {{ $initials }}
                                    </div>
                                @endif
                            </div>

                            <h4 class="font-black text-lg text-black group-hover:text-blue-600 transition-colors line-clamp-1">{{ $toko->nama_toko ?? 'Nama Toko' }}</h4>
                            <p class="text-zinc-500 text-xs mt-1 font-medium"><i class="fas fa-map-pin text-zinc-300 mr-1"></i> {{ $toko->kota ?? 'Indonesia' }}</p>

                            <div class="mt-6 pt-4 border-t border-zinc-100 flex items-center justify-between">
                                <span class="text-xs font-bold text-zinc-500 bg-zinc-50 px-3 py-1.5 rounded-lg border border-zinc-100">{{ $toko->jumlah_produk_aktif ?? 0 }} Item</span>
                                <div class="w-8 h-8 rounded-full bg-zinc-100 flex items-center justify-center text-zinc-400 group-hover:bg-black group-hover:text-white transition-colors duration-300">
                                    <i class="fas fa-arrow-right text-xs -rotate-45 group-hover:rotate-0 transition-transform"></i>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="col-span-full text-center text-zinc-500 py-12">Belum ada mitra toko.</div>
                @endforelse
            </div>
        </section>

        {{-- PRODUK GRID --}}
        @foreach(['listProdukLokal' => ['title' => 'Rekomendasi Area Anda', 'badge' => 'TERDEKAT'], 'listProdukNasional' => ['title' => 'Tren Nasional', 'badge' => 'TERLARIS']] as $varName => $config)
            @if(isset($$varName) && count($$varName) > 0)
            <section>
                <div class="flex items-end gap-4 mb-10 border-b border-zinc-200 pb-4">
                    <div class="w-3 h-10 bg-blue-600 rounded-full shadow-[0_0_15px_rgba(37,99,235,0.4)]"></div>
                    <div>
                        <span class="text-[10px] font-black tracking-widest text-zinc-400 uppercase">{{ $config['badge'] }}</span>
                        <h2 class="text-2xl lg:text-3xl font-black text-black tracking-tight leading-none mt-1">{{ $config['title'] }}</h2>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
                    @foreach($$varName as $p)
                        @php
                            $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                        @endphp
                        <a href="{{ route('produk.detail', $p->id) }}" class="group bg-white rounded-2xl shadow-[0_4px_15px_rgb(0,0,0,0.02)] overflow-hidden hover:shadow-[0_15px_30px_rgb(0,0,0,0.06)] transition-all duration-300 flex flex-col border border-zinc-100 hover:border-blue-100">

                            <div class="aspect-square bg-zinc-100 overflow-hidden relative">
                                <img src="{{ asset($img) }}" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?q=80&w=600&auto=format&fit=crop'" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out mix-blend-multiply" alt="{{ $p->nama_barang }}">
                            </div>

                            <div class="p-4 flex flex-col flex-1 bg-white">
                                <h3 class="text-sm font-semibold text-zinc-800 line-clamp-2 leading-snug group-hover:text-blue-600 transition-colors">{{ $p->nama_barang }}</h3>

                                <div class="mt-auto pt-3">
                                    <div class="text-lg font-black text-black tracking-tight group-hover:text-blue-600 transition-colors">Rp{{ number_format($p->harga, 0, ',', '.') }}</div>
                                    <div class="flex items-center gap-1.5 mt-2 text-[10px] font-bold text-zinc-400 uppercase tracking-wide">
                                        <i class="fas fa-store"></i> <span class="truncate">{{ $p->nama_toko }}</span>
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
        // Sisa logika chatbot sama seperti sebelumnya, hanya ganti style UI-nya
        // yang sekarang menggunakan kelas Black & Blue sesuai HTML di atas.
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
                // SESUAIKAN ENDPOINT API ANDA
                const res = await fetch('{{ url("/api/chat") }}', {
                    method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({message: text, history: chatHistory.slice(-6)})
                });
                if (!res.ok) throw new Error("Error");
                const data = await res.json();

                if(!isCallMode && document.getElementById('loading')) document.getElementById('loading').remove();
                appendMessage(data.reply, 'bot');
                chatHistory.push({sender:'bot', text: data.reply.replace(/<[^>]*>?/gm, '')});
                if(isCallMode) speakText(data.reply, true);
            } catch(e) {
                if(document.getElementById('loading')) document.getElementById('loading').remove();
                appendMessage("Jaringan server proyek sedang sibuk. Mohon coba lagi.", 'bot');
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
