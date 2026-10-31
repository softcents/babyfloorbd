@extends('frontEnd.layouts.master')
@section('title', 'সার্চ: ' . $keyword)

@php
    $sliderStep = ($max_price > $min_price) ? max(1, min(500, ceil(($max_price - $min_price) / 80))) : 1;
@endphp

@push('css')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css" />
    <style>
        .ui-slider-horizontal { height: 5px; background: #e0e0e0; border: none; border-radius: 10px; }
        .ui-slider .ui-slider-range { background: var(--primary); border-radius: 10px; }
        .ui-slider .ui-slider-handle {
            width: 16px; height: 16px; top: -6px; border-radius: 50%;
            background: var(--primary); border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--primary); outline: none; cursor: pointer;
        }
        .search-hero {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
            padding: 28px 0 24px;
            margin-bottom: 4px;
        }
        .search-hero-inner {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }
        .search-hero-icon {
            width: 48px; height: 48px;
            background: rgba(255,255,255,0.12);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            flex-shrink: 0;
        }
        .search-hero-text h1 {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 700;
            color: #fff;
            line-height: 1.3;
        }
        .search-hero-text p {
            font-size: 13px;
            color: rgba(255,255,255,0.65);
            margin-top: 3px;
        }
        .search-keyword-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary);
            color: #fff;
            border-radius: 999px;
            padding: 4px 14px;
            font-size: 13px;
            font-weight: 600;
            margin-top: 8px;
        }
        /* ── No Results ── */
        .snr-wrap {
            padding: 48px 16px 60px;
        }
        .snr-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 32px rgba(0,0,0,0.07);
            max-width: 560px;
            margin: 0 auto;
            padding: 48px 36px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .snr-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--primary-light));
        }
        .snr-icon-wrap {
            width: 96px; height: 96px;
            background: linear-gradient(135deg, #fff0f3, #ffe4e8);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            position: relative;
        }
        .snr-icon-wrap i {
            font-size: 40px;
            color: var(--primary);
        }
        .snr-icon-wrap::after {
            content: '?';
            position: absolute;
            top: 4px; right: 4px;
            width: 24px; height: 24px;
            background: var(--primary);
            color: #fff;
            border-radius: 50%;
            font-size: 13px;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
            line-height: 1;
        }
        .snr-title {
            font-size: 20px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 10px;
        }
        .snr-keyword {
            color: var(--primary);
        }
        .snr-subtitle {
            font-size: 14px;
            color: #888;
            margin-bottom: 28px;
            line-height: 1.6;
        }
        .snr-search-box {
            display: flex;
            gap: 0;
            border: 2px solid #eee;
            border-radius: 50px;
            overflow: hidden;
            transition: border-color 0.2s;
            margin-bottom: 24px;
        }
        .snr-search-box:focus-within {
            border-color: var(--primary);
        }
        .snr-search-box input {
            flex: 1;
            border: none;
            padding: 13px 20px;
            font-size: 14px;
            outline: none;
            background: transparent;
            color: #333;
        }
        .snr-search-box button {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 13px 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; gap: 6px;
            transition: background 0.2s;
        }
        .snr-search-box button:hover { background: var(--primary-dark); }
        .snr-divider {
            display: flex; align-items: center; gap: 12px;
            color: #ccc; font-size: 12px; margin-bottom: 20px;
        }
        .snr-divider::before, .snr-divider::after {
            content: ''; flex: 1; height: 1px; background: #eee;
        }
        .snr-links {
            display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;
        }
        .snr-link-btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            border: 1.5px solid var(--border);
            border-radius: 50px;
            font-size: 13px;
            font-weight: 600;
            color: #444;
            transition: all 0.2s;
        }
        .snr-link-btn:hover, .snr-link-btn.primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .snr-tips {
            margin-top: 28px;
            background: #f8f9ff;
            border-radius: 12px;
            padding: 16px 20px;
            text-align: left;
        }
        .snr-tips-title {
            font-size: 13px;
            font-weight: 700;
            color: #444;
            margin-bottom: 8px;
            display: flex; align-items: center; gap: 6px;
        }
        .snr-tips ul {
            list-style: none;
            padding: 0; margin: 0;
        }
        .snr-tips ul li {
            font-size: 13px;
            color: #666;
            padding: 3px 0;
            display: flex; align-items: flex-start; gap: 8px;
        }
        .snr-tips ul li::before {
            content: '✓';
            color: var(--primary);
            font-weight: 700;
            flex-shrink: 0;
        }
    </style>
