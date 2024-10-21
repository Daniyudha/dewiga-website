@extends('layouts.frontend')

@section('content')
    <!--==================== HOME ====================-->
    <section>
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <!--========== ISLANDS 1 ==========-->
                <section class="islands swiper-slide">
                    <img src="{{ asset('frontend/assets/img/about-top.jpg') }}" alt="" class="islands__bg" />
                    <div class="bg__overlay">
                        <div class="islands__container container">
                            <div class="islands__data">
                                <h2 class="islands__subtitle">Information</h2>
                                <h1 class="islands__title">About Us</h1>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <section class="contact section" id="">
        <div class="contact__container container grid">
            <div class="contact__images">
                <div class="contact__orbe"></div>

                <div class="contact__img">
                    <img src="{{ asset('frontend/assets/img/about-1.jpg') }}" alt="" />
                </div>
            </div>

            <div class="contact__content">
                <div class="contact__data">
                    <span class="section__subtitle">About Us</span>
                    <h2 class="section__title">What is the Gabugan Tourist Village?</h2>
                    <p class="contact__description">
                        Gabugan Tourism Village is a community-based tourism village that offers a natural, rural feel.
                        Gabugan Tourism Village has been established since 2004 and is included in the independent tourist
                        village category which of course already has a wealth of experience and awards in managing and
                        organizing tourism activities. Gabugan Tourism Village is at the foot of Mount Merapi to the south
                        of the Kaliurang tourist attraction, precisely in Donokerto Village, Kepanewonan (District) Turi,
                        Sleman Regency, Yogyakarta Special Region. Located between 2 temple tourist attractions, namely
                        Borobudur Temple and Prambanan Temple. Located 17 km from the center of Jogja city with a travel
                        time of around 45 minutes, it has an easy access route so that large vehicles such as buses can
                        easily pass through.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="value section" id="">
        <div class="value__container container grid">
            <div class="values__images">
                <div class="value__orbe"></div>

                <div class="value__img">
                    <img src="{{ asset('frontend/assets/img/about-2.jpg') }}" alt="" />
                </div>
            </div>

            <div class="value__content">
                <p class="contact__description">
                    Gabugan Tourism Village provides a recreational atmosphere that is different from tourist
                    places/attractions in general, because Gabugan Tourism Village prioritizes the concept of education
                    in accordance with the city of Jogja's nickname, namely the city of students. The activities are
                    packaged attractively in accordance with the principles of experiential tourism, namely that
                    tourists participate in activities, tourists will be invited to learn and take part in activities
                    among the village community. Gabugan Tourism Village has a strong feel of village community life and
                    upholds the traditional and cultural values of Javanese society, so that visitors truly experience
                    life in a comfortable, cool rural setting, with a panoramic view of the mountain as a backdrop and
                    mingle with the daily activities of the community.
                </p>
            </div>
        </div>
    </section>

    <section class="contact section" id="">
        <div class="contact__container container grid">
            <div class="contact__images">
                <div class="contact__orbe"></div>

                <div class="contact__img">
                    <img src="{{ asset('frontend/assets/img/value-img.jpg') }}" alt="" />
                </div>
            </div>

            <div class="contact__content">
                <div class="contact__data">
                    <p class="contact__description">
                        Gabugan Tourism Village offers activities and research based on nature, social, cultural, business
                        activities (home industry), agriculture, plantations, fisheries and animal husbandry. All activities
                        are activities that residents carry out every day, in total there are 10 activities that tourists
                        can take part in. The Gabugan tourist village has as many as 50 homestays which have been
                        standardized and can be used by tourists to stay overnight. The concept of a homestay in the Gabugan
                        Tourist Village is that it is a resident's house, so tourists apart from traveling will also have a
                        new family. Experienced local guides, some of whom have been certified as tour guides, outbound
                        guides and tracking guides, are ready to help and accompany tourists in their activities and
                        exploring the Gabugan Tourist Village.
                    </p>
                    <a href="{{ route('contact') }}">
                        <button class="button contact__card-button learn_more">Lets Talk !</button>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
