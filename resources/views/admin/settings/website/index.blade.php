@extends('admin.layouts.layout')

@section('title', 'Website Settings')
@section('styles')
    <style>
        .nav-tabs .nav-link {
            color: #667eea;
            font-weight: 500;
            border: none;
            border-bottom: 3px solid transparent;
            padding: 12px 24px;
        }

        .nav-tabs .nav-link:hover {
            border-color: #667eea;
            color: #764ba2;
        }

        .nav-tabs .nav-link.active {
            color: #764ba2;
            background: transparent;
            border-bottom: 3px solid #764ba2;
        }
    </style>
@endsection
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
                        <!-- Navigation Tabs -->
                        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab">
                                    <i class="fas fa-cog me-2"></i>General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="social-tab" data-bs-toggle="tab" data-bs-target="#social" type="button" role="tab">
                                    <i class="fas fa-share-alt me-2"></i>Social Media
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                                    <i class="fas fa-search me-2"></i>SEO
                                </button>
                            </li>
                        </ul>

                        <form action="{{ route('admin.settings.website.update') }}" method="POST" enctype="multipart/form-data" id="websiteForm">
                            @csrf

                            <div class="tab-content" id="settingsTabContent">
                                <!-- GENERAL TAB -->
                                <div class="tab-pane fade show active" id="general" role="tabpanel">
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
                                            <i class="fas fa-code text-primary me-2"></i>Analytics & Tracking
                                        </h5>
                                        <div class="row g-3">
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
                                </div>

                                <!-- SOCIAL MEDIA TAB -->
                                <div class="tab-pane fade" id="social" role="tabpanel">
                                    <div class="mb-4">
                                        <h5 class="mb-3 fw-bold">
                                            <i class="fas fa-share-alt text-primary me-2"></i>Social Media URLs
                                        </h5>
                                        <p class="text-muted">Add your social media profile URLs. These will appear in your website header/footer.</p>
                                    </div>

                                    <div class="row g-4">
                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-facebook text-primary me-2"></i>Facebook URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="facebook_url"
                                                   value="{{ old('facebook_url', $websiteSettings->facebook_url) }}"
                                                   placeholder="https://facebook.com/yourpage">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-twitter text-info me-2"></i>Twitter URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="twitter_url"
                                                   value="{{ old('twitter_url', $websiteSettings->twitter_url) }}"
                                                   placeholder="https://twitter.com/yourhandle">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-linkedin text-primary me-2"></i>LinkedIn URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="linkedin_url"
                                                   value="{{ old('linkedin_url', $websiteSettings->linkedin_url) }}"
                                                   placeholder="https://linkedin.com/company/yourcompany">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-youtube text-danger me-2"></i>YouTube URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="youtube_url"
                                                   value="{{ old('youtube_url', $websiteSettings->youtube_url) }}"
                                                   placeholder="https://youtube.com/@yourchannel">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-whatsapp text-success me-2"></i>WhatsApp URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="whatsapp_url"
                                                   value="{{ old('whatsapp_url', $websiteSettings->whatsapp_url) }}"
                                                   placeholder="https://wa.me/1234567890">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fab fa-instagram text-danger me-2"></i>Instagram URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="instagram_url"
                                                   value="{{ old('instagram_url', $websiteSettings->instagram_url) }}"
                                                   placeholder="https://instagram.com/yourprofile">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-rss text-warning me-2"></i>RSS Feed URL
                                            </label>
                                            <input type="url"
                                                   class="form-control"
                                                   name="rss_url"
                                                   value="{{ old('rss_url', $websiteSettings->rss_url) }}"
                                                   placeholder="https://yoursite.com/feed">
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO TAB -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <div class="mb-4">
                                        <h5 class="mb-3 fw-bold">
                                            <i class="fas fa-search text-primary me-2"></i>SEO Settings
                                        </h5>
                                        <p class="text-muted">Configure meta tags and search engine optimization settings for your website.</p>
                                    </div>

                                    {{-- Meta Title --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-heading text-primary me-2"></i>Meta Title
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               name="meta_title"
                                               value="{{ old('meta_title', $websiteSettings->meta_title) }}"
                                               placeholder="Your Website - Best News Portal in Bangladesh"
                                               maxlength="60">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                    </div>

                                    {{-- Meta Description --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-align-left text-primary me-2"></i>Meta Description
                                        </label>
                                        <textarea class="form-control"
                                                  name="meta_description"
                                                  rows="3"
                                                  placeholder="Brief description of your website for search engines..."
                                                  maxlength="160">{{ old('meta_description', $websiteSettings->meta_description) }}</textarea>
                                        <small class="text-muted">Recommended: 150-160 characters</small>
                                    </div>

                                    {{-- Meta Keywords --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-tags text-primary me-2"></i>Meta Keywords
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               name="meta_keywords"
                                               value="{{ old('meta_keywords', $websiteSettings->meta_keywords) }}"
                                               placeholder="news, bangladesh, latest news, breaking news">
                                        <small class="text-muted">Separate keywords with commas</small>
                                    </div>

                                    {{-- Google Site Verification --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fab fa-google text-danger me-2"></i>Google Site Verification
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               name="google_verification"
                                               value="{{ old('google_verification', $websiteSettings->google_verification) }}"
                                               placeholder="google-site-verification=xxxxxxxxxxxxx">
                                        <small class="text-muted">From Google Search Console</small>
                                    </div>

                                    {{-- OG Image --}}
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">
                                            <i class="fas fa-image text-primary me-2"></i>Default OG Image (Social Sharing)
                                        </label>
                                        <input type="file"
                                               class="form-control"
                                               name="og_image"
                                               accept="image/*"
                                               onchange="previewImage(this, 'og_image_preview')">
                                        <small class="text-muted d-block mb-2">Recommended size: 1200x630px (Facebook/Twitter share image)</small>

                                        @if($websiteSettings->og_image)
                                            <div class="mt-2">
                                                <img src="{{ asset($websiteSettings->og_image) }}"
                                                     alt="OG Image"
                                                     id="og_image_preview"
                                                     style="max-width: 300px; border-radius: 10px; display: block;">
                                            </div>
                                        @else
                                            <img id="og_image_preview" style="display: none; max-width: 300px; border-radius: 10px; margin-top: 10px;">
                                        @endif
                                    </div>


                                </div>
                            </div>

                            {{-- Submit Button --}}
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-lg px-5" id="submitBtn"
                                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);">
                                    <i class="fas fa-save me-2"></i>Save All Settings
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
