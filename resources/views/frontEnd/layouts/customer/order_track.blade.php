@extends('frontEnd.layouts.master')
@section('title','Track Your Order')

@php
    $generalsetting = \App\Models\GeneralSetting::first();
@endphp

@section('content')
<main class="app-main">
    <section class="page-hero">
        <div class="container">
            <h1><i class="fas fa-truck"></i> অর্ডার ট্র্যাকিং</h1>
            <p>অর্ডার আইডি অথবা মোবাইল নম্বর দিয়ে ডেলিভারি স্ট্যাটাস দেখুন</p>
        </div>
    </section>

    <section class="service-page-section">
        <div class="container">
            <div class="service-card">
                
                {{-- ট্র্যাকিং ফর্ম --}}
                <form class="service-form" id="trackForm" action="{{ route('customer.order_track_result') }}" method="GET">
                    
                    {{-- ইনভয়েস/অর্ডার আইডি --}}
                    <div class="auth-field">
                        <label for="invoice_id">অর্ডার আইডি (Invoice ID)</label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-receipt"></i>
                            <input type="text" id="invoice_id" name="invoice_id" value="{{ request('invoice_id') }}" placeholder="যেমন: 54321">
                        </div>
                        @error('invoice_id')
                            <span class="auth-error text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="text-center my-2 text-muted" style="font-size: 14px; font-weight: 600;">
                        অথবা
                    </div>

                    {{-- মোবাইল নম্বর --}}
                    <div class="auth-field">
                        <label for="trackPhone">মোবাইল নম্বর</label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-phone"></i>
                            <input type="tel" id="trackPhone" name="phone" value="{{ request('phone') }}" placeholder="01XXXXXXXXX">
                        </div>
                        @error('phone')
                            <span class="auth-error text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    {{-- ভ্যালিডেশন এরর মেসেজ (জাভাস্ক্রিপ্ট থেকে শো হবে) --}}
                    <div id="validationError" class="text-danger small mb-3" style="display: none; font-weight: 500;">
                        দয়া করে অর্ডার আইডি অথবা মোবাইল নাম্বার যেকোনো একটি দিন!
                    </div>

                    <button type="submit" class="auth-submit"><i class="fas fa-search"></i> ট্র্যাক করুন</button>
                </form>

             

            {{-- হেল্প সেকশন ডাইনামিক করা হয়েছে --}}
            <div class="service-help">
                <i class="fas fa-info-circle"></i>
                <p>সাহায্য লাগলে কল করুন: 
                    <a href="tel:{{ $generalsetting->phone ?? '88012345678' }}">
                        {{ $contact->hotline }}
                    </a>
                </p>
            </div>
        </div>
    </section>
</main>

{{-- কাস্টম ফর্ম ভ্যালিডেশন স্ক্রিপ্ট --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const trackForm = document.getElementById('trackForm');
        
        if (trackForm) {
            trackForm.addEventListener('submit', function(e) {
                const invoiceId = document.getElementById('invoice_id').value.trim();
                const phone = document.getElementById('trackPhone').value.trim();
                const errorMsg = document.getElementById('validationError');

                // যদি দুটোই ফাঁকা থাকে, তবে ফর্ম সাবমিট আটকাবে
                if (!invoiceId && !phone) {
                    e.preventDefault(); 
                    errorMsg.style.display = 'block';
                } else {
                    errorMsg.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection