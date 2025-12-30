<?php

namespace App\Services;

use App\Models\RssFeed;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RssImportService
{
    /**
     * Import posts from a specific RSS feed
     */
    public function importFromFeed(RssFeed $feed, $limit = null)
    {
        $limit = $limit ?? $feed->import_limit;

        $items = $this->fetchFeedItems($feed->feed_url, $limit);

        $imported = 0;
        $skipped = 0;

        foreach ($items as $item) {
            if ($this->postExists($item['link'])) {
                $skipped++;
                continue;
            }

            $this->createPostFromItem($item, $feed);
            $imported++;
        }

        $feed->updateFetchStats($imported);

        return [
            'imported' => $imported,
            'skipped' => $skipped,
            'total' => count($items)
        ];
    }

    /**
     * Import from all active auto-import feeds
     */
    public function importFromAllFeeds()
    {
        $feeds = RssFeed::active()->autoImport()->get();

        $totalImported = 0;
        $feedsProcessed = 0;

        foreach ($feeds as $feed) {
            if ($feed->shouldFetch()) {
                $result = $this->importFromFeed($feed);
                $totalImported += $result['imported'];
                $feedsProcessed++;
            }
        }

        return [
            'feeds_processed' => $feedsProcessed,
            'total_imported' => $totalImported
        ];
    }

    /**
     * Fetch items from RSS feed URL
     */
    protected function fetchFeedItems($url, $limit = 10)
    {
        try {
            $response = Http::timeout(30)->get($url);

            if (!$response->successful()) {
                throw new \Exception('Failed to fetch RSS feed: HTTP ' . $response->status());
            }

            $xml = simplexml_load_string($response->body());

            if ($xml === false) {
                throw new \Exception('Invalid RSS feed format');
            }

            $items = [];
            $count = 0;

            // Support both RSS and Atom formats
            if (isset($xml->channel->item)) {
                // RSS 2.0
                foreach ($xml->channel->item as $item) {
                    if ($count >= $limit) break;

                    $items[] = $this->parseRssItem($item);
                    $count++;
                }
            } elseif (isset($xml->entry)) {
                // Atom
                foreach ($xml->entry as $entry) {
                    if ($count >= $limit) break;

                    $items[] = $this->parseAtomEntry($entry);
                    $count++;
                }
            }

            return $items;
        } catch (\Exception $e) {
            throw new \Exception('Error fetching feed: ' . $e->getMessage());
        }
    }

    /**
     * Parse RSS 2.0 item
     */
    protected function parseRssItem($item)
    {
        $title = (string) $item->title;
        $description = (string) ($item->description ?? '');
        $content = (string) ($item->children('content', true)->encoded ?? $description);
        $link = (string) $item->link;
        $pubDate = $item->pubDate ? date('Y-m-d H:i:s', strtotime((string) $item->pubDate)) : now();

        // Extract image from content or media
        $image = $this->extractImage($item, $content);

        return [
            'title' => $title,
            'excerpt' => Str::limit(strip_tags($description), 200),
            'content' => $this->cleanContent($content),
            'link' => $link,
            'published_at' => $pubDate,
            'image_url' => $image
        ];
    }

    /**
     * Parse Atom entry
     */
    protected function parseAtomEntry($entry)
    {
        $title = (string) $entry->title;
        $content = (string) ($entry->content ?? $entry->summary ?? '');
        $link = (string) $entry->link['href'];
        $pubDate = $entry->published ? date('Y-m-d H:i:s', strtotime((string) $entry->published)) : now();

        $image = $this->extractImage($entry, $content);

        return [
            'title' => $title,
            'excerpt' => Str::limit(strip_tags($content), 200),
            'content' => $this->cleanContent($content),
            'link' => $link,
            'published_at' => $pubDate,
            'image_url' => $image
        ];
    }

    /**
     * Extract image from RSS item
     */
    protected function extractImage($item, $content)
    {
        // Try media:content
        if (isset($item->children('media', true)->content)) {
            return (string) $item->children('media', true)->content->attributes()->url;
        }

        // Try media:thumbnail
        if (isset($item->children('media', true)->thumbnail)) {
            return (string) $item->children('media', true)->thumbnail->attributes()->url;
        }

        // Try enclosure
        if (isset($item->enclosure)) {
            $type = (string) $item->enclosure['type'];
            if (strpos($type, 'image') !== false) {
                return (string) $item->enclosure['url'];
            }
        }

        // Extract from content HTML
        preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $content, $matches);
        return $matches[1] ?? null;
    }

    /**
     * Clean HTML content
     */
    protected function cleanContent($content)
    {
        // Remove scripts and styles
        $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/i', '', $content);
        $content = preg_replace('/<style\b[^<]*(?:(?!<\/style>)<[^<]*)*<\/style>/i', '', $content);

        return trim($content);
    }

    /**
     * Check if post with this source URL already exists
     */
    protected function postExists($sourceUrl)
    {
        return Post::where('source_url', $sourceUrl)->exists();
    }

    /**
     * Create post from RSS item
     */
    protected function createPostFromItem($item, RssFeed $feed)
    {
        $slug = Str::slug($item['title']);
        $originalSlug = $slug;
        $counter = 1;

        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $postData = [
            'title_en' => $item['title'],
            'excerpt_en' => $item['excerpt'],
            'content_en' => $item['content'],
            'slug' => $slug,
            'user_id' => auth()->id() ?? 1,
            'status' => 'draft', // Import as draft for review
            'post_type' => 'article',
            'source_url' => $item['link'],
            'published_at' => $item['published_at'],
            'allow_comments' => true,
        ];

        $post = Post::create($postData);

        // Attach category if feed has one
        if ($feed->category_id) {
            $post->categories()->attach($feed->category_id);
        }

        // Download and attach featured image if available
        if ($item['image_url']) {
            $this->downloadFeaturedImage($post, $item['image_url']);
        }

        return $post;
    }

    /**
     * Download and attach featured image
     */
    protected function downloadFeaturedImage(Post $post, $imageUrl)
    {
        try {
            $response = Http::timeout(30)->get($imageUrl);

            if (!$response->successful()) {
                return;
            }

            $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $filename = 'rss-' . $post->id . '-' . time() . '.' . $extension;
            $path = 'posts/' . date('Y/m');

            \Storage::disk('public')->put($path . '/' . $filename, $response->body());

            $post->featured_image = $path . '/' . $filename;
            $post->save();
        } catch (\Exception $e) {
            // Silently fail if image download fails
            \Log::warning('Failed to download RSS feed image: ' . $e->getMessage());
        }
    }
}
