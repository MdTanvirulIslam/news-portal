@extends('admin.layouts.layout')

@section('content')
<div class="row">
    <div class="col-xl-8 mx-auto">
        <div class="widget-content widget-content-area br-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Edit User: {{ $user->name }}</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email Address *</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="editor" {{ old('role', $user->role) === 'editor' ? 'selected' : '' }}>Editor</option>
                            <option value="reporter" {{ old('role', $user->role) === 'reporter' ? 'selected' : '' }}>Reporter</option>
                            <option value="contributor" {{ old('role', $user->role) === 'contributor' ? 'selected' : '' }}>Contributor</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch" style="margin-top: 35px;">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active Account</label>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <hr>
                        <h6>Change Password (Optional)</h6>
                        <p class="text-muted small">Leave blank to keep current password</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Enter new password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" 
                               placeholder="Confirm new password">
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="alert alert-info">
                            <strong>User Statistics:</strong>
                            <ul class="mb-0">
                                <li>Total Posts: {{ $user->posts()->count() }}</li>
                                <li>Published Posts: {{ $user->posts()->where('status', 'published')->count() }}</li>
                                <li>Member Since: {{ $user->created_at->format('M d, Y') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
