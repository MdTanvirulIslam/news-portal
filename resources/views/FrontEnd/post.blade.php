@extends('FrontEnd.layouts.master')

@section('body')
    <div class="container custom-container top-fixed-top-margin">
        <div class="row custom-row">
            <div class="news-items">
                <div class="news-item-0">
                    <div class="row mx-0">
                        {{-- LEFT CONTENT AREA --}}
                        <div class="left-content-area details-left-content-area">
                            <div class="theiaStickySidebar">
                                {{-- CATEGORY --}}
                                <div class="col-md-12 custom-padding">
                                    <div class="details-category-title">
                                        @if($post->categories->first())
                                            <a href="{{ getCategoryUrl($post->categories->first()) }}">
                                                {{ trans_field($post->categories->first(), 'name') }}
                                            </a>
                                        @else
                                            <a href="#">
                                                @if(currentLocale() == 'bn')
                                                    সংবাদ
                                                @else
                                                    News
                                                @endif
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-12 custom-padding">
                                    <div class="details-content">
                                        {{-- POST TITLE --}}
                                        <h1>{{ trans_field($post, 'title') }}</h1>

                                        {{-- AUTHOR & SHARE SECTION --}}
                                        <div class="details-share-btn-main-wrapper">
                                            <div class="details-share-btn-wrapper d-flex justify-content-between align-items-center flex-wrap">

                                                {{-- AUTHOR INFO --}}
                                                <small class="small d-flex align-items-center">
                                                    <div class="details-author me-2">
                                                        <a href="#">
                                                            @if($post->user->avatar)
                                                                <img class="img-fluid rounded-circle"
                                                                     src="{{ asset('storage/avatars/' . $post->user->avatar) }}"
                                                                     alt="{{ $post->user->name }}" />
                                                            @else
                                                                <img class="img-fluid rounded-circle"
                                                                     src="https://secure.gravatar.com/avatar/{{ md5(strtolower(trim($post->user->email))) }}?s=100&d=mm&r=g"
                                                                     alt="{{ $post->user->name }}" />
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <div class="post-text">
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 512 512">
                                                                <path d="M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1 0 32c0 8.8 7.2 16 16 16l32 0zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z"></path>
                                                            </svg>
                                                            <a href="{{ getUserProfileUrl($post->user) }}">
                                                                @if(currentLocale() == 'bn')
                                                                    লেখা : {{ $post->user->name }}
                                                                @else
                                                                    Written by: {{ $post->user->name }}
                                                                @endif
                                                            </a>
                                                        </p>
                                                        <p>
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 512 512">
                                                                <path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120l0 136c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2 280 120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"></path>
                                                            </svg>
                                                            {{ formatBanglaDate($post->published_at, 'F d, Y, h:i A') }}
                                                        </p>
                                                    </div>
                                                </small>

                                                {{-- SOCIAL SHARE BUTTONS --}}
                                                <div class="d-flex align-items-center social_list social_list_0 social-media-icons">
                                                    {{-- Facebook --}}
                                                    <a href="{{ shareUrl('facebook', getPostUrl($post), trans_field($post, 'title')) }}" target="_blank">
                                                        <div class="mb-0 social-icon share-social-icon facebook">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M22 12c0-5.5228-4.4772-10-10-10S2 6.4772 2 12c0 4.9911 3.6572 9.1289 8.4375 9.877v-6.987H7.8984v-2.89h2.5391v-2.197c0-2.507 1.4924-3.8906 3.7773-3.8906 1.0943 0 2.2383.195 2.2383.195v2.4609h-1.2617c-1.2438 0-1.6328.7727-1.6328 1.5625v1.8691h2.7734l-.4434 2.89h-2.33v6.987C18.3428 21.1289 22 16.9911 22 12z"></path>
                                                            </svg>
                                                        </div>
                                                    </a>

                                                    {{-- Twitter --}}
                                                    <a href="{{ shareUrl('twitter', getPostUrl($post), trans_field($post, 'title')) }}" target="_blank">
                                                        <div class="mb-0 social-icon share-social-icon twitter">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 512 512">
                                                                <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"></path>
                                                            </svg>
                                                        </div>
                                                    </a>

                                                    {{-- WhatsApp --}}
                                                    <a href="{{ shareUrl('whatsapp', getPostUrl($post), trans_field($post, 'title')) }}" target="_blank">
                                                        <div class="mb-0 social-icon share-social-icon whatsapp">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M20.52 3.48A11.77 11.77 0 0012.07 0 11.87 11.87 0 000 12c0 2.09.55 4.14 1.61 5.95L0 24l6.21-1.62A11.91 11.91 0 0012 24h.07A11.86 11.86 0 0024 12.08a11.7 11.7 0 00-3.48-8.6zM12.07 22a10.05 10.05 0 01-5.13-1.4l-.37-.22-3.69.97.99-3.6-.24-.37A9.99 9.99 0 1122.07 12 10 10 0 0112.07 22zm5.6-7.6c-.3-.15-1.76-.87-2.04-.96s-.48-.15-.68.15-.78.96-.96 1.16-.36.22-.66.07a8.38 8.38 0 01-2.47-1.53 9.3 9.3 0 01-1.7-2.13c-.17-.3 0-.45.13-.6.13-.13.3-.35.45-.52s.2-.3.3-.5a.57.57 0 00-.03-.53c-.15-.15-.68-1.62-.94-2.22-.25-.6-.5-.52-.68-.52h-.57a1.11 1.11 0 00-.8.38 3.34 3.34 0 00-1 2.45 5.83 5.83 0 001.2 3.02c.15.22 2.35 3.6 5.7 5.03a19.58 19.58 0 002.02.74c.85.27 1.62.23 2.22.14a3.38 3.38 0 002.21-1.55 2.72 2.72 0 00.19-1.55c-.08-.15-.27-.23-.57-.38z"></path>
                                                            </svg>
                                                        </div>
                                                    </a>

                                                    {{-- Copy Link --}}
                                                    <a href="#" onclick="copyToClipboard('{{ getPostUrl($post) }}'); return false;">
                                                        <div class="mb-0 social-icon copy share-social-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 448 512">
                                                                <path d="M208 0L332.1 0c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9L448 336c0 26.5-21.5 48-48 48l-192 0c-26.5 0-48-21.5-48-48l0-288c0-26.5 21.5-48 48-48zM48 128l80 0 0 64-64 0 0 256 192 0 0-32 64 0 0 48c0 26.5-21.5 48-48 48L48 512c-26.5 0-48-21.5-48-48L0 176c0-26.5 21.5-48 48-48z"></path>
                                                            </svg>
                                                        </div>
                                                    </a>

                                                    {{-- Print --}}
                                                    <a href="#" onclick="window.print(); return false;">
                                                        <div class="mb-0 social-icon print share-social-icon">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M19 8h-1V3H6v5H5a2 2 0 00-2 2v7h4v4h10v-4h4v-7a2 2 0 00-2-2zM8 5h8v3H8V5zm8 14H8v-5h8v5zm2-7a1 1 0 110-2 1 1 0 010 2z"></path>
                                                            </svg>
                                                        </div>
                                                    </a>

                                                    {{-- Google News (if you have icon) --}}
                                                    @if(file_exists(public_path('images/44.png')))
                                                        <a class="p-2" href="#">
                                                            <img class="img-fluid m-0" src="{{ asset('images/44.png') }}" alt="googlenews" />
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- FEATURED IMAGE --}}
                                        @if($post->featured_image)
                                            <div class="image-caption-wrapper">
                                                <img class="img-fluid lazyload"
                                                     src="{{ getPostImage($post) }}"
                                                     alt="{{ trans_field($post, 'title') }}" />
                                            </div>
                                        @endif

                                        {{-- POST CONTENT --}}
                                        <article class="content-details">
                                            {!! trans_field($post, 'content') !!}
                                        </article>

                                        {{-- AUDIO SECTION (if audio file exists) --}}
                                        @if($post->audio_file)
                                            <h3 class="postAudio">
                                                @if(currentLocale() == 'bn')
                                                    অডিও
                                                @else
                                                    Audio
                                                @endif
                                            </h3>
                                            <audio controls>
                                                <source src="{{ asset('storage/' . $post->audio_file) }}" type="audio/mpeg">
                                                <source src="{{ asset('storage/' . $post->audio_file) }}" type="audio/ogg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @endif

                                        {{-- VIDEO SECTION (if video_url exists) --}}
                                        @if($post->video_url)
                                            <h3 class="postVideo">
                                                @if(currentLocale() == 'bn')
                                                    ভিডিও
                                                @else
                                                    Video
                                                @endif
                                            </h3>
                                            <div class="video-wrapper">
                                                @php
                                                    // Check if it's a YouTube URL
                                                    $isYoutube = str_contains($post->video_url, 'youtube.com') || str_contains($post->video_url, 'youtu.be');

                                                    if ($isYoutube) {
                                                        // Extract YouTube video ID
                                                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu.be\/)([^"&?\/\s]{11})/', $post->video_url, $matches);
                                                        $videoId = $matches[1] ?? '';
                                                    }
                                                @endphp

                                                @if($isYoutube && !empty($videoId))
                                                    {{-- YouTube Embed --}}
                                                    <iframe src="https://www.youtube.com/embed/{{ $videoId }}"
                                                            title="YouTube video"
                                                            loading="lazy"
                                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                                            allowfullscreen>
                                                    </iframe>
                                                @else
                                                    {{-- Regular Video File or Other Video URL --}}
                                                    <video controls>
                                                        <source src="{{ $post->video_url }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- TAGS --}}
                                        @if($post->tags->count() > 0)
                                            <ul class="tag-ul">
                                                <li>
                                                    @if(currentLocale() == 'bn')
                                                        বিষয়:
                                                    @else
                                                        Tags:
                                                    @endif
                                                </li>
                                                @foreach($post->tags as $tag)
                                                    <li>
                                                        <a href="{{ getTagUrl($tag) }}">{{ trans_field($tag, 'name') }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- RIGHT SIDEBAR --}}
                        <div class="right-content-area details-right-content-area pt-4">
                            <div class="theiaStickySidebar">
                                <div class="col-md-12 custom-padding">
                                    <div class="tab-content-wrapper details-tab-content-wrapper">
                                        {{-- TABS --}}
                                        <ul class="nav nav-pills side-tab-main" id="pills-tab" role="tablist">
                                            <li class="nav-item active" id="endNews" data-bs-toggle="tab" data-bs-target="#endNews-tab-pane" type="button" role="tab" aria-controls="endNews-tab-pane" aria-selected="true">
                                                <a class="nav-link" aria-selected="false" tabindex="-1" role="tab">
                                                    @if(currentLocale() == 'bn')
                                                        সর্বশেষ
                                                    @else
                                                        Latest
                                                    @endif
                                                </a>
                                            </li>

                                            <li class="nav-item" id="popNews" data-bs-toggle="tab" data-bs-target="#popNews-tab-pane" type="button" role="tab" aria-controls="popNews-tab-pane" aria-selected="false" tabindex="-1">
                                                <a class="nav-link" aria-selected="false" tabindex="-1" role="tab">
                                                    @if(currentLocale() == 'bn')
                                                        জনপ্রিয়
                                                    @else
                                                        Popular
                                                    @endif
                                                </a>
                                            </li>
                                        </ul>

                                        <div class="tab-content custom-tab-content" id="myTabContent">
                                            {{-- LATEST NEWS TAB --}}
                                            <div class="tab-pane new-fade active show" id="endNews-tab-pane" role="tabpanel" aria-labelledby="endNews" tabindex="0">
                                                <div class="latest-news details-latest-news">
                                                    <ul class="latest-news-ul">
                                                        @foreach($latestPosts as $latestPost)
                                                            <li>
                                                                <a href="{{ getPostUrl($latestPost) }}">
                                                                    <div class="latest-news-left">
                                                                        <img src="{{ getPostImage($latestPost) }}" class="img-fluid" alt="{{ trans_field($latestPost, 'title') }}" />
                                                                    </div>
                                                                    <div class="latest-news-right">
                                                                        <h3>{{ truncateText(trans_field($latestPost, 'title'), 80) }}</h3>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>

                                            {{-- POPULAR NEWS TAB --}}
                                            <div class="tab-pane new-fade" id="popNews-tab-pane" role="tabpanel" aria-labelledby="popNews" tabindex="0">
                                                <div class="latest-news details-latest-news">
                                                    <ul class="latest-news-ul">
                                                        @foreach($popularPosts as $popularPost)
                                                            <li>
                                                                <a href="{{ getPostUrl($popularPost) }}">
                                                                    <div class="latest-news-left">
                                                                        <img src="{{ getPostImage($popularPost) }}" class="img-fluid" alt="{{ trans_field($popularPost, 'title') }}" />
                                                                    </div>
                                                                    <div class="latest-news-right">
                                                                        <h3>{{ truncateText(trans_field($popularPost, 'title'), 80) }}</h3>
                                                                    </div>
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="archive-btn d-none d-md-block">
                                        <a href="{{ localized_route('post.index') }}" class="btn btn-date-picker btn-block">
                                            @if(currentLocale() == 'bn')
                                                আর্কাইভ
                                            @else
                                                Archive
                                            @endif
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- COPY TO CLIPBOARD SCRIPT --}}
    <script>
        function copyToClipboard(text) {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(text).then(function() {
                    alert('Link copied to clipboard!');
                }, function(err) {
                    console.error('Could not copy text: ', err);
                });
            } else {
                // Fallback for older browsers
                const textArea = document.createElement("textarea");
                textArea.value = text;
                document.body.appendChild(textArea);
                textArea.select();
                try {
                    document.execCommand('copy');
                    alert('Link copied to clipboard!');
                } catch (err) {
                    console.error('Could not copy text: ', err);
                }
                document.body.removeChild(textArea);
            }
        }
    </script>
@endsection
