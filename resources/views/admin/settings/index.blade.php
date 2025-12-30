@extends('admin.layouts.layout')

@section('title', 'Settings Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <div class="card-body text-white">
                    <h2 class="mb-0"><i class="fas fa-cog me-2"></i>Settings Dashboard</h2>
                    <p class="mb-0 opacity-75">Manage all your website settings from here</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        {{-- Logo Settings --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('admin.settings.logos.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-image fa-3x" style="color: #667eea;"></i>
                        </div>
                        <h5 class="card-title mb-2">Logo Settings</h5>
                        <p class="card-text text-muted small">
                            Main, Footer, Favicon, Lazy Banner & OG Image
                        </p>
                        <span class="badge bg-success mt-2">{{ $stats['logo_settings'] }}</span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Language Logos --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('admin.settings.language-logos.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-language fa-3x" style="color: #667eea;"></i>
                        </div>
                        <h5 class="card-title mb-2">Language Logos</h5>
                        <p class="card-text text-muted small">
                            English & Bangla logos
                        </p>
                        <span class="badge bg-success mt-2">{{ $stats['language_logos'] }}</span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Website Settings --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('admin.settings.website.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-globe fa-3x" style="color: #667eea;"></i>
                        </div>
                        <h5 class="card-title mb-2">Website Settings</h5>
                        <p class="card-text text-muted small">
                            Title, colors, fonts, loader & more
                        </p>
                        <span class="badge bg-success mt-2">{{ $stats['website_settings'] }}</span>
                    </div>
                </div>
            </a>
        </div>

        {{-- Email Settings --}}
        <div class="col-md-6 col-lg-4 mb-4">
            <a href="{{ route('admin.settings.email.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm hover-card">
                    <div class="card-body text-center p-4">
                        <div class="icon-wrapper mb-3">
                            <i class="fas fa-envelope fa-3x" style="color: #667eea;"></i>
                        </div>
                        <h5 class="card-title mb-2">Email Settings</h5>
                        <p class="card-text text-muted small">
                            SMTP configuration & test email
                        </p>
                        <span class="badge bg-success mt-2">{{ $stats['email_settings'] }}</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<style>
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3) !important;
}

.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 15px;
}
</style>
@endsection
