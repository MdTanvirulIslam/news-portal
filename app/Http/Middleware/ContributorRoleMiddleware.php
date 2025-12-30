<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ContributorRoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Skip for login and register routes
        if ($request->is('admin/login') || $request->is('admin/register/*')) {
            return $next($request);
        }

        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // All authenticated users can access contributor routes
        return $next($request);
    }
}
