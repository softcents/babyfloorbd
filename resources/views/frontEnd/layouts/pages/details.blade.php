@extends('frontEnd.layouts.master')
@section('title', $details->name)

@push('seo')
@php
    $metaTitle       = $details->meta_title ?? $details->name;
    $metaDescription = $details->meta_description ?? Str::limit(strip_tags($details->description), 160);
    $metaKeywords    = $details->meta_keywords ?? $details->name;
    $metaImage       = $details->meta_image ? asset($details->meta_image) : asset(optional($details->image)->image);
@endphp
<meta name="app-url"     content="{{ route('product', $details->slug) }}" />
<meta name="robots"      content="index, follow" />
<meta name="title"       content="{{ $metaTitle }}" />
<meta name="description" content="{{ $metaDescription }}" />
<meta name="keywords"    content="{{ $metaKeywords }}" />
<meta name="twitter:card"        content="summary_large_image" />
<meta name="twitter:title"       content="{{ $metaTitle }}" />
<meta name="twitter:description" content="{{ $metaDescription }}" />
<meta name="twitter:image"       content="{{ $metaImage }}" />
<meta property="og:title"       content="{{ $metaTitle }}" />
<meta property="og:type"        content="product" />
<meta property="og:url"         content="{{ route('product', $details->slug) }}" />
<meta property="og:image"       content="{{ $metaImage }}" />
<meta property="og:description" content="{{ $metaDescription }}" />
@endpush

