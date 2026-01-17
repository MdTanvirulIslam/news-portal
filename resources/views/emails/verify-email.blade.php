<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 28px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #333;
            font-size: 22px;
            margin-bottom: 20px;
        }
        .email-body p {
            color: #555;
            line-height: 1.6;
            margin-bottom: 20px;
        }
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
        }
        .verify-button:hover {
            opacity: 0.9;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .link-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            word-break: break-all;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
<div class="email-container">
    <div class="email-header">
        <h1>✉️ Verify Your Email</h1>
    </div>

    <div class="email-body">
        <h2>Hello {{ $user->name }}!</h2>

        <p>Thank you for registering! Please verify your email address to activate your account.</p>

        <p>Click the button below to verify your email:</p>

        <div style="text-align: center;">
            <a href="{{ $verificationUrl }}" class="verify-button">
                Verify Email Address
            </a>
        </div>

        <p>Or copy and paste this link into your browser:</p>

        <div class="link-box">
            {{ $verificationUrl }}
        </div>

        <p><strong>This link will expire in 24 hours.</strong></p>

        <p>If you didn't create an account, please ignore this email.</p>
    </div>

    <div class="email-footer">
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</div>
</body>
</html>
