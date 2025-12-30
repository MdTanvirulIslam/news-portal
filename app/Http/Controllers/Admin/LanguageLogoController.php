<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LanguageLogo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class LanguageLogoController extends Controller
{
    /**
     * Display language logos form
     */
    public function index()
    {
        $languageLogos = LanguageLogo::getSettings();
        return view('admin.settings.language-logos.index', compact('languageLogos'));
    }

    /**
     * Update language logos
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'english_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'english_logo_alt' => 'nullable|string|max:255',
                'bangla_logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
                'bangla_logo_alt' => 'nullable|string|max:255',
            ]);

            $languageLogos = LanguageLogo::getSettings();
            $data = [];

            // Process English Logo
            if ($request->hasFile('english_logo')) {
                $file = $request->file('english_logo');

                if ($file->isValid()) {
                    // Delete old logo
                    $languageLogos->deleteEnglishLogo();

                    // Create directory if needed
                    if (!Storage::disk('public')->exists('language-logos')) {
                        Storage::disk('public')->makeDirectory('language-logos');
                    }

                    // Store new logo
                    $path = $file->store('language-logos', 'public');
                    $data['english_logo'] = $path;

                    Log::info('English logo uploaded', ['path' => $path]);
                }
            } else {
                $data['english_logo'] = $languageLogos->english_logo;
            }

            // Process Bangla Logo
            if ($request->hasFile('bangla_logo')) {
                $file = $request->file('bangla_logo');

                if ($file->isValid()) {
                    // Delete old logo
                    $languageLogos->deleteBanglaLogo();

                    // Create directory if needed
                    if (!Storage::disk('public')->exists('language-logos')) {
                        Storage::disk('public')->makeDirectory('language-logos');
                    }

                    // Store new logo
                    $path = $file->store('language-logos', 'public');
                    $data['bangla_logo'] = $path;

                    Log::info('Bangla logo uploaded', ['path' => $path]);
                }
            } else {
                $data['bangla_logo'] = $languageLogos->bangla_logo;
            }

            // Add alt texts
            $data['english_logo_alt'] = $request->input('english_logo_alt', $languageLogos->english_logo_alt);
            $data['bangla_logo_alt'] = $request->input('bangla_logo_alt', $languageLogos->bangla_logo_alt);

            // Update
            LanguageLogo::updateSettings($data);

            return redirect()->route('admin.settings.language-logos.index')
                ->with('success', 'Language logos updated successfully!');

        } catch (\Exception $e) {
            Log::error('Language logo upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.settings.language-logos.index')
                ->with('error', 'Failed to upload logos: ' . $e->getMessage());
        }
    }
}
