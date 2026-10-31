@extends('frontEnd.layouts.master')
@section('title','শপিং কার্ট')

@push('css')
<style>
/* ===== FULL CART PAGE — matches site design system ===== */
.cart-page {
    padding: 28px 0 56px;
    background: #f7f7f7;
    min-height: 60vh;
}

/* Breadcrumb */
.cart-page-breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #999;
    margin-bottom: 20px;
}
.cart-page-breadcrumb a {
    color: #666;
    transition: color .2s;
}
.cart-page-breadcrumb a:hover { color: var(--primary); }
.cart-page-breadcrumb i { font-size: 9px; color: #ccc; }
.cart-page-breadcrumb strong { color: #333; }

/* Two-column layout */
.cart-page-grid {
    display: grid;
    grid-template-columns: 1fr 300px;
    gap: 20px;
    align-items: start;
}
@media (max-width: 860px) {
    .cart-page-grid { grid-template-columns: 1fr; }
}

/* ===== MOBILE FIXES ===== */
@media (max-width: 600px) {
    .cart-page { padding: 16px 0 40px; overflow-x: hidden; }

    /* Item row: image + info উপরে, qty + price নিচে */
    .cart-page-item {
        flex-wrap: wrap;
        gap: 10px;
        padding: 12px 14px;
        position: relative;
    }
    .cart-page-thumb {
        width: 60px;
        height: 60px;
    }
    .cart-page-info {
        flex: 1;
        min-width: 0;
        padding-right: 24px; /* remove button-এর জায়গা */
    }
    .cart-page-qty {
        flex-basis: auto;
        margin-left: 70px; /* image width + gap এর সমান */
    }
    .cart-page-item-right {
        flex-direction: row;
        align-items: center;
        gap: 10px;
        flex-basis: auto;
        margin-left: auto;
    }
    /* Remove button absolute — top-right কর্নারে */
    .cart-page-remove {
        position: absolute;
        top: 12px;
        right: 12px;
    }

    /* Coupon row */
    .cart-page-coupon-row { gap: 6px; }
    .cart-page-coupon-input { font-size: 13px; padding: 9px 10px; }
    .cart-page-coupon-btn { padding: 9px 14px; font-size: 13px; }

    /* Summary card head */
    .cart-page-card-head h4 { font-size: 14px; }
    .cart-page-summary-row { font-size: 13px; padding: 10px 14px; }
    .cart-page-summary-row.is-grand { font-size: 14px; }
    .cart-page-summary-row.is-grand span:last-child { font-size: 16px; }
    .cart-page-summary-actions { padding: 12px 14px 14px; }
}

/* Card */
.cart-page-card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #f0f0f0;
    overflow: hidden;
}
.cart-page-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 18px;
    border-bottom: 1px solid #f0f0f0;
}
.cart-page-card-head h4 {
    font-size: 15px;
    font-weight: 700;
    color: #222;
    margin: 0;
}
.cart-page-badge {
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
    background: #fdf0f2;
    padding: 3px 10px;
    border-radius: 20px;
}

