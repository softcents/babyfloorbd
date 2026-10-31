(function () {
    'use strict';

    var menuToggle = document.getElementById('menuToggle');
    var menuClose = document.getElementById('menuClose');
    var mainNav = document.getElementById('mainNav');
    var navOverlay = document.getElementById('navOverlay');
    var siteHeader = document.querySelector('.site-header');
    var isAppHome = document.body.classList.contains('app-home');

    function setMenuOpen(open) {
        if (!mainNav || !menuToggle) return;
        mainNav.classList.toggle('open', open);
        menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        document.body.classList.toggle('menu-open', open);
        if (navOverlay) {
            navOverlay.classList.toggle('show', open);
            navOverlay.setAttribute('aria-hidden', open ? 'false' : 'true');
        }
        document.body.style.overflow = open ? 'hidden' : '';
    }

    if (menuToggle && mainNav) {
        menuToggle.addEventListener('click', function () {
            setMenuOpen(!mainNav.classList.contains('open'));
        });
    }

    if (menuClose) {
        menuClose.addEventListener('click', function () {
            setMenuOpen(false);
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener('click', function () {
            setMenuOpen(false);
        });
    }

    if (mainNav) {
        mainNav.querySelectorAll('a').forEach(function (link) {
            link.addEventListener('click', function () {
                if (window.innerWidth <= 900) setMenuOpen(false);
            });
        });
    }

    document.querySelectorAll('.mobile-sub-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var group = btn.closest('.mobile-cat-group');
            if (!group) return;
            var open = group.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    document.querySelectorAll('.drawer-child-toggle').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var group = btn.closest('.drawer-sub-group');
            if (!group) return;
            var open = group.classList.toggle('is-open');
            btn.setAttribute('aria-expanded', open ? 'true' : 'false');
        });
    });

    var cartWrap = document.querySelector('.cart-wrap');
    if (cartWrap) {
        var removeUrl = cartWrap.getAttribute('data-remove-url');

        function formatBdt(n) {
            return '৳' + Math.round(n);
        }

        function refreshMiniCart() {
            var items = cartWrap.querySelectorAll('.mini-cart-item');
            var total = 0;
            var qtyTotal = 0;
            items.forEach(function (it) {
                total += parseFloat(it.getAttribute('data-line')) || 0;
                qtyTotal += parseInt(it.getAttribute('data-qty'), 10) || 0;
            });

            var amtEl = cartWrap.querySelector('.mini-cart-total-amt');
            if (amtEl) amtEl.textContent = formatBdt(total);

            var headAmt = cartWrap.querySelector('.cart-amount');
            if (headAmt) headAmt.textContent = Math.round(total) + '৳';

            var countEl = cartWrap.querySelector('#cart-qty');
            if (countEl) countEl.textContent = qtyTotal;

            // Mobile bottom nav badge sync
            var navBadge = document.querySelector('.mobile-bottom-nav .nav-badge');
            var navCartLink = document.querySelector('.mobile-bottom-nav .nav-cart');
            if (navCartLink) {
                if (qtyTotal > 0) {
                    if (!navBadge) {
                        navBadge = document.createElement('em');
                        navBadge.className = 'nav-badge';
                        navCartLink.appendChild(navBadge);
                    }
                    navBadge.textContent = qtyTotal > 99 ? '99+' : qtyTotal;
                } else if (navBadge) {
                    navBadge.remove();
                }
            }

            var dropdownCount = cartWrap.querySelector('.cart-dropdown-count');
            if (dropdownCount) dropdownCount.textContent = qtyTotal + ' টি পণ্য';

            var foot = cartWrap.querySelector('.cart-dropdown-foot');
            var body = cartWrap.querySelector('.cart-dropdown-body');
            if (items.length === 0) {
                if (foot) foot.style.display = 'none';
                if (body && !body.querySelector('.mini-cart-empty')) {
                    body.innerHTML = '<div class="mini-cart-empty"><i class="fas fa-cart-shopping"></i><p>আপনার কার্ট খালি</p></div>';
                }
            }
        }

        cartWrap.addEventListener('click', function (e) {
            var btn = e.target.closest('.mini-cart-remove');
            if (!btn) return;
            e.preventDefault();
            e.stopPropagation();
            var id = btn.getAttribute('data-id');
            if (!id || !removeUrl) return;

            var row = btn.closest('.mini-cart-item');
            if (row) row.style.opacity = '0.5';

            var sep = removeUrl.indexOf('?') === -1 ? '?' : '&';
            fetch(removeUrl + sep + 'id=' + encodeURIComponent(id), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function () {
                    if (row) row.remove();
                    refreshMiniCart();
                })
                .catch(function () {
                    if (row) row.style.opacity = '1';
                });
        });
    }

    var searchForm = document.querySelector('.mobile-search');
    var searchInput = document.getElementById('mobileSearchInput');
    var searchResults = document.getElementById('mobileSearchResults');
    if (searchForm && searchInput && searchResults) {
        var liveUrl = searchForm.getAttribute('data-live-url');
        var searchTimer = null;
        var lastQuery = '';

        function showState(msg) {
            searchResults.innerHTML = '<div class="mobile-search-state">' + msg + '</div>';
            searchResults.classList.add('show');
        }

        function hideResults() {
            searchResults.classList.remove('show');
            searchResults.innerHTML = '';
        }

        function runLiveSearch(q) {
            if (!liveUrl) return;
            var sep = liveUrl.indexOf('?') === -1 ? '?' : '&';
            fetch(liveUrl + sep + 'keyword=' + encodeURIComponent(q), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (r) { return r.text(); })
                .then(function (html) {
                    var trimmed = (html || '').trim();
                    if (trimmed && trimmed.indexOf('search_product') !== -1) {
                        searchResults.innerHTML = trimmed;
                        searchResults.classList.add('show');
                    } else {
                        showState('কোনো পণ্য পাওয়া যায়নি');
                    }
                })
                .catch(function () { hideResults(); });
        }

        searchInput.addEventListener('input', function () {
            var q = searchInput.value.trim();
            lastQuery = q;
            clearTimeout(searchTimer);
            if (q.length < 2) {
                hideResults();
                return;
            }
            showState('খুঁজছি...');
            searchTimer = setTimeout(function () {
                runLiveSearch(q);
            }, 300);
        });

        searchInput.addEventListener('focus', function () {
            if (searchInput.value.trim().length >= 2 && searchResults.innerHTML.trim()) {
                searchResults.classList.add('show');
            }
        });

        document.addEventListener('click', function (e) {
            if (!searchForm.contains(e.target)) hideResults();
        });

        searchForm.addEventListener('submit', function (e) {
            if (searchInput.value.trim() === '') e.preventDefault();
        });

        var searchIcon = searchForm.querySelector('.search-icon');
        if (searchIcon) {
            searchIcon.addEventListener('click', function () {
                if (searchInput.value.trim() !== '') {
                    searchForm.submit();
                } else {
                    searchInput.focus();
                }
            });
        }
    }

    function pad2(n) { return (n < 10 ? '0' : '') + n; }

    document.querySelectorAll('.deal-countdown[data-end]').forEach(function (timer) {
        var endTime = new Date(timer.getAttribute('data-end')).getTime();
        var dEl = timer.querySelector('.t-days');
        var hEl = timer.querySelector('.t-hours');
        var mEl = timer.querySelector('.t-mins');
        var sEl = timer.querySelector('.t-secs');
        if (isNaN(endTime) || !dEl || !hEl || !mEl || !sEl) return;

        var interval = null;
        var tickTimer = function () {
            var diff = endTime - Date.now();
            if (diff <= 0) {
                dEl.textContent = hEl.textContent = mEl.textContent = sEl.textContent = '00';
                if (interval) clearInterval(interval);
                return;
            }
            dEl.textContent = pad2(Math.floor(diff / 86400000));
            hEl.textContent = pad2(Math.floor((diff % 86400000) / 3600000));
            mEl.textContent = pad2(Math.floor((diff % 3600000) / 60000));
            sEl.textContent = pad2(Math.floor((diff % 60000) / 1000));
        };
        tickTimer();
        interval = setInterval(tickTimer, 1000);
    });

    if (isAppHome && siteHeader) {
        var lastScroll = 0;
        window.addEventListener('scroll', function () {
            var y = window.scrollY || window.pageYOffset;
            siteHeader.classList.toggle('is-scrolled', y > 10);
            lastScroll = y;
        }, { passive: true });
    }

    if (isAppHome) {
        document.querySelectorAll('.category-chip').forEach(function (chip, i, all) {
            chip.addEventListener('click', function () {
                all.forEach(function (c) { c.classList.remove('active'); });
                chip.classList.add('active');
            });
        });
    }

    var loadMoreBtn = document.getElementById('loadMoreProducts');
    var productsGrid = document.getElementById('allProductsGrid');
    if (loadMoreBtn && productsGrid) {
        loadMoreBtn.addEventListener('click', function () {
            if (loadMoreBtn.classList.contains('is-loading')) return;
            var baseUrl = loadMoreBtn.getAttribute('data-url');
            var page = parseInt(loadMoreBtn.getAttribute('data-page'), 10) || 2;
            var sep = baseUrl.indexOf('?') === -1 ? '?' : '&';
            var originalText = loadMoreBtn.textContent.trim();

            loadMoreBtn.classList.add('is-loading');
            loadMoreBtn.textContent = 'লোড হচ্ছে...';

            fetch(baseUrl + sep + 'page=' + encodeURIComponent(page), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data && data.html) {
                        productsGrid.insertAdjacentHTML('beforeend', data.html);
                    }
                    if (data && data.hasMore) {
                        loadMoreBtn.setAttribute('data-page', data.nextPage);
                        loadMoreBtn.textContent = originalText;
                        loadMoreBtn.classList.remove('is-loading');
                    } else {
                        loadMoreBtn.remove();
                    }
                })
                .catch(function () {
                    loadMoreBtn.textContent = originalText;
                    loadMoreBtn.classList.remove('is-loading');
                });
        });
    }

    var noticeTicker = document.getElementById('noticeTicker');
    var noticeClose = document.getElementById('noticeClose');

    if (noticeClose && noticeTicker) {
        if (sessionStorage.getItem('noticeClosed') === '1') {
            noticeTicker.classList.add('is-hidden');
            document.body.classList.add('notice-closed');
        }

        noticeClose.addEventListener('click', function () {
            noticeTicker.classList.add('is-hidden');
            document.body.classList.add('notice-closed');
            sessionStorage.setItem('noticeClosed', '1');
        });
    }
})();
