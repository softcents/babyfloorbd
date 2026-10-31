<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="{{ ($themeColors ?? [])['primary'] ?? '#df2d4d' }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title')</title>
			@if(!empty($seo->search_console_verification))
{!! $seo->search_console_verification ?? '' !!}
@endif
        <link rel="shortcut icon" href="{{asset($generalsetting->favicon)}}" alt="Super Ecommerce Favicon" />
        <meta name="author" content="Creative Design" />
        <link rel="canonical" href="" />
        @include('frontEnd.layouts.partials.fb_app_id_meta')
        @stack('seo')
        @stack('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <link rel="preload" href="{{ asset('public/frontEnd/css/style.css') }}?v={{ $assetVersions['style'] ?? time() }}" as="style">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/style.css') }}?v={{ $assetVersions['style'] ?? time() }}">
    <link rel="stylesheet" href="{{ asset('public/frontEnd/css/site-fixes.css') }}?v={{ file_exists(public_path('frontEnd/css/site-fixes.css')) ? filemtime(public_path('frontEnd/css/site-fixes.css')) : time() }}">
    @include('frontEnd.layouts.partials.dynamic-theme')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Roboto:wght@400;500;600;700&display=swap" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&family=Roboto:wght@400;500;600;700&display=swap"></noscript>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"></noscript>
    <script>window.dataLayer = window.dataLayer || [];</script>

@php
    $breakingNewsText = trim(preg_replace('/\s+/u', ' ', (string) (optional($generalsetting)->top_headline ?? '')));
    $showNewsTicker = $breakingNewsText !== ''
        && (int) (optional($generalsetting)->news_ticker_enabled ?? 0) === 1;
    $tickerSpeed = max(20, min(50, (int) (strlen($breakingNewsText) / 4)));
@endphp
</head>
<body class="app-home {{ $showNewsTicker ? 'has-notice' : 'notice-closed' }}">
    {{-- ========== Google Tag Manager (noscript) ========== --}}
    @foreach($gtm_code ?? [] as $gtm)
    @php $gtm_ns_id = preg_match('/^GTM-/i', trim($gtm->code)) ? trim($gtm->code) : 'GTM-'.trim($gtm->code); @endphp
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtm_ns_id }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endforeach
    {{-- ========== End GTM noscript ========== --}}

@if($showNewsTicker)
<!-- Scrolling Notice Topbar -->
<div class="notice-ticker" id="noticeTicker" role="region" aria-label="বিশেষ ঘোষণা">
    <div class="notice-badge">
        <i class="fas fa-bolt" aria-hidden="true"></i>
        <span>SPECIAL NOTICE</span>
    </div>
    <div class="notice-track-wrap">
        <div class="notice-track">
		
		
		@foreach([1, 2] as $copy)
            <span class="notice-text">{{ $breakingNewsText }}<</span>
             @endforeach
		
		
		</div>
    </div>
    <button type="button" class="notice-close" id="noticeClose" aria-label="ঘোষণা বন্ধ করুন">
        <i class="fas fa-times" aria-hidden="true"></i>
    </button>
</div>
@endif


