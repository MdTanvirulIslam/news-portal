<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LogoSetting;
use App\Models\LanguageLogo;
use App\Models\WebsiteSetting;
use App\Models\EmailSetting;

class SettingsDashboardController extends Controller
{
    public function index()
    {
        // Get settings statistics
        $stats = [
            'logo_settings' => LogoSetting::first() ? 'Configured' : 'Not Configured',
            'language_logos' => LanguageLogo::first() ? 'Configured' : 'Not Configured',
            'website_settings' => WebsiteSetting::first() ? 'Configured' : 'Not Configured',
            'email_settings' => EmailSetting::first() ? 'Configured' : 'Not Configured',
        ];

        return view('admin.settings.index', compact('stats'));
    }
}
