<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class EmailSettingController extends Controller
{
    /**
     * Display email settings form
     */
    public function index()
    {
        $emailSettings = EmailSetting::getSettings();
        
        // Available options
        $mailDrivers = ['smtp', 'sendmail', 'mailgun', 'ses', 'postmark'];
        $encryptions = ['tls', 'ssl', 'none'];
        
        return view('admin.settings.email.index', compact(
            'emailSettings',
            'mailDrivers',
            'encryptions'
        ));
    }

    /**
     * Update email settings
     */
    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'mail_enabled' => 'required|boolean',
                'mail_driver' => 'required|string|in:smtp,sendmail,mailgun,ses,postmark',
                'mail_host' => 'nullable|string|max:255',
                'mail_port' => 'nullable|integer|min:1|max:65535',
                'mail_encryption' => 'nullable|string|in:tls,ssl,none',
                'mail_username' => 'nullable|string|max:255',
                'mail_password' => 'nullable|string|max:255',
                'mail_from_address' => 'nullable|email|max:255',
                'mail_from_name' => 'nullable|string|max:255',
                'mail_reply_to' => 'nullable|email|max:255',
                'mail_reply_to_name' => 'nullable|string|max:255',
                'mail_timeout' => 'nullable|integer|min:10|max:300',
            ]);

            $emailSettings = EmailSetting::getSettings();
            
            // If password is empty, keep the existing one
            if (empty($validated['mail_password'])) {
                $validated['mail_password'] = $emailSettings->mail_password;
            }

            // Update settings
            EmailSetting::updateSettings($validated);
            
            // Apply settings to config (for this request only)
            $this->applyEmailConfig($validated);
            
            Log::info('Email settings updated successfully');

            return redirect()->route('admin.settings.email.index')
                ->with('success', 'Email settings updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Email settings update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('admin.settings.email.index')
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    /**
     * Test email connection
     */
    public function testConnection(Request $request)
    {
        try {
            $emailSettings = EmailSetting::getSettings();
            
            if (!$emailSettings->mail_enabled) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email is currently disabled. Please enable it first.'
                ], 400);
            }

            // Apply current settings
            $this->applyEmailConfig($emailSettings->toArray());
            
            // Send test email
            Mail::raw('This is a test email from your Laravel application. If you received this, your email configuration is working correctly!', function ($message) use ($emailSettings) {
                $message->to($emailSettings->mail_from_address)
                        ->subject('Test Email - Laravel Configuration');
            });
            
            Log::info('Test email sent successfully');
            
            return response()->json([
                'success' => true,
                'message' => 'Test email sent successfully! Check your inbox at ' . $emailSettings->mail_from_address
            ]);
            
        } catch (\Exception $e) {
            Log::error('Test email failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send test email: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Apply email configuration to Laravel config
     */
    private function applyEmailConfig(array $settings)
    {
        Config::set('mail.default', $settings['mail_driver']);
        Config::set('mail.mailers.smtp.transport', 'smtp');
        Config::set('mail.mailers.smtp.host', $settings['mail_host']);
        Config::set('mail.mailers.smtp.port', $settings['mail_port']);
        Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] === 'none' ? null : $settings['mail_encryption']);
        Config::set('mail.mailers.smtp.username', $settings['mail_username']);
        Config::set('mail.mailers.smtp.password', $settings['mail_password']);
        Config::set('mail.mailers.smtp.timeout', $settings['mail_timeout'] ?? 30);
        Config::set('mail.from.address', $settings['mail_from_address']);
        Config::set('mail.from.name', $settings['mail_from_name']);
    }
}
