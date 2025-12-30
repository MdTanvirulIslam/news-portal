<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
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

        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access required.');
        }

        return $next($request);
    }
}