<!-- Header -->
<header class="site-header">
    <div class="container header-inner">
        <button class="menu-toggle" id="menuToggle" aria-label="মেনু খুলুন" aria-expanded="false">
            <i class="fas fa-bars"></i>
        </button>

        <a href="{{route('home')}}" class="logo">
            <img src="{{asset($generalsetting->dark_logo)}}" alt="লিচু">
        </a>

        <form class="mobile-search" action="{{ route('search') }}" method="get" role="search" autocomplete="off"
              data-live-url="{{ route('livesearch') }}">
            <input type="search" name="keyword" id="mobileSearchInput" placeholder="পণ্য খুঁজুন..." aria-label="পণ্য খুঁজুন">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <div class="mobile-search-results" id="mobileSearchResults" aria-live="polite"></div>
        </form>

        <nav class="main-nav" id="mainNav" aria-label="প্রধান মেনু">
            <div class="drawer-head">
                <span class="drawer-title">মোবাইল মেন্যু</span>
                <button type="button" class="drawer-close" id="menuClose" aria-label="মেনু বন্ধ করুন">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="drawer-links">
                <a href="{{ route('home') }}" class="drawer-only"><i class="fas fa-house"></i> হোম</a>

                <div class="nav-cat-desktop">
                    @foreach($menucategories as $category)
                        @if($category->subcategories->count())
                            <div class="nav-cat-item has-children">
                                <a href="{{ route('category', $category->slug) }}"
                                   class="nav-cat-link {{ request()->is('category/'.$category->slug) ? 'active' : '' }}"
                                   aria-haspopup="true">
                                    {{ $category->name }}
                                    <span class="nav-cat-chevron" aria-hidden="true">
                                        <i class="fas fa-angle-down"></i>
                                    </span>
                                </a>
                                <div class="nav-sub-dropdown">
                                    @foreach($category->subcategories as $subcategory)
                                        @if($subcategory->childcategories->count())
                                            <div class="nav-sub-item has-children">
                                                <a href="{{ route('subcategory', $subcategory->slug) }}" class="nav-sub-link">
                                                    {{ $subcategory->subcategoryName }}
                                                    <i class="fas fa-angle-right nav-sub-arrow" aria-hidden="true"></i>
                                                </a>
                                                <div class="nav-child-dropdown">
                                                    @foreach($subcategory->childcategories as $childcategory)
                                                        <a href="{{ route('products', $childcategory->slug) }}">
                                                            {{ $childcategory->childcategoryName }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @else
                                            <a href="{{ route('subcategory', $subcategory->slug) }}" class="nav-sub-link">
                                                {{ $subcategory->subcategoryName }}
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a href="{{ route('category', $category->slug) }}"
                               class="nav-cat-link {{ request()->is('category/'.$category->slug) ? 'active' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endif
                    @endforeach
                </div>

                <div class="nav-cat-mobile drawer-only-block">
                    @foreach($menucategories as $category)
                        <div class="mobile-cat-group {{ $category->subcategories->count() ? 'has-children' : '' }}">
                            <div class="mobile-cat-row">
                                <a href="{{ route('category', $category->slug) }}" class="mobile-cat-parent">
                                    @if(!empty($category->icon))
                                        <img src="{{ asset($category->icon) }}" alt="" class="side-cat-icon">
                                    @elseif(!empty($category->image))
                                        <img src="{{ asset($category->image) }}" alt="" class="side-cat-icon">
                                    @else
                                        <i class="fas fa-leaf"></i>
                                    @endif
                                    {{ $category->name }}
                                </a>
                                @if($category->subcategories->count())
                                    <button type="button" class="mobile-sub-toggle" aria-expanded="false" aria-label="{{ $category->name }} সাব ক্যাটাগরি">
                                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                    </button>
                                @endif
                            </div>
                            @if($category->subcategories->count())
                                <div class="drawer-sub-links">
                                    @foreach($category->subcategories as $subcategory)
                                        <div class="drawer-sub-group {{ $subcategory->childcategories->count() ? 'has-children' : '' }}">
                                            <div class="drawer-sub-row">
                                                <a href="{{ route('subcategory', $subcategory->slug) }}" class="drawer-sub-parent">
                                                    {{ $subcategory->subcategoryName }}
                                                </a>
                                                @if($subcategory->childcategories->count())
                                                    <button type="button" class="drawer-child-toggle" aria-expanded="false" aria-label="{{ $subcategory->subcategoryName }} চাইল্ড ক্যাটাগরি">
                                                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            @if($subcategory->childcategories->count())
                                                <div class="drawer-child-links">
                                                    @foreach($subcategory->childcategories as $childcategory)
                                                        <a href="{{ route('products', $childcategory->slug) }}">
                                                            {{ $childcategory->childcategoryName }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <a href="{{ route('customer.order_track') }}" class="drawer-only"><i class="fas fa-truck"></i> Track Order</a>
                <a href="{{ route('complaint') }}" class="drawer-only"><i class="fas fa-headset"></i> কমপ্লেইন</a>
                <a href="{{ route('customer.login') }}" class="drawer-only"><i class="far fa-user"></i> আমার অ্যাকাউন্ট</a>
            </div>
        </nav>

        @php
            $cartCount = Cart::instance('shopping')->count();
            $cartSubtotal = (int) floatval(preg_replace('/[^\d.]/', '', Cart::instance('shopping')->subtotal()));
            $isCustomerLoggedIn = Auth::guard('customer')->check();
        @endphp
        <div class="header-actions">
            <div class="cart-wrap" data-remove-url="{{ route('cart.remove') }}">
                <a href="{{ route('customer.checkout') }}" class="cart-btn" aria-label="কার্ট">
                    <span class="cart-amount">{{ $cartSubtotal }}৳</span>
                    <i class="fas fa-shopping-basket"></i>
                    <span class="cart-count" id="cart-qty">{{ $cartCount }}</span>
                </a>

                <div class="cart-dropdown" id="miniCart">
                    <div class="cart-dropdown-head">
                        <span class="cart-dropdown-title">আপনার কার্ট</span>
                        <span class="cart-dropdown-count">{{ $cartCount }} টি পণ্য</span>
                    </div>

                    <div class="cart-dropdown-body">
                        @forelse(Cart::instance('shopping')->content() as $item)
                            <div class="mini-cart-item" data-rowid="{{ $item->rowId }}"
                                 data-qty="{{ $item->qty }}" data-line="{{ (float) $item->price * $item->qty }}">
                                <a href="{{ route('product', $item->options->slug ?? '#') }}" class="mini-cart-thumb">
                                    <img src="{{ asset($item->options->image ?? 'public/uploads/default.webp') }}" alt="{{ $item->name }}">
                                </a>
                                <div class="mini-cart-info">
                                    <a href="{{ route('product', $item->options->slug ?? '#') }}" class="mini-cart-name">{{ Str::limit($item->name, 40) }}</a>
                                    <span class="mini-cart-meta">{{ $item->qty }} × ৳{{ (int) $item->price }}</span>
                                </div>
                                <div class="mini-cart-right">
                                    <span class="mini-cart-line">৳{{ (int) ($item->price * $item->qty) }}</span>
                                    <button type="button" class="mini-cart-remove" data-id="{{ $item->rowId }}" aria-label="রিমুভ করুন">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="mini-cart-empty">
                                <i class="fas fa-cart-shopping"></i>
                                <p>আপনার কার্ট খালি</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="cart-dropdown-foot" @if($cartCount == 0) style="display:none" @endif>
                        <div class="mini-cart-total">
                            <span>সর্বমোট</span>
                            <span class="mini-cart-total-amt" data-total="{{ $cartSubtotal }}">৳{{ $cartSubtotal }}</span>
                        </div>
                        <div class="mini-cart-actions">
                            <a href="{{ route('cart.show') }}" class="mini-cart-view">কার্ট দেখুন</a>
                            <a href="{{ route('customer.checkout') }}" class="mini-cart-checkout">অর্ডার করুন</a>
                        </div>
                    </div>
                </div>
            </div>

            <a href="{{ $isCustomerLoggedIn ? route('customer.account') : route('customer.login') }}" class="profile-btn" aria-label="অ্যাকাউন্ট">
                <i class="far fa-user"></i>
            </a>
            <a href="{{ route('customer.order_track') }}" class="btn-track">Track Order</a>
        </div>
    </div>
</header>

<!-- Mobile Category Chips -->
<nav class="mobile-category-scroll" aria-label="ক্যাটাগরি">
    @foreach($menucategories as $category)
        <a href="{{ route('category', $category->slug) }}"
           class="category-chip {{ request()->is('category/'.$category->slug) ? 'active' : '' }}">
            {{ $category->name }}
        </a>
    @endforeach
</nav>

<div class="nav-overlay" id="navOverlay" aria-hidden="true"></div>

 @yield('content')

<!-- Features Bar -->
@if(!empty($homepage_features) && count($homepage_features) > 0)
<section class="features-bar">
    <div class="container features-grid">
        @foreach($homepage_features as $feature)
        <div class="feature-item">
            <i class="{{ $feature->icon }}"></i>
            <div>
                <h4>{{ $feature->title }}</h4>
                <p>{{ $feature->description }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif



<!-- Footer -->
<footer class="site-footer">
    <div class="container footer-grid">
        <div class="footer-about">
            <img src="{{ asset(optional($generalsetting)->white_logo ?? 'public/logo.png') }}" alt="লিচু" class="footer-logo">
            <p>{{ optional($generalsetting)->footer_about_text ?? 'Note Found' }}</p>
            <div class="social-links">
			
			
			 @foreach($socialicons as $value)
                <a href="{{ $value->link }}" aria-label="{{ $value->title }}"><i class="{{ $value->icon }}"></i></a>
				@endforeach
				
            </div>
        </div>
        <div>
            <h4>প্রয়োজনীয় লিঙ্ক</h4>
            <ul>
                <li><a href="{{route('contact')}}">যোগাযোগ</a></li>
				@foreach($pages as $page)
                <li><a href="{{ route('page', ['slug' => $page->slug]) }}">{{ $page->name }}</a></li>
				@endforeach
				
            </ul>
        </div>
        <div>
            <h4>আমাদের গ্রাহক সেবা</h4>
            <ul>
                <li><a href="{{route('customer.login')}}">আমার অ্যাকাউন্ট</a></li>
                <li><a href="{{route('customer.order_track')}}">অর্ডার ট্র্যাক করুন</a></li>
                <li><a href="{{ route('complaint') }}">কমপ্লেইন করুন</a></li>
                <li><a href="/blogs">আমাদের ব্লগ</a></li>
            </ul>
        </div>
        <div class="footer-newsletter">
            <h4>নিউজলেটার</h4>
            <p>নতুন পণ্য, অফার ও আপডেট সবার আগে পেতে সাবস্ক্রাইব করুন।</p>
            <form action="{{ route('frontend.newsletter.subscribe') }}" method="POST" class="newsletter-form">
                @csrf
                <input type="email" name="email" placeholder="আপনার ইমেইল দিন" required aria-label="ইমেইল">
                <button type="submit"><i class="fas fa-paper-plane"></i> সাবস্ক্রাইব</button>
            </form>
            @if(session('newsletter_success'))
                <div class="newsletter-note is-success"><i class="fas fa-check-circle"></i> {{ session('newsletter_success') }}</div>
            @elseif(session('newsletter_error'))
                <div class="newsletter-note is-error"><i class="fas fa-exclamation-circle"></i> {{ session('newsletter_error') }}</div>
            @elseif($errors->has('email'))
                <div class="newsletter-note is-error"><i class="fas fa-exclamation-circle"></i> সঠিক ইমেইল ঠিকানা দিন।</div>
            @endif
			<div class="app-download-links" style="margin-top: 25px;">
    <h4 style="margin-bottom: 12px; font-size: 16px;">আমাদের অ্যাপ ডাউনলোড করুন</h4>
    <div style="display: flex; gap: 12px; flex-wrap: wrap;">
        <a href="{{ optional($generalsetting)->google_play_link ?? '#' }}" target="_blank" rel="noopener" aria-label="Download on Google Play">
            <img src="/public/uploads/play.svg" alt="Google Play Store" style="height: 40px; width: auto; border-radius: 4px;">
        </a>
        <a href="{{ optional($generalsetting)->app_store_link ?? '#' }}" target="_blank" rel="noopener" aria-label="Download on App Store">
            <img src="/public/uploads/app.png" alt="Apple App Store" style="height: 40px; width: auto; border-radius: 4px;">
        </a>
    </div>
</div>
        </div>
		
    </div>
<div class="footer-bottom">
        <div style="display: flex; align-items: center; justify-content: center; flex-wrap: wrap; gap: 5px; line-height: 1;">
            <span>&copy; {{ date('Y') }} সকল কিছুর স্বত্বাধিকারঃ {{ optional($generalsetting)->name ?? config('app.name') }} | সকল কারিগরি সহযোগিতায়</span>
            <a href="https://softcents.com" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 5px; text-decoration: none;">
                Softcents Systems
            </a>
        </div>
    </div>
</footer>

<script defer src="{{ asset('public/frontEnd/js/main.js') }}?v={{ $assetVersions['main_js'] ?? time() }}"></script>
@if(request()->is('/'))
<script defer src="{{ asset('public/frontEnd/js/hero-slider.js') }}?v={{ $assetVersions['hero_js'] ?? time() }}"></script>
@endif
@stack('script')
@include('frontEnd.layouts.partials.deferred-tracking')

{{-- ══════════════════════════════════════════════════════
     GLOBAL VARIANT ORDER POPUP — v2
     Triggers on .vom-btn click (both order + cart buttons)
══════════════════════════════════════════════════════ --}}
<style>
#vomOverlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:9998;backdrop-filter:blur(4px);}
#vomModal{
    display:none;position:fixed;left:50%;top:50%;
    transform:translate(-50%,-50%) scale(.88);
    z-index:9999;width:94%;max-width:460px;
    background:#fff;border-radius:20px;overflow:hidden;
    box-shadow:0 30px 80px rgba(0,0,0,.25);
    transition:transform .22s cubic-bezier(.34,1.56,.64,1), opacity .22s ease;
    opacity:0;
}
#vomModal.vom-open{transform:translate(-50%,-50%) scale(1);opacity:1;}

/* Header */
.vom-head{display:flex;align-items:center;justify-content:space-between;padding:14px 18px;border-bottom:1px solid #f0f0f0;}
.vom-head h5{margin:0;font-size:16px;font-weight:800;color:#1a1a1a;}
.vom-close{width:30px;height:30px;border:none;background:var(--primary);color:#fff;border-radius:8px;cursor:pointer;font-size:18px;line-height:1;display:flex;align-items:center;justify-content:center;font-weight:700;}

/* Product row */
.vom-pro{display:flex;align-items:center;gap:14px;padding:14px 18px;border-bottom:1px solid #f7f7f7;}
.vom-pro img{width:70px;height:70px;border-radius:12px;object-fit:cover;border:1px solid #eee;flex-shrink:0;}
.vom-pro-name{font-size:14px;font-weight:700;color:#1a1a1a;margin:0 0 4px;line-height:1.4;}
.vom-pro-price{display:flex;align-items:center;gap:8px;}
.vom-pro-price .new{font-size:18px;font-weight:800;color:var(--primary);}
.vom-pro-price .old{font-size:13px;color:#ccc;text-decoration:line-through;}

/* Options */
.vom-body{padding:14px 18px;min-height:60px;max-height:260px;overflow-y:auto;}
.vom-sec-label{font-size:11px;font-weight:800;color:#888;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;}
.vom-sec-label a{font-size:11px;font-weight:600;color:var(--primary);text-decoration:none;text-transform:none;letter-spacing:0;}
.vom-opts{display:flex;flex-wrap:wrap;gap:7px;margin-bottom:16px;}
.vom-size-btn{
    padding:7px 16px;border:2px solid #e0e0e0;border-radius:8px;
    background:#fff;font-size:13px;font-weight:600;color:#444;cursor:pointer;transition:.15s;
}
.vom-size-btn:hover{border-color:var(--primary);color:var(--primary);}
.vom-size-btn.sel{background:var(--primary);border-color:var(--primary);color:#fff;box-shadow:0 3px 8px rgba(0,0,0,.14);}
.vom-color-btn{
    width:34px;height:34px;border-radius:50%;border:3px solid #ddd;
    cursor:pointer;transition:.15s;position:relative;
    display:inline-flex;align-items:center;justify-content:center;
}
.vom-color-btn:hover{border-color:#999;transform:scale(1.1);}
.vom-color-btn.sel{border-color:var(--primary);box-shadow:0 0 0 2px var(--primary);}
.vom-color-btn.sel::after{content:'✓';position:absolute;font-size:13px;font-weight:900;color:#fff;text-shadow:0 1px 3px rgba(0,0,0,.6);}

/* Footer buttons */
.vom-foot{padding:12px 18px 18px;border-top:1px solid #f7f7f7;display:flex;gap:10px;}
.vom-btn-cart{
    flex:1;padding:13px;border:2px solid var(--primary);border-radius:12px;
    background:#fff;color:var(--primary);font-size:14px;font-weight:700;
    cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:6px;
}
.vom-btn-cart:hover{background:#fff8f8;}
.vom-btn-order{
    flex:1;padding:13px;border:none;border-radius:12px;
    background:var(--primary);color:#fff;font-size:14px;font-weight:800;
    cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:6px;
    box-shadow:0 4px 14px rgba(0,0,0,.16);
}
.vom-btn-order:hover{opacity:.9;transform:translateY(-1px);}
.vom-spinner{text-align:center;padding:24px 0;color:#ccc;}
.vom-error{text-align:center;padding:20px;color:#e53935;font-size:13px;}
#vomPrice.vom-flash{animation:vomPriceFlash .4s ease;}
@keyframes vomPriceFlash{0%,100%{transform:scale(1)}50%{transform:scale(1.12);color:var(--primary)}}
.vom-size-btn .vom-opt-price{font-size:11px;opacity:.85;margin-left:4px;}
</style>

<div id="vomOverlay" onclick="vomClose()"></div>
<div id="vomModal">
    <div class="vom-head">
        <h5>পণ্য অপশন নির্বাচন করুন</h5>
        <button class="vom-close" onclick="vomClose()">✕</button>
    </div>
    <div class="vom-pro">
        <img id="vomImg" src="" alt="">
        <div>
            <p id="vomName" class="vom-pro-name"></p>
            <div class="vom-pro-price">
                <span id="vomPrice" class="new"></span>
                <span id="vomOldP" class="old"></span>
            </div>
        </div>
    </div>
    <div id="vomBody" class="vom-body">
        <div class="vom-spinner"><i class="fas fa-spinner fa-spin" style="font-size:20px;"></i></div>
    </div>
    <form id="vomForm" action="{{ route('cart.store') }}" method="POST">
        @csrf
        <input type="hidden" name="id"            id="vomPid">
        <input type="hidden" name="qty"           value="1">
        <input type="hidden" name="order_now"     id="vomOrderNow" value="1">
        <input type="hidden" name="product_size"  id="vomSizeId">
        <input type="hidden" name="product_color" id="vomColorId">
        <input type="hidden" name="size_name"     id="vomSizeName">
        <input type="hidden" name="color_name"    id="vomColorName">
        <div class="vom-foot">
            <button type="button" class="vom-btn-cart" onclick="vomSubmit(0)">
                <i class="fas fa-shopping-basket"></i> কার্টে যোগ করুন
            </button>
            <button type="button" class="vom-btn-order" onclick="vomSubmit(1)">
                <i class="fas fa-bolt"></i> অর্ডার করুন
            </button>
        </div>
    </form>
</div>

<script>
window.VomPopup = (function(){
    var _cache = {};
    var _ajaxUrl = @json(url('/ajax/product-options'));
    var _csrf = document.querySelector('meta[name="csrf-token"]')
        ? document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        : (document.querySelector('#vomForm input[name="_token"]') || {value:''}).value;

    var S = {
        sizes: [], colors: [], variants: [],
        basePrice: 0, baseOldPrice: 0
    };

    function $(id){ return document.getElementById(id); }

    function esc(s){
        return String(s).replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
    }

    function fmtPrice(n){ return '৳' + Math.round(parseFloat(n) || 0); }

    function vColorId(v){
        if (v.color_id != null && v.color_id !== '') return String(v.color_id);
        if (v.color && v.color.id != null) return String(v.color.id);
        return null;
    }
    function vSizeId(v){
        if (v.size_id != null && v.size_id !== '') return String(v.size_id);
        if (v.size && v.size.id != null) return String(v.size.id);
        return null;
    }

    function selColorId(){
        var v = $('vomColorId').value;
        return v ? String(v) : null;
    }
    function selSizeId(){
        var v = $('vomSizeId').value;
        return v ? String(v) : null;
    }

    function matchVariant(color, size){
        var match = null;
        if (color && size) {
            match = S.variants.find(function(v){ return vColorId(v) === color && vSizeId(v) === size; });
        }
        if (!match && color) {
            match = S.variants.find(function(v){ return vColorId(v) === color && (!size || vSizeId(v) === size); });
        }
        if (!match && size) {
            match = S.variants.find(function(v){ return vSizeId(v) === size && (!color || vColorId(v) === color); });
        }
        if (!match && color) {
            match = S.variants.find(function(v){ return vColorId(v) === color; });
        }
        if (!match && size) {
            match = S.variants.find(function(v){ return vSizeId(v) === size; });
        }
        return match;
    }

    function resolvePrice(color, size){
        var match = matchVariant(color, size);
        if (match && match.price != null && parseFloat(match.price) > 0) {
            return Math.round(parseFloat(match.price));
        }
        return S.basePrice;
    }

    function updatePriceDisplay(price){
        var el = $('vomPrice');
        var oldEl = $('vomOldP');
        if (!el) return;
        el.textContent = fmtPrice(price);
        el.classList.remove('vom-flash');
        void el.offsetWidth;
        el.classList.add('vom-flash');
        var showOld = S.baseOldPrice;
        if (showOld > 0 && showOld > price) {
            oldEl.textContent = fmtPrice(showOld);
        } else {
            oldEl.textContent = '';
        }
    }

    function refreshPrice(){
        updatePriceDisplay(resolvePrice(selColorId(), selSizeId()));
    }

    function selectSize(btn){
        document.querySelectorAll('#vomModal .vom-size-btn').forEach(function(x){ x.classList.remove('sel'); });
        btn.classList.add('sel');
        $('vomSizeName').value = btn.getAttribute('data-sname') || '';
        $('vomSizeId').value   = btn.getAttribute('data-sid') || '';
        refreshPrice();
    }

    function selectColor(btn){
        document.querySelectorAll('#vomModal .vom-color-btn').forEach(function(x){ x.classList.remove('sel'); });
        btn.classList.add('sel');
        $('vomColorName').value = btn.getAttribute('data-cname') || '';
        $('vomColorId').value   = btn.getAttribute('data-cid') || '';
        renderOptions();
        refreshPrice();
    }

    function clearSize(e){
        if (e) e.preventDefault();
        document.querySelectorAll('#vomModal .vom-size-btn').forEach(function(x){ x.classList.remove('sel'); });
        $('vomSizeName').value = '';
        $('vomSizeId').value   = '';
        refreshPrice();
    }

    function clearColor(e){
        if (e) e.preventDefault();
        document.querySelectorAll('#vomModal .vom-color-btn').forEach(function(x){ x.classList.remove('sel'); });
        $('vomColorName').value = '';
        $('vomColorId').value   = '';
        renderOptions();
        refreshPrice();
    }

    function renderOptions(){
        var h = '';
        var effColor = selColorId();

        if (S.sizes.length) {
            h += '<div class="vom-sec-label">সাইজ / পরিমাণ <a href="#" data-vom-clear="size">Clear</a></div><div class="vom-opts">';
            S.sizes.forEach(function(s){
                var sid = String(s.id);
                var p   = resolvePrice(effColor, sid);
                var extra = (S.variants.length && p !== S.basePrice) ? '<span class="vom-opt-price">'+fmtPrice(p)+'</span>' : '';
                var sel = ($('vomSizeId').value === sid) ? ' sel' : '';
                h += '<button type="button" class="vom-size-btn'+sel+'" data-sid="'+sid+'" data-sname="'+esc(s.name)+'">'+esc(s.name)+extra+'</button>';
            });
            h += '</div>';
        }

        if (S.colors.length) {
            h += '<div class="vom-sec-label" style="margin-top:12px;">রঙ <a href="#" data-vom-clear="color">Clear</a></div><div class="vom-opts">';
            S.colors.forEach(function(c){
                var cid = String(c.id);
                var bg  = c.code || '#ccc';
                var sel = ($('vomColorId').value === cid) ? ' sel' : '';
                h += '<button type="button" class="vom-color-btn'+sel+'" data-cid="'+cid+'" data-cname="'+esc(c.name)+'" style="background:'+bg+';" title="'+esc(c.name)+'"></button>';
            });
            h += '</div>';
        }

        $('vomBody').innerHTML = h || '<p style="text-align:center;color:#aaa;padding:16px;font-size:13px;">কোনো অপশন নেই।</p>';
    }

    function openModal(d){
        $('vomPid').value = d.id;
        $('vomSizeName').value = '';
        $('vomColorName').value = '';
        $('vomSizeId').value = '';
        $('vomColorId').value = '';

        S.sizes    = d.sizes    || [];
        S.colors   = d.colors   || [];
        S.variants = d.variants || [];
        S.basePrice    = parseInt(d.price, 10)     || 0;
        S.baseOldPrice = parseInt(d.old_price, 10) || 0;

        $('vomName').textContent = d.name || '';
        $('vomImg').src = d.image || '';
        $('vomImg').alt = d.name || '';

        renderOptions();
        refreshPrice();

        $('vomOverlay').style.display = 'block';
        var m = $('vomModal');
        m.style.display = 'block';
        document.body.style.overflow = 'hidden';
        requestAnimationFrame(function(){
            requestAnimationFrame(function(){ m.classList.add('vom-open'); });
        });
    }

    function closeModal(){
        var m = $('vomModal');
        m.classList.remove('vom-open');
        setTimeout(function(){
            m.style.display = 'none';
            $('vomOverlay').style.display = 'none';
            document.body.style.overflow = '';
        }, 240);
    }

    function directSubmit(id, mode){
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = $('vomForm').action;
        form.style.display = 'none';
        var fields = {_token: _csrf, id: id, qty: '1'};
        if (mode === 'order') fields.order_now = '1';
        Object.keys(fields).forEach(function(k){
            var i = document.createElement('input');
            i.type = 'hidden'; i.name = k; i.value = fields[k];
            form.appendChild(i);
        });
        document.body.appendChild(form);
        form.submit();
    }

    function handleData(d, id, mode){
        if (d.has_variants || (d.sizes && d.sizes.length) || (d.colors && d.colors.length)) {
            openModal(d);
        } else {
            directSubmit(id, mode);
        }
    }

    function loadProduct(btn){
        var id   = btn.getAttribute('data-id');
        var mode = btn.getAttribute('data-mode') || 'order';
        if (!id) return;

        var orig = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        if (_cache[id]) {
            btn.disabled = false;
            btn.innerHTML = orig;
            handleData(_cache[id], id, mode);
            return;
        }

        fetch(_ajaxUrl + '/' + id, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(r){ return r.ok ? r.json() : Promise.reject(r.status); })
        .then(function(d){
            btn.disabled = false;
            btn.innerHTML = orig;
            _cache[id] = d;
            handleData(d, id, mode);
        })
        .catch(function(){
            btn.disabled = false;
            btn.innerHTML = orig;
            directSubmit(id, mode);
        });
    }

    /* Product card buttons */
    document.addEventListener('click', function(e){
        var btn = e.target.closest('.vom-btn');
        if (!btn || btn.closest('#vomModal')) return;
        e.preventDefault();
        e.stopPropagation();
        loadProduct(btn);
    });

    /* Modal option clicks — event delegation (no inline onclick) */
    $('vomBody').addEventListener('click', function(e){
        var clear = e.target.closest('[data-vom-clear]');
        if (clear) {
            e.preventDefault();
            if (clear.getAttribute('data-vom-clear') === 'size') clearSize(e);
            if (clear.getAttribute('data-vom-clear') === 'color') clearColor(e);
            return;
        }
        var sizeBtn = e.target.closest('.vom-size-btn');
        if (sizeBtn) { e.preventDefault(); selectSize(sizeBtn); return; }
        var colorBtn = e.target.closest('.vom-color-btn');
        if (colorBtn) { e.preventDefault(); selectColor(colorBtn); return; }
    });

    document.addEventListener('keydown', function(e){
        if (e.key === 'Escape') closeModal();
    });

    return {
        submit: function(isOrder){
            $('vomOrderNow').value = isOrder ? '1' : '0';
            $('vomForm').submit();
        },
        close: closeModal
    };
})();

function vomSubmit(isOrder){ window.VomPopup.submit(isOrder); }
function vomClose(){ window.VomPopup.close(); }
</script>

<!-- Mobile Bottom Nav -->
<nav class="mobile-bottom-nav" aria-label="মোবাইল মেনু">

    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-house"></i>
        <span>হোম</span>
    </a>

    <a href="{{ route('shop') }}" class="{{ request()->routeIs('shop', 'all.products', 'category', 'subcategory', 'brand', 'offers') ? 'active' : '' }}">
        <i class="fas fa-store"></i>
        <span>শপ</span>
    </a>

    <a href="{{ route('customer.checkout') }}" class="nav-cart {{ request()->routeIs('customer.checkout') ? 'active' : '' }}">
        <i class="fas fa-cart-shopping"></i>
        <span>কার্ট</span>
        @if($cartCount > 0)
        <em class="nav-badge">{{ $cartCount > 99 ? '99+' : $cartCount }}</em>
        @endif
    </a>

    @if($isCustomerLoggedIn)
    <a href="{{ route('customer.account') }}" class="{{ request()->routeIs('customer.account', 'customer.orders', 'customer.invoice') ? 'active' : '' }}">
        <i class="far fa-user-circle"></i>
        <span>অ্যাকাউন্ট</span>
    </a>
    @else
    <a href="{{ route('customer.login') }}" class="{{ request()->routeIs('customer.login', 'customer.register') ? 'active' : '' }}">
        <i class="far fa-user"></i>
        <span>লগইন</span>
    </a>
    @endif

</nav>

{{-- ========== Promo / Newsletter Image Popup (Admin → Popup Management) ========== --}}
@include('frontEnd.layouts.partials.promo_popup')

{{-- ========== Duplicate Order Alert ========== --}}
@include('frontEnd.layouts.partials.duplicate_order_modal')

{{-- ========== Sales Notification Popup (bottom-left "just bought") ========== --}}
@include('frontEnd.layouts.partials.sales_notification_popup')

{{-- ========== Gemini AI Customer Chat ========== --}}
@include('frontEnd.layouts.partials.gemini_customer_chat')

</body>
</html>