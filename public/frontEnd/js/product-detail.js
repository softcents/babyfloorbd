(function () {
    'use strict';

    var qtyInput = document.getElementById('qtyInput');
    var qtyMinus = document.getElementById('qtyMinus');
    var qtyPlus = document.getElementById('qtyPlus');
    var priceEl = document.querySelector('.product-detail-price');
    var weightBtns = document.querySelectorAll('.weight-btn');
    var clearBtn = document.querySelector('.btn-clear-option');

    if (qtyMinus && qtyInput) {
        qtyMinus.addEventListener('click', function () {
            var val = parseInt(qtyInput.value, 10) || 1;
            if (val > 1) qtyInput.value = val - 1;
        });
    }

    if (qtyPlus && qtyInput) {
        qtyPlus.addEventListener('click', function () {
            var val = parseInt(qtyInput.value, 10) || 1;
            if (val < 99) qtyInput.value = val + 1;
        });
    }

    weightBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            weightBtns.forEach(function (b) { b.classList.remove('active'); });
            btn.classList.add('active');
            if (priceEl && btn.dataset.price) {
                priceEl.textContent = btn.dataset.price + '৳';
            }
        });
    });

    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            weightBtns.forEach(function (b) { b.classList.remove('active'); });
            if (priceEl) priceEl.textContent = '320৳ – 620৳';
        });
    }

    /* Image & Video Gallery */
    var galleryImg = document.getElementById('galleryMainImg');
    var galleryVideoWrap = document.getElementById('galleryVideoWrap');
    var galleryVideoFrame = document.getElementById('galleryVideoFrame');
    var thumbsWrap = document.querySelector('.gallery-thumbs');

    function setActiveThumb(thumb) {
        if (!thumbsWrap) return;
        thumbsWrap.querySelectorAll('.gallery-thumb').forEach(function (t) {
            t.classList.remove('active');
            t.setAttribute('aria-selected', 'false');
        });
        thumb.classList.add('active');
        thumb.setAttribute('aria-selected', 'true');
    }

    function showImage(src) {
        if (galleryVideoWrap) {
            galleryVideoWrap.hidden = true;
        }
        if (galleryVideoFrame) {
            galleryVideoFrame.src = '';
        }
        if (galleryImg) {
            galleryImg.hidden = false;
            galleryImg.classList.add('is-fading');
            galleryImg.onload = function () {
                galleryImg.classList.remove('is-fading');
                galleryImg.onload = null;
            };
            galleryImg.src = src;
            if (galleryImg.complete) {
                galleryImg.classList.remove('is-fading');
            }
        }
    }

    function showVideo(src) {
        if (galleryImg) {
            galleryImg.hidden = true;
        }
        if (galleryVideoWrap && galleryVideoFrame) {
            galleryVideoWrap.hidden = false;
            galleryVideoFrame.src = src;
        }
    }

    function handleThumbClick(thumb) {
        var type = thumb.getAttribute('data-type') || 'image';
        var src = thumb.getAttribute('data-src');
        if (!src) return;

        setActiveThumb(thumb);

        if (type === 'video') {
            showVideo(src);
        } else {
            showImage(src);
        }
    }

    if (thumbsWrap) {
        thumbsWrap.addEventListener('click', function (e) {
            var thumb = e.target.closest('.gallery-thumb');
            if (!thumb || !thumbsWrap.contains(thumb)) return;
            e.preventDefault();
            handleThumbClick(thumb);
        });
    }

    /* Review Modal */
    var reviewModal = document.getElementById('reviewModal');
    var openReviewBtn = document.getElementById('openReviewModal');
    var closeReviewBtn = document.getElementById('reviewModalClose');
    var reviewOverlay = document.getElementById('reviewModalOverlay');

    function openReviewModal() {
        if (!reviewModal) return;
        reviewModal.hidden = false;
        document.body.style.overflow = 'hidden';
        var nameInput = document.getElementById('reviewName');
        if (nameInput) nameInput.focus();
    }

    function closeReviewModal() {
        if (!reviewModal) return;
        reviewModal.hidden = true;
        document.body.style.overflow = '';
    }

    if (openReviewBtn) {
        openReviewBtn.addEventListener('click', openReviewModal);
    }

    if (closeReviewBtn) {
        closeReviewBtn.addEventListener('click', closeReviewModal);
    }

    if (reviewOverlay) {
        reviewOverlay.addEventListener('click', closeReviewModal);
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && reviewModal && !reviewModal.hidden) {
            closeReviewModal();
        }
    });

    /* Review Star Input */
    var starBtns = document.querySelectorAll('.star-input-btn');
    var ratingInput = document.getElementById('reviewRating');

    function setStars(value) {
        starBtns.forEach(function (btn) {
            var v = parseInt(btn.getAttribute('data-value'), 10);
            var icon = btn.querySelector('i');
            var active = v <= value;
            btn.classList.toggle('active', active);
            if (icon) {
                icon.className = active ? 'fas fa-star' : 'far fa-star';
            }
        });
        if (ratingInput) ratingInput.value = value;
    }

    starBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            setStars(parseInt(btn.getAttribute('data-value'), 10));
        });
    });

    /* Review Form */
    var reviewForm = document.getElementById('reviewForm');
    if (reviewForm) {
        reviewForm.addEventListener('submit', function (e) {
            e.preventDefault();
            var rating = ratingInput ? parseInt(ratingInput.value, 10) : 0;
            if (rating < 1) {
                alert('অনুগ্রহ করে রেটিং দিন।');
                return;
            }
            alert('ধন্যবাদ! আপনার রিভিউ জমা দেওয়া হয়েছে।');
            reviewForm.reset();
            setStars(0);
            closeReviewModal();
        });
    }
})();
