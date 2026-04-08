@extends('layouts.seller')

@section('title', 'Manajemen Pengiriman')

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 space-y-8">

    {{-- Notifikasi SweetAlert (Render from Backend) --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-2xl' }}));</script>
    @endif

    {{-- 1. HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
                <i class="mdi mdi-truck-delivery text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan Logistik</h1>
                <p class="text-sm font-medium text-slate-500 mt-0.5">Atur armada toko dan kurir ekspedisi untuk pengiriman material Anda.</p>
            </div>
        </div>

        <button type="button" onclick="openModal('tambah')" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-sm shadow-slate-900/20 transition-all flex-shrink-0">
            <i class="mdi mdi-plus-circle-outline text-lg leading-none"></i> Tambah Layanan
        </button>
    </div>

    {{-- 2. EDU BANNER (B2B) --}}
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 text-white overflow-hidden shadow-lg shadow-blue-500/20 flex flex-col md:flex-row items-start md:items-center gap-6">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-16 h-16 bg-white/10 border border-white/20 rounded-2xl flex items-center justify-center text-white shadow-inner flex-shrink-0">
            <i class="mdi mdi-dump-truck text-3xl"></i>
        </div>
        <div class="relative z-10">
            <h5 class="text-lg font-black tracking-tight mb-2">Pentingnya "Armada Toko" di Bisnis Material</h5>
            <p class="text-sm font-medium text-blue-100 leading-relaxed max-w-4xl">
                Pembeli grosir material berat (Semen, Pasir, Besi) tidak dapat dikirim via kurir motor. Pastikan Anda <b>menambahkan dan mengaktifkan Armada Toko</b> agar pembeli bisa checkout material berat dengan biaya flat (per rit/truk).
            </p>
        </div>
    </div>

    {{-- 3. DAFTAR KATEGORI PENGIRIMAN --}}
    <div class="space-y-6">
        @foreach($tipeOrder as $tipeKey => $tipeLabel)
            @php
                $kurirList = $groupedKurir[$tipeKey] ?? [];
                $headerIcon = $tipeKey == 'TOKO' ? 'mdi-truck-flatbed text-amber-500 bg-amber-50' : 'mdi-package-variant text-blue-500 bg-blue-50';
            @endphp

            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">

                {{-- Header Kategori --}}
                <div class="bg-slate-50/50 px-6 py-5 border-b border-slate-100 flex items-center gap-3">
                    <div class="p-2 rounded-xl {{ $headerIcon }}"><i class="mdi leading-none text-lg"></i></div>
                    <h5 class="font-black text-slate-800 text-lg">{{ $tipeLabel }}</h5>
                </div>

                {{-- List Kurir --}}
                <div class="divide-y divide-slate-100">
                    @if(count($kurirList) > 0)
                        @foreach($kurirList as $kurir)
                            <div class="p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-5 hover:bg-slate-50/50 transition-colors">

                                {{-- Info Kurir --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <h6 class="text-base font-bold text-slate-900">{{ $kurir->nama_kurir }}</h6>
                                        @if($kurir->is_active)
                                            <i class="mdi mdi-check-decagram text-emerald-500 text-lg leading-none" title="Aktif"></i>
                                        @endif
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm">
                                        <span class="text-slate-500 font-medium flex items-center gap-1.5"><i class="mdi mdi-clock-outline text-slate-400"></i> Estimasi: {{ $kurir->estimasi_waktu }}</span>
                                        <span class="hidden sm:block w-1 h-1 bg-slate-300 rounded-full"></span>
                                        <span class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-black">Tarif: Rp {{ number_format($kurir->biaya, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                {{-- Aksi & Toggle --}}
                                <div class="flex items-center gap-4 sm:gap-6 w-full sm:w-auto justify-end border-t border-slate-100 sm:border-t-0 pt-4 sm:pt-0 mt-2 sm:mt-0">

                                    {{-- Buttons --}}
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition-colors"
                                            onclick="openModal('edit', {{ json_encode($kurir) }})" title="Edit Layanan">
                                            <i class="mdi mdi-pencil-box-outline text-xl leading-none"></i>
                                        </button>

                                        <form action="{{ route('seller.pengaturan.pengiriman.destroy', $kurir->id) }}" method="POST" class="m-0 delete-form">
                                            @csrf @method('DELETE')
                                            <button type="button" class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:bg-red-50 hover:text-red-500 transition-colors btn-delete-confirm" title="Hapus Layanan">
                                                <i class="mdi mdi-trash-can-outline text-xl leading-none"></i>
                                            </button>
                                        </form>
                                    </div>

                                    <div class="w-px h-8 bg-slate-200"></div>

                                    {{-- Tailwind iOS Switch --}}
                                    <label class="relative inline-flex items-center cursor-pointer" title="Aktifkan/Nonaktifkan">
                                        <input type="checkbox" class="sr-only peer toggle-input" data-id="{{ $kurir->id }}" {{ $kurir->is_active ? 'checked' : '' }}>
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                    </label>

                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="py-12 flex flex-col items-center justify-center opacity-60">
                            <i class="mdi mdi-inbox-remove-outline text-5xl text-slate-400 mb-3"></i>
                            <p class="text-sm font-bold text-slate-500">Belum ada pengaturan layanan untuk kategori ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

</div>

{{-- 4. TAILWIND CUSTOM MODAL (Pengganti Bootstrap) --}}
<div id="kurirModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Background Overlay Gelap --}}
    <div id="modalOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300" aria-hidden="true" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            {{-- Modal Panel --}}
            <div id="modalPanel" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all opacity-0 scale-95 duration-300 sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">

                <form action="{{ route('seller.pengaturan.pengiriman.store') }}" method="POST" id="kurirForm">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="bg-slate-50 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-900" id="modalTitle">Form Layanan Logistik</h3>
                        <button type="button" onclick="closeModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-red-100 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors">
                            <i class="mdi mdi-close text-lg leading-none"></i>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 space-y-5">
                        <input type="hidden" name="action" id="form-action" value="tambah">
                        <input type="hidden" name="kurir_id" id="kurir_id">

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Tipe Kategori Ekspedisi</label>
                            <select name="tipe_kurir" id="tipe_kurir" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer">
                                @foreach ($tipeOrder as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Nama Layanan / Kurir</label>
                            <input type="text" name="nama_kurir" id="nama_kurir" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none placeholder-slate-400" placeholder="Contoh: Pickup L300 / JNE Kargo" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Estimasi Tiba</label>
                                <input type="text" name="estimasi_waktu" id="estimasi_waktu" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none placeholder-slate-400" placeholder="Contoh: 1 Hari / 3 Jam" required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Biaya Flat (Rp)</label>
                                <input type="number" name="biaya" id="biaya" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none placeholder-slate-400" placeholder="Isi 0 jika Gratis" required min="0">
                            </div>
                        </div>

                        <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 flex justify-between items-center gap-4">
                            <div>
                                <strong class="block text-sm font-black text-slate-800">Langsung Aktifkan</strong>
                                <span class="text-[11px] font-medium text-slate-500 leading-tight">Pembeli dapat langsung memilih opsi ini saat checkout.</span>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0">
                                <input type="checkbox" name="is_active" id="is_active_modal" value="1" class="sr-only peer" checked>
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3 rounded-b-3xl">
                        <button type="button" onclick="closeModal()" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-sm shadow-blue-600/20 transition-colors">Simpan Pengaturan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

    // --- 1. TAILWIND MODAL LOGIC (Pengganti Bootstrap) ---
    const modal = document.getElementById('kurirModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalPanel = document.getElementById('modalPanel');

    function openModal(mode, data = null) {
        // Reset Form
        const form = document.getElementById('kurirForm');
        form.reset();

        if(mode === 'tambah') {
            document.getElementById('modalTitle').textContent = 'Tambah Armada / Ekspedisi';
            document.getElementById('form-action').value = 'tambah';
            document.getElementById('kurir_id').value = '';
            document.getElementById('is_active_modal').checked = true;
        } else if(mode === 'edit' && data) {
            document.getElementById('modalTitle').textContent = 'Edit Rincian Layanan';
            document.getElementById('form-action').value = 'update';
            document.getElementById('kurir_id').value = data.id;
            document.getElementById('nama_kurir').value = data.nama_kurir;
            document.getElementById('tipe_kurir').value = data.tipe_kurir;
            document.getElementById('estimasi_waktu').value = data.estimasi_waktu;
            document.getElementById('biaya').value = data.biaya;
            document.getElementById('is_active_modal').checked = data.is_active == 1;
        }

        // Tampilkan Modal dengan animasi Tailwind
        modal.classList.remove('hidden');
        // Force reflow agar animasi jalan
        void modal.offsetWidth;
        modalOverlay.classList.replace('opacity-0', 'opacity-100');
        modalPanel.classList.replace('opacity-0', 'opacity-100');
        modalPanel.classList.replace('scale-95', 'scale-100');
    }

    function closeModal() {
        modalOverlay.classList.replace('opacity-100', 'opacity-0');
        modalPanel.classList.replace('opacity-100', 'opacity-0');
        modalPanel.classList.replace('scale-100', 'scale-95');

        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300); // Waktu harus sama dengan durasi tailwind (duration-300)
    }

    // --- 2. AJAX TOGGLE STATUS (Switch Native iOS) ---
    document.querySelectorAll('.toggle-input').forEach(toggle => {
        toggle.addEventListener('change', function() {
            let kurirId = this.dataset.id;
            let isActive = this.checked ? 1 : 0;
            let checkbox = this;

            fetch("{{ route('seller.pengaturan.pengiriman.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ kurir_id: kurirId, is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if(data.status !== 'success') throw new Error('Update failed');

                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: isActive ? 'Layanan Diaktifkan' : 'Layanan Dinonaktifkan',
                    showConfirmButton: false, timer: 1500, customClass: { popup: 'rounded-2xl' }
                });
            })
            .catch(error => {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Koneksi gagal!', showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-2xl' }});
                checkbox.checked = !isActive; // Kembalikan Switch
            });
        });
    });

    // --- 3. KONFIRMASI HAPUS LAYANAN ---
    document.querySelectorAll('.btn-delete-confirm').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            let form = this.closest('.delete-form');
            Swal.fire({
                title: 'Hapus Layanan?',
                text: "Layanan logistik ini akan dihapus permanen dari daftar toko Anda.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' } // Border radius konsisten
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

</script>
@endpush
