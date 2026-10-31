@php
    $promoPopup = \Illuminate\Support\Facades\Cache::remember('active_promo_popup', 300, function () {
        return \App\Models\Popup::where('status', 1)->latest()->first();
    });
    $promoImageOnly = $promoPopup
        && empty(trim((string) $promoPopup->description))
        && empty(trim((string) $promoPopup->btn_text));
@endphp

@if($promoPopup && !empty($promoPopup->image))
<style>
#promoPopupOverlay {
    position: fixed;
    inset: 0;
    z-index: 100001;
    background: rgba(0, 0, 0, 0.72);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
}
#promoPopupOverlay.is-open {
    display: flex;
    opacity: 1;
}
#promoPopupOverlay .promo-popup-box {
    position: relative;
    max-width: 520px;
    width: min(92vw, 520px);
    max-height: 88vh;
    padding: 8px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.28);
    animation: promoPopupIn 0.35s ease;
    border: none;
    outline: none;
}
@keyframes promoPopupIn {
    from { transform: scale(0.92); opacity: 0; }
    to   { transform: scale(1); opacity: 1; }
}
#promoPopupOverlay .promo-popup-close {
    position: absolute;
    top: 4px;
    right: 8px;
    width: 28px;
    height: 28px;
    border: none;
    border-radius: 0;
    background: transparent;
    color: #555;
    font-size: 24px;
    font-weight: 300;
    line-height: 1;
    cursor: pointer;
    box-shadow: none;
    z-index: 2;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0;
}
#promoPopupOverlay .promo-popup-close:hover {
    color: #111;
    background: transparent;
}
#promoPopupOverlay .promo-popup-img-wrap {
    display: block;
    border: none;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: none;
    outline: none;
    background: #fff;
    line-height: 0;
}
#promoPopupOverlay .promo-popup-img-wrap img {
    width: 100%;
    height: auto;
    max-height: calc(88vh - 36px);
    object-fit: contain;
    display: block;
    border: none;
    outline: none;
    box-shadow: none;
    border-radius: 4px;
}
#promoPopupOverlay .promo-popup-card {
    background: #fff;
    border: none;
    border-radius: 4px;
    overflow: hidden;
    box-shadow: none;
    outline: none;
}
#promoPopupOverlay .promo-popup-body {
    padding: 14px 0 0;
    text-align: center;
    background: transparent;
    border: none;
}
#promoPopupOverlay .promo-popup-title {
    font-size: 20px;
    font-weight: 800;
    color: #111827;
    margin: 0 0 8px;
}
#promoPopupOverlay .promo-popup-desc {
    font-size: 14px;
    color: #4b5563;
    line-height: 1.6;
    margin: 0 0 16px;
}
#promoPopupOverlay .promo-popup-btn {
    display: inline-block;
    background: var(--primary, #df2d4d);
    color: #fff !important;
    text-decoration: none;
    padding: 11px 28px;
    border-radius: 999px;
    font-weight: 700;
    font-size: 14px;
}
#promoPopupOverlay .promo-popup-foot {
    font-size: 12px;
    color: #9ca3af;
    margin-top: 12px;
}
@media (max-width: 575px) {
    #promoPopupOverlay { padding: 16px 12px; }
    #promoPopupOverlay .promo-popup-box {
        max-width: 360px;
        width: min(94vw, 360px);
        padding: 6px;
        border-radius: 10px;
    }
    #promoPopupOverlay .promo-popup-img-wrap img {
        max-height: calc(82vh - 32px);
    }
    #promoPopupOverlay .promo-popup-close {
        top: 2px;
        right: 6px;
        width: 26px;
        height: 26px;
        font-size: 22px;
    }
}
</style>

<div id="promoPopupOverlay" role="dialog" aria-modal="true" aria-label="{{ $promoPopup->title ?? 'Promotional offer' }}">
    <div class="promo-popup-box">
        <button type="button" class="promo-popup-close" id="promoPopupClose" aria-label="বন্ধ করুন">&times;</button>

        @if($promoImageOnly)
            @if(!empty($promoPopup->link))
                <a href="{{ $promoPopup->link }}" class="promo-popup-img-wrap" id="promoPopupImageLink" target="_blank" rel="noopener">
                    <img src="{{ asset('public/' . $promoPopup->image) }}" alt="{{ $promoPopup->title ?? 'Offer' }}">
                </a>
            @else
                <div class="promo-popup-img-wrap">
                    <img src="{{ asset('public/' . $promoPopup->image) }}" alt="{{ $promoPopup->title ?? 'Offer' }}">
                </div>
            @endif
        @else
            <div class="promo-popup-card">
                <a href="{{ $promoPopup->link ?: '#' }}" class="promo-popup-img-wrap" @if($promoPopup->link) target="_blank" rel="noopener" @endif>
                    <img src="{{ asset('public/' . $promoPopup->image) }}" alt="{{ $promoPopup->title ?? 'Offer' }}">
                </a>
                <div class="promo-popup-body">
                    @if(!empty($promoPopup->title))
                        <h3 class="promo-popup-title">{{ $promoPopup->title }}</h3>
                    @endif
                    @if(!empty($promoPopup->description))
                        <p class="promo-popup-desc">{{ $promoPopup->description }}</p>
                    @endif
                    @if(!empty($promoPopup->btn_text) && !empty($promoPopup->link))
                        <a href="{{ $promoPopup->link }}" class="promo-popup-btn" target="_blank" rel="noopener">{{ $promoPopup->btn_text }}</a>
                    @endif
                    @if(!empty($promoPopup->offer_end_text))
                        <div class="promo-popup-foot">{{ $promoPopup->offer_end_text }}</div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<script>
(function () {
    var overlay  = document.getElementById('promoPopupOverlay');
    var closeBtn = document.getElementById('promoPopupClose');
    if (!overlay || !closeBtn) return;

    var storageKey = 'promo_popup_closed_{{ $promoPopup->id }}';

    function closePopup(persist) {
        overlay.classList.remove('is-open');
        setTimeout(function () { overlay.style.display = 'none'; }, 280);
        if (persist) {
            try { sessionStorage.setItem(storageKey, '1'); } catch (e) {}
        }
    }

    try {
        if (sessionStorage.getItem(storageKey) === '1') return;
    } catch (e) {}

    closeBtn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        closePopup(true);
    });

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closePopup(true);
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
            closePopup(true);
        }
    });

    function openPopup() {
        overlay.style.display = 'flex';
        requestAnimationFrame(function () {
            overlay.classList.add('is-open');
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(openPopup, 700);
        });
    } else {
        setTimeout(openPopup, 700);
    }
})();
</script>
@endif
