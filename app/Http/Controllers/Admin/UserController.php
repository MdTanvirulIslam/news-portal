<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Mail\AccountApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::withCount('posts')->orderBy('created_at', 'desc');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    $colors = [
                        'admin' => 'danger',
                        'editor' => 'warning',
                        'reporter' => 'info',
                        'contributor' => 'secondary',
                        'listener' => 'primary',
                        'artist' => 'success',
                        'lyricist' => 'dark',
                        'composer' => 'info',
                        'label' => 'warning',
                        'publisher' => 'danger'
                    ];
                    $color = $colors[$user->role] ?? 'secondary';
                    return '<span class="badge badge-' . $color . '">' . ucfirst($user->role) . '</span>';
                })
                ->addColumn('posts_count', function ($user) {
                    return $user->posts_count;
                })
                ->addColumn('email_status', function ($user) {
                    if ($user->hasVerifiedEmail()) {
                        return '<span class="badge badge-success"><i class="fas fa-check-circle"></i> Verified</span>';
                    } else {
                        return '<span class="badge badge-danger"><i class="fas fa-times-circle"></i> Not Verified</span>';
                    }
                })
                ->addColumn('status', function ($user) {
                    if ($user->is_active) {
                        return '<span class="badge badge-success">Active</span>';
                    } else {
                        return '<span class="badge badge-warning">Pending Approval</span>';
                    }
                })
                ->addColumn('joined', function ($user) {
                    return $user->created_at->format('M d, Y');
                })
                ->addColumn('action', function ($user) {
                    if ($user->id === auth()->id()) {
                        return '<span class="text-muted">Current User</span>';
                    }

                    $buttons = '<div class="action-btns d-flex gap-2 justify-content-center">';

                    // Approve button (only if user is inactive, not admin, and email is verified)
                    if (!$user->is_active && $user->role !== 'admin') {
                        if ($user->hasVerifiedEmail()) {
                            // Email verified - show approve button
                            $buttons .= '<button onclick="approveUser(' . $user->id . ')" class="btn btn-sm btn-icon btn-success" title="Approve User">
                                <i class="fas fa-check"></i>
                            </button>';
                        } else {
                            // Email not verified - show disabled button with tooltip
                            $buttons .= '<button class="btn btn-sm btn-icon btn-secondary" disabled title="User must verify email first">
                                <i class="fas fa-ban"></i>
                            </button>';
                        }
                    }

                    // Edit button
                    $buttons .= '<a href="' . route('admin.users.edit', $user->id) . '" class="btn btn-sm btn-icon btn-primary" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';

                    // Delete button
                    $buttons .= '<button onclick="deleteUser(' . $user->id . ')" class="btn btn-sm btn-icon btn-danger" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>';

                    $buttons .= '</div>';

                    return $buttons;
                })
                ->rawColumns(['role', 'email_status', 'status', 'action'])
                ->make(true);
        }

        // Get statistics
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'pending' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
        ];

        return view('admin.users.index', compact('stats'));
    }

    public function edit(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.profile.edit')->with('error', 'Use profile page to edit your own account');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,editor,reporter,contributor,listener,artist,lyricist,composer,label,publisher',
            'password' => 'nullable|min:8|confirmed',
            'is_active' => 'boolean',
        ]);

        // Check if user is being activated (from edit page)
        $wasInactive = !$user->is_active;
        $willBeActive = $request->boolean('is_active');

        // Check email verification before activation
        if ($wasInactive && $willBeActive) {
            if (!$user->hasVerifiedEmail()) {
                return redirect()->back()->with('error', 'Cannot approve user. Email must be verified first.');
            }
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        // Send congratulations email using QUEUE if user was just activated
        if ($wasInactive && $willBeActive) {
            try {
                Mail::to($user->email)->queue(new AccountApproved($user));
            } catch (\Exception $e) {
                \Log::error('Failed to queue approval email to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        // Log activity if ActivityLog model exists
        // ActivityLog::log('updated', "Updated user: {$user->name}", $user);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot delete your own account'], 400);
        }

        if ($user->posts()->count() > 0) {
            return response()->json(['success' => false, 'message' => 'Cannot delete user with posts'], 400);
        }

        // ActivityLog::log('deleted', "Deleted user: {$user->name}", $user);
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }

    /**
     * Approve a pending user and send congratulations email using QUEUE
     * IMPORTANT: User must have verified email before approval
     */
    public function approve(User $user)
    {
        // Check if user is already active
        if ($user->is_active) {
            return response()->json(['success' => false, 'message' => 'User is already active'], 400);
        }

        // CRITICAL: Check if email is verified before approval
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'success' => false,
                'message' => '❌ Cannot approve user. Email address must be verified first. Please ask the user to check their email and verify their account.'
            ], 400);
        }

        // Activate user account
        $user->update(['is_active' => true]);

        // Send congratulations email notification using QUEUE
        try {
            Mail::to($user->email)->queue(new AccountApproved($user));
            $message = '🎉 User approved successfully! Congratulations email queued for ' . $user->email;
        } catch (\Exception $e) {
            \Log::error('Failed to queue approval email to ' . $user->email . ': ' . $e->getMessage());
            $message = 'User approved successfully, but failed to queue email notification.';
        }

        return response()->json(['success' => true, 'message' => $message]);
    }

    /**
     * Reject/deactivate a user
     */
    public function reject(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Cannot deactivate your own account'], 400);
        }

        $user->update(['is_active' => false]);

        return response()->json(['success' => true, 'message' => 'User deactivated successfully']);
    }
}
