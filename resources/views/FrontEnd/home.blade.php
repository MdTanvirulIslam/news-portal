@extends('FrontEnd.layouts.master')

@section('body')

    <!-- Trending Tags Section -->
    <div class="container overflow-hidden mt-4 d-none d-lg-block">
        <div class="d-flex max-sm:flex-nowrap align-items-center">
            <!-- Animated Icon -->
            <div class="mt-1">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="animated-svg"
                    fill="#D12026"
                    transform="rotate(315)"
                    viewBox="0 0 20 20"
                >
                    <path
                        fill-opacity="0.3"
                        d="M9.9 5C6.8 5 4 6.4 2.2 8.7l1.1 1.1c1.6-2 4-3.2 6.7-3.2s5.1 1.3 6.7 3.2l1.1-1.1C15.8 6.4 13 5 9.9 5z"
                    >
                        <animate
                            id="A"
                            attributeName="fill-opacity"
                            begin="B.end+0.05s"
                            calcMode="linear"
                            dur="450ms"
                            values="0.5;1;0.5"
                        ></animate>
                    </path>
                    <path
                        fill-opacity="0.3"
                        d="M9.9 8c-2.3 0-4.3 1.1-5.6 2.8l1.1 1.1c1-1.4 2.6-2.4 4.5-2.4s3.5.9 4.5 2.4l1.1-1.1C14.2 9.1 12.2 8 9.9 8z"
                    >
                        <animate
                            id="B"
                            attributeName="fill-opacity"
                            begin="C.end+0.05s"
                            calcMode="linear"
                            dur="450ms"
                            values="0.5;1;0.5"
                        ></animate>
                    </path>
                    <path
                        fill-opacity="0.3"
                        d="M9.9 11c-1.5 0-2.7.8-3.4 2l1.1 1.1c.4-.9 1.3-1.6 2.3-1.6s2 .7 2.3 1.6l1.1-1.1c-.7-1.2-1.9-2-3.4-2z"
                    >
                        <animate
                            id="C"
                            attributeName="fill-opacity"
                            begin="D.end+0.05s"
                            calcMode="linear"
                            dur="450ms"
                            values="0.5;1;0.5"
                        ></animate>
                    </path>
                    <circle cx="9.9" cy="15.3" r="1" fill-opacity="0.3">
                        <animate
                            id="D"
                            attributeName="fill-opacity"
                            begin="0s;A.end+0.05s"
                            calcMode="linear"
                            dur="450ms"
                            values="0.5;1;0.5"
                        ></animate>
                    </circle>
                </svg>
            </div>

            <!-- Trending Label -->
            <div class="news-label">
                <div class="label-red">
                    <span>
                        @if(currentLocale() == 'bn')
                            ট্রেন্ডিং
                        @else
                            Trending
                        @endif
                    </span>
                </div>
            </div>

            <!-- Dynamic Tags -->
            <div class="ml-3">
                <div class="separator"></div>
                <div class="d-flex align-items-center gap-2 ms-3 flex-nowrap text-nowrap tag-list">
                    @forelse($trendingTags as $tag)
                        <a class="tag-item"
                           href="{{ localized_route('tag.show', ['slug' => trans_slug($tag)]) }}"
                           title="{{ trans_field($tag, 'name') }} ({{ $tag->posts_count }} {{ currentLocale() == 'bn' ? 'টি পোস্ট' : 'posts' }})">
                            <p class="tag-text">{{ trans_field($tag, 'name') }}</p>
                        </a>
                    @empty
                        <p class="text-muted">
                            @if(currentLocale() == 'bn')
                                কোনো ট্রেন্ডিং ট্যাগ নেই
                            @else
                                No trending tags
                            @endif
                        </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Posts Section (4 posts) -->
    <section class="special-report">
        <div class="container custom-container">
            <div class="row custom-row">
                <div class="col-md-12 custom-padding">
                    <!-- Optional: Section Title -->
                </div>

                <div class="col-md-12 col-lg-12 custom-padding">
                    <div class="row custom-row">
                        @forelse($featuredPosts as $post)
                            <div class="col-md-3 col-6 custom-padding">
                                <div class="special-news">
                                    <div class="special-news-single">
                                        <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                            <div class="special-news-image">
                                                @if($post->featured_image)
                                                    <img
                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid lazyload"
                                                    />
                                                @else
                                                    <img
                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid"
                                                    />
                                                @endif
                                            </div>

                                            <div class="special-news-text">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12 custom-padding">
                                <div class="alert alert-info text-center">
                                    @if(currentLocale() == 'bn')
                                        কোনো বিশেষ প্রতিবেদন নেই
                                    @else
                                        No special reports available
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Bangladesh Category Section (7 posts: 1 large + 6 grid) -->
    @if($bangladeshMainPost)
        <section class="sports-news-section">
            <div class="container custom-container">
                <div class="row custom-row">
                    <!-- Section Title -->
                    <div class="col-md-12 custom-padding">
                        <div class="title-heading-wrapper">
                            <div class="title-heading">
                                @if($bangladeshCategory)
                                    <a class="title-heading-text"
                                       href="{{ localized_route('category.show', ['slug' => trans_slug($bangladeshCategory)]) }}">
                                        {{ trans_field($bangladeshCategory, 'name') }}
                                    </a>
                                @else
                                    <span class="title-heading-text">
                                    @if(currentLocale() == 'bn')
                                            বাংলাদেশ
                                        @else
                                            Bangladesh
                                        @endif
                                </span>
                                @endif
                            </div>

                            @if($bangladeshCategory)
                                <a class="title-right-link"
                                   href="{{ localized_route('category.show', ['slug' => trans_slug($bangladeshCategory)]) }}">
                                    @if(currentLocale() == 'bn')
                                        আরও খবর
                                    @else
                                        More News
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- LEFT - Main Post -->
                    <div class="sports-news-section-left">
                        <div class="col-md-12 custom-padding">
                            <div class="sports-news">
                                <a href="{{ localized_route('post.show', ['slug' => trans_slug($bangladeshMainPost)]) }}">
                                    <div class="sports-news-image">
                                        @if($bangladeshMainPost->featured_image)
                                            <img
                                                src="{{ !empty($bangladeshMainPost->featured_image) ? asset('storage/' . $bangladeshMainPost->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                data-src="{{ asset('storage/' . $bangladeshMainPost->featured_image) }}"
                                                alt="{{ trans_field($bangladeshMainPost, 'title') }}"
                                                class="img-fluid lazyload"
                                            />
                                        @else
                                            <img
                                                src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                alt="{{ trans_field($bangladeshMainPost, 'title') }}"
                                                class="img-fluid"
                                            />
                                        @endif
                                    </div>
                                    <div class="sports-news-text">
                                        <h2>{{ trans_field($bangladeshMainPost, 'title') }}</h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT - Grid Posts (6 posts) -->
                    <div class="sports-news-section-right">
                        <div class="col-md-12 custom-padding">
                            <div class="row custom-row">
                            @if($bangladeshGridPosts->count() > 0)
                                @php
                                    $leftColumnPosts = $bangladeshGridPosts->take(3);
                                    $rightColumnPosts = $bangladeshGridPosts->skip(3)->take(3);
                                @endphp

                                <!-- Left Column (3 posts) -->
                                    <div class="col-md-6 custom-padding">
                                        <ul class="sports-ul-top">
                                            @foreach($leftColumnPosts as $post)
                                                <li>
                                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                                        <div class="sports-ul-top-left">
                                                            <div class="sports-ul-top-left-image">
                                                                @if($post->featured_image)
                                                                    <img
                                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid lazyload"
                                                                    />
                                                                @else
                                                                    <img
                                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid"
                                                                    />
                                                                @endif
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

                                    <!-- Right Column (3 posts) -->
                                    <div class="col-md-6 custom-padding">
                                        <ul class="sports-ul-top">
                                            @foreach($rightColumnPosts as $post)
                                                <li>
                                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                                        <div class="sports-ul-top-left">
                                                            <div class="sports-ul-top-left-image">
                                                                @if($post->featured_image)
                                                                    <img
                                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid lazyload"
                                                                    />
                                                                @else
                                                                    <img
                                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid"
                                                                    />
                                                                @endif
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
            </div>
        </section>
    @endif

    <!-- National Category Section (7 posts: 1 large + 6 grid) -->
    @if($nationalMainPost)
        <section class="political-news">
            <div class="container custom-container">
                <div class="row custom-row">
                    <!-- Section Title -->
                    <div class="col-md-12 custom-padding">
                        <div class="title-heading-wrapper">
                            <div class="title-heading">
                                @if($nationalCategory)
                                    <a class="title-heading-text"
                                       href="{{ localized_route('category.show', ['slug' => trans_slug($nationalCategory)]) }}">
                                        {{ trans_field($nationalCategory, 'name') }}
                                    </a>
                                @else
                                    <span class="title-heading-text">
                                    @if(currentLocale() == 'bn')
                                            জাতীয়
                                        @else
                                            National
                                        @endif
                                </span>
                                @endif
                            </div>

                            @if($nationalCategory)
                                <a class="title-right-link"
                                   href="{{ localized_route('category.show', ['slug' => trans_slug($nationalCategory)]) }}">
                                    @if(currentLocale() == 'bn')
                                        আরও খবর
                                    @else
                                        More News
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- LEFT - Main Post -->
                    <div class="sports-news-section-left">
                        <div class="col-md-12 custom-padding">
                            <div class="sports-news">
                                <a href="{{ localized_route('post.show', ['slug' => trans_slug($nationalMainPost)]) }}">
                                    <div class="sports-news-image">
                                        @if($nationalMainPost->featured_image)
                                            <img
                                                src="{{ !empty($nationalMainPost->featured_image) ? asset('storage/' . $nationalMainPost->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                data-src="{{ asset('storage/' . $nationalMainPost->featured_image) }}"
                                                alt="{{ trans_field($nationalMainPost, 'title') }}"
                                                class="img-fluid lazyload"
                                            />
                                        @else
                                            <img
                                                src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                alt="{{ trans_field($nationalMainPost, 'title') }}"
                                                class="img-fluid"
                                            />
                                        @endif
                                    </div>
                                    <div class="sports-news-text">
                                        <h2>{{ trans_field($nationalMainPost, 'title') }}</h2>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT - Grid Posts (6 posts) -->
                    <div class="sports-news-section-right">
                        <div class="col-md-12 custom-padding">
                            <div class="row custom-row">
                            @if($nationalGridPosts->count() > 0)
                                @php
                                    $leftColumnPosts = $nationalGridPosts->take(3);
                                    $rightColumnPosts = $nationalGridPosts->skip(3)->take(3);
                                @endphp

                                <!-- Left Column (3 posts) -->
                                    <div class="col-md-6 custom-padding">
                                        <ul class="sports-ul-top">
                                            @foreach($leftColumnPosts as $post)
                                                <li>
                                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                                        <div class="sports-ul-top-left">
                                                            <div class="sports-ul-top-left-image">
                                                                @if($post->featured_image)
                                                                    <img
                                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid lazyload"
                                                                    />
                                                                @else
                                                                    <img
                                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid"
                                                                    />
                                                                @endif
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

                                    <!-- Right Column (3 posts) -->
                                    <div class="col-md-6 custom-padding">
                                        <ul class="sports-ul-top">
                                            @foreach($rightColumnPosts as $post)
                                                <li>
                                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                                        <div class="sports-ul-top-left">
                                                            <div class="sports-ul-top-left-image">
                                                                @if($post->featured_image)
                                                                    <img
                                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid lazyload"
                                                                    />
                                                                @else
                                                                    <img
                                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                        alt="{{ trans_field($post, 'title') }}"
                                                                        class="img-fluid"
                                                                    />
                                                                @endif
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
            </div>
        </section>
    @endif

    <!-- Sports Category Section (9 posts: 4 left + 1 middle + 4 right) -->
    @if($sportsLeadPost)

        <section class="sports-section_wrapper">
            <div class="container custom-container">
                <div class="row custom-row">
                    <!-- Section Title -->
                    <div class="col-md-12 custom-padding">
                        <div class="title-heading-wrapper">
                            <div class="title-heading">
                                @if($sportsCategory)
                                    <a class="title-heading-text"
                                       href="{{ localized_route('category.show', ['slug' => trans_slug($sportsCategory)]) }}">
                                        {{ trans_field($sportsCategory, 'name') }}
                                    </a>
                                @else
                                    <span class="title-heading-text">
                                    @if(currentLocale() == 'bn')
                                            খেলাধুলা
                                        @else
                                            Sports
                                        @endif
                                </span>
                                @endif
                            </div>

                            @if($sportsCategory)
                                <a class="title-right-link"
                                   href="{{ localized_route('category.show', ['slug' => trans_slug($sportsCategory)]) }}">
                                    @if(currentLocale() == 'bn')
                                        আরও খবর
                                    @else
                                        More News
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- LEFT SECTION - 4 Posts -->
                    <div class="sports-section-left order-sm-1 order-2">
                        <div class="col-md-12 custom-padding">
                            <ul class="sports-news-ul">
                                @foreach($sportsLeftPosts as $post)
                                    <li>
                                        <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                            <div class="sports-news-ul-left">
                                                @if($post->featured_image)
                                                    <img
                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid lazyload"
                                                    />
                                                @else
                                                    <img
                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid"
                                                    />
                                                @endif
                                            </div>
                                            <div class="sports-news-ul-right">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- MIDDLE SECTION - Lead Post (Latest) -->
                    <div class="sports-section-middle order-sm-2 order-1">
                        <div class="col-md-12 custom-padding">
                            <div class="sports-lead-news">
                                <a href="{{ localized_route('post.show', ['slug' => trans_slug($sportsLeadPost)]) }}">
                                    <div class="sports-lead-news-image">

                                        @if($sportsLeadPost->featured_image)
                                            <img
                                                src="{{ !empty($sportsLeadPost->featured_image) ? asset('storage/' . $sportsLeadPost->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                data-src="{{ asset('storage/' . $sportsLeadPost->featured_image) }}"
                                                alt="{{ trans_field($sportsLeadPost, 'title') }}"
                                                class="img-fluid lazyload"
                                            />
                                        @else
                                            <img
                                                src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                alt="{{ trans_field($sportsLeadPost, 'title') }}"
                                                class="img-fluid"
                                            />
                                        @endif
                                    </div>
                                    <div class="sports-lead-news-text">
                                        <h2>{{ trans_field($sportsLeadPost, 'title') }}</h2>
                                        <p>{{ Str::words(trans_field($sportsLeadPost, 'excerpt'), 20, '...') }}</p>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT SECTION - 4 Posts -->
                    <div class="sports-section-right order-sm-3 order-3">
                        <div class="col-md-12 custom-padding">
                            <ul class="sports-news-ul">
                                @foreach($sportsRightPosts as $post)
                                    <li>
                                        <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                            <div class="sports-news-ul-left">
                                                @if($post->featured_image)
                                                    <img
                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid lazyload"
                                                    />
                                                @else
                                                    <img
                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid"
                                                    />
                                                @endif
                                            </div>
                                            <div class="sports-news-ul-right">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- International Category Section (7 posts: 1 large with excerpt + 6 grid) -->
    @if($internationalMainPost)
        <section class="entertainment-news">
            <div class="container custom-container">
                <div class="row custom-row">
                    <!-- Section Title -->
                    <div class="col-md-12 custom-padding">
                        <div class="title-heading-wrapper title-heading-wrapper-white">
                            <div class="title-heading">
                                @if($internationalCategory)
                                    <a class="title-heading-text"
                                       href="{{ localized_route('category.show', ['slug' => trans_slug($internationalCategory)]) }}">
                                        {{ trans_field($internationalCategory, 'name') }}
                                    </a>
                                @else
                                    <span class="title-heading-text">
                                    @if(currentLocale() == 'bn')
                                            আন্তর্জাতিক
                                        @else
                                            International
                                        @endif
                                </span>
                                @endif
                            </div>

                            @if($internationalCategory)
                                <a class="title-right-link title-right-link-white"
                                   href="{{ localized_route('category.show', ['slug' => trans_slug($internationalCategory)]) }}">
                                    @if(currentLocale() == 'bn')
                                        আরও খবর
                                    @else
                                        More News
                                    @endif
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                        <path d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="col-12 custom-padding">
                        <div class="row custom-row">
                            <!-- LEFT - Main Post with Excerpt (Latest) -->
                            <div class="col-md-5 col-12 custom-padding">
                                <div class="entertainment-lead-news">
                                    {{-- ✅ FIXED: Changed from $post to $internationalMainPost --}}
                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($internationalMainPost)]) }}">
                                        <div class="entertainment-lead-image">
                                            @if($internationalMainPost->featured_image)
                                                <img
                                                    src="{{ !empty($internationalMainPost->featured_image) ? asset('storage/' . $internationalMainPost->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                    data-src="{{ asset('storage/' . $internationalMainPost->featured_image) }}"
                                                    alt="{{ trans_field($internationalMainPost, 'title') }}"
                                                    class="img-fluid lazyload"
                                                />
                                            @else
                                                <img
                                                    src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                    alt="{{ trans_field($internationalMainPost, 'title') }}"
                                                    class="img-fluid"
                                                />
                                            @endif
                                        </div>

                                        <div class="entertainment-lead-text-wrapper">
                                            <div class="entertainment-lead-text">
                                                <h2>{{ trans_field($internationalMainPost, 'title') }}</h2>
                                                <p>{{ Str::words(trans_field($internationalMainPost, 'excerpt'), 20, '...') }}</p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>

                            <!-- RIGHT - 6 Grid Posts (3 columns × 2 rows) -->
                            <div class="col-md-7 col-12 custom-padding">
                                <div class="row custom-row">
                                    @if($internationalGridPosts->count() > 0)
                                        @foreach($internationalGridPosts as $post)
                                            <div class="col-lg-4 col-6 custom-padding">
                                                <div class="entertainment-other-news">
                                                    <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                                        <div class="entertainment-news-image">
                                                            @if($post->featured_image)
                                                                <img
                                                                    src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                    data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                                    alt="{{ trans_field($post, 'title') }}"
                                                                    class="img-fluid lazyload"
                                                                />
                                                            @else
                                                                <img
                                                                    src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                                    alt="{{ trans_field($post, 'title') }}"
                                                                    class="img-fluid"
                                                                />
                                                            @endif
                                                        </div>
                                                        <div class="entertainment-other-news-text-wrapper">
                                                            <div class="entertainment-other-news-text">
                                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif


    <!-- Special Report Section (Static - Keep as is) -->
    <section class="special-report">
        <div class="container custom-container">
            <div class="row custom-row">
                <div class="col-md-12 custom-padding">
                    <div class="title-heading-wrapper bg-gray">
                        <div class="title-heading">
                            <a class="title-heading-text" href="#"> বিশেষ প্রতিবেদন </a>
                        </div>
                        <a class="title-right-link" href="#">
                            আরও খবর
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                <path
                                    d="M0 256a256 256 0 1 0 512 0A256 256 0 1 0 0 256zm395.3 11.3l-112 112c-4.6 4.6-11.5 5.9-17.4 3.5s-9.9-8.3-9.9-14.8l0-64-96 0c-17.7 0-32-14.3-32-32l0-32c0-17.7 14.3-32 32-32l96 0 0-64c0-6.5 3.9-12.3 9.9-14.8s12.9-1.1 17.4 3.5l112 112c6.2 6.2 6.2 16.4 0 22.6z"
                                ></path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 col-lg-12 custom-padding">
                    <div class="row custom-row">
                        @forelse($featuredPosts as $post)
                            <div class="col-md-3 col-6 custom-padding">
                                <div class="special-news">
                                    <div class="special-news-single">
                                        <a href="{{ localized_route('post.show', ['slug' => trans_slug($post)]) }}">
                                            <div class="special-news-image">
                                                @if($post->featured_image)
                                                    <img
                                                        src="{{ !empty($post->featured_image) ? asset('storage/' . $post->featured_image) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        data-src="{{ asset('storage/' . $post->featured_image) }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid lazyload"
                                                    />
                                                @else
                                                    <img
                                                        src="{{ !empty($logoSettings->lazy_banner) ? asset('storage/' . $logoSettings->lazy_banner) : asset('FrontEnd/images/placeholder.jpg') }}"
                                                        alt="{{ trans_field($post, 'title') }}"
                                                        class="img-fluid"
                                                    />
                                                @endif
                                            </div>

                                            <div class="special-news-text">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-md-12 custom-padding">
                                <div class="alert alert-info text-center">
                                    @if(currentLocale() == 'bn')
                                        কোনো বিশেষ প্রতিবেদন নেই
                                    @else
                                        No special reports available
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
