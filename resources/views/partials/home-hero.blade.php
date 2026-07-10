{{-- HERO SECTION WITH SLIDER --}}
<section class="relative bg-neutral-900 overflow-hidden min-h-[100vh] flex items-center pt-24 pb-16" id="hero">
    {{-- Slider Backgrounds --}}
    <div class="absolute inset-0 z-0">
        @if(isset($heroSetting) && $heroSetting && $heroSetting->slides->count() > 0)
            @foreach($heroSetting->slides as $index => $slide)
                <div class="hero__slide absolute inset-0 transition-opacity duration-1000 ease-out {{ $index === 0 ? 'hero__slide--active' : 'opacity-0' }}">
                    <img src="{{ $slide->image_url }}" 
                         class="w-full h-full object-cover" 
                         alt="{{ $slide->alt_text }}">
                </div>
            @endforeach
        @else
            {{-- Fallback default slides --}}
            <div class="hero__slide hero__slide--active absolute inset-0 transition-opacity duration-1000 ease-out">
                <img src="{{ asset('frontend/assets/img/hero1.jpg') }}" 
                     class="w-full h-full object-cover" 
                     alt="Sawah Desa Wisata Gabugan">
            </div>
            <div class="hero__slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-0">
                <img src="{{ asset('frontend/assets/img/hero2.jpg') }}" 
                     class="w-full h-full object-cover" 
                     alt="Gabugan Village">
            </div>
            <div class="hero__slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-0">
                <img src="{{ asset('frontend/assets/img/hero3.jpg') }}" 
                     class="w-full h-full object-cover" 
                     alt="Gabugan Activities">
            </div>
            <div class="hero__slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-0">
                <img src="{{ asset('frontend/assets/img/hero4.jpg') }}" 
                     class="w-full h-full object-cover" 
                     alt="Gabugan Landscape">
            </div>
            <div class="hero__slide absolute inset-0 transition-opacity duration-1000 ease-out opacity-0">
                <img src="{{ asset('frontend/assets/img/hero5.jpg') }}" 
                     class="w-full h-full object-cover" 
                     alt="Gabugan Culture">
            </div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
    </div>

    <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
        <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-6 mx-auto">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#00c887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
            {{ __('messages.hero.subtitle') }}
        </div>

        <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold tracking-tight mb-6 max-w-5xl mx-auto leading-tight text-white">
            {{ __('messages.hero.title') }} <br>
            <span class="text-[#00c887] italic font-normal">{{ __('messages.hero.subtitle') }}</span>
        </h1>

        <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto mb-10 leading-relaxed font-light">
            {{ __('messages.hero.description') }}
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-16">
            <a href="#paket" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-medium transition duration-300 transform hover:-translate-y-0.5">
                {{ __('messages.hero.cta_packages') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/20 px-8 py-4 rounded-full font-medium transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                {{ __('messages.hero.cta_contact') }}
            </a>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-5xl mx-auto text-left">
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-2 md:p-5 rounded-2xl flex items-center gap-3">
                <div class="p-2 bg-[#00a877]/20 rounded-lg text-[#00c887] h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-xs md:text-sm text-white">{{ __('messages.hero_highlights.location') }}</h4>
                    <p class="text-xs text-neutral-400 font-light mt-0.5">{{ __('messages.hero_highlights.location_desc') }}</p>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-2 md:p-5 rounded-2xl flex items-center gap-3">
                <div class="p-2 bg-[#00a877]/20 rounded-lg text-[#00c887] h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-xs md:text-sm text-white">{{ __('messages.hero_highlights.homestay') }}</h4>
                    <p class="text-xs text-neutral-400 font-light mt-0.5">{{ __('messages.hero_highlights.homestay_desc') }}</p>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-2 md:p-5 rounded-2xl flex items-center gap-3">
                <div class="p-2 bg-[#00a877]/20 rounded-lg text-[#00c887] h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707m2.828-9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-xs md:text-sm text-white">{{ __('messages.hero_highlights.salak') }}</h4>
                    <p class="text-xs text-neutral-400 font-light mt-0.5">{{ __('messages.hero_highlights.salak_desc') }}</p>
                </div>
            </div>
            <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-2 md:p-5 rounded-2xl flex items-center gap-3">
                <div class="p-2 bg-[#00a877]/20 rounded-lg text-[#00c887] h-full flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" /></svg>
                </div>
                <div>
                    <h4 class="font-semibold text-xs md:text-sm text-white">{{ __('messages.hero_highlights.guide') }}</h4>
                    <p class="text-xs text-neutral-400 font-light mt-0.5">{{ __('messages.hero_highlights.guide_desc') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Pagination Dots --}}
    @php
        $slideCount = (isset($heroSetting) && $heroSetting && $heroSetting->slides->count() > 0) ? $heroSetting->slides->count() : 5;
    @endphp
    <div class="hero__pagination absolute bottom-20 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
        @for($i = 0; $i < $slideCount; $i++)
        <button type="button" class="hero__dot w-2.5 h-2.5 rounded-full {{ $i === 0 ? 'bg-white' : 'bg-white/50' }} transition-all duration-500 ease-out" data-index="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endfor
    </div>

    <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden line-height-0">
        <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[40px] md:h-[60px] text-[#e8fbf3] fill-current">
            <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
        </svg>
    </div>
</section>
