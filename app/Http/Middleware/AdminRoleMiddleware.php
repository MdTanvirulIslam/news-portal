<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * This middleware allows all 10 role types to access admin panel
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip authentication check for login and register routes
        if ($request->is('admin/login') ||
            $request->is('admin/register') ||
            $request->is('admin/register/*')) {
            return $next($request);
        }

        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('admin.login')
                ->with('error', 'Please login to continue.');
        }

        $user = Auth::user();

        // Define allowed roles for admin panel (ALL 10 ROLES)
        $allowedRoles = [
            'admin',
            'editor',
            'reporter',
            'contributor',
            'listener',
            'artist',
            'lyricist',
            'composer',
            'label',
            'publisher'
        ];

        // Check if user has allowed role
        if (!in_array($user->role, $allowedRoles)) {
            abort(403, 'Unauthorized. You do not have permission to access admin panel.');
        }

        // Check if user account is active (admins bypass this check)
        if (!$user->is_active && $user->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account is pending admin approval. You will be notified via email once approved.');
        }

        return $next($request);
    }
}
