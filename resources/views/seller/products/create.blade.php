@extends('layouts.seller')

@section('title', 'Tambah Produk')

@if(session('success'))
    <div class="alert alert-success" style="padding: 1rem; background: #d4edda; color: #155724; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger" style="padding: 1rem; background: #f8d7da; color: #721c24; border-radius: 8px; margin-bottom: 1rem;">
        {{ session('error') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning" style="padding: 1rem; background: #fff3cd; color: #856404; border-radius: 8px; margin-bottom: 1rem;">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<div class="page-header">
    <h3 class="page-title">Tambah Produk Bangunan</h3>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('seller.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="section-title">Informasi Dasar</div>
                    <div class="form-group mb-3">
                        <label class="form-label">Nama Barang</label>
                        <input type="text" name="nama_barang" class="form-control" placeholder="Contoh: Semen Gresik 50kg" required>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan detail barang Anda..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <div class="input-group prefix">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="harga" class="form-control" placeholder="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Satuan Unit</label>
                                <select name="satuan_unit" class="form-select" required>
                                    @foreach(['Kg', 'Meter', 'Sak', 'Batang', 'Pcs', 'Roll', 'Box', 'Engkel'] as $unit)
                                        <option value="{{ $unit }}">Per {{ $unit }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Stok</label>
                                <input type="number" name="stok" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Berat Satuan (Kg)</label>
                                <div class="input-group suffix">
                                    <input type="number" step="0.01" name="berat_kg" class="form-control" placeholder="1.00">
                                    <span class="input-group-text">kg</span>
                                </div>
                                <small class="text-muted">Penting untuk hitung ongkir ekspedisi.</small>
                            </div>
                        </div>
                    </div>

                    <div class="section-title mt-4">Media & Foto</div>
                    <div class="form-group mb-4">
                        <label class="form-label">Foto Utama Produk</label>
                        <input type="file" name="gambar_utama" class="form-control" accept="image/*">
                    </div>

                    <div class="form-actions">
                        <a href="{{ route('seller.products.index') }}" class="btn btn-cancel">Batal</a>
                        <button type="submit" class="btn btn-save">Simpan Produk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card side-widget-card">
            <div class="card-body text-center">
                <i class="mdi mdi-lightbulb-on-outline text-warning" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Tips Berjualan</h5>
                <p class="text-muted small">Gunakan nama produk yang jelas seperti <b>"Merk + Tipe + Ukuran"</b> untuk memudahkan pembeli menemukan barang Anda.</p>
            </div>
        </div>
    </div>
</div>
@endsection