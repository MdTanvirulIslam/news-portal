<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sitemap - {{ config('app.name') }}</title>
    <meta name="description" content="Browse all content on {{ config('app.name') }} - Complete sitemap of all articles, categories, and tags">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
        }
        .sitemap-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }
        .sitemap-section {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .sitemap-section h2 {
            border-bottom: 3px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 25px;
            color: #333;
        }
        .post-list {
            list-style: none;
            padding: 0;
        }
        .post-list li {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .post-list li:last-child {
            border-bottom: none;
        }
        .post-list a {
            color: #495057;
            text-decoration: none;
            transition: all 0.3s;
        }
        .post-list a:hover {
            color: #667eea;
            padding-left: 10px;
        }
        .post-meta {
            font-size: 0.85em;
            color: #6c757d;
            margin-left: 10px;
        }
        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
        .category-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #667eea;
            transition: all 0.3s;
        }
        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        .category-card h4 {
            margin-bottom: 15px;
            color: #333;
        }
        .tag-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .tag-badge {
            background: #667eea;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.9em;
        }
        .tag-badge:hover {
            background: #764ba2;
            color: white;
            transform: scale(1.05);
        }
        .back-to-home {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s;
            z-index: 1000;
        }
        .back-to-home:hover {
            background: #764ba2;
            color: white;
            transform: scale(1.1);
        }
        .coming-soon {
            color: #6c757d;
            font-style: italic;
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="sitemap-header">
    <div class="container">
        <div class="text-center">
            <h1 class="display-4 mb-3">
                <i class="fas fa-sitemap me-3"></i> Sitemap
            </h1>
            <p class="lead">Browse all content on {{ config('app.name') }}</p>
            <p class="text-white-50">
                <i class="fas fa-newspaper me-2"></i> {{ $recentPosts->count() }} Recent Posts
                <span class="mx-2">•</span>
                <i class="fas fa-folder me-2"></i> {{ $categories->count() }} Categories
                <span class="mx-2">•</span>
                <i class="fas fa-tags me-2"></i> {{ $tags->count() }} Tags
            </p>
        </div>
    </div>
</div>

<div class="container pb-5">
    <!-- Recent Posts -->
    <div class="sitemap-section">
        <h2>
            <i class="fas fa-clock text-primary me-2"></i> Recent Posts
        </h2>
        <ul class="post-list">
            @forelse($recentPosts as $post)
                <li>
                    <i class="fas fa-{{ $post->post_type === 'video' ? 'video' : ($post->post_type === 'gallery' ? 'images' : 'newspaper') }} me-2 text-muted"></i>
                    @php
                        $postSlug = $post->slug_en ?: $post->slug_bn;
                        $postUrl = url('/post/' . $postSlug);
                    @endphp
                    <a href="{{ $postUrl }}">
                        {{ $post->title_en ?: $post->title_bn }}
                    </a>
                    <span class="post-meta">
                            <i class="far fa-calendar-alt me-1"></i>
                            {{ $post->published_at->format('M d, Y') }}
                        </span>
                </li>
            @empty
                <li class="text-muted">No posts available</li>
            @endforelse
        </ul>
    </div>

    <!-- Categories with Posts -->
    <div class="sitemap-section">
        <h2>
            <i class="fas fa-folder-open text-warning me-2"></i> Categories
        </h2>
        <div class="category-grid">
            @forelse($categories as $category)
                <div class="category-card">
                    <h4>
                        @php
                            $categorySlug = $category->slug_en ?: $category->slug_bn;
                            $categoryUrl = url('/category/' . $categorySlug);
                        @endphp
                        <a href="{{ $categoryUrl }}" class="text-decoration-none text-dark">
                            <i class="fas fa-folder me-2 text-warning"></i>
                            {{ $category->name_en ?: $category->name_bn }}
                        </a>
                    </h4>
                    <ul class="list-unstyled mb-0">
                        @forelse($category->latest_posts as $post)
                            <li class="mb-2">
                                <i class="fas fa-angle-right me-2 text-muted small"></i>
                                @php
                                    $postSlug = $post->slug_en ?: $post->slug_bn;
                                    $postUrl = url('/post/' . $postSlug);
                                @endphp
                                <a href="{{ $postUrl }}" class="text-decoration-none text-secondary small">
                                    {{ Str::limit($post->title_en ?: $post->title_bn, 50) }}
                                </a>
                            </li>
                        @empty
                            <li class="text-muted small">No posts in this category</li>
                        @endforelse
                        @if(isset($category->latest_posts) && $category->latest_posts->count() >= 10)
                            <li class="mt-2">
                                <a href="{{ $categoryUrl }}" class="text-primary small">
                                    <i class="fas fa-arrow-right me-1"></i> View all posts →
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            @empty
                <div class="text-muted">No categories available</div>
            @endforelse
        </div>
    </div>

    <!-- Tags -->
    <div class="sitemap-section">
        <h2>
            <i class="fas fa-tags text-info me-2"></i> Tags
        </h2>
        <div class="tag-cloud">
            @forelse($tags as $tag)
                @php
                    $tagSlug = $tag->slug_en ?: $tag->slug_bn;
                    $tagUrl = url('/tag/' . $tagSlug);
                @endphp
                <a href="{{ $tagUrl }}" class="tag-badge">
                    <i class="fas fa-tag me-1"></i>
                    {{ $tag->name_en ?: $tag->name_bn }}
                    <span class="badge bg-light text-dark ms-1">{{ $tag->posts_count }}</span>
                </a>
            @empty
                <span class="text-muted">No tags available</span>
            @endforelse
        </div>
    </div>

    <!-- Static Pages -->
    <div class="sitemap-section">
        <h2>
            <i class="fas fa-file-alt text-success me-2"></i> Important Pages
        </h2>
        <ul class="post-list">
            <li>
                <i class="fas fa-home me-2 text-muted"></i>
                <a href="{{ url('/') }}">Homepage</a>
            </li>
            <li>
                <i class="fas fa-user-shield me-2 text-muted"></i>
                <a href="{{ url('/admin/login') }}">Admin Login</a>
            </li>
            <li>
                <i class="fas fa-info-circle me-2 text-muted"></i>
                <span class="coming-soon">About Us (Coming Soon)</span>
            </li>
            <li>
                <i class="fas fa-envelope me-2 text-muted"></i>
                <span class="coming-soon">Contact Us (Coming Soon)</span>
            </li>
            <li>
                <i class="fas fa-shield-alt me-2 text-muted"></i>
                <span class="coming-soon">Privacy Policy (Coming Soon)</span>
            </li>
            <li>
                <i class="fas fa-file-contract me-2 text-muted"></i>
                <span class="coming-soon">Terms & Conditions (Coming Soon)</span>
            </li>
        </ul>
        <small class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Frontend pages will be available once the website design is complete.
        </small>
    </div>

    <!-- Footer Info -->
    <div class="text-center text-muted mt-5">
        <p>
            <i class="fas fa-code me-2"></i>
            For search engines, visit our
            <a href="{{ url('/sitemap.xml') }}" class="text-primary">XML Sitemap</a>
        </p>
        <p class="small">
            Last updated: {{ now()->format('F d, Y') }}
        </p>
    </div>
</div>

<!-- Back to Home Button -->
<a href="{{ url('/') }}" class="back-to-home" title="Back to Homepage">
    <i class="fas fa-home fa-lg"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
