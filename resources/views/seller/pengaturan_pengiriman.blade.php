@extends('layouts.seller')

@section('title', 'Pengaturan Pengiriman')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/seller_style.css') }}">
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-truck-delivery"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Pengaturan Pengiriman</span>
        </div>
    </h3>
</div>

<div class="card shadow-sm border-0" style="border-radius: 16px; background: transparent;">
    <div class="card-body p-0">
        
        <div class="bg-white p-4 rounded mb-4" style="border: 1px solid #e5e7eb;">
            <h4 class="card-title fw-bold" style="color: #111827;">Pengaturan Jasa Kirim Toko Anda</h4>
            <p class="text-muted mb-4">Atur jasa kirim yang akan ditampilkan kepada pembeli. Aktifkan toggle untuk membuat layanan tersedia saat checkout.</p>
            
            @foreach($tipeOrder as $tipeKey => $tipeLabel)
                @php $kurirList = $groupedKurir[$tipeKey] ?? []; @endphp
                
                <div class="shipping-category">
                    {{-- Accordion Header --}}
                    <div class="category-title" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $tipeKey }}" aria-expanded="true">
                        {{ $tipeLabel }} <i class="mdi mdi-chevron-down toggle-arrow"></i>
                    </div>
                    
                    {{-- Accordion Body --}}
                    <div class="collapse show" id="collapse-{{ $tipeKey }}"> 
                        <div class="category-content">
                            @if(!empty($kurirList))
                                @foreach($kurirList as $kurir)
                                    <div class="courier-item">
                                        <div class="courier-info">
                                            <span class="courier-name">{{ $kurir->nama_kurir }}</span>
                                            <p class="courier-desc">{{ $kurir->estimasi_waktu }} | Rp {{ number_format($kurir->biaya, 0, ',', '.') }}</p>
                                        </div>
                                        
                                        <div class="courier-actions">
                                            {{-- Toggle Active Status --}}
                                            <label class="toggle-switch">
                                                <input type="checkbox" class="toggle-input" data-id="{{ $kurir->id }}" {{ $kurir->is_active ? 'checked' : '' }}>
                                                <span class="toggle-slider"></span>
                                            </label>
                                            
                                            {{-- Dropdown Action --}}
                                            <div class="dropdown dropdown-action">
                                                <button class="btn btn-icon dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Atur <i class="mdi mdi-chevron-down"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <a class="dropdown-item btn-edit-courier" href="#" 
                                                           data-id="{{ $kurir->id }}"
                                                           data-nama="{{ $kurir->nama_kurir }}"
                                                           data-tipe="{{ $kurir->tipe_kurir }}"
                                                           data-waktu="{{ $kurir->estimasi_waktu }}"
                                                           data-biaya="{{ $kurir->biaya }}"
                                                           data-aktif="{{ $kurir->is_active }}">
                                                           <i class="mdi mdi-pencil me-2"></i> Edit Detail
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <form action="{{ route('seller.pengaturan.pengiriman.destroy', $kurir->id) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                                                <i class="mdi mdi-trash-can-outline me-2"></i> Hapus Layanan
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="mdi mdi-truck-fast"></i>
                                    <p class="mb-0">Belum ada layanan pengiriman untuk kategori ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="mt-4 pt-3" style="border-top: 1px solid #e5e7eb;">
                <button class="btn-mono" id="addCourierBtn">
                    <i class="mdi mdi-plus me-1"></i> Tambah Opsi Pengiriman Baru
                </button>
            </div>
            
        </div>
    </div>
</div>

{{-- MODAL TAMBAH/EDIT KURIR --}}
<div class="modal fade" id="kurirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <form action="{{ route('seller.pengaturan.pengiriman.store') }}" method="POST">
                @csrf
                <div class="modal-header" style="background: #f9fafb; border-bottom: 1px solid #e5e7eb; border-radius: 12px 12px 0 0;">
                    <h5 class="modal-title fw-bold" id="modalTitle" style="color: #111827;">Tambah Opsi Pengiriman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <input type="hidden" name="action" id="form-action" value="tambah">
                    <input type="hidden" name="kurir_id" id="kurir_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Nama Layanan</label>
                        <input type="text" name="nama_kurir" id="nama_kurir" class="form-control" placeholder="Contoh: JNE REG / Kurir Pribadi" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Tipe Kategori</label>
                        <select name="tipe_kurir" id="tipe_kurir" class="form-select">
                            @foreach ($tipeOrder as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Estimasi Waktu</label>
                        <input type="text" name="estimasi_waktu" id="estimasi_waktu" class="form-control" placeholder="Contoh: 1-2 Hari / 2 Jam" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Biaya Flat (Rp)</label>
                        <input type="number" name="biaya" id="biaya" class="form-control" placeholder="Isi 0 jika gratis ongkir" required min="0">
                    </div>
                    
                    <div class="form-check form-switch d-flex align-items-center gap-2">
                        <input class="form-check-input mt-0" type="checkbox" name="is_active" id="is_active_modal" value="1" checked style="width: 40px; height: 20px; cursor: pointer;">
                        <label class="form-check-label fw-bold" for="is_active_modal" style="cursor: pointer; color: #111827;">Langsung Aktifkan Layanan</label>
                    </div>
                </div>
                
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb;">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-mono">Simpan Pengiriman</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. MODAL LOGIC (TAMBAH & EDIT) ---
    const kurirModal = new bootstrap.Modal(document.getElementById('kurirModal'));
    
    // Tombol Tambah
    document.getElementById('addCourierBtn').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Tambah Opsi Pengiriman';
        document.getElementById('form-action').value = 'tambah';
        document.getElementById('kurir_id').value = '';
        document.querySelector('#kurirModal form').reset();
        document.getElementById('is_active_modal').checked = true;
        kurirModal.show();
    });

    // Tombol Edit (Ambil data dari data-attributes, tidak perlu fetch AJAX lagi)
    document.querySelectorAll('.btn-edit-courier').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            
            document.getElementById('modalTitle').textContent = 'Edit Opsi Pengiriman';
            document.getElementById('form-action').value = 'update';
            
            // Isi form dengan data dari atribut
            document.getElementById('kurir_id').value = this.dataset.id;
            document.getElementById('nama_kurir').value = this.dataset.nama;
            document.getElementById('tipe_kurir').value = this.dataset.tipe;
            document.getElementById('estimasi_waktu').value = this.dataset.waktu;
            document.getElementById('biaya').value = this.dataset.biaya;
            document.getElementById('is_active_modal').checked = this.dataset.aktif == 1;
            
            kurirModal.show();
        });
    });

    // --- 2. TOGGLE AJAX UPDATE ---
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
                if(data.status !== 'success') {
                    throw new Error('Update failed');
                }
                // Optional: Tampilkan sweetalert toast sukses di sini
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memperbarui status!');
                checkbox.checked = !isActive; // Kembalikan ke posisi semula
            });
        });
    });
});
</script>
@endpush