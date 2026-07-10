{{-- SECTION 9: AI CALL TO ACTION (CTA) --}}
<section id="asisten-ai" class="py-24 bg-[#053d2c] text-white text-center relative overflow-hidden">
    <div class="absolute inset-0 opacity-5">
        <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <defs>
                <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                    <path d="M 40 0 L 0 0 0 40" fill="none" stroke="white" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <div class="container mx-auto px-6 relative z-10">
        <div class="w-20 h-20 rounded-full overflow-hidden mx-auto mb-8 border-2 border-[#00a877]/50 shadow-lg shadow-[#00a877]/20">
            <img src="{{ asset('frontend/assets/img/bot-avatar.png') }}" alt="Mas Pandu" class="w-full h-full object-cover">
        </div>

        <h2 class="font-serif text-white text-3xl md:text-5xl font-bold mb-6 max-w-3xl mx-auto leading-tight">
            {{ __('messages.ai_cta.title') }}
        </h2>
        
        <p class="text-neutral-300 text-sm md:text-base max-w-2xl mx-auto mb-10 leading-relaxed font-light">
            {{ __('messages.ai_cta.desc') }}
        </p>

        <button id="ai-chat-toggle-2" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:-translate-y-0.5">
            <i class="bx bx-chat"></i> {{ __('messages.ai_cta.button') }}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </div>
</section>
