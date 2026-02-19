@extends('layouts.seller')

@section('title', isset($product) ? 'Kelola Produk' : 'Tambah Produk')

@section('content')
{{-- 
    =========================================
    FULL PAGE "GOD LEVEL" WITH CAROUSEL
    =========================================
--}}
<style>
    /* --- RESET & VARIABLES --- */
    :root {
        --sc-primary: #00AA5B; /* Tokopedia Green */
        --sc-primary-hover: #00924e;
        --sc-accent: #ff5722; /* Shopee Orange */
        --sc-bg: #f8fafc;
        --sc-surface: #ffffff;
        --sc-text: #334155;
        --sc-border: #e2e8f0;
    }

    .sc-container {
        font-family: 'Inter', sans-serif;
        color: var(--sc-text);
        max-width: 100%;
        overflow-x: hidden;
    }

    /* --- CARDS --- */
    .sc-card {
        background: var(--sc-surface);
        border: 1px solid var(--sc-border);
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .sc-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid var(--sc-border);
        background: #fff;
        display: flex; align-items: center; gap: 10px;
    }
    .sc-card-title { font-size: 16px; font-weight: 700; margin: 0; color: #0f172a; }
    .sc-card-body { padding: 24px; }

    /* --- FORMS --- */
    .sc-form-group { margin-bottom: 20px; }
    .sc-label { display: block; font-weight: 600; font-size: 14px; margin-bottom: 8px; color: #475569; }
    .sc-label span { color: #ef4444; }

    .sc-input, .sc-select, .sc-textarea {
        display: block; width: 100%; padding: 10px 14px;
        font-size: 14px; color: #334155; background-color: #fff;
        border: 1px solid #cbd5e1; border-radius: 8px;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .sc-input:focus, .sc-select:focus, .sc-textarea:focus {
        border-color: var(--sc-primary); outline: 0;
        box-shadow: 0 0 0 3px rgba(0, 170, 91, 0.15);
    }

    /* Input Group */
    .sc-input-group { display: flex; align-items: stretch; width: 100%; }
    .sc-input-group-text {
        display: flex; align-items: center; padding: 0 14px;
        font-size: 14px; font-weight: 600; color: #64748b;
        background-color: #f1f5f9; border: 1px solid #cbd5e1;
        border-radius: 8px 0 0 8px; border-right: none;
    }
    .sc-input-group .sc-input { border-top-left-radius: 0; border-bottom-left-radius: 0; }
    .sc-input-group .sc-input-right { border-top-right-radius: 8px !important; border-bottom-right-radius: 8px !important; }
    .sc-input-suffix {
        display: flex; align-items: center; padding: 0 14px;
        font-size: 12px; color: #64748b; background-color: #f1f5f9;
        border: 1px solid #cbd5e1; border-radius: 0 8px 8px 0; border-left: none;
    }

    /* --- UPLOAD ZONE --- */
    .upload-zone {
        border: 2px dashed #cbd5e1; border-radius: 12px;
        background: #f8fafc; padding: 30px; text-align: center;
        cursor: pointer; transition: all 0.2s;
    }
    .upload-zone:hover, .upload-zone.active { border-color: var(--sc-primary); background: #f0fdf4; }
    .upload-zone i { font-size: 40px; color: #94a3b8; display: block; margin-bottom: 10px; }
    
    .preview-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 15px; }
    .preview-box {
        width: 100px; height: 100px; border-radius: 8px;
        overflow: hidden; position: relative; border: 1px solid #e2e8f0;
    }
    .preview-box img { width: 100%; height: 100%; object-fit: cover; }
    .preview-box .btn-del {
        position: absolute; top: 4px; right: 4px; width: 24px; height: 24px;
        background: rgba(255,255,255,0.9); color: #ef4444;
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        cursor: pointer; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .preview-box .label-main {
        position: absolute; bottom: 0; left: 0; width: 100%;
        background: rgba(0,0,0,0.6); color: #fff; font-size: 10px; text-align: center; padding: 2px 0;
    }

    /* --- MOBILE MOCKUP STYLING (CAROUSEL FIX) --- */
    .mockup-container { position: sticky; top: 20px; width: 300px; margin: 0 auto; }
    .hp-frame {
        width: 100%; height: 600px; background: #fff;
        border-radius: 35px; border: 8px solid #1e293b;
        position: relative; overflow: hidden;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .hp-notch {
        position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 120px; height: 20px; background: #1e293b;
        border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; z-index: 50;
    }
    .hp-screen {
        height: 100%; overflow-y: auto; background: #fff;
        padding-bottom: 60px; scrollbar-width: none;
    }
    .hp-screen::-webkit-scrollbar { display: none; }

    /* CAROUSEL LOGIC */
    .hp-carousel-wrapper {
        width: 100%; height: 300px; background: #e2e8f0;
        position: relative;
    }
    .hp-carousel-track {
        display: flex;
        width: 100%; height: 100%;
        overflow-x: auto;
        scroll-snap-type: x mandatory;
        scroll-behavior: smooth;
        scrollbar-width: none; /* Firefox */
    }
    .hp-carousel-track::-webkit-scrollbar { display: none; } /* Chrome */
    
    .hp-slide {
        min-width: 100%; height: 100%;
        scroll-snap-align: start;
        object-fit: cover;
    }
    
    .hp-indicators {
        position: absolute; bottom: 10px; right: 10px;
        background: rgba(0,0,0,0.4); padding: 4px 8px; border-radius: 10px;
        display: flex; gap: 4px; z-index: 10; pointer-events: none;
    }
    .hp-dot { width: 6px; height: 6px; background: rgba(255,255,255,0.5); border-radius: 50%; }
    .hp-dot.active { background: #fff; }

    /* HP Details */
    .hp-info { padding: 12px; border-bottom: 6px solid #f1f5f9; }
    .hp-price { color: var(--sc-accent); font-size: 18px; font-weight: 700; }
    .hp-title { font-size: 13px; line-height: 1.4; color: #1e293b; margin-top: 5px; }
    .hp-shop { padding: 10px 12px; display: flex; align-items: center; gap: 10px; border-bottom: 6px solid #f1f5f9; }
    .hp-shop-icon { width: 36px; height: 36px; background: #cbd5e1; border-radius: 50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:bold; }
    .hp-footer {
        position: absolute; bottom: 0; width: 100%; height: 50px;
        background: #fff; border-top: 1px solid #e2e8f0;
        display: flex; align-items: center; padding: 0 10px;
    }
    .btn-buy { flex: 1; background: var(--sc-accent); color: #fff; font-size: 12px; font-weight: 600; text-align: center; padding: 8px; border-radius: 4px; margin-left: 5px; }

    /* Action Buttons */
    .btn-save {
        background: var(--sc-primary); color: #fff; border: none;
        padding: 10px 20px; border-radius: 50px; font-weight: 600;
        display: inline-flex; align-items: center; gap: 5px; transition: 0.2s;
    }
    .btn-save:hover { background: var(--sc-primary-hover); color: #fff; }
    .btn-cancel {
        background: #fff; border: 1px solid #cbd5e1; color: #475569;
        padding: 10px 20px; border-radius: 50px; font-weight: 600; text-decoration: none;
    }
    .btn-cancel:hover { background: #f8fafc; color: #1e293b; }
</style>

<div class="sc-container">
    <form id="productForm" action="{{ isset($product) ? route('seller.products.update', $product->id) : route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 style="font-weight: 800; color: #1e293b; margin-bottom: 0;">{{ isset($product) ? 'Edit Produk' : 'Tambah Produk Baru' }}</h3>
                <p class="text-muted" style="margin-bottom: 0; font-size: 14px;">Pastikan deskripsi produk lengkap dan menarik.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('seller.products.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-save"><i class="mdi mdi-check"></i> Simpan Produk</button>
            </div>
        </div>

        <div class="row">
            {{-- KOLOM KIRI --}}
            <div class="col-lg-8">
                
                {{-- INFO PRODUK --}}
                <div class="sc-card">
                    <div class="sc-card-header">
                        <i class="mdi mdi-information-outline" style="font-size: 18px; color: var(--sc-primary);"></i>
                        <h6 class="sc-card-title">Informasi Dasar</h6>
                    </div>
                    <div class="sc-card-body">
                        <div class="sc-form-group">
                            <label class="sc-label">Nama Produk <span>*</span></label>
                            <input type="text" class="sc-input" id="inputNama" name="nama_barang" value="{{ old('nama_barang', $product->nama_barang ?? '') }}" placeholder="Contoh: Sepatu Pria Nike Air Zoom" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Kategori <span>*</span></label>
                                <select class="sc-select" name="kategori_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (old('kategori_id', $product->kategori_id ?? '') == $cat->id) ? 'selected' : '' }}>{{ $cat->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Etalase</label>
                                <div class="sc-input-group">
                                    <span class="sc-input-group-text"><i class="mdi mdi-store"></i></span>
                                    <input type="text" class="sc-input sc-input-right" placeholder="Contoh: Promo Ramadhan">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- UPLOAD FOTO --}}
                <div class="sc-card">
                    <div class="sc-card-header">
                        <i class="mdi mdi-image-multiple-outline" style="font-size: 18px; color: var(--sc-primary);"></i>
                        <h6 class="sc-card-title">Foto Produk</h6>
                    </div>
                    <div class="sc-card-body">
                        <input type="file" id="realFileInput" name="gambar[]" multiple accept="image/*" style="display: none;">
                        <div class="upload-zone" id="uploadZone">
                            <i class="mdi mdi-cloud-upload-outline"></i>
                            <h6 style="font-weight: 700;">Klik atau Tarik Foto ke Sini</h6>
                            <p class="text-muted small mb-0">Format: JPG, PNG (Max 5 Foto)</p>
                        </div>
                        <div class="preview-grid" id="previewGrid"></div>
                    </div>
                </div>

                {{-- HARGA & STOK --}}
                <div class="sc-card">
                    <div class="sc-card-header">
                        <i class="mdi mdi-cash-multiple" style="font-size: 18px; color: var(--sc-primary);"></i>
                        <h6 class="sc-card-title">Harga & Stok</h6>
                    </div>
                    <div class="sc-card-body">
                        <div class="row">
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Harga Satuan <span>*</span></label>
                                <div class="sc-input-group">
                                    <span class="sc-input-group-text">Rp</span>
                                    <input type="text" class="sc-input sc-input-right" id="viewHarga" placeholder="0" required>
                                    <input type="hidden" name="harga" id="rawHarga" value="{{ old('harga', $product->harga ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Stok <span>*</span></label>
                                <input type="number" class="sc-input" name="stok" value="{{ old('stok', $product->stok ?? '') }}" placeholder="0" required>
                            </div>
                        </div>
                        {{-- Diskon --}}
                        <div style="background: #f8fafc; padding: 15px; border-radius: 8px; border: 1px dashed #cbd5e1;">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="checkDiskon">
                                <label class="form-check-label fw-bold" for="checkDiskon">Aktifkan Harga Coret / Diskon</label>
                            </div>
                            <div id="boxDiskon" class="row mt-3" style="display: none;">
                                <div class="col-4">
                                    <select class="sc-select" id="typeDiskon" name="tipe_diskon">
                                        <option value="PERSEN">Diskon %</option>
                                        <option value="NOMINAL">Potongan Rp</option>
                                    </select>
                                </div>
                                <div class="col-8">
                                    <input type="number" class="sc-input" id="valDiskon" name="nilai_diskon" placeholder="Masukkan nilai">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- DETAIL & PENGIRIMAN --}}
                <div class="sc-card">
                    <div class="sc-card-header">
                        <i class="mdi mdi-file-document-outline" style="font-size: 18px; color: var(--sc-primary);"></i>
                        <h6 class="sc-card-title">Detail & Pengiriman</h6>
                    </div>
                    <div class="sc-card-body">
                        <div class="row">
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Merek</label>
                                <input type="text" class="sc-input" id="inputMerk" name="merk_barang" value="{{ old('merk_barang', $product->merk_barang ?? '') }}">
                            </div>
                            <div class="col-md-6 sc-form-group">
                                <label class="sc-label">Kondisi</label>
                                <select class="sc-select" id="inputKondisi" name="kondisi">
                                    <option value="Baru">Baru</option>
                                    <option value="Bekas">Bekas</option>
                                </select>
                            </div>
                        </div>
                        <div class="sc-form-group">
                            <label class="sc-label">Deskripsi Lengkap <span>*</span></label>
                            <textarea class="sc-textarea" id="inputDeskripsi" name="deskripsi" rows="6" placeholder="Tuliskan detail produk..." required>{{ old('deskripsi', $product->deskripsi ?? '') }}</textarea>
                        </div>
                        <div class="sc-form-group">
                            <label class="sc-label">Berat (Gram) <span>*</span></label>
                            <div class="sc-input-group">
                                <input type="number" class="sc-input" id="inputBerat" name="berat_gram" value="{{ isset($product->berat_kg) ? $product->berat_kg * 1000 : '' }}" placeholder="Contoh: 1000">
                                <span class="sc-input-suffix">Gram</span>
                            </div>
                            <input type="hidden" name="berat_kg" id="beratKgRaw">
                        </div>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (PREVIEW HP) --}}
            <div class="col-lg-4 d-none d-lg-block">
                <div class="mockup-container">
                    <div class="text-center mb-2 fw-bold text-muted small">Live Preview</div>
                    <div class="hp-frame">
                        <div class="hp-notch"></div>
                        <div class="hp-screen">
                            {{-- Header HP --}}
                            <div style="position: absolute; top: 20px; width: 100%; display: flex; justify-content: space-between; padding: 10px 15px; z-index: 10;">
                                <div style="background: rgba(0,0,0,0.3); border-radius: 50%; width: 30px; height: 30px; display:flex; align-items:center; justify-content:center; color:white;"><i class="mdi mdi-arrow-left"></i></div>
                                <div style="background: rgba(0,0,0,0.3); border-radius: 50%; width: 30px; height: 30px; display:flex; align-items:center; justify-content:center; color:white;"><i class="mdi mdi-cart-outline"></i></div>
                            </div>

                            {{-- Carousel Gambar (Slide) --}}
                            <div class="hp-carousel-wrapper">
                                <div class="hp-carousel-track" id="hpCarousel">
                                    <img src="https://placehold.co/400x400/e2e8f0/94a3b8?text=Produk" class="hp-slide">
                                </div>
                                <div class="hp-indicators" id="hpIndicators" style="display:none;">
                                    </div>
                            </div>

                            {{-- Info HP --}}
                            <div class="hp-info">
                                <div class="hp-price" id="hpPrice">Rp0</div>
                                <div id="hpDiskon" style="display: none; font-size: 11px; color: #94a3b8;">
                                    <span style="text-decoration: line-through;" id="hpOldPrice">Rp0</span>
                                    <span style="background: #ffeceb; color: #ff5722; padding: 0 4px; font-weight: bold; margin-left: 5px;">HEMAT</span>
                                </div>
                                <div class="hp-title" id="hpTitle">Nama Produk Anda...</div>
                            </div>

                            {{-- Shop Info --}}
                            <div class="hp-shop">
                                <div class="hp-shop-icon">{{ substr(Auth::user()->nama ?? 'T', 0, 1) }}</div>
                                <div>
                                    <div style="font-size: 12px; font-weight: 700;">{{ Auth::user()->nama ?? 'Nama Toko' }}</div>
                                    <div style="font-size: 10px; color: #00AA5B;">Online</div>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div style="padding: 12px;">
                                <div style="font-weight: 700; font-size: 13px; margin-bottom: 5px;">Rincian Produk</div>
                                <div style="font-size: 11px; color: #64748b;" id="hpSpec">
                                    Merek: - <br> Kondisi: Baru <br> Berat: 0 gr
                                </div>
                                <div style="margin-top: 10px; font-size: 12px; color: #334155; white-space: pre-line;" id="hpDesc">Belum ada deskripsi.</div>
                            </div>
                        </div>

                        {{-- Footer HP --}}
                        <div class="hp-footer">
                            <i class="mdi mdi-message-processing-outline" style="font-size: 20px; color: #64748b; margin-right: 10px;"></i>
                            <div style="flex:1; border: 1px solid var(--sc-primary); color: var(--sc-primary); text-align: center; padding: 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">+ Keranjang</div>
                            <div class="btn-buy">Beli Sekarang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function formatRupiah(angka) {
        if(!angka) return '';
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);
        if (ribuan) {
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return 'Rp ' + rupiah;
    }

    $(document).ready(function() {
        const fileInput = $('#realFileInput');
        const uploadZone = $('#uploadZone');
        const previewGrid = $('#previewGrid');
        
        // Carousel Elements
        const hpCarousel = $('#hpCarousel');
        const hpIndicators = $('#hpIndicators');
        
        let dt = new DataTransfer();

        uploadZone.on('click', () => fileInput.click());

        fileInput.on('change', function() {
            for(let file of this.files) {
                if(dt.items.length < 5 && file.type.startsWith('image/')) {
                    dt.items.add(file);
                }
            }
            updateFiles();
        });

        function updateFiles() {
            fileInput[0].files = dt.files; 
            previewGrid.empty();
            hpCarousel.empty(); 
            hpIndicators.empty();

            if (dt.files.length > 0) {
                // Show Indicators if more than 1
                if(dt.files.length > 1) hpIndicators.show(); else hpIndicators.hide();

                Array.from(dt.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        // 1. Grid Preview (Form)
                        let badge = index === 0 ? '<div class="label-main">Utama</div>' : '';
                        let html = `
                            <div class="preview-box">
                                <img src="${e.target.result}">
                                ${badge}
                                <div class="btn-del" data-idx="${index}"><i class="mdi mdi-close"></i></div>
                            </div>
                        `;
                        previewGrid.append(html);

                        // 2. Carousel HP (Inject Slide)
                        let slide = `<img src="${e.target.result}" class="hp-slide">`;
                        hpCarousel.append(slide);

                        // 3. Carousel Dot
                        let activeClass = index === 0 ? 'active' : '';
                        let dot = `<div class="hp-dot ${activeClass}"></div>`;
                        hpIndicators.append(dot);
                    }
                    reader.readAsDataURL(file);
                });
            } else {
                // Placeholder Slide
                hpCarousel.html('<img src="https://placehold.co/400x400/e2e8f0/94a3b8?text=Produk" class="hp-slide">');
                hpIndicators.hide();
            }
        }

        // Hapus Gambar
        $(document).on('click', '.btn-del', function(e) {
            e.stopPropagation();
            let idx = $(this).data('idx');
            dt.items.remove(idx);
            updateFiles();
        });

        // Live Text Logic
        $('#inputNama').on('input', function() { $('#hpTitle').text($(this).val() || 'Nama Produk Anda...'); });
        $('#inputDeskripsi').on('input', function() { $('#hpDesc').text($(this).val() || 'Belum ada deskripsi.'); });

        // Specs Logic
        function updateSpecs() {
            let m = $('#inputMerk').val() || '-';
            let k = $('#inputKondisi').val();
            let b = $('#inputBerat').val() || '0';
            $('#hpSpec').html(`Merek: ${m} <br> Kondisi: ${k} <br> Berat: ${b} gr`);
            $('#beratKgRaw').val(parseFloat(b)/1000);
        }
        $('#inputMerk, #inputKondisi, #inputBerat').on('input change', updateSpecs);

        // Price Logic
        const viewHarga = $('#viewHarga');
        const rawHarga = $('#rawHarga');
        viewHarga.on('keyup', function() {
            let val = $(this).val().replace(/[^0-9]/g, '');
            rawHarga.val(val);
            $(this).val(formatRupiah(val).replace('Rp ', ''));
            calcPrice();
        });
        $('#checkDiskon').on('change', function() {
            if(this.checked) $('#boxDiskon').slideDown(); else $('#boxDiskon').slideUp();
            calcPrice();
        });
        $('#typeDiskon, #valDiskon').on('input change', calcPrice);

        function calcPrice() {
            let price = parseFloat(rawHarga.val()) || 0;
            let final = price;
            let active = $('#checkDiskon').is(':checked');
            let type = $('#typeDiskon').val();
            let val = parseFloat($('#valDiskon').val()) || 0;

            if(active && val > 0) {
                $('#hpDiskon').show();
                $('#hpOldPrice').text(formatRupiah(price));
                if(type === 'PERSEN') final = price - (price * (val/100));
                else final = price - val;
            } else {
                $('#hpDiskon').hide();
            }
            if(final < 0) final = 0;
            $('#hpPrice').text(formatRupiah(final));
        }

        if(rawHarga.val()) viewHarga.trigger('keyup');
    });
</script>
@endpush