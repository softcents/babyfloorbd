<style>
#snx-popup {
    position: fixed;
    bottom: 24px;
    left: 24px;
    z-index: 99999;
    width: 320px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 16px;
    border-left: 4px solid var(--primary);
    transform: translateX(-380px);
    opacity: 0;
    transition: transform 0.45s cubic-bezier(.34,1.56,.64,1), opacity 0.35s ease;
    pointer-events: none;
}
#snx-popup.snx-show {
    transform: translateX(0);
    opacity: 1;
    pointer-events: auto;
}
#snx-popup .snx-img {
    width: 58px;
    height: 58px;
    border-radius: 10px;
    object-fit: cover;
    flex-shrink: 0;
    border: 1px solid #f0f0f0;
}
#snx-popup .snx-body { flex: 1; min-width: 0; }
#snx-popup .snx-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
#snx-popup .snx-title span { color: var(--primary); }
#snx-popup .snx-product {
    font-size: 12px;
    color: #475569;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
#snx-popup .snx-time {
    font-size: 11px;
    color: #94a3b8;
    margin-top: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
}
#snx-popup .snx-close {
    position: absolute;
    top: 8px;
    right: 10px;
    font-size: 16px;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    background: none;
    border: none;
    padding: 0;
}
#snx-popup .snx-close:hover { color: var(--primary); }
#snx-popup .snx-badge {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    color: #64748b;
    background: #f1f5f9;
    border-radius: 20px;
    padding: 2px 7px;
    margin-top: 4px;
}
#snx-popup .snx-dot {
    width: 7px; height: 7px;
    background: #22c55e;
    border-radius: 50%;
    display: inline-block;
    animation: snx-pulse 1.5s infinite;
}
@keyframes snx-pulse {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:.5; transform:scale(1.4); }
}
@media (max-width: 480px) {
    #snx-popup { width: calc(100vw - 32px); left: 16px; bottom: 80px; }
}
</style>

<div id="snx-popup" role="alert" aria-live="polite">
    <button class="snx-close" id="snx-close-btn" aria-label="বন্ধ করুন">✕</button>
    <img id="snx-img" class="snx-img" src="" alt="Product">
    <div class="snx-body">
        <div class="snx-title"><span id="snx-name"></span> এই মাত্র কিনেছেন</div>
        <div class="snx-product" id="snx-product"></div>
        <div class="snx-time">
            <span class="snx-dot"></span>
            <span id="snx-time"></span>
        </div>
        <div class="snx-badge">🛡️ Verified Order</div>
    </div>
</div>

<script>
(function(){
    var popup      = document.getElementById('snx-popup');
    if (!popup) return;

    var imgEl      = document.getElementById('snx-img');
    var nameEl     = document.getElementById('snx-name');
    var productEl  = document.getElementById('snx-product');
    var timeEl     = document.getElementById('snx-time');
    var closeBtn   = document.getElementById('snx-close-btn');
    var apiUrl     = @json(route('sales.notifications', [], false));

    var notifications = [];
    var currentIndex  = 0;
    var hideTimer, nextTimer, fetchTimer;
    var userClosed    = false;
    var SHOW_DURATION = 5000;
    var INT_MIN       = 8000;
    var INT_MAX       = 15000;
    var defaultImg    = @json(asset('public/uploads/default.webp'));

    closeBtn.addEventListener('click', function(){
        hidePopup();
        userClosed = true;
        clearTimeout(fetchTimer);
        fetchTimer = setTimeout(function(){ userClosed = false; showNext(); }, 120000);
    });

    function showPopup(item) {
        imgEl.src             = item.image || defaultImg;
        imgEl.onerror         = function(){ this.src = defaultImg; };
        nameEl.textContent    = item.name || 'কেউ';
        productEl.textContent = item.product_name || '';
        timeEl.textContent    = item.time || 'এইমাত্র';

        productEl.onclick = function(){
            if (item.product_url && item.product_url !== '#') {
                window.location.href = item.product_url;
            }
        };
        productEl.style.cursor = item.product_url && item.product_url !== '#' ? 'pointer' : 'default';

        popup.classList.add('snx-show');
        clearTimeout(hideTimer);
        hideTimer = setTimeout(hidePopup, SHOW_DURATION);
    }

    function hidePopup() {
        popup.classList.remove('snx-show');
    }

    function showNext() {
        if (userClosed || notifications.length === 0) return;
        var item = notifications[currentIndex % notifications.length];
        currentIndex++;
        showPopup(item);
        clearTimeout(nextTimer);
        var delay = INT_MIN + Math.floor(Math.random() * (INT_MAX - INT_MIN));
        nextTimer = setTimeout(showNext, delay);
    }

    function fetchAndStart() {
        fetch(apiUrl, {
            method: 'GET',
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin'
        })
            .then(function(r){
                if (!r.ok) throw new Error('HTTP ' + r.status);
                return r.json();
            })
            .then(function(data){
                if (!data || !data.enabled) return;
                var items = Array.isArray(data.items) ? data.items : [];
                if (items.length === 0) return;

                if (data.display_duration) SHOW_DURATION = data.display_duration;
                if (data.interval_min)     INT_MIN       = data.interval_min;
                if (data.interval_max)     INT_MAX       = data.interval_max;

                notifications = items.slice().sort(function(){ return Math.random() - 0.5; });
                currentIndex  = 0;
                clearTimeout(nextTimer);
                if (!userClosed) {
                    nextTimer = setTimeout(showNext, 2500);
                }
            })
            .catch(function(err){
                console.warn('Sales notification popup:', err.message || err);
            });
    }

    function scheduleFetch() {
        if ('requestIdleCallback' in window) {
            requestIdleCallback(fetchAndStart, { timeout: 10000 });
        } else {
            setTimeout(fetchAndStart, 8000);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', scheduleFetch);
    } else {
        scheduleFetch();
    }

    setInterval(fetchAndStart, 300000);
})();
</script>
