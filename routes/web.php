<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\CategoryController;
use App\Http\Controllers\FrontEnd\PostController;
use App\Http\Controllers\FrontEnd\TagController;
use App\Http\Controllers\FrontEnd\PageController;
use App\Http\Controllers\FrontEnd\SearchController;
use App\Http\Controllers\SitemapController;

// ========================================
// AUTH REDIRECTS
// ========================================
Route::get('/admin', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::get('/register', function () {
    return redirect()->route('admin.register.reporter');
})->name('register');

Route::get('/home', function () {
    if (auth()->check()) {
        $adminRoles = ['admin', 'editor', 'reporter', 'contributor'];
        if (in_array(auth()->user()->role, $adminRoles)) {
            return redirect()->route('admin.dashboard');
        }
    }
    return redirect('/');
})->name('home');

// ========================================
// ROOT REDIRECT TO DEFAULT LANGUAGE (BANGLA)
// ========================================
Route::get('/', function () {
    // Get user's preferred language from session, default to Bangla
    $locale = session('locale', 'bn');
    return redirect()->route('home.index', ['locale' => $locale]);
});

// ========================================
// LANGUAGE SWITCHER ROUTE
// ========================================
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'bn'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('language.switch');

// ========================================
// PUBLIC SITEMAPS (No Language Prefix)
// ========================================
Route::get('/sitemap.xml', [SitemapController::class, 'xml'])->name('sitemap.xml');
Route::get('/sitemap', [SitemapController::class, 'html'])->name('sitemap.html');

// RSS Feed (optional)
Route::get('/feed', [App\Http\Controllers\RssFeedController::class, 'index'])->name('feed');

// ========================================
// MULTILINGUAL ROUTES (WITH LANGUAGE PREFIX)
// ========================================
Route::group(['prefix' => '{locale}', 'where' => ['locale' => 'en|bn'], 'middleware' => 'web'], function () {

    // ========================================
    // HOMEPAGE
    // ========================================
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    // ========================================
    // POSTS
    // ========================================
    Route::prefix('post')->name('post.')->group(function () {
        // All posts listing
        Route::get('/', [PostController::class, 'index'])->name('index');

        // Posts by type (video, gallery, article)
        Route::get('/type/{type}', [PostController::class, 'byType'])->name('type');

        // Single post (MUST BE LAST to avoid conflicts)
        Route::get('/{slug}', [PostController::class, 'show'])->name('show');

        // Like post (AJAX)
        Route::post('/{id}/like', [PostController::class, 'like'])->name('like');
    });

    // ========================================
    // AJAX/API ENDPOINTS (for dynamic loading)
    // ========================================
    // Breaking news (JSON)
    Route::get('/breaking-news', [PostController::class, 'breaking'])->name('breaking');

    // Featured posts (JSON)
    Route::get('/featured-posts', [PostController::class, 'featured'])->name('featured');

    // ========================================
    // CATEGORIES
    // ========================================
    Route::prefix('category')->name('category.')->group(function () {
        // All categories
        Route::get('/', [CategoryController::class, 'index'])->name('index');

        // Single category
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
    });

    // ========================================
    // TAGS
    // ========================================
    Route::prefix('tag')->name('tag.')->group(function () {
        // All tags
        Route::get('/', [TagController::class, 'index'])->name('index');

        // Single tag
        Route::get('/{slug}', [TagController::class, 'show'])->name('show');
    });

    // ========================================
    // PAGES (About, Contact, Privacy, etc.)
    // ========================================
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

    // ========================================
    // SEARCH
    // ========================================
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Alternative: If you want to use PostController's search method instead
    // Route::get('/search', [PostController::class, 'search'])->name('search');

});

// ========================================
// FALLBACK ROUTE (404 Handler)
// ========================================
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
