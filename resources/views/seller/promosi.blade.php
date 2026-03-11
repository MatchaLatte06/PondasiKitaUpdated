@extends('layouts.seller')

@section('title', 'Manajemen Harga Coret (Diskon)')

@section('content')
<style>
    :root {
        --promo-dark: #0f172a;
        --promo-primary: #ef4444; /* Merah untuk diskon */
        --promo-warning: #f59e0b;
        --promo-success: #10b981;
        --promo-border: #e2e8f0;
        --promo-bg: #f8fafc;
        --text-mut: #64748b;
    }
    .promo-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* HEADER & STATS */
    .promo-header-row { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 20px; margin-bottom: 24px; }
    .promo-title-box { display: flex; align-items: center; gap: 15px; }
    .promo-icon { background: var(--promo-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    .stats-card { background: white; border: 1px solid var(--promo-border); padding: 16px 24px; border-radius: 12px; display: flex; gap: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .stat-item h6 { font-size: 12px; font-weight: 700; color: var(--text-mut); text-transform: uppercase; margin-bottom: 4px; }
    .stat-item h3 { font-size: 24px; font-weight: 800; color: var(--promo-dark); margin: 0; }
    .stat-item h3.text-danger { color: var(--promo-primary) !important; }

    /* TABS & TOOLBAR */
    .promo-nav-tabs { display: flex; gap: 8px; border-bottom: 2px solid var(--promo-border); margin-bottom: 24px; overflow-x: auto; scrollbar-width: none; }
    .promo-nav-tabs::-webkit-scrollbar { display: none; }
    .promo-tab { padding: 12px 20px; font-weight: 700; color: var(--text-mut); text-decoration: none; font-size: 14px; border-bottom: 3px solid transparent; white-space: nowrap; transition: 0.2s; }
    .promo-tab:hover { color: var(--promo-dark); }
    .promo-tab.active { color: var(--promo-primary); border-bottom-color: var(--promo-primary); }

    .promo-toolbar { display: flex; justify-content: space-between; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
    .search-box { position: relative; flex-grow: 1; max-width: 400px; }
    .search-box i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 1.2rem; }
    .search-box input { width: 100%; padding: 12px 16px 12px 45px; border-radius: 10px; border: 1px solid #cbd5e1; font-weight: 500; font-size: 14px; }
    .search-box input:focus { border-color: var(--promo-primary); outline: none; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }

    /* TABLE PROMO */
    .promo-card { background: white; border: 1px solid var(--promo-border); border-radius: 16px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); overflow-x: auto; }
    .table-promo { width: 100%; min-width: 900px; border-collapse: collapse; }
    .table-promo th { background: var(--promo-bg); color: #334155; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 16px 20px; border-bottom: 2px solid var(--promo-border); text-align: left; }
    .table-promo td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .table-promo tr:hover td { background-color: #f8fafc; }

    .product-info { display: flex; align-items: center; gap: 12px; }
    .product-img { width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid var(--promo-border); }
    .product-name { font-size: 13px; font-weight: 700; color: var(--promo-dark); margin: 0 0 4px 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .product-price-ori { font-size: 12px; color: var(--text-mut); text-decoration: line-through; }

    .discount-badge { display: inline-block; background: #fef2f2; color: var(--promo-primary); border: 1px solid #fecaca; padding: 4px 8px; border-radius: 6px; font-weight: 800; font-size: 12px; }
    .price-final { font-size: 15px; font-weight: 900; color: var(--promo-primary); }

    /* Badges */
    .status-badge { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; border: 1px solid transparent; }
    .s-aktif { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }
    .s-nonaktif { background: #f1f5f9; color: #475569; border-color: #cbd5e1; }
    .s-mendatang { background: #fffbeb; color: #d97706; border-color: #fde68a; }

    .btn-edit { background: white; border: 1px solid #cbd5e1; color: #334155; padding: 6px 14px; border-radius: 6px; font-weight: 700; font-size: 12px; transition: 0.2s; cursor: pointer; }
    .btn-edit:hover { background: var(--promo-primary); color: white; border-color: var(--promo-primary); }
</style>

<div class="promo-wrapper">

    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{!! session('success') !!}', showConfirmButton: false, timer: 3000}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Gagal!', '{!! session('error') !!}', 'error'));</script>
    @endif

    {{-- 1. HEADER & STATS --}}
    <div class="promo-header-row">
        <div class="promo-title-box">
            <div class="promo-icon"><i class="mdi mdi-tag-multiple-outline"></i></div>
            <div>
                <h3 class="m-0 fw-bold fs-4">Harga Coret Produk</h3>
                <p class="m-0 text-muted" style="font-size: 13px;">Atur diskon produk agar lebih menarik perhatian pembeli.</p>
            </div>
        </div>
        <div class="stats-card">
            <div class="stat-item border-end pe-4">
                <h6>Semua Promo</h6>
                <h3>{{ $stats['semua'] ?? 0 }}</h3>
            </div>
            <div class="stat-item border-end pe-4">
                <h6>Sedang Aktif</h6>
                <h3 class="text-danger">{{ $stats['aktif'] ?? 0 }}</h3>
            </div>
            <div class="stat-item">
                <h6>Akan Datang</h6>
                <h3>{{ $stats['akan_datang'] ?? 0 }}</h3>
            </div>
        </div>
    </div>

    {{-- 2. TABS & TOOLBAR --}}
    @php $tab = $currentTab ?? 'semua'; @endphp
    <div class="promo-nav-tabs">
        <a href="?tab=semua" class="promo-tab {{ $tab == 'semua' ? 'active' : '' }}">Semua</a>
        <a href="?tab=aktif" class="promo-tab {{ $tab == 'aktif' ? 'active' : '' }}">Sedang Berjalan</a>
        <a href="?tab=akan_datang" class="promo-tab {{ $tab == 'akan_datang' ? 'active' : '' }}">Akan Datang</a>
        <a href="?tab=tidak_aktif" class="promo-tab {{ $tab == 'tidak_aktif' ? 'active' : '' }}">Tidak Aktif / Selesai</a>
    </div>

    <div class="promo-toolbar">
        <form action="{{ route('seller.promotion.discounts') }}" method="GET" class="search-box m-0">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <i class="mdi mdi-magnify"></i>
            <input type="text" name="search" placeholder="Cari Nama Produk..." value="{{ request('search') }}">
        </form>
    </div>

    {{-- 3. TABEL PROMOSI --}}
    <div class="promo-card">
        <table class="table-promo">
            <thead>
                <tr>
                    <th width="35%">Informasi Produk</th>
                    <th width="15%">Diskon</th>
                    <th width="15%">Harga Akhir</th>
                    <th width="20%">Periode Promo</th>
                    <th width="15%" class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    @php
                        // Logika Tampilan Status & Harga
                        $hasPromo = !empty($p->nilai_diskon) && $p->nilai_diskon > 0;
                        $now = time();
                        $start = strtotime($p->diskon_mulai);
                        $end = strtotime($p->diskon_berakhir);
                        
                        $statusClass = 's-nonaktif'; $statusText = 'Tidak Aktif';
                        if ($hasPromo) {
                            if ($now >= $start && $now <= $end) { $statusClass = 's-aktif'; $statusText = 'Aktif'; }
                            elseif ($now < $start) { $statusClass = 's-mendatang'; $statusText = 'Mendatang'; }
                            else { $statusClass = 's-nonaktif'; $statusText = 'Berakhir'; }
                        }

                        // Kalkulasi Harga Akhir
                        $hargaAkhir = $p->harga;
                        if ($hasPromo && $statusText == 'Aktif') {
                            if ($p->tipe_diskon == 'PERSEN') {
                                $potongan = ($p->harga * $p->nilai_diskon) / 100;
                                $hargaAkhir = $p->harga - $potongan;
                            } else {
                                $hargaAkhir = $p->harga - $p->nilai_diskon;
                            }
                        }
                    @endphp
                    <tr>
                        {{-- Produk --}}
                        <td>
                            <div class="product-info">
                                <img src="{{ asset('assets/uploads/products/' . ($p->gambar_utama ?? 'default.jpg')) }}" class="product-img">
                                <div>
                                    <p class="product-name">{{ $p->nama_barang }}</p>
                                    @if($hasPromo)
                                        <span class="product-price-ori">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                    @else
                                        <span style="font-size: 13px; font-weight: 700; color: var(--text-mut);">Rp {{ number_format($p->harga, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Diskon --}}
                        <td>
                            @if($hasPromo)
                                <div class="discount-badge">
                                    {{ $p->tipe_diskon == 'PERSEN' ? $p->nilai_diskon.'%' : 'Rp '.number_format($p->nilai_diskon, 0, ',', '.') }}
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Harga Akhir --}}
                        <td>
                            @if($hasPromo)
                                <div class="price-final">Rp {{ number_format($hargaAkhir, 0, ',', '.') }}</div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>

                        {{-- Periode & Status --}}
                        <td>
                            @if($hasPromo)
                                <div style="font-size: 11px; font-weight: 700; color: var(--text-mut); margin-bottom: 6px;">
                                    {{ date('d M Y', $start) }} - {{ date('d M Y', $end) }}
                                </div>
                                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                            @else
                                <span class="status-badge s-nonaktif">Tidak Aktif</span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="text-end">
                            <button type="button" class="btn-edit" onclick="openPromoModal({{ json_encode($p) }})">
                                <i class="mdi mdi-pencil-outline"></i> Atur Promo
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="mdi mdi-tag-off-outline d-block mb-2" style="font-size: 3rem; color: #cbd5e1;"></i>
                            <h6 class="fw-bold text-dark">Data Produk Kosong</h6>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 d-flex justify-content-center">
        {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL ATUR PROMO --}}
<div class="modal fade" id="modalPromo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form id="formPromo">
                <input type="hidden" name="product_ids[]" id="input_product_id">
                <div class="modal-header bg-light border-bottom rounded-top-4 p-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="mdi mdi-tag-plus text-danger me-2"></i> Atur Harga Coret</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="fw-bold" style="font-size:12px; color:#475569;">NAMA PRODUK</label>
                        <p id="modal_product_name" class="fw-bold text-dark m-0 mt-1"></p>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:12px; color:#475569;">TIPE DISKON</label>
                            <select name="tipe_diskon" id="modal_tipe_diskon" class="form-select mt-1" required>
                                <option value="PERSEN">Persentase (%)</option>
                                <option value="NOMINAL">Nominal (Rp)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:12px; color:#475569;">NILAI DISKON</label>
                            <input type="number" name="nilai_diskon" id="modal_nilai_diskon" class="form-control mt-1" placeholder="Cth: 10" required>
                            <small class="text-muted" style="font-size:11px;">*Isi 0 untuk mematikan promo.</small>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:12px; color:#475569;">TANGGAL MULAI</label>
                            <input type="datetime-local" name="diskon_mulai" id="modal_diskon_mulai" class="form-control mt-1">
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold" style="font-size:12px; color:#475569;">TANGGAL BERAKHIR</label>
                            <input type="datetime-local" name="diskon_berakhir" id="modal_diskon_berakhir" class="form-control mt-1">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer p-3 bg-light border-top rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger fw-bold px-4" id="btnSavePromo">Simpan Promo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Fungsi Buka Modal & Set Data
    function openPromoModal(product) {
        document.getElementById('input_product_id').value = product.id;
        document.getElementById('modal_product_name').innerText = product.nama_barang;
        
        document.getElementById('modal_tipe_diskon').value = product.tipe_diskon || 'PERSEN';
        document.getElementById('modal_nilai_diskon').value = product.nilai_diskon || '';
        
        // Format Tanggal untuk datetime-local input
        if(product.diskon_mulai) {
            document.getElementById('modal_diskon_mulai').value = product.diskon_mulai.substring(0, 16);
        } else {
            document.getElementById('modal_diskon_mulai').value = '';
        }

        if(product.diskon_berakhir) {
            document.getElementById('modal_diskon_berakhir').value = product.diskon_berakhir.substring(0, 16);
        } else {
            document.getElementById('modal_diskon_berakhir').value = '';
        }

        new bootstrap.Modal(document.getElementById('modalPromo')).show();
    }

    // Fungsi Simpan (AJAX) sinkron dengan Controller 'updateDiscount'
    document.getElementById('btnSavePromo').addEventListener('click', function() {
        let form = document.getElementById('formPromo');
        let formData = new FormData(form);
        
        // Ubah format array untuk payload JSON
        let dataObj = Object.fromEntries(formData.entries());
        dataObj.product_ids = [document.getElementById('input_product_id').value];

        fetch("{{ route('seller.promotion.discounts.update') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify(dataObj)
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success') {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success', 
                    title: data.message, showConfirmButton: false, timer: 2000
                }).then(() => location.reload());
            } else {
                Swal.fire('Gagal!', data.message || 'Terjadi kesalahan', 'error');
            }
        })
        .catch(err => {
            Swal.fire('Error!', 'Sistem gagal menghubungi server.', 'error');
        });
    });
</script>
@endsection