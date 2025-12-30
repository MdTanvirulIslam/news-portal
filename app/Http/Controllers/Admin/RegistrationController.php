<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegistrationController extends Controller
{
    /**
     * Show reporter registration form
     */
    public function reporterForm()
    {
        return view('admin.auth.register-reporter');
    }

    /**
     * Show contributor registration form
     */
    public function contributorForm()
    {
        return view('admin.auth.register-contributor');
    }

    /**
     * Handle reporter registration
     */
    public function registerReporter(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'reporter',
            'is_active' => false, // Reporter accounts need admin approval
        ]);

        // You might want to send an email notification to admin here

        return redirect()->route('admin.login')
            ->with('success', 'Reporter account created successfully! Please wait for admin approval.');
    }

    /**
     * Handle contributor registration
     */
    public function registerContributor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'contributor',
            'is_active' => true, // Contributor accounts are active by default
        ]);

        return redirect()->route('admin.login')
            ->with('success', 'Contributor account created successfully! You can now login.');
    }
}
