{{-- SECTION 7: PARTNER LOGOS --}}
@if(isset($partnerLogos) && $partnerLogos->count() > 0)
<section class="py-16 bg-neutral-50/50 border-t border-b border-neutral-100">
    <div class="container mx-auto px-6 max-w-6xl">
        <div class="text-center mb-12">
            <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-2">@lang('messages.logos.subtitle')</span>
            <h2 class="font-serif text-2xl md:text-3xl font-bold text-[#053d2c]">@lang('messages.logos.title')</h2>
        </div>

        {{-- 
          Menggunakan flex-wrap & justify-center agar jika logo bertambah banyak, 
          tata letak otomatis turun ke baris baru dan tetap seimbang di tengah.
        --}}
        <div class="flex flex-wrap items-center justify-center gap-4 md:gap-6">
            @foreach ($partnerLogos as $logo)
                {{-- 
                  Setiap logo dibungkus dalam "card" putih dengan ukuran mutlak yang sama (fixed-size).
                  Ini membuat visual berat logo lingkaran dan memanjang menjadi seimbang.
                --}}
                @php
                    $wrapperClass = "group flex items-center justify-center p-2 w-auto h-24 md:w-auto md:h-28 bg-transparent transition-all duration-300 hover:-translate-y-1";
                @endphp

                @if($logo->url)
                    <a href="{{ $logo->url }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="{{ $wrapperClass }}"
                       title="{{ $logo->name }}">
                        <img class="lazy_img max-h-12 md:max-h-16 max-w-full object-contain filter grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </a>
                @else
                    <div class="{{ $wrapperClass }}"
                         title="{{ $logo->name }}">
                        <img class="lazy_img max-h-12 md:max-h-16 max-w-full object-contain filter grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-300"
                             data-src="{{ asset('storage/' . $logo->image) }}"
                             alt="{{ $logo->name }}" />
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif