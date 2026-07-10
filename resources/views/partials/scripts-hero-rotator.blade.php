{{-- Hero Background Slider with Fade Transition & Pagination Dots --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.hero__slide');
    const dots = document.querySelectorAll('.hero__dot');

    if (slides.length > 1) {
        let currentIndex = 0;
        let intervalId;
        const INTERVAL = 4000; // 4 detik per slide

        function goToSlide(index) {
            // Sembunyikan semua slide
            slides.forEach(slide => {
                slide.classList.remove('hero__slide--active');
                slide.classList.add('opacity-0');
            });

            // Tampilkan slide yang dipilih
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('hero__slide--active');

            // Update dots
            dots.forEach((dot, i) => {
                if (i === index) {
                    dot.classList.remove('bg-white/50');
                    dot.classList.add('bg-white');
                    dot.style.width = '24px';
                } else {
                    dot.classList.remove('bg-white');
                    dot.classList.add('bg-white/50');
                    dot.style.width = '10px';
                }
            });

            currentIndex = index;
        }

        function nextSlide() {
            const next = (currentIndex + 1) % slides.length;
            goToSlide(next);
        }

        function startAutoPlay() {
            stopAutoPlay();
            intervalId = setInterval(nextSlide, INTERVAL);
        }

        function stopAutoPlay() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        // Click dots
        dots.forEach(dot => {
            dot.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                if (index !== currentIndex) {
                    goToSlide(index);
                    startAutoPlay(); // Reset timer
                }
            });
        });

        // Pause on hover
        const heroSection = document.getElementById('hero');
        heroSection.addEventListener('mouseenter', stopAutoPlay);
        heroSection.addEventListener('mouseleave', startAutoPlay);

        // Init
        goToSlide(0);
        startAutoPlay();
    }
});
</script>
