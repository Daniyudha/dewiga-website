@extends('layouts.frontend')

@section('title', __('messages.seo.blog_title'))
@section('meta_description', __('messages.seo.blog_desc'))
@section('og_title', __('messages.seo.blog_title'))
@section('og_description', __('messages.seo.blog_desc'))

@section('content')
    {{-- HERO BLOG --}}
    <section class="relative min-h-[650px] flex items-end overflow-hidden">

    <img
        class="absolute inset-0 w-full h-full object-cover"
        src="{{ asset('frontend/assets/img/top-blog.jpg') }}"
        alt="@lang('messages.nav.blog')"
    >

    {{-- Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

    <div class="container-custom relative z-10 pb-20">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-white/70 mb-8">

            <a
                href="{{ route('homepage') }}"
                class="hover:text-white transition"
            >
                @lang('messages.nav.home')
            </a>

            <i class="bx bx-chevron-right"></i>

            <span class="text-white">
                @lang('messages.nav.blog')
            </span>

        </div>

        {{-- Badge --}}
        <span class="inline-flex items-center px-5 py-2 rounded-full bg-secondary text-primary-900 text-sm font-semibold mb-5">

            @lang('messages.blog.hero_subtitle')

        </span>

        {{-- Title --}}
        <h1 class="font-heading text-4xl md:text-6xl lg:text-7xl text-white font-bold max-w-4xl leading-tight">

            @lang('messages.nav.blog')

        </h1>

        {{-- Description --}}
        <p class="text-primary-100 text-lg mt-6 max-w-2xl leading-relaxed">

            @lang('messages.blog.hero_desc')

        </p>

        {{-- Bottom Info --}}
        <div class="flex flex-wrap items-center gap-8 mt-10">

            <div class="flex items-center gap-3 text-white">

                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center">

                    <i class="bx bx-news text-xl"></i>

                </div>

                <div>

                    <p class="text-xs text-primary-200">
                        @lang('messages.blog.articles')
                    </p>

                    <p class="font-semibold">
                        {{ $blogs->count() }}+
                    </p>

                </div>

            </div>

            <div class="flex items-center gap-3 text-white">

                <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center">

                    <i class="bx bx-book-open text-xl"></i>

                </div>

                <div>

                    <p class="text-xs text-primary-200">
                        @lang('messages.blog.content')
                    </p>

                    <p class="font-semibold">
                        @lang('messages.blog.content_value')
                    </p>

                </div>

            </div>

        </div>

    </div>

    </section>


    {{-- BLOG LIST --}}
    <section class="section-padding bg-primary-50/30">
    <div class="container-custom">
        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-3 mb-14">
            <a
                href="{{ route('blog.index') }}"
                class="{{ !request()->category
                    ? 'bg-primary text-white border-primary'
                    : 'bg-white text-primary border-primary/20 hover:bg-primary hover:text-white' }}
                    px-6 py-3 rounded-full border font-medium transition-all duration-300"
            >
                @lang('messages.blog.all')
            </a>
            @foreach ($categories ?? [] as $category)
                <a
                    href="{{ route('blog.index', ['category' => $category->slug]) }}"
                    class="{{ request()->category === $category->slug
                        ? 'bg-primary text-white border-primary'
                        : 'bg-white text-primary border-primary/20 hover:bg-primary hover:text-white' }}
                        px-6 py-3 rounded-full border font-medium transition-all duration-300"
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>
        @if ($blogs->count() > 0)
            <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                @foreach ($blogs as $blog)
                    <article
                        class="group bg-white rounded-[28px] overflow-hidden border border-slate-100 shadow-md hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col"
                    >
                        <a
                            href="{{ route('blog.show', $blog->slug) }}"
                            class="block"
                        >
                            {{-- Image --}}
                            <div class="relative h-64 overflow-hidden">
                                <img
                                    data-src="{{ Storage::url($blog->image) }}"
                                    alt="{{ $blog->title }}"
                                    class="lazy_img w-full h-full object-cover transition duration-700 group-hover:scale-110"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                @if ($blog->category)
                                    <div class="absolute top-5 left-5">
                                        <span class="bg-white/95 backdrop-blur-md text-primary px-4 py-2 rounded-full text-xs font-semibold">
                                            {{ $blog->category->name }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </a>
                        {{-- Content --}}
                        <div class="p-6 flex flex-col flex-1">
                            {{-- Date --}}
                            <div class="flex items-center gap-4 text-xs text-primary-500 mb-3">
                                <span class="flex items-center gap-1">
                                    <i class="bx bx-calendar"></i>
                                    {{ date('d M Y', strtotime($blog->created_at)) }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <i class="bx bx-show"></i>
                                    {{ $blog->reads }}
                                </span>
                            </div>
                            {{-- Title --}}
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <h2 class="text-xl font-bold text-primary-900 line-clamp-2 min-h-[60px] group-hover:text-primary transition">
                                    {{ $blog->title }}
                                </h2>
                            </a>
                            {{-- Excerpt --}}
                            <p class="text-primary-600 mt-3 line-clamp-3 min-h-[72px]">
                                {{ $blog->excerpt }}
                            </p>
                            {{-- Footer --}}
                            <div class="mt-auto pt-6 flex items-center justify-between">
                                <a
                                    href="{{ route('blog.show', $blog->slug) }}"
                                    class="text-primary font-semibold flex items-center gap-2"
                                >
                                    @lang('messages.blog.read_article')
                                    <i class="bx bx-right-arrow-alt text-xl"></i>
                                </a>
                                <button
                                    data-title="{{ $blog->title }}"
                                    data-route="{{ route('blog.show', $blog->slug) }}"
                                    class="share-button w-11 h-11 rounded-full bg-primary-50 text-primary flex items-center justify-center hover:bg-primary hover:text-white transition"
                                >
                                    <i class="bx bx-share-alt"></i>
                                </button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-[32px] p-16 text-center shadow-lg">
                <i class="bx bx-news text-6xl text-primary/30 mb-4"></i>
                <h3 class="text-2xl font-bold text-primary-900 mb-2">
                    @lang('messages.blog.empty_title')
                </h3>
                <p class="text-primary-500">
                    @lang('messages.blog.empty_desc')
                </p>
            </div>
        @endif
    </div>

    </section>

@endsection
