<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class UserController extends Controller
{
    /**
     * Display user profile with posts
     *
     * ALTERNATE SOLUTION: Controller handles BOTH locale and id
     */
    public function show($locale, $id)
    {
        // Set the locale
        App::setLocale($locale);
        session()->put('locale', $locale);

        // Find user by ID
        $user = User::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        // Get user's published posts (10 per page)
        $posts = Post::where('user_id', $user->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->with(['categories'])
            ->select('id', 'user_id', 'title_en', 'title_bn', 'slug_en', 'slug_bn',
                'featured_image', 'published_at', 'views_count')
            ->latest('published_at')
            ->paginate(10);

        // Calculate stats
        $stats = [
            'total_posts' => Post::where('user_id', $user->id)
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->count(),

            'total_views' => Post::where('user_id', $user->id)
                    ->where('status', 'published')
                    ->sum('views_count') ?? 0,

            'total_categories' => 0,
        ];

        return view('FrontEnd.user-profile', compact('user', 'posts', 'stats'));
    }

    /**
     * Load more posts via AJAX
     */
    public function loadMorePosts(Request $request, $locale, $id)
    {
        // Set the locale
        App::setLocale($locale);

        $page = $request->get('page', 1);

        $user = User::where('id', $id)
            ->where('is_active', true)
            ->firstOrFail();

        $posts = Post::where('user_id', $user->id)
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->select('id', 'user_id', 'title_en', 'title_bn', 'slug_en', 'slug_bn',
                'featured_image', 'published_at', 'views_count')
            ->latest('published_at')
            ->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'posts' => $posts->items(),
            'has_more' => $posts->hasMorePages(),
            'next_page' => $posts->currentPage() + 1,
        ]);
    }
}
