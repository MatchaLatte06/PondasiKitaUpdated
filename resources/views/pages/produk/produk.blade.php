@extends('layouts.app')

@section('title', 'Jelajahi Produk - Pondasikita')

@section('content')
{{-- === PERBAIKAN: Tambahkan CSS Navbar Disini === --}}
<link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}"> {{-- INI YANG TADINYA HILANG --}}
<link rel="stylesheet" href="{{ asset('assets/css/produk_page_style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">

<div class="filter-overlay" id="filter-overlay"></div>

<div class="page-container" style="margin-top: 80px; padding: 20px;">
    
    {{-- SIDEBAR FILTER --}}
    <aside class="sidebar-filters" id="sidebar-filters">
        <form action="{{ route('produk.index') }}" method="GET">
            
            {{-- Jika ada query pencarian dari navbar, ikutkan dalam filter --}}
            @if(request('query'))
                <input type="hidden" name="query" value="{{ request('query') }}">
            @endif

            <div class="filter-header">
                <span><i class="mdi mdi-filter-variant"></i> FILTER</span>
                <button type="button" class="close-filter-btn" id="close-filter-btn">&times;</button>
            </div>

            <div class="filter-group">
                <h4 class="filter-title">KATEGORI</h4>
                <div class="category-list">
                    @php $counter = 0; $limit = 7; @endphp
                    @foreach($categories as $k)
                        @php 
                            $counter++;
                            $isChecked = in_array($k->id, request('kategori', [])) ? 'checked' : '';
                            $hiddenClass = ($counter > $limit) ? 'hidden-category' : '';
                        @endphp
                        <label class="filter-option {{ $hiddenClass }}" style="{{ $counter > $limit ? 'display:none;' : 'display:flex;' }}">
                            <input type="checkbox" name="kategori[]" value="{{ $k->id }}" {{ $isChecked }}>
                            {{ $k->nama_kategori }}
                        </label>
                    @endforeach
                    
                    @if($counter > $limit)
                        <div class="show-more-container">
                            <button type="button" id="toggle-categories" class="btn-show-more" style="background:none; border:none; color:#007bff; cursor:pointer; margin-top:5px;">
                                Lihat Selengkapnya <i class="mdi mdi-chevron-down"></i>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-title">LOKASI TOKO</h4>
                <div class="location-select-wrapper">
                    <select name="lokasi" class="filter-select" style="width: 100%; padding: 8px; border-radius: 5px; border: 1px solid #ddd;">
                        <option value="">Semua Lokasi</option>
                        @foreach($locations as $l)
                            <option value="{{ $l->city_id }}" {{ request('lokasi') == $l->city_id ? 'selected' : '' }}>
                                {{ $l->nama_kota }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="filter-group">
                <h4 class="filter-title">HARGA</h4>
                <div class="filter-harga" style="display: flex; gap: 10px;">
                    <input type="number" name="harga_min" placeholder="Rp Min" value="{{ request('harga_min') }}" style="width: 50%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                    <input type="number" name="harga_max" placeholder="Rp Max" value="{{ request('harga_max') }}" style="width: 50%; padding: 8px; border: 1px solid #ddd; border-radius: 5px;">
                </div>
            </div>

            <button type="submit" class="apply-filter-btn" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; border-radius: 5px; margin-top: 15px; cursor: pointer;">Terapkan Filter</button>
            
            {{-- Tombol Reset --}}
            @if(request()->hasAny(['kategori', 'lokasi', 'harga_min', 'harga_max', 'query']))
                <a href="{{ route('produk.index') }}" style="display: block; text-align: center; margin-top: 10px; color: #666; text-decoration: none; font-size: 14px;">Reset Filter</a>
            @endif
        </form>
    </aside>

    {{-- KONTEN PRODUK --}}
    <main class="main-content" style="flex: 1;">
        <div class="top-bar" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span>Menampilkan <strong>{{ $products->total() }} produk</strong> ditemukan.</span>
            
            {{-- Tombol Filter Mobile --}}
            <button class="mobile-btn" id="mobile-filter-btn" style="display: none;">
                <i class="mdi mdi-filter-variant"></i> Filter
            </button>
        </div>

        <div class="products-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @forelse($products as $b)
                @php
                    $img = !empty($b->gambar_utama) ? 'assets/uploads/products/'.$b->gambar_utama : 'assets/uploads/products/default.jpg';
                @endphp
                <a href="{{ url('pages/detail_produk?id=' . $b->id . '&toko_slug=' . ($b->toko_slug ?? '#')) }}" class="product-link" style="text-decoration: none; color: inherit;">
                    <div class="product-card" style="border: 1px solid #eee; border-radius: 10px; overflow: hidden; transition: transform 0.2s; background: white;">
                        <div class="product-image" style="height: 200px; overflow: hidden;">
                            <img src="{{ asset($img) }}" alt="{{ $b->nama_barang }}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                        </div>
                        <div class="product-details" style="padding: 15px;">
                            <h3 style="font-size: 16px; margin: 0 0 10px; height: 40px; overflow: hidden;">{{ Str::limit($b->nama_barang, 40) }}</h3>
                            <p class="price" style="font-weight: bold; color: #d32f2f; margin: 0 0 10px;">Rp{{ number_format($b->harga, 0, ',', '.') }}</p>
                            <div class="product-seller-info" style="font-size: 12px; color: #666;">
                                <div class="store-name" style="margin-bottom: 3px;"><i class="mdi mdi-store"></i> {{ $b->nama_toko }}</div>
                                <div class="store-location"><i class="mdi mdi-map-marker"></i> {{ $b->nama_kota ?? 'Indonesia' }}</div>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="no-results" style="grid-column: 1 / -1; text-align: center; padding: 50px;">
                    <img src="{{ asset('assets/image/empty-box.png') }}" alt="Kosong" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
                    <h3>Oops! Produk tidak ditemukan.</h3>
                    <p>Coba ubah kata kunci atau filter pencarian Anda.</p>
                    <a href="{{ route('produk.index') }}" class="btn btn-primary" style="margin-top: 10px; display: inline-block;">Lihat Semua Produk</a>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION LARAVEL --}}
        <div style="margin-top: 40px; display: flex; justify-content: center;">
            {{ $products->links() }} 
        </div>
    </main>