@endpush

@section('content')
<div class="shop-sidebar-overlay" id="shopOverlay"></div>

{{-- Search Hero Banner --}}
<div class="search-hero">
    <div class="container">
        <div class="search-hero-inner">
            <div class="search-hero-icon">
                <i class="fas fa-search"></i>
            </div>
            <div class="search-hero-text">
                <h1>সার্চ রেজাল্ট</h1>
                @if($keyword)
                    <p>
                        "<strong style="color:#fff">{{ $keyword }}</strong>" এর জন্য
                        @if($products->total() > 0)
                            <span style="color:rgba(255,255,255,0.8)">{{ $products->total() }}টি পণ্য পাওয়া গেছে</span>
                        @else
                            <span style="color:rgba(255,255,255,0.6)">কোনো পণ্য পাওয়া যায়নি</span>
                        @endif
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="shop-page">
    <div class="container">

        {{-- Toolbar --}}
        <div class="shop-toolbar">
            <nav class="shop-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <strong>সার্চ: {{ $keyword }}</strong>
            </nav>

            <div class="shop-toolbar-right">
                <span class="shop-count">
                    @if($products->total() > 0)
                        {{ $products->firstItem() }}–{{ $products->lastItem() }} / মোট {{ $products->total() }} পণ্য
                    @else
                        কোনো পণ্য পাওয়া যায়নি
                    @endif
                </span>

                <div class="shop-sort">
                    <form action="{{ route('search') }}" method="GET" id="sortForm">
                        <input type="hidden" name="keyword" value="{{ $keyword }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price', $min_price) }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price', $max_price) }}">
                        <select name="sort" id="shopSort">
                            <option value="1" @selected(request('sort') == 1)>নতুন আগে</option>
                            <option value="2" @selected(request('sort') == 2)>পুরানো আগে</option>
                            <option value="3" @selected(request('sort') == 3)>দাম: বেশি → কম</option>
                            <option value="4" @selected(request('sort') == 4)>দাম: কম → বেশি</option>
                            <option value="5" @selected(request('sort') == 5)>নাম: A-Z</option>
                            <option value="6" @selected(request('sort') == 6)>নাম: Z-A</option>
                        </select>
                    </form>
                </div>

                @if($products->total() > 0)
                    <button type="button" class="shop-filter-toggle" id="shopFilterToggle">
                        <i class="fas fa-sliders-h"></i> ফিল্টার
                    </button>
                @endif
            </div>
        </div>

        @if($products->total() > 0)
        {{-- Layout: Sidebar + Grid --}}
        <div class="shop-layout" id="shopLayout">

            {{-- Sidebar --}}
            <aside class="shop-sidebar" id="shopSidebar">
                <div class="shop-sidebar-head">
                    <i class="fas fa-filter"></i> ফিল্টার
                    <button type="button" class="shop-sidebar-close" id="shopSidebarClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ route('search') }}" method="GET" id="filterForm">
                    <input type="hidden" name="keyword" value="{{ $keyword }}">
                    @if(request()->filled('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Price Range --}}
                    <div class="shop-sidebar-section">
                        <div class="shop-sidebar-title">
                            <i class="fas fa-tag"></i> মূল্য পরিসর
                        </div>
                        <div class="shop-price-inputs">
                            <span>৳</span>
                            <input type="text" name="min_price" id="min_price" readonly>
                            <span class="shop-price-sep">—</span>
                            <span>৳</span>
                            <input type="text" name="max_price" id="max_price" readonly>
                        </div>
                        <div id="price-range" class="shop-price-slider"></div>
                        <button type="submit" class="shop-filter-apply mt-3">
                            <i class="fas fa-check me-1"></i> প্রয়োগ করুন
                        </button>
                    </div>

                    {{-- New search from sidebar --}}
                    <div class="shop-sidebar-section">
                        <div class="shop-sidebar-title">
                            <i class="fas fa-search"></i> নতুন সার্চ
                        </div>
                        <div style="display:flex; gap:6px;">
                            <input type="text" name="keyword" value="{{ $keyword }}"
                                   placeholder="পণ্য খুঁজুন..."
                                   style="flex:1; border:1px solid var(--border); border-radius:7px; padding:7px 10px; font-size:13px; outline:none;">
                            <button type="submit" style="background:var(--primary); color:#fff; border:none; border-radius:7px; padding:7px 12px; cursor:pointer;">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </aside>

            {{-- Product Grid --}}
            <div>
                <div class="product-grid product-grid--shop">
                    @foreach($products as $value)
                        @php
                            $avgRating = $value->reviews->avg('ratting') ?? 0;
                            $filled    = floor($avgRating);
                            $half      = $avgRating - $filled >= 0.5;
                            $empty     = 5 - $filled - ($half ? 1 : 0);
                        @endphp
                        <article class="product-card">
                            <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                                @if($value->old_price && $value->old_price > $value->new_price)
                                    @php $disc = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                    <span class="deal-badge">{{ $disc }}% ছাড়</span>
                                @endif
                                <img src="{{ asset($value->image ? $value->image->image : 'public/frontEnd/images/no-image.png') }}"
                                     alt="{{ $value->name }}" loading="lazy">
                                @if(!is_null($value->stock) && $value->stock < 1)
                                    <span class="deal-stockout">STOCK OUT</span>
                                @endif
                            </a>
                            <div class="product-body">
                                <h3 class="product-title">
                                    <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a>
                                </h3>
                                @if($avgRating > 0)
                                    <div class="product-rating">
                                        @for($i=0;$i<$filled;$i++)<i class="fas fa-star"></i>@endfor
                                        @if($half)<i class="fas fa-star-half-alt"></i>@endif
                                        @for($i=0;$i<$empty;$i++)<i class="far fa-star"></i>@endfor
                                    </div>
                                @endif
                                <p class="product-price">
                                    @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                    ৳{{ (int) $value->new_price }}
                                </p>
                                @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($products->hasPages())
                    <div class="shop-pagination">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

        </div>{{-- /.shop-layout --}}

        @else
        {{-- ══ No Results ══ --}}
        <div class="snr-wrap">
            <div class="snr-card">

                {{-- Icon --}}
                <div class="snr-icon-wrap">
                    <i class="fas fa-search"></i>
                </div>

                {{-- Title --}}
                <h2 class="snr-title">
                    "<span class="snr-keyword">{{ $keyword }}</span>"<br>এর জন্য কোনো পণ্য পাওয়া যায়নি
                </h2>
                <p class="snr-subtitle">
                    আপনার সার্চ কীওয়ার্ডটি পাওয়া যায়নি।<br>
                    নিচে নতুন কীওয়ার্ড দিয়ে আবার চেষ্টা করুন।
                </p>

                {{-- Retry Search --}}
                <form class="snr-search-box" action="{{ route('search') }}" method="GET">
                    <input type="text" name="keyword" value="{{ $keyword }}"
                           placeholder="নতুন কীওয়ার্ড লিখুন..." autofocus>
                    <button type="submit">
                        <i class="fas fa-search"></i> সার্চ
                    </button>
                </form>

                {{-- Divider --}}
                <div class="snr-divider">অথবা</div>

                {{-- Quick Links --}}
                <div class="snr-links">
                    <a href="{{ route('shop') }}" class="snr-link-btn primary">
                        <i class="fas fa-store"></i> সকল পণ্য
                    </a>
                    @foreach($menucategories->take(4) as $cat)
                        <a href="{{ route('category', $cat->slug) }}" class="snr-link-btn">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- Tips --}}
                <div class="snr-tips">
                    <div class="snr-tips-title">
                        <i class="fas fa-lightbulb" style="color:var(--primary)"></i>
                        সার্চ টিপস
                    </div>
                    <ul>
                        <li>সঠিক বানান নিশ্চিত করুন</li>
                        <li>ছোট বা সাধারণ কীওয়ার্ড ব্যবহার করুন</li>
                        <li>ক্যাটাগরি মেনু থেকে পণ্য খুঁজুন</li>
                    </ul>
                </div>

            </div>
        </div>
        @endif

    </div>{{-- /.container --}}
