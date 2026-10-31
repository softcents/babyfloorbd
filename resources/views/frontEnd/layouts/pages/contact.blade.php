@extends('frontEnd.layouts.master')
@section('title', 'যোগাযোগ করুন')

@section('content')

<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <div class="page-hero-icon"><i class="fas fa-headset"></i></div>
            <div>
                <h1>যোগাযোগ করুন</h1>
                <nav class="page-hero-breadcrumb">
                    <a href="{{ route('home') }}">হোম</a>
                    <span>/</span>
                    <strong>যোগাযোগ</strong>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="service-page-section">
    <div class="container">
        <div class="contact-wrap">

            {{-- Left: Info --}}
            <aside class="contact-info-side">
                <h3>আমাদের সাথে যোগাযোগ করুন</h3>
                <p>আপনার যেকোনো প্রশ্ন বা মতামতের জন্য আমাদের সাথে যোগাযোগ করুন। আমরা দ্রুত সাড়া দেবো।</p>

                <div class="contact-info-list">
                    @if(optional($contact)->hotline)
                    <a href="tel:{{ $contact->hotline }}" class="contact-info-item">
                        <span class="cii-icon"><i class="fas fa-phone-alt"></i></span>
                        <div>
                            <small>হটলাইন</small>
                            <strong>{{ $contact->hotline }}</strong>
                        </div>
                    </a>
                    @endif

                    @if(optional($contact)->email)
                    <a href="mailto:{{ $contact->email }}" class="contact-info-item">
                        <span class="cii-icon"><i class="fas fa-envelope"></i></span>
                        <div>
                            <small>ইমেইল</small>
                            <strong>{{ $contact->email }}</strong>
                        </div>
                    </a>
                    @endif

                    @if(optional($contact)->address)
                    <div class="contact-info-item">
                        <span class="cii-icon"><i class="fas fa-map-marker-alt"></i></span>
                        <div>
                            <small>ঠিকানা</small>
                            <strong>{{ $contact->address }}</strong>
                        </div>
                    </div>
                    @endif
                </div>
            </aside>

            {{-- Right: Form --}}
            <div class="contact-form-side">

                @if(session('success'))
                    <div class="contact-success">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('frontend.contact.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="status" value="0">

                    <div class="cf-row-2">
                        <div class="cf-group">
                            <label>নাম <span>*</span></label>
                            <input type="text" name="full_name" placeholder="আপনার নাম" required>
                        </div>
                        <div class="cf-group">
                            <label>মোবাইল <span>*</span></label>
                            <input type="tel" name="mobile" placeholder="০১xxx-xxxxxx" required>
                        </div>
                    </div>

                    <div class="cf-row-2">
                        <div class="cf-group">
                            <label>ইমেইল</label>
                            <input type="email" name="email" placeholder="example@mail.com">
                        </div>
                        <div class="cf-group">
                            <label>বিষয়</label>
                            <input type="text" name="subject" placeholder="বিষয় লিখুন">
                        </div>
                    </div>

                    <div class="cf-group">
                        <label>মেসেজ <span>*</span></label>
                        <textarea name="details" rows="5" placeholder="আপনার বার্তা লিখুন..." required></textarea>
                    </div>

                    <button type="submit" class="cf-btn">
                        <i class="fas fa-paper-plane"></i> মেসেজ পাঠান
                    </button>
                </form>
            </div>

        </div>
    </div>
</section>

<style>
.contact-wrap {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 28px;
    align-items: start;
}

/* Info Side */
.contact-info-side {
    background: var(--primary);
    border-radius: 16px;
    padding: 30px 24px;
    color: #fff;
    position: sticky;
    top: calc(var(--notice-height, 0px) + 90px);
}
.contact-info-side h3 {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 10px;
}
.contact-info-side > p {
    font-size: 13px;
    opacity: 0.85;
    line-height: 1.7;
    margin-bottom: 24px;
}
.contact-info-list { display: flex; flex-direction: column; gap: 12px; }
.contact-info-item {
    display: flex;
    align-items: center;
    gap: 14px;
    background: rgba(255,255,255,0.12);
    border-radius: 10px;
    padding: 13px 15px;
    color: #fff;
    text-decoration: none;
    transition: background 0.2s;
}
a.contact-info-item:hover { background: rgba(255,255,255,0.22); }
.cii-icon {
    width: 36px; height: 36px;
    background: rgba(255,255,255,0.2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 14px; flex-shrink: 0;
}
.contact-info-item small { display: block; font-size: 11px; opacity: 0.7; }
.contact-info-item strong { display: block; font-size: 13px; font-weight: 600; }

/* Form Side */
.contact-form-side {
    background: #fff;
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
}
.contact-success {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #166534;
    border-radius: 10px;
    padding: 12px 16px;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
}
.cf-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.cf-group { margin-bottom: 16px; }
.cf-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #444;
    margin-bottom: 6px;
}
.cf-group label span { color: var(--primary); }
.cf-group input,
.cf-group textarea {
    width: 100%;
    border: 1.5px solid #eee;
    border-radius: 9px;
    padding: 11px 13px;
    font-size: 14px;
    color: #333;
    background: #fafafa;
    outline: none;
    transition: border-color 0.2s;
    font-family: inherit;
    resize: vertical;
}
.cf-group input:focus,
.cf-group textarea:focus {
    border-color: var(--primary);
    background: #fff;
}
.cf-btn {
    width: 100%;
    background: var(--primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 13px;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-family: inherit;
    transition: background 0.2s;
}
.cf-btn:hover { background: var(--primary-dark); }

@media (max-width: 900px) {
    .contact-wrap { grid-template-columns: 1fr; }
    .contact-info-side { position: static; }
    .cf-row-2 { grid-template-columns: 1fr; }
}
@media (max-width: 480px) {
    .contact-form-side { padding: 18px; }
}
</style>

@endsection