@push('css')
<style>
/* Page background */
.product-detail-page { background: #f5f5f5; padding: 20px 0 56px; }

/* Review modal (page-specific) */
.review-modal { position:fixed; inset:0; z-index:2000; display:flex; align-items:center; justify-content:center; }
.review-modal[hidden] { display:none; }
.review-modal-overlay { position:absolute; inset:0; background:rgba(0,0,0,.55); cursor:pointer; }
.review-modal-box { position:relative; z-index:1; background:#fff; border-radius:16px; padding:28px; width:100%; max-width:460px; margin:16px; max-height:92vh; overflow-y:auto; }
.review-modal-head { display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; }
.review-modal-head h3 { font-size:17px; font-weight:700; color:#1a1a2e; margin:0; }
.review-modal-close { background:none; border:none; font-size:20px; cursor:pointer; color:#888; line-height:1; padding:4px; }
.review-modal-close:hover { color:var(--primary); }
.review-star-input { display:flex; gap:6px; margin-bottom:16px; }
.star-input-btn { background:none; border:none; cursor:pointer; padding:0; font-size:28px; color:#ddd; transition:color .15s; line-height:1; }
.star-input-btn.active,.star-input-btn:hover { color:#f5b301; }
.review-input,.review-form--modal .review-textarea { width:100%; border:1.5px solid #e8e8e8; border-radius:10px; padding:11px 14px; font-size:14px; color:#333; outline:none; font-family:inherit; transition:border-color .2s; margin-bottom:14px; background:#fff; box-sizing:border-box; }
.review-input:focus,.review-form--modal .review-textarea:focus { border-color:var(--primary); }
.review-form--modal .review-textarea { resize:vertical; }
.btn-submit-review { width:100%; background:var(--primary); color:#fff; border:none; border-radius:10px; padding:13px; font-size:14px; font-weight:700; cursor:pointer; font-family:inherit; }

/* Wholesale (page-specific) */
.wholesale-section { background:#f8fff8; border:1px solid #d4edda; border-radius:10px; padding:16px; margin-bottom:18px; }
.wholesale-section h5 { font-size:14px; font-weight:700; margin:0 0 10px; color:#155724; }
.wholesale-table { width:100%; border-collapse:collapse; font-size:13px; }
.wholesale-table th { padding:8px 10px; background:#e9f7ef; font-weight:600; color:#155724; text-align:left; }
.wholesale-table td { padding:8px 10px; border-top:1px solid #d4edda; }
.wholesale-tier-row { cursor:pointer; transition:background .15s; }
.wholesale-tier-row:hover { background:#f0fdf4; }
.wholesale-tier-row.active-tier { background:#d4edda; border-left:3px solid #28a745; }
.wholesale-price { color:#22c55e; font-weight:700; }
</style>
@endpush

@section('content')
@php
    /* ── computed variables ── */
    $avgRating  = (float)($productReviewsAverage ?? 0);
    $fillStars  = floor($avgRating);
    $halfStar   = ($avgRating - $fillStars) >= 0.5;
    $emptyStars = 5 - $fillStars - ($halfStar ? 1 : 0);

    $videoType     = $details->pro_video_type ?? ($details->pro_video ? 'youtube' : null);
    $hasVideo      = ($videoType === 'youtube' && $details->pro_video)
                  || ($videoType === 'upload'  && $details->pro_video_path);
    $videoEmbedSrc = '';
    if ($hasVideo) {
        $videoEmbedSrc = $videoType === 'youtube'
            ? 'https://www.youtube.com/embed/'.$details->pro_video.'?autoplay=1&rel=0'
            : asset($details->pro_video_path);
    }

    $firstImage = optional($details->images->first())->image;

    $galleryThumbCount = $details->images->count() + ($hasVideo ? 1 : 0);
    $galleryScrollable = $galleryThumbCount > 5;

    /* variant helpers */
    $productcolors = $details->variantPrices->pluck('color')->unique('id')->filter();
    $productsizes  = $details->variantPrices->pluck('size')->unique('id')->filter();
@endphp

{{-- hidden form for cart/order submission --}}
<form action="{{ route('cart.store') }}" method="POST" name="formName" id="productForm" style="display:none">
    @csrf
    <input type="hidden" name="id"    value="{{ $details->id }}">
    <input type="hidden" name="qty"   id="formQty"
           value="{{ ($details->is_wholesale && optional($details->wholesalePrices)->count() > 0) ? max(1,(int)$details->wholesalePrices->sortBy('min_quantity')->first()->min_quantity) : 1 }}">
    @if($details->pro_unit)
        <input type="hidden" name="pro_unit" value="{{ $details->pro_unit }}">
    @endif
    @if($productcolors->count() > 0)
        @foreach($productcolors as $pc)
            <input type="radio" name="product_color" value="{{ $pc->id }}" id="clr{{ $pc->id }}" class="emptyalert">
        @endforeach
    @endif
    @if($productsizes->count() > 0)
        @foreach($productsizes as $ps)
            <input type="radio" name="product_size" value="{{ $ps->id }}" id="sz{{ $ps->id }}" class="emptyalert">
        @endforeach
    @endif
</form>

<section class="product-detail-page product-section">
    <div class="container">

        {{-- ── Breadcrumb ── --}}
        <nav class="breadcrumb" aria-label="ব্রেডক্রাম্ব">
            <a href="{{ route('home') }}">হোম</a>
            <span>/</span>
            <a href="{{ route('category', $details->category->slug) }}">{{ $details->category->name }}</a>
            @if($details->subcategory)
                <span>/</span>
                <a href="{{ route('subcategory', $details->subcategory->slug) }}">{{ $details->subcategory->subcategoryName }}</a>
            @endif
            @if($details->childcategory)
                <span>/</span>
                <a href="{{ route('products', $details->childcategory->slug) }}">{{ $details->childcategory->childcategoryName }}</a>
            @endif
            <span>/</span>
            <span>{{ Str::limit($details->name, 40) }}</span>
        </nav>

        {{-- ── Product Detail ── --}}
        <div class="product-detail">

            {{-- Gallery --}}
            <div class="product-gallery">
                <div class="gallery-main" id="galleryMainBox">
                    @if($details->old_price && $details->old_price > $details->new_price)
                        @php $disc = round((($details->old_price - $details->new_price)*100)/$details->old_price); @endphp
                        <span class="gallery-disc-badge">{{ $disc }}% ছাড়</span>
                    @endif

                    <img id="galleryMainImg" class="gallery-media is-active"
                         src="{{ asset($firstImage ?? 'public/frontEnd/images/no-image.png') }}"
                         alt="{{ $details->name }}">

                    <div class="gallery-video-wrap" id="galleryVideoWrap" hidden>
                        @if($videoType === 'youtube')
                            <iframe id="galleryVideoFrame" src="" title="পণ্যের ভিডিও"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                        @elseif($videoType === 'upload')
                            <video id="galleryVideoFile" controls>
                                <source src="{{ $videoEmbedSrc }}" type="video/mp4">
                            </video>
                        @endif
                    </div>
                </div>

                <div class="gallery-thumbs-wrap{{ $galleryScrollable ? ' is-scrollable' : '' }}">
                    @if($galleryScrollable)
                    <button type="button" class="gallery-thumbs-nav gallery-thumbs-nav--prev" id="galleryThumbsPrev" aria-label="আগের ছবি" disabled>
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    @endif

                    <div class="gallery-thumbs{{ $galleryScrollable ? ' is-scrollable' : '' }}" id="galleryThumbs" role="tablist" aria-label="পণ্যের ছবি ও ভিডিও">
                        @foreach($details->images as $k => $img)
                        <button type="button"
                                class="gallery-thumb {{ $k === 0 ? 'active' : '' }}"
                                role="tab"
                                aria-selected="{{ $k === 0 ? 'true' : 'false' }}"
                                aria-label="ছবি {{ $k+1 }}"
                                data-type="image"
                                data-src="{{ asset($img->image) }}"
                                data-color-id="{{ $img->color_id ?? '' }}">
                            <img src="{{ asset($img->image) }}" alt="">
                        </button>
                        @endforeach
                        @if($hasVideo)
                        <button type="button"
                                class="gallery-thumb gallery-thumb--video"
                                role="tab" aria-selected="false"
                                aria-label="পণ্যের ভিডিও"
                                data-type="video"
                                data-src="{{ $videoEmbedSrc }}"
                                data-videotype="{{ $videoType }}">
                            @if($videoType === 'youtube' && $details->pro_video)
                                <img src="https://img.youtube.com/vi/{{ $details->pro_video }}/mqdefault.jpg"
                                     alt="ভিডিও থাম্বনেইল" style="width:100%;height:100%;object-fit:cover;">
                                <span class="video-thumb-inner" style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;background:rgba(0,0,0,.35);">
                                    <i class="fas fa-play" style="font-size:20px;color:#fff;"></i>
                                </span>
                            @else
                                <span class="video-thumb-inner">
                                    <i class="fas fa-play"></i>
                                    <span>ভিডিও</span>
                                </span>
                            @endif
                        </button>
                        @endif
                    </div>

                    @if($galleryScrollable)
                    <button type="button" class="gallery-thumbs-nav gallery-thumbs-nav--next" id="galleryThumbsNext" aria-label="পরের ছবি">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                    @endif
                </div>
            </div>

            {{-- Product Info --}}
            <div class="product-detail-info">

                <h1 class="product-detail-title">{{ $details->name }}</h1>

                <div class="product-rating product-rating--detail" aria-label="{{ $avgRating }} স্টার">
                    @for($i=0;$i<$fillStars;$i++)<i class="fas fa-star"></i>@endfor
                    @if($halfStar)<i class="fas fa-star-half-alt"></i>@endif
                    @for($i=0;$i<$emptyStars;$i++)<i class="far fa-star"></i>@endfor
                    <span class="rating-count">({{ $productReviewsTotal }} রিভিউ)</span>
                </div>

                <ul class="product-attrs">
                    @if($details->brand)
                    <li>
                        <span class="attr-label">ব্র্যান্ড:</span>
                        <a href="{{ route('category', $details->category->slug) }}" class="attr-value product-brand">{{ $details->brand->name }}</a>
                    </li>
                    @endif
                    <li>
                        <span class="attr-label">ক্যাটাগরি:</span>
                        <a href="{{ route('category', $details->category->slug) }}" class="attr-value">{{ $details->category->name }}</a>
                    </li>
                    @if($details->product_code)
                    <li>
                        <span class="attr-label">কোড:</span>
                        <span class="attr-value">{{ $details->product_code }}</span>
                    </li>
                    @endif
                    <li>
                        <span class="attr-label">স্টক:</span>
                        @if(!is_null($details->stock) && $details->stock < 1)
                            <span class="attr-value out-stock"><i class="fas fa-times-circle"></i> স্টক নেই</span>
                        @else
                            <span class="attr-value in-stock"><i class="fas fa-check-circle"></i> স্টকে আছে</span>
                        @endif
                    </li>
                </ul>

                @if($details->short_description)
                    <p class="product-detail-short">{{ $details->short_description }}</p>
                @endif

                <p class="product-detail-price">
                    @if($details->old_price)<del>৳{{ (int)$details->old_price }}</del>@endif
                    <span id="newPrice">৳{{ (int)$details->new_price }}</span>
                </p>

                {{-- Wholesale --}}
                @if($details->is_wholesale && optional($details->wholesalePrices)->count() > 0)
                <div class="wholesale-section">
                    <h5><i class="fas fa-tags"></i> পাইকারি মূল্য</h5>
                    <table class="wholesale-table">
                        <thead><tr><th>পরিমাণ</th><th>মূল্য</th><th>স্টক</th></tr></thead>
                        <tbody>
                            @foreach($details->wholesalePrices->sortBy('min_quantity') as $tier)
                            <tr class="wholesale-tier-row"
                                data-min-qty="{{ $tier->min_quantity }}"
                                data-max-qty="{{ $tier->max_quantity ?? 999999 }}"
                                data-price="{{ $tier->wholesale_price }}">
                                <td>{{ $tier->min_quantity }}{{ $tier->max_quantity ? ' – '.$tier->max_quantity : '+' }} পিস</td>
                                <td class="wholesale-price">৳{{ number_format($tier->wholesale_price,2) }}</td>
                                <td>{{ $tier->stock ?? 0 }} পিস</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                {{-- Colors --}}
                @if($productcolors->count() > 0)
                <div class="pro-color">
                    <span class="product-option-label">রঙ বেছে নিন</span>
                    <div class="color-selector">
                        @foreach($productcolors as $pc)
                        @php $colorHex = $pc->color ?? ($pc->colorCode ?? '#ccc'); @endphp
                        <div class="color-swatch-item" title="{{ $pc->name ?? $pc->colorName ?? '' }}">
                            <input type="radio" id="ci_clr{{ $pc->id }}" name="ui_color" value="{{ $pc->id }}"
                                   data-real-id="clr{{ $pc->id }}">
                            <label class="color-swatch" for="ci_clr{{ $pc->id }}"
                                   style="background-color:{{ $colorHex }};">
                                <i class="fas fa-check color-swatch-check"></i>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Sizes as weight-btn --}}
                @if($productsizes->count() > 0)
                <label class="product-option-label">সাইজ / ভ্যারিয়েন্ট নির্বাচন করুন</label>
                <div class="weight-options" id="weightOptions">
                    @foreach($productsizes as $ps)
                    @php
                        $vPrice = $details->variantPrices
                            ->where('size_id', $ps->id)
                            ->first()?->price ?? $details->new_price;
                    @endphp
                    <button type="button" class="weight-btn"
                            data-size-id="{{ $ps->id }}"
                            data-price="{{ (int)$vPrice }}">
                        {{ $ps->sizeName ?? $ps->name }}
                    </button>
                    @endforeach
                </div>
                <button type="button" class="btn-clear-option" id="btnClearSize">Clear</button>
                @endif

                <div class="product-qty-row">
                    <label class="product-option-label" for="qtyInput">পরিমাণ</label>
                    <div class="qty-selector">
                        <button type="button" class="qty-btn" id="qtyMinus" aria-label="কমান">−</button>
                        <input type="number" class="qty-input" id="qtyInput"
                               value="{{ ($details->is_wholesale && optional($details->wholesalePrices)->count() > 0) ? max(1,(int)$details->wholesalePrices->sortBy('min_quantity')->first()->min_quantity) : 1 }}"
                               min="1" max="99" aria-label="পরিমাণ">
                        <button type="button" class="qty-btn" id="qtyPlus" aria-label="বাড়ান">+</button>
                    </div>
                </div>

                <div class="product-detail-actions">
                    <a href="#" class="btn-add-cart" id="btnAddCart">
                        <i class="fas fa-shopping-basket"></i> Add to Cart
                    </a>
                    <a href="#" class="btn-buy-now" id="btnBuyNow">
                        <i class="fas fa-bolt"></i> Buy Now
                    </a>
                </div>

                <div class="product-share">
                    <h3>Share:</h3>
                    <div class="share-links">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(Request::url()) }}"
                           target="_blank" rel="noopener" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(Request::url()) }}&text={{ urlencode($details->name) }}"
                           target="_blank" rel="noopener" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($details->name.' – '.Request::url()) }}"
                           target="_blank" rel="noopener" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>

            </div>{{-- /.product-detail-info --}}
        </div>{{-- /.product-detail --}}

        {{-- ── Description ── --}}
        <section class="product-description">
            <h2 class="pd-section-title">Description</h2>
            <div class="pd-content">
                {!! $details->description !!}
            </div>
        </section>

        {{-- ── Reviews ── --}}
        <section class="product-reviews" id="reviews">
            <div class="reviews-head">
                <h2>গ্রাহক রিভিউ</h2>
                <button type="button" class="btn-write-review" id="openReviewModal">রিভিউ লিখুন</button>
            </div>

            <div class="reviews-summary">
                <div class="reviews-score-box">
                    <span class="reviews-score">{{ number_format($avgRating, 1) }}</span>
                    <div class="product-rating product-rating--detail" aria-label="{{ $avgRating }} স্টার">
                        @for($i=0;$i<$fillStars;$i++)<i class="fas fa-star"></i>@endfor
                        @if($halfStar)<i class="fas fa-star-half-alt"></i>@endif
                        @for($i=0;$i<$emptyStars;$i++)<i class="far fa-star"></i>@endfor
                    </div>
                    <p class="reviews-total">{{ $productReviewsTotal }}টি রিভিউ</p>
                </div>
                @php
                    $starCounts = $productReviews->groupBy('ratting')->map->count();
                    $totalR = max($productReviewsTotal, 1);
                @endphp
                <div class="reviews-bars">
                    @foreach([5,4,3,2,1] as $star)
                    @php $cnt = $starCounts[$star] ?? 0; $pct = round($cnt/$totalR*100); @endphp
                    <div class="review-bar-row">
                        <span>{{ $star }}★</span>
                        <div class="review-bar"><span style="width:{{ $pct }}%"></span></div>
                        <em>{{ $cnt }}</em>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="review-list" id="productReviewList">
                @if($productReviewsTotal > 0)
                    @include('frontEnd.layouts.ajax.product-reviews', ['reviews' => $productReviews])
                @else
                    <div style="text-align:center;padding:40px;color:#aaa;">
                        <i class="far fa-comment-dots" style="font-size:40px;display:block;margin-bottom:12px;"></i>
                        এখনো কোনো রিভিউ নেই।
                    </div>
                @endif
            </div>

            @if($productReviewsTotal > $productReviews->count())
            <button type="button" class="btn-load-more" id="loadMoreProductReviews"
                    data-product-id="{{ $details->id }}"
                    data-offset="{{ $productReviews->count() }}"
                    data-limit="3">
                আরো রিভিউ দেখুন
            </button>
            @endif
        </section>

        {{-- ── Related Products ── --}}
        @if($products->count() > 0)
        <section class="related-products">
            <h2 class="related-title">এ জাতীয় আরও পণ্য</h2>
            <div class="product-grid">
                @foreach($products->take(8) as $value)
                @php
                    $rAvg  = $value->reviews->avg('ratting') ?? 0;
                    $rFill = floor($rAvg);
                    $rHalf = ($rAvg - $rFill) >= 0.5;
                    $rEmpty= 5 - $rFill - ($rHalf ? 1 : 0);
                @endphp
                <article class="product-card">
                    <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                        @if($value->old_price && $value->old_price > $value->new_price)
                            @php $rd = round((($value->old_price-$value->new_price)*100)/$value->old_price); @endphp
                            <span class="deal-badge">{{ $rd }}% ছাড়</span>
                        @endif
                        <img src="{{ asset(optional($value->image)->image ?? 'public/frontEnd/images/no-image.png') }}"
                             alt="{{ $value->name }}" loading="lazy">
                    </a>
                    <div class="product-body">
                        <h3 class="product-title">
                            <a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a>
                        </h3>
                        <p class="product-price">
                            @if($value->old_price)<del>৳{{ (int)$value->old_price }}</del> @endif
                            ৳{{ (int)$value->new_price }}
                        </p>
                        <div class="product-actions">
                            @if(!$value->prosizes->isEmpty() || !$value->procolors->isEmpty())
                                <a href="{{ route('product', $value->slug) }}" class="btn-order">অর্ডার করুন</a>
                                <a href="{{ route('product', $value->slug) }}" class="btn-cart" aria-label="কার্টে যোগ করুন"><i class="fas fa-shopping-cart"></i></a>
                            @else
                                <form action="{{ route('cart.store') }}" method="POST" style="display:contents">
                                    @csrf
                                    <input type="hidden" name="id"        value="{{ $value->id }}">
                                    <input type="hidden" name="qty"       value="1">
                                    <input type="hidden" name="order_now" value="1">
                                    <button type="submit" class="btn-order">অর্ডার করুন</button>
                                </form>
                                <form action="{{ route('cart.store') }}" method="POST" style="display:contents">
                                    @csrf
                                    <input type="hidden" name="id"  value="{{ $value->id }}">
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="btn-cart cart_store" data-id="{{ $value->id }}" aria-label="কার্টে যোগ করুন">
                                        <i class="fas fa-shopping-cart"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
        </section>
        @endif

    </div>
</section>

{{-- ════ Review Modal ════ --}}
<div class="review-modal" id="reviewModal" role="dialog" aria-modal="true" aria-labelledby="reviewModalTitle" hidden>
    <div class="review-modal-overlay" id="reviewModalOverlay"></div>
    <div class="review-modal-box">
        <div class="review-modal-head">
            <h3 id="reviewModalTitle">রিভিউ লিখুন</h3>
            <button type="button" class="review-modal-close" id="reviewModalClose" aria-label="বন্ধ করুন">
                <i class="fas fa-times"></i>
            </button>
        </div>

        @if(Auth::guard('customer')->check())
        <form class="review-form review-form--modal" id="reviewForm"
              action="{{ route('customer.review') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $details->id }}">

            <label class="product-option-label" for="reviewRating">রেটিং</label>
            <div class="review-star-input" id="reviewStarInput" role="radiogroup" aria-label="রেটিং নির্বাচন">
                <button type="button" class="star-input-btn" data-value="1" aria-label="১ স্টার"><i class="far fa-star"></i></button>
                <button type="button" class="star-input-btn" data-value="2" aria-label="২ স্টার"><i class="far fa-star"></i></button>
                <button type="button" class="star-input-btn" data-value="3" aria-label="৩ স্টার"><i class="far fa-star"></i></button>
                <button type="button" class="star-input-btn" data-value="4" aria-label="৪ স্টার"><i class="far fa-star"></i></button>
                <button type="button" class="star-input-btn" data-value="5" aria-label="৫ স্টার"><i class="far fa-star"></i></button>
                <input type="hidden" name="ratting" id="reviewRating" value="0">
            </div>

            <label class="product-option-label" for="reviewMessage">রিভিউ</label>
            <textarea class="review-textarea" id="reviewMessage" name="review"
                      rows="4" placeholder="আপনার অভিজ্ঞতা লিখুন..." required></textarea>

            <button type="submit" class="btn-submit-review">রিভিউ জমা দিন</button>
        </form>
        @else
        <div style="text-align:center;padding:24px 0;">
            <i class="fas fa-user-circle" style="font-size:52px;color:#ddd;display:block;margin-bottom:14px;"></i>
            <p style="color:#666;margin-bottom:16px;">রিভিউ লিখতে লগইন করুন</p>
            <a href="{{ route('customer.login') }}"
               style="background:var(--primary);color:#fff;padding:11px 32px;border-radius:8px;font-weight:700;display:inline-block;text-decoration:none;">
                লগইন করুন
            </a>
        </div>
        @endif
    </div>
</div>

@endsection

@push('script')
<script>
var basePrice = {{ (float)$details->new_price }};
var variants  = @json($details->variantPrices);

@if($details->is_wholesale && optional($details->wholesalePrices)->count() > 0)
var wholesaleTiers = @json($details->wholesalePrices->sortBy('min_quantity')->values());
function getWholesalePrice(qty) {
    for (var i = 0; i < wholesaleTiers.length; i++) {
        var t = wholesaleTiers[i];
        if (qty >= t.min_quantity && qty <= (t.max_quantity || 999999)) return t.wholesale_price;
    }
    return null;
}
function highlightTier(qty) {
    document.querySelectorAll('.wholesale-tier-row').forEach(function(r) {
        r.classList.toggle('active-tier',
            qty >= parseInt(r.dataset.minQty,10) && qty <= parseInt(r.dataset.maxQty,10));
    });
}
document.querySelectorAll('.wholesale-tier-row').forEach(function(row) {
    row.addEventListener('click', function() {
        document.getElementById('qtyInput').value = parseInt(row.dataset.minQty,10);
        syncQty(); updatePrice();
    });
});
@endif

/* ── Gallery ── */
(function(){
    var mainImg    = document.getElementById('galleryMainImg');
    var videoWrap  = document.getElementById('galleryVideoWrap');
    var videoFrame = document.getElementById('galleryVideoFrame');
    var thumbsWrap = document.getElementById('galleryThumbs');
    var prevBtn    = document.getElementById('galleryThumbsPrev');
    var nextBtn    = document.getElementById('galleryThumbsNext');
    var thumbs     = document.querySelectorAll('.gallery-thumb');
    var isScrollable = thumbsWrap && thumbsWrap.classList.contains('is-scrollable');

    function setActive(btn) {
        thumbs.forEach(function(b){ b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
        btn.classList.add('active'); btn.setAttribute('aria-selected','true');
    }

    function scrollThumbIntoView(btn) {
        if (!isScrollable || !btn || btn.style.display === 'none') return;
        btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'nearest' });
    }

    function updateNavState() {
        if (!isScrollable || !thumbsWrap) return;
        var max = thumbsWrap.scrollWidth - thumbsWrap.clientWidth;
        if (prevBtn) prevBtn.disabled = thumbsWrap.scrollLeft <= 2;
        if (nextBtn) nextBtn.disabled = thumbsWrap.scrollLeft >= max - 2;
    }

    function scrollByThumb(dir) {
        if (!thumbsWrap) return;
        var thumb = thumbsWrap.querySelector('.gallery-thumb');
        var step = thumb ? thumb.offsetWidth + 10 : 82;
        thumbsWrap.scrollBy({ left: dir * step, behavior: 'smooth' });
    }

    if (isScrollable) {
        if (prevBtn) prevBtn.addEventListener('click', function(){ scrollByThumb(-1); });
        if (nextBtn) nextBtn.addEventListener('click', function(){ scrollByThumb(1); });
        thumbsWrap.addEventListener('scroll', updateNavState, { passive: true });
        window.addEventListener('resize', updateNavState);
        updateNavState();
        window.updateGalleryThumbsNav = updateNavState;
    }

    thumbs.forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (btn.dataset.type === 'video') {
                setActive(btn);
                mainImg.style.display = 'none';
                if (videoWrap) videoWrap.hidden = false;
                if (btn.dataset.videotype === 'youtube' && videoFrame) videoFrame.src = btn.dataset.src;
            } else {
                setActive(btn);
                if (videoWrap) { videoWrap.hidden = true; if(videoFrame) videoFrame.src=''; }
                mainImg.style.display = '';
                mainImg.src = btn.dataset.src;
            }
            scrollThumbIntoView(btn);
        });
    });
})();

/* ── Qty sync ── */
function syncQty() {
    document.getElementById('formQty').value = document.getElementById('qtyInput').value;
}
document.getElementById('qtyMinus').addEventListener('click', function(){
    var inp = document.getElementById('qtyInput');
    inp.value = Math.max(1, parseInt(inp.value,10)-1);
    syncQty(); updatePrice();
});
document.getElementById('qtyPlus').addEventListener('click', function(){
    var inp = document.getElementById('qtyInput');
    inp.value = parseInt(inp.value,10)+1;
    syncQty(); updatePrice();
});
document.getElementById('qtyInput').addEventListener('input', function(){ syncQty(); updatePrice(); });

/* ── Weight/Size buttons ── */
document.querySelectorAll('.weight-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.weight-btn').forEach(function(b){ b.classList.remove('active'); });
        btn.classList.add('active');
        var radio = document.getElementById('sz' + btn.dataset.sizeId);
        if (radio) radio.checked = true;
        updatePrice();
    });
});
var clearBtn = document.getElementById('btnClearSize');
if (clearBtn) {
    clearBtn.addEventListener('click', function() {
        document.querySelectorAll('.weight-btn').forEach(function(b){ b.classList.remove('active'); });
        document.querySelectorAll('#productForm input[name="product_size"]').forEach(function(r){ r.checked=false; });
        updatePrice();
    });
}

