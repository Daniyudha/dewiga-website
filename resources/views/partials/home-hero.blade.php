{{-- HERO SECTION --}}
<section class="hero relative h-screen max-h-[100vh] overflow-hidden" id="hero">
    <img class="hero__bg show absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
         src="{{ asset('frontend/assets/img/hero1.jpg') }}" alt="Desa Wisata Gabugan" />
    <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
         data-src="{{ asset('frontend/assets/img/hero2.jpg') }}" alt="Gabugan Village" />
    <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
         data-src="{{ asset('frontend/assets/img/hero3.jpg') }}" alt="Gabugan Activities" />
    <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
         data-src="{{ asset('frontend/assets/img/hero4.jpg') }}" alt="Gabugan Landscape" />
    <img class="hero__bg lazy_img absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 opacity-0"
         data-src="{{ asset('frontend/assets/img/hero5.jpg') }}" alt="Gabugan Culture" />

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

        {{-- Pagination Dots (pill shape — active dot becomes elongated) --}}
        <div class="hero__pagination absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-2 z-20">
            <button type="button" class="hero__dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white hover:w-6 transition-all duration-500 ease-out" data-index="0" aria-label="Slide 1"></button>
            <button type="button" class="hero__dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white hover:w-6 transition-all duration-500 ease-out" data-index="1" aria-label="Slide 2"></button>
            <button type="button" class="hero__dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white hover:w-6 transition-all duration-500 ease-out" data-index="2" aria-label="Slide 3"></button>
            <button type="button" class="hero__dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white hover:w-6 transition-all duration-500 ease-out" data-index="3" aria-label="Slide 4"></button>
            <button type="button" class="hero__dot h-2.5 w-2.5 rounded-full bg-white/50 hover:bg-white hover:w-6 transition-all duration-500 ease-out" data-index="4" aria-label="Slide 5"></button>
        </div>
    </div>
</section>