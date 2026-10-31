@extends('frontEnd.layouts.master') 

@section('title', $seo->meta_title ?? 'Home')

@push('seo')
 
<meta name="app-url" content="{{ url('/') }}" />
<meta name="robots" content="index, follow" />

<meta name="description" content="{{ $seo->meta_description ?? '' }}" />
<meta name="keywords" content="{{ $seo->meta_tags ?? '' }}" />

<!-- Open Graph data -->
<meta property="og:title" content="{{ $seo->meta_title ?? '' }}" />
<meta property="og:type" content="website" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ asset($generalsetting->og_baner ?? 'public/logo.png') }}" />
<meta property="og:description" content="{{ $seo->meta_description ?? '' }}" />

@if(!empty($seo->search_console_verification))
<meta name="google-site-verification" content="{{ $seo->search_console_verification }}">
@endif
@endpush 


@section('content')
<main class="app-main">
    <!-- Hero Banner Slider -->
    <section class="hero-banner">
        <div class="container">
            <div class="hero-slider" id="heroSlider" aria-label="হিরো ব্যানার স্লাইডার">
                <div class="hero-track" id="heroTrack">
				
				@foreach ($sliders as $key => $value)
                    <div class="hero-slide">
                       <a href="{{ $value->link }}">
    <img src="{{ asset($value->image) }}" alt="slider" class="hero-img" @if($key === 0) fetchpriority="high" decoding="async" @else loading="lazy" decoding="async" @endif>
</a>
                    </div>
					@endforeach
					 
                 
                </div>
                <button type="button" class="hero-nav hero-prev" id="heroPrev" aria-label="আগের স্লাইড"><i class="fas fa-chevron-left"></i></button>
                <button type="button" class="hero-nav hero-next" id="heroNext" aria-label="পরের স্লাইড"><i class="fas fa-chevron-right"></i></button>
                <div class="hero-dots" id="heroDots" role="tablist" aria-label="স্লাইড নির্বাচন"></div>
            </div>
        </div>
    </section>

    <!-- Flash Sale -->
    @if(!empty($flas_sales) && count($flas_sales) > 0)
    @php
        $flashEnd = !empty(optional($generalsetting)->flash_sale_end_date)
            ? $generalsetting->flash_sale_end_date . 'T23:59:59'
            : '';
    @endphp
    <section class="flash-sale-section">
        <div class="container">
            <div class="flash-sale-head">
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

                <a href="{{ route('flashsales') }}" class="flash-sale-viewall">সব দেখুন <i class="fas fa-arrow-right"></i></a>
            </div>

            <div class="product-grid">
                @foreach($flas_sales as $value)
                    @php
                        $soldQty = (int) ($value->sold ?? 0);
                        $stockQty = is_null($value->stock) ? null : (int) $value->stock;
                        $totalQty = $stockQty !== null ? ($soldQty + $stockQty) : null;
                        $soldPct = ($totalQty && $totalQty > 0) ? min(100, round($soldQty / $totalQty * 100)) : null;
                    @endphp
                    <article class="product-card flash-card">
                        <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                            @if($value->old_price && $value->old_price > $value->new_price)
                                @php $fd = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                <span class="deal-badge flash-badge"><i class="fas fa-bolt"></i> {{ $fd }}%</span>
                            @endif
                            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" loading="lazy">
                            @if(!is_null($value->stock) && $value->stock < 1)
                                <span class="deal-stockout">STOCK OUT</span>
                            @endif
                        </a>
                        <div class="product-body">
                            <h3 class="product-title"><a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a></h3>
                            <p class="product-price">
                                @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                ৳{{ (int) $value->new_price }}
                            </p>

                            @if($soldPct !== null)
                                <div class="flash-progress" title="বিক্রি হয়েছে {{ $soldQty }}">
                                    <div class="flash-progress-bar" style="width: {{ max($soldPct, 6) }}%"></div>
                                </div>
                                <span class="flash-sold-text"><i class="fas fa-fire"></i> বিক্রি {{ $soldQty }} • বাকি {{ $stockQty }}</span>
                            @elseif($soldQty > 0)
                                <span class="flash-sold-text"><i class="fas fa-fire"></i> বিক্রি হয়েছে {{ $soldQty }}</span>
                            @endif

                            @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif
@foreach ($sliderbottomads as $value)
<section class="promo-section">
    <div class="container">
        <a href="{{ $value->link ?? '#' }}" target="_blank" style="display: block;">
            <div class="promo-banner">
                <img src="{{ asset($value->image) }}" alt="Promo banner" class="promo-banner-img" loading="lazy">
            </div>
        </a>
    </div>
</section>
@endforeach
    <!-- Hot Deal -->
    @if(!empty($hotdeal_top) && count($hotdeal_top) > 0)
    @php
        $hotDealEnd = !empty(optional($generalsetting)->hot_deal_end_date)
            ? $generalsetting->hot_deal_end_date . 'T23:59:59'
            : '';
    @endphp
    <section class="hot-deal-section">
        <div class="container">
            <div class="hot-deal-head">
                <div class="hot-deal-heading">
                    <span class="hot-deal-flame"><i class="fas fa-fire"></i></span>
                    <div class="hot-deal-heading-text">
                        <h2>Hot Deal</h2>
                        <p>সীমিত সময়ের বিশেষ অফার</p>
                    </div>
                </div>

                @if($hotDealEnd)
                    <div class="deal-countdown hot-deal-timer" data-end="{{ $hotDealEnd }}">
                        <span class="timer-label">শেষ হবে</span>
                        <div class="timer-box"><b class="t-days">00</b><small>দিন</small></div>
                        <span class="timer-colon">:</span>
                        <div class="timer-box"><b class="t-hours">00</b><small>ঘণ্টা</small></div>
                        <span class="timer-colon">:</span>
                        <div class="timer-box"><b class="t-mins">00</b><small>মিনিট</small></div>
                        <span class="timer-colon">:</span>
                        <div class="timer-box"><b class="t-secs">00</b><small>সেকেন্ড</small></div>
                    </div>
                @endif

                <a href="{{ route('hotdeals') }}" class="hot-deal-viewall">সব দেখুন <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="product-grid">
                @foreach($hotdeal_top as $value)
                    <article class="product-card">
                        <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                            @if($value->old_price && $value->old_price > $value->new_price)
                                @php $deal = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                <span class="deal-badge">{{ $deal }}% ছাড়</span>
                            @endif
                            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" loading="lazy">
                            @if(!is_null($value->stock) && $value->stock < 1)
                                <span class="deal-stockout">STOCK OUT</span>
                            @endif
                        </a>
                        <div class="product-body">
                            <h3 class="product-title"><a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a></h3>
                            <p class="product-price">
                                @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                ৳{{ (int) $value->new_price }}
                            </p>
                            @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif


@foreach ($homepageads as $value)
<section class="promo-section">
    <div class="container">
        <a href="{{ $value->link ?? '#' }}" target="_blank" style="display: block;">
            <div class="promo-banner">
                <img src="{{ asset($value->image) }}" alt="Promo banner" class="promo-banner-img" loading="lazy">
            </div>
        </a>
    </div>
</section>
@endforeach

    <!-- Category-wise Products (dynamic) -->
    @if(!empty($homeproducts) && count($homeproducts) > 0)
        @foreach($homeproducts as $homecat)
            @continue($homecat->products->isEmpty())
            <section class="product-section">
                <div class="container">
                    <div class="section-head">
                        <h2>{{ $homecat->name }}</h2>
                        <a href="{{ route('category', $homecat->slug) }}" class="btn-view-all">সব দেখুন</a>
                    </div>
                    <div class="product-grid">
                        @foreach($homecat->products as $value)
                            <article class="product-card">
                                <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                                    @if($value->old_price && $value->old_price > $value->new_price)
                                        @php $disc = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                        <span class="deal-badge">{{ $disc }}% ছাড়</span>
                                    @endif
                                    <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" loading="lazy">
                                    @if(!is_null($value->stock) && $value->stock < 1)
                                        <span class="deal-stockout">STOCK OUT</span>
                                    @endif
                                </a>
                                <div class="product-body">
                                    <h3 class="product-title"><a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a></h3>
                                    <p class="product-price">
                                        @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                        ৳{{ (int) $value->new_price }}
                                    </p>
                                    @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endif

  
  

	@foreach ($homepageads2 as $value)
