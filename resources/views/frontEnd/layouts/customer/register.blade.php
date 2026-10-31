@extends('frontEnd.layouts.master')
@section('title','Customer Register')

@php
    $generalsetting = \App\Models\GeneralSetting::first();
@endphp

@section('content')
<main class="app-main auth-main">
    <div class="auth-shell">
        <div class="auth-card">
            


            <div class="auth-card-head">
                <h1>রেজিস্টার করুন</h1>
                <p>কয়েকটি তথ্য দিয়ে অ্যাকাউন্ট খুলে নিন</p>
            </div>
            
            {{-- লারাভেল ফর্ম ইন্টিগ্রেশন --}}
            <form class="auth-form" id="registerForm" action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- পুরো নাম --}}
                <div class="auth-field">
                    <label for="regName">পুরো নাম <span id="owner_name_label" style="display: none; color: #888; font-size: 12px;">(মালিকের নাম)</span></label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-user"></i>
                        <input type="text" id="regName" name="name" value="{{ old('name') }}" class="@error('name') is-invalid @enderror" placeholder="আপনার নাম" required>
                    </div>
                    @error('name')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- মোবাইল নম্বর --}}
                <div class="auth-field">
                    <label for="regPhone">মোবাইল নম্বর</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="regPhone" name="phone" value="{{ old('phone') }}" class="@error('phone') is-invalid @enderror" placeholder="01XXXXXXXXX" required>
                    </div>
                    @error('phone')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- ইমেইল --}}
                <div class="auth-field">
                    <label for="regEmail">ইমেইল <em id="email_optional_text">(ঐচ্ছিক)</em></label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="regEmail" name="email" value="{{ old('email') }}" class="@error('email') is-invalid @enderror" placeholder="email@example.com">
                    </div>
                    @error('email')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                {{-- রিসেলার চেকবক্স --}}
                @if(($generalsetting?->reseller_enabled ?? 1) == 1)
                <label class="auth-check auth-check-block" style="background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                    <input type="checkbox" id="is_reseller" name="is_reseller" value="1" onchange="toggleResellerFields()" {{ old('is_reseller') ? 'checked' : '' }}>
                    <span style="font-weight: 600; color: #333;">আমি রিসেলার একাউন্ট তৈরি করতে চাই</span>
                </label>
                
                {{-- রিসেলার শপের নাম (হিডেন) --}}
                <div class="auth-field" id="reseller_shop_name_field" style="display: none;">
                    <label for="reseller_shop_name">শপের নাম <span class="text-danger">*</span></label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-store"></i>
                        <input type="text" id="reseller_shop_name" name="reseller_shop_name" value="{{ old('reseller_shop_name') }}" class="@error('reseller_shop_name') is-invalid @enderror" placeholder="আপনার শপের নাম">
                    </div>
                    @error('reseller_shop_name')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                @endif

                {{-- সেলার/ভেন্ডর চেকবক্স --}}
                @if(($generalsetting?->vendor_enabled ?? 1) == 1)
                <label class="auth-check auth-check-block" style="background: #f8f9fa; padding: 12px; border-radius: 8px; border: 1px solid #e9ecef; margin-bottom: 15px;">
                    <input type="checkbox" id="is_seller" name="is_seller" value="1" onchange="toggleSellerFields()" {{ old('is_seller') ? 'checked' : '' }}>
                    <span style="font-weight: 600; color: #333;">আমি সেলার একাউন্ট তৈরি করতে চাই</span>
                </label>

                {{-- সেলার এক্সট্রা ফিল্ডস (হিডেন) --}}
                <div id="seller_extra_fields" style="display: none;">
                    <div class="auth-field">
                        <label for="shop_name">শপের নাম <span class="text-danger">*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-store"></i>
                            <input type="text" id="shop_name" name="shop_name" value="{{ old('shop_name') }}" placeholder="আপনার শপের নাম লিখুন">
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="slug">শপ URL (Slug) <span class="text-danger">*</span></label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-link"></i>
                            <input type="text" id="slug" name="slug" value="{{ old('slug') }}" placeholder="my-shop-name">
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="address">ঠিকানা</label>
                        <div class="auth-input-wrap">
                            <i class="fas fa-map-marker-alt"></i>
                            <input type="text" id="address" name="address" value="{{ old('address') }}" placeholder="আপনার শপের ঠিকানা">
                        </div>
                    </div>
                </div>
                @endif

                {{-- ভেরিফিকেশন মেসেজ --}}
                <div id="verification_note" style="display: none; padding: 12px; margin-bottom: 15px; border-radius: 8px; background: #e0f2fe; color: #0369a1; font-size: 13px; border: 1px solid #bae6fd;">
                    <i class="fas fa-info-circle"></i> একাউন্ট খোলার পর ড্যাশবোর্ড থেকে এনআইডি ও অন্যান্য তথ্য দিয়ে ভেরিফিকেশন সম্পন্ন করতে হবে।
                </div>
                
                {{-- পাসওয়ার্ড --}}
                <div class="auth-field">
                    <label for="regPassword">পাসওয়ার্ড</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="regPassword" name="password" class="@error('password') is-invalid @enderror" placeholder="কমপক্ষে ৬ অক্ষর" required minlength="6">
                        <button type="button" class="auth-toggle-pass" data-target="regPassword" aria-label="পাসওয়ার্ড দেখুন">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                    @error('password')
                        <span class="text-danger d-block mt-1" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                
                {{-- পাসওয়ার্ড নিশ্চিত করুন --}}
                <div class="auth-field">
                    <label for="regConfirm">পাসওয়ার্ড নিশ্চিত করুন</label>
                    <div class="auth-input-wrap">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="regConfirm" name="password_confirmation" placeholder="আবার পাসওয়ার্ড" required>
                        <button type="button" class="auth-toggle-pass" data-target="regConfirm" aria-label="পাসওয়ার্ড দেখুন">
                            <i class="far fa-eye"></i>
                        </button>
                    </div>
                </div>
                
                <label class="auth-check auth-check-block mb-3">
                    <input type="checkbox" id="regTerms" required checked>
                    <span>আমি <a href="#">শর্তাবলী</a> ও <a href="#">গোপনীয়তা নীতি</a> মেনে নিচ্ছি</span>
                </label>
                
                <button type="submit" class="auth-submit">অ্যাকাউন্ট তৈরি করুন</button>
            </form>
            
            <p class="auth-switch">ইতিমধ্যে অ্যাকাউন্ট আছে? <a href="{{ route('customer.login') }}">লগইন করুন</a></p>
        </div>
    </div>
