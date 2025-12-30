<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NewsletterSubscriberController extends Controller
{
    /**
     * Display a listing of subscribers
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $subscribers = NewsletterSubscriber::query()->orderBy('created_at', 'desc');

            return DataTables::of($subscribers)
                ->addIndexColumn()
                ->addColumn('select', function ($subscriber) {
                    return '<input type="checkbox" class="subscriber-checkbox" value="' . $subscriber->id . '">';
                })
                ->addColumn('status', function ($subscriber) {
                    if ($subscriber->status === 'active' && $subscriber->is_verified) {
                        return '<span class="badge bg-success">Active & Verified</span>';
                    } elseif ($subscriber->status === 'active' && !$subscriber->is_verified) {
                        return '<span class="badge bg-warning">Unverified</span>';
                    } elseif ($subscriber->status === 'unsubscribed') {
                        return '<span class="badge bg-secondary">Unsubscribed</span>';
                    } else {
                        return '<span class="badge bg-danger">Bounced</span>';
                    }
                })
                ->addColumn('name', function ($subscriber) {
                    return $subscriber->name ?? '<span class="text-muted">—</span>';
                })
                ->addColumn('subscribed', function ($subscriber) {
                    return $subscriber->subscribed_at ? $subscriber->subscribed_at->format('M d, Y') :
                        ($subscriber->created_at ? $subscriber->created_at->format('M d, Y') : '—');
                })
                ->addColumn('action', function ($subscriber) {
                    $verifyBtn = '';
                    if (!$subscriber->is_verified) {
                        $verifyBtn = '<button type="button" class="action-btn btn-verify"
                            onclick="verifySubscriber(' . $subscriber->id . ')"
                            title="Verify Email">
                            <i class="fas fa-check"></i>
                        </button>';
                    }

                    $deleteBtn = '<button type="button"
                        class="action-btn btn-delete"
                        onclick="deleteSubscriber(' . $subscriber->id . ')"
                        title="Delete Subscriber">
                        <i class="fas fa-trash-alt"></i>
                    </button>';

                    return '<div class="d-flex justify-content-center">' . $verifyBtn . $deleteBtn . '</div>';
                })
                ->rawColumns(['select', 'status', 'name', 'action'])
                ->make(true);
        }

        // Statistics
        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::activeAndVerified()->count(),
            'unverified' => NewsletterSubscriber::where('is_verified', false)->count(),
            'unsubscribed' => NewsletterSubscriber::where('status', 'unsubscribed')->count(),
        ];

        return view('admin.newsletter.subscribers', compact('stats'));
    }

    /**
     * Delete a subscriber
     */
    public function destroy(NewsletterSubscriber $subscriber)
    {
        // Log activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('deleted', "Deleted subscriber: {$subscriber->email}");
        }

        $subscriber->delete();

        return response()->json([
            'success' => true,
            'message' => 'Subscriber deleted successfully!'
        ]);
    }

    /**
     * Bulk delete subscribers
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:newsletter_subscribers,id',
        ]);

        $count = NewsletterSubscriber::whereIn('id', $validated['ids'])->delete();

        // Log activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('deleted', "Bulk deleted {$count} subscribers");
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} subscriber(s) deleted successfully!"
        ]);
    }

    /**
     * Manually verify a subscriber
     */
    public function verify(NewsletterSubscriber $subscriber)
    {
        $subscriber->markAsVerified();

        // Log activity if ActivityLog model exists
        if (class_exists('App\Models\ActivityLog')) {
            ActivityLog::log('updated', "Verified subscriber: {$subscriber->email}");
        }

        return response()->json([
            'success' => true,
            'message' => 'Subscriber verified successfully!'
        ]);
    }

    /**
     * Export subscribers to CSV
     */
    public function export()
    {
        $subscribers = NewsletterSubscriber::activeAndVerified()
            ->get(['email', 'name', 'subscribed_at', 'created_at']);

        $filename = 'subscribers_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');

            // Header row
            fputcsv($file, ['Email', 'Name', 'Subscribed Date']);

            // Data rows
            foreach ($subscribers as $subscriber) {
                $subscribedDate = $subscriber->subscribed_at ?? $subscriber->created_at;
                fputcsv($file, [
                    $subscriber->email,
                    $subscriber->name ?? '',
                    $subscribedDate ? $subscribedDate->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
