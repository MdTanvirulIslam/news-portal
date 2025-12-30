<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo(Request $request): ?string
    {
        // For admin routes, redirect to admin login
        if ($request->is('admin/*') || $request->is('admin')) {
            return route('admin.login');  // Changed from 'admin.login'
        }

        // For frontend routes, you can redirect to frontend login
        // return route('login'); // If you have a frontend login route

        return '/';  // Or redirect to home
    }
}
