<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EmailVerificationController extends Controller
{
    /**
     * Show email verification notice
     */
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.verify-email');
    }

    /**
     * Verify email with token
     */
    public function verify(Request $request, $id, $token)
    {
        $user = User::findOrFail($id);

        // Check if email is already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->route('admin.login')
                ->with('info', 'Email already verified. You can login now.');
        }

        // Check if token matches
        if ($user->email_verification_token !== $token) {
            return redirect()->route('admin.login')
                ->with('error', 'Invalid verification link.');
        }

        // Check if token is expired (24 hours)
        if ($user->email_verification_sent_at) {
            // Convert to Carbon if it's a string
            $sentAt = $user->email_verification_sent_at instanceof \Carbon\Carbon
                ? $user->email_verification_sent_at
                : Carbon::parse($user->email_verification_sent_at);

            // Check if expired (24 hours)
            if ($sentAt->addHours(24)->isPast()) {
                return redirect()->route('admin.login')
                    ->with('error', 'Verification link has expired. Please login and request a new verification email.');
            }
        }

        // Mark email as verified
        $user->markEmailAsVerified();
        $user->email_verification_token = null;
        $user->email_verification_sent_at = null;
        $user->save();

        return redirect()->route('admin.login')
            ->with('success', 'Email verified successfully! You can now login.');
    }

    /**
     * Resend verification email using QUEUE
     */
    public function resend(Request $request)
    {
        $user = auth()->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('admin.dashboard');
        }

        // Generate new verification token
        $token = Str::random(64);
        $user->email_verification_token = $token;
        $user->email_verification_sent_at = now();
        $user->save();

        // Generate verification URL
        $verificationUrl = route('verification.verify', [
            'id' => $user->id,
            'token' => $token
        ]);

        // Send verification email using QUEUE
        try {
            Mail::to($user->email)->queue(new VerifyEmail($user, $verificationUrl));

            return back()->with('success', 'Verification email queued! Please check your inbox in a moment.');
        } catch (\Exception $e) {
            \Log::error('Failed to queue verification email: ' . $e->getMessage());
            return back()->with('error', 'Failed to send verification email. Please try again later.');
        }
    }
}
