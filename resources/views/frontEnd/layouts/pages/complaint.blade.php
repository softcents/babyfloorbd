@extends('frontEnd.layouts.master')
@section('title', 'কমপ্লেইন')

@section('content')

{{-- Hero --}}
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <div class="page-hero-icon"><i class="fas fa-headset"></i></div>
            <div>
                <h1>কমপ্লেইন করুন</h1>
                <nav class="page-hero-breadcrumb">
                    <a href="{{ route('home') }}">হোম</a>
                    <span>/</span>
                    <strong>কমপ্লেইন</strong>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="service-page-section">
    <div class="container">

        @if(session('success'))
            <div class="complaint-alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="complaint-layout">

            {{-- Left: Info Panel --}}
            <aside class="complaint-info-panel">
                <div class="complaint-info-head">
                    <div class="complaint-info-icon">
                        <i class="fas fa-life-ring"></i>
                    </div>
                    <h3>আমাদের সাথে যোগাযোগ করুন</h3>
                    <p>যেকোনো সমস্যায় আমরা আপনার পাশে আছি। আপনার অভিযোগ জমা দিন, আমরা দ্রুত সমাধান দেবো।</p>
                </div>

                <div class="complaint-contact-list">
                    @if(optional($contact)->hotline)
                    <a href="tel:{{ $contact->hotline }}" class="complaint-contact-item">
                        <div class="cci-icon"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <span class="cci-label">হটলাইন</span>
                            <span class="cci-value">{{ $contact->hotline }}</span>
                        </div>
                    </a>
                    @endif

                    @if(optional($contact)->email)
                    <a href="mailto:{{ $contact->email }}" class="complaint-contact-item">
                        <div class="cci-icon"><i class="fas fa-envelope"></i></div>
                        <div>
                            <span class="cci-label">ইমেইল</span>
                            <span class="cci-value">{{ $contact->email }}</span>
                        </div>
                    </a>
                    @endif

                    @if(optional($contact)->address)
                    <div class="complaint-contact-item">
                        <div class="cci-icon"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <span class="cci-label">ঠিকানা</span>
                            <span class="cci-value">{{ $contact->address }}</span>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="complaint-guarantee">
                    <div class="cg-item">
                        <i class="fas fa-shield-alt"></i>
                        <span>নিরাপদ ডাটা</span>
                    </div>
                    <div class="cg-item">
                        <i class="fas fa-clock"></i>
                        <span>২৪-৪৮ ঘণ্টার সমাধান</span>
                    </div>
                    <div class="cg-item">
                        <i class="fas fa-user-check"></i>
                        <span>বিশ্বস্ত সেবা</span>
                    </div>
                </div>
            </aside>

            {{-- Right: Form --}}
            <div class="complaint-form-panel">
                <div class="complaint-form-header">
                    <h2><i class="fas fa-edit"></i> কমপ্লেইন ফর্ম</h2>
                    <p>নিচের ফর্মটি পূরণ করুন। সকল (*) চিহ্নিত ঘর পূরণ বাধ্যতামূলক।</p>
                </div>

                <form action="{{ route('complaint.store') }}" method="POST" enctype="multipart/form-data"
                      class="complaint-form" id="complaintForm">
                    @csrf

                    <div class="cf-row-2">
                        <div class="cf-group">
                            <label class="cf-label">আপনার নাম <span>*</span></label>
                            <div class="cf-input-wrap">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name" placeholder="আপনার নাম লিখুন"
                                       value="{{ old('name') }}" required>
                            </div>
                            @error('name')<p class="cf-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="cf-group">
                            <label class="cf-label">মোবাইল নম্বর <span>*</span></label>
                            <div class="cf-input-wrap">
                                <i class="fas fa-mobile-alt"></i>
                                <input type="tel" name="phone" placeholder="০১xxx-xxxxxx"
                                       value="{{ old('phone') }}" required maxlength="11"
                                       oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                            </div>
                            @error('phone')<p class="cf-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="cf-group">
                        <label class="cf-label">অর্ডার আইডি <span class="cf-optional">(ঐচ্ছিক)</span></label>
                        <div class="cf-input-wrap">
                            <i class="fas fa-hashtag"></i>
                            <input type="text" name="order_id" placeholder="Order ID লিখুন"
                                   value="{{ old('order_id') }}"
                                   oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                        </div>
                    </div>

                    <div class="cf-group">
                        <label class="cf-label">কমপ্লেইনের বিবরণ <span>*</span></label>
                        <div class="cf-textarea-wrap">
                            <textarea name="description" rows="5"
                                      placeholder="আপনার সমস্যাটি বিস্তারিত লিখুন..."
                                      required>{{ old('description') }}</textarea>
                        </div>
                        @error('description')<p class="cf-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="cf-group">
                        <label class="cf-label">প্রমাণস্বরূপ ছবি <span class="cf-optional">(ঐচ্ছিক)</span></label>
                        <div class="cf-file-wrap" id="fileWrap">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <p>ছবি টেনে আনুন বা <span>ব্রাউজ করুন</span></p>
                            <small>JPG, JPEG, PNG — সর্বোচ্চ 2MB</small>
                            <input type="file" name="image" accept=".jpg,.jpeg,.png"
                                   id="fileInput" style="position:absolute;inset:0;opacity:0;cursor:pointer;">
                        </div>
                        <p class="cf-file-name" id="fileName" style="display:none;margin-top:6px;font-size:13px;color:#666;">
                            <i class="fas fa-file-image me-1"></i><span></span>
                        </p>
                        @error('image')<p class="cf-error">{{ $message }}</p>@enderror
                    </div>

                    <button type="submit" class="cf-submit-btn">
                        <i class="fas fa-paper-plane"></i>
                        কমপ্লেইন পাঠান
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
.complaint-alert-success {
    display: flex; align-items: center; gap: 12px;
    background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;
    padding: 14px 20px; margin-bottom: 24px;
    color: #166534; font-size: 14px; font-weight: 600;
}
.complaint-alert-success i { font-size: 18px; color: #22c55e; }

.complaint-layout {
    display: grid;
    grid-template-columns: 340px 1fr;
    gap: 28px;
    align-items: start;
}

/* Info Panel */
.complaint-info-panel {
    background: var(--primary);
    border-radius: 18px;
    padding: 32px 28px;
    color: #fff;
    position: sticky;
    top: calc(var(--notice-height, 0px) + 90px);
}
.complaint-info-head { margin-bottom: 28px; }
.complaint-info-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,0.15);
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px; margin-bottom: 16px;
}
.complaint-info-head h3 { font-size: 18px; font-weight: 700; margin-bottom: 10px; }
.complaint-info-head p { font-size: 13px; opacity: 0.85; line-height: 1.7; }

