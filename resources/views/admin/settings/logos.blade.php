@extends('admin.layouts.layout')

@section('styles')
<style>
    .settings-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 15px;
        padding: 25px;
        color: white;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
    }
    
    .logo-upload-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        border: 2px solid #f0f0f0;
        transition: all 0.3s ease;
    }
    
    .logo-upload-card:hover {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
    }
    
    .logo-preview {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        margin-top: 10px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .logo-preview img {
        max-height: 100px;
        max-width: 100%;
    }
    
    .gradient-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .gradient-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        color: white;
    }
</style>
@endsection

@section('content')
<div class="settings-header">
    <h4 class="mb-1"><i class="fas fa-image"></i> Logo Settings</h4>
    <p class="mb-0" style="opacity: 0.9; font-size: 14px;">Manage your website logos and images</p>
</div>

<form action="{{ route('admin.settings.logos.update') }}" method="POST" enctype="multipart/form-data" id="logoForm">
    @csrf

    <div class="row">
        <!-- Main Logo -->
        <div class="col-md-6">
            <div class="logo-upload-card">
                <h5><i class="fas fa-star text-warning"></i> Main Logo</h5>
                <p class="text-muted small">Primary website logo (header)</p>
                
                <input type="file" name="main_logo" id="main_logo" class="form-control" accept="image/*">
                
                <div class="logo-preview">
                    @if($logoSettings->main_logo)
                        <img src="{{ $logoSettings->getLogoUrl('main_logo') }}" alt="{{ $logoSettings->main_logo_alt }}" id="main_logo_preview" style="display: block;">
                    @else
                        <span class="text-muted" id="main_logo_placeholder">No logo uploaded</span>
                    @endif
                </div>
                
                <input type="text" name="main_logo_alt" class="form-control mt-2" placeholder="Alt text" 
                    value="{{ old('main_logo_alt', $logoSettings->main_logo_alt) }}">
            </div>
        </div>

        <!-- Footer Logo -->
        <div class="col-md-6">
            <div class="logo-upload-card">
                <h5><i class="fas fa-shoe-prints text-info"></i> Footer Logo</h5>
                <p class="text-muted small">Logo for website footer</p>
                
                <input type="file" name="footer_logo" id="footer_logo" class="form-control" accept="image/*">
                
                <div class="logo-preview">
                    @if($logoSettings->footer_logo)
                        <img src="{{ $logoSettings->getLogoUrl('footer_logo') }}" alt="{{ $logoSettings->footer_logo_alt }}" id="footer_logo_preview" style="display: block;">
                    @else
                        <span class="text-muted" id="footer_logo_placeholder">No logo uploaded</span>
                    @endif
                </div>
                
                <input type="text" name="footer_logo_alt" class="form-control mt-2" placeholder="Alt text" 
                    value="{{ old('footer_logo_alt', $logoSettings->footer_logo_alt) }}">
            </div>
        </div>

        <!-- Favicon -->
        <div class="col-md-4">
            <div class="logo-upload-card">
                <h5><i class="fas fa-bookmark text-primary"></i> Favicon</h5>
                <p class="text-muted small">Browser tab icon (16x16 or 32x32)</p>
                
                <input type="file" name="favicon" id="favicon" class="form-control" accept="image/*">
                
                <div class="logo-preview">
                    @if($logoSettings->favicon)
                        <img src="{{ $logoSettings->getLogoUrl('favicon') }}" alt="Favicon" id="favicon_preview" style="display: block;">
                    @else
                        <span class="text-muted" id="favicon_placeholder">No favicon</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Lazy Banner -->
        <div class="col-md-4">
            <div class="logo-upload-card">
                <h5><i class="fas fa-image text-success"></i> Lazy Banner</h5>
                <p class="text-muted small">Placeholder for lazy loading</p>
                
                <input type="file" name="lazy_banner" id="lazy_banner" class="form-control" accept="image/*">
                
                <div class="logo-preview">
                    @if($logoSettings->lazy_banner)
                        <img src="{{ $logoSettings->getLogoUrl('lazy_banner') }}" alt="Lazy Banner" id="lazy_banner_preview" style="display: block;">
                    @else
                        <span class="text-muted" id="lazy_banner_placeholder">No banner</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- OG Image -->
        <div class="col-md-4">
            <div class="logo-upload-card">
                <h5><i class="fas fa-share-alt text-danger"></i> OG Image</h5>
                <p class="text-muted small">Social media share image</p>
                
                <input type="file" name="og_image" id="og_image" class="form-control" accept="image/*">
                
                <div class="logo-preview">
                    @if($logoSettings->og_image)
                        <img src="{{ $logoSettings->getLogoUrl('og_image') }}" alt="OG Image" id="og_image_preview" style="display: block;">
                    @else
                        <span class="text-muted" id="og_image_placeholder">No OG image</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <button type="submit" class="gradient-btn" id="submitBtn">
            <i class="fas fa-save"></i> Save Logo Settings
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Image preview function
function previewImage(input, previewId, placeholderId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
            
            if (placeholderId) {
                const placeholder = document.getElementById(placeholderId);
                if (placeholder) {
                    placeholder.style.display = 'none';
                }
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Attach preview handlers
document.getElementById('main_logo').addEventListener('change', function() {
    previewImage(this, 'main_logo_preview', 'main_logo_placeholder');
});

document.getElementById('footer_logo').addEventListener('change', function() {
    previewImage(this, 'footer_logo_preview', 'footer_logo_placeholder');
});

document.getElementById('favicon').addEventListener('change', function() {
    previewImage(this, 'favicon_preview', 'favicon_placeholder');
});

document.getElementById('lazy_banner').addEventListener('change', function() {
    previewImage(this, 'lazy_banner_preview', 'lazy_banner_placeholder');
});

document.getElementById('og_image').addEventListener('change', function() {
    previewImage(this, 'og_image_preview', 'og_image_placeholder');
});

// Form submission with loading state
document.getElementById('logoForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
    submitBtn.disabled = true;
});

// Success toast
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
        background: '#d4edda',
        color: '#155724'
    });
@endif

// Error toast
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
        background: '#f8d7da',
        color: '#721c24'
    });
@endif

// Validation errors
@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Validation Error',
        html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
        confirmButtonText: 'OK',
        confirmButtonColor: '#dc3545'
    });
@endif
</script>
@endsection
