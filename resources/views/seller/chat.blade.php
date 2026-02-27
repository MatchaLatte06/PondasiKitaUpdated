@extends('layouts.seller')

@section('title', 'Manajemen Chat')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/seller_chat.css') }}">
@endpush

@section('content')
<div class="page-header mb-4">
    <h3 class="page-title d-flex align-items-center m-0">
        <div class="page-title-icon-mono me-3">
            <i class="mdi mdi-forum"></i>
        </div> 
        <div class="d-flex align-items-center" style="font-size: 1.6rem;">
            <a href="{{ route('seller.dashboard') }}" class="header-path-link">Dashboard</a>
            <i class="mdi mdi-chevron-right header-path-separator"></i>
            <span class="header-path-current">Manajemen Chat</span>
        </div>
    </h3>
</div>

<div class="card shadow-sm border-0 bg-transparent">
    <div class="card-body p-0">
        
        <div class="chat-container">
            {{-- Panel Kiri: Daftar Chat --}}
            <div class="chat-list-pane" id="chat-list-container">
                <div class="p-4 text-center text-muted">
                    <div class="spinner-border spinner-border-sm mb-2" role="status"></div>
                    <p>Memuat pesan...</p>
                </div>
            </div>
            
            {{-- Panel Kanan: Ruang Chat --}}
            <div class="chat-window-pane">
                
                {{-- Tampilan saat belum ada chat yang dipilih --}}
                <div id="chat-window-placeholder" class="chat-placeholder">
                    <i class="mdi mdi-message-text-outline"></i>
                    <h5>Pilih Percakapan</h5>
                    <p>Klik salah satu pelanggan di samping untuk mulai membalas.</p>
                </div>

                {{-- Tampilan Jendela Chat Aktif --}}
                <div id="chat-window-main" class="d-none h-100 d-flex flex-column">
                    <div class="chat-header">
                        <i class="mdi mdi-account-circle text-muted fs-4"></i>
                        <span id="chat-window-header">Nama Pelanggan</span>
                    </div>
                    
                    <div class="message-container" id="message-container">
                        </div>
                    
                    <form class="chat-input-form" id="message-form">
                        @csrf
                        <input type="hidden" name="chat_id" id="active_chat_id">
                        <input type="text" name="message_text" class="form-control" placeholder="Ketik pesan balasan di sini..." required autocomplete="off">
                        <button type="submit" class="btn-mono"><i class="mdi mdi-send"></i></button>
                    </form>
                </div>

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
    const currentUserId = {{ Auth::id() }};

    // Fungsi untuk memuat daftar chat
    function loadChatList() {
        fetch("{{ route('seller.service.chat.list') }}")
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('chat-list-container');
                container.innerHTML = '';
                
                if (data.status === 'success' && data.data.length > 0) {
                    data.data.forEach(chat => {
                        let initial = chat.nama_pelanggan.charAt(0).toUpperCase();
                        let chatItem = `
                            <div class="chat-list-item" data-chat-id="${chat.id}" data-customer-name="${chat.nama_pelanggan}">
                                <div class="chat-avatar">${initial}</div>
                                <div style="flex-grow:1; min-width:0;">
                                    <h6>${chat.nama_pelanggan}</h6>
                                    <p>${chat.last_message || 'Tidak ada pesan'}</p>
                                </div>
                            </div>`;
                        container.insertAdjacentHTML('beforeend', chatItem);
                    });
                } else {
                    container.innerHTML = `<div class="p-4 text-center text-muted">Belum ada percakapan.</div>`;
                }
            })
            .catch(err => console.error('Error loading chats:', err));
    }

    // Fungsi memuat isi pesan
    function loadMessages(chatId) {
        if (!chatId) return;
        
        let url = "{{ route('seller.service.chat.messages', ':id') }}".replace(':id', chatId);
        
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('message-container');
                container.innerHTML = '';
                
                if (data.status === 'success') {
                    data.data.forEach(msg => {
                        let msgClass = msg.sender_id == currentUserId ? 'seller' : 'customer';
                        let msgHtml = `<div class="message ${msgClass}">${msg.message_text}</div>`;
                        container.insertAdjacentHTML('afterbegin', msgHtml); // prepend
                    });
                }
            });
    }

    // Event Delegation untuk klik daftar chat (Vanilla JS)
    document.getElementById('chat-list-container').addEventListener('click', function(e) {
        let item = e.target.closest('.chat-list-item');
        if (!item) return;

        activeChatId = item.getAttribute('data-chat-id');
        const customerName = item.getAttribute('data-customer-name');

        // Ganti class active
        document.querySelectorAll('.chat-list-item').forEach(el => el.classList.remove('active'));
        item.classList.add('active');

        // Ubah Tampilan UI Kanan
        document.getElementById('chat-window-placeholder').classList.add('d-none');
        document.getElementById('chat-window-main').classList.remove('d-none');
        document.getElementById('chat-window-header').textContent = customerName;
        document.getElementById('active_chat_id').value = activeChatId;
        
        loadMessages(activeChatId);

        // Mulai Polling (Setiap 5 detik)
        if(pollingInterval) clearInterval(pollingInterval);
        pollingInterval = setInterval(() => loadMessages(activeChatId), 5000);
    });

    // Event Submit Form Kirim Pesan
    document.getElementById('message-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        let formData = new FormData(this);
        
        fetch("{{ route('seller.service.chat.send') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                this.querySelector('input[name="message_text"]').value = '';
                loadMessages(activeChatId); // Muat ulang pesan seketika
            }
        });
    });

    // Panggilan pertama saat halaman diload
    loadChatList();
});
</script>
@endpush