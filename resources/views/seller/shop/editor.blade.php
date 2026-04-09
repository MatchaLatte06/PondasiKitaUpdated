@extends('layouts.seller')

@section('title', 'Editor Dekorasi Toko')

@push('styles')
<style>
    [x-cloak] { display: none !important; }

    /* Layout Editor Full Screen menutupi default layout jika diperlukan */
    .editor-wrapper { height: calc(100vh - 64px); }

    /* Scrollbar minimalis untuk editor */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

    /* MOCKUP KANVAS TENGAH (Frame HP) */
    .canvas-phone {
        width: 360px; min-height: 700px;
        background: #f8fafc; border-radius: 40px;
        box-shadow: 0 0 0 10px #0f172a, 0 25px 50px -12px rgba(0,0,0,0.5);
        position: relative; overflow: hidden; display: flex; flex-direction: column;
    }
    .canvas-notch {
        position: absolute; top: 0; left: 50%; transform: translateX(-50%);
        width: 120px; height: 24px; background: #0f172a;
        border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; z-index: 50;
    }

    /* Efek saat Drag & Drop */
    .sortable-ghost { opacity: 0.4; background-color: #e2e8f0; border: 2px dashed #3b82f6; }
    .sortable-drag { cursor: grabbing !important; }

    /* Hover Komponen di Kanvas */
    .canvas-component { position: relative; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;}
    .canvas-component:hover { border-color: #cbd5e1; }
    .canvas-component.active { border-color: #3b82f6; box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1); z-index: 10;}
</style>
@endpush

@push('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div x-data="shopBuilder()" x-init="initBuilder()" class="bg-slate-100 font-sans text-slate-800 -m-6 h-screen flex flex-col" x-cloak>

    {{-- HEADER EDITOR --}}
    <div class="h-16 bg-white border-b border-slate-200 flex justify-between items-center px-6 shadow-sm z-20 flex-shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('seller.shop.decoration.template') }}" class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 hover:bg-slate-200 transition-colors">
                <i class="mdi mdi-arrow-left"></i>
            </a>
            <div>
                <h1 class="font-black text-slate-800 leading-none">Editor Dekorasi</h1>
                <span class="text-[11px] font-bold text-slate-500" x-text="templateName">Memuat...</span>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <button @click="resetCanvas()" class="px-4 py-2 text-sm font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors outline-none">Reset</button>
            <button @click="saveDecoration()" class="px-6 py-2 text-sm font-bold text-white bg-[#ee4d2d] hover:bg-[#d73211] rounded-lg shadow-md transition-colors flex items-center gap-2 outline-none">
                <i class="mdi mdi-content-save"></i> Simpan & Tayangkan
            </button>
        </div>
    </div>

    {{-- WORKSPACE 3 KOLOM --}}
    <div class="flex-1 flex overflow-hidden">

        {{-- KOLOM 1: KIRI (PANEL KOMPONEN) --}}
        <div class="w-72 bg-white border-r border-slate-200 flex flex-col z-10 flex-shrink-0">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex-shrink-0">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">Komponen</h2>
            </div>
            <div class="p-4 space-y-3 overflow-y-auto flex-1 hide-scrollbar" id="component-list">

                {{-- Draggable Items --}}
                <div class="p-3 border border-slate-200 rounded-xl flex items-center gap-3 cursor-grab hover:bg-blue-50 hover:border-blue-200 transition-colors shadow-sm" data-type="banner">
                    <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-xl"><i class="mdi mdi-image-area"></i></div>
                    <div><div class="text-sm font-bold text-slate-800">Banner Tunggal</div><div class="text-[10px] text-slate-500">Gambar statis promo</div></div>
                </div>

                <div class="p-3 border border-slate-200 rounded-xl flex items-center gap-3 cursor-grab hover:bg-emerald-50 hover:border-emerald-200 transition-colors shadow-sm" data-type="carousel">
                    <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl"><i class="mdi mdi-view-carousel"></i></div>
                    <div><div class="text-sm font-bold text-slate-800">Carousel</div><div class="text-[10px] text-slate-500">Gambar slider berjalan</div></div>
                </div>

                <div class="p-3 border border-slate-200 rounded-xl flex items-center gap-3 cursor-grab hover:bg-orange-50 hover:border-orange-200 transition-colors shadow-sm" data-type="produk">
                    <div class="w-10 h-10 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-xl"><i class="mdi mdi-fire"></i></div>
                    <div><div class="text-sm font-bold text-slate-800">Produk Pilihan</div><div class="text-[10px] text-slate-500">Grid produk terlaris</div></div>
                </div>

                <div class="p-3 border border-slate-200 rounded-xl flex items-center gap-3 cursor-grab hover:bg-purple-50 hover:border-purple-200 transition-colors shadow-sm" data-type="video">
                    <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-xl"><i class="mdi mdi-play-circle"></i></div>
                    <div><div class="text-sm font-bold text-slate-800">Video Promo</div><div class="text-[10px] text-slate-500">Embed video Youtube</div></div>
                </div>
            </div>
        </div>

        {{-- KOLOM 2: TENGAH (KANVAS MOCKUP HP) --}}
        <div class="flex-1 bg-slate-100 overflow-y-auto flex justify-center py-10 relative">

            <div class="canvas-phone">
                <div class="canvas-notch"></div>

                {{-- Header Toko Statis di Canvas --}}
                <div class="h-36 bg-gradient-to-r from-slate-800 to-slate-900 p-4 flex flex-col justify-end text-white flex-shrink-0 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-12 h-12 rounded-full border-2 border-white/50 bg-white/20 backdrop-blur-sm flex items-center justify-center text-xl font-black">{{ strtoupper(substr($toko->nama_toko ?? 'T', 0, 1)) }}</div>
                        <div>
                            <div class="text-sm font-black">{{ $toko->nama_toko ?? 'NAMA TOKO' }}</div>
                            <div class="text-[10px] text-white/80">Mode Edit Dekorasi</div>
                        </div>
                    </div>
                </div>

                {{-- AREA DROPZONE KOMPONEN DINAMIS --}}
                <div id="canvas-dropzone" class="flex-1 bg-white flex flex-col min-h-[400px] pb-20 relative">

                    {{-- Empty State (Muncul jika array kosong) --}}
                    <div x-show="canvasItems.length === 0" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 p-6 text-center z-0 pointer-events-none">
                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3"><i class="mdi mdi-tray-arrow-down text-3xl"></i></div>
                        <p class="text-sm font-bold text-slate-500">Kanvas Kosong</p>
                        <p class="text-xs mt-1">Tarik komponen dari panel kiri ke area ini.</p>
                    </div>

                    {{-- Render komponen menggunakan struktur For Alpine yang aman dari Vue --}}
                    <template x-for="(item, index) in canvasItems" :key="item.uid">
                        <div class="canvas-component p-2 relative z-10"
                             :class="{ 'active': activeItemId === item.uid }"
                             @click="setActive(item)"
                             :data-uid="item.uid">

                            {{-- Tombol Hapus (Hanya muncul saat item aktif) --}}
                            <button x-show="activeItemId === item.uid" @click.stop="removeItem(index)" class="absolute -top-3 -right-3 w-6 h-6 bg-red-500 text-white rounded-full shadow-lg z-20 flex items-center justify-center hover:bg-red-600 outline-none">
                                <i class="mdi mdi-close text-xs"></i>
                            </button>

                            {{-- Placeholder Tipe Komponen --}}
                            <div x-show="item.type === 'banner'" style="display: none;" class="w-full h-32 bg-blue-50 rounded-xl flex items-center justify-center border border-blue-200 border-dashed text-blue-500 flex-col gap-1 shadow-sm">
                                <i class="mdi mdi-image text-3xl"></i><span class="text-[10px] font-black uppercase" x-text="item.config.title || 'BANNER PROMO'"></span>
                            </div>

                            <div x-show="item.type === 'carousel'" style="display: none;" class="w-full h-40 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-200 border-dashed text-emerald-500 flex-col gap-1 shadow-sm">
                                <i class="mdi mdi-view-carousel text-4xl"></i><span class="text-[10px] font-black uppercase">CAROUSEL SLIDER</span>
                            </div>

                            <div x-show="item.type === 'produk'" style="display: none;" class="bg-white border border-slate-100 rounded-xl p-3 shadow-sm shadow-slate-200/50">
                                <div class="flex items-center gap-1.5 mb-3 px-1">
                                    <i class="mdi mdi-fire text-red-500"></i>
                                    <h4 class="text-xs font-black text-slate-800" x-text="item.config.title || 'Produk Pilihan'"></h4>
                                </div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="h-28 bg-slate-50 rounded-lg flex flex-col items-center justify-center text-slate-400 border border-slate-100 p-2"><i class="mdi mdi-image-outline text-2xl mb-1"></i><div class="w-full h-1.5 bg-slate-200 rounded"></div></div>
                                    <div class="h-28 bg-slate-50 rounded-lg flex flex-col items-center justify-center text-slate-400 border border-slate-100 p-2"><i class="mdi mdi-image-outline text-2xl mb-1"></i><div class="w-full h-1.5 bg-slate-200 rounded"></div></div>
                                </div>
                            </div>

                            <div x-show="item.type === 'video'" style="display: none;" class="w-full h-36 bg-slate-900 rounded-xl flex items-center justify-center text-red-500 shadow-sm border border-slate-700">
                                <i class="mdi mdi-play-circle text-5xl"></i>
                            </div>

                        </div>
                    </template>

                </div>
            </div>
        </div>

        {{-- KOLOM 3: KANAN (PANEL PROPERTI/PENGATURAN) --}}
        <div class="w-80 bg-white border-l border-slate-200 z-10 flex flex-col flex-shrink-0 shadow-[-5px_0_15px_rgba(0,0,0,0.03)]">
            <div class="p-4 border-b border-slate-100 bg-slate-50 flex-shrink-0">
                <h2 class="text-sm font-black text-slate-800 uppercase tracking-wider">Pengaturan</h2>
            </div>

            <div class="p-4 overflow-y-auto flex-1 hide-scrollbar relative">

                {{-- Jika Ada Item yang Diklik --}}
                <div x-show="activeItem !== null" style="display: none;">
                    <div class="space-y-5" x-data="{ currentConfig: {} }" x-effect="if(activeItem) currentConfig = activeItem.config">

                        <div class="p-3 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 flex items-center gap-2 mb-2">
                            <div class="w-6 h-6 bg-white rounded-md flex items-center justify-center font-black shadow-sm"><i class="mdi mdi-pencil text-sm"></i></div>
                            <span class="text-xs font-bold">Edit <span x-text="activeItem ? activeItem.type.toUpperCase() : ''"></span></span>
                        </div>

                        {{-- Form Title (Semua Punya Ini) --}}
                        <div>
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Judul Seksi</label>
                            <input type="text" x-model="currentConfig.title" @input="updateItemConfig(currentConfig)" class="w-full bg-white border border-slate-300 text-sm font-bold rounded-xl px-3 py-2.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-50 transition-all shadow-sm" placeholder="Contoh: Promo Spesial">
                        </div>

                        {{-- Form Khusus Banner --}}
                        <div x-show="activeItem && (activeItem.type === 'banner' || activeItem.type === 'carousel')">
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Upload Gambar</label>
                            <div class="w-full h-32 border-2 border-dashed border-slate-300 rounded-2xl bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:bg-slate-100 hover:border-blue-400 transition-colors group">
                                <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-200 mb-2 group-hover:scale-110 transition-transform">
                                    <i class="mdi mdi-cloud-upload text-xl text-blue-500"></i>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500">Klik untuk pilih gambar</span>
                            </div>
                        </div>

                        {{-- Form Khusus Produk --}}
                        <div x-show="activeItem && activeItem.type === 'produk'">
                            <label class="block text-[11px] font-black text-slate-500 mb-1.5 uppercase tracking-wider">Pilih Produk</label>
                            <button class="w-full py-3 bg-white border-2 border-slate-200 text-slate-600 text-xs font-bold rounded-xl hover:border-slate-800 hover:text-slate-900 transition-colors flex items-center justify-center gap-2 outline-none">
                                <i class="mdi mdi-plus-circle text-base"></i> Tambah Produk
                            </button>
                        </div>

                    </div>
                </div>

                {{-- Jika Tidak Ada Item yang Diklik --}}
                <div x-show="activeItem === null" class="absolute inset-0 flex flex-col items-center justify-center text-center text-slate-400 p-6 pointer-events-none">
                    <i class="mdi mdi-cursor-default-click-outline text-5xl mb-3 text-slate-200"></i>
                    <p class="text-xs font-bold text-slate-500">Pilih Komponen</p>
                    <p class="text-[10px] font-medium mt-1 leading-relaxed">Klik salah satu komponen di kanvas untuk mulai mengatur kontennya.</p>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    const generateUid = () => Date.now().toString(36) + Math.random().toString(36).substr(2);

    function shopBuilder() {
        return {
            templateName: 'Kanvas Kosong',
            canvasItems: [],
            activeItemId: null,

            // Getter untuk mengambil item yang sedang aktif
            get activeItem() {
                if(!this.activeItemId) return null;
                return this.canvasItems.find(item => item.uid === this.activeItemId) || null;
            },

            initBuilder() {
                const urlParams = new URLSearchParams(window.location.search);
                const tplId = urlParams.get('tpl');

                if(tplId && tplId !== 'blank') {
                    this.templateName = `Template #${tplId}`;
                    this.loadTemplateData(tplId);
                }

                // INIT SORTABLE JS SECARA AMAN
                this.$nextTick(() => {
                    const compList = document.getElementById('component-list');
                    const canvasDrop = document.getElementById('canvas-dropzone');

                    if(compList && canvasDrop) {
                        new Sortable(compList, {
                            group: { name: 'shared', pull: 'clone', put: false },
                            sort: false,
                            animation: 150
                        });

                        new Sortable(canvasDrop, {
                            group: 'shared',
                            animation: 150,
                            ghostClass: 'sortable-ghost',
                            dragClass: 'sortable-drag',
                            onAdd: (evt) => {
                                const type = evt.item.dataset.type;
                                evt.item.remove();
                                this.addComponent(type, evt.newIndex);
                            },
                            onEnd: (evt) => {
                                const movedItem = this.canvasItems.splice(evt.oldIndex, 1)[0];
                                this.canvasItems.splice(evt.newIndex, 0, movedItem);
                            }
                        });
                    }
                });
            },

            addComponent(type, index) {
                const newItem = {
                    uid: generateUid(),
                    type: type,
                    config: { title: '' }
                };
                this.canvasItems.splice(index, 0, newItem);
                this.setActive(newItem);
            },

            setActive(item) {
                this.activeItemId = item.uid;
            },

            updateItemConfig(newConfig) {
                // Update konfigurasi secara reaktif
                const index = this.canvasItems.findIndex(i => i.uid === this.activeItemId);
                if (index !== -1) {
                    this.canvasItems[index].config = { ...newConfig };
                }
            },

            removeItem(index) {
                this.canvasItems.splice(index, 1);
                this.activeItemId = null;
            },

            resetCanvas() {
                if(this.canvasItems.length === 0) return;

                Swal.fire({
                    title: 'Kosongkan Kanvas?',
                    text: "Semua komponen akan dihapus.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ee4d2d',
                    confirmButtonText: 'Ya, Hapus Semua',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.canvasItems = [];
                        this.activeItemId = null;
                    }
                });
            },

            saveDecoration() {
                if(this.canvasItems.length === 0) {
                    Swal.fire({icon: 'error', title: 'Oops!', text: 'Kanvas masih kosong. Tambahkan minimal 1 komponen.', customClass: { popup: 'rounded-3xl' }});
                    return;
                }

                const payload = JSON.stringify(this.canvasItems);
                console.log("PAYLOAD TO SAVE:", payload);

                Swal.fire({
                    title: 'Menyimpan...',
                    timer: 1500,
                    didOpen: () => { Swal.showLoading() },
                    customClass: { popup: 'rounded-3xl' }
                }).then(() => {
                    Swal.fire({icon: 'success', title: 'Berhasil!', text: 'Dekorasi toko berhasil disimpan dan ditayangkan.', customClass: { popup: 'rounded-3xl' }});
                });
            },

            loadTemplateData(id) {
                if(id == 1) {
                    this.canvasItems = [
                        { uid: generateUid(), type: 'banner', config: { title: 'Promo Gajian' } },
                        { uid: generateUid(), type: 'kategori', config: { title: 'Kategori Pilihan' } },
                        { uid: generateUid(), type: 'produk', config: { title: 'Produk Terlaris' } },
                    ];
                }
            }
        }
    }
</script>
@endsection
