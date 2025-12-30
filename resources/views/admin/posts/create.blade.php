@extends('admin.layouts.layout')

@section('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        .editor-container {
            height: 350px;
            background: white;
        }
        .gallery-item {
            background: #f8f9fa;
            transition: all 0.3s;
        }
        .gallery-item:hover {
            background: #e9ecef;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" id="post-form">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mb-0">Create Post</h3>
                    </div>

                    <div class="card-body">
                        <!-- Language Tabs -->
                        <ul class="nav nav-tabs mb-4" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-english" type="button" role="tab">
                                    <i class="fas fa-globe me-1"></i> English
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-bangla" type="button" role="tab">
                                    <i class="fas fa-language me-1"></i> বাংলা
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-seo" type="button" role="tab">
                                    <i class="fas fa-search me-1"></i> SEO
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-settings" type="button" role="tab">
                                    <i class="fas fa-cog me-1"></i> Settings
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- English Tab -->
                            <div class="tab-pane fade show active" id="tab-english" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Title (English)</label>
                                        <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                                               value="{{ old('title_en') }}" placeholder="Enter post title">
                                        @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3" id="excerpt-section-en">
                                        <label class="form-label">Excerpt (English)</label>
                                        <textarea name="excerpt_en" rows="3" class="form-control @error('excerpt_en') is-invalid @enderror"
                                                  placeholder="Short description">{{ old('excerpt_en') }}</textarea>
                                        @error('excerpt_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Content Editor - Only for ARTICLE -->
                                    <div class="col-md-12 mb-3" id="content-section-en">
                                        <label class="form-label">Content (English)</label>
                                        <div id="editor-container-en" class="editor-container"></div>
                                        <input type="hidden" name="content_en" id="content_en">
                                        @error('content_en')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Bangla Tab -->
                            <div class="tab-pane fade" id="tab-bangla" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">শিরোনাম (বাংলা)</label>
                                        <input type="text" name="title_bn" class="form-control @error('title_bn') is-invalid @enderror"
                                               value="{{ old('title_bn') }}" placeholder="শিরোনাম লিখুন">
                                        @error('title_bn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3" id="excerpt-section-bn">
                                        <label class="form-label">সংক্ষিপ্ত বিবরণ (বাংলা)</label>
                                        <textarea name="excerpt_bn" rows="3" class="form-control @error('excerpt_bn') is-invalid @enderror"
                                                  placeholder="সংক্ষিপ্ত বিবরণ">{{ old('excerpt_bn') }}</textarea>
                                        @error('excerpt_bn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Content Editor - Only for ARTICLE -->
                                    <div class="col-md-12 mb-3" id="content-section-bn">
                                        <label class="form-label">বিষয়বস্তু (বাংলা)</label>
                                        <div id="editor-container-bn" class="editor-container"></div>
                                        <input type="hidden" name="content_bn" id="content_bn">
                                        @error('content_bn')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Tab -->
                            <div class="tab-pane fade" id="tab-seo" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Title (English)</label>
                                        <input type="text" name="meta_title_en" class="form-control"
                                               value="{{ old('meta_title_en') }}" placeholder="SEO title">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা শিরোনাম (বাংলা)</label>
                                        <input type="text" name="meta_title_bn" class="form-control"
                                               value="{{ old('meta_title_bn') }}" placeholder="এসইও শিরোনাম">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Description (English)</label>
                                        <textarea name="meta_description_en" rows="3" class="form-control"
                                                  placeholder="SEO description">{{ old('meta_description_en') }}</textarea>
                                        <small class="text-muted">Recommended: 120-160 characters</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা বিবরণ (বাংলা)</label>
                                        <textarea name="meta_description_bn" rows="3" class="form-control"
                                                  placeholder="এসইও বিবরণ">{{ old('meta_description_bn') }}</textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Keywords (English)</label>
                                        <input type="text" name="meta_keywords_en" class="form-control"
                                               value="{{ old('meta_keywords_en') }}" placeholder="keyword1, keyword2">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা কীওয়ার্ড (বাংলা)</label>
                                        <input type="text" name="meta_keywords_bn" class="form-control"
                                               value="{{ old('meta_keywords_bn') }}" placeholder="কীওয়ার্ড১, কীওয়ার্ড২">
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Tab -->
                            <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                                <div class="row">
                                    <!-- Post Type -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Post Type <span class="text-danger">*</span></label>
                                        <select name="post_type" id="post_type" class="form-select" required>
                                            <option value="article" {{ old('post_type', 'article') == 'article' ? 'selected' : '' }}>📰 Article</option>
                                            <option value="video" {{ old('post_type') == 'video' ? 'selected' : '' }}>🎬 Video</option>
                                            <option value="gallery" {{ old('post_type') == 'gallery' ? 'selected' : '' }}>🖼️ Gallery</option>
                                        </select>
                                        <small class="text-muted d-block mt-1" id="post-type-help">
                                            Article: Full content with optional media
                                        </small>
                                    </div>

                                    <!-- Categories -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Categories <span class="text-danger">*</span></label>
                                        <select name="categories[]" class="form-select select2-multiple" multiple required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (collect(old('categories'))->contains($category->id)) ? 'selected' : '' }}>
                                                    {{ $category->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('categories')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tags -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Tags (Optional)</label>
                                        <select name="tags[]" class="form-select select2-multiple" multiple>
                                            @foreach($tags as $tag)
                                                <option value="{{ $tag->id }}" {{ (collect(old('tags'))->contains($tag->id)) ? 'selected' : '' }}>
                                                    {{ $tag->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Featured Image -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Featured Image <span class="text-danger">*</span></label>
                                        <input type="file" name="featured_image" class="form-control" accept="image/*" required>
                                        <small class="text-muted">Max size: 5MB. Recommended: 1200x630px</small>
                                    </div>

                                    <!-- Video URL - Always visible, REQUIRED for video posts -->
                                    <div class="col-md-12 mb-3" id="video-section">
                                        <label class="form-label">
                                            <i class="fas fa-video text-danger me-1"></i>
                                            Video URL <span class="text-danger d-none" id="video-required">*</span>
                                        </label>
                                        <input type="url" name="video_url" id="video_url" class="form-control"
                                               value="{{ old('video_url') }}"
                                               placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            <span id="video-help">Optional: Add YouTube or Vimeo URL to embed video in your article</span>
                                        </small>
                                    </div>

                                    <!-- Audio File - Only for ARTICLE (Optional) -->
                                    <div class="col-md-12 mb-3 d-none" id="audio-section">
                                        <label class="form-label">
                                            <i class="fas fa-microphone text-primary me-1"></i> Audio File (Optional)
                                        </label>
                                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Max size: 10MB. Supported formats: MP3, WAV
                                        </small>
                                    </div>

                                    <!-- Gallery Images - Only for GALLERY (Required) -->
                                    <div class="col-md-12 mb-3 d-none" id="gallery-section">
                                        <label class="form-label">
                                            <i class="fas fa-images text-success me-1"></i> Gallery Images <span class="text-danger">*</span>
                                        </label>

                                        <div id="gallery-container">
                                            <!-- First Image -->
                                            <div class="gallery-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label">Image <span class="text-danger">*</span></label>
                                                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" required>
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">Caption (English)</label>
                                                        <input type="text" name="gallery_captions_en[]" class="form-control" placeholder="Image description">
                                                    </div>
                                                    <div class="col-md-6 mb-2">
                                                        <label class="form-label">ক্যাপশন (বাংলা)</label>
                                                        <input type="text" name="gallery_captions_bn[]" class="form-control" placeholder="ছবির বিবরণ">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="button" id="add-gallery-image" class="btn btn-outline-success btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add Another Image
                                        </button>
                                        <small class="text-muted d-block mt-2">Max size: 5MB per image. Upload at least 3 images for gallery</small>
                                    </div>

                                    <!-- Status -->
                                    @php
                                        $userRole = Auth::user()->role;
                                        $isReporterOrContributor = in_array($userRole, ['reporter', 'contributor']);
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required>
                                            @if($isReporterOrContributor)
                                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft (Save for later)</option>
                                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Review (Submit for approval)</option>
                                            @else
                                                <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            @endif
                                        </select>
                                        @if($isReporterOrContributor)
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Save as <strong>Draft</strong> to continue working, or <strong>Pending Review</strong> to submit for approval.
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Scheduled Date -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Schedule Date (Optional)</label>
                                        <input type="datetime-local" name="scheduled_at" class="form-control" value="{{ old('scheduled_at') }}">
                                        <small class="text-muted">Leave empty for immediate publish</small>
                                    </div>

                                    <!-- Flags -->
                                    <div class="col-md-12 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                                           value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_featured">
                                                        <i class="fas fa-star text-warning"></i> Featured Post
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_breaking" id="is_breaking"
                                                           value="1" {{ old('is_breaking') ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_breaking">
                                                        <i class="fas fa-bolt text-danger"></i> Breaking News
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Allow Comments -->
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="allow_comments" id="allow_comments"
                                                   value="1" {{ old('allow_comments', 1) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="allow_comments">
                                                Allow Comments
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Post
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            console.log('Initializing post creation page...');

            if (typeof Quill === 'undefined') {
                console.error('Quill not loaded!');
                return;
            }

            // Initialize Quill editors
            var quillEn = new Quill('#editor-container-en', {
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                },
                placeholder: 'Write your content here...',
                theme: 'snow'
            });

            var quillBn = new Quill('#editor-container-bn', {
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3, 4, 5, 6, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image', 'video'],
                        ['clean']
                    ]
                },
                placeholder: 'এখানে আপনার বিষয়বস্তু লিখুন...',
                theme: 'snow'
            });

            // Load old values if validation fails
            @if(old('content_en'))
                quillEn.root.innerHTML = {!! json_encode(old('content_en')) !!};
            @endif

                @if(old('content_bn'))
                quillBn.root.innerHTML = {!! json_encode(old('content_bn')) !!};
            @endif

            // Initialize Select2
            if (typeof $ !== 'undefined' && typeof $.fn.select2 !== 'undefined') {
                $('.select2-multiple').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Select options',
                    allowClear: true
                });
            }

            // Post type change handler
            var postTypeSelect = document.getElementById('post_type');
            if (postTypeSelect) {
                postTypeSelect.addEventListener('change', function() {
                    var type = this.value;
                    console.log('Post type changed to:', type);

                    // Get all sections
                    var contentEnSection = document.getElementById('content-section-en');
                    var contentBnSection = document.getElementById('content-section-bn');
                    var excerptEnSection = document.getElementById('excerpt-section-en');
                    var excerptBnSection = document.getElementById('excerpt-section-bn');
                    var audioSection = document.getElementById('audio-section');
                    var gallerySection = document.getElementById('gallery-section');
                    var videoSection = document.getElementById('video-section');

                    var videoInput = document.getElementById('video_url');
                    var videoRequired = document.getElementById('video-required');
                    var videoHelp = document.getElementById('video-help');
                    var postTypeHelp = document.getElementById('post-type-help');

                    // Hide all sections first
                    contentEnSection.classList.add('d-none');
                    contentBnSection.classList.add('d-none');
                    audioSection.classList.add('d-none');
                    gallerySection.classList.add('d-none');

                    // Remove required from gallery images
                    var galleryInputs = document.querySelectorAll('input[name="gallery_images[]"]');
                    galleryInputs.forEach(function(input) {
                        input.removeAttribute('required');
                    });

                    // Show sections based on post type
                    if (type === 'article') {
                        // ARTICLE: Full editor + optional video/audio
                        contentEnSection.classList.remove('d-none');
                        contentBnSection.classList.remove('d-none');
                        audioSection.classList.remove('d-none');
                        videoInput.removeAttribute('required');
                        videoRequired.classList.add('d-none');
                        videoHelp.textContent = 'Optional: Add YouTube or Vimeo URL to embed video in your article';
                        postTypeHelp.textContent = 'Article: Full content with optional video/audio';
                    }
                    else if (type === 'video') {
                        // VIDEO: Only video URL + excerpt (REQUIRED)
                        videoInput.setAttribute('required', 'required');
                        videoRequired.classList.remove('d-none');
                        videoHelp.textContent = 'Required: YouTube or Vimeo URL for this video post';
                        postTypeHelp.textContent = 'Video: Title + Video URL + Short description';
                    }
                    else if (type === 'gallery') {
                        // GALLERY: Only gallery images (REQUIRED)
                        gallerySection.classList.remove('d-none');
                        videoSection.classList.add('d-none');
                        videoInput.removeAttribute('required');
                        // Make gallery images required
                        galleryInputs.forEach(function(input) {
                            input.setAttribute('required', 'required');
                        });
                        postTypeHelp.textContent = 'Gallery: Title + Multiple images with captions';
                    }
                });

                // Trigger on load
                postTypeSelect.dispatchEvent(new Event('change'));
            }

            // Add gallery image button
            var addGalleryBtn = document.getElementById('add-gallery-image');
            if (addGalleryBtn) {
                addGalleryBtn.addEventListener('click', function() {
                    var container = document.getElementById('gallery-container');
                    var itemCount = container.querySelectorAll('.gallery-item').length + 1;

                    var newItem = document.createElement('div');
                    newItem.className = 'gallery-item border rounded p-3 mb-3';
                    newItem.innerHTML = `
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <label class="form-label">Image #${itemCount} <span class="text-danger">*</span></label>
                                <input type="file" name="gallery_images[]" class="form-control" accept="image/*" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">Caption (English)</label>
                                <input type="text" name="gallery_captions_en[]" class="form-control" placeholder="Image description">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">ক্যাপশন (বাংলা)</label>
                                <input type="text" name="gallery_captions_bn[]" class="form-control" placeholder="ছবির বিবরণ">
                            </div>
                            <div class="col-md-12">
                                <button type="button" class="btn btn-danger btn-sm remove-gallery-item">
                                    <i class="fas fa-trash me-1"></i> Remove
                                </button>
                            </div>
                        </div>
                    `;
                    container.appendChild(newItem);
                });
            }

            // Remove gallery item (event delegation)
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-gallery-item') ||
                    e.target.closest('.remove-gallery-item')) {
                    var item = e.target.closest('.gallery-item');
                    if (item) {
                        item.remove();
                    }
                }
            });

            // Form submission
            var form = document.getElementById('post-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submitting...');

                    // Save Quill content to hidden fields
                    document.getElementById('content_en').value = quillEn.root.innerHTML;
                    document.getElementById('content_bn').value = quillBn.root.innerHTML;

                    // Validation
                    var titleEn = document.querySelector('input[name="title_en"]').value;
                    var titleBn = document.querySelector('input[name="title_bn"]').value;

                    if (!titleEn && !titleBn) {
                        e.preventDefault();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'At least one title (English or Bangla) is required!',
                                customClass: {
                                    confirmButton: 'btn btn-primary'
                                },
                                buttonsStyling: false
                            });
                        } else {
                            alert('At least one title (English or Bangla) is required!');
                        }
                        return false;
                    }
                });
            }

            console.log('Post creation page ready!');
        });
    </script>
@endsection
