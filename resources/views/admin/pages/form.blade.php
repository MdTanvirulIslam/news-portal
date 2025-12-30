@extends('admin.layouts.layout')

@section('styles')
    <!-- TinyMCE or CKEditor -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        .form-section {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f1f2f3;
        }
        .language-tab {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <div class="page-title">
            <h3>{{ isset($page) ? 'Edit Page' : 'Create New Page' }}</h3>
        </div>
        <div class="page-toolbar">
            <a href="{{ route('admin.admin.pages.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Back to Pages
            </a>
        </div>
    </div>

    <!-- Validation Errors -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <strong>Error!</strong> Please fix the following issues:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Form -->
    <form action="{{ isset($page) ? route('admin.pages.update', $page) : route('admin.pages.store') }}"
          method="POST">
        @csrf
        @if(isset($page))
            @method('PUT')
        @endif

        <div class="row">
            <!-- Main Content -->
            <div class="col-xl-9">

                <!-- English Content -->
                <div class="form-section">
                    <div class="section-title">
                        🇬🇧 English Content
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title (EN) <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title_en"
                               class="form-control @error('title_en') is-invalid @enderror"
                               value="{{ old('title_en', $page->title_en ?? '') }}"
                               placeholder="Enter page title in English"
                               required>
                        @error('title_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (EN)</label>
                        <input type="text"
                               name="slug_en"
                               class="form-control @error('slug_en') is-invalid @enderror"
                               value="{{ old('slug_en', $page->slug_en ?? '') }}"
                               placeholder="auto-generated-from-title">
                        <small class="text-muted">Leave empty to auto-generate from title</small>
                        @error('slug_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content (EN) <span class="text-danger">*</span></label>
                        <textarea name="content_en"
                                  id="content_en"
                                  class="form-control tinymce-editor @error('content_en') is-invalid @enderror"
                                  rows="10"
                                  required>{{ old('content_en', $page->content_en ?? '') }}</textarea>
                        @error('content_en')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Bangla Content -->
                <div class="form-section">
                    <div class="section-title">
                        🇧🇩 Bangla Content
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Title (BN) <span class="text-danger">*</span></label>
                        <input type="text"
                               name="title_bn"
                               class="form-control @error('title_bn') is-invalid @enderror"
                               value="{{ old('title_bn', $page->title_bn ?? '') }}"
                               placeholder="বাংলায় শিরোনাম লিখুন"
                               required>
                        @error('title_bn')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Slug (BN)</label>
                        <input type="text"
                               name="slug_bn"
                               class="form-control @error('slug_bn') is-invalid @enderror"
                               value="{{ old('slug_bn', $page->slug_bn ?? '') }}"
                               placeholder="auto-generated-from-title">
                        <small class="text-muted">Leave empty to auto-generate from title</small>
                        @error('slug_bn')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Content (BN) <span class="text-danger">*</span></label>
                        <textarea name="content_bn"
                                  id="content_bn"
                                  class="form-control tinymce-editor @error('content_bn') is-invalid @enderror"
                                  rows="10"
                                  required>{{ old('content_bn', $page->content_bn ?? '') }}</textarea>
                        @error('content_bn')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="form-section">
                    <div class="section-title">
                        🔍 SEO Settings
                    </div>

                    <!-- English SEO -->
                    <div class="language-tab mb-3">
                        <h6 class="mb-3">English SEO</h6>

                        <div class="mb-3">
                            <label class="form-label">Meta Title (EN)</label>
                            <input type="text"
                                   name="meta_title_en"
                                   class="form-control"
                                   value="{{ old('meta_title_en', $page->meta_title_en ?? '') }}"
                                   placeholder="SEO title for search engines">
                            <small class="text-muted">Recommended: 50-60 characters</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description (EN)</label>
                            <textarea name="meta_description_en"
                                      class="form-control"
                                      rows="3"
                                      placeholder="SEO description for search engines">{{ old('meta_description_en', $page->meta_description_en ?? '') }}</textarea>
                            <small class="text-muted">Recommended: 150-160 characters</small>
                        </div>
                    </div>

                    <!-- Bangla SEO -->
                    <div class="language-tab">
                        <h6 class="mb-3">Bangla SEO</h6>

                        <div class="mb-3">
                            <label class="form-label">Meta Title (BN)</label>
                            <input type="text"
                                   name="meta_title_bn"
                                   class="form-control"
                                   value="{{ old('meta_title_bn', $page->meta_title_bn ?? '') }}"
                                   placeholder="সার্চ ইঞ্জিনের জন্য SEO শিরোনাম">
                            <small class="text-muted">প্রস্তাবিত: ৫০-৬০ অক্ষর</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description (BN)</label>
                            <textarea name="meta_description_bn"
                                      class="form-control"
                                      rows="3"
                                      placeholder="সার্চ ইঞ্জিনের জন্য SEO বিবরণ">{{ old('meta_description_bn', $page->meta_description_bn ?? '') }}</textarea>
                            <small class="text-muted">প্রস্তাবিত: ১৫০-১৬০ অক্ষর</small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Sidebar -->
            <div class="col-xl-3">

                <!-- Publish Section -->
                <div class="form-section">
                    <div class="section-title">Publish</div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="is_active"
                                   id="is_active"
                                {{ old('is_active', $page->is_active ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                Active
                            </label>
                        </div>
                        <small class="text-muted">Show this page on the website</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number"
                               name="order"
                               class="form-control"
                               value="{{ old('order', $page->order ?? 0) }}"
                               min="0"
                               placeholder="0">
                        <small class="text-muted">Lower number appears first</small>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            {{ isset($page) ? 'Update Page' : 'Create Page' }}
                        </button>

                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </div>

                <!-- Info Section -->
                @if(isset($page))
                    <div class="form-section">
                        <div class="section-title">Page Info</div>

                        <div class="mb-2">
                            <small class="text-muted">Created:</small>
                            <p class="mb-1">{{ $page->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted">Last Updated:</small>
                            <p class="mb-0">{{ $page->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script>
        // Initialize TinyMCE
        tinymce.init({
            selector: '.tinymce-editor',
            height: 400,
            menubar: true,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic forecolor | alignleft aligncenter ' +
                'alignright alignjustify | bullist numlist outdent indent | ' +
                'removeformat | help',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
        });

        // Auto-generate slug from title
        document.querySelector('input[name="title_en"]').addEventListener('blur', function() {
            const slugInput = document.querySelector('input[name="slug_en"]');
            if (!slugInput.value) {
                slugInput.value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
            }
        });

        document.querySelector('input[name="title_bn"]').addEventListener('blur', function() {
            const slugInput = document.querySelector('input[name="slug_bn"]');
            if (!slugInput.value) {
                slugInput.value = this.value.toLowerCase()
                    .replace(/[^\w\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/--+/g, '-')
                    .trim();
            }
        });
    </script>
@endsection
