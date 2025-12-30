<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <meta name="description" content="{{ $seo->meta_description ?? '' }}" />
    <meta name="keywords" content="{{ $seo->meta_tags ?? '' }}" />

    <!-- Open Graph data -->
    <meta property="og:title" content="{{ $seo->meta_title ?? '' }}" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:image" content="{{ asset($generalsetting->og_baner ?? 'public/logo.png') }}" />
    <meta property="og:description" content="{{ $seo->meta_description ?? '' }}" />

    @if(!empty($seo->search_console_verification))
        <meta name="google-site-verification" content="{{ $seo->search_console_verification }}">
    @endif
<!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/' . $logoSettings->favicon) }}" type="image/x-icon">
    <title> আমাদের দেশ | Amar Desh </title>
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

<!-- hamburger section   -->
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
