<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CategoryController extends Controller
{
    /**
     * Display posts for a specific category
     *
     * @param string $locale
     * @param string $slug
     */
    public function show($locale, $slug)
    {
        // Find category by locale-specific slug
        $slugColumn = 'slug_' . $locale;

        $category = Category::where($slugColumn, $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get subcategories (children) of this category
        $subcategories = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get posts from this category (including subcategories)
        $categoryIds = [$category->id];

        // Add subcategory IDs to search
        if ($subcategories->count() > 0) {
            $categoryIds = array_merge($categoryIds, $subcategories->pluck('id')->toArray());
        }

        // Get posts with pagination (15 per page)
        $posts = Post::where('posts.status', 'published')
            ->where('posts.published_at', '<=', now())
            ->join('post_category', 'posts.id', '=', 'post_category.post_id')
            ->whereIn('post_category.category_id', $categoryIds)
            ->select('posts.*')
            ->distinct()
            ->with(['user', 'categories'])
            ->latest('posts.published_at')
            ->paginate(15); // 15 posts per page

        // Split first 3 posts for featured display
        $featuredPosts = $posts->take(3);
        $regularPosts = $posts->skip(3);

        // Get this week's popular posts (top 10)
        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek();     // Sunday

        $weeklyPopularPosts = Post::where('posts.status', 'published')
            ->where('posts.published_at', '<=', now())
            ->whereBetween('posts.published_at', [$startOfWeek, $endOfWeek])
            ->join('post_category', 'posts.id', '=', 'post_category.post_id')
            ->whereIn('post_category.category_id', $categoryIds)
            ->select('posts.*')
            ->distinct()
            ->with(['user', 'categories'])
            ->orderBy('posts.views_count', 'desc')
            ->limit(10)
            ->get();

        // If no posts this week, get all-time popular from this category
        if ($weeklyPopularPosts->count() === 0) {
            $weeklyPopularPosts = Post::where('posts.status', 'published')
                ->where('posts.published_at', '<=', now())
                ->join('post_category', 'posts.id', '=', 'post_category.post_id')
                ->whereIn('post_category.category_id', $categoryIds)
                ->select('posts.*')
                ->distinct()
                ->with(['user', 'categories'])
                ->orderBy('posts.views_count', 'desc')
                ->limit(10)
                ->get();
        }

        return view('FrontEnd.category', compact(
            'category',
            'subcategories',
            'posts',
            'featuredPosts',
            'regularPosts',
            'weeklyPopularPosts'
        ));
    }

    /**
     * Load more posts via AJAX (for "আরও পড়ুন" button)
     *
     * @param Request $request
     * @param string $locale
     * @param string $slug
     */
    public function loadMore(Request $request, $locale, $slug)
    {
        $page = $request->get('page', 1);
        $slugColumn = 'slug_' . $locale;

        $category = Category::where($slugColumn, $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get subcategories
        $subcategories = Category::where('parent_id', $category->id)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        $categoryIds = array_merge([$category->id], $subcategories);

        // Get posts for this page
        $posts = Post::where('posts.status', 'published')
            ->where('posts.published_at', '<=', now())
            ->join('post_category', 'posts.id', '=', 'post_category.post_id')
            ->whereIn('post_category.category_id', $categoryIds)
            ->select('posts.*')
            ->distinct()
            ->with(['user', 'categories'])
            ->latest('posts.published_at')
            ->paginate(15, ['*'], 'page', $page);

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'posts' => $posts->items(),
            'has_more' => $posts->hasMorePages(),
            'next_page' => $posts->currentPage() + 1,
        ]);
    }
}
