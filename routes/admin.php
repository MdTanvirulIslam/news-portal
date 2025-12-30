<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\RegistrationController;
use App\Http\Controllers\Admin\RssFeedController;
use App\Http\Controllers\Admin\SettingsDashboardController;
use App\Http\Controllers\Admin\LogoSettingController;
use App\Http\Controllers\Admin\LanguageLogoController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\EmailSettingController;

// ========================================
// GUEST ROUTES (No Authentication Required)
// These should come BEFORE auth routes
// ========================================
Route::prefix('admin')->middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'store'])->name('admin.login.store');

    // Registration Routes
    Route::get('register/reporter', [RegistrationController::class, 'reporterForm'])->name('admin.register.reporter');
    Route::post('register/reporter', [RegistrationController::class, 'registerReporter'])->name('admin.register.reporter.store');

    Route::get('register/contributor', [RegistrationController::class, 'contributorForm'])->name('admin.register.contributor');
    Route::post('register/contributor', [RegistrationController::class, 'registerContributor'])->name('admin.register.contributor.store');
});

// ========================================
// AUTHENTICATED ADMIN ROUTES
// ========================================
Route::prefix('admin')->middleware(['auth', 'admin.role'])->name('admin.')->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout - Keep this separate with POST method
    Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

    // Categories
    Route::resource('categories', CategoryController::class);
    Route::get('categories/{parent}/subcategories', [CategoryController::class, 'getSubcategories'])
        ->name('categories.subcategories');
    Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');

    // Posts
    Route::resource('posts', PostController::class);
    Route::post('posts/{post}/approve', [PostController::class, 'approve'])
        ->name('posts.approve');

    Route::post('posts/{post}/reject', [PostController::class, 'reject'])
        ->name('posts.reject');

    // Tags
    Route::resource('tags', TagController::class);
    Route::post('tags/bulk-delete', [TagController::class, 'bulkDelete'])->name('tags.bulk-delete');

    // RSS Feeds
    Route::prefix('rss-feeds')->name('rss-feeds.')->group(function () {
        Route::get('/', [RssFeedController::class, 'index'])->name('index');
        Route::get('/create', [RssFeedController::class, 'create'])->name('create');
        Route::post('/', [RssFeedController::class, 'store'])->name('store');
        Route::get('/{rssFeed}/edit', [RssFeedController::class, 'edit'])->name('edit');
        Route::put('/{rssFeed}', [RssFeedController::class, 'update'])->name('update');
        Route::patch('/{rssFeed}', [RssFeedController::class, 'update']);
        Route::delete('/{rssFeed}', [RssFeedController::class, 'destroy'])->name('destroy');
        Route::post('/{rssFeed}/import', [RssFeedController::class, 'import'])->name('import');
        Route::post('/import-all', [RssFeedController::class, 'importAll'])->name('import-all');
    });

    // Newsletter Subscribers (Admin & Editor only)
    Route::middleware('role:admin,editor')->group(function () {
        Route::prefix('newsletter/subscribers')->name('newsletter.subscribers.')->group(function () {
            Route::get('/', [NewsletterSubscriberController::class, 'index'])->name('index');
            Route::delete('/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('destroy');
            Route::get('/export', [NewsletterSubscriberController::class, 'export'])->name('export');
            Route::post('/bulk-delete', [NewsletterSubscriberController::class, 'bulkDelete'])->name('bulk-delete');
        });
    });

    // Users (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['create', 'store']);
    });

    // Settings (Admin only)
    Route::middleware('role:admin')->prefix('settings')->name('settings.')->group(function () {
        // Dashboard
        Route::get('/', [SettingsDashboardController::class, 'index'])->name('index');

        // Logo Settings
        Route::get('logos', [LogoSettingController::class, 'index'])->name('logos.index');
        Route::post('logos', [LogoSettingController::class, 'update'])->name('logos.update');

        // Language Logos
        Route::get('language-logos', [LanguageLogoController::class, 'index'])->name('language-logos.index');
        Route::post('language-logos', [LanguageLogoController::class, 'update'])->name('language-logos.update');

        // Website Settings
        Route::get('website', [WebsiteSettingController::class, 'index'])->name('website.index');
        Route::post('website', [WebsiteSettingController::class, 'update'])->name('website.update');

        // Email Settings
        Route::get('email', [EmailSettingController::class, 'index'])->name('email.index');
        Route::post('email', [EmailSettingController::class, 'update'])->name('email.update');
        Route::post('email/test', [EmailSettingController::class, 'testConnection'])->name('email.test');
    });

    // Pages (Admin only)
    Route::middleware('role:admin')->resource('pages', PageController::class);
    Route::post('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])
        ->name('pages.toggle-status')->middleware('role:admin');


    // Cache Clear (Admin only)
    Route::post('cache/clear', function () {
        \Artisan::call('cache:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        return redirect()->back()->with('success', 'Cache cleared!');
    })->name('cache.clear')->middleware('role:admin');
});

Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {
    // ... your existing admin routes ...

    // Sitemap Management
    Route::get('/sitemap', [App\Http\Controllers\Admin\AdminSitemapController::class, 'index'])->name('sitemap.index');
    Route::post('/sitemap/regenerate', [App\Http\Controllers\Admin\AdminSitemapController::class, 'regenerate'])->name('sitemap.regenerate');
    Route::post('/sitemap/submit/google', [App\Http\Controllers\Admin\AdminSitemapController::class, 'submitToGoogle'])->name('sitemap.submit.google');
    Route::post('/sitemap/submit/bing', [App\Http\Controllers\Admin\AdminSitemapController::class, 'submitToBing'])->name('sitemap.submit.bing');
    Route::get('/sitemap/download', [App\Http\Controllers\Admin\AdminSitemapController::class, 'download'])->name('sitemap.download');
});