</div>{{-- /.shop-page --}}
@endsection

@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
(function () {
    // Sort → auto-submit
    var sortEl = document.getElementById('shopSort');
    if (sortEl) {
        sortEl.addEventListener('change', function () {
            document.getElementById('sortForm').submit();
        });
    }

    @if($products->total() > 0)
    // Price Slider
    var minP   = {{ $min_price ?? 0 }},
        maxP   = {{ $max_price ?? 10000 }},
        curMin = {{ request()->filled('min_price') ? (float)request('min_price') : ($min_price ?? 0) }},
        curMax = {{ request()->filled('max_price') ? (float)request('max_price') : ($max_price ?? 10000) }};

    $('#price-range').slider({
        step: {{ $sliderStep }}, range: true, min: minP, max: maxP,
        values: [curMin, curMax],
        slide: function (e, ui) {
            $('#min_price').val(ui.values[0]);
            $('#max_price').val(ui.values[1]);
        }
    });
    $('#min_price').val(curMin);
    $('#max_price').val(curMax);

    // Mobile sidebar
    var sidebar  = document.getElementById('shopSidebar'),
        overlay  = document.getElementById('shopOverlay'),
        btnOpen  = document.getElementById('shopFilterToggle'),
        btnClose = document.getElementById('shopSidebarClose');

    if (sidebar && btnOpen) {
        function openSidebar()  { sidebar.classList.add('is-open'); overlay.classList.add('is-open'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('is-open'); overlay.classList.remove('is-open'); document.body.style.overflow = ''; }
        btnOpen.addEventListener('click', openSidebar);
        btnClose.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);
    }

    // Sticky sidebar
    (function () {
        var sidebar = document.getElementById('shopSidebar');
        var layout  = document.getElementById('shopLayout');
        if (!sidebar || !layout) return;
        function updateSticky() {
            if (window.innerWidth < 768) { sidebar.classList.remove('is-sticky'); sidebar.style.removeProperty('left'); return; }
            var headerH  = document.querySelector('.site-header') ? document.querySelector('.site-header').offsetHeight : 80;
            var noticeEl = document.querySelector('.notice-ticker:not(.is-hidden)');
            var noticeH  = noticeEl ? noticeEl.offsetHeight : 0;
            var stickyTop = headerH + noticeH + 12;
            var layoutTop = layout.getBoundingClientRect().top;
            if (layoutTop <= stickyTop) {
                sidebar.classList.add('is-sticky');
                sidebar.style.top  = stickyTop + 'px';
                sidebar.style.left = layout.getBoundingClientRect().left + 'px';
                sidebar.style.maxHeight = (window.innerHeight - stickyTop - 16) + 'px';
            } else {
                sidebar.classList.remove('is-sticky');
                sidebar.style.removeProperty('top');
                sidebar.style.removeProperty('left');
                sidebar.style.removeProperty('max-height');
            }
        }
        window.addEventListener('scroll', updateSticky, { passive: true });
        window.addEventListener('resize', updateSticky);
        updateSticky();
    })();
    @endif
})();
</script>
@endpush
