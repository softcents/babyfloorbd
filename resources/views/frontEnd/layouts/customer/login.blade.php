@extends('frontEnd.layouts.master')
@section('title','Customer Login')

@php
    $generalsetting = \App\Models\GeneralSetting::first();
    $fbEnabled      = $generalsetting && $generalsetting->facebook_login_enabled && $generalsetting->facebook_app_id;
    $ggEnabled      = $generalsetting && $generalsetting->google_login_enabled   && $generalsetting->google_client_id;
@endphp

@section('content')
<main class="app-main auth-main">
    <div class="auth-shell">
        <div class="auth-card">
        
            
            <div class="auth-card-head">
                <h1>লগইন করুন</h1>
                <p>আপনার অ্যাকাউন্টে প্রবেশ করতে তথ্য দিন</p>
            </div>
            
            <form class="auth-form" id="loginForm" action="{{ route('customer.signin') }}" method="POST">
                @csrf
                
                {{-- মোবাইল নম্বর বা ইমেইল --}}
                <div class="auth-field">
                    <label for="loginUser">মোবাইল নম্বর বা ইমেইল</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-user" aria-hidden="true"></i>
                        <input type="text" 
                               id="loginUser" 
                               name="login" 
                               value="{{ old('login') }}" 
                               class="@error('login') is-invalid @enderror" 
                               placeholder="01XXXXXXXXX বা email@example.com" 
                               autocomplete="username" required>
                    </div>
                    @error('login')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- পাসওয়ার্ড --}}
                <div class="auth-field">
                    <label for="loginPassword">পাসওয়ার্ড</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-lock" aria-hidden="true"></i>
                        <input type="password" 
                               id="loginPassword" 
                               name="password" 
                               class="@error('password') is-invalid @enderror" 
                               placeholder="পাসওয়ার্ড লিখুন" 
                               autocomplete="current-password" required>
                        <button type="button" class="auth-toggle-pass" onclick="togglePassword()" aria-label="পাসওয়ার্ড দেখুন">
                            <i class="far fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- মনে রাখুন --}}
                <div class="auth-row-between">
                    <label class="auth-check">
                        <input type="checkbox" name="remember" checked> <span>মনে রাখুন</span>
                    </label>
                    <a href="{{ route('customer.forgot.password') }}" class="auth-link">পাসওয়ার্ড ভুলে গেছেন?</a>
                </div>
                
                <button type="submit" class="auth-submit">লগইন করুন</button>
            </form>
            
            @if($fbEnabled || $ggEnabled)
            <div class="auth-divider"><span>অথবা</span></div>
            
            <div class="auth-social">
                @if($ggEnabled)
                <a href="{{ route('social.login.redirect', 'google') }}" class="auth-social-btn auth-social-google">
                    <i class="fab fa-google"></i> Google দিয়ে লগইন
                </a>
                @endif
                @if($fbEnabled)
                <a href="{{ route('social.login.redirect', 'facebook') }}" class="auth-social-btn auth-social-fb">
                    <i class="fab fa-facebook-f"></i> Facebook দিয়ে লগইন
                </a>
                @endif
            </div>
            @endif
            
            <p class="auth-switch">অ্যাকাউন্ট নেই? <a href="{{ route('customer.register') }}">রেজিস্টার করুন</a></p>

            {{-- ডেমো ইউজার এক্সেস --}}
            @if(isset($demoMode) && $demoMode)
            <div style="margin-top: 25px; padding-top: 15px; border-top: 1px dashed #ddd; text-align: center;">
                <small style="color: #888; display: block; margin-bottom: 10px;">ডেমো এক্সেস</small>
                <button type="button" style="border: 1px solid #fd7e14; color: #fd7e14; background: transparent; padding: 4px 12px; border-radius: 5px; cursor: pointer; margin-right: 5px;" onclick="fillDemoCreds('01631843149','12345678')">রিসেলার</button>
                <button type="button" style="border: 1px solid #17a2b8; color: #17a2b8; background: transparent; padding: 4px 12px; border-radius: 5px; cursor: pointer;" onclick="fillDemoCreds('01870829343','123456789')">ভেন্ডর</button>
            </div>
            @endif

        </div>
    </div>
</main>

{{-- জাভাস্ক্রিপ্ট স্ক্রিপ্টসমূহ --}}
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('loginPassword');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }

    function fillDemoCreds(login, pass) {
        document.getElementById('loginUser').value = login;
        document.getElementById('loginPassword').value = pass;
    }
</script>
@endsection