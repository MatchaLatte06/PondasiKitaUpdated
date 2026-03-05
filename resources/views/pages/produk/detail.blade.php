<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->nama_barang }} - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}">

    <style>
        body { background-color: #f8fafc; font-family: 'Inter', sans-serif; color: #334155; }
        .container { max-width: 1200px; margin: 0 auto; padding: 0 15px; }

        /* BREADCRUMB */
        .breadcrumb { font-size: 0.85rem; margin: 20px 0; color: #64748b; }
        .breadcrumb a { color: #3b82f6; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* =======================================
           LAYOUT 3 KOLOM ENTERPRISE (Kiri, Tengah, Kanan)
           ======================================= */
        .product-grid-layout {
            display: grid;
            grid-template-columns: 350px 1fr 300px;
            gap: 30px;
            align-items: start; 
            margin-bottom: 50px;
        }

        @media (max-width: 992px) {
            .product-grid-layout { grid-template-columns: 1fr; } 
        }

        /* --- KOLOM KIRI (GALERI FOTO) --- */
        .col-gallery { position: sticky; top: 90px; }
        .main-image-box { background: white; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; aspect-ratio: 1/1; margin-bottom: 15px; }
        .main-image-box img { width: 100%; height: 100%; object-fit: cover; }
        .thumb-strip { display: flex; gap: 10px; overflow-x: auto; scrollbar-width: none; }
        .thumb-box { width: 60px; height: 60px; border-radius: 8px; border: 2px solid transparent; overflow: hidden; cursor: pointer; opacity: 0.6; transition: 0.2s; }
        .thumb-box.active { border-color: #3b82f6; opacity: 1; }
        .thumb-box:hover { opacity: 1; }
        .thumb-box img { width: 100%; height: 100%; object-fit: cover; }

        /* --- KOLOM TENGAH (INFO & DESKRIPSI) --- */
        .col-info { background: transparent; }
        .p-title { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin: 0 0 10px 0; line-height: 1.4; }

        .p-meta { display: flex; align-items: center; gap: 15px; font-size: 0.9rem; color: #64748b; margin-bottom: 20px; border-bottom: 1px solid #e2e8f0; padding-bottom: 15px;}
        .p-meta-item { display: flex; align-items: center; gap: 5px; }
        .star-icon { color: #f59e0b; }
        .p-price-area { margin-bottom: 25px; }
        .p-price { font-size: 2rem; font-weight: 900; color: #ef4444; margin: 0; }

        /* Tabs Style untuk Spesifikasi & Deskripsi */
        .content-card { background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 25px; margin-bottom: 20px; }
        .card-header-title { font-size: 1.1rem; font-weight: 800; color: #0f172a; margin: 0 0 15px 0; padding-bottom: 10px; border-bottom: 2px solid #f1f5f9; }

        .spec-table { width: 100%; font-size: 0.9rem; }
        .spec-table td { padding: 10px 0; border-bottom: 1px dashed #e2e8f0; }
        .spec-table td:first-child { width: 150px; color: #64748b; font-weight: 600; }
        .spec-table td:last-child { color: #1e293b; font-weight: 500; }

        .desc-text { font-size: 0.95rem; line-height: 1.7; color: #334155; }

        /* Review Styles */
        .review-item { padding: 20px 0; border-bottom: 1px dashed #e2e8f0; }
        .review-item:last-child { border-bottom: none; padding-bottom: 0; }
        .reviewer-name { font-weight: 700; font-size: 0.95rem; color: #0f172a; margin-bottom: 4px; }
        .review-stars { color: #f59e0b; font-size: 0.8rem; margin-bottom: 8px; }
        .review-date { font-size: 0.75rem; color: #94a3b8; margin-left: 10px; }
        .review-text { font-size: 0.9rem; color: #334155; line-height: 1.5; margin: 0; }

        /* --- KOLOM KANAN (STICKY ACTION CARD & TOKO) --- */
        .col-action { position: sticky; top: 90px; display: flex; flex-direction: column; gap: 20px; }

        /* Toko Widget */
        .store-widget { background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; display: flex; align-items: center; gap: 15px; }
        .store-avatar { width: 50px; height: 50px; border-radius: 50%; overflow: hidden; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .store-avatar img { width: 100%; height: 100%; object-fit: cover; }
        .store-initials { width: 100%; height: 100%; display: flex; justify-content: center; align-items: center; color: white; font-weight: bold; font-size: 18px; }
        .store-info h4 { margin: 0 0 4px 0; font-size: 1rem; font-weight: 700; color: #0f172a; }
        .store-info p { margin: 0; font-size: 0.75rem; color: #64748b; }
        .btn-visit { display: inline-block; margin-top: 8px; font-size: 0.75rem; color: #3b82f6; border: 1px solid #3b82f6; padding: 4px 12px; border-radius: 50px; text-decoration: none; font-weight: 600; transition: 0.2s; }
        .btn-visit:hover { background: #3b82f6; color: white; }

        /* Action Box (Beli) */
        .action-box { background: white; border-radius: 12px; border: 1px solid #e2e8f0; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .action-box h3 { font-size: 1rem; font-weight: 800; color: #0f172a; margin: 0 0 15px 0; }

        .qty-wrapper { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
        .qty-controls { display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 8px; overflow: hidden; }
        .qty-btn { background: white; border: none; width: 35px; height: 35px; font-size: 1.2rem; color: #475569; cursor: pointer; transition: 0.2s; }
        .qty-btn:hover { background: #f1f5f9; color: #0f172a; }
        .qty-input { width: 50px; height: 35px; border: none; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; text-align: center; font-weight: 700; font-size: 0.95rem; outline: none; }
        .stock-info { font-size: 0.8rem; color: #64748b; }

        .subtotal-area { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .subtotal-area span { font-size: 0.9rem; color: #64748b; font-weight: 600; }
        .subtotal-value { font-size: 1.2rem; font-weight: 800; color: #0f172a; }

        .btn-add-cart { width: 100%; background: white; border: 1px solid #3b82f6; color: #3b82f6; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; margin-bottom: 10px; cursor: pointer; transition: 0.2s; }
        .btn-add-cart:hover { background: #eff6ff; }
        .btn-buy-now { width: 100%; background: #3b82f6; border: none; color: white; padding: 12px; border-radius: 8px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: 0.2s; }
        .btn-buy-now:hover { background: #2563eb; }

        .out-of-stock-btn { width: 100%; background: #e2e8f0; color: #94a3b8; border: none; padding: 12px; border-radius: 8px; font-weight: 700; cursor: not-allowed; }
    </style>
</head>
<body>

    @include('partials.navbar')

    <main class="container">

        {{-- BREADCRUMB --}}
        <div class="breadcrumb">
            <a href="{{ url('/') }}">Beranda</a> <i class="fas fa-chevron-right" style="font-size: 10px; margin: 0 8px;"></i>
            <a href="{{ url('/kategori/' . ($product->kategori_id ?? '')) }}">{{ $product->nama_kategori ?? 'Kategori' }}</a> <i class="fas fa-chevron-right" style="font-size: 10px; margin: 0 8px;"></i>
            <span style="color: #0f172a; font-weight: 600;">{{ Str::limit($product->nama_barang, 30) }}</span>
        </div>

        {{-- ENTERPRISE 3-COLUMN GRID --}}
        <div class="product-grid-layout">

            {{-- 1. KOLOM KIRI (FOTO) --}}
            <div class="col-gallery">
                <div class="main-image-box">
                    <img src="{{ asset('assets/uploads/products/' . $gallery_images[0]) }}" id="mainProductImage" onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">
                </div>
                <div class="thumb-strip">
                    @foreach ($gallery_images as $index => $img)
                        <div class="thumb-box {{ $index == 0 ? 'active' : '' }}" onclick="changeImage(this, '{{ asset('assets/uploads/products/' . $img) }}')">
                            <img src="{{ asset('assets/uploads/products/' . $img) }}" onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. KOLOM TENGAH (INFO PRODUK) --}}
            <div class="col-info">
                <h1 class="p-title">{{ $product->nama_barang }}</h1>

                <div class="p-meta">
                    <div class="p-meta-item">
                        <i class="fas fa-star star-icon"></i>
                        <strong style="color: #0f172a;">{{ number_format($avg_rating, 1) }}</strong>
                    </div>
                    <div class="p-meta-item">
                        <a href="#reviews" style="color: #64748b; text-decoration: underline;">{{ $jumlah_ulasan }} Penilaian</a>
                    </div>
                    <div class="p-meta-item">
                        Terjual <strong style="color: #0f172a;">200+</strong>
                    </div>
                </div>

                <div class="p-price-area">
                    <h2 class="p-price" id="basePrice" data-price="{{ $product->harga }}">Rp {{ number_format($product->harga, 0, ',', '.') }}</h2>
                    @if($product->satuan_unit)
                        <span style="font-size: 0.85rem; color: #64748b;">Per {{ $product->satuan_unit }}</span>
                    @endif
                </div>

                {{-- Spesifikasi Card --}}
                <div class="content-card">
                    <h3 class="card-header-title">Spesifikasi Material</h3>
                    <table class="spec-table">
                        <tr><td>Kategori</td><td>{{ $product->nama_kategori ?? 'Lainnya' }}</td></tr>
                        <tr><td>Kondisi</td><td>Baru</td></tr>
                        <tr><td>Min. Pemesanan</td><td>1 {{ $product->satuan_unit }}</td></tr>
                        <tr><td>Berat</td><td>{{ $product->berat_kg ?? '1' }} kg</td></tr>
                    </table>
                </div>

                {{-- Deskripsi Card --}}
                <div class="content-card">
                    <h3 class="card-header-title">Deskripsi Produk</h3>
                    <div class="desc-text">
                        {!! nl2br(e($product->deskripsi)) ?: '<i>Tidak ada deskripsi produk.</i>' !!}
                    </div>
                </div>

                {{-- Ulasan Card --}}
                <div class="content-card" id="reviews">
                    <h3 class="card-header-title">Ulasan Pembeli ({{ $jumlah_ulasan }})</h3>
                    @if ($jumlah_ulasan > 0)
                        @foreach ($reviews as $ulasan)
                            <div class="review-item">
                                <div class="review-stars">
                                    @for ($i = 0; $i < 5; $i++)
                                        <i class="fas fa-star" style="color: {{ $i < $ulasan->rating ? '#f59e0b' : '#e2e8f0' }};"></i>
                                    @endfor
                                    <span class="review-date">{{ \Carbon\Carbon::parse($ulasan->created_at)->format('d M Y') }}</span>
                                </div>
                                <div class="reviewer-name">{{ $ulasan->username }}</div>
                                <p class="review-text">{{ $ulasan->ulasan }}</p>
                            </div>
                        @endforeach
                    @else
                        <p style="color: #94a3b8; font-size: 0.9rem;">Belum ada ulasan untuk produk ini. Jadilah yang pertama!</p>
                    @endif
                </div>
            </div>

            {{-- 3. KOLOM KANAN (STICKY ACTION) --}}
            <div class="col-action">

                {{-- Widget Toko --}}
                <div class="store-widget">
                    <div class="store-avatar">
                        @if (!empty($product->logo_toko) && file_exists(public_path('assets/uploads/logos/' . $product->logo_toko)))
                            <img src="{{ asset('assets/uploads/logos/' . $product->logo_toko) }}" alt="Logo">
                        @else
                            <div class="store-initials" style="background-color: {{ $storeColor }};">{{ $storeInitials }}</div>
                        @endif
                    </div>
                    <div class="store-info">
                        <h4><i class="fas fa-check-circle" style="color: #10b981; font-size: 0.85rem;"></i> {{ $product->nama_toko }}</h4>
                        <p><i class="fas fa-map-marker-alt"></i> {{ $product->nama_kota_toko ?? 'Indonesia' }}</p>
                        <a href="{{ url('pages/toko?slug=' . $product->slug_toko) }}" class="btn-visit">Kunjungi Toko</a>
                    </div>
                </div>

                {{-- Action Box (Keranjang & Beli) --}}
                <div class="action-box">
                    <h3>Atur Jumlah dan Catatan</h3>

                    <form id="formTambahKeranjang">
                        <input type="hidden" name="barang_id" value="{{ $product->id }}">

                        <div class="qty-wrapper">
                            <div class="qty-controls">
                                <button type="button" class="qty-btn" onclick="updateQty(-1)">-</button>
                                <input type="number" class="qty-input" name="jumlah" id="inputQty" value="1" min="1" max="{{ $product->stok }}" readonly>
                                <button type="button" class="qty-btn" onclick="updateQty(1)">+</button>
                            </div>
                            <div class="stock-info">Sisa Stok: <strong>{{ $product->stok }}</strong></div>
                        </div>

                        <div class="subtotal-area">
                            <span>Subtotal</span>
                            <div class="subtotal-value" id="subtotalDisplay">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        </div>

                        @if ($product->stok > 0)
                            <button type="button" class="btn-add-cart" id="btnKeranjang"><i class="fas fa-plus"></i> Keranjang</button>
                            
                            {{-- PERBAIKAN: Tombol Beli Langsung Diubah ID-nya --}}
                            <button type="button" class="btn-buy-now" id="btnBeliLangsung">Beli Langsung</button>
                        @else
                            <button type="button" class="out-of-stock-btn" disabled>Stok Habis</button>
                        @endif
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

        // Fungsi Penahan Aksi (Meminta Login)
        function requireLogin(event) {
            if (!isLoggedIn) {
                if(event) event.preventDefault();
                
                Swal.fire({
                    icon: 'info',
                    title: 'Kamu Belum Masuk',
                    text: 'Silakan masuk atau daftar akun dahulu untuk melanjutkan belanja.',
                    confirmButtonText: 'Masuk Sekarang',
                    showCancelButton: true,
                    cancelButtonText: 'Nanti Saja',
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#94a3b8',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = loginUrl;
                    }
                });
                return false; // Berhenti di sini
            }
            return true; // Lanjut jika sudah login
        }


        // --- 2. IMAGE GALLERY SWITCHER ---
        function changeImage(element, imgUrl) {
            document.getElementById('mainProductImage').src = imgUrl;
            document.querySelectorAll('.thumb-box').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        // --- 3. QUANTITY & SUBTOTAL LOGIC ---
        const basePrice = {{ $product->harga }};
        const maxStock = {{ $product->stok }};
        const inputQty = document.getElementById('inputQty');
        const subtotalDisplay = document.getElementById('subtotalDisplay');

        function formatRupiah(angka) {
            return 'Rp ' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function updateQty(change) {
            let currentVal = parseInt(inputQty.value);
            let newVal = currentVal + change;

            if (newVal >= 1 && newVal <= maxStock) {
                inputQty.value = newVal;
                subtotalDisplay.innerText = formatRupiah(basePrice * newVal);
            }
        }

        // --- 4. AJAX ADD TO CART ---
        document.getElementById('btnKeranjang')?.addEventListener('click', function(e) {
            if (!requireLogin(e)) return; // Cek Login Dulu

            let form = document.getElementById('formTambahKeranjang');
            let formData = new FormData(form);

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
                if(data.status === 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Berhasil dimasukkan ke keranjang!',
                        showConfirmButton: false,
                        timer: 2500
                    });
                    
                    // Opsional: Refresh halaman setelah 1 detik untuk update angka keranjang di Navbar
                    setTimeout(() => { window.location.reload(); }, 1000);
                } else {
                    Swal.fire('Gagal', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                Swal.fire('Oops!', 'Gagal menyambung ke server.', 'error');
            });
        });

        // --- 5. DIRECT BUY (BELI LANGSUNG) ---
        document.getElementById('btnBeliLangsung')?.addEventListener('click', function(e) {
            if (!requireLogin(e)) return; // Cek Login Dulu

            // Jika sudah login, lempar langsung ke rute Checkout beserta ID dan Jumlah
            let qty = inputQty.value;
            let productId = "{{ $product->id }}";
            
            window.location.href = `{{ route('checkout') }}?product_id=${productId}&jumlah=${qty}`;
        });
    </script>
</body>
</html>