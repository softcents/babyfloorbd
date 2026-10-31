@extends('frontEnd.layouts.master')
@section('title', $subcategory->subcategoryName)

@php
    $sliderStep = ($max_price > $min_price) ? max(1, min(500, ceil(($max_price - $min_price) / 80))) : 1;
@endphp

@push('seo')
    <meta name="app-url" content="{{ route('subcategory', $subcategory->slug) }}" />
    <meta name="robots" content="index, follow" />
    <meta name="description" content="{{ $subcategory->meta_description }}" />
    <meta name="keywords" content="{{ $subcategory->slug }}" />
    <meta name="twitter:card" content="product" />
    <meta name="twitter:title" content="{{ $subcategory->subcategoryName }}" />
    <meta name="twitter:description" content="{{ $subcategory->meta_description }}" />
    <meta name="twitter:image" content="{{ asset($subcategory->image) }}" />
    <meta property="og:title" content="{{ $subcategory->subcategoryName }}" />
    <meta property="og:type" content="product" />
    <meta property="og:url" content="{{ route('subcategory', $subcategory->slug) }}" />
    <meta property="og:image" content="{{ asset($subcategory->image) }}" />
    <meta property="og:description" content="{{ $subcategory->meta_description }}" />
@endpush

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
        .subcat-filter-item { display: flex; align-items: center; gap: 8px; padding: 5px 0; cursor: pointer; }
        .subcat-filter-item input[type="checkbox"] { accent-color: var(--primary); width: 15px; height: 15px; cursor: pointer; }
        .subcat-filter-item label { font-size: 13px; color: #444; cursor: pointer; }
    </style>
@endpush

@section('content')
<div class="shop-sidebar-overlay" id="shopOverlay"></div>

{{-- Banner --}}
<section class="category-banner">
    <div class="container">
        <h1><span>{{ $subcategory->subcategoryName }}</span></h1>
    </div>
</section>

<div class="shop-page">
    <div class="container">

        {{-- Child Category Pills --}}
        @if($subcategory->childcategories->isNotEmpty())
            <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
                @foreach($subcategory->childcategories as $child)
                    <a href="{{ url('products/'.$child->slug) }}"
                       style="padding:5px 14px; border:1px solid var(--border); border-radius:999px; font-size:13px; color:#444; background:#fff; transition:0.2s;"
                       onmouseover="this.style.background='var(--primary)';this.style.color='#fff';this.style.borderColor='var(--primary)'"
                       onmouseout="this.style.background='#fff';this.style.color='#444';this.style.borderColor='var(--border)'">
                        {{ $child->childcategoryName }}
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Toolbar --}}
        <div class="shop-toolbar">
            <nav class="shop-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home"></i></a>
                <span>/</span>
                <a href="{{ route('category', $subcategory->category->slug ?? '#') }}">{{ $subcategory->category->name ?? 'ক্যাটাগরি' }}</a>
                <span>/</span>
                <strong>{{ $subcategory->subcategoryName }}</strong>
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
                        @if(request()->filled('childcategory'))
                            @foreach((array) request('childcategory') as $cid)
                                <input type="hidden" name="childcategory[]" value="{{ $cid }}">
                            @endforeach
                        @endif
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

        {{-- Layout --}}
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

                    {{-- Category Navigation: always visible --}}
                    <div class="shop-sidebar-section">
                        <div class="shop-sidebar-title">
                            <i class="fas fa-th-large"></i> ক্যাটাগরি
                        </div>
                        <ul class="shop-cat-list">
                            <li>
                                <a href="{{ route('subcategory', $subcategory->slug) }}" class="active">
                                    সব পণ্য
                                </a>
                            </li>
                            @foreach($childcategories as $child)
                                <li>
                                    <a href="{{ url('products/'.$child->slug) }}"
                                       class="{{ request()->is('products/'.$child->slug) ? 'active' : '' }}">
                                        {{ $child->childcategoryName }}
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
                    @empty
                        <div class="shop-empty">
                            <i class="fas fa-box-open"></i>
                            <p>এই সাব-ক্যাটাগরিতে কোনো পণ্য পাওয়া যায়নি।</p>
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

        @if($subcategory->meta_description)
            <div class="mt-4 pt-4 border-top" style="font-size:14px; color:#666; line-height:1.7;">
                {!! $subcategory->meta_description !!}
            </div>
        @endif
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

    document.querySelectorAll('.subcat-cb').forEach(function (cb) {
        cb.addEventListener('change', function () {
            document.getElementById('filterForm').submit();
        });
    });

    var minP = {{ $min_price ?? 0 }}, maxP = {{ $max_price ?? 10000 }},
        curMin = {{ request()->filled('min_price') ? (float)request('min_price') : ($min_price ?? 0) }},
        curMax = {{ request()->filled('max_price') ? (float)request('max_price') : ($max_price ?? 10000) }};

    $('#price-range').slider({
        step: {{ $sliderStep }}, range: true, min: minP, max: maxP,
        values: [curMin, curMax],
        slide: function (e, ui) { $('#min_price').val(ui.values[0]); $('#max_price').val(ui.values[1]); }
    });
    $('#min_price').val(curMin);
    $('#max_price').val(curMax);

    var sidebar = document.getElementById('shopSidebar'),
        overlay = document.getElementById('shopOverlay'),
        btnOpen = document.getElementById('shopFilterToggle'),
        btnClose = document.getElementById('shopSidebarClose');
    function openSidebar()  { sidebar.classList.add('is-open'); overlay.classList.add('is-open'); document.body.style.overflow='hidden'; }
    function closeSidebar() { sidebar.classList.remove('is-open'); overlay.classList.remove('is-open'); document.body.style.overflow=''; }
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
})();
</script>

{{-- GA4 + Facebook Pixel --}}
<script>
window.dataLayer = window.dataLayer || [];
(function () {
    var listName = @json($subcategory->subcategoryName);
    var listSlug = @json($subcategory->slug);
    var listItems = [
        @foreach($products as $index => $value)
        { item_id:"{{ $value->id }}", item_name:@json($value->name), price:{{ (float)$value->new_price }},
          item_brand:@json(optional($value->brand)->name), item_category:@json(optional($value->category)->name ?? $subcategory->subcategoryName),
          item_list_id:listSlug, item_list_name:listName, index:{{ $loop->iteration }}, slug:@json($value->slug), currency:"BDT" }@if(!$loop->last),@endif
        @endforeach
    ];
    if (listItems.length) {
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({ event:"view_item_list", ecommerce:{ item_list_id:listSlug, item_list_name:listName, items:listItems } });
    }
    if (typeof fbq === "function") fbq("trackCustom", "ViewSubcategory", { content_category:listName, content_ids:listItems.map(function(i){return i.item_id;}), currency:"BDT" });
    $(document).on("click", ".product-card a", function () {
        var href = $(this).attr("href") || "", parts = href.split("/"), slug = parts[parts.length-1].split("?")[0];
        var item = listItems.find(function(i){ return i.slug===slug; });
        if (!item) return;
        window.dataLayer.push({ ecommerce:null });
        window.dataLayer.push({ event:"select_item", ecommerce:{ item_list_id:listSlug, item_list_name:listName, items:[item] } });
        if (typeof fbq === "function") fbq("trackCustom", "SubcategoryProductClick", { content_ids:[item.item_id], content_name:item.item_name, value:item.price, currency:"BDT" });
    });
})();
</script>
@endpush
