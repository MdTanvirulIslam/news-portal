<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class SitemapController extends Controller
{
    /**
     * Generate XML Sitemap for search engines
     */
    public function xml()
    {
        // Cache sitemap for 1 hour to improve performance
        $sitemap = Cache::remember('sitemap_xml', 3600, function () {
            return $this->generateXmlSitemap();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=3600');
    }

    /**
     * Generate HTML Sitemap for users
     * FIXED: Proper many-to-many relationship handling
     */
    public function html()
    {
        // Get all active categories
        $categories = Category::where('is_active', true)
            ->orderBy('name_en')
            ->get();

        // For each category, get latest 10 published posts
        foreach ($categories as $category) {
            $category->latest_posts = $category->posts()
                ->where('status', 'published')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->limit(10)
                ->get();
        }

        // Get recent posts (all categories)
        $recentPosts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->limit(20)
            ->get();

        // Get all active tags with post count
        $tags = Tag::where('is_active', true)
            ->withCount(['posts' => function($query) {
                $query->where('status', 'published');
            }])
            ->having('posts_count', '>', 0)
            ->orderBy('name_en')
            ->get();

        return view('FrontEnd.sitemap.html', compact('categories', 'recentPosts', 'tags'));
    }

    /**
     * Generate XML Sitemap content
     * Works without frontend routes - uses direct URLs
     */
    private function generateXmlSitemap()
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Homepage
        $xml .= $this->addUrl(url('/'), now(), 'daily', '1.0');

        // Categories - Use direct URLs instead of named routes
        $categories = Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $slug = $category->slug_en ?: $category->slug_bn;
            $categoryUrl = url('/category/' . $slug);

            $xml .= $this->addUrl(
                $categoryUrl,
                $category->updated_at,
                'daily',
                '0.8'
            );
        }

        // Posts - Use direct URLs instead of named routes
        $posts = Post::where('status', 'published')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->get();

        foreach ($posts as $post) {
            $slug = $post->slug_en ?: $post->slug_bn;
            $postUrl = url('/post/' . $slug);

            $xml .= $this->addUrl(
                $postUrl,
                $post->updated_at,
                'weekly',
                '0.6'
            );
        }

        // Tags - Use direct URLs instead of named routes
        $tags = Tag::where('is_active', true)
            ->has('posts')
            ->get();

        foreach ($tags as $tag) {
            $slug = $tag->slug_en ?: $tag->slug_bn;
            $tagUrl = url('/tag/' . $slug);

            $xml .= $this->addUrl(
                $tagUrl,
                $tag->updated_at,
                'weekly',
                '0.5'
            );
        }

        // Static pages (optional - commented out since you don't have frontend yet)
        // Uncomment these when you create these pages
        // $xml .= $this->addUrl(url('/about'), now(), 'monthly', '0.4');
        // $xml .= $this->addUrl(url('/contact'), now(), 'monthly', '0.4');
        // $xml .= $this->addUrl(url('/privacy-policy'), now(), 'monthly', '0.3');
        // $xml .= $this->addUrl(url('/terms-conditions'), now(), 'monthly', '0.3');

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Add URL to sitemap
     */
    private function addUrl($loc, $lastmod, $changefreq, $priority)
    {
        $xml = '<url>';
        $xml .= '<loc>' . htmlspecialchars($loc) . '</loc>';
        $xml .= '<lastmod>' . $lastmod->toAtomString() . '</lastmod>';
        $xml .= '<changefreq>' . $changefreq . '</changefreq>';
        $xml .= '<priority>' . $priority . '</priority>';
        $xml .= '</url>';

        return $xml;
    }
}
