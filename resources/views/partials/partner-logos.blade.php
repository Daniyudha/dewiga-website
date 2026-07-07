{{-- PARTNER LOGOS COMPONENT --}}
@if(isset($partnerLogos) && $partnerLogos->count() > 0)
<section class="py-16 bg-white">
    <div class="container-custom">
        <div class="text-center mb-10">
            <span class="section-subtitle">@lang('messages.logos.subtitle')</span>
            <h2 class="section-title">@lang('messages.logos.title')</h2>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-x-12 gap-y-8">
            @foreach ($partnerLogos as $logo)
                @if($logo->url)
                    <a href="{{ $logo->url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="logos__img hover:opacity-80 transition-all duration-300"
                       title="{{ $logo->name }}">
                        <img class="lazy_img max-h-16 w-auto"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </a>
                @else
                    <div class="logos__img hover:opacity-80 transition-all duration-300"
                         title="{{ $logo->name }}">
                        <img class="lazy_img max-h-16 w-auto"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif