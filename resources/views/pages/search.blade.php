<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Pencarian: {{ request('query') }} - Pondasikita</title>
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
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .search-container { max-width: 1200px; margin: 40px auto; padding: 0 15px; display: flex; gap: 30px; align-items: flex-start;}
        
        /* HEADER HASIL */
        .search-header { margin-bottom: 20px; width: 100%; }
        .search-header h1 { font-size: 1.5rem; color: #0f172a; font-weight: 800; margin: 0; }
        .search-header p { font-size: 0.95rem; color: #64748b; margin-top: 5px; }

        /* SIDEBAR FILTER KATEGORI */
        .filter-sidebar { width: 250px; background: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; position: sticky; top: 90px; flex-shrink: 0; }
        .filter-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; }
        
        .cat-list { list-style: none; padding: 0; margin: 0; }
        .cat-list li { margin-bottom: 5px; }
        .cat-link { display: flex; justify-content: space-between; align-items: center; padding: 8px 10px; color: #475569; text-decoration: none; border-radius: 6px; font-size: 0.95rem; transition: 0.2s; }
        .cat-link:hover { background: #f1f5f9; color: #2563eb; }
        .cat-link.active { background: #eff6ff; color: #2563eb; font-weight: 700; border-left: 3px solid #2563eb; border-radius: 0 6px 6px 0;}

        /* HASIL PRODUK KANAN */
        .product-area { flex: 1; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        
        /* Product Card sama persis dengan halaman Beranda */
        .product-link { text-decoration: none; color: inherit; display: block; height: 100%; }
        .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.05); transition: 0.3s; border: 1px solid #f1f5f9; height: 100%; display: flex; flex-direction: column; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-color: #e2e8f0; }
        .product-image { width: 100%; aspect-ratio: 1/1; background: #f8fafc; }
        .product-image img { width: 100%; height: 100%; object-fit: cover; }
        .product-details { padding: 12px; display: flex; flex-direction: column; flex-grow: 1; }
        .product-details h3 { font-size: 0.9rem; font-weight: 600; color: #334155; margin: 0 0 8px 0; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .price { font-size: 1.15rem; font-weight: 800; color: #ef4444; margin: 0 0 10px 0; }
        .product-footer { margin-top: auto; display: flex; flex-direction: column; gap: 6px; }
        .product-location { font-size: 0.75rem; color: #64748b; display: flex; align-items: center; gap: 4px; }
        
        /* EMPTY STATE */
        .empty-state { text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px solid #e2e8f0; }
        .empty-state i { font-size: 4rem; color: #cbd5e1; margin-bottom: 15px; }
        .empty-state h3 { font-size: 1.2rem; color: #0f172a; margin: 0 0 10px 0; }
        .empty-state p { color: #64748b; font-size: 0.95rem; margin: 0; }

        /* TOMBOL LIHAT SELENGKAPNYA */
        .view-more-btn {
            background: transparent;
            border: none;
            color: #2563eb;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 10px;
            width: 100%;
            text-align: left;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 6px;
            margin-top: 5px;
            transition: all 0.2s ease;
        }
        .view-more-btn:hover {
            background-color: #eff6ff;
            color: #1e3a8a;
        }

        /* PAGINASI */
        .pagination-wrap { margin-top: 30px; display: flex; justify-content: center; }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .search-container { flex-direction: column; }
            .filter-sidebar { width: 100%; position: relative; top: 0; }
        }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px]">

    @include('partials.navbar')

    <div class="search-container">
        
       {{-- SIDEBAR KIRI: FILTER KATEGORI --}}
        <aside class="filter-sidebar">
            <h2 class="filter-title"><i class="fas fa-filter"></i> Kategori</h2>
            
            @php
                // Logika Pintar: Cek apakah kategori yang sedang diklik ada di urutan > 5
                $isExpanded = false;
                if (!empty($kategoriId)) {
                    $activeIndex = $categories->search(function($cat) use ($kategoriId) {
                        return $cat->id == $kategoriId;
                    });
                    if ($activeIndex >= 5) {
                        $isExpanded = true;
                    }
                }
            @endphp

            <ul class="cat-list">
                {{-- Tombol Semua Kategori (Reset Filter) --}}
                <li>
                    <a href="{{ route('search', ['query' => $keyword]) }}" class="cat-link {{ empty($kategoriId) ? 'active' : '' }}">
                        Semua Kategori
                    </a>
                </li>
                
                {{-- Tampilkan HANYA 5 Kategori Pertama --}}
                @foreach($categories->take(5) as $cat)
                    <li>
                        <a href="{{ route('search', ['query' => $keyword, 'kategori' => $cat->id]) }}" 
                           class="cat-link {{ $kategoriId == $cat->id ? 'active' : '' }}">
                            {{ $cat->nama_kategori }}
                        </a>
                    </li>
                @endforeach

                {{-- Jika kategori lebih dari 5, sembunyikan sisanya di dalam div ini --}}
                @if($categories->count() > 5)
                    <div id="hidden-categories" style="display: {{ $isExpanded ? 'block' : 'none' }};">
                        @foreach($categories->skip(5) as $cat)
                            <li>
                                <a href="{{ route('search', ['query' => $keyword, 'kategori' => $cat->id]) }}" 
                                   class="cat-link {{ $kategoriId == $cat->id ? 'active' : '' }}">
                                    {{ $cat->nama_kategori }}
                                </a>
                            </li>
                        @endforeach
                    </div>

                    {{-- Tombol View More --}}
                    <li>
                        <button type="button" id="btn-toggle-cat" class="view-more-btn" onclick="toggleCategories()">
                            @if($isExpanded)
                                Sembunyikan <i class="fas fa-chevron-up"></i>
                            @else
                                Lihat Selengkapnya <i class="fas fa-chevron-down"></i>
                            @endif
                        </button>
                    </li>
                @endif
            </ul>
        </aside>

        {{-- KANAN: HASIL PENCARIAN --}}
        <div class="product-area">
            
            <div class="search-header">
                @if(!empty($keyword))
                    <h1>Hasil pencarian untuk "{{ $keyword }}"</h1>
                @else
                    <h1>Semua Produk</h1>
                @endif
                <p>Menampilkan {{ $products->total() }} produk yang relevan.</p>
            </div>

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
                                        <div class="product-location">
                                            <i class="fas fa-map-marker-alt" style="color: #94a3b8;"></i> {{ $p->kota_toko ?? 'Indonesia' }}
                                        </div>
                                        <div class="product-location" style="color: #10b981; font-weight: 600;">
                                            <i class="fas fa-store"></i> {{ $p->nama_toko }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Komponen Paginasi Laravel (Next/Prev Page) --}}
                <div class="pagination-wrap">
                    {{ $products->links() }}
                </div>

            @else
                {{-- Jika Produk Tidak Ditemukan --}}
                <div class="empty-state">
                    <i class="fas fa-search-minus"></i>
                    <h3>Oops, barang tidak ditemukan</h3>
                    <p>Coba gunakan kata kunci yang lebih umum atau ejaan yang berbeda.</p>
                </div>
            @endif

        </div>

    </div>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script>
        // Logika untuk menampilkan dan menyembunyikan sisa kategori
        function toggleCategories() {
            const hiddenDiv = document.getElementById('hidden-categories');
            const btnToggle = document.getElementById('btn-toggle-cat');

            if (hiddenDiv.style.display === 'none') {
                // Munculkan
                hiddenDiv.style.display = 'block';
                btnToggle.innerHTML = 'Sembunyikan <i class="fas fa-chevron-up"></i>';
            } else {
                // Sembunyikan
                hiddenDiv.style.display = 'none';
                btnToggle.innerHTML = 'Lihat Selengkapnya <i class="fas fa-chevron-down"></i>';
            }
        }
    </script>
</body>
</html>