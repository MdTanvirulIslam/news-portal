<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display posts for a specific tag
     *
     * @param string $locale (bn or en)
     * @param string $slug (tag slug in current language)
     */
    public function show($locale, $slug)
    {
        // Find tag by slug based on current locale
        $slugColumn = 'slug_' . $locale;

        $tag = Tag::where($slugColumn, $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get posts with this tag
        // Filter by published status and published date
        $posts = Post::whereHas('tags', function($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->latest('published_at')
            ->paginate(20);

        // Get related tags (tags used with posts in this tag)
        $relatedTags = Tag::whereHas('posts', function($query) use ($tag) {
            // Posts that have this tag
            $query->whereHas('tags', function($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            });
        })
            ->where('id', '!=', $tag->id) // Exclude current tag
            ->where('is_active', true)
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        // Get popular posts with this tag
        $popularPosts = Post::whereHas('tags', function($query) use ($tag) {
            $query->where('tags.id', $tag->id);
        })
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        return view('FrontEnd.tag-single', compact(
            'tag',
            'posts',
            'relatedTags',
            'popularPosts'
        ));
    }

    /**
     * Display all tags
     *
     * @param string $locale
     */
    public function index($locale)
    {
        // Get all active tags with post count
        $tags = Tag::where('is_active', true)
            ->withCount('posts')
            ->orderBy('posts_count', 'desc')
            ->paginate(50);

        // Get popular tags
        $popularTags = Tag::where('is_active', true)
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(20)
            ->get();

        return view('FrontEnd.home', compact('tags', 'popularTags'));
    }
}
