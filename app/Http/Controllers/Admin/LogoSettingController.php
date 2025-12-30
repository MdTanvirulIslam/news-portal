<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LogoSettingController extends Controller
{
    /**
     * Display logo settings form
     */
    public function index()
    {
        // Force fresh data from database, ignore cache
        $logoSettings = LogoSetting::first();

        if (!$logoSettings) {
            $logoSettings = new LogoSetting();
        }

        return view('admin.settings.logos', compact('logoSettings'));
    }

    /**
     * Update logo settings
     */
    public function update(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'main_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'footer_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'favicon' => 'nullable|image|mimes:png,jpg,jpeg,ico,svg|max:512',
                'lazy_banner' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
                'og_image' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
                'main_logo_alt' => 'nullable|string|max:255',
                'footer_logo_alt' => 'nullable|string|max:255',
                'main_logo_width' => 'nullable|string|max:50',
                'main_logo_height' => 'nullable|string|max:50',
            ]);

            // Get or create logo settings
            $logoSettings = LogoSetting::first();

            if (!$logoSettings) {
                $logoSettings = new LogoSetting();
            }

            // Image fields to process
            $imageFields = ['main_logo', 'footer_logo', 'favicon', 'lazy_banner', 'og_image'];
            $data = [];

            // Process each image field
            foreach ($imageFields as $field) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);

                    Log::info("Processing upload for {$field}", [
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime' => $file->getMimeType(),
                    ]);

                    // Validate file is valid
                    if ($file->isValid()) {
                        // Delete old image if exists
                        if ($logoSettings->$field) {
                            Log::info("Deleting old {$field}", ['path' => $logoSettings->$field]);
                            $logoSettings->deleteOldImage($field);
                        }

                        // Create logos directory if it doesn't exist
                        if (!Storage::disk('public')->exists('logos')) {
                            Storage::disk('public')->makeDirectory('logos');
                        }

                        // Store new image
                        $path = $file->store('logos', 'public');
                        $data[$field] = $path;

                        Log::info("Logo uploaded successfully", [
                            'field' => $field,
                            'path' => $path,
                            'exists' => Storage::disk('public')->exists($path),
                            'full_path' => storage_path('app/public/' . $path),
                        ]);
                    } else {
                        Log::error("Invalid file upload", [
                            'field' => $field,
                            'error' => $file->getError()
                        ]);
                    }
                } else {
                    // Keep existing value if no new file uploaded
                    $data[$field] = $logoSettings->$field;
                }
            }

            // Add alt text and dimensions
            $data['main_logo_alt'] = $request->input('main_logo_alt', $logoSettings->main_logo_alt);
            $data['footer_logo_alt'] = $request->input('footer_logo_alt', $logoSettings->footer_logo_alt);
            $data['main_logo_width'] = $request->input('main_logo_width', $logoSettings->main_logo_width);
            $data['main_logo_height'] = $request->input('main_logo_height', $logoSettings->main_logo_height);

            // Update or create
            if ($logoSettings->exists) {
                $logoSettings->update($data);
                Log::info("Logo settings updated", ['id' => $logoSettings->id, 'data' => $data]);
            } else {
                $logoSettings = LogoSetting::create($data);
                Log::info("Logo settings created", ['id' => $logoSettings->id, 'data' => $data]);
            }

            // Clear cache
            Cache::forget('logo_settings');

            // Verify database was updated
            $fresh = LogoSetting::first();
            Log::info("Verified database update", [
                'main_logo' => $fresh->main_logo,
                'footer_logo' => $fresh->footer_logo,
            ]);

            return redirect()->route('admin.settings.logos.index')
                ->with('success', 'Logo settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Logo upload failed', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.settings.logos.index')
                ->with('error', 'Failed to upload logos: ' . $e->getMessage());
        }
    }
}