/* ── Color sync ── */
document.querySelectorAll('input[name="ui_color"]').forEach(function(ui) {
    ['change','click'].forEach(function(ev){
        ui.addEventListener(ev, function() {
            /* sync to hidden form radio */
            var real = document.getElementById(ui.dataset.realId);
            if (real) { real.checked = true; }
            filterImagesByColor(ui.value);
            updatePrice();
        });
    });
});

function filterImagesByColor(colorId) {
    var thumbs = document.querySelectorAll('#galleryThumbs .gallery-thumb:not(.gallery-thumb--video)');
    var first = null;
    thumbs.forEach(function(btn) {
        var cid = btn.dataset.colorId;
        var show = !colorId || !cid || String(cid)===String(colorId);
        btn.style.display = show ? '' : 'none';
        if (show && !first) first = btn;
    });
    if (first) first.click();
    if (typeof window.updateGalleryThumbsNav === 'function') {
        window.updateGalleryThumbsNav();
    }
}

/* ── Price updater ── */
function getColorId() {
    var el = document.querySelector('#productForm input[name="product_color"]:checked');
    return el ? String(el.value) : null;
}
function getSizeId() {
    var el = document.querySelector('#productForm input[name="product_size"]:checked');
    return el ? String(el.value) : null;
}
function getVColorId(v) {
    if (v.color_id != null) return String(v.color_id);
    if (v.color && typeof v.color === 'object' && v.color.id != null) return String(v.color.id);
    return null;
}
function getVSizeId(v) {
    if (v.size_id != null) return String(v.size_id);
    if (v.size && typeof v.size === 'object' && v.size.id != null) return String(v.size.id);
    return null;
}

