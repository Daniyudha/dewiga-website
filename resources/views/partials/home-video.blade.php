{{-- VIDEO PROFIL DESA --}}
<section class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="text-center mb-12">
            <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-3">{{ __('messages.video.subtitle') }}</span>
            <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">{{ __('messages.video.title') }}</h2>
            <p class="text-neutral-500 text-sm mt-2 max-w-xl mx-auto">{{ __('messages.video.desc') }}</p>
        </div>
        <div class="relative max-w-5xl mx-auto">
            {{-- Decorative elements --}}
            <div class="absolute -top-4 -left-4 w-24 h-24 bg-[#e8fbf3] rounded-3xl -z-10"></div>
            <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-[#e8fbf3] rounded-3xl -z-10"></div>
            
            {{-- Video container with fancy border --}}
            <div class="relative rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white ring-2 ring-[#00a877]/20 aspect-video group">
                {{-- Play button overlay --}}
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/10 transition-colors duration-300 z-10 pointer-events-none"></div>
                
                <iframe class="youtube-video w-full h-full relative z-0"
                        src="https://www.youtube.com/embed/wqQGYi1-JdA?si=NnuW_wZO2dX2JX3z"
                        title="YouTube video player"
                        frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                        loading="lazy"></iframe>
            </div>
            
            {{-- Caption --}}
            <p class="text-center text-neutral-400 text-xs mt-4">
                <i class="bx bx-play-circle align-middle mr-1"></i> 
                {{ __('messages.video.caption') }}
            </p>
        </div>
    </div>
</section>
