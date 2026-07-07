@extends('layouts.frontend')

@section('title', __('messages.seo.home_title'))
@section('meta_description', __('messages.seo.home_desc'))
@section('og_title', __('messages.seo.home_title'))
@section('og_description', __('messages.seo.home_desc'))
@section('twitter_title', __('messages.seo.home_title'))
@section('twitter_description', __('messages.seo.home_desc'))

@section('content')
    {{-- HERO --}}
    @include('partials.home-hero')

    {{-- VALUE PROPOSITION --}}
    @include('partials.home-value')

    {{-- FEATURED ACTIVITIES --}}
    @include('partials.home-featured')

    {{-- POPULAR PACKAGES --}}
    @include('partials.home-popular')

    {{-- TESTIMONIALS --}}
    @include('partials.home-testimonials')

    {{-- STATISTICS --}}
    @include('partials.home-stats')

    {{-- BLOG --}}
    @include('partials.home-blog')

    {{-- GALLERY --}}
    @include('partials.home-gallery')

    {{-- VIDEO --}}
    @include('partials.home-video')

    {{-- PARTNER LOGOS --}}
    @include('partials.partner-logos')

    {{-- HERO ROTATOR SCRIPT --}}
    @include('partials.scripts-hero-rotator')

    {{-- Share Button Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.share-button').forEach(btn => {
                btn.addEventListener('click', function() {
                    const title = this.dataset.title;
                    const url = this.dataset.route;
                    if (navigator.share) {
                        navigator.share({ title, url }).catch(console.error);
                    } else {
                        this.classList.toggle('is-open');
                    }
                });
            });
        });
    </script>
@endsection