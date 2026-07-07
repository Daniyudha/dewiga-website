{{-- POPULAR PACKAGES (Swiper) --}}
<section id="popular" class="section-padding bg-primary-50/30">
    <div class="container-custom">
        <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-10">
            <div>
                <span class="section-subtitle">@lang('messages.popular.subtitle')</span>
                <h2 class="section-title mb-0">@lang('messages.popular.title')</h2>
            </div>
            <a href="{{ route('travel_package.index') }}" class="hidden lg:inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all">
                @lang('messages.popular.see_all')
                <i class="bx bx-right-arrow-alt"></i>
            </a>
        </div>

        <div class="popular__container swiper mt-12 overflow-hidden">
            <div class="swiper-wrapper">
                @foreach ($travel_packages as $travel_package)
                    <article class="swiper-slide h-auto">
                        <a href="{{ route('travel_package.show', $travel_package->slug) }}"
                           class="group flex flex-col h-full bg-white rounded-[28px] overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500">
                            {{-- IMAGE --}}
                            <div class="relative h-72 overflow-hidden">
                                <img data-src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}"
                                     alt="{{ $travel_package->location }}"
                                     class="lazy_img w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                <div class="absolute top-4 left-4">
                                    <span class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg">{{ $travel_package->type }}</span>
                                </div>
                                <div class="absolute bottom-4 left-4 right-4">
                                    <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-2">
                                        <span class="text-xs text-gray-300 block">@lang('messages.popular.start_from')</span>
                                        <h4 class="text-white text-xl font-bold">{{ formatPrice($travel_package->price) }} /pax</h4>
                                    </div>
                                </div>
                            </div>
                            {{-- CONTENT --}}
                            <div class="flex flex-col flex-1 p-6">
                                <h3 class="text-xl font-bold text-primary-900 line-clamp-2 min-h-[30px]">{{ $travel_package->location }}</h3>
                                <p class="text-sm text-gray-500 group-hover:text-primary-500 line-clamp-2 min-h-[44px] mt-2">{{ Str::limit(strip_tags($travel_package->description ?? ''), 80) }}</p>
                                <div class="flex items-center justify-between mt-auto pt-5">
                                    <span class="text-gray-500 group-hover:text-primary font-semibold">@lang('messages.popular.view_detail')</span>
                                    <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                        <i class="bx bx-right-arrow-alt text-lg"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
            <div class="swiper-pagination popular-pagination mt-4"></div>
            <div class="flex justify-center items-center gap-4 mt-8">
                <div class="popular-prev"><i class="bx bx-chevron-left"></i></div>
                <div class="popular-next"><i class="bx bx-chevron-right"></i></div>
            </div>
        </div>

        <div class="text-center mt-12 lg:hidden">
            <a href="{{ route('travel_package.index') }}" class="button">@lang('messages.popular.see_all')</a>
        </div>
    </div>
</section>