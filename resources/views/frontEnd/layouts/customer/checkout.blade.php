@extends('frontEnd.layouts.master')
@section('title', 'চেকআউট')
@php $generalsetting = \App\Models\GeneralSetting::first(); @endphp

@push('css')
<style>
/* ═══════════════════════════════════════════════════════
   CHECKOUT — Clean · Easy · Responsive
═══════════════════════════════════════════════════════ */
.ck-page {
    background: linear-gradient(180deg, #f6f7fb 0%, #eef1f6 100%);
    padding-bottom: 100px;
    min-height: 60vh;
}

/* Top bar */
.ck-topbar {
    background: #fff;
    border-bottom: 1px solid #e9edf2;
    padding: 12px 0;
    margin-bottom: 20px;
    box-shadow: 0 1px 0 rgba(0,0,0,.03);
}
.ck-topbar-inner {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}
.ck-logo {
    font-size: 18px;
    font-weight: 800;
    color: var(--primary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ck-logo img { height: 34px; object-fit: contain; }

.ck-steps {
    display: flex;
    align-items: center;
    gap: 0;
}
.ck-step {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #b0b8c4;
}
.ck-step-num {
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
    color: #b0b8c4;
}
.ck-step.done .ck-step-num { background: #22c55e; border-color: #22c55e; color: #fff; }
.ck-step.active .ck-step-num { background: var(--primary); border-color: var(--primary); color: #fff; }
.ck-step.active { color: var(--primary); }
.ck-step.done { color: #22c55e; }
.ck-step-line { width: 36px; height: 2px; background: #dde3ea; margin: 0 6px; }
.ck-step-line.done { background: #22c55e; }

.ck-breadcrumb { font-size: 12px; color: #9aa3af; }
.ck-breadcrumb a { color: #9aa3af; text-decoration: none; }
.ck-breadcrumb a:hover { color: var(--primary); }
.ck-breadcrumb span { margin: 0 4px; }

/* Layout */
.ck-row {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 20px;
}
.ck-left {
    flex: 1 1 100%;
    min-width: 0;
}
.ck-right {
    flex: 1 1 100%;
    min-width: 0;
}
.ck-sticky { position: sticky; top: 90px; z-index: 20; }

@media (max-width: 991px) {
    .ck-row {
        flex-direction: column-reverse;
        gap: 16px;
        align-items: stretch;
    }
    .ck-left,
    .ck-right,
    .ck-sticky,
    .ck-right .ck-card {
        width: 100%;
        max-width: 100%;
    }
}

@media (min-width: 992px) {
    .ck-row {
        flex-direction: row;
        flex-wrap: nowrap;
        gap: 28px;
        align-items: flex-start;
    }
    .ck-left {
        flex: 1 1 58%;
        max-width: 58%;
        order: 1;
    }
    .ck-right {
        flex: 0 0 38%;
        max-width: 38%;
        order: 2;
        margin-bottom: 0;
        align-self: flex-start;
        position: sticky;
        top: 90px;
        z-index: 20;
        max-height: calc(100vh - 100px);
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
    }
    .ck-right .ck-card { overflow: visible; }
    .ck-sticky {
        position: static;
        top: auto;
        max-height: none;
        overflow: visible;
    }
}

/* Cards */
.ck-card {
    background: #fff;
    border-radius: 16px;
    border: 1px solid #e8ecf1;
    box-shadow: 0 4px 24px rgba(15, 23, 42, .04);
    margin-bottom: 16px;
    overflow: hidden;
}
.ck-card-head {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: linear-gradient(180deg, #fafbfd 0%, #fff 100%);
    border-bottom: 1px solid #f0f3f7;
}
.ck-step-badge {
    width: 36px;
    height: 36px;
    border-radius: 12px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    font-weight: 800;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(var(--primary-rgb), .2);
}
.ck-card-title { font-size: 16px; font-weight: 700; color: #1f2937; margin: 0; }
.ck-card-subtitle { font-size: 12px; color: #9aa3af; margin: 2px 0 0; }
.ck-card-body { padding: 20px; }

/* Form */
.ck-form-group { margin-bottom: 14px; }

/* বিভাগ · জেলা · উপজেলা — এক লাইনে */
.ck-location-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
    width: 100%;
    margin-bottom: 14px;
}
.ck-location-col { min-width: 0; }
.ck-location-col .ck-form-group { margin-bottom: 0; }

@media (max-width: 767px) {
    .ck-location-row {
        grid-template-columns: 1fr;
        gap: 14px;
    }
    .ck-location-col .ck-form-group { margin-bottom: 0; }
}
.ck-label {
    font-size: 13px;
    font-weight: 600;
    color: #4b5563;
    margin-bottom: 6px;
    display: block;
}
.ck-input {
    width: 100%;
    min-height: 48px;
    background: #f9fafb;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    padding: 0 14px;
    font-size: 15px;
    color: #1f2937;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
}
.ck-input:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(var(--primary-rgb), .08);
}
.ck-input:disabled { background: #f3f4f6; color: #9ca3af; }
textarea.ck-input { min-height: 88px; padding: 12px 14px; resize: vertical; line-height: 1.5; }
select.ck-input {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23999' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 14px center;
    padding-right: 38px;
}
.ck-input-icon { position: relative; }
.ck-input-icon .ck-input { padding-left: 44px; }
.ck-input-icon i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    font-size: 15px;
    pointer-events: none;
}
.ck-input-icon textarea.ck-input + i,
.ck-input-icon i.ck-icon-top { top: 16px; transform: none; }

/* Payment */
.ck-pay-grid { display: grid; gap: 10px; }
.ck-pay-option {
    display: flex;
    align-items: center;
    gap: 0;
    border: 1.5px solid #e8ecf1;
    border-radius: 14px;
    cursor: pointer;
    background: #fafbfc;
    overflow: hidden;
    transition: all .2s;
    min-height: 68px;
}
.ck-pay-option:hover { border-color: #d1d5db; background: #fff; }
.ck-pay-option:has(input:checked) {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 1px var(--primary), 0 6px 20px rgba(var(--primary-rgb), .08);
}
.ck-pay-option input[type="radio"] { position: absolute; opacity: 0; pointer-events: none; }
.ck-pay-left {
    width: 68px;
    min-height: 68px;
    flex-shrink: 0;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    border-right: 1px solid #e8ecf1;
}
.ck-pay-option:has(input:checked) .ck-pay-left { background: #fff5f6; }
.ck-pay-logo { width: 38px; height: 38px; object-fit: contain; }
.ck-pay-body { padding: 12px 14px; flex: 1; min-width: 0; }
.ck-pay-body strong { display: block; font-size: 14px; font-weight: 700; color: #1f2937; margin-bottom: 2px; }
.ck-pay-body small { font-size: 12px; color: #9ca3af; line-height: 1.4; }
.ck-pay-radio {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #d1d5db;
    margin-right: 14px;
    flex-shrink: 0;
    position: relative;
}
.ck-pay-option:has(input:checked) .ck-pay-radio { border-color: var(--primary); background: var(--primary); }
.ck-pay-option:has(input:checked) .ck-pay-radio::after {
    content: '';
    position: absolute;
    top: 50%; left: 50%;
    transform: translate(-50%, -50%);
    width: 8px; height: 8px;
    background: #fff;
    border-radius: 50%;
}

.ck-manual-box {
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 12px;
    padding: 16px;
    margin-top: 8px;
}
.ck-manual-box .ck-manual-title {
    font-size: 13px;
    font-weight: 700;
    color: #92400e;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Summary */
.ck-summary-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 18px;
    border-bottom: 1px solid #f0f3f7;
    background: linear-gradient(180deg, #fafbfd 0%, #fff 100%);
}
.ck-summary-title h6 { margin: 0; font-size: 15px; font-weight: 700; color: #1f2937; }
.ck-count-badge {
    background: var(--primary);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 20px;
}

.ck-products { max-height: 300px; overflow-y: auto; padding: 4px 16px; }
.ck-products::-webkit-scrollbar { width: 4px; }
.ck-products::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }

.ck-pro-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px dashed #eef1f5;
    position: relative;
    align-items: flex-start;
}
.ck-pro-item:last-child { border-bottom: none; }
.ck-pro-thumb-wrap { position: relative; flex-shrink: 0; }
.ck-pro-thumb {
    width: 64px;
    height: 64px;
    border-radius: 12px;
    border: 1px solid #eef1f5;
    object-fit: cover;
}
.ck-pro-qty-badge {
    position: absolute;
    top: -6px; right: -6px;
    background: var(--primary);
    color: #fff;
    min-width: 20px;
    height: 20px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 2px solid #fff;
    padding: 0 4px;
}
.ck-pro-info { flex: 1; min-width: 0; padding-right: 22px; }
.ck-pro-info .name {
    font-size: 13px;
    font-weight: 600;
    color: #1f2937;
    display: block;
    margin-bottom: 3px;
    line-height: 1.4;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.ck-pro-info .name:hover { color: var(--primary); }
.ck-pro-info .variant { font-size: 11px; color: #9ca3af; margin-bottom: 8px; }
.ck-pro-info .price-qty {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    flex-wrap: wrap;
}
.ck-pro-info .item-price { font-size: 14px; font-weight: 800; color: var(--primary); white-space: nowrap; }
.ck-pro-qty-ctrl {
    display: flex;
    align-items: center;
    background: #f3f4f6;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}
.ck-qty-btn {
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #4b5563;
    transition: .15s;
}
.ck-qty-btn:hover { background: var(--primary); color: #fff; }
.ck-qty-num { min-width: 28px; text-align: center; font-size: 13px; font-weight: 700; color: #1f2937; }
.ck-pro-remove {
    position: absolute;
    top: 10px; right: 0;
    width: 28px; height: 28px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #d1d5db;
    cursor: pointer;
    transition: .2s;
}
.ck-pro-remove:hover { color: #ef4444; background: #fef2f2; }

/* Coupon */
.ck-coupon { padding: 14px 16px; background: #f9fafb; border-top: 1px solid #f0f3f7; }
.ck-coupon-row {
    display: flex;
    min-height: 46px;
    border: 1.5px solid #e5e7eb;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
}
.ck-coupon-row:focus-within { border-color: var(--primary); }
.ck-coupon-icon { width: 44px; display: flex; align-items: center; justify-content: center; color: #9ca3af; flex-shrink: 0; }
.ck-coupon-input { flex: 1; border: none; outline: none; font-size: 14px; background: transparent; padding: 0 8px; min-width: 0; }
.ck-coupon-btn {
    background: var(--primary);
    color: #fff;
    border: none;
    padding: 0 16px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    white-space: nowrap;
}
.ck-coupon-applied {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
    background: #f0fdf4;
    border: 1.5px solid #86efac;
    border-radius: 12px;
    padding: 10px 12px;
    font-size: 13px;
    font-weight: 600;
    color: #15803d;
}
.ck-coupon-remove { color: #ef4444; text-decoration: none; font-size: 11px; font-weight: 700; white-space: nowrap; }

/* Totals */
.ck-totals { padding: 14px 16px; }
.ck-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 8px;
    gap: 12px;
}
.ck-total-row .val { font-weight: 600; color: #374151; white-space: nowrap; }
.ck-total-row.discount .val { color: #16a34a; }
.ck-total-row.grand {
    border-top: 2px dashed #e8ecf1;
    padding-top: 12px;
    margin-top: 4px;
    margin-bottom: 0;
    font-size: 18px;
    font-weight: 800;
}
.ck-total-row.grand .label { color: #1f2937; }
.ck-total-row.grand .val { color: var(--primary); font-size: 20px; }

.ck-advance-box {
    background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    border: 1px solid #86efac;
    border-radius: 12px;
    padding: 12px;
    margin-top: 12px;
}
.ck-advance-row { display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px; gap: 8px; }
.ck-advance-row:last-child { margin-bottom: 0; }

/* Submit */
.ck-submit-wrap { padding: 14px 16px 16px; }
.ck-place-order {
    width: 100%;
    min-height: 52px;
    padding: 14px 20px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #fff;
    font-size: 16px;
    font-weight: 800;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 8px 24px rgba(var(--primary-rgb), .25);
    transition: transform .2s, box-shadow .2s;
}
.ck-place-order:hover { transform: translateY(-1px); box-shadow: 0 12px 28px rgba(var(--primary-rgb), .3); }

.ck-trust {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 12px 16px;
    border-top: 1px solid #f5f6f8;
}
.ck-trust-item { display: flex; align-items: center; gap: 5px; font-size: 11px; color: #9ca3af; font-weight: 500; }
.ck-trust-item i { color: #22c55e; }

.ck-advance-warning {
    background: #fffbeb;
    border: 1.5px solid #fde68a;
    border-radius: 12px;
    padding: 12px 14px;
    margin-bottom: 14px;
    display: flex;
    align-items: flex-start;
    gap: 10px;
}
.ck-advance-warning i { color: #f59e0b; margin-top: 2px; }
.ck-advance-warning p { margin: 0; font-size: 13px; color: #92400e; line-height: 1.5; }

#payment-error {
    font-size: 13px;
    padding: 10px 12px;
    background: #fef2f2;
    border-radius: 10px;
    border: 1px solid #fecaca;
    color: #dc2626;
}

/* Mobile sticky bottom bar */
.ck-mobile-bar {
    display: none;
    position: fixed;
    left: 0; right: 0; bottom: 0;
    z-index: 500;
    background: #fff;
    border-top: 1px solid #e8ecf1;
    padding: 10px 14px calc(10px + env(safe-area-inset-bottom, 0px));
    box-shadow: 0 -8px 30px rgba(15,23,42,.08);
    align-items: center;
    gap: 12px;
}
.ck-mobile-bar-total { flex: 1; min-width: 0; }
.ck-mobile-bar-total small { display: block; font-size: 11px; color: #9ca3af; margin-bottom: 2px; }
.ck-mobile-bar-total strong { font-size: 18px; font-weight: 800; color: var(--primary); }
.ck-mobile-bar-btn {
    flex-shrink: 0;
    min-height: 48px;
    padding: 0 22px;
    border: none;
    border-radius: 12px;
    background: var(--primary);
    color: #fff;
    font-size: 14px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 4px 14px rgba(var(--primary-rgb), .25);
}

/* Responsive */
@media (max-width: 991px) {
    .ck-page { padding-bottom: calc(88px + env(safe-area-inset-bottom, 0px)); overflow-x: hidden; }
    .ck-right {
        position: static;
        max-height: none;
        overflow: visible;
        width: 100%;
        max-width: 100%;
    }
    .ck-left {
        width: 100%;
        max-width: 100%;
    }
    .ck-sticky { position: static; width: 100%; }
    .ck-card { border-radius: 14px; width: 100%; max-width: 100%; box-sizing: border-box; }
    .ck-mobile-btn { display: none !important; }
    .ck-desktop-btn { display: none !important; }
    .ck-mobile-bar { display: flex; }
    .ck-steps { display: none; }
    .ck-breadcrumb { display: none; }
    .ck-topbar { margin-bottom: 14px; }
    .ck-products { max-height: 220px; overflow-x: hidden; }
    .ck-pro-item { max-width: 100%; }
    .ck-coupon-row { min-width: 0; }
}

@media (min-width: 992px) {
    .ck-mobile-btn { display: none !important; }
    .ck-desktop-btn { display: block !important; }
    .ck-mobile-bar { display: none !important; }
}

@media (max-width: 575px) {
    .ck-topbar-inner { justify-content: center; }
    .ck-card-body { padding: 16px; }
    .ck-card-head { padding: 14px 16px; }
    .ck-input { font-size: 16px; min-height: 50px; }
    .ck-pay-left { width: 58px; min-height: 64px; }
    .ck-pay-body { padding: 10px 12px; }
    .ck-pay-body strong { font-size: 13px; }
    .ck-pay-radio { margin-right: 10px; }
    .ck-pro-thumb { width: 56px; height: 56px; }
    .ck-total-row.grand .val { font-size: 18px; }
}

/* OTP verification popup */
.ck-otp-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.62);
    z-index: 10050;
    backdrop-filter: blur(4px);
}
.ck-otp-modal {
    display: none;
    position: fixed;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%) scale(0.92);
    z-index: 10051;
    width: 94%;
    max-width: 420px;
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 28px 70px rgba(0, 0, 0, 0.28);
    opacity: 0;
    transition: transform 0.22s cubic-bezier(0.34, 1.56, 0.64, 1), opacity 0.22s ease;
}
.ck-otp-overlay.is-open,
.ck-otp-modal.is-open { display: block; }
.ck-otp-modal.is-open {
    transform: translate(-50%, -50%) scale(1);
    opacity: 1;
}
.ck-otp-head {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 16px 18px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: #fff;
}
.ck-otp-head h5 {
    margin: 0;
    font-size: 17px;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ck-otp-body { padding: 20px 18px 18px; }
.ck-otp-desc {
    margin: 0 0 14px;
    font-size: 13px;
    color: #6b7280;
    line-height: 1.55;
}
.ck-otp-label {
    display: block;
    margin-bottom: 8px;
    font-size: 13px;
    font-weight: 700;
    color: #374151;
}
.ck-otp-input {
    width: 100%;
    min-height: 54px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 22px;
    font-weight: 700;
    text-align: center;
    letter-spacing: 0.35em;
    color: #111827;
    background: #f9fafb;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.ck-otp-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.15);
    background: #fff;
}
.ck-otp-error {
    margin: 0 0 12px;
    padding: 10px 12px;
    border-radius: 10px;
    background: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    font-size: 13px;
}
.ck-otp-status {
    display: none;
    margin: 0 0 12px;
    padding: 10px 12px;
    border-radius: 10px;
    font-size: 13px;
    line-height: 1.5;
}
.ck-otp-status.is-show { display: block; }
.ck-otp-status.success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; }
.ck-otp-status.error { background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; }
.ck-otp-status.info { background: #eff6ff; border: 1px solid #bfdbfe; color: #1d4ed8; }
.ck-otp-btn-resend:disabled { opacity: .55; cursor: not-allowed; }
.ck-otp-actions {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 18px;
    justify-content: space-between;
    align-items: center;
}
.ck-otp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    border: none;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    transition: transform 0.15s ease, opacity 0.15s ease;
}
.ck-otp-btn:hover { opacity: 0.92; }
.ck-otp-btn:active { transform: scale(0.98); }
.ck-otp-btn-resend {
    padding: 10px 14px;
    background: #fff;
    color: #4b5563;
    border: 1px solid #d1d5db;
}
.ck-otp-btn-confirm {
    padding: 12px 20px;
    background: #16a34a;
    color: #fff;
    box-shadow: 0 4px 14px rgba(22, 163, 74, 0.28);
}
body.ck-otp-lock { overflow: hidden; }
body.ck-otp-lock .ck-mobile-bar { display: none !important; }
</style>
@endpush

@section('content')
@php
    $subtotal = Cart::instance('shopping')->subtotal();
    $subtotal = str_replace(',', '', $subtotal);
    $subtotal = str_replace('.00', '', $subtotal);
    $subtotal = (float) $subtotal;

    $requires_shipping = false;
    foreach (Cart::instance('shopping')->content() as $item) {
        $product = \App\Models\Product::find($item->id);
        if ($product && $product->is_digital != 1) { $requires_shipping = true; break; }
    }

    $hasAllFreeDelivery = \App\Http\Controllers\Frontend\ShoppingController::hasAllFreeDeliveryProducts();
    if ($requires_shipping && !$hasAllFreeDelivery) {
        $shipping = Session::get('shipping') ? Session::get('shipping') : 0;
    } else {
        $shipping = 0; Session::put('shipping', 0);
    }

    $discount    = Session::get('discount', 0);
    $grand_total = $subtotal + $shipping - $discount;

    $cartItemsForJs = [];
    $hasDigital = false;
    foreach (Cart::instance('shopping')->content() as $item) {
        $p = \App\Models\Product::find($item->id);
        if ($p && $p->is_digital == 1) { $hasDigital = true; }
        $cartItemsForJs[] = [
            'id'               => $item->id,
            'name'             => $item->name,
            'qty'              => $item->qty,
            'price'            => (float) $item->price,
            'image'            => asset($item->options->image ?? ''),
            'link'             => isset($item->options->slug) ? url('/product/'.$item->options->slug) : '#',
            'is_digital'       => (int) ($p->is_digital ?? 0),
            'free_delivery'    => (int) ($p->free_delivery ?? 0),
            'color_id'         => $item->options->color_id ?? null,
            'size_id'          => $item->options->size_id ?? null,
            'variant_price_id' => $item->options->variant_price_id ?? null,
        ];
    }

    $advance_amount = \App\Http\Controllers\Frontend\ShoppingController::getCartAdvanceAmount();
    $hasAdvance     = $advance_amount > 0;
    $payable_now    = $hasAdvance ? $advance_amount : $grand_total;
    $due_amount     = $hasAdvance ? ($grand_total - $advance_amount) : 0;

    $__gsCheckoutOtp          = \App\Models\GeneralSetting::where('status', 1)->first();
    $__custCheckoutOtpPending = session('chkotp_customer_pending');
    $__checkoutOtpDraft       = session('chkotp_customer_draft', []);
    // ডিজিটাল প্রোডাক্ট থাকলে OTP modal দেখানো হবে না
    $__showCheckoutOtpModal   = $__gsCheckoutOtp && ($__gsCheckoutOtp->checkout_otp_enabled ?? 0) == 1 && $__custCheckoutOtpPending && !$hasDigital;
    $__otpFieldValue          = session('checkout_otp_cleared') ? '' : old('checkout_otp');
    $__otpResendRetryAfter    = 0;
    if ($__custCheckoutOtpPending) {
        $sentAt = (int) session('chkotp_customer_sent_at', 0);
        if ($sentAt > 0) {
            $__otpResendRetryAfter = max(0, 30 - (time() - $sentAt));
        }
    }
@endphp

<div class="ck-page">

    {{-- Top Bar --}}
    <div class="ck-topbar">
        <div class="container">
            <div class="ck-topbar-inner">
                {{-- Step indicator --}}
                <div class="ck-steps">
                    <div class="ck-step done">
                        <div class="ck-step-num"><i class="fas fa-check" style="font-size:10px;"></i></div>
                        <span>কার্ট</span>
                    </div>
                    <div class="ck-step-line done"></div>
                    <div class="ck-step active">
                        <div class="ck-step-num">2</div>
                        <span>চেকআউট</span>
                    </div>
                    <div class="ck-step-line"></div>
                    <div class="ck-step">
                        <div class="ck-step-num">3</div>
                        <span>কনফার্মেশন</span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="container">
        <form id="checkout-form" action="{{ route('customer.ordersave') }}" method="POST" data-parsley-validate="">
            @csrf
            <input type="hidden" name="checkout_otp" id="checkout_otp_hidden" value="{{ $__otpFieldValue }}">
            @if(!empty($__showCheckoutOtpModal) && !empty($__checkoutOtpDraft))
                @foreach(['name','phone','address','division_id','district_id','upazila_id','payment_method','manual_trx_id','manual_sender_number','order_note'] as $__draftKey)
                    @if(!empty($__checkoutOtpDraft[$__draftKey]))
                        <input type="hidden" name="{{ $__draftKey }}" value="{{ $__checkoutOtpDraft[$__draftKey] }}">
                    @endif
                @endforeach
            @endif
            <input type="hidden" name="traffic_source"   id="inp_ts"  value="{{ old('traffic_source',  session('order_traffic_source',  'direct')) }}">
            <input type="hidden" name="traffic_referrer" id="inp_tsr" value="{{ old('traffic_referrer', session('order_traffic_referrer', '')) }}">
            <script>
            try {
                var elTs=document.getElementById('inp_ts'),elTsr=document.getElementById('inp_tsr');
                var ts=sessionStorage.getItem('_ts'),tsr=sessionStorage.getItem('_tsr');
                if(ts!==null&&ts!==''){elTs.value=ts;}else if(elTs.value&&elTs.value!=='direct'){sessionStorage.setItem('_ts',elTs.value);}
                if(tsr!==null&&tsr!==''){elTsr.value=tsr;}else if(elTsr.value){sessionStorage.setItem('_tsr',elTsr.value);}
            }catch(e){}
            </script>

            <div class="ck-row">

                {{-- ════════════ LEFT — SHIPPING + PAYMENT ════════════ --}}
                <div class="ck-left">

                    {{-- STEP 1: SHIPPING INFO --}}
                    <div class="ck-card">
                        <div class="ck-card-head">
                            <div class="ck-step-badge">1</div>
                            <div>
                                <p class="ck-card-title">ডেলিভারি তথ্য</p>
                                <p class="ck-card-subtitle">আপনার নাম, ফোন ও ঠিকানা দিন</p>
                            </div>
                        </div>
                        <div class="ck-card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="ck-form-group">
                                        <label class="ck-label">পূর্ণ নাম *</label>
                                        <div class="ck-input-icon">
                                            <i class="far fa-user"></i>
                                            <input type="text" name="name" class="ck-input"
                                                value="{{ Auth::guard('customer')->user()->name ?? old('name') }}"
                                                placeholder="আপনার সম্পূর্ণ নাম" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="ck-form-group">
                                        <label class="ck-label">মোবাইল নম্বর *</label>
                                        <div class="ck-input-icon">
                                            <i class="fas fa-mobile-alt"></i>
                                            <input type="text" name="phone" class="ck-input"
                                                minlength="11" maxlength="11" pattern="0[0-9]+"
                                                value="{{ Auth::guard('customer')->user()->phone ?? old('phone') }}"
                                                placeholder="017xxxxxxxx" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="ck-form-group">
                                        <label class="ck-label">সম্পূর্ণ ঠিকানা *</label>
                                        <div class="ck-input-icon">
                                            <i class="fas fa-map-marker-alt"></i>
                                            <input type="text" name="address" class="ck-input"
                                                value="{{ Auth::guard('customer')->user()->address ?? old('address') }}"
                                                placeholder="বাসা নং, রোড, এলাকা" required>
                                        </div>
                                    </div>
                                </div>

                                @if($requires_shipping)
                                <div class="ck-location-row">
                                    <div class="ck-location-col">
                                        <div class="ck-form-group">
                                            <label class="ck-label">বিভাগ *</label>
                                            <select name="division_id" id="checkout_division" class="ck-input" required>
                                                <option value="">বিভাগ বেছে নিন</option>
                                                @foreach(($divisions ?? collect()) as $d)
                                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ck-location-col">
                                        <div class="ck-form-group">
                                            <label class="ck-label">জেলা *</label>
                                            <select name="district_id" id="checkout_district" class="ck-input" required disabled>
                                                <option value="">আগে বিভাগ নিন</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="ck-location-col">
                                        <div class="ck-form-group">
                                            <label class="ck-label">উপজেলা *</label>
                                            <select name="upazila_id" id="checkout_upazila" class="ck-input" required disabled>
                                                <option value="">আগে জেলা নিন</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-12">
                                    <div class="ck-form-group mb-0">
                                        <label class="ck-label">শিপিং</label>
                                        <input class="ck-input" value="ডিজিটাল / ফ্রি শিপিং — লোকেশন লাগবে না" readonly disabled>
                                        <input type="hidden" name="division_id" value="">
                                        <input type="hidden" name="district_id" value="">
                                        <input type="hidden" name="upazila_id"  value="">
                                    </div>
                                </div>
                                @endif

                                <div class="col-12">
                                    <div class="ck-form-group mb-0">
                                        <label class="ck-label">অর্ডার নোট <span style="font-weight:400;text-transform:none;color:#aaa;">(ঐচ্ছিক)</span></label>
                                        <div class="ck-input-icon">
                                            <i class="far fa-comment-alt ck-icon-top"></i>
                                            <textarea name="order_note" id="order_note" class="ck-input" rows="2"
                                                placeholder="ডেলিভারি সম্পর্কে কিছু বলতে চাইলে লিখুন...">{{ $order_note ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- STEP 2: PAYMENT METHOD --}}
                    <div class="ck-card">
                        <div class="ck-card-head">
                            <div class="ck-step-badge">2</div>
                            <div>
                                <p class="ck-card-title">পেমেন্ট পদ্ধতি</p>
                                <p class="ck-card-subtitle">আপনার পছন্দের পেমেন্ট বেছে নিন</p>
                            </div>
                        </div>
                        <div class="ck-card-body">

                            @if($hasDigital)
                            <div class="ck-advance-warning" style="background:#eff6ff;border-color:#bfdbfe;">
                                <i class="fas fa-lock" style="color:#2563eb;"></i>
                                <p style="color:#1e40af;"><strong>ডিজিটাল পণ্য — অনলাইন পেমেন্ট আবশ্যক!</strong><br>
                                ডিজিটাল পণ্যের জন্য অবশ্যই অনলাইন পেমেন্ট করতে হবে। পেমেন্ট সম্পন্ন হলে ডাউনলোড লিঙ্ক পাবেন। OTP ছাড়াই সরাসরি পেমেন্ট গেটওয়েতে যাবেন।</p>
                            </div>
                            @endif

                            @if($hasAdvance)
                            <div class="ck-advance-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <p><strong>অগ্রিম পেমেন্ট প্রয়োজন!</strong><br>
                                এই অর্ডারে <b>৳ {{ number_format($advance_amount, 2) }}</b> অগ্রিম পেমেন্ট করতে হবে।</p>
                            </div>
                            @endif

                            <div class="ck-pay-grid">

                            {{-- COD --}}
                            @if(!$hasDigital && !$hasAdvance)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="cod" checked required>
                                <div class="ck-pay-left" style="background:#f0fdf4;">
                                    <i class="fas fa-truck" style="font-size:22px;color:#16a34a;"></i>
                                </div>
                                <div class="ck-pay-body">
                                    <strong>ক্যাশ অন ডেলিভারি</strong>
                                    <small>পণ্য হাতে পেয়ে মূল্য পরিশোধ করুন</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endif

                            {{-- bKash --}}
                            @if($bkash_gateway)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="bkash" required>
                                <div class="ck-pay-left">
                                    <img src="{{ asset('public/frontEnd/images/bkash.svg') }}" class="ck-pay-logo" alt="bKash">
                                </div>
                                <div class="ck-pay-body">
                                    <strong>bKash পেমেন্ট</strong>
                                    <small>বিকাশ অ্যাপ বা গেটওয়ে দ্বারা পেমেন্ট</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endif

                            {{-- ShurjoPay --}}
                            @if($shurjopay_gateway)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="shurjopay" required>
                                <div class="ck-pay-left">
                                    <img src="{{ asset('public/frontEnd/images/shurjoPay.png') }}" class="ck-pay-logo" alt="ShurjoPay">
                                </div>
                                <div class="ck-pay-body">
                                    <strong>ShurjoPay</strong>
                                    <small>Card / Mobile Banking</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endif

                            {{-- UddoktaPay --}}
                            @if($uddoktapay_gateway)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="uddoktapay" required>
                                <div class="ck-pay-left">
                                    <img src="{{ asset('public/frontEnd/images/uddokta.png') }}" class="ck-pay-logo" alt="UddoktaPay">
                                </div>
                                <div class="ck-pay-body">
                                    <strong>UddoktaPay</strong>
                                    <small>মোবাইল ব্যাংকিং পেমেন্ট গেটওয়ে</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endif

                            {{-- aamarPay --}}
                            @if($aamarpay_gateway)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="aamarpay" required>
                                <div class="ck-pay-left">
                                    <img src="{{ asset('public/frontEnd/images/aamarpay.png') }}" class="ck-pay-logo" alt="aamarPay"
                                         onerror="this.src=''; this.style.display='none'; this.parentElement.innerHTML='<i class=\'fas fa-credit-card\' style=\'font-size:22px;color:#6366f1;\'></i>';">
                                </div>
                                <div class="ck-pay-body">
                                    <strong>aamarPay</strong>
                                    <small>কার্ড ও মোবাইল ব্যাংকিং</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endif

                            {{-- Manual Gateways --}}
                            @foreach($manual_gateways ?? [] as $mg)
                            <label class="ck-pay-option">
                                <input type="radio" name="payment_method" value="manual_{{ $mg->id }}" required>
                                <div class="ck-pay-left">
                                    @if($mg->logo_asset_url)
                                        <img src="{{ $mg->logo_asset_url }}" class="ck-pay-logo" alt="{{ $mg->title }}">
                                    @else
                                        <i class="fas fa-money-check-alt" style="font-size:22px;color:#6366f1;"></i>
                                    @endif
                                </div>
                                <div class="ck-pay-body">
                                    <strong>{{ $mg->title }}</strong>
                                    <small>ম্যানুয়াল — ট্রানজেকশন আইডি দিয়ে কনফার্ম করুন</small>
                                </div>
                                <div class="ck-pay-radio"></div>
                            </label>
                            @endforeach

                            </div>

                            {{-- Manual TRX fields --}}
                            <div id="manual-payment-fields" class="ck-manual-box" style="display:none;">
                                <div class="ck-manual-title">
                                    <i class="fas fa-info-circle"></i> ম্যানুয়াল পেমেন্ট নির্দেশনা
                                </div>
                                <div id="manual-instructions-body" class="small" style="color:#92400e;white-space:pre-wrap;margin-bottom:12px;"></div>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="ck-label">ট্রানজেকশন আইডি *</label>
                                        <input type="text" name="manual_trx_id" id="manual_trx_id" class="ck-input" value="{{ old('manual_trx_id') }}" maxlength="55" placeholder="TrxID / Reference">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="ck-label">প্রেরকের নম্বর <span style="font-weight:400;">(ঐচ্ছিক)</span></label>
                                        <input type="text" name="manual_sender_number" class="ck-input" value="{{ old('manual_sender_number') }}" maxlength="55" placeholder="01xxx">
                                    </div>
                                </div>
                            </div>
                            <script>
                                window.MANUAL_GATEWAYS = @json(($manual_gateways ?? collect())->map(fn($g) => ['code' => 'manual_'.$g->id, 'instructions' => (string)($g->instructions ?? '')])->values()->all());
                            </script>

                            <div id="payment-error" style="display:none; margin-top:10px;">
                                <i class="fas fa-exclamation-circle"></i> অনুগ্রহ করে একটি পেমেন্ট মেথড সিলেক্ট করুন।
                            </div>
                        </div>
                    </div>

                    {{-- Mobile: Place Order --}}
                    <div class="ck-mobile-btn">
                        <button type="submit" class="ck-place-order">
                            <i class="fas fa-check-circle"></i> অর্ডার নিশ্চিত করুন
                        </button>
                        <div class="ck-trust" style="border:none;padding-top:10px;">
                            <div class="ck-trust-item"><i class="fas fa-lock"></i> নিরাপদ চেকআউট</div>
                            <div class="ck-trust-item"><i class="fas fa-shield-alt"></i> ১০০% সিকিউর</div>
                        </div>
                    </div>
                </div>

                {{-- ════════════ RIGHT — ORDER SUMMARY ════════════ --}}
                <div class="ck-right">
                    <div class="ck-sticky">
                        <div class="ck-card">

                            {{-- Header --}}
                            <div class="ck-summary-title">
                                <h6><i class="fas fa-shopping-bag" style="color:var(--primary);margin-right:8px;"></i>অর্ডার সামারি</h6>
                                <span class="ck-count-badge">{{ Cart::instance('shopping')->count() }} টি পণ্য</span>
                            </div>

                            {{-- Products --}}
                            <div class="ck-products cartlist">
                                @foreach(Cart::instance('shopping')->content() as $value)
                                <div class="ck-pro-item">
                                    <a class="ck-pro-remove cart_remove" data-id="{{ $value->rowId }}" title="সরান">
                                        <i class="fas fa-times"></i>
                                    </a>
                                    <div class="ck-pro-thumb-wrap">
                                        <a href="{{ route('product', $value->options->slug) }}">
                                            <img src="{{ asset($value->options->image) }}" class="ck-pro-thumb" alt="{{ $value->name }}">
                                        </a>
                                        <span class="ck-pro-qty-badge">{{ $value->qty }}</span>
                                    </div>
                                    <div class="ck-pro-info">
                                        <a href="{{ route('product', $value->options->slug) }}" class="name">
                                            {{ Str::limit($value->name, 40) }}
                                        </a>
                                        @if($value->options->product_size || $value->options->product_color)
                                        <div class="variant">
                                            @if($value->options->product_size) Size: {{ $value->options->product_size }} @endif
                                            @if($value->options->product_color) &middot; Color: {{ $value->options->product_color }} @endif
                                        </div>
                                        @endif
                                        <div class="price-qty">
                                            <span class="item-price">৳ {{ number_format($value->price * $value->qty, 0) }}</span>
                                            <div class="ck-pro-qty-ctrl checkout-qty" data-rowid="{{ $value->rowId }}">
                                                <button type="button" class="ck-qty-btn minus"><i class="fas fa-minus" style="font-size:9px;"></i></button>
                                                <span class="ck-qty-num qty-value">{{ $value->qty }}</span>
                                                <button type="button" class="ck-qty-btn plus"><i class="fas fa-plus" style="font-size:9px;"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            {{-- Coupon --}}
                            <div class="ck-coupon">
                                @if(!Session::has('coupon_code'))
                                <div class="ck-coupon-row">
                                    <div class="ck-coupon-icon"><i class="fas fa-tag"></i></div>
                                    <input type="text" id="coupon_input" class="ck-coupon-input" placeholder="কুপন কোড লিখুন...">
                                    <button type="button" class="ck-coupon-btn" onclick="submitCoupon()">প্রয়োগ</button>
                                </div>
                                @else
                                <div class="ck-coupon-applied">
                                    <span><i class="fas fa-check-circle" style="margin-right:5px;"></i> <b>{{ Session::get('coupon_code') }}</b> কুপন প্রয়োগ হয়েছে!</span>
                                    <a href="{{ route('coupon.remove') }}" class="ck-coupon-remove">✕ সরান</a>
                                </div>
                                @endif
                            </div>

                            {{-- Totals --}}
                            <div class="ck-totals">
                                <div class="ck-total-row">
                                    <span class="label">সাবটোটাল</span>
                                    <span class="val" id="subtotalAmount">৳ {{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="ck-total-row">
                                    <span class="label">ডেলিভারি চার্জ</span>
                                    <span class="val" id="shippingAmount">৳ {{ number_format($shipping, 2) }}</span>
                                </div>
                                @if($discount > 0)
                                <div class="ck-total-row discount">
                                    <span class="label">কুপন ছাড়</span>
                                    <span class="val" id="discountAmount">- ৳ {{ number_format($discount, 2) }}</span>
                                </div>
                                @endif
                                <div class="ck-total-row grand">
                                    <span class="label">সর্বমোট</span>
                                    <span class="val" id="grandTotalAmount">৳ {{ number_format($grand_total, 2) }}</span>
                                </div>

                                @if($hasAdvance)
                                <div class="ck-advance-box">
                                    <div class="ck-advance-row">
                                        <span style="color:#15803d;font-weight:700;">✓ অগ্রিম (এখন পেইড):</span>
                                        <span style="color:#15803d;font-weight:700;" id="advanceAmountCell">৳ {{ number_format($advance_amount,2) }}</span>
                                    </div>
                                    <div class="ck-advance-row">
                                        <span style="color:#dc2626;font-weight:700;">বাকি (ডিউ):</span>
                                        <span style="color:#dc2626;font-weight:700;" id="dueAmountCell">৳ {{ number_format($due_amount,2) }}</span>
                                    </div>
                                </div>
                                @endif
                            </div>

                            {{-- Desktop: Place Order --}}
                            <div class="ck-desktop-btn">
                                <div class="ck-submit-wrap">
                                    <button type="submit" class="ck-place-order">
                                        <i class="fas fa-lock"></i> অর্ডার নিশ্চিত করুন
                                    </button>
                                </div>
                                <div class="ck-trust">
                                    <div class="ck-trust-item"><i class="fas fa-lock"></i> SSL Secured</div>
                                    <div class="ck-trust-item"><i class="fas fa-shield-alt"></i> Safe Checkout</div>
                                    <div class="ck-trust-item"><i class="fas fa-undo"></i> Easy Return</div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </form>

        @if(!empty($__showCheckoutOtpModal))
        <form id="checkout_otp_resend_form" method="POST" action="{{ route('customer.ordersave') }}" style="display:none;">
            @csrf
            <input type="hidden" name="checkout_otp_resend" value="1">
            @if(!empty($__checkoutOtpDraft['phone']))
                <input type="hidden" name="phone" value="{{ $__checkoutOtpDraft['phone'] }}">
            @endif
        </form>
        <div id="checkoutOtpOverlay" class="ck-otp-overlay is-open" aria-hidden="false"></div>
        <div id="checkoutOtpModal" class="ck-otp-modal is-open" role="dialog" aria-modal="true" aria-labelledby="checkoutOtpModalLabel">
            <div class="ck-otp-head">
                <h5 id="checkoutOtpModalLabel">
                    <i class="fas fa-mobile-alt"></i> OTP ভেরিফিকেশন
                </h5>
            </div>
            <div class="ck-otp-body">
                <p class="ck-otp-desc">আপনার মোবাইলে একটি <strong>৬ ডিজিটের OTP</strong> পাঠানো হয়েছে। কোডটি লিখে অর্ডার সম্পূর্ণ করুন।</p>
                @unless(session('checkout_otp_resend_success'))
                @error('checkout_otp')
                    <div class="ck-otp-error">{{ $message }}</div>
                @enderror
                @endunless
                <div id="checkout_otp_status" class="ck-otp-status" role="status" aria-live="polite"></div>
                <label class="ck-otp-label" for="checkout_otp_modal_field">OTP কোড</label>
                <input type="text" id="checkout_otp_modal_field" class="ck-otp-input" maxlength="6"
                    inputmode="numeric" autocomplete="one-time-code" placeholder="● ● ● ● ● ●"
                    value="{{ $__otpFieldValue }}">
                <div class="ck-otp-actions">
                    <button type="submit" form="checkout_otp_resend_form" class="ck-otp-btn ck-otp-btn-resend" id="checkout_otp_resend_btn">
                        <i class="fas fa-redo-alt"></i> <span id="checkout_otp_resend_label">OTP আবার পাঠান</span>
                    </button>
                    <button type="button" class="ck-otp-btn ck-otp-btn-confirm" id="checkout_otp_confirm_btn">
                        <i class="fas fa-check-circle"></i> অর্ডার সম্পূর্ণ করুন
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Mobile sticky order bar --}}
    <div class="ck-mobile-bar">
        <div class="ck-mobile-bar-total">
            <small>সর্বমোট</small>
            <strong id="ckMobileGrandTotal">৳ {{ number_format($grand_total, 2) }}</strong>
        </div>
        <button type="submit" form="checkout-form" class="ck-mobile-bar-btn">
            <i class="fas fa-check-circle"></i> অর্ডার করুন
        </button>
    </div>
</div>
@endsection

@push('script')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="{{ asset('public/backEnd/assets/css/toastr.min.css') }}">
<script src="{{ asset('public/backEnd/assets/js/toastr.min.js') }}"></script>
<script>{!! Toastr::message() !!}</script>

@if(!empty($__showCheckoutOtpModal))
<script>
(function () {
    var resendRetryAfter = @json((int) ($__otpResendRetryAfter ?? 0));
    var resendCooldownTimer = null;

    function ckOtpOpen() {
        var overlay = document.getElementById('checkoutOtpOverlay');
        var modal = document.getElementById('checkoutOtpModal');
        if (!overlay || !modal) return;
        overlay.classList.add('is-open');
        modal.classList.add('is-open');
        document.body.classList.add('ck-otp-lock');
    }

    function ckOtpNotify(message, type) {
        type = type || 'error';
        if (typeof toastr !== 'undefined') {
            if (type === 'success') toastr.success(message, 'সফল');
            else if (type === 'info') toastr.info(message, 'তথ্য');
            else toastr.error(message, 'ত্রুটি');
        } else {
            alert(message);
        }
    }

    function ckOtpShowStatus(message, type) {
        var statusEl = document.getElementById('checkout_otp_status');
        if (!statusEl) return;
        statusEl.textContent = message;
        statusEl.className = 'ck-otp-status is-show ' + (type || 'info');
    }

    function ckOtpStartCooldown(btn, seconds) {
        if (!btn || seconds <= 0) return;
        var label = document.getElementById('checkout_otp_resend_label');
        var remain = Math.max(1, parseInt(seconds, 10) || 30);
        btn.disabled = true;
        if (resendCooldownTimer) clearInterval(resendCooldownTimer);
        function tick() {
            if (label) label.textContent = 'আবার পাঠান (' + remain + 's)';
            if (remain <= 0) {
                clearInterval(resendCooldownTimer);
                resendCooldownTimer = null;
                btn.disabled = false;
                if (label) label.textContent = 'OTP আবার পাঠান';
                return;
            }
            remain -= 1;
        }
        tick();
        resendCooldownTimer = setInterval(tick, 1000);
    }

    function ckOtpConfirm() {
        var modalInput = document.getElementById('checkout_otp_modal_field');
        var raw = modalInput ? modalInput.value : '';
        var otp = raw.replace(/\D/g, '').slice(0, 6);
        if (otp.length !== 6) {
            ckOtpNotify('৬ ডিজিটের OTP কোড লিখুন।', 'error');
            if (modalInput) modalInput.focus();
            return;
        }

        window.__checkoutOtpSkipCancel = true;
        document.getElementById('checkout_otp_hidden').value = otp;

        var form = document.getElementById('checkout-form');
        if (!form) return;

        if (typeof window.isSubmitting !== 'undefined') window.isSubmitting = true;
        if (typeof window.checkoutOtpPending !== 'undefined') window.checkoutOtpPending = false;

        form.submit();
    }

    function ckOtpInit() {
        ckOtpOpen();
        if (resendRetryAfter > 0) {
            ckOtpStartCooldown(document.getElementById('checkout_otp_resend_btn'), resendRetryAfter);
        }

        var modalInput = document.getElementById('checkout_otp_modal_field');
        if (modalInput) {
            setTimeout(function () { modalInput.focus(); }, 300);
            modalInput.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 6);
            });
            modalInput.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    ckOtpConfirm();
                }
            });
        }

        var checkoutForm = document.getElementById('checkout-form');
        if (checkoutForm) {
            checkoutForm.querySelectorAll('input[name="payment_method"]').forEach(function (el) {
                el.disabled = true;
            });
            ['checkout_division', 'checkout_district', 'checkout_upazila'].forEach(function (id) {
                var el = document.getElementById(id);
                if (el) el.disabled = true;
            });
        }

        var confirmBtn = document.getElementById('checkout_otp_confirm_btn');
        if (confirmBtn) confirmBtn.addEventListener('click', ckOtpConfirm);

        var resendForm = document.getElementById('checkout_otp_resend_form');
        var resendBtn = document.getElementById('checkout_otp_resend_btn');
        if (resendForm) {
            resendForm.addEventListener('submit', function (e) {
                e.preventDefault();
                if (resendBtn && resendBtn.disabled) return;

                window.__checkoutOtpSkipCancel = true;
                ckOtpShowStatus('নতুন OTP পাঠানো হচ্ছে...', 'info');
                if (resendBtn) resendBtn.disabled = true;

                var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content
                    || (document.querySelector('#checkout-form input[name="_token"]') || {}).value
                    || (document.querySelector('#checkout_otp_resend_form input[name="_token"]') || {}).value
                    || '';
                var tokenInput = resendForm.querySelector('input[name="_token"]');
                if (tokenInput && csrfToken) tokenInput.value = csrfToken;
                var postUrl = (document.getElementById('checkout-form') || {}).action || resendForm.action;

                $.ajax({
                    url: postUrl,
                    type: 'POST',
                    data: $(resendForm).serialize(),
                    dataType: 'json',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    success: function (data) {
                        if (data && data.ok === true) {
                            ckOtpShowStatus(data.message || 'নতুন OTP পাঠানো হয়েছে।', 'success');
                            ckOtpNotify(data.message || 'নতুন OTP পাঠানো হয়েছে।', 'success');
                            if (modalInput) {
                                modalInput.value = '';
                                modalInput.focus();
                            }
                            var hiddenOtp = document.getElementById('checkout_otp_hidden');
                            if (hiddenOtp) hiddenOtp.value = '';
                            document.querySelectorAll('.ck-otp-error').forEach(function (el) {
                                el.remove();
                            });
                            ckOtpStartCooldown(resendBtn, data.retry_after || 30);
                            return;
                        }

                        var retryAfter = parseInt(data.retry_after, 10) || 0;
                        if (retryAfter > 0) {
                            ckOtpShowStatus(data.message || 'কিছুক্ষণ পর আবার চেষ্টা করুন।', 'info');
                            ckOtpNotify(data.message || 'কিছুক্ষণ পর আবার চেষ্টা করুন।', 'info');
                            ckOtpStartCooldown(resendBtn, retryAfter);
                            return;
                        }

                        ckOtpShowStatus(data.message || 'OTP পাঠানো যায়নি।', 'error');
                        ckOtpNotify(data.message || 'OTP পাঠানো যায়নি।', 'error');
                        if (resendBtn) resendBtn.disabled = false;
                    },
                    error: function (xhr) {
                        var data = xhr.responseJSON || {};
                        var message = data.message || '';
                        var retryAfter = parseInt(data.retry_after, 10) || 0;

                        if (xhr.status === 419) {
                            message = 'সেশন মেয়াদ শেষ। পেজ রিফ্রেশ (F5) করে আবার চেষ্টা করুন।';
                        }

                        if (retryAfter > 0) {
                            ckOtpShowStatus(message || 'কিছুক্ষণ পর আবার চেষ্টা করুন।', 'info');
                            ckOtpNotify(message || 'কিছুক্ষণ পর আবার চেষ্টা করুন।', 'info');
                            ckOtpStartCooldown(resendBtn, retryAfter);
                            return;
                        }

                        ckOtpShowStatus(message || 'OTP পাঠানো যায়নি। আবার চেষ্টা করুন।', 'error');
                        ckOtpNotify(message || 'OTP পাঠানো যায়নি।', 'error');
                        if (resendBtn) resendBtn.disabled = false;
                    }
                });
            });
        }

        @if(session('checkout_otp_resend_success'))
            ckOtpShowStatus(@json(session('checkout_otp_resend_success')), 'success');
        @endif

        if (checkoutForm) {
            checkoutForm.addEventListener('submit', function (e) {
                if (!document.getElementById('checkoutOtpModal')) return;
                var hidden = document.getElementById('checkout_otp_hidden');
                var otpVal = hidden ? String(hidden.value || '').replace(/\D/g, '') : '';
                if (otpVal.length !== 6) {
                    e.preventDefault();
                    e.stopPropagation();
                    ckOtpOpen();
                    ckOtpNotify('৬ ডিজিটের OTP কোড লিখুন।', 'error');
                    if (modalInput) modalInput.focus();
                }
            }, true);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', ckOtpInit);
    } else {
        ckOtpInit();
    }
})();
</script>
@endif

<script>
function submitCoupon() {
    var code = (document.getElementById('coupon_input') || {}).value || '';
    code = code.trim();
    if (!code) {
        if (typeof toastr !== 'undefined') toastr.error('কুপন কোড লিখুন');
        else alert('কুপন কোড লিখুন');
        return;
    }

    var csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
    var btn = document.querySelector('.ck-coupon-btn');
    var origText = btn ? btn.textContent : '';
    if (btn) { btn.textContent = '...'; btn.disabled = true; }

    $.ajax({
        url: '{{ route("coupon.apply") }}',
        type: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': csrfToken },
        data: { coupon_code: code, _token: csrfToken },
        success: function (res) {
            if (typeof toastr !== 'undefined' && res && res.message) toastr.success(res.message);
            window.location.reload();
        },
        error: function (xhr) {
            if (btn) { btn.textContent = origText; btn.disabled = false; }
            var msg = '';
            try { msg = (xhr.responseJSON || {}).message || ''; } catch(e){}
            if (!msg) msg = 'কুপন প্রয়োগ ব্যর্থ হয়েছে।';
            if (typeof toastr !== 'undefined') toastr.error(msg); else alert(msg);
        }
    });
}
</script>

<script>
let incompleteOrderTimer;
window.isSubmitting = false;
window.checkoutOtpPending = @json((bool)($__custCheckoutOtpPending ?? false));

$(document).ready(function() {
    // Remove item
    $(document).on('click', '.cart_remove', function(e) {
        e.preventDefault(); e.stopImmediatePropagation();
        var id = $(this).data("id");
        if (!id) return;
        var $item = $(this).closest('.ck-pro-item');
        $item.css('opacity','0.4');
        $.ajax({
            type: "GET",
            url: "{{ route('cart.remove') }}",
            data: { id: id },
            success: function() { window.location.reload(); },
            error:   function() { $item.css('opacity','1'); window.location.reload(); }
        });
    });

    // Qty buttons
    $(document).on('click', '.checkout-qty .plus', function() {
        var rowId = $(this).closest('.checkout-qty').data('rowid');
        var $wrap = $(this).closest('.checkout-qty');
        $wrap.css('opacity','0.4');
        $.get("{{ route('cart.increment') }}", {id:rowId}, function(){ window.location.reload(); })
         .fail(function(){ $wrap.css('opacity','1'); window.location.reload(); });
    });
    $(document).on('click', '.checkout-qty .minus', function() {
        var rowId = $(this).closest('.checkout-qty').data('rowid');
        var $wrap = $(this).closest('.checkout-qty');
        $wrap.css('opacity','0.4');
        $.get("{{ route('cart.decrement') }}", {id:rowId}, function(){ window.location.reload(); })
         .fail(function(){ $wrap.css('opacity','1'); window.location.reload(); });
    });

    const baseSubtotal       = parseFloat("{{ $subtotal ?? 0 }}");
    const baseDiscount       = parseFloat("{{ $discount ?? 0 }}");
    const advanceAmount      = parseFloat("{{ $advance_amount ?? 0 }}");
    const hasAdvance         = @json($hasAdvance ?? false);
    const requiresShipping   = @json($requires_shipping ?? false);
    const cartItems          = @json($cartItemsForJs ?? []);
    const hasAllFreeDelivery = @json($hasAllFreeDelivery ?? false);

    function checkFreeDelivery() {
        let allFree = true;
        for (let i=0;i<cartItems.length;i++) {
            if(cartItems[i].is_digital==1) continue;
            if(cartItems[i].free_delivery!=1){ allFree=false; break; }
        }
        return allFree;
    }

    function districtChargeFromSelect() {
        if (!$('#checkout_district').length || !$('#checkout_district').val()) return 0;
        return parseFloat($('#checkout_district option:selected').attr('data-charge')) || 0;
    }

    function applyShippingToDomAndSession() {
        var isFree = checkFreeDelivery();
        var shippingCharge = isFree ? 0 : districtChargeFromSelect();
        var grandTotal = baseSubtotal + shippingCharge - baseDiscount;
        var dueAmount  = hasAdvance ? (grandTotal - advanceAmount) : 0;
        $('#shippingAmount').text('৳ ' + shippingCharge.toFixed(2));
        $('#grandTotalAmount').text('৳ ' + grandTotal.toFixed(2));
        $('#ckMobileGrandTotal').text('৳ ' + grandTotal.toFixed(2));
        if(hasAdvance){ $('#dueAmountCell').text('৳ '+dueAmount.toFixed(2)); $('#dueAmountText').text(dueAmount.toFixed(2)); }
        if(!requiresShipping) return;
        if(isFree){ $.get('{{ route("shipping.charge") }}',{id:'free_delivery'}); }
        else { var did=$('#checkout_district').val(); if(did){ $.get('{{ route("shipping.charge") }}',{id:did}); } }
    }

    $('#checkout_division').on('change', function () {
        var divId=$(this).val();
        $('#checkout_district').prop('disabled',!divId).html(divId?'<option value="">লোড হচ্ছে...</option>':'<option value="">আগে বিভাগ সিলেক্ট করুন</option>');
        $('#checkout_upazila').prop('disabled',true).html('<option value="">আগে জেলা সিলেক্ট করুন</option>');
        if(!divId){ applyShippingToDomAndSession(); saveIncompleteOrder(); return; }
        $.get('{{ url('/ajax/delivery/districts') }}/'+divId, function(res){
            var opts='<option value="">জেলা নির্বাচন করুন</option>';
            (res.data||[]).forEach(function(r){ opts+='<option value="'+r.id+'" data-charge="'+r.delivery_charge+'">'+r.name+' (৳'+r.delivery_charge+')</option>'; });
            $('#checkout_district').html(opts).prop('disabled',false);
        }).fail(function(){ $('#checkout_district').html('<option value="">লোড ব্যর্থ</option>'); });
        applyShippingToDomAndSession(); saveIncompleteOrder();
    });

    $('#checkout_district').on('change', function () {
        var distId=$(this).val();
        $('#checkout_upazila').prop('disabled',!distId).html(distId?'<option value="">লোড হচ্ছে...</option>':'<option value="">আগে জেলা সিলেক্ট করুন</option>');
        if(!distId){ applyShippingToDomAndSession(); saveIncompleteOrder(); return; }
        applyShippingToDomAndSession();
        $.get('{{ url('/ajax/delivery/upazilas') }}/'+distId, function(res){
            var opts='<option value="">উপজেলা নির্বাচন করুন</option>';
            (res.data||[]).forEach(function(r){ opts+='<option value="'+r.id+'">'+r.name+'</option>'; });
            $('#checkout_upazila').html(opts).prop('disabled',false);
        }).fail(function(){ $('#checkout_upazila').html('<option value="">লোড ব্যর্থ</option>'); });
        saveIncompleteOrder();
    });

    $('#checkout_upazila').on('change', function(){ saveIncompleteOrder(); });

    $(document).ready(function(){
        var isFreeOnLoad = hasAllFreeDelivery || checkFreeDelivery();
        if(!requiresShipping) return;
        if(isFreeOnLoad){ applyShippingToDomAndSession(); }
        else {
            var curShip=parseFloat($('#shippingAmount').text().replace(/[৳,\s]/g,'').trim())||0;
            var gt=baseSubtotal+curShip-baseDiscount;
            $('#grandTotalAmount').text('৳ '+gt.toFixed(2));
            $('#ckMobileGrandTotal').text('৳ '+gt.toFixed(2));
            if(hasAdvance){ $('#dueAmountCell').text('৳ '+(gt-advanceAmount).toFixed(2)); }
            var did=$('#checkout_district').val();
            if(did){ $.get('{{ route("shipping.charge") }}',{id:did}); }
        }
    });

    function selectedLocationText($sel) {
        if(!$sel.length||!$sel.val()) return '';
        return ($sel.find('option:selected').text()||'').replace(/\s*\(৳[^)]*\)\s*/g,'').trim();
    }
    function buildCheckoutAddress() {
        var street=($('input[name="address"]').val()||'').trim(), parts=[];
        if(requiresShipping){
            var div=selectedLocationText($('#checkout_division')), dist=selectedLocationText($('#checkout_district')), upa=selectedLocationText($('#checkout_upazila'));
            if(div) parts.push(div); if(dist) parts.push(dist); if(upa) parts.push(upa);
        }
        if(street) parts.unshift(street);
        return parts.join(', ');
    }
    function buildCheckoutMeta(shippingCharge) {
        var meta={subtotal:baseSubtotal,discount:baseDiscount,shipping_charge:shippingCharge,order_note:($('#order_note').val()||'').trim()};
        if(requiresShipping){
            meta.division_id=$('#checkout_division').val()||null;
            meta.district_id=$('#checkout_district').val()||null;
            meta.upazila_id=$('#checkout_upazila').val()||null;
            var loc=[],div=selectedLocationText($('#checkout_division')),dist=selectedLocationText($('#checkout_district')),upa=selectedLocationText($('#checkout_upazila'));
            if(upa) loc.push(upa); if(dist) loc.push(dist); if(div) loc.push(div);
            meta.location_label=loc.join(', ');
        }
        return meta;
    }
    function saveIncompleteOrder(immediate) {
        if (window.isSubmitting) return;
        if (incompleteOrderTimer) clearTimeout(incompleteOrderTimer);

        var runSave = function () {
            var name = ($('input[name="name"]').val() || '').trim();
            var phone = ($('input[name="phone"]').val() || '').replace(/\D/g, '');
            var address = buildCheckoutAddress();
            if (phone.length < 11 || !cartItems || !cartItems.length) return;

            var csrfToken = $('meta[name="csrf-token"]').attr('content')
                || $('#checkout-form input[name="_token"]').val() || '';
            var isFree = checkFreeDelivery();
            var shippingCharge = isFree ? 0 : districtChargeFromSelect();
            var total = (baseSubtotal + shippingCharge - baseDiscount).toFixed(2);

            $.ajax({
                url: '{{ route("incomplete.order.store") }}',
                type: 'POST',
                contentType: 'application/json; charset=UTF-8',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                data: JSON.stringify({
                    _token: csrfToken,
                    name: name,
                    phone: phone,
                    address: address,
                    items: cartItems,
                    checkout_meta: buildCheckoutMeta(shippingCharge),
                    total_amount: total,
                    product_image: cartItems[0] && cartItems[0].image ? cartItems[0].image : '',
                    product_link: cartItems[0] && cartItems[0].link ? cartItems[0].link : ''
                })
            });
        };

        if (immediate) { runSave(); return; }
        incompleteOrderTimer = setTimeout(runSave, 1500);
    }

    $('#checkout-form input, #checkout-form select, #checkout-form textarea').on('input change', function(){
        if($(this).attr('name')!=='payment_method'){ saveIncompleteOrder(); }
    });

    $('#checkout-form').on('submit', function(e) {
        var pm=$('input[name="payment_method"]:checked').val();
        if(!pm){
            e.preventDefault();
            toastr.error('পেমেন্ট মেথড সিলেক্ট করুন।','Error');
            $('#payment-error').show();
            $('html,body').animate({scrollTop:$('.ck-step-badge:last').offset().top-150},500);
            return false;
        }
        $('#payment-error').hide();
        if(pm.indexOf('manual_')===0){
            var trx=$('input[name="manual_trx_id"]').val();
            if(!trx||!String(trx).trim()){
                e.preventDefault();
                toastr.error('ট্রানজেকশন আইডি লিখুন।','Error');
                $('#manual-payment-fields').show();
                $('html,body').animate({scrollTop:$('#manual-payment-fields').offset().top-120},400);
                return false;
            }
        }
        window.__checkoutOtpSkipCancel=true;
        window.isSubmitting=true;
        if(incompleteOrderTimer) clearTimeout(incompleteOrderTimer);
    });

    window.addEventListener('pagehide', function () {
        if (window.isSubmitting) return;
        saveIncompleteOrder(true);
    });

    function syncManualPaymentUi() {
        var v=$('input[name="payment_method"]:checked').val()||'';
        if(v.indexOf('manual_')===0){
            $('#manual-payment-fields').show();
            var inst='';
            (window.MANUAL_GATEWAYS||[]).forEach(function(g){ if(g.code===v) inst=g.instructions||''; });
            $('#manual-instructions-body').html($('<div/>').text(inst).html().replace(/\n/g,'<br>'));
            $('#manual_trx_id').prop('required',true);
        } else { $('#manual-payment-fields').hide(); $('#manual_trx_id').prop('required',false); }
    }

    $('input[name="payment_method"]').on('change', function(){ $('#payment-error').hide(); syncManualPaymentUi(); });
    if(!$('input[name="payment_method"]:checked').length && $('input[name="payment_method"]').length){
        $('input[name="payment_method"]:first').prop('checked',true);
    }
    syncManualPaymentUi();
    setTimeout(function(){ saveIncompleteOrder(); }, 1200);
});
</script>

<script type="text/javascript">
(function () {
    if (typeof window.EcomTracking === 'undefined') return;
    var items = @json($cartItemsForJs);
    var hasAdvance = @json($hasAdvance);
    var advanceAmount = parseFloat("{{ $advance_amount }}") || 0;
    var grandTotal = parseFloat("{{ $grand_total }}") || 0;
    var payableNow = hasAdvance ? advanceAmount : grandTotal;
    var coupon = @json(Session::get('coupon_code', null));
    function checkoutUserFromForm() {
        return { name:($('input[name="name"]').val()||'').trim(), phone:($('input[name="phone"]').val()||'').trim(),
                 address:($('input[name="address"]').val()||'').trim(), city:($('#checkout_district option:selected').text()||'').replace(/\s*\(৳[^)]*\)\s*/g,'').trim() };
    }
    if(items.length){ EcomTracking.initiateCheckout({items:items,value:payableNow,coupon:coupon}); }
    var identifyTimer;
    $('#checkout-form input[name="name"], #checkout-form input[name="phone"]').on('input blur', function(){
        clearTimeout(identifyTimer);
        identifyTimer=setTimeout(function(){ var u=checkoutUserFromForm(); if(u.phone&&String(u.phone).replace(/\D/g,'').length>=11){ EcomTracking.identify(u); } }, 800);
    });
    @auth('customer')
    EcomTracking.identify(@json(\App\Support\EcommerceTrackingUser::fromCustomer(auth('customer')->user())));
    @endauth
    var form=document.getElementById('checkout-form');
    if(form){ form.addEventListener('submit', function(){ var pm=form.querySelector('input[name="payment_method"]:checked'); EcomTracking.identify(checkoutUserFromForm()); EcomTracking.addPaymentInfo({items:items,value:payableNow,coupon:coupon,payment_method:pm?pm.value:''}); }); }
})();
</script>
@endpush