/* Cart items list (same as mini-cart pattern) */
.cart-page-item {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 18px;
    border-bottom: 1px solid #f5f5f5;
    transition: background .15s;
}
.cart-page-item:last-child { border-bottom: none; }
.cart-page-item:hover { background: #fafafa; }

.cart-page-thumb {
    width: 72px;
    height: 72px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    border: 1px solid #f0f0f0;
    background: #fff;
}
.cart-page-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

.cart-page-info {
    flex: 1;
    min-width: 0;
}
.cart-page-name {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    line-height: 1.35;
    margin-bottom: 4px;
    text-decoration: none;
    transition: color .15s;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.cart-page-name:hover { color: var(--primary); }
.cart-page-variants {
    display: flex;
    flex-wrap: wrap;
    gap: 4px;
    margin-bottom: 6px;
}
.cart-page-variant-tag {
    font-size: 11px;
    color: #777;
    background: #f5f5f5;
    border-radius: 4px;
    padding: 2px 8px;
}
.cart-page-unit-price {
    font-size: 13px;
    color: #888;
}
.cart-page-unit-price strong {
    color: var(--primary);
    font-weight: 700;
}

/* Qty controls */
.cart-page-qty {
    display: inline-flex;
    align-items: center;
    border: 1px solid var(--border);
    border-radius: 5px;
    overflow: hidden;
    flex-shrink: 0;
}
.cart-page-qty button {
    width: 34px;
    height: 34px;
    border: none;
    background: #f5f5f5;
    font-size: 16px;
    cursor: pointer;
    color: #444;
    transition: background .15s;
    display: flex;
    align-items: center;
    justify-content: center;
}
.cart-page-qty button:hover { background: #e8e8e8; }
.cart-page-qty input {
    width: 42px;
    height: 34px;
    border: none;
    border-left: 1px solid var(--border);
    border-right: 1px solid var(--border);
    text-align: center;
    font-size: 14px;
    font-weight: 600;
    color: #222;
    background: #fff;
    outline: none;
}

/* Right side of each item */
.cart-page-item-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 10px;
    flex-shrink: 0;
}
.cart-page-line-total {
    font-size: 15px;
    font-weight: 800;
    color: var(--primary);
    white-space: nowrap;
}
.cart-page-remove {
    border: none;
    background: transparent;
    color: #ccc;
    font-size: 17px;
    cursor: pointer;
    line-height: 1;
    transition: color .15s;
    padding: 0;
}
.cart-page-remove:hover { color: #e53935; }

/* Empty state */
.cart-page-empty {
    text-align: center;
    padding: 52px 24px;
}
.cart-page-empty i {
    font-size: 48px;
    color: #e0e0e0;
    margin-bottom: 14px;
    display: block;
}
.cart-page-empty p {
    font-size: 15px;
    color: #aaa;
    margin: 0 0 18px;
}
.cart-page-empty a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: var(--primary);
    color: #fff;
    border-radius: 6px;
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    transition: background .2s;
}
.cart-page-empty a:hover { background: var(--primary-dark); }

/* Coupon section */
.cart-page-coupon {
    padding: 14px 18px;
    border-top: 1px solid #f0f0f0;
    background: #fcfcfc;
}
.cart-page-coupon-row {
    display: flex;
    gap: 8px;
}
.cart-page-coupon-input {
    flex: 1;
    border: 1px solid var(--border);
    border-radius: 6px;
    padding: 9px 13px;
    font-size: 13px;
    color: #333;
    font-family: inherit;
    outline: none;
    transition: border-color .2s;
}
.cart-page-coupon-input:focus { border-color: var(--primary); }
.cart-page-coupon-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 6px;
    padding: 9px 18px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
    font-family: inherit;
    transition: background .2s;
}
.cart-page-coupon-btn:hover { background: var(--primary-dark); }
.cart-page-coupon-applied {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 9px 13px;
    font-size: 13px;
    color: #166534;
}
.cart-page-coupon-applied button {
    background: none;
    border: none;
    color: #dc2626;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    font-family: inherit;
    white-space: nowrap;
}

/* ===== ORDER SUMMARY (right) ===== */
.cart-page-summary {}

.cart-page-summary-rows {
    padding: 4px 0;
}
.cart-page-summary-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 11px 18px;
    border-bottom: 1px solid #f5f5f5;
    font-size: 14px;
    color: #555;
}
.cart-page-summary-row:last-child { border-bottom: none; }
.cart-page-summary-row.is-discount { color: #16a34a; }
.cart-page-summary-row.is-grand {
    font-size: 15px;
    font-weight: 800;
    color: #222;
    border-top: 1.5px solid #ebebeb;
    border-bottom: none;
    padding-top: 14px;
    padding-bottom: 14px;
    margin-top: 2px;
}
.cart-page-summary-row.is-grand span:last-child { color: var(--primary); font-size: 17px; }
.cart-page-shipping-note {
    font-size: 11px;
    color: #aaa;
    display: block;
    margin-top: 2px;
}

.cart-page-summary-actions {
    padding: 14px 18px 18px;
    border-top: 1px solid #f0f0f0;
}
.cart-page-checkout-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    width: 100%;
    padding: 13px;
    background: var(--primary);
    color: #fff;
    border-radius: 8px;
    font-size: 15px;
    font-weight: 700;
    text-align: center;
    transition: background .2s;
    font-family: inherit;
    border: none;
    cursor: pointer;
    text-decoration: none;
}
.cart-page-checkout-btn:hover { background: var(--primary-dark); color: #fff; }
.cart-page-checkout-btn:disabled,
.cart-page-checkout-btn.disabled {
    opacity: .5;
    pointer-events: none;
}
.cart-page-view-shop {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    margin-top: 10px;
    font-size: 13px;
    color: #888;
    text-decoration: none;
    transition: color .2s;
}
.cart-page-view-shop:hover { color: var(--primary); }

.cart-page-trust {
    display: flex;
    justify-content: center;
    gap: 20px;
    padding: 12px 18px;
    border-top: 1px solid #f0f0f0;
}
.cart-page-trust-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 3px;
    font-size: 11px;
    color: #aaa;
}
.cart-page-trust-item i { font-size: 15px; color: #4ade80; }
</style>
@endpush

@section('content')
@php
    $subtotal   = (float) str_replace([',','.00'], ['',''], Cart::instance('shopping')->subtotal());
    $shipping   = (float) (Session::get('shipping') ?: 0);
    $discount   = (float) (Session::get('discount') ?: 0);
    $grandTotal = ($subtotal + $shipping) - $discount;
    $cartCount  = (int) Cart::instance('shopping')->count();
    view()->share('subtotal', $subtotal);
@endphp

<div class="cart-page">
<div class="container">

    {{-- Breadcrumb --}}
    <div class="cart-page-breadcrumb">
        <a href="{{ route('home') }}"><i class="fas fa-home"></i> হোম</a>
        <i class="fas fa-angle-right"></i>
        <strong>শপিং কার্ট</strong>
    </div>

    <div class="cart-page-grid">

        {{-- ===== LEFT: Items ===== --}}
        <div>
            <div class="cart-page-card">
                <div class="cart-page-card-head">
                    <h4>শপিং কার্ট</h4>
                    <span class="cart-page-badge">{{ $cartCount }} টি পণ্য</span>
                </div>

                @if($data->isEmpty())
                <div class="cart-page-empty">
                    <i class="fas fa-cart-shopping"></i>
                    <p>আপনার কার্ট এখন খালি।</p>
                    <a href="{{ route('shop') }}"><i class="fas fa-arrow-left"></i> শপিং চালিয়ে যান</a>
                </div>
                @else

                @foreach($data as $value)
                <div class="cart-page-item"
                     data-row-id="{{ $value->rowId }}"
                     data-product-id="{{ $value->id }}"
                     data-product-name="{{ e($value->name) }}"
                     data-price="{{ (float) $value->price }}">

                    <a href="{{ route('product', $value->options->slug ?? '#') }}" class="cart-page-thumb">
                        <img src="{{ asset($value->options->image ?? 'public/uploads/default.webp') }}" alt="{{ $value->name }}">
                    </a>

                    <div class="cart-page-info">
                        <a href="{{ route('product', $value->options->slug ?? '#') }}" class="cart-page-name" title="{{ $value->name }}">
                            {{ Str::limit($value->name, 50) }}
                        </a>
                        @if(($value->options->product_size ?? null) || ($value->options->product_color ?? null))
                        <div class="cart-page-variants">
                            @if($value->options->product_size ?? null)
                                <span class="cart-page-variant-tag">{{ $value->options->product_size }}</span>
                            @endif
                            @if($value->options->product_color ?? null)
                                <span class="cart-page-variant-tag">{{ $value->options->product_color }}</span>
                            @endif
                        </div>
                        @endif
                        <span class="cart-page-unit-price">
                            একক মূল্য: <strong>৳{{ number_format($value->price, 0) }}</strong>
                        </span>
                    </div>

                    <div class="cart-page-qty">
                        <button type="button" class="cart_decrement" data-id="{{ $value->rowId }}">−</button>
                        <input type="text" value="{{ $value->qty }}" readonly>
                        <button type="button" class="cart_increment" data-id="{{ $value->rowId }}">+</button>
                    </div>

                    <div class="cart-page-item-right">
                        <span class="cart-page-line-total">৳{{ number_format($value->price * $value->qty, 0) }}</span>
                        <button type="button" class="cart-page-remove cart_remove" data-id="{{ $value->rowId }}" title="সরান">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    </div>
                </div>
                @endforeach

                {{-- Coupon --}}
                <div class="cart-page-coupon">
                    @if(Session::has('coupon_code'))
                    <div class="cart-page-coupon-applied">
                        <span><i class="fas fa-tag" style="margin-right:5px;"></i> <strong>{{ Session::get('coupon_code') }}</strong> — ৳{{ number_format($discount,0) }} ছাড় প্রয়োগ হয়েছে</span>
                        <form action="{{ route('coupon.remove') }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit">সরান ✕</button>
                        </form>
                    </div>
                    @else
                    <div class="cart-page-coupon-row">
                        <input type="text" id="cartCoupon" class="cart-page-coupon-input" placeholder="কুপন কোড লিখুন...">
                        <button type="button" id="applyCouponBtn" class="cart-page-coupon-btn" onclick="cartApplyCoupon()">প্রয়োগ করুন</button>
                    </div>
                    @endif
                </div>

                @endif
            </div>
        </div>

        {{-- ===== RIGHT: Summary ===== --}}
        <div class="cart-page-summary">
            <div class="cart-page-card">
                <div class="cart-page-card-head">
                    <h4>অর্ডার সারসংক্ষেপ</h4>
                </div>

                <div class="cart-page-summary-rows">
                    <div class="cart-page-summary-row">
                        <span>সাবটোটাল ({{ $cartCount }} পণ্য)</span>
                        <span>৳{{ number_format($subtotal, 0) }}</span>
                    </div>
                    <div class="cart-page-summary-row">
                        <span>
                            ডেলিভারি চার্জ
                            @if($shipping == 0)<small class="cart-page-shipping-note">চেকআউটে নির্ধারিত হবে</small>@endif
                        </span>
                        <span>{{ $shipping > 0 ? '৳'.number_format($shipping,0) : '—' }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="cart-page-summary-row is-discount">
                        <span><i class="fas fa-tag" style="margin-right:4px;font-size:11px;"></i> ছাড়</span>
                        <span>− ৳{{ number_format($discount, 0) }}</span>
                    </div>
                    @endif
                    <div class="cart-page-summary-row is-grand">
                        <span>সর্বমোট</span>
                        <span>৳{{ number_format($grandTotal, 0) }}</span>
                    </div>
                </div>

                <div class="cart-page-summary-actions">
                    @if(!$data->isEmpty())
                    <a href="{{ route('customer.checkout') }}" class="cart-page-checkout-btn" id="checkoutButton">
                        <i class="fas fa-lock" style="font-size:13px;"></i> চেকআউট করুন
                    </a>
                    @else
                    <span class="cart-page-checkout-btn disabled">
                        <i class="fas fa-lock" style="font-size:13px;"></i> চেকআউট করুন
                    </span>
                    @endif
                    <a href="{{ route('shop') }}" class="cart-page-view-shop">
                        <i class="fas fa-arrow-left"></i> শপিং চালিয়ে যান
                    </a>
                </div>

                <div class="cart-page-trust">
                    <div class="cart-page-trust-item"><i class="fas fa-shield-alt"></i> <span>Secure</span></div>
                    <div class="cart-page-trust-item"><i class="fas fa-undo"></i> <span>Easy Return</span></div>
                    <div class="cart-page-trust-item"><i class="fas fa-truck"></i> <span>Fast Delivery</span></div>
                </div>
            </div>
        </div>

    </div>
</div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// ===== Cart Actions (jQuery loaded above) =====

function cartGet(url, params, onDone) {
    fetch(url + '?' + new URLSearchParams(params), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(function() { onDone(); })
        .catch(function() { onDone(); });
}

document.addEventListener('click', function(e) {
    // Remove
    var removeBtn = e.target.closest('.cart_remove');
    if (removeBtn) {
        var id   = removeBtn.getAttribute('data-id');
        var item = removeBtn.closest('.cart-page-item');
        if (item) item.style.opacity = '0.4';
        cartGet('{{ route("cart.remove") }}', { id: id }, function() { location.reload(); });
        return;
    }
    // Increment
    var incrBtn = e.target.closest('.cart_increment');
    if (incrBtn) {
        var id  = incrBtn.getAttribute('data-id');
        var qty = incrBtn.closest('.cart-page-qty');
        if (qty) qty.style.opacity = '0.5';
        cartGet('{{ route("cart.increment") }}', { id: id }, function() { location.reload(); });
        return;
    }
    // Decrement
    var decrBtn = e.target.closest('.cart_decrement');
    if (decrBtn) {
        var id  = decrBtn.getAttribute('data-id');
        var qty = decrBtn.closest('.cart-page-qty');
        if (qty) qty.style.opacity = '0.5';
        cartGet('{{ route("cart.decrement") }}', { id: id }, function() { location.reload(); });
        return;
    }
});

function cartApplyCoupon() {
    var code = document.getElementById('cartCoupon').value.trim();
    if (!code) { alert('কুপন কোড লিখুন'); return; }
    var btn  = document.getElementById('applyCouponBtn');
    var csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    btn.textContent = '...'; btn.disabled = true;

    var formData = new FormData();
    formData.append('coupon_code', code);
    formData.append('_token', csrf);

    fetch('{{ route("coupon.apply") }}', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrf },
        body: formData
    })
    .then(function(res) {
        if (res.ok) { location.reload(); return; }
        return res.json().then(function(data) {
            btn.textContent = 'প্রয়োগ করুন'; btn.disabled = false;
            alert(data.message || 'কুপন প্রয়োগ ব্যর্থ হয়েছে।');
        });
    })
    .catch(function() {
        btn.textContent = 'প্রয়োগ করুন'; btn.disabled = false;
        alert('কুপন প্রয়োগ ব্যর্থ হয়েছে।');
    });
}

// ===== Tracking =====
window.dataLayer = window.dataLayer || [];
(function() {
    var currency      = 'BDT';
    var cartValue     = {{ (float)$grandTotal }};
    var cartItemCount = {{ (int)$cartCount }};
    var cartItems     = [
        @foreach($data as $item)
        { item_id:'{{ $item->id }}', item_name:@json($item->name), price:{{ (float)$item->price }}, quantity:{{ (int)$item->qty }} }@if(!$loop->last),@endif
        @endforeach
    ];

    window.pushCartEvent = function(type, item, qtyChange) {
        var qty   = Math.abs(qtyChange) || 1;
        var value = (item.price || 0) * qty;
        window.dataLayer.push({ event: type, ecommerce: { currency: currency, value: value, items: [Object.assign({}, item, { quantity: qty })] } });
        if (typeof fbq === 'function') {
            if (type === 'add_to_cart')
                fbq('track', 'AddToCart', { value: value, currency: currency, content_ids: [item.item_id], contents: [{ id: item.item_id, quantity: qty }] });
            else if (type === 'remove_from_cart')
                fbq('trackCustom', 'RemoveFromCart', { value: value, currency: currency, content_ids: [item.item_id], contents: [{ id: item.item_id, quantity: qty }] });
        }
    };

    if (typeof window.EcomTracking !== 'undefined') {
        EcomTracking.viewCart({ value: cartValue, items: cartItems.map(function(i) { return { id: i.item_id, name: i.item_name, price: i.price, qty: i.quantity }; }) });
    } else {
        window.dataLayer.push({ event: 'view_cart', ecommerce: { currency: currency, value: cartValue, items: cartItems } });
        if (typeof fbq === 'function') fbq('trackCustom', 'ViewCart', { value: cartValue, currency: currency, num_items: cartItemCount });
        if (typeof ttq !== 'undefined') ttq.track('ViewContent', { content_type: 'product_group', value: cartValue, currency: currency, quantity: cartItemCount });
    }

    var checkoutBtn = document.getElementById('checkoutButton');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function() {
            if (typeof window.EcomTracking !== 'undefined') {
                EcomTracking.initiateCheckout({ items: cartItems.map(function(i) { return { id: i.item_id, name: i.item_name, price: i.price, qty: i.quantity }; }), value: cartValue });
            } else {
                if (typeof fbq === 'function')
                    fbq('track', 'InitiateCheckout', { value: cartValue, currency: currency, num_items: cartItemCount, content_ids: cartItems.map(function(i) { return i.item_id; }), contents: cartItems.map(function(i) { return { id: i.item_id, quantity: i.quantity, item_price: i.price }; }) });
                if (typeof ttq !== 'undefined')
                    ttq.track('InitiateCheckout', { value: cartValue, currency: currency, quantity: cartItemCount, content_type: 'product' });
                window.dataLayer.push({ event: 'begin_checkout', ecommerce: { currency: currency, value: cartValue, items: cartItems } });
            }
        });
    }
})();
</script>
@endpush