function updatePrice() {
    var color = getColorId();
    var size  = getSizeId();

    var match = null;
    /* exact match */
    if (color && size) {
        match = variants.find(function(v){ return getVColorId(v)===color && getVSizeId(v)===size; });
    }
    /* color only */
    if (!match && color && !size) {
        match = variants.find(function(v){ return getVColorId(v)===color; });
    }
    /* size only */
    if (!match && size && !color) {
        match = variants.find(function(v){ return getVSizeId(v)===size; });
    }
    /* color with any size */
    if (!match && color) {
        match = variants.find(function(v){ return getVColorId(v)===color; });
    }
    /* size with any color */
    if (!match && size) {
        match = variants.find(function(v){ return getVSizeId(v)===size; });
    }

    var price = basePrice;
    if (match && match.price != null && parseFloat(match.price) > 0) {
        price = parseFloat(match.price);
    }

    @if($details->is_wholesale && optional($details->wholesalePrices)->count() > 0)
    var qty = parseInt(document.getElementById('qtyInput').value,10)||1;
    var wp  = getWholesalePrice(qty);
    if (wp !== null) price = parseFloat(wp);
    highlightTier(qty);
    @endif

    document.getElementById('newPrice').textContent = '৳'+Math.round(price);
}
updatePrice();

/* ── Professional Center Popup ── */
(function(){
    var overlay = document.createElement('div');
    overlay.id = 'pdAlertOverlay';
    overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.5);'
        +'backdrop-filter:blur(2px);-webkit-backdrop-filter:blur(2px);';

    var box = document.createElement('div');
    box.id  = 'pdAlertBox';
    box.style.cssText = 'position:fixed;top:50%;left:50%;transform:translate(-50%,-50%) scale(.88);'
        +'z-index:9999;background:#fff;border-radius:18px;padding:36px 32px 28px;'
        +'width:92%;max-width:360px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.22);'
        +'transition:transform .22s cubic-bezier(.34,1.56,.64,1),opacity .22s;opacity:0;pointer-events:none;';

    box.innerHTML =
        '<div id="pdAlertIcon" style="width:64px;height:64px;border-radius:50%;margin:0 auto 18px;'
        +'display:flex;align-items:center;justify-content:center;font-size:28px;"></div>'
        +'<p id="pdAlertMsg" style="font-size:16px;font-weight:700;color:#1a1a2e;margin:0 0 8px;line-height:1.4;"></p>'
        +'<p id="pdAlertSub" style="font-size:13px;color:#888;margin:0 0 22px;"></p>'
        +'<button id="pdAlertOk" style="background:var(--primary);color:#fff;border:none;border-radius:10px;'
        +'padding:12px 36px;font-size:14px;font-weight:700;cursor:pointer;font-family:inherit;'
        +'transition:filter .2s;width:100%;">ঠিক আছে</button>';

    document.body.appendChild(overlay);
    document.body.appendChild(box);

    function closePdAlert(){
        box.style.transform    = 'translate(-50%,-50%) scale(.88)';
        box.style.opacity      = '0';
        box.style.pointerEvents = 'none';
        overlay.style.display  = 'none';
        document.body.style.overflow = '';
    }
    document.getElementById('pdAlertOk').addEventListener('click', closePdAlert);
    overlay.addEventListener('click', closePdAlert);

    window.pdAlert = function(msg, sub, type) {
        var icon = document.getElementById('pdAlertIcon');
        var colors = { error: '#e53e3e', warning: '#f5a623', info: '#3182ce' };
        var icons  = { error: '✕', warning: '⚠', info: 'ℹ' };
        var bg = colors[type] || colors.warning;
        icon.style.background = bg + '1a'; /* 10% opacity */
        icon.style.color = bg;
        icon.textContent = icons[type] || icons.warning;
        document.getElementById('pdAlertMsg').textContent = msg;
        document.getElementById('pdAlertSub').textContent = sub || '';
        document.getElementById('pdAlertOk').style.background = bg;
        box.style.pointerEvents = 'auto';
        overlay.style.display   = 'block';
        document.body.style.overflow = 'hidden';
        /* trigger animation */
        requestAnimationFrame(function(){
            box.style.transform = 'translate(-50%,-50%) scale(1)';
            box.style.opacity   = '1';
        });
    };
})();