</div>

{{-- SCRIPT JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mobile Filter Logic
        const mobileFilterBtn = document.getElementById('mobile-filter-btn');
        const sidebarFilters = document.getElementById('sidebar-filters');
        const closeFilterBtn = document.getElementById('close-filter-btn');
        const filterOverlay = document.getElementById('filter-overlay');
        
        // Cek ukuran layar untuk tombol mobile
        function checkMobile() {
            if (window.innerWidth <= 768) {
                if(mobileFilterBtn) mobileFilterBtn.style.display = 'block';
                if(sidebarFilters) sidebarFilters.classList.add('mobile-hidden');
            } else {
                if(mobileFilterBtn) mobileFilterBtn.style.display = 'none';
                if(sidebarFilters) sidebarFilters.classList.remove('mobile-hidden');
                if(sidebarFilters) sidebarFilters.classList.remove('active');
                if(filterOverlay) filterOverlay.classList.remove('active');
            }
        }
        
        checkMobile();
        window.addEventListener('resize', checkMobile);

        if (mobileFilterBtn) {
            mobileFilterBtn.addEventListener('click', () => {
                sidebarFilters.classList.add('active');
                sidebarFilters.classList.remove('mobile-hidden');
                filterOverlay.classList.add('active');
            });
        }
        
        const hideFilter = () => {
            sidebarFilters.classList.remove('active');
            sidebarFilters.classList.add('mobile-hidden');
            filterOverlay.classList.remove('active');
        };
        
        if (closeFilterBtn) closeFilterBtn.addEventListener('click', hideFilter);
        if (filterOverlay) filterOverlay.addEventListener('click', hideFilter);

        // Show More Categories Logic
        const toggleBtn = document.getElementById('toggle-categories');
        if(toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                const hiddenItems = document.querySelectorAll('.hidden-category');
                const isExpanded = toggleBtn.classList.contains('expanded');

                hiddenItems.forEach(item => {
                    item.style.display = isExpanded ? 'none' : 'flex';
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

<style>
    /* CSS Tambahan untuk Pagination & Mobile Responsiveness */
    .mobile-hidden { display: none; }
    
    @media (max-width: 768px) {
        .page-container { flex-direction: column; }
        .sidebar-filters { 
            position: fixed; top: 0; left: -100%; width: 80%; height: 100vh; 
            background: white; z-index: 1000; padding: 20px; transition: 0.3s; 
            overflow-y: auto; box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        .sidebar-filters.active { left: 0; }
        .filter-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        .filter-overlay.active { display: block; }
    }

    /* Style Pagination Bawaan Laravel (Tailwind/Bootstrap Friendly) */
    .pagination { display: flex; list-style: none; gap: 5px; }
    .pagination li a, .pagination li span {
        padding: 8px 12px; border: 1px solid #ddd; border-radius: 5px; 
        text-decoration: none; color: #333;
    }
    .pagination li.active span {
        background-color: #007bff; color: white; border-color: #007bff;
    }
</style>
@endsection