</main>

{{-- জাভাস্ক্রিপ্ট --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // পাসওয়ার্ড শো/হাইড লজিক
        const toggleButtons = document.querySelectorAll('.auth-toggle-pass');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // অটো URL Slug জেনারেট লজিক
        const shopNameInput = document.getElementById('shop_name');
        const slugInput = document.getElementById('slug');
        if (shopNameInput && slugInput) {
            shopNameInput.addEventListener('input', function() {
                let slug = this.value.toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .trim();
                slugInput.value = slug;
            });
        }

        // পেজ লোড হওয়ার সময় চেক করা
        if (document.getElementById("is_seller")?.checked) toggleSellerFields();
        if (document.getElementById("is_reseller")?.checked) toggleResellerFields();
    });

    // রিসেলার টগল ফাংশন
    function toggleResellerFields() {
        const isReseller = document.getElementById("is_reseller").checked;
        const shopNameField = document.getElementById("reseller_shop_name_field");
        const shopNameInput = document.getElementById("reseller_shop_name");
        const emailOptionalText = document.getElementById("email_optional_text");
        const emailInput = document.getElementById("regEmail");
        const verificationNote = document.getElementById("verification_note");

        if (shopNameField) shopNameField.style.display = isReseller ? 'block' : 'none';
        if (shopNameInput) isReseller ? shopNameInput.setAttribute('required', 'required') : shopNameInput.removeAttribute('required');
        
        // রিসেলার হলে ইমেইল বাধ্যতামূলক
        if (emailInput) {
            if (isReseller) {
                emailInput.setAttribute('required', 'required');
                emailOptionalText.style.display = 'none';
            } else if (!document.getElementById("is_seller")?.checked) {
                emailInput.removeAttribute('required');
                emailOptionalText.style.display = 'inline';
            }
        }

        if (verificationNote) verificationNote.style.display = isReseller ? 'block' : 'none';

        // রিসেলার চেক করলে সেলার আনচেক হবে
        if (isReseller) {
            const sellerCheckbox = document.getElementById("is_seller");
            if (sellerCheckbox && sellerCheckbox.checked) {
                sellerCheckbox.checked = false;
                toggleSellerFields();
            }
        }
    }

    // সেলার টগল ফাংশন
    function toggleSellerFields() {
        const isSeller = document.getElementById("is_seller").checked;
        const extraFields = document.getElementById("seller_extra_fields");
        const shopNameInput = document.getElementById("shop_name");
        const slugInput = document.getElementById("slug");
        const ownerLabel = document.getElementById("owner_name_label");
        const emailOptionalText = document.getElementById("email_optional_text");
        const emailInput = document.getElementById("regEmail");
        const verificationNote = document.getElementById("verification_note");

        if (extraFields) extraFields.style.display = isSeller ? 'block' : 'none';
        if (ownerLabel) ownerLabel.style.display = isSeller ? 'inline' : 'none';
        
        if (shopNameInput) isSeller ? shopNameInput.setAttribute('required', 'required') : shopNameInput.removeAttribute('required');
        if (slugInput) isSeller ? slugInput.setAttribute('required', 'required') : slugInput.removeAttribute('required');

        // সেলার হলে ইমেইল বাধ্যতামূলক
        if (emailInput) {
            if (isSeller) {
                emailInput.setAttribute('required', 'required');
                emailOptionalText.style.display = 'none';
            } else if (!document.getElementById("is_reseller")?.checked) {
                emailInput.removeAttribute('required');
                emailOptionalText.style.display = 'inline';
            }
        }

        if (verificationNote) verificationNote.style.display = isSeller ? 'block' : 'none';

        // সেলার চেক করলে রিসেলার আনচেক হবে
        if (isSeller) {
            const resellerCheckbox = document.getElementById("is_reseller");
            if (resellerCheckbox && resellerCheckbox.checked) {
                resellerCheckbox.checked = false;
                toggleResellerFields();
            }
        }
    }
</script>
@endsection