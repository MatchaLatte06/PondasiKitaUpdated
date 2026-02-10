<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondasikita - Marketplace Bahan Bangunan</title>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Masukkan CSS custom Anda di sini (Typewriter, Chatbot, Store Card) */
        /* ... (Copy CSS dari file lama Anda ke sini) ... */
        
        /* Contoh Typewriter CSS yang Anda miliki: */
        .typing-wrapper { display: inline-block; }
        .typing-text { font-weight: bold; color: #fff; border-bottom: 2px solid transparent; }
        .typing-cursor { display: inline-block; width: 3px; background-color: #fff; animation: blink 0.7s infinite; margin-left: 2px; }
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0; } 100% { opacity: 1; } }
        /* Lanjutkan copy CSS lainnya... */
        
        .store-card { position: relative; overflow: hidden; display: block; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: white; transition: transform 0.2s; text-decoration: none; color: inherit; }
        .store-card:hover { transform: translateY(-5px); }
        .store-banner { height: 100px; background-size: cover; background-position: center; position: relative; }
        .store-info { padding: 35px 15px 15px 15px; position: relative; }
        .store-logo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: absolute; bottom: -30px; left: 20px; z-index: 2; background: white; }
        .store-logo-initial { width: 60px; height: 60px; border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 22px; text-transform: uppercase; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: absolute; bottom: -30px; left: 20px; z-index: 2; }
    </style>
</head>
<body>
    
    {{-- Navbar (Pastikan file resources/views/partials/navbar.blade.php sudah ada) --}}
    @include('partials.navbar')

    <section class="hero-banner">
        <div class="container">
            <div class="hero-content">
                <h2>
                    <span class="typing-text"></span><span class="typing-cursor">&nbsp;</span>
                </h2>
                <h3>Temukan semua kebutuhan proyek Anda dari toko-toko terpercaya.</h3>
                {{-- Gunakan route() atau url() --}}
                <a href="{{ url('pages/produk') }}" class="btn-primary">Jelajahi Produk</a>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            
            {{-- KATEGORI --}}
            <section class="categories">
                <h2 class="section-title"><span>Kategori Populer</span></h2>
                <div class="category-grid">
                    @forelse($categories as $cat)
                        <a href="{{ url('pages/produk?kategori=' . $cat->id) }}" class="category-item">
                            <div class="category-icon">
                                <i class="{{ $cat->icon_class ?? 'fas fa-tools' }}"></i>
                            </div>
                            <p>{{ $cat->nama_kategori }}</p>
                        </a>
                    @empty
                        <p>Kategori kosong.</p>
                    @endforelse
                </div>
            </section>

            {{-- FEATURED STORES --}}
            <section class="featured-stores">
                <div class="section-header">
                    <h2 class="section-title"><span>{{ $tokoSectionTitle }}</span></h2>
                    <a href="{{ url('pages/semua_toko') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="store-grid">
                    @forelse($listToko as $toko)
                        @php
                            $bannerPath = 'assets/uploads/banners/' . $toko->banner_toko;
                            $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                            $bgStyle = $hasBanner ? "background-image: url(" . asset($bannerPath) . ");" : "background-color: " . $toko->color . "; opacity: 0.8;";

                            $logoPath = 'assets/uploads/logos/' . $toko->logo_toko;
                            $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                        @endphp

                        <a href="{{ url('pages/toko?slug=' . $toko->slug) }}" class="store-card">
                            <div class="store-banner" style="{{ $bgStyle }}">
                                @if($hasLogo)
                                    <img src="{{ asset($logoPath) }}" class="store-logo" alt="Logo">
                                @else
                                    <div class="store-logo-initial" style="background-color: {{ $toko->color }};">
                                        {{ $toko->initials }}
                                    </div>
                                @endif
                            </div>

                            <div class="store-info">
                                <h4>{{ $toko->nama_toko }}</h4>
                                <p><i class="fas fa-map-marker-alt"></i> {{ $toko->kota }}</p>
                                <p class="product-count">{{ $toko->jumlah_produk_aktif }} Produk</p>
                            </div>
                        </a>
                    @empty
                        <p>Belum ada toko tersedia.</p>
                    @endforelse
                </div>
            </section>

            {{-- PRODUK LOKAL (Hanya tampil jika ada datanya) --}}
            @if(count($listProdukLokal) > 0)
            <section class="products">
                <div class="section-header">
                    <h2 class="section-title"><span>Produk Terlaris di Wilayah Anda</span></h2>
                    <a href="{{ url('pages/produk') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="product-grid">
                    @foreach($listProdukLokal as $p)
                        @php
                            $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                        @endphp
                        <a href="{{ url('pages/detail_produk?id=' . $p->id . '&toko_slug=' . $p->slug_toko) }}" class="product-link">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset($img) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                </div>
                                <div class="product-details">
                                    <h3>{{ Str::limit($p->nama_barang, 40) }}</h3>
                                    <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                    <div class="product-store-info"><i class="fas fa-store-alt"></i> <span>{{ $p->nama_toko }}</span></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- PRODUK NASIONAL --}}
            <section id="nasional-content" class="products">
                <div class="section-header">
                    <h2 class="section-title"><span>Produk Terlaris Nasional</span></h2>
                    <a href="{{ url('pages/produk') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="product-grid">
                    @forelse($listProdukNasional as $p)
                        @php
                            $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                        @endphp
                        <a href="{{ url('pages/detail_produk?id=' . $p->id . '&toko_slug=' . $p->slug_toko) }}" class="product-link">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset($img) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                </div>
                                <div class="product-details">
                                    <h3>{{ Str::limit($p->nama_barang, 40) }}</h3>
                                    <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                    <div class="product-store-info"><i class="fas fa-store-alt"></i> <span>{{ $p->nama_toko }}</span></div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p>Belum ada produk terlaris.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </main>
    
    {{-- Footer --}}
    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>

    {{-- Chatbot Button HTML --}}
    <button id="live-chat-toggle" class="live-chat-toggle" onclick="toggleChat()">
        <i class="fas fa-robot"></i> <span class="chat-toggle-text">Tanya POTA</span>
    </button>
    
    {{-- Chatbot Window HTML (Copy paste isi HTML Chatbot POTA Anda di sini, sama persis) --}}
    <div id="live-chat-window" class="live-chat-window">
        <div class="chat-messages" id="chat-messages">
             <div class="chat-message bot">Halo {{ Auth::user()->nama ?? 'Tamu' }}! Saya POTA. Tekan tombol telepon 📞 di atas untuk ngobrol langsung, atau ketik di bawah ya!</div>
        </div>
        </div>

    {{-- SCRIPTS (Chatbot JS & Typewriter JS) --}}
    <script>
        const typingText = document.querySelector(".typing-text");
        const phrases = [
            "Cari Bahan Bangunan?", 
            "Renovasi Rumah Impian?", 
            "Solusi Material Terlengkap", 
            "Harga Terbaik & Terpercaya",
            "Belanja Mudah dari Rumah"
        ];

        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 100;

        function typeEffect() {
            const currentPhrase = phrases[phraseIndex];

            if (isDeleting) {
                typingText.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 50; 
            } else {
                typingText.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 100;
            }

            if (!isDeleting && charIndex === currentPhrase.length) {
                isDeleting = true;
                typeSpeed = 2000; 
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typeSpeed = 500;
            }

            setTimeout(typeEffect, typeSpeed);
        }

        document.addEventListener("DOMContentLoaded", typeEffect);
    </script>

</body>
</html>