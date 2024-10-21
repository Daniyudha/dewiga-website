@extends('layouts.frontend')

@section('content')
    <!--==================== HOME ====================-->
    <section>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <!--========== ISLANDS 1 ==========-->
                <section class="islands swiper-slide">
                    <img src="{{ asset('frontend/assets/img/contact-top.jpg') }}" alt="" class="islands__bg" />
                    <div class="bg__overlay">
                        <div class="islands__container container">
                            <div class="islands__data">
                                <h2 class="islands__subtitle">Need Information</h2>
                                <h1 class="islands__title">Contact Us</h1>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
    <!--==================== CONTACT ====================-->
    <section class="contact section" id="contact">
        <div class="contact__container container grid">
            <div class="contact__images">
                <div class="contact__orbe"></div>

                <div class="contact__img">
                    <img src="{{ asset('frontend/assets/img/contact-img.jpg') }}" alt="" />
                </div>
            </div>

            <div class="contact__content">
                <div class="contact__data">
                    <span class="section__subtitle">Need Help</span>
                    <h2 class="section__title">Don't hesitate to contact us</h2>
                    <p class="contact__description">
                        Are you still confused about the information on this website page? Don't worry, just contact us for
                        more information.
                    </p>
                </div>

                <div class="contact__card">
                    <div class="contact__card-box">
                        <div class="contact__card-info">
                            <i class="bx bxs-phone-call"></i>
                            <div>
                                <h3 class="contact__card-title">Call</h3>
                                <p class="contact__card-description">6281328856252</p>
                            </div>
                        </div>
                        <a href="tel:6281328856252">
                            <button class="button contact__card-button">Call Now</button>
                        </a>
                    </div>
                    <div class="contact__card-box">
                        <div class="contact__card-info">
                            <i class="bx bxs-message-rounded-dots"></i>
                            <div>
                                <h3 class="contact__card-title">Whatsapp</h3>
                                <p class="contact__card-description">6281328856252</p>
                            </div>
                        </div>
                        <a target="_blank"
                            href="https://api.whatsapp.com/send?phone=6281328856252&text=Hello, I would like to consult regarding a visit to the Gabugan Tourism Village?">
                            <button class="button contact__card-button">Chat Now</button>
                        </a>
                    </div>
                    <div class="contact__card-box">
                        <div class="contact__card-info">
                            <i class="bx bxs-envelope"></i>
                            <div>
                                <h3 class="contact__card-title">Email</h3>
                                <p class="contact__card-description">edpdewiga @gmail.com</p>
                            </div>
                        </div>
                        <a href="mailto:edpdewiga@gmail.com" target="_blank"></a>
                        <button class="button contact__card-button">
                            Email Now
                        </button>
                    </div>
                    <div class="contact__card-box">
                        <div class="contact__card-info">
                            <i class="bx bxs-phone-call"></i>
                            <div>
                                <h3 class="contact__card-title">Instagram</h3>
                                <p class="contact__card-description">Desa Wisata Gabugan</p>
                            </div>
                        </div>
                        <a href="https://www.instagram.com/desawisatagabugan/" target="_blank"></a>
                        <button class="button contact__card-button">Visit Now</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
