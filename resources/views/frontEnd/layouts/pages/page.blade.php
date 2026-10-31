@extends('frontEnd.layouts.master')
@section('title', $page->title ?? 'পেজ')

@section('content')

{{-- Hero --}}
<section class="page-hero">
    <div class="container">
        <div class="page-hero-inner">
            <div class="page-hero-icon"><i class="fas fa-file-alt"></i></div>
            <div>
                <h1>{{ $page->title }}</h1>
                <nav class="page-hero-breadcrumb">
                    <a href="{{ route('home') }}">হোম</a>
                    <span>/</span>
                    <strong>{{ $page->title }}</strong>
                </nav>
            </div>
        </div>
    </div>
</section>

<section class="service-page-section">
    <div class="container">
        <div class="static-page-layout">

            {{-- Sidebar: other pages --}}
            <aside class="static-page-sidebar">
                <div class="sps-head">
                    <i class="fas fa-layer-group"></i> সকল পেজ
                </div>
                <ul class="sps-list">
                    @foreach($cmnmenu as $item)
                        <li>
                            <a href="{{ route('page', $item->slug) }}"
                               class="{{ $item->slug === ($page->slug ?? '') ? 'active' : '' }}">
                                <i class="fas fa-chevron-right"></i>
                                {{ $item->name }}
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('contact') }}">
                            <i class="fas fa-chevron-right"></i>
                            যোগাযোগ করুন
                        </a>
                    </li>
                </ul>
            </aside>

            {{-- Main Content --}}
            <article class="static-page-content">
                <div class="spc-header">
                    <h2>{{ $page->title }}</h2>
                </div>
                <div class="spc-body prose-content">
                    {!! $page->description !!}
                </div>
            </article>

        </div>
    </div>
</section>

<style>
.static-page-layout {
    display: grid;
    grid-template-columns: 240px 1fr;
    gap: 28px;
    align-items: start;
}

/* Sidebar */
.static-page-sidebar {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
    position: sticky;
    top: calc(var(--notice-height, 0px) + 90px);
}
.sps-head {
    background: var(--primary);
    color: #fff;
    padding: 14px 18px;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sps-list {
    list-style: none;
    padding: 8px 0;
    margin: 0;
}
.sps-list li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 11px 18px;
    font-size: 13px;
    color: #555;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}
.sps-list li a i {
    font-size: 10px;
    color: #ccc;
    flex-shrink: 0;
    transition: color 0.2s;
}
.sps-list li a:hover,
.sps-list li a.active {
    background: #fff5f7;
    color: var(--primary);
    border-left-color: var(--primary);
}
.sps-list li a:hover i,
.sps-list li a.active i { color: var(--primary); }

/* Content */
.static-page-content {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    overflow: hidden;
    min-height: 400px;
}
.spc-header {
    padding: 22px 28px 18px;
    border-bottom: 1px solid #f0f0f0;
}
.spc-header h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
    position: relative;
    padding-left: 14px;
}
.spc-header h2::before {
    content: '';
    position: absolute;
    left: 0; top: 4px; bottom: 4px;
    width: 4px;
    background: var(--primary);
    border-radius: 4px;
}
.spc-body {
    padding: 28px;
}

/* Prose typography */
.prose-content h1, .prose-content h2, .prose-content h3,
.prose-content h4, .prose-content h5 {
    color: #1a1a2e;
    font-weight: 700;
    margin: 20px 0 10px;
    line-height: 1.4;
}
.prose-content h2 { font-size: 18px; }
.prose-content h3 { font-size: 16px; }
.prose-content p {
    font-size: 14px;
    color: #555;
    line-height: 1.8;
    margin-bottom: 14px;
}
.prose-content ul, .prose-content ol {
    padding-left: 20px;
    margin-bottom: 14px;
}
.prose-content li {
    font-size: 14px;
    color: #555;
    line-height: 1.8;
    margin-bottom: 4px;
}
.prose-content a {
    color: var(--primary);
    text-decoration: underline;
}
.prose-content img {
    max-width: 100%;
    border-radius: 8px;
    margin: 12px 0;
}
.prose-content table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 16px;
    font-size: 14px;
}
.prose-content table th,
.prose-content table td {
    border: 1px solid #e5e7eb;
    padding: 10px 14px;
    text-align: left;
}
.prose-content table th {
    background: #f9fafb;
    font-weight: 600;
    color: #333;
}
.prose-content blockquote {
    border-left: 4px solid var(--primary);
    background: #fff5f7;
    padding: 12px 18px;
    border-radius: 0 8px 8px 0;
    margin: 16px 0;
    font-style: italic;
    color: #666;
}
.prose-content strong { color: #222; }
.prose-content hr {
    border: none;
    border-top: 1px solid #eee;
    margin: 24px 0;
}

@media (max-width: 768px) {
    .static-page-layout {
        grid-template-columns: 1fr;
    }
    .static-page-sidebar { position: static; }
    .spc-body { padding: 18px; }
    .spc-header { padding: 18px; }
}
</style>

@endsection
