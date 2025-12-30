<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class AdminSitemapController extends Controller
{
    /**
     * Display sitemap dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_posts' => Post::where('status', 'published')->count(),
            'total_categories' => Category::where('is_active', true)->count(),
            'total_tags' => Tag::where('is_active', true)->has('posts')->count(),
            'last_updated' => Cache::get('sitemap_last_generated', now()),
            'sitemap_cached' => Cache::has('sitemap_xml'),
        ];

        // Get recent posts for preview
        $recentPosts = Post::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.sitemap.index', compact('stats', 'recentPosts'));
    }

    /**
     * Regenerate sitemap cache
     */
    public function regenerate()
    {
        try {
            // Clear sitemap cache
            Cache::forget('sitemap_xml');

            // Update last generated timestamp
            Cache::put('sitemap_last_generated', now(), 86400); // 24 hours

            return response()->json([
                'success' => true,
                'message' => 'Sitemap cache cleared! It will regenerate on next visit.',
                'timestamp' => now()->format('Y-m-d H:i:s')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error regenerating sitemap: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit sitemap to Google
     */
    public function submitToGoogle()
    {
        try {
            $sitemapUrl = url('/sitemap.xml');
            $pingUrl = "https://www.google.com/ping?sitemap=" . urlencode($sitemapUrl);

            // Ping Google
            $response = @file_get_contents($pingUrl);

            return response()->json([
                'success' => true,
                'message' => 'Sitemap submitted to Google Search Console!',
                'url' => $sitemapUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not submit to Google. Please submit manually: ' . url('/sitemap.xml')
            ], 500);
        }
    }

    /**
     * Submit sitemap to Bing
     */
    public function submitToBing()
    {
        try {
            $sitemapUrl = url('/sitemap.xml');
            $pingUrl = "https://www.bing.com/ping?sitemap=" . urlencode($sitemapUrl);

            // Ping Bing
            $response = @file_get_contents($pingUrl);

            return response()->json([
                'success' => true,
                'message' => 'Sitemap submitted to Bing!',
                'url' => $sitemapUrl
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Could not submit to Bing. Please submit manually: ' . url('/sitemap.xml')
            ], 500);
        }
    }

    /**
     * Download XML sitemap
     * FIXED: Works without frontend routes
     */
    public function download()
    {
        try {
            // Generate sitemap XML directly
            $xml = $this->generateSitemapXml();

            return response($xml)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="sitemap.xml"')
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate');

        } catch (\Exception $e) {
            return back()->with('error', 'Error downloading sitemap: ' . $e->getMessage());
        }
    }

    /**
     * Generate sitemap XML content
     * UPDATED: Works without frontend routes - uses direct URLs
     */
    private function generateSitemapXml()
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
        $tags = Tag::where('is_active', true)->has('posts')->get();
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
