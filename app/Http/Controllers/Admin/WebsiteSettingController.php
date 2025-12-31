<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class WebsiteSettingController extends Controller
{
    /**
     * Display website settings form
     */
    public function index()
    {
        $websiteSettings = WebsiteSetting::getSettings();

        // Available options
        $loaderTypes = ['spinner', 'dots', 'bars', 'circle', 'pulse', 'custom'];
        $fonts = [
            'Roboto',
            'Open Sans',
            'Lato',
            'Montserrat',
            'Poppins',
            'Raleway',
            'Nunito',
            'Ubuntu',
            'Playfair Display',
            'Merriweather',
        ];
        $timezones = timezone_identifiers_list();

        return view('admin.settings.website.index', compact(
            'websiteSettings',
            'loaderTypes',
            'fonts',
            'timezones'
        ));
    }

    /**
     * Update website settings
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                // General Settings
                'website_title' => 'nullable|string|max:255',
                'show_loader' => 'required|boolean',
                'loader_type' => 'required|string|in:spinner,dots,bars,circle,pulse,custom',
                'loader_image' => 'nullable|image|mimes:png,jpg,jpeg,svg,gif|max:1024',
                'base_color' => 'nullable|string|max:7',
                'footer_color' => 'nullable|string|max:7',
                'copyright_color' => 'nullable|string|max:7',
                'header_text_color' => 'nullable|string|max:7',
                'link_color' => 'nullable|string|max:7',
                'link_hover_color' => 'nullable|string|max:7',
                'heading_font' => 'nullable|string|max:100',
                'body_font' => 'nullable|string|max:100',
                'timezone' => 'nullable|string|max:100',
                'posts_per_page' => 'nullable|integer|min:1|max:100',
                'google_adsense' => 'nullable|string|max:500',
                'google_analytics' => 'nullable|string|max:500',
                'facebook_pixel' => 'nullable|string|max:500',
                'maintenance_mode' => 'required|boolean',

                // Social Media URLs
                'facebook_url' => 'nullable|url|max:255',
                'twitter_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
                'youtube_url' => 'nullable|url|max:255',
                'whatsapp_url' => 'nullable|url|max:255',
                'instagram_url' => 'nullable|url|max:255',
                'rss_url' => 'nullable|url|max:255',

                // SEO Settings
                'meta_title' => 'nullable|string|max:60',
                'meta_description' => 'nullable|string|max:160',
                'meta_keywords' => 'nullable|string|max:500',
                'google_verification' => 'nullable|string|max:500',
                'og_image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',

            ]);

            $websiteSettings = WebsiteSetting::getSettings();
            $data = $validated;

            // Handle loader image upload
            if ($request->hasFile('loader_image')) {
                $file = $request->file('loader_image');

                if ($file->isValid()) {
                    // Delete old loader image
                    if ($websiteSettings->loader_image) {
                        $websiteSettings->deleteLoaderImage();
                    }

                    // Create directory if needed
                    if (!Storage::disk('public')->exists('loaders')) {
                        Storage::disk('public')->makeDirectory('loaders');
                    }

                    // Store new image
                    $path = $file->store('loaders', 'public');
                    $data['loader_image'] = $path;

                    Log::info('Loader image uploaded', ['path' => $path]);
                }
            } else {
                // Keep existing loader image
                $data['loader_image'] = $websiteSettings->loader_image;
            }

            // Handle OG image upload
            if ($request->hasFile('og_image')) {
                $file = $request->file('og_image');

                if ($file->isValid()) {
                    // Delete old OG image
                    if ($websiteSettings->og_image && file_exists(public_path($websiteSettings->og_image))) {
                        unlink(public_path($websiteSettings->og_image));
                    }

                    // Create directory if needed
                    $directory = public_path('images/seo');
                    if (!file_exists($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    // Store new image in public/images/seo
                    $filename = 'og-image-' . time() . '.' . $file->getClientOriginalExtension();
                    $file->move($directory, $filename);
                    $data['og_image'] = 'images/seo/' . $filename;

                    Log::info('OG image uploaded', ['path' => $data['og_image']]);
                }
            } else {
                // Keep existing OG image
                $data['og_image'] = $websiteSettings->og_image;
            }

            // Update settings
            WebsiteSetting::updateSettings($data);

            Log::info('Website settings updated successfully');

            return redirect()->route('admin.settings.website.index')
                ->with('success', 'Website settings updated successfully!');

        } catch (\Exception $e) {
            Log::error('Website settings update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.settings.website.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }
}
