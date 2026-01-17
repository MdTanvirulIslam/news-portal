<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approved</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background-color: #f4f4f7;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .celebration-icon {
            font-size: 70px;
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .email-header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .email-body p {
            color: #555;
            line-height: 1.7;
            margin-bottom: 20px;
            font-size: 16px;
        }
        .login-button {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white !important;
            padding: 16px 45px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 25px 0;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            transition: transform 0.2s;
        }
        .login-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }
        .info-box {
            background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
            border-left: 5px solid #10b981;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .info-box strong {
            color: #059669;
            font-size: 18px;
        }
        .features-list {
            list-style: none;
            padding: 0;
            margin: 25px 0;
        }
        .features-list li {
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
            font-size: 15px;
            color: #374151;
        }
        .features-list li:last-child {
            border-bottom: none;
        }
        .features-list i {
            color: #10b981;
            margin-right: 12px;
            font-size: 18px;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .support-info {
            background: #eff6ff;
            padding: 18px;
            border-radius: 8px;
            margin: 25px 0;
            text-align: center;
            border: 1px solid #dbeafe;
        }
        .credentials-box {
            background: #f9fafb;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
            border: 2px dashed #d1d5db;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="celebration-icon">🎉</div>
            <h1>Congratulations!</h1>
            <p style="margin: 15px 0 0 0; opacity: 0.95; font-size: 18px;">Your Account Has Been Approved</p>
        </div>
        
        <div class="email-body">
            <h2>Welcome Aboard, {{ $user->name }}! 🎊</h2>
            
            <p><strong>Fantastic news!</strong> Your <strong>{{ ucfirst($user->role) }}</strong> account has been reviewed and approved by our admin team. You're now ready to access all platform features!</p>
            
            <div class="info-box">
                <strong>✅ Your Account Is Now Active!</strong><br>
                <span style="color: #059669;">You can login immediately and start using all features available to {{ ucfirst($user->role) }}s.</span>
            </div>
            
            <div style="text-align: center; margin: 35px 0;">
                <a href="{{ route('admin.login') }}" class="login-button">
                    🚀 LOGIN TO YOUR ACCOUNT
                </a>
            </div>
            
            <h3 style="color: #111827; margin-top: 35px; font-size: 20px;">🎯 What's Next?</h3>
            
            <ul class="features-list">
                @if($user->role === 'artist')
                    <li>✨ Complete your artist profile with your bio and portfolio</li>
                    <li>🎵 Upload your music samples and showcase your work</li>
                    <li>🤝 Connect and collaborate with lyricists and composers</li>
                    <li>🌟 Start building your presence and growing your fanbase</li>
                @elseif($user->role === 'lyricist')
                    <li>✨ Complete your lyricist profile and writing style</li>
                    <li>📝 Share your best lyrics and writing portfolio</li>
                    <li>🤝 Collaborate with talented artists and composers</li>
                    <li>🎨 Create amazing lyrics and build your reputation</li>
                @elseif($user->role === 'composer')
                    <li>✨ Set up your composer profile with your expertise</li>
                    <li>🎼 Upload your music compositions and arrangements</li>
                    <li>🤝 Work with artists, lyricists, and producers</li>
                    <li>🎹 Showcase your musical talent and creativity</li>
                @elseif($user->role === 'label')
                    <li>✨ Complete your label profile and company information</li>
                    <li>👥 Manage your artists roster and contracts</li>
                    <li>🔍 Discover and sign new talented artists</li>
                    <li>📄 Handle releases, distribution, and licensing</li>
                @elseif($user->role === 'publisher')
                    <li>✨ Set up your publisher profile and credentials</li>
                    <li>⚖️ Manage music rights and royalty collections</li>
                    <li>🤝 Connect with content creators and rights holders</li>
                    <li>📋 Handle licensing agreements and contracts</li>
                @elseif($user->role === 'reporter')
                    <li>✨ Access your personalized admin dashboard</li>
                    <li>✍️ Create and publish engaging posts and articles</li>
                    <li>📊 Manage and organize your content library</li>
                    <li>🚀 Start contributing to our growing community</li>
                @else
                    <li>✨ Explore your personalized dashboard and features</li>
                    <li>✍️ Create your first post and share your thoughts</li>
                    <li>🤝 Connect with our vibrant community members</li>
                    <li>🚀 Begin your exciting journey with us today</li>
                @endif
            </ul>
            
            <div class="support-info">
                <strong style="color: #1e40af;">💡 Need Help Getting Started?</strong><br>
                <span style="color: #3b82f6;">Our support team is here to help! Feel free to reach out if you have any questions or need assistance.</span>
            </div>
            
            <div class="credentials-box">
                <p style="margin: 0 0 10px 0; font-weight: 600; color: #111827; font-size: 16px;">🔐 Your Login Information:</p>
                <p style="margin: 5px 0; color: #374151;"><strong>Email:</strong> {{ $user->email }}</p>
                <p style="margin: 5px 0; font-size: 14px; color: #6b7280;">Use the password you created during registration</p>
            </div>
            
            <p style="margin-top: 30px; font-size: 15px; color: #374151;">
                We're excited to have you as part of our community. Let's create something amazing together! 🎵
            </p>
        </div>
        
        <div class="email-footer">
            <p style="margin: 0 0 15px 0; font-weight: 600; font-size: 16px; color: #111827;">
                Welcome to {{ config('app.name') }}! 🎶
            </p>
            <p style="margin: 0;">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p style="margin: 15px 0 0 0; font-size: 13px;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