/* ── Add to Cart / Buy Now buttons ── */
function sendSuccess(actionName) {
    var form = document.getElementById('productForm');
    if (!form) return false;
    if (form.querySelector('input[name="product_color"]') &&
        !form.querySelector('input[name="product_color"]:checked')) {
        pdAlert('রঙ সিলেক্ট করুন', 'অর্ডার করার আগে পণ্যের রঙ নির্বাচন করুন।', 'error');
        return false;
    }
    if (form.querySelector('input[name="product_size"]') &&
        !form.querySelector('input[name="product_size"]:checked')) {
        pdAlert('সাইজ / ভ্যারিয়েন্ট সিলেক্ট করুন', 'অর্ডার করার আগে পণ্যের সাইজ বা ভ্যারিয়েন্ট নির্বাচন করুন।', 'warning');
        return false;
    }
    // add hidden input for action
    var existing = form.querySelector('input[name="'+actionName+'"]');
    if (!existing) {
        var inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = actionName; inp.value = '1';
        form.appendChild(inp);
    }
    // remove opposite action
    var opposite = actionName === 'add_cart' ? 'order_now' : 'add_cart';
    var old = form.querySelector('input[name="'+opposite+'"]');
    if (old) old.parentNode.removeChild(old);

    syncQty();
    form.submit();
    return false;
}

