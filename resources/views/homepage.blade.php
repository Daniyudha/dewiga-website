@extends('layouts.frontend')

@section('title', __('messages.seo.home_title'))
@section('meta_description', __('messages.seo.home_desc'))
@section('og_title', __('messages.seo.home_title'))
@section('og_description', __('messages.seo.home_desc'))
@section('twitter_title', __('messages.seo.home_title'))
@section('twitter_description', __('messages.seo.home_desc'))

@section('content')
    {{-- SECTION 1: HERO SECTION WITH SLIDER --}}
    @include('partials.home-hero')

    {{-- SECTION 2: MENGENAL LEBIH DEKAT (VALUE PROPOSITION) --}}
    <section id="profil" class="py-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-5 relative flex justify-center lg:justify-start">
                    <div class="relative w-[80%] aspect-[4/5] rounded-[2rem] overflow-hidden shadow-2xl z-10 border-4 border-white">
                        <img src="{{ asset('frontend/assets/img/value-img.jpg') }}" 
                             class="w-full h-full object-cover" 
                             alt="Agrowisata Salak Gading">
                        <div class="absolute bottom-4 left-4 bg-[#053d2c]/80 backdrop-blur-sm text-white px-4 py-1 rounded-full text-xs font-semibold">
                            {{ __('messages.home_value.badge_tracking') }}
                        </div>
                    </div>
                    <div class="absolute right-0 bottom-[-20px] w-[55%] aspect-square rounded-[2rem] overflow-hidden shadow-2xl z-20 border-8 border-[#e8fbf3]">
                        <img src="{{ asset('frontend/assets/img/value-img-2.jpg') }}" 
                             class="w-full h-full object-cover" 
                             alt="Membajak Sawah Tradisional">
                             <div class="absolute top-4 left-4 bg-[#053d2c]/80 backdrop-blur-sm text-white px-4 py-1 rounded-full text-xs font-semibold">
                            {{ __('messages.home_value.badge_wayang') }}
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-7">
                    <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">{{ __('messages.value.subtitle') }}</span>
                    <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c] leading-tight mb-6">
                        {{ __('messages.value.title') }}
                    </h2>
                    
                    <p class="text-neutral-700 leading-relaxed mb-4">
                        {{ __('messages.value.description') }}
                    </p>
                    <p class="text-neutral-700 leading-relaxed mb-8">
                        {{ __('messages.home_value.extra_desc') }}
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-[#00a877]/10 text-[#00a877] rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                            </span>
                            <div>
                                <h5 class="font-bold text-[#053d2c] text-sm">{{ __('messages.home_value.cards.edu_tourism') }}</h5>
                                <p class="text-xs text-neutral-500">{{ __('messages.value.cards.edu_tourism.desc') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-[#00a877]/10 text-[#00a877] rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                            </span>
                            <div>
                                <h5 class="font-bold text-[#053d2c] text-sm">{{ __('messages.home_value.cards.live_in') }}</h5>
                                <p class="text-xs text-neutral-500">{{ __('messages.value.cards.live_in.desc') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-[#00a877]/10 text-[#00a877] rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                            </span>
                            <div>
                                <h5 class="font-bold text-[#053d2c] text-sm">{{ __('messages.home_value.cards.agriculture') }}</h5>
                                <p class="text-xs text-neutral-500">{{ __('messages.value.cards.agriculture.desc') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="p-2 bg-[#00a877]/10 text-[#00a877] rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" /></svg>
                            </span>
                            <div>
                                <h5 class="font-bold text-[#053d2c] text-sm">{{ __('messages.home_value.cards.local_culture') }}</h5>
                                <p class="text-xs text-neutral-500">{{ __('messages.value.cards.local_culture.desc') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-6 py-3 rounded-full font-medium text-sm transition duration-300">
                            {{ __('messages.value.learn_more') }}
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 3: VIDEO PROFIL DESA --}}
    @include('partials.home-video')

    {{-- SECTION 4: PAKET WISATA (POPULAR PACKAGES SWIPER) --}}
    @include('partials.home-popular')

    {{-- SECTION 5: RAGAM KEGIATAN SERU (FEATURED ACTIVITIES) --}}
    @include('partials.home-featured')

    {{-- SECTION 6: TESTIMONIALS (SWIPER SLIDER) --}}
    @include('partials.home-testimonials')

    {{-- SECTION 7: PARTNER LOGOS --}}
    @if(isset($partnerLogos) && $partnerLogos->count() > 0)
    <section class="py-16 bg-white border-t border-neutral-100">
        <div class="container mx-auto px-6 max-w-5xl">
            <div class="text-center mb-10">
                <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-2">@lang('messages.logos.subtitle')</span>
                <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#053d2c]">@lang('messages.logos.title')</h2>
            </div>

            <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
                @foreach ($partnerLogos as $logo)
                    @if($logo->url)
                        <a href="{{ $logo->url }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="hover:opacity-80 hover:grayscale-0 transition-all duration-300"
                           title="{{ $logo->name }}">
                            <div class="h-14 md:h-16 w-32 flex items-center justify-center">
                                <img class="lazy_img h-full w-full object-contain"
                                     data-src="{{ asset('storage/' . $logo->image) }}"
                                     alt="{{ $logo->name }}" />
                            </div>
                        </a>
                    @else
                        <div class="hover:opacity-80 hover:grayscale-0 transition-all duration-300"
                             title="{{ $logo->name }}">
                            <div class="h-14 md:h-16 w-32 flex items-center justify-center">
                                <img class="lazy_img h-full w-full object-contain"
                                     data-src="{{ asset('storage/' . $logo->image) }}"
                                     alt="{{ $logo->name }}" />
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- SECTION 8: BLOG SECTION --}}
    @include('partials.home-blog')

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
            <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center mx-auto mb-8 border border-white/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#00c887]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
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

    {{-- SECTION 10: FAQ (PERTANYAAN YANG SERING DIAJUKAN) --}}
    <section id="faq" class="py-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6 max-w-4xl">
            
            <div class="text-center mb-16">
                <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-3">{{ __('messages.faq.subtitle') }}</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">
                    {{ __('messages.faq.title') }}
                </h2>
            </div>

            <div class="space-y-4">
                <details class="group bg-white p-6 rounded-2xl border border-neutral-100 shadow-sm [&_summary::-webkit-details-marker]:hidden" open>
                    <summary class="flex items-center justify-between cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-3 text-[#053d2c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00a877] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-semibold text-base md:text-lg">{{ __('messages.faq.q1') }}</h3>
                        </div>
                        <span class="ml-1.5 p-1.5 bg-[#e8fbf3] text-[#00a877] rounded-full transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-neutral-600 pl-9 text-sm leading-relaxed">
                        {{ __('messages.faq.a1') }}
                    </p>
                </details>
                <details class="group bg-white p-6 rounded-2xl border border-neutral-100 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-3 text-[#053d2c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00a877] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-semibold text-base md:text-lg">{{ __('messages.faq.q2') }}</h3>
                        </div>
                        <span class="ml-1.5 p-1.5 bg-[#e8fbf3] text-[#00a877] rounded-full transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-neutral-600 pl-9 text-sm leading-relaxed">
                        {{ __('messages.faq.a2') }}
                    </p>
                </details>
                <details class="group bg-white p-6 rounded-2xl border border-neutral-100 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-3 text-[#053d2c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00a877] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-semibold text-base md:text-lg">{{ __('messages.faq.q3') }}</h3>
                        </div>
                        <span class="ml-1.5 p-1.5 bg-[#e8fbf3] text-[#00a877] rounded-full transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-neutral-600 pl-9 text-sm leading-relaxed">
                        {{ __('messages.faq.a3') }}
                    </p>
                </details>
                <details class="group bg-white p-6 rounded-2xl border border-neutral-100 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-3 text-[#053d2c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00a877] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-semibold text-base md:text-lg">{{ __('messages.faq.q4') }}</h3>
                        </div>
                        <span class="ml-1.5 p-1.5 bg-[#e8fbf3] text-[#00a877] rounded-full transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-neutral-600 pl-9 text-sm leading-relaxed">
                        {{ __('messages.faq.a4') }}
                    </p>
                </details>
                <details class="group bg-white p-6 rounded-2xl border border-neutral-100 shadow-sm [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex items-center justify-between cursor-pointer focus:outline-none">
                        <div class="flex items-center gap-3 text-[#053d2c]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-[#00a877] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <h3 class="font-semibold text-base md:text-lg">{{ __('messages.faq.q5') }}</h3>
                        </div>
                        <span class="ml-1.5 p-1.5 bg-[#e8fbf3] text-[#00a877] rounded-full transition duration-300 group-open:-rotate-180">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        </span>
                    </summary>
                    <p class="mt-4 text-neutral-600 pl-9 text-sm leading-relaxed">
                        {{ __('messages.faq.a5') }}
                    </p>
                </details>
            </div>
        </div>
    </section>
@endsection
