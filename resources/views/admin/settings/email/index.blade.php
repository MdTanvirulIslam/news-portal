@extends('admin.layouts.layout')

@section('title', 'Email Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="card-body text-white">
                    <h2 class="mb-0"><i class="fas fa-envelope me-2"></i>Email Settings</h2>
                    <p class="mb-0 opacity-75">Configure SMTP settings for sending emails</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.email.update') }}" method="POST" id="emailForm">
                        @csrf

                        {{-- Enable/Disable Email --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-toggle-on text-primary me-2"></i>Enable Email
                            </label>
                            <select class="form-select form-select-lg" name="mail_enabled">
                                <option value="1" {{ $emailSettings->mail_enabled ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ !$emailSettings->mail_enabled ? 'selected' : '' }}>Disabled</option>
                            </select>
                            <small class="text-muted">Enable or disable email functionality</small>
                        </div>

                        {{-- Mail Driver --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-server text-primary me-2"></i>Mail Driver
                            </label>
                            <select class="form-select form-select-lg" name="mail_driver">
                                @foreach($mailDrivers as $driver)
                                    <option value="{{ $driver }}" {{ $emailSettings->mail_driver === $driver ? 'selected' : '' }}>
                                        {{ strtoupper($driver) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- SMTP Settings --}}
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-cog text-primary me-2"></i>SMTP Configuration
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">SMTP Host</label>
                                    <input type="text"
                                           class="form-control form-control-lg"
                                           name="mail_host"
                                           value="{{ old('mail_host', $emailSettings->mail_host) }}"
                                           placeholder="smtp.gmail.com">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">SMTP Port</label>
                                    <input type="number"
                                           class="form-control form-control-lg"
                                           name="mail_port"
                                           value="{{ old('mail_port', $emailSettings->mail_port) }}"
                                           placeholder="587">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">Encryption</label>
                                    <select class="form-select form-select-lg" name="mail_encryption">
                                        @foreach($encryptions as $encryption)
                                            <option value="{{ $encryption }}" {{ $emailSettings->mail_encryption === $encryption ? 'selected' : '' }}>
                                                {{ strtoupper($encryption) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Authentication --}}
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-user-lock text-primary me-2"></i>Authentication
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">SMTP Username</label>
                                    <input type="text"
                                           class="form-control form-control-lg"
                                           name="mail_username"
                                           value="{{ old('mail_username', $emailSettings->mail_username) }}"
                                           placeholder="your-email@gmail.com">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">SMTP Password</label>
                                    <input type="password"
                                           class="form-control form-control-lg"
                                           name="mail_password"
                                           placeholder="Leave empty to keep current password">
                                    <small class="text-muted">Leave blank to keep existing password</small>
                                </div>
                            </div>
                        </div>

                        {{-- From Address --}}
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-paper-plane text-primary me-2"></i>Email Sender Information
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">From Email</label>
                                    <input type="email"
                                           class="form-control form-control-lg"
                                           name="mail_from_address"
                                           value="{{ old('mail_from_address', $emailSettings->mail_from_address) }}"
                                           placeholder="noreply@yourdomain.com">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">From Name</label>
                                    <input type="text"
                                           class="form-control form-control-lg"
                                           name="mail_from_name"
                                           value="{{ old('mail_from_name', $emailSettings->mail_from_name) }}"
                                           placeholder="Your Website Name">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Reply-To Email</label>
                                    <input type="email"
                                           class="form-control form-control-lg"
                                           name="mail_reply_to"
                                           value="{{ old('mail_reply_to', $emailSettings->mail_reply_to) }}"
                                           placeholder="support@yourdomain.com">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Reply-To Name</label>
                                    <input type="text"
                                           class="form-control form-control-lg"
                                           name="mail_reply_to_name"
                                           value="{{ old('mail_reply_to_name', $emailSettings->mail_reply_to_name) }}"
                                           placeholder="Support Team">
                                </div>
                            </div>
                        </div>

                        {{-- Timeout --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-clock text-primary me-2"></i>Connection Timeout (seconds)
                            </label>
                            <input type="number"
                                   class="form-control form-control-lg"
                                   name="mail_timeout"
                                   value="{{ old('mail_timeout', $emailSettings->mail_timeout) }}"
                                   min="10"
                                   max="300">
                            <small class="text-muted">Maximum time to wait for SMTP connection</small>
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary btn-lg px-4" id="testBtn"
                                    style="border-radius: 10px;">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>

                            <button type="submit" class="btn btn-lg px-5" id="submitBtn"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);">
                                <i class="fas fa-save me-2"></i>Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Form submit with loading state
document.getElementById('emailForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    btn.disabled = true;
});

// Test email connection
document.getElementById('testBtn').addEventListener('click', function() {
    const btn = this;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
    btn.disabled = true;

    fetch('{{ route('admin.settings.email.test') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Test Email';
        btn.disabled = false;

        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Test Email Sent!',
                text: data.message,
                confirmButtonColor: '#667eea'
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Test Failed',
                text: data.message,
                confirmButtonColor: '#667eea'
            });
        }
    })
    .catch(error => {
        btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Test Email';
        btn.disabled = false;

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to send test email. Please check your settings.',
            confirmButtonColor: '#667eea'
        });
    });
});

// Show success/error messages
@if(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        background: '#d4edda',
        color: '#155724'
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '{{ session('error') }}',
        confirmButtonColor: '#667eea'
    });
@endif

@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        confirmButtonColor: '#667eea'
    });
@endif
</script>
@endsection
