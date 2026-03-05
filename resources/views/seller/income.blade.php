@extends('layouts.seller')

@section('title', 'Dompet & Penghasilan')

@section('content')
<style>
    /* === CSS ISOLATED UNTUK DOMPET FINANCE === */
    :root {
        --inc-dark: #0f172a;
        --inc-primary: #2563eb;
        --inc-success: #10b981;
        --inc-warning: #f59e0b;
        --inc-border: #e2e8f0;
        --inc-bg: #f8fafc;
        --text-mut: #64748b;
    }
    .inc-wrapper { font-family: 'Inter', sans-serif; color: #1e293b; }

    /* Header */
    .inc-header-box { display: flex; align-items: center; gap: 16px; margin-bottom: 24px; }
    .inc-icon { background: var(--inc-dark); color: white; width: 48px; height: 48px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    
    /* Layout Utama */
    .inc-layout { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }
    @media (max-width: 992px) { .inc-layout { grid-template-columns: 1fr; } }

    /* Kartu Saldo */
    .wallet-card { background: white; border-radius: 16px; border: 1px solid var(--inc-border); padding: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); margin-bottom: 24px; }
    .wallet-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed var(--inc-border); padding-bottom: 16px; margin-bottom: 20px; }
    
    .balance-box { display: flex; flex-direction: column; gap: 4px; }
    .balance-label { font-size: 12px; font-weight: 700; color: var(--text-mut); text-transform: uppercase; letter-spacing: 0.5px; }
    .balance-main { font-size: 2.2rem; font-weight: 900; color: var(--inc-dark); letter-spacing: -1px; }
    .balance-pending { font-size: 1.2rem; font-weight: 800; color: var(--inc-warning); }

    .btn-withdraw { background: var(--inc-dark); color: white; border: none; padding: 12px 24px; border-radius: 10px; font-weight: 700; font-size: 14px; transition: 0.2s; box-shadow: 0 4px 6px rgba(15,23,42,0.2); }
    .btn-withdraw:hover { background: #1e293b; transform: translateY(-2px); }

    .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .stat-box { background: var(--inc-bg); border: 1px solid var(--inc-border); padding: 16px; border-radius: 10px; }
    .stat-val { font-size: 1.2rem; font-weight: 800; color: var(--inc-success); margin-top: 5px; }

    /* Table Area */
    .tx-card { background: white; border-radius: 16px; border: 1px solid var(--inc-border); overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .tx-toolbar { background: var(--inc-bg); padding: 16px 24px; border-bottom: 1px solid var(--inc-border); display: flex; flex-direction: column; gap: 15px; }
    
    .tx-tabs { display: flex; gap: 10px; border-bottom: 2px solid var(--inc-border); padding-bottom: 0; }
    .tx-tab { padding: 10px 20px; font-weight: 700; font-size: 14px; color: var(--text-mut); text-decoration: none; border-bottom: 3px solid transparent; transition: 0.2s; }
    .tx-tab:hover { color: var(--inc-dark); }
    .tx-tab.active { color: var(--inc-primary); border-bottom-color: var(--inc-primary); }

    .filter-row { display: flex; gap: 12px; flex-wrap: wrap; }
    .inc-input { border: 1px solid var(--inc-border); padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 500; outline: none; transition: 0.2s; }
    .inc-input:focus { border-color: var(--inc-primary); box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
    
    .tx-table { width: 100%; border-collapse: collapse; }
    .tx-table th { background: white; color: #334155; font-size: 11px; font-weight: 800; text-transform: uppercase; padding: 16px 24px; border-bottom: 2px solid var(--inc-border); text-align: left; }
    .tx-table td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }
    .tx-table tr:hover td { background-color: #f8fafc; }

    .badge-tx { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
    .bg-pending { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .bg-released { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }

    /* Right Widget */
    .widget-card { background: white; border-radius: 16px; border: 1px solid var(--inc-border); padding: 20px; margin-bottom: 24px; }
    .widget-title { font-size: 14px; font-weight: 800; color: var(--inc-dark); margin-bottom: 16px; display: flex; align-items: center; gap: 8px; }
    
    .history-list { display: flex; flex-direction: column; }
    .history-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px dashed var(--inc-border); }
    .history-item:last-child { border-bottom: none; }
    .h-amount { font-weight: 800; color: var(--inc-dark); font-size: 14px; }
    .h-date { font-size: 12px; color: var(--text-mut); font-weight: 500; }
    
    .badge-wd { font-size: 10px; padding: 3px 8px; border-radius: 4px; font-weight: 700; text-transform: uppercase; }
    .wd-pending { background: #fef2f2; color: #ef4444; }
    .wd-completed { background: #eff6ff; color: #2563eb; }

    /* Empty State */
    .empty-box { text-align: center; padding: 40px 20px; }
    .empty-icon { font-size: 3rem; color: #cbd5e1; margin-bottom: 10px; }
</style>

<div class="inc-wrapper">
    
    {{-- Notifikasi --}}
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({title: 'Berhasil!', text: '{{ session('success') }}', icon: 'success', confirmButtonColor: '#0f172a'}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire('Gagal!', '{{ session('error') }}', 'error'));</script>
    @endif

    {{-- HEADER --}}
    <div class="inc-header-box">
        <div class="inc-icon"><i class="mdi mdi-wallet-outline"></i></div>
        <div>
            <h3 class="m-0 fw-bold fs-4">Dompet & Penghasilan</h3>
            <p class="m-0 text-muted" style="font-size: 13px;">Pantau pendapatan dari pesanan yang selesai dan tarik saldo ke rekening Anda.</p>
        </div>
    </div>

    <div class="inc-layout">
        
        {{-- KOLOM KIRI (KARTU UTAMA & TABEL) --}}
        <div>
            {{-- DOMPET SALDO --}}
            <div class="wallet-card">
                <div class="wallet-header">
                    <div class="balance-box">
                        <span class="balance-label"><i class="mdi mdi-check-decagram text-success fs-6"></i> Saldo Aktif (Bisa Ditarik)</span>
                        <div class="balance-main">Rp {{ number_format($saldo_aktif, 0, ',', '.') }}</div>
                    </div>
                    <button type="button" class="btn-withdraw" data-bs-toggle="modal" data-bs-target="#payoutModal">
                        <i class="mdi mdi-bank-transfer-out"></i> Tarik Saldo
                    </button>
                </div>

                <div class="stats-grid">
                    <div class="stat-box" style="border-color: #fde68a; background: #fffbeb;">
                        <span class="balance-label text-warning"><i class="mdi mdi-timer-sand"></i> Dana Tertahan (Pending)</span>
                        <div class="balance-pending">Rp {{ number_format($penghasilan_pending, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size: 11px; margin-top:4px;">Dana cair setelah pembeli klik selesai.</div>
                    </div>
                    <div class="stat-box">
                        <span class="balance-label">Total Omzet Bulan Ini</span>
                        <div class="stat-val">Rp {{ number_format($dilepas_bulan_ini, 0, ',', '.') }}</div>
                        <div class="text-muted" style="font-size: 11px; margin-top:4px;">Omzet kotor dari pesanan selesai.</div>
                    </div>
                </div>
            </div>

            {{-- TABEL TRANSAKSI --}}
            <div class="tx-card">
                <div class="tx-toolbar">
                    <div class="tx-tabs">
                        <a href="?tab=dilepas" class="tx-tab {{ $tab == 'dilepas' ? 'active' : '' }}">Dana Masuk (Selesai)</a>
                        <a href="?tab=pending" class="tx-tab {{ $tab == 'pending' ? 'active' : '' }}">Dana Tertahan</a>
                    </div>
                    
                    <form action="{{ route('seller.finance.income') }}" method="GET" class="filter-row m-0">
                        <input type="hidden" name="tab" value="{{ $tab }}">
                        <input type="date" name="date" class="inc-input" value="{{ request('date') }}" onchange="this.form.submit()">
                        <input type="text" name="search" class="inc-input flex-grow-1" placeholder="Cari No. Invoice..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-dark fw-bold px-3 rounded-3" style="background:#0f172a;"><i class="mdi mdi-magnify"></i></button>
                        @if(request('search') || request('date'))
                            <a href="{{ route('seller.finance.income', ['tab' => $tab]) }}" class="btn btn-light border fw-bold px-3 rounded-3">Reset</a>
                        @endif
                    </form>
                </div>

                <div style="overflow-x: auto;">
                    <table class="tx-table">
                        <thead>
                            <tr>
                                <th>No. Invoice</th>
                                <th>Tanggal</th>
                                <th>Pembayaran</th>
                                <th>Status Dana</th>
                                <th class="text-end">Nominal Masuk</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaksi_list as $tx)
                                <tr>
                                    <td><strong style="font-family: monospace;">{{ $tx->kode_invoice }}</strong></td>
                                    <td class="text-muted" style="font-size: 13px;">{{ \Carbon\Carbon::parse($tx->tanggal_transaksi)->format('d M Y, H:i') }}</td>
                                    <td><span class="badge bg-light text-dark border">{{ $tx->metode_pembayaran ?? 'Manual' }}</span></td>
                                    <td>
                                        @if($tab == 'pending')
                                            <span class="badge-tx bg-pending">Tertahan</span>
                                        @else
                                            <span class="badge-tx bg-released">Dilepas</span>
                                        @endif
                                    </td>
                                    <td class="text-end fw-bold text-success">+ Rp {{ number_format($tx->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-box">
                                            <i class="mdi mdi-receipt-text-outline empty-icon"></i>
                                            <h5 class="fw-bold text-dark mb-1">Tidak Ada Transaksi</h5>
                                            <p class="text-muted m-0" style="font-size:13px;">Belum ada dana masuk pada kategori/filter ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transaksi_list->hasPages())
                    <div class="p-3 bg-light border-top d-flex justify-content-center">
                        {{ $transaksi_list->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>

        {{-- KOLOM KANAN (WIDGETS) --}}
        <div>
            
            {{-- Widget Riwayat Payout --}}
            <div class="widget-card">
                <div class="widget-title">
                    <i class="mdi mdi-history text-primary"></i> Riwayat Tarik Saldo
                </div>
                
                @if($riwayat_payout->isEmpty())
                    <div class="text-center py-4 text-muted small fw-bold">Belum ada riwayat penarikan.</div>
                @else
                    <div class="history-list">
                        @foreach($riwayat_payout as $rp)
                            <div class="history-item">
                                <div>
                                    <div class="h-amount">Rp {{ number_format($rp->jumlah_payout, 0, ',', '.') }}</div>
                                    <div class="h-date">{{ \Carbon\Carbon::parse($rp->tanggal_request)->format('d M Y, H:i') }}</div>
                                </div>
                                <div>
                                    @if($rp->status == 'pending')
                                        <span class="badge-wd wd-pending">Diproses Admin</span>
                                    @elseif($rp->status == 'completed')
                                        <span class="badge-wd wd-completed">Berhasil Dikirim</span>
                                    @else
                                        <span class="badge-wd bg-dark text-white">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Widget Info Rekening --}}
            <div class="widget-card" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white;">
                <div class="widget-title text-white border-bottom border-secondary pb-3">
                    <i class="mdi mdi-bank"></i> Rekening Penerima
                </div>
                <div class="mt-3">
                    <p class="small text-light mb-1 opacity-75">Saldo akan ditransfer ke:</p>
                    <h5 class="fw-bold mb-1">BCA - 1234567890</h5>
                    <p class="small m-0 text-info fw-bold">A.N. PRABU ALAM TIAN</p>
                    <a href="{{ route('seller.finance.bank') }}" class="btn btn-sm btn-light text-dark fw-bold mt-3 w-100">Ubah Rekening</a>
                </div>
            </div>

        </div>

    </div>
</div>

{{-- MODAL TARIK SALDO --}}
<div class="modal fade" id="payoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <form action="{{ route('seller.finance.payout') }}" method="POST" id="payoutForm">
                @csrf
                <div class="modal-header bg-light border-bottom" style="border-radius: 16px 16px 0 0; padding: 20px;">
                    <h5 class="modal-title fw-bold text-dark"><i class="mdi mdi-bank-transfer-out text-primary me-2"></i>Tarik Saldo Toko</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="bg-primary text-white p-3 rounded-3 mb-4 d-flex justify-content-between align-items-center">
                        <span class="small fw-bold opacity-75">Saldo Tersedia:</span>
                        <span class="fs-4 fw-bold">Rp {{ number_format($saldo_aktif, 0, ',', '.') }}</span>
                    </div>

                    <label class="form-label fw-bold text-muted small text-uppercase">Nominal Penarikan (Rp)</label>
                    <input type="number" name="jumlah_payout" id="inputPayout" class="form-control form-control-lg fw-bold text-dark" placeholder="Minimal Rp 50.000" min="50000" max="{{ $saldo_aktif }}" required>
                    <small class="text-danger mt-1 d-none fw-bold" id="errorMsg">Nominal melebihi saldo aktif!</small>

                    <div class="mt-4 p-3 bg-light rounded border text-muted small">
                        <i class="mdi mdi-information text-primary"></i> Dana akan ditransfer ke rekening bank utama Anda (BCA) dalam waktu maksimal 1x24 Jam kerja.
                    </div>
                </div>
                <div class="modal-footer p-4 border-top">
                    <button type="button" class="btn btn-light fw-bold px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-dark fw-bold px-4" style="background: #0f172a;" id="btnSubmitPayout">Ajukan Penarikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputPayout = document.getElementById('inputPayout');
    const btnSubmit = document.getElementById('btnSubmitPayout');
    const errorMsg = document.getElementById('errorMsg');
    const maxSaldo = {{ $saldo_aktif }};

    // Validasi Real-time Modal
    inputPayout.addEventListener('input', function() {
        let val = parseInt(this.value) || 0;
        if(val > maxSaldo) {
            errorMsg.classList.remove('d-none');
            btnSubmit.disabled = true;
            this.style.borderColor = '#ef4444';
        } else {
            errorMsg.classList.add('d-none');
            btnSubmit.disabled = false;
            this.style.borderColor = '#cbd5e1';
        }
    });

    // Konfirmasi SweetAlert
    btnSubmit.addEventListener('click', function(e) {
        e.preventDefault();
        let val = parseInt(inputPayout.value) || 0;
        
        if(val < 50000) {
            Swal.fire('Info', 'Minimal penarikan adalah Rp 50.000', 'info');
            return;
        }

        Swal.fire({
            title: 'Konfirmasi Penarikan',
            html: `Anda akan menarik dana sebesar <b>Rp ${new Intl.NumberFormat('id-ID').format(val)}</b>. Lanjutkan?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
            confirmButtonText: 'Ya, Tarik Dana'
        }).then((result) => {
            if(result.isConfirmed) {
                btnSubmit.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Memproses...';
                btnSubmit.disabled = true;
                document.getElementById('payoutForm').submit();
            }
        });
    });
});
</script>
@endpush