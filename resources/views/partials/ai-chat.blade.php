{{-- 
=======================================
 AI CHAT ASSISTANT — HTML STRUCTURE
=======================================
 Dependencies: partials/ai-chat-data.blade.php (must be loaded before)
=======================================
--}}

{{-- Overlay --}}
<div id="ai-chat-overlay" class="fixed inset-0 bg-black/40 z-[9998] hidden transition-opacity duration-300"></div>

{{-- Chat Panel (slide from right) --}}
<div id="ai-chat-panel" class="fixed top-0 right-0 z-[9999] h-screen w-full sm:w-3/4 lg:w-1/2 xl:1/3 bg-white shadow-2xl flex flex-col translate-x-full transition-transform duration-300 ease-out">
    
    {{-- Chat Header --}}
    <div class="flex items-center justify-between px-6 py-5 bg-[#053d2c] text-white shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-16 h-16 bg-[#00a877]/20 rounded-xl flex items-center justify-center">
                {{-- <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-[#00c887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg> --}}
                <img src="{{ asset('frontend/assets/img/bot-avatar.png') }}" alt="AI Avatar" srcset="" class="w-full h-full object-cover rounded-md">
            </div>
            <div>
                <h3 class="font-semibold text-sm text-white">Mas Pandu</h3>
                <p class="text-[10px] text-[#00c887]">Asisten AI Desa Wisata Gabugan</p>
            </div>
        </div>
        <button id="ai-chat-close" class="w-9 h-9 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
        </button>
    </div>

    {{-- Chat Messages --}}
    <div id="ai-chat-messages" class="flex-1 overflow-y-auto p-6 space-y-4 bg-[#f8fdfb]">
        {{-- Welcome message injected by JS --}}
    </div>

    {{-- Typing Indicator --}}
    <div id="ai-typing" class="hidden px-6 py-3 bg-[#f8fdfb] shrink-0">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-[#00a877] rounded-lg flex items-center justify-center text-white text-xs font-bold shrink-0">MP</div>
            <div class="bg-white border border-neutral-100 rounded-2xl rounded-tl-none px-4 py-3 text-sm text-neutral-500 shadow-sm flex items-center gap-1">
                <span class="rounded-full inline-block">Mas Pandu Mengetik</span>
                <span class="typing-dot w-2 h-2 bg-[#00a877] rounded-full inline-block animate-bounce" style="animation-delay:0ms"></span>
                <span class="typing-dot w-2 h-2 bg-[#00a877] rounded-full inline-block animate-bounce" style="animation-delay:150ms"></span>
                <span class="typing-dot w-2 h-2 bg-[#00a877] rounded-full inline-block animate-bounce" style="animation-delay:300ms"></span>
            </div>
        </div>
    </div>

    {{-- Quick Replies --}}
    <div class="px-6 py-3 border-t border-neutral-100 bg-white shrink-0">
        <div class="flex flex-wrap gap-2">
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">📋 Cara Pesan Paket</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🌿 Info Paket Wisata</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">💰 Harga & Biaya</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">📍 Lokasi & Akses</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🏠 Info Homestay</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🎯 Aktivitas Seru</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🍲 Kuliner Khas</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🎨 Belajar Batik</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🎵 Gamelan Jawa</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">🌊 River Trekking</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">👥 Rombongan</button>
            <button class="quick-chat-btn text-xs bg-[#e8fbf3] hover:bg-[#d0f6e5] text-[#00a877] px-3 py-1.5 rounded-full font-medium transition">💡 Tips Persiapan</button>
        </div>
    </div>

    {{-- Chat Input --}}
    <div class="p-4 border-t border-neutral-100 bg-white shrink-0">
        <form id="ai-chat-form" class="flex items-center gap-2">
            <input id="ai-chat-input" type="text" placeholder="Ketik pesan..." autocomplete="off" class="flex-1 border border-neutral-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-[#00a877] transition">
            <button type="submit" class="w-11 h-11 bg-[#00a877] hover:bg-[#009065] text-white rounded-xl flex items-center justify-center transition shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 rotate-90" viewBox="0 0 20 20" fill="currentColor"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" /></svg>
            </button>
        </form>
    </div>
</div>

{{-- Floating Button --}}
<button id="ai-chat-toggle" class="fixed bottom-6 right-6 z-[100] w-14 h-14 bg-[#00a877] hover:bg-[#009065] text-white rounded-full shadow-lg flex items-center justify-center transition duration-300 hover:scale-105 hover:shadow-xl">
    <svg id="chat-icon-open" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
    </svg>
    <svg id="chat-icon-close" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 hidden" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
</button>