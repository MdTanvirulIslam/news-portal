<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics for cards
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $pendingPosts = Post::where('status', 'pending')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalUsers = User::count();
        $totalCategories = Category::count();
        $totalTags = Tag::count();
        $totalSubscribers = 0; // Add your subscribers logic here

        // Pending approvals
        $pendingApprovals = Post::with(['user:id,name', 'categories:id,name_en'])
            ->select('id', 'title_en', 'title_bn', 'user_id', 'status', 'created_at')
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent posts (FIXED - removed views)
        $recentPosts = Post::with(['user:id,name', 'categories:id,name_en'])
            ->select('id', 'title_en', 'title_bn', 'user_id', 'status', 'created_at')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Popular posts (FIXED - use created_at instead of views)
        $popularPosts = Post::with(['user:id,name', 'categories:id,name_en'])
            ->select('id', 'title_en', 'title_bn', 'user_id', 'created_at', 'published_at')
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalPosts',
            'publishedPosts',
            'pendingPosts',
            'draftPosts',
            'totalUsers',
            'totalCategories',
            'totalTags',
            'totalSubscribers',
            'pendingApprovals',
            'recentPosts',
            'popularPosts'
        ));
    }
}
