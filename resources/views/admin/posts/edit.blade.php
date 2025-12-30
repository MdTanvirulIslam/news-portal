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
            {{-- Approve Banner --}}
            @if($post->status === 'pending' && in_array(Auth::user()->role, ['admin', 'editor']))
                <div class="alert alert-warning alert-dismissible fade show d-flex justify-content-between align-items-center" role="alert">
                    <div>
                        <i class="fas fa-clock me-2"></i>
                        <strong>Pending Approval:</strong> This post is waiting for approval.
                    </div>
                    <button type="button" class="btn btn-success btn-sm" id="approve-btn" data-post-id="{{ $post->id }}">
                        <i class="fas fa-check me-1"></i> Approve & Publish Now
                    </button>
                </div>
            @endif

            <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data" id="post-form">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h3 class="card-title mb-0">Edit Post: {{ $post->title_en ?? $post->title_bn }}</h3>
                            <div>
                                @php
                                    $statusColors = [
                                        'published' => 'success',
                                        'pending' => 'warning',
                                        'draft' => 'secondary',
                                        'rejected' => 'danger',
                                        'scheduled' => 'info'
                                    ];
                                    $color = $statusColors[$post->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }} me-2">{{ ucfirst($post->status) }}</span>
                                @if($post->status === 'pending')
                                    <i class="fas fa-clock text-warning" title="Awaiting approval"></i>
                                @endif
                            </div>
                        </div>
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
                                               value="{{ old('title_en', $post->title_en) }}" placeholder="Enter post title">
                                        @error('title_en')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3" id="excerpt-section-en">
                                        <label class="form-label">Excerpt (English)</label>
                                        <textarea name="excerpt_en" rows="3" class="form-control @error('excerpt_en') is-invalid @enderror"
                                                  placeholder="Short description">{{ old('excerpt_en', $post->excerpt_en) }}</textarea>
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
                                               value="{{ old('title_bn', $post->title_bn) }}" placeholder="শিরোনাম লিখুন">
                                        @error('title_bn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3" id="excerpt-section-bn">
                                        <label class="form-label">সংক্ষিপ্ত বিবরণ (বাংলা)</label>
                                        <textarea name="excerpt_bn" rows="3" class="form-control @error('excerpt_bn') is-invalid @enderror"
                                                  placeholder="সংক্ষিপ্ত বিবরণ">{{ old('excerpt_bn', $post->excerpt_bn) }}</textarea>
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
                                               value="{{ old('meta_title_en', $post->meta_title_en) }}" placeholder="SEO title">
                                        <small class="text-muted">Recommended: 50-60 characters</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা শিরোনাম (বাংলা)</label>
                                        <input type="text" name="meta_title_bn" class="form-control"
                                               value="{{ old('meta_title_bn', $post->meta_title_bn) }}" placeholder="এসইও শিরোনাম">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Description (English)</label>
                                        <textarea name="meta_description_en" rows="3" class="form-control"
                                                  placeholder="SEO description">{{ old('meta_description_en', $post->meta_description_en) }}</textarea>
                                        <small class="text-muted">Recommended: 120-160 characters</small>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা বিবরণ (বাংলা)</label>
                                        <textarea name="meta_description_bn" rows="3" class="form-control"
                                                  placeholder="এসইও বিবরণ">{{ old('meta_description_bn', $post->meta_description_bn) }}</textarea>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Meta Keywords (English)</label>
                                        <input type="text" name="meta_keywords_en" class="form-control"
                                               value="{{ old('meta_keywords_en', $post->meta_keywords_en) }}" placeholder="keyword1, keyword2">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">মেটা কীওয়ার্ড (বাংলা)</label>
                                        <input type="text" name="meta_keywords_bn" class="form-control"
                                               value="{{ old('meta_keywords_bn', $post->meta_keywords_bn) }}" placeholder="কীওয়ার্ড১, কীওয়ার্ড২">
                                    </div>
                                </div>
                            </div>

                            <!-- Settings Tab -->
                            <div class="tab-pane fade" id="tab-settings" role="tabpanel">
                                <div class="row">
                                    <!-- Post Type (Display Only) -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Post Type</label>
                                        <input type="text" class="form-control" value="{{ ucfirst($post->post_type) }}" readonly>
                                        <input type="hidden" name="post_type" value="{{ $post->post_type }}">
                                        <small class="text-muted">Post type cannot be changed after creation</small>
                                    </div>

                                    <!-- Categories -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Categories <span class="text-danger">*</span></label>
                                        <select name="categories[]" class="form-select select2-multiple" multiple required>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ (collect(old('categories', $post->categories->pluck('id')))->contains($category->id)) ? 'selected' : '' }}>
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
                                                <option value="{{ $tag->id }}"
                                                    {{ (collect(old('tags', $post->tags->pluck('id')))->contains($tag->id)) ? 'selected' : '' }}>
                                                    {{ $tag->name_en }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Current Featured Image -->
                                    @if($post->featured_image)
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Current Featured Image</label>
                                            <div>
                                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="Featured" class="img-thumbnail" style="max-width: 300px;">
                                            </div>
                                        </div>
                                    @endif

                                <!-- Featured Image -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Featured Image {{ $post->featured_image ? '(Upload new to replace)' : '(Required)' }}</label>
                                        <input type="file" name="featured_image" class="form-control" accept="image/*" {{ !$post->featured_image ? 'required' : '' }}>
                                        <small class="text-muted">Max size: 5MB. Recommended: 1200x630px</small>
                                    </div>

                                    <!-- Video URL -->
                                    <div class="col-md-12 mb-3" id="video-section">
                                        <label class="form-label">
                                            <i class="fas fa-video text-danger me-1"></i>
                                            Video URL <span class="text-danger d-none" id="video-required">*</span>
                                        </label>
                                        <input type="url" name="video_url" id="video_url" class="form-control"
                                               value="{{ old('video_url', $post->video_url) }}"
                                               placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            <span id="video-help">YouTube or Vimeo URL</span>
                                        </small>
                                    </div>

                                    <!-- Current Audio -->
                                    @if($post->audio_file)
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Current Audio</label>
                                            <div class="border rounded p-2 bg-light">
                                                <audio controls class="w-100">
                                                    <source src="{{ asset('storage/' . $post->audio_file) }}" type="audio/mpeg">
                                                    Your browser does not support the audio element.
                                                </audio>
                                            </div>
                                        </div>
                                    @endif

                                <!-- Audio File - Only for ARTICLE -->
                                    <div class="col-md-12 mb-3 d-none" id="audio-section">
                                        <label class="form-label">
                                            <i class="fas fa-microphone text-primary me-1"></i>
                                            Audio File {{ $post->audio_file ? '(Upload new to replace)' : '(Optional)' }}
                                        </label>
                                        <input type="file" name="audio_file" class="form-control" accept="audio/*">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i>
                                            Max size: 10MB. Supported formats: MP3, WAV
                                        </small>
                                    </div>

                                    <!-- Current Gallery Images -->
                                    @if($post->post_type === 'gallery' && $post->media->count() > 0)
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Current Gallery Images</label>
                                            <div class="row" id="current-gallery">
                                                @foreach($post->media as $media)
                                                    <div class="col-md-3 mb-3" id="media-{{ $media->id }}">
                                                        <div class="card">
                                                            <img src="{{ asset('storage/' . $media->file_path) }}"
                                                                 class="card-img-top" alt="Gallery" style="height: 150px; object-fit: cover;">
                                                            <div class="card-body p-2">
                                                                <small class="text-muted d-block mb-2">
                                                                    {{ $media->caption_en ?: $media->caption_bn ?: 'No caption' }}
                                                                </small>
                                                                <button type="button" class="btn btn-danger btn-sm w-100"
                                                                        onclick="deleteGalleryImage({{ $media->id }})">
                                                                    <i class="fas fa-trash"></i> Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                <!-- Add Gallery Images -->
                                    <div class="col-md-12 mb-3 d-none" id="gallery-section">
                                        <label class="form-label">
                                            <i class="fas fa-images text-success me-1"></i>
                                            {{ $post->post_type === 'gallery' && $post->media->count() > 0 ? 'Add More Gallery Images' : 'Gallery Images' }}
                                        </label>

                                        <div id="gallery-container">
                                            <div class="gallery-item border rounded p-3 mb-3">
                                                <div class="row">
                                                    <div class="col-md-12 mb-2">
                                                        <label class="form-label">Image</label>
                                                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*">
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
                                        <small class="text-muted d-block mt-2">Max size: 5MB per image</small>
                                    </div>

                                    <!-- Status -->
                                    @php
                                        $userRole = Auth::user()->role;
                                        $isReporterOrContributor = in_array($userRole, ['reporter', 'contributor']);
                                    @endphp

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Status <span class="text-danger">*</span></label>
                                        <select name="status" class="form-select" required {{ $isReporterOrContributor ? 'disabled' : '' }}>
                                            @if($isReporterOrContributor)
                                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                            @else
                                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="pending" {{ old('status', $post->status) == 'pending' ? 'selected' : '' }}>Pending Review</option>
                                                <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                                <option value="rejected" {{ old('status', $post->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                                <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                            @endif
                                        </select>
                                        @if($isReporterOrContributor)
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle"></i>
                                                Status is managed by Admin/Editor
                                            </small>
                                            <input type="hidden" name="status" value="pending">
                                        @endif
                                    </div>

                                    <!-- Scheduled Date -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Schedule Date (Optional)</label>
                                        <input type="datetime-local" name="scheduled_at" class="form-control"
                                               value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}">
                                        <small class="text-muted">Leave empty for immediate publish</small>
                                    </div>

                                    <!-- Flags -->
                                    <div class="col-md-12 mb-3">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                                           value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="is_featured">
                                                        <i class="fas fa-star text-warning"></i> Featured Post
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" name="is_breaking" id="is_breaking"
                                                           value="1" {{ old('is_breaking', $post->is_breaking) ? 'checked' : '' }}>
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
                                                   value="1" {{ old('allow_comments', $post->allow_comments) ? 'checked' : '' }}>
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
                            <div class="d-flex gap-2">
                                @if($post->status === 'pending' && in_array(Auth::user()->role, ['admin', 'editor']))
                                    <button type="button" class="btn btn-success" id="approve-btn-footer" data-post-id="{{ $post->id }}">
                                        <i class="fas fa-check me-1"></i> Approve & Publish
                                    </button>
                                @endif

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Update Post
                                </button>
                            </div>
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
            console.log('Initializing post edit page...');

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

            // Load existing content
            @if($post->content_en)
                quillEn.root.innerHTML = {!! json_encode($post->content_en) !!};
            @endif

                @if($post->content_bn)
                quillBn.root.innerHTML = {!! json_encode($post->content_bn) !!};
            @endif

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

            // Show/hide sections based on post type
            var postType = '{{ $post->post_type }}';
            var contentEnSection = document.getElementById('content-section-en');
            var contentBnSection = document.getElementById('content-section-bn');
            var audioSection = document.getElementById('audio-section');
            var gallerySection = document.getElementById('gallery-section');
            var videoSection = document.getElementById('video-section');
            var videoInput = document.getElementById('video_url');
            var videoRequired = document.getElementById('video-required');

            if (postType === 'article') {
                // Article: Show content editors + audio
                audioSection.classList.remove('d-none');
                videoInput.removeAttribute('required');
            } else if (postType === 'video') {
                // Video: Hide content editors, video required
                contentEnSection.classList.add('d-none');
                contentBnSection.classList.add('d-none');
                videoInput.setAttribute('required', 'required');
                videoRequired.classList.remove('d-none');
            } else if (postType === 'gallery') {
                // Gallery: Hide content editors, show gallery
                contentEnSection.classList.add('d-none');
                contentBnSection.classList.add('d-none');
                videoSection.classList.add('d-none');
                gallerySection.classList.remove('d-none');
            }

            // Add gallery image
            document.getElementById('add-gallery-image')?.addEventListener('click', function() {
                var container = document.getElementById('gallery-container');
                var newItem = document.createElement('div');
                newItem.className = 'gallery-item border rounded p-3 mb-3';
                newItem.innerHTML = `
                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <label class="form-label">Image</label>
                            <input type="file" name="gallery_images[]" class="form-control" accept="image/*">
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

            // Remove gallery item
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-gallery-item') ||
                    e.target.closest('.remove-gallery-item')) {
                    e.target.closest('.gallery-item').remove();
                }
            });

            // Approve button handlers
            const approveBtnTop = document.getElementById('approve-btn');
            const approveBtnFooter = document.getElementById('approve-btn-footer');

            function performApprove(postId) {
                if (typeof $ === 'undefined') {
                    alert('jQuery not loaded');
                    return;
                }

                $.ajax({
                    url: '/admin/posts/' + postId + '/approve',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function(response) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonColor: '#28a745'
                            }).then(() => {
                                window.location.href = '{{ route("admin.posts.index") }}';
                            });
                        } else {
                            alert(response.message);
                            window.location.href = '{{ route("admin.posts.index") }}';
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON?.message || 'Error approving post';
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: message,
                                confirmButtonColor: '#dc3545'
                            });
                        } else {
                            alert(message);
                        }
                    }
                });
            }

            if (approveBtnTop) {
                approveBtnTop.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Approve Post?',
                            text: "This post will be published immediately!",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, approve it!',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) performApprove(postId);
                        });
                    } else {
                        if (confirm('Approve and publish this post?')) performApprove(postId);
                    }
                });
            }

            if (approveBtnFooter) {
                approveBtnFooter.addEventListener('click', function() {
                    const postId = this.getAttribute('data-post-id');
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Approve Post?',
                            text: "This post will be published immediately!",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, approve it!',
                            cancelButtonText: 'Cancel',
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) performApprove(postId);
                        });
                    } else {
                        if (confirm('Approve and publish this post?')) performApprove(postId);
                    }
                });
            }

            // Form submission
            var form = document.getElementById('post-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Save Quill content
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
                                customClass: { confirmButton: 'btn btn-primary' },
                                buttonsStyling: false
                            });
                        } else {
                            alert('At least one title (English or Bangla) is required!');
                        }
                        return false;
                    }
                });
            }

            console.log('Post edit page ready!');
        });

        // Delete gallery image function
        function deleteGalleryImage(mediaId) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Delete Image?',
                    text: "This action cannot be undone!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performDeleteImage(mediaId);
                    }
                });
            } else {
                if (confirm('Delete this image?')) {
                    performDeleteImage(mediaId);
                }
            }
        }

        function performDeleteImage(mediaId) {
            // You need to create a route for this
            fetch('/admin/posts/media/' + mediaId, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('media-' + mediaId).remove();
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: 'Image deleted successfully',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting image');
                });
        }
    </script>
@endsection
