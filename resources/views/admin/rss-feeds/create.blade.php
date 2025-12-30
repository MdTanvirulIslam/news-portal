@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.rss-feeds.store') }}" method="POST" id="create-rss-form">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-plus"></i> Add New RSS Feed
                        </h3>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Feed Name -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Feed Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="e.g., BBC News" required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category (Optional)</label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Imported posts will be assigned to this category</small>
                            </div>

                            <!-- Feed URL -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">RSS Feed URL <span class="text-danger">*</span></label>
                                <input type="url" name="feed_url" class="form-control @error('feed_url') is-invalid @enderror"
                                       value="{{ old('feed_url') }}" placeholder="https://example.com/rss" required>
                                @error('feed_url')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Enter the complete RSS/Atom feed URL</small>
                            </div>

                            <!-- Import Limit -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Import Limit <span class="text-danger">*</span></label>
                                <input type="number" name="import_limit" class="form-control @error('import_limit') is-invalid @enderror"
                                       value="{{ old('import_limit', 10) }}" min="1" max="100" required>
                                @error('import_limit')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maximum posts to import per fetch (1-100)</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <!-- Placeholder for alignment -->
                            </div>

                            <!-- Status Toggles -->
                            <div class="col-md-12 mb-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                                   value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                <i class="fas fa-toggle-on text-success"></i> Active
                                            </label>
                                            <small class="d-block text-muted">Enable/disable this RSS feed</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="auto_import" id="auto_import"
                                                   value="1" {{ old('auto_import') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="auto_import">
                                                <i class="fas fa-sync-alt text-info"></i> Auto Import
                                            </label>
                                            <small class="d-block text-muted">Automatically import posts from this feed</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Box -->
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <h6><i class="fas fa-info-circle"></i> How it Works:</h6>
                                    <ul class="mb-0">
                                        <li>Posts will be imported as <strong>Draft</strong> for review before publishing</li>
                                        <li>Duplicate posts (same source URL) will be automatically skipped</li>
                                        <li>Featured images will be downloaded and attached if available</li>
                                        <li>Auto-import feeds will fetch new posts every hour (if enabled)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.rss-feeds.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submit-btn">
                                <i class="fas fa-save"></i> Create RSS Feed
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Show success message
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            @endif

            // Show error message
            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            @endif

            // Show validation errors
            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left; margin: 0; padding-left: 20px;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            @endif

            // Form submission with loading state
            $('#create-rss-form').on('submit', function() {
                var submitBtn = $('#submit-btn');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Creating...');
            });
        });
    </script>
@endsection
