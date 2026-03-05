<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Pondasikita</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('assets/css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar_style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <style>
        body { background-color: #f3f4f6; font-family: 'Inter', sans-serif; color: #1f2937; }
        .checkout-container { max-width: 1100px; margin: 40px auto; padding: 0 15px; display: flex; gap: 30px; align-items: flex-start; }
        
        .checkout-title { font-size: 1.5rem; font-weight: 800; margin-bottom: 25px; display: flex; align-items: center; gap: 10px; color: #111827; }
        
        .main-col { flex: 1; display: flex; flex-direction: column; gap: 20px; }
        .side-col { width: 350px; position: sticky; top: 90px; }

        /* KOTAK KARTU (MODERN CARD) */
        .card-box { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); border: 1px solid #f1f5f9; }
        .card-title { font-size: 1.1rem; font-weight: 700; margin: 0 0 20px 0; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; }

        /* ALAMAT OPTION (CARD STYLE) */
        .address-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
        .address-card { border: 2px solid #e5e7eb; border-radius: 10px; padding: 15px; cursor: pointer; position: relative; transition: 0.2s; background: #f9fafb; }
        .address-card:hover { border-color: #93c5fd; }
        .address-card input[type="radio"] { position: absolute; opacity: 0; }
        .address-card.selected { border-color: #3b82f6; background: #eff6ff; }
        .address-card.selected::after { content: '\f058'; font-family: 'Font Awesome 5 Free'; font-weight: 900; color: #3b82f6; position: absolute; top: 15px; right: 15px; font-size: 1.2rem; }
        .address-card h4 { margin: 0 0 5px 0; font-size: 1rem; color: #1f2937; }
        .address-card p { margin: 0; font-size: 0.85rem; color: #6b7280; line-height: 1.4; }

        /* FORM MANUAL ALAMAT */
        .manual-form { display: none; background: #f8fafc; padding: 20px; border-radius: 10px; border: 1px solid #e2e8f0; margin-top: 10px;}
        .manual-form.active { display: block; animation: fadeIn 0.3s; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 6px; color: #4b5563; }
        .form-control { width: 100%; padding: 10px 12px; border: 1px solid #d1d5db; border-radius: 6px; font-size: 0.95rem; outline: none; transition: 0.2s; box-sizing: border-box; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }

        /* LIST PRODUK */
        .store-group { margin-bottom: 25px; }
        .store-group h4 { font-size: 1rem; color: #10b981; margin: 0 0 10px 0; display: flex; align-items: center; gap: 8px;}
        .item-row { display: flex; gap: 15px; padding: 15px; border: 1px solid #f1f5f9; border-radius: 8px; margin-bottom: 10px; }
        .item-row img { width: 60px; height: 60px; object-fit: cover; border-radius: 6px; }
        .item-info { flex: 1; }
        .item-info h5 { margin: 0 0 5px 0; font-size: 0.95rem; }
        .item-info p { margin: 0; font-size: 0.85rem; color: #6b7280; }
        .item-price { font-weight: 700; color: #ef4444; }

        /* PENGIRIMAN & CATATAN */
        .shipping-box { background: #f8fafc; padding: 15px; border-radius: 8px; margin-top: 10px; }

        /* RINGKASAN BELANJA (STICKY KANAN) */
        .summary-row { display: flex; justify-content: space-between; font-size: 0.95rem; margin-bottom: 12px; color: #4b5563; }
        .summary-total { display: flex; justify-content: space-between; font-size: 1.2rem; font-weight: 800; color: #111827; padding-top: 15px; border-top: 1px dashed #d1d5db; margin-top: 10px; }
        .btn-submit { width: 100%; background: #3b82f6; color: white; padding: 14px; border: none; border-radius: 8px; font-weight: 700; font-size: 1rem; margin-top: 20px; cursor: pointer; transition: 0.2s; }
        .btn-submit:hover { background: #2563eb; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(59,130,246,0.3); }
        .btn-submit:disabled { background: #9ca3af; cursor: not-allowed; transform: none; box-shadow: none; }

        @media (max-width: 992px) {
            .checkout-container { flex-direction: column; }
            .side-col { width: 100%; position: static; }
            .address-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
        @csrf
        {{-- Data Tersembunyi (Hidden Inputs) --}}
        <input type="hidden" name="user_email" value="{{ $userEmail }}">
        <input type="hidden" name="total_produk_subtotal" value="{{ $totalProduk }}">
        
        {{-- Input Final Alamat untuk dikirim ke Backend --}}
        <input type="hidden" name="shipping_label_alamat" id="final_label">
        <input type="hidden" name="shipping_nama_penerima" id="final_nama">
        <input type="hidden" name="shipping_telepon_penerima" id="final_telepon">
        <input type="hidden" name="shipping_alamat_lengkap" id="final_alamat">
        <input type="hidden" name="shipping_kecamatan" id="final_kecamatan">
        <input type="hidden" name="shipping_kota_kabupaten" id="final_kota">
        <input type="hidden" name="shipping_provinsi" id="final_provinsi">
        <input type="hidden" name="shipping_kode_pos" id="final_kodepos">

        {{-- Passing Data Produk --}}
        @if($isDirectPurchase)
            <input type="hidden" name="direct_purchase" value="1">
            <input type="hidden" name="product_id" value="{{ request('product_id') }}">
            <input type="hidden" name="jumlah" value="{{ request('jumlah') }}">
        @else
            {{-- PERBAIKAN ERROR FOREACH: Pecah string menjadi array terlebih dahulu --}}
            @php
                $rawItems = request('selected_items', '');
                $itemArray = is_string($rawItems) && $rawItems !== '' ? explode(',', $rawItems) : (is_array($rawItems) ? $rawItems : []);
            @endphp
            @foreach($itemArray as $itemId)
                <input type="hidden" name="selected_items[]" value="{{ trim($itemId) }}">
            @endforeach
        @endif

        <div class="checkout-container">
            {{-- KOLOM KIRI (Form & Produk) --}}
            <div class="main-col">
                <h1 class="checkout-title"><i class="fas fa-file-invoice-dollar text-blue-500"></i> Pengiriman & Pembayaran</h1>

                {{-- KARTU 1: ALAMAT PENGIRIMAN --}}
                <div class="card-box">
                    <h3 class="card-title">1. Alamat Pengiriman</h3>
                    
                    <div class="address-grid">
                        {{-- Opsi Alamat Profil --}}
                        <label class="address-card selected" id="card-saved">
                            <input type="radio" name="address_type" value="saved" checked>
                            <h4><i class="fas fa-home text-blue-500"></i> Alamat Profil</h4>
                            @if($alamatUser && !$isAlamatIncomplete)
                                <p class="mt-2"><strong>{{ $alamatUser->nama_penerima }}</strong> ({{ $alamatUser->telepon_penerima }})</p>
                                <p>{{ $alamatUser->alamat_lengkap }}</p>
                                <p>{{ $alamatUser->district_name }}, {{ $alamatUser->city_name }}</p>
                            @else
                                <p class="mt-2 text-red-500"><i class="fas fa-exclamation-triangle"></i> Alamat profil Anda belum lengkap.</p>
                            @endif
                        </label>

                        {{-- Opsi Alamat Baru --}}
                        <label class="address-card" id="card-manual">
                            <input type="radio" name="address_type" value="manual">
                            <h4><i class="fas fa-map-marker-alt text-blue-500"></i> Kirim ke Alamat Lain</h4>
                            <p class="mt-2">Isi formulir alamat baru secara manual untuk pesanan ini.</p>
                        </label>
                    </div>

                    {{-- Form Manual --}}
                    <div id="manual-address-form" class="manual-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label>Nama Penerima</label>
                                <input type="text" class="form-control manual-input" id="manual_nama" placeholder="Nama Lengkap">
                            </div>
                            <div class="form-group">
                                <label>No. Telepon Aktif</label>
                                <input type="number" class="form-control manual-input" id="manual_telepon" placeholder="081234567xxx">
                            </div>
                        </div>
                        <div class="form-group" style="margin-bottom: 15px;">
                            <label>Alamat Lengkap (Jalan, RT/RW, Patokan)</label>
                            <textarea class="form-control manual-input" id="manual_alamat" rows="2"></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Kecamatan</label>
                                <input type="text" class="form-control manual-input" id="manual_kecamatan">
                            </div>
                            <div class="form-group">
                                <label>Kota/Kabupaten</label>
                                <input type="text" class="form-control manual-input" id="manual_kota">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>Provinsi</label>
                                <input type="text" class="form-control manual-input" id="manual_provinsi">
                            </div>
                            <div class="form-group">
                                <label>Kode Pos</label>
                                <input type="number" class="form-control manual-input" id="manual_kodepos">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KARTU 2: PRODUK & PENGIRIMAN --}}
                <div class="card-box">
                    <h3 class="card-title">2. Detail Produk & Kurir</h3>
                    
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Tipe Pengambilan</label>
                        <select name="tipe_pengambilan" id="tipe_pengambilan" class="form-control" style="max-width: 300px; background: #f8fafc;">
                            <option value="pengiriman">Dikirim Kurir ke Alamat</option>
                            <option value="ambil_di_toko">Ambil Sendiri di Toko</option>
                        </select>
                    </div>

                    @foreach($itemsPerToko as $tokoId => $toko)
                        <div class="store-group">
                            <h4><i class="fas fa-store"></i> {{ $toko['nama_toko'] }} <small style="color: #6b7280; font-weight: normal; margin-left: 5px;">({{ $toko['kota_toko'] }})</small></h4>
                            
                            @foreach($toko['items'] as $item)
                                @php $subtotal = $item->harga * $item->jumlah; @endphp
                                <div class="item-row">
                                    <img src="{{ asset('assets/uploads/products/' . ($item->gambar_utama ?? 'default.jpg')) }}" onerror="this.src='{{ asset('assets/uploads/products/default.jpg') }}'">
                                    <div class="item-info">
                                        <h5>{{ $item->nama_barang }}</h5>
                                        <p>{{ $item->jumlah }} x Rp{{ number_format($item->harga, 0, ',', '.') }}</p>
                                    </div>
                                    <div class="item-price">Rp{{ number_format($subtotal, 0, ',', '.') }}</div>
                                </div>
                            @endforeach

                            <div class="shipping-box" id="shipping-box-{{ $tokoId }}">
                                <div class="form-group">
                                    <label>Pilih Durasi Pengiriman</label>
                                    <select name="shipping[{{ $tokoId }}]" class="form-control shipping-select" style="max-width: 100%;">
                                        <option value="reguler_15000">Reguler (2-3 Hari) - Rp15.000</option>
                                        <option value="kargo_30000">Kargo Truk (Khusus Material Berat) - Rp30.000</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="form-group" style="margin-top: 20px;">
                        <label>Pesan Tambahan (Opsional)</label>
                        <input type="text" name="catatan" class="form-control" placeholder="Silakan tinggalkan pesan untuk penjual...">
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Ringkasan Lengket) --}}
            <div class="side-col">
                <div class="card-box">
                    <h3 class="card-title">Ringkasan Belanja</h3>
                    <div class="summary-row">
                        <span>Total Harga Barang</span>
                        <span>Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                    </div>
                    <div class="summary-row">
                        <span>Total Ongkos Kirim</span>
                        <span id="shipping-total-display">Rp0</span>
                    </div>
                    <div class="summary-total">
                        <span>Total Tagihan</span>
                        <span id="grand-total-display">Rp{{ number_format($totalProduk, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="btn-submit" id="btn-submit">Bayar Sekarang</button>
                </div>
            </div>
        </div>
    </form>

    @include('partials.footer')
    
    {{-- SCRIPT INTERAKTIF MODERN --}}
    @php
        $addressData = null;
        if ($alamatUser) {
            $addressData = [
                'label'     => $alamatUser->label_alamat ?? 'Alamat Utama',
                'nama'      => $alamatUser->nama_penerima ?? '',
                'telepon'   => $alamatUser->telepon_penerima ?? '',
                'alamat'    => $alamatUser->alamat_lengkap ?? '',
                'kecamatan' => $alamatUser->district_name ?? '',
                'kota'      => $alamatUser->city_name ?? '',
                'provinsi'  => $alamatUser->province_name ?? '',
                'kodepos'   => $alamatUser->kode_pos ?? ''
            ];
        }
    @endphp

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. DATA ALAMAT DARI BACKEND
            const savedAddress = @json($addressData);
            
            const isProfileIncomplete = @json($isAlamatIncomplete);
            const totalProduk = {{ $totalProduk }};

            // 2. ELEMEN DOM
            const radioAddress = document.querySelectorAll('input[name="address_type"]');
            const cardSaved = document.getElementById('card-saved');
            const cardManual = document.getElementById('card-manual');
            const manualFormDiv = document.getElementById('manual-address-form');
            const manualInputs = document.querySelectorAll('.manual-input');
            const btnSubmit = document.getElementById('btn-submit');
            
            // Input Final (Hidden)
            const final = {
                label: document.getElementById('final_label'),
                nama: document.getElementById('final_nama'),
                telepon: document.getElementById('final_telepon'),
                alamat: document.getElementById('final_alamat'),
                kecamatan: document.getElementById('final_kecamatan'),
                kota: document.getElementById('final_kota'),
                provinsi: document.getElementById('final_provinsi'),
                kodepos: document.getElementById('final_kodepos')
            };
            
            // 3. LOGIKA TOGGLE KARTU ALAMAT
            function updateAddressUI() {
                const selected = document.querySelector('input[name="address_type"]:checked').value;
                
                // Ubah style kartu
                if (selected === 'saved') {
                    cardSaved.classList.add('selected');
                    cardManual.classList.remove('selected');
                    manualFormDiv.classList.remove('active');
                    
                    // Masukkan data saved ke hidden input
                    if (savedAddress) {
                        final.label.value = savedAddress.label; final.nama.value = savedAddress.nama;
                        final.telepon.value = savedAddress.telepon; final.alamat.value = savedAddress.alamat;
                        final.kecamatan.value = savedAddress.kecamatan; final.kota.value = savedAddress.kota;
                        final.provinsi.value = savedAddress.provinsi; final.kodepos.value = savedAddress.kodepos;
                    }

                    // Kunci tombol jika profil kosong
                    if (isProfileIncomplete) {
                        btnSubmit.disabled = true;
                        btnSubmit.innerText = 'Lengkapi Alamat Profil Dulu';
                    } else {
                        btnSubmit.disabled = false;
                        btnSubmit.innerText = 'Bayar Sekarang';
                    }
                } else {
                    cardSaved.classList.remove('selected');
                    cardManual.classList.add('selected');
                    manualFormDiv.classList.add('active');
                    
                    final.label.value = "Alamat Baru Manual";
                    syncManualToHidden(); // Update real-time dari ketikan user
                    
                    btnSubmit.disabled = false;
                    btnSubmit.innerText = 'Bayar Sekarang';
                }
            }

            function syncManualToHidden() {
                if (document.querySelector('input[name="address_type"]:checked').value !== 'manual') return;
                final.nama.value = document.getElementById('manual_nama').value;
                final.telepon.value = document.getElementById('manual_telepon').value;
                final.alamat.value = document.getElementById('manual_alamat').value;
                final.kecamatan.value = document.getElementById('manual_kecamatan').value;
                final.kota.value = document.getElementById('manual_kota').value;
                final.provinsi.value = document.getElementById('manual_provinsi').value;
                final.kodepos.value = document.getElementById('manual_kodepos').value;
            }

            // Event Listener Alamat
            radioAddress.forEach(radio => radio.addEventListener('change', updateAddressUI));
            manualInputs.forEach(input => input.addEventListener('input', syncManualToHidden));
            updateAddressUI(); // Init pertama kali

            // 4. LOGIKA HITUNG ONGKIR
            const shippingSelects = document.querySelectorAll('.shipping-select');
            const tipePengambilan = document.getElementById('tipe_pengambilan');
            
            function calculateTotal() {
                let shippingCost = 0;
                
                if (tipePengambilan.value === 'pengiriman') {
                    // Munculkan opsi kurir
                    document.querySelectorAll('.shipping-box').forEach(el => el.style.display = 'block');
                    
                    shippingSelects.forEach(sel => {
                        let valParts = sel.value.split('_'); // misal: reguler_15000
                        if (valParts.length > 1) {
                            shippingCost += parseInt(valParts[1]);
                        }
                    });
                } else {
                    // Sembunyikan opsi kurir jika ambil di toko
                    document.querySelectorAll('.shipping-box').forEach(el => el.style.display = 'none');
                    shippingCost = 0;
                }

                document.getElementById('shipping-total-display').innerText = 'Rp' + shippingCost.toLocaleString('id-ID');
                document.getElementById('grand-total-display').innerText = 'Rp' + (totalProduk + shippingCost).toLocaleString('id-ID');
            }

            tipePengambilan.addEventListener('change', calculateTotal);
            shippingSelects.forEach(sel => sel.addEventListener('change', calculateTotal));
            calculateTotal(); // Init pertama kali
            
            // 5. VALIDASI SEBELUM SUBMIT
            document.getElementById('checkout-form').addEventListener('submit', function(e) {
                if (document.querySelector('input[name="address_type"]:checked').value === 'manual') {
                    if (!final.nama.value || !final.telepon.value || !final.alamat.value) {
                        e.preventDefault();
                        alert('Mohon isi Nama Penerima, Telepon, dan Alamat Lengkap untuk Alamat Manual!');
                    }
                }
            });
        });
    </script>
</body>
</html>