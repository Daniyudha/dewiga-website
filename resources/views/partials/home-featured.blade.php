{{-- FEATURED ACTIVITIES SECTION WITH IMAGES --}}
<section id="aktivitas" class="py-24 bg-white">
    <div class="container mx-auto px-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-end gap-6 mb-12">
            <div>
                <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">{{ __('messages.featured.subtitle') }}</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">{{ __('messages.featured.title') }}</h2>
            </div>
        </div>

        @php
            $featuredActivities = \App\Models\Activity::where('is_featured', true)->orderBy('order')->take(6)->get();
        @endphp
        @if($featuredActivities->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($featuredActivities as $i => $act)
            <a href="{{ route('activities.show', $act->slug) }}" class="bg-white border border-neutral-100 rounded-3xl overflow-hidden hover:shadow-xl transition-all duration-300 group flex flex-col h-full cursor-pointer">
                <div class="relative h-44 overflow-hidden">
                    <img src="{{ $act->image ? asset('storage/' . $act->image) : asset('frontend/assets/img/hero2.jpg') }}" alt="{{ $act->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                    <div class="absolute bottom-3 left-4 w-10 h-10 bg-[#00a877] text-white flex items-center justify-center rounded-xl shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                    <div class="absolute top-3 right-3 w-8 h-8 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-[#053d2c] opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-[#00a877] hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                    </div>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-serif text-lg font-bold text-[#053d2c] mb-2 group-hover:text-[#00a877] transition-colors">{{ $act->title }}</h3>
                    <p class="text-neutral-600 text-sm font-light leading-relaxed flex-1">{{ $act->description }}</p>
                </div>
            </a>
            @endforeach
        </div>
        @endif

        <div class="text-center mt-12">
            <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-semibold transition duration-300">
                {{ __('messages.featured.cta') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>
</section>
</write_to_file>