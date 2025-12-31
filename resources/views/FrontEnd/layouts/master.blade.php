<!DOCTYPE html>
<html lang="{{ currentLocale() }}">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

@php
    // Ensure websiteSettings is always available
    if (!isset($websiteSettings)) {
        $websiteSettings = \App\Models\WebsiteSetting::getSettings();
    }

    // Check if we're on the homepage
    $isHomepage = request()->route() && in_array(request()->route()->getName(), ['home.index', 'home']);

    // Determine page type and set SEO variables
    $pageTitle = '';
    $metaDescription = '';
    $metaKeywords = '';
    $ogTitle = '';
    $ogDescription = '';
    $ogImage = '';
    $ogType = 'website';

    if ($isHomepage) {
        // HOMEPAGE - Force homepage settings even if $post exists
        $pageTitle = $websiteSettings->meta_title ?? $websiteSettings->website_title ?? 'আমাদের দেশ';
        $metaDescription = $websiteSettings->meta_description ?? '';
        $metaKeywords = $websiteSettings->meta_keywords ?? '';
        $ogTitle = $websiteSettings->meta_title ?? $websiteSettings->website_title ?? 'আমাদের দেশ';
        $ogDescription = $websiteSettings->meta_description ?? '';
        $ogImage = $websiteSettings->og_image ? asset($websiteSettings->og_image) : asset('images/logo.png');
    } elseif (isset($post) && request()->route()->getName() === 'post.show') {
        // SINGLE POST PAGE (only if we're actually on a post page)
        $pageTitle = trans_field($post, 'meta_title') ?: trans_field($post, 'title');
        $metaDescription = trans_field($post, 'meta_description') ?: trans_field($post, 'excerpt') ?: Str::limit(strip_tags(trans_field($post, 'content')), 160);
        $metaKeywords = trans_field($post, 'meta_keywords') ?: '';
        $ogTitle = trans_field($post, 'title');
        $ogDescription = trans_field($post, 'excerpt') ?: Str::limit(strip_tags(trans_field($post, 'content')), 200);
        $ogImage = !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset($websiteSettings->og_image ?? 'images/logo.png');
        $ogType = 'article';
    } elseif (isset($page)) {
        // CUSTOM PAGE (About, Contact, etc.)
        $pageTitle = trans_field($page, 'meta_title') ?: trans_field($page, 'title');
        $metaDescription = trans_field($page, 'meta_description') ?: Str::limit(strip_tags(trans_field($page, 'content')), 160);
        $metaKeywords = '';
        $ogTitle = trans_field($page, 'title');
        $ogDescription = trans_field($page, 'meta_description') ?: Str::limit(strip_tags(trans_field($page, 'content')), 200);
        $ogImage = asset($websiteSettings->og_image ?? 'images/logo.png');
    } elseif (isset($category)) {
        // CATEGORY PAGE
        $pageTitle = trans_field($category, 'meta_title') ?: trans_field($category, 'name');
        $metaDescription = trans_field($category, 'meta_description') ?: trans_field($category, 'description') ?: '';
        $metaKeywords = trans_field($category, 'meta_keywords') ?: '';
        $ogTitle = trans_field($category, 'name');
        $ogDescription = trans_field($category, 'description') ?: '';
        $ogImage = !empty($category->image) ? asset('storage/' . $category->image) : asset($websiteSettings->og_image ?? 'images/logo.png');
    } elseif (isset($tag)) {
        // TAG PAGE
        $pageTitle = trans_field($tag, 'meta_title') ?: trans_field($tag, 'name');
        $metaDescription = trans_field($tag, 'meta_description') ?: '';
        $metaKeywords = trans_field($tag, 'meta_keywords') ?: '';
        $ogTitle = trans_field($tag, 'name');
        $ogDescription = trans_field($tag, 'meta_description') ?: '';
        $ogImage = asset($websiteSettings->og_image ?? 'images/logo.png');
    } else {
        // DEFAULT (Other pages)
        $pageTitle = $websiteSettings->meta_title ?? $websiteSettings->website_title ?? 'আমাদের দেশ';
        $metaDescription = $websiteSettings->meta_description ?? '';
        $metaKeywords = $websiteSettings->meta_keywords ?? '';
        $ogTitle = $websiteSettings->meta_title ?? $websiteSettings->website_title ?? 'আমাদের দেশ';
        $ogDescription = $websiteSettings->meta_description ?? '';
        $ogImage = $websiteSettings->og_image ? asset($websiteSettings->og_image) : asset('images/logo.png');
    }
@endphp

<!-- Page Title -->
    <title>{{ $pageTitle }}</title>

    <!-- Meta Description & Keywords -->
    <meta name="description" content="{{ $metaDescription }}" />
    @if(!empty($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}" />
    @endif

<!-- Open Graph Meta Tags -->
    <meta property="og:title" content="{{ $ogTitle }}" />
    <meta property="og:type" content="{{ $ogType }}" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ $ogImage }}" />
    <meta property="og:description" content="{{ $ogDescription }}" />
    <meta property="og:site_name" content="{{ $websiteSettings->website_title ?? 'আমাদের দেশ' }}" />

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="{{ $ogTitle }}" />
    <meta name="twitter:description" content="{{ $ogDescription }}" />
    <meta name="twitter:image" content="{{ $ogImage }}" />

    <!-- Article Meta Tags (for posts only) -->
    @if(isset($post) && request()->route()->getName() === 'post.show')
        <meta property="article:published_time" content="{{ $post->published_at->toIso8601String() }}" />
        <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}" />
        @if($post->user)
            <meta property="article:author" content="{{ $post->user->name }}" />
        @endif
        @if($post->categories && $post->categories->count() > 0)
            @foreach($post->categories as $cat)
                <meta property="article:section" content="{{ trans_field($cat, 'name') }}" />
            @endforeach
        @endif
        @if(method_exists($post, 'tags') && $post->tags && $post->tags->count() > 0)
            @foreach($post->tags as $postTag)
                <meta property="article:tag" content="{{ trans_field($postTag, 'name') }}" />
            @endforeach
        @endif
    @endif

<!-- Google Site Verification -->
    @if(!empty($websiteSettings->google_verification))
        <meta name="google-site-verification" content="{{ $websiteSettings->google_verification }}">
    @endif

<!-- Additional Head Code (Custom Scripts, Schema, etc.) -->
    @if(!empty($websiteSettings->additional_head_code))
        {!! $websiteSettings->additional_head_code !!}
    @endif

<!-- Favicon -->
    @if(isset($logoSettings) && !empty($logoSettings->favicon))
        <link rel="shortcut icon" href="{{ asset('storage/' . $logoSettings->favicon) }}" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @endif

<!-- Fonts & Styles -->
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Bengali:wght@100..900&display=swap" rel="stylesheet"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
    <link rel="stylesheet" href="{{ asset('FrontEnd/css/style.css') }}"/>
    @yield('style')
</head>
<body>
<!-- header section -->
@include('FrontEnd.layouts.header')

<!-- Navbar section -->
@include('FrontEnd.layouts.navbar')

<!-- hamburger section -->
@include('FrontEnd.layouts.hamburger')

@yield('body')

<!-- Footer section -->
@include('FrontEnd.layouts.footer')

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('FrontEnd/js/main.js') }}"></script>
@yield('script')
</body>
</html>
