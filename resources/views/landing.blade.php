<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondasikita - Marketplace Bahan Bangunan</title>
    
    {{-- CSS Assets --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.css') }}"> 
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/navbar_style.css') }}"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* === TYPEWRITER EFFECT === */
        .typing-wrapper { display: inline-block; }
        .typing-text { font-weight: bold; color: #fff; border-bottom: 2px solid transparent; }
        .typing-cursor { display: inline-block; width: 3px; background-color: #fff; animation: blink 0.7s infinite; margin-left: 2px; }
        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0; } 100% { opacity: 1; } }

        /* === CHATBOT STYLE === */
        .live-chat-toggle { position: fixed; bottom: 20px; right: 20px; background: #007bff; color: white; border: none; padding: 15px 20px; border-radius: 50px; font-size: 16px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2); z-index: 9999; display: flex; align-items: center; gap: 8px; transition: transform 0.3s; }
        .live-chat-toggle:hover { transform: scale(1.05); }
        .live-chat-window { position: fixed; bottom: 90px; right: 20px; width: 350px; height: 450px; background: white; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; z-index: 9999; border: 1px solid #eee; transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); opacity: 0; transform: translateY(20px) scale(0.95); }
        .live-chat-window.active { display: flex; opacity: 1; transform: translateY(0) scale(1); }
        .live-chat-window.expanded { width: 90% !important; height: 90% !important; bottom: 5% !important; right: 5% !important; border-radius: 15px; z-index: 10000; }
        .chat-header { background: #007bff; color: white; padding: 15px; display: flex; justify-content: space-between; align-items: center; font-weight: bold; }
        .chat-messages { flex: 1; padding: 15px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 10px; }
        .chat-message { max-width: 80%; padding: 10px 14px; border-radius: 10px; font-size: 14px; line-height: 1.4; word-wrap: break-word; position: relative; }
        .chat-message.bot { background: #e9ecef; color: #333; align-self: flex-start; border-bottom-left-radius: 0; padding-bottom: 25px; }
        .chat-message.user { background: #007bff; color: white; align-self: flex-end; border-bottom-right-radius: 0; }
        .chat-message.loading { background: transparent; color: #888; font-style: italic; font-size: 12px; padding: 0; margin-left: 5px; }
        .speak-icon { position: absolute; bottom: 5px; right: 10px; font-size: 12px; color: #888; cursor: pointer; padding: 2px 5px; }
        .chat-input-area { padding: 10px; border-top: 1px solid #eee; background: white; display: flex; align-items: center; gap: 8px; }
        .chat-input-area input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 20px; outline: none; font-size: 14px; }
        .chat-input-area button { background: #007bff; color: white; border: none; width: 35px; height: 35px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; }
        #voice-btn { background: #f8f9fa; border: 1px solid #ccc; color: #555; width: 40px; height: 40px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; }
        #voice-btn.recording { background: #dc3545; color: white; border-color: #dc3545; animation: pulse 1.5s infinite; }
        #voice-call-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%); z-index: 10001; display: none; flex-direction: column; align-items: center; justify-content: center; color: white; }
        .voice-visualizer { width: 80px; height: 80px; border-radius: 50%; background: white; display: flex; align-items: center; justify-content: center; margin-bottom: 30px; transition: all 0.3s; }
        .voice-visualizer.speaking { animation: pulse-blue 1.5s infinite; background: #4facfe; }
        .voice-visualizer.listening { animation: pulse-white 1.5s infinite; background: #ff416c; }
        .voice-btn-hangup { background: #ff416c; color: white; border: none; padding: 12px 25px; border-radius: 30px; font-weight: bold; cursor: pointer; display: flex; gap: 8px; align-items: center; }
        
        /* === STORE CARD STYLE === */
        .store-card { position: relative; overflow: hidden; display: block; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); background: white; transition: transform 0.2s; text-decoration: none; color: inherit; }
        .store-card:hover { transform: translateY(-5px); }
        .store-banner { height: 100px; background-size: cover; background-position: center; position: relative; }
        .store-info { padding: 35px 15px 15px 15px; position: relative; }
        .store-logo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: absolute; bottom: -30px; left: 20px; z-index: 2; background: white; }
        .store-logo-initial { width: 60px; height: 60px; border-radius: 50%; color: #fff; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 22px; text-transform: uppercase; border: 3px solid #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.1); position: absolute; bottom: -30px; left: 20px; z-index: 2; }
        
        /* Animations */
        @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
        @keyframes pulse-white { 0% { box-shadow: 0 0 0 0 rgba(255,255,255,0.7); transform: scale(1); } 70% { box-shadow: 0 0 0 20px rgba(255,255,255,0); transform: scale(1.1); } 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); transform: scale(1); } }
        @keyframes pulse-blue { 0% { box-shadow: 0 0 0 0 rgba(79,172,254,0.7); transform: scale(1); } 70% { box-shadow: 0 0 0 20px rgba(79,172,254,0); transform: scale(1.1); } 100% { box-shadow: 0 0 0 0 rgba(79,172,254,0); transform: scale(1); } }
    </style>
</head>
<body>
    
    {{-- Include Navbar --}}
    @include('partials.navbar')

    {{-- Hero Section --}}
    <section class="hero-banner">
        <div class="container">
            <div class="hero-content">
                <h2>
                    <span class="typing-text"></span><span class="typing-cursor">&nbsp;</span>
                </h2>
                <h3>Temukan semua kebutuhan proyek Anda dari toko-toko terpercaya.</h3>
                <a href="{{ url('pages/produk') }}" class="btn-primary">Jelajahi Produk</a>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            
            {{-- KATEGORI --}}
            <section class="categories">
                <h2 class="section-title"><span>Kategori Populer</span></h2>
                <div class="category-grid">
                    @forelse($categories as $cat)
                        <a href="{{ url('pages/produk?kategori=' . $cat->id) }}" class="category-item">
                            <div class="category-icon">
                                <i class="{{ $cat->icon_class ?? 'fas fa-tools' }}"></i>
                            </div>
                            <p>{{ $cat->nama_kategori }}</p>
                        </a>
                    @empty
                        <p>Kategori kosong.</p>
                    @endforelse
                </div>
            </section>

            {{-- TOKO POPULER --}}
            <section class="featured-stores">
                <div class="section-header">
                    <h2 class="section-title"><span>{{ $tokoSectionTitle }}</span></h2>
                    <a href="{{ url('pages/semua_toko') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="store-grid">
                    @forelse($listToko as $toko)
                        {{-- Logic Gambar Toko (Dipindah ke sini agar aman tanpa ubah Model dulu) --}}
                        @php
                            $bannerPath = 'assets/uploads/banners/' . $toko->banner_toko;
                            $hasBanner = !empty($toko->banner_toko) && file_exists(public_path($bannerPath));
                            $bgStyle = $hasBanner 
                                ? "background-image: url(" . asset($bannerPath) . ");" 
                                : "background-color: " . $toko->color . "; opacity: 0.8;";

                            $logoPath = 'assets/uploads/logos/' . $toko->logo_toko;
                            $hasLogo = !empty($toko->logo_toko) && file_exists(public_path($logoPath));
                        @endphp

                        <a href="{{ url('pages/toko?slug=' . $toko->slug) }}" class="store-card">
                            <div class="store-banner" style="{{ $bgStyle }}">
                                @if($hasLogo)
                                    <img src="{{ asset($logoPath) }}" class="store-logo" alt="Logo">
                                @else
                                    <div class="store-logo-initial" style="background-color: {{ $toko->color }};">
                                        {{ $toko->initials }}
                                    </div>
                                @endif
                            </div>

                            <div class="store-info">
                                <h4>{{ $toko->nama_toko }}</h4>
                                <p><i class="fas fa-map-marker-alt"></i> {{ $toko->kota }}</p>
                                <p class="product-count">{{ $toko->jumlah_produk_aktif }} Produk</p>
                            </div>
                        </a>
                    @empty
                        <p>Belum ada toko tersedia.</p>
                    @endforelse
                </div>
            </section>

            {{-- PRODUK LOKAL (Jika Ada) --}}
            @if(count($listProdukLokal) > 0)
            <section class="products">
                <div class="section-header">
                    <h2 class="section-title"><span>Produk Terlaris di Wilayah Anda</span></h2>
                    <a href="{{ url('pages/produk') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="product-grid">
                    @foreach($listProdukLokal as $p)
                        @php
                            $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                        @endphp
                        <a href="{{ url('pages/detail_produk?id=' . $p->id . '&toko_slug=' . $p->slug_toko) }}" class="product-link">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset($img) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                </div>
                                <div class="product-details">
                                    <h3>{{ Str::limit($p->nama_barang, 40) }}</h3>
                                    <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                    <div class="product-store-info"><i class="fas fa-store-alt"></i> <span>{{ $p->nama_toko }}</span></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- PRODUK NASIONAL --}}
            <section id="nasional-content" class="products">
                <div class="section-header">
                    <h2 class="section-title"><span>Produk Terlaris Nasional</span></h2>
                    <a href="{{ url('pages/produk') }}" class="see-all">Lihat Semua <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="product-grid">
                    @forelse($listProdukNasional as $p)
                        @php
                            $img = !empty($p->gambar_utama) ? 'assets/uploads/products/'.$p->gambar_utama : 'assets/uploads/products/default.jpg';
                        @endphp
                        <a href="{{ url('pages/detail_produk?id=' . $p->id . '&toko_slug=' . $p->slug_toko) }}" class="product-link">
                            <div class="product-card">
                                <div class="product-image">
                                    <img src="{{ asset($img) }}" onerror="this.onerror=null; this.src='{{ asset('assets/uploads/products/default.jpg') }}';">
                                </div>
                                <div class="product-details">
                                    <h3>{{ Str::limit($p->nama_barang, 40) }}</h3>
                                    <p class="price">Rp{{ number_format($p->harga, 0, ',', '.') }}</p>
                                    <div class="product-store-info"><i class="fas fa-store-alt"></i> <span>{{ $p->nama_toko }}</span></div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p>Belum ada produk terlaris.</p>
                    @endforelse
                </div>
            </section>

        </div>
    </main>
    
    {{-- Include Footer --}}
    @include('partials.footer')
    
    {{-- Scripts Bawaan Navbar --}}
    <script src="{{ asset('assets/js/navbar.js') }}"></script>

    {{-- ===================== FITUR CHATBOT POTA (FULL) ===================== --}}
    
    <button id="live-chat-toggle" class="live-chat-toggle" onclick="toggleChat()">
        <i class="fas fa-robot"></i> <span class="chat-toggle-text">Tanya POTA</span>
    </button>
    
    <div id="live-chat-window" class="live-chat-window">
        <div class="chat-header">
            <div style="display:flex; align-items:center; gap:10px;">
                <span id="chat-header-title">POTA (Mandor AI)</span>
            </div>
            <div class="header-controls">
                <button onclick="startVoiceCallMode()" title="Mode Telepon" style="background:none; border:none; color:white; cursor:pointer; margin-right:8px;">
                    <i class="fas fa-phone-volume"></i>
                </button>
                <button onclick="toggleFullScreen()" title="Perbesar" style="background:none; border:none; color:white; cursor:pointer;">
                    <i id="icon-resize" class="fas fa-expand"></i>
                </button>
                <button id="close-chat" class="close-chat-btn" onclick="toggleChat()" style="margin-left:10px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        
        <div class="chat-messages" id="chat-messages">
             {{-- Pesan sambutan mengambil nama user dari Auth::user() --}}
             <div class="chat-message bot">Halo {{ Auth::user()->nama ?? 'Tamu' }}! Saya POTA. Tekan tombol telepon 📞 di atas untuk ngobrol langsung, atau ketik di bawah ya!</div>
        </div>
        
        <div class="chat-input-area">
            <button id="voice-btn" onclick="toggleVoice()" title="Tekan untuk bicara"><i class="fas fa-microphone"></i></button>
            <input type="text" id="chat-input" placeholder="Ketik pesan..." onkeypress="handleEnter(event)">
            <button id="send-chat-btn" onclick="sendMessage()"><i class="fas fa-paper-plane"></i></button>
        </div>

        <div id="voice-call-overlay">
            <div class="voice-status" id="voice-status-text">Menghubungkan...</div>
            <div class="voice-visualizer" id="voice-visualizer"><i class="fas fa-microphone"></i></div>
            <button class="voice-btn-hangup" onclick="endVoiceCallMode()"><i class="fas fa-phone-slash"></i> Tutup</button>
        </div>
    </div>

    {{-- JAVASCRIPT LENGKAP --}}
    <script>
        /* === TYPEWRITER EFFECT === */
        const typingText = document.querySelector(".typing-text");
        const phrases = [
            "Cari Bahan Bangunan?", 
            "Renovasi Rumah Impian?", 
            "Solusi Material Terlengkap", 
            "Harga Terbaik & Terpercaya",
            "Belanja Mudah dari Rumah"
        ];
        let phraseIndex = 0;
        let charIndex = 0;
        let isDeleting = false;
        let typeSpeed = 100;

        function typeEffect() {
            const currentPhrase = phrases[phraseIndex];
            if (isDeleting) {
                typingText.textContent = currentPhrase.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 50; 
            } else {
                typingText.textContent = currentPhrase.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 100;
            }
            if (!isDeleting && charIndex === currentPhrase.length) {
                isDeleting = true;
                typeSpeed = 2000; 
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typeSpeed = 500;
            }
            setTimeout(typeEffect, typeSpeed);
        }
        document.addEventListener("DOMContentLoaded", typeEffect);

        /* === CHATBOT LOGIC (POTA) === */
        const chatWindow = document.getElementById('live-chat-window');
        const messagesContainer = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const toggleBtn = document.getElementById('live-chat-toggle');
        const callOverlay = document.getElementById('voice-call-overlay');
        const voiceStatus = document.getElementById('voice-status-text');
        const voiceVisualizer = document.getElementById('voice-visualizer');
        
        let chatHistory = []; 
        let isCallMode = false;
        let recognition = null;
        let voices = []; 

        function loadVoices() { voices = window.speechSynthesis.getVoices(); }
        window.speechSynthesis.onvoiceschanged = loadVoices;

        if (window.SpeechRecognition || window.webkitSpeechRecognition) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.lang = 'id-ID';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            recognition.onresult = (event) => {
                const text = event.results[0][0].transcript;
                if(isCallMode) {
                    voiceStatus.innerText = "Memproses: " + text;
                    voiceVisualizer.className = "voice-visualizer";
                    voiceVisualizer.innerHTML = '<i class="fas fa-brain"></i>';
                    sendMessage(text);
                } else {
                    chatInput.value = text;
                    document.getElementById('voice-btn').classList.remove('recording');
                }
            };
            recognition.onerror = (event) => {
                document.getElementById('voice-btn').classList.remove('recording');
                if(isCallMode) {
                    if(event.error === 'no-speech') speakText("Halo? Ada orang?", true);
                    else { voiceStatus.innerText = "Gagal mendengar."; setTimeout(startListening, 2000); }
                }
            };
            recognition.onend = () => { 
                if(!isCallMode) document.getElementById('voice-btn').classList.remove('recording'); 
            };
        }

        function toggleChat() {
            chatWindow.classList.toggle('active');
            if(!chatWindow.classList.contains('active')) {
                toggleBtn.style.display = 'flex';
                endVoiceCallMode();
            } else {
                if(window.innerWidth < 768) toggleBtn.style.display = 'none';
                chatInput.focus();
            }
        }

        function toggleFullScreen() {
            chatWindow.classList.toggle('expanded');
            const icon = document.getElementById('icon-resize');
            icon.className = chatWindow.classList.contains('expanded') ? 'fas fa-compress' : 'fas fa-expand';
        }

        function handleEnter(e) { if(e.key === 'Enter') sendMessage(); }

        function toggleVoice() {
            if(!recognition) { alert("Browser tidak support suara."); return; }
            const btn = document.getElementById('voice-btn');
            if(btn.classList.contains('recording')) {
                recognition.stop();
                btn.classList.remove('recording');
            } else {
                recognition.start();
                btn.classList.add('recording');
            }
        }

        function startVoiceCallMode() {
            if(!recognition) { alert("Browser tidak support suara."); return; }
            isCallMode = true;
            callOverlay.style.display = 'flex';
            chatWindow.classList.add('expanded');
            voiceStatus.innerText = "POTA Bicara...";
            voiceVisualizer.className = "voice-visualizer speaking";
            speakText("Halo! POTA siap mendengarkan.", true);
        }

        function endVoiceCallMode() {
            isCallMode = false;
            callOverlay.style.display = 'none';
            window.speechSynthesis.cancel();
            if(recognition) recognition.stop();
            chatWindow.classList.remove('expanded');
        }

        function startListening() {
            if(!isCallMode) return;
            try {
                recognition.start();
                voiceStatus.innerText = "Silakan bicara...";
                voiceVisualizer.className = "voice-visualizer listening";
                voiceVisualizer.innerHTML = '<i class="fas fa-microphone"></i>';
            } catch(e) { console.log("Mic busy"); }
        }

        function appendMessage(text, sender) {
            const div = document.createElement('div');
            div.classList.add('chat-message', sender);
            if(sender === 'bot') {
                div.innerHTML = text;
                const cleanText = text.replace(/"/g, "'").replace(/\n/g, " ").replace(/<[^>]*>?/gm, '');
                div.innerHTML += `<i class="fas fa-volume-up speak-icon" onclick="speakText('${cleanText}')"></i>`;
            } else {
                div.innerText = text;
            }
            messagesContainer.appendChild(div);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        async function sendMessage(textOverride = null) {
            const text = textOverride || chatInput.value.trim();
            if(!text) return;

            if(!textOverride) { appendMessage(text, 'user'); chatInput.value = ''; }
            chatHistory.push({sender:'user', text:text});
            if(chatHistory.length > 6) chatHistory.shift();

            if(!isCallMode) {
                const loadDiv = document.createElement('div');
                loadDiv.id = 'loading-indicator';
                loadDiv.className = 'chat-message loading';
                loadDiv.innerText = 'POTA mengetik...';
                messagesContainer.appendChild(loadDiv);
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }

            try {
                // PENTING: Pastikan Anda sudah membuat Route API untuk Chatbot di routes/api.php
                // atau gunakan URL sementara ini
                const res = await fetch('{{ url("/api/chat") }}', { 
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Token keamanan Laravel
                    },
                    body: JSON.stringify({message: text, history: chatHistory})
                });
                
                if (!res.ok) throw new Error("HTTP Error: " + res.status);
                const data = await res.json();
                
                if(!isCallMode) {
                    const loader = document.getElementById('loading-indicator');
                    if(loader) loader.remove();
                }
                
                appendMessage(data.reply, 'bot');
                let cleanText = data.reply.replace(/<[^>]*>?/gm, '');
                chatHistory.push({sender:'bot', text: cleanText});
                if(isCallMode) speakText(data.reply, true);

            } catch(e) {
                const loader = document.getElementById('loading-indicator');
                if(loader) loader.remove();
                if(isCallMode) {
                    voiceStatus.innerText = "Error koneksi...";
                    speakText("Maaf, koneksi terputus.", false);
                } else {
                    appendMessage("Maaf, gagal terhubung ke server.", 'bot');
                }
            }
        }

        function speakText(text, autoListen = false) {
            window.speechSynthesis.cancel();
            const clean = text.replace(/<[^>]*>?/gm, '').replace(/[*_#]/g, '');
            const u = new SpeechSynthesisUtterance(clean);
            u.lang = 'id-ID';
            u.pitch = 0.8; 
            u.rate = 1.1;
            
            if (voices.length === 0) loadVoices();
            const indoVoice = voices.find(v => v.lang === 'id-ID' && v.name.includes('Google')); 
            if (indoVoice) u.voice = indoVoice;

            u.onstart = () => { 
                if(isCallMode) { 
                    voiceVisualizer.className="voice-visualizer speaking"; 
                    voiceVisualizer.innerHTML='<i class="fas fa-volume-up"></i>'; 
                    voiceStatus.innerText = "POTA Menjawab...";
                }
            };
            
            u.onend = () => { 
                if(isCallMode) {
                    voiceVisualizer.className="voice-visualizer";
                    if(autoListen) setTimeout(startListening, 500); 
                }
            };
            window.speechSynthesis.speak(u);
        }
    </script>

</body>
</html>