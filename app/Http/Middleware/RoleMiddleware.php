<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$roles
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('admin.login')
                           ->with('error', 'Please login to access this page.');
        }
        
        // Check if user has one of the required roles
        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Unauthorized action. You do not have permission to access this resource.');
        }
        
        return $next($request);
    }
}
