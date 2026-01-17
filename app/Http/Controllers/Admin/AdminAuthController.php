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

            $user = Auth::user();

            // Define allowed roles
            $allowedRoles = [
                'admin', 'editor', 'reporter', 'contributor',
                'listener', 'artist', 'lyricist', 'composer', 'label', 'publisher'
            ];

            // Check if user has allowed role
            if (!in_array($user->role, $allowedRoles)) {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'You do not have permission to access this system.',
                ]);
            }

            // Check if email is verified
            if (!$user->hasVerifiedEmail()) {
                // Don't logout, just redirect to verification notice
                return redirect()->route('verification.notice');
            }

            // Check if account is active (except admin)
            if (!$user->is_active && $user->role !== 'admin') {
                Auth::logout();
                throw ValidationException::withMessages([
                    'email' => 'Your account is pending admin approval. You will be notified via email once approved.',
                ]);
            }

            // Update last login info
            $user->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip(),
            ]);

            // Successful login
            return redirect()->intended(route('admin.dashboard'))
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Login failed
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

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}
