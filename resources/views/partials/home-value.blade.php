{{-- VALUE PROPOSITION --}}
<section id="value" class="section-padding bg-gradient-to-b from-white to-primary-50/30 overflow-hidden">
    <div class="container-custom grid lg:grid-cols-2 gap-16 items-center">

        {{-- Images --}}
        <div class="relative">
            <div class="relative h-[500px] lg:h-[600px]">
                {{-- Main Image --}}
                <div class="absolute top-0 left-0 w-[78%] h-[75%] rounded-3xl overflow-hidden border-4 border-white shadow-2xl">
                    <img class="w-full h-full object-cover"
                         src="{{ asset('frontend/assets/img/value-img.jpg') }}"
                         alt="@lang('messages.value.title')">
                </div>
                {{-- Secondary Image --}}
                <div class="absolute bottom-0 right-0 w-[55%] h-[42%] rounded-3xl overflow-hidden border-4 border-white shadow-2xl">
                    <img class="w-full h-full object-cover"
                         src="{{ asset('frontend/assets/img/value-img-2.jpg') }}"
                         alt="@lang('messages.value.title')">
                </div>
                {{-- Decorative Shape --}}
                <div class="absolute -top-8 -left-8 w-32 h-32 rounded-full bg-primary/10 blur-3xl"></div>
                <div class="absolute -bottom-8 -right-8 w-40 h-40 rounded-full bg-secondary/10 blur-3xl"></div>
            </div>
        </div>

        {{-- Content --}}
        <div>
            <span class="section-subtitle">@lang('messages.value.subtitle')</span>
            <h2 class="section-title mb-6">@lang('messages.value.title')</h2>
            <p class="text-primary-600 leading-relaxed mb-10 max-w-xl">@lang('messages.value.description')</p>

            {{-- Cards --}}
            <div class="grid sm:grid-cols-2 gap-5">
                <div class="value-card">
                    <div class="value-card-icon"><i class="bx bx-book-open"></i></div>
                    <h3 class="value-card-title">@lang('messages.value.cards.edu_tourism.title')</h3>
                    <p class="value-card-desc">@lang('messages.value.cards.edu_tourism.desc')</p>
                </div>
                <div class="value-card">
                    <div class="value-card-icon secondary"><i class="bx bx-home-heart"></i></div>
                    <h3 class="value-card-title">@lang('messages.value.cards.live_in.title')</h3>
                    <p class="value-card-desc">@lang('messages.value.cards.live_in.desc')</p>
                </div>
                <div class="value-card">
                    <div class="value-card-icon"><i class="bx bx-leaf"></i></div>
                    <h3 class="value-card-title">@lang('messages.value.cards.agriculture.title')</h3>
                    <p class="value-card-desc">@lang('messages.value.cards.agriculture.desc')</p>
                </div>
                <div class="value-card">
                    <div class="value-card-icon secondary"><i class="bx bx-mask"></i></div>
                    <h3 class="value-card-title">@lang('messages.value.cards.local_culture.title')</h3>
                    <p class="value-card-desc">@lang('messages.value.cards.local_culture.desc')</p>
                </div>
            </div>

            {{-- CTA --}}
            <div class="flex flex-wrap gap-4 mt-10">
                <a href="{{ route('about-us') }}" class="button">@lang('messages.value.learn_more')</a>
                <a href="{{ route('travel_package.index') }}" class="button-transparent button-outline">@lang('messages.nav.packages')</a>
            </div>
        </div>

    </div>
</section>