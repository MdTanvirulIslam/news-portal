<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * Show login form
     */
    public function create()
    {
        return view('admin.auth.login');
    }

    /**
     * Handle login
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Check if user has admin access
            if (in_array(Auth::user()->role, ['admin', 'editor', 'reporter', 'contributor'])) {
                return redirect()->intended(route('admin.dashboard'));
            }

            // If not admin role, logout
            Auth::logout();
            throw ValidationException::withMessages([
                'email' => 'You do not have admin access.',
            ]);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Handle logout
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
