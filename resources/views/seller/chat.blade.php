@extends('layouts.seller')

@section('title', 'Pusat Pesan (Chat)')

@push('styles')
<style>
    /* Styling khusus scrollbar Chat agar terlihat seperti Mac/iOS */
    .chat-scrollbar::-webkit-scrollbar { width: 6px; }
    .chat-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .chat-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    .chat-scrollbar:hover::-webkit-scrollbar-thumb { background: #94a3b8; }
</style>
@endpush

@section('content')
<div class="h-[calc(100vh-100px)] bg-slate-50 p-4 md:p-6 lg:p-8 font-sans text-slate-900 flex flex-col">

    {{-- HEADER PAGE --}}
    <div class="flex items-center gap-4 mb-6 flex-shrink-0">
        <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-blue-600 shadow-sm flex-shrink-0">
            <i class="mdi mdi-forum-outline text-2xl"></i>
        </div>
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Pusat Pesan Pelanggan</h1>
            <p class="text-sm font-medium text-slate-500 mt-0.5">Jawab pertanyaan pembeli dengan cepat untuk meningkatkan rasio penjualan.</p>
        </div>
    </div>

    {{-- CHAT ENGINE CONTAINER --}}
    <div class="flex flex-1 bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm relative">

        {{-- ================================================= --}}
        {{-- SISI KIRI: DAFTAR KONTAK (SIDEBAR)                --}}
        {{-- ================================================= --}}
        <div id="sidebarPanel" class="w-full md:w-[320px] lg:w-[380px] bg-slate-50 border-r border-slate-200 flex flex-col flex-shrink-0 transition-transform duration-300 relative z-10">

            {{-- Header & Search --}}
            <div class="p-4 border-b border-slate-200 bg-white">
                <div class="relative w-full group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="mdi mdi-magnify text-slate-400 group-focus-within:text-blue-600 transition-colors text-lg"></i>
                    </div>
                    <input type="text" id="searchContact" placeholder="Cari pelanggan..." class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 placeholder-slate-400 focus:ring-2 focus:ring-blue-600 focus:border-blue-600 focus:bg-white outline-none transition-all shadow-sm">
                </div>
            </div>

            {{-- Contact List --}}
            <div id="contactList" class="flex-1 overflow-y-auto chat-scrollbar bg-slate-50">
                <div class="flex flex-col items-center justify-center py-10 opacity-50">
                    <i class="mdi mdi-loading mdi-spin text-4xl text-slate-400 mb-3"></i>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Memuat Riwayat Chat...</p>
                </div>
            </div>

        </div>

        {{-- ================================================= --}}
        {{-- SISI KANAN: RUANG CHAT                            --}}
        {{-- ================================================= --}}
        <div id="chatPanel" class="absolute md:relative top-0 left-0 w-full h-full md:flex flex-1 flex-col bg-slate-100 z-20 md:z-auto transition-transform duration-300 translate-x-full md:translate-x-0 hidden md:block">

            {{-- EMPTY STATE: Belum pilih kontak --}}
            <div id="chatPlaceholder" class="flex flex-col items-center justify-center h-full bg-slate-50/50">
                <div class="w-24 h-24 bg-white border border-slate-200 rounded-full flex items-center justify-center mb-5 shadow-sm">
                    <i class="mdi mdi-message-text-outline text-4xl text-slate-300"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800 mb-1">Ruang Obrolan Kosong</h4>
                <p class="text-sm font-medium text-slate-500">Pilih salah satu percakapan di samping untuk membalas pesan.</p>
            </div>

            {{-- JENDELA CHAT AKTIF --}}
            <div id="chatActiveWindow" class="hidden flex-col h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]">

                {{-- Header Chat --}}
                <div class="bg-white/90 backdrop-blur-md px-4 sm:px-6 py-3.5 border-b border-slate-200 flex items-center gap-4 shadow-sm z-10 flex-shrink-0">
                    <button type="button" id="btnBackMobile" class="md:hidden w-10 h-10 rounded-full bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition-colors">
                        <i class="mdi mdi-arrow-left text-xl leading-none"></i>
                    </button>

                    <div id="activeAvatar" class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 border border-blue-200 flex items-center justify-center font-black text-lg flex-shrink-0">U</div>

                    <div class="flex-1 min-w-0">
                        <h5 id="activeName" class="text-sm font-black text-slate-900 truncate mb-0.5">Nama Pelanggan</h5>
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Online</span>
                        </div>
                    </div>
                </div>

                {{-- Area Pesan (Balon Chat) --}}
                <div id="messageArea" class="flex-1 p-4 sm:p-6 overflow-y-auto chat-scrollbar flex flex-col gap-4">
                    {{-- Balon pesan akan di-inject JS ke sini --}}
                </div>

                {{-- Form Input Bawah --}}
                <form id="formSendMessage" class="bg-white p-4 border-t border-slate-200 flex items-end gap-3 flex-shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                    @csrf
                    <input type="hidden" id="activeChatId">

                    <button type="button" class="w-11 h-11 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center transition-colors flex-shrink-0" title="Kirim Gambar Material">
                        <i class="mdi mdi-paperclip text-xl leading-none"></i>
                    </button>

                    <textarea id="inputMessage" class="flex-1 bg-slate-50 border border-slate-200 text-slate-900 text-sm font-medium rounded-xl px-4 py-3 min-h-[44px] max-h-[120px] resize-none focus:bg-white focus:ring-2 focus:ring-blue-600 outline-none transition-all placeholder-slate-400 chat-scrollbar" placeholder="Ketik balasan Anda..." required></textarea>

                    <button type="submit" id="btnSendMsg" class="w-11 h-11 rounded-xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center transition-colors shadow-sm shadow-blue-600/20 flex-shrink-0 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="mdi mdi-send text-lg leading-none"></i>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let activeChatId = null;
    let pollingInterval = null;

    const contactListDiv = document.getElementById('contactList');
    const msgArea = document.getElementById('messageArea');
    const searchInput = document.getElementById('searchContact');
    const msgInput = document.getElementById('inputMessage');

    const pnlSidebar = document.getElementById('sidebarPanel');
    const pnlChat = document.getElementById('chatPanel');
    const placeholder = document.getElementById('chatPlaceholder');
    const activeWindow = document.getElementById('chatActiveWindow');

    // Mencegah enter mengirim form langsung, shift+enter untuk baris baru
    msgInput.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('formSendMessage').dispatchEvent(new Event('submit'));
        }
    });

    // 1. LOAD DAFTAR KONTAK (CHAT LIST)
    function loadChatList() {
        fetch("{{ route('seller.service.chat.list') }}")
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    renderContactList(data.data);
                }
            })
            .catch(err => {
                contactListDiv.innerHTML = '<div class="text-center py-10 text-red-500 font-bold text-xs"><i class="mdi mdi-alert-circle text-2xl block mb-2"></i>Gagal memuat kontak.</div>';
            });
    }

    function renderContactList(chats) {
        contactListDiv.innerHTML = '';
        if(chats.length === 0) {
            contactListDiv.innerHTML = '<div class="text-center py-10 opacity-50"><i class="mdi mdi-message-off-outline text-4xl text-slate-400 block mb-2"></i><p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Belum ada pesan.</p></div>';
            return;
        }

        chats.forEach(chat => {
            let initial = chat.nama_pelanggan.charAt(0);
            let isActiveClass = (chat.id == activeChatId) ? 'bg-blue-50/50 border-l-4 border-l-blue-600 pl-3 pr-4' : 'bg-transparent border-l-4 border-l-transparent hover:bg-slate-100/50 px-4';

            let html = `
                <div class="contact-item flex items-center gap-3 py-4 border-b border-slate-100 cursor-pointer transition-colors ${isActiveClass}" data-id="${chat.id}" data-name="${chat.nama_pelanggan}">
                    <div class="w-12 h-12 rounded-full bg-slate-200 text-slate-600 border border-slate-300 flex items-center justify-center font-black text-lg flex-shrink-0 uppercase">${initial}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h6 class="text-sm font-bold text-slate-900 truncate pr-2">${chat.nama_pelanggan}</h6>
                            <span class="text-[10px] font-bold text-slate-400 whitespace-nowrap">${chat.time_display}</span>
                        </div>
                        <p class="text-xs font-medium text-slate-500 truncate">${chat.last_message || '...'}</p>
                    </div>
                </div>
            `;
            contactListDiv.insertAdjacentHTML('beforeend', html);
        });
    }

    // 2. FITUR PENCARIAN KONTAK LOKAL
    searchInput.addEventListener('keyup', function() {
        let keyword = this.value.toLowerCase();
        let items = contactListDiv.querySelectorAll('.contact-item');
        items.forEach(item => {
            let name = item.querySelector('h6').textContent.toLowerCase();
            if (name.includes(keyword)) {
                item.classList.remove('hidden');
                item.classList.add('flex');
            } else {
                item.classList.remove('flex');
                item.classList.add('hidden');
            }
        });
    });

    // 3. KLIK KONTAK BUKA RUANG CHAT
    contactListDiv.addEventListener('click', function(e) {
        let item = e.target.closest('.contact-item');
        if(!item) return;

        // Set Active State Visual UI
        contactListDiv.querySelectorAll('.contact-item').forEach(el => {
            el.classList.remove('bg-blue-50/50', 'border-l-blue-600', 'pl-3', 'pr-4');
            el.classList.add('bg-transparent', 'border-l-transparent', 'hover:bg-slate-100/50', 'px-4');
        });
        item.classList.remove('bg-transparent', 'border-l-transparent', 'hover:bg-slate-100/50', 'px-4');
        item.classList.add('bg-blue-50/50', 'border-l-blue-600', 'pl-3', 'pr-4');

        activeChatId = item.dataset.id;
        let cName = item.dataset.name;

        // UI Updates
        placeholder.classList.add('hidden');
        placeholder.classList.remove('flex');

        activeWindow.classList.remove('hidden');
        activeWindow.classList.add('flex');

        document.getElementById('activeName').textContent = cName;
        document.getElementById('activeAvatar').textContent = cName.charAt(0);
        document.getElementById('activeChatId').value = activeChatId;

        // Mode Mobile: Slide Panel Chat (Menggunakan flex dan translate tailwind)
        if(window.innerWidth <= 768) {
            pnlChat.classList.remove('hidden', 'translate-x-full');
            pnlChat.classList.add('flex', 'translate-x-0');
        }

        // Load Pesan & Mulai Polling
        loadMessages(activeChatId, true);
        if(pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => { loadMessages(activeChatId, false); }, 4000);
    });

    // 4. LOAD PESAN DARI DATABASE
    function loadMessages(chatId, forceScroll = false) {
        if(!chatId) return;

        let url = "{{ route('seller.service.chat.messages', ':id') }}".replace(':id', chatId);

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    msgArea.innerHTML = '';

                    data.data.forEach(msg => {
                        let isOut = msg.is_mine;
                        let wrapAlign = isOut ? 'self-end items-end' : 'self-start items-start';
                        let bubbleColor = isOut ? 'bg-blue-600 text-white rounded-l-2xl rounded-tr-2xl rounded-br-sm' : 'bg-white border border-slate-200 text-slate-800 rounded-r-2xl rounded-tl-2xl rounded-bl-sm';
                        let timeColor = isOut ? 'text-blue-200' : 'text-slate-400';

                        let html = `
                            <div class="flex flex-col max-w-[85%] md:max-w-[75%] ${wrapAlign}">
                                <div class="px-4 py-2.5 shadow-sm text-[13px] md:text-sm font-medium leading-relaxed ${bubbleColor}">
                                    ${msg.text}
                                    <div class="text-[10px] font-bold mt-1 text-right ${timeColor}">${msg.time}</div>
                                </div>
                            </div>
                        `;
                        msgArea.insertAdjacentHTML('beforeend', html);
                    });

                    if(forceScroll) scrollToBottom();
                }
            });
    }

    function scrollToBottom() {
        msgArea.scrollTop = msgArea.scrollHeight;
    }

    // 5. KIRIM PESAN AJAX
    document.getElementById('formSendMessage').addEventListener('submit', function(e) {
        e.preventDefault();

        let text = msgInput.value.trim();
        let chatId = document.getElementById('activeChatId').value;

        if(text === '' || !chatId) return;

        let btn = document.getElementById('btnSendMsg');
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin text-xl leading-none"></i>';

        fetch("{{ route('seller.service.chat.send') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ chat_id: chatId, message_text: text })
        })
        .then(res => res.json())
        .then(data => {
            if(data.status === 'success') {
                msgInput.value = '';
                loadMessages(chatId, true);
                loadChatList();
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-send text-lg leading-none"></i>';
            msgInput.focus();
        });
    });

    // 6. TOMBOL BACK MOBILE
    document.getElementById('btnBackMobile').addEventListener('click', function() {
        pnlChat.classList.add('hidden', 'translate-x-full');
        pnlChat.classList.remove('flex', 'translate-x-0');

        if(pollingInterval) clearInterval(pollingInterval);
        activeChatId = null;

        contactListDiv.querySelectorAll('.contact-item').forEach(el => {
            el.classList.remove('bg-blue-50/50', 'border-l-blue-600', 'pl-3', 'pr-4');
            el.classList.add('bg-transparent', 'border-l-transparent', 'hover:bg-slate-100/50', 'px-4');
        });

        placeholder.classList.remove('hidden');
        placeholder.classList.add('flex');

        activeWindow.classList.add('hidden');
        activeWindow.classList.remove('flex');

        loadChatList();
    });

    // Init
    loadChatList();
});
</script>
@endpush
