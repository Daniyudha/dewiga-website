@extends('layouts.frontend')

@section('title', $blog->title . ' - ' . __('messages.seo.blog_title'))
@section('meta_description', strip_tags($blog->excerpt))
@section('meta_keywords', $blog->meta_keywords)
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
    "author": { "@type": "Organization", "name": "Desa Wisata Gabugan" }
}
</script>
@endsection

@section('content')
    {{-- HERO --}}
    <section class="relative bg-neutral-900 overflow-hidden min-h-[55vh] flex items-end pt-24">
        <div class="absolute inset-0 z-0">
            <img src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}" class="w-full h-full object-cover opacity-30">
            <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 via-emerald-950/60 to-black/60 z-10"></div>
        </div>
        <div class="relative z-10 container mx-auto px-6 pb-16">
            <div class="max-w-4xl">
                @if ($blog->category)
                    <span class="inline-flex items-center gap-2 bg-[#00a877] text-white px-5 py-2 rounded-full text-sm font-semibold mb-6"><i class="bx bx-folder"></i> {{ $blog->category->name }}</span>
                @endif
                <h1 class="font-serif text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-6 text-white">{{ $blog->title }}</h1>
                <div class="flex flex-wrap items-center gap-6 text-neutral-200">
                    <span class="flex items-center gap-2"><i class="bx bx-calendar"></i> {{ date('d F Y', strtotime($blog->created_at)) }}</span>
                    <span class="flex items-center gap-2"><i class="bx bx-show"></i> {{ number_format($blog->reads) }} @lang('messages.blog.reads')</span>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10 overflow-hidden">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none" class="relative block w-full h-[30px] md:h-[50px] text-white fill-current">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V0C26.9,8.75,57.05,18.3,88.42,26.49,158.41,44.75,243.62,56.88,321.39,56.44Z"></path>
            </svg>
        </div>
    </section>

    {{-- BREADCRUMB --}}
    <section class="bg-white border-b border-neutral-100">
        <div class="container mx-auto px-6 py-4">
            <div class="flex flex-wrap items-center gap-2 text-sm text-neutral-500">
                <a href="{{ route('homepage') }}" class="hover:text-[#00a877]">@lang('messages.nav.home')</a>
                <i class="bx bx-chevron-right"></i>
                <a href="{{ route('blog.index') }}" class="hover:text-[#00a877]">@lang('messages.nav.blog')</a>
                @if ($blog->category)
                    <i class="bx bx-chevron-right"></i>
                    <a href="{{ route('blog.index', ['category' => $blog->category->slug]) }}" class="hover:text-[#00a877]">{{ $blog->category->name }}</a>
                @endif
                <i class="bx bx-chevron-right"></i>
                <span class="text-[#053d2c] font-medium truncate">{{ Str::limit($blog->title, 50) }}</span>
            </div>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="py-24 bg-[#f8fdfb]" id="blog">
        <div class="container mx-auto px-6">
            <div class="grid lg:grid-cols-[1fr_340px] gap-8 lg:gap-12">
                <div class="blog__detail min-w-0">
                    <div class="bg-white rounded-[2rem] shadow-lg p-6 mb-8">
                        <div class="grid sm:grid-cols-3 gap-6">
                            <div>
                                <span class="text-xs text-neutral-400 uppercase">@lang('messages.blog.publish_date')</span>
                                <p class="font-semibold text-[#053d2c] mt-1">{{ date('d F Y', strtotime($blog->created_at)) }}</p>
                            </div>
                            @if ($blog->category)
                            <div>
                                <span class="text-xs text-neutral-400 uppercase">@lang('messages.blog.categories')</span>
                                <p class="font-semibold text-[#053d2c] mt-1">{{ $blog->category->name }}</p>
                            </div>
                            @endif
                            <div>
                                <span class="text-xs text-neutral-400 uppercase">@lang('messages.blog.read_count')</span>
                                <p class="font-semibold text-[#053d2c] mt-1">{{ number_format($blog->reads) }} @lang('messages.blog.times')</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] shadow-xl p-8 md:p-10">
                        <div class="blog-content prose prose-lg max-w-none">{!! $blog->description !!}</div>
                    </div>

                    <div class="bg-[#e8fbf3] rounded-3xl p-6 mt-8">
                        <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-4">
                            <div>
                                <h3 class="font-bold text-[#053d2c] text-lg">@lang('messages.blog.share_subtitle')</h3>
                                <p class="text-sm text-neutral-600">@lang('messages.blog.share_desc')</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="https://wa.me/?text={{ urlencode($blog->title . ' - ' . route('blog.show', $blog->slug)) }}" target="_blank" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all"><i class="bx bxl-whatsapp text-xl text-[#25D366]"></i></a>
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('blog.show', $blog->slug) }}" target="_blank" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all"><i class="bx bxl-facebook text-xl text-[#1877F2]"></i></a>
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ route('blog.show', $blog->slug) }}" target="_blank" class="w-10 h-10 rounded-full bg-white flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all"><i class="bx bxl-twitter text-xl text-black"></i></a>
                                <button class="copy-button w-10 h-10 rounded-full bg-white border border-neutral-100 flex items-center justify-center hover:-translate-y-1 hover:shadow-lg transition-all" data-url="{{ route('blog.show', $blog->slug) }}"><i class="bx bx-copy text-xl text-[#053d2c]"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:sticky lg:top-24 self-start">
                    @if ($categories->count() > 0)
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-neutral-100">
                        <h3 class="font-bold text-lg text-[#053d2c] mb-5">@lang('messages.blog.categories')</h3>
                        <ul class="space-y-1">
                            @foreach ($categories as $category)
                                <li><a href="{{ route('blog.index', ['category' => $category->slug]) }}" class="flex items-center gap-2 text-sm text-neutral-600 p-2 rounded-lg hover:bg-[#e8fbf3] hover:text-[#00a877] hover:pl-4 transition-all"><i class="bx bx-folder text-[#E8A838]"></i> {{ $category->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if ($travel_packages->count() > 0)
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl border border-neutral-100">
                        <h3 class="font-serif text-lg font-bold text-[#053d2c] mb-4 pb-3 border-b-2 border-[#E8A838]">@lang('messages.blog.popular_packages')</h3>
                        <div class="space-y-4">
                            @foreach ($travel_packages as $travel_package)
                                <article class="group">
                                    <a href="{{ route('travel_package.show', $travel_package->slug) }}" class="flex gap-4 p-3 rounded-2xl hover:bg-[#e8fbf3] transition">
                                        <img src="{{ Storage::url($travel_package->galleries->first()->images ?? '') }}" alt="{{ $travel_package->location }}" class="w-24 h-24 rounded-xl object-cover">
                                        <div class="flex-1">
                                            <p class="text-xs text-neutral-500 mb-1">{{ $travel_package->type }}</p>
                                            <h4 class="font-semibold text-[#053d2c] line-clamp-2">{{ $travel_package->location }}</h4>
                                            <div class="text-[#00a877] font-bold mt-2">{{ formatPrice($travel_package->price) }}</div>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <div class="bg-gradient-to-br from-[#053d2c] to-[#043424] text-white p-6 rounded-[2rem]">
                        <h3 class="font-serif font-semibold text-lg mb-2">@lang('messages.blog.ask_question')</h3>
                        <p class="text-neutral-300 text-sm mb-4">@lang('messages.blog.ask_desc')</p>
                        <a href="https://api.whatsapp.com/send?phone=6281328856252&text={{ urlencode(__('messages.whatsapp.default_message')) }}" target="_blank" class="inline-flex items-center justify-center gap-2 w-full bg-[#00a877] hover:bg-[#009065] text-white px-6 py-3 rounded-full font-medium transition text-sm"><i class="bx bxl-whatsapp"></i> @lang('messages.whatsapp.cta')</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- RELATED BLOGS --}}
    @if ($relatedBlogs->count() > 0)
    <section class="py-24 bg-white" id="related">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <span class="text-[#00a877] font-semibold text-sm uppercase tracking-wider block mb-3">@lang('messages.blog.related_subtitle')</span>
                <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">@lang('messages.blog.related_title')</h2>
            </div>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($relatedBlogs as $relatedBlog)
                    <article class="bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-xl transition-all duration-300 group">
                        <div class="relative overflow-hidden h-48">
                            <img data-src="{{ Storage::url($relatedBlog->image) }}" alt="{{ $relatedBlog->title }}" class="w-full h-full object-cover lazy_img group-hover:scale-110 transition-transform duration-500">
                            <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="absolute inset-0 flex items-center justify-center bg-[#053d2c]/0 group-hover:bg-[#053d2c]/30 transition-all">
                                <span class="w-12 h-12 rounded-full bg-[#00a877] text-white flex items-center justify-center text-xl opacity-0 group-hover:opacity-100 translate-y-4 group-hover:translate-y-0 transition-all"><i class="bx bx-right-arrow-alt"></i></span>
                            </a>
                        </div>
                        <div class="p-6">
                            @if ($relatedBlog->category)
                                <span class="inline-block text-xs font-semibold text-[#E8A838] uppercase tracking-wider mb-2">{{ $relatedBlog->category->name }}</span>
                            @endif
                            <a href="{{ route('blog.show', $relatedBlog->slug) }}"><h2 class="font-serif text-lg font-bold text-[#053d2c] mb-2 line-clamp-2 hover:text-[#00a877] transition-colors">{{ $relatedBlog->title }}</h2></a>
                            <p class="text-sm text-neutral-600 leading-relaxed mb-4 line-clamp-3">{{ $relatedBlog->excerpt }}</p>
                            <div class="flex items-center gap-4 pt-4 border-t border-neutral-100">
                                <span class="flex items-center gap-1.5 text-xs text-neutral-500"><i class="bx bx-calendar"></i> {{ date('d M Y', strtotime($relatedBlog->created_at)) }}</span>
                                <span class="flex items-center gap-1.5 text-xs text-neutral-500"><i class="bx bx-show"></i> {{ $relatedBlog->reads }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="status-container hidden"></div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.copy-button').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    const url = this.getAttribute('data-url');
                    const clipboard = new ClipboardJS(this, { text: () => url });
                    clipboard.on('success', () => { showStatusMessage(true); clipboard.destroy(); });
                    clipboard.on('error', () => { showStatusMessage(false); clipboard.destroy(); });
                    clipboard.onClick(e);
                });
            });
        });
        function showStatusMessage(isSuccess) {
            const c = document.querySelector('.status-container');
            const m = document.createElement('p');
            m.textContent = isSuccess ? '{{ __("messages.blog.copied") }}' : '{{ __("messages.blog.copy_failed") }}';
            m.className = 'fixed top-28 left-1/2 -translate-x-1/2 z-[999] bg-[#00a877] text-white px-6 py-3 rounded-xl shadow-2xl text-center text-sm font-medium animate-slideDown';
            c.appendChild(m); c.classList.remove('hidden');
            setTimeout(() => { m.remove(); if (c.childElementCount === 0) c.classList.add('hidden'); }, 3000);
        }
    </script>
@endsection

@push('style-alt')
<style>
    .blog-content { color: #334155; line-height: 1.9; }
    .blog-content h2, .blog-content h3, .blog-content h4 { color: #053d2c; margin-top: 2rem; margin-bottom: 1rem; font-weight: 700; font-family: 'Playfair Display', serif; }
    .blog-content img { width: 100%; border-radius: 1.5rem; margin: 2rem 0; box-shadow: 0 20px 40px rgba(0,0,0,.12); }
    .blog-content p { margin-bottom: 1rem; text-align: justify; }
    .blog-content ul li { list-style: disc; margin-left: 1.5rem; }
    .blog-content ol li { list-style: decimal; margin-left: 1.5rem; }
    .blog-content blockquote { border-left: 5px solid #00a877; padding-left: 1rem; margin: 2rem 0; font-style: italic; color: #64748b; }
</style>
@endpush