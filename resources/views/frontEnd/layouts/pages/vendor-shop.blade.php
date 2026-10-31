@extends('frontEnd.layouts.master')
@section('title', $vendor->shop_name)

@push('css')
<link rel="stylesheet" href="{{ asset('public/frontEnd/css/jquery-ui.css') }}">
@endpush

@section('content')

<style>
/* ===== VENDOR HERO ===== */
.vs-hero {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 28px;
    box-shadow: 0 6px 28px rgba(0,0,0,.12);
}
.vs-hero-banner {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    background-color: #1e293b;
}
.vs-hero-banner::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(160deg, rgba(0,0,0,.35) 0%, rgba(0,0,0,.65) 100%);
}
.vs-hero-body {
    position: relative;
    z-index: 2;
    padding: 40px 36px 36px;
    display: flex;
    align-items: center;
    gap: 28px;
    flex-wrap: wrap;
}

/* Logo */
.vs-logo-wrap { position: relative; flex-shrink: 0; }
.vs-logo-wrap img,
.vs-logo-fallback {
    width: 110px; height: 110px;
    border-radius: 50%;
    border: 4px solid #fff;
    object-fit: cover;
    box-shadow: 0 4px 18px rgba(0,0,0,.35);
    display: block;
}
.vs-logo-fallback {
    background: var(--primary);
    display: flex; align-items: center; justify-content: center;
    font-size: 44px; font-weight: 800; color: #fff;
}
.vs-verified {
    position: absolute; bottom: 2px; right: 2px;
    width: 30px; height: 30px; border-radius: 50%;
    background: #2563eb; border: 3px solid #fff;
    display: flex; align-items: center; justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,.25);
}
.vs-verified i { color: #fff; font-size: 13px; }

/* Info */
.vs-info { flex: 1; min-width: 0; }
.vs-shop-name {
    font-size: 28px; font-weight: 800; color: #fff;
    margin: 0 0 6px;
    text-shadow: 0 2px 8px rgba(0,0,0,.4);
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.vs-shop-desc {
    font-size: 14px; color: rgba(255,255,255,.8);
    margin: 0 0 18px; max-width: 480px; line-height: 1.6;
}
.vs-stats {
    display: flex; gap: 12px; flex-wrap: wrap;
}
.vs-stat {
    background: rgba(255,255,255,.14);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 10px;
    padding: 10px 18px;
    color: #fff;
    min-width: 90px;
}
.vs-stat-num { font-size: 22px; font-weight: 800; display: block; line-height: 1.1; }
.vs-stat-label { font-size: 11px; opacity: .85; display: block; margin-top: 2px; }
.vs-stars { display: flex; align-items: center; gap: 3px; margin-bottom: 4px; }
.vs-stars i { font-size: 14px; color: #fbbf24; }

/* ===== TOOLBAR ===== */
.vs-toolbar {
    background: #fff;
    border-radius: 12px;
    padding: 14px 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.vs-breadcrumb {
    display: flex; align-items: center; gap: 6px;
    font-size: 13px; color: #64748b;
}
.vs-breadcrumb a { color: #64748b; text-decoration: none; }
.vs-breadcrumb a:hover { color: var(--primary); }
.vs-breadcrumb span { color: #cbd5e1; }
.vs-breadcrumb strong { color: #1e293b; font-weight: 600; }
.vs-toolbar-right { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
.vs-count { font-size: 13px; color: #64748b; white-space: nowrap; }
.vs-count strong { color: #1e293b; }
.vs-sort-select {
    border: 1.5px solid #e2e8f0; border-radius: 8px;
    padding: 8px 12px; font-size: 13px; color: #374151;
    background: #f8fafc; cursor: pointer; outline: none;
    transition: border-color .2s;
}
.vs-sort-select:focus { border-color: var(--primary); }

/* ===== EMPTY STATE ===== */
.vs-empty {
    text-align: center; padding: 60px 20px;
    background: #fff; border-radius: 12px;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
}
.vs-empty i { font-size: 52px; color: #e2e8f0; display: block; margin-bottom: 16px; }
.vs-empty p { color: #94a3b8; font-size: 16px; margin: 0; }

/* ===== PAGINATION ===== */
.vs-pagination {
    display: flex; justify-content: center;
    gap: 6px; flex-wrap: wrap;
    margin-top: 32px; padding-bottom: 8px;
    list-style: none; padding-left: 0;
}
.vs-pagination a,
.vs-pagination span {
    display: flex; align-items: center; justify-content: center;
    width: 38px; height: 38px; border-radius: 50%;
    font-size: 14px; font-weight: 600; text-decoration: none;
    border: 1.5px solid #e2e8f0; background: #fff; color: #475569;
    transition: all .2s;
}
.vs-pagination a:hover { background: var(--primary); color: #fff; border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,.12); }
.vs-pagination .active { background: var(--primary); color: #fff; border-color: var(--primary); pointer-events: none; box-shadow: 0 4px 10px rgba(0,0,0,.12); }
.vs-pagination .disabled { background: #f8fafc; color: #cbd5e1; border-color: #f1f5f9; cursor: not-allowed; }

/* ===== MOBILE ===== */
@media (max-width: 600px) {
    .vs-hero-body { padding: 24px 18px; gap: 18px; }
    .vs-logo-wrap img, .vs-logo-fallback { width: 80px; height: 80px; }
    .vs-logo-fallback { font-size: 32px; }
    .vs-shop-name { font-size: 20px; }
    .vs-stat-num { font-size: 18px; }
    .vs-toolbar { padding: 12px 14px; }
    .vs-count { display: none; }
}
</style>

<section class="product-section">
    <div class="container">

        {{-- ===== VENDOR HERO ===== --}}
        <div class="vs-hero">
            <div class="vs-hero-banner" style="background-image: url('{{ $vendor->banner ? asset($vendor->banner) : '' }}');"></div>

            <div class="vs-hero-body">
                {{-- Logo --}}
                <div class="vs-logo-wrap">
                    @if($vendor->logo)
                        <img src="{{ asset($vendor->logo) }}" alt="{{ $vendor->shop_name }}">
                    @else
                        <div class="vs-logo-fallback">{{ strtoupper(substr($vendor->shop_name, 0, 1)) }}</div>
                    @endif
                    @if($vendor->verification_status == 'approved')
                    <div class="vs-verified" title="Verified Merchant">
                        <i class="fas fa-check"></i>
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="vs-info">
                    <div class="vs-shop-name">
                        {{ $vendor->shop_name }}
                        @if($vendor->verification_status == 'approved')
                        <span style="background:rgba(37,99,235,.25);border:1px solid rgba(255,255,255,.3);color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;letter-spacing:.5px;">
                            <i class="fas fa-check-circle" style="margin-right:3px;font-size:10px;"></i> VERIFIED
                        </span>
                        @endif
                    </div>

                    @if(!empty($vendor->shop_description))
                    <p class="vs-shop-desc">{{ Str::limit($vendor->shop_description, 120) }}</p>
                    @endif

                    <div class="vs-stats">
                        <div class="vs-stat">
                            <span class="vs-stat-num">{{ $vendor->total_products ?? 0 }}</span>
                            <span class="vs-stat-label">Products</span>
                        </div>
                        <div class="vs-stat">
                            <span class="vs-stat-num">{{ $vendor->total_reviews ?? 0 }}</span>
                            <span class="vs-stat-label">Reviews</span>
                        </div>
                        <div class="vs-stat">
                            @php $rating = $vendor->average_rating ?? 0; @endphp
                            <div class="vs-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($rating))
                                        <i class="fas fa-star"></i>
                                    @elseif($i - 0.5 <= $rating)
                                        <i class="fas fa-star-half-alt"></i>
                                    @else
                                        <i class="far fa-star" style="color:rgba(255,255,255,.5);"></i>
                                    @endif
                                @endfor
                                <span style="font-size:13px;font-weight:700;margin-left:4px;">{{ $rating > 0 ? number_format($rating,1) : '0.0' }}</span>
                            </div>
                            <span class="vs-stat-label">Rating</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== TOOLBAR ===== --}}
        <div class="vs-toolbar">
            <div class="vs-breadcrumb">
                <a href="{{ route('home') }}"><i class="fas fa-home" style="font-size:12px;"></i></a>
                <span>›</span>
                <strong>{{ $vendor->shop_name }}</strong>
            </div>

            <div class="vs-toolbar-right">
                <span class="vs-count">
                    Showing <strong>{{ $products->firstItem() ?? 0 }}–{{ $products->lastItem() ?? 0 }}</strong>
                    of <strong>{{ $products->total() }}</strong> products
                </span>
                <form class="sort-form">
                    <select name="sort" class="vs-sort-select">
                        <option value="1" @selected(request('sort')==1)>Latest</option>
                        <option value="2" @selected(request('sort')==2)>Oldest</option>
                        <option value="3" @selected(request('sort')==3)>Price: High → Low</option>
                        <option value="4" @selected(request('sort')==4)>Price: Low → High</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- ===== PRODUCT GRID ===== --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="category-product main_product_inner">

                    @forelse($products as $key => $value)
                    <div class="product_item wist_item wow zoomIn"
                         data-wow-duration="1.5s"
                         data-wow-delay="0.{{ $key }}s">
                        <div class="product_item_inner">

                            @if($value->old_price && $value->old_price > $value->new_price)
                            <div class="sale-badge">
                                <div class="sale-badge-inner">
                                    <div class="sale-badge-box">
                                        <span class="sale-badge-text">
                                            <p>{{ number_format((($value->old_price - $value->new_price) * 100) / $value->old_price, 0) }}%</p>
                                            ছাড়
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="pro_img">
                                <a href="{{ route('product', $value->slug) }}">
                                    <img src="{{ asset($value->image ? $value->image->image : '') }}"
                                         alt="{{ $value->name }}" class="img-fluid" loading="lazy">
                                </a>
                            </div>

                            <div class="pro_des">
                                <div class="pro_name">
                                    <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 35) }}</a>
                                </div>
                            </div>
                        </div>

                        @php
                            $avgRating  = $value->reviews->avg('ratting');
                            $filled     = floor($avgRating);
                            $half       = $avgRating - $filled >= 0.5;
                            $empty      = 5 - $filled - ($half ? 1 : 0);
                        @endphp
                        @for ($i = 0; $i < $filled; $i++) <i class="fas fa-star"></i> @endfor
                        @if ($half) <i class="fas fa-star-half-alt"></i> @endif
                        @for ($i = 0; $i < $empty; $i++) <i class="far fa-star"></i> @endfor

                        <div class="pro_price">
                            <p>
                                @if($value->old_price) <del>৳ {{ $value->old_price }}</del> @endif
                                ৳ {{ $value->new_price }}
                            </p>
                        </div>

                        @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                    </div>
                    @empty
                    <div class="col-12">
                        <div class="vs-empty">
                            <i class="fas fa-box-open"></i>
                            <p>এই শপে এখনো কোনো পণ্য নেই।</p>
                        </div>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- ===== PAGINATION ===== --}}
        @if($products->hasPages())
        <div class="vs-pagination">
            @if($products->onFirstPage())
                <span class="disabled"><i class="fas fa-chevron-left"></i></span>
            @else
                <a href="{{ $products->previousPageUrl() }}"><i class="fas fa-chevron-left"></i></a>
            @endif

            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                @if($page == $products->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach

            @if($products->hasMorePages())
                <a href="{{ $products->nextPageUrl() }}"><i class="fas fa-chevron-right"></i></a>
            @else
                <span class="disabled"><i class="fas fa-chevron-right"></i></span>
            @endif
        </div>
        @endif

    </div>
</section>

@endsection

@push('script')
<script>
document.querySelector('.sort-form select').addEventListener('change', function() {
    this.form.submit();
});
</script>
@endpush
