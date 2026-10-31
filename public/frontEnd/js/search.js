(function () {
    'use strict';

    var PRODUCTS = [
        { name: 'অর্গানিক বাগানের লিচু', price: '320৳ – 620৳', img: 'lichu-product-15-1024x1024.png', views: 248 },
        { name: 'প্রিমিয়াম মিষ্টি লিচু', price: '350৳ – 680৳', img: 'lichu-product-14-1024x1024.png', views: 192 },
        { name: 'দিনাজপুর লিচু বাজেট প্যাক', price: '240৳ – 470৳', img: 'lichu-product-20-1024x1024.png', views: 315 },
        { name: 'রাজশাহী লিচু উপহার ঝুড়ি', price: '270৳ – 530৳', img: 'lichu-product-19-1024x1024.png', views: 178 },
        { name: 'সিজনাল টাটকা লিচু', price: '350৳ – 680৳', img: 'lichu-product-18-1024x1024.png', views: 221 },
        { name: 'বাছাই করা বড় লিচু', price: '260৳ – 520৳', img: 'lichu-product-17-1024x1024.png', views: 156 },
        { name: 'হোম ডেলিভারি লিচু প্যাক', price: '260৳ – 510৳', img: 'lichu-product-16-1024x1024.png', views: 203 },
        { name: 'চায়না-৩ লিচু স্পেশাল', price: '380৳ – 720৳', img: 'lichu-product-13-1024x1024.png', views: 167 },
        { name: 'বোম্বাই লিচু প্রিমিয়াম', price: '300৳ – 580৳', img: 'lichu-product-12-1024x1024.png', views: 134 },
        { name: 'বেদানা লিচু কালেকশন', price: '290৳ – 560৳', img: 'lichu-product-11-1024x1024.png', views: 189 },
        { name: 'ফ্যামিলি লিচু প্যাক', price: '420৳ – 850৳', img: 'lichu-product-10-1024x1024.png', views: 276 },
        { name: 'গিফট বক্স লিচু', price: '450৳ – 900৳', img: 'lichu-product-9-1024x1024.png', views: 142 }
    ];

    var IMG_BASE = 'https://lichu.borbila.net/wp-content/uploads/2026/05/lichu-products/';

    var params = new URLSearchParams(window.location.search);
    var query = (params.get('q') || '').trim();

    var searchInputs = document.querySelectorAll('input[name="q"], #searchPageInput');
    var resultsGrid = document.getElementById('searchResults');
    var resultsTitle = document.getElementById('searchResultsTitle');
    var resultsCount = document.getElementById('searchResultsCount');
    var emptyState = document.getElementById('searchEmpty');

    searchInputs.forEach(function (input) {
        if (query) input.value = query;
    });

    function matches(product, q) {
        return product.name.toLowerCase().indexOf(q.toLowerCase()) !== -1;
    }

    function renderCard(product) {
        return (
            '<article class="product-card">' +
                '<a href="product-detail.html" class="product-thumb">' +
                    '<img src="' + IMG_BASE + product.img + '" alt="' + product.name + '" loading="lazy">' +
                '</a>' +
                '<div class="product-body">' +
                    '<div class="product-rating" aria-label="৫ স্টার">' +
                        '<i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>' +
                    '</div>' +
                    '<h3 class="product-title"><a href="product-detail.html">' + product.name + '</a></h3>' +
                    '<div class="product-meta-row">' +
                        '<p class="product-price">' + product.price + '</p>' +
                        '<span class="product-views"><i class="far fa-eye"></i> ' + product.views + '</span>' +
                    '</div>' +
                    '<div class="product-actions">' +
                        '<a href="product-detail.html" class="btn-order">অর্ডার করুন</a>' +
                        '<a href="#" class="btn-cart" aria-label="কার্টে যোগ করুন"><i class="fas fa-shopping-cart"></i></a>' +
                    '</div>' +
                '</div>' +
            '</article>'
        );
    }

    function renderResults() {
        if (!resultsGrid) return;

        var filtered = query
            ? PRODUCTS.filter(function (p) { return matches(p, query); })
            : PRODUCTS;

        if (resultsTitle) {
            resultsTitle.textContent = query
                ? '"' + query + '" এর জন্য অনুসন্ধান ফলাফল'
                : 'সকল পণ্য';
        }

        if (resultsCount) {
            resultsCount.textContent = filtered.length + 'টি পণ্য পাওয়া গেছে';
        }

        if (emptyState) {
            emptyState.hidden = filtered.length > 0;
        }

        resultsGrid.innerHTML = filtered.map(renderCard).join('');
        resultsGrid.hidden = filtered.length === 0;
    }

    renderResults();
})();
