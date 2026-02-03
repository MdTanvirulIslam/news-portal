<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfileCompleted
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Skip for admin
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Skip for profile edit route
        if ($request->routeIs('admin.profile.*')) {
            return $next($request);
        }

        // Check if profile is completed
        if (!$user->profile_completed && $user->requiresApproval()) {
            return redirect()->route('admin.profile.edit')
                ->with('info', 'Please complete your profile to continue.');
        }

        return $next($request);
    }
}
