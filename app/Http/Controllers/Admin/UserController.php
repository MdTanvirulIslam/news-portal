<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ArtistProfile;
use App\Models\LyricistProfile;
use App\Models\ComposerProfile;
use App\Models\ProfileVerificationLog;
use App\Models\ActivityLog;
use App\Mail\AccountApproved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    // In UserController index method, update the DataTables query

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::withCount('posts')
                ->with(['artistProfile', 'lyricistProfile', 'composerProfile'])
                ->orderBy('created_at', 'desc');

            // Handle profile verification filter from DataTables
            if ($request->has('profile_verified_filter')) {
                $filter = $request->get('profile_verified_filter');

                if ($filter === 'verified') {
                    $users->where(function($query) {
                        $query->whereHas('artistProfile', function($q) {
                            $q->where('is_verified', true);
                        })->orWhereHas('lyricistProfile', function($q) {
                            $q->where('is_verified', true);
                        })->orWhereHas('composerProfile', function($q) {
                            $q->where('is_verified', true);
                        });
                    });
                } elseif ($filter === 'not_verified') {
                    $users->where(function($query) {
                        $query->whereHas('artistProfile', function($q) {
                            $q->where('is_verified', false)->orWhereNull('is_verified');
                        })->orWhereHas('lyricistProfile', function($q) {
                            $q->where('is_verified', false)->orWhereNull('is_verified');
                        })->orWhereHas('composerProfile', function($q) {
                            $q->where('is_verified', false)->orWhereNull('is_verified');
                        })->orWhereNotIn('role', ['artist', 'lyricist', 'composer']);
                    });
                }
            }

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('role', function ($user) {
                    $colors = [
                        'admin' => 'danger',
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
                ->addColumn('profile_verified', function ($user) {
                    // Get profile verification status based on role
                    $profile = null;
                    switch ($user->role) {
                        case 'artist':
                            $profile = $user->artistProfile;
                            break;
                        case 'lyricist':
                            $profile = $user->lyricistProfile;
                            break;
                        case 'composer':
                            $profile = $user->composerProfile;
                            break;
                        default:
                            return '<span class="badge badge-secondary" title="Not applicable for this role">N/A</span>';
                    }

                    if (!$profile) {
                        return '<span class="badge badge-warning" title="No profile created">No Profile</span>';
                    }

                    if ($profile->is_verified) {
                        $verifiedDate = $profile->verified_at ? $profile->verified_at->format('M d, Y') : '';
                        return '<span class="badge badge-success" title="Verified on ' . $verifiedDate . '">
                        <i class="fas fa-check-circle"></i> Verified
                        ' . ($verifiedDate ? '<br><small class="badge-sm">' . $verifiedDate . '</small>' : '') . '
                    </span>';
                    } else {
                        return '<span class="badge badge-warning" title="Profile not verified yet">
                        <i class="fas fa-clock"></i> Not Verified
                    </span>';
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
                    if (in_array($user->role, ['artist', 'lyricist', 'composer'])) {
                        $buttons .= '<a href="' . route('admin.users.profile.view', $user->id) . '" class="btn btn-sm btn-icon btn-info" title="View Profile">
                        <i class="fas fa-eye"></i>
                    </a>';
                    }
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
                ->rawColumns(['role', 'email_status', 'profile_verified', 'status', 'action'])
                ->make(true);
        }

        // Get statistics (keep existing code)
        $stats = [
            'total' => User::count(),
            'active' => User::where('is_active', true)->count(),
            'pending' => User::where('is_active', false)->count(),
            'admins' => User::where('role', 'admin')->count(),
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
            'profiles_verified' => $this->getProfileVerifiedCount(),
            'profiles_not_verified' => $this->getProfileNotVerifiedCount(),
        ];

        return view('admin.users.index', compact('stats'));
    }

    /**
     * View user profile
     */
    // Update the viewProfile method to include verification logs

    public function viewProfile(User $user)
    {
        if (!in_array($user->role, ['artist', 'lyricist', 'composer'])) {
            return redirect()->route('admin.users.index')->with('error', 'This user does not have a professional profile.');
        }

        $profile = null;
        $profileData = [];
        $verificationLogs = [];

        switch ($user->role) {
            case 'artist':
                $profile = $user->artistProfile;
                if ($profile) {
                    $profileData = [
                        'stage_name' => $profile->stage_name,
                        'gender' => ucfirst($profile->gender),
                        'nationality' => $profile->nationality,
                        'artist_type' => str_replace('_', ' ', ucfirst($profile->artist_type)),
                        'genres' => $profile->genres ? array_map(function($genre) {
                            return ArtistProfile::getAvailableGenres()[$genre] ?? ucfirst($genre);
                        }, $profile->genres) : [],
                        'years_of_experience' => $profile->years_of_experience,
                        'vocal_type' => $profile->vocal_type,
                        'instruments' => $profile->instruments ? array_map(function($instrument) {
                            return ArtistProfile::getAvailableInstruments()[$instrument] ?? ucfirst($instrument);
                        }, $profile->instruments) : [],
                        'portfolio_links' => $profile->portfolio_links,
                        'demo_audio' => $profile->demo_audio,
                        'previous_albums' => $profile->previous_albums,
                        'performance_videos' => $profile->performance_videos,
                        'manager_name' => $profile->manager_name,
                        'manager_phone' => $profile->manager_phone,
                        'booking_email' => $profile->booking_email,
                        'live_show_price_min' => $profile->live_show_price_min ? '৳' . number_format($profile->live_show_price_min, 2) : 'Not set',
                        'live_show_price_max' => $profile->live_show_price_max ? '৳' . number_format($profile->live_show_price_max, 2) : 'Not set',
                        'studio_recording_fee' => $profile->studio_recording_fee ? '৳' . number_format($profile->studio_recording_fee, 2) : 'Not set',
                        'location_availability' => $profile->location_availability,
                        'is_verified' => $profile->is_verified,
                        'verified_at' => $profile->verified_at,
                        'verified_by' => $profile->verifiedBy ? $profile->verifiedBy->name : null,
                        'created_at' => $profile->created_at,
                        'updated_at' => $profile->updated_at,
                    ];
                }
                break;

            case 'lyricist':
                $profile = $user->lyricistProfile;
                if ($profile) {
                    $profileData = [
                        'pen_name' => $profile->pen_name,
                        'writing_types' => $profile->writing_types ? array_map(function($type) {
                            return LyricistProfile::getWritingTypes()[$type] ?? ucfirst($type);
                        }, $profile->writing_types) : [],
                        'languages' => $profile->languages ? array_map(function($lang) {
                            return LyricistProfile::getAvailableLanguages()[$lang] ?? ucfirst($lang);
                        }, $profile->languages) : [],
                        'portfolio_links' => $profile->portfolio_links,
                        'songs_written' => $profile->songs_written,
                        'years_of_experience' => $profile->years_of_experience,
                        'work_email' => $profile->work_email,
                        'phone' => $profile->phone,
                        'collaboration_availability' => $profile->collaboration_availability,
                        'price_range_min' => $profile->price_range_min ? '৳' . number_format($profile->price_range_min, 2) : 'Not set',
                        'price_range_max' => $profile->price_range_max ? '৳' . number_format($profile->price_range_max, 2) : 'Not set',
                        'is_verified' => $profile->is_verified,
                        'verified_at' => $profile->verified_at,
                        'verified_by' => $profile->verifiedBy ? $profile->verifiedBy->name : null,
                        'created_at' => $profile->created_at,
                        'updated_at' => $profile->updated_at,
                    ];
                }
                break;

            case 'composer':
                $profile = $user->composerProfile;
                if ($profile) {
                    $profileData = [
                        'composer_types' => $profile->composer_types ? array_map(function($type) {
                            return ComposerProfile::getComposerTypes()[$type] ?? ucfirst($type);
                        }, $profile->composer_types) : [],
                        'genres' => $profile->genres ? array_map(function($genre) {
                            return ComposerProfile::getAvailableGenres()[$genre] ?? ucfirst($genre);
                        }, $profile->genres) : [],
                        'instruments_knowledge' => $profile->instruments_knowledge ? array_map(function($instrument) {
                            return ComposerProfile::getAvailableInstruments()[$instrument] ?? ucfirst($instrument);
                        }, $profile->instruments_knowledge) : [],
                        'studio_availability' => $profile->studio_availability ? 'Yes' : 'No',
                        'sample_works' => $profile->sample_works,
                        'experience_years' => $profile->experience_years,
                        'work_charges_min' => $profile->work_charges_min ? '৳' . number_format($profile->work_charges_min, 2) : 'Not set',
                        'work_charges_max' => $profile->work_charges_max ? '৳' . number_format($profile->work_charges_max, 2) : 'Not set',
                        'recording_location' => $profile->recording_location,
                        'booking_email' => $profile->booking_email,
                        'booking_phone' => $profile->booking_phone,
                        'is_verified' => $profile->is_verified,
                        'verified_at' => $profile->verified_at,
                        'verified_by' => $profile->verifiedBy ? $profile->verifiedBy->name : null,
                        'created_at' => $profile->created_at,
                        'updated_at' => $profile->updated_at,
                    ];
                }
                break;
        }

        if (!$profile) {
            return redirect()->route('admin.users.index')->with('error', 'Profile not found or not completed yet.');
        }

        // Get verification logs
        $verificationLogs = ProfileVerificationLog::where('user_id', $user->id)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.profile-view', compact('user', 'profile', 'profileData', 'verificationLogs'));
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

    /**
     * Get count of verified profiles
     */
    private function getProfileVerifiedCount()
    {
        $count = 0;
        $count += ArtistProfile::where('is_verified', true)->count();
        $count += LyricistProfile::where('is_verified', true)->count();
        $count += ComposerProfile::where('is_verified', true)->count();
        return $count;
    }

    /**
     * Get count of not verified profiles
     */
    private function getProfileNotVerifiedCount()
    {
        $count = 0;
        $count += ArtistProfile::where('is_verified', false)->orWhereNull('is_verified')->count();
        $count += LyricistProfile::where('is_verified', false)->orWhereNull('is_verified')->count();
        $count += ComposerProfile::where('is_verified', false)->orWhereNull('is_verified')->count();
        return $count;
    }
}
