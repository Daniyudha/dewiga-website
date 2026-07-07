@extends('layouts.frontend')

@section('title', $blog->title . ' - ' . __('messages.seo.blog_title'))
@section('meta_description', strip_tags($blog->excerpt))
@section('og_title', $blog->title)
@section('og_description', strip_tags($blog->excerpt))
@section('og_image', Storage::url($blog->image))
@section('og_type', 'article')
@section('twitter_title', $blog->title)
@section('twitter_description', strip_tags($blog->excerpt))
@section('twitter_image', Storage::url($blog->image))

@section('schema')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "{{ $blog->title }}",
    "description": "{{ strip_tags($blog->excerpt) }}",
    "image": "{{ Storage::url($blog->image) }}",
    "datePublished": "{{ $blog->created_at->toIso8601String() }}",
    "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
    "author": {
        "@type": "Organization",
        "name": "Desa Wisata Gabugan"
    }
}
</script>
@endsection

@section('content')
    {{-- HERO --}}
    <section class="relative min-h-[600px] overflow-hidden">

        <img
            src="{{ Storage::url($blog->image) }}"
            alt="{{ $blog->title }}"
            class="absolute inset-0 w-full h-full object-cover"
        >

        {{-- Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/55 to-black/40"></div>

        <div class="relative z-10 container-custom min-h-[600px] flex items-end pb-20">

            <div class="max-w-4xl">

                @if ($blog->category)
                    <span class="inline-flex items-center gap-2 bg-secondary text-primary-900 px-5 py-2 rounded-full text-sm font-semibold mb-6">
                        <i class="bx bx-folder"></i>
                        {{ $blog->category->name }}
                    </span>
                @endif

                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6">
                    {{ $blog->title }}
                </h1>

                <div class="flex flex-wrap items-center gap-6 text-primary-100">

                    <div class="flex items-center gap-2">
                        <i class="bx bx-calendar"></i>
                        {{ date('d F Y', strtotime($blog->created_at)) }}
                    </div>

                    <div class="flex items-center gap-2">
                        <i class="bx bx-show"></i>
                        {{ number_format($blog->reads) }}
                        @lang('messages.blog.reads')
                    </div>

                </div>

            </div>

        </div>

    </section>

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b border-stone-100">
        <div class="container-custom py-4">

            <div class="flex flex-wrap items-center gap-2 text-sm text-primary-500">

                <a href="{{ route('homepage') }}" class="hover:text-primary">
                    @lang('messages.nav.home')
                </a>

                <i class="bx bx-chevron-right"></i>

                <a href="{{ route('blog.index') }}" class="hover:text-primary">
                    @lang('messages.nav.blog')
                </a>

                @if ($blog->category)
                    <i class="bx bx-chevron-right"></i>

                    <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}"
                    class="hover:text-primary">
                        {{ $blog->category->name }}
                    </a>
                @endif

                <i class="bx bx-chevron-right"></i>

                <span class="text-primary font-medium truncate">
                    {{ Str::limit($blog->title, 50) }}
                </span>

            </div>

        </div>
    </section>

    {{-- CONTENT --}}
    <section class="section-padding" id="blog">
        <div class="container-custom">
            <div class="grid lg:grid-cols-[1fr_340px] gap-8 lg:gap-12">
                {{-- LEFT: Main Content --}}
                <div class="blog__detail min-w-0">
                    {{-- Meta --}}
                    <div class="bg-white rounded-3xl shadow-lg p-6 mb-8">

                        <div class="grid sm:grid-cols-3 gap-6">

                            <div>
                                <span class="text-xs text-primary-400 uppercase">
                                    @lang('messages.blog.publish_date')
                                </span>

                                <p class="font-semibold text-primary-900 mt-1">
                                    {{ date('d F Y', strtotime($blog->created_at)) }}
                                </p>
                            </div>

                            @if ($blog->category)
                                <div>
                                    <span class="text-xs text-primary-400 uppercase">
                                        @lang('messages.blog.categories')
                                    </span>

                                    <p class="font-semibold text-primary-900 mt-1">
                                        {{ $blog->category->name }}
                                    </p>
                                </div>
                            @endif

                            <div>
                                <span class="text-xs text-primary-400 uppercase">
                                    @lang('messages.blog.read_count')
                                </span>

                                <p class="font-semibold text-primary-900 mt-1">
                                    {{ number_format($blog->reads) }} @lang('messages.blog.times')
                                </p>
                            </div>

                        </div>

                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-[32px] shadow-xl p-8 md:p-10">

                        <div class="blog-content prose prose-lg max-w-none">

                            {!! $blog->description !!}

                        </div>

                    </div>

                    {{-- Share Buttons --}}
                    <div class="bg-primary-50 rounded-3xl p-6 mt-8">

                        <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4">

                            <div>
                                <h3 class="font-bold text-primary-900 text-lg">
                                    @lang('messages.blog.share_subtitle')
                                </h3>

                                <p class="text-sm text-primary-600">
                                    @lang('messages.blog.share_desc')
                                </p>
                            </div>

                            <div class="flex items-center gap-2">

                                {{-- WhatsApp --}}
                                <a
                                    href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . route('blog.show', $blog->slug)) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    title="@lang('messages.blog.share_whatsapp')"
                                    class="w-10 h-10 rounded-full bg-white text-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300"
                                >
                                    <i class="bx bxl-whatsapp text-xl text-[#25D366]"></i>
                                </a>

                                {{-- Facebook --}}
                                <a
                                    href="https://www.facebook.com/sharer/sharer.php?u={{ route('blog.show', $blog->slug) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    title="@lang('messages.blog.share_facebook')"
                                    class="w-10 h-10 rounded-full bg-white text-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300"
                                >
                                    <i class="bx bxl-facebook text-xl text-[#1877F2]"></i>
                                </a>

                                {{-- Twitter / X --}}
                                <a
                                    href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ route('blog.show', $blog->slug) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    title="@lang('messages.blog.share_twitter')"
                                    class="w-10 h-10 rounded-full bg-white text-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300"
                                >
                                    <i class="bx bxl-twitter text-xl text-black"></i>
                                </a>

                                {{-- Copy Link --}}
                                <button
                                    class="copy-button w-10 h-10 rounded-full bg-white text-primary-700 border border-primary-100 flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all duration-300"
                                    data-url="{{ route('blog.show', $blog->slug) }}"
                                    title="@lang('messages.blog.copy_link')"
                                >
                                    <i class="bx bx-copy text-xl"></i>
                                </button>

                            </div>

                        </div>

                    </div>
                </div>

                {{-- RIGHT: Sidebar --}}
                <div class="space-y-6 lg:sticky lg:top-24 self-start">
                    {{-- Categories Widget --}}
                    @if ($categories->count() > 0)
                        <div class="bg-white rounded-[28px] p-6 shadow-xl border border-stone-100">
                            <h3 class="font-bold text-lg text-primary-900 mb-5">
                                @lang('messages.blog.categories')
                            </h3>
                            <ul class="space-y-1">
                                @foreach ($categories as $category)
                                    <li>
                                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                                           class="flex items-center gap-2 text-sm text-primary-700 p-2 rounded-lg hover:bg-primary-50 hover:text-primary hover:pl-4 transition-all">
                                            <i class="bx bx-folder text-secondary"></i>
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Popular Packages Widget --}}
                    @if ($travel_packages->count() > 0)
                        <div class="bg-white rounded-2xl p-6 shadow-card">
                            <h3 class="font-heading text-primary font-semibold mb-4 pb-3 border-b-2 border-secondary">
                                @lang('messages.blog.popular_packages')
                            </h3>
                            <div class="space-y-4">
                                @foreach ($travel_packages as $travel_package)
                                    <article class="group">

                                    <a
                                        href="{{ route('travel_package.show', $travel_package->slug) }}"
                                        class="flex gap-4 p-3 rounded-2xl hover:bg-primary-50 transition"
                                    >

                                        <img
                                            src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}"
                                            alt="{{ $travel_package->location }}"
                                            class="w-24 h-24 rounded-xl object-cover"
                                        >

                                        <div class="flex-1">

                                            <p class="text-xs text-primary-500 mb-1">
                                                {{ $travel_package->type }}
                                            </p>

                                            <h4 class="font-semibold text-primary-900 line-clamp-2">
                                                {{ $travel_package->location }}
                                            </h4>

                                            <div class="text-primary font-bold mt-2">
                                                {{ formatPrice($travel_package->price) }}
                                            </div>

                                        </div>

                                    </a>

                                </article>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- WhatsApp CTA Widget --}}
                    <div class="bg-gradient-to-br from-primary to-primary-800 text-white p-6 rounded-2xl">
                        <h3 class="font-heading text-white font-semibold text-lg mb-2">@lang('messages.blog.ask_question')</h3>
                        <p class="text-primary-200 text-sm mb-4">@lang('messages.blog.ask_desc')</p>
                        <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="button button-gold w-full text-center flex items-center justify-center gap-2">
                            <i class="bx bxl-whatsapp"></i>
                            @lang('messages.whatsapp.cta')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- RELATED BLOGS --}}
    @if ($relatedBlogs->count() > 0)
        <section class="section-padding bg-primary-50/50" id="related">
            <div class="container-custom">
                <div class="text-center mb-12">
                    <span class="section-subtitle">@lang('messages.blog.related_subtitle')</span>
                    <h2 class="section-title">@lang('messages.blog.related_title')</h2>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($relatedBlogs as $relatedBlog)
                        <article class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 group">
                            <div class="relative overflow-hidden">
                                <img data-src="{{ Storage::url($relatedBlog->image) }}"
                                     alt="{{ $relatedBlog->title }}"
                                     class="w-full h-48 object-cover lazy_img group-hover:scale-110 transition-transform duration-500" />
                                <a href="{{ route('blog.show', $relatedBlog->slug) }}"
                                   class="absolute inset-0 flex items-center justify-center bg-primary-900/0 group-hover:bg-primary-900/30 transition-all duration-300">
                                    <span class="w-12 h-12 rounded-full bg-secondary text-primary-900 flex items-center justify-center text-xl opacity-0 group-hover:opacity-100 -translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                        <i class="bx bx-right-arrow-alt"></i>
                                    </span>
                                </a>
                            </div>

                            <div class="p-5">
                                @if ($relatedBlog->category)
                                    <span class="inline-block text-xs font-semibold text-secondary uppercase tracking-wider mb-2">
                                        {{ $relatedBlog->category->name }}
                                    </span>
                                @endif
                                <a href="{{ route('blog.show', $relatedBlog->slug) }}">
                                    <h2 class="font-heading text-lg font-semibold text-primary-900 mb-2 line-clamp-2 hover:text-primary transition-colors">
                                        {{ $relatedBlog->title }}
                                    </h2>
                                </a>
                                <p class="text-sm text-primary-600 leading-relaxed mb-4 line-clamp-3">{{ $relatedBlog->excerpt }}</p>

                                <div class="flex items-center gap-4 pt-4 border-t border-stone-100">
                                    <div class="flex items-center gap-1.5 text-xs text-primary-500">
                                        <i class="bx bx-calendar"></i>
                                        <span>{{ date('d M Y', strtotime($relatedBlog->created_at)) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-xs text-primary-500">
                                        <i class="bx bx-show"></i>
                                        <span>{{ $relatedBlog->reads }}</span>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Status Container --}}
    <div class="status-container hidden"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('.copy-button');
            copyButtons.forEach(copyButton => {
                copyButton.addEventListener('click', event => {
                    const url = copyButton.getAttribute('data-url');
                    const clipboard = new ClipboardJS(copyButton, {
                        text: function() { return url; }
                    });
                    clipboard.on('success', function() {
                        showStatusMessage(true);
                        clipboard.destroy();
                    });
                    clipboard.on('error', function() {
                        showStatusMessage(false);
                        clipboard.destroy();
                    });
                    clipboard.onClick(event);
                });
            });
        });

        function showStatusMessage(isSuccess) {
            const statusContainer = document.querySelector('.status-container');
            const statusMessage = document.createElement('p');
            statusMessage.textContent = isSuccess ?
                '{{ __("messages.blog.copied") }}' :
                '{{ __("messages.blog.copy_failed") }}';
            statusMessage.className = 'fixed top-28 left-1/2 -translate-x-1/2 z-[999] bg-primary text-white px-6 py-3 rounded-xl shadow-2xl text-center text-sm font-medium animate-slideDown';
            statusContainer.appendChild(statusMessage);
            statusContainer.classList.remove('hidden');
            setTimeout(function() {
                statusMessage.remove();
                if (statusContainer.childElementCount === 0) {
                    statusContainer.classList.add('hidden');
                }
            }, 3000);
        }
    </script>
