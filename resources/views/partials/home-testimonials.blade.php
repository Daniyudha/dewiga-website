{{-- TESTIMONIALS SECTION SWIPER SLIDER WITH READ MORE MODAL --}}
<section id="testimoni" class="py-24 bg-[#03291d] text-white overflow-hidden">
    <div class="container mx-auto px-6 text-center">

        <span class="text-[#00c887] font-semibold text-xs uppercase tracking-wider block mb-3">{{ __('messages.testimonials.subtitle') }}</span>
        <h2 class="font-serif text-white text-3xl md:text-5xl font-bold mb-4">
            {{ __('messages.testimonials.title') }}
        </h2>
        <p class="text-neutral-400 text-sm max-w-xl mx-auto mb-14 font-light">{{ __('messages.testimonials.subtitle') }}</p>

        {{-- Testimonial Swiper --}}
        @if($testimonials && $testimonials->count() > 0)
        <div class="relative max-w-5xl mx-auto">
            <div class="swiper testimonial-swiper !pb-14">
                <div class="swiper-wrapper !items-stretch">
                    @foreach ($testimonials as $testimonial)
                    <div class="swiper-slide !h-auto flex">
                        <div class="bg-[#053d2c] border border-white/5 p-8 md:p-10 rounded-3xl text-left shadow-lg flex flex-col w-full">
                            {{-- Stars --}}
                            <div class="flex gap-1 text-yellow-400 mb-5 shrink-0">
                                @for ($i = 0; $i < 5; $i++)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                @endfor
                            </div>

                            {{-- Content with line clamp, flex-1 to fill equal space --}}
                            <div class="flex-1 flex flex-col">
                                <div class="testimonial-text text-neutral-300 font-light italic leading-relaxed text-sm md:text-base" id="testi-text-{{ $loop->index }}">
                                    "{{ $testimonial->content }}"
                                </div>
                                @if (Str::length($testimonial->content) > 200)
                                <button onclick="openTestimonialModal('testi-full-{{ $loop->index }}')" class="text-[#00c887] text-xs font-semibold mt-3 hover:text-[#00a877] transition-colors text-left self-start shrink-0">
                                    {{ __('messages.testimonials.read_more') }} →
                                </button>
                                @endif
                            </div>

                            {{-- User info with random avatar --}}
                            <div class="flex items-center gap-4 border-t border-white/5 pt-5 mt-5 shrink-0">
                                @php
                                    $seed = md5($testimonial->name . $testimonial->id);
                                    $avatarUrl = 'https://i.pravatar.cc/150?u=' . $seed;
                                @endphp
                                <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : $avatarUrl }}"
                                     alt="{{ $testimonial->name }}"
                                     class="w-11 h-11 rounded-full object-cover shrink-0 border-2 border-white/10">
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-sm text-white truncate">{{ $testimonial->name }}</h4>
                                    <p class="text-xs text-neutral-500 truncate">{{ $testimonial->title ?? __('messages.testimonials.visitor') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Hidden full content for modal --}}
                    <div id="testi-full-{{ $loop->index }}" class="hidden">
                        <div class="flex gap-1 text-yellow-400 mb-5">
                            @for ($i = 0; $i < 5; $i++)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                            @endfor
                        </div>
                        <p class="text-neutral-200 leading-relaxed text-sm md:text-base mb-6">
                            "{{ $testimonial->content }}"
                        </p>
                        <div class="flex items-center gap-4 border-t border-white/5 pt-5">
                            @php
                                $seed = md5($testimonial->name . $testimonial->id);
                                $avatarUrl = 'https://i.pravatar.cc/150?u=' . $seed;
                            @endphp
                            <img src="{{ $testimonial->avatar ? asset('storage/' . $testimonial->avatar) : $avatarUrl }}"
                                 alt="{{ $testimonial->name }}"
                                 class="w-11 h-11 rounded-full object-cover shrink-0 border-2 border-white/10">
                            <div>
                                <h4 class="font-semibold text-sm text-white">{{ $testimonial->name }}</h4>
                                <p class="text-xs text-neutral-500">{{ $testimonial->title ?? __('messages.testimonials.visitor') }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Dots Pagination --}}
                <div class="swiper-pagination testimonial-pagination !bottom-0"></div>
            </div>
        </div>
        @else
        <p class="text-neutral-400">{{ __('messages.testimonials.empty') }}</p>
        @endif

        {{-- Submit Testimonial Button --}}
        <div class="mt-12">
            <a href="{{ route('testimonials.create') }}" class="inline-flex items-center gap-2 border-2 border-[#00c887] hover:bg-[#00c887] text-[#00c887] hover:text-[#053d2c] px-8 py-3.5 rounded-full font-semibold transition duration-300 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                {{ __('messages.testimonials.submit_testimonial') }}
            </a>
        </div>
    </div>

    {{-- Modal Overlay --}}
    <div id="testimonial-modal" class="fixed inset-0 z-[9999] bg-black/70 backdrop-blur-sm flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-300" onclick="if(event.target===this) closeTestimonialModal()">
        <div class="bg-[#053d2c] border border-white/10 rounded-3xl shadow-2xl w-full max-w-lg max-h-[80vh] overflow-y-auto transform scale-95 transition-transform duration-300" id="testimonial-modal-content">
            <div class="sticky top-0 bg-[#053d2c] flex items-center justify-between px-6 pt-6 pb-3 border-b border-white/5">
                <h3 class="font-semibold text-white text-sm">{{ __('messages.testimonials.full_testimonial') }}</h3>
                <button onclick="closeTestimonialModal()" class="w-8 h-8 bg-white/10 hover:bg-white/20 rounded-xl flex items-center justify-center transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-white" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                </button>
            </div>
            <div id="testimonial-modal-body" class="px-6 py-6"></div>
        </div>
    </div>
