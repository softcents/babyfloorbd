@extends('frontEnd.layouts.master')
@section('title','Invoice #{{ $order->invoice_id }}')
@section('content')

@php
    $payment        = \App\Models\Payment::where('order_id', $order->id)->orderBy('id','desc')->first();
    $gateway_status = $payment ? strtolower(trim($payment->payment_status)) : '';
    $payment_method = $payment ? strtolower(trim($payment->payment_method)) : strtolower(trim($order->payment_method ?? ''));
    $admin_status   = strtolower(trim($order->payment_status ?? ''));
    $order_status   = strtolower(trim($order->status ?? ''));
    $grand_total    = $order->amount;
    $paid_amount    = 0;

    if ($payment && !in_array($gateway_status, ['failed', 'cancel', 'cancelled', 'rejected'])) {
        $paid_amount = $payment->amount;
    }

    $is_cod             = in_array($payment_method, ['cod', 'cash', 'cash_on_delivery', 'hand cash']);
    $is_order_completed = in_array($order_status, ['completed', 'delivered']) || in_array($admin_status, ['completed', 'delivered']);

    if ($is_cod && !$is_order_completed && $paid_amount >= $grand_total) {
        $paid_amount = 0;
    }
    if ($is_order_completed) {
        $paid_amount = $grand_total;
    } elseif (($paid_amount == 0 || !$payment) && in_array($admin_status, ['paid', 'success', 'approved'])) {
        $paid_amount = $grand_total;
    }

    $due_amount = max(0, $grand_total - $paid_amount);
    $is_failed  = ($paid_amount == 0 && in_array($gateway_status, ['failed', 'cancel', 'cancelled']));

    $subtotal = ($order->amount + $order->discount) - $order->shipping_charge;
    $shipping = $order->shipping_charge;
    $discount = $order->discount;

    // Payment status label & color
    if ($paid_amount >= $grand_total) {
        $statusLabel = 'PAID'; $statusColor = '#16a34a'; $statusBg = '#dcfce7';
    } elseif ($is_failed) {
        $statusLabel = 'FAILED'; $statusColor = '#dc2626'; $statusBg = '#fee2e2';
    } elseif ($paid_amount > 0) {
        $statusLabel = 'PARTIAL'; $statusColor = '#d97706'; $statusBg = '#fef3c7';
    } else {
        $statusLabel = 'UNPAID'; $statusColor = '#dc2626'; $statusBg = '#fee2e2';
    }

    // Status colors computed above
@endphp

<style>
/* ====== GLOBAL ====== */
*, *::before, *::after { box-sizing: border-box; }
.inv-wrap {
    padding: 28px 0 48px;
    background: #f1f5f9;
    min-height: 80vh;
}
.inv-actions {
    max-width: 860px;
    margin: 0 auto 16px;
    padding: 0 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.inv-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #475569;
    text-decoration: none;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 16px;
    transition: all .2s;
}
.inv-back-btn:hover { color: var(--primary); border-color: var(--primary); }
.inv-print-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background: var(--primary);
    border: none;
    border-radius: 8px;
    padding: 9px 20px;
    cursor: pointer;
    transition: opacity .2s;
}
.inv-print-btn:hover { opacity: .88; }

/* ====== INVOICE CARD ====== */
.inv-card {
    max-width: 860px;
    margin: 0 auto;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 4px 32px rgba(0,0,0,.08);
    overflow: hidden;
}

