@extends('layouts.frontend')

@section('title', __('messages.seo.home_title'))
@section('meta_description', __('messages.seo.home_desc'))
@section('og_title', __('messages.seo.home_title'))
@section('og_description', __('messages.seo.home_desc'))
@section('twitter_title', __('messages.seo.home_title'))
@section('twitter_description', __('messages.seo.home_desc'))

@section('content')
    {{-- SECTION 1: HERO SECTION WITH SLIDER --}}
    @include('partials.home-hero')

    {{-- SECTION 2: MENGENAL LEBIH DEKAT (VALUE PROPOSITION) --}}
    @include('partials.home-value')

    {{-- SECTION 3: VIDEO PROFIL DESA --}}
    @include('partials.home-video')

    {{-- SECTION 4: PAKET WISATA (POPULAR PACKAGES SWIPER) --}}
    @include('partials.home-popular')

    {{-- SECTION 5: RAGAM KEGIATAN SERU (FEATURED ACTIVITIES) --}}
    @include('partials.home-featured')

    {{-- SECTION 6: TESTIMONIALS (SWIPER SLIDER) --}}
    @include('partials.home-testimonials')

    {{-- SECTION 7: PARTNER LOGOS --}}
    @include('partials.home-partner-logos')

    {{-- SECTION 8: BLOG SECTION --}}
    @include('partials.home-blog')

    {{-- SECTION 9: AI CALL TO ACTION (CTA) --}}
    @include('partials.home-ai-cta')

    {{-- SECTION 10: FAQ (PERTANYAAN YANG SERING DIAJUKAN) --}}
    @include('partials.home-faq')
@endsection
