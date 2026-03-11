<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Pondasikita</title>

    {{-- Tailwind CSS CDN + Config Dewa --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8' },
                        surface: '#fcfcfd',
                    },
                    boxShadow: {
                        'soft': '0 4px 40px -4px rgba(0,0,0,0.03)',
                        'float': '0 10px 30px -5px rgba(0,0,0,0.08)',
                        'bottom-nav': '0 -10px 40px rgba(0,0,0,0.08)',
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeIn: { '0%': { opacity: 0, transform: 'translateY(15px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } }
                    }
                }
            }
        }
    </script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f4f5; }

        /* Remove Number Input Arrows */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="text-zinc-800 antialiased pt-[80px] pb-28 lg:pb-12">

    {{-- Navbar --}}
    @include('partials.navbar')

    {{-- BREADCRUMB MINIMALIS --}}
    <div class="bg-white border-b border-zinc-200 hidden md:block relative z-10 shadow-sm">
        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 py-3">
            <nav class="flex text-xs font-semibold text-zinc-500 items-center gap-3">
                <a href="{{ url('/') }}" class="hover:text-black transition-colors">Beranda</a>
                <span class="w-1 h-1 rounded-full bg-zinc-300"></span>
                <span class="text-zinc-900">Keranjang Belanja</span>
            </nav>
        </div>
    </div>

    <main class="max-w-[1200px] mx-auto px-4 sm:px-6 py-6 lg:py-10">

        {{-- Header Keranjang --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-black tracking-tight">Keranjang</h1>
                <p class="text-sm font-medium text-zinc-500 mt-1">Periksa kembali barang belanjaan B2B Anda.</p>
            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- KONDISI 1: BELUM LOGIN --}}
        {{-- ======================================================= --}}
        @if(isset($is_guest) && $is_guest)
            <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-12 text-center animate-fade-in flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-zinc-50 rounded-full flex items-center justify-center mb-6 shadow-inner">
                    <i class="fas fa-lock text-4xl text-zinc-300"></i>
                </div>
                <h2 class="text-2xl font-black text-black mb-3">Akses Keranjang Dibatasi</h2>
                <p class="text-zinc-500 font-medium max-w-md mb-8">Anda harus masuk ke akun bisnis Anda terlebih dahulu untuk melihat dan mengelola keranjang belanja.</p>
                <a href="{{ route('login') }}" class="bg-black hover:bg-blue-600 text-white font-bold py-3.5 px-8 rounded-xl transition-all shadow-lg hover:-translate-y-1 hover:shadow-blue-500/30">
                    Masuk Sekarang
                </a>
            </div>

        {{-- ======================================================= --}}
        {{-- KONDISI 2: KERANJANG KOSONG --}}
        {{-- ======================================================= --}}
        @elseif(isset($groupedCart) && $groupedCart->isEmpty())
            <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-12 text-center animate-fade-in flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-blue-50 rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-shopping-basket text-4xl text-blue-300"></i>
                </div>
                <h2 class="text-2xl font-black text-black mb-3">Keranjang Masih Kosong</h2>
                <p class="text-zinc-500 font-medium max-w-md mb-8">Wah, keranjang belanjamu belum terisi. Yuk mulai eksplorasi ribuan material terbaik untuk proyekmu!</p>
                <a href="{{ route('produk.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-8 rounded-xl transition-all shadow-lg hover:-translate-y-1 hover:shadow-blue-500/40">
                    Mulai Belanja
                </a>
            </div>

        {{-- ======================================================= --}}
        {{-- KONDISI 3: KERANJANG TERISI (LAYOUT 12-COL GRID) --}}
        {{-- ======================================================= --}}
        @else
            <div class="flex flex-col lg:grid lg:grid-cols-12 gap-8 items-start relative">

                {{-- BAGIAN KIRI: LIST BARANG (Col-span-8) --}}
                <div class="w-full lg:col-span-8 flex flex-col gap-6 animate-fade-in">

                    {{-- Master Checkbox (Pilih Semua) --}}
                    <div class="bg-white rounded-2xl shadow-soft border border-zinc-200 p-4 flex items-center gap-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center shrink-0">
                                <input type="checkbox" id="check-all" class="peer sr-only" checked onchange="toggleAllCheckboxes(this)">
                                <div class="w-5 h-5 rounded-[6px] border-2 border-zinc-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all duration-300 flex items-center justify-center group-hover:border-blue-400">
                                    <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100 transition-all duration-300"></i>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-zinc-700 group-hover:text-black transition-colors select-none">Pilih Semua Material</span>
                        </label>
                    </div>

                    {{-- Loop Per Toko --}}
                    @foreach($groupedCart as $namaToko => $items)
                        <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 overflow-hidden">

                            {{-- Header Toko --}}
                            <div class="bg-zinc-50 border-b border-zinc-200 px-6 py-4 flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-white border border-zinc-200 shadow-sm flex items-center justify-center text-emerald-500 shrink-0">
                                    <i class="fas fa-store text-xs"></i>
                                </div>
                                <h3 class="font-black text-sm text-zinc-900 tracking-wide">{{ $namaToko }}</h3>
                            </div>

                            {{-- Loop Barang dalam Toko --}}
                            <div class="p-6 flex flex-col gap-6">
                                @foreach($items as $item)
                                    <div class="flex gap-4 sm:gap-6 pb-6 border-b border-zinc-100 border-dashed last:border-0 last:pb-0 relative group" id="cart-row-{{ $item->cart_id }}">

                                        {{-- Custom Checkbox Item --}}
                                        <label class="flex items-start mt-2 cursor-pointer">
                                            <div class="relative flex items-center justify-center shrink-0">
                                                <input type="checkbox" class="peer sr-only js-item-checkbox"
                                                       data-id="{{ $item->cart_id }}"
                                                       data-price="{{ $item->harga }}"
                                                       data-qty="{{ $item->jumlah }}" checked>
                                                <div class="w-5 h-5 rounded-[6px] border-2 border-zinc-300 peer-checked:bg-blue-600 peer-checked:border-blue-600 transition-all duration-300 flex items-center justify-center hover:border-blue-400">
                                                    <i class="fas fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100 transition-all duration-300"></i>
                                                </div>
                                            </div>
                                        </label>

                                        {{-- Gambar Produk --}}
                                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-zinc-100 border border-zinc-200 overflow-hidden shrink-0">
                                            <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" class="w-full h-full object-cover mix-blend-multiply" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                        </div>

                                        {{-- Info & Action --}}
                                        <div class="flex-1 min-w-0 flex flex-col">
                                            <h4 class="text-sm font-bold text-zinc-800 line-clamp-2 leading-snug mb-1">{{ $item->nama_barang }}</h4>
                                            <div class="text-base sm:text-lg font-black text-black mb-3">Rp{{ number_format($item->harga, 0, ',', '.') }}</div>

                                            <div class="mt-auto flex items-center justify-between">

                                                {{-- Qty Control (Pill UI) --}}
                                                <div class="flex items-center bg-white border border-zinc-200 rounded-xl p-1 shadow-sm">
                                                    <button type="button" class="w-7 h-7 flex items-center justify-center text-zinc-500 hover:bg-zinc-100 hover:text-black rounded-lg transition-colors font-bold" onclick="updateQty({{ $item->cart_id }}, -1)">-</button>
                                                    <input type="text" id="qty-input-{{ $item->cart_id }}" class="w-8 text-center font-black text-xs text-black outline-none bg-transparent" value="{{ $item->jumlah }}" readonly>
                                                    <button type="button" class="w-7 h-7 flex items-center justify-center text-blue-600 hover:bg-blue-50 rounded-lg transition-colors font-bold" onclick="updateQty({{ $item->cart_id }}, 1)">+</button>
                                                </div>

                                                {{-- Tombol Hapus --}}
                                                <button type="button" onclick="hapusItem({{ $item->cart_id }})" class="text-zinc-400 hover:text-red-500 transition-colors p-2 hover:bg-red-50 rounded-xl flex items-center gap-1.5 group/del">
                                                    <i class="fas fa-trash-alt text-sm group-hover/del:scale-110 transition-transform"></i>
                                                    <span class="text-xs font-bold hidden sm:inline">Hapus</span>
                                                </button>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- BAGIAN KANAN: RINGKASAN BELANJA (Col-span-4 Sticky) --}}
                <div class="w-full lg:col-span-4 lg:sticky lg:top-28 animate-fade-in" style="animation-delay: 0.1s;">

                    {{-- Form Checkout (Data Tersembunyi) --}}
                    <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                        @csrf
                        <input type="hidden" name="selected_items" id="selected-items-input">

                        <div class="bg-white rounded-[2rem] shadow-soft border border-zinc-200 p-6 sm:p-8">
                            <h3 class="text-lg font-black text-black mb-6 flex items-center gap-2">
                                <i class="fas fa-receipt text-blue-600"></i> Ringkasan Belanja
                            </h3>

                            <div class="space-y-4 mb-6 text-sm">
                                <div class="flex justify-between items-center text-zinc-500 font-medium">
                                    <span>Total Harga (<span id="summary-count" class="font-bold text-black">0</span> brg)</span>
                                    <span id="summary-price" class="font-bold text-black">Rp0</span>
                                </div>
                                <div class="flex justify-between items-center text-zinc-500 font-medium">
                                    <span>Total Diskon</span>
                                    <span class="font-bold text-emerald-500">- Rp0</span>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-zinc-200 border-dashed mb-8">
                                <div class="flex justify-between items-end">
                                    <span class="text-xs font-black text-zinc-400 uppercase tracking-widest">Total Tagihan</span>
                                    <span id="summary-total-price" class="text-2xl lg:text-3xl font-black text-black tracking-tight leading-none">Rp0</span>
                                </div>
                            </div>

                            {{-- Tombol Checkout (Hanya muncul di Desktop, di Mobile pindah ke bottom bar) --}}
                            <button type="submit" id="btn-checkout-desktop" class="hidden lg:flex w-full bg-black text-white hover:bg-blue-600 font-black py-4 rounded-2xl transition-all duration-300 items-center justify-center gap-2 shadow-[0_4px_20px_rgba(0,0,0,0.15)] hover:shadow-[0_8px_30px_rgba(37,99,235,0.3)] hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-black disabled:hover:translate-y-0 disabled:hover:shadow-none">
                                Lanjut Pembayaran <i class="fas fa-arrow-right"></i>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        @endif

    </main>

    {{-- ======================================================= --}}
    {{-- MOBILE STICKY BOTTOM BAR (Muncul di layar HP saja) --}}
    {{-- ======================================================= --}}
    @if(isset($groupedCart) && !$groupedCart->isEmpty() && !(isset($is_guest) && $is_guest))
        <div class="fixed bottom-0 left-0 w-full bg-white/90 backdrop-blur-xl border-t border-zinc-200 p-4 pb-safe shadow-bottom-nav z-50 lg:hidden flex items-center justify-between gap-4">
            <div class="flex flex-col flex-1 min-w-0">
                <span class="text-[10px] font-black text-zinc-400 uppercase tracking-widest">Total Bayar</span>
                <span id="mobile-summary-total" class="text-xl font-black text-black truncate">Rp0</span>
            </div>
            <button type="button" onclick="submitCheckoutForm()" id="btn-checkout-mobile" class="w-auto px-6 bg-black text-white font-black py-3.5 rounded-xl active:scale-95 transition-transform disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                Checkout
            </button>
        </div>
    @endif

    @include('partials.footer')

    <script src="{{ asset('assets/js/navbar.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kalkulasi awal
            calculateTotal();

            // Event listener untuk semua checkbox individual
            const checkboxes = document.querySelectorAll('.js-item-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    calculateTotal();
                    updateMasterCheckboxState();
                });
            });
        });

        // 1. Logika Checkbox Master (Pilih Semua)
        function toggleAllCheckboxes(masterCb) {
            const isChecked = masterCb.checked;
            const checkboxes = document.querySelectorAll('.js-item-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = isChecked;
            });
            calculateTotal();
        }

        // Cek apakah semua tercentang, untuk update status master checkbox
        function updateMasterCheckboxState() {
            const masterCb = document.getElementById('check-all');
            if(!masterCb) return;
            const allCheckboxes = document.querySelectorAll('.js-item-checkbox');
            const checkedCheckboxes = document.querySelectorAll('.js-item-checkbox:checked');

            masterCb.checked = (allCheckboxes.length > 0 && allCheckboxes.length === checkedCheckboxes.length);
        }

        // 2. Kalkulator Total Real-time
        function calculateTotal() {
            let totalBarang = 0;
            let totalHarga = 0;
            let selectedIds = [];

            const checkboxes = document.querySelectorAll('.js-item-checkbox:checked');
            checkboxes.forEach(cb => {
                let qty = parseInt(cb.getAttribute('data-qty'));
                let price = parseInt(cb.getAttribute('data-price'));

                totalBarang += qty;
                totalHarga += (qty * price);
                selectedIds.push(cb.getAttribute('data-id'));
            });

            // Format Rupiah
            const formattedTotal = 'Rp' + totalHarga.toLocaleString('id-ID');

            // Update DOM (Desktop)
            const elSummaryCount = document.getElementById('summary-count');
            const elSummaryPrice = document.getElementById('summary-price');
            const elSummaryTotal = document.getElementById('summary-total-price');
            const inputSelected = document.getElementById('selected-items-input');
            const btnDesktop = document.getElementById('btn-checkout-desktop');

            if(elSummaryCount) elSummaryCount.innerText = totalBarang;
            if(elSummaryPrice) elSummaryPrice.innerText = formattedTotal;
            if(elSummaryTotal) elSummaryTotal.innerText = formattedTotal;
            if(inputSelected) inputSelected.value = selectedIds.join(',');

            // Update DOM (Mobile)
            const elMobileTotal = document.getElementById('mobile-summary-total');
            const btnMobile = document.getElementById('btn-checkout-mobile');
            if(elMobileTotal) elMobileTotal.innerText = formattedTotal;

            // Enable/Disable tombol checkout
            const isDisabled = (totalBarang === 0);
            if(btnDesktop) btnDesktop.disabled = isDisabled;
            if(btnMobile) btnMobile.disabled = isDisabled;
        }

        // Submit form dari tombol mobile
        function submitCheckoutForm() {
            document.getElementById('checkout-form').submit();
        }

        // 3. Update Kuantitas (AJAX)
        async function updateQty(cartId, change) {
            const input = document.getElementById(`qty-input-${cartId}`);
            const checkbox = document.querySelector(`.js-item-checkbox[data-id="${cartId}"]`);

            let currentQty = parseInt(input.value);
            let newQty = currentQty + change;

            if (newQty < 1) return;

            // Efek UI loading sementara
            input.value = '...';

            try {
                const res = await fetch('{{ route('keranjang.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ cart_id: cartId, jumlah: newQty })
                });

                if (res.ok) {
                    input.value = newQty;
                    checkbox.setAttribute('data-qty', newQty); // Update atribut qty untuk kalkulator

                    // Efek animasi bounce angka
                    input.style.transform = 'scale(1.2)';
                    setTimeout(() => input.style.transform = 'scale(1)', 150);

                    calculateTotal(); // Hitung ulang total
                } else {
                    input.value = currentQty;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Gagal memperbarui jumlah', customClass: { popup: 'rounded-2xl' }});
                }
            } catch (error) {
                input.value = currentQty;
            }
        }

        // 4. Hapus Barang dari Keranjang (AJAX)
        function hapusItem(cartId) {
            Swal.fire({
                title: 'Hapus Material?',
                text: "Barang ini akan dihapus dari keranjang belanja Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#000000',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-3xl', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch('{{ route('keranjang.hapus') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify({ cart_id: cartId })
                        });

                        if (res.ok) {
                            // Hapus elemen dengan animasi fade out
                            const row = document.getElementById(`cart-row-${cartId}`);
                            row.style.transition = 'all 0.3s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';

                            setTimeout(() => {
                                row.remove();
                                calculateTotal(); // Hitung ulang total

                                // Jika keranjang benar-benar habis, reload halaman untuk memunculkan Empty State
                                if(document.querySelectorAll('.js-item-checkbox').length === 0) {
                                    window.location.reload();
                                } else {
                                    // Cek apakah toko kosong setelah item dihapus
                                    document.querySelectorAll('.store-group').forEach(store => {
                                        const itemsInStore = store.querySelectorAll('.js-item-checkbox');
                                        if(itemsInStore.length === 0) store.remove();
                                    });
                                }
                            }, 300);

                        }
                    } catch (error) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghapus barang', customClass: { popup: 'rounded-2xl' }});
                    }
                }
            });
        }
    </script>
</body>
</html>
