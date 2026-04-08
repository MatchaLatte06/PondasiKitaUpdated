@extends('layouts.seller')

@section('title', 'Dompet & Penghasilan')

@push('styles')
<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Mencegah input number menampilkan panah atas/bawah */
    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type="number"] { -moz-appearance: textfield; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 space-y-6">

    {{-- SETUP SWEETALERT TOAST --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
            customClass: { popup: 'rounded-2xl shadow-lg border border-slate-100' }
        });
    </script>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.fire({icon: 'success', title: '{!! session('success') !!}'}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({title: 'Gagal!', text: '{!! session('error') !!}', icon: 'error', customClass: { popup: 'rounded-3xl' }}));</script>
    @endif

    {{-- 1. HEADER --}}
    <div class="flex items-center gap-4 mb-6">
        <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
            <i class="mdi mdi-wallet-outline text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dompet & Penghasilan</h1>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Pantau pendapatan dari pesanan yang selesai dan tarik saldo ke rekening Anda.</p>
        </div>
    </div>

    {{-- 2. MAIN LAYOUT (KIRI 2, KANAN 1) --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- ================================================= --}}
        {{-- SISI KIRI (DOMPET & TABEL)                        --}}
        {{-- ================================================= --}}
        <div class="lg:col-span-8 space-y-8">

            {{-- KARTU SALDO UTAMA --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm relative overflow-hidden">
                {{-- Efek Glow Halus Latar --}}
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-blue-50 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 mb-6 border-b border-slate-100">
                    <div>
                        <span class="flex items-center gap-1.5 text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1">
                            <i class="mdi mdi-check-decagram text-emerald-500 text-base leading-none"></i> Saldo Aktif (Bisa Ditarik)
                        </span>
                        <div class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                            Rp {{ number_format($saldo_aktif, 0, ',', '.') }}
                        </div>
                    </div>

                    <button type="button" onclick="openPayoutModal()" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-slate-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-sm shadow-slate-900/20 transition-all flex-shrink-0">
                        <i class="mdi mdi-bank-transfer-out text-xl leading-none"></i> Tarik Saldo
                    </button>
                </div>

                <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-amber-50 border border-amber-200/60 p-4 rounded-2xl">
                        <span class="flex items-center gap-1.5 text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1">
                            <i class="mdi mdi-timer-sand text-sm leading-none"></i> Dana Tertahan (Pending)
                        </span>
                        <div class="text-xl font-black text-amber-600 mb-1">Rp {{ number_format($penghasilan_pending, 0, ',', '.') }}</div>
                        <p class="text-[10px] font-bold text-amber-600/70">Dana cair setelah pembeli klik pesanan selesai.</p>
                    </div>
                    <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl">
                        <span class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Total Omzet Bulan Ini</span>
                        <div class="text-xl font-black text-emerald-500 mb-1">Rp {{ number_format($dilepas_bulan_ini, 0, ',', '.') }}</div>
                        <p class="text-[10px] font-bold text-slate-400">Omzet kotor dari pesanan yang telah selesai.</p>
                    </div>
                </div>
            </div>

            {{-- TABEL RIWAYAT DANA MASUK --}}
            <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col">

                {{-- Tabs Filter --}}
                <div class="flex gap-6 px-6 pt-4 border-b border-slate-100 overflow-x-auto hide-scrollbar bg-slate-50/50">
                    <a href="?tab=dilepas" class="pb-3 text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab == 'dilepas' ? 'border-blue-600 text-blue-600' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Dana Masuk (Selesai)</a>
                    <a href="?tab=pending" class="pb-3 text-sm font-bold whitespace-nowrap transition-colors border-b-2 {{ $tab == 'pending' ? 'border-amber-500 text-amber-500' : 'border-transparent text-slate-500 hover:text-slate-800' }}">Dana Tertahan</a>
                </div>

                {{-- Form Pencarian & Tanggal --}}
                <div class="p-4 sm:p-6 border-b border-slate-100 bg-white">
                    <form action="{{ route('seller.finance.income') }}" method="GET" class="flex flex-col sm:flex-row gap-3 m-0">
                        <input type="hidden" name="tab" value="{{ $tab }}">

                        <input type="date" name="date" class="w-full sm:w-auto bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all cursor-pointer shadow-sm" value="{{ request('date') }}" onchange="this.form.submit()">

                        <div class="relative w-full flex-1 group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="mdi mdi-magnify text-slate-400 group-focus-within:text-blue-600 transition-colors text-lg"></i>
                            </div>
                            <input type="text" name="search" placeholder="Cari No. Invoice..." value="{{ request('search') }}" class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-semibold text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white outline-none transition-all shadow-sm">
                        </div>

                        <button type="submit" class="hidden sm:flex items-center justify-center px-4 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl shadow-sm shadow-slate-900/20 transition-all flex-shrink-0">
                            <i class="mdi mdi-magnify text-lg leading-none"></i>
                        </button>

                        @if(request('search') || request('date'))
                            <a href="{{ route('seller.finance.income', ['tab' => $tab]) }}" class="flex items-center justify-center px-4 py-2.5 bg-white border border-slate-200 text-slate-500 hover:text-slate-800 hover:bg-slate-50 text-sm font-bold rounded-xl transition-colors shadow-sm flex-shrink-0">Reset</a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Konten --}}
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">No. Invoice</th>
                                <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Tanggal</th>
                                <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Pembayaran</th>
                                <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap">Status Dana</th>
                                <th class="py-4 px-6 text-[11px] font-black text-slate-500 uppercase tracking-widest whitespace-nowrap text-right">Nominal Masuk</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($transaksi_list as $tx)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="py-4 px-6 font-mono font-black text-blue-600 text-sm whitespace-nowrap">{{ $tx->kode_invoice }}</td>
                                    <td class="py-4 px-6 text-xs font-bold text-slate-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($tx->tanggal_transaksi)->format('d M Y, H:i') }}</td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        <span class="inline-flex px-2.5 py-1 bg-slate-100 border border-slate-200 text-slate-700 text-[10px] font-black uppercase tracking-widest rounded-lg">{{ $tx->metode_pembayaran ?? 'Manual' }}</span>
                                    </td>
                                    <td class="py-4 px-6 whitespace-nowrap">
                                        @if($tab == 'pending')
                                            <span class="inline-flex px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border-amber-200">Tertahan</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-lg border text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border-emerald-200">Dilepas</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-emerald-500 text-base whitespace-nowrap">
                                        + Rp {{ number_format($tx->subtotal, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center justify-center opacity-60">
                                            <i class="mdi mdi-receipt-text-outline text-6xl text-slate-300 mb-4"></i>
                                            <h5 class="text-lg font-black text-slate-800 mb-1">Tidak Ada Transaksi</h5>
                                            <p class="text-sm font-medium text-slate-500">Belum ada dana masuk pada kategori/filter ini.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($transaksi_list->hasPages())
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex justify-center lg:justify-end">
                        {{ $transaksi_list->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>

        </div>

        {{-- ================================================= --}}
        {{-- SISI KANAN (WIDGETS)                              --}}
        {{-- ================================================= --}}
        <div class="lg:col-span-4 space-y-6">

            {{-- WIDGET REKENING (B2B Premium Vibe) --}}
            <div class="bg-gradient-to-br from-slate-900 to-slate-800 border border-slate-800 rounded-3xl p-6 text-white shadow-xl shadow-slate-900/10 relative overflow-hidden">
                <i class="mdi mdi-bank text-9xl absolute -right-4 -bottom-6 text-white/5 pointer-events-none transform -rotate-12"></i>

                <h4 class="text-sm font-black text-slate-300 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-700 pb-3">
                    <i class="mdi mdi-bank text-lg text-blue-400"></i> Rekening Penerima
                </h4>

                <div class="relative z-10">
                    <p class="text-xs font-medium text-slate-400 mb-1">Saldo akan ditransfer ke:</p>
                    <h5 class="text-lg font-black text-white mb-0.5 tracking-tight">BCA - 1234567890</h5>
                    <p class="text-[11px] font-black text-blue-400 tracking-widest uppercase">A.N. PRABU ALAM TIAN</p>

                    <a href="{{ route('seller.finance.bank') }}" class="mt-5 w-full flex items-center justify-center px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white text-xs font-bold rounded-xl transition-colors backdrop-blur-md">
                        Ubah Rekening
                    </a>
                </div>
            </div>

            {{-- WIDGET RIWAYAT PENARIKAN --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-100 pb-3">
                    <i class="mdi mdi-history text-lg text-slate-300"></i> Riwayat Tarik Saldo
                </h4>

                @if($riwayat_payout->isEmpty())
                    <div class="py-8 text-center text-sm font-bold text-slate-400 opacity-70">Belum ada riwayat penarikan.</div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($riwayat_payout as $rp)
                            <div class="py-3.5 flex justify-between items-center group hover:bg-slate-50 -mx-2 px-2 rounded-xl transition-colors">
                                <div>
                                    <div class="text-sm font-black text-slate-900 mb-0.5">Rp {{ number_format($rp->jumlah_payout, 0, ',', '.') }}</div>
                                    <div class="text-[11px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($rp->tanggal_request)->format('d M Y, H:i') }}</div>
                                </div>
                                <div>
                                    @if($rp->status == 'pending')
                                        <span class="inline-flex px-2 py-1 rounded bg-amber-50 text-amber-600 border border-amber-200 text-[9px] font-black uppercase tracking-widest">Diproses Admin</span>
                                    @elseif($rp->status == 'completed')
                                        <span class="inline-flex px-2 py-1 rounded bg-blue-50 text-blue-600 border border-blue-200 text-[9px] font-black uppercase tracking-widest">Berhasil Dikirim</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 rounded bg-red-50 text-red-600 border border-red-200 text-[9px] font-black uppercase tracking-widest">Ditolak</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

    </div>
</div>

{{-- MODAL TAILWIND TARIK SALDO --}}
<div id="payoutModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div id="modalOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity opacity-0 duration-300" onclick="closePayoutModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div id="modalPanel" class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all opacity-0 scale-95 duration-300 w-full sm:max-w-lg border border-slate-200">

                <form action="{{ route('seller.finance.payout') }}" method="POST" id="payoutForm">
                    @csrf

                    {{-- Header --}}
                    <div class="bg-slate-50 px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-black text-slate-900 flex items-center gap-2"><i class="mdi mdi-bank-transfer-out text-blue-600"></i> Tarik Saldo Toko</h3>
                        <button type="button" onclick="closePayoutModal()" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-red-100 text-slate-500 hover:text-red-500 flex items-center justify-center transition-colors">
                            <i class="mdi mdi-close text-lg leading-none"></i>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="p-6 space-y-6">

                        <div class="bg-blue-600 text-white p-5 rounded-2xl flex justify-between items-center shadow-inner relative overflow-hidden">
                            <i class="mdi mdi-wallet absolute -right-2 -bottom-2 text-6xl text-white/10 transform -rotate-12 pointer-events-none"></i>
                            <div class="relative z-10">
                                <span class="block text-[11px] font-black text-blue-200 uppercase tracking-widest mb-1">Saldo Tersedia:</span>
                                <span class="text-2xl font-black tracking-tight">Rp {{ number_format($saldo_aktif, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-2">Nominal Penarikan (Rp) <span class="text-red-500">*</span></label>
                            <div class="flex border border-slate-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-blue-600 transition-all" id="inputContainer">
                                <span class="bg-slate-100 px-4 py-3.5 text-slate-500 font-black border-r border-slate-200 text-lg">Rp</span>
                                <input type="number" name="jumlah_payout" id="inputPayout" class="w-full bg-slate-50 px-4 py-3.5 text-lg font-black text-slate-900 outline-none" placeholder="Min. 50000" min="50000" max="{{ $saldo_aktif }}" required>
                            </div>
                            <p class="text-[11px] font-bold text-red-500 mt-2 hidden" id="errorMsg">Nominal ditarik melebihi saldo aktif Anda!</p>
                        </div>

                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 flex items-start gap-3">
                            <i class="mdi mdi-information text-blue-500 text-xl leading-none"></i>
                            <p class="text-xs font-medium text-slate-600 leading-relaxed m-0">Dana akan ditransfer ke rekening bank utama Anda (BCA) dalam waktu maksimal <span class="font-bold text-slate-900">1x24 Jam kerja</span>.</p>
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex flex-col sm:flex-row justify-end gap-3 rounded-b-3xl">
                        <button type="button" onclick="closePayoutModal()" class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-bold rounded-xl hover:bg-slate-100 transition-colors">Batal</button>
                        <button type="button" id="btnSubmitPayout" class="w-full sm:w-auto px-6 py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl shadow-sm shadow-slate-900/20 transition-all flex items-center justify-center gap-2">
                            Ajukan Penarikan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // --- 1. MODAL TAILWIND LOGIC ---
    const payoutModal = document.getElementById('payoutModal');
    const modalOverlay = document.getElementById('modalOverlay');
    const modalPanel = document.getElementById('modalPanel');

    function openPayoutModal() {
        document.getElementById('inputPayout').value = ''; // reset
        document.getElementById('errorMsg').classList.add('hidden');
        document.getElementById('inputContainer').classList.replace('border-red-500', 'border-slate-200');
        document.getElementById('inputContainer').classList.replace('focus-within:ring-red-500', 'focus-within:ring-blue-600');
        document.getElementById('btnSubmitPayout').disabled = false;

        payoutModal.classList.remove('hidden');
        void payoutModal.offsetWidth;
        modalOverlay.classList.replace('opacity-0', 'opacity-100');
        modalPanel.classList.replace('opacity-0', 'opacity-100');
        modalPanel.classList.replace('scale-95', 'scale-100');
    }

    function closePayoutModal() {
        modalOverlay.classList.replace('opacity-100', 'opacity-0');
        modalPanel.classList.replace('opacity-100', 'opacity-0');
        modalPanel.classList.replace('scale-100', 'scale-95');
        setTimeout(() => payoutModal.classList.add('hidden'), 300);
    }

    document.addEventListener('DOMContentLoaded', function() {

        // --- 2. VALIDASI INPUT REALTIME ---
        const inputPayout = document.getElementById('inputPayout');
        const btnSubmit = document.getElementById('btnSubmitPayout');
        const errorMsg = document.getElementById('errorMsg');
        const inputContainer = document.getElementById('inputContainer');
        const maxSaldo = {{ $saldo_aktif }};

        inputPayout.addEventListener('input', function() {
            let val = parseInt(this.value) || 0;

            if(val > maxSaldo) {
                // Tampilkan Error UI
                errorMsg.classList.remove('hidden');
                btnSubmit.disabled = true;
                btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                inputContainer.classList.replace('border-slate-200', 'border-red-500');
                inputContainer.classList.replace('focus-within:ring-blue-600', 'focus-within:ring-red-500');
            } else {
                // Sembunyikan Error UI
                errorMsg.classList.add('hidden');
                btnSubmit.disabled = false;
                btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                inputContainer.classList.replace('border-red-500', 'border-slate-200');
                inputContainer.classList.replace('focus-within:ring-red-500', 'focus-within:ring-blue-600');
            }
        });

        // --- 3. SWEETALERT KONFIRMASI (ANTI SPAM) ---
        btnSubmit.addEventListener('click', function(e) {
            e.preventDefault();
            let val = parseInt(inputPayout.value) || 0;

            if(val < 50000) {
                Swal.fire({title: 'Nominal Terlalu Kecil', text: 'Minimal penarikan adalah Rp 50.000', icon: 'info', customClass: { popup: 'rounded-3xl' }});
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Penarikan',
                html: `Anda akan menarik dana sebesar <b>Rp ${new Intl.NumberFormat('id-ID').format(val)}</b> ke Rekening BCA Anda. Lanjutkan?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Tarik Dana',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-3xl' }
            }).then((result) => {
                if(result.isConfirmed) {
                    // Loading State & Cegah Double Submit
                    btnSubmit.innerHTML = '<i class="mdi mdi-loading mdi-spin text-lg leading-none"></i> Memproses...';
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-70', 'cursor-not-allowed');
                    document.getElementById('payoutForm').submit();
                }
            });
        });
    });
</script>
@endpush