/* ====== TOP STRIPE ====== */
.inv-stripe {
    background: var(--primary);
    padding: 28px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    flex-wrap: wrap;
}
.inv-stripe-logo img { height: 44px; object-fit: contain; filter: brightness(0) invert(1); }
.inv-stripe-logo .brand-name { color: #fff; font-size: 22px; font-weight: 800; letter-spacing: .5px; }
.inv-stripe-right { text-align: right; }
.inv-stripe-right h2 { font-size: 32px; font-weight: 800; color: #fff; margin: 0; letter-spacing: 2px; }
.inv-stripe-right p  { font-size: 13px; color: rgba(255,255,255,.8); margin: 4px 0 0; }

/* ====== META ROW ====== */
.inv-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 0;
    border-bottom: 1px solid #f0f4f8;
}
.inv-meta-item {
    padding: 14px 24px;
    border-right: 1px solid #f0f4f8;
}
.inv-meta-item:last-child { border-right: none; }
.inv-meta-label { font-size: 11px; font-weight: 600; color: #94a3b8; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 3px; }
.inv-meta-value { font-size: 14px; font-weight: 700; color: #1e293b; }
.inv-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: .5px;
    background: {{ $statusBg }};
    color: {{ $statusColor }};
}

/* ====== BILL INFO ====== */
.inv-bill {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    padding: 28px 36px;
    border-bottom: 1px solid #f0f4f8;
}
@media (max-width: 600px) {
    .inv-bill { grid-template-columns: 1fr; }
    .inv-stripe { padding: 20px 18px; }
    .inv-stripe-right h2 { font-size: 24px; }
    .inv-meta-item { padding: 12px 16px; }
    .inv-table-wrap { padding: 0 10px 20px; }
    .inv-totals { padding: 0 10px 24px; }
    .inv-bill { padding: 20px 16px; }
    .inv-footer { padding: 16px; }
}
.inv-bill-box {}
.inv-bill-title {
    font-size: 11px;
    font-weight: 700;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .8px;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 2px solid #f1f5f9;
}
.inv-bill-name { font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px; }
.inv-bill-info { font-size: 13px; color: #64748b; line-height: 1.7; }
.inv-bill-info span { display: block; }

/* ====== TABLE ====== */
.inv-table-wrap { padding: 0 36px 28px; overflow-x: auto; }
.inv-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}
.inv-table thead tr {
    background: #f8fafc;
    border-bottom: 2px solid #e2e8f0;
}
.inv-table thead th {
    padding: 12px 14px;
    text-align: left;
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .6px;
    white-space: nowrap;
}
.inv-table thead th:last-child { text-align: right; }
.inv-table tbody tr { border-bottom: 1px solid #f1f5f9; transition: background .15s; }
.inv-table tbody tr:last-child { border-bottom: none; }
.inv-table tbody tr:hover { background: #fafcff; }
.inv-table td { padding: 14px; color: #374151; vertical-align: middle; }
.inv-table td:last-child { text-align: right; font-weight: 600; color: #1e293b; }
.inv-table .prod-name { font-weight: 600; color: #1e293b; margin-bottom: 3px; }
.inv-table .prod-variant { font-size: 12px; color: #94a3b8; }
.inv-table .prod-variant span { display: inline-block; background: #f1f5f9; border-radius: 4px; padding: 1px 7px; margin-right: 4px; }
.inv-sl { color: #94a3b8; font-size: 12px; font-weight: 700; width: 36px; }

/* ====== TOTALS ====== */
.inv-totals {
    padding: 0 36px 32px;
    display: flex;
    justify-content: flex-end;
}
.inv-totals-box { width: 280px; }
.inv-totals-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 9px 0;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #475569;
}
.inv-totals-row:last-child { border-bottom: none; }
.inv-totals-row.grand {
    padding: 12px 0 0;
    font-size: 16px;
    font-weight: 800;
    color: #1e293b;
    border-top: 2px solid #e2e8f0;
    border-bottom: none;
    margin-top: 4px;
}
.inv-totals-row.paid-row { color: #16a34a; font-weight: 700; }
.inv-totals-row.due-row  { color: #dc2626; font-weight: 700; }

/* ====== FOOTER ====== */
.inv-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 18px 36px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}
.inv-footer-note { font-size: 12px; color: #94a3b8; font-style: italic; }
.inv-footer-tc { font-size: 12px; color: #64748b; }
.inv-footer-tc a { color: var(--primary); font-weight: 600; }

/* ====== NOTE BOX ====== */
.inv-note-box {
    margin: 0 36px 24px;
    background: #fff8f0;
    border-left: 3px solid #f59e0b;
    border-radius: 0 8px 8px 0;
    padding: 10px 16px;
    font-size: 13px;
    color: #92400e;
}

/* ====== PRINT ====== */
@media print {
    header, footer, .inv-actions, .no-print { display: none !important; }
    body { background: #fff !important; }
    .inv-wrap { background: #fff !important; padding: 0 !important; }
    .inv-card { box-shadow: none !important; border-radius: 0 !important; max-width: 100% !important; }
    @page { margin: 10mm; }
}
</style>

<div class="inv-wrap">
    {{-- Action Buttons --}}
    <div class="inv-actions no-print">
        <a href="{{ route('customer.orders') }}" class="inv-back-btn">
            <i class="fas fa-arrow-left"></i> অর্ডার লিস্টে ফিরুন
        </a>
        <button onclick="window.print()" class="inv-print-btn">
            <i class="fas fa-print"></i> প্রিন্ট করুন
        </button>
    </div>

    <div class="inv-card">

        {{-- ===== TOP STRIPE ===== --}}
        <div class="inv-stripe">
            <div class="inv-stripe-logo">
                @if($generalsetting && $generalsetting->white_logo)
                    <img src="{{ asset($generalsetting->white_logo) }}" alt="Logo">
                @else
                    <span class="brand-name">{{ $generalsetting->name ?? config('app.name') }}</span>
                @endif
            </div>
            <div class="inv-stripe-right">
                <h2>INVOICE</h2>
                <p>#{{ $order->invoice_id }}</p>
            </div>
        </div>

        {{-- ===== META ROW ===== --}}
        <div class="inv-meta">
            <div class="inv-meta-item">
                <div class="inv-meta-label">Invoice No</div>
                <div class="inv-meta-value">{{ $order->invoice_id }}</div>
            </div>
            <div class="inv-meta-item">
                <div class="inv-meta-label">তারিখ</div>
                <div class="inv-meta-value">{{ $order->created_at->format('d M, Y') }}</div>
            </div>
            <div class="inv-meta-item">
                <div class="inv-meta-label">পেমেন্ট পদ্ধতি</div>
                <div class="inv-meta-value" style="text-transform:uppercase;">{{ $payment_method ?: 'N/A' }}</div>
            </div>
            <div class="inv-meta-item">
                <div class="inv-meta-label">স্ট্যাটাস</div>
                <div class="inv-meta-value">
                    <span class="inv-status-badge">{{ $statusLabel }}</span>
                </div>
            </div>
        </div>

        {{-- ===== BILLING INFO ===== --}}
        <div class="inv-bill">
            <div class="inv-bill-box">
                <div class="inv-bill-title"><i class="fas fa-building" style="margin-right:5px;"></i> Invoice From</div>
                <div class="inv-bill-name">{{ $generalsetting->name ?? '' }}</div>
                <div class="inv-bill-info">
                    @if(!empty($contact->phone)) <span><i class="fas fa-phone-alt" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $contact->phone }}</span> @endif
                    @if(!empty($contact->email)) <span><i class="fas fa-envelope" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $contact->email }}</span> @endif
                    @if(!empty($contact->address)) <span><i class="fas fa-map-marker-alt" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $contact->address }}</span> @endif
                </div>
            </div>
            <div class="inv-bill-box">
                <div class="inv-bill-title"><i class="fas fa-user" style="margin-right:5px;"></i> Invoice To</div>
                <div class="inv-bill-name">{{ $order->shipping?->name ?? '' }}</div>
                <div class="inv-bill-info">
                    @if($order->shipping?->phone)    <span><i class="fas fa-phone-alt" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $order->shipping->phone }}</span> @endif
                    @if($order->shipping?->address)  <span><i class="fas fa-map-marker-alt" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $order->shipping->address }}</span> @endif
                    @if($order->shipping?->area)     <span><i class="fas fa-map-pin" style="width:14px;color:#94a3b8;margin-right:4px;"></i>{{ $order->shipping->area }}</span> @endif
                </div>
            </div>
        </div>

        {{-- ===== ORDER NOTE ===== --}}
        @if(!empty($order->order_note) || !empty($order->note))
        <div class="inv-note-box">
            <i class="fas fa-sticky-note" style="margin-right:6px;"></i>
            <strong>অর্ডার নোট:</strong> {{ $order->order_note ?? $order->note }}
        </div>
        @endif

        {{-- ===== PRODUCTS TABLE ===== --}}
        <div class="inv-table-wrap">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th class="inv-sl">#</th>
                        <th>পণ্যের নাম</th>
                        <th style="text-align:right;">একক মূল্য</th>
                        <th style="text-align:center;">পরিমাণ</th>
                        <th>মোট</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderdetails as $item)
                    @php
                        $sizeDisplay = $colorDisplay = null;
                        if ($item->size) {
                            $sizeDisplay = $item->size->sizeName ?? $item->size->size_name ?? $item->size->name ?? null;
                        } elseif ($item->product_size) {
                            $s = \App\Models\Size::find($item->product_size);
                            $sizeDisplay = $s ? ($s->sizeName ?? $s->size_name ?? null) : null;
                        }
                        if ($item->color) {
                            $colorDisplay = $item->color->getDisplayName() ?? $item->color->colorName ?? $item->color->color_name ?? $item->color->name ?? null;
                        } elseif ($item->product_color) {
                            $c = \App\Models\Color::find($item->product_color);
                            $colorDisplay = $c ? ($c->getDisplayName() ?? $c->colorName ?? $c->color_name ?? null) : null;
                        }
                    @endphp
                    <tr>
                        <td class="inv-sl">{{ $loop->iteration }}</td>
                        <td>
                            <div class="prod-name">{{ $item->product_name }}</div>
                            @if($sizeDisplay || $colorDisplay)
                            <div class="prod-variant">
                                @if($sizeDisplay) <span>Size: {{ $sizeDisplay }}</span> @endif
                                @if($colorDisplay) <span>Color: {{ $colorDisplay }}</span> @endif
                            </div>
                            @endif
                        </td>
                        <td style="text-align:right;color:#64748b;">৳{{ number_format($item->sale_price, 0) }}</td>
                        <td style="text-align:center;font-weight:600;">{{ $item->qty }}</td>
                        <td>৳{{ number_format($item->sale_price * $item->qty, 0) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ===== TOTALS ===== --}}
        <div class="inv-totals">
            <div class="inv-totals-box">
                <div class="inv-totals-row">
                    <span>সাবটোটাল</span>
                    <span>৳{{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="inv-totals-row">
                    <span>ডেলিভারি চার্জ</span>
                    <span>+৳{{ number_format($shipping, 2) }}</span>
                </div>
                @if($discount > 0)
                <div class="inv-totals-row" style="color:#16a34a;">
                    <span>ছাড় (Discount)</span>
                    <span>-৳{{ number_format($discount, 2) }}</span>
                </div>
                @endif
                <div class="inv-totals-row grand">
                    <span>সর্বমোট</span>
                    <span>৳{{ number_format($grand_total, 2) }}</span>
                </div>

                @if($paid_amount > 0 && $due_amount > 0)
                    <div class="inv-totals-row paid-row">
                        <span><i class="fas fa-check-circle" style="margin-right:4px;"></i> অগ্রিম পরিশোধ</span>
                        <span>৳{{ number_format($paid_amount, 2) }}</span>
                    </div>
                    <div class="inv-totals-row due-row">
                        <span><i class="fas fa-exclamation-circle" style="margin-right:4px;"></i> বাকি পরিমাণ</span>
                        <span>৳{{ number_format($due_amount, 2) }}</span>
                    </div>
                @elseif($paid_amount >= $grand_total)
                    <div class="inv-totals-row paid-row">
                        <span><i class="fas fa-check-circle" style="margin-right:4px;"></i> পরিশোধিত</span>
                        <span>৳{{ number_format($paid_amount, 2) }}</span>
                    </div>
                @else
                    <div class="inv-totals-row due-row">
                        <span><i class="fas fa-exclamation-circle" style="margin-right:4px;"></i> বাকি পরিমাণ</span>
                        <span>৳{{ number_format($grand_total, 2) }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== FOOTER ===== --}}
        <div class="inv-footer">
            <div class="inv-footer-note">* This is a computer generated invoice.</div>
            <div class="inv-footer-tc">
                <a href="{{ route('page', ['slug' => 'terms-condition']) }}">Terms &amp; Conditions</a>
            </div>
        </div>

    </div>
</div>

@endsection