</section>

@push('script-alt')
<script>
function openTestimonialModal(contentId) {
    const contentEl = document.getElementById(contentId);
    const modal = document.getElementById('testimonial-modal');
    const modalBody = document.getElementById('testimonial-modal-body');

    if (contentEl && modal && modalBody) {
        modalBody.innerHTML = contentEl.innerHTML;
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            document.getElementById('testimonial-modal-content').classList.remove('scale-95');
        }, 10);
        document.body.style.overflow = 'hidden';
    }
}

function closeTestimonialModal() {
    const modal = document.getElementById('testimonial-modal');
    if (modal) {
        modal.classList.add('opacity-0');
        document.getElementById('testimonial-modal-content').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeTestimonialModal();
});

// Swiper init
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        new Swiper('.testimonial-swiper', {
            slidesPerView: 1,
            spaceBetween: 24,
            centeredSlides: true,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.testimonial-pagination',
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + ' w-2.5 h-2.5 rounded-full !opacity-100 transition-all duration-300"></span>';
                },
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    centeredSlides: true,
                }
            }
        });
    }
});
</script>
@endpush

@push('style-alt')
<style>
/* Force equal height on all swiper slides */
.testimonial-swiper .swiper-wrapper {
    align-items: stretch !important;
}
.testimonial-swiper .swiper-slide {
    height: auto !important;
    display: flex !important;
}
.testimonial-swiper .swiper-slide > div {
    height: 100%;
}
.testimonial-swiper .swiper-pagination-bullet {
    background: rgba(255, 255, 255, 0.3) !important;
    width: 10px !important;
    height: 10px !important;
}
.testimonial-swiper .swiper-pagination-bullet:hover {
    background: rgba(255, 255, 255, 0.6) !important;
}
.testimonial-swiper .swiper-pagination-bullet-active {
    background: #00c887 !important;
    width: 32px !important;
    border-radius: 999px !important;
}
.testimonial-text {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 5.6em;
}
/* Modal scrollbar styling */
#testimonial-modal-content::-webkit-scrollbar {
    width: 4px;
}
#testimonial-modal-content::-webkit-scrollbar-track {
    background: transparent;
}
#testimonial-modal-content::-webkit-scrollbar-thumb {
    @apply bg-white/20 rounded-full;
}
</style>
@endpush
</write_to_file>