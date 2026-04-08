@extends('layouts.seller')

@section('title', 'Dekorasi Toko')

@section('content')
{{-- IMPORT LIBRARY LANGSUNG DI DALAM CONTENT AGAR PASTI TER-LOAD --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    .hide-scrollbar::-webkit-scrollbar { display: none; }
    .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .sortable-ghost { opacity: 0.4; background-color: #eff6ff; border: 2px dashed #3b82f6; }
    .sortable-drag { cursor: grabbing !important; }
</style>

<div class="min-h-screen bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 pb-32" x-data="shopDecorator()" x-init="initApp()">

    {{-- HEADER & TOMBOL SIMPAN --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-indigo-600 shadow-sm flex-shrink-0">
                <i class="mdi mdi-palette-outline text-2xl"></i>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Dekorasi Toko</h1>
                <p class="text-sm font-medium text-slate-500 mt-0.5">Atur tata letak halaman toko Anda secara real-time.</p>
            </div>
        </div>

        <button @click="saveLayout()" :disabled="isSaving" class="w-full md:w-auto flex items-center justify-center gap-2 px-8 py-3 bg-slate-900 hover:bg-black text-white text-sm font-bold rounded-xl shadow-sm shadow-slate-900/20 transition-all flex-shrink-0 disabled:opacity-70">
            <i class="mdi" :class="isSaving ? 'mdi-loading mdi-spin' : 'mdi-content-save'"></i>
            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Dekorasi'"></span>
        </button>
    </div>

    {{-- MAIN LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

        {{-- PANEL KIRI: EDITOR DRAG & DROP --}}
        <div class="lg:col-span-7 space-y-6">

            {{-- Pilihan Komponen Baru --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest mb-4">Tambah Komponen</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <button @click="addComponent('banner')" class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 hover:bg-blue-50 border border-slate-100 hover:border-blue-200 rounded-2xl text-slate-500 hover:text-blue-600 transition-colors group">
                        <i class="mdi mdi-image-area text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold">Banner</span>
                    </button>
                    <button @click="addComponent('produk')" class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 hover:bg-emerald-50 border border-slate-100 hover:border-emerald-200 rounded-2xl text-slate-500 hover:text-emerald-600 transition-colors group">
                        <i class="mdi mdi-cube-outline text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold">Grid Produk</span>
                    </button>
                    <button @click="addComponent('kategori')" class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 hover:bg-amber-50 border border-slate-100 hover:border-amber-200 rounded-2xl text-slate-500 hover:text-amber-600 transition-colors group">
                        <i class="mdi mdi-shape-outline text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold">Kategori</span>
                    </button>
                    <button @click="addComponent('video')" class="flex flex-col items-center justify-center gap-2 p-4 bg-slate-50 hover:bg-red-50 border border-slate-100 hover:border-red-200 rounded-2xl text-slate-500 hover:text-red-600 transition-colors group">
                        <i class="mdi mdi-youtube text-2xl group-hover:scale-110 transition-transform"></i>
                        <span class="text-xs font-bold">Video</span>
                    </button>
                </div>
            </div>

            {{-- List Komponen Aktif (Sortable) --}}
            <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm min-h-[400px]">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Susunan Halaman (<span x-text="layout.length"></span>)</h3>
                    <span class="text-xs font-bold text-slate-400"><i class="mdi mdi-drag-horizontal"></i> Tarik untuk menyusun</span>
                </div>

                <div x-show="layout.length === 0" class="py-10 text-center border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50" style="display: none;">
                    <i class="mdi mdi-view-dashboard-outline text-4xl text-slate-300 mb-2"></i>
                    <p class="text-sm font-bold text-slate-500">Kanvas kosong. Tambahkan komponen dari menu di atas.</p>
                </div>

                {{-- Area Sortable --}}
                <div id="sortable-list" class="space-y-3">
                    <template x-for="(item, index) in layout" :key="item.id">
                        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm transition-all hover:border-blue-300 group">

                            {{-- Header Komponen (Bisa di-drag) --}}
                            <div class="flex items-center justify-between p-4 bg-slate-50 cursor-grab active:cursor-grabbing">
                                <div class="flex items-center gap-3">
                                    <i class="mdi mdi-drag text-slate-400 text-xl drag-handle"></i>
                                    <div class="w-8 h-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-500">
                                        <i class="mdi" :class="getIcon(item.type)"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider" x-text="item.type"></h4>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <button type="button" @click="activeEditIndex = activeEditIndex === index ? null : index" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:border-blue-200 flex items-center justify-center transition-colors">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                    <button type="button" @click="removeComponent(index)" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 hover:text-red-600 hover:bg-red-50 hover:border-red-200 flex items-center justify-center transition-colors">
                                        <i class="mdi mdi-trash-can-outline"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- Editor Detail --}}
                            <div x-show="activeEditIndex === index" class="p-4 border-t border-slate-100 bg-white" style="display: none;">

                                {{-- Jika Banner --}}
                                <template x-if="item.type === 'banner'">
                                    <div class="space-y-3">
                                        <label class="block text-xs font-bold text-slate-700">URL Gambar Banner</label>
                                        <input type="text" x-model="item.image" placeholder="https://contoh.com/gambar.jpg" class="w-full bg-slate-50 border border-slate-200 text-sm font-medium rounded-xl px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none">
                                        <p class="text-[10px] text-slate-400 font-bold">*Tempel URL gambar untuk live preview.</p>
                                    </div>
                                </template>

                                {{-- Jika Produk/Kategori --}}
                                <template x-if="item.type === 'produk' || item.type === 'kategori'">
                                    <div class="space-y-3">
                                        <label class="block text-xs font-bold text-slate-700">Judul Bagian</label>
                                        <input type="text" x-model="item.title" class="w-full bg-slate-50 border border-slate-200 text-sm font-medium rounded-xl px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>
                                </template>

                                {{-- Jika Video --}}
                                <template x-if="item.type === 'video'">
                                    <div class="space-y-3">
                                        <label class="block text-xs font-bold text-slate-700">URL YouTube Embed</label>
                                        <input type="text" x-model="item.url" placeholder="https://www.youtube.com/embed/xxxxxx" class="w-full bg-slate-50 border border-slate-200 text-sm font-medium rounded-xl px-3 py-2.5 focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none">
                                    </div>
                                </template>

                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: LIVE PREVIEW MOBILE --}}
        <div class="lg:col-span-5 relative">
            <div class="sticky top-24 pb-10">

                <div class="text-center mb-4">
                    <span class="inline-flex items-center gap-2 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-lg text-xs font-black uppercase tracking-widest shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Live Preview
                    </span>
                </div>

                {{-- MOCKUP HP MENGGUNAKAN TAILWIND MURNI --}}
                <div class="w-[320px] h-[640px] mx-auto bg-slate-100 border-[14px] border-slate-900 rounded-[45px] shadow-2xl relative overflow-hidden flex flex-col">

                    {{-- Poni HP (Notch) --}}
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[120px] h-[24px] bg-slate-900 rounded-b-2xl z-50"></div>

                    {{-- Header Toko (Di Dalam HP) --}}
                    <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white pt-10 pb-4 px-4 shadow-md relative z-40 flex-shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-white/20 border border-white/30 flex items-center justify-center font-black text-lg">
                                {{ substr($toko->nama_toko ?? 'T', 0, 1) }}
                            </div>
                            <div>
                                <h4 class="text-sm font-black">{{ $toko->nama_toko ?? 'Nama Toko' }}</h4>
                                <div class="text-[10px] font-bold text-blue-200"><i class="mdi mdi-star text-amber-300"></i> 4.9 | Aktif 5 menit lalu</div>
                            </div>
                        </div>
                    </div>

                    {{-- Isi Tampilan Toko (Bisa di-scroll) --}}
                    <div class="flex-1 overflow-y-auto hide-scrollbar bg-slate-100">
                        <template x-for="item in layout" :key="item.id">
                            <div class="mb-2 bg-white shadow-sm">

                                {{-- Preview Banner --}}
                                <template x-if="item.type === 'banner'">
                                    <div class="w-full h-36 bg-slate-200 flex items-center justify-center overflow-hidden">
                                        <template x-if="item.image">
                                            <img :src="item.image" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!item.image">
                                            <span class="text-xs font-bold text-slate-400"><i class="mdi mdi-image-outline text-2xl block text-center mb-1"></i> Banner Promo</span>
                                        </template>
                                    </div>
                                </template>

                                {{-- Preview Kategori --}}
                                <template x-if="item.type === 'kategori'">
                                    <div class="p-3">
                                        <h5 class="text-xs font-black text-slate-800 mb-3" x-text="item.title || 'Kategori Toko'"></h5>
                                        <div class="flex gap-3 overflow-x-hidden">
                                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 border border-slate-200"></div>
                                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 border border-slate-200"></div>
                                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 border border-slate-200"></div>
                                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex-shrink-0 border border-slate-200"></div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Preview Produk --}}
                                <template x-if="item.type === 'produk'">
                                    <div class="p-3 bg-slate-50">
                                        <div class="flex justify-between items-center mb-3">
                                            <h5 class="text-xs font-black text-slate-800 uppercase tracking-widest" x-text="item.title || 'Produk Pilihan'"></h5>
                                            <span class="text-[10px] font-bold text-blue-600">Lihat Semua</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                                                <div class="w-full h-24 bg-slate-200"></div>
                                                <div class="p-2">
                                                    <div class="w-full h-3 bg-slate-100 rounded mb-1"></div>
                                                    <div class="w-1/2 h-3 bg-slate-100 rounded mb-2"></div>
                                                    <div class="text-xs font-black text-red-500">Rp 150.000</div>
                                                </div>
                                            </div>
                                            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-sm">
                                                <div class="w-full h-24 bg-slate-200"></div>
                                                <div class="p-2">
                                                    <div class="w-full h-3 bg-slate-100 rounded mb-1"></div>
                                                    <div class="w-1/2 h-3 bg-slate-100 rounded mb-2"></div>
                                                    <div class="text-xs font-black text-red-500">Rp 85.000</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                {{-- Preview Video --}}
                                <template x-if="item.type === 'video'">
                                    <div class="p-3">
                                        <div class="w-full h-40 bg-slate-800 rounded-xl flex flex-col items-center justify-center text-white relative overflow-hidden">
                                            <template x-if="item.url">
                                                <iframe :src="item.url" class="absolute inset-0 w-full h-full border-0 pointer-events-none"></iframe>
                                            </template>
                                            <template x-if="!item.url">
                                                <div class="text-center">
                                                    <i class="mdi mdi-play-circle text-4xl text-red-500 mb-1"></i>
                                                    <p class="text-[10px] font-bold text-slate-400">Video Placeholder</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>

                            </div>
                        </template>

                        {{-- Tampilan jika kanvas kosong --}}
                        <div x-show="layout.length === 0" style="display: none;" class="flex flex-col items-center justify-center h-full text-center px-4">
                            <i class="mdi mdi-cellphone-cog text-5xl text-slate-300 mb-2"></i>
                            <p class="text-xs font-bold text-slate-400">Tampilan toko Anda kosong.</p>
                        </div>
                    </div>

                </div>
                {{-- END MOCKUP HP --}}

            </div>
        </div>

    </div>
</div>

{{-- SCRIPT LOGIKA ALPINE JS & SORTABLE JS --}}
<script>
    // Ini adalah objek fungsi yang dipanggil oleh x-data="shopDecorator()"
    function shopDecorator() {
        return {
            // Ambil data layout dari backend
            layout: @json($layoutData ?? []),
            activeEditIndex: null,
            isSaving: false,

            getIcon(type) {
                const icons = {
                    'banner': 'mdi-image-area text-blue-500',
                    'produk': 'mdi-cube-outline text-emerald-500',
                    'kategori': 'mdi-shape-outline text-amber-500',
                    'video': 'mdi-youtube text-red-500'
                };
                return icons[type] || 'mdi-widgets';
            },

            addComponent(type) {
                const id = type + '_' + Date.now();
                const newComp = { id: id, type: type };

                if(type === 'produk') newComp.title = 'Produk Pilihan';
                if(type === 'kategori') newComp.title = 'Kategori Toko';
                if(type === 'banner') newComp.image = '';
                if(type === 'video') newComp.url = '';

                this.layout.push(newComp);
                this.activeEditIndex = this.layout.length - 1; // Auto buka editor
            },

            removeComponent(index) {
                this.layout.splice(index, 1);
                this.activeEditIndex = null;
            },

            initApp() {
                const el = document.getElementById('sortable-list');
                const self = this;

                // Inisialisasi Sortable JS
                Sortable.create(el, {
                    handle: '.drag-handle',
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    dragClass: 'sortable-drag',
                    onEnd: function (evt) {
                        // Sinkronisasi data Alpine saat elemen digeser
                        const itemEl = self.layout[evt.oldIndex];
                        self.layout.splice(evt.oldIndex, 1);
                        self.layout.splice(evt.newIndex, 0, itemEl);
                        self.activeEditIndex = null;
                    },
                });
            },

            saveLayout() {
                this.isSaving = true;

                fetch("{{ route('seller.shop.decoration.update') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ layout_data: this.layout })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.status === 'success') {
                        Swal.fire({ title: 'Berhasil!', text: 'Dekorasi toko berhasil disimpan.', icon: 'success', customClass: { popup: 'rounded-3xl' }});
                    } else {
                        throw new Error(data.message);
                    }
                })
                .catch(error => {
                    Swal.fire({ title: 'Gagal!', text: 'Terjadi kesalahan saat menyimpan.', icon: 'error', customClass: { popup: 'rounded-3xl' }});
                })
                .finally(() => {
                    this.isSaving = false;
                });
            }
        }
    }
</script>
@endsection
