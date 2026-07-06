@extends('layouts.frontend')

@section('title', __('messages.seo.home_title'))
@section('meta_description', __('messages.seo.home_desc'))
@section('og_title', __('messages.seo.home_title'))
@section('og_description', __('messages.seo.home_desc'))
@section('twitter_title', __('messages.seo.home_title'))
@section('twitter_description', __('messages.seo.home_desc'))

@section('content')
    {{-- HERO SECTION --}}
    <section class="hero relative h-screen max-h-[100vh] overflow-hidden" id="hero">
        <img class="hero__bg show absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
             src="{{ asset('frontend/assets/img/hero.jpg') }}" alt="Desa Wisata Gabugan" />
        <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
             data-src="{{ asset('frontend/assets/img/hero1.jpg') }}" alt="Gabugan Village" />
        <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
             data-src="{{ asset('frontend/assets/img/hero2.jpg') }}" alt="Gabugan Activities" />
        <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
             data-src="{{ asset('frontend/assets/img/hero3.jpg') }}" alt="Gabugan Landscape" />

        {{-- Overlay --}}
        <div class="hero__overlay absolute inset-0 bg-gradient-to-b from-black/40 via-black/50 to-black/70"></div>

        {{-- Content --}}
        <div class="hero__container relative z-10 h-full flex flex-col justify-center items-center container-custom text-center">
            <span class="hero__subtitle block text-secondary text-sm font-semibold uppercase tracking-[3px] mb-4">
                @lang('messages.hero.subtitle')
            </span>
            <h1 class="hero__title font-heading text-4xl md:text-5xl lg:text-6xl text-white max-w-4xl leading-tight">
                @lang('messages.hero.title')
            </h1>
            <p class="hero__description text-stone-200/90 text-base md:text-lg max-w-2xl mt-4 leading-relaxed">
                @lang('messages.hero.description')
            </p>
            <div class="hero__actions flex flex-col sm:flex-row gap-4 mt-8">
                <a href="{{ route('travel_package.index') }}" class="button">
                    <i class="bx bx-search"></i>
                    @lang('messages.hero.cta_packages')
                </a>
                <a href="{{ route('contact') }}" class="button-transparent button-outline !border-white !text-white hover:!bg-white hover:!text-primary">
                    <i class="bx bx-phone"></i>
                    @lang('messages.hero.cta_contact')
                </a>
            </div>
        </div>
    </section>

    {{-- VALUE PROPOSITION --}}
    <section
    id="value"
    class="section-padding bg-gradient-to-b from-white to-primary-50/30 overflow-hidden"
    >
        <div class="container-custom grid lg:grid-cols-2 gap-16 items-center">

            {{-- Images --}}
            <div class="relative">

                <div class="relative h-[500px] lg:h-[600px]">

                    {{-- Main Image --}}
                    <div class="absolute top-0 left-0 w-[78%] h-[75%] rounded-3xl overflow-hidden border-4 border-white shadow-2xl">
                        <img
                            class="w-full h-full object-cover"
                            src="{{ asset('frontend/assets/img/value-img.jpg') }}"
                            alt="@lang('messages.value.title')"
                        >
                    </div>

                    {{-- Secondary Image --}}
                    <div class="absolute bottom-0 right-0 w-[55%] h-[42%] rounded-3xl overflow-hidden border-4 border-white shadow-2xl">
                        <img
                            class="w-full h-full object-cover"
                            src="{{ asset('frontend/assets/img/value-img-2.jpg') }}"
                            alt="@lang('messages.value.title')"
                        >
                    </div>

                    {{-- Decorative Shape --}}
                    <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full bg-primary/10 blur-3xl"></div>
                    <div class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full bg-secondary/10 blur-3xl"></div>

                </div>

            </div>

            {{-- Content --}}
            <div>

                <span class="section-subtitle">
                    @lang('messages.value.subtitle')
                </span>

                <h2 class="section-title mb-6">
                    @lang('messages.value.title')
                </h2>

                <p class="text-primary-600 leading-relaxed mb-10 max-w-xl">
                    @lang('messages.value.description')
                </p>

                {{-- Cards --}}
                <div class="grid sm:grid-cols-2 gap-5">

                    {{-- Edu Wisata --}}
                    <div class="value-card">
                        <div class="value-card-icon">
                            <i class="bx bx-book-open"></i>
                        </div>

                        <h3 class="value-card-title">
                            @lang('messages.value.cards.edu_tourism.title')
                        </h3>

                        <p class="value-card-desc">
                            @lang('messages.value.cards.edu_tourism.desc')
                        </p>
                    </div>

                    {{-- Live In --}}
                    <div class="value-card">
                        <div class="value-card-icon secondary">
                            <i class="bx bx-home-heart"></i>
                        </div>

                        <h3 class="value-card-title">
                            @lang('messages.value.cards.live_in.title')
                        </h3>

                        <p class="value-card-desc">
                            @lang('messages.value.cards.live_in.desc')
                        </p>
                    </div>

                    {{-- Agriculture --}}
                    <div class="value-card">
                        <div class="value-card-icon">
                            <i class="bx bx-leaf"></i>
                        </div>

                        <h3 class="value-card-title">
                            @lang('messages.value.cards.agriculture.title')
                        </h3>

                        <p class="value-card-desc">
                            @lang('messages.value.cards.agriculture.desc')
                        </p>
                    </div>

                    {{-- Local Culture --}}
                    <div class="value-card">
                        <div class="value-card-icon secondary">
                            <i class="bx bx-mask"></i>
                        </div>

                        <h3 class="value-card-title">
                            @lang('messages.value.cards.local_culture.title')
                        </h3>

                        <p class="value-card-desc">
                            @lang('messages.value.cards.local_culture.desc')
                        </p>
                    </div>

                </div>

                {{-- CTA --}}
                <div class="flex flex-wrap gap-4 mt-10">

                    <a
                        href="{{ route('about-us') }}"
                        class="button"
                    >
                        @lang('messages.value.learn_more')
                    </a>

                    <a
                        href="{{ route('travel_package.index') }}"
                        class="button-transparent button-outline"
                    >
                        @lang('messages.nav.packages')
                    </a>

                </div>

            </div>

        </div>
    </section>

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

    {{-- POPULAR PACKAGES (Swiper) --}}
    <section
        id="popular"
        class="section-padding bg-primary-50/30"
    >
        <div class="container-custom">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-6 mb-10">
                <div>
                    <span class="section-subtitle">
                        @lang('messages.popular.subtitle')
                    </span>
                    <h2 class="section-title mb-0">
                        @lang('messages.popular.title')
                    </h2>
                </div>
                <a
                    href="{{ route('travel_package.index') }}"
                    class="hidden lg:inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all"
                >
                    @lang('messages.popular.see_all')
                    <i class="bx bx-right-arrow-alt"></i>
                </a>
            </div>

            <div class="popular__container swiper mt-12 overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach ($travel_packages as $travel_package)
                        <article class="swiper-slide h-auto">
                            <a
                                href="{{ route('travel_package.show', $travel_package->slug) }}"
                                class="group flex flex-col h-full bg-white rounded-[28px] overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500"
                            >
                                {{-- IMAGE --}}
                                <div class="relative h-72 overflow-hidden">
                                    <img
                                        data-src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}"
                                        alt="{{ $travel_package->location }}"
                                        class="lazy_img w-full h-full object-cover transition duration-700 group-hover:scale-110"
                                    >
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                    {{-- CATEGORY --}}
                                    <div class="absolute top-4 left-4">
                                        <span class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg">
                                            {{ $travel_package->type }}
                                        </span>
                                    </div>
                                    {{-- PRICE --}}
                                    <div class="absolute bottom-4 left-4 right-4">
                                        <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-2">
                                            <span class="text-xs text-gray-300 block">
                                                    @lang('messages.popular.start_from')
                                                </span>
                                            <h4 class="text-white text-xl font-bold">
                                                {{ formatPrice($travel_package->price) }} /pax
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                {{-- CONTENT --}}
                                <div class="flex flex-col flex-1 p-6">
                                    {{-- TITLE --}}
                                    <h3 class="text-xl font-bold text-primary-900 line-clamp-2 min-h-[30px]">
                                        {{ $travel_package->location }}
                                    </h3>
                                    {{-- DESCRIPTION --}}
                                    <p class="text-sm text-gray-500 group-hover:text-primary-500 line-clamp-2 min-h-[44px] mt-2">
                                        {{ Str::limit(strip_tags($travel_package->description ?? ''), 80) }}
                                    </p>
                                    {{-- FOOTER --}}
                                    <div class="flex items-center justify-between mt-auto pt-5">
                                        <span class="text-gray-500 group-hover:text-primary font-semibold">
                                            @lang('messages.popular.view_detail')
                                        </span>
                                        <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center transition-all duration-300 group-hover:translate-x-1">
                                            <i class="bx bx-right-arrow-alt text-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="swiper-pagination popular-pagination mt-4"></div>

                {{-- Navigation --}}
                <div class="flex justify-center items-center gap-4 mt-8">
                    <div class="popular-prev">
                        <i class="bx bx-chevron-left"></i>
                    </div>
                    <div class="popular-next">
                        <i class="bx bx-chevron-right"></i>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="text-center mt-12 lg:hidden">
                <a
                    href="{{ route('travel_package.index') }}"
                    class="button"
                >
                    @lang('messages.popular.see_all')
                </a>
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS (Swiper) --}}
    <section class="section-padding bg-primary-50/50" id="testimonials">
        <div class="container-custom">
            <span class="section-subtitle text-center">@lang('messages.testimonials.subtitle')</span>
            <h2 class="section-title text-center">@lang('messages.testimonials.title')</h2>

            @if($testimonials->count() > 0)
            <div class="testimonials__container swiper mt-10 !pb-12 overflow-hidden">
                <div class="swiper-wrapper">
                    @foreach($testimonials as $testimonial)
                        <div class="swiper-slide h-auto">
                            <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col h-full border border-slate-50">
                                <div class="flex gap-0.5 text-yellow-500 mb-4">
                                    <i class="bx bxs-star"></i>
                                    <i class="bx bxs-star"></i>
                                    <i class="bx bxs-star"></i>
                                    <i class="bx bxs-star"></i>
                                    <i class="bx bxs-star"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-primary-600 leading-relaxed text-sm line-clamp-3 mb-3">
                                        "{{ $testimonial->content }}"
                                    </p>
                                    {{-- Mengirim data langsung ke fungsi JS untuk menghindari error di Swiper Loop --}}
                                    <button
                                        type="button"
                                        class="swiper-no-swiping testimonial-btn relative z-20 text-secondary hover:underline text-xs font-semibold uppercase tracking-wider mb-4 block"
                                        data-name="{{ $testimonial->name }}"
                                        data-content="{{ $testimonial->content }}"
                                        data-avatar="{{ $testimonial->avatar ? asset('storage/'.$testimonial->avatar) : 'https://i.pravatar.cc/80?u='.urlencode($testimonial->name) }}"
                                    >
                                        @lang('messages.testimonials.read_more')
                                        <i class="bx bx-chevron-right align-middle"></i>
                                    </button>
                                </button>
                                </div>
                                <div class="flex items-center gap-3 pt-4 border-t border-stone-100 mt-auto">
                                    @if($testimonial->avatar && file_exists(public_path('storage/' . $testimonial->avatar)))
                                        <img src="{{ asset('storage/' . $testimonial->avatar) }}"
                                            alt="{{ $testimonial->name }}"
                                            class="testimonial__avatar w-12 h-12 rounded-full object-cover"
                                            loading="lazy" />
                                    @else
                                        <img src="https://i.pravatar.cc/48?u={{ urlencode($testimonial->name) }}"
                                            alt="{{ $testimonial->name }}"
                                            class="testimonial__avatar w-12 h-12 rounded-full object-cover"
                                            loading="lazy" />
                                    @endif
                                    <div>
                                        <h4 class="font-heading text-primary-900 font-semibold text-sm">
                                            {{ $testimonial->name }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
            @else
            <div class="text-center py-12 text-primary-400 mt-10">
                <i class="bx bx-message-square-detail text-4xl block mb-3"></i>
                <p>@lang('messages.testimonials.empty')</p>
            </div>
            @endif

            <div class="text-center mt-10">
                <a href="{{ route('testimonials.create') }}" class="button-transparent button-outline">
                    <i class="bx bxs-edit"></i>
                    @lang('messages.testimonials.submit_testimonial')
                </a>
            </div>
        </div>
    </section>

    {{-- Testimonial Modal --}}
    <div id="testimonialModal"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300"
        style="background: rgba(0,0,0,.7); backdrop-filter: blur(4px);"
        onclick="if(event.target===this) closeTestimonialModal()">

        <div
            id="testimonialModalBody"
            class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto p-6 md:p-8 transform scale-95 transition-all duration-300">

            <div class="flex items-start justify-between mb-6">

                <div class="flex items-center gap-4">

                    <img
                        id="testimonialModalAvatar"
                        src=""
                        alt=""
                        class="w-12 h-12 rounded-full object-cover">

                    <div>
                        <h3
                            id="testimonialModalName"
                            class="font-heading text-lg font-bold text-primary-900">
                        </h3>

                        <div class="flex gap-0.5 text-yellow-500 text-xs mt-1">
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                            <i class="bx bxs-star"></i>
                        </div>

                    </div>

                </div>

                <button
                    type="button"
                    onclick="closeTestimonialModal()"
                    class="text-gray-400 hover:text-red-500 text-3xl">

                    <i class="bx bx-x"></i>

                </button>

            </div>

            <div
                id="testimonialModalContent"
                class="text-primary-600 leading-relaxed text-base italic">
            </div>

        </div>

    </div>

    @push('script-alt')
    <script>
        // 1. Fungsi Modal
        function openTestimonialModal(name, content, avatar) {

            document.getElementById('testimonialModalName').textContent = name;

            document.getElementById('testimonialModalContent').innerHTML =
                content.replace(/\n/g, '<br>');

            const avatarImg = document.getElementById('testimonialModalAvatar');

            avatarImg.src = avatar;
            avatarImg.alt = name;

            const modal = document.getElementById('testimonialModal');
            const body = document.getElementById('testimonialModalBody');

            modal.classList.remove('opacity-0', 'invisible');

            body.classList.remove('scale-95');
            body.classList.add('scale-100');

            document.body.style.overflow = 'hidden';
        }

        function closeTestimonialModal() {
            const modal = document.getElementById('testimonialModal');
            const body = document.getElementById('testimonialModalBody');

            modal.classList.add('invisible', 'opacity-0');
            body.classList.add('scale-95');
            body.classList.remove('scale-100');
            document.body.style.overflow = ''; // Buka scroll halaman
        }

        // 2. Global Event Listener (Event Delegation)
        // Menangani klik tombol dengan aman tanpa bentrok dengan Swiper Loop/Clone
        document.addEventListener('click', function(e){

            const btn = e.target.closest('.testimonial-btn');

            if(!btn) return;

            e.preventDefault();
            e.stopPropagation();

            openTestimonialModal(
                btn.dataset.name,
                btn.dataset.content,
                btn.dataset.avatar
            );

        });

        // 3. Inisialisasi Swiper
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper(".testimonials__container", {
                spaceBetween: 24,
                grabCursor: true,
                loop: true,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                
                // Pengaturan Anti-Drag pada Tombol
                noSwiping: true,
                noSwipingClass: 'swiper-no-swiping',
                
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                breakpoints: {
                    320: { slidesPerView: 1 },
                    640: { slidesPerView: 2 },
                    1024: { slidesPerView: 3 },
                },
            });
        });

        // Close Modal via Keyboard Esc
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTestimonialModal();
        });
    </script>
    @endpush

    {{-- STATISTICS --}}
    <section class="section-padding bg-gradient-to-br from-primary to-primary-800 text-white" id="stats">
        <div class="container-custom">
            <div class="stats__grid grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="stats__item">
                    <span class="stats__number block font-heading text-3xl md:text-4xl font-bold text-secondary">
                        @lang('messages.stats.visitors')
                    </span>
                    <span class="stats__label block text-sm text-primary-200 mt-2">
                        @lang('messages.stats.visitors_label')
                    </span>
                </div>
                <div class="stats__item">
                    <span class="stats__number block font-heading text-3xl md:text-4xl font-bold text-secondary">
                        @lang('messages.stats.packages')
                    </span>
                    <span class="stats__label block text-sm text-primary-200 mt-2">
                        @lang('messages.stats.packages_label')
                    </span>
                </div>
                <div class="stats__item">
                    <span class="stats__number block font-heading text-3xl md:text-4xl font-bold text-secondary">
                        @lang('messages.stats.activities')
                    </span>
                    <span class="stats__label block text-sm text-primary-200 mt-2">
                        @lang('messages.stats.activities_label')
                    </span>
                </div>
                <div class="stats__item">
                    <span class="stats__number block font-heading text-3xl md:text-4xl font-bold text-secondary">
                        @lang('messages.stats.awards')
                    </span>
                    <span class="stats__label block text-sm text-primary-200 mt-2">
                        @lang('messages.stats.awards_label')
                    </span>
                </div>
            </div>
        </div>
    </section>

    {{-- BLOG SECTION --}}
    <section class="section-padding" id="blog">
        <div class="blog__container container-custom">
            <span class="section-subtitle text-center">@lang('messages.blog.subtitle')</span>
            <h2 class="section-title text-center">@lang('messages.blog.title')</h2>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
                @foreach ($blogs as $blog)
                    <article class="blog__card bg-white rounded-2xl overflow-hidden shadow-lg group hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                        <div class="blog__image relative h-48 overflow-hidden">
                            <img data-src="{{ Storage::url($blog->image) }}"
                                alt="{{ $blog->title }}"
                                class="blog__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <a href="{{ route('blog.show', $blog->slug) }}"
                            class="blog__button absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-primary shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-primary hover:text-white">
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <div class="blog__data p-5 flex-1 flex flex-col">
                            @if ($blog->category)
                                <span class="blog__category inline-block text-xs font-semibold text-secondary uppercase tracking-wide mb-2">
                                    {{ $blog->category->name }}
                                </span>
                            @endif
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <h2 class="blog__title font-heading text-primary-900 font-semibold text-lg leading-snug mb-2 hover:text-primary transition-colors">
                                    {{ $blog->title }}
                                </h2>
                            </a>
                            <p class="blog__description text-sm text-primary-500 leading-relaxed mb-4">
                                {{ $blog->excerpt }}
                            </p>

                            {{-- Footer card yang selalu di bawah --}}
                            <div class="mt-auto">
                                <div class="blog__footer flex items-center gap-4 pt-4 border-t border-stone-100 text-xs text-primary-400">
                                    <div class="blog__reaction flex items-center gap-1">
                                        <i class="bx bx-calendar"></i>
                                        <span>{{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                    </div>
                                    <div class="blog__reaction flex items-center gap-1">
                                        <i class="bx bx-show"></i>
                                        <span>{{ $blog->reads }}</span>
                                    </div>
                                    <button data-title="{{ $blog->title }}"
                                            data-route="{{ route('blog.show', $blog->slug) }}"
                                            class="blog__reaction flex items-center gap-1 ml-auto hover:text-primary transition-colors share-button">
                                        <i class="bx bx-share-alt"></i>
                                        <span>@lang('messages.blog.share')</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Tombol See All Blogs --}}
            <div class="text-center mt-12">
                <a href="{{ route('blog.index') }}" 
                class="button">
                    <i class="bx bx-right-arrow-alt"></i>
                    @lang('messages.blog.see_all')
                </a>
            </div>
        </div>
    </section>

    {{-- GALLERY SLIDER (baguetteBox) --}}
    <div class="tz-gallery">
        <div class="slideshow-img overflow-hidden py-8 bg-primary-50/30">
            <div class="gallery-slider-pot lazy_img">
                <div class="slide-track-pot flex animate-slide">
                    @for ($i = 1; $i <= 8; $i++)
                        <div class="slide-item-pot shrink-0">
                            <a class="lightbox" href="{{ asset('frontend/assets/img/gal-' . $i . '.jpg') }}">
                                <img src="{{ asset('frontend/assets/img/gal-' . $i . '.jpg') }}"
                                     alt="Gallery {{ $i }}"
                                     class="h-80 w-full object-cover shadow-md"
                                     loading="lazy">
                            </a>
                        </div>
                    @endfor
                    {{-- Duplicate for seamless loop --}}
                    @for ($i = 1; $i <= 8; $i++)
                        <div class="slide-item-pot shrink-0">
                            <a class="lightbox" href="{{ asset('frontend/assets/img/gal-' . $i . '.jpg') }}">
                                <img src="{{ asset('frontend/assets/img/gal-' . $i . '.jpg') }}"
                                     alt="Gallery {{ $i }}"
                                     class="h-80 w-full object-cover shadow-md"
                                     loading="lazy">
                            </a>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

    {{-- VIDEO EMBED --}}
    <section class="container-custom section-padding">
        <div class="video-section lazy_img max-w-4xl mx-auto rounded-2xl overflow-hidden shadow-2xl aspect-video">
            <iframe class="youtube-video w-full h-full"
                    src="https://www.youtube.com/embed/wqQGYi1-JdA?si=NnuW_wZO2dX2JX3z"
                    title="YouTube video player"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen
                    loading="lazy"></iframe>
        </div>
    </section>

    {{-- LOGOS --}}
    <section class="pb-12">
        <div class="container-custom grid grid-cols-3 gap-8 items-center justify-items-center">
            <div class="logos__img opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
                <img class="lazy_img max-h-16 w-auto"
                     data-src="{{ asset('frontend/assets/img/logo-WI.png') }}"
                     alt="Wisata Indonesia" />
            </div>
            <div class="logos__img opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
                <img class="lazy_img max-h-16 w-auto"
                     data-src="{{ asset('frontend/assets/img/sleman-tlc.png') }}"
                     alt="Sleman TLC" />
            </div>
            <div class="logos__img opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300">
                <img class="lazy_img max-h-16 w-auto"
                     data-src="{{ asset('frontend/assets/img/wiyata.png') }}"
                     alt="Wiyata" />
            </div>
        </div>
    </section>

    {{-- Hero Background Rotator Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const heroBgs = document.querySelectorAll('.hero__bg');
            if (heroBgs.length > 1) {
                let imageIndex = 0;
                setInterval(function() {
                    heroBgs.forEach(img => img.classList.remove('show'));
                    imageIndex = (imageIndex + 1) % heroBgs.length;
                    heroBgs[imageIndex].classList.add('show');
                }, 4000);
            }
        });
    </script>

    {{-- Share Button Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.share-button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const title = this.dataset.title;
                    const url = this.dataset.route;
                    if (navigator.share) {
                        navigator.share({ title, url }).catch(console.error);
                    } else {
                        this.classList.toggle('is-open');
                    }
                });
            });
        });
    </script>
@endsection