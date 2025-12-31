@extends('FrontEnd.layouts.master')

@section('body')
    <!-- Breadcrumb Section -->
    <div class="container custom-container top-fixed-top-margin">
        <div class="row custom-row">
            <div class="col-md-12 custom-padding">
                <div class="category__breadcrumb mb-3">
                    {{-- Main Category Name --}}
                    <ul class="category__breadcrumb-list--main list-unstyled">
                        <li>
                            <a href="{{ getCategoryUrl($category) }}">
                                <h2>{{ trans_field($category, 'name') }}</h2>
                            </a>
                        </li>
                    </ul>

                    {{-- Subcategories List --}}
                    @if($subcategories->count() > 0)
                        <ul class="category__breadcrumb-list category__breadcrumb-list--sub">
                            @foreach($subcategories as $subcategory)
                                <li>
                                    <a href="{{ getCategoryUrl($subcategory) }}">
                                        {{ trans_field($subcategory, 'name') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="container custom-container">
        <div class="row custom-row">
            {{-- LEFT SIDE - Posts --}}
            <div class="category__main">
                <div class="theiaStickySidebar">
                    <div class="col-md-12 custom-padding">
                        {{-- Featured Posts Section (First 3 posts) --}}
                        @if($featuredPosts->count() > 0)
                            <div class="row custom-row category__row">
                                {{-- Large Featured Post (First Post) --}}
                                @if($featuredPosts->first())
                                    <div class="col-md-8 col-12 custom-padding">
                                        <div class="category__featured category__featured--primary">
                                            <a href="{{ getPostUrl($featuredPosts->first()) }}">
                                                <div class="category__featured-image">
                                                    <img src="{{ getPostImage($featuredPosts->first()) }}"
                                                         class="img-fluid lazyload"
                                                         alt="{{ trans_field($featuredPosts->first(), 'title') }}" />
                                                </div>
                                                <div class="category__featured-content">
                                                    <h1>{{ trans_field($featuredPosts->first(), 'title') }}</h1>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                {{-- Two Small Featured Posts (Posts 2 & 3) --}}
                                <div class="col-md-4 custom-padding">
                                    <div class="category__featured-wrapper category__featured-wrapper--secondary">
                                        <div class="row custom-row">
                                            @foreach($featuredPosts->skip(1)->take(2) as $featuredPost)
                                                <div class="col-md-12 col-6 custom-padding">
                                                    <div class="category__featured category__featured--secondary">
                                                        <a href="{{ getPostUrl($featuredPost) }}">
                                                            <div class="category__featured-image">
                                                                <img src="{{ getPostImage($featuredPost) }}"
                                                                     class="img-fluid lazyload"
                                                                     alt="{{ trans_field($featuredPost, 'title') }}" />
                                                            </div>
                                                            <div class="category__featured-content category__featured-content--secondary">
                                                                <h2>{{ trans_field($featuredPost, 'title') }}</h2>
                                                            </div>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Regular Posts Grid (Posts 4+) --}}
                        <div class="row custom-row category__grid category__grid--list" id="posts-container">
                            @forelse($regularPosts as $post)
                                <div class="col-md-4 col-6 custom-padding post-item">
                                    <div class="category__card category__card--list">
                                        <a href="{{ getPostUrl($post) }}">
                                            <div class="category__card-image">
                                                <img src="{{ getPostImage($post) }}"
                                                     class="img-fluid lazyload"
                                                     alt="{{ trans_field($post, 'title') }}" />
                                            </div>
                                            <div class="category__card-content">
                                                <h3>{{ trans_field($post, 'title') }}</h3>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-md-12 custom-padding">
                                    <div class="alert alert-info text-center">
                                        @if(currentLocale() == 'bn')
                                            কোনো পোস্ট পাওয়া যায়নি
                                        @else
                                            No more posts found
                                        @endif
                                    </div>
                                </div>
                            @endforelse

                            {{-- Load More Button --}}
                            @if($posts->hasMorePages())
                                <div class="col-md-12 col-6 text-center m-auto">
                                    <div class="category__load-more col-md-3 m-auto mb-4">
                                        <button class="category__load-more-btn btn btn-more"
                                                id="load-more-btn"
                                                data-page="{{ $posts->currentPage() + 1 }}"
                                                data-category-slug="{{ trans_slug($category) }}">
                                            @if(currentLocale() == 'bn')
                                                আরও পড়ুন
                                            @else
                                                Read More
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR - Weekly Popular Posts --}}
            <div class="category__sidebar">
                <div class="theiaStickySidebar">
                    <div class="col-md-12 custom-padding">
                        <div class="category__sidebar-heading">
                            <h2>
                                @if(currentLocale() == 'bn')
                                    এই সপ্তাহের পাঠকপ্রিয়
                                @else
                                    This Week's Favorite
                                @endif
                            </h2>
                        </div>

                        <ul class="category__sidebar-list">
                            @forelse($weeklyPopularPosts as $popularPost)
                                <li>
                                    <div class="category__sidebar-item">
                                        <a href="{{ getPostUrl($popularPost) }}">
                                            <div class="category__sidebar-item-image">
                                                <img src="{{ getPostImage($popularPost) }}"
                                                     class="img-fluid"
                                                     alt="{{ trans_field($popularPost, 'title') }}" />
                                            </div>
                                            <div class="category__sidebar-item-content">
                                                <h2>{{ truncateText(trans_field($popularPost, 'title'), 80) }}</h2>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @empty
                                <li>
                                    <div class="alert alert-info text-center">
                                        @if(currentLocale() == 'bn')
                                            কোনো জনপ্রিয় পোস্ট নেই
                                        @else
                                            No popular posts
                                        @endif
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{-- AJAX Load More Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loadMoreBtn = document.getElementById('load-more-btn');

            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    const button = this;
                    const page = button.getAttribute('data-page');
                    const categorySlug = button.getAttribute('data-category-slug');
                    const locale = '{{ currentLocale() }}';
                    const container = document.getElementById('posts-container');

                    // Show loading state
                    button.disabled = true;
                    button.textContent = '{{ currentLocale() == 'bn' ? 'লোড হচ্ছে...' : 'Loading...' }}';

                    // Fetch more posts
                    fetch(`/${locale}/category/${categorySlug}/load-more?page=${page}`, {
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
                                    const loadMoreDiv = button.closest('.col-md-12');
                                    loadMoreDiv.insertAdjacentHTML('beforebegin', postHtml);
                                });

                                // Update button state
                                if (data.has_more) {
                                    button.setAttribute('data-page', data.next_page);
                                    button.disabled = false;
                                    button.textContent = '{{ currentLocale() == 'bn' ? 'আরও পড়ুন' : 'Read More' }}';
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
                            button.textContent = '{{ currentLocale() == 'bn' ? 'আরও পড়ুন' : 'Read More' }}';
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
                : '{{ asset('FrontEnd/images/placeholder.jpg') }}';

            return `
                <div class="col-md-4 col-6 custom-padding post-item">
                    <div class="category__card category__card--list">
                        <a href="/${locale}/post/${slug}">
                            <div class="category__card-image">
                                <img src="${imageUrl}" class="img-fluid lazyload" alt="${title}" />
                            </div>
                            <div class="category__card-content">
                                <h3>${title}</h3>
                            </div>
                        </a>
                    </div>
                </div>
            `;
        }
    </script>
@endsection
