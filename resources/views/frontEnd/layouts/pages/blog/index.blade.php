@extends('frontEnd.layouts.master')
@section('title', 'ব্লগ')

@push('css')
<style>
.blog-page { padding: 28px 0 48px; background: #f5f5f5; }

/* ── Page Hero ── */
.blog-page-hero {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    padding: 36px 0 28px;
    margin-bottom: 28px;
    text-align: center;
}
.blog-page-hero h1 { font-size: 28px; font-weight: 800; color: #fff; margin: 0 0 8px; }
.blog-page-hero p  { font-size: 14px; color: rgba(255,255,255,.8); margin: 0 0 14px; }
.blog-page-hero .hero-breadcrumb {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 13px; color: rgba(255,255,255,.7);
}
.blog-page-hero .hero-breadcrumb a { color: rgba(255,255,255,.9); text-decoration: none; }
.blog-page-hero .hero-breadcrumb a:hover { color: #fff; }
.blog-page-hero .hero-breadcrumb span { opacity: .6; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="blog-page-hero">
    <div class="container">
        <h1><i class="fas fa-pen-nib" style="margin-right:8px;opacity:.85;"></i>সর্বশেষ ব্লগ</h1>
        <p>জ্ঞান, টিপস ও সর্বশেষ আপডেট পড়ুন</p>
        <nav class="hero-breadcrumb">
            <a href="{{ route('home') }}"><i class="fas fa-home"></i> হোম</a>
            <span>/</span>
            <span>ব্লগ</span>
        </nav>
    </div>
</div>

<section class="blog-page">
    <div class="container">

        {{-- Blog Grid --}}
        <div class="blog-grid">
            @forelse($blogs as $blog)
            @php
                $wordCount = str_word_count(strip_tags($blog->description ?? $blog->short_description ?? ''));
                $readMin   = max(1, round($wordCount / 200));
            @endphp
            <article class="blog-card">
                <a href="{{ route('blog.details', $blog->slug) }}" class="blog-card-img">
                    <img src="{{ $blog->image ? url('public/'.$blog->image) : url('public/no-image.png') }}"
                         alt="{{ $blog->title }}" loading="lazy">
                    <span class="blog-card-tag">ব্লগ</span>
                </a>
                <div class="blog-card-body">
                    <div class="blog-card-meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $blog->created_at->format('d M Y') }}</span>
                        <span><i class="far fa-eye"></i> {{ number_format($blog->views ?? 0) }}</span>
                    </div>
                    <h2 class="blog-card-title">
                        <a href="{{ route('blog.details', $blog->slug) }}">{{ Str::limit($blog->title, 65) }}</a>
                    </h2>
                    <p class="blog-card-excerpt">{{ Str::limit($blog->short_description, 130) }}</p>
                    <div class="blog-card-footer">
                        <a href="{{ route('blog.details', $blog->slug) }}" class="blog-read-more">
                            বিস্তারিত <i class="fas fa-arrow-right"></i>
                        </a>
                        <span class="blog-read-time"><i class="far fa-clock"></i> {{ $readMin }} মিনিট</span>
                    </div>
                </div>
            </article>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px 0;color:#aaa;">
                <i class="fas fa-blog" style="font-size:48px;display:block;margin-bottom:14px;opacity:.3;"></i>
                <p style="font-size:15px;">কোনো ব্লগ পাওয়া যায়নি।</p>
            </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($blogs->hasPages())
        <div style="display:flex;justify-content:center;margin-top:36px;">
            {{ $blogs->links('pagination::bootstrap-4') }}
        </div>
        @endif

    </div>
</section>
@endsection
