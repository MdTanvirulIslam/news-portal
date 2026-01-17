<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Portal') }} - Register</title>
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px 0;
        }

        .register-container {
            width: 100%;
            max-width: 700px;
            padding: 20px;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .register-header h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .register-header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .register-body {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }

        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-label .required {
            color: #ef4444;
        }

        .form-control, .form-select {
            padding: 10px 15px;
            border-radius: 8px;
            border: 2px solid #e2e8f0;
            transition: all 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .btn-register {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .btn-back {
            background: #6c757d;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-back:hover {
            background: #5a6268;
        }

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

        .form-text {
            font-size: 12px;
            color: #6c757d;
            margin-top: 5px;
            display: block;
        }

        .password-strength {
            margin-top: 5px;
            padding: 8px;
            border-radius: 5px;
            font-size: 12px;
            display: none;
        }

        .password-strength.weak {
            background: #fee2e2;
            color: #991b1b;
            display: block;
        }

        .password-strength.medium {
            background: #fef3c7;
            color: #92400e;
            display: block;
        }

        .password-strength.strong {
            background: #d1fae5;
            color: #065f46;
            display: block;
        }

        .role-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            margin-bottom: 20px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            position: absolute;
            opacity: 0;
        }

        .role-label {
            display: block;
            padding: 15px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--primary);
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
        }

        .role-label i {
            font-size: 24px;
            display: block;
            margin-bottom: 8px;
        }

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
    </style>
</head>
<body>
<!-- Loader -->
<div id="load_screen">
    <div class="text-center">
        <div class="spinner-grow text-primary"></div>
    </div>
</div>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h2><i class="fas fa-user-plus me-2"></i>Create Account</h2>
            <p>Join our community and start your journey</p>
        </div>

        <div class="register-body">
            <!-- Display Messages -->
            @if($errors->any())
                <div class="alert alert-danger">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Registration failed!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.store') }}" id="registerForm">
            @csrf

            <!-- Select Role -->
                <div class="mb-4">
                    <label class="form-label">
                        <i class="fas fa-users me-2"></i>Select Your Profile Type <span class="required">*</span>
                    </label>
                    <div class="role-selector">
                        @foreach($roles as $value => $label)
                            <div class="role-option">
                                <input type="radio" name="role" id="role_{{ $value }}" value="{{ $value }}"
                                       {{ old('role') == $value ? 'checked' : '' }} required>
                                <label for="role_{{ $value }}" class="role-label">
                                    <i class="fas fa-{{ $value == 'listener' ? 'headphones' : ($value == 'artist' ? 'microphone' : ($value == 'lyricist' ? 'pen' : ($value == 'composer' ? 'music' : ($value == 'label' ? 'building' : 'book')))) }}"></i>
                                    <strong>{{ $label }}</strong>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('role')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Full Name -->
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">
                            <i class="fas fa-user me-2"></i>Full Name <span class="required">*</span>
                        </label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Enter your full name">
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="col-md-12 mb-3">
                        <label for="email" class="form-label">
                            <i class="fas fa-envelope me-2"></i>Email Address <span class="required">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required
                               placeholder="your@email.com">
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock me-2"></i>Password <span class="required">*</span>
                        </label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required
                               placeholder="Minimum 8 characters">
                        <div id="password-strength" class="password-strength"></div>
                        @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">
                            <i class="fas fa-lock me-2"></i>Confirm Password <span class="required">*</span>
                        </label>
                        <input type="password" class="form-control" id="password_confirmation"
                               name="password_confirmation" required placeholder="Re-enter password">
                        <div id="password-match" class="form-text"></div>
                    </div>

                    <!-- Country -->
                    <div class="col-md-6 mb-3">
                        <label for="country" class="form-label">
                            <i class="fas fa-globe me-2"></i>Country
                        </label>
                        <select class="form-select @error('country') is-invalid @enderror"
                                id="country" name="country">
                            <option value="">Select Country</option>
                            @foreach($countries as $code => $name)
                                <option value="{{ $code }}" {{ old('country') == $code ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('country')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- City -->
                    <div class="col-md-6 mb-3">
                        <label for="city" class="form-label">
                            <i class="fas fa-city me-2"></i>City
                        </label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror"
                               id="city" name="city" value="{{ old('city') }}" placeholder="Your city">
                        @error('city')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="col-md-12 mb-3">
                        <label for="phone" class="form-label">
                            <i class="fas fa-phone me-2"></i>Phone Number
                        </label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                               id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="+880 1XXX-XXXXXX">
                        <span class="form-text">Optional: We may use this for verification</span>
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="terms_accepted"
                               name="terms_accepted" value="1" required>
                        <label class="form-check-label" for="terms_accepted">
                            I agree to the <a href="#" target="_blank">Terms & Conditions</a> <span class="required">*</span>
                        </label>
                    </div>
                </div>

                <!-- Copyright Agreement -->
                <div class="mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="copyright_accepted"
                               name="copyright_accepted" value="1" required>
                        <label class="form-check-label" for="copyright_accepted">
                            I agree to the <a href="#" target="_blank">Copyright Agreement</a> <span class="required">*</span>
                        </label>
                    </div>
                </div>

                <!-- Alert Info -->
                <div class="alert alert-info mb-3">
                    <small>
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> Artist, Lyricist, Composer, Label, and Publisher accounts require admin approval.
                    </small>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-register text-white" id="submitBtn">
                        <i class="fas fa-user-plus me-2"></i>CREATE ACCOUNT
                    </button>

                    <a href="{{ route('admin.login') }}" class="btn btn-back text-white">
                        <i class="fas fa-arrow-left me-2"></i>BACK TO LOGIN
                    </a>
                </div>

                <!-- Additional Links -->
                <div class="text-center mt-3">
                    <p class="mb-0">
                        Already have an account?
                        <a href="{{ route('admin.login') }}" class="text-primary text-decoration-none fw-bold">Login Here</a>
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

    // Password strength checker
    const passwordInput = document.getElementById('password');
    const strengthDiv = document.getElementById('password-strength');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        const strength = checkPasswordStrength(password);

        strengthDiv.className = 'password-strength ' + strength.class;
        strengthDiv.textContent = strength.message;
    });

    function checkPasswordStrength(password) {
        if (password.length === 0) {
            return { class: '', message: '' };
        }

        let strength = 0;
        const checks = {
            length: password.length >= 8,
            lowercase: /[a-z]/.test(password),
            uppercase: /[A-Z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[^A-Za-z0-9]/.test(password)
        };

        // Count passed checks
        Object.values(checks).forEach(check => {
            if (check) strength++;
        });

        if (strength < 3) {
            return {
                class: 'weak',
                message: '⚠️ Weak password. Add uppercase, numbers & symbols.'
            };
        } else if (strength < 5) {
            return {
                class: 'medium',
                message: '✓ Medium password. Add more variety for stronger security.'
            };
        } else {
            return {
                class: 'strong',
                message: '✓ Strong password! Great job!'
            };
        }
    }

    // Password match checker
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const matchDiv = document.getElementById('password-match');

    confirmPasswordInput.addEventListener('input', function() {
        const password = passwordInput.value;
        const confirmPassword = this.value;

        if (confirmPassword.length === 0) {
            matchDiv.textContent = '';
            matchDiv.style.color = '';
            return;
        }

        if (password === confirmPassword) {
            matchDiv.textContent = '✓ Passwords match';
            matchDiv.style.color = '#065f46';
        } else {
            matchDiv.textContent = '✗ Passwords do not match';
            matchDiv.style.color = '#991b1b';
        }
    });

    // Form validation
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('password_confirmation').value;

        if (password !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
            return false;
        }

        // Disable submit button to prevent double submission
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Account...';
    });

    // Hide alerts after 5 seconds
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-danger, .alert-success');
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
