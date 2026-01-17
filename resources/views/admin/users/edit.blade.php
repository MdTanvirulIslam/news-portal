@extends('admin.layouts.layout')

@section('styles')
    <style>
        .edit-user-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            margin-bottom: 25px;
        }

        .form-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .role-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            margin: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="edit-user-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">✏️ Edit User</h4>
                <p class="mb-0" style="opacity: 0.9; font-size: 14px;">Update user information and permissions</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="form-card">
                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Name --}}
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Role --}}
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">User Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror"
                                    id="role"
                                    name="role"
                                    required>
                                <option value="">Select Role</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="editor" {{ old('role', $user->role) == 'editor' ? 'selected' : '' }}>Editor</option>
                                <option value="reporter" {{ old('role', $user->role) == 'reporter' ? 'selected' : '' }}>Reporter</option>
                                <option value="contributor" {{ old('role', $user->role) == 'contributor' ? 'selected' : '' }}>Contributor</option>
                                <option value="listener" {{ old('role', $user->role) == 'listener' ? 'selected' : '' }}>Listener</option>
                                <option value="artist" {{ old('role', $user->role) == 'artist' ? 'selected' : '' }}>Artist</option>
                                <option value="lyricist" {{ old('role', $user->role) == 'lyricist' ? 'selected' : '' }}>Lyricist</option>
                                <option value="composer" {{ old('role', $user->role) == 'composer' ? 'selected' : '' }}>Composer</option>
                                <option value="label" {{ old('role', $user->role) == 'label' ? 'selected' : '' }}>Label/Owner</option>
                                <option value="publisher" {{ old('role', $user->role) == 'publisher' ? 'selected' : '' }}>Publisher</option>
                            </select>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="col-md-6 mb-3">
                            <label for="is_active" class="form-label">Account Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('is_active') is-invalid @enderror"
                                    id="is_active"
                                    name="is_active"
                                    required>
                                <option value="1" {{ old('is_active', $user->is_active) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('is_active', $user->is_active) == 0 ? 'selected' : '' }}>Inactive (Pending Approval)</option>
                            </select>
                            @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password (Optional)</label>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Leave blank to keep current password">
                            <small class="text-muted">Minimum 8 characters</small>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password"
                                   class="form-control"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Re-enter new password">
                        </div>
                    </div>

                    {{-- User Info Card --}}
                    <div class="alert alert-info mt-3">
                        <h6 class="mb-2"><i class="fas fa-info-circle me-2"></i>User Information</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <small><strong>Posts Created:</strong> {{ $user->posts_count ?? 0 }}</small>
                            </div>
                            <div class="col-md-4">
                                <small><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</small>
                            </div>
                            <div class="col-md-4">
                                <small><strong>Last Login:</strong> {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</small>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Show/hide password confirmation based on password input
        $('#password').on('input', function() {
            if ($(this).val().length > 0) {
                $('#password_confirmation').prop('required', true);
            } else {
                $('#password_confirmation').prop('required', false);
            }
        });
    </script>
@endsection