.complaint-contact-list { display: flex; flex-direction: column; gap: 12px; margin-bottom: 28px; }
.complaint-contact-item {
    display: flex; align-items: center; gap: 14px;
    background: rgba(255,255,255,0.12);
    border-radius: 12px; padding: 14px 16px;
    color: #fff; text-decoration: none;
    transition: background 0.2s;
}
.complaint-contact-item:hover { background: rgba(255,255,255,0.22); color: #fff; }
.cci-icon {
    width: 38px; height: 38px; border-radius: 10px;
    background: rgba(255,255,255,0.2);
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; flex-shrink: 0;
}
.cci-label { display: block; font-size: 11px; opacity: 0.7; }
.cci-value { display: block; font-size: 14px; font-weight: 600; }

.complaint-guarantee {
    display: flex; flex-direction: column; gap: 10px;
    border-top: 1px solid rgba(255,255,255,0.2);
    padding-top: 20px;
}
.cg-item { display: flex; align-items: center; gap: 10px; font-size: 13px; opacity: 0.85; }
.cg-item i { font-size: 15px; opacity: 0.9; }

/* Form Panel */
.complaint-form-panel {
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}
.complaint-form-header {
    padding: 24px 28px 20px;
    border-bottom: 1px solid #f0f0f0;
}
.complaint-form-header h2 {
    font-size: 18px; font-weight: 700; color: #1a1a2e;
    display: flex; align-items: center; gap: 10px; margin-bottom: 6px;
}
.complaint-form-header h2 i { color: var(--primary); }
.complaint-form-header p { font-size: 13px; color: #888; }

.complaint-form { padding: 28px; }
.cf-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.cf-group { margin-bottom: 20px; }
.cf-label {
    display: block; font-size: 13px; font-weight: 600;
    color: #444; margin-bottom: 8px;
}
.cf-label span { color: var(--primary); }
.cf-optional { color: #aaa; font-weight: 400; }

.cf-input-wrap {
    position: relative;
}
.cf-input-wrap i {
    position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
    color: #bbb; font-size: 14px; pointer-events: none;
}
.cf-input-wrap input {
    width: 100%;
    padding: 11px 14px 11px 40px;
    border: 1.5px solid #eee;
    border-radius: 10px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    outline: none;
    transition: all 0.2s;
    font-family: inherit;
}
.cf-input-wrap input:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.08);
}

.cf-textarea-wrap textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #eee;
    border-radius: 10px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    outline: none;
    resize: vertical;
    transition: all 0.2s;
    font-family: inherit;
    line-height: 1.6;
}
.cf-textarea-wrap textarea:focus {
    border-color: var(--primary);
    background: #fff;
    box-shadow: 0 0 0 3px rgba(var(--primary-rgb), 0.08);
}

.cf-file-wrap {
    position: relative;
    border: 2px dashed #e0e0e0;
    border-radius: 10px;
    padding: 28px 20px;
    text-align: center;
    background: #fafafa;
    cursor: pointer;
    transition: all 0.2s;
}
.cf-file-wrap:hover { border-color: var(--primary); background: #fff8f9; }
.cf-file-wrap i { font-size: 28px; color: #ccc; margin-bottom: 8px; display: block; }
.cf-file-wrap p { font-size: 13px; color: #888; margin: 0; }
.cf-file-wrap p span { color: var(--primary); font-weight: 600; }
.cf-file-wrap small { font-size: 11px; color: #bbb; }

.cf-error { font-size: 12px; color: var(--primary); margin-top: 5px; }

.cf-submit-btn {
    width: 100%;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 12px;
    padding: 14px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center; gap: 10px;
    letter-spacing: 0.3px;
    transition: all 0.2s;
    margin-top: 8px;
    font-family: inherit;
}
.cf-submit-btn:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(var(--primary-rgb), 0.3);
}

@media (max-width: 991px) {
    .complaint-layout { grid-template-columns: 1fr; }
    .complaint-info-panel { position: static; }
    .cf-row-2 { grid-template-columns: 1fr; }
    .complaint-form { padding: 20px; }
    .complaint-form-header { padding: 20px; }
}
@media (max-width: 480px) {
    .complaint-form { padding: 16px; }
}
</style>

@endsection

@push('script')
<script>
document.getElementById('fileInput').addEventListener('change', function () {
    var fn = document.getElementById('fileName');
    if (this.files.length) {
        fn.style.display = 'flex';
        fn.querySelector('span').textContent = this.files[0].name;
        document.querySelector('.cf-file-wrap').style.borderColor = 'var(--primary)';
    } else {
        fn.style.display = 'none';
    }
});
</script>
@endpush
