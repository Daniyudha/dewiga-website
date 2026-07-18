{{-- POPULAR PACKAGES SWIPER SLIDER --}}
<section id="paket" class="py-24 bg-[#f8fdfb] overflow-hidden">
    <div class="container mx-auto px-6">

        {{-- Header --}}
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-14">
            <div class="text-center lg:text-left">
                <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">{{ __('messages.popular.subtitle') }}</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">{{ __('messages.popular.title') }}</h2>
                <p class="text-neutral-600 max-w-xl mt-3 text-sm">
                    {{ __('messages.home_value.popular_desc') }}
                </p>
            </div>
            <a href="{{ route('travel_package.index') }}" class="hidden lg:inline-flex items-center gap-2 bg-[#053d2c] hover:bg-[#0a4d36] text-white px-6 py-3 rounded-full text-sm font-medium transition">
                {{ __('messages.popular.see_all') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>

        {{-- Swiper --}}
        @if($travel_packages && $travel_packages->count() > 0)
        <div class="swiper packages-swiper !overflow-visible">
            <div class="swiper-wrapper">
                @foreach ($travel_packages as $package)
                <div class="swiper-slide !h-auto">
                    <div class="bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-xl transition-all duration-300 group flex flex-col h-full">
                        <a href="{{ route('travel_package.show', $package->slug ?? $package->id) }}" class="block">
                            <div class="relative aspect-[4/3] overflow-hidden">
                                @if($package->galleries->count() > 0)
                                    <img src="{{ asset('storage/' . $package->galleries->first()->images) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" alt="{{ $package->title ?? $package->type }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1595855759920-86582396756a?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                                <span class="absolute top-4 left-4 bg-[#00a877] text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-full">{{ $package->type }}</span>
                                @if($package->is_signature)
                                <span class="absolute top-4 right-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-[10px] font-bold tracking-wider uppercase px-3 py-1.5 rounded-full flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                    {{ __('Signature') }}
                                </span>
                                @endif
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="bg-white/20 backdrop-blur-sm border border-white/20 rounded-2xl px-4 py-2">
                                        <span class="text-[10px] text-white/70 block">@lang('messages.popular.start_from')</span>
                                        <span class="text-white text-lg font-bold">Rp {{ number_format($package->price, 0, ',', '.') }}<span class="text-xs font-normal text-white/70"> {{ __('messages.home_value.per_person') }}</span></span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <div class="p-5 flex flex-col flex-1">
                            <div class="flex items-center gap-2 text-xs text-neutral-500 mb-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#00a877]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                {{ $package->location }}
                            </div>
                            <h3 class="font-serif text-lg font-bold text-[#053d2c] mb-2 line-clamp-2 group-hover:text-[#00a877] transition-colors">{{ $package->type }}</h3>
                            <p class="text-xs text-neutral-500 font-light leading-relaxed mb-4 line-clamp-2">{{ Str::limit(strip_tags($package->description ?? ''), 100) }}</p>
                            <div class="mt-auto flex items-center justify-between gap-2 pt-3 border-t border-neutral-100">
                                <a href="{{ route('travel_package.show', $package->slug ?? $package->id) }}" class="text-[#00a877] text-sm font-semibold hover:gap-2 transition-all inline-flex items-center gap-1">
                                    {{ __('messages.popular.view_detail') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Navigation: Dots kiri, Nav kanan --}}
            <div class="flex items-center justify-between mt-8">
                {{-- Dots Pagination (kiri) --}}
                <div class="packages-pagination flex items-center gap-2"></div>

                {{-- Prev/Next Buttons (kanan) --}}
                <div class="flex items-center gap-3">
                    <button class="packages-prev w-11 h-11 rounded-full bg-white border border-neutral-200 flex items-center justify-center text-neutral-600 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button class="packages-next w-11 h-11 rounded-full bg-white border border-neutral-200 flex items-center justify-center text-neutral-600 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition-all duration-300 shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-16">
            <p class="text-neutral-500 text-lg">{{ __('messages.home_value.empty_packages') }}</p>
        </div>
        @endif

        {{-- Mobile CTA --}}
        <div class="text-center mt-6 lg:hidden">
            <a href="{{ route('travel_package.index') }}" class="inline-flex items-center gap-2 bg-[#053d2c] hover:bg-[#0a4d36] text-white px-8 py-3.5 rounded-full font-medium transition text-sm">
                {{ __('messages.popular.see_all') }}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
            </a>
        </div>
    </div>
</section>

@push('script-alt')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        const swiper = new Swiper('.packages-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.packages-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + '" style="width:10px;height:10px;border-radius:50%;opacity:1;transition:all 0.3s;display:inline-block;"></span>';
                },
            },
            navigation: {
                nextEl: '.packages-next',
                prevEl: '.packages-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                1024: {
                    slidesPerView: 3,
                },
            }
        });
    }
});
</script>
@endpush

@push('style-alt')
<style>
.packages-swiper .swiper-pagination-bullet {
    background: #d1d5db !important; /* gray-300 */
    opacity: 1 !important;
}
.packages-swiper .swiper-pagination-bullet-active {
    background: #00a877 !important;
    width: 2rem !important;
    border-radius: 9999px !important;
}
.packages-swiper .swiper-pagination {
    position: relative !important;
    width: auto !important;
    display: flex;
    align-items: center;
    gap: 4px;
}
</style>
@endpush
</write_to_file>