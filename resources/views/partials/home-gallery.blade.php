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