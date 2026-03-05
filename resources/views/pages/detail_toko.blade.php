<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $toko->nama_toko }} - Pondasikita</title>
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .store-container { max-width: 1200px; margin: 30px auto; padding: 0 15px; }

        /* =========================================
           STORE HEADER BANNER PROFILE
           ========================================= */
        .store-profile-header {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.04);
            margin-bottom: 30px;
            border: 1px solid #e2e8f0;
        }

        .store-banner-img {
            width: 100%;
            height: 220px;
            background-color: #cbd5e1;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .store-banner-img::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.6), transparent);
        }

        .store-info-bar {
            display: flex;
            align-items: flex-start;
            padding: 20px 30px;
            position: relative;
            gap: 25px;
        }

        .store-avatar-box {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-top: -60px; /* Bikin avatar nembus ke banner atas */
            z-index: 2;
            overflow: hidden;
            flex-shrink: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .store-avatar-box img { width: 100%; height: 100%; object-fit: cover; }
        .store-initials { width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: white; font-size: 36px; font-weight: 900; }

        .store-details { flex: 1; padding-top: 5px;}
        .store-name { font-size: 1.6rem; font-weight: 800; color: #0f172a; margin: 0 0 5px 0; display: flex; align-items: center; gap: 8px;}
        .badge-pro { background: #10b981; color: white; font-size: 0.75rem; padding: 3px 8px; border-radius: 4px; font-weight: 700; display: flex; align-items: center; gap: 4px; }
        
        .store-meta { display: flex; gap: 20px; color: #64748b; font-size: 0.95rem; margin-top: 10px; font-weight: 500;}
        .meta-item { display: flex; align-items: center; gap: 6px; }
        .meta-item i { color: #3b82f6; }

        .store-actions { display: flex; gap: 10px; padding-top: 10px;}
        .btn-chat { background: white; border: 1px solid #3b82f6; color: #3b82f6; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;}
        .btn-chat:hover { background: #eff6ff; }
        .btn-follow { background: #3b82f6; border: none; color: white; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;}
        .btn-follow:hover { background: #2563eb; }

        /* =========================================
           PRODUCT GRID TAMPILAN
           ========================================= */
        .section-title { font-size: 1.35rem; font-weight: 800; color: #0f172a; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px; }
        .section-title::before { content: ''; display: block; width: 5px; height: 22px; background-color: #3b82f6; border-radius: 5px; }

        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 18px; margin-bottom: 40px;}
        
        .product-link { text-decoration: none; color: inherit; display: block; height: 100%; }
        .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f1f5f9; height: 100%; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-color: #e2e8f0; }
        .product-image { width: 100%; aspect-ratio: 1/1; background: #f8fafc; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-details { padding: 15px; display: flex; flex-direction: column; flex-grow: 1; }
        .product-details h3 { font-size: 0.95rem; font-weight: 600; color: #334155; margin: 0 0 10px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .price { font-size: 1.2rem; font-weight: 800; color: #ef4444; margin: 0 0 10px 0; }
        .product-footer { margin-top: auto; border-top: 1px dashed #e2e8f0; padding-top: 10px; font-size: 0.8rem; color: #94a3b8; display: flex; justify-content: space-between; }

        .empty-state { text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
        .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 15px; }

        @media (max-width: 768px) {
            .store-info-bar { flex-direction: column; align-items: center; text-align: center; padding: 15px 20px; gap: 15px;}
            .store-avatar-box { margin-top: -70px; }
            .store-name { justify-content: center; }
            .store-meta { justify-content: center; flex-wrap: wrap; }
            .store-actions { justify-content: center; width: 100%; }
            .store-actions button { flex: 1; }
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <main class="store-container">
        
        {{-- 1. STORE HEADER PROFILE --}}
        @php
            $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
            $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
            $bgBanner = $hasBanner ? asset($bannerPath) : 'https://placehold.co/1200x300/1e293b/ffffff?text=Toko+Material';
            
            $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
            $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
        @endphp

        <div class="store-profile-header">
            {{-- Banner Latar Belakang --}}
            <div class="store-banner-img" style="background-image: url('{{ $bgBanner }}');"></div>
            
            {{-- Info Bar --}}
            <div class="store-info-bar">
                
                {{-- Foto Profil / Logo --}}
                <div class="store-avatar-box">
                    @if($hasLogo)
                        <img src="{{ asset($logoPath) }}" alt="Logo {{ $toko->nama_toko }}">
                    @else
                        <div class="store-initials" style="background-color: {{ $storeColor }};">
                            {{ $storeInitials }}
                        </div>
                    @endif
                </div>

                {{-- Detail Nama & Stats --}}
                <div class="store-details">
                    <h1 class="store-name">
                        {{ $toko->nama_toko }} 
                        <span class="badge-pro"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                    </h1>
                    
                    <div class="store-meta">
                        <div class="meta-item">
                            <i class="fas fa-map-marker-alt"></i> {{ $toko->kota ?? 'Lokasi tidak diketahui' }}
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-box-open"></i> {{ $products->total() }} Produk Aktif
                        </div>
                        <div class="meta-item">
                            <i class="fas fa-clock"></i> Buka: {{ $toko->jam_buka ?? '08:00' }} - {{ $toko->jam_tutup ?? '17:00' }}
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="store-actions">
                    <button class="btn-chat" onclick="alert('Fitur Chat Segera Hadir')"><i class="fas fa-comment-dots"></i> Chat Toko</button>
                    {{-- <button class="btn-follow"><i class="fas fa-plus"></i> Ikuti Toko</button> --}}
                </div>
            </div>
        </div>

        {{-- 2. DAFTAR PRODUK TOKO INI --}}
        <h2 class="section-title">Katalog Produk</h2>

        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $p)
                    @php
                        $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                    @endphp
                    <a href="{{ route('produk.detail', $p->id) }}" class="product-link">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ asset($img) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                            </div>
                            <div class="product-details">
                                <h3>{{ \Illuminate\Support\Str::limit($p->nama_barang, 40) }}</h3>
                                <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                
                                <div class="product-footer">
                                    <span style="color: #f59e0b;"><i class="fas fa-star"></i> 5.0</span>
                                    <span>Terjual 10+</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Paginasi --}}
            <div style="display: flex; justify-content: center; margin-bottom: 50px;">
                {{ $products->links() }}
            </div>

        @else
            <div class="empty-state">
                <i class="fas fa-store-slash"></i>
                <h3>Toko ini belum memiliki produk aktif</h3>
                <p style="color: #64748b; margin-top: 10px;">Penjual mungkin sedang mengatur etalasenya. Silakan kembali lagi nanti.</p>
            </div>
        @endif

    </main>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>