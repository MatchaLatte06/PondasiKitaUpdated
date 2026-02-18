@extends('layouts.seller')

@section('title', 'Produk Saya')

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center" role="alert" style="background-color: #d1e7dd; color: #0f5132; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #badbcc;">
        <i class="mdi mdi-check-circle-outline me-2" style="font-size: 20px;"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center" role="alert" style="background-color: #f8d7da; color: #842029; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
        <i class="mdi mdi-alert-circle-outline me-2" style="font-size: 20px;"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-warning" style="background-color: #fff3cd; color: #664d03; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #ffe69c;">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h3 class="page-title">Produk Saya</h3>
    <a href="{{ route('seller.products.create') }}" class="btn btn-primary">
        <i class="mdi mdi-plus"></i> Tambah Produk Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <div class="product-info-cell">
                                <img src="{{ $product->gambar_utama ? asset('storage/'.$product->gambar_utama) : 'https://placehold.co/50' }}" class="product-thumb">
                                <div class="product-details">
                                    <span class="product-name">{{ $product->nama_barang }}</span>
                                    <small class="text-muted">ID: {{ $product->id }}</small>
                                </div>
                            </div>
                        </td>
                        <td>Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                        <td>{{ $product->stok }}</td>
                        <td><span class="badge bg-light text-dark">Per {{ $product->satuan_unit }}</span></td>
                        <td>
                            <span class="status-dot {{ $product->is_active ? 'approved' : '' }}"></span>
                            <span class="status-text {{ $product->is_active ? 'approved' : '' }}">
                                {{ $product->is_active ? 'Aktif' : 'Non-Aktif' }}
                            </span>
                        </td>
                        <td>
                            <div class="dropdown-action">
                                <a href="{{ route('seller.products.edit', $product->id) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                <form action="{{ route('seller.products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="empty-state">
                                <i class="mdi mdi-package-variant"></i>
                                <p>Belum ada produk. Mulai jualan sekarang!</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection