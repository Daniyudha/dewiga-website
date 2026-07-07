@extends('layouts.frontend')

@section('title', $travel_package->location . ' - ' . __('messages.seo.packages_title'))
@section('meta_description', strip_tags(substr($travel_package->description, 0, 160)))
@section('og_title', $travel_package->location . ' - Desa Wisata Gabugan')
@section('og_description', strip_tags(substr($travel_package->description, 0, 160)))
@section('og_image', $travel_package->galleries->first() ? Storage::url($travel_package->galleries->first()->images) : asset('frontend/assets/img/hero.jpg'))
@section('twitter_title', $travel_package->location . ' - Desa Wisata Gabugan')
@section('twitter_description', strip_tags(substr($travel_package->description, 0, 160)))
@section('twitter_image', $travel_package->galleries->first() ? Storage::url($travel_package->galleries->first()->images) : asset('frontend/assets/img/package-top.jpg'))

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Product",
    "name": "{{ $travel_package->location }}",
    "description": "{{ strip_tags(substr($travel_package->description, 0, 200)) }}",
    "offers": {
        "@type": "Offer",
        "price": "{{ $travel_package->price }}",
        "priceCurrency": "IDR",
        "availability": "https://schema.org/InStock",
        "url": "{{ url()->current() }}"
    },
    "image": "{{ $travel_package->galleries->first() ? Storage::url($travel_package->galleries->first()->images) : asset('frontend/assets/img/hero.jpg') }}"
}
</script>
@endsection

@section('content')
    {{-- HERO DETAIL PACKAGE --}}
    <section class="hero--page relative min-h-[650px] flex items-end overflow-hidden" id="heroSlider">
        @if ($travel_package->galleries->count() > 0)
            @foreach ($travel_package->galleries as $index => $gallery)
                <img
                    class="hero__bg absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000 ease-in-out {{ $index === 0 ? 'show' : '' }}"
                    src="{{ Storage::url($gallery->images) }}"
                    alt="{{ $gallery->name ?? $travel_package->location }}"
                    data-index="{{ $index }}"
                >
            @endforeach
        @else
            <img
                class="hero__bg show absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000 ease-in-out"
                src="{{ asset('frontend/assets/img/package-top.jpg') }}"
                alt="{{ $travel_package->location }}"
                data-index="0"
            >
        @endif

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        {{-- Hero Slider Navigation Dots --}}
        @if ($travel_package->galleries->count() > 1)
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2">
            @foreach ($travel_package->galleries as $index => $gallery)
                <button
                    class="hero-dot w-2.5 h-2.5 rounded-full transition-all duration-300 {{ $index === 0 ? 'bg-secondary scale-110' : 'bg-white/50 hover:bg-white/80' }}"
                    data-slide="{{ $index }}"
                    aria-label="Slide {{ $index + 1 }}"
                ></button>
            @endforeach
        </div>
        @endif

        <div class="container-custom relative z-10 pb-20">
            {{-- Breadcrumb --}}
            <div class="flex items-center gap-2 text-sm text-white/70 mb-8">
                <a href="{{ route('homepage') }}" class="hover:text-white">
                    @lang('messages.nav.home')
                </a>
                <i class="bx bx-chevron-right"></i>
                <a href="{{ route('travel_package.index') }}" class="hover:text-white">
                    @lang('messages.nav.packages')
                </a>
                <i class="bx bx-chevron-right"></i>
                <span class="text-white">
                    {{ $travel_package->location }}
                </span>
            </div>
            {{-- Badge --}}
            <span class="inline-flex items-center px-5 py-2 rounded-full bg-secondary text-primary-900 text-sm font-semibold mb-5">
                {{ $travel_package->type }}
            </span>
            {{-- Title --}}
            <h1 class="font-heading text-4xl md:text-6xl font-bold text-white max-w-4xl leading-tight">
                {{ $travel_package->location }}
            </h1>
            {{-- Price --}}
            <div class="mt-6 flex flex-wrap items-end gap-6">
                <div>
                    <span class="text-primary-200 text-sm">
                        @lang('messages.popular.start_from')
                    </span>
                    <h2 class="text-secondary text-3xl md:text-4xl font-bold">
                        {{ formatPrice($travel_package->price) }} /pax
                    </h2>
                </div>
                <a
                    href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.package_message',['name' => $travel_package->location])) }}"
                    target="_blank"
                    class="inline-flex items-center gap-3 px-4 py-2 rounded-2xl bg-[#25D366] text-white font-semibold hover:scale-105 transition"
                >
                    <i class="bx bxl-whatsapp text-xl"></i>
                    @lang('messages.whatsapp.cta')
                </a>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="section-padding bg-primary-50/30">
        <div class="container-custom">
            <div class="grid lg:grid-cols-[1fr_400px] gap-10">
                {{-- LEFT CONTENT --}}
                <div>
                    {{-- SUMMARY CARD --}}
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl mb-10">
                        <div class="bg-gradient-to-r from-primary to-primary-700 p-8 text-white">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                                <div>
                                    <span class="inline-flex items-center px-4 py-2 rounded-full bg-white/20 backdrop-blur text-sm">
                                        {{ $travel_package->type }}
                                    </span>
                                    <h2 class="text-3xl text-white font-bold mt-4">
                                        {{ $travel_package->location }}
                                    </h2>
                                </div>
                                <div class="text-left md:text-right">
                                    <span class="text-primary-100 text-sm block">
                                        @lang('messages.packages.price')
                                    </span>
                                    <h3 class="text-2xl text-white font-bold">
                                        {{ formatPrice($travel_package->price) }} /pax
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="grid md:grid-cols-3 divide-y md:divide-y-0 md:divide-x">
                            <div class="p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                                    <i class="bx bx-map text-primary text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-primary-400 block">
                                        @lang('messages.packages.location')
                                    </span>
                                    <p class="font-semibold text-primary-900">
                                        {{ $travel_package->location }}
                                    </p>
                                </div>
                            </div>

                            <div class="p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                                    <i class="bx bx-category text-primary text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-primary-400 block">
                                        @lang('messages.packages.type')
                                    </span>
                                    <p class="font-semibold text-primary-900">
                                        {{ $travel_package->type }}
                                    </p>
                                </div>
                            </div>
                            <div class="p-6 flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center">
                                    <i class="bx bx-check-shield text-primary text-xl"></i>
                                </div>
                                <div>
                                    <span class="text-xs text-primary-400 block">
                                        @lang('messages.packages.status')
                                    </span>
                                    <p class="font-semibold text-primary-900">
                                        @lang('messages.packages.available')
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="bg-white rounded-[32px] shadow-xl p-8 mb-10">
                        <span class="section-subtitle">
                            @lang('messages.packages.description_title')
                        </span>
                        <h2 class="section-title mb-6">
                            {{ $travel_package->location }}
                        </h2>
                        <div class="package-description prose prose-lg max-w-none text-primary-700">
                            {!! $travel_package->description !!}
                        </div>
                    </div>

                    {{-- FACILITIES --}}
                    @if ($travel_package->facilities ?? false)
                    <div class="bg-white rounded-[32px] shadow-xl p-8 mb-10">
                        <h2 class="text-2xl font-bold text-primary-900 mb-6">
                            @lang('messages.packages.facilities')
                        </h2>
                        <div class="grid sm:grid-cols-2 gap-4">
                            @foreach(explode(',', $travel_package->facilities) as $facility)
                            <div class="flex items-center gap-3 p-4 rounded-2xl bg-primary-50">
                                <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center">
                                    <i class="bx bx-check"></i>
                                </div>
                                <span class="font-medium text-primary-800">
                                    {{ trim($facility) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- FAQ --}}
                    <div class="bg-white rounded-[32px] shadow-xl p-8">
                        <h2 class="text-2xl font-bold text-primary-900 mb-6">
                            @lang('messages.packages.faq')
                        </h2>
                        <div class="space-y-4" id="faq-accordion">
                            {{-- FAQ 1 --}}
                            <div class="value__accordion-item border border-primary-100 rounded-2xl overflow-hidden">
                                <header class="value__accordion-header p-5 flex items-center gap-3 cursor-pointer">
                                    <i class="bx bx-help-circle text-primary text-xl"></i>
                                    <h3 class="flex-1 font-medium">
                                        @lang('messages.packages.faq1_q')
                                    </h3>
                                    <i class="bx bx-chevron-down value__accordion-arrow"></i>
                                </header>
                                <div class="value__accordion-content">
                                <div class="px-5 pb-5 text-primary-600 leading-relaxed">
                                    @lang('messages.packages.faq1_a')
                                </div>
                            </div>
                            </div>
                            {{-- FAQ 2 --}}
                            <div class="value__accordion-item border border-primary-100 rounded-2xl overflow-hidden">
                                <header class="value__accordion-header p-5 flex items-center gap-3 cursor-pointer">
                                    <i class="bx bx-help-circle text-primary text-xl"></i>
                                    <h3 class="flex-1 font-medium">
                                        @lang('messages.packages.faq2_q')
                                    </h3>
                                    <i class="bx bx-chevron-down value__accordion-arrow"></i>
                                </header>
                                <div class="value__accordion-content">
                                    <div class="px-5 pb-5 text-primary-600 leading-relaxed">
                                        @lang('messages.packages.faq2_a')
                                    </div>
                                </div>
                            </div>
                            {{-- FAQ 3 --}}
                            <div class="value__accordion-item border border-primary-100 rounded-2xl overflow-hidden">
                                <header class="value__accordion-header p-5 flex items-center gap-3 cursor-pointer">
                                    <i class="bx bx-help-circle text-primary text-xl"></i>
                                    <h3 class="flex-1 font-medium">
                                        @lang('messages.packages.faq3_q')
                                    </h3>
                                    <i class="bx bx-chevron-down value__accordion-arrow"></i>
                                </header>
                                <div class="value__accordion-content">
                                    <div class="px-5 pb-5 text-primary-600 leading-relaxed">
                                        @lang('messages.packages.faq3_a')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR --}}
                <div>
                    <div class="bg-white rounded-[32px] overflow-hidden shadow-xl lg:sticky lg:top-28">
                        <div class="bg-primary text-white text-center p-4">
                            <h3 class="text-2xl text-primary-100 font-bold mt-2">
                                @lang('messages.packages.book_now')
                            </h3>
                        </div>
                        <div class="p-8">

                            <form action="{{ route('booking.store') }}" method="POST">
                                @csrf
                                <input type="hidden"
                                    name="travel_package_id"
                                    value="{{ $travel_package->id }}">
                                <div class="space-y-4">
                                    <input
                                        type="text"
                                        name="name"
                                        placeholder="@lang('messages.packages.form_name_placeholder')"
                                        required
                                        class="w-full h-12 px-4 shadow-lg rounded-xl"
                                    >
                                    <input
                                        type="email"
                                        name="email"
                                        placeholder="@lang('messages.packages.form_email_placeholder')"
                                        required
                                        class="w-full h-12 px-4 shadow-lg rounded-xl"
                                    >
                                    <input
                                        type="number"
                                        name="number_phone"
                                        placeholder="@lang('messages.packages.form_phone_placeholder')"
                                        required
                                        class="w-full h-12 px-4 shadow-lg rounded-xl"
                                    >
                                    <input
                                        type="date"
                                        name="date"
                                        required
                                        class="w-full h-12 px-4 shadow-lg rounded-xl"
                                    >
                                    <button
                                        type="submit"
                                        class="button w-full"
                                    >
                                        @lang('messages.packages.form_submit')
                                    </button>
                                </div>
                            </form>
                            <div class="text-center mt-6 pt-6 border-t">
                                <p class="text-sm text-primary-500 mb-3">
                                    @lang('messages.packages.or_wa')
                                </p>
                                <a
                                    href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.package_message', ['name' => $travel_package->location])) }}"
                                    target="_blank"
                                    class="inline-flex items-center gap-2 text-[#25D366] font-semibold"
                                >
                                    <i class="bx bxl-whatsapp text-2xl"></i>
                                    0813-2885-6252
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mobile Sticky CTA --}}
    <div class="mobile-cta fixed bottom-0 left-0 w-full z-[999] hidden lg:hidden">
        <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.package_message', ['name' => $travel_package->location])) }}"
           target="_blank"
           rel="noopener noreferrer"
           class="button button-gold w-full text-center rounded-none flex items-center justify-center gap-2 py-4 text-base">
            <i class="bx bxl-whatsapp"></i>
            @lang('messages.whatsapp.ask_now')
        </a>
    </div>

    {{-- OTHER PACKAGES --}}
    @if ($travel_packages->count() > 0)
        <section class="section-padding bg-gradient-to-b from-primary/30 to-white" id="popular">
            <div class="container-custom">
                <div class="text-center mb-14">
                    <span class="section-subtitle">
                        @lang('messages.packages.other_subtitle')
                    </span>
                    <h2 class="section-title mb-3">
                        @lang('messages.packages.other_title')
                    </h2>
                    <p class="text-primary-500 max-w-2xl mx-auto">
                        @lang('messages.packages.other_desc')
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($travel_packages as $other)
                        @if ($other->id !== $travel_package->id)
                            <article>
                                <a
                                    href="{{ route('travel_package.show', $other->slug) }}"
                                    class="group block bg-white rounded-[28px] overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 h-full"
                                >
                                    {{-- Image --}}
                                    <div class="relative h-72 overflow-hidden">
                                        <img
                                            src="{{ Storage::url($other->galleries->first()->images ?? '') }}"
                                            alt="{{ $other->location }}"
                                            loading="lazy"
                                            class="w-full h-full object-cover transition duration-700 group-hover:scale-110"
                                        >
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent"></div>
                                        {{-- Package Type --}}
                                        <div class="absolute top-4 left-4">
                                            <span class="bg-primary text-white text-xs font-semibold px-4 py-2 rounded-full shadow-lg">
                                                {{ $other->type }}
                                            </span>
                                        </div>
                                        {{-- Price --}}
                                        <div class="absolute bottom-4 left-4 right-4">
                                            <div class="bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-2">
                                                <span class="text-gray-300 text-xs block">
                                                    @lang('messages.popular.start_from')
                                                </span>
                                                <h4 class="text-xl font-bold text-white">
                                                    {{ formatPrice($other->price) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Content --}}
                                    <div class="p-6 flex flex-col">
                                        <h3 class="text-xl font-bold text-primary-900 mb-3 line-clamp-2 group-hover:text-primary transition">
                                            {{ $other->location }}
                                        </h3>
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-500 group-hover:text-primary font-medium">
                                                @lang('messages.popular.view_detail')
                                            </span>
                                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center group-hover:translate-x-1 transition">
                                                <i class="bx bx-right-arrow-alt"></i>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection

@push('style-alt')
<style>
    blockquote {
        border-left: 4px solid hsl(145, 70%, 38%);
        padding-left: 1rem;
        margin: 1rem 0;
        color: #6b7280;
        font-style: italic;
    }
    .blog__detail ul li,
    .package-description ul li {
        list-style: disc;
        margin-left: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .package-description ol li {
        list-style: decimal;
        margin-left: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .package-description {
        line-height: 1.8;
    }
    .package-description img {
        border-radius: 0.75rem;
        margin: 1.5rem 0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
</style>
@endpush

@push('script-alt')
<script>
    // Hero Image Slider with Fade Transition
    document.addEventListener('DOMContentLoaded', function() {
        const heroSection = document.getElementById('heroSlider');
        const heroBgs = heroSection ? heroSection.querySelectorAll('.hero__bg') : [];
        const dots = document.querySelectorAll('.hero-dot');
        let currentIndex = 0;
        let slideInterval;

        if (heroBgs.length <= 1) return;

        function goToSlide(index) {
            // Remove show from all images
            heroBgs.forEach(img => img.classList.remove('show'));

            // Activate current image
            heroBgs[index].classList.add('show');

            // Update dots
            dots.forEach(dot => {
                dot.classList.remove('bg-secondary', 'scale-110');
                dot.classList.add('bg-white/50', 'hover:bg-white/80');
            });
            if (dots[index]) {
                dots[index].classList.remove('bg-white/50', 'hover:bg-white/80');
                dots[index].classList.add('bg-secondary', 'scale-110');
            }

            currentIndex = index;
        }

        function nextSlide() {
            const next = (currentIndex + 1) % heroBgs.length;
            goToSlide(next);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        // Dot click handlers
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const slideIndex = parseInt(this.dataset.slide);
                if (slideIndex !== currentIndex) {
                    goToSlide(slideIndex);
                    resetInterval();
                }
            });
        });

        // Start auto-rotation
        slideInterval = setInterval(nextSlide, 5000);
    });

    // FAQ Accordion
    document.addEventListener('DOMContentLoaded', function () {
        const accordionItems = document.querySelectorAll('.value__accordion-item');
        accordionItems.forEach(item => {
            const header = item.querySelector('.value__accordion-header');
            header.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                // Tutup semua item
                accordionItems.forEach(acc => {
                    acc.classList.remove('active');
                });
                // Jika sebelumnya belum aktif, buka item yang diklik
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });
        // Buka FAQ pertama saat load
        if (accordionItems.length > 0) {
            accordionItems[0].classList.add('active');
        }
    });
</script>
@endpush
