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
