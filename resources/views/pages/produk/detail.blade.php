<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->nama_barang }} - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                        'floating': '0 -10px 40px -10px rgba(0,0,0,0.1)',
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }

        /* Hide scrollbar for horizontal strips */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Remove Number Input Arrows */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Prose formatting for DB text */
        .prose-desc p { margin-bottom: 1em; }
        .prose-desc ul { list-style-type: disc; padding-left: 1.5em; margin-bottom: 1em; }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px] pb-24 lg:pb-0"> {{-- Padding bottom added for mobile sticky checkout --}}

    {{-- Include Navbar --}}
    @include('partials.navbar')

    {{-- BREADCRUMB --}}
    <div class="bg-white border-b border-zinc-200 shadow-sm relative z-10 hidden md:block">
        <div class="max-w-[1250px] mx-auto px-4 lg:px-8 py-3">
            <nav class="flex text-sm text-zinc-500 font-medium items-center gap-2">
                <a href="{{ url('/') }}" class="hover:text-blue-600 transition-colors">Beranda</a>
                <i class="fas fa-chevron-right text-[10px] text-zinc-400"></i>
                <a href="{{ url('/kategori/' . ($product->kategori_id ?? '')) }}" class="hover:text-blue-600 transition-colors">{{ $product->nama_kategori ?? 'Kategori' }}</a>
                <i class="fas fa-chevron-right text-[10px] text-zinc-400"></i>
                <span class="text-zinc-900 font-semibold truncate max-w-[300px]">{{ $product->nama_barang }}</span>
            </nav>
        </div>
    </div>

    {{-- MAIN LAYOUT: 12-COLUMN GRID ENTERPRISE --}}
    <main class="max-w-[1250px] mx-auto px-4 lg:px-8 py-6 lg:py-10">

        <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 items-start">

            {{-- ========================================== --}}
            {{-- KOLOM 1 (KIRI): GALERI FOTO (Col-span-4) --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-4 lg:sticky lg:top-28">
                {{-- Main Image --}}
                <div class="w-full aspect-square bg-white rounded-3xl border border-zinc-200 shadow-card overflow-hidden mb-4 p-2 relative group">
                    <img src="{{ asset('assets/uploads/products/' . $gallery_images[0]) }}" id="mainProductImage" class="w-full h-full object-cover rounded-2xl transition-transform duration-500 group-hover:scale-105" onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">

                    {{-- Zoom Hint --}}
                    <div class="absolute bottom-4 right-4 bg-black/50 backdrop-blur-md text-white w-8 h-8 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i class="fas fa-search-plus text-xs"></i>
                    </div>
                </div>

                {{-- Thumbnail Strip --}}
                <div class="flex gap-3 overflow-x-auto no-scrollbar py-1">
                    @foreach ($gallery_images as $index => $img)
                        <button type="button" onclick="changeImage(this, '{{ asset('assets/uploads/products/' . $img) }}')" class="thumb-btn shrink-0 w-16 h-16 rounded-xl bg-white border-2 overflow-hidden transition-all duration-200 {{ $index == 0 ? 'border-blue-600 ring-2 ring-blue-600/30 opacity-100' : 'border-zinc-200 opacity-60 hover:opacity-100 hover:border-blue-300' }}">
                            <img src="{{ asset('assets/uploads/products/' . $img) }}" class="w-full h-full object-cover" onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- KOLOM 2 (TENGAH): INFO PRODUK (Col-span-5) --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-5 flex flex-col gap-6">

                {{-- Head Info --}}
                <div class="bg-white rounded-3xl border border-zinc-200 p-6 sm:p-8 shadow-card">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-widest mb-4">
                        {{ $product->nama_kategori ?? 'Material' }}
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 leading-[1.3] mb-4">
                        {{ $product->nama_barang }}
                    </h1>

                    {{-- Rating Meta --}}
                    <div class="flex flex-wrap items-center gap-4 text-sm font-medium text-zinc-500 pb-6 border-b border-zinc-100">
                        <div class="flex items-center gap-1.5 text-zinc-800">
                            <i class="fas fa-star text-yellow-500"></i>
                            <span class="font-bold">{{ number_format($avg_rating, 1) }}</span>
                        </div>
                        <div class="w-1 h-1 bg-zinc-300 rounded-full"></div>
                        <a href="#reviews" class="hover:text-blue-600 transition-colors underline decoration-dotted underline-offset-4">
                            {{ $jumlah_ulasan }} Ulasan
                        </a>
                        <div class="w-1 h-1 bg-zinc-300 rounded-full"></div>
                        <div>Terjual <span class="font-bold text-zinc-800">200+</span></div>
                    </div>

                    {{-- Price Area --}}
                    <div class="pt-6">
                        <div class="text-4xl font-black text-black tracking-tight mb-1 flex items-end gap-2" id="basePriceDisplay">
                            Rp{{ number_format($product->harga, 0, ',', '.') }}
                            @if($product->satuan_unit)
                                <span class="text-sm font-bold text-zinc-400 mb-1.5">/ {{ $product->satuan_unit }}</span>
                            @endif
                        </div>

                        {{-- B2B Tag --}}
                        <div class="mt-4 bg-zinc-50 border border-zinc-200 rounded-xl p-3 flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                            <p class="text-xs text-zinc-600 leading-relaxed">Harga sudah termasuk PPN. Pembelian skala grosir (B2B) dapat menghubungi penjual untuk negosiasi harga khusus.</p>
                        </div>
                    </div>
                </div>

                {{-- Detail Tabs: Spesifikasi --}}
                <div class="bg-white rounded-3xl border border-zinc-200 p-6 sm:p-8 shadow-card">
                    <h3 class="text-lg font-black text-zinc-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-list-ul text-blue-600"></i> Spesifikasi Material
                    </h3>

                    <div class="border border-zinc-200 rounded-2xl overflow-hidden">
                        <table class="w-full text-sm text-left">
                            <tbody class="divide-y divide-zinc-200">
                                <tr class="bg-zinc-50">
                                    <td class="py-3 px-4 font-semibold text-zinc-500 w-1/3">Kondisi</td>
                                    <td class="py-3 px-4 font-bold text-zinc-900"><span class="bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded text-xs">Baru</span></td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 font-semibold text-zinc-500">Min. Pesanan</td>
                                    <td class="py-3 px-4 font-bold text-zinc-900">1 {{ $product->satuan_unit }}</td>
                                </tr>
                                <tr class="bg-zinc-50">
                                    <td class="py-3 px-4 font-semibold text-zinc-500">Berat Est.</td>
                                    <td class="py-3 px-4 font-bold text-zinc-900">{{ $product->berat_kg ?? '1' }} kg</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 font-semibold text-zinc-500">Kategori Utama</td>
                                    <td class="py-3 px-4 font-bold text-blue-600 cursor-pointer hover:underline">{{ $product->nama_kategori ?? 'Lainnya' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Detail Tabs: Deskripsi --}}
                <div class="bg-white rounded-3xl border border-zinc-200 p-6 sm:p-8 shadow-card">
                    <h3 class="text-lg font-black text-zinc-900 mb-6 flex items-center gap-2">
                        <i class="fas fa-file-alt text-blue-600"></i> Deskripsi Produk
                    </h3>
                    <div class="text-sm text-zinc-700 leading-[1.8] prose-desc">
                        {!! nl2br(e($product->deskripsi)) ?: '<i class="text-zinc-400">Penjual tidak menyertakan deskripsi lengkap.</i>' !!}
                    </div>
                </div>

                {{-- Ulasan Section --}}
                <div id="reviews" class="bg-white rounded-3xl border border-zinc-200 p-6 sm:p-8 shadow-card scroll-mt-28">
                    <div class="flex items-center justify-between mb-8">
                        <h3 class="text-lg font-black text-zinc-900 flex items-center gap-2">
                            <i class="fas fa-comments text-blue-600"></i> Ulasan Pembeli
                        </h3>
                        <span class="bg-zinc-100 text-zinc-600 font-bold px-3 py-1 rounded-full text-xs">{{ $jumlah_ulasan }} Ulasan</span>
                    </div>

                    <div class="space-y-6">
                        @if ($jumlah_ulasan > 0)
                            @foreach ($reviews as $ulasan)
                                <div class="pb-6 border-b border-zinc-100 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-zinc-200 text-zinc-500 font-bold flex items-center justify-center text-xs">
                                                {{ strtoupper(substr($ulasan->username, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-sm text-zinc-900">{{ $ulasan->username }}</div>
                                                <div class="text-[10px] text-zinc-400">{{ \Carbon\Carbon::parse($ulasan->created_at)->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                        <div class="flex text-yellow-500 text-[10px]">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="fas fa-star {{ $i < $ulasan->rating ? '' : 'text-zinc-200' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-sm text-zinc-700 leading-relaxed pl-11">{{ $ulasan->ulasan }}</p>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-10">
                                <i class="fas fa-comment-slash text-4xl text-zinc-200 mb-3"></i>
                                <p class="text-zinc-500 text-sm font-medium">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ========================================== --}}
            {{-- KOLOM 3 (KANAN): CHECKOUT CARD (Col-span-3) --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-3 lg:sticky lg:top-28 flex flex-col gap-6 relative z-20">

                @php
                    $colors = ['#18181b', '#27272a', '#3f3f46', '#09090b'];
                    $storeColor = $colors[crc32($product->nama_toko) % count($colors)];
                    $words = explode(" ", $product->nama_toko);
                    $acronym = ""; foreach ($words as $w) { $acronym .= mb_substr($w, 0, 1); }
                    $storeInitials = strtoupper(substr($acronym, 0, 2)) ?: "TK";
                @endphp

                {{-- Profil Toko Widget --}}
                <div class="bg-white rounded-3xl border border-zinc-200 p-5 shadow-card group">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl overflow-hidden shadow-sm shrink-0">
                            @if (!empty($product->logo_toko) && file_exists(public_path('assets/uploads/logos/' . $product->logo_toko)))
                                <img src="{{ asset('assets/uploads/logos/' . $product->logo_toko) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex justify-center items-center text-white font-black text-lg" style="background-color: {{ $storeColor }};">
                                    {{ $storeInitials }}
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-black text-sm text-zinc-900 truncate mb-1 flex items-center gap-1.5">
                                <i class="fas fa-check-circle text-blue-500"></i> {{ $product->nama_toko }}
                            </h4>
                            <p class="text-[11px] font-semibold text-zinc-500 truncate mb-2">
                                <i class="fas fa-map-marker-alt text-zinc-400"></i> {{ $product->nama_kota_toko ?? 'Indonesia' }}
                            </p>
                            <a href="{{ url('pages/toko?slug=' . $product->slug_toko) }}" class="inline-block text-[10px] font-bold bg-zinc-100 text-zinc-700 hover:bg-black hover:text-white px-3 py-1.5 rounded-lg transition-colors">
                                Kunjungi Toko
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Form Checkout (Sticky di Mobile bawah) --}}
                <div class="fixed bottom-0 left-0 w-full lg:relative bg-white lg:rounded-3xl border-t lg:border border-zinc-200 p-4 lg:p-6 shadow-floating lg:shadow-card z-50 lg:z-auto">
                    <h3 class="hidden lg:block font-black text-lg text-zinc-900 mb-4">Atur Jumlah</h3>

                    <form id="formTambahKeranjang" class="flex flex-col lg:block gap-4">
                        <input type="hidden" name="barang_id" value="{{ $product->id }}">

                        {{-- Control Qty & Subtotal wrapper for mobile flex --}}
                        <div class="flex justify-between items-center lg:block">
                            {{-- Input QTY Enterprise --}}
                            <div class="flex items-center gap-3">
                                <div class="flex items-center bg-white border-2 border-zinc-200 rounded-xl p-1">
                                    <button type="button" class="w-8 h-8 flex justify-center items-center text-zinc-500 hover:bg-zinc-100 rounded-lg transition-colors font-black" onclick="updateQty(-1)">-</button>
                                    <input type="number" class="w-10 text-center font-black text-sm text-zinc-900 outline-none" name="jumlah" id="inputQty" value="1" min="1" max="{{ $product->stok }}" readonly>
                                    <button type="button" class="w-8 h-8 flex justify-center items-center text-blue-600 hover:bg-blue-50 rounded-lg transition-colors font-black" onclick="updateQty(1)">+</button>
                                </div>
                                <div class="text-xs font-bold text-zinc-400 lg:mt-2 hidden sm:block">
                                    Sisa: <span class="text-zinc-700">{{ $product->stok }}</span>
                                </div>
                            </div>

                            {{-- Subtotal Display --}}
                            <div class="lg:mt-6 lg:mb-6 text-right lg:text-left">
                                <span class="block text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-1 lg:mb-2">Subtotal</span>
                                <div class="text-xl lg:text-2xl font-black text-black leading-none" id="subtotalDisplay">
                                    Rp{{ number_format($product->harga, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex lg:flex-col gap-2 pt-2 lg:pt-0">
                            @if ($product->stok > 0)
                                <button type="button" id="btnKeranjang" class="flex-1 lg:w-full bg-white border-2 border-blue-600 text-blue-600 hover:bg-blue-50 font-black py-3 rounded-xl transition-all flex items-center justify-center gap-2 text-sm shadow-sm">
                                    <i class="fas fa-plus"></i> <span class="hidden sm:inline">Keranjang</span>
                                </button>
                                <button type="button" id="btnBeliLangsung" class="flex-1 lg:w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-3 rounded-xl transition-all shadow-[0_4px_20px_rgba(37,99,235,0.3)] hover:-translate-y-0.5 text-sm">
                                    Beli Langsung
                                </button>
                            @else
                                <button type="button" class="w-full bg-zinc-200 text-zinc-400 font-black py-3.5 rounded-xl cursor-not-allowed text-sm" disabled>
                                    Stok Habis
                                </button>
                            @endif
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // --- 1. VARIABEL AUTH & LOGIC ---
        const isLoggedIn = @json(auth()->check());
        const loginUrl = "{{ route('login') }}";

        function requireLogin(event) {
            if (!isLoggedIn) {
                if(event) event.preventDefault();
                Swal.fire({
                    icon: 'info',
                    title: 'Akses Dibatasi',
                    text: 'Silakan masuk ke akun Anda terlebih dahulu untuk melakukan transaksi B2B.',
                    confirmButtonText: 'Login Sekarang',
                    showCancelButton: true,
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#000000',
                    cancelButtonColor: '#cbd5e1',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) window.location.href = loginUrl;
                });
                return false;
            }
            return true;
        }

        // --- 2. IMAGE GALLERY LOGIC ---
        function changeImage(btn, imgUrl) {
            // Ganti src gambar utama
            document.getElementById('mainProductImage').src = imgUrl;

            // Reset semua border thumbnail
            const allThumbs = document.querySelectorAll('.thumb-btn');
            allThumbs.forEach(el => {
                el.classList.remove('border-blue-600', 'ring-2', 'ring-blue-600/30', 'opacity-100');
                el.classList.add('border-zinc-200', 'opacity-60');
            });

            // Beri highlight pada thumbnail yg diklik
            btn.classList.remove('border-zinc-200', 'opacity-60');
            btn.classList.add('border-blue-600', 'ring-2', 'ring-blue-600/30', 'opacity-100');
        }

        // --- 3. QUANTITY & SUBTOTAL LOGIC ---
        const basePrice = {{ $product->harga }};
        const maxStock = {{ $product->stok }};
        const inputQty = document.getElementById('inputQty');
        const subtotalDisplay = document.getElementById('subtotalDisplay');

        function formatRupiah(angka) {
            return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateQty(change) {
            let currentVal = parseInt(inputQty.value);
            let newVal = currentVal + change;

            if (newVal >= 1 && newVal <= maxStock) {
                inputQty.value = newVal;
                // Animasi ringan saat angka ganti
                subtotalDisplay.style.opacity = 0.5;
                setTimeout(() => {
                    subtotalDisplay.innerText = formatRupiah(basePrice * newVal);
                    subtotalDisplay.style.opacity = 1;
                }, 100);
            }
        }

        // --- 4. AJAX ADD TO CART ---
        document.getElementById('btnKeranjang')?.addEventListener('click', function(e) {
            if (!requireLogin(e)) return;

            let form = document.getElementById('formTambahKeranjang');
            let formData = new FormData(form);

            const btn = this;
            const originalContent = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            btn.disabled = true;

            fetch('{{ route("keranjang.tambah") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalContent;
                btn.disabled = false;

                if(data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Masuk Keranjang!',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: { popup: 'rounded-xl' }
                    });
                    setTimeout(() => { window.location.reload(); }, 1000);
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                btn.innerHTML = originalContent;
                btn.disabled = false;
                Swal.fire('Oops!', 'Gagal menyambung ke server.', 'error');
            });
        });

        // --- 5. DIRECT BUY ---
        document.getElementById('btnBeliLangsung')?.addEventListener('click', function(e) {
            if (!requireLogin(e)) return;

            let qty = inputQty.value;
            let productId = "{{ $product->id }}";

            window.location.href = `{{ route('checkout') }}?product_id=${productId}&jumlah=${qty}`;
        });
    </script>
</body>
</html>
