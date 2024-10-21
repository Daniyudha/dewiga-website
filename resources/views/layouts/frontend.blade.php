<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />


    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="Gabugan Tourism Village" />
    <meta property="og:url" content="https://desawisatagabugan.com" />
    <meta property="og:site_name" content="Gabugan Tourism Village" />
    <meta property="og:image" content="">

    <!--=============== BOXICONS ===============-->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />

    <!--=============== SWIPER CSS ===============-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/libraries/swiper-bundle.min.css') }}" />

    <!--=============== CSS ===============-->
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.css">
    @stack('style-alt')
    <title>Desa Wisata Gabugan | Natural Rural Feel</title>

    {{-- Favicon --}}
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon/favicon.ico') }}" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('frontend/assets/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16"
        href="{{ asset('frontend/assets/favicon/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/assets/favicon/site.webmanifest') }}">
</head>

<body>
    <!--==================== HEADER ====================-->
    <header class="header" id="header">
        <nav class="nav container">
            <a href="{{ route('homepage') }}" class="nav__logo"><img class="brand-logo"
                    src="{{ asset('frontend/assets/img/brand-logo.png') }}" alt="" srcset=""> GABUGAN</a>

            <div class="nav__menu">
                <ul class="nav__list">
                    <li class="nav__item">
                        <a href="{{ route('homepage') }}"
                            class="nav__link {{ request()->is('/') ? ' active-link' : '' }}">
                            <i class="bx bx-home-alt"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ route('travel_package.index') }}"
                            class="nav__link {{ request()->is('travel-packages') || request()->is('travel-packages/*') ? ' active-link' : '' }}">
                            <i class="bx bx-building-house"></i>
                            <span>Package</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ route('blog.index') }}"
                            class="nav__link {{ request()->is('blogs') || request()->is('blogs/*') ? ' active-link' : '' }}">
                            <i class="bx bx-book"></i>
                            <span>Blog</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ route('gallery') }}"
                            class="nav__link {{ request()->is('gallery') ? ' active-link' : '' }}">
                            <i class="bx bx-image"></i>
                            <span>Gallery</span>
                        </a>
                    </li>
                    <li class="nav__item">
                        <a href="{{ route('contact') }}"
                            class="nav__link {{ request()->is('contact') ? ' active-link' : '' }}">
                            <i class="bx bx-phone"></i>
                            <span>Contact</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- theme -->
            <i class="bx bx-moon change-theme" id="theme-button"></i>

            <a target="_blank"
                href="https://api.whatsapp.com/send?phone=6281328856252&text=Hello, I would like to consult regarding a visit to the Gabugan Tourism Village?"
                class="button nav__button">Booking Now</a>
        </nav>
    </header>

    <!--==================== MAIN ====================-->
    <main class="main">
        @yield('content')
    </main>

    <div class="notification" id="notification-message"></div>
    <!-- start contact Area -->
    <section class="contact-area" id="contact-form">
        <div class="container">
            <div class="container">
                <h1 class="section__title">If you need, Just drop us a line</h1>
                <p>we will provide the information you need</p>
            </div>
            <form id="myForm" action="{{ route('send.email') }}" method="post"
                class="form-area contact-form text-right">
                @csrf
                <div class="row">
                    <div class="col-lg-6 form-group">
                        <input name="name" placeholder="Enter your name" onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Enter your name'" class="common-input mb-20 form-control"
                            required="" type="text">

                        <input name="email" placeholder="Enter email address"
                            pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Enter email address'" class="common-input mb-20 form-control"
                            required="" type="email">

                        <input name="subject" placeholder="Enter your subject" onfocus="this.placeholder = ''"
                            onblur="this.placeholder = 'Enter your subject'" class="common-input mb-20 form-control"
                            required="" type="text">
                    </div>
                    <div class="col-lg-6 form-group">
                        <textarea class="common-textarea mt-10 form-control" name="message" placeholder="Messege"
                            onfocus="this.placeholder = ''" onblur="this.placeholder = 'Messege'" required=""></textarea>
                        <button id="submitBtn" type="submit" class="button contact__card-button">Send Message</button>
                        <span class="spinner-border d-none" id="loadingSpinner" role="status" aria-hidden="true"></span>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </section>
    <!-- end contact Area -->

    <!--==================== FOOTER ====================-->
    <footer class="footer section">
        <div class="footer__container container grid">
            <div>
                <a href="{{ route('homepage') }}" class="footer__logo">
                    <img class="footer-logo" src="{{ asset('frontend/assets/img/brand-logo.png') }}"
                        alt="Desa Gabugan" srcset="">
                </a>
                <p class="footer__description">
                    Our vision is to provide the best service <br> and share the best experience for many people
                </p>
            </div>

            <div class="footer__content">
                <div>
                    <h3 class="footer__title">About</h3>

                    <ul class="footer__links">
                        <li>
                            <a href="{{ route('about-us') }}" class="footer__link">About Us</a>
                        </li>
                        <li>
                            <a href="{{ route('gallery') }}" class="footer__link">Gallery</a>
                        </li>
                        <li>
                            <a href="{{ route('blog.index') }}" class="footer__link">News & Blog</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer__title">Company</h3>

                    <ul class="footer__links">
                        <li>
                            <a href="{{ route('about-us') }}" class="footer__link">How We Work?
                            </a>
                        </li>
                        {{-- <li>
                            <a href="#" class="footer__link">Capital </a>
                        </li>
                        <li>
                            <a href="#" class="footer__link"> Security</a>
                        </li> --}}
                    </ul>
                </div>
                <div>
                    <h3 class="footer__title">Support</h3>

                    <ul class="footer__links">
                        <li>
                            <a href="#" class="footer__link">FAQs </a>
                        </li>
                        {{-- <li>
                            <a href="#" class="footer__link">Support center
                            </a>
                        </li> --}}
                        <li>
                            <a href="#" class="footer__link"> Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h3 class="footer__title">Follow us</h3>

                    <ul class="footer__social">
                        <a href="https://web.facebook.com/desa.gabugan" target="_blank" class="footer__social-link">
                            <i class="bx bxl-facebook-circle"></i>
                        </a>
                        <a href="https://www.instagram.com/desawisatagabugan/" target="_blank"
                            class="footer__social-link">
                            <i class="bx bxl-instagram-alt"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?phone=6281328856252&text=Hello, I would like to consult regarding a visit to the Gabugan Tourism Village?"
                            class="footer__social-link">
                            <i class="bx bxl-whatsapp"></i>
                        </a>
                    </ul>
                </div>
            </div>
        </div>

        <div class="footer__info container">
            <span class="footer__copy">
                &#169; Desa Wisata Gabugan. 2024. All rigths reserved.
            </span>
            <div class="footer__privacy">
                {{-- <a href="#">Terms & Agreements</a>
                <a href="#">Privacy Policy</a> --}}
                <a href="https://www.gegacreative.com/" target="_blank">Build with love by GEGA CREATIVE</a>
            </div>
        </div>
    </footer>

    <!--========== SCROLL UP ==========-->
    <a href="#" class="scrollup" id="scroll-up">
        <i class="bx bx-chevrons-up"></i>
    </a>

    <!--=============== popup image ===============-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.8.1/baguetteBox.min.js"></script>
    <script>
        baguetteBox.run('.tz-gallery');
    </script>

    <!--=============== SCROLLREVEAL ===============-->
    <script src="{{ asset('frontend/assets/libraries/scrollreveal.min.js') }}"></script>

    <!--=============== SWIPER JS ===============-->
    <script src="{{ asset('frontend/assets/libraries/swiper-bundle.min.js') }}"></script>

    <!--=============== MAIN JS ===============-->
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#myForm').submit(function (e) {
                e.preventDefault(); // Menghentikan pengiriman formulir standar
                // Menampilkan indikator loading dan mengubah teks tombol
                $('#submitBtn').prop('disabled', true);
                $('#submitBtn').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
                // Mengirimkan data formulir dengan AJAX
                $.ajax({
                    type: "POST",
                    url: "{{ route('send.email') }}",
                    data: $('#myForm').serialize(), // Mengambil data formulir
                    success: function (response) {
                        $('#myForm')[0].reset(); // Mengosongkan formulir

                        // Menampilkan notifikasi dengan animasi fadeIn
                        $('#notification-message').removeClass('error').text(response.message).fadeIn();

                        // Menyembunyikan notifikasi setelah 3 detik
                        setTimeout(function () {
                            $('#notification-message').fadeOut();
                        }, 3000);
                    },
                    error: function (xhr, status, error) {
                        // Menampilkan notifikasi error dengan animasi fadeIn
                        $('#notification-message').addClass('error').text('Gagal mengirim email. Silakan coba lagi nanti.').fadeIn();

                        // Menyembunyikan notifikasi error setelah 3 detik
                        setTimeout(function () {
                            $('#notification-message').fadeOut();
                        }, 3000);
                    },
                    complete: function () {
                        // Menyembunyikan indikator loading dan mengembalikan teks tombol
                        $('#submitBtn').prop('disabled', false);
                        $('#submitBtn').html('Send Message');
                    }
                });
            });
        });
    </script>

    @stack('script-alt')
</body>

</html>
