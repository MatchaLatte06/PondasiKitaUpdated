<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $product->nama_barang }} - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#eff6ff', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #fafafa; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        
        @keyframes shimmer { 100% { transform: translateX(100%); } }
        .animate-shimmer { animation: shimmer 2.5s infinite; }

        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="text-zinc-900 antialiased pt-[80px]">

    @include('partials.navbar')

    <main class="max-w-[1440px] mx-auto px-4 lg:px-10 py-8 lg:py-12">
        <div class="flex flex-col lg:grid lg:grid-cols-12 gap-12 items-start">

            {{-- ========================================== --}}
            {{-- KOLOM 1: VISUAL (Sticky) --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-4 lg:sticky lg:top-28">
                <div class="space-y-6">
                    <div class="relative group aspect-square bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden p-3 transition-all duration-500 hover:shadow-xl hover:shadow-blue-500/5">
                        <img src="{{ asset('assets/uploads/products/' . $gallery_images[0]) }}" id="mainProductImage" 
                             class="w-full h-full object-cover rounded-[2rem] transition-transform duration-700 group-hover:scale-110" 
                             onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">
                        
                        {{-- Badge Kondisi --}}
                        <div class="absolute top-8 left-8">
                            <span class="bg-white/80 backdrop-blur-md px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest text-zinc-900 border border-white/20 shadow-sm">
                                Kondisi: Baru
                            </span>
                        </div>
                    </div>

                    {{-- Thumbnails --}}
                    <div class="flex gap-4 overflow-x-auto no-scrollbar py-2">
                        @foreach ($gallery_images as $index => $img)
                            <button onclick="changeImage(this, '{{ asset('assets/uploads/products/' . $img) }}')" 
                                    class="thumb-btn shrink-0 w-20 h-20 rounded-2xl bg-white border-2 overflow-hidden transition-all duration-300 {{ $index == 0 ? 'border-brand-600 shadow-md ring-4 ring-brand-50' : 'border-zinc-100 opacity-60 hover:opacity-100' }}">
                                <img src="{{ asset('assets/uploads/products/' . $img) }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- KOLOM 2: INFO & SPECS (Scrolls with Kolom 3) --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-5 space-y-10">
                {{-- Header Produk --}}
                <div class="space-y-6">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-lg bg-zinc-100 text-zinc-500 text-[10px] font-black uppercase tracking-widest">
                        <i class="fas fa-tag"></i> {{ $product->nama_kategori }}
                    </div>
                    
                    <h1 class="text-4xl lg:text-5xl font-black text-zinc-900 leading-[1.1] tracking-tighter break-words">
                        {{ $product->nama_barang }}
                    </h1>

                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2.5">
                            <div class="flex text-amber-400 text-sm">
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-base font-black text-zinc-900">{{ number_format($avg_rating, 1) }}</span>
                            <span class="text-sm font-bold text-zinc-400">({{ $jumlah_ulasan }} Ulasan)</span>
                        </div>
                        <div class="w-1.5 h-1.5 rounded-full bg-zinc-200"></div>
                        <div class="text-sm font-bold text-zinc-500 italic">Terjual <span class="text-zinc-900 font-black not-italic">200+ Produk</span></div>
                    </div>
                </div>

                {{-- Specs Grid --}}
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-white border border-zinc-100 p-6 rounded-[2rem] flex items-center gap-4 transition-all hover:border-brand-100">
                        <div class="w-12 h-12 rounded-2xl bg-brand-50 flex items-center justify-center text-brand-600"><i class="fas fa-weight-hanging text-lg"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-0.5">Berat</p>
                            <p class="text-base font-black text-zinc-900">{{ $product->berat_kg ?? '1' }} Kg</p>
                        </div>
                    </div>
                    <div class="bg-white border border-zinc-100 p-6 rounded-[2rem] flex items-center gap-4 transition-all hover:border-brand-100">
                        <div class="w-12 h-12 rounded-2xl bg-zinc-50 flex items-center justify-center text-zinc-400"><i class="fas fa-shield-alt text-lg"></i></div>
                        <div>
                            <p class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-0.5">Garansi</p>
                            <p class="text-base font-black text-zinc-900">Tersedia</p>
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <div class="bg-white rounded-[2.5rem] border border-zinc-100 p-8 lg:p-12 space-y-8 shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03]">
                        <i class="fas fa-quote-right text-8xl"></i>
                    </div>
                    <h3 class="text-xs font-black text-brand-600 uppercase tracking-[0.3em] flex items-center gap-4">
                        Deskripsi Lengkap
                        <div class="h-px bg-brand-100 flex-1"></div>
                    </h3>
                    <div class="text-base text-zinc-600 leading-[1.8] font-medium break-words space-y-4">
                        {!! nl2br(e($product->deskripsi)) ?: '<span class="italic text-zinc-400">Deskripsi produk tidak tersedia.</span>' !!}
                    </div>
                </div>

                {{-- REVIEWS --}}
                <div id="reviews" class="bg-white rounded-[2.5rem] border border-zinc-100 p-8 lg:p-12 space-y-12">
                    {{-- Stats --}}
                    <div class="grid md:grid-cols-12 gap-8 items-center border-b border-zinc-50 pb-12">
                        <div class="md:col-span-5 flex flex-col items-center md:items-start">
                            <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest mb-4">Total Kepuasan</span>
                            <div class="flex items-center gap-5">
                                <div class="text-8xl font-black text-zinc-900 tracking-tighter leading-none">{{ number_format($avg_rating, 1) }}</div>
                                <div class="flex flex-col gap-1.5">
                                    <div class="flex text-amber-400 text-[10px]">
                                        @for($i=0; $i<5; $i++) <i class="fas fa-star {{ $i < round($avg_rating) ? '' : 'text-zinc-100' }}"></i> @endfor
                                    </div>
                                    <p class="text-[10px] font-black text-zinc-500 uppercase tracking-widest">{{ $jumlah_ulasan }} Review</p>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-7 flex flex-col gap-3">
                            @foreach([5, 4, 3, 2, 1] as $star)
                                @php
                                    $count = DB::table('tb_review_produk')->where('barang_id', $product->id)->where('rating', $star)->count();
                                    $percent = $jumlah_ulasan > 0 ? ($count / $jumlah_ulasan) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-4 group">
                                    <div class="flex items-center gap-1.5 w-10 shrink-0">
                                        <span class="text-[11px] font-black text-zinc-900">{{ $star }}</span>
                                        <i class="fas fa-star text-[9px] text-amber-400"></i>
                                    </div>
                                    <div class="flex-1 h-2 bg-zinc-50 rounded-full overflow-hidden border border-zinc-100">
                                        <div class="h-full bg-brand-600 rounded-full transition-all duration-1000 group-hover:brightness-110" style="width: {{ $percent }}%"></div>
                                    </div>
                                    <span class="text-[11px] font-bold text-zinc-400 w-10 text-right">{{ round($percent) }}%</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Comment List --}}
                    <div class="space-y-10">
                        @forelse ($reviews as $ulasan)
                            <div class="flex gap-6 group">
                                <div class="w-14 h-14 rounded-2xl bg-zinc-100 flex-shrink-0 flex items-center justify-center font-black text-zinc-400 border border-zinc-200 transition-all duration-300 group-hover:bg-brand-600 group-hover:text-white group-hover:border-brand-600 shadow-sm">
                                    {{ strtoupper(substr($ulasan->username, 0, 1)) }}
                                </div>
                                <div class="space-y-3 flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h5 class="text-base font-black text-zinc-900 truncate pr-4">{{ $ulasan->username }}</h5>
                                        <span class="text-[10px] font-bold text-zinc-300 uppercase shrink-0">{{ \Carbon\Carbon::parse($ulasan->created_at)->diffForHumans() }}</span>
                                    </div>
                                    <div class="flex text-amber-400 text-[9px] gap-0.5">
                                        @for($i=0; $i<5; $i++) <i class="fas fa-star {{ $i < $ulasan->rating ? '' : 'text-zinc-100' }}"></i> @endfor
                                    </div>
                                    <p class="text-sm text-zinc-600 leading-relaxed break-words">{{ $ulasan->ulasan }}</p>
                                    @if(!empty($ulasan->gambar_ulasan))
                                        <div class="mt-4">
                                            <img src="{{ asset('assets/uploads/reviews/' . $ulasan->gambar_ulasan) }}" class="w-24 h-24 object-cover rounded-2xl border border-zinc-100 shadow-sm transition-transform hover:scale-105 cursor-pointer">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10">
                                <div class="w-20 h-20 bg-zinc-50 rounded-full flex items-center justify-center mx-auto mb-4 border border-dashed border-zinc-200 text-zinc-200">
                                    <i class="fas fa-comments text-3xl"></i>
                                </div>
                                <p class="text-sm font-bold text-zinc-400 uppercase tracking-widest">Belum Ada Ulasan Produk</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- ========================================== --}}
            {{-- KOLOM 3: CHECKOUT & STORE --}}
            {{-- ========================================== --}}
            <div class="w-full lg:col-span-3 space-y-6 lg:sticky lg:top-28">
                {{-- Store Card --}}
                <div class="bg-white rounded-[2.5rem] border border-zinc-100 shadow-sm overflow-hidden group">
                    <div class="h-24 bg-zinc-900 relative">
                        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at center, #2563eb 1px, transparent 1px); background-size: 14px 14px;"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-zinc-900 to-transparent"></div>
                    </div>
                    <div class="px-8 pb-8 relative">
                        <div class="absolute -top-12 left-8">
                            @if (!empty($product->logo_toko))
                                <img src="{{ asset('assets/uploads/logos/' . $product->logo_toko) }}" class="w-20 h-20 rounded-2xl border-[6px] border-white shadow-xl object-cover bg-white transition-transform duration-500 group-hover:scale-105">
                            @else
                                <div class="w-20 h-20 rounded-2xl border-[6px] border-white shadow-xl bg-zinc-950 flex items-center justify-center text-white font-black text-2xl uppercase">{{ substr($product->nama_toko, 0, 2) }}</div>
                            @endif
                        </div>
                        <div class="pt-12 space-y-5">
                            <div class="space-y-1">
                                <h4 class="font-black text-xl text-zinc-900 truncate flex items-center gap-2">
                                    {{ $product->nama_toko }} <i class="fas fa-check-circle text-brand-500 text-sm"></i>
                                </h4>
                                <p class="text-[11px] font-bold text-zinc-400 uppercase tracking-widest flex items-center gap-1.5">
                                    <i class="fas fa-location-dot text-brand-500/50"></i> {{ $product->nama_kota_toko }}
                                </p>
                            </div>
                            <a href="{{ url('pages/toko?slug=' . $product->slug_toko) }}" 
                               class="block w-full py-4 bg-zinc-50 hover:bg-zinc-900 hover:text-white text-zinc-700 text-center text-[10px] font-black uppercase tracking-[0.2em] rounded-2xl transition-all border border-zinc-100">
                               Kunjungi Toko
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Checkout Card --}}
                <div class="bg-white rounded-[3rem] border border-zinc-100 p-8 lg:p-10 shadow-[0_30px_60px_rgba(0,0,0,0.04)] relative overflow-hidden">
                    <h3 class="text-xs font-black text-zinc-900 uppercase tracking-[0.3em] mb-8 italic">Konfirmasi Pesanan</h3>
                    
                    <form id="formTambahKeranjang" class="space-y-8">
                        <input type="hidden" name="barang_id" value="{{ $product->id }}">
                        
                        {{-- Qty Selector --}}
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center bg-zinc-50 rounded-2xl p-1.5 border border-zinc-100 flex-1">
                                <button type="button" onclick="updateQty(-1)" class="w-12 h-12 flex items-center justify-center font-black text-zinc-400 hover:text-zinc-900 transition-colors">-</button>
                                <input type="number" id="inputQty" name="jumlah" value="1" readonly class="w-full text-center bg-transparent font-black text-lg outline-none text-zinc-900">
                                <button type="button" onclick="updateQty(1)" class="w-12 h-12 flex items-center justify-center font-black text-brand-600 hover:scale-125 transition-all">+</button>
                            </div>
                            <div class="text-right">
                                <p class="text-[9px] font-black text-zinc-300 uppercase tracking-widest leading-none mb-1">Stok Ready</p>
                                <p class="text-sm font-black text-zinc-900">{{ $product->stok }} <span class="text-[10px] text-zinc-400">Unit</span></p>
                            </div>
                        </div>

                        {{-- Total Price --}}
                        <div class="py-6 border-t border-zinc-50">
                            <span class="text-[10px] font-black text-brand-600 uppercase tracking-[0.3em] block mb-2">Estimasi Subtotal</span>
                            <div class="text-4xl font-black text-zinc-900 tracking-tighter" id="subtotalDisplay">
                                Rp{{ number_format($product->harga, 0, ',', '.') }}
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-4">
                            <button type="button" id="btnKeranjang" class="w-full py-5 bg-white border-2 border-zinc-900 text-zinc-900 rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] hover:bg-zinc-950 hover:text-white transition-all active:scale-95 shadow-sm">
                                <i class="fas fa-cart-plus mr-2"></i> + Keranjang
                            </button>
                            
                            <button type="button" id="btnBeliLangsung" 
                                    class="group relative w-full py-5 bg-brand-600 text-white rounded-[1.5rem] font-black text-xs uppercase tracking-[0.2em] overflow-hidden shadow-xl shadow-brand-500/20 transition-all hover:-translate-y-1 active:scale-95">
                                <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-shimmer"></div>
                                <span class="relative z-10">Beli Sekarang <i class="fas fa-arrow-right ml-2 text-[10px]"></i></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </main>

    @include('partials.footer')

    {{-- SWEETALERT & JAVASCRIPT LOGIC --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 1. Variabel Global (Gunakan ?? 0 agar tidak error jika data kosong)
        const basePrice = {{ $product->harga ?? 0 }};
        const maxStock = {{ $product->stok ?? 0 }};
        const inputQty = document.getElementById('inputQty');
        const subtotalDisplay = document.getElementById('subtotalDisplay');
        const formKeranjang = document.getElementById('formTambahKeranjang');

        // 2. Fungsi Format Rupiah
        function formatRupiah(angka) { 
            return 'Rp' + angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
        }

        // 3. Fungsi Tambah/Kurang QTY
        function updateQty(change) {
            let currentVal = parseInt(inputQty.value);
            if (isNaN(currentVal)) currentVal = 1; // Jaga-jaga kalau input kosong

            let newVal = currentVal + change;
            
            if (newVal >= 1 && newVal <= maxStock) {
                inputQty.value = newVal;
                
                // Animasi subtotal
                subtotalDisplay.style.transform = "scale(0.95)";
                setTimeout(() => {
                    subtotalDisplay.innerText = formatRupiah(basePrice * newVal);
                    subtotalDisplay.style.transform = "scale(1)";
                }, 50);
            } else if (newVal > maxStock) {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'warning',
                    title: 'Maksimal stok tercapai!', showConfirmButton: false, timer: 2000
                });
            }
        }

        // 4. Fungsi Ganti Gambar (Gallery)
        function changeImage(btn, url) {
            const mainImg = document.getElementById('mainProductImage');
            mainImg.style.opacity = "0.5";
            setTimeout(() => {
                mainImg.src = url;
                mainImg.style.opacity = "1";
            }, 150);
            
            document.querySelectorAll('.thumb-btn').forEach(el => {
                el.classList.remove('border-brand-600', 'shadow-md', 'ring-4', 'ring-brand-50');
                el.classList.add('border-zinc-100', 'opacity-60');
            });
            btn.classList.add('border-brand-600', 'shadow-md', 'ring-4', 'ring-brand-50');
            btn.classList.remove('opacity-60');
        }

        // 5. DOM Ready (Nyawa untuk Tombol Keranjang & Beli)
        document.addEventListener('DOMContentLoaded', function() {
            
            const btnKeranjang = document.getElementById('btnKeranjang');
            const btnBeliLangsung = document.getElementById('btnBeliLangsung');

            // --- A. LOGIKA TOMBOL "+ KERANJANG" ---
            if (btnKeranjang) {
                btnKeranjang.addEventListener('click', async function() {
                    // Validasi Login (Opsional, pastikan route sudah benar)
                    @guest
                        window.location.href = "{{ route('login') }}";
                        return;
                    @endguest

                    // Animasi Loading
                    const originalText = btnKeranjang.innerHTML;
                    btnKeranjang.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    btnKeranjang.disabled = true;

                    try {
                        const formData = new FormData(formKeranjang);
                        
                        // PASTIKAN NAMA ROUTE DI BAWAH INI SESUAI DENGAN web.php BOS!
                        const response = await fetch('{{ route('keranjang.tambah') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (response.ok) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Material ditambahkan ke keranjang.',
                                confirmButtonColor: '#2563eb',
                                customClass: { popup: 'rounded-3xl' }
                            });
                            // Refresh untuk update badge keranjang di navbar
                            setTimeout(() => window.location.reload(), 1500); 
                        } else {
                            throw new Error(result.message || 'Gagal menambahkan ke keranjang');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error', title: 'Oops...', text: error.message,
                            confirmButtonColor: '#000', customClass: { popup: 'rounded-3xl' }
                        });
                    } finally {
                        btnKeranjang.innerHTML = originalText;
                        btnKeranjang.disabled = false;
                    }
                });
            }

            // --- B. LOGIKA TOMBOL "BELI SEKARANG" ---
            if (btnBeliLangsung) {
                btnBeliLangsung.addEventListener('click', function() {
                    @guest
                        window.location.href = "{{ route('login') }}";
                        return;
                    @endguest

                    // Ubah action form untuk direct checkout
                    // PASTIKAN NAMA ROUTE DI BAWAH INI SESUAI DENGAN web.php BOS!
                    formKeranjang.action = "{{ route('checkout.langsung') }}"; 
                    formKeranjang.method = "POST";
                    
                    // Tambahkan CSRF manual jika form belum punya (biasanya butuh jika dikirim murni via HTML)
                    if (!formKeranjang.querySelector('input[name="_token"]')) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                        formKeranjang.appendChild(csrfInput);
                    }

                    formKeranjang.submit();
                });
            }
        });
    </script>
</body>
</html>