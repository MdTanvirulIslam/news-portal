@extends('admin.layouts.layout')

@section('styles')
    <style>
        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 30px;
            color: white;
            margin-bottom: 25px;
            position: relative;
            overflow: hidden;
        }

        .profile-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.1);
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 5px solid white;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #667eea;
            margin-right: 25px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            border: 1px solid #e9ecef;
        }

        .info-card h5 {
            color: #495057;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .info-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px dashed #dee2e6;
        }

        .info-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            color: #343a40;
            font-size: 15px;
        }

        .badge-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 5px;
        }

        .badge-custom {
            background: #e9ecef;
            color: #495057;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 13px;
            border: 1px solid #dee2e6;
        }

        .document-preview {
            background: #f8f9fa;
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            color: #6c757d;
        }

        .document-preview i {
            font-size: 48px;
            margin-bottom: 10px;
            color: #adb5bd;
        }

        .status-badge {
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .action-buttons {
            position: absolute;
            top: 25px;
            right: 25px;
            display: flex;
            gap: 10px;
        }

        .profile-stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 15px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            min-width: 120px;
        }

        .stat-number {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }

        .verification-info {
            background: #d1f7c4;
            border: 1px solid #28a745;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .verification-info i {
            color: #28a745;
        }

        .verification-log-item {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            background: #f8f9fa;
            border-left: 4px solid #28a745;
        }

        .verification-log-item.unverified {
            border-left-color: #dc3545;
            background: #fff5f5;
        }

        .verification-log-time {
            font-size: 12px;
            color: #6c757d;
            margin-top: 3px;
        }

        .document-checklist {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-top: 10px;
        }

        .document-item {
            display: flex;
            align-items: center;
            padding: 8px 12px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px solid #e9ecef;
        }

        .document-item.complete {
            background: #d1f7c4;
            border-color: #28a745;
        }

        .verification-reason {
            background: #e9ecef;
            border-left: 3px solid #6c757d;
            padding: 10px 15px;
            margin-top: 8px;
            font-size: 13px;
            color: #495057;
        }
    </style>
