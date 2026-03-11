<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Material - Pondasikita</title>
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
                        'card': '0 1px 6px 0 rgba(49,53,59,0.12)',
                        'card-hover': '0 4px 12px 0 rgba(49,53,59,0.2)',
                        'mobile-drawer': '4px 0 24px rgba(0,0,0,0.15)',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Background abu-abu muda khas e-commerce */
        body { font-family: 'Inter', sans-serif; background-color: #f5f5f5; }

        /* Scrollbar Minimalis */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 4px;}
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }

        /* Sembunyikan Panah di Input Number */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Custom Checkbox UI */
        .custom-checkbox { appearance: none; background-color: #fff; margin: 0; width: 1.25rem; height: 1.25rem; border: 2px solid #cbd5e1; border-radius: 0.25rem; display: grid; place-content: center; cursor: pointer; transition: all 0.2s ease-in-out; flex-shrink: 0; }
        .custom-checkbox::before { content: ""; width: 0.65rem; height: 0.65rem; transform: scale(0); transition: 120ms transform ease-in-out; background-color: white; transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%); }
        .custom-checkbox:checked { background-color: #2563eb; border-color: #2563eb; }
        .custom-checkbox:checked::before { transform: scale(1); }

        /* Pagination Fix */
        .pagination-wrap nav { display: flex; justify-content: center; width: 100%; margin: 2.5rem 0; }
        .pagination-wrap .pagination { display: flex; gap: 0.5rem; background: white; padding: 0.5rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .pagination-wrap .page-item .page-link { display: flex; align-items: center; justify-content: center; min-width: 2.5rem; height: 2.5rem; border-radius: 0.5rem; font-weight: 600; color: #4b5563; padding: 0 0.5rem; transition: all 0.2s; }
        .pagination-wrap .page-item:not(.active) .page-link:hover { background: #f3f4f6; color: #111827; }
        .pagination-wrap .page-item.active .page-link { background: #2563eb; color: white; border-color: #2563eb; }
    </style>
</head>
<body class="text-gray-800 antialiased pt-[80px]"> {{-- Padding Top yang FIX untuk menahan Navbar Fixed --}}

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- BREADCRUMB (Full Width, Bagian Paling Atas Sendiri) --}}
    <div class="bg-white border-b border-gray-200 shadow-sm relative z-10 hidden lg:block">
        <div class="max-w-[1250px] mx-auto px-4 lg:px-8 py-3">
            <nav class="flex text-sm text-gray-500 font-medium items-center gap-2">
                <a href="{{ url('/') }}" class="hover:text-brand-600 transition-colors">Beranda</a>
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <a href="{{ route('produk.index') }}" class="text-brand-600 font-semibold">Katalog Material</a>

                @if(request()->has('query') && request('query') != '')
                <i class="fas fa-chevron-right text-[10px] text-gray-400"></i>
                <span class="text-gray-800 font-semibold">Pencarian: "{{ request('query') }}"</span>
                @endif
            </nav>
        </div>
    </div>

    {{-- KONTEN UTAMA (Wrapper Layout Flex Row) --}}
    <div class="max-w-[1250px] mx-auto px-4 lg:px-8 py-6 flex flex-col lg:flex-row items-start gap-6">

        {{-- ========================================================= --}}
        {{-- MOBILE OVERLAY --}}
        {{-- ========================================================= --}}
        <div id="filter-overlay" class="fixed inset-0 bg-black/60 z-[60] hidden lg:hidden backdrop-blur-sm"></div>

        {{-- ========================================================= --}}
        {{-- SIDEBAR FILTER (KIRI) --}}
        {{-- ========================================================= --}}
        <aside id="sidebar-filters" class="fixed inset-y-0 left-0 z-[70] w-[280px] bg-white transform -translate-x-full transition-transform duration-300 ease-in-out flex flex-col shadow-mobile-drawer lg:relative lg:translate-x-0 lg:w-[250px] lg:shadow-none lg:bg-transparent lg:z-0 lg:shrink-0">

            {{-- Header Mobile Filter --}}
            <div class="flex items-center justify-between p-4 border-b border-gray-200 lg:hidden bg-white shrink-0">
                <h3 class="text-lg font-bold text-gray-800">Filter Pencarian</h3>
                <button type="button" id="close-filter-btn" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-100 rounded-full">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            {{-- Form Wrapper (Sticky on Desktop) --}}
            <div class="lg:sticky lg:top-28 w-full h-full lg:h-auto flex flex-col bg-white lg:rounded-xl lg:shadow-card lg:border lg:border-gray-200 overflow-hidden">

                {{-- Header Desktop Filter --}}
                <div class="hidden lg:flex items-center justify-between p-4 border-b border-gray-200 bg-white">
                    <h3 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Filter</h3>
                    @if(request()->except('page'))
                        <a href="{{ route('produk.index') }}" class="text-xs font-bold text-red-500 hover:text-red-700">Reset Filter</a>
                    @endif
                </div>

                {{-- Form Element --}}
                <form action="{{ route('produk.index') }}" method="GET" id="filterForm" class="flex-1 overflow-y-auto custom-scrollbar p-4 flex flex-col gap-6">
                    @if(request()->has('query'))
                        <input type="hidden" name="query" value="{{ request('query') }}">
                    @endif

                    {{-- FILTER: JENIS TOKO --}}
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm">Jenis Toko</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" class="custom-checkbox" onchange="showApplyButton()">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 select-none flex items-center gap-2">
                                    <i class="fas fa-crown text-purple-600 w-4"></i> Official Store
                                </span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" class="custom-checkbox" onchange="showApplyButton()">
                                <span class="text-sm text-gray-600 group-hover:text-gray-900 select-none flex items-center gap-2">
                                    <i class="fas fa-check-circle text-green-500 w-4"></i> Pro Merchant
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-200"></div>

                    {{-- FILTER: KATEGORI --}}
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm">Kategori</h4>
                        <div id="filter-kategori" class="space-y-3">
                            @php $limit = 5; $counter = 0; @endphp
                            @foreach($categories as $k)
                                @php
                                    $counter++;
                                    $isChecked = in_array($k->id, $filter_kategori ?? []) ? 'checked' : '';
                                    $isHidden = ($counter > $limit && empty($isChecked)) ? 'hidden' : '';
                                @endphp
                                <label class="flex items-start gap-3 cursor-pointer group cat-item {{ $isHidden }}">
                                    <input type="checkbox" name="kategori[]" value="{{ $k->id }}" class="custom-checkbox mt-0.5" {{ $isChecked }} onchange="showApplyButton()">
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 select-none line-clamp-2 leading-snug">{{ $k->nama_kategori }}</span>
                                </label>
                            @endforeach

                            @if($categories->count() > $limit)
                                <button type="button" id="btn-more-cat" class="text-xs font-bold text-brand-600 hover:text-brand-700 w-full text-left mt-2 flex items-center gap-1">
                                    Lihat Selengkapnya <i class="fas fa-chevron-down"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-200"></div>

                    {{-- FILTER: LOKASI --}}
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm">Lokasi</h4>
                        <div class="max-h-[180px] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                            @foreach($locations as $l)
                                <label class="flex items-center gap-3 cursor-pointer group">
                                    <input type="checkbox" name="lokasi[]" value="{{ $l->city_id }}" class="custom-checkbox" {{ ($filter_lokasi ?? '') == $l->city_id ? 'checked' : '' }} onchange="showApplyButton()">
                                    <span class="text-sm text-gray-600 group-hover:text-gray-900 select-none">{{ $l->nama_kota }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="w-full h-px bg-gray-200"></div>

                    {{-- FILTER: HARGA --}}
                    <div>
                        <h4 class="font-bold text-gray-800 mb-3 text-sm">Rentang Harga</h4>
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex items-center bg-white border border-gray-300 rounded-md px-2 py-1.5 focus-within:border-brand-500 flex-1">
                                <span class="text-gray-400 text-xs font-bold mr-1">Rp</span>
                                <input type="number" name="harga_min" placeholder="MIN" value="{{ $filter_harga_min ?? '' }}" class="w-full border-none outline-none text-xs p-0" oninput="showApplyButton()">
                            </div>
                            <span class="text-gray-400 font-bold">-</span>
                            <div class="flex items-center bg-white border border-gray-300 rounded-md px-2 py-1.5 focus-within:border-brand-500 flex-1">
                                <span class="text-gray-400 text-xs font-bold mr-1">Rp</span>
                                <input type="number" name="harga_max" placeholder="MAX" value="{{ $filter_harga_max ?? '' }}" class="w-full border-none outline-none text-xs p-0" oninput="showApplyButton()">
                            </div>
                        </div>
                    </div>

                    {{-- Button Apply (Melayang di bawah) --}}
                    <div id="btn-apply-wrapper" class="hidden lg:sticky bottom-0 left-0 w-full bg-white pt-3 pb-1 border-t border-gray-100 z-10 mt-auto">
                        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-2.5 px-4 rounded-lg shadow-md transition-colors text-sm">
                            Terapkan
                        </button>
                    </div>

                    {{-- Button Apply khusus Mobile (Selalu muncul) --}}
                    <div class="lg:hidden mt-auto pt-4">
                        <button type="submit" class="w-full bg-brand-600 hover:bg-brand-700 text-white font-bold py-3 px-4 rounded-lg shadow-md">
                            Tampilkan Produk
                        </button>
                    </div>

                </form>
            </div>
        </aside>

        {{-- ========================================================= --}}
        {{-- KANAN: KONTEN UTAMA (HASIL PRODUK) --}}
        {{-- ========================================================= --}}
        <main class="flex-1 w-full min-w-0">

            {{-- HEADER HASIL PENCARIAN (Sorting & Info Box) --}}
            <div class="bg-white rounded-xl shadow-card border border-gray-200 p-4 mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative z-10">

                {{-- Text Kiri --}}
                <div class="text-sm text-gray-700">
                    Menampilkan <span class="font-bold text-brand-600">{{ $products->count() }}</span> produk dari total <span class="font-bold">{{ $products->total() }}</span>
                </div>

                {{-- Aksi Kanan --}}
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    {{-- Button Filter HP --}}
                    <button id="mobile-filter-btn" class="lg:hidden flex-1 flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-lg hover:bg-gray-50 active:bg-gray-100 shadow-sm">
                        <i class="fas fa-filter text-sm"></i> Filter
                    </button>

                    {{-- Dropdown Sort (Tokped Style) --}}
                    <div class="flex items-center gap-3 flex-1 sm:flex-none justify-end">
                        <span class="text-sm font-semibold text-gray-700 hidden sm:block">Urutkan:</span>
                        <div class="relative w-full sm:w-[180px]">
                            <select class="appearance-none bg-white border border-gray-300 text-gray-700 py-2 pl-3 pr-8 rounded-lg text-sm font-semibold focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 cursor-pointer w-full shadow-sm">
                                <option>Paling Sesuai</option>
                                <option>Terbaru</option>
                                <option>Harga Terendah</option>
                                <option>Harga Tertinggi</option>
                            </select>
                            <i class="fas fa-chevron-down absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- GRID PRODUK (Absolute Square Grid) --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 relative z-0">

                @forelse($products as $b)
                    @php
                        $img = !empty($b->gambar_utama) ? 'assets/uploads/products/'.$b->gambar_utama : 'assets/uploads/products/default.jpg';
                    @endphp
                    <a href="{{ route('produk.detail', $b->id) }}" class="bg-white rounded-lg shadow-card hover:shadow-card-hover transition-shadow duration-200 overflow-hidden flex flex-col group border border-transparent hover:border-brand-500 relative">

                        {{-- Gambar (Memaksa rasio 1:1 Sempurna) --}}
                        <div class="w-full pt-[100%] relative bg-white border-b border-gray-100 overflow-hidden">
                            <img src="{{ asset($img) }}" onerror="this.src='https://images.unsplash.com/photo-1589939705384-5185137a7f0f?q=80&w=400&auto=format&fit=crop'" class="absolute inset-0 w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500 ease-in-out" alt="{{ $b->nama_barang }}">
                        </div>

                        {{-- Detail Informasi --}}
                        <div class="p-3 flex flex-col flex-1">
                            <h3 class="text-[13px] sm:text-sm font-normal text-gray-800 line-clamp-2 leading-[1.3] mb-1.5">{{ $b->nama_barang }}</h3>

                            <div class="mt-auto">
                                <div class="text-[15px] sm:text-[17px] font-bold text-gray-900 leading-none mb-1.5">Rp{{ number_format($b->harga, 0, ',', '.') }}</div>

                                {{-- Lokasi --}}
                                <div class="flex items-center text-[11px] text-gray-500 mt-1">
                                    <i class="fas fa-crown text-purple-600 w-4 text-[10px]"></i>
                                    <span class="truncate">{{ $b->nama_kota ?? 'Jakarta Pusat' }}</span>
                                </div>

                                {{-- Rating --}}
                                <div class="flex items-center text-[10px] sm:text-[11px] text-gray-500 mt-2 pt-2 border-t border-gray-100">
                                    <i class="fas fa-star text-yellow-400 mr-1 text-[10px]"></i>
                                    <span class="font-semibold text-gray-700 mr-1">4.9</span>
                                    <span class="mx-1 text-gray-300">|</span>
                                    <span>100+ terjual</span>
                                </div>
                            </div>
                        </div>
                    </a>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-xl border border-gray-200 shadow-sm mt-2">
                        <img src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/zeus/kratos/60454a86.png" alt="Empty" class="w-40 sm:w-48 mb-4 opacity-80">
                        <h3 class="text-lg sm:text-xl font-bold text-gray-800 mb-2">Pencarian Tidak Ditemukan</h3>
                        <p class="text-gray-500 text-sm text-center max-w-sm mb-6 px-4">Coba gunakan kata kunci yang lebih umum atau kurangi filter untuk menemukan apa yang Anda cari.</p>
                        <a href="{{ route('produk.index') }}" class="bg-white border-2 border-brand-600 text-brand-600 hover:bg-brand-50 font-bold py-2.5 px-6 rounded-lg transition-colors">
                            Hapus Semua Filter
                        </a>
                    </div>
                @endforelse

            </div>

            {{-- PAGINASI --}}
            <div class="pagination-wrap">
                {{ $products->links() }}
            </div>

        </main>
    </div>

    @include('partials.footer')
    <script src="{{ asset('assets/js/navbar.js') }}"></script>

    {{-- LOGIKA INTERAKSI --}}
    <script>
        // Memunculkan tombol terapkan jika filter disentuh
        function showApplyButton() {
            const btnWrapper = document.getElementById('btn-apply-wrapper');
            if(btnWrapper) {
                btnWrapper.classList.remove('hidden');
                btnWrapper.classList.add('block');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar Drawer Mobile
            const mobileFilterBtn = document.getElementById('mobile-filter-btn');
            const sidebarFilters = document.getElementById('sidebar-filters');
            const closeFilterBtn = document.getElementById('close-filter-btn');
            const filterOverlay = document.getElementById('filter-overlay');

            function openFilter() {
                sidebarFilters.classList.remove('-translate-x-full');
                filterOverlay.classList.remove('hidden');
                setTimeout(() => filterOverlay.classList.remove('opacity-0'), 10);
                document.body.style.overflow = 'hidden';
            }

            function closeFilter() {
                sidebarFilters.classList.add('-translate-x-full');
                filterOverlay.classList.add('opacity-0');
                setTimeout(() => filterOverlay.classList.add('hidden'), 300);
                document.body.style.overflow = '';
            }

            if (mobileFilterBtn) mobileFilterBtn.addEventListener('click', openFilter);
            if (closeFilterBtn) closeFilterBtn.addEventListener('click', closeFilter);
            if (filterOverlay) filterOverlay.addEventListener('click', closeFilter);

            // Tampilkan Kategori Lebih Banyak
            const btnMoreCat = document.getElementById('btn-more-cat');
            if(btnMoreCat) {
                btnMoreCat.addEventListener('click', function() {
                    const hiddenItems = document.querySelectorAll('.cat-item.hidden');
                    const isExpanded = this.classList.contains('expanded');

                    if (!isExpanded) {
                        hiddenItems.forEach(item => { item.classList.remove('hidden'); item.classList.add('shown-by-btn'); });
                        this.innerHTML = 'Sembunyikan <i class="fas fa-chevron-up ml-1"></i>';
                        this.classList.add('expanded');
                    } else {
                        const shownItems = document.querySelectorAll('.cat-item.shown-by-btn');
                        shownItems.forEach(item => {
                            const checkbox = item.querySelector('input[type="checkbox"]');
                            if(!checkbox.checked) { item.classList.add('hidden'); item.classList.remove('shown-by-btn'); }
                        });
                        this.innerHTML = 'Lihat Selengkapnya <i class="fas fa-chevron-down ml-1"></i>';
                        this.classList.remove('expanded');
                    }
                });
            }
        });
    </script>
</body>
</html>