@endsection

@push('style-alt')
<style>
    blockquote {
        border-left: 4px solid hsl(145, 70%, 38%);
        padding-left: 1rem;
        margin: 1rem 0;
        color: #6b7280;
        font-style: italic;
    }
    .blog__detail ul li {
        list-style: disc;
        margin-left: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .blog__detail ol li {
        list-style: decimal;
        margin-left: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .blog__detail img {
        border-radius: 0.75rem;
        margin: 1.5rem 0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    .blog-content {
    color: #334155;
    line-height: 1.9;
    }

    .blog-content h2,
    .blog-content h3,
    .blog-content h4 {
        color: #0f172a;
        margin-top: 2rem;
        margin-bottom: 1rem;
        font-weight: 700;
    }

    .blog-content img {
        width: 100%;
        border-radius: 1.5rem;
        margin: 2rem 0;
        box-shadow: 0 20px 40px rgba(0,0,0,.12);
    }

    .blog-content p {
        margin-bottom: 1rem;
        text-align: justify;
    }

    .blog-content ul li {
        list-style: disc;
        margin-left: 1.5rem;
    }

    .blog-content ol li {
        list-style: decimal;
        margin-left: 1.5rem;
    }

    .blog-content blockquote {
        border-left: 5px solid #d4a017;
        padding-left: 1rem;
        margin: 2rem 0;
        font-style: italic;
        color: #64748b;
    }
</style>
@endpush
