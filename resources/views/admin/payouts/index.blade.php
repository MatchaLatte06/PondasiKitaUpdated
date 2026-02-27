@extends('layouts.admin')

@section('title', 'Pusat Keuangan & Payout')

@push('styles')
<style>
    :root {
        --bank-bg: #f8fafc;
        --bank-border: #e2e8f0;
    }
    
    /* FINANCIAL STATS CARDS */
    .finance-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px; }
    .fin-card { background: white; border-radius: 16px; padding: 24px; border: 1px solid var(--bank-border); box-shadow: 0 2px 4px rgba(0,0,0,0.02); display: flex; flex-direction: column; justify-content: center; }
    .fin-card.highlight { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); color: white; border: none; }
    .fin-card.highlight .text-muted { color: #e0e7ff !important; }
    .fin-label { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
    .fin-value { font-size: 28px; font-weight: 800; margin: 0; }

    /* TABLE UI */
    .table-card { background: white; border-radius: 20px; border: 1px solid var(--bank-border); overflow: hidden; }
    .table-modern { width: 100%; border-collapse: collapse; }
    .table-modern thead th { background: #f8fafc; color: #64748b; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 16px 20px; border-bottom: 1px solid var(--bank-border); }
    .table-modern tbody td { padding: 16px 20px; vertical-align: middle; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
    .table-modern tbody tr:hover { background: #fcfcfc; }

    /* BANK DETAILS CHIP */
    .bank-box { background: var(--bank-bg); border: 1px solid var(--bank-border); border-radius: 10px; padding: 10px 14px; display: inline-flex; flex-direction: column; min-width: 200px; }
    .bank-name { font-size: 12px; font-weight: 800; color: #4f46e5; text-transform: uppercase; margin-bottom: 2px; }
    .bank-acc { font-family: monospace; font-size: 16px; font-weight: 700; color: #1e293b; letter-spacing: 1px; }
    .bank-owner { font-size: 11px; color: #64748b; font-weight: 600; margin-top: 4px; }

    /* BADGES */
    .badge-status { padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; }
    .bg-pending { background: #fef3c7; color: #d97706; }
    .bg-completed { background: #dcfce7; color: #15803d; }
    .bg-rejected { background: #fee2e2; color: #b91c1c; }

    /* MODAL EXTRAS */
    .transfer-warning-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 16px; margin-top: 20px; }
    .modal-amount { font-size: 36px; font-weight: 800; color: #4f46e5; text-align: center; margin: 15px 0; font-family: monospace; }
</style>
@endpush

@section('content')
<div class="dashboard-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-dark mb-1">Pusat Pencairan Dana (Payout)</h2>
        <p class="text-muted small">Kelola penarikan saldo penghasilan dari Mitra Toko.</p>
    </div>
</div>

{{-- 1. FINANCIAL METRICS --}}
<div class="finance-grid">
    <div class="fin-card highlight shadow-sm">
        <span class="fin-label text-muted">Perlu Ditransfer (Pending)</span>
        <h3 class="fin-value">Rp {{ number_format($stats['total_pending_amount'], 0, ',', '.') }}</h3>
        <span class="small mt-2" style="color: #c7d2fe;">Dari {{ $stats['total_pending_count'] }} permintaan aktif</span>
    </div>
    <div class="fin-card">
        <span class="fin-label text-muted">Sukses Dibayar (Bulan Ini)</span>
        <h3 class="fin-value text-success">Rp {{ number_format($stats['total_completed_amount'], 0, ',', '.') }}</h3>
    </div>
    <div class="fin-card">
        <span class="fin-label text-muted">Permintaan Ditolak</span>
        <h3 class="fin-value text-danger">{{ number_format($stats['total_rejected']) }} <span class="fs-6 fw-normal text-muted">Kasus</span></h3>
    </div>
</div>

{{-- 2. MAIN TABLE --}}
<div class="table-card shadow-sm">
    <div class="d-flex flex-column flex-md-row justify-content-between p-4 border-bottom bg-white gap-3">
        <div class="btn-group p-1 bg-light rounded-3">
            @foreach(['pending' => 'Perlu Diproses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $val => $label)
                <a href="{{ route('admin.payouts.index', ['status' => $val, 'search' => $search]) }}" 
                   class="btn btn-sm border-0 {{ $status_filter == $val ? 'bg-white shadow-sm fw-bold text-primary' : 'text-muted' }} px-4">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('admin.payouts.index') }}" method="GET">
            <input type="hidden" name="status" value="{{ $status_filter }}">
            <div class="input-group">
                <span class="input-group-text bg-white"><i class="mdi mdi-magnify"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Cari nama toko / ID Payout..." value="{{ $search }}">
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table-modern">
            <thead>
                <tr>
                    <th>ID & Waktu Request</th>
                    <th>Toko (Mitra)</th>
                    <th>Informasi Rekening Tujuan</th>
                    <th>Jumlah Penarikan</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $p)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">#PAY-{{ str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</div>
                        <div class="text-muted small mt-1">{{ \Carbon\Carbon::parse($p->tanggal_request)->format('d M Y, H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $p->nama_toko }}</div>
                        <div class="text-muted small"><i class="mdi mdi-email-outline"></i> {{ $p->email_pemilik }}</div>
                    </td>
                    <td>
                        <div class="bank-box shadow-sm">
                            <span class="bank-name">{{ $p->rekening_bank ?? 'N/A' }}</span>
                            <span class="bank-acc">{{ $p->nomor_rekening ?? 'Belum diisi' }}</span>
                            <span class="bank-owner">a.n {{ $p->atas_nama_rekening ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="fw-bold text-primary fs-5">Rp {{ number_format($p->jumlah_payout, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span class="badge-status bg-{{ $p->status }}">
                            <i class="mdi {{ $p->status == 'pending' ? 'mdi-clock-outline' : ($p->status == 'completed' ? 'mdi-check-circle' : 'mdi-close-circle') }}"></i> 
                            {{ strtoupper($p->status) }}
                        </span>
                        @if($p->tanggal_proses)
                            <div class="text-muted" style="font-size: 10px; margin-top: 4px;">Tgl Proses: <br>{{ \Carbon\Carbon::parse($p->tanggal_proses)->format('d/m/y H:i') }}</div>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($p->status == 'pending')
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-success btn-sm fw-bold px-3 shadow-sm btn-proses" 
                                    data-bs-toggle="modal" data-bs-target="#prosesModal"
                                    data-id="{{ $p->id }}" 
                                    data-toko="{{ $p->nama_toko }}" 
                                    data-jumlah="{{ number_format($p->jumlah_payout, 0, ',', '.') }}"
                                    data-bank="{{ $p->rekening_bank }}"
                                    data-rekening="{{ $p->nomor_rekening }}"
                                    data-owner="{{ $p->atas_nama_rekening }}">
                                    Proses Bayar
                                </button>
                                <button type="button" class="btn btn-outline-danger btn-sm px-3 btn-tolak"
                                    data-bs-toggle="modal" data-bs-target="#tolakModal"
                                    data-id="{{ $p->id }}">
                                    Tolak
                                </button>
                            </div>
                        @else
                            <button class="btn btn-light btn-sm border text-muted" disabled>Terkunci</button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="mdi mdi-cash-register text-muted" style="font-size: 4rem;"></i>
                        <p class="text-muted fw-bold mt-2">Tidak ada data penarikan untuk tab ini.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="p-3 bg-light border-top">
        {{ $payouts->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- MODAL PROSES TRANSFER --}}
<div class="modal fade" id="prosesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <form id="formProses" method="POST" action="">
                @csrf
                <input type="hidden" name="action" value="approve">
                <div class="modal-header border-bottom bg-light rounded-top-4 p-4">
                    <h5 class="modal-title fw-bold text-dark"><i class="mdi mdi-bank-transfer text-primary me-2"></i> Konfirmasi Transfer Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center text-muted small fw-bold text-uppercase">Jumlah yang harus ditransfer</div>
                    <div class="modal-amount">Rp <span id="mdl-jumlah">0</span></div>
                    
                    <div class="bank-box w-100 text-center py-3 my-3 bg-white border shadow-sm">
                        <span class="bank-name fs-6" id="mdl-bank">BANK</span>
                        <span class="bank-acc fs-4" id="mdl-rekening">000-000-000</span>
                        <span class="bank-owner fs-6">a.n <span id="mdl-owner">Nama Pemilik</span></span>
                    </div>

                    <div class="transfer-warning-box">
                        <div class="d-flex gap-2">
                            <i class="mdi mdi-alert text-warning fs-4"></i>
                            <div class="small text-dark">
                                <strong>Peringatan Penting!</strong> Sistem ini tidak memotong uang secara otomatis. Anda <b>wajib</b> mentransfer uang secara manual melalui m-Banking / Internet Banking ke rekening di atas sebelum menekan tombol "Sudah Ditransfer".
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top p-4">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4 fw-bold">Ya, Uang Sudah Ditransfer</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL TOLAK PAYOUT --}}
<div class="modal fade" id="tolakModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <form id="formTolak" method="POST" action="">
                @csrf
                <input type="hidden" name="action" value="reject">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="fw-bold text-danger">Tolak Penarikan Dana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <label class="form-label fw-bold">Alasan Penolakan</label>
                    <textarea name="catatan_admin" class="form-control rounded-3" rows="4" required placeholder="Contoh: Nomor rekening tidak valid atau ada transaksi mencurigakan..."></textarea>
                    <small class="text-muted mt-2 d-block">Dana akan dikembalikan ke dompet toko setelah penolakan (sesuai logika backend Anda).</small>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4">Tolak Payout</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Trigger Modal Proses Bayar
        document.querySelectorAll('.btn-proses').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Masukkan data ke dalam UI Modal
                document.getElementById('mdl-jumlah').innerText = this.getAttribute('data-jumlah');
                document.getElementById('mdl-bank').innerText = this.getAttribute('data-bank');
                document.getElementById('mdl-rekening').innerText = this.getAttribute('data-rekening');
                document.getElementById('mdl-owner').innerText = this.getAttribute('data-owner');
                
                // Set form action dinamis
                document.getElementById('formProses').action = `/portal-rahasia-pks/payouts/${id}/process`;
            });
        });

        // Handle Trigger Modal Tolak
        document.querySelectorAll('.btn-tolak').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                // Set form action dinamis
                document.getElementById('formTolak').action = `/portal-rahasia-pks/payouts/${id}/process`;
            });
        });
    });
</script>
@endpush