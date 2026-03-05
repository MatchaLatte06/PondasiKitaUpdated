<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jelajahi Toko - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- Menggunakan helper asset() Laravel untuk memanggil CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/toko_page.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Fallback CSS untuk Inisial Toko jika tidak ada di toko_page.css */
        .store-logo-initial {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 24px;
            color: white;
            text-transform: uppercase;
        }
        /* Style simpel untuk paginasi */
        .pagination-wrap { margin-top: 40px; display: flex; justify-content: center; }
    </style>
</head>
<body>

    {{-- Include Navbar --}}
    @include('partials.navbar')

    <div class="page-container">
        <main class="main-content" style="max-width: 1200px; margin: 30px auto; padding: 0 15px;">
            
            <div class="page-header" style="margin-bottom: 25px;">
                <h3 style="font-size: 1.8rem; color: #0f172a; margin: 0 0 5px 0;">Temukan Toko Bahan Bangunan Terbaik</h3>
                <p style="color: #64748b; margin: 0;">Jelajahi ribuan mitra toko terpercaya dari seluruh Indonesia.</p>
            </div>

            {{-- FILTER BAR --}}
            <div class="filter-bar" style="background: white; padding: 15px 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 15px; margin-bottom: 30px;">
                <span class="filter-title" style="font-weight: 600; color: #334155;"><i class="fas fa-filter"></i> Filter Lokasi:</span>
                
                {{-- Form Filter mengarah ke Route yang benar --}}
                <form action="{{ route('toko.index') }}" method="GET" style="display: flex; gap: 10px; flex-grow: 1; max-width: 400px;">
                    <select name="lokasi" class="form-select" style="flex: 1; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; outline: none;">
                        <option value="semua">Semua Kota</option>
                        @foreach($locations as $lokasi)
                            <option value="{{ $lokasi->city_id }}" {{ $filter_lokasi == $lokasi->city_id ? 'selected' : '' }}>
                                {{ $lokasi->city_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn-primary" style="padding: 10px 20px; background: #2563eb; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">Terapkan</button>
                </form>
            </div>

            {{-- DAFTAR TOKO --}}
            <div class="shops-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                
                @forelse($stores as $toko)
                    @php
                        // Logika Helper Inisial & Warna diubah ke Blade Native PHP block
                        $colors = ['#e53935', '#d81b60', '#8e24aa', '#5e35b1', '#3949ab', '#1e88e5', '#039be5', '#00acc1', '#00897b', '#43a047', '#7cb342', '#c0ca33', '#fdd835', '#ffb300', '#fb8c00', '#f4511e'];
                        $storeColor = $colors[crc32($toko->nama_toko) % count($colors)];
                        
                        $words = explode(" ", $toko->nama_toko);
                        $acronym = "";
                        foreach ($words as $w) { $acronym .= mb_substr($w, 0, 1); }
                        $storeInitials = strtoupper(substr($acronym, 0, 2)) ?: "TK";

                        // Setup Banner
                        $bannerPath = 'assets/uploads/banners/' . ($toko->banner_toko ?? '');
                        $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                        $bannerStyle = $hasBanner 
                            ? "background-image: url('".asset($bannerPath)."'); background-size: cover; background-position: center;" 
                            : "background-color: $storeColor; opacity: 0.9;";

                        // Setup Logo
                        $logoPath = 'assets/uploads/logos/' . ($toko->logo_toko ?? '');
                        $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                    @endphp

                    <a href="{{ route('toko.detail', ['slug' => $toko->slug]) }}" class="shop-card" style="display: block; background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; text-decoration: none; color: inherit; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                        
                        <div class="shop-banner" style="height: 100px; position: relative; {{ $bannerStyle }}">
                            <div class="shop-logo-wrapper" style="width: 70px; height: 70px; border-radius: 50%; border: 4px solid white; position: absolute; bottom: -35px; left: 20px; background: white; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                @if($hasLogo)
                                    <img src="{{ asset($logoPath) }}" alt="{{ $toko->nama_toko }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div class="store-logo-initial" style="background-color: {{ $storeColor }};">
                                        {{ $storeInitials }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="shop-info" style="padding: 45px 20px 20px 20px;">
                            <h4 class="shop-name" style="margin: 0 0 5px 0; font-size: 1.1rem; font-weight: 700; color: #0f172a;">{{ $toko->nama_toko }}</h4>
                            <p class="shop-location" style="margin: 0 0 15px 0; font-size: 0.85rem; color: #64748b;">
                                <i class="fas fa-map-marker-alt" style="color: #2563eb;"></i> {{ $toko->city_name }}
                            </p>
                            
                            <div class="shop-stats" style="display: flex; gap: 15px; border-top: 1px dashed #e2e8f0; padding-top: 15px; font-size: 0.85rem; color: #475569;">
                                <div class="stat-item">
                                    <strong style="color: #0f172a;">{{ number_format($toko->jumlah_produk) }}</strong> Produk
                                </div>
                                <div class="stat-item">
                                    <i class="fas fa-star" style="color: #f59e0b;"></i> 
                                    <strong style="color: #0f172a;">{{ number_format($toko->rating, 1) }}</strong> Rating
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    {{-- EMPTY STATE JIKA LOKASI TIDAK ADA TOKO --}}
                    <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <i class="fas fa-store-slash" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                        <h3 style="color: #0f172a; margin: 0 0 10px 0;">Oops! Toko tidak ditemukan</h3>
                        <p style="color: #64748b; margin: 0;">Belum ada toko yang terdaftar di lokasi pencarian ini.</p>
                        
                        @if($filter_lokasi !== 'semua')
                            <a href="{{ route('toko.index') }}" style="display: inline-block; margin-top: 15px; background: #f8fafc; color: #2563eb; padding: 8px 20px; border-radius: 8px; text-decoration: none; font-weight: 600; border: 1px solid #e2e8f0;">
                                Tampilkan Semua Kota
                            </a>
                        @endif
                    </div>
                @endforelse

            </div>

            {{-- KOMPONEN PAGINASI LARAVEL --}}
            <div class="pagination-wrap">
                {{ $stores->links() }}
            </div>

        </main>
    </div>

    {{-- Include Footer & JS --}}
    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
</body>
</html>