@endsection

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="d-flex align-items-center position-relative">
                    <div class="profile-avatar">
                        @if($user->avatar)
                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" style="width:100%; height:100%; border-radius:50%; object-fit:cover;">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <h3 class="mb-2">{{ $user->name }}</h3>
                        <p class="mb-2">
                            <span class="badge badge-light me-2">{{ ucfirst($user->role) }}</span>
                            <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-warning' }}">
                                {{ $user->is_active ? 'Active' : 'Pending Approval' }}
                            </span>
                            @if($profileData['is_verified'] ?? false)
                                <span class="badge badge-info">
                                    <i class="fas fa-check-circle me-1"></i> Verified Profile
                                </span>
                            @endif
                        </p>
                        <p class="mb-0">
                            <i class="fas fa-envelope me-2"></i>{{ $user->email }}
                            <i class="fas fa-calendar ms-4 me-2"></i>Joined {{ $user->created_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>

                @if($user->role === 'artist' && $profileData['stage_name'])
                    <div class="profile-stats">
                        <div class="stat-item">
                            <div class="stat-number">{{ $profileData['years_of_experience'] ?? '0' }}</div>
                            <div class="stat-label">Years Experience</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ count($profileData['genres'] ?? []) }}</div>
                            <div class="stat-label">Genres</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-number">{{ count($profileData['instruments'] ?? []) }}</div>
                            <div class="stat-label">Instruments</div>
                        </div>
                    </div>
            @endif

            <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-2"></i>Back
                    </a>

                    @if(!$user->is_active && $user->hasVerifiedEmail())
                        <button onclick="approveUser({{ $user->id }})" class="btn btn-success">
                            <i class="fas fa-check me-2"></i>Approve User
                        </button>
                    @endif

                    @if(in_array($user->role, ['artist', 'lyricist', 'composer']))
                        @if($profileData['is_verified'] ?? false)
                            <button onclick="unverifyProfile({{ $user->id }})" class="btn btn-warning">
                                <i class="fas fa-times-circle me-2"></i>Unverify Profile
                            </button>
                        @else
                            <button onclick="verifyProfile({{ $user->id }})" class="btn btn-info"
                                    @if(!$user->is_active || !$user->hasVerifiedEmail()) disabled @endif>
                                <i class="fas fa-check-double me-2"></i>Verify Profile
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            <div class="row">
                <!-- Left Column: Basic Information -->
                <div class="col-lg-8">
                    <!-- Professional Information -->
                    <div class="info-card">
                        <h5><i class="fas fa-briefcase me-2 text-primary"></i>Professional Information</h5>

                        @if($user->role === 'artist')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Stage Name</div>
                                        <div class="info-value">{{ $profileData['stage_name'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Artist Type</div>
                                        <div class="info-value">{{ $profileData['artist_type'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Years of Experience</div>
                                        <div class="info-value">{{ $profileData['years_of_experience'] ?? '0' }} years</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Vocal Type</div>
                                        <div class="info-value">{{ $profileData['vocal_type'] ?? 'Not specified' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Genres</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['genres']))
                                        @foreach($profileData['genres'] as $genre)
                                            <span class="badge-custom">{{ $genre }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No genres specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Instruments</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['instruments']))
                                        @foreach($profileData['instruments'] as $instrument)
                                            <span class="badge-custom">{{ $instrument }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No instruments specified</span>
                                    @endif
                                </div>
                            </div>

                        @elseif($user->role === 'lyricist')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Pen Name</div>
                                        <div class="info-value">{{ $profileData['pen_name'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Songs Written</div>
                                        <div class="info-value">{{ $profileData['songs_written'] ?? '0' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Experience</div>
                                        <div class="info-value">{{ $profileData['years_of_experience'] ?? '0' }} years</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Collaboration</div>
                                        <div class="info-value">{{ ucfirst(str_replace('_', ' ', $profileData['collaboration_availability'] ?? 'Not specified')) }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Writing Types</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['writing_types']))
                                        @foreach($profileData['writing_types'] as $type)
                                            <span class="badge-custom">{{ $type }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No writing types specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Languages</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['languages']))
                                        @foreach($profileData['languages'] as $language)
                                            <span class="badge-custom">{{ $language }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No languages specified</span>
                                    @endif
                                </div>
                            </div>

                        @elseif($user->role === 'composer')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Experience</div>
                                        <div class="info-value">{{ $profileData['experience_years'] ?? '0' }} years</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Studio Available</div>
                                        <div class="info-value">{{ $profileData['studio_availability'] ?? 'No' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Recording Location</div>
                                        <div class="info-value">{{ $profileData['recording_location'] ?? 'Not specified' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Composer Types</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['composer_types']))
                                        @foreach($profileData['composer_types'] as $type)
                                            <span class="badge-custom">{{ $type }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No composer types specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Genres</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['genres']))
                                        @foreach($profileData['genres'] as $genre)
                                            <span class="badge-custom">{{ $genre }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No genres specified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Instruments Knowledge</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['instruments_knowledge']))
                                        @foreach($profileData['instruments_knowledge'] as $instrument)
                                            <span class="badge-custom">{{ $instrument }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No instruments specified</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Contact & Business Information -->
                    <div class="info-card">
                        <h5><i class="fas fa-address-card me-2 text-primary"></i>Contact & Business Information</h5>

                        @if($user->role === 'artist')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Booking Email</div>
                                        <div class="info-value">{{ $profileData['booking_email'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Manager Name</div>
                                        <div class="info-value">{{ $profileData['manager_name'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Manager Phone</div>
                                        <div class="info-value">{{ $profileData['manager_phone'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Live Show Price</div>
                                        <div class="info-value">
                                            {{ $profileData['live_show_price_min'] }} - {{ $profileData['live_show_price_max'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Studio Recording Fee</div>
                                <div class="info-value">{{ $profileData['studio_recording_fee'] }}</div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Location Availability</div>
                                <div class="badge-list">
                                    @if(!empty($profileData['location_availability']))
                                        @foreach($profileData['location_availability'] as $location)
                                            <span class="badge-custom">{{ ucfirst($location) }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No locations specified</span>
                                    @endif
                                </div>
                            </div>

                        @elseif($user->role === 'lyricist')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Work Email</div>
                                        <div class="info-value">{{ $profileData['work_email'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Phone</div>
                                        <div class="info-value">{{ $profileData['phone'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Price Range</div>
                                <div class="info-value">{{ $profileData['price_range_min'] }} - {{ $profileData['price_range_max'] }}</div>
                            </div>

                        @elseif($user->role === 'composer')
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Booking Email</div>
                                        <div class="info-value">{{ $profileData['booking_email'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-item">
                                        <div class="info-label">Booking Phone</div>
                                        <div class="info-value">{{ $profileData['booking_phone'] ?? 'Not set' }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-label">Work Charges</div>
                                <div class="info-value">{{ $profileData['work_charges_min'] }} - {{ $profileData['work_charges_max'] }}</div>
                            </div>
                        @endif
                    </div>

                    <!-- Portfolio & Works -->
                    @if(!empty($profileData['portfolio_links']) || !empty($profileData['sample_works']))
                        <div class="info-card">
                            <h5><i class="fas fa-link me-2 text-primary"></i>Portfolio & Sample Works</h5>

                            <div class="info-item">
                                <div class="info-label">Links</div>
                                <div class="badge-list">
                                    @php
                                        $links = $profileData['portfolio_links'] ?? $profileData['sample_works'] ?? [];
                                    @endphp
                                    @if(!empty($links))
                                        @foreach($links as $link)
                                            <a href="{{ $link }}" target="_blank" class="badge-custom text-decoration-none">
                                                <i class="fas fa-external-link-alt me-1"></i> Visit
                                            </a>
                                        @endforeach
                                    @else
                                        <span class="text-muted">No links available</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column: Verification & Documents -->
                <div class="col-lg-4">
                    <!-- Verification Status -->
                    <div class="info-card">
                        <h5>
                            <i class="fas fa-shield-alt me-2 text-primary"></i>Verification Status
                            <button onclick="loadVerificationLogs({{ $user->id }})" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-history me-1"></i> History
                            </button>
                        </h5>

                    @if($profileData['is_verified'] ?? false)
                        <!-- Verified State -->
                            <div class="verification-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-check me-3 fa-2x"></i>
                                    <div>
                                        <h6 class="mb-1 text-success">Profile Verified</h6>
                                        <p class="mb-1">
                                            <strong>Verified on:</strong>
                                            {{ \Carbon\Carbon::parse($profileData['verified_at'])->format('M d, Y h:i A') }}
                                        </p>
                                        @if($profileData['verified_by'])
                                            <p class="mb-0">
                                                <strong>Verified by:</strong>
                                                {{ $profileData['verified_by'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Unverify Button -->
                            <div class="mt-3">
                                <button onclick="unverifyProfile({{ $user->id }})" class="btn btn-sm btn-outline-danger w-100">
                                    <i class="fas fa-times-circle me-1"></i> Remove Verification
                                </button>
                            </div>
                    @else
                        <!-- Not Verified State -->
                            <div class="info-item">
                                <div class="info-label">Verification Status</div>
                                <div class="info-value">
                                    <span class="status-badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i> Not Verified
                                    </span>
                                </div>
                            </div>

                            <!-- Document Checklist -->
                            <div class="mt-3">
                                <h6 class="mb-2">Documents Checklist:</h6>
                                <div class="document-checklist">
                                    @php
                                        $hasRequiredDocs = false;
                                        $requiredDocsCount = 0;
                                        $uploadedDocsCount = 0;
                                        $documents = [];

                                        if($user->role === 'artist') {
                                            $documents = [
                                                ['field' => 'govt_id', 'label' => 'Government ID', 'required' => true],
                                                ['field' => 'artist_contract', 'label' => 'Artist Contract', 'required' => true],
                                                ['field' => 'copyright_declaration', 'label' => 'Copyright Declaration', 'required' => true],
                                            ];
                                        } elseif($user->role === 'lyricist') {
                                            $documents = [
                                                ['field' => 'govt_id', 'label' => 'Government ID', 'required' => true],
                                                ['field' => 'copyright_declaration', 'label' => 'Copyright Declaration', 'required' => true],
                                            ];
                                        } elseif($user->role === 'composer') {
                                            $documents = [
                                                ['field' => 'govt_id', 'label' => 'Government ID', 'required' => true],
                                                ['field' => 'previous_work_docs', 'label' => 'Work Credits', 'required' => false],
                                            ];
                                        }
                                    @endphp

                                    @foreach($documents as $doc)
                                        @php
                                            $hasDoc = $profile->{$doc['field']} ? true : false;
                                            if($doc['required']) {
                                                $requiredDocsCount++;
                                                if($hasDoc) $uploadedDocsCount++;
                                            }
                                        @endphp
                                        <div class="document-item {{ $hasDoc ? 'complete' : '' }}">
                                            @if($hasDoc)
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger me-2"></i>
                                            @endif
                                            <span class="{{ $hasDoc ? 'text-success' : 'text-muted' }}">
                                                {{ $doc['label'] }}
                                                @if(!$hasDoc && $doc['required'])
                                                    <span class="text-danger">*</span>
                                                @endif
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                            @php
                                $hasRequiredDocs = ($requiredDocsCount === $uploadedDocsCount);
                            @endphp

                            <!-- Progress -->
                                <div class="mt-3">
                                    <div class="d-flex justify-content-between mb-1">
                                        <small>Documents Progress</small>
                                        <small>{{ $uploadedDocsCount }}/{{ $requiredDocsCount }}</small>
                                    </div>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar {{ $hasRequiredDocs ? 'bg-success' : 'bg-warning' }}"
                                             role="progressbar"
                                             style="width: {{ $requiredDocsCount > 0 ? ($uploadedDocsCount/$requiredDocsCount)*100 : 0 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Verification Requirements -->
                                <div class="alert alert-info mt-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Verification Requirements:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>All required documents (*) must be uploaded</li>
                                        <li>Email must be verified</li>
                                        <li>Account must be approved</li>
                                        <li>Profile information must be complete</li>
                                    </ul>
                                </div>

                                <!-- Verification Button -->
                                @if($user->is_active && $user->hasVerifiedEmail())
                                    @if($hasRequiredDocs)
                                        <div class="text-center">
                                            <button onclick="verifyProfile({{ $user->id }})" class="btn btn-info w-100">
                                                <i class="fas fa-check-double me-2"></i> Verify This Profile
                                            </button>
                                            <small class="text-muted d-block mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                Click to verify after checking all documents
                                            </small>
                                        </div>
                                    @else
                                        <div class="alert alert-warning mt-2">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            Cannot verify profile yet. Required documents are missing.
                                        </div>
                                    @endif
                                @else
                                    <div class="alert alert-warning mt-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Account must be approved and email verified before profile verification.
                                    </div>
                                @endif
                            </div>
                    @endif

                    <!-- Email & Account Status -->
                        <div class="info-item">
                            <div class="info-label">Email Verification</div>
                            <div class="info-value">
                                @if($user->hasVerifiedEmail())
                                    <span class="status-badge bg-success text-white">
                                        <i class="fas fa-check-circle"></i> Verified
                                    </span>
                                    <small class="d-block text-muted mt-1">
                                        Verified on: {{ $user->email_verified_at->format('M d, Y') }}
                                    </small>
                                @else
                                    <span class="status-badge bg-danger text-white">
                                        <i class="fas fa-times-circle"></i> Not Verified
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Account Status</div>
                            <div class="info-value">
                                @if($user->is_active)
                                    <span class="status-badge bg-success text-white">
                                        <i class="fas fa-check-circle"></i> Active
                                    </span>
                                @else
                                    <span class="status-badge bg-warning text-dark">
                                        <i class="fas fa-clock"></i> Pending Approval
                                    </span>
                                    @if(!$user->hasVerifiedEmail())
                                        <small class="d-block text-danger mt-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i> Email must be verified before approval
                                        </small>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Verification History -->
                    @if($verificationLogs->count() > 0)
                        <div class="info-card">
                            <h5><i class="fas fa-history me-2 text-primary"></i>Verification History</h5>
                            <div style="max-height: 300px; overflow-y: auto;">
                                @foreach($verificationLogs as $log)
                                    <div class="verification-log-item {{ $log->action === 'unverified' ? 'unverified' : '' }}">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                @if($log->action === 'verified')
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <strong class="text-success">Verified</strong>
                                                @else
                                                    <i class="fas fa-times-circle text-danger me-2"></i>
                                                    <strong class="text-danger">Unverified</strong>
                                                @endif
                                            </div>
                                            <small class="verification-log-time">{{ $log->created_at->diffForHumans() }}</small>
                                        </div>
                                        <div class="mt-1">
                                            <small>By: {{ $log->admin->name ?? 'Unknown' }}</small>
                                        </div>
                                        @if($log->reason)
                                            <div class="verification-reason">
                                                <small>{{ $log->reason }}</small>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-2">
                                <button onclick="loadVerificationLogs({{ $user->id }})" class="btn btn-sm btn-link">
                                    View All History
                                </button>
                            </div>
                        </div>
                @endif

                <!-- Documents -->
                    <div class="info-card">
                        <h5><i class="fas fa-file-alt me-2 text-primary"></i>Documents</h5>

                        @if($user->role === 'artist')
                            @if($profile->govt_id || $profile->artist_contract || $profile->copyright_declaration)
                                <div class="info-item">
                                    <div class="info-label">Government ID</div>
                                    @if($profile->govt_id)
                                        <a href="{{ Storage::url($profile->govt_id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Artist Contract</div>
                                    @if($profile->artist_contract)
                                        <a href="{{ Storage::url($profile->artist_contract) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Copyright Declaration</div>
                                    @if($profile->copyright_declaration)
                                        <a href="{{ Storage::url($profile->copyright_declaration) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            @else
                                <div class="document-preview">
                                    <i class="fas fa-file-upload"></i>
                                    <p class="mb-0">No documents uploaded</p>
                                </div>
                            @endif

                        @elseif($user->role === 'lyricist')
                            @if($profile->govt_id || $profile->copyright_declaration)
                                <div class="info-item">
                                    <div class="info-label">Government ID</div>
                                    @if($profile->govt_id)
                                        <a href="{{ Storage::url($profile->govt_id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Copyright Declaration</div>
                                    @if($profile->copyright_declaration)
                                        <a href="{{ Storage::url($profile->copyright_declaration) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            @else
                                <div class="document-preview">
                                    <i class="fas fa-file-upload"></i>
                                    <p class="mb-0">No documents uploaded</p>
                                </div>
                            @endif

                        @elseif($user->role === 'composer')
                            @if($profile->govt_id || $profile->previous_work_docs)
                                <div class="info-item">
                                    <div class="info-label">Government ID</div>
                                    @if($profile->govt_id)
                                        <a href="{{ Storage::url($profile->govt_id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>

                                <div class="info-item">
                                    <div class="info-label">Previous Work Credits</div>
                                    @if($profile->previous_work_docs)
                                        <a href="{{ Storage::url($profile->previous_work_docs) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">Not uploaded</span>
                                    @endif
                                </div>
                            @else
                                <div class="document-preview">
                                    <i class="fas fa-file-upload"></i>
                                    <p class="mb-0">No documents uploaded</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Additional Information -->
                    <div class="info-card">
                        <h5><i class="fas fa-info-circle me-2 text-primary"></i>Additional Information</h5>

                        <div class="info-item">
                            <div class="info-label">Profile Created</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($profileData['created_at'])->format('M d, Y h:i A') }}</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Last Updated</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($profileData['updated_at'])->format('M d, Y h:i A') }}</div>
                        </div>

                        @if($user->role === 'artist')
                            @if(!empty($profileData['previous_albums']))
                                <div class="info-item">
                                    <div class="info-label">Previous Albums</div>
                                    <div class="badge-list">
                                        @foreach($profileData['previous_albums'] as $album)
                                            <span class="badge-custom">{{ $album }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Verification History Modal -->
    <div class="modal fade" id="verificationHistoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-history me-2"></i>Verification History for {{ $user->name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="verificationHistoryContent">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Loading verification history...</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Approve User Function
        function approveUser(id) {
            Swal.fire({
                title: 'Approve User?',
                html: "This will activate the user account and send them a congratulations email.<br><br><strong>Note:</strong> Email must be verified before approval.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, approve!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/' + id + '/approve',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                title: 'Approved!',
                                text: response.message,
                                icon: 'success',
                                timer: 3000
                            }).then(() => {
                                location.reload();
                            });
                        },
                        error: function(xhr) {
                            Swal.fire({
                                title: 'Error!',
                                text: xhr.responseJSON.message,
                                icon: 'error',
                                confirmButtonColor: '#d33'
                            });
                        }
                    });
                }
            });
        }

        // Verify Profile Function
        function verifyProfile(id) {
            Swal.fire({
                title: 'Verify Profile?',
                html: `
                    <div class="text-start">
                        <p>You are about to verify this profile. Please confirm:</p>
                        <ul>
                            <li>✅ All required documents are uploaded</li>
                            <li>✅ Profile information is accurate</li>
                            <li>✅ No false information is present</li>
                            <li>✅ Documents are authentic</li>
                        </ul>
                        <p><strong>Reason for verification (optional):</strong></p>
                        <textarea id="verificationReason" class="form-control" rows="3" placeholder="Enter reason for verification..."></textarea>
                        <p class="text-warning mt-2"><i class="fas fa-exclamation-triangle me-1"></i> This action will mark the profile as verified.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Verify Profile',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const reason = document.getElementById('verificationReason').value;

                    return fetch(`/admin/users/${id}/verify-profile`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ reason: reason })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Verification failed: ${error}`
                            );
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Profile Verified!',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                <p>Profile has been verified successfully!</p>
                                <p class="text-muted">Verified on: ${result.value.verified_at}</p>
                                <p class="text-muted">Verified by: ${result.value.verified_by}</p>
                            </div>
                        `,
                        icon: 'success',
                        confirmButtonColor: '#28a745',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Unverify Profile Function
        function unverifyProfile(id) {
            Swal.fire({
                title: 'Remove Verification?',
                html: `
                    <div class="text-start">
                        <p>You are about to remove verification from this profile. This will:</p>
                        <ul class="text-danger">
                            <li>❌ Remove the verified badge</li>
                            <li>❌ Reset verification status</li>
                            <li>❌ Require re-verification in the future</li>
                        </ul>
                        <p class="text-danger"><strong>Reason for removal (required):</strong></p>
                        <textarea id="unverificationReason" class="form-control" rows="3" placeholder="Enter reason for removing verification..." required></textarea>
                        <p class="text-danger mt-2"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-times-circle me-1"></i> Remove Verification',
                cancelButtonText: 'Cancel',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    const reason = document.getElementById('unverificationReason').value;

                    if (!reason.trim()) {
                        Swal.showValidationMessage('Please provide a reason for removing verification');
                        return false;
                    }

                    return fetch(`/admin/users/${id}/unverify-profile`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ reason: reason })
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Failed to remove verification: ${error}`
                            );
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Verification Removed!',
                        html: `
                            <div class="text-center">
                                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                                <p>Profile verification has been removed.</p>
                                <p class="text-muted">The profile will need to be re-verified.</p>
                            </div>
                        `,
                        icon: 'warning',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                }
            });
        }

        // Load Verification History
        function loadVerificationLogs(userId) {
            const modal = new bootstrap.Modal(document.getElementById('verificationHistoryModal'));
            modal.show();

            $.ajax({
                url: `/admin/users/${userId}/verification-logs`,
                type: 'GET',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    let html = '';

                    if (response.logs.length === 0) {
                        html = `
                            <div class="text-center py-5">
                                <i class="fas fa-history fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No verification history found.</p>
                            </div>
                        `;
                    } else {
                        response.logs.forEach(log => {
                            html += `
                                <div class="verification-log-item ${log.action === 'unverified' ? 'unverified' : ''} mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            ${log.action === 'verified'
                                ? '<i class="fas fa-check-circle text-success me-2"></i><strong class="text-success">Verified</strong>'
                                : '<i class="fas fa-times-circle text-danger me-2"></i><strong class="text-danger">Unverified</strong>'
                            }
                                        </div>
                                        <small class="text-muted">${log.time_ago}</small>
                                    </div>
                                    <div class="mt-1">
                                        <small><strong>By:</strong> ${log.admin_name}</small>
                                        <br>
                                        <small><strong>Date:</strong> ${log.created_at}</small>
                                    </div>
                                    ${log.reason ? `
                                        <div class="verification-reason mt-2">
                                            <small><strong>Reason:</strong> ${log.reason}</small>
                                        </div>
                                    ` : ''}
                                </div>
                            `;
                        });
                    }

                    $('#verificationHistoryContent').html(html);
                },
                error: function(xhr) {
                    $('#verificationHistoryContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Failed to load verification history. Please try again.
                        </div>
                    `);
                }
            });
        }

        // Download document
        function downloadDocument(url) {
            window.open(url, '_blank');
        }
    </script>
@endsection
