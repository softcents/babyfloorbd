@extends('frontEnd.layouts.master')
@section('title', 'অর্ডার সফল — #' . $order->invoice_id)

@push('css')
<style>
.os-page {
    background: linear-gradient(180deg, #f6f7fb 0%, #eef1f6 100%);
    padding-bottom: 60px;
    min-height: 60vh;
}
.os-topbar {
    background: #fff;
    border-bottom: 1px solid #e9edf2;
    padding: 12px 0;
    margin-bottom: 20px;
    box-shadow: 0 1px 0 rgba(0,0,0,.03);
}
.os-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.os-logo {
    font-size: 18px;
    font-weight: 800;
    color: var(--primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}
.os-logo img { height: 34px; object-fit: contain; }
.os-steps {
    display: flex;
    align-items: center;
    gap: 0;
}
.os-step {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #b0b8c4;
}
.os-step-num {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    border: 2px solid #dde3ea;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    background: #fff;
}
.os-step.done .os-step-num { background: #22c55e; border-color: #22c55e; color: #fff; }
.os-step.done { color: #22c55e; }
.os-step.active .os-step-num { background: var(--primary); border-color: var(--primary); color: #fff; }
.os-step.active { color: var(--primary); }
.os-step-line { width: 36px; height: 2px; background: #dde3ea; margin: 0 6px; }
.os-step-line.done { background: #22c55e; }

.os-hero {
    background: #fff;
    border: 1px solid #e8ecf1;
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(15, 23, 42, .06);
    padding: 32px 24px;
    text-align: center;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
}
.os-hero::before {
    content: '';
    position: absolute;
    inset: 0 0 auto 0;
    height: 4px;
    background: linear-gradient(90deg, #22c55e, #16a34a);
}
.os-hero-icon {
    width: 76px;
    height: 76px;
    margin: 0 auto 16px;
    border-radius: 50%;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    box-shadow: 0 10px 28px rgba(34, 197, 94, .35);
    animation: osPop .5s cubic-bezier(.34, 1.56, .64, 1);
}
@keyframes osPop {
    0% { transform: scale(.6); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}
.os-hero h1 {
    margin: 0 0 8px;
    font-size: 24px;
    font-weight: 800;
    color: #111827;
}
.os-hero p {
    margin: 0;
    font-size: 14px;
    color: #6b7280;
    line-height: 1.6;
}
.os-invoice-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    margin-top: 14px;
    padding: 8px 16px;
    border-radius: 999px;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    font-size: 14px;
    font-weight: 700;
}

.os-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: center;
    margin-bottom: 20px;
}
.os-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 11px 18px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: transform .15s ease, opacity .15s ease;
}
.os-btn:hover { opacity: .92; transform: translateY(-1px); }
.os-btn-primary { background: var(--primary); color: #fff; box-shadow: 0 4px 14px rgba(var(--primary-rgb), .25); }
.os-btn-dark { background: #1f2937; color: #fff; }
.os-btn-light { background: #fff; color: #374151; border: 1px solid #d1d5db; }

.os-digital {
    background: linear-gradient(135deg, #eff6ff, #f0f9ff);
    border: 1px dashed #38bdf8;
    border-radius: 16px;
    padding: 20px;
    text-align: center;
    margin-bottom: 20px;
}
.os-digital h3 {
    margin: 0 0 6px;
    font-size: 16px;
    font-weight: 800;
    color: #0f172a;
}
.os-digital p { margin: 0 0 14px; font-size: 13px; color: #64748b; }
.os-dl-btns { display: flex; flex-wrap: wrap; gap: 8px; justify-content: center; }
.os-dl-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 16px;
    border-radius: 999px;
    background: #0284c7;
    color: #fff;
    font-size: 13px;
    font-weight: 700;
    text-decoration: none;
}
.os-dl-btn:hover { background: #0369a1; color: #fff; }

.os-row {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 20px;
}
.os-left { flex: 1 1 100%; min-width: 0; order: 2; }
.os-right {
    flex: 1 1 100%;
    min-width: 0;
    order: 1;
    margin-bottom: 12px;
}
@media (min-width: 992px) {
    .os-row { flex-wrap: nowrap; gap: 28px; }
    .os-left { flex: 1 1 58%; max-width: 58%; order: 1; }
    .os-right {
        flex: 0 0 38%;
        max-width: 38%;
        order: 2;
        position: sticky;
        top: 90px;
        margin-bottom: 0;
    }
}

.os-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8ecf1;
    box-shadow: 0 4px 24px rgba(15, 23, 42, .04);
    margin-bottom: 16px;
    overflow: hidden;
}
.os-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: linear-gradient(180deg, #fafbfd 0%, #fff 100%);
    border-bottom: 1px solid #f0f3f7;
}
.os-card-icon {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    flex-shrink: 0;
}
.os-card-title { font-size: 16px; font-weight: 700; color: #1f2937; margin: 0; }
.os-card-sub { font-size: 12px; color: #9aa3af; margin: 2px 0 0; }
.os-card-body { padding: 20px; }

.os-info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
}
@media (max-width: 575px) {
    .os-info-grid { grid-template-columns: 1fr; }
}
.os-info-item label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: #9ca3af;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
}
.os-info-item span {
    display: block;
    font-size: 14px;
    color: #1f2937;
    font-weight: 600;
    line-height: 1.5;
}

.os-product {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid #f3f4f6;
}
.os-product:last-child { border-bottom: none; padding-bottom: 0; }
.os-product:first-child { padding-top: 0; }
.os-pro-thumb {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    object-fit: cover;
    border: 1px solid #eee;
    flex-shrink: 0;
    background: #f9fafb;
}
.os-pro-info { flex: 1; min-width: 0; }
.os-pro-name {
    font-size: 14px;
    font-weight: 700;
    color: #1f2937;
    margin: 0 0 4px;
    line-height: 1.4;
}
.os-pro-meta { font-size: 12px; color: #9ca3af; margin: 0; }
.os-pro-price {
    text-align: right;
    flex-shrink: 0;
}
.os-pro-price strong {
    display: block;
    font-size: 15px;
    font-weight: 800;
    color: var(--primary);
}
.os-pro-price small { font-size: 12px; color: #9ca3af; }

.os-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: #4b5563;
}
.os-total-row.grand {
    border-top: 2px solid #e5e7eb;
    margin-top: 8px;
    padding-top: 14px;
    font-size: 18px;
    font-weight: 800;
    color: #111827;
}
.os-total-row.discount { color: #dc2626; }
.os-pay-box {
    margin-top: 14px;
    padding: 14px;
    border-radius: 12px;
    background: #111827;
    color: #fff;
}
.os-pay-box .os-total-row { color: #d1d5db; padding: 4px 0; }
.os-pay-box .os-total-row strong { color: #fff; }

.os-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 700;
}
.os-status.paid { background: #dcfce7; color: #15803d; }
.os-status.due { background: #fee2e2; color: #b91c1c; }
.os-status.pending { background: #fef3c7; color: #b45309; }

#invoice-pdf-area { background: #fff; }

@media print {
    .no-print { display: none !important; }
    .os-page { background: #fff; padding: 0; }
    .os-card { box-shadow: none; border: 1px solid #ddd; break-inside: avoid; }
    .os-right { position: static; }
}
</style>
@endpush

@section('content')
@php
    $payment = \App\Models\Payment::where('order_id', $order->id)->orderBy('id', 'desc')->first();

    $gateway_status = $payment ? strtolower(trim($payment->payment_status)) : '';
    $payment_method = $payment ? strtolower(trim($payment->payment_method)) : strtolower(trim($order->payment_method ?? ''));

    $admin_status = strtolower(trim($order->payment_status ?? ''));
    $order_status = strtolower(trim($order->status ?? ''));

    $grand_total = $order->amount;
    $paid_amount = 0;

    if ($payment && !in_array($gateway_status, ['failed', 'cancel', 'cancelled', 'rejected'])) {
        $paid_amount = $payment->amount;
    }

    $is_cod = in_array($payment_method, ['cod', 'cash', 'cash_on_delivery', 'hand cash', 'hand_cash']);
    $is_order_completed = in_array($order_status, ['completed', 'delivered']) || in_array($admin_status, ['completed', 'delivered']);

    if ($is_cod && ! $is_order_completed) {
        if ($paid_amount >= $grand_total) {
            $paid_amount = 0;
        }
    }

    if ($is_order_completed) {
        $paid_amount = $grand_total;
    } elseif (($paid_amount == 0 || ! $payment) && in_array($admin_status, ['paid', 'success', 'approved'])) {
        $paid_amount = $grand_total;
    }

    $due_amount = max(0, $grand_total - $paid_amount);
    $subtotal = ($order->amount + $order->discount) - $order->shipping_charge;
    $is_fully_paid = ($paid_amount >= $grand_total);
    $downloads = $is_fully_paid ? \App\Models\DigitalDownload::where('order_id', $order->id)->get() : collect();

    $paymentLabels = [
        'cod' => 'ক্যাশ অন ডেলিভারি',
        'cash' => 'ক্যাশ অন ডেলিভারি',
        'cash_on_delivery' => 'ক্যাশ অন ডেলিভারি',
        'hand cash' => 'ক্যাশ অন ডেলিভারি',
        'hand_cash' => 'ক্যাশ অন ডেলিভারি',
        'bkash' => 'bKash',
        'shurjopay' => 'ShurjoPay',
        'uddoktapay' => 'UddoktaPay',
        'aamarpay' => 'AamarPay',
    ];
    $paymentLabel = $paymentLabels[$payment_method] ?? ucfirst(str_replace(['_', 'manual '], [' ', ''], $payment_method));

    if ($is_fully_paid) {
        $payStatusClass = 'paid';
        $payStatusText = 'পরিশোধিত';
    } elseif ($paid_amount > 0) {
        $payStatusClass = 'pending';
        $payStatusText = 'আংশিক পরিশোধ';
    } else {
        $payStatusClass = 'due';
        $payStatusText = $is_cod ? 'ডেলিভারিতে পরিশোধ' : 'পেমেন্ট বাকি';
    }
@endphp

<div class="os-page">

    <div class="os-topbar no-print">
        <div class="container">
            <div class="os-topbar-inner">
                <a href="{{ route('home') }}" class="os-logo">
                    @if(!empty($generalsetting->dark_logo))
                        <img src="{{ asset($generalsetting->dark_logo) }}" alt="Logo">
                    @else
                        <i class="fas fa-store"></i>
                        {{ $generalsetting->name ?? config('app.name') }}
                    @endif
                </a>
                <div class="os-steps">
                    <div class="os-step done">
                        <div class="os-step-num"><i class="fas fa-check" style="font-size:10px;"></i></div>
                        <span>কার্ট</span>
                    </div>
                    <div class="os-step-line done"></div>
                    <div class="os-step done">
                        <div class="os-step-num"><i class="fas fa-check" style="font-size:10px;"></i></div>
                        <span>চেকআউট</span>
                    </div>
                    <div class="os-step-line done"></div>
                    <div class="os-step active">
                        <div class="os-step-num"><i class="fas fa-check" style="font-size:10px;"></i></div>
                        <span>কনফার্মেশন</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">

        <div class="os-hero no-print">
            <div class="os-hero-icon"><i class="fas fa-check"></i></div>
            <h1>অর্ডার সফলভাবে গ্রহণ করা হয়েছে!</h1>
            <p>ধন্যবাদ। আপনার অর্ডারটি আমরা পেয়েছি এবং শীঘ্রই প্রসেস করা হবে।</p>
            <div class="os-invoice-badge">
                <i class="fas fa-receipt"></i>
                ইনভয়েস নং: #{{ $order->invoice_id }}
            </div>
        </div>

        <div class="os-actions no-print">
            <a href="{{ route('home') }}" class="os-btn os-btn-light">
                <i class="fas fa-home"></i> হোমে যান
            </a>
            <a href="{{ route('customer.order_track') }}" class="os-btn os-btn-light">
                <i class="fas fa-truck"></i> অর্ডার ট্র্যাক
            </a>
            @auth('customer')
                <a href="{{ route('customer.orders') }}" class="os-btn os-btn-dark">
                    <i class="fas fa-list"></i> আমার অর্ডার
                </a>
            @endauth
            <button type="button" onclick="window.print()" class="os-btn os-btn-light">
                <i class="fas fa-print"></i> প্রিন্ট
            </button>
            <button type="button" onclick="downloadPDF()" class="os-btn os-btn-primary">
                <i class="fas fa-download"></i> ইনভয়েস ডাউনলোড
            </button>
        </div>

        @if($is_fully_paid && $downloads->count() > 0)
        <div class="os-digital no-print">
            <h3><i class="fas fa-cloud-download-alt"></i> ডিজিটাল আইটেম প্রস্তুত</h3>
            <p>পেমেন্ট সফল হওয়ায় আপনার ফাইলগুলো ডাউনলোডের জন্য উন্মুক্ত করা হয়েছে।</p>
            <div class="os-dl-btns">
                @foreach($downloads as $dl)
                    <a href="{{ route('digital.download', $dl->token) }}" class="os-dl-btn">
                        <i class="fas fa-download"></i>
                        {{ $dl->product->name ?? 'ফাইল ডাউনলোড' }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <div id="invoice-pdf-area">
            <div class="os-row">

                <div class="os-left">

                    <div class="os-card">
                        <div class="os-card-head">
                            <div class="os-card-icon"><i class="fas fa-shopping-bag"></i></div>
                            <div>
                                <p class="os-card-title">অর্ডার আইটেম</p>
                                <p class="os-card-sub">{{ $order->orderdetails->count() }}টি পণ্য</p>
                            </div>
                        </div>
                        <div class="os-card-body">
                            @foreach($order->orderdetails as $item)
                                @php
                                    $sizeDisplay = $item->size ? ($item->size->sizeName ?? $item->size->size_name ?? $item->size->name ?? null) : null;
                                    $colorDisplay = $item->color ? ($item->color->getDisplayName() ?? $item->color->colorName ?? $item->color->color_name ?? $item->color->name ?? null) : null;
                                    if (! $sizeDisplay && $item->product_size) {
                                        $s = \App\Models\Size::find($item->product_size);
                                        $sizeDisplay = $s ? ($s->sizeName ?? $s->size_name ?? null) : null;
                                    }
                                    if (! $colorDisplay && $item->product_color) {
                                        $c = \App\Models\Color::find($item->product_color);
                                        $colorDisplay = $c ? ($c->getDisplayName() ?? $c->colorName ?? $c->color_name ?? null) : null;
                                    }
                                    $thumb = optional($item->image)->image ? asset($item->image->image) : asset('public/no-image.png');
                                @endphp
                                <div class="os-product">
                                    <img src="{{ $thumb }}" alt="{{ $item->product_name }}" class="os-pro-thumb">
                                    <div class="os-pro-info">
                                        <p class="os-pro-name">{{ $item->product_name }}</p>
                                        <p class="os-pro-meta">
                                            @if($sizeDisplay) সাইজ: {{ $sizeDisplay }} @endif
                                            @if($sizeDisplay && $colorDisplay) · @endif
                                            @if($colorDisplay) রং: {{ $colorDisplay }} @endif
                                            @if($sizeDisplay || $colorDisplay) · @endif
                                            পরিমাণ: {{ $item->qty }}
                                        </p>
                                    </div>
                                    <div class="os-pro-price">
                                        <strong>৳{{ number_format($item->sale_price * $item->qty, 2) }}</strong>
                                        <small>৳{{ number_format($item->sale_price, 2) }} × {{ $item->qty }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="os-card">
                        <div class="os-card-head">
                            <div class="os-card-icon"><i class="fas fa-user"></i></div>
                            <div>
                                <p class="os-card-title">ডেলিভারি তথ্য</p>
                                <p class="os-card-sub">গ্রাহক ও ঠিকানা</p>
                            </div>
                        </div>
                        <div class="os-card-body">
                            <div class="os-info-grid">
                                <div class="os-info-item">
                                    <label>গ্রাহকের নাম</label>
                                    <span>{{ $order->shipping->name ?? 'N/A' }}</span>
                                </div>
                                <div class="os-info-item">
                                    <label>মোবাইল</label>
                                    <span>{{ $order->shipping->phone ?? '—' }}</span>
                                </div>
                                <div class="os-info-item" style="grid-column:1/-1;">
                                    <label>ঠিকানা</label>
                                    <span>{{ $order->shipping->address ?? '—' }}</span>
                                </div>
                                @if(!empty($order->shipping->area))
                                <div class="os-info-item">
                                    <label>এলাকা</label>
                                    <span>{{ $order->shipping->area }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>

                <div class="os-right">
                    <div class="os-card">
                        <div class="os-card-head">
                            <div class="os-card-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            <div>
                                <p class="os-card-title">পেমেন্ট সারাংশ</p>
                                <p class="os-card-sub">{{ $order->created_at->format('d M, Y — h:i A') }}</p>
                            </div>
                        </div>
                        <div class="os-card-body">
                            <div class="os-total-row">
                                <span>সাবটোটাল</span>
                                <span>৳{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="os-total-row">
                                <span>ডেলিভারি চার্জ</span>
                                <span>৳{{ number_format($order->shipping_charge, 2) }}</span>
                            </div>
                            @if($order->discount > 0)
                            <div class="os-total-row discount">
                                <span>ডিসকাউন্ট</span>
                                <span>-৳{{ number_format($order->discount, 2) }}</span>
                            </div>
                            @endif
                            <div class="os-total-row grand">
                                <span>সর্বমোট</span>
                                <span>৳{{ number_format($grand_total, 2) }}</span>
                            </div>

                            <div class="os-pay-box">
                                <div class="os-total-row">
                                    <span>পরিশোধিত</span>
                                    <strong>৳{{ number_format($paid_amount, 2) }}</strong>
                                </div>
                                <div class="os-total-row">
                                    <span>বাকি</span>
                                    <strong>৳{{ number_format($due_amount, 2) }}</strong>
                                </div>
                            </div>

                            <div style="margin-top:16px;">
                                <div class="os-info-item" style="margin-bottom:10px;">
                                    <label>পেমেন্ট মেথড</label>
                                    <span>{{ $paymentLabel }}</span>
                                </div>
                                <span class="os-status {{ $payStatusClass }}">
                                    <i class="fas fa-circle" style="font-size:7px;"></i>
                                    {{ $payStatusText }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="os-card no-print">
                        <div class="os-card-body" style="text-align:center;">
                            <p style="margin:0 0 6px;font-size:13px;color:#6b7280;">আরও সাহায্য দরকার?</p>
                            @if(!empty($contact->phone))
                                <a href="tel:{{ $contact->phone }}" class="os-btn os-btn-light" style="width:100%;">
                                    <i class="fas fa-phone-alt"></i> {{ $contact->phone }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
@php
    $purchaseItems = [];
    foreach ($order->orderdetails as $item) {
        $purchaseItems[] = [
            'id'   => (string) ($item->product_id ?? $item->id),
            'name' => $item->product_name,
            'price'=> (float) $item->sale_price,
            'qty'  => (int) $item->qty,
        ];
    }
    $purchaseTrackingUser = \App\Support\EcommerceTrackingUser::fromOrder($order);
@endphp
<script>
(function () {
    if (typeof window.EcomTracking === 'undefined') return;

    var orderId = '{{ $order->invoice_id }}';
    var storageKey = 'purchase_fired_' + orderId;
    if (localStorage.getItem(storageKey)) return;
    localStorage.setItem(storageKey, '1');

    var user = @json($purchaseTrackingUser);
    user.fbp = window.EcomTracking.getCookie('_fbp');
    user.fbc = window.EcomTracking.getCookie('_fbc');
    user.address = user.address || @json($order->shipping?->address ?? '');
    user.area = user.area || user.city || @json($order->shipping?->area ?? '');

    window.EcomTracking.purchase({
        transaction_id: orderId,
        order_id: orderId,
        event_id: 'purchase_' + orderId,
        value: parseFloat("{{ $order->amount }}") || 0,
        shipping: parseFloat("{{ $order->shipping_charge }}") || 0,
        tax: 0,
        coupon: @json($order->coupon_code),
        payment_method: @json($payment_method),
        items: @json($purchaseItems),
        user: user,
        order_info: {
            invoice_id: orderId,
            order_id: '{{ $order->id }}',
            payment_method: @json($payment_method),
            payment_status: @json($payment ? $payment->payment_status : ''),
            grand_total: parseFloat("{{ $order->amount }}") || 0,
            shipping: parseFloat("{{ $order->shipping_charge }}") || 0,
            discount: parseFloat("{{ $order->discount }}") || 0,
            item_count: @json(count($purchaseItems))
        }
    });
})();
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
function downloadPDF() {
    var element = document.getElementById('invoice-pdf-area');
    var invoiceId = "{{ $order->invoice_id }}";
    var opt = {
        margin: [10, 10, 10, 10],
        filename: 'Invoice-' + invoiceId + '.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: { scale: 2, useCORS: true },
        jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
    };
    html2pdf().set(opt).from(element).save();
}
</script>
@endpush
