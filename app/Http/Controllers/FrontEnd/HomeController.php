<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @param string $locale (bn or en)
     */
    public function index($locale)
    {
        // Get trending tags (top 10 most used)
        $trendingTags = Tag::where('is_active', true)
            ->withCount('posts')
            ->having('posts_count', '>', 0)
            ->orderBy('posts_count', 'desc')
            ->limit(10)
            ->get();

        // Get featured posts (LATEST 4 FEATURED)
        $featuredPosts = Post::where('status', 'published')
            ->where('is_featured', true)
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(4)
            ->get();

        // Get breaking news
        $breakingNews = Post::where('status', 'published')
            ->where('is_breaking', true)
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(3)
            ->get();

        // Get latest posts
        $latestPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->latest('published_at')
            ->limit(10)
            ->get();

        // Get popular posts (most viewed)
        $popularPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['user', 'categories'])
            ->orderBy('views_count', 'desc')
            ->limit(5)
            ->get();

        // Get active categories for sidebar/sections
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->with(['children' => function($query) {
                $query->where('is_active', true)
                    ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // ========================================
        // BANGLADESH CATEGORY SECTION (7 posts)
        // ========================================
        $bangladeshCategory = Category::where('is_active', true)
            ->where(function($query) {
                $query->where('name_en', 'LIKE', '%Bangladesh%')
                    ->orWhere('name_bn', 'LIKE', '%বাংলাদেশ%');
            })
            ->first();

        $bangladeshPosts = collect();
        if ($bangladeshCategory) {
            $bangladeshPosts = Post::where('status', 'published')
                ->where('published_at', '<=', now())
                ->whereHas('categories', function($query) use ($bangladeshCategory) {
                    $query->where('categories.id', $bangladeshCategory->id);
                })
                ->with(['user', 'categories'])
                ->latest('published_at')
                ->limit(7)
                ->get();
        }

        // Split: 1 main post + 6 grid posts
        $bangladeshMainPost = $bangladeshPosts->first();
        $bangladeshGridPosts = $bangladeshPosts->skip(1)->take(6);

        // ========================================
        // NATIONAL CATEGORY SECTION (7 posts)
        // ========================================
        $nationalCategory = Category::where('is_active', true)
            ->where(function($query) {
                $query->where('name_en', 'LIKE', '%National%')
                    ->orWhere('name_bn', 'LIKE', '%জাতীয়%');
            })
            ->first();

        $nationalPosts = collect();
        if ($nationalCategory) {
            $nationalPosts = Post::where('status', 'published')
                ->where('published_at', '<=', now())
                ->whereHas('categories', function($query) use ($nationalCategory) {
                    $query->where('categories.id', $nationalCategory->id);
                })
                ->with(['user', 'categories'])
                ->latest('published_at')
                ->limit(7)
                ->get();
        }

        // Split: 1 main post + 6 grid posts
        $nationalMainPost = $nationalPosts->first();
        $nationalGridPosts = $nationalPosts->skip(1)->take(6);

        // ========================================
        // SPORTS CATEGORY SECTION (9 posts)
        // ========================================
        $sportsCategory = Category::where('is_active', true)
            ->where(function($query) {
                $query->where('name_en', 'LIKE', '%Sports%')
                    ->orWhere('name_bn', 'LIKE', '%খেলা%')
                    ->orWhere('name_bn', 'LIKE', '%খেলাধুলা%');
            })
            ->first();

        $sportsPosts = collect();
        if ($sportsCategory) {
            // ✅ CORRECTED: Use post_category pivot table (YOUR actual table name)
            $sportsPosts = Post::where('posts.status', 'published')
                ->where('posts.published_at', '<=', now())
                ->join('post_category', 'posts.id', '=', 'post_category.post_id')
                ->where('post_category.category_id', $sportsCategory->id)
                ->select('posts.*')     // Only select posts columns
                ->distinct()            // Prevent duplicate posts
                ->with(['user', 'categories'])
                ->latest('posts.published_at')
                ->limit(9)
                ->get();
        }

        // Split: 1 lead (middle) + 4 left + 4 right
        $sportsLeadPost = $sportsPosts->first();            // Post 1 → Middle
        $sportsLeftPosts = $sportsPosts->skip(1)->take(4);  // Posts 2-5 → Left
        $sportsRightPosts = $sportsPosts->skip(5)->take(4); // Posts 6-9 → Right


        // ========================================
        // INTERNATIONAL CATEGORY SECTION (7 posts)
        // ========================================
        $internationalCategory = Category::where('is_active', true)
            ->where(function($query) {
                $query->where('name_en', 'LIKE', '%International%')
                    ->orWhere('name_bn', 'LIKE', '%আন্তর্জাতিক%');
            })
            ->first();

        $internationalPosts = collect();
        if ($internationalCategory) {
            $internationalPosts = Post::where('status', 'published')
                ->where('published_at', '<=', now())
                ->whereHas('categories', function($query) use ($internationalCategory) {
                    $query->where('categories.id', $internationalCategory->id);
                })
                ->with(['user', 'categories'])
                ->latest('published_at')
                ->limit(7)
                ->get();
        }

        // Split: 1 main post (with excerpt) + 6 grid posts
        $internationalMainPost = $internationalPosts->first();
        $internationalGridPosts = $internationalPosts->skip(1)->take(6);
        //dd($internationalMainPost);
        return view('FrontEnd.home', compact(
            'trendingTags',
            'featuredPosts',
            'breakingNews',
            'latestPosts',
            'popularPosts',
            'categories',
            'bangladeshCategory',
            'bangladeshMainPost',
            'bangladeshGridPosts',
            'nationalCategory',
            'nationalMainPost',
            'nationalGridPosts',
            'sportsCategory',
            'sportsLeadPost',
            'sportsLeftPosts',
            'sportsRightPosts',
            'internationalCategory',
            'internationalMainPost',
            'internationalGridPosts'
        ));
    }
}