document.getElementById('btnAddCart').addEventListener('click', function(e){
    e.preventDefault();
    sendSuccess('add_cart');
});
document.getElementById('btnBuyNow').addEventListener('click', function(e){
    e.preventDefault();
    sendSuccess('order_now');
});

/* ── Review Modal ── */
var reviewModal   = document.getElementById('reviewModal');
var reviewOverlay = document.getElementById('reviewModalOverlay');
document.getElementById('openReviewModal').addEventListener('click', function(){
    reviewModal.hidden = false; document.body.style.overflow='hidden';
});
document.getElementById('reviewModalClose').addEventListener('click', function(){
    reviewModal.hidden = true; document.body.style.overflow='';
});
if (reviewOverlay) reviewOverlay.addEventListener('click', function(){
    reviewModal.hidden = true; document.body.style.overflow='';
});

/* ── Star input buttons ── */
var starBtns = document.querySelectorAll('.star-input-btn');
var ratingInput = document.getElementById('reviewRating');
if (starBtns.length && ratingInput) {
    starBtns.forEach(function(btn) {
        btn.addEventListener('click', function(){
            var val = parseInt(btn.dataset.value,10);
            ratingInput.value = val;
            starBtns.forEach(function(b,i){
                b.classList.toggle('active', i < val);
                b.querySelector('i').className = i < val ? 'fas fa-star' : 'far fa-star';
            });
        });
        btn.addEventListener('mouseenter', function(){
            var val = parseInt(btn.dataset.value,10);
            starBtns.forEach(function(b,i){ b.querySelector('i').className = i < val ? 'fas fa-star' : 'far fa-star'; });
        });
        btn.addEventListener('mouseleave', function(){
            var cur = parseInt(ratingInput.value,10)||0;
            starBtns.forEach(function(b,i){ b.querySelector('i').className = i < cur ? 'fas fa-star' : 'far fa-star'; });
        });
    });
}

