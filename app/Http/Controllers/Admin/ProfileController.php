<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // ADD THIS IMPORT
use App\Models\ArtistProfile;
use App\Models\LyricistProfile;
use App\Models\ComposerProfile;
use App\Models\ProfileVerificationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Show profile edit page
     */
    public function edit()
    {
        $user = auth()->user();

        // Get role-specific profile data
        $profileData = $this->getProfileData($user);

        return view('admin.profile.edit', [
            'user' => $user,
            'profileData' => $profileData,
        ]);
    }

    /**
     * Update artist profile via AJAX
     */
    public function updateArtistProfile(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'artist') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            // Personal Information
            'stage_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other,prefer_not_to_say',
            'nationality' => 'required|string|max:255',
            'artist_type' => 'required|in:singer,performer,band_member,rapper,instrumentalist',

            // Professional Profile
            'genres' => 'required|array|min:1',
            'years_of_experience' => 'required|integer|min:0|max:100',
            'vocal_type' => 'nullable|string|max:255',
            'instruments' => 'nullable|array',

            // Portfolio
            'portfolio_links.*' => 'nullable|url',
            'demo_audio' => 'nullable|file|mimes:mp3,wav,m4a|max:10240',
            'previous_albums.*' => 'nullable|string',
            'performance_videos.*' => 'nullable|url',

            // Business Information
            'manager_name' => 'nullable|string|max:255',
            'manager_phone' => 'nullable|string|max:20',
            'booking_email' => 'nullable|email|max:255',
            'live_show_price_min' => 'nullable|numeric|min:0',
            'live_show_price_max' => 'nullable|numeric|min:0|gte:live_show_price_min',
            'studio_recording_fee' => 'nullable|numeric|min:0',
            'location_availability' => 'required|array|min:1',

            // Verification
            'govt_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'artist_contract' => 'nullable|file|mimes:pdf|max:5120',
            'copyright_declaration' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'live_show_price_max.gte' => 'Maximum price must be greater than or equal to minimum price.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $artistProfile = ArtistProfile::firstOrNew(['user_id' => $user->id]);

            // Handle file uploads
            $fileFields = ['demo_audio', 'govt_id', 'artist_contract', 'copyright_declaration'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($artistProfile->$field) {
                        Storage::disk('public')->delete($artistProfile->$field);
                    }

                    $folder = $field === 'demo_audio' ? 'artist/demo_audio' : 'artist/documents';
                    $artistProfile->$field = $request->file($field)->store($folder, 'public');
                }
            }

            // Clean array inputs
            $arrayFields = ['genres', 'instruments', 'location_availability', 'portfolio_links', 'performance_videos'];
            foreach ($arrayFields as $field) {
                if ($request->has($field) && is_array($request->$field)) {
                    $data[$field] = array_filter($request->$field);
                }
            }

            // Handle previous albums
            $previousAlbums = [];
            if ($request->has('previous_albums') && is_array($request->previous_albums)) {
                foreach ($request->previous_albums as $album) {
                    if (!empty(trim($album))) {
                        $previousAlbums[] = trim($album);
                    }
                }
            }

            // Update profile data
            $artistProfile->fill([
                'stage_name' => $request->stage_name,
                'gender' => $request->gender,
                'nationality' => $request->nationality,
                'artist_type' => $request->artist_type,
                'genres' => $request->genres,
                'years_of_experience' => $request->years_of_experience,
                'vocal_type' => $request->vocal_type,
                'instruments' => $request->instruments ?? [],
                'portfolio_links' => $request->portfolio_links ?? [],
                'previous_albums' => $previousAlbums,
                'performance_videos' => $request->performance_videos ?? [],
                'manager_name' => $request->manager_name,
                'manager_phone' => $request->manager_phone,
                'booking_email' => $request->booking_email,
                'live_show_price_min' => $request->live_show_price_min,
                'live_show_price_max' => $request->live_show_price_max,
                'studio_recording_fee' => $request->studio_recording_fee,
                'location_availability' => $request->location_availability,
            ]);

            $artistProfile->save();

            // Mark user profile as completed if not already
            if (!$user->profile_completed) {
                $user->update([
                    'profile_completed' => true,
                    'profile_completed_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Artist profile updated successfully!',
                'redirect' => route('admin.dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update lyricist profile via AJAX
     */
    public function updateLyricistProfile(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'lyricist') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            // Professional Details
            'pen_name' => 'nullable|string|max:255',
            'writing_types' => 'required|array|min:1',
            'languages' => 'required|array|min:1',

            // Portfolio
            'portfolio_links.*' => 'nullable|url',
            'songs_written' => 'required|integer|min:0',
            'years_of_experience' => 'required|integer|min:0|max:100',

            // Contact
            'work_email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'collaboration_availability' => 'required|string|in:available,selective,not_available,commission_only',

            // Pricing
            'price_range_min' => 'nullable|numeric|min:0',
            'price_range_max' => 'nullable|numeric|min:0|gte:price_range_min',

            // Verification
            'govt_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'copyright_declaration' => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'price_range_max.gte' => 'Maximum price must be greater than or equal to minimum price.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $lyricistProfile = LyricistProfile::firstOrNew(['user_id' => $user->id]);

            // Handle file uploads
            $fileFields = ['govt_id', 'copyright_declaration'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($lyricistProfile->$field) {
                        Storage::disk('public')->delete($lyricistProfile->$field);
                    }

                    $lyricistProfile->$field = $request->file($field)->store('lyricist/documents', 'public');
                }
            }

            // Update profile data
            $lyricistProfile->fill([
                'pen_name' => $request->pen_name,
                'writing_types' => $request->writing_types,
                'languages' => $request->languages ?? [],
                'portfolio_links' => $request->portfolio_links ?? [],
                'songs_written' => $request->songs_written,
                'years_of_experience' => $request->years_of_experience,
                'work_email' => $request->work_email,
                'phone' => $request->phone,
                'collaboration_availability' => $request->collaboration_availability,
                'price_range_min' => $request->price_range_min,
                'price_range_max' => $request->price_range_max,
            ]);

            $lyricistProfile->save();

            // Mark user profile as completed if not already
            if (!$user->profile_completed) {
                $user->update([
                    'profile_completed' => true,
                    'profile_completed_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Lyricist profile updated successfully!',
                'redirect' => route('admin.dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update composer profile via AJAX
     */
    public function updateComposerProfile(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'composer') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            // Professional Details
            'composer_types' => 'required|array|min:1',
            'genres' => 'required|array|min:1',
            'instruments_knowledge' => 'nullable|array',
            'studio_availability' => 'required|in:yes,no,shared,home_studio',
            'experience_years' => 'required|integer|min:0|max:100',

            // Sample Works
            'sample_works.*' => 'nullable|url',

            // Business Information
            'work_charges_min' => 'nullable|numeric|min:0',
            'work_charges_max' => 'nullable|numeric|min:0|gte:work_charges_min',
            'recording_location' => 'nullable|string|max:500',
            'booking_email' => 'nullable|email|max:255',
            'booking_phone' => 'nullable|string|max:20',

            // Verification
            'govt_id' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'previous_work_docs' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ], [
            'work_charges_max.gte' => 'Maximum charges must be greater than or equal to minimum charges.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $composerProfile = ComposerProfile::firstOrNew(['user_id' => $user->id]);

            // Handle file uploads
            $fileFields = ['govt_id', 'previous_work_docs'];
            foreach ($fileFields as $field) {
                if ($request->hasFile($field)) {
                    // Delete old file if exists
                    if ($composerProfile->$field) {
                        Storage::disk('public')->delete($composerProfile->$field);
                    }

                    $composerProfile->$field = $request->file($field)->store('composer/documents', 'public');
                }
            }

            // Update profile data
            $composerProfile->fill([
                'composer_types' => $request->composer_types,
                'genres' => $request->genres,
                'instruments_knowledge' => $request->instruments_knowledge ?? [],
                'studio_availability' => $request->studio_availability === 'yes',
                'sample_works' => $request->sample_works ?? [],
                'experience_years' => $request->experience_years,
                'work_charges_min' => $request->work_charges_min,
                'work_charges_max' => $request->work_charges_max,
                'recording_location' => $request->recording_location,
                'booking_email' => $request->booking_email,
                'booking_phone' => $request->booking_phone,
            ]);

            $composerProfile->save();

            // Mark user profile as completed if not already
            if (!$user->profile_completed) {
                $user->update([
                    'profile_completed' => true,
                    'profile_completed_at' => now(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Composer profile updated successfully!',
                'redirect' => route('admin.dashboard')
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating profile: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get profile data based on user role
     */
    private function getProfileData($user)
    {
        switch ($user->role) {
            case 'artist':
                $profile = $user->artistProfile ?? new ArtistProfile(['user_id' => $user->id]);

                return [
                    'profile' => $profile,
                    'genres' => ArtistProfile::getAvailableGenres(),
                    'languages' => ArtistProfile::getAvailableLanguages(),
                    'instruments' => ArtistProfile::getAvailableInstruments(),
                    'selectedGenres' => $profile->genres ?? [],
                    'selectedInstruments' => $profile->instruments ?? [],
                    'selectedLocations' => $profile->location_availability ?? [],
                    'portfolioLinks' => $profile->portfolio_links ?? [],
                    'performanceVideos' => $profile->performance_videos ?? [],
                    'previousAlbums' => $profile->previous_albums ?? [],
                ];

            case 'lyricist':
                $profile = $user->lyricistProfile ?? new LyricistProfile(['user_id' => $user->id]);

                return [
                    'profile' => $profile,
                    'writingTypes' => LyricistProfile::getWritingTypes(),
                    'languages' => LyricistProfile::getAvailableLanguages(),
                    'collaborationOptions' => LyricistProfile::getCollaborationOptions(),
                    'selectedWritingTypes' => $profile->writing_types ?? [],
                    'selectedLanguages' => $profile->languages ?? [],
                    'portfolioLinks' => $profile->portfolio_links ?? [],
                ];

            case 'composer':
                $profile = $user->composerProfile ?? new ComposerProfile(['user_id' => $user->id]);

                return [
                    'profile' => $profile,
                    'composerTypes' => ComposerProfile::getComposerTypes(),
                    'genres' => ComposerProfile::getAvailableGenres(),
                    'instruments' => ComposerProfile::getAvailableInstruments(),
                    'studioAvailabilityOptions' => ComposerProfile::getStudioAvailabilityOptions(),
                    'selectedComposerTypes' => $profile->composer_types ?? [],
                    'selectedGenres' => $profile->genres ?? [],
                    'selectedInstruments' => $profile->instruments_knowledge ?? [],
                    'sampleWorks' => $profile->sample_works ?? [],
                ];

            // Add other roles here as needed
            default:
                return [
                    'profile' => null,
                    'selectedGenres' => [],
                    'selectedInstruments' => [],
                    'selectedLocations' => [],
                    'portfolioLinks' => [],
                    'performanceVideos' => [],
                    'previousAlbums' => [],
                    'selectedWritingTypes' => [],
                    'selectedLanguages' => [],
                    'selectedComposerTypes' => [],
                    'sampleWorks' => [],
                ];
        }
    }

    /**
     * Delete uploaded file
     */
    public function deleteFile(Request $request, $field)
    {
        $user = auth()->user();
        \Log::info('Delete file requested', ['user_id' => $user->id, 'field' => $field]);

        // Determine which model to use based on user role
        if ($user->role === 'artist') {
            $profile = ArtistProfile::where('user_id', $user->id)->first();
            $allowedFields = ['demo_audio', 'govt_id', 'artist_contract', 'copyright_declaration'];
        } elseif ($user->role === 'lyricist') {
            $profile = LyricistProfile::where('user_id', $user->id)->first();
            $allowedFields = ['govt_id', 'copyright_declaration'];
        } elseif ($user->role === 'composer') {
            $profile = ComposerProfile::where('user_id', $user->id)->first();
            $allowedFields = ['govt_id', 'previous_work_docs'];
        } else {
            \Log::warning('Unauthorized delete attempt', ['user_id' => $user->id, 'role' => $user->role]);
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        if (!$profile) {
            \Log::warning('Profile not found', ['user_id' => $user->id]);
            return response()->json(['success' => false, 'message' => 'Profile not found'], 404);
        }

        \Log::info('Checking field', ['field' => $field, 'allowed_fields' => $allowedFields, 'field_in_allowed' => in_array($field, $allowedFields)]);

        if (!in_array($field, $allowedFields)) {
            \Log::warning('Invalid field attempted', ['field' => $field]);
            return response()->json(['success' => false, 'message' => 'Invalid field'], 400);
        }

        if ($profile->$field) {
            \Log::info('Deleting file', ['field' => $field, 'file_path' => $profile->$field]);
            Storage::disk('public')->delete($profile->$field);
            $profile->$field = null;
            $profile->save();

            return response()->json(['success' => true, 'message' => 'File deleted successfully']);
        }

        \Log::warning('File not found', ['field' => $field]);
        return response()->json(['success' => false, 'message' => 'File not found'], 404);
    }

    /**
     * Verify user profile
     */
    public function verifyProfile(Request $request, User $user)
    {
        // Fix: Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. Only admins can verify profiles.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Determine which profile to verify based on user role
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
                    return response()->json([
                        'success' => false,
                        'message' => 'This user does not have a verifiable profile.'
                    ], 400);
            }

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found.'
                ], 404);
            }

            // Check if profile is already verified
            if ($profile->is_verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile is already verified.'
                ], 400);
            }

            // Verify the profile
            $profile->update([
                'is_verified' => true,
                'verified_at' => now(),
                'verified_by' => auth()->id(),
            ]);

            // Log the verification action
            ProfileVerificationLog::create([
                'user_id' => $user->id,
                'profile_type' => $user->role,
                'admin_id' => auth()->id(),
                'action' => 'verified',
                'reason' => $request->input('reason', 'Profile verification completed.'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile verified successfully!',
                'verified_at' => $profile->verified_at->format('M d, Y h:i A'),
                'verified_by' => auth()->user()->name,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile verification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unverify user profile
     */
    public function unverifyProfile(Request $request, User $user)
    {
        // Fix: Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action. Only admins can unverify profiles.'
            ], 403);
        }

        try {
            DB::beginTransaction();

            // Determine which profile to unverify based on user role
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
                    return response()->json([
                        'success' => false,
                        'message' => 'This user does not have a verifiable profile.'
                    ], 400);
            }

            if (!$profile) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile not found.'
                ], 404);
            }

            // Unverify the profile
            $profile->update([
                'is_verified' => false,
                'verified_at' => null,
                'verified_by' => null,
            ]);

            // Log the unverification action
            ProfileVerificationLog::create([
                'user_id' => $user->id,
                'profile_type' => $user->role,
                'admin_id' => auth()->id(),
                'action' => 'unverified',
                'reason' => $request->input('reason', 'Verification removed by admin.'),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Profile verification removed successfully!',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile unverification failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'admin_id' => auth()->id(),
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get verification logs for a user
     */
    public function getVerificationLogs(User $user)
    {
        // Fix: Check if user is admin
        if (auth()->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        try {
            $logs = ProfileVerificationLog::where('user_id', $user->id)
                ->with('admin')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($log) {
                    return [
                        'id' => $log->id,
                        'action' => $log->action,
                        'action_text' => $log->action === 'verified' ? 'Verified' : 'Unverified',
                        'action_icon' => $log->action === 'verified' ? 'fa-check-circle' : 'fa-times-circle',
                        'action_color' => $log->action === 'verified' ? 'success' : 'danger',
                        'reason' => $log->reason,
                        'admin' => [
                            'name' => $log->admin->name ?? 'System',
                            'email' => $log->admin->email ?? '',
                        ],
                        'created_at' => $log->created_at->format('M d, Y h:i A'),
                        'time_ago' => $log->created_at->diffForHumans(),
                        'timestamp' => $log->created_at->timestamp,
                    ];
                });

            return response()->json([
                'success' => true,
                'logs' => $logs,
                'total_logs' => $logs->count(),
                'verified_count' => $logs->where('action', 'verified')->count(),
                'unverified_count' => $logs->where('action', 'unverified')->count(),
            ]);

        } catch (\Exception $e) {
            \Log::error('Failed to get verification logs: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'exception' => $e
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to load verification logs: ' . $e->getMessage()
            ], 500);
        }
    }
}
