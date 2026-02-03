@php
    $profile = $profileData['profile'] ?? null;
@endphp

@extends('admin.layouts.layout')

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">

            <div class="page-meta mb-4">
                <h2><i class="fas fa-user-circle me-2"></i>{{ $profile ? 'Edit' : 'Complete Your' }} {{ ucfirst($user->role) }} Profile</h2>
                <p class="text-muted">{{ $profile ? 'Update your profile information' : 'Fill in all required information to activate your account' }}</p>
            </div>

        @if($user->role === 'artist')
            <!-- Artist Form (existing code) -->
                <form id="artistProfileForm" enctype="multipart/form-data">
                    <!-- ... existing artist form ... -->
                </form>

        @elseif($user->role === 'lyricist')
            <!-- Lyricist Form (existing code) -->
                <form id="lyricistProfileForm" enctype="multipart/form-data">
                    <!-- ... existing lyricist form ... -->
                </form>

            @elseif($user->role === 'composer')
                <form id="composerProfileForm" enctype="multipart/form-data">
                @csrf

                <!-- Professional Details -->
                    <div class="widget-content widget-content-area mb-4">
                        <h5 class="mb-3"><i class="fas fa-music me-2"></i>পেশাদার বিবরণ (Professional Details)</h5>

                        <!-- Composer Types -->
                        <div class="mb-4">
                            <label class="form-label d-block">Composer Type <span class="text-danger">*</span></label>
                            <div class="invalid-feedback mb-2"></div>
                            <div class="row">
                                @php
                                    $selectedComposerTypes = old('composer_types', $profileData['selectedComposerTypes'] ?? []);
                                @endphp
                                @foreach($profileData['composerTypes'] as $key => $type)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="composer_types[]"
                                                   value="{{ $key }}" id="composer_type_{{ $key }}"
                                                {{ in_array($key, $selectedComposerTypes) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="composer_type_{{ $key }}">
                                                {{ $type }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>Select all that apply (at least one required)
                            </small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Experience (Years) <span class="text-danger">*</span></label>
                                <input type="number" name="experience_years" class="form-control"
                                       min="0" max="100" required
                                       value="{{ old('experience_years', $profile->experience_years ?? '') }}">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Studio Availability <span class="text-danger">*</span></label>
                                <select name="studio_availability" class="form-select" required>
                                    <option value="">Select Availability</option>
                                    @foreach($profileData['studioAvailabilityOptions'] as $key => $option)
                                        <option value="{{ $key }}"
                                            {{ (old('studio_availability', $profile->studio_availability ? 'yes' : 'no') == $key) ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Specialization -->
                    <div class="widget-content widget-content-area mb-4">
                        <h5 class="mb-3"><i class="fas fa-star me-2"></i>Specialization</h5>

                        <!-- Genres -->
                        <div class="mb-4">
                            <label class="form-label d-block">Genres Specialised <span class="text-danger">*</span></label>
                            <div class="invalid-feedback mb-2"></div>
                            <div class="row">
                                @php
                                    $selectedGenres = old('genres', $profileData['selectedGenres'] ?? []);
                                @endphp
                                @foreach($profileData['genres'] as $key => $genre)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="genres[]"
                                                   value="{{ $key }}" id="genre_{{ $key }}"
                                                {{ in_array($key, $selectedGenres) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="genre_{{ $key }}">
                                                {{ $genre }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Instruments Knowledge -->
                        <div class="mb-4">
                            <label class="form-label d-block">Instruments Knowledge</label>
                            <div class="invalid-feedback mb-2"></div>
                            <div class="row">
                                @php
                                    $selectedInstruments = old('instruments_knowledge', $profileData['selectedInstruments'] ?? []);
                                @endphp
                                @foreach($profileData['instruments'] as $key => $instrument)
                                    <div class="col-md-4 col-sm-6 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="instruments_knowledge[]"
                                                   value="{{ $key }}" id="instrument_{{ $key }}"
                                                {{ in_array($key, $selectedInstruments) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="instrument_{{ $key }}">
                                                {{ $instrument }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>Select all instruments you are proficient with
                            </small>
                        </div>
                    </div>

                    <!-- Sample Works -->
                    <div class="widget-content widget-content-area mb-4">
                        <h5 class="mb-3"><i class="fas fa-headphones me-2"></i>Sample Works</h5>

                        <div class="mb-3">
                            <label class="form-label">Sample Works Links (YouTube/Spotify etc.)</label>
                            <div id="sample-works-container">
                                @php
                                    $sampleWorks = old('sample_works', $profile->sample_works ?? []);
                                    $sampleWorks = array_filter($sampleWorks);
                                @endphp
                                @if(count($sampleWorks) > 0)
                                    @foreach($sampleWorks as $index => $link)
                                        <div class="input-group mb-2 sample-work-item">
                                            <input type="url" name="sample_works[]" class="form-control"
                                                   value="{{ $link }}"
                                                   placeholder="https://youtube.com, https://spotify.com, etc.">
                                            <button type="button" class="btn btn-outline-danger remove-sample-work">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2 sample-work-item">
                                        <input type="url" name="sample_works[]" class="form-control"
                                               placeholder="https://youtube.com, https://spotify.com, etc.">
                                        <button type="button" class="btn btn-outline-danger remove-sample-work">
                                            <i class="fas fa-minus"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="add-sample-work">
                                <i class="fas fa-plus me-1"></i>Add Sample Work
                            </button>
                            <small class="text-muted d-block mt-1">
                                <i class="fas fa-info-circle me-1"></i>Add links to your published/composed work
                            </small>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>

                    <!-- Business Information -->
                    <div class="widget-content widget-content-area mb-4">
                        <h5 class="mb-3"><i class="fas fa-briefcase me-2"></i>ব্যবসায়িক তথ্য (Business Information)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Work Charges (Min) - Optional</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="work_charges_min" class="form-control" step="0.01"
                                           value="{{ old('work_charges_min', $profile->work_charges_min ?? '') }}">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Work Charges (Max) - Optional</label>
                                <div class="input-group">
                                    <span class="input-group-text">৳</span>
                                    <input type="number" name="work_charges_max" class="form-control" step="0.01"
                                           value="{{ old('work_charges_max', $profile->work_charges_max ?? '') }}">
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label">Location for Recording</label>
                                <textarea name="recording_location" class="form-control" rows="3"
                                          placeholder="Mention your recording studio location or preferred locations">{{ old('recording_location', $profile->recording_location ?? '') }}</textarea>
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Booking Email</label>
                                <input type="email" name="booking_email" class="form-control"
                                       value="{{ old('booking_email', $profile->booking_email ?? '') }}"
                                       placeholder="booking@email.com">
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Booking Phone</label>
                                <input type="text" name="booking_phone" class="form-control"
                                       value="{{ old('booking_phone', $profile->booking_phone ?? '') }}"
                                       placeholder="+8801XXXXXXXXX">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Verification Documents -->
                    <div class="widget-content widget-content-area mb-4">
                        <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>যাচাইকরণ (Verification)</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Government ID</label>
                                <input type="file" name="govt_id" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 5MB)</small>
                                @if($profile && $profile->govt_id)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <i class="fas fa-file-alt text-primary me-2"></i>
                                        <a href="{{ Storage::url($profile->govt_id) }}" target="_blank" class="text-primary">
                                            View Current Document
                                        </a>
                                        <button type="button" class="btn btn-sm btn-link text-danger delete-file" data-field="govt_id">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </button>
                                    </div>
                                @endif
                                <div class="invalid-feedback"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Previous Work Credits/Documents</label>
                                <input type="file" name="previous_work_docs" class="form-control" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted">PDF, JPG, PNG (Max 5MB)</small>
                                @if($profile && $profile->previous_work_docs)
                                    <div class="mt-2 p-2 bg-light rounded">
                                        <i class="fas fa-file-contract text-primary me-2"></i>
                                        <a href="{{ Storage::url($profile->previous_work_docs) }}" target="_blank" class="text-primary">
                                            View Work Credits
                                        </a>
                                        <button type="button" class="btn btn-sm btn-link text-danger delete-file" data-field="previous_work_docs">
                                            <i class="fas fa-trash me-1"></i>Remove
                                        </button>
                                    </div>
                                @endif
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg px-5" id="submitBtn">
                            <i class="fas fa-save me-2"></i>{{ $profile ? 'Update' : 'Save' }} Profile
                        </button>
                    </div>
                </form>
            @endif

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Determine which form to use based on user role
            const userRole = '{{ $user->role }}';

            // Common functions for all forms
            function setupCommonFormEvents() {
                // Add/Remove Portfolio Links (for artist/lyricist)
                $('#add-portfolio-link').on('click', function() {
                    $('#portfolio-links-container').append(`
                        <div class="input-group mb-2 portfolio-link-item">
                            <input type="url" name="portfolio_links[]" class="form-control" placeholder="https://example.com">
                            <button type="button" class="btn btn-outline-danger remove-portfolio-link">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    `);
                });

                $(document).on('click', '.remove-portfolio-link', function() {
                    $(this).closest('.portfolio-link-item').remove();
                });

                // Add/Remove Sample Works (for composer)
                $('#add-sample-work').on('click', function() {
                    $('#sample-works-container').append(`
                        <div class="input-group mb-2 sample-work-item">
                            <input type="url" name="sample_works[]" class="form-control" placeholder="https://youtube.com, https://spotify.com, etc.">
                            <button type="button" class="btn btn-outline-danger remove-sample-work">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    `);
                });

                $(document).on('click', '.remove-sample-work', function() {
                    $(this).closest('.sample-work-item').remove();
                });

                // Delete file handler
                $(document).on('click', '.delete-file', function() {
                    const button = $(this);
                    const field = button.data('field');
                    console.log('Deleting field:', field); // Debug log

                    Swal.fire({
                        title: 'Are you sure?',
                        text: "This file will be permanently deleted!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route("admin.profile.file.delete", ":field") }}'.replace(':field', field),
                                type: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(response) {
                                    console.log('Delete response:', response); // Debug log
                                    if(response.success) {
                                        button.closest('.p-2').fadeOut(300, function() {
                                            $(this).remove();
                                        });
                                        Swal.fire('Deleted!', 'File has been deleted.', 'success');
                                    } else {
                                        Swal.fire('Error!', response.message || 'Failed to delete file.', 'error');
                                    }
                                },
                                error: function(xhr) {
                                    console.log('Delete error:', xhr.responseJSON); // Debug log
                                    Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to delete file.', 'error');
                                }
                            });
                        }
                    });
                });
            }

            // Common form submission function
            function submitForm(form, url) {
                const formData = new FormData(form[0]);
                const submitBtn = form.find('#submitBtn');

                submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Saving...');

                // Clear previous validation errors
                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.invalid-feedback').text('');

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                if(response.redirect) {
                                    window.location.href = response.redirect;
                                }
                            });
                        }
                    },
                    error: function(xhr) {
                        submitBtn.prop('disabled', false).html('<i class="fas fa-save me-2"></i>Save Profile');

                        if(xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;

                            $.each(errors, function(key, value) {
                                // Handle array field names
                                const fieldName = key.replace(/\[\]/g, '');
                                const input = form.find('[name="' + fieldName + '[]"], [name="' + key + '"]').first();
                                input.addClass('is-invalid');
                                input.closest('.mb-3').find('.invalid-feedback').text(value[0]);
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please fix the errors and try again.'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Something went wrong!'
                            });
                        }
                    }
                });
            }

            // Setup form based on role
            setupCommonFormEvents();

            if (userRole === 'artist') {
                $('#artistProfileForm').on('submit', function(e) {
                    e.preventDefault();
                    submitForm($(this), '{{ route("admin.profile.artist.update") }}');
                });
            } else if (userRole === 'lyricist') {
                $('#lyricistProfileForm').on('submit', function(e) {
                    e.preventDefault();
                    submitForm($(this), '{{ route("admin.profile.lyricist.update") }}');
                });
            } else if (userRole === 'composer') {
                $('#composerProfileForm').on('submit', function(e) {
                    e.preventDefault();
                    submitForm($(this), '{{ route("admin.profile.composer.update") }}');
                });
            }
        });
    </script>
@endsection
