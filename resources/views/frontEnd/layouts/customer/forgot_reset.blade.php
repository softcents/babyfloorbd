@extends('frontEnd.layouts.master')
@section('title','OTP ভেরিফিকেশন')

@section('content')
<main class="app-main auth-main">
    <div class="auth-shell">
        <div class="auth-card">

            {{-- আইকন --}}
            <div class="auth-card-icon">
                <i class="fas fa-shield-alt"></i>
            </div>

            <div class="auth-card-head">
                <h1>OTP ভেরিফিকেশন</h1>
                <p>আপনার ফোনে পাঠানো OTP কোড এবং নতুন পাসওয়ার্ড দিন।</p>
            </div>

            {{-- মেইন ফর্ম --}}
            <form class="auth-form" action="{{ route('customer.forgot.store') }}" method="POST">
                @csrf

                {{-- OTP কোড --}}
                <div class="auth-field">
                    <label for="fr_otp">OTP কোড</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-key" aria-hidden="true"></i>
                        <input type="text"
                               id="fr_otp"
                               name="otp"
                               value="{{ old('otp') }}"
                               class="@error('otp') is-invalid @enderror"
                               placeholder="6 ডিজিটের কোড"
                               inputmode="numeric"
                               maxlength="6"
                               autocomplete="one-time-code"
                               required>
                    </div>
                    @error('otp')
                        <span class="text-danger d-block mt-1" style="font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- নতুন পাসওয়ার্ড --}}
                <div class="auth-field">
                    <label for="fr_password">নতুন পাসওয়ার্ড</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input type="password"
                               id="fr_password"
                               name="password"
                               class="@error('password') is-invalid @enderror"
                               placeholder="নতুন পাসওয়ার্ড লিখুন"
                               autocomplete="new-password"
                               required>
                        <button type="button" class="auth-toggle-pass" onclick="toggleFrPass()" aria-label="পাসওয়ার্ড দেখুন">
                            <i class="far fa-eye" id="frToggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger d-block mt-1" style="font-size:13px;">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="auth-submit" style="margin-top:8px;">
                    <i class="fas fa-check-circle" style="margin-right:6px;"></i> পাসওয়ার্ড রিসেট করুন
                </button>
            </form>

            {{-- Resend OTP --}}
            <div class="auth-divider" style="margin-top:20px;"><span>কোড পাননি?</span></div>

            <form action="{{ route('customer.forgot.resendotp') }}" method="POST" style="text-align:center;">
                @csrf
                <button type="submit" style="background:none;border:none;color:var(--primary);font-weight:600;font-size:14px;cursor:pointer;padding:0;">
                    <i class="fas fa-redo" style="margin-right:4px;"></i> OTP আবার পাঠান
                </button>
            </form>

            <p style="text-align:center;margin-top:16px;">
                <a href="{{ route('customer.login') }}" class="auth-link">
                    <i class="fas fa-arrow-left" style="margin-right:4px;"></i> লগইনে ফিরে যান
                </a>
            </p>

        </div>
    </div>
</main>

<script>
function toggleFrPass() {
    var input = document.getElementById('fr_password');
    var icon  = document.getElementById('frToggleIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection
