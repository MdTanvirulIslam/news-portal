<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Support\Facades\App;

class PageController extends Controller
{
    /**
     * Display a custom page (About, Terms, Privacy, Contact, etc.)
     * 
     * @param string $locale - Language (bn/en)
     * @param string $slug - Page slug
     */
    public function show($locale, $slug)
    {
        // Set locale
        App::setLocale($locale);
        session()->put('locale', $locale);
        
        // Get the page by slug based on current locale
        $page = Page::where('slug_' . $locale, $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get related pages (other active pages, excluding current, limit 5)
        $relatedPages = Page::where('is_active', true)
            ->where('id', '!=', $page->id)
            ->orderBy('order', 'asc')
            ->limit(5)
            ->get();

        // Get latest posts for sidebar (limit 5)
        $latestPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->select('id', 'title_en', 'title_bn', 'slug_en', 'slug_bn', 
                     'featured_image', 'published_at')
            ->latest('published_at')
            ->limit(5)
            ->get();

        return view('FrontEnd.page', compact('page', 'relatedPages', 'latestPosts'));
    }

    /**
     * Get all active pages (optional - for pages listing)
     */
    public function index($locale)
    {
        App::setLocale($locale);
        session()->put('locale', $locale);
        
        $pages = Page::where('is_active', true)
            ->orderBy('order', 'asc')
            ->get();

        return view('FrontEnd.pages-list', compact('pages'));
    }
}
