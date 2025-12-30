@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-sitemap me-2"></i> Sitemap Manager
                        </h3>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" onclick="regenerateSitemap()">
                                <i class="fas fa-sync-alt me-1"></i> Regenerate Sitemap
                            </button>
                            <a href="{{ url('/sitemap.xml') }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-code me-1"></i> View XML Sitemap
                            </a>
                            <a href="{{ url('/sitemap') }}" target="_blank" class="btn btn-info btn-sm">
                                <i class="fas fa-list me-1"></i> View HTML Sitemap
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Published Posts
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($stats['total_posts']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Active Categories
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($stats['total_categories']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-folder fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Active Tags
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ number_format($stats['total_tags']) }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Cache Status
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @if($stats['sitemap_cached'])
                                                    <span class="badge bg-success">Cached</span>
                                                @else
                                                    <span class="badge bg-warning">Not Cached</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-database fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sitemap Information -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h5 class="alert-heading">
                                    <i class="fas fa-info-circle me-2"></i> Sitemap Information
                                </h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>XML Sitemap URL:</strong></p>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{ url('/sitemap.xml') }}" id="xml-url" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('xml-url')">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>

                                        <p><strong>HTML Sitemap URL:</strong></p>
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" value="{{ url('/sitemap') }}" id="html-url" readonly>
                                            <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('html-url')">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Last Updated:</strong> {{ $stats['last_updated']->format('F d, Y - h:i A') }}</p>
                                        <p><strong>Update Frequency:</strong> Automatically updates every hour</p>
                                        <p class="mb-0"><strong>Total URLs:</strong> Approximately {{ $stats['total_posts'] + $stats['total_categories'] + $stats['total_tags'] + 5 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-tools me-2"></i> Actions
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-lg btn-primary" onclick="submitToGoogle()">
                                                    <i class="fab fa-google me-2"></i> Submit to Google
                                                </button>
                                                <small class="text-muted mt-2">Submit your sitemap to Google Search Console</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-lg btn-info" onclick="submitToBing()">
                                                    <i class="fas fa-search me-2"></i> Submit to Bing
                                                </button>
                                                <small class="text-muted mt-2">Submit your sitemap to Bing Webmaster Tools</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-grid">
                                                <a href="{{ route('admin.sitemap.download') }}" class="btn btn-lg btn-success">
                                                    <i class="fas fa-download me-2"></i> Download XML
                                                </a>
                                                <small class="text-muted mt-2">Download sitemap.xml file</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Posts Preview -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-list me-2"></i> Recent Posts in Sitemap (Last 10)
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Type</th>
                                                <th>Published</th>
                                                <th>URL</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($recentPosts as $post)
                                                <tr>
                                                    <td>{{ Str::limit($post->title_en ?: $post->title_bn, 50) }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $post->post_type === 'article' ? 'primary' : ($post->post_type === 'video' ? 'warning' : 'success') }}">
                                                            {{ ucfirst($post->post_type) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $post->published_at->format('M d, Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.posts.edit', $post->id) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center text-muted">No published posts found</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-question-circle me-2"></i> How to Use
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <h6>1. Submit to Google Search Console:</h6>
                                    <ul>
                                        <li>Go to <a href="https://search.google.com/search-console" target="_blank">Google Search Console</a></li>
                                        <li>Select your property</li>
                                        <li>Go to "Sitemaps" in the left sidebar</li>
                                        <li>Enter: <code>sitemap.xml</code></li>
                                        <li>Click "Submit"</li>
                                    </ul>

                                    <h6>2. Submit to Bing Webmaster Tools:</h6>
                                    <ul>
                                        <li>Go to <a href="https://www.bing.com/webmasters" target="_blank">Bing Webmaster Tools</a></li>
                                        <li>Select your site</li>
                                        <li>Go to "Sitemaps"</li>
                                        <li>Enter: <code>{{ url('/sitemap.xml') }}</code></li>
                                        <li>Click "Submit"</li>
                                    </ul>

                                    <h6>3. Update robots.txt:</h6>
                                    <p>Add this line to your <code>public/robots.txt</code> file:</p>
                                    <pre class="bg-dark text-light p-3 rounded">Sitemap: {{ url('/sitemap.xml') }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Regenerate sitemap
        function regenerateSitemap() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Regenerating Sitemap...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            fetch('{{ route('admin.sitemap.regenerate') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: data.success ? 'success' : 'error',
                            title: data.success ? 'Success!' : 'Error!',
                            text: data.message,
                            showConfirmButton: true
                        }).then(() => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                    } else {
                        alert(data.message);
                        if (data.success) location.reload();
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to regenerate sitemap'
                        });
                    } else {
                        alert('Failed to regenerate sitemap');
                    }
                });
        }

        // Submit to Google
        function submitToGoogle() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Submitting to Google...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            fetch('{{ route('admin.sitemap.submit.google') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: data.success ? 'success' : 'info',
                            title: data.success ? 'Submitted!' : 'Note',
                            text: data.message,
                            showConfirmButton: true
                        });
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to submit to Google'
                        });
                    } else {
                        alert('Failed to submit to Google');
                    }
                });
        }

        // Submit to Bing
        function submitToBing() {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Submitting to Bing...',
                    text: 'Please wait...',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }

            fetch('{{ route('admin.sitemap.submit.bing') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: data.success ? 'success' : 'info',
                            title: data.success ? 'Submitted!' : 'Note',
                            text: data.message,
                            showConfirmButton: true
                        });
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Failed to submit to Bing'
                        });
                    } else {
                        alert('Failed to submit to Bing');
                    }
                });
        }

        // Copy to clipboard
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId);
            copyText.select();
            copyText.setSelectionRange(0, 99999); // For mobile devices
            document.execCommand("copy");

            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 2000
                });
                Toast.fire({
                    icon: 'success',
                    title: 'Copied to clipboard!'
                });
            } else {
                alert('Copied to clipboard!');
            }
        }
    </script>
@endsection
