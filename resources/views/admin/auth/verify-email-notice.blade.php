<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Email - {{ config('app.name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .verify-container {
            width: 100%;
            max-width: 500px;
            padding: 20px;
        }

        .verify-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .verify-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .verify-icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .verify-header h2 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .verify-body {
            padding: 40px 30px;
            text-align: center;
        }

        .verify-body p {
            color: #6c757d;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .btn-resend {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 14px 30px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-resend:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .email-display {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            font-weight: 600;
            color: var(--primary);
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="verify-container">
        <div class="verify-card">
            <div class="verify-header">
                <div class="verify-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <h2>Verify Your Email</h2>
                <p style="opacity: 0.9; font-size: 14px;">Check your inbox to continue</p>
            </div>

            <div class="verify-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    </div>
                @endif

                <p>
                    <strong>Thanks for signing up!</strong><br>
                    Before getting started, please verify your email address by clicking on the link we just emailed to:
                </p>

                <div class="email-display">
                    <i class="fas fa-envelope me-2"></i>{{ auth()->user()->email }}
                </div>

                <p class="text-muted">
                    If you didn't receive the email, we will gladly send you another.
                </p>

                <form method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-resend text-white w-100 mb-3">
                        <i class="fas fa-paper-plane me-2"></i>Resend Verification Email
                    </button>
                </form>

                <div class="mt-4">
                    <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-link text-decoration-none">
                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="{{ asset('assets/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
