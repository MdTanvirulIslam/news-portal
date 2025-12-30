<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::user();
                $adminRoles = ['admin', 'editor', 'reporter', 'contributor'];

                // If user is trying to access login/register pages
                if ($request->is('admin/login') || $request->is('admin/register/*')) {
                    if (in_array($user->role, $adminRoles) && ($user->is_active || $user->role === 'admin')) {
                        return redirect()->route('admin.dashboard');
                    }
                    return redirect('/');
                }

                // For admin users, redirect to dashboard
                if (in_array($user->role, $adminRoles) && ($user->is_active || $user->role === 'admin')) {
                    return redirect()->route('admin.dashboard');
                }

                // For non-admin users
                return redirect('/');
            }
        }

        return $next($request);
    }
}
