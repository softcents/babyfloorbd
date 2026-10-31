(function () {
    'use strict';

    var slider = document.getElementById('heroSlider');
    var track = document.getElementById('heroTrack');
    var dotsWrap = document.getElementById('heroDots');
    var prevBtn = document.getElementById('heroPrev');
    var nextBtn = document.getElementById('heroNext');

    if (!slider || !track) return;

    var slides = track.querySelectorAll('.hero-slide');
    var total = slides.length;
    var current = 0;
    var autoplayMs = 4500;
    var timer = null;
    var touchStartX = 0;
    var touchDeltaX = 0;

    function updateHeight() {
        var img = slides[current] && slides[current].querySelector('.hero-img');
        if (img && img.offsetHeight) {
            slider.style.height = img.offsetHeight + 'px';
        }
    }

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';

        if (dotsWrap) {
            dotsWrap.querySelectorAll('.hero-dot').forEach(function (dot, i) {
                dot.classList.toggle('active', i === current);
                dot.setAttribute('aria-selected', i === current ? 'true' : 'false');
            });
        }

        updateHeight();
    }

    function next() {
        goTo(current + 1);
    }

    function prev() {
        goTo(current - 1);
    }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(next, autoplayMs);
    }

    function stopAutoplay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    if (dotsWrap && total > 1) {
        slides.forEach(function (_, i) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'hero-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('role', 'tab');
            dot.setAttribute('aria-label', 'স্লাইড ' + (i + 1));
            dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
            dot.addEventListener('click', function () {
                goTo(i);
                startAutoplay();
            });
            dotsWrap.appendChild(dot);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            prev();
            startAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            next();
            startAutoplay();
        });
    }

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    slider.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].clientX;
        touchDeltaX = 0;
        stopAutoplay();
    }, { passive: true });

    slider.addEventListener('touchmove', function (e) {
        touchDeltaX = e.changedTouches[0].clientX - touchStartX;
    }, { passive: true });

    slider.addEventListener('touchend', function () {
        if (Math.abs(touchDeltaX) > 50) {
            if (touchDeltaX < 0) next();
            else prev();
        }
        startAutoplay();
    }, { passive: true });

    if (total <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (dotsWrap) dotsWrap.style.display = 'none';
        updateHeight();
        return;
    }

    slides.forEach(function (slide, i) {
        var img = slide.querySelector('.hero-img');
        if (img) {
            if (img.complete) {
                if (i === 0) updateHeight();
            } else {
                img.addEventListener('load', function () {
                    if (i === current) updateHeight();
                });
            }
        }
    });

    window.addEventListener('resize', updateHeight);

    goTo(0);
    startAutoplay();
})();
