{{-- BLOG SECTION --}}
<section class="section-padding" id="blog">
    <div class="blog__container container-custom">
        <span class="section-subtitle text-center">@lang('messages.blog.subtitle')</span>
        <h2 class="section-title text-center">@lang('messages.blog.title')</h2>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-10">
            @foreach ($blogs as $blog)
                <article class="blog__card bg-white rounded-2xl overflow-hidden shadow-lg group hover:shadow-2xl transition-shadow duration-300 flex flex-col">
                    <div class="blog__image relative h-48 overflow-hidden">
                        <img data-src="{{ Storage::url($blog->image) }}" alt="{{ $blog->title }}"
                             class="blog__img lazy_img w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        <a href="{{ route('blog.show', $blog->slug) }}"
                           class="blog__button absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-primary shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-primary hover:text-white">
                            <i class="bx bx-right-arrow-alt"></i>
                        </a>
                    </div>
                    <div class="blog__data p-5 flex-1 flex flex-col">
                        @if ($blog->category)
                            <span class="blog__category inline-block text-xs font-semibold text-secondary uppercase tracking-wide mb-2">{{ $blog->category->name }}</span>
                        @endif
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <h2 class="blog__title font-heading text-primary-900 font-semibold text-lg leading-snug mb-2 hover:text-primary transition-colors">{{ $blog->title }}</h2>
                        </a>
                        <p class="blog__description text-sm text-primary-500 leading-relaxed mb-4">{{ $blog->excerpt }}</p>
                        <div class="mt-auto">
                            <div class="blog__footer flex items-center gap-4 pt-4 border-t border-stone-100 text-xs text-primary-400">
                                <div class="blog__reaction flex items-center gap-1">
                                    <i class="bx bx-calendar"></i>
                                    <span>{{ date('d M Y', strtotime($blog->created_at)) }}</span>
                                </div>
                                <div class="blog__reaction flex items-center gap-1">
                                    <i class="bx bx-show"></i>
                                    <span>{{ $blog->reads }}</span>
                                </div>
                                <button data-title="{{ $blog->title }}" data-route="{{ route('blog.show', $blog->slug) }}"
                                        class="blog__reaction flex items-center gap-1 ml-auto hover:text-primary transition-colors share-button">
                                    <i class="bx bx-share-alt"></i>
                                    <span>@lang('messages.blog.share')</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <a href="{{ route('blog.index') }}" class="button">
                <i class="bx bx-right-arrow-alt"></i>
                @lang('messages.blog.see_all')
            </a>
        </div>
    </div>
</section>