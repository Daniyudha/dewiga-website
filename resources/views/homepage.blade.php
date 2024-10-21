@extends('layouts.frontend')

@section('content')
    <div class="hero">
        <h2>Explore</h2>
        <h1>Living Traditions,</h1>
        <h1>Timeless Experiences</h1>
        <p>
            This is a great time travel and enjoy <br />
            the magic of tradition.
        </p>

        <div class="background-hero">
            <img class="lazy_img hero-bg show-hero" data-src="{{ asset('frontend/assets/img/hero1.jpg') }}" alt="">
            <img class="lazy_img hero-bg" data-src="{{ asset('frontend/assets/img/hero2.jpg') }}" alt="">
            <img class="lazy_img hero-bg" data-src="{{ asset('frontend/assets/img/hero3.jpg') }}" alt="">
        </div>
    </div>
    <!--==================== HOME ====================-->
    {{-- <section>
        <div class="swiper-container">
            <div>
                <!--========== ISLANDS 1 ==========-->
                <section class="islands">
                    <img data-src="{{ asset('frontend/assets/img/bajak.jpg') }}" alt="" class="islands__bg" />
                    <div class="bg__overlay">
                        <div class="islands__container container">
                            <div class="islands__data" style="z-index: 99; position: relative">
                                <h2 class="islands__subtitle">
                                    Explore
                                </h2>
                                <h1 class="islands__title">
                                    Living Traditions, <br>Timeless Experiences
                                </h1>
                                <p class="islands__description">
                                    This is a great time travel and enjoy <br />
                                    the magic of tradition.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section> --}}

    <!--==================== VALUE ====================-->
    <section class="value section" id="value">
        <div class="value__container container grid">
            <div class="values__images">
                <div class="value__orbe"></div>

                <div class="value__img">
                    <img class="lazy_img" data-src="{{ asset('frontend/assets/img/value-img.jpg') }}" alt="" />
                </div>
            </div>

            <div class="value__content">
                <div class="value__data">
                    <span class="section__subtitle">Why Choose Us</span>
                    <h2 class="section__title">
                        Experience That We Promise To You
                    </h2>
                    <p class="value__description">
                        We are always ready to serve by providing the best service for you. We will give you a valuable
                        experience.
                    </p>
                </div>

                <div class="value__accordion">
                    <div class="value__accordion-item">
                        <header class="value__accordion-header">
                            <i class="bx bxs-shield-x value-accordion-icon"></i>
                            <h3 class="value__accordion-title">
                                Pleasant Village
                            </h3>
                            <div class="value__accordion-arrow">
                                <i class="bx bxs-down-arrow"></i>
                            </div>
                        </header>

                        <div class="value__accordion-content">
                            <p class="value__accordion-description">
                                We provide an interesting experience about village life and its simplicity.
                            </p>
                        </div>
                    </div>
                    <div class="value__accordion-item">
                        <header class="value__accordion-header">
                            <i class="bx bxs-x-square value-accordion-icon"></i>
                            <h3 class="value__accordion-title">
                                Affordable price for you
                            </h3>
                            <div class="value__accordion-arrow">
                                <i class="bx bxs-down-arrow"></i>
                            </div>
                        </header>

                        <div class="value__accordion-content">
                            <p class="value__accordion-description">
                                We try to accommodate your budget because holidays don't have to be expensive.
                            </p>
                        </div>
                    </div>
                    <div class="value__accordion-item">
                        <header class="value__accordion-header">
                            <i class="bx bxs-bar-chart-square value-accordion-icon"></i>
                            <h3 class="value__accordion-title">
                                Best plan for your time
                            </h3>
                            <div class="value__accordion-arrow">
                                <i class="bx bxs-down-arrow"></i>
                            </div>
                        </header>

                        <div class="value__accordion-content">
                            <p class="value__accordion-description">
                                We will provide various activities related to rural life that will leave an impression on
                                your life.
                            </p>
                        </div>
                    </div>
                    <div class="value__accordion-item">
                        <header class="value__accordion-header">
                            <i class="bx bxs-check-square value-accordion-icon"></i>
                            <h3 class="value__accordion-title">
                                Security guarantee
                            </h3>
                            <div class="value__accordion-arrow">
                                <i class="bx bxs-down-arrow"></i>
                            </div>
                        </header>

                        <div class="value__accordion-content">
                            <p class="value__accordion-description">
                                We make sure that our services have a
                                good of security
                            </p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('about-us') }}">
                    <button class="button contact__card-button learn_more">Learn More</button>
                </a>
            </div>
        </div>
    </section>

    <!--==================== POPULAR ====================-->
    <section class="section" id="popular">
        <div class="container">
            <span class="section__subtitle" style="text-align: center">Best Choice</span>
            <h2 class="section__title" style="text-align: center">
                Popular Activities
            </h2>

            <div class="popular__container swiper">
                <div class="swiper-wrapper">
                    @foreach ($travel_packages as $travel_package)
                        <article class="popular__card swiper-slide">
                            <a href="{{ route('travel_package.show', $travel_package->slug) }}">
                                <img data-src="{{ Storage::url($travel_package->galleries->first()->images) }}"
                                    alt="" class="popular__img lazy_img" />
                                <div class="popular__data">
                                    <h2 class="popular__price">
                                        <span>$</span>{{ number_format($travel_package->price, 2) }}
                                    </h2>
                                    <h3 class="popular__title">
                                        {{ $travel_package->location }}
                                    </h3>
                                    <p class="popular__description">{{ $travel_package->type }}</p>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>

                <div class="swiper-button-next">
                    <i class="bx bx-chevron-right"></i>
                </div>
                <div class="swiper-button-prev">
                    <i class="bx bx-chevron-left"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- blog -->
    <section class="blog section" id="blog">
        <div class="blog__container container">
            <span class="section__subtitle" style="text-align: center">Our Blog</span>
            <h2 class="section__title" style="text-align: center">
                The Best Trip For You
            </h2>

            <div class="blog__content grid">
                @foreach ($blogs as $blog)
                    <article class="blog__card">
                        <div class="blog__image">
                            <img data-src="{{ Storage::url($blog->image) }}" alt="" class="blog__img lazy_img" />
                            <a href="{{ route('blog.show', $blog->slug) }}" class="blog__button">
                                <i class="bx bx-right-arrow-alt"></i>
                            </a>
                        </div>

                        <div class="blog__data">
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <h2 class="blog__title">
                                    {{ $blog->title }}
                                </h2>
                            </a>
                            <p class="blog__description">
                                {{ $blog->excerpt }}
                            </p>

                            <div class="blog__footer">
                                <div class="blog__reaction">
                                    {{ date('d M Y', strtotime($blog->created_at)) }}
                                </div>
                                <div class="blog__reaction">
                                    <i class="bx bx-show"></i>
                                    <span>{{ $blog->reads }}</span>
                                </div>
                                <a>
                                    <button data-title="{{ $blog->title }}" data-route="{{ route('blog.show', $blog->slug) }}"
                                        class="blog__reaction share-button">
                                        <i class="bx bx-share-alt"></i>
                                        <span>Share</span>
                                    </button>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!--==================== Slider Image ====================-->
    <div class="tz-gallery">
        <div class="slideshow-img">
            <div class="gallery-slider-pot lazy_img">
                <div class="slide-track-pot">

                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-3.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-3.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-4.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-4.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-5.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-5.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-6.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-6.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-7.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-8.jpg') }}">
                        </a>
                    </div>

                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-3.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-3.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-4.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-4.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-5.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-5.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-6.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-6.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-1.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-7.jpg') }}">
                        </a>
                    </div>
                    <div class="slide-item-pot">
                        <a class="lightbox" href="{{ asset('frontend/assets/img/gal-2.jpg') }}">
                            <img src="{{ asset('frontend/assets/img/gal-8.jpg') }}">
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--==================== VIDEO EMBED ====================-->
    <section class="container">
        <div class="video-section lazy_img">
            <iframe class="youtube-video" src="https://www.youtube.com/embed/wqQGYi1-JdA?si=NnuW_wZO2dX2JX3z"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                allowfullscreen></iframe>
        </div>
    </section>

    <!--==================== LOGOS ====================-->
    <section class="logos" style="margin-top: 3rem; padding-bottom: 3rem">
        <div class="logos__container container grid">
            <div class="logos__img">
                <img class="lazy_img" data-src="{{ asset('frontend/assets/img/logo-WI.png') }}" alt="" />
            </div>
            <div class="logos__img">
                <img class="lazy_img" data-src="{{ asset('frontend/assets/img/sleman-tlc.png') }}" alt="" />
            </div>
            <div class="logos__img">
                <img class="lazy_img" data-src="{{ asset('frontend/assets/img/wiyata.png') }}" alt="" />
            </div>
            {{-- <div class="logos__img">
                <img data-src="{{ asset('frontend/assets/img/airbnb.png') }}" alt="" />
            </div> --}}
        </div>
    </section>

    <script src="https://cdn-script.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        let headerBackground = document.querySelectorAll(".hero-bg");

        let imageIndex = 0;

        function changeBackground() {
            headerBackground[imageIndex].classList.remove("show-hero");
            imageIndex++;

            if (imageIndex >= headerBackground.length) {
                imageIndex = 0;
            }

            headerBackground[imageIndex].classList.add("show-hero")
        }
        setInterval(changeBackground, 3000);

        // share button

        $(document).ready(function() {
            let shareButton = $('.share-button')

            shareButton.on('click', function() {
                if (navigator.share) {
                    navigator.share({
                            title: $(this).data('title'),
                            url: $(this).data('route')
                        }).then(() => {
                            console.log('Thanks for sharing!');
                        })
                        .catch(console.error);
                } else {
                    $(this).addClass('is-open');
                }
            })
        })

        // const shareButtons = document.querySelectorAll('.share-button');

        // shareButtons.forEach(shareButton => {
        //     shareButton.addEventListener('click', event => {
        //         if (navigator.share) {
        //             navigator.share({
        //                 title: '{{ $blog->title }}',
        //                 url: event.target.getAttribute('data-route')
        //             }).then(() => {
        //                 console.log('Thanks for sharing!');
        //                 console.log(event.target.getAttribute('data-route'));
        //             }).catch(console.error);
        //         } else {
        //             shareDialog.classList.add('is-open');
        //         }
        //     });
        // });

        // closeButton.addEventListener('click', event => {
        //     shareDialog.classList.remove('is-open');
        // });
    </script>
@endsection
