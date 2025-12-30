<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\WebsiteSetting;
use App\Models\LogoSetting;
use App\Models\Category;

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
        // Filter: is_active = true, show_in_menu = true, parent_id = null
        // Order by: order field
        // Load children (subcategories) that are also active and show_in_menu
        $menuCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->where('show_in_menu', true)
            ->with(['children' => function($query) {
                $query->where('is_active', true)
                    ->where('show_in_menu', true)
                    ->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        // Share with all views
        $view->with('websiteSettings', $websiteSettings);
        $view->with('logoSettings', $logoSettings);
        $view->with('menuCategories', $menuCategories);
    }
}
