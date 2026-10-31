@extends('frontEnd.layouts.master')
@section('title', 'সকল পণ্য')

@php
    $sliderStep = max(1, min(500, ceil(($max_price - $min_price) / 80)));
@endphp

@push('seo')
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ optional($generalsetting)->tagline ?? 'Browse all approved products in one place.' }}" />
@endpush

@push('css')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css" />
    <style>
        /* jQuery UI Slider থিম ওভাররাইড */
        .ui-slider-horizontal { height: 5px; background: #e0e0e0; border: none; border-radius: 10px; }
        .ui-slider .ui-slider-range { background: var(--primary); border-radius: 10px; }
        .ui-slider .ui-slider-handle {
            width: 16px; height: 16px; top: -6px; border-radius: 50%;
            background: var(--primary); border: 2px solid #fff;
            box-shadow: 0 0 0 2px var(--primary); outline: none; cursor: pointer;
        }
        .ui-slider .ui-slider-handle:focus { outline: none; }
    </style>
@endpush

@section('content')
<div class="shop-sidebar-overlay" id="shopOverlay"></div>

<div class="shop-page">
    <div class="container">

        {{-- ── Toolbar ── --}}
        <div class="shop-toolbar">
            <nav class="shop-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <strong>সকল পণ্য</strong>
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
                    <form action="{{ url()->current() }}" method="GET" id="sortForm">
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

                <button type="button" class="shop-filter-toggle" id="shopFilterToggle">
                    <i class="fas fa-sliders-h"></i> ফিল্টার
                </button>
            </div>
        </div>

        {{-- ── Layout: Sidebar + Grid ── --}}
        <div class="shop-layout" id="shopLayout">

            {{-- Sidebar --}}
            <aside class="shop-sidebar" id="shopSidebar">
                <div class="shop-sidebar-head">
                    <i class="fas fa-filter"></i> ফিল্টার
                    <button type="button" class="shop-sidebar-close" id="shopSidebarClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ url()->current() }}" method="GET" id="filterForm">
                    @if(request()->filled('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    {{-- Categories --}}
                    <div class="shop-sidebar-section">
                        <div class="shop-sidebar-title">
                            <i class="fas fa-th-large"></i> ক্যাটাগরি
                        </div>
                        <ul class="shop-cat-list">
                            <li>
                                <a href="{{ route('shop') }}" class="{{ !request()->filled('category') ? 'active' : '' }}">
                                    সব পণ্য
                                </a>
                            </li>
                            @foreach($menucategories as $cat)
                                <li>
                                    <a href="{{ route('category', $cat->slug) }}">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

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
                        <div id="price-range" class="shop-price-slider ui-slider-shop"></div>
                        <button type="submit" class="shop-filter-apply mt-3">
                            <i class="fas fa-check me-1"></i> প্রয়োগ করুন
                        </button>
                    </div>
                </form>
            </aside>

            {{-- Product Grid --}}
            <div>
                <div class="product-grid product-grid--shop">
                    @forelse($products as $key => $value)
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
                                        @for($i = 0; $i < $filled; $i++)<i class="fas fa-star"></i>@endfor
                                        @if($half)<i class="fas fa-star-half-alt"></i>@endif
                                        @for($i = 0; $i < $empty; $i++)<i class="far fa-star"></i>@endfor
                                    </div>
                                @endif

                                <p class="product-price">
                                    @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                    ৳{{ (int) $value->new_price }}
                                </p>

                                @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                            </div>
                        </article>
                    @empty
                        <div class="shop-empty">
                            <i class="fas fa-box-open"></i>
                            <p>কোনো পণ্য পাওয়া যায়নি।</p>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                @if($products->hasPages())
                    <div class="shop-pagination">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

        </div>{{-- /.shop-layout --}}
    </div>{{-- /.container --}}
</div>{{-- /.shop-page --}}
@endsection

@push('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
    <script>
    (function () {
        // Sort → auto-submit
        document.getElementById('shopSort').addEventListener('change', function () {
            document.getElementById('sortForm').submit();
        });

        // Price range slider
        var minP = {{ $min_price }},
            maxP = {{ $max_price }},
            curMin = {{ request()->filled('min_price') ? (float) request('min_price') : $min_price }},
            curMax = {{ request()->filled('max_price') ? (float) request('max_price') : $max_price }};

        $('#price-range').slider({
            step: {{ $sliderStep }},
            range: true,
            min: minP,
            max: maxP,
            values: [curMin, curMax],
            slide: function (event, ui) {
                $('#min_price').val(ui.values[0]);
                $('#max_price').val(ui.values[1]);
            }
        });
        $('#min_price').val(curMin);
        $('#max_price').val(curMax);

        // Mobile sidebar toggle
        var sidebar  = document.getElementById('shopSidebar');
        var overlay  = document.getElementById('shopOverlay');
        var btnOpen  = document.getElementById('shopFilterToggle');
        var btnClose = document.getElementById('shopSidebarClose');

        function openSidebar()  { sidebar.classList.add('is-open'); overlay.classList.add('is-open'); document.body.style.overflow = 'hidden'; }
        function closeSidebar() { sidebar.classList.remove('is-open'); overlay.classList.remove('is-open'); document.body.style.overflow = ''; }

        btnOpen.addEventListener('click', openSidebar);
        btnClose.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        // ── Sticky Sidebar (desktop only) ──
        (function () {
            var sidebar   = document.getElementById('shopSidebar');
            var layout    = document.getElementById('shopLayout');
            if (!sidebar || !layout) return;

            function updateSticky() {
                if (window.innerWidth < 768) {
                    sidebar.classList.remove('is-sticky');
                    sidebar.style.removeProperty('left');
                    return;
                }
                var layoutRect  = layout.getBoundingClientRect();
                var headerH     = document.querySelector('.site-header')
                                    ? document.querySelector('.site-header').offsetHeight : 80;
                var noticeH     = document.querySelector('.notice-ticker:not(.is-hidden)')
                                    ? document.querySelector('.notice-ticker').offsetHeight : 0;
                var gap         = 12;
                var stickyTop   = headerH + noticeH + gap;

                if (layoutRect.top <= stickyTop) {
                    if (!sidebar.classList.contains('is-sticky')) {
                        sidebar.classList.add('is-sticky');
                    }
                    var sidebarLeft = layout.getBoundingClientRect().left;
                    sidebar.style.top  = stickyTop + 'px';
                    sidebar.style.left = sidebarLeft + 'px';
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
    })();
    </script>
@endpush
