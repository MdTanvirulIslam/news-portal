<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class RegistrationController extends Controller
{
    /**
     * Show general registration form
     */
    public function create()
    {
        $roles = [
            'listener' => 'Listener',
            'artist' => 'Artist',
            'lyricist' => 'Lyricist',
            'composer' => 'Composer',
            'label' => 'Label/Owner',
            'publisher' => 'Publisher',
        ];

        $countries = $this->getCountries();

        return view('admin.auth.register', compact('roles', 'countries'));
    }

    /**
     * Handle user registration with email verification
     * Uses QUEUE for email sending
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:listener,artist,lyricist,composer,label,publisher'],
            'country' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'terms_accepted' => ['required', 'accepted'],
            'copyright_accepted' => ['required', 'accepted'],
        ]);

        // Check if role requires approval
        $requiresApproval = in_array($request->role, User::APPROVAL_REQUIRED_ROLES);

        // Generate email verification token
        $verificationToken = Str::random(64);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'country' => $request->country,
            'city' => $request->city,
            'phone' => $request->phone,
            'terms_accepted' => true,
            'copyright_accepted' => true,
            'is_active' => !$requiresApproval, // Auto-approve listeners
            'profile_completed' => false,
            'email_verification_token' => $verificationToken,
            'email_verification_sent_at' => now(),
        ]);

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'token' => $verificationToken
        ]);

        // Send verification email using QUEUE
        try {
            Mail::to($user->email)->queue(new VerifyEmail($user, $verificationUrl));

            $message = 'Registration successful! Please check your email to verify your account.';

            if ($requiresApproval) {
                $message .= ' Your account is also pending admin approval.';
            }
        } catch (\Exception $e) {
            \Log::error('Failed to queue verification email: ' . $e->getMessage());
            $message = 'Registration successful but failed to send verification email. Please contact support.';
        }

        return redirect()->route('admin.login')->with('success', $message);
    }

    /**
     * Show reporter registration form (backward compatible)
     */
    public function reporterForm()
    {
        return view('admin.auth.register-reporter');
    }

    /**
     * Show contributor registration form (backward compatible)
     */
    public function contributorForm()
    {
        return view('admin.auth.register-contributor');
    }

    /**
     * Handle reporter registration (backward compatible)
     */
    public function registerReporter(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate email verification token
        $verificationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'reporter',
            'is_active' => false,
            'terms_accepted' => true,
            'copyright_accepted' => true,
            'email_verification_token' => $verificationToken,
            'email_verification_sent_at' => now(),
        ]);

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'token' => $verificationToken
        ]);

        // Send verification email using QUEUE
        try {
            Mail::to($user->email)->queue(new VerifyEmail($user, $verificationUrl));
        } catch (\Exception $e) {
            \Log::error('Failed to queue verification email: ' . $e->getMessage());
        }

        return redirect()->route('admin.login')
            ->with('success', 'Registration successful! Please check your email to verify your account. Your account also requires admin approval.');
    }

    /**
     * Handle contributor registration (backward compatible)
     */
    public function registerContributor(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Generate email verification token
        $verificationToken = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'contributor',
            'is_active' => true,
            'terms_accepted' => true,
            'copyright_accepted' => true,
            'email_verification_token' => $verificationToken,
            'email_verification_sent_at' => now(),
        ]);

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'token' => $verificationToken
        ]);

        // Send verification email using QUEUE
        try {
            Mail::to($user->email)->queue(new VerifyEmail($user, $verificationUrl));
        } catch (\Exception $e) {
            \Log::error('Failed to queue verification email: ' . $e->getMessage());
        }

        return redirect()->route('admin.login')
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Get list of countries
     */
    private function getCountries()
    {
        return [
            'BD' => 'Bangladesh',
            'IN' => 'India',
            'PK' => 'Pakistan',
            'US' => 'United States',
            'GB' => 'United Kingdom',
            'CA' => 'Canada',
            'AU' => 'Australia',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'MY' => 'Malaysia',
        ];
    }
}
