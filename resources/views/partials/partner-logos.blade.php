{{-- PARTNER LOGOS COMPONENT --}}
@if(isset($partnerLogos) && $partnerLogos->count() > 0)
<section class="py-12 md:py-16 bg-white">
    <div class="container-custom px-6 md:px-8 max-w-5xl">
        <div class="text-center mb-10">
            <span class="section-subtitle">@lang('messages.logos.subtitle')</span>
            <h2 class="section-title">@lang('messages.logos.title')</h2>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
            @foreach ($partnerLogos as $logo)
                @if($logo->url)
                    <a href="{{ $logo->url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300"
                       title="{{ $logo->name }}">
                        <img class="lazy_img max-h-14 md:max-h-16 w-auto"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </a>
                @else
                    <div class="opacity-60 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-300"
                         title="{{ $logo->name }}">
                        <img class="lazy_img max-h-14 md:max-h-16 w-auto"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