<section class="promo-section">
    <div class="container">
        <a href="{{ $value->link ?? '#' }}" target="_blank" style="display: block;">
            <div class="promo-banner">
                <img src="{{ asset($value->image) }}" alt="Promo banner" class="promo-banner-img" loading="lazy">
            </div>
        </a>
    </div>
</section>
@endforeach
	
	

    <!-- All Products (dynamic, load more) -->
    @if(!empty($home_all_products) && count($home_all_products) > 0)
    <section class="product-section">
        <div class="container">
            <div class="section-head">
                <h2>সকল প্রোডাক্ট</h2>
                <a href="{{ route('all.products') }}" class="btn-view-all">সব দেখুন</a>
            </div>
            <div class="product-grid" id="allProductsGrid">
                @foreach($home_all_products as $value)
                    <article class="product-card">
                        <a href="{{ route('product', $value->slug) }}" class="product-thumb">
                            @if($value->old_price && $value->old_price > $value->new_price)
                                @php $disc = round((($value->old_price - $value->new_price) * 100) / $value->old_price); @endphp
                                <span class="deal-badge">{{ $disc }}% ছাড়</span>
                            @endif
                            <img src="{{ asset($value->image ? $value->image->image : '') }}" alt="{{ $value->name }}" loading="lazy">
                            @if(!is_null($value->stock) && $value->stock < 1)
                                <span class="deal-stockout">STOCK OUT</span>
                            @endif
                        </a>
                        <div class="product-body">
                            <h3 class="product-title"><a href="{{ route('product', $value->slug) }}">{{ Str::limit($value->name, 45) }}</a></h3>
                            <p class="product-price">
                                @if($value->old_price)<del>৳{{ (int) $value->old_price }}</del> @endif
                                ৳{{ (int) $value->new_price }}
                            </p>
                            @include('frontEnd.layouts.partials.product-actions', ['value' => $value])
                        </div>
                    </article>
                @endforeach
            </div>
            @if($home_all_products_total > 12)
                <div class="section-cta">
                    <button type="button" class="btn-load-more" id="loadMoreProducts"
                            data-url="{{ route('home.load_products') }}" data-page="2">
                       আরও দেখুন
                    </button>
                </div>
            @endif
        </div>
    </section>
    @endif




	@foreach ($hitdealsbaner as $value)
<section class="promo-section">
    <div class="container">
        <a href="{{ $value->link ?? '#' }}" target="_blank" style="display: block;">
            <div class="promo-banner">
                <img src="{{ asset($value->image) }}" alt="Promo banner" class="promo-banner-img" loading="lazy">
            </div>
        </a>
    </div>
</section>
@endforeach

{{-- ── Brand Section ── --}}
@if(isset($brands) && $brands->count() > 0 && ($generalsetting?->homepage_brands_enabled ?? 1) == 1)
<section class="brand-section">
    <div class="container">
        <div class="section-head">
            <h2>আমাদের ব্র্যান্ড</h2>
            <a href="{{ route('all.products') }}" class="btn-view-all">সব দেখুন</a>
        </div>
        <div class="brand-grid">
            @foreach($brands as $brand)
            <a href="{{ route('brand.products', $brand->slug) }}" class="brand-card">
                <div class="brand-card-img">
                    <img src="{{ asset($brand->image) }}" alt="{{ $brand->name }}" loading="lazy">
                </div>
                <span class="brand-card-name">{{ $brand->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Merchant / Vendor Shops Section ── --}}
