<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of posts
     *
     * @param string $locale
     */
    public function index($locale)
    {
        // Get all published posts with pagination
        $posts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['user', 'categories', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        // Get popular posts for sidebar
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // Get latest posts for sidebar
        $latestPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->limit(5)
            ->get();

        // Get categories for sidebar
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount(['posts' => function($query) {
                $query->where('status', 'published');
            }])
            ->orderBy('order')
            ->get();

        return view('FrontEnd.posts.index', compact(
            'posts',
            'popularPosts',
            'latestPosts',
            'categories'
        ));
    }

    /**
     * Display a single post
     *
     * @param string $locale
     * @param string $slug
     */
    public function show($locale, $slug)
    {
        // Find post by locale-specific slug
        $slugColumn = 'slug_' . $locale;

        $post = Post::where($slugColumn, $slug)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['user', 'categories', 'tags'])
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        // Get related posts (same categories)
        $relatedPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(6)
            ->get();

        // Get popular posts for sidebar (exclude current post)
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->orderBy('views_count', 'desc')
            ->limit(10)
            ->get();

        // Get latest posts for sidebar (exclude current post)
        $latestPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(10)
            ->get();

        // Get posts by same author (exclude current post)
        $authorPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('user_id', $post->user_id)
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('FrontEnd.post', compact(
            'post',
            'relatedPosts',
            'popularPosts',
            'latestPosts',
            'authorPosts'
        ));
    }

    /**
     * Display posts by type (video, gallery, article)
     *
     * @param string $locale
     * @param string $type
     */
    public function byType($locale, $type)
    {
        // Validate post type
        $validTypes = ['article', 'gallery', 'video', 'audio'];

        if (!in_array($type, $validTypes)) {
            abort(404);
        }

        // Get posts by type
        $posts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('post_type', $type)
            ->with(['user', 'categories', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        // Get popular posts
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where('post_type', $type)
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('FrontEnd.posts.by-type', compact(
            'posts',
            'popularPosts',
            'type'
        ));
    }

    /**
     * Search posts
     *
     * @param string $locale
     * @param Request $request
     */
    public function search($locale, Request $request)
    {
        $query = $request->input('q');

        if (empty($query)) {
            return redirect()->route('home.index', ['locale' => $locale]);
        }

        // Search in title and content based on locale
        $titleColumn = 'title_' . $locale;
        $contentColumn = 'content_' . $locale;
        $excerptColumn = 'excerpt_' . $locale;

        $posts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(function($q) use ($query, $titleColumn, $contentColumn, $excerptColumn) {
                $q->where($titleColumn, 'LIKE', "%{$query}%")
                    ->orWhere($contentColumn, 'LIKE', "%{$query}%")
                    ->orWhere($excerptColumn, 'LIKE', "%{$query}%");
            })
            ->with(['user', 'categories', 'tags'])
            ->latest('published_at')
            ->paginate(20);

        // Get popular posts for sidebar
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('FrontEnd.posts.search', compact(
            'posts',
            'popularPosts',
            'query'
        ));
    }

    /**
     * Get breaking news (for AJAX/API)
     *
     * @param string $locale
     */
    public function breaking($locale)
    {
        $breakingNews = Post::where('status', 'published')
            ->where('is_breaking', true)
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $breakingNews
        ]);
    }

    /**
     * Get featured posts (for AJAX/API)
     *
     * @param string $locale
     */
    public function featured($locale)
    {
        $featuredPosts = Post::where('status', 'published')
            ->where('is_featured', true)
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $featuredPosts
        ]);
    }

    /**
     * Like a post (AJAX)
     *
     * @param string $locale
     * @param int $id
     */
    public function like($locale, $id)
    {
        $post = Post::findOrFail($id);

        // Check if user already liked (using session/cookie)
        $sessionKey = 'liked_posts';
        $likedPosts = session()->get($sessionKey, []);

        if (in_array($id, $likedPosts)) {
            return response()->json([
                'success' => false,
                'message' => 'Already liked'
            ]);
        }

        // Increment likes count
        $post->increment('likes_count');

        // Store in session
        $likedPosts[] = $id;
        session()->put($sessionKey, $likedPosts);

        return response()->json([
            'success' => true,
            'likes_count' => $post->likes_count
        ]);
    }
}
