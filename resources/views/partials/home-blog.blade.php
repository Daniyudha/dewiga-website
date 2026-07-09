{{-- BLOG SECTION --}}
<section id="blog" class="py-24 bg-[#f8fdfb]">
    <div class="container mx-auto px-6">

        {{-- Section Header --}}
        <div class="text-center mb-14">
            <span class="text-[#00a877] font-semibold text-xs uppercase tracking-wider block mb-3">@lang('messages.blog.subtitle')</span>
            <h2 class="font-serif text-3xl md:text-5xl font-bold text-[#053d2c]">@lang('messages.blog.title')</h2>
        </div>

        {{-- Blog Grid --}}
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($blogs as $blog)
                <article class="group bg-white rounded-[2rem] overflow-hidden border border-neutral-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 flex flex-col">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="block">
                        <div class="relative h-52 overflow-hidden">
                            <img data-src="{{ Storage::url($blog->image) }}"
                                 alt="{{ $blog->title }}"
                                 class="lazy_img w-full h-full object-cover transition duration-700 group-hover:scale-110" />
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent"></div>
                            @if ($blog->category)
                                <div class="absolute top-4 left-4">
                                    <span class="bg-white/95 backdrop-blur-md text-[#053d2c] px-4 py-1.5 rounded-full text-xs font-semibold shadow-sm">{{ $blog->category->name }}</span>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-[#053d2c] shadow-md opacity-0 group-hover:opacity-100 transition-opacity duration-300 hover:bg-[#00a877] hover:text-white">
                                <i class="bx bx-right-arrow-alt"></i>
                            </div>
                        </div>
                    </a>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-4 text-xs text-neutral-500 mb-3">
                            <span class="flex items-center gap-1">
                                <i class="bx bx-calendar"></i>
                                {{ date('d M Y', strtotime($blog->created_at)) }}
                            </span>
                            <span class="flex items-center gap-1">
                                <i class="bx bx-show"></i>
                                {{ $blog->reads }}
                            </span>
                        </div>
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <h2 class="font-serif text-lg font-bold text-[#053d2c] line-clamp-2 min-h-[56px] group-hover:text-[#00a877] transition-colors">{{ $blog->title }}</h2>
                        </a>
                        <p class="text-neutral-600 mt-3 text-sm leading-relaxed line-clamp-3 min-h-[63px]">{{ $blog->excerpt }}</p>
                        <div class="mt-auto pt-5 flex items-center justify-between">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="text-[#00a877] font-semibold flex items-center gap-2 text-sm hover:gap-3 transition-all">
                                @lang('messages.blog.read_article')
                                <i class="bx bx-right-arrow-alt text-lg"></i>
                            </a>
                            <button data-title="{{ $blog->title }}" data-route="{{ route('blog.show', $blog->slug) }}"
                                    class="share-button w-10 h-10 rounded-full bg-[#e8fbf3] text-[#00a877] flex items-center justify-center hover:bg-[#00a877] hover:text-white transition-all">
                                <i class="bx bx-share-alt"></i>
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        {{-- See All Button --}}
        <div class="text-center mt-14">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 bg-[#00a877] hover:bg-[#009065] text-white px-8 py-4 rounded-full font-semibold transition duration-300 transform hover:-translate-y-0.5">
                @lang('messages.blog.see_all')
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div>
</section>
</write_to_file>