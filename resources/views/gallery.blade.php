@extends('layouts.frontend')

@section('title', __('messages.seo.gallery_title'))
@section('meta_description', __('messages.seo.gallery_desc'))
@section('og_title', __('messages.seo.gallery_title'))
@section('og_description', __('messages.seo.gallery_desc'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[55vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="@lang('messages.nav.gallery')" class="w-full h-full object-cover opacity-30">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="@lang('messages.nav.gallery')" class="w-full h-full object-cover opacity-30">
            @else
                <img src="{{ asset('frontend/assets/img/gallery-top.jpg') }}" alt="@lang('messages.nav.gallery')" class="w-full h-full object-cover opacity-30">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
            <div class="inline-flex items-center gap-2 bg-[#053d2c]/80 border border-[#00a877]/30 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-[#00c887] uppercase mb-5 mx-auto">
                <i class="bx bx-camera"></i>
                @lang('messages.gallery.subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.gallery.title')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.gallery.description')
            </p>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section

    {{-- SITE GALLERY FROM ADMIN (MASONRY) --}}
    @php $siteGalleries = \App\Models\SiteGallery::orderBy('order')->get(); @endphp
    @if($siteGalleries && $siteGalleries->count() > 0)
    <section class="pb-24 bg-white">
        <div class="container mx-auto px-6 py-12">
            <div class="tz-gallery masonry-grid">
                @foreach($siteGalleries as $item)
                <div class="masonry-item break-inside-avoid mb-4">
                    <a href="{{ asset('storage/' . $item->image) }}" data-fancybox="site-gallery" data-caption="{{ $item->title ?? '' }}" class="group block rounded-xl overflow-hidden relative">
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title ?? 'Gallery' }}" class="w-full h-auto object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                        @if($item->title)
                        <div class="absolute bottom-0 left-0 right-0 p-4 bg-gradient-to-t from-black/70 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <p class="text-white text-sm font-medium">{{ $item->title }}</p>
                        </div>
                        @endif
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <style>
        .masonry-grid { column-count: 2; column-gap: 1rem; }
        .masonry-item { display: inline-block; width: 100%; }
        @media (min-width: 768px) { .masonry-grid { column-count: 3; } }
        @media (min-width: 1024px) { .masonry-grid { column-count: 4; } }
    </style>
    @endif

    {{-- GALLERY FEED --}}
    <section class="py-24 bg-[#e8fbf3]">
        <div class="container mx-auto px-6">
            <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-8 border-b border-neutral-100">
                    <div>
                        <h3 class="font-serif text-2xl font-bold text-[#053d2c]">@lang('messages.gallery.instagram_feed')</h3>
                        <p class="text-neutral-500 mt-1">@lang('messages.gallery.instagram_desc')</p>
                    </div>
                    <a href="https://www.instagram.com/desawisatagabugan" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-gradient-to-r from-pink-500 via-red-500 to-yellow-500 text-white px-6 py-3 rounded-full font-semibold hover:shadow-lg transition">
                        <i class="bx bxl-instagram text-xl"></i>
                        @lang('messages.gallery.instagram_handle')
                    </a>
                </div>
                <div class="p-6 md:p-8">
                    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
                    <div class="instagram-feed-wrapper">
                        <div class="elfsight-app-c9115b0c-b45f-4b8b-a17e-d4e24a1a0c0d" data-elfsight-app-lazy></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="bg-gradient-to-br from-[#053d2c] to-[#043424] rounded-[2rem] p-12 md:p-16 text-center text-white">
                <span class="text-[#00c887] font-semibold text-xs uppercase tracking-wider block mb-3">@lang('messages.gallery.cta_badge')</span>
                <h2 class="font-serif text-white text-3xl md:text-4xl font-bold mb-4">@lang('messages.gallery.cta_title')</h2>
                <p class="text-neutral-300 max-w-2xl mx-auto mb-8 font-light">@lang('messages.gallery.cta_desc')</p>
                <a href="https://api.whatsapp.com/send?phone=6281328856252" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-semibold transition duration-300">
                    <i class="bx bxl-whatsapp text-xl"></i>
                    @lang('messages.gallery.cta_consult')
                </a>
            </div>
        </div>
    </section>

    <style>
        .eapps-instagram-feed-title { display: none !important; }
        .eapps-instagram-feed-posts-grid-load-more {
            background: linear-gradient(135deg, #14532d 0%, #1f7a44 100%) !important;
            color: white !important;
            border-radius: 999px !important;
            font-weight: 700 !important;
        }
        .eapps-instagram-feed-posts-grid-load-more:hover { transform: translateY(-4px) !important; }
        .instagram-feed-wrapper { position: relative; overflow: hidden; }
    </style>
@endsection

@push('style-alt')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css"/>
@endpush

@push('script-alt')
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Fancybox !== 'undefined') {
        Fancybox.bind('[data-fancybox="site-gallery"]', {
            groupAll: true,
            caption: function (fancybox, slide) {
                return slide.triggerEl?.getAttribute('data-caption') || '';
            },
            Image: {
                fit: 'contain',
            }
        });
    }
});
</script>
@endpush
