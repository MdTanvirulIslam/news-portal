@extends('admin.layouts.layout')

@section('styles')
    <!-- Quill Editor CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <style>
        /* Quill Editor Container */
        .quill-editor-container {
            margin-bottom: 1.5rem;
        }

        /* Quill Editor Wrapper */
        .quill-wrapper {
            border: 1px solid #d3d3d3;
            border-radius: 8px;
            background: #ffffff;
            overflow: hidden;
        }

        /* Quill Toolbar */
        .ql-toolbar.ql-snow {
            border: none;
            border-bottom: 1px solid #e0e6ed;
            background: #fafbfc;
            padding: 12px;
        }

        /* Quill Editor Container */
        .ql-container.ql-snow {
            border: none;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            font-size: 15px;
        }

        /* Quill Editor Area */
        .ql-editor {
            min-height: 350px;
            padding: 20px;
            line-height: 1.6;
        }

        .ql-editor.ql-blank::before {
            color: #888;
            font-style: normal;
        }

        /* Quill Toolbar Buttons */
        .ql-toolbar button {
            width: 32px;
            height: 32px;
        }

        .ql-toolbar button:hover {
            background: #e3e8ee;
            border-radius: 4px;
        }

        /* Error State */
        .quill-wrapper.is-invalid {
            border-color: #e7515a;
        }

        /* Focus State */
        .quill-wrapper:focus-within {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.15);
        }

        /* Current Image Preview */
        .current-image-preview {
            max-width: 200px;
            border-radius: 8px;
            border: 2px solid #e0e6ed;
            padding: 4px;
        }
    </style>
@endsection

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">
            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Edit Page: {{ $page->title_en ?: $page->title_bn }}</h5>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left me-1">
                                    <line x1="19" y1="12" x2="5" y2="12"></line>
                                    <polyline points="12 19 5 12 12 5"></polyline>
                                </svg>
                                Back
                            </a>
                        </div>

                        <form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title (English) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_en" class="form-control @error('title_en') is-invalid @enderror"
                                           value="{{ old('title_en', $page->title_en) }}" required placeholder="About Us">
                                    @error('title_en')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title (বাংলা) <span class="text-danger">*</span></label>
                                    <input type="text" name="title_bn" class="form-control @error('title_bn') is-invalid @enderror"
                                           value="{{ old('title_bn', $page->title_bn) }}" required placeholder="আমাদের সম্পর্কে">
                                    @error('title_bn')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <!-- English Content Editor -->
                                <div class="col-md-12 quill-editor-container">
                                    <label class="form-label">Content (English) <span class="text-danger">*</span></label>
                                    <div class="quill-wrapper @error('content_en') is-invalid @enderror">
                                        <div id="editor-en"></div>
                                    </div>
                                    <textarea name="content_en" id="content_en" style="display:none;" required>{{ old('content_en', $page->content_en) }}</textarea>
                                    @error('content_en')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <!-- Bangla Content Editor -->
                                <div class="col-md-12 quill-editor-container">
                                    <label class="form-label">Content (বাংলা) <span class="text-danger">*</span></label>
                                    <div class="quill-wrapper @error('content_bn') is-invalid @enderror">
                                        <div id="editor-bn"></div>
                                    </div>
                                    <textarea name="content_bn" id="content_bn" style="display:none;" required>{{ old('content_bn', $page->content_bn) }}</textarea>
                                    @error('content_bn')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Featured Image</label>

                                    @if($page->featured_image)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $page->featured_image) }}" alt="Current Image" class="current-image-preview">
                                            <div class="form-check mt-2">
                                                <input type="checkbox" name="remove_image" id="remove_image" class="form-check-input" value="1">
                                                <label for="remove_image" class="form-check-label text-danger">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                                        <polyline points="3 6 5 6 21 6"></polyline>
                                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                        <line x1="10" y1="11" x2="10" y2="17"></line>
                                                        <line x1="14" y1="11" x2="14" y2="17"></line>
                                                    </svg>
                                                    Remove current image
                                                </label>
                                            </div>
                                        </div>
                                    @endif

                                    <input type="file" name="featured_image" class="form-control" accept="image/*">
                                    <small class="text-muted">Optional. Max: 2MB. Leave empty to keep current image.</small>
                                    @error('featured_image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                            {{ old('is_active', $page->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Published</label>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="show_in_menu" id="show_in_menu" value="1"
                                            {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_in_menu">Show in Menu</label>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO Section -->
                            <h6 class="mt-4 mb-3">SEO Settings</h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Title (English)</label>
                                    <input type="text" name="meta_title_en" class="form-control"
                                           value="{{ old('meta_title_en', $page->meta_title_en) }}" placeholder="Optional">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Title (বাংলা)</label>
                                    <input type="text" name="meta_title_bn" class="form-control"
                                           value="{{ old('meta_title_bn', $page->meta_title_bn) }}" placeholder="ঐচ্ছিক">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description (English)</label>
                                    <textarea name="meta_description_en" class="form-control" rows="2" placeholder="Optional">{{ old('meta_description_en', $page->meta_description_en) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description (বাংলা)</label>
                                    <textarea name="meta_description_bn" class="form-control" rows="2" placeholder="ঐচ্ছিক">{{ old('meta_description_bn', $page->meta_description_bn) }}</textarea>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save me-1">
                                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                        <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                        <polyline points="7 3 7 8 15 8"></polyline>
                                    </svg>
                                    Update Page
                                </button>
                                <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Quill Editor JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        // Show validation errors with SweetAlert
        @if ($errors->any())
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Validation Error!',
            html: errorMessages.replace(/\n/g, '<br>'),
            confirmButtonColor: '#e7515a'
        });
        @endif

        // Quill toolbar configuration
        const toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'size': ['small', false, 'large', 'huge'] }],

            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],

            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            [{ 'align': [] }],

            ['blockquote', 'code-block'],
            ['link', 'image'],

            ['clean']
        ];

        // Initialize English Editor
        const quillEn = new Quill('#editor-en', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'Write your content in English here...'
        });

        // Initialize Bangla Editor
        const quillBn = new Quill('#editor-bn', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            },
            placeholder: 'এখানে বাংলায় আপনার কন্টেন্ট লিখুন...'
        });

        // Load existing content from database
        const contentEn = document.getElementById('content_en').value;
        const contentBn = document.getElementById('content_bn').value;

        if (contentEn) {
            quillEn.root.innerHTML = contentEn;
        }
        if (contentBn) {
            quillBn.root.innerHTML = contentBn;
        }

        // Update hidden textareas before form submission
        document.querySelector('form').addEventListener('submit', function(e) {
            // Get HTML content from Quill editors
            const newContentEn = quillEn.root.innerHTML;
            const newContentBn = quillBn.root.innerHTML;

            // Update hidden textareas
            document.getElementById('content_en').value = newContentEn;
            document.getElementById('content_bn').value = newContentBn;

            // Check if content is empty
            const isEnEmpty = newContentEn === '<p><br></p>' || newContentEn.trim() === '';
            const isBnEmpty = newContentBn === '<p><br></p>' || newContentBn.trim() === '';

            if (isEnEmpty || isBnEmpty) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Content Required!',
                    text: 'Please fill in both English and Bangla content.',
                    confirmButtonColor: '#e7515a'
                });
                return false;
            }
        });

        // Auto-save to hidden fields on content change
        quillEn.on('text-change', function() {
            document.getElementById('content_en').value = quillEn.root.innerHTML;
        });

        quillBn.on('text-change', function() {
            document.getElementById('content_bn').value = quillBn.root.innerHTML;
        });
    </script>
@endsection
