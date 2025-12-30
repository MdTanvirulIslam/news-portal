@extends('admin.layouts.layout')

@section('title', 'Language Logos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="card-body text-white">
                    <h2 class="mb-0"><i class="fas fa-language me-2"></i>Language Logos</h2>
                    <p class="mb-0 opacity-75">Upload logos for English and Bangla</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card" style="border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.08);">
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.language-logos.update') }}" method="POST" enctype="multipart/form-data" id="languageLogoForm">
                        @csrf

                        {{-- English Logo --}}
                        <div class="mb-5">
                            <h5 class="mb-3 fw-bold" style="color: #667eea;">
                                <i class="fas fa-flag-usa me-2"></i>English Logo
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload English Logo</label>
                                    <input type="file"
                                           class="form-control @error('english_logo') is-invalid @enderror"
                                           name="english_logo"
                                           accept="image/*"
                                           onchange="previewImage(this, 'english_preview')">
                                    @error('english_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Alt Text (Optional)</label>
                                    <input type="text"
                                           class="form-control"
                                           name="english_logo_alt"
                                           value="{{ old('english_logo_alt', $languageLogos->english_logo_alt) }}"
                                           placeholder="English Logo">
                                </div>
                            </div>

                            {{-- Preview --}}
                            <div class="mt-3">
                                @if($languageLogos->english_logo)
                                    <img src="{{ $languageLogos->getEnglishLogoUrl() }}"
                                         alt="English Logo"
                                         id="english_preview"
                                         style="max-width: 300px; border-radius: 10px; border: 3px solid #667eea; padding: 10px; display: block;">
                                @else
                                    <img id="english_preview"
                                         style="display: none; max-width: 300px; border-radius: 10px; border: 3px solid #667eea; padding: 10px;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>No English logo uploaded yet
                                    </div>
                                @endif
                            </div>
                        </div>

                        <hr class="my-5">

                        {{-- Bangla Logo --}}
                        <div class="mb-5">
                            <h5 class="mb-3 fw-bold" style="color: #667eea;">
                                <i class="fas fa-globe-asia me-2"></i>Bangla (বাংলা) Logo
                            </h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Upload Bangla Logo</label>
                                    <input type="file"
                                           class="form-control @error('bangla_logo') is-invalid @enderror"
                                           name="bangla_logo"
                                           accept="image/*"
                                           onchange="previewImage(this, 'bangla_preview')">
                                    @error('bangla_logo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Alt Text (Optional)</label>
                                    <input type="text"
                                           class="form-control"
                                           name="bangla_logo_alt"
                                           value="{{ old('bangla_logo_alt', $languageLogos->bangla_logo_alt) }}"
                                           placeholder="বাংলা লোগো">
                                </div>
                            </div>

                            {{-- Preview --}}
                            <div class="mt-3">
                                @if($languageLogos->bangla_logo)
                                    <img src="{{ $languageLogos->getBanglaLogoUrl() }}"
                                         alt="Bangla Logo"
                                         id="bangla_preview"
                                         style="max-width: 300px; border-radius: 10px; border: 3px solid #667eea; padding: 10px; display: block;">
                                @else
                                    <img id="bangla_preview"
                                         style="display: none; max-width: 300px; border-radius: 10px; border: 3px solid #667eea; padding: 10px;">
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>No Bangla logo uploaded yet
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-lg px-5" id="submitBtn"
                                    style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);">
                                <i class="fas fa-save me-2"></i>Save Language Logos
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

            // Hide "no logo" alert if exists
            const alert = img.nextElementSibling;
            if (alert && alert.classList.contains('alert')) {
                alert.style.display = 'none';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Form submit with loading state
document.getElementById('languageLogoForm').addEventListener('submit', function() {
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
