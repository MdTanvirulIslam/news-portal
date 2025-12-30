<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->route('admin.login');
        }

        // Define allowed roles for admin panel
        $allowedRoles = ['admin', 'editor', 'reporter', 'contributor'];

        if (!in_array(Auth::user()->role, $allowedRoles)) {
            abort(403, 'Unauthorized. Admin panel access required.');
        }

        // Check if user is active (except admin)
        if (!Auth::user()->is_active && Auth::user()->role !== 'admin') {
            Auth::logout();
            return redirect()->route('admin.login')
                ->with('error', 'Your account is not active. Please contact administrator.');
        }

        return $next($request);
    }
}
