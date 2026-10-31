@php
    $_img      = ($value->image && $value->image->image) ? asset($value->image->image) : asset('public/no-image.png');
    $_pid      = $value->id;
    $_pname    = addslashes(Str::limit($value->name, 60));
    $_price    = (int)$value->new_price;
    $_oldprice = (int)($value->old_price ?? 0);
    $_slug     = $value->slug;
@endphp
<div class="product-actions">
    <button type="button" class="btn-order vom-btn"
        data-id="{{ $_pid }}"
        data-name="{{ $_pname }}"
        data-price="{{ $_price }}"
        data-old-price="{{ $_oldprice }}"
        data-image="{{ $_img }}"
        data-slug="{{ $_slug }}"
        data-mode="order">অর্ডার করুন</button>

    <button type="button" class="btn-cart vom-btn"
        aria-label="কার্টে যোগ করুন"
        data-id="{{ $_pid }}"
        data-name="{{ $_pname }}"
        data-price="{{ $_price }}"
        data-old-price="{{ $_oldprice }}"
        data-image="{{ $_img }}"
        data-slug="{{ $_slug }}"
        data-mode="cart">
        <i class="fas fa-shopping-basket"></i>
    </button>
</div>
