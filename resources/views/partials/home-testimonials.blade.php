{{-- TESTIMONIALS (Swiper) --}}
<section class="section-padding bg-primary-50/50" id="testimonials">
    <div class="container-custom">
        <span class="section-subtitle text-center">@lang('messages.testimonials.subtitle')</span>
        <h2 class="section-title text-center">@lang('messages.testimonials.title')</h2>

        @if($testimonials->count() > 0)
        <div class="testimonials__container swiper mt-10 !pb-12 overflow-hidden">
            <div class="swiper-wrapper">
                @foreach($testimonials as $testimonial)
                    <div class="swiper-slide h-auto">
                        <div class="bg-white rounded-2xl p-6 shadow-lg flex flex-col h-full border border-slate-50">
                            <div class="flex gap-0.5 text-yellow-500 mb-4">
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                                <i class="bx bxs-star"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-primary-600 leading-relaxed text-sm line-clamp-3 mb-3">"{{ $testimonial->content }}"</p>
                                <button type="button"
                                        class="swiper-no-swiping testimonial-btn relative z-20 text-secondary hover:underline text-xs font-semibold uppercase tracking-wider mb-4 block"
                                        data-name="{{ $testimonial->name }}"
                                        data-content="{{ $testimonial->content }}"
                                        data-avatar="{{ $testimonial->avatar ? asset('storage/'.$testimonial->avatar) : 'https://i.pravatar.cc/80?u='.urlencode($testimonial->name) }}">
                                    @lang('messages.testimonials.read_more')
                                    <i class="bx bx-chevron-right align-middle"></i>
                                </button>
                            </div>
                            <div class="flex items-center gap-3 pt-4 border-t border-stone-100 mt-auto">
                                @if($testimonial->avatar && file_exists(public_path('storage/' . $testimonial->avatar)))
                                    <img src="{{ asset('storage/' . $testimonial->avatar) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover" loading="lazy" />
                                @else
                                    <img src="https://i.pravatar.cc/48?u={{ urlencode($testimonial->name) }}" alt="{{ $testimonial->name }}" class="w-12 h-12 rounded-full object-cover" loading="lazy" />
                                @endif
                                <div>
                                    <h4 class="font-heading text-primary-900 font-semibold text-sm">{{ $testimonial->name }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
        @else
        <div class="text-center py-12 text-primary-400 mt-10">
            <i class="bx bx-message-square-detail text-4xl block mb-3"></i>
            <p>@lang('messages.testimonials.empty')</p>
        </div>
        @endif

        <div class="text-center mt-10">
            <a href="{{ route('testimonials.create') }}" class="button-transparent button-outline">
                <i class="bx bxs-edit"></i>
                @lang('messages.testimonials.submit_testimonial')
            </a>
        </div>
    </div>
</section>

{{-- Testimonial Modal --}}
<div id="testimonialModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 opacity-0 invisible transition-all duration-300"
     style="background: rgba(0,0,0,.7); backdrop-filter: blur(4px);"
     onclick="if(event.target===this) closeTestimonialModal()">
    <div id="testimonialModalBody" class="bg-white rounded-2xl shadow-2xl max-w-lg w-full max-h-[85vh] overflow-y-auto p-6 md:p-8 transform scale-95 transition-all duration-300">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <img id="testimonialModalAvatar" src="" alt="" class="w-12 h-12 rounded-full object-cover">
                <div>
                    <h3 id="testimonialModalName" class="font-heading text-lg font-bold text-primary-900"></h3>
                    <div class="flex gap-0.5 text-yellow-500 text-xs mt-1">
                        <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i>
                    </div>
                </div>
            </div>
            <button type="button" onclick="closeTestimonialModal()" class="text-gray-400 hover:text-red-500 text-3xl"><i class="bx bx-x"></i></button>
        </div>
        <div id="testimonialModalContent" class="text-primary-600 leading-relaxed text-base italic"></div>
    </div>
</div>

@push('script-alt')
<script>
function openTestimonialModal(name, content, avatar) {
    document.getElementById('testimonialModalName').textContent = name;
    document.getElementById('testimonialModalContent').innerHTML = content.replace(/\n/g, '<br>');
    const avatarImg = document.getElementById('testimonialModalAvatar');
    avatarImg.src = avatar;
    avatarImg.alt = name;
    const modal = document.getElementById('testimonialModal');
    const body = document.getElementById('testimonialModalBody');
    modal.classList.remove('opacity-0', 'invisible');
    body.classList.remove('scale-95');
    body.classList.add('scale-100');
    document.body.style.overflow = 'hidden';
}
function closeTestimonialModal() {
    const modal = document.getElementById('testimonialModal');
    const body = document.getElementById('testimonialModalBody');
    modal.classList.add('invisible', 'opacity-0');
    body.classList.add('scale-95');
    body.classList.remove('scale-100');
    document.body.style.overflow = '';
}
document.addEventListener('click', function(e){
    const btn = e.target.closest('.testimonial-btn');
    if(!btn) return;
    e.preventDefault();
    e.stopPropagation();
    openTestimonialModal(btn.dataset.name, btn.dataset.content, btn.dataset.avatar);
});
document.addEventListener('DOMContentLoaded', function() {
    new Swiper(".testimonials__container", {
        spaceBetween: 24, grabCursor: true, loop: true,
        autoplay: { delay: 5000, disableOnInteraction: false },
        noSwiping: true, noSwipingClass: 'swiper-no-swiping',
        pagination: { el: ".swiper-pagination", clickable: true },
        breakpoints: { 320: { slidesPerView: 1 }, 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } },
    });
});
document.addEventListener('keydown', function(e) { if (e.key === 'Escape') closeTestimonialModal(); });
</script>
@endpush