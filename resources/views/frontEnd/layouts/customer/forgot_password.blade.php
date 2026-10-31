@extends('frontEnd.layouts.master')
@section('title','পাসওয়ার্ড ভুলে গেছেন?')

@section('content')
<main class="app-main auth-main">
    <div class="auth-shell">
        <div class="auth-card">

            {{-- আইকন --}}
            <div class="auth-card-icon">
                <i class="fas fa-lock"></i>
            </div>

            <div class="auth-card-head">
                <h1>পাসওয়ার্ড রিসেট</h1>
                <p>আপনার রেজিস্টার্ড মোবাইল নম্বর দিন, OTP পাঠানো হবে।</p>
            </div>

            <form class="auth-form" action="{{ route('customer.forgot.verify') }}" method="POST">
                @csrf

                {{-- মোবাইল নম্বর --}}
                <div class="auth-field">
                    <label for="fp_phone">মোবাইল নম্বর</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-phone-alt" aria-hidden="true"></i>
                        <input type="tel"
                               id="fp_phone"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="@error('phone') is-invalid @enderror"
                               placeholder="01XXXXXXXXX"
                               autocomplete="tel"
                               required>
                    </div>
                    @error('phone')
                        <span class="text-danger d-block mt-1" style="font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth-submit" style="margin-top:8px;">
                    <i class="fas fa-paper-plane" style="margin-right:6px;"></i> OTP পাঠান
                </button>
            </form>

            <p class="auth-switch" style="margin-top:20px;text-align:center;">
                <a href="{{ route('customer.login') }}" class="auth-link">
                    <i class="fas fa-arrow-left" style="margin-right:4px;"></i> লগইনে ফিরে যান
                </a>
            </p>

        </div>
    </div>
</main>
@endsection
