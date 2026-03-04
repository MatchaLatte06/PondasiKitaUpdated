@extends('layouts.seller')

@section('title', 'Manajemen Pengiriman')

@section('content')
<style>
    /* CSS ISOLATED & BULLETPROOF */
    :root {
        --ship-dark: #0f172a;
        --ship-primary: #2563eb;
        --ship-success: #10b981;
        --ship-danger: #ef4444;
        --ship-border: #e2e8f0;
        --ship-bg: #f8fafc;
    }

    .ship-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }
    
    /* Header Halaman */
    .ship-header-box { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .ship-icon-box { background: var(--ship-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .ship-title { font-size: 1.5rem; font-weight: 800; color: var(--ship-dark); margin: 0; }
    
    /* B2B Edu Box */
    .b2b-edu-box { background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); border-radius: 16px; padding: 24px; color: white; margin-bottom: 30px; display: flex; align-items: center; gap: 20px; box-shadow: 0 10px 15px -3px rgba(30, 58, 138, 0.3); }
    .b2b-edu-icon { background: rgba(255,255,255,0.15); padding: 15px; border-radius: 12px; font-size: 2rem; }
    .b2b-edu-text h5 { font-weight: 800; margin-bottom: 6px; font-size: 1.1rem; }
    .b2b-edu-text p { margin: 0; font-size: 0.9rem; opacity: 0.9; line-height: 1.5; }

    /* Kategori Pengiriman Card */
    .ship-category-card { background: white; border-radius: 16px; border: 1px solid var(--ship-border); margin-bottom: 24px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .ship-category-header { background: var(--ship-bg); padding: 16px 24px; border-bottom: 1px solid var(--ship-border); display: flex; align-items: center; gap: 12px; }
    .ship-category-header h5 { margin: 0; font-weight: 800; font-size: 1.1rem; color: var(--ship-dark); }
    
    .ship-list { display: flex; flex-direction: column; }
    .ship-item { padding: 20px 24px; border-bottom: 1px dashed var(--ship-border); display: flex; justify-content: space-between; align-items: center; gap: 15px; transition: 0.2s; }
    .ship-item:hover { background: #fdfdfd; }
    .ship-item:last-child { border-bottom: none; }

    .ship-item-info { flex-grow: 1; }
    .ship-item-name { font-size: 16px; font-weight: 800; color: var(--ship-dark); margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
    .ship-item-desc { font-size: 13px; color: #64748b; margin: 0; font-weight: 500; }
    .ship-price-badge { background: #eff6ff; color: var(--ship-primary); padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-block; margin-top: 8px; }

    /* Custom iOS Toggle Switch */
    .ios-switch { position: relative; display: inline-block; width: 50px; height: 26px; flex-shrink: 0; }
    .ios-switch input { opacity: 0; width: 0; height: 0; }
    .ios-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 34px; }
    .ios-slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.2); }
    .ios-switch input:checked + .ios-slider { background-color: var(--ship-success); }
    .ios-switch input:checked + .ios-slider:before { transform: translateX(24px); }

    /* Aksi Tombol */
    .ship-actions { display: flex; align-items: center; gap: 16px; }
    .btn-act { background: transparent; border: none; color: #64748b; font-size: 1.2rem; cursor: pointer; transition: 0.2s; padding: 5px; }
    .btn-act.edit:hover { color: var(--ship-primary); }
    .btn-act.delete:hover { color: var(--ship-danger); }

    /* Modal Styling */
    .modal-content-custom { border-radius: 16px; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); }
    .modal-header-custom { background: var(--ship-bg); border-bottom: 1px solid var(--ship-border); border-radius: 16px 16px 0 0; padding: 20px 24px; }
    .form-label-custom { font-weight: 700; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .form-control-custom { border-radius: 10px; border: 2px solid var(--ship-border); padding: 12px 16px; font-weight: 500; font-size: 14px; transition: 0.2s; }
    .form-control-custom:focus { border-color: var(--ship-primary); box-shadow: none; outline: none; }
</style>

<div class="ship-wrapper">
    
    {{-- Notifikasi SweetAlert --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({toast: true, position: 'top-end', icon: 'success', title: '{{ session('success') }}', showConfirmButton: false, timer: 3000}));</script>
    @endif

    {{-- HEADER --}}
    <div class="ship-header-box d-flex justify-content-between align-items-center flex-wrap">
        <div class="d-flex align-items-center gap-3">
            <div class="ship-icon-box"><i class="mdi mdi-truck-delivery"></i></div>
            <div>
                <h3 class="ship-title">Pengaturan Logistik</h3>
                <p class="text-muted m-0" style="font-size: 0.9rem;">Atur armada toko dan kurir ekspedisi untuk pengiriman material Anda.</p>
            </div>
        </div>
        <button class="btn btn-primary fw-bold px-4 py-2" style="border-radius: 10px; background: #0f172a; border: none;" id="addCourierBtn">
            <i class="mdi mdi-plus-circle-outline me-1"></i> Tambah Layanan
        </button>
    </div>

    {{-- B2B EDUCATIONAL BANNER --}}
    <div class="b2b-edu-box">
        <div class="b2b-edu-icon"><i class="mdi mdi-dump-truck"></i></div>
        <div class="b2b-edu-text">
            <h5>Pentingnya "Armada Toko" di Bisnis Bangunan</h5>
            <p>Pembeli grosir material berat (Semen, Pasir, Besi Beton) tidak dapat dikirim via kurir motor. Pastikan Anda <b>menambahkan dan mengaktifkan Armada Toko</b> agar pembeli bisa checkout material berat dengan biaya flat (per rit/truk) sesuai zona toko Anda.</p>
        </div>
    </div>

    {{-- LOOPING KATEGORI (TOKO & PIHAK_KETIGA) --}}
    @foreach($tipeOrder as $tipeKey => $tipeLabel)
        @php 
            $kurirList = $groupedKurir[$tipeKey] ?? []; 
            $icon = $tipeKey == 'TOKO' ? 'mdi-truck-flatbed text-warning' : 'mdi-package-variant text-primary';
        @endphp
        
        <div class="ship-category-card">
            <div class="ship-category-header">
                <i class="mdi {{ $icon }} fs-4"></i>
                <h5>{{ $tipeLabel }}</h5>
            </div>
            
            <div class="ship-list">
                @if(count($kurirList) > 0)
                    @foreach($kurirList as $kurir)
                        <div class="ship-item">
                            <div class="ship-item-info">
                                <div class="ship-item-name">
                                    {{ $kurir->nama_kurir }}
                                    @if($kurir->is_active)
                                        <i class="mdi mdi-check-decagram text-success fs-6" title="Aktif"></i>
                                    @endif
                                </div>
                                <p class="ship-item-desc"><i class="mdi mdi-clock-outline"></i> Estimasi: {{ $kurir->estimasi_waktu }}</p>
                                <div class="ship-price-badge">Tarif: Rp {{ number_format($kurir->biaya, 0, ',', '.') }}</div>
                            </div>
                            
                            <div class="ship-actions">
                                {{-- Edit Button --}}
                                <button class="btn-act edit btn-edit-courier" title="Edit Layanan"
                                        data-id="{{ $kurir->id }}"
                                        data-nama="{{ $kurir->nama_kurir }}"
                                        data-tipe="{{ $kurir->tipe_kurir }}"
                                        data-waktu="{{ $kurir->estimasi_waktu }}"
                                        data-biaya="{{ $kurir->biaya }}"
                                        data-aktif="{{ $kurir->is_active }}">
                                    <i class="mdi mdi-pencil-box-outline"></i>
                                </button>
                                
                                {{-- Delete Form --}}
                                <form action="{{ route('seller.pengaturan.pengiriman.destroy', $kurir->id) }}" method="POST" class="m-0 d-inline delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-act delete btn-delete-confirm" title="Hapus Layanan"><i class="mdi mdi-trash-can-outline"></i></button>
                                </form>

                                {{-- Toggle Active Switch --}}
                                <div style="border-left: 2px solid #e2e8f0; height: 30px; margin: 0 10px;"></div>
                                <label class="ios-switch" title="Aktifkan/Nonaktifkan">
                                    <input type="checkbox" class="toggle-input" data-id="{{ $kurir->id }}" {{ $kurir->is_active ? 'checked' : '' }}>
                                    <span class="ios-slider"></span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="mdi mdi-inbox-remove-outline" style="font-size: 3rem; color: #cbd5e1;"></i>
                        <p class="text-muted fw-bold mt-2 mb-0">Belum ada pengaturan layanan untuk kategori ini.</p>
                    </div>
                @endif
            </div>
        </div>
    @endforeach

</div>

{{-- MODAL TAMBAH/EDIT KURIR --}}
<div class="modal fade" id="kurirModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <form action="{{ route('seller.pengaturan.pengiriman.store') }}" method="POST">
                @csrf
                <div class="modal-header modal-header-custom">
                    <h5 class="modal-title fw-bold" id="modalTitle" style="color: #0f172a;">Form Layanan Logistik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    <input type="hidden" name="action" id="form-action" value="tambah">
                    <input type="hidden" name="kurir_id" id="kurir_id">
                    
                    <div class="mb-4">
                        <label class="form-label-custom">Tipe Kategori Ekspedisi</label>
                        <select name="tipe_kurir" id="tipe_kurir" class="form-select form-control-custom">
                            @foreach ($tipeOrder as $key => $label)
                                <option value="{{ $key }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-custom">Nama Layanan / Kurir</label>
                        <input type="text" name="nama_kurir" id="nama_kurir" class="form-control form-control-custom" placeholder="Contoh: Pickup L300 / JNE Kargo" required>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label-custom">Estimasi Tiba</label>
                            <input type="text" name="estimasi_waktu" id="estimasi_waktu" class="form-control form-control-custom" placeholder="Contoh: 1 Hari / 3 Jam" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Biaya Flat (Rp)</label>
                            <input type="number" name="biaya" id="biaya" class="form-control form-control-custom" placeholder="Isi 0 jika Gratis" required min="0">
                        </div>
                    </div>
                    
                    <div class="bg-light p-3 border rounded-3 d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block text-dark" style="font-size: 14px;">Langsung Aktifkan</strong>
                            <span class="text-muted" style="font-size: 12px;">Pembeli dapat langsung melihat opsi ini saat checkout.</span>
                        </div>
                        <label class="ios-switch">
                            <input type="checkbox" name="is_active" id="is_active_modal" value="1" checked>
                            <span class="ios-slider"></span>
                        </label>
                    </div>
                </div>
                
                <div class="modal-footer p-4 border-0 bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-outline-secondary fw-bold border-2" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4" style="background: #2563eb; border:none;">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. MODAL LOGIC ---
    const kurirModal = new bootstrap.Modal(document.getElementById('kurirModal'));
    
    document.getElementById('addCourierBtn').addEventListener('click', function() {
        document.getElementById('modalTitle').textContent = 'Tambah Armada / Ekspedisi Baru';
        document.getElementById('form-action').value = 'tambah';
        document.getElementById('kurir_id').value = '';
        document.querySelector('#kurirModal form').reset();
        document.getElementById('is_active_modal').checked = true;
        kurirModal.show();
    });

    document.querySelectorAll('.btn-edit-courier').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('modalTitle').textContent = 'Edit Rincian Layanan';
            document.getElementById('form-action').value = 'update';
            document.getElementById('kurir_id').value = this.dataset.id;
            document.getElementById('nama_kurir').value = this.dataset.nama;
            document.getElementById('tipe_kurir').value = this.dataset.tipe;
            document.getElementById('estimasi_waktu').value = this.dataset.waktu;
            document.getElementById('biaya').value = this.dataset.biaya;
            document.getElementById('is_active_modal').checked = this.dataset.aktif == 1;
            kurirModal.show();
        });
    });

    // --- 2. AJAX TOGGLE STATUS (Biar tidak perlu reload page) ---
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
                    showConfirmButton: false, timer: 1500
                });
            })
            .catch(error => {
                Swal.fire({toast: true, position: 'top-end', icon: 'error', title: 'Koneksi gagal!', showConfirmButton: false, timer: 2000});
                checkbox.checked = !isActive; // Kembalikan ke posisi awal jika gagal
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
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

});
</script>
@endpush