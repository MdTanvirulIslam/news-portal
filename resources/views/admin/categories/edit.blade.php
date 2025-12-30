@extends('admin.layouts.layout')

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">

            <!-- Header -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Categories</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit Category</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Edit Category</h5>
                            <span class="badge badge-light-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers me-1"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                                {{ $category->posts()->count() }} Posts
                            </span>
                        </div>

                        <!-- Validation Errors -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Language Tabs -->
                            <ul class="nav nav-tabs mb-3" id="languageTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="english-tab" data-bs-toggle="tab" data-bs-target="#english" type="button" role="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                                        English
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="bangla-tab" data-bs-toggle="tab" data-bs-target="#bangla" type="button" role="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-flag me-2"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path><line x1="4" y1="22" x2="4" y2="15"></line></svg>
                                        বাংলা
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search me-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                        SEO
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings me-2"><circle cx="12" cy="12" r="3"></circle><path d="M12 1v6m0 6v6m5.66-13.66l-4.24 4.24m0 6l-4.24 4.24m13.66-5.66l-6 0m-6 0l-6 0m13.66 5.66l-4.24-4.24m0-6l-4.24-4.24"></path></svg>
                                        Settings
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="languageTabsContent">
                                
                                <!-- English Tab -->
                                <div class="tab-pane fade show active" id="english" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Name (English)</label>
                                            <input type="text" name="name_en" class="form-control @error('name_en') is-invalid @enderror" value="{{ old('name_en', $category->name_en) }}" placeholder="Technology, Sports, Politics">
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">At least one language is required (English OR Bangla)</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Description (English)</label>
                                            <textarea name="description_en" class="form-control" rows="1">{{ old('description_en', $category->description_en) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bangla Tab -->
                                <div class="tab-pane fade" id="bangla" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">নাম (বাংলা)</label>
                                            <input type="text" name="name_bn" class="form-control @error('name_bn') is-invalid @enderror" value="{{ old('name_bn', $category->name_bn) }}" placeholder="প্রযুক্তি, খেলাধুলা, রাজনীতি">
                                            @error('name_bn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">At least one language is required (English OR Bangla)</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">বিবরণ (বাংলা)</label>
                                            <textarea name="description_bn" class="form-control" rows="1">{{ old('description_bn', $category->description_bn) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Tab -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Title (English)</label>
                                            <input type="text" name="meta_title_en" class="form-control" value="{{ old('meta_title_en', $category->meta_title_en) }}">
                                            <small class="text-muted">Recommended: 50-60 characters</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Title (বাংলা)</label>
                                            <input type="text" name="meta_title_bn" class="form-control" value="{{ old('meta_title_bn', $category->meta_title_bn) }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Description (English)</label>
                                            <textarea name="meta_description_en" class="form-control" rows="2">{{ old('meta_description_en', $category->meta_description_en) }}</textarea>
                                            <small class="text-muted">Recommended: 150-160 characters</small>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Description (বাংলা)</label>
                                            <textarea name="meta_description_bn" class="form-control" rows="2">{{ old('meta_description_bn', $category->meta_description_bn) }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Keywords (English)</label>
                                            <input type="text" name="meta_keywords_en" class="form-control" value="{{ old('meta_keywords_en', $category->meta_keywords_en) }}" placeholder="keyword1, keyword2, keyword3">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Meta Keywords (বাংলা)</label>
                                            <input type="text" name="meta_keywords_bn" class="form-control" value="{{ old('meta_keywords_bn', $category->meta_keywords_bn) }}">
                                        </div>
                                    </div>
                                </div>

                                <!-- Settings Tab -->
                                <div class="tab-pane fade" id="settings" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Parent Category</label>
                                            <select name="parent_id" class="form-select">
                                                <option value="">None (Top Level)</option>
                                                @foreach($categories as $cat)
                                                    @if($cat->id != $category->id)
                                                        <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name_en ?? $cat->name_bn }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <small class="text-muted">Select parent for sub-category</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Display Order</label>
                                            <input type="number" name="order" class="form-control" value="{{ old('order', $category->order) }}" min="0">
                                            <small class="text-muted">Lower numbers appear first</small>
                                        </div>

                                        @if($category->image)
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Current Image</label><br>
                                            <img src="{{ Storage::url($category->image) }}" alt="{{ $category->name_en }}" style="max-height: 150px; border-radius: 8px; border: 2px solid #e0e6ed;">
                                        </div>
                                        @endif

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Category Image</label>
                                            <input type="file" name="image" id="category-image" class="form-control" accept="image/*">
                                            <small class="text-muted">Max: 2MB. Leave empty to keep current.</small>
                                            <div id="image-preview" class="mt-2" style="display:none;">
                                                <img src="" alt="Preview" style="max-height: 150px; border-radius: 8px;">
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Icon Class</label>
                                            <input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon) }}" placeholder="fas fa-newspaper">
                                            <small class="text-muted">FontAwesome icon class</small>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_active">
                                                    Active
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <div class="form-check form-check-primary form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="show_in_menu" id="show_in_menu" value="1" {{ old('show_in_menu', $category->show_in_menu) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="show_in_menu">
                                                    Show in Menu
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Action Buttons -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-2">
                                    <button type="submit" class="btn btn-success w-100 _effect--ripple waves-effect waves-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-save me-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                                        Update Category
                                    </button>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle me-2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
<script>
// Image preview
document.getElementById('category-image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            preview.querySelector('img').src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
