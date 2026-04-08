@extends('layouts.seller')

@section('title', 'Katalog Material (Etalase)')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 space-y-6">

    {{-- SETUP SWEETALERT TOAST PREMIUM --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
            customClass: { popup: 'rounded-2xl shadow-lg border border-slate-100' }
        });
    </script>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.fire({icon: 'success', title: '{{ session('success') }}'}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.fire({icon: 'error', title: '{{ session('error') }}'}));</script>
    @endif

    {{-- HEADER HALAMAN --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
                <i class="mdi mdi-package-variant-closed text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Katalog Material</h1>
                <p class="text-sm font-medium text-slate-500 mt-0.5">Kelola daftar produk, stok gudang, dan visibilitas etalase toko Anda.</p>
            </div>
        </div>

        <a href="{{ route('seller.products.create') }}" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-xl shadow-sm shadow-blue-600/20 transition-all flex-shrink-0">
            <i class="mdi mdi-plus-box-outline text-lg leading-none"></i> Tambah Material
        </a>
    </div>

    {{-- MAIN CARD --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden flex flex-col">

        {{-- TOOLBAR PENCARIAN & FILTER --}}
        <form action="{{ route('seller.products.index') }}" method="GET" class="bg-slate-50/50 p-4 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row gap-4 items-center">

            <div class="relative w-full flex-1 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <i class="mdi mdi-magnify text-slate-400 group-focus-within:text-blue-500 transition-colors text-lg"></i>
                </div>
                <input type="text" name="search" class="w-full pl-11 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all shadow-sm" placeholder="Cari nama produk atau SKU..." value="{{ request('search') }}">
            </div>

            <div class="flex items-center gap-3 w-full sm:w-auto">
                <select name="status" class="w-full sm:w-48 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl px-4 py-2.5 hover:bg-slate-50 focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer transition-colors shadow-sm" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Etalase Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Diarsipkan (Nonaktif)</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu Moderasi</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak Pusat</option>
                </select>

                @if(request('search') || request('status'))
                    <a href="{{ route('seller.products.index') }}" class="flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-100 text-sm font-bold rounded-xl transition-colors shadow-sm">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- TABEL PRODUK --}}
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Informasi Material</th>
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Harga Satuan</th>
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-center">Stok</th>
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-center">Moderasi</th>
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-center">Etalase</th>
                        <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($products as $produk)
                        <tr class="hover:bg-slate-50/50 transition-colors group">

                            {{-- 1. Info Produk --}}
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-4 min-w-[280px]">
                                    @php $img = !empty($produk->gambar_utama) ? 'assets/uploads/products/'.$produk->gambar_utama : 'assets/image/default-product.png'; @endphp
                                    <img src="{{ asset($img) }}" alt="img" class="w-14 h-14 rounded-xl object-cover border border-slate-200 flex-shrink-0 bg-white" onerror="this.src='{{ asset('assets/image/default-product.png') }}'">
                                    <div>
                                        <h6 class="text-sm font-bold text-slate-900 leading-snug mb-1.5 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ $produk->nama_barang }}</h6>
                                        <span class="inline-block font-mono text-[10px] font-bold text-slate-500 bg-slate-100 border border-slate-200 px-2 py-0.5 rounded-md">{{ $produk->kode_barang ?? 'Tanpa SKU' }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- 2. Harga --}}
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="text-sm font-black text-blue-600">Rp {{ number_format($produk->harga, 0, ',', '.') }}</div>
                                <div class="text-[11px] font-bold text-slate-400 mt-0.5">/ {{ $produk->satuan_unit ?? 'pcs' }}</div>
                            </td>

                            {{-- 3. Stok --}}
                            <td class="py-4 px-6 text-center">
                                @if($produk->stok <= 5)
                                    <div class="inline-flex items-center justify-center bg-red-50 text-red-600 border border-red-200 font-black text-sm px-3 py-1 rounded-lg">
                                        {{ $produk->stok }}
                                    </div>
                                    <div class="text-[10px] font-bold text-red-500 mt-1">Sisa Dikit!</div>
                                @else
                                    <div class="font-black text-sm text-slate-800">{{ $produk->stok }}</div>
                                @endif
                            </td>

                            {{-- 4. Status Moderasi --}}
                            <td class="py-4 px-6 text-center">
                                @if($produk->status_moderasi == 'approved')
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-50 text-emerald-500" title="Disetujui Admin">
                                        <i class="mdi mdi-check-decagram text-xl leading-none"></i>
                                    </div>
                                @elseif($produk->status_moderasi == 'pending')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-50 text-amber-600 border border-amber-200 text-[10px] font-black uppercase tracking-wider">
                                        <i class="mdi mdi-timer-sand text-sm"></i> Menunggu
                                    </span>
                                @elseif($produk->status_moderasi == 'rejected')
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-red-50 text-red-600 border border-red-200 text-[10px] font-black uppercase tracking-wider cursor-help" title="{{ $produk->alasan_penolakan ?? 'Melanggar aturan komunitas' }}">
                                        <i class="mdi mdi-close-octagon text-sm"></i> Ditolak
                                    </span>
                                @endif
                            </td>

                            {{-- 5. Toggle Etalase (Native Tailwind iOS Switch) --}}
                            <td class="py-4 px-6 text-center">
                                @php $isApproved = $produk->status_moderasi == 'approved'; @endphp
                                <label class="relative inline-flex items-center cursor-pointer {{ !$isApproved ? 'opacity-50 cursor-not-allowed' : '' }}" title="{{ $isApproved ? 'Klik untuk On/Off Etalase' : 'Selesaikan moderasi dulu' }}">
                                    <input type="checkbox" class="sr-only peer toggle-etalase" data-id="{{ $produk->id }}" {{ $produk->is_active ? 'checked' : '' }} {{ !$isApproved ? 'disabled' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                            </td>

                            {{-- 6. Aksi Edit/Delete --}}
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('seller.products.edit', $produk->id) }}" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-slate-200 text-slate-500 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm" title="Edit Data">
                                        <i class="mdi mdi-pencil-outline text-lg leading-none"></i>
                                    </a>

                                    <form action="{{ route('seller.products.destroy', $produk->id) }}" method="POST" class="m-0 delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="w-9 h-9 rounded-xl flex items-center justify-center bg-white border border-slate-200 text-slate-500 hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all shadow-sm btn-delete-confirm" title="Hapus Produk">
                                            <i class="mdi mdi-trash-can-outline text-lg leading-none"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-60">
                                    <i class="mdi mdi-package-variant-closed text-6xl text-slate-300 mb-4"></i>
                                    <h5 class="text-lg font-black text-slate-800 mb-1">Gudang Digital Kosong</h5>
                                    <p class="text-sm font-medium text-slate-500">Tidak ada produk yang sesuai dengan filter pencarian.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION (Laravel Bootstrap/Tailwind wrapper) --}}
        @if($products->hasPages())
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-center lg:justify-end">
                {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // 1. LIVE TOGGLE ETALASE (AJAX Tanpa Reload + Loading State)
    document.querySelectorAll('.toggle-etalase').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let productId = this.dataset.id;
            let isActive = this.checked ? 1 : 0;
            let checkbox = this;

            // Disable sementara mencegah double click spam
            checkbox.disabled = true;

            fetch("{{ route('seller.products.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId, is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    Toast.fire({
                        icon: 'success',
                        title: isActive ? 'Produk Ditampilkan di Etalase' : 'Produk Disembunyikan'
                    });
                } else {
                    throw new Error('Gagal update');
                }
            })
            .catch(error => {
                Toast.fire({icon: 'error', title: 'Koneksi gagal! Gagal merubah etalase.'});
                checkbox.checked = !isActive; // Kembalikan posisi toggle jika error
            })
            .finally(() => {
                checkbox.disabled = false; // Re-enable toggle
            });
        });
    });

    // 2. KONFIRMASI HAPUS (SweetAlert2 Premium Design)
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Material?',
                text: "Data produk ini beserta foto-fotonya akan dihapus permanen dan tidak bisa dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus Permanen!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' } // Border radius konsisten dgn UI
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

});
</script>
@endpush
