@extends('frontEnd.layouts.master')
@section('title', $blog->title)

@push('css')
<style>
/* ════════════════════════════
   BLOG DETAILS PAGE
════════════════════════════ */
.blog-details-page { padding: 0 0 48px; background: #f5f5f5; }

/* ── Hero ── */
.bd-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 32px 0 24px;
    margin-bottom: 28px;
}
.bd-hero .hero-breadcrumb {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; color: rgba(255,255,255,.75);
    margin-bottom: 12px;
}
.bd-hero .hero-breadcrumb a { color: rgba(255,255,255,.95); text-decoration: none; }
.bd-hero .hero-breadcrumb a:hover { color: #fff; }
.bd-hero .hero-breadcrumb span { opacity: .55; }
.bd-hero h1 { font-size: 24px; font-weight: 800; color: #fff; line-height: 1.45; margin: 0 0 10px; }
.bd-hero .bd-hero-meta {
    display: flex; align-items: center; gap: 18px; flex-wrap: wrap;
    font-size: 13px; color: rgba(255,255,255,.75);
}
.bd-hero .bd-hero-meta span { display: flex; align-items: center; gap: 5px; }

/* ── Article Card ── */
.bd-article {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
}
.bd-article-img {
    width: 100%;
    max-height: 440px;
    object-fit: cover;
    display: block;
}
.bd-article-body { padding: 28px 30px 32px; }

/* Content from WYSIWYG */
.bd-content { font-size: 15px; line-height: 1.9; color: #333; }
.bd-content h1,.bd-content h2,.bd-content h3,
.bd-content h4,.bd-content h5,.bd-content h6 {
    font-weight: 700; color: #111; margin: 24px 0 12px;
}
.bd-content h2 { font-size: 20px; }
.bd-content h3 { font-size: 18px; }
.bd-content p  { margin: 0 0 14px; }
.bd-content img { max-width: 100%; border-radius: 8px; margin: 8px 0; }
.bd-content a   { color: var(--primary); text-decoration: underline; }
.bd-content ul,.bd-content ol { padding-left: 22px; margin: 0 0 14px; }
.bd-content blockquote {
    border-left: 4px solid var(--primary);
    margin: 16px 0; padding: 12px 18px;
    background: #fef8f8; color: #555;
    border-radius: 0 6px 6px 0;
    font-style: italic;
}

/* ── Share Bar ── */
.bd-share {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    margin-top: 24px; padding-top: 20px;
    border-top: 1px solid #f0f0f0;
}
.bd-share-label { font-size: 13px; font-weight: 600; color: #555; }
.bd-share a {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 15px; border-radius: 20px;
    font-size: 13px; font-weight: 600; text-decoration: none;
    transition: opacity .2s;
}
.bd-share a:hover { opacity: .82; }
.bd-share .sh-fb  { background: #1877f2; color: #fff; }
.bd-share .sh-tw  { background: #1da1f2; color: #fff; }
.bd-share .sh-wa  { background: #25d366; color: #fff; }

/* ════════════════════════════
   SIDEBAR
════════════════════════════ */
.bd-sidebar { display: flex; flex-direction: column; gap: 20px; }

.bd-widget {
    background: #fff; border-radius: 12px;
    overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06);
}
.bd-widget-head {
    padding: 14px 18px 12px;
    border-bottom: 1px solid #f0f0f0;
    font-size: 15px; font-weight: 700; color: #111;
    display: flex; align-items: center; gap: 8px;
}
.bd-widget-head::before {
    content: ''; display: inline-block;
    width: 4px; height: 18px;
    background: var(--primary); border-radius: 3px;
}

/* Recent Blog list */
.rb-list { list-style: none; margin: 0; padding: 8px 0; }
.rb-item { padding: 10px 16px; }
.rb-item + .rb-item { border-top: 1px solid #f7f7f7; }
.rb-item a { display: flex; gap: 12px; text-decoration: none; align-items: flex-start; }
.rb-thumb {
    width: 72px; height: 58px; object-fit: cover;
    border-radius: 6px; flex-shrink: 0;
}
.rb-info-title {
    font-size: 13px; font-weight: 600; color: #222; line-height: 1.4;
    display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
}
.rb-item a:hover .rb-info-title { color: var(--primary); }
.rb-info-meta {
    font-size: 11px; color: #999; margin-top: 4px;
    display: flex; align-items: center; gap: 6px;
}

/* Back to Blogs button */
.bd-back-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 10px 22px; border-radius: 24px;
    font-size: 14px; font-weight: 600;
    background: var(--primary); color: #fff; text-decoration: none;
    transition: opacity .2s;
    margin-top: 20px;
}
.bd-back-btn:hover { opacity: .88; color: #fff; }

/* ── Responsive ── */
@media(max-width: 767px) {
    .bd-hero h1 { font-size: 18px; }
    .bd-article-body { padding: 18px 16px 22px; }
}
</style>
@endpush

@section('content')

@php
    $wordCount = str_word_count(strip_tags($blog->description ?? ''));
    $readMin   = max(1, round($wordCount / 200));
    $shareUrl  = urlencode(url()->current());
    $shareTitle = urlencode($blog->title);
@endphp

{{-- Hero --}}
<div class="bd-hero">
    <div class="container">
        <nav class="hero-breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> হোম</a>
            <span>/</span>
            <a href="{{ route('blogs') }}">ব্লগ</a>
            <span>/</span>
            <span>{{ Str::limit($blog->title, 42) }}</span>
        </nav>
        <h1>{{ $blog->title }}</h1>
        <div class="bd-hero-meta">
            <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('d M, Y') }}</span>
            <span><i class="far fa-eye"></i> {{ number_format($blog->views ?? 0) }} ভিউ</span>
            <span><i class="far fa-clock"></i> {{ $readMin }} মিনিট পড়া</span>
        </div>
    </div>
</div>

<section class="blog-details-page">
    <div class="container">
        <div class="row">

            {{-- Main Content --}}
            <div class="col-lg-8 col-md-7 mb-4">
                <article class="bd-article">

                    {{-- Cover Image --}}
                    <img src="{{ $blog->image ? url('public/'.$blog->image) : url('public/no-image.png') }}"
                         class="bd-article-img"
                         alt="{{ $blog->title }}">

                    <div class="bd-article-body">

                        {{-- Content --}}
                        <div class="bd-content">
                            {!! $blog->description !!}
                        </div>

                        {{-- Share --}}
                        <div class="bd-share">
                            <span class="bd-share-label">শেয়ার করুন:</span>
                            <a class="sh-fb"
                               href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                               target="_blank" rel="noopener">
                                <i class="fab fa-facebook-f"></i> Facebook
                            </a>
                            <a class="sh-tw"
                               href="https://twitter.com/intent/tweet?url={{ $shareUrl }}&text={{ $shareTitle }}"
                               target="_blank" rel="noopener">
                                <i class="fab fa-twitter"></i> Twitter
                            </a>
                            <a class="sh-wa"
                               href="https://wa.me/?text={{ $shareTitle }}%20{{ $shareUrl }}"
                               target="_blank" rel="noopener">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>

                    </div>
                </article>

                <a href="{{ route('blogs') }}" class="bd-back-btn">
                    <i class="fas fa-arrow-left"></i> সব ব্লগ দেখুন
                </a>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 col-md-5 mb-4">
                <aside class="bd-sidebar">

                    {{-- Recent Blogs Widget --}}
                    <div class="bd-widget">
                        <div class="bd-widget-head">সাম্প্রতিক পোস্ট</div>
                        <ul class="rb-list">
                            @forelse($recentBlogs as $rb)
                            <li class="rb-item">
                                <a href="{{ route('blog.details', $rb->slug) }}">
                                    <img class="rb-thumb"
                                         src="{{ $rb->image ? url('public/'.$rb->image) : url('public/no-image.png') }}"
                                         alt="{{ $rb->title }}">
                                    <div>
                                        <div class="rb-info-title">{{ Str::limit($rb->title, 60) }}</div>
                                        <div class="rb-info-meta">
                                            <i class="far fa-calendar-alt"></i>
                                            {{ $rb->created_at->format('d M Y') }}
                                            &middot;
                                            <i class="far fa-eye"></i>
                                            {{ number_format($rb->views ?? 0) }}
                                        </div>
                                    </div>
                                </a>
                            </li>
                            @empty
                            <li class="rb-item" style="color:#aaa;font-size:13px;text-align:center;padding:20px;">
                                কোনো পোস্ট নেই
                            </li>
                            @endforelse
                        </ul>
                    </div>

                </aside>
            </div>

        </div>
    </div>
</section>
@endsection
