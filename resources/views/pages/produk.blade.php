<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Jelajahi Produk - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    {{-- CSS Assets --}}
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/produk_page_style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Gaya tambahan untuk perbaikan UI */
        .hidden-category { display: none; }
        .pagination-wrap { margin-top: 30px; display: flex; justify-content: center; }
        .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f1f5f9; height: 100%; display: flex; flex-direction: column; text-decoration: none; color: inherit;}
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-color: #e2e8f0; }
        .product-image { width: 100%; aspect-ratio: 1/1; background: #f8fafc; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-details { padding: 12px; display: flex; flex-direction: column; flex-grow: 1; }
        .product-details h3 { font-size: 0.9rem; font-weight: 600; color: #334155; margin: 0 0 8px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .price { font-size: 1.15rem; font-weight: 800; color: #ef4444; margin: 0 0 10px 0; }
        .product-seller-info { margin-top: auto; display: flex; flex-direction: column; gap: 6px; font-size: 0.75rem; color: #64748b; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="filter-overlay" id="filter-overlay"></div>
    
    <div class="page-container">
        
        {{-- SIDEBAR KIRI: FORM FILTER --}}
        <aside class="sidebar-filters" id="sidebar-filters">
            <form action="{{ route('produk.index') }}" method="GET" id="filterForm">
                
                {{-- Pertahankan kata kunci pencarian jika ada --}}
                @if(request()->has('query'))
                    <input type="hidden" name="query" value="{{ request('query') }}">
                @endif

                <div class="filter-header">
                    <span><i class="mdi mdi-filter-variant"></i> FILTER</span>
                    <button type="button" class="close-filter-btn" id="close-filter-btn">&times;</button>
                </div>

                <div class="filter-group">
                    <h4 class="filter-title">KATEGORI</h4>
                    <div class="category-list">
                        @php
                            $counter = 0;
                            $limit = 7;
                        @endphp
                        
                        @foreach($categories as $k)
                            @php
                                $counter++;
                                $isChecked = in_array($k->id, $filter_kategori) ? 'checked' : '';
                                // Jika kategori ini sedang dicentang, JANGAN sembunyikan meskipun urutannya lebih dari 7
                                $isHidden = ($counter > $limit && empty($isChecked)) ? 'hidden-category' : '';
                            @endphp
                            <label class="filter-option {{ $isHidden }}">
                                <input type="checkbox" name="kategori[]" value="{{ $k->id }}" {{ $isChecked }}>
                                {{ $k->nama_kategori }}
                            </label>
                        @endforeach
                        
                        @if($categories->count() > $limit)
                            <div class="show-more-container">
                                <button type="button" id="toggle-categories" class="btn-show-more">
                                    Lihat Selengkapnya <i class="mdi mdi-chevron-down"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="filter-group">
                    <h4 class="filter-title">LOKASI TOKO</h4>
                    <div class="location-select-wrapper">
                        <select name="lokasi" class="filter-select">
                            <option value="">Semua Lokasi</option>
                            @foreach($locations as $l)
                                <option value="{{ $l->city_id }}" {{ $filter_lokasi == $l->city_id ? 'selected' : '' }}>
                                    {{ $l->nama_kota }}
                                </option>
                            @endforeach
                        </select>
                        <i class="mdi mdi-chevron-down select-icon"></i>
                    </div>
                </div>

                <div class="filter-group">
                    <h4 class="filter-title">RENTANG HARGA</h4>
                    <div class="filter-harga" style="display: flex; gap: 10px; flex-direction: column;">
                        <input type="number" name="harga_min" placeholder="Rp Minimum" value="{{ $filter_harga_min }}" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%;">
                        <input type="number" name="harga_max" placeholder="Rp Maksimum" value="{{ $filter_harga_max }}" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 6px; width: 100%;">
                    </div>
                </div>

                <button type="submit" class="apply-filter-btn" style="width: 100%; padding: 12px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; margin-top: 15px;">
                    Terapkan Filter
                </button>
                
                {{-- Tombol Reset Filter --}}
                @if(!empty($filter_kategori) || !empty($filter_lokasi) || !empty($filter_harga_min) || !empty($filter_harga_max))
                    <a href="{{ route('produk.index') }}" style="display: block; text-align: center; margin-top: 10px; color: #ef4444; font-size: 0.9rem; text-decoration: none;">
                        Hapus Filter
                    </a>
                @endif
            </form>
        </aside>

        {{-- KANAN: HASIL PRODUK --}}
        <main class="main-content">
            <div class="top-bar" style="margin-bottom: 20px;">
                <span>Menampilkan <strong>{{ $products->total() }} produk</strong> ditemukan.</span>
            </div>

            <div class="mobile-action-bar">
                <button class="mobile-btn" id="mobile-filter-btn"><i class="mdi mdi-filter-variant"></i> Filter</button>
            </div>

            <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                
                @forelse($products as $b)
                    <a href="{{ route('produk.detail', $b->id) }}" class="product-link">
                        <div class="product-card">
                            <div class="product-image">
                                <img src="{{ asset('assets/uploads/products/' . ($b->gambar_utama ?? 'default.jpg')) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';" alt="{{ $b->nama_barang }}">
                            </div>
                            <div class="product-details">
                                <h3>{{ \Illuminate\Support\Str::limit($b->nama_barang, 40) }}</h3>
                                <p class="price">Rp{{ number_format($b->harga, 0, ',', '.') }}</p>
                                
                                <div class="product-seller-info">
                                    <span class="store-name" style="color: #10b981; font-weight: 600;"><i class="fas fa-store"></i> {{ $b->nama_toko }}</span>
                                    <span class="store-location"><i class="fas fa-map-marker-alt"></i> {{ $b->nama_kota ?? 'Lokasi Tidak Tersedia' }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0;">
                        <i class="fas fa-box-open" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 15px;"></i>
                        <h3 style="color: #0f172a; margin: 0 0 10px 0;">Oops! Produk tidak ditemukan.</h3>
                        <p style="color: #64748b; margin: 0;">Coba ubah kata kunci atau filter pencarian Anda.</p>
                        <a href="{{ route('produk.index') }}" style="display: inline-block; margin-top: 15px; color: #2563eb; text-decoration: underline;">Reset Pencarian</a>
                    </div>
                @endforelse

            </div>

            {{-- KOMPONEN PAGINASI LARAVEL --}}
            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>

        </main>
    </div>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Filter Logic
            const mobileFilterBtn = document.getElementById('mobile-filter-btn');
            const sidebarFilters = document.getElementById('sidebar-filters');
            const closeFilterBtn = document.getElementById('close-filter-btn');
            const filterOverlay = document.getElementById('filter-overlay');
            
            if (mobileFilterBtn) {
                mobileFilterBtn.addEventListener('click', () => {
                    sidebarFilters.classList.add('active');
                    filterOverlay.classList.add('active');
                    document.body.style.overflow = 'hidden'; // Kunci scroll
                });
            }
            const hideFilter = () => {
                sidebarFilters.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
            };
            if (closeFilterBtn) closeFilterBtn.addEventListener('click', hideFilter);
            if (filterOverlay) filterOverlay.addEventListener('click', hideFilter);

            // Show More Categories Logic (Toggle)
            const toggleBtn = document.getElementById('toggle-categories');
            if(toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    const hiddenItems = document.querySelectorAll('.hidden-category');
                    const isExpanded = toggleBtn.classList.contains('expanded');

                    hiddenItems.forEach(item => {
                        // Ubah display block agar tampil seperti list biasa
                        item.style.display = isExpanded ? 'none' : 'block'; 
                    });

                    if (!isExpanded) {
                        toggleBtn.innerHTML = 'Sembunyikan <i class="mdi mdi-chevron-up"></i>';
                        toggleBtn.classList.add('expanded');
                    } else {
                        toggleBtn.innerHTML = 'Lihat Selengkapnya <i class="mdi mdi-chevron-down"></i>';
                        toggleBtn.classList.remove('expanded');
                    }
                });
            }
        });
    </script>

</body>
</html>