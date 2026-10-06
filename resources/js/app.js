import Alpine from 'alpinejs';
import Lenis from 'lenis';
import 'lenis/dist/lenis.css';

window.Alpine = Alpine;
Alpine.start();

// Inisialisasi Smooth Scroll Lenis (Sesuai dependency di folder OT)
const lenis = new Lenis({
    autoRaf: true,
    duration: 1.2,
    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
    smoothWheel: true,
    touchMultiplier: 1.5,
});

window.lenis = lenis;

// Setup smooth scrolling untuk anchor links
function setupAnchorSmoothScroll() {
    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const rawHref = this.getAttribute('href');
            if (!rawHref) return;

            // Handle #id atau /#id
            const hashIndex = rawHref.indexOf('#');
            if (hashIndex === -1) return;

            const path = rawHref.substring(0, hashIndex);
            const hash = rawHref.substring(hashIndex);

            if (hash === '#' || hash.length <= 1) return;

            const isCurrentPage = !path || path === '/' || path === window.location.pathname;
            if (!isCurrentPage) return;

            const targetEl = document.querySelector(hash);
            if (targetEl) {
                e.preventDefault();
                lenis.scrollTo(targetEl, {
                    offset: -68,
                    duration: 1.2,
                    easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
                });
                history.pushState(null, '', hash);
            }
        });
    });
}

// Inisialisasi Sistem Animasi Scroll Reveal (Sesuai folder OT: ScrollReveal component)
function initScrollReveal() {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.querySelectorAll('[data-reveal]').forEach(el => el.classList.add('is-revealed'));
        return;
    }

    const revealElements = document.querySelectorAll('[data-reveal]');
    if (!revealElements.length) return;

    // IntersectionObserver dengan margin bottom -8% (sama dengan viewport margin: "-10%" di OT)
    const observer = new IntersectionObserver((entries, obs) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const el = entry.target;
                const delay = el.getAttribute('data-reveal-delay');
                if (delay) {
                    el.style.transitionDelay = `${delay}ms`;
                }
                el.classList.add('is-revealed');
                obs.unobserve(el);
            }
        });
    }, {
        root: null,
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.08
    });

    revealElements.forEach(el => {
        const rect = el.getBoundingClientRect();
        // Bila elemen sudah berada di layar saat halaman pertama dimuat
        if (rect.top < window.innerHeight * 0.88 && rect.bottom > 0) {
            const delay = el.getAttribute('data-reveal-delay') || 0;
            setTimeout(() => {
                el.classList.add('is-revealed');
            }, Math.min(Number(delay), 350));
        } else {
            observer.observe(el);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    setupAnchorSmoothScroll();
    initScrollReveal();
});

// Bila ada perubahan konten dinamis
window.addEventListener('load', () => {
    initScrollReveal();
});
