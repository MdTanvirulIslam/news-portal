<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EditorRoleMiddleware
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

        $user = Auth::user();
        if (!$user->isAdmin() && !$user->isEditor()) {
            abort(403, 'Unauthorized. Editor or higher access required.');
        }

        return $next($request);
    }
}
