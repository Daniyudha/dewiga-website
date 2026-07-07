{{-- Hero Background Rotator Script with Pagination Dots --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroBgs = document.querySelectorAll('.hero__bg');
    const heroDots = document.querySelectorAll('.hero__dot');

    if (heroBgs.length > 1) {
        let imageIndex = 0;
        let intervalId;

        function goToSlide(index) {
            heroBgs.forEach(img => img.classList.remove('show'));
            heroBgs[index].classList.add('show');
            heroDots.forEach(dot => {
                dot.classList.remove('bg-white', 'w-6');
                dot.classList.add('bg-white/50');
                dot.style.width = '10px';
            });
            heroDots[index].classList.remove('bg-white/50');
            heroDots[index].classList.add('bg-white');
            heroDots[index].style.width = '36px';
        }

        function startAutoPlay() {
            if (intervalId) clearInterval(intervalId);
            intervalId = setInterval(function() {
                imageIndex = (imageIndex + 1) % heroBgs.length;
                goToSlide(imageIndex);
            }, 3000);
        }

        heroDots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                const index = parseInt(this.dataset.index);
                imageIndex = index;
                goToSlide(index);
                startAutoPlay();
            });
        });

        const heroSection = document.getElementById('hero');
        heroSection.addEventListener('mouseenter', function() {
            if (intervalId) clearInterval(intervalId);
        });
        heroSection.addEventListener('mouseleave', function() {
            startAutoPlay();
        });

        goToSlide(0);
        startAutoPlay();
    }
});
</script>