@extends('frontEnd.layouts.master')
@section('title','অর্ডার ট্র্যাকিং ফলাফল')

@php
    $generalsetting = \App\Models\GeneralSetting::first();
@endphp

@push('css')
<style>
    .track-print-only {
        display: none;
    }

    .track-print-sheet {
        background: #fff;
        color: #111;
        font-family: 'Hind Siliguri', 'Roboto', sans-serif;
        max-width: 210mm;
        margin: 0 auto;
        padding: 0;
    }

    .tp-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        padding-bottom: 20px;
        border-bottom: 2px solid #111;
        margin-bottom: 24px;
    }

    .tp-brand img {
        width: 130px;
        height: auto;
        margin-bottom: 10px;
    }

    .tp-brand-name {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .tp-brand-meta {
        font-size: 12px;
        color: #555;
        line-height: 1.6;
    }

    .tp-doc-title {
        text-align: right;
    }

    .tp-doc-title h1 {
        font-size: 26px;
        font-weight: 800;
        letter-spacing: 1px;
        margin: 0 0 8px;
        color: #111;
    }

    .tp-doc-title p {
        font-size: 13px;
        color: #555;
        margin: 0 0 4px;
    }

    .tp-status-pill {
        display: inline-block;
        margin-top: 8px;
        padding: 5px 14px;
        border: 1px solid #111;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .tp-status-pill.is-cancelled {
        border-color: #b91c1c;
        color: #b91c1c;
    }

    .tp-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .tp-info-box {
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 14px;
        background: #fafafa;
    }

    .tp-info-box label {
        display: block;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #666;
        margin-bottom: 8px;
    }

    .tp-info-box strong {
        display: block;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .tp-info-box span {
        display: block;
        font-size: 12px;
        color: #444;
        line-height: 1.5;
    }

    .tp-progress {
        display: flex;
        align-items: center;
        gap: 0;
        margin-bottom: 24px;
        padding: 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
    }

    .tp-progress-step {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .tp-progress-step:not(:last-child)::after {
        content: '';
        position: absolute;
        top: 11px;
        left: 50%;
        width: 100%;
        height: 2px;
        background: #ddd;
        z-index: 0;
    }

    .tp-progress-step.done:not(:last-child)::after,
    .tp-progress-step.active:not(:last-child)::after {
        background: #111;
    }

    .tp-progress-dot {
        width: 22px;
        height: 22px;
        border-radius: 50%;
        border: 2px solid #ccc;
        background: #fff;
        margin: 0 auto 8px;
        position: relative;
        z-index: 1;
    }

    .tp-progress-step.done .tp-progress-dot {
        border-color: #111;
        background: #111;
    }

    .tp-progress-step.done .tp-progress-dot::after {
        content: '✓';
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
    }

    .tp-progress-step.active .tp-progress-dot {
        border-color: #111;
        box-shadow: inset 0 0 0 5px #fff;
        background: #111;
    }

    .tp-progress-step small {
        display: block;
        font-size: 10px;
        font-weight: 700;
        color: #333;
    }

    .tp-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .tp-table thead th {
        background: #111;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        padding: 10px 12px;
        text-align: left;
    }

    .tp-table thead th:nth-child(3),
    .tp-table thead th:nth-child(4),
    .tp-table thead th:nth-child(5) {
        text-align: center;
    }

    .tp-table thead th:last-child {
        text-align: right;
    }

    .tp-table tbody td {
        padding: 12px;
        border-bottom: 1px solid #eee;
        font-size: 13px;
        vertical-align: top;
    }

    .tp-table tbody td:nth-child(3),
    .tp-table tbody td:nth-child(4) {
        text-align: center;
    }

    .tp-table tbody td:last-child {
        text-align: right;
        font-weight: 700;
    }

    .tp-product-name {
        font-weight: 700;
        margin-bottom: 3px;
    }

    .tp-product-meta {
        font-size: 11px;
        color: #666;
    }

    .tp-summary-wrap {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 28px;
    }

    .tp-summary {
        width: 280px;
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
    }

    .tp-summary-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 14px;
        font-size: 13px;
        border-bottom: 1px solid #eee;
    }

    .tp-summary-row:last-child {
        border-bottom: none;
    }

    .tp-summary-row.total {
        background: #111;
        color: #fff;
        font-size: 16px;
        font-weight: 800;
    }

    .tp-summary-row.discount {
        color: #b91c1c;
    }

    .tp-footer {
        border-top: 1px solid #ddd;
        padding-top: 16px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 16px;
        font-size: 11px;
        color: #666;
    }

    .tp-footer strong {
        display: block;
        color: #111;
        font-size: 12px;
        margin-bottom: 4px;
    }

    .tp-cancel-banner {
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 20px;
        text-align: center;
    }

    @page {
        size: A4;
        margin: 12mm 14mm;
    }

    @media print {
        html, body {
            background: #fff !important;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .track-print-sheet {
            page-break-after: always;
            max-width: none;
        }

        .track-print-sheet:last-child {
            page-break-after: auto;
        }

        .tp-table thead th {
            background: #111 !important;
            color: #fff !important;
        }

        .tp-summary-row.total {
            background: #111 !important;
            color: #fff !important;
        }
    }
</style>
@endpush

@section('content')
<main class="app-main">
    <section class="page-hero track-screen-only">
        <div class="container">
            <h1><i class="fas fa-truck"></i> অর্ডার ট্র্যাকিং ফলাফল</h1>
            <p>আপনার অর্ডারের বর্তমান স্ট্যাটাস ও বিস্তারিত তথ্য</p>
        </div>
    </section>

    <section class="service-page-section track-screen-only">
        <div class="container">
            @if($order->count() == 0)
                <div class="service-card service-card-wide">
                    <div class="track-empty">
                        <i class="fas fa-box-open"></i>
                        <h4 class="fw-bold mb-2">অর্ডার খুঁজে পাওয়া যায়নি</h4>
                        <p>আপনার ইনভয়েস আইডি অথবা ফোন নম্বরটি সঠিক কিনা যাচাই করুন।</p>
                        <a href="{{ route('customer.order_track') }}" class="track-btn-primary mt-3">
                            <i class="fas fa-arrow-left"></i> আবার চেষ্টা করুন
                        </a>
                    </div>
                </div>
            @else
                <div class="service-card service-card-wide" style="padding: 0; background: transparent; box-shadow: none;">
                    @foreach($order as $value)
                        @php
                            $statusId = (int) $value->order_status;
                            $statusName = optional($value->status)->name ?? optional(\App\Models\OrderStatus::find($value->order_status))->name ?? 'Unknown';
                            $orderdetails = $value->orderdetails ?? \App\Models\OrderDetails::where('order_id', $value->id)->get();
                            $subtotal = 0;

                            $timelineSteps = [
                                ['label' => 'অর্ডার গ্রহণ', 'note' => 'আপনার অর্ডার গৃহীত হয়েছে'],
                                ['label' => 'প্রসেসিং', 'note' => 'অর্ডার প্রস্তুত করা হচ্ছে'],
                                ['label' => 'শিপিং', 'note' => 'ডেলিভারির পথে'],
                                ['label' => 'ডেলিভারি সম্পন্ন', 'note' => 'অর্ডার পৌঁছে গেছে'],
                            ];

                            $activeIndex = 0;
                            $allDone = false;
                            if ($statusId === 11) {
                                $activeIndex = -1;
                            } elseif ($statusId >= 6) {
                                $allDone = true;
                            } elseif (in_array($statusId, [3, 4, 5], true)) {
                                $activeIndex = 2;
                            } elseif ($statusId === 2) {
                                $activeIndex = 1;
                            }
                        @endphp

                        <div class="track-order-card">
                            <div class="track-order-head">
                                <div>
                                    <div class="track-order-id">Invoice #{{ $value->invoice_id }}</div>
                                    <div class="track-order-date">
                                        {{ date('d M, Y h:i A', strtotime($value->created_at)) }}
                                    </div>
                                </div>
                                <span class="track-status-badge">{{ $statusName }}</span>
                            </div>

                            <div class="track-order-body">
                                @if($statusId === 11)
                                    <div class="track-cancelled">
                                        <i class="fas fa-times-circle"></i>
                                        এই অর্ডারটি বাতিল করা হয়েছে
                                    </div>
                                @else
                                    <div class="track-timeline mb-4">
                                        @foreach($timelineSteps as $index => $step)
                                            @php
                                                $stepClass = '';
                                                if ($allDone || ($activeIndex >= 0 && $index < $activeIndex)) {
                                                    $stepClass = 'done';
                                                } elseif ($index === $activeIndex) {
                                                    $stepClass = 'active';
                                                }
                                            @endphp
                                            <div class="track-step {{ $stepClass }}">
                                                <div class="track-dot"></div>
                                                <div>
                                                    <strong>{{ $step['label'] }}</strong>
                                                    <small>{{ $step['note'] }}</small>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="track-order-meta">
                                    <div class="track-meta-item">
                                        <div class="track-meta-icon"><i class="fas fa-user"></i></div>
                                        <div>
                                            <h6>কাস্টমার</h6>
                                            <p>{{ $value->shipping->name ?? 'Guest' }}</p>
                                            <small>{{ $value->shipping->phone ?? $value->shipping_phone ?? '-' }}</small>
                                        </div>
                                    </div>
                                    <div class="track-meta-item">
                                        <div class="track-meta-icon"><i class="fas fa-map-marker-alt"></i></div>
                                        <div>
                                            <h6>ডেলিভারি ঠিকানা</h6>
                                            <p>{{ $value->shipping->area ?? 'General' }}</p>
                                            <small>{{ Str::limit($value->shipping->address ?? '-', 45) }}</small>
                                        </div>
                                    </div>
                                    <div class="track-meta-item">
                                        <div class="track-meta-icon"><i class="fas fa-credit-card"></i></div>
                                        <div>
                                            <h6>পেমেন্ট</h6>
                                            <p class="text-uppercase">{{ $value->payment->payment_method ?? 'COD' }}</p>
                                            <small class="{{ $value->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                                                স্ট্যাটাস: {{ ucfirst($value->payment_status ?? 'pending') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="track-items-title">অর্ডার আইটেম</div>

                                @foreach($orderdetails as $product)
                                    <div class="track-item-row">
                                        <div class="track-item-left">
                                            <div class="track-item-img">
                                                <img src="{{ asset($product->image->image ?? 'public/frontEnd/images/no-image.png') }}" alt="Product">
                                            </div>
                                            <div>
                                                <div class="track-item-name">{{ $product->product_name }}</div>
                                                <div class="track-item-meta">
                                                    @if($product->product_size)
                                                        <span>Size: {{ $product->product_size }}</span>
                                                    @endif
                                                    @if($product->product_color)
                                                        <span>Color: {{ $product->product_color }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="track-item-price">
                                            <strong>{{ number_format($product->sale_price * $product->qty, 0) }} ৳</strong>
                                            <small>{{ number_format($product->sale_price, 0) }} × {{ $product->qty }}</small>
                                        </div>
                                    </div>
                                    @php $subtotal += ($product->sale_price * $product->qty); @endphp
                                @endforeach

                                <div class="track-order-summary">
                                    <div class="track-summary-row">
                                        <span>সাবটোটাল</span>
                                        <span>{{ number_format($subtotal, 0) }} ৳</span>
                                    </div>
                                    <div class="track-summary-row">
                                        <span>ডেলিভারি চার্জ</span>
                                        <span>(+) {{ number_format($value->shipping_charge, 0) }} ৳</span>
                                    </div>
                                    @if($value->discount > 0)
                                        <div class="track-summary-row text-danger">
                                            <span>ডিসকাউন্ট</span>
                                            <span>(-) {{ number_format($value->discount, 0) }} ৳</span>
                                        </div>
                                    @endif
                                    <div class="track-summary-row total">
                                        <span>মোট</span>
                                        <span>{{ number_format($value->amount, 0) }} ৳</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="track-actions">
                    <a href="{{ route('customer.order_track') }}" class="track-btn-primary">
                        <i class="fas fa-search"></i> আরেকটি অর্ডার ট্র্যাক করুন
                    </a>
                    <button type="button" onclick="window.print()" class="track-btn-outline">
                        <i class="fas fa-print"></i> প্রিন্ট করুন
                    </button>
                </div>
            @endif

            <div class="service-help">
                <i class="fas fa-info-circle"></i>
                <p>সাহায্য লাগলে কল করুন:
                    <a href="tel:{{ $generalsetting->phone ?? '88012345678' }}">
                        {{ $contact->hotline ?? $generalsetting->phone }}
                    </a>
                </p>
            </div>
        </div>
    </section>

    @if($order->count() > 0)
    <div class="track-print-only">
        @foreach($order as $value)
            @php
                $statusId = (int) $value->order_status;
                $statusName = optional($value->status)->name ?? optional(\App\Models\OrderStatus::find($value->order_status))->name ?? 'Unknown';
                $orderdetails = $value->orderdetails ?? \App\Models\OrderDetails::where('order_id', $value->id)->get();
                $subtotal = 0;
                $paymentMethod = strtoupper($value->payment->payment_method ?? 'COD');
                $paymentStatus = ucfirst($value->payment_status ?? 'pending');

                $progressSteps = ['অর্ডার গ্রহণ', 'প্রসেসিং', 'শিপিং', 'ডেলিভারি'];
                $activeIndex = 0;
                $allDone = false;
                if ($statusId === 11) {
                    $activeIndex = -1;
                } elseif ($statusId >= 6) {
                    $allDone = true;
                } elseif (in_array($statusId, [3, 4, 5], true)) {
                    $activeIndex = 2;
                } elseif ($statusId === 2) {
                    $activeIndex = 1;
                }
            @endphp

            <div class="track-print-sheet">
                <div class="tp-header">
                    <div class="tp-brand">
                        <img src="{{ asset($generalsetting->dark_logo ?? $generalsetting->white_logo) }}" alt="{{ $generalsetting->name }}">
                        <div class="tp-brand-name">{{ $generalsetting->name }}</div>
                        <div class="tp-brand-meta">
                            @if(!empty($contact->address)){{ $contact->address }}<br>@endif
                            @if(!empty($contact->phone))ফোন: {{ $contact->phone }}@endif
                            @if(!empty($contact->email))<br>ইমেইল: {{ $contact->email }}@endif
                        </div>
                    </div>
                    <div class="tp-doc-title">
                        <h1>INVOICE</h1>
                        <p><strong>Invoice #:</strong> {{ $value->invoice_id }}</p>
                        <p><strong>Date:</strong> {{ date('d M, Y h:i A', strtotime($value->created_at)) }}</p>
                        <span class="tp-status-pill {{ $statusId === 11 ? 'is-cancelled' : '' }}">{{ $statusName }}</span>
                    </div>
                </div>

                @if($statusId === 11)
                    <div class="tp-cancel-banner">⚠ এই অর্ডারটি বাতিল করা হয়েছে</div>
                @else
                    <div class="tp-progress">
                        @foreach($progressSteps as $index => $label)
                            @php
                                $stepClass = '';
                                if ($allDone || ($activeIndex >= 0 && $index < $activeIndex)) {
                                    $stepClass = 'done';
                                } elseif ($index === $activeIndex) {
                                    $stepClass = 'active';
                                }
                            @endphp
                            <div class="tp-progress-step {{ $stepClass }}">
                                <div class="tp-progress-dot"></div>
                                <small>{{ $label }}</small>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="tp-info-grid">
                    <div class="tp-info-box">
                        <label>কাস্টমার</label>
                        <strong>{{ $value->shipping->name ?? 'Guest' }}</strong>
                        <span>{{ $value->shipping->phone ?? $value->shipping_phone ?? '-' }}</span>
                    </div>
                    <div class="tp-info-box">
                        <label>ডেলিভারি ঠিকানা</label>
                        <strong>{{ $value->shipping->area ?? 'General' }}</strong>
                        <span>{{ $value->shipping->address ?? '-' }}</span>
                    </div>
                    <div class="tp-info-box">
                        <label>পেমেন্ট তথ্য</label>
                        <strong>{{ $paymentMethod }}</strong>
                        <span>স্ট্যাটাস: {{ $paymentStatus }}</span>
                    </div>
                </div>

                <table class="tp-table">
                    <thead>
                        <tr>
                            <th style="width: 40px;">#</th>
                            <th>পণ্যের বিবরণ</th>
                            <th style="width: 70px;">দাম</th>
                            <th style="width: 50px;">পরিমাণ</th>
                            <th style="width: 90px;">মোট</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orderdetails as $index => $product)
                            @php $lineTotal = $product->sale_price * $product->qty; $subtotal += $lineTotal; @endphp
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="tp-product-name">{{ $product->product_name }}</div>
                                    @if($product->product_size || $product->product_color)
                                        <div class="tp-product-meta">
                                            @if($product->product_size)Size: {{ $product->product_size }}@endif
                                            @if($product->product_size && $product->product_color) · @endif
                                            @if($product->product_color)Color: {{ $product->product_color }}@endif
                                        </div>
                                    @endif
                                </td>
                                <td>{{ number_format($product->sale_price, 0) }} ৳</td>
                                <td>{{ $product->qty }}</td>
                                <td>{{ number_format($lineTotal, 0) }} ৳</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="tp-summary-wrap">
                    <div class="tp-summary">
                        <div class="tp-summary-row">
                            <span>সাবটোটাল</span>
                            <span>{{ number_format($subtotal, 0) }} ৳</span>
                        </div>
                        <div class="tp-summary-row">
                            <span>ডেলিভারি চার্জ</span>
                            <span>{{ number_format($value->shipping_charge, 0) }} ৳</span>
                        </div>
                        @if($value->discount > 0)
                            <div class="tp-summary-row discount">
                                <span>ডিসকাউন্ট</span>
                                <span>- {{ number_format($value->discount, 0) }} ৳</span>
                            </div>
                        @endif
                        <div class="tp-summary-row total">
                            <span>সর্বমোট</span>
                            <span>{{ number_format($value->amount, 0) }} ৳</span>
                        </div>
                    </div>
                </div>

                <div class="tp-footer">
                    <div>
                        <strong>Thank you for your order!</strong>
                        <span>এটি একটি কম্পিউটার জেনারেটেড ইনভয়েস।</span>
                    </div>
                    <div style="text-align: right;">
                        <strong>{{ $generalsetting->name }}</strong>
                        <span>Printed: {{ now()->format('d M, Y h:i A') }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</main>
@endsection
