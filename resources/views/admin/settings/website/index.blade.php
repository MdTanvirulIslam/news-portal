@extends('admin.layouts.layout')

@section('title', 'Website Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="card-body text-white">
                    <h2 class="mb-0"><i class="fas fa-cog me-2"></i>Website Settings</h2>
                    <p class="mb-0 opacity-75">Configure your website appearance and functionality</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data" id="websiteForm">
                        @csrf

                        {{-- Website Title --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-heading text-primary me-2"></i>Website Title
                            </label>
                            <input type="text"
                                   class="form-control form-control-lg"
                                   name="website_title"
                                   value="{{ old('website_title', $websiteSettings->website_title) }}"
                                   placeholder="My Awesome Website">
                        </div>

                        {{-- Loader Settings --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-spinner text-primary me-2"></i>Show Loader
                                </label>
                                <select class="form-select form-select-lg" name="show_loader">
                                    <option value="1" {{ $websiteSettings->show_loader ? 'selected' : '' }}>Yes</option>
                                    <option value="0" {{ !$websiteSettings->show_loader ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-circle-notch text-primary me-2"></i>Loader Type
                                </label>
                                <select class="form-select form-select-lg" name="loader_type">
                                    @foreach($loaderTypes as $type)
                                        <option value="{{ $type }}" {{ $websiteSettings->loader_type === $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Loader Image --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-image text-primary me-2"></i>Custom Loader Image
                            </label>
                            <input type="file"
                                   class="form-control"
                                   name="loader_image"
                                   accept="image/*"
                                   onchange="previewImage(this, 'loader_image_preview')">
                            @if($websiteSettings->loader_image)
                                <div class="mt-2">
                                    <img src="{{ $websiteSettings->getLoaderImageUrl() }}"
                                         alt="Loader Image"
                                         id="loader_image_preview"
                                         style="max-width: 200px; border-radius: 10px; display: block;">
                                </div>
                            @else
                                <img id="loader_image_preview" style="display: none; max-width: 200px; border-radius: 10px; margin-top: 10px;">
                            @endif
                        </div>

                        {{-- Color Settings --}}
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-palette text-primary me-2"></i>Color Scheme
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Base Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="base_color"
                                           value="{{ old('base_color', $websiteSettings->base_color) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Footer Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="footer_color"
                                           value="{{ old('footer_color', $websiteSettings->footer_color) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Copyright Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="copyright_color"
                                           value="{{ old('copyright_color', $websiteSettings->copyright_color) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Header Text Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="header_text_color"
                                           value="{{ old('header_text_color', $websiteSettings->header_text_color) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Link Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="link_color"
                                           value="{{ old('link_color', $websiteSettings->link_color) }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Link Hover Color</label>
                                    <input type="color"
                                           class="form-control form-control-color"
                                           name="link_hover_color"
                                           value="{{ old('link_hover_color', $websiteSettings->link_hover_color) }}">
                                </div>
                            </div>
                        </div>

                        {{-- Typography --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-font text-primary me-2"></i>Heading Font
                                </label>
                                <select class="form-select form-select-lg" name="heading_font">
                                    @foreach($fonts as $font)
                                        <option value="{{ $font }}" {{ $websiteSettings->heading_font === $font ? 'selected' : '' }}>
                                            {{ $font }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-align-left text-primary me-2"></i>Body Font
                                </label>
                                <select class="form-select form-select-lg" name="body_font">
                                    @foreach($fonts as $font)
                                        <option value="{{ $font }}" {{ $websiteSettings->body_font === $font ? 'selected' : '' }}>
                                            {{ $font }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Timezone & Posts Per Page --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-globe text-primary me-2"></i>Timezone
                                </label>
                                <select class="form-select form-select-lg" name="timezone">
                                    @foreach($timezones as $tz)
                                        <option value="{{ $tz }}" {{ $websiteSettings->timezone === $tz ? 'selected' : '' }}>
                                            {{ $tz }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-list text-primary me-2"></i>Posts Per Page
                                </label>
                                <input type="number"
                                       class="form-control form-control-lg"
                                       name="posts_per_page"
                                       value="{{ old('posts_per_page', $websiteSettings->posts_per_page) }}"
                                       min="1"
                                       max="100">
                            </div>
                        </div>

                        {{-- Verification Codes --}}
                        <div class="mb-4">
                            <h5 class="mb-3 fw-bold">
                                <i class="fas fa-code text-primary me-2"></i>Verification & Analytics
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Google Search Console</label>
                                    <input type="text"
                                           class="form-control"
                                           name="google_search_console"
                                           value="{{ old('google_search_console', $websiteSettings->google_search_console) }}"
                                           placeholder="google-site-verification=...">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Google AdSense</label>
                                    <input type="text"
                                           class="form-control"
                                           name="google_adsense"
                                           value="{{ old('google_adsense', $websiteSettings->google_adsense) }}"
                                           placeholder="ca-pub-...">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Google Analytics</label>
                                    <input type="text"
                                           class="form-control"
                                           name="google_analytics"
                                           value="{{ old('google_analytics', $websiteSettings->google_analytics) }}"
                                           placeholder="G-...">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Facebook Pixel</label>
                                    <input type="text"
                                           class="form-control"
                                           name="facebook_pixel"
                                           value="{{ old('facebook_pixel', $websiteSettings->facebook_pixel) }}"
                                           placeholder="123456789...">
                                </div>
                            </div>
                        </div>

                        {{-- Maintenance Mode --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold">
                                <i class="fas fa-wrench text-primary me-2"></i>Maintenance Mode
                            </label>
                            <select class="form-select form-select-lg" name="maintenance_mode">
                                <option value="0" {{ !$websiteSettings->maintenance_mode ? 'selected' : '' }}>Disabled</option>
                                <option value="1" {{ $websiteSettings->maintenance_mode ? 'selected' : '' }}>Enabled</option>
                            </select>
                            <small class="text-muted">When enabled, only admins can access the site</small>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-end">
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
// Image preview function
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById(previewId);
            img.src = e.target.result;
            img.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Form submit with loading state
document.getElementById('websiteForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    btn.disabled = true;
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