@if(isset($vendors) && $vendors->count() > 0 && ($generalsetting?->homepage_vendors_enabled ?? 1) == 1)
<section class="vendor-section">
    <div class="container">
        <div class="section-head">
            <h2>মার্চেন্ট শপ</h2>
            <a href="/sellers" class="btn-view-all">সব দেখুন</a>
        </div>
        <div class="vendor-grid">
            @foreach($vendors->take(10) as $vendor)
            @php $vRating = (float)($vendor->average_rating ?? 0); $vFill = floor($vRating); $vHalf = ($vRating-$vFill)>=.5; @endphp
            <a href="{{ route('vendor.shop', $vendor->slug) }}" class="vendor-card">
                {{-- Banner --}}
                <div class="vendor-banner {{ $vendor->banner ? '' : 'vendor-banner--no-img' }}"
                     @if($vendor->banner) style="background-image:url('{{ asset($vendor->banner) }}')" @endif>
                    @if($vendor->verification_status === 'approved')
                        <span class="vendor-badge-verified"><i class="fas fa-check-circle"></i> Verified</span>
                    @endif
                </div>
                {{-- Body --}}
                <div class="vendor-body">
                    <div class="vendor-avatar">
                        @if($vendor->logo)
                            <img src="{{ asset($vendor->logo) }}" alt="{{ $vendor->shop_name }}">
                        @else
                            <div class="vendor-avatar-initial">{{ strtoupper(substr($vendor->shop_name,0,1)) }}</div>
                        @endif
                    </div>
                    <div class="vendor-name">{{ Str::limit($vendor->shop_name, 20) }}</div>
                    <div class="vendor-stars">
                        @for($i=1;$i<=5;$i++)
                            @if($i<=$vFill)<i class="fas fa-star"></i>
                            @elseif($vHalf && $i==$vFill+1)<i class="fas fa-star-half-alt"></i>
                            @else<i class="far fa-star"></i>
                            @endif
                        @endfor
                        <span class="vendor-review-count">({{ $vendor->total_reviews ?? 0 }})</span>
                    </div>
                    <div class="vendor-visit-btn">ভিজিট করুন <i class="fas fa-arrow-right"></i></div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── Blog Section ── --}}
@if(isset($blogs) && $blogs->count() > 0 && ($generalsetting?->homepage_blogs_enabled ?? 1) == 1)
<section class="blog-section">
    <div class="container">
        <div class="section-head">
            <h2>সর্বশেষ ব্লগ</h2>
            <a href="{{ route('blogs') }}" class="btn-view-all">সব দেখুন</a>
        </div>
        <div class="blog-grid">
            @foreach($blogs->take(3) as $blog)
            @php $wordCount = str_word_count(strip_tags($blog->description ?? $blog->short_description ?? '')); $readMin = max(1, round($wordCount/200)); @endphp
            <article class="blog-card">
                <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card-img">
                    <img src="{{ $blog->image ? url('public/'.$blog->image) : url('public/no-image.png') }}"
                         alt="{{ $blog->title }}" loading="lazy">
                    <span class="blog-card-tag">ব্লগ</span>
                </a>
                <div class="blog-card-body">
                    <div class="blog-card-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('d M Y') }}</span>
                        <span><i class="far fa-eye"></i> {{ number_format($blog->views ?? 0) }} ভিউ</span>
                    </div>
                    <h3 class="blog-card-title">
                        <a href="{{ route('blog.details', $blog->slug) }}">{{ Str::limit($blog->title, 70) }}</a>
                    </h3>
                    <p class="blog-card-excerpt">{{ Str::limit($blog->short_description, 130) }}</p>
                    <div class="blog-card-footer">
                        <a href="{{ route('blog.details', $blog->slug) }}" class="blog-read-more">
                            বিস্তারিত পড়ুন <i class="fas fa-arrow-right"></i>
                        </a>
                        <span class="blog-read-time"><i class="far fa-clock"></i> {{ $readMin }} মিনিট</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

</main>


@endsection 