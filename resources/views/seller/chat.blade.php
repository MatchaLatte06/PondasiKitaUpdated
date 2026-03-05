@extends('layouts.seller')

@section('title', 'Pusat Pesan (Chat)')

@section('content')
<style>
    /* === CSS ISOLATED UNTUK CHAT ENGINE === */
    :root {
        --chat-primary: #0f172a;    /* Biru Gelap B2B */
        --chat-accent: #2563eb;     /* Biru Terang untuk tombol/bubble */
        --chat-bg-main: #e2e8f0;    /* Background area chat ala WA Web */
        --chat-bg-panel: #ffffff;
        --chat-border: #cbd5e1;
        --chat-bubble-in: #ffffff;
        --chat-bubble-out: #dbeafe; /* Biru muda untuk pesan sendiri */
        --text-dark: #1e293b;
        --text-mut: #64748b;
    }

    .chat-app-wrapper { font-family: 'Inter', sans-serif; display: flex; flex-direction: column; height: calc(100vh - 100px); }
    
    /* Header Page */
    .page-title-box { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-shrink: 0; }
    .icon-wrapper { background: var(--chat-primary); color: white; width: 45px; height: 45px; border-radius: 12px; display: flex; justify-content: center; align-items: center; font-size: 1.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .page-title h3 { margin: 0; font-size: 20px; font-weight: 800; color: var(--chat-primary); }
    .page-title p { margin: 0; font-size: 13px; color: var(--text-mut); }

    /* Main Container Split */
    .chat-engine-container { display: flex; flex-grow: 1; background: var(--chat-bg-panel); border-radius: 16px; border: 1px solid var(--chat-border); overflow: hidden; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); }

    /* ========================================================
       PANEL KIRI (DAFTAR KONTAK) 
       ======================================================== */
    .sidebar-panel { width: 320px; border-right: 1px solid var(--chat-border); display: flex; flex-direction: column; background: #f8fafc; flex-shrink: 0; transition: 0.3s; }
    
    .sidebar-header { padding: 15px 20px; border-bottom: 1px solid var(--chat-border); background: white; }
    .search-box { position: relative; }
    .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-box input { width: 100%; padding: 10px 10px 10px 35px; border-radius: 8px; border: 1px solid var(--chat-border); font-size: 13px; background: #f1f5f9; transition: 0.2s; outline: none; }
    .search-box input:focus { border-color: var(--chat-accent); background: white; }

    .contact-list { flex-grow: 1; overflow-y: auto; }
    .contact-list::-webkit-scrollbar { width: 6px; }
    .contact-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

    .contact-item { display: flex; padding: 15px 20px; border-bottom: 1px solid var(--chat-border); cursor: pointer; transition: 0.2s; align-items: center; gap: 12px; }
    .contact-item:hover { background: #f1f5f9; }
    .contact-item.active { background: white; border-left: 4px solid var(--chat-accent); }
    
    .c-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 18px; flex-shrink: 0; text-transform: uppercase; }
    .c-info { flex-grow: 1; overflow: hidden; }
    .c-top-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .c-name { font-weight: 700; font-size: 14px; color: var(--text-dark); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .c-time { font-size: 11px; color: #94a3b8; font-weight: 600; }
    .c-msg { font-size: 13px; color: var(--text-mut); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* ========================================================
       PANEL KANAN (RUANG OBROLAN) 
       ======================================================== */
    .chat-panel { flex-grow: 1; display: flex; flex-direction: column; background: var(--chat-bg-main); position: relative; }
    
    /* Empty State */
    .chat-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: #94a3b8; background: #f8fafc; }
    .chat-empty i { font-size: 5rem; margin-bottom: 15px; opacity: 0.5; }
    
    /* Chat Header Aktif */
    .chat-header { background: white; padding: 15px 24px; border-bottom: 1px solid var(--chat-border); display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); z-index: 10; }
    .btn-back { display: none; background: none; border: none; font-size: 1.5rem; color: var(--text-dark); cursor: pointer; padding: 0; }
    .active-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--chat-primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; }
    .active-info h5 { margin: 0 0 2px 0; font-size: 15px; font-weight: 800; color: var(--text-dark); }
    .active-info span { font-size: 12px; color: #10b981; font-weight: 600; }

    /* Area Balon Pesan */
    .message-area { flex-grow: 1; padding: 24px; overflow-y: auto; display: flex; flex-direction: column; gap: 15px; }
    .message-area::-webkit-scrollbar { width: 6px; }
    .message-area::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }

    .msg-wrapper { display: flex; flex-direction: column; max-width: 75%; }
    .msg-wrapper.in { align-self: flex-start; }
    .msg-wrapper.out { align-self: flex-end; }
    
    .msg-bubble { padding: 12px 16px; border-radius: 12px; font-size: 14px; line-height: 1.5; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .msg-wrapper.in .msg-bubble { background: var(--chat-bubble-in); color: var(--text-dark); border-top-left-radius: 4px; }
    .msg-wrapper.out .msg-bubble { background: var(--chat-bubble-out); color: var(--text-dark); border-top-right-radius: 4px; }
    
    .msg-time { font-size: 10px; color: #94a3b8; margin-top: 4px; align-self: flex-end; font-weight: 600; }
    .msg-wrapper.out .msg-time { color: #60a5fa; } /* Waktu di balon kita */

    /* Form Input Area */
    .input-area { background: white; padding: 15px 24px; border-top: 1px solid var(--chat-border); display: flex; align-items: center; gap: 12px; }
    .btn-attach { background: none; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer; transition: 0.2s; padding: 5px; }
    .btn-attach:hover { color: var(--chat-accent); }
    
    .input-box { flex-grow: 1; border: 1px solid var(--chat-border); border-radius: 24px; padding: 12px 20px; font-size: 14px; outline: none; background: #f8fafc; transition: 0.2s; }
    .input-box:focus { border-color: var(--chat-accent); background: white; }
    
    .btn-send { background: var(--chat-accent); color: white; border: none; width: 45px; height: 45px; border-radius: 50%; display: flex; justify-content: center; align-items: center; font-size: 1.2rem; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 6px rgba(37,99,235,0.2); flex-shrink: 0; }
    .btn-send:hover { background: #1d4ed8; transform: scale(1.05); }

    /* RESPONSIVE MOBILE */
    @media (max-width: 768px) {
        .chat-engine-container { flex-direction: column; border-radius: 0; border: none; }
        .sidebar-panel { width: 100%; height: 100%; border-right: none; }
        .chat-panel { display: none; width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 50; }
        .chat-panel.mobile-active { display: flex; }
        .btn-back { display: block; }
    }
</style>

<div class="chat-app-wrapper">

    {{-- HEADER PAGE --}}
    <div class="page-title-box">
        <div class="icon-wrapper"><i class="mdi mdi-forum-outline"></i></div>
        <div class="page-title">
            <h3>Pusat Pesan Pelanggan</h3>
            <p>Jawab pertanyaan pembeli dengan cepat untuk meningkatkan rasio penjualan.</p>
        </div>
    </div>

    {{-- CHAT ENGINE MAIN --}}
    <div class="chat-engine-container">
        
        {{-- SISI KIRI: DAFTAR KONTAK --}}
        <div class="sidebar-panel" id="sidebarPanel">
            <div class="sidebar-header">
                <div class="search-box">
                    <i class="mdi mdi-magnify"></i>
                    <input type="text" id="searchContact" placeholder="Cari nama pelanggan...">
                </div>
            </div>
            
            <div class="contact-list" id="contactList">
                <div class="text-center py-5 text-muted">
                    <div class="spinner-border spinner-border-sm mb-2 text-primary" role="status"></div>
                    <p style="font-size: 12px; font-weight: 600;">Memuat Riwayat Chat...</p>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: RUANG CHAT --}}
        <div class="chat-panel" id="chatPanel">
            
            {{-- Tampilan Kosong (Belum milih kontak) --}}
            <div id="chatPlaceholder" class="chat-empty">
                <i class="mdi mdi-message-processing-outline"></i>
                <h4 class="fw-bold text-dark">Pilih Pesan</h4>
                <p>Klik nama pelanggan di samping untuk mulai berdiskusi.</p>
            </div>

            {{-- Jendela Chat Aktif --}}
            <div id="chatActiveWindow" style="display: none; flex-direction: column; height: 100%;">
                
                {{-- Header Chat --}}
                <div class="chat-header">
                    <button class="btn-back" id="btnBackMobile"><i class="mdi mdi-arrow-left"></i></button>
                    <div class="active-avatar" id="activeAvatar">U</div>
                    <div class="active-info">
                        <h5 id="activeName">Nama Pelanggan</h5>
                        <span>Online</span>
                    </div>
                </div>

                {{-- Area Pesan (Balon) --}}
                <div class="message-area" id="messageArea">
                    </div>

                {{-- Form Input Bawah --}}
                <form class="input-area" id="formSendMessage">
                    @csrf
                    <input type="hidden" id="activeChatId">
                    
                    {{-- Tombol Lampiran (Fungsionalitas desain B2B) --}}
                    <button type="button" class="btn-attach" title="Kirim Gambar Material"><i class="mdi mdi-paperclip"></i></button>
                    
                    <input type="text" id="inputMessage" class="input-box" placeholder="Ketik balasan untuk pelanggan..." required autocomplete="off">
                    
                    <button type="submit" class="btn-send" id="btnSendMsg"><i class="mdi mdi-send"></i></button>
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
    
    const pnlSidebar = document.getElementById('sidebarPanel');
    const pnlChat = document.getElementById('chatPanel');
    const placeholder = document.getElementById('chatPlaceholder');
    const activeWindow = document.getElementById('chatActiveWindow');

    // 1. LOAD DAFTAR KONTAK (CHAT LIST)
    function loadChatList() {
        fetch("{{ route('seller.service.chat.list') }}")
            .then(res => res.json())
            .then(data => {
                if(data.status === 'success') {
                    renderContactList(data.data);
                }
            })
            .catch(err => console.error('Error fetching list:', err));
    }

    function renderContactList(chats) {
        contactListDiv.innerHTML = '';
        if(chats.length === 0) {
            contactListDiv.innerHTML = '<div class="text-center py-5 text-muted fw-bold" style="font-size:13px;">Belum ada riwayat pesan masuk.</div>';
            return;
        }

        chats.forEach(chat => {
            let initial = chat.nama_pelanggan.charAt(0);
            let isActiveClass = (chat.id == activeChatId) ? 'active' : '';
            
            let html = `
                <div class="contact-item ${isActiveClass}" data-id="${chat.id}" data-name="${chat.nama_pelanggan}">
                    <div class="c-avatar">${initial}</div>
                    <div class="c-info">
                        <div class="c-top-row">
                            <h6 class="c-name">${chat.nama_pelanggan}</h6>
                            <span class="c-time">${chat.time_display}</span>
                        </div>
                        <p class="c-msg">${chat.last_message || '...'}</p>
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
            let name = item.querySelector('.c-name').textContent.toLowerCase();
            item.style.display = name.includes(keyword) ? 'flex' : 'none';
        });
    });

    // 3. KLIK KONTAK BUKA RUANG CHAT
    contactListDiv.addEventListener('click', function(e) {
        let item = e.target.closest('.contact-item');
        if(!item) return;

        // Set Active State
        contactListDiv.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        activeChatId = item.dataset.id;
        let cName = item.dataset.name;

        // UI Updates
        placeholder.style.display = 'none';
        activeWindow.style.display = 'flex';
        document.getElementById('activeName').textContent = cName;
        document.getElementById('activeAvatar').textContent = cName.charAt(0);
        document.getElementById('activeChatId').value = activeChatId;

        // Mode Mobile: Tampilkan panel chat
        if(window.innerWidth <= 768) {
            pnlChat.classList.add('mobile-active');
        }

        // Load Pesan & Mulai Polling
        loadMessages(activeChatId, true);
        if(pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => { loadMessages(activeChatId, false); }, 4000); // Poll tiap 4 detik
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
                        let wrapClass = msg.is_mine ? 'out' : 'in';
                        
                        let html = `
                            <div class="msg-wrapper ${wrapClass}">
                                <div class="msg-bubble">
                                    ${msg.text}
                                    <div class="msg-time">${msg.time}</div>
                                </div>
                            </div>
                        `;
                        msgArea.insertAdjacentHTML('beforeend', html);
                    });

                    // Scroll ke paling bawah hanya saat pertama kali klik kontak atau jika dikirim paksa
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
        
        let msgInput = document.getElementById('inputMessage');
        let text = msgInput.value.trim();
        let chatId = document.getElementById('activeChatId').value;
        
        if(text === '' || !chatId) return;

        let btn = document.getElementById('btnSendMsg');
        btn.disabled = true;
        btn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i>';

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
                loadMessages(chatId, true); // Panggil pesan terbaru dan scroll ke bawah
                loadChatList(); // Update sidebar (last message)
            }
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="mdi mdi-send"></i>';
            msgInput.focus();
        });
    });

    // 6. TOMBOL BACK MOBILE
    document.getElementById('btnBackMobile').addEventListener('click', function() {
        pnlChat.classList.remove('mobile-active');
        if(pollingInterval) clearInterval(pollingInterval);
        activeChatId = null;
        contactListDiv.querySelectorAll('.contact-item').forEach(el => el.classList.remove('active'));
        
        // Kembalikan ke placeholder
        placeholder.style.display = 'flex';
        activeWindow.style.display = 'none';
        
        // Refresh list untuk jaga-jaga ada pesan masuk saat di luar chat
        loadChatList();
    });

    // Inisialisasi Pertama
    loadChatList();
});
</script>
@endpush