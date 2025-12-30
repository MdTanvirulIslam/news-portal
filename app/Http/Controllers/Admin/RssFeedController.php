<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RssFeed;
use App\Models\Category;
use App\Services\RssImportService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RssFeedController extends Controller
{
    protected $rssImportService;

    public function __construct(RssImportService $rssImportService)
    {
        $this->rssImportService = $rssImportService;
    }

    /**
     * Display a listing of RSS feeds
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $feeds = RssFeed::with('category:id,name_en')
                ->select('id', 'name', 'feed_url', 'category_id', 'is_active', 'auto_import', 'last_fetched_at', 'total_imported', 'created_at');

            return DataTables::of($feeds)
                ->addColumn('category', function ($feed) {
                    return $feed->category ? $feed->category->name_en : '<span class="badge bg-secondary">No Category</span>';
                })
                ->addColumn('status', function ($feed) {
                    $status = $feed->is_active ? 'Active' : 'Inactive';
                    $badge = $feed->is_active ? 'success' : 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . $status . '</span>';
                })
                ->addColumn('auto_import_status', function ($feed) {
                    $status = $feed->auto_import ? 'Enabled' : 'Disabled';
                    $badge = $feed->auto_import ? 'info' : 'secondary';
                    return '<span class="badge bg-' . $badge . '">' . $status . '</span>';
                })
                ->addColumn('last_fetch', function ($feed) {
                    return $feed->last_fetched_at ? $feed->last_fetched_at->diffForHumans() : '<span class="text-muted">Never</span>';
                })
                ->addColumn('stats', function ($feed) {
                    return '<span class="badge bg-primary">' . $feed->total_imported . ' imported</span>';
                })
                ->addColumn('actions', function ($feed) {
                    // Beautiful gradient icon buttons
                    $importBtn = '<button type="button" class="action-btn btn-import import-feed"
                        data-id="' . $feed->id . '"
                        data-name="' . htmlspecialchars($feed->name) . '"
                        title="Import Posts">
                        <i class="fas fa-download"></i>
                    </button>';

                    $editBtn = '<a href="' . route('admin.rss-feeds.edit', $feed) . '"
                        class="action-btn btn-edit"
                        title="Edit Feed">
                        <i class="fas fa-edit"></i>
                    </a>';

                    $deleteBtn = '<button type="button"
                        class="action-btn btn-delete delete-feed"
                        data-id="' . $feed->id . '"
                        title="Delete Feed">
                        <i class="fas fa-trash-alt"></i>
                    </button>';

                    return '<div class="d-flex justify-content-center">' . $importBtn . $editBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['category', 'status', 'auto_import_status', 'last_fetch', 'stats', 'actions'])
                ->make(true);
        }

        return view('admin.rss-feeds.index');
    }

    /**
     * Show the form for creating a new RSS feed
     */
    public function create()
    {
        $categories = Category::select('id', 'name_en')->orderBy('name_en')->get();
        return view('admin.rss-feeds.create', compact('categories'));
    }

    /**
     * Store a newly created RSS feed
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'feed_url' => 'required|url|unique:rss_feeds,feed_url',
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'auto_import' => 'boolean',
            'import_limit' => 'required|integer|min:1|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['auto_import'] = $request->has('auto_import');

        RssFeed::create($validated);

        return redirect()->route('admin.rss-feeds.index')
            ->with('success', 'RSS Feed created successfully!');
    }

    /**
     * Show the form for editing the specified RSS feed
     */
    public function edit(RssFeed $rssFeed)
    {
        $categories = Category::select('id', 'name_en')->orderBy('name_en')->get();
        return view('admin.rss-feeds.edit', compact('rssFeed', 'categories'));
    }

    /**
     * Update the specified RSS feed
     */
    public function update(Request $request, RssFeed $rssFeed)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'feed_url' => 'required|url|unique:rss_feeds,feed_url,' . $rssFeed->id,
            'category_id' => 'nullable|exists:categories,id',
            'is_active' => 'boolean',
            'auto_import' => 'boolean',
            'import_limit' => 'required|integer|min:1|max:100',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $validated['auto_import'] = $request->has('auto_import');

        $rssFeed->update($validated);

        return redirect()->route('admin.rss-feeds.index')
            ->with('success', 'RSS Feed updated successfully!');
    }

    /**
     * Remove the specified RSS feed
     */
    public function destroy(RssFeed $rssFeed)
    {
        $rssFeed->delete();

        return response()->json([
            'success' => true,
            'message' => 'RSS Feed deleted successfully!'
        ]);
    }

    /**
     * Import posts from RSS feed
     */
    public function import(Request $request, RssFeed $rssFeed)
    {
        try {
            $result = $this->rssImportService->importFromFeed($rssFeed);

            return response()->json([
                'success' => true,
                'message' => "Successfully imported {$result['imported']} post(s). {$result['skipped']} skipped (already exist).",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import from all active auto-import feeds
     */
    public function importAll()
    {
        try {
            $result = $this->rssImportService->importFromAllFeeds();

            return response()->json([
                'success' => true,
                'message' => "Processed {$result['feeds_processed']} feed(s). Imported {$result['total_imported']} post(s).",
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bulk import failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
