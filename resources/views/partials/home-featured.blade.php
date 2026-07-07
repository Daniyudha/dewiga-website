{{-- FEATURED ACTIVITIES --}}
<section class="section-padding bg-primary-50/50" id="featured">
    <div class="container-custom">
        <span class="section-subtitle text-center">@lang('messages.featured.subtitle')</span>
        <h2 class="section-title text-center">@lang('messages.featured.title')</h2>

        <div class="featured__container grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mt-10">
            {{-- Plowing (Large) --}}
            <div class="featured__card featured__card--large relative group rounded-2xl overflow-hidden shadow-lg md:col-span-2 md:row-span-2">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/bajak.jpg') }}"
                     alt="@lang('messages.featured.activities.plowing')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                    <h3 class="featured__name text-white font-heading text-xl md:text-2xl font-semibold">
                        @lang('messages.featured.activities.plowing')
                    </h3>
                    <p class="featured__desc text-stone-300 text-sm mt-1 max-w-md">
                        @lang('messages.featured.activities.plowing_desc')
                    </p>
                </div>
            </div>

            {{-- Rice Planting --}}
            <div class="featured__card relative group rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/tanam-padi.jpg') }}"
                     alt="@lang('messages.featured.activities.planting')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                    <h3 class="featured__name text-white font-heading text-lg font-semibold">
                        @lang('messages.featured.activities.planting')
                    </h3>
                    <p class="featured__desc text-stone-300 text-xs mt-1">
                        @lang('messages.featured.activities.planting_desc')
                    </p>
                </div>
            </div>

            {{-- Batik --}}
            <div class="featured__card relative group rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/batik.jpg') }}"
                     alt="@lang('messages.featured.activities.batik')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                    <h3 class="featured__name text-white font-heading text-lg font-semibold">
                        @lang('messages.featured.activities.batik')
                    </h3>
                    <p class="featured__desc text-stone-300 text-xs mt-1">
                        @lang('messages.featured.activities.batik_desc')
                    </p>
                </div>
            </div>

            {{-- Karawitan --}}
            <div class="featured__card relative group rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/gamel.jpg') }}"
                     alt="@lang('messages.featured.activities.karawitan')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                    <h3 class="featured__name text-white font-heading text-lg font-semibold">
                        @lang('messages.featured.activities.karawitan')
                    </h3>
                    <p class="featured__desc text-stone-300 text-xs mt-1">
                        @lang('messages.featured.activities.karawitan_desc')
                    </p>
                </div>
            </div>

            {{-- Wayang --}}
            <div class="featured__card relative group rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/wayang.jpg') }}"
                     alt="@lang('messages.featured.activities.wayang')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                    <h3 class="featured__name text-white font-heading text-lg font-semibold">
                        @lang('messages.featured.activities.wayang')
                    </h3>
                    <p class="featured__desc text-stone-300 text-xs mt-1">
                        @lang('messages.featured.activities.wayang_desc')
                    </p>
                </div>
            </div>

            {{-- Makanan Tradisional --}}
            <div class="featured__card relative group rounded-2xl overflow-hidden shadow-lg aspect-[4/3]">
                <img class="featured__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                     data-src="{{ asset('frontend/assets/img/makanan.jpg') }}"
                     alt="@lang('messages.featured.activities.makanan')" />
                <div class="featured__overlay absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-5">
                    <h3 class="featured__name text-white font-heading text-lg font-semibold">
                        @lang('messages.featured.activities.makanan')
                    </h3>
                    <p class="featured__desc text-stone-300 text-xs mt-1">
                        @lang('messages.featured.activities.makanan_desc')
                    </p>
                </div>
            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ route('travel_package.index') }}" class="button">
                <i class="bx bx-search"></i>
                @lang('messages.featured.cta')
            </a>
        </div>
    </div>
</section>