/* ── Load more reviews ── */
(function(){
    var btn = document.getElementById('loadMoreProductReviews');
    if (!btn) return;
    btn.addEventListener('click', function(){
        var productId = btn.dataset.productId;
        var offset    = parseInt(btn.dataset.offset||'0',10);
        var limit     = parseInt(btn.dataset.limit||'3',10);
        var list      = document.getElementById('productReviewList');
        btn.disabled  = true; btn.textContent='লোড হচ্ছে...';
        $.ajax({
            url: '{{ route('product.reviews.load') }}', type:'GET', dataType:'json',
            data:{ product_id:productId, offset:offset, limit:limit },
            success:function(res){
                if(res&&res.ok&&res.html) list.insertAdjacentHTML('beforeend',res.html);
                btn.setAttribute('data-offset', res.loaded);
                if(!res||!res.has_more) btn.parentElement.removeChild(btn);
                else { btn.disabled=false; btn.textContent='আরো রিভিউ দেখুন'; }
            },
            error:function(){ btn.disabled=false; btn.textContent='আরো রিভিউ দেখুন'; }
        });
    });
})();
</script>

{{-- Unified EcomTracking (GTM + FB + TikTok) --}}
<script>
(function () {
    var productItem = {
        id:    '{{ $details->id }}',
        name:  @json($details->name),
        price: {{ (float) $details->new_price }},
        qty:   1
    };

    function getQty() {
        var el = document.getElementById('qtyInput');
        var qty = parseInt(el ? el.value : '1', 10);
        return (isNaN(qty) || qty < 1) ? 1 : qty;
    }

    function trackViewContent() {
        if (typeof window.EcomTracking !== 'undefined') {
            EcomTracking.viewContent({ items: [productItem], value: productItem.price });
            return;
        }
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({
            event: 'view_item',
            ecommerce: {
                currency: 'BDT',
                value: productItem.price,
                items: [{ item_id: productItem.id, item_name: productItem.name, price: productItem.price, quantity: 1 }]
            }
        });
        if (typeof fbq === 'function') {
            fbq('track', 'ViewContent', { content_ids: [productItem.id], content_name: productItem.name, value: productItem.price, currency: 'BDT' });
        }
        if (typeof ttq !== 'undefined') {
            ttq.track('ViewContent', { content_type: 'product', content_id: String(productItem.id), value: productItem.price, currency: 'BDT' });
        }
    }

    function trackAddToCart() {
        var qty = getQty();
        var value = productItem.price * qty;
        var line = { id: productItem.id, name: productItem.name, price: productItem.price, qty: qty };
        if (typeof window.EcomTracking !== 'undefined') {
            EcomTracking.addToCart({ items: [line], value: value });
            return;
        }
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({ event: 'add_to_cart', ecommerce: { currency: 'BDT', value: value, items: [{ item_id: line.id, item_name: line.name, price: line.price, quantity: qty }] } });
        if (typeof fbq === 'function') fbq('track', 'AddToCart', { content_ids: [line.id], value: value, currency: 'BDT' });
        if (typeof ttq !== 'undefined') ttq.track('AddToCart', { content_type: 'product', value: value, currency: 'BDT', contents: [{ content_id: String(line.id), quantity: qty, price: line.price }] });
    }

    function trackInitiateCheckout() {
        var qty = getQty();
        var value = productItem.price * qty;
        var line = { id: productItem.id, name: productItem.name, price: productItem.price, qty: qty };
        if (typeof window.EcomTracking !== 'undefined') {
            EcomTracking.initiateCheckout({ items: [line], value: value });
            return;
        }
        window.dataLayer.push({ ecommerce: null });
        window.dataLayer.push({ event: 'begin_checkout', ecommerce: { currency: 'BDT', value: value, items: [{ item_id: line.id, item_name: line.name, price: line.price, quantity: qty }] } });
        if (typeof fbq === 'function') fbq('track', 'InitiateCheckout', { value: value, currency: 'BDT' });
        if (typeof ttq !== 'undefined') ttq.track('InitiateCheckout', { value: value, currency: 'BDT', quantity: qty, content_type: 'product' });
    }

    trackViewContent();

    var btnCart = document.getElementById('btnAddCart');
    var btnBuy  = document.getElementById('btnBuyNow');
    if (btnCart) btnCart.addEventListener('click', trackAddToCart);
    if (btnBuy)  btnBuy.addEventListener('click', trackInitiateCheckout);
})();
</script>
@endpush
