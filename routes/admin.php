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
use App\Http\Controllers\Admin\EmailVerificationController;
use App\Http\Controllers\Admin\ProfileController;

// ========================================
// GUEST ROUTES (No Authentication Required)
// ========================================
Route::prefix('admin')->middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [AdminAuthController::class, 'create'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'store'])->name('admin.login.store');

    // NEW: General Registration Route
    Route::get('register', [RegistrationController::class, 'create'])->name('admin.register');
    Route::post('register', [RegistrationController::class, 'store'])->name('admin.register.store');

    // OLD: Backward Compatible - Reporter & Contributor Registration
    Route::get('register/reporter', [RegistrationController::class, 'reporterForm'])->name('admin.register.reporter');
    Route::post('register/reporter', [RegistrationController::class, 'registerReporter'])->name('admin.register.reporter.store');

    Route::get('register/contributor', [RegistrationController::class, 'contributorForm'])->name('admin.register.contributor');
    Route::post('register/contributor', [RegistrationController::class, 'registerContributor'])->name('admin.register.contributor.store');
});

// ========================================
// EMAIL VERIFICATION ROUTES (NO AUTH REQUIRED FOR VERIFY LINK)
// ========================================
Route::prefix('admin')->group(function () {
    // Verification link - accessible without authentication
    Route::get('email/verify/{id}/{token}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
});

// ========================================
// AUTHENTICATED ROUTES - ALL USERS
// ========================================
Route::prefix('admin')->middleware(['auth'])->name('admin.')->group(function () {

    // Dashboard - ALL authenticated users can access
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Logout - ALL authenticated users
    Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

    // Email Verification Notice & Resend (requires auth)
    Route::get('email/verify', [EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::post('email/verification-notification', [EmailVerificationController::class, 'resend'])
        ->middleware('throttle:6,1')
        ->name('verification.resend');

    // ========================================
    // POSTS - ALL AUTHENTICATED USERS CAN ACCESS
    // ========================================
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');           // View All Posts
        Route::get('/create', [PostController::class, 'create'])->name('create');   // Create New Post
        Route::post('/', [PostController::class, 'store'])->name('store');          // Store New Post
        Route::get('/{post}', [PostController::class, 'show'])->name('show');       // View Single Post
        Route::get('/{post}/edit', [PostController::class, 'edit'])->name('edit');  // Edit Post
        Route::put('/{post}', [PostController::class, 'update'])->name('update');   // Update Post
        Route::delete('/{post}', [PostController::class, 'destroy'])->name('destroy'); // Delete Post
    });

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/artist', [ProfileController::class, 'updateArtistProfile'])->name('profile.artist.update');
    Route::post('/profile/file/delete/{field}', [ProfileController::class, 'deleteFile'])->name('profile.file.delete');

    Route::post('/profile/lyricist', [ProfileController::class, 'updateLyricistProfile'])->name('profile.lyricist.update');
    Route::post('/profile/composer', [ProfileController::class, 'updateComposerProfile'])->name('profile.composer.update');

    // ========================================
    // ADMIN ONLY ROUTES
    // ========================================
    Route::middleware('role:admin')->group(function () {

        // Categories
        Route::resource('categories', CategoryController::class);
        Route::get('categories/{parent}/subcategories', [CategoryController::class, 'getSubcategories'])
            ->name('categories.subcategories');
        Route::post('categories/bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulk-delete');

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

        // Newsletter Subscribers
        Route::prefix('newsletter/subscribers')->name('newsletter.subscribers.')->group(function () {
            Route::get('/', [NewsletterSubscriberController::class, 'index'])->name('index');
            Route::delete('/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])->name('destroy');
            Route::get('/export', [NewsletterSubscriberController::class, 'export'])->name('export');
            Route::post('/bulk-delete', [NewsletterSubscriberController::class, 'bulkDelete'])->name('bulk-delete');
        });

        // Users Management
        Route::resource('users', UserController::class)->except(['create', 'store']);
        Route::post('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::post('users/{user}/reject', [UserController::class, 'reject'])->name('users.reject');
        Route::get('/users/{user}/profile', [UserController::class, 'viewProfile'])->name('users.profile.view');
        Route::post('/users/{user}/verify-profile', [ProfileController::class, 'verifyProfile'])->name('users.profile.verify');
        Route::post('/users/{user}/unverify-profile', [ProfileController::class, 'unverifyProfile'])->name('users.profile.unverify');
        Route::get('/users/{user}/verification-logs', [ProfileController::class, 'getVerificationLogs'])
            ->name('users.verification.logs');

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
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

        // Pages Management
        Route::resource('pages', PageController::class);
        Route::post('pages/{page}/toggle-status', [PageController::class, 'toggleStatus'])->name('pages.toggle-status');

        // Post Approval (Admin Only)
        Route::post('posts/{post}/approve', [PostController::class, 'approve'])->name('posts.approve');
        Route::post('posts/{post}/reject', [PostController::class, 'reject'])->name('posts.reject');

        // Cache Clear
        Route::post('cache/clear', function () {
            \Artisan::call('cache:clear');
            \Artisan::call('view:clear');
            \Artisan::call('route:clear');
            return redirect()->back()->with('success', 'Cache cleared!');
        })->name('cache.clear');

        // Sitemap Management
        Route::get('sitemap', [App\Http\Controllers\Admin\AdminSitemapController::class, 'index'])->name('sitemap.index');
        Route::post('sitemap/regenerate', [App\Http\Controllers\Admin\AdminSitemapController::class, 'regenerate'])->name('sitemap.regenerate');
        Route::post('sitemap/submit/google', [App\Http\Controllers\Admin\AdminSitemapController::class, 'submitToGoogle'])->name('sitemap.submit.google');
        Route::post('sitemap/submit/bing', [App\Http\Controllers\Admin\AdminSitemapController::class, 'submitToBing'])->name('sitemap.submit.bing');
        Route::get('sitemap/download', [App\Http\Controllers\Admin\AdminSitemapController::class, 'download'])->name('sitemap.download');
    });
});


