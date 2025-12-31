@extends('FrontEnd.layouts.master')

@section('title', trans_field($page, 'meta_title') ?: trans_field($page, 'title'))

@section('body')
    <!-- content section start -->
    <div class="container custom-container top-fixed-top-margin">
        <div class="row custom-row">
            {{-- Breadcrumb --}}
            <div class="col-md-12 custom-padding">
                <div class="category__breadcrumb mb-3">
                    <ul class="category__breadcrumb-list--main list-unstyled">
                        <li>
                            <a href="{{ url(currentLocale()) }}">
                                @if(currentLocale() == 'bn')
                                    হোম
                                @else
                                    Home
                                @endif
                            </a>
                            <span class="mx-2">/</span>
                        </li>
                        <li class="active">
                            <strong>{{ trans_field($page, 'title') }}</strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="row custom-row">
            {{-- Main Content (Left Side) --}}
            <div class="col-md-8 custom-padding">
                <div class="page-content-card">
                    {{-- Page Title --}}
                    <div class="page-header">
                        <h1 class="page-title">{{ trans_field($page, 'title') }}</h1>
                        <div class="page-meta">
                            <small class="text-muted">
                                <i class="fa-solid fa-calendar-alt"></i>
                                {{ $page->updated_at->format('d M Y') }}
                            </small>
                        </div>
                        <hr class="page-divider">
                    </div>

                    {{-- Page Content --}}
                    <div class="page-content-body">
                        {!! trans_field($page, 'content') !!}
                    </div>
                </div>
            </div>

            {{-- Sidebar (Right Side) --}}
            <div class="col-md-4 custom-padding">
                <div class="theiaStickySidebar">
                    {{-- Related Pages --}}
                    @if($relatedPages->count() > 0)
                        <div class="sidebar-section mb-4">
                            <div class="sports-main-heading sports-main-heading-v6">
                                <h2>
                                    @if(currentLocale() == 'bn')
                                        অন্যান্য পেজ
                                    @else
                                        Other Pages
                                    @endif
                                </h2>
                            </div>
                            <ul class="sports-ul-top">
                                @foreach($relatedPages as $relatedPage)
                                    <li>
                                        <a href="{{ url(currentLocale() . '/page/' . trans_slug($relatedPage)) }}">
                                            <div class="sports-ul-top-right">
                                                <h3>
                                                    <i class="fa-solid fa-file-lines me-2"></i>
                                                    {{ trans_field($relatedPage, 'title') }}
                                                </h3>
                                                @if(trans_field($relatedPage, 'meta_description'))
                                                    <p class="text-muted small mt-1">
                                                        {{ Str::limit(trans_field($relatedPage, 'meta_description'), 80) }}
                                                    </p>
                                                @endif
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Latest Posts --}}
                    @if($latestPosts->count() > 0)
                        <div class="sidebar-section">
                            <div class="sports-main-heading sports-main-heading-v6">
                                <h2>
                                    @if(currentLocale() == 'bn')
                                        সর্বশেষ সংবাদ
                                    @else
                                        Latest News
                                    @endif
                                </h2>
                            </div>
                            <ul class="sports-ul-top">
                                @foreach($latestPosts as $post)
                                    <li>
                                        <a href="{{ getPostUrl($post) }}">
                                            <div class="sports-ul-top-left">
                                                <div class="sports-ul-top-left-image">
                                                    <img src="{{ getPostImage($post) }}" 
                                                         class="img-fluid lazyload"
                                                         alt="{{ trans_field($post, 'title') }}" />
                                                </div>
                                            </div>
                                            <div class="sports-ul-top-right">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- content section end -->
@endsection

@section('style')
<style>
/* Page Content Card */
.page-content-card {
    background: #fff;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 30px;
}

/* Page Header */
.page-header {
    margin-bottom: 30px;
}

.page-title {
    font-size: 32px;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 15px;
    line-height: 1.3;
}

.page-meta {
    font-size: 14px;
    margin-bottom: 15px;
}

.page-divider {
    border-top: 2px solid #D12026;
    margin: 20px 0;
}

/* Page Content Body */
.page-content-body {
    font-size: 16px;
    line-height: 1.8;
    color: #333;
}

.page-content-body p {
    margin-bottom: 20px;
    text-align: justify;
}

.page-content-body h2 {
    font-size: 26px;
    font-weight: 700;
    color: #1a1a1a;
    margin-top: 35px;
    margin-bottom: 20px;
    border-bottom: 2px solid #D12026;
    padding-bottom: 10px;
}

.page-content-body h3 {
    font-size: 22px;
    font-weight: 600;
    color: #2a2a2a;
    margin-top: 30px;
    margin-bottom: 15px;
}

.page-content-body h4 {
    font-size: 18px;
    font-weight: 600;
    color: #3a3a3a;
    margin-top: 25px;
    margin-bottom: 12px;
}

.page-content-body ul,
.page-content-body ol {
    margin: 20px 0;
    padding-left: 30px;
}

.page-content-body li {
    margin-bottom: 12px;
    line-height: 1.7;
}

.page-content-body a {
    color: #D12026;
    text-decoration: none;
    font-weight: 500;
}

.page-content-body a:hover {
    text-decoration: underline;
}

.page-content-body img {
    max-width: 100%;
    height: auto;
    margin: 25px 0;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.page-content-body blockquote {
    border-left: 4px solid #D12026;
    padding: 15px 20px;
    margin: 25px 0;
    background: #f9f9f9;
    font-style: italic;
    color: #555;
    border-radius: 5px;
}

.page-content-body table {
    width: 100%;
    margin: 25px 0;
    border-collapse: collapse;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.page-content-body table th,
.page-content-body table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

.page-content-body table th {
    background: #D12026;
    color: #fff;
    font-weight: 600;
}

.page-content-body table tr:nth-child(even) {
    background: #f9f9f9;
}

.page-content-body strong {
    color: #1a1a1a;
    font-weight: 600;
}

.page-content-body em {
    color: #555;
}

/* Sidebar Section */
.sidebar-section {
    margin-bottom: 30px;
}

/* Breadcrumb Styling */
.category__breadcrumb-list--main {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    margin: 0;
    padding: 0;
    font-size: 14px;
}

.category__breadcrumb-list--main li {
    display: flex;
    align-items: center;
}

.category__breadcrumb-list--main li a {
    color: #D12026;
    text-decoration: none;
    font-weight: 500;
}

.category__breadcrumb-list--main li a:hover {
    text-decoration: underline;
}

.category__breadcrumb-list--main li.active strong {
    color: #333;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-content-card {
        padding: 20px 15px;
    }

    .page-title {
        font-size: 24px;
    }

    .page-content-body {
        font-size: 15px;
    }

    .page-content-body h2 {
        font-size: 22px;
    }

    .page-content-body h3 {
        font-size: 18px;
    }

    .page-content-body h4 {
        font-size: 16px;
    }
}
</style>
@endsection
