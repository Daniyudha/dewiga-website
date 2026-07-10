{{-- SECTION 7: PARTNER LOGOS --}}
@if(isset($partnerLogos) && $partnerLogos->count() > 0)
<section class="py-16 bg-white border-t border-neutral-100">
    <div class="container mx-auto px-6 max-w-5xl">
        <div class="text-center mb-10">
            <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-2">@lang('messages.logos.subtitle')</span>
            <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#053d2c]">@lang('messages.logos.title')</h2>
        </div>

        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12">
            @foreach ($partnerLogos as $logo)
                @if($logo->url)
                    <a href="{{ $logo->url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="hover:opacity-80 hover:grayscale-0 transition-all duration-300"
                       title="{{ $logo->name }}">
                        <div class="h-20 md:h-20 w-full flex items-center justify-center">
                            <img class="lazy_img h-full w-full object-contain"
                                 data-src="{{ asset('storage/' . $logo->image) }}"
                                 alt="{{ $logo->name }}" />
                        </div>
                    </a>
                @else
                    <div class="hover:opacity-80 hover:grayscale-0 transition-all duration-300"
                         title="{{ $logo->name }}">
                        <div class="h-14 md:h-16 w-32 flex items-center justify-center">
                            <img class="lazy_img h-full w-full object-contain"
                                 data-src="{{ asset('storage/' . $logo->image) }}"
                                 alt="{{ $logo->name }}" />
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
