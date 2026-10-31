@extends('frontEnd.layouts.master')
@section('title', 'Flash Sale')

@php
    $generalsetting = \App\Models\GeneralSetting::first();
    $flashEnd = !empty($generalsetting->flash_sale_end_date)
        ? $generalsetting->flash_sale_end_date . 'T23:59:59'
        : '';
    $sliderStep = ($max_price > $min_price) ? max(1, min(500, ceil(($max_price - $min_price) / 80))) : 1;
@endphp

@push('css')
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/themes/base/jquery-ui.css" />
    <style>
        .ui-slider-horizontal { height: 5px; background: #e0e0e0; border: none; border-radius: 10px; }
        .ui-slider .ui-slider-range { background: #ff9800; border-radius: 10px; }
        .ui-slider .ui-slider-handle {
            width: 16px; height: 16px; top: -6px; border-radius: 50%;
            background: #ff9800; border: 2px solid #fff;
            box-shadow: 0 0 0 2px #ff9800; outline: none; cursor: pointer;
        }
    </style>
@endpush

@section('content')
<div class="shop-sidebar-overlay" id="shopOverlay"></div>

<div class="shop-page">
    <div class="container">

        {{-- ── Flash Sale Header Banner ── --}}
        <div class="flash-sale-head mb-4" style="margin-top: 4px;">
            <div class="flash-sale-heading">
                <span class="flash-sale-bolt"><i class="fas fa-bolt"></i></span>
                <div class="flash-sale-heading-text">
                    <h2>Flash Sale</h2>
                    <p>ঝলমলে ছাড় — দ্রুত কিনুন!</p>
                </div>
            </div>

            @if($flashEnd)
                <div class="deal-countdown flash-sale-timer" data-end="{{ $flashEnd }}">
                    <span class="timer-label">শেষ হতে বাকি</span>
                    <div class="timer-box"><b class="t-days">00</b><small>দিন</small></div>
                    <span class="timer-colon">:</span>
                    <div class="timer-box"><b class="t-hours">00</b><small>ঘণ্টা</small></div>
                    <span class="timer-colon">:</span>
                    <div class="timer-box"><b class="t-mins">00</b><small>মিনিট</small></div>
                    <span class="timer-colon">:</span>
                    <div class="timer-box"><b class="t-secs">00</b><small>সেকেন্ড</small></div>
                </div>
            @endif
        </div>

        {{-- ── Toolbar ── --}}
        <div class="shop-toolbar">
            <nav class="shop-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <strong>Flash Sale</strong>
            </nav>

            <div class="shop-toolbar-right">
                <span class="shop-count">
                    @if($products->total() > 0)
                        {{ $products->firstItem() }}–{{ $products->lastItem() }} / মোট {{ $products->total() }} পণ্য
                    @else
                        কোনো পণ্য নেই
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

        {{-- ── Layout ── --}}
        <div class="shop-layout" id="shopLayout">

            {{-- Sidebar --}}
            <aside class="shop-sidebar" id="shopSidebar">
                <div class="shop-sidebar-head" style="background: linear-gradient(120deg, #1f2937 0%, var(--primary-dark) 55%, var(--primary) 100%);">
                    <i class="fas fa-bolt"></i> Flash Sale Filter
                    <button type="button" class="shop-sidebar-close" id="shopSidebarClose">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form action="{{ url()->current() }}" method="GET" id="filterForm">
                    @if(request()->filled('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

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
                </form>
            </aside>

            {{-- Product Grid --}}
            <div>
                <div class="product-grid product-grid--shop">
                    @forelse($products as $value)
                        @php
                            $soldQty  = (int) ($value->sold ?? 0);
                            $stockQty = is_null($value->stock) ? null : (int) $value->stock;
                            $totalQty = $stockQty !== null ? ($soldQty + $stockQty) : null;
                            $soldPct  = ($totalQty && $totalQty > 0) ? min(100, round($soldQty / $totalQty * 100)) : null;
                        @endphp

                        <article class="product-card flash-card">
                            <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                                @if($value->old_price && $value->old_price > $value->new_price)
                                    @php $fd = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                    <span class="deal-badge flash-badge"><i class="fas fa-bolt"></i> {{ $fd }}%</span>
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

                                <p class="product-price">
                                    @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                    ৳{{ (int) $value->new_price }}
                                </p>

                                @if($soldPct !== null)
                                    <div class="flash-progress" title="বিক্রি হয়েছে {{ $soldQty }}">
                                        <div class="flash-progress-bar" style="width: {{ max($soldPct, 6) }}%"></div>
                                    </div>
                                    <span class="flash-sold-text">
                                        <i class="fas fa-fire"></i> বিক্রি {{ $soldQty }} • বাকি {{ $stockQty }}
                                    </span>
                                @elseif($soldQty > 0)
                                    <span class="flash-sold-text">
                                        <i class="fas fa-fire"></i> বিক্রি হয়েছে {{ $soldQty }}
                                    </span>
                                @endif

                                @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                            </div>
                        </article>
                    @empty
                        <div class="shop-empty">
                            <i class="fas fa-bolt"></i>
                            <p>এই মুহূর্তে কোনো ফ্ল্যাশ সেল পণ্য নেই।</p>
                        </div>
                    @endforelse
                </div>

                @if($products->hasPages())
                    <div class="shop-pagination">
                        {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"></script>
<script>
(function () {
    document.getElementById('shopSort').addEventListener('change', function () {
        document.getElementById('sortForm').submit();
    });

    var minP = {{ $min_price ?? 0 }},
        maxP = {{ $max_price ?? 10000 }},
        curMin = {{ request()->filled('min_price') ? (float) request('min_price') : ($min_price ?? 0) }},
        curMax = {{ request()->filled('max_price') ? (float) request('max_price') : ($max_price ?? 10000) }};

    $('#price-range').slider({
        step: {{ $sliderStep }},
        range: true, min: minP, max: maxP,
        values: [curMin, curMax],
        slide: function (e, ui) {
            $('#min_price').val(ui.values[0]);
            $('#max_price').val(ui.values[1]);
        }
    });
    $('#min_price').val(curMin);
    $('#max_price').val(curMax);

    var sidebar = document.getElementById('shopSidebar'),
        overlay = document.getElementById('shopOverlay'),
        btnOpen = document.getElementById('shopFilterToggle'),
        btnClose = document.getElementById('shopSidebarClose');

    function openSidebar()  { sidebar.classList.add('is-open'); overlay.classList.add('is-open'); document.body.style.overflow = 'hidden'; }
    function closeSidebar() { sidebar.classList.remove('is-open'); overlay.classList.remove('is-open'); document.body.style.overflow = ''; }

    btnOpen.addEventListener('click', openSidebar);
    btnClose.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);

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

    @if($flashEnd)
    // Countdown Timer
    (function countdown() {
        var end = new Date("{{ $flashEnd }}").getTime();
        function tick() {
            var now  = Date.now(),
                diff = Math.max(0, end - now),
                d    = Math.floor(diff / 86400000),
                h    = Math.floor((diff % 86400000) / 3600000),
                m    = Math.floor((diff % 3600000) / 60000),
                s    = Math.floor((diff % 60000) / 1000);
            function pad(n) { return String(n).padStart(2, '0'); }
            document.querySelectorAll('.flash-sale-timer .t-days').forEach(function(el){ el.textContent = pad(d); });
            document.querySelectorAll('.flash-sale-timer .t-hours').forEach(function(el){ el.textContent = pad(h); });
            document.querySelectorAll('.flash-sale-timer .t-mins').forEach(function(el){ el.textContent = pad(m); });
            document.querySelectorAll('.flash-sale-timer .t-secs').forEach(function(el){ el.textContent = pad(s); });
            if (diff > 0) setTimeout(tick, 1000);
        }
        tick();
    })();
    @endif
})();
</script>
@endpush
