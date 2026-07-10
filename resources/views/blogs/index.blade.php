@extends('layouts.frontend')

@section('title', __('messages.seo.blog_title'))
@section('meta_description', __('messages.seo.blog_desc'))
@section('meta_keywords', 'desa wisata gabugan blog, artikel wisata sleman, berita desa wisata, budaya jawa, agrowisata salak')
@section('og_title', __('messages.seo.blog_title'))
@section('og_description', __('messages.seo.blog_desc'))
@section('og_image', asset('frontend/assets/img/top-blog.jpg'))
@section('twitter_image', asset('frontend/assets/img/top-blog.jpg'))

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[60vh] flex items-center pt-24">
        <div class="absolute inset-0 z-0">
            @if($heroSetting && $heroSetting->slides->count() > 0)
                <img src="{{ $heroSetting->slides->first()->image_url }}" alt="@lang('messages.nav.blog')" class="w-full h-full object-cover opacity-30">
            @elseif($heroSetting && $heroSetting->image_url)
                <img src="{{ $heroSetting->image_url }}" alt="@lang('messages.nav.blog')" class="w-full h-full object-cover opacity-30">
            @else
                <img src="{{ asset('frontend/assets/img/top-blog.jpg') }}" alt="@lang('messages.nav.blog')" class="w-full h-full object-cover opacity-30">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
        </div>
        <div class="container mx-auto px-6 relative z-10 text-center text-white mt-8">
            <div class="inline-flex items-center gap-2 bg-[#00a877]/90 px-4 py-1.5 rounded-full text-xs font-semibold tracking-wider text-white uppercase mb-5 mx-auto">
                @lang('messages.blog.hero_subtitle')
            </div>
            <h1 class="font-serif text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-6 text-white">
                @lang('messages.nav.blog')
            </h1>
            <p class="text-neutral-200 text-base md:text-lg max-w-3xl mx-auto leading-relaxed font-light">
                @lang('messages.blog.hero_desc')
            </p>
            <div class="flex flex-wrap items-center justify-center gap-6 mt-8">
                <div class="flex items-center gap-3 text-white">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center"><i class="bx bx-news text-xl"></i></div>
                    <div class="text-left">
                        <p class="text-xs text-neutral-300">@lang('messages.blog.articles')</p>
                        <p class="font-semibold">{{ $blogs->total() }}+</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-white">
                    <div class="w-12 h-12 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center"><i class="bx bx-book-open text-xl"></i></div>
                    <div class="text-left">
                        <p class="text-xs text-neutral-300">@lang('messages.blog.content')</p>
                        <p class="font-semibold">@lang('messages.blog.content_value')</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- BLOG LIST --}}
    <section class="py-24 bg-[#f8fdfb]">
        <div class="container mx-auto px-6">
            <div class="flex flex-wrap justify-center gap-3 mb-14">
                <a href="{{ route('blog.index') }}"
                   class="{{ !request()->category ? 'bg-[#00a877] text-white border-[#00a877]' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-[#e8fbf3] hover:text-[#00a877]' }} px-6 py-3 rounded-full border font-medium transition-all duration-300 text-sm">
                    @lang('messages.blog.all')
                </a>
                @foreach ($categories ?? [] as $category)
                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                       class="{{ request()->category === $category->slug ? 'bg-[#00a877] text-white border-[#00a877]' : 'bg-white text-neutral-600 border-neutral-200 hover:bg-[#e8fbf3] hover:text-[#00a877]' }} px-6 py-3 rounded-full border font-medium transition-all duration-300 text-sm">
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>

            @if ($blogs->count() > 0)
                <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-8">
                    @foreach ($blogs as $blog)
                        <article class="group bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="block">
                                <div class="relative h-64 overflow-hidden">
                                    <img data-src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}"
                                         class="lazy_img w-full h-full object-cover transition duration-700 group-hover:scale-110">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                                    @if ($blog->category)
                                        <div class="absolute top-5 left-5">
                                            <span class="bg-white/95 backdrop-blur-md text-[#053d2c] px-4 py-2 rounded-full text-xs font-semibold">{{ $blog->category->name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="p-6 flex flex-col flex-1">
                                <div class="flex items-center gap-4 text-xs text-neutral-500 mb-3">
                                    <span class="flex items-center gap-1"><i class="bx bx-calendar"></i> {{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                    <span class="flex items-center gap-1"><i class="bx bx-show"></i> {{ $blog->reads }}</span>
                                </div>
                                <a href="{{ route('blog.show', $blog->slug) }}">
                                    <h2 class="text-xl font-bold text-[#053d2c] line-clamp-2 min-h-[60px] group-hover:text-[#00a877] transition">{{ $blog->title }}</h2>
                                </a>
                                <p class="text-neutral-600 mt-3 line-clamp-3 min-h-[72px] text-sm">{{ $blog->excerpt }}</p>
                                <div class="mt-auto pt-6 flex items-center justify-between">
                                    <a href="{{ route('blog.show', $blog->slug) }}" class="text-[#00a877] font-semibold flex items-center gap-2 text-sm">
                                        @lang('messages.blog.read_article') <i class="bx bx-right-arrow-alt text-xl"></i>
                                    </a>
                                    <button data-title="{{ $blog->title }}" data-route="{{ route('blog.show', $blog->slug) }}"
                                            class="share-button w-11 h-11 rounded-full bg-[#e8fbf3] text-[#00a877] flex items-center justify-center hover:bg-[#00a877] hover:text-white transition">
                                        <i class="bx bx-share-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if ($blogs->hasPages())
                <div class="mt-14 flex justify-center">
                    <nav class="flex items-center gap-2" role="navigation">
                        @if ($blogs->onFirstPage())
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-neutral-300 border border-neutral-200 cursor-not-allowed"><i class="bx bx-chevron-left"></i></span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition"><i class="bx bx-chevron-left"></i></a>
                        @endif
                        @foreach ($blogs->getUrlRange(1, $blogs->lastPage()) as $page => $url)
                            @if ($page == $blogs->currentPage())
                                <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-[#00a877] text-white border border-[#00a877]">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}" class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-[#053d2c] border border-neutral-200 hover:bg-[#00a877] hover:text-white hover:border-[#00a877] transition"><i class="bx bx-chevron-right"></i></a>
                        @else
                            <span class="inline-flex items-center justify-center w-11 h-11 rounded-xl text-sm font-medium bg-white text-neutral-300 border border-neutral-200 cursor-not-allowed"><i class="bx bx-chevron-right"></i></span>
                        @endif
                    </nav>
                </div>
                @endif
            @else
                <div class="bg-white rounded-[2rem] p-16 text-center shadow-lg">
                    <i class="bx bx-news text-6xl text-[#00a877]/30 mb-4"></i>
                    <h3 class="text-2xl font-bold text-[#053d2c] mb-2">@lang('messages.blog.empty_title')</h3>
                    <p class="text-neutral-500">@lang('messages.blog.empty_desc')</p>
                </div>
            @endif
        </div>
    </section>
@endsection