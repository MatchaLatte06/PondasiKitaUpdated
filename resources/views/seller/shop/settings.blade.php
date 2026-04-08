@extends('layouts.seller')

@section('title', 'Pengaturan Toko')

@push('styles')
<style>
    /* Transisi untuk Tab Content */
    .tab-content { display: none; opacity: 0; transform: translateY(10px); transition: all 0.3s ease-out; }
    .tab-content.active { display: block; opacity: 1; transform: translateY(0); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 pb-32">

    {{-- SETUP SWEETALERT TOAST --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const Toast = Swal.mixin({
            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
            customClass: { popup: 'rounded-2xl shadow-lg border border-slate-100' }
        });
    </script>
    @if(session('success'))
        <script>document.addEventListener('DOMContentLoaded', () => Toast.fire({icon: 'success', title: '{{ session('success') }}'}));</script>
    @endif
    @if(session('error'))
        <script>document.addEventListener('DOMContentLoaded', () => Swal.fire({title: 'Gagal!', text: '{{ session('error') }}', icon: 'error', customClass: { popup: 'rounded-3xl' }}));</script>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-6 shadow-sm flex items-start gap-3">
            <i class="mdi mdi-alert-circle text-xl mt-0.5"></i>
            <div>
                <h5 class="font-bold text-sm mb-1">Gagal Menyimpan!</h5>
                <ul class="list-disc list-inside text-xs font-medium space-y-0.5">
                    @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="flex items-center gap-4 mb-8">
        <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-slate-700 shadow-sm flex-shrink-0">
            <i class="mdi mdi-cog-outline text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pengaturan</h1>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Kelola operasional, notifikasi, dan keamanan akun toko Anda.</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- ========================================== --}}
        {{-- NAVIGASI TAB VERTIKAL (KIRI)               --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-3">
            <div class="bg-white border border-slate-200 rounded-3xl p-3 shadow-sm sticky top-24">
                <nav class="flex flex-col gap-1">
                    <button type="button" onclick="switchTab('general')" id="btn-general" class="tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-blue-700 bg-blue-50 transition-colors text-left">
                        <i class="mdi mdi-store-cog text-lg"></i> Operasional Toko
                    </button>
                    <button type="button" onclick="switchTab('notification')" id="btn-notification" class="tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors text-left">
                        <i class="mdi mdi-bell-outline text-lg"></i> Notifikasi
                    </button>
                    <button type="button" onclick="switchTab('security')" id="btn-security" class="tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors text-left">
                        <i class="mdi mdi-shield-lock-outline text-lg"></i> Keamanan Akun
                    </button>
                </nav>
            </div>
        </div>

        {{-- ========================================== --}}
        {{-- AREA KONTEN (KANAN)                        --}}
        {{-- ========================================== --}}
        <div class="lg:col-span-9">

            {{-- FORM 1: GENERAL & NOTIFICATION (Disatukan dalam 1 form submit) --}}
            <form action="{{ route('seller.shop.settings.update') }}" method="POST" id="formGeneral">
                @csrf
                @method('PUT')
                <input type="hidden" name="form_type" value="general">

                {{-- KONTEN TAB: OPERASIONAL TOKO --}}
                <div id="tab-general" class="tab-content active space-y-6">

                    {{-- Mode Libur --}}
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <h3 class="text-base font-black text-slate-900 mb-1 flex items-center gap-2">
                                    <i class="mdi mdi-beach text-amber-500 text-xl"></i> Mode Libur / Tutup Toko
                                </h3>
                                <p class="text-sm font-medium text-slate-500 leading-relaxed max-w-xl">
                                    Aktifkan mode libur untuk mencegah pembeli membuat pesanan baru. Pesanan yang sedang berjalan tetap harus diselesaikan.
                                </p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer flex-shrink-0 mt-1">
                                <input type="checkbox" name="status_libur" class="sr-only peer" {{ ($toko->status_libur ?? 0) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-amber-500"></div>
                            </label>
                        </div>
                    </div>

                    {{-- Pesan Otomatis (Auto-Reply) --}}
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <h3 class="text-base font-black text-slate-900 mb-1 flex items-center gap-2">
                            <i class="mdi mdi-robot-outline text-indigo-500 text-xl"></i> Balasan Chat Otomatis
                        </h3>
                        <p class="text-sm font-medium text-slate-500 mb-4">Pesan ini akan dikirim otomatis ketika pelanggan mengirim pesan pertama kali.</p>

                        <textarea name="pesan_otomatis" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all min-h-[100px] resize-none" placeholder="Cth: Halo! Selamat datang di toko kami. Pesanan Anda akan segera kami proses...">{{ $toko->pesan_otomatis ?? '' }}</textarea>
                    </div>
                </div>

                {{-- KONTEN TAB: NOTIFIKASI --}}
                <div id="tab-notification" class="tab-content space-y-6">
                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900 mb-6 pb-4 border-b border-slate-100">Preferensi Notifikasi</h3>

                        <div class="space-y-6">
                            {{-- Item Notif 1 --}}
                            <div class="flex justify-between items-center gap-4">
                                <div>
                                    <h6 class="text-sm font-bold text-slate-800">Email Pesanan Baru</h6>
                                    <p class="text-xs font-medium text-slate-500">Kirim email setiap kali ada pesanan baru masuk.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notif_email_pesanan" class="sr-only peer" {{ ($notif['email_pesanan'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            {{-- Item Notif 2 --}}
                            <div class="flex justify-between items-center gap-4">
                                <div>
                                    <h6 class="text-sm font-bold text-slate-800">Push Chat Pelanggan</h6>
                                    <p class="text-xs font-medium text-slate-500">Tampilkan pop-up notifikasi saat ada chat baru di dashboard.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notif_push_chat" class="sr-only peer" {{ ($notif['push_chat'] ?? true) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            {{-- Item Notif 3 --}}
                            <div class="flex justify-between items-center gap-4">
                                <div>
                                    <h6 class="text-sm font-bold text-slate-800">Email Info & Promo Pondasikita</h6>
                                    <p class="text-xs font-medium text-slate-500">Terima email mengenai fitur baru dan tips berjualan.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="notif_email_promo" class="sr-only peer" {{ ($notif['email_promo'] ?? false) ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- STICKY ACTION BAR BAWAH (Hanya muncul untuk General & Notif) --}}
                <div id="sticky-action-bar" class="fixed bottom-0 left-0 lg:left-[260px] right-0 bg-white/80 backdrop-blur-md border-t border-slate-200 px-6 py-4 flex items-center justify-between z-40 shadow-[0_-10px_15px_-3px_rgba(0,0,0,0.05)]">
                    <div class="hidden sm:block">
                        <p class="text-xs font-bold text-slate-500 m-0"><i class="mdi mdi-information text-blue-500"></i> Pastikan untuk menyimpan perubahan.</p>
                    </div>
                    <div class="flex gap-3 w-full sm:w-auto">
                        <button type="submit" class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-2.5 bg-slate-900 hover:bg-black text-white font-bold rounded-xl shadow-sm shadow-slate-900/20 transition-all btn-save-loader">
                            <i class="mdi mdi-content-save"></i> Simpan Pengaturan
                        </button>
                    </div>
                </div>
            </form>

            {{-- FORM 2: SECURITY (Terpisah karena logicnya berbeda) --}}
            <div id="tab-security" class="tab-content space-y-6">
                <form action="{{ route('seller.shop.settings.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="form_type" value="security">

                    <div class="bg-white border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm">
                        <h3 class="text-base font-black text-slate-900 mb-1 flex items-center gap-2">
                            <i class="mdi mdi-lock-outline text-red-500 text-xl"></i> Ubah Password Akun
                        </h3>
                        <p class="text-sm font-medium text-slate-500 mb-6 pb-4 border-b border-slate-100">Gunakan kombinasi huruf dan angka agar akun Anda tetap aman.</p>

                        <div class="space-y-4 max-w-md">
                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Password Saat Ini</label>
                                <div class="relative">
                                    <input type="password" name="current_password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all" required>
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Password Baru</label>
                                <input type="password" name="new_password" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all" required minlength="8">
                            </div>

                            <div>
                                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="w-full bg-slate-50 border border-slate-200 text-slate-900 text-sm font-bold rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all" required minlength="8">
                            </div>

                            <div class="pt-4 border-t border-slate-100">
                                <button type="submit" class="w-full flex items-center justify-center gap-2 px-8 py-3 bg-red-500 hover:bg-red-600 text-white font-bold rounded-xl shadow-sm shadow-red-500/20 transition-all btn-save-loader">
                                    Perbarui Password
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. LOGIKA TAB VERTIKAL (Vanilla JS - SPA Feel)
    function switchTab(tabId) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.remove('active');
        });

        // Reset all buttons style
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.className = 'tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors text-left';
        });

        // Show active content
        document.getElementById('tab-' + tabId).classList.add('active');

        // Highlight active button
        const activeBtn = document.getElementById('btn-' + tabId);
        activeBtn.className = 'tab-btn flex items-center gap-3 px-4 py-3 rounded-2xl text-sm font-bold text-blue-700 bg-blue-50 transition-colors text-left';

        // Sembunyikan sticky bar jika di tab security (karena security punya tombol submit sendiri)
        const stickyBar = document.getElementById('sticky-action-bar');
        if(tabId === 'security') {
            stickyBar.classList.add('hidden');
            stickyBar.classList.remove('flex');
        } else {
            stickyBar.classList.remove('hidden');
            stickyBar.classList.add('flex');
        }
    }

    // 2. LOADING STATE UNTUK TOMBOL SUBMIT
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('.btn-save-loader');
            if(btn) {
                btn.innerHTML = '<i class="mdi mdi-loading mdi-spin text-lg leading-none"></i> Menyimpan...';
                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
            }
        });
    });
</script>
@endpush
