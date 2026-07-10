/*=============== CHANGE BACKGROUND HEADER ===============*/
const scrollHeader = () => {
    const header = document.getElementById("header");
    if (header) {
        window.scrollY >= 50 ?
            header.classList.add("scroll-header") :
            header.classList.remove("scroll-header");
    }
};
window.addEventListener("scroll", scrollHeader);

/*=============== SHOW NAV MENU ===============*/
const navMenu = document.getElementById("nav-menu");
const navToggle = document.getElementById("nav-toggle");
const navClose = document.getElementById("nav-close");

if (navToggle && navMenu) {
    navToggle.addEventListener("click", () => {
        navMenu.classList.add("show-menu");
    });
}

/*=============== CLOSE NAV MENU ===============*/
if (navClose && navMenu) {
    navClose.addEventListener("click", () => {
        navMenu.classList.remove("show-menu");
    });
}

/*=============== CLOSE ON LINK CLICK ===============*/
const navLinks = document.querySelectorAll(".nav__link");
navLinks.forEach(link => {
    link.addEventListener("click", () => {
        if (navMenu) {
            navMenu.classList.remove("show-menu");
        }
    });
});

/*=============== CLOSE ON OUTSIDE CLICK (backdrop) ===============*/
if (navMenu) {
    navMenu.addEventListener("click", (e) => {
        // Close when clicking the backdrop (the ::before pseudo-element)
        if (e.target === navMenu) {
            navMenu.classList.remove("show-menu");
        }
    });
}

/*=============== SWIPER POPULAR ===============*/
const popularSwiper = new Swiper(".popular__container", {
    loop: true,
    spaceBetween: 24,

    slidesPerView: 1.1,

    breakpoints: {
        640: {
            slidesPerView: 2,
        },

        1024: {
            slidesPerView: 3,
        },
    },

    pagination: {
        el: ".popular-pagination",
        clickable: true,
    },

    navigation: {
        nextEl: ".popular-next",
        prevEl: ".popular-prev",
    },
});

/*=============== VALUE ACCORDION ===============*/
const accordionItems = document.querySelectorAll(".value__accordion-item");

accordionItems.forEach((item) => {
    const accordionHeader = item.querySelector(".value__accordion-header");

    if (accordionHeader) {
        accordionHeader.addEventListener("click", () => {
            const openItem = document.querySelector(".accordion-open");
            toggleItem(item);
            if (openItem && openItem !== item) {
                toggleItem(openItem);
            }
        });
    }
});

const toggleItem = (item) => {
    const accordionContent = item.querySelector(".value__accordion-content");

    if (item.classList.contains("accordion-open")) {
        accordionContent.removeAttribute("style");
        item.classList.remove("accordion-open");
    } else {
        accordionContent.style.height = accordionContent.scrollHeight + "px";
        item.classList.add("accordion-open");
    }
};

/*=============== SHOW SCROLL UP ===============*/
const scrollUp = () => {
    const scrollUpEl = document.getElementById("scroll-up");
    if (scrollUpEl) {
        window.scrollY >= 350 ?
            scrollUpEl.classList.add("show-scroll") :
            scrollUpEl.classList.remove("show-scroll");
    }
};
window.addEventListener("scroll", scrollUp);

// /*=============== DARK LIGHT THEME ===============*/
// const themeButton = document.getElementById("theme-button");
// const darkTheme = "dark-theme";
// const iconTheme = "bx-sun";

// if (themeButton) {
//     // Previously selected topic (if user selected)
//     const selectedTheme = localStorage.getItem("selected-theme");
//     const selectedIcon = localStorage.getItem("selected-icon");

//     // We obtain the current theme that the interface has by validating the dark-theme class
//     const getCurrentTheme = () =>
//         document.body.classList.contains(darkTheme) ? "dark" : "light";
//     const getCurrentIcon = () =>
//         themeButton.classList.contains(iconTheme) ? "bx bx-moon" : "bx bx-sun";

//     // We validate if the user previously chose a topic
//     if (selectedTheme) {
//         document.body.classList[selectedTheme === "dark" ? "add" : "remove"](darkTheme);
//         themeButton.classList[selectedIcon === "bx bx-moon" ? "add" : "remove"](iconTheme);
//     }

//     // Activate / deactivate the theme manually with the button
//     themeButton.addEventListener("click", () => {
//         document.body.classList.toggle(darkTheme);
//         themeButton.classList.toggle(iconTheme);
//         localStorage.setItem("selected-theme", getCurrentTheme());
//         localStorage.setItem("selected-icon", getCurrentIcon());
//     });
// }

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: "top",
    distance: "60px",
    duration: 2500,
    delay: 400,
});

sr.reveal(".popular__container, .blog__container, .footer__container, .contact-area, .contact__card");
sr.reveal(".footer__info", { delay: 100 });
sr.reveal(".hero__subtitle", { origin: "left", distance: "40px" });
sr.reveal(".hero__title", { origin: "left", distance: "40px", delay: 200 });
sr.reveal(".hero__description", { origin: "left", distance: "40px", delay: 400 });
sr.reveal(".hero__actions", { origin: "left", distance: "40px", delay: 600 });
sr.reveal(".value__data", { origin: "left", distance: "40px" });
sr.reveal(".value__cards", { origin: "right", distance: "40px", delay: 200 });
sr.reveal(".featured__container", { interval: 200 });
sr.reveal(".testimonial__card", { interval: 200 });
sr.reveal(".stats__grid", { origin: "bottom", distance: "40px" });

// lazy
const initLazyLoad = () => {
    const lazyImages = document.querySelectorAll('.lazy_img');

    const options = {
        rootMargin: '0px',
        threshold: 0.1
    };

        // Wrap lazy images with skeleton container
        lazyImages.forEach(function(lazyImage) {
            // Only if not already wrapped
            if (!lazyImage.closest('.lazy_img-container')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'lazy_img-container';
                const skeleton = document.createElement('div');
                skeleton.className = 'skeleton-green';
                lazyImage.parentNode.insertBefore(wrapper, lazyImage);
                wrapper.appendChild(lazyImage);
                wrapper.appendChild(skeleton);
            }
        });

        const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const lazyElement = entry.target;
                if (!lazyElement.classList.contains('loaded')) {
                    lazyElement.classList.add('loaded');
                    const dataSrc = lazyElement.dataset.src;

                    if (lazyElement.tagName === 'IMG') {
                        lazyElement.src = dataSrc;
                        lazyElement.onload = () => {
                            lazyElement.style.filter = 'blur(0)';
                        };
                    } else {
                        lazyElement.style.backgroundImage = `url(${dataSrc})`;
                        lazyElement.style.filter = 'blur(0)';
                    }
                }
                observer.unobserve(lazyElement);
            }
        });
    }, options);

    // Get updated list (after wrapping)
    const updatedLazyImages = document.querySelectorAll('.lazy_img:not(.loaded)');
    updatedLazyImages.forEach(lazyImage => {
        observer.observe(lazyImage);
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initLazyLoad();
});

