@extends("admin.layouts.layout")

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">

            <!-- Header -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tags.index') }}">Tags</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

            <!-- Error Messages -->
            @if($errors->any())
                <div class="alert alert-light-danger alert-dismissible fade show border-0 mb-4" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x close"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                    <strong>Validation Error!</strong>
                    <ul class="mb-0 mt-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row mb-4 layout-spacing layout-top-spacing">
                <div class="col-xxl-9 col-xl-12 col-lg-12 col-md-12 col-sm-12">

                    <form action="{{ route('admin.tags.store') }}" method="POST">
                        @csrf

                        <div class="widget-content widget-content-area ecommerce-create-section">

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>Create New Tag</h5>
                                    <p class="text-muted">Fill at least one language (English or Bangla)</p>
                                </div>
                            </div>

                            <!-- Language Tabs -->
                            <div class="row">
                                <div class="col-md-12">
                                    <ul class="nav nav-tabs mb-4" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#english-tab" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                </svg>
                                                English
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#bangla-tab" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-globe me-2">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12"></line>
                                                    <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                </svg>
                                                বাংলা
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#seo-tab" type="button">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search me-2">
                                                    <circle cx="11" cy="11" r="8"></circle>
                                                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                                                </svg>
                                                SEO
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="tab-content">

                                <!-- English Tab -->
                                <div class="tab-pane fade show active" id="english-tab">
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>Name</label>
                                            <input type="text" class="form-control @error('name_en') is-invalid @enderror" name="name_en" value="{{ old('name_en') }}" placeholder="Enter tag name">
                                            @error('name_en')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">Optional if Bangla name provided</small>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>Slug</label>
                                            <input type="text" class="form-control" name="slug" value="{{ old('slug') }}" placeholder="auto-generated">
                                            <small class="form-text text-muted">Leave empty to auto-generate from name</small>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>Description</label>
                                            <textarea class="form-control" name="description_en" rows="4" placeholder="Short description">{{ old('description_en') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bangla Tab -->
                                <div class="tab-pane fade" id="bangla-tab">
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>নাম (Name)</label>
                                            <input type="text" class="form-control @error('name_bn') is-invalid @enderror" name="name_bn" value="{{ old('name_bn') }}" placeholder="ট্যাগের নাম লিখুন">
                                            @error('name_bn')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="form-text text-muted">ইংরেজি নাম দিলে এটি ঐচ্ছিক</small>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>বিবরণ (Description)</label>
                                            <textarea class="form-control" name="description_bn" rows="4" placeholder="সংক্ষিপ্ত বিবরণ">{{ old('description_bn') }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- SEO Tab -->
                                <div class="tab-pane fade" id="seo-tab">
                                    <h6 class="mb-3">English SEO</h6>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <label>Meta Title</label>
                                            <input type="text" class="form-control" name="meta_title_en" value="{{ old('meta_title_en') }}" placeholder="Meta title">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <label>Meta Description</label>
                                            <textarea class="form-control" name="meta_description_en" rows="3" placeholder="Meta description">{{ old('meta_description_en') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>Meta Keywords</label>
                                            <input type="text" class="form-control" name="meta_keywords_en" value="{{ old('meta_keywords_en') }}" placeholder="keyword1, keyword2, keyword3">
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <h6 class="mb-3">Bangla SEO</h6>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <label>মেটা শিরোনাম</label>
                                            <input type="text" class="form-control" name="meta_title_bn" value="{{ old('meta_title_bn') }}" placeholder="মেটা শিরোনাম">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-12">
                                            <label>মেটা বিবরণ</label>
                                            <textarea class="form-control" name="meta_description_bn" rows="3" placeholder="মেটা বিবরণ">{{ old('meta_description_bn') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-sm-12">
                                            <label>মেটা কীওয়ার্ড</label>
                                            <input type="text" class="form-control" name="meta_keywords_bn" value="{{ old('meta_keywords_bn') }}" placeholder="কীওয়ার্ড১, কীওয়ার্ড২">
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <!-- Status & Actions -->
                        <div class="widget-content widget-content-area ecommerce-create-section mt-4">
                            <div class="row mb-4">
                                <div class="col-sm-12">
                                    <label>Status</label>
                                    <select class="form-select" name="is_active">
                                        <option value="1" selected>Published</option>
                                        <option value="0">Draft</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="submit" class="btn btn-success w-100 _effect--ripple waves-effect waves-light">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-check-circle me-2">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                        </svg>
                                        Create Tag
                                    </button>
                                </div>
                                <div class="col-sm-6">
                                    <a href="{{ route('admin.tags.index') }}" class="btn btn-outline-secondary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-circle me-2">
                                            <circle cx="12" cy="12" r="10"></circle>
                                            <line x1="15" y1="9" x2="9" y2="15"></line>
                                            <line x1="9" y1="9" x2="15" y2="15"></line>
                                        </svg>
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
@endsection
