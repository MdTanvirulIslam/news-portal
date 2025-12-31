@extends('FrontEnd.layouts.master')

@section('title', $user->name . ' - ' . ($websiteSettings->website_title_bn ?? 'প্রোফাইল'))

@section('body')
    <!-- content section start -->
    <div class="container custom-container top-fixed-top-margin">
        <div class="row custom-row">
            <div class="container py-4">
                <div class="row g-4">
                    {{-- LEFT SIDE - User Profile --}}
                    <div class="col-md-4">
                        <div class="card profile-card text-center">
                            <div class="card-body">
                                {{-- Profile Image --}}
                                @if(!empty($user->profile_picture))
                                    <img src="{{ asset('storage/profiles/' . $user->profile_picture) }}"
                                         class="profile-img mb-3"
                                         alt="{{ $user->name }}">
                                @else
                                    <img src="https://secure.gravatar.com/avatar/{{ md5(strtolower(trim($user->email))) }}?s=200&d=mm&r=g"
                                         class="profile-img mb-3"
                                         alt="{{ $user->name }}">
                                @endif

                                {{-- User Name --}}
                                <h5 class="mb-0">{{ $user->name }}</h5>

                                {{-- User Role --}}
                                <p class="text-muted mb-1">
                                    @if(currentLocale() == 'bn')
                                        @switch($user->role)
                                            @case('admin')
                                            প্রশাসক
                                            @break
                                            @case('editor')
                                            সম্পাদক
                                            @break
                                            @case('reporter')
                                            প্রতিবেদক
                                            @break
                                            @case('contributor')
                                            অবদানকারী
                                            @break
                                            @default
                                            লেখক
                                        @endswitch
                                    @else
                                        {{ ucfirst($user->role) }}
                                    @endif
                                </p>

                                {{-- Address --}}
                                @if(!empty($user->address))
                                    <small class="text-muted">
                                        <i class="fa-solid fa-location-dot"></i>
                                        {{ $user->address }}
                                    </small>
                                @endif

                                <hr>

                                {{-- Contact Info & Stats --}}
                                <div class="text-start profile-info">
                                    @if(!empty($user->phone))
                                        <p class="mb-2">
                                            <i class="fa-solid fa-phone"></i>
                                            <span class="ms-2">{{ $user->phone }}</span>
                                        </p>
                                    @endif

                                    <p class="mb-2">
                                        <i class="fa-solid fa-envelope"></i>
                                        <span class="ms-2">{{ $user->email }}</span>
                                    </p>

                                    {{-- Post Stats --}}
                                    <p class="mb-2">
                                        <i class="fa-solid fa-newspaper"></i>
                                        <span class="ms-2">
                                            {{ $stats['total_posts'] }}
                                            @if(currentLocale() == 'bn')
                                                টি পোস্ট
                                            @else
                                                Posts
                                            @endif
                                        </span>
                                    </p>

                                    <p class="mb-2">
                                        <i class="fa-solid fa-eye"></i>
                                        <span class="ms-2">
                                            {{ number_format($stats['total_views']) }}
                                            @if(currentLocale() == 'bn')
                                                ভিউ
                                            @else
                                                Views
                                            @endif
                                        </span>
                                    </p>

                                    <p class="mb-0">
                                        <i class="fa-solid fa-folder"></i>
                                        <span class="ms-2">
                                            {{ $stats['total_categories'] }}
                                            @if(currentLocale() == 'bn')
                                                টি ক্যাটেগরি
                                            @else
                                                Categories
                                            @endif
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT SIDE - User Posts --}}
                    <div class="col-md-8">
                        {{-- Section Title --}}
                        {{--<div class="mb-3">
                            <h4>
                                @if(currentLocale() == 'bn')
                                    {{ $user->name }} এর সকল পোস্ট ({{ $stats['total_posts'] }})
                                @else
                                    All Posts by {{ $user->name }} ({{ $stats['total_posts'] }})
                                @endif
                            </h4>
                        </div>
--}}
                        {{-- Posts Grid --}}
                        <div class="row custom-row" id="posts-container">
                            @forelse($posts as $post)
                                <div class="col-md-6 custom-padding post-item">
                                    <ul class="sports-ul-top">
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
                                    </ul>
                                </div>
                            @empty
                                <div class="col-md-12 custom-padding">
                                    <div class="alert alert-info text-center">
                                        @if(currentLocale() == 'bn')
                                            এই লেখকের কোনো পোস্ট পাওয়া যায়নি
                                        @else
                                            No posts found by this author
                                        @endif
                                    </div>
                                </div>
                            @endforelse
                        </div>

                        {{-- Load More Button --}}
                        @if($posts->hasMorePages())
                            <div class="text-center mt-4">
                                <button class="btn btn-primary"
                                        id="load-more-btn"
                                        data-page="{{ $posts->currentPage() + 1 }}"
                                        data-user-id="{{ $user->id }}">
                                    @if(currentLocale() == 'bn')
                                        আরও পোস্ট দেখুন
                                    @else
                                        Load More Posts
                                    @endif
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- AJAX Load More Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-btn');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const button = this;
                    const page = button.getAttribute('data-page');
                    const userId = button.getAttribute('data-user-id');
                    const locale = '{{ currentLocale() }}';
                    const container = document.getElementById('posts-container');

                    // Show loading state
                    button.disabled = true;
                    button.textContent = '{{ currentLocale() == 'bn' ? 'লোড হচ্ছে...' : 'Loading...' }}';

                    // Fetch more posts
                    fetch(`/${locale}/user/${userId}/load-more?page=${page}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.posts.length > 0) {
                                // Append new posts
                                data.posts.forEach(post => {
                                    const postHtml = createPostCard(post, locale);
                                    container.insertAdjacentHTML('beforeend', postHtml);
                                });

                                // Update button state
                                if (data.has_more) {
                                    button.setAttribute('data-page', data.next_page);
                                    button.disabled = false;
                                    button.textContent = '{{ currentLocale() == 'bn' ? 'আরও পোস্ট দেখুন' : 'Load More Posts' }}';
                                } else {
                                    button.remove(); // Remove button if no more posts
                                }
                            } else {
                                button.remove();
                            }
                        })
                        .catch(error => {
                            console.error('Error loading more posts:', error);
                            button.disabled = false;
                            button.textContent = '{{ currentLocale() == 'bn' ? 'আরও পোস্ট দেখুন' : 'Load More Posts' }}';
                            alert('{{ currentLocale() == 'bn' ? 'পোস্ট লোড করতে ত্রুটি' : 'Error loading posts' }}');
                        });
                });
            }
        });

        // Helper function to create post card HTML
        function createPostCard(post, locale) {
            const title = locale === 'bn' ? post.title_bn : post.title_en;
            const slug = locale === 'bn' ? post.slug_bn : post.slug_en;
            const imageUrl = post.featured_image
                ? `{{ asset('storage/') }}/${post.featured_image}`
                : '{{ asset('images/placeholder.jpg') }}';

            return `
            <div class="col-md-6 custom-padding post-item">
                <ul class="sports-ul-top">
                    <li>
                        <a href="/${locale}/post/${slug}">
                            <div class="sports-ul-top-left">
                                <div class="sports-ul-top-left-image">
                                    <img src="${imageUrl}" class="img-fluid lazyload" alt="${title}" />
                                </div>
                            </div>
                            <div class="sports-ul-top-right">
                                <h3>${title}</h3>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        `;
        }
    </script>
@endsection
