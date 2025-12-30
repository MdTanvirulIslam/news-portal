<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'News Portal') }} - Admin Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/src/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .login-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 16px;
            opacity: 0.9;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 14px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        /* Loader */
        #load_screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .spinner-grow {
            width: 3rem;
            height: 3rem;
        }

        /* Alerts */
        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
    </style>
</head>
<body>
<!-- Loader -->
<div id="load_screen">
    <div class="text-center">
        <div class="spinner-grow text-primary"></div>
    </div>
</div>

<div class="login-container">
    <div class="login-card">
        <div class="login-header">
            <h2>News Portal</h2>
            <p>Admin Panel Login</p>
        </div>

        <div class="login-body">
            <!-- Display Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong>Login failed!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input
                        type="email"
                        class="form-control @error('email') is-invalid @enderror"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="admin@example.com"
                    >
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        id="password"
                        name="password"
                        required
                        placeholder="Enter password"
                    >
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn btn-login w-100 text-white mb-3">
                    LOGIN TO DASHBOARD
                </button>

                <div class="text-center">
                    <p class="mb-2">
                        <a href="#" class="text-warning text-decoration-none">Forgot your password?</a>
                    </p>
                    <p class="mb-0">
                        Don't have an account?
                        <a href="{{ route('admin.register.reporter') }}" class="text-primary text-decoration-none">Register as Reporter</a> or
                        <a href="{{ route('admin.register.contributor') }}" class="text-primary text-decoration-none">Contributor</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/src/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
    // Hide loader
    window.addEventListener('load', function() {
        setTimeout(function() {
            document.getElementById('load_screen').style.opacity = '0';
            document.getElementById('load_screen').style.transition = 'opacity 0.5s';
            setTimeout(function() {
                document.getElementById('load_screen').style.display = 'none';
            }, 500);
        }, 500);
    });

    // Hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.style.display = 'none';
            }, 500);
        });
    }, 5000);
</script>
</body>
</html>
