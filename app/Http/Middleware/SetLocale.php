<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get locale from URL (first segment)
        $locale = $request->segment(1);

        // Available languages
        $availableLocales = ['en', 'bn'];

        // Check if locale is valid
        if (in_array($locale, $availableLocales)) {
            // Set application locale
            App::setLocale($locale);

            // Store in session for persistence
            Session::put('locale', $locale);
        } else {
            // Default to Bangla if no valid locale
            App::setLocale('bn');
            Session::put('locale', 'bn');
        }

        return $next($request);
    }
}
