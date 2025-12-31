<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\WebsiteSetting;
use App\Models\LogoSetting;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class SettingsComposer
{
    /**
     * Bind data to the view.
     *
     * @param  \Illuminate\View\View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get website settings (first or create default)
        $websiteSettings = WebsiteSetting::first() ?? new WebsiteSetting();

        // Get logo settings (first or create default)
        $logoSettings = LogoSetting::first() ?? new LogoSetting();

        // Get menu categories (parent categories with subcategories)
        // Cache for 30 minutes for better performance
        $menuCategories = Cache::remember('menu_categories', 1800, function () {
            return Category::whereNull('parent_id')
                ->where('is_active', true)
                ->where('show_in_menu', true)
                ->with(['children' => function($query) {
                    $query->where('is_active', true)
                        ->where('show_in_menu', true)
                        ->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        });

        // ✅ NEW: Get footer pages (cache for 1 hour)
        $footerPages = Cache::remember('footer_pages', 3600, function () {
            return Page::active()
                ->ordered()
                ->get();
        });

        // Share with all views
        $view->with('websiteSettings', $websiteSettings);
        $view->with('logoSettings', $logoSettings);
        $view->with('menuCategories', $menuCategories);
        $view->with('footerPages', $footerPages);  //
    }
}
