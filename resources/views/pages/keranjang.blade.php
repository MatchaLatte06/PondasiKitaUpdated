<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja - Pondasikita</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; color: #111827; }
        .cart-container { max-width: 1200px; margin: 40px auto; padding: 0 15px; display: flex; gap: 24px; align-items: flex-start; }
        
        /* STATE BELUM LOGIN & KOSONG */
        .empty-state { width: 100%; text-align: center; padding: 80px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .empty-state i { font-size: 5rem; color: #cbd5e1; margin-bottom: 20px; }
        .empty-state h2 { font-size: 1.5rem; font-weight: 800; margin-bottom: 10px; }
        .empty-state p { color: #64748b; margin-bottom: 25px; }
        .btn-action { display: inline-block; background: #2563eb; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: 0.2s; }
        .btn-action:hover { background: #1d4ed8; }

        /* BAGIAN KIRI: LIST BARANG */
        .cart-items-section { flex: 1; }
        .store-group { background: white; border-radius: 12px; padding: 20px; margin-bottom: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .store-header { font-weight: 700; font-size: 1.1rem; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
        .store-header i { color: #10b981; }
        
        .cart-item { display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px dashed #f1f5f9; }
        .cart-item:last-child { border-bottom: none; padding-bottom: 0; }
        .item-checkbox { margin-top: 5px; width: 18px; height: 18px; cursor: pointer; }
        .item-img { width: 80px; height: 80px; border-radius: 8px; object-fit: cover; border: 1px solid #e2e8f0; }
        .item-details { flex: 1; }
        .item-name { font-weight: 600; font-size: 1rem; margin: 0 0 5px 0; color: #334155; }
        .item-price { font-weight: 800; color: #ef4444; font-size: 1.1rem; margin: 0 0 15px 0; }
        
        /* KONTROL JUMLAH & HAPUS */
        .item-actions { display: flex; justify-content: space-between; align-items: center; }
        .qty-controls { display: flex; align-items: center; border: 1px solid #cbd5e1; border-radius: 6px; overflow: hidden; width: max-content; }
        .qty-btn { background: white; border: none; padding: 6px 12px; cursor: pointer; font-weight: bold; color: #475569; transition: 0.2s;}
        .qty-btn:hover { background: #f1f5f9; }
        .qty-input { width: 40px; text-align: center; border: none; border-left: 1px solid #cbd5e1; border-right: 1px solid #cbd5e1; font-weight: 600; color: #0f172a; outline: none; }
        
        .btn-delete { background: none; border: none; color: #94a3b8; font-size: 1.2rem; cursor: pointer; transition: 0.2s; }
        .btn-delete:hover { color: #ef4444; }

        /* BAGIAN KANAN: RINGKASAN BELANJA */
        .cart-summary { width: 320px; background: white; border-radius: 12px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); position: sticky; top: 90px; }
        .summary-title { font-size: 1.1rem; font-weight: 800; margin: 0 0 15px 0; padding-bottom: 15px; border-bottom: 1px solid #f1f5f9; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 15px; color: #475569; font-size: 0.95rem; }
        .summary-total { display: flex; justify-content: space-between; font-weight: 800; font-size: 1.2rem; color: #0f172a; margin-top: 10px; padding-top: 15px; border-top: 1px dashed #cbd5e1; }
        
        .btn-checkout { width: 100%; background: #2563eb; color: white; border: none; padding: 14px; border-radius: 8px; font-weight: 700; font-size: 1rem; margin-top: 20px; cursor: pointer; transition: 0.2s; }
        .btn-checkout:hover { background: #1d4ed8; }
        .btn-checkout:disabled { background: #94a3b8; cursor: not-allowed; }

        @media (max-width: 992px) {
            .cart-container { flex-direction: column; }
            .cart-summary { width: 100%; position: relative; top: 0; }
        }
    </style>
</head>
<body>

    @include('partials.navbar')

    <div class="cart-container">
        
        {{-- KONDISI 1: BELUM LOGIN --}}
        @if(isset($is_guest) && $is_guest)
            <div class="empty-state">
                <i class="fas fa-lock"></i>
                <h2>Silakan Masuk ke Akun Anda</h2>
                <p>Anda harus masuk (login) terlebih dahulu untuk melihat dan menggunakan fitur keranjang belanja.</p>
                <a href="{{ route('login') }}" class="btn-action">Masuk Sekarang</a>
            </div>
        
        {{-- KONDISI 2: KERANJANG KOSONG --}}
        @elseif(isset($groupedCart) && $groupedCart->isEmpty())
            <div class="empty-state">
                <i class="fas fa-shopping-basket"></i>
                <h2>Keranjang Belanja Kosong</h2>
                <p>Wah, keranjang belanjamu masih kosong. Yuk temukan bahan bangunan impianmu!</p>
                <a href="{{ route('produk.index') }}" class="btn-action">Mulai Belanja</a>
            </div>

        {{-- KONDISI 3: KERANJANG TERISI --}}
        @else
            {{-- Kiri: List Produk --}}
            <div class="cart-items-section">
                @foreach($groupedCart as $namaToko => $items)
                    <div class="store-group">
                        <div class="store-header">
                            <i class="fas fa-store"></i> {{ $namaToko }}
                        </div>

                        @foreach($items as $item)
                            <div class="cart-item" id="cart-row-{{ $item->cart_id }}">
                                {{-- Checkbox untuk memilih barang yang mau dicheckout --}}
                                <input type="checkbox" class="item-checkbox js-item-checkbox" 
                                       data-id="{{ $item->cart_id }}" 
                                       data-price="{{ $item->harga }}" 
                                       data-qty="{{ $item->jumlah }}" checked>
                                
                                <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" class="item-img" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                
                                <div class="item-details">
                                    <h3 class="item-name">{{ $item->nama_barang }}</h3>
                                    <p class="item-price">Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                                    
                                    <div class="item-actions">
                                        <div class="qty-controls">
                                            <button type="button" class="qty-btn" onclick="updateQty({{ $item->cart_id }}, -1)">-</button>
                                            <input type="text" id="qty-input-{{ $item->cart_id }}" class="qty-input" value="{{ $item->jumlah }}" readonly>
                                            <button type="button" class="qty-btn" onclick="updateQty({{ $item->cart_id }}, 1)">+</button>
                                        </div>
                                        <button type="button" class="btn-delete" onclick="hapusItem({{ $item->cart_id }})" title="Hapus Barang">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{-- Kanan: Ringkasan Belanja --}}
            <div class="cart-summary">
                <h3 class="summary-title">Ringkasan Belanja</h3>
                <div class="summary-row">
                    <span>Total Harga (<span id="summary-count">0</span> barang)</span>
                    <span id="summary-price">Rp0</span>
                </div>
                <div class="summary-total">
                    <span>Total Tagihan</span>
                    <span id="summary-total-price">Rp0</span>
                </div>
                
                <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="selected_items" id="selected-items-input">
                    
                    <button type="submit" class="btn-checkout" id="btn-checkout">Beli Sekarang</button>
                </form>
            </div>
        @endif

    </div>

    @include('partials.footer')
    
    {{-- Script untuk AJAX & Kalkulasi Total --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            calculateTotal();

            // Dengarkan perubahan pada checkbox
            const checkboxes = document.querySelectorAll('.js-item-checkbox');
            checkboxes.forEach(cb => {
                cb.addEventListener('change', calculateTotal);
            });
        });

        // 1. Kalkulator Total Real-time
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

            document.getElementById('summary-count').innerText = totalBarang;
            document.getElementById('summary-price').innerText = 'Rp' + totalHarga.toLocaleString('id-ID');
            document.getElementById('summary-total-price').innerText = 'Rp' + totalHarga.toLocaleString('id-ID');
            document.getElementById('selected-items-input').value = selectedIds.join(',');

            // Disable tombol beli jika tidak ada yang dicentang
            document.getElementById('btn-checkout').disabled = totalBarang === 0;
        }

        // 2. Update Kuantitas (AJAX)
        async function updateQty(cartId, change) {
            const input = document.getElementById(`qty-input-${cartId}`);
            const checkbox = document.querySelector(`.js-item-checkbox[data-id="${cartId}"]`);
            
            let currentQty = parseInt(input.value);
            let newQty = currentQty + change;

            if (newQty < 1) return; // Tidak boleh kurang dari 1

            // Animasi loading kecil
            input.value = '...';

            try {
                const res = await fetch('{{ route('keranjang.update') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ cart_id: cartId, jumlah: newQty })
                });

                if (res.ok) {
                    input.value = newQty;
                    checkbox.setAttribute('data-qty', newQty); // Update data di checkbox
                    calculateTotal(); // Hitung ulang total di kanan
                } else {
                    input.value = currentQty;
                    Swal.fire('Error', 'Gagal memperbarui jumlah', 'error');
                }
            } catch (error) {
                input.value = currentQty;
            }
        }

        // 3. Hapus Barang dari Keranjang (AJAX dengan SweetAlert)
        function hapusItem(cartId) {
            Swal.fire({
                title: 'Hapus barang ini?',
                text: "Barang akan dihapus dari keranjang belanjamu.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    try {
                        const res = await fetch('{{ route('keranjang.hapus') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ cart_id: cartId })
                        });

                        if (res.ok) {
                            // Hapus elemen HTML-nya
                            document.getElementById(`cart-row-${cartId}`).remove();
                            calculateTotal(); // Hitung ulang total
                            
                            // Jika keranjang habis (semua dihapus), refresh halaman agar muncul state kosong
                            if(document.querySelectorAll('.cart-item').length === 0) {
                                window.location.reload();
                            }
                        }
                    } catch (error) {
                        Swal.fire('Error', 'Gagal menghapus barang', 'error');
                    }
                }
            });
        }
    </script>
</body>
</html>