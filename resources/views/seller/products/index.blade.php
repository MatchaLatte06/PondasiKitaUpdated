@extends('layouts.seller')

@section('title', 'Manajemen Produk')

@section('content')
<div class="content-wrapper">
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-cube"></i>
            </span> Produk Saya
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('seller.dashboard') }}">Seller</a></li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="card-title">Daftar Produk</h4>
                        <a href="{{ route('seller.products.create') }}" class="btn btn-primary btn-sm btn-icon-text">
                            <i class="mdi mdi-plus btn-icon-prepend"></i> Tambah Produk
                        </a>
                    </div>

                    {{-- Flash Message --}}
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th> No </th>
                                    <th> Gambar </th>
                                    <th> Nama Produk </th>
                                    <th> Harga </th>
                                    <th> Stok </th>
                                    <th> Status </th>
                                    <th> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $index => $produk)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="py-1">
                                            @php
                                                $img = !empty($produk->gambar_utama) ? 'assets/uploads/products/'.$produk->gambar_utama : 'assets/image/default-product.png';
                                            @endphp
                                            <img src="{{ asset($img) }}" alt="image" onerror="this.src='{{ asset('assets/image/default-product.png') }}'" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;" />
                                        </td>
                                        <td>{{ Str::limit($produk->nama_barang, 30) }}</td>
                                        <td>Rp{{ number_format($produk->harga, 0, ',', '.') }}</td>
                                        <td>{{ $produk->stok }}</td>
                                        <td>
                                            @if($produk->status_moderasi == 'pending')
                                                <label class="badge badge-warning">Menunggu</label>
                                            @elseif($produk->status_moderasi == 'rejected')
                                                <label class="badge badge-danger">Ditolak</label>
                                            @elseif($produk->is_active)
                                                <label class="badge badge-success">Aktif</label>
                                            @else
                                                <label class="badge badge-secondary">Nonaktif</label>
                                            @endif
                                        </td>
                                        <td>
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('seller.products.edit', $produk->id) }}" class="btn btn-inverse-info btn-icon btn-sm" title="Edit">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            
                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('seller.products.destroy', $produk->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Yakin hapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-inverse-danger btn-icon btn-sm" title="Hapus">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>

                                            {{-- Tombol Toggle Status (Hanya jika Approved) --}}
                                            @if($produk->status_moderasi == 'approved')
                                                <button class="btn btn-inverse-warning btn-icon btn-sm toggle-status" 
                                                        title="{{ $produk->is_active ? 'Nonaktifkan' : 'Aktifkan' }}"
                                                        data-id="{{ $produk->id }}" 
                                                        data-active="{{ $produk->is_active }}">
                                                    <i class="mdi {{ $produk->is_active ? 'mdi-eye-off' : 'mdi-eye' }}"></i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="mdi mdi-package-variant mb-2" style="font-size: 3rem; color: #ccc;"></i>
                                                <h5 class="text-muted">Belum ada produk.</h5>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Status AJAX
        $('.toggle-status').click(function() {
            var btn = $(this);
            var id = btn.data('id');
            var currentStatus = btn.data('active');
            var newStatus = currentStatus ? 0 : 1;
            var actionText = currentStatus ? 'nonaktifkan' : 'aktifkan';

            if(confirm('Apakah Anda yakin ingin meng-' + actionText + ' produk ini?')) {
                $.ajax({
                    url: "{{ route('seller.products.toggle') }}", 
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        product_id: id,
                        is_active: newStatus
                    },
                    success: function(response) {
                        // Reload halaman agar status terupdate
                        location.reload();
                    },
                    error: function(xhr) {
                        alert('Gagal mengubah status. Silakan coba lagi.');
                    }
                });
            }
        });
    });
</script>
@endpush