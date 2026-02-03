<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontEnd\HomeController;
use App\Http\Controllers\FrontEnd\CategoryController;
use App\Http\Controllers\FrontEnd\PostController;
use App\Http\Controllers\FrontEnd\TagController;
use App\Http\Controllers\FrontEnd\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\FrontEnd\UserController;

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
        $adminRoles = ['admin','editor','reporter','contributor','listener','artist','lyricist','composer','label','publisher'];
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

// ========================================
// MULTILINGUAL ROUTES (WITH LANGUAGE PREFIX)
// ========================================
Route::group([
    'prefix' => '{locale}',
    'where' => ['locale' => 'en|bn'],
    'middleware' => 'web'  // ✅ ONLY web middleware, NO locale middleware
], function () {

    // HOMEPAGE
    Route::get('/', [HomeController::class, 'index'])->name('home.index');

    // POSTS
    Route::prefix('post')->name('post.')->group(function () {
        Route::get('/', [PostController::class, 'index'])->name('index');
        Route::get('/type/{type}', [PostController::class, 'byType'])->name('type');
        Route::get('/{slug}', [PostController::class, 'show'])->name('show');
        Route::post('/{id}/like', [PostController::class, 'like'])->name('like');
    });

    // AJAX/API ENDPOINTS
    Route::get('/breaking-news', [PostController::class, 'breaking'])->name('breaking');
    Route::get('/featured-posts', [PostController::class, 'featured'])->name('featured');

    // CATEGORIES
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/{slug}/load-more', [CategoryController::class, 'loadMore'])->name('loadMore');
        Route::get('/{slug}', [CategoryController::class, 'show'])->name('show');
    });

    // TAGS
    Route::prefix('tag')->name('tag.')->group(function () {
        Route::get('/', [TagController::class, 'index'])->name('index');
        Route::get('/{slug}/load-more', [TagController::class, 'loadMore'])->name('loadMore');
        Route::get('/{slug}', [TagController::class, 'show'])->name('show');
    });

    // PAGES
    Route::get('/page/{slug}', [PageController::class, 'show'])->name('page.show');

    // ========================================
    // USER PROFILE ROUTES
    // ✅ ALTERNATE SOLUTION: Controller handles locale
    // ========================================
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/{id}', [UserController::class, 'show'])->name('profile');
        Route::get('/{id}/load-more', [UserController::class, 'loadMorePosts'])->name('loadMore');
    });

});

// FALLBACK ROUTE (404 Handler)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
