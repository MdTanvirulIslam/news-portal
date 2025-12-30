@extends('admin.layouts.layout')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 12px; padding: 25px; color: white; margin-bottom: 25px;">
                <h4 class="mb-1">📊 Dashboard</h4>
                <p class="mb-0" style="opacity: 0.9;">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Total Posts -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Posts</h6>
                        <h3 class="mb-0">{{ $totalPosts }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-file-alt" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Published -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Published</h6>
                        <h3 class="mb-0">{{ $publishedPosts }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check-circle" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pending</h6>
                        <h3 class="mb-0">{{ $pendingPosts }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-clock" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drafts -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Drafts</h6>
                        <h3 class="mb-0">{{ $draftPosts }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-edit" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Row Statistics -->
    <div class="row">
        <!-- Total Users -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Users</h6>
                        <h3 class="mb-0">{{ $totalUsers }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-users" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Categories</h6>
                        <h3 class="mb-0">{{ $totalCategories }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-folder" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tags -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Tags</h6>
                        <h3 class="mb-0">{{ $totalTags }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-tags" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscribers -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-4">
            <div class="widget-content-area br-8" style="padding: 20px;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Subscribers</h6>
                        <h3 class="mb-0">{{ $totalSubscribers }}</h3>
                    </div>
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #f2994a 0%, #f2c94c 100%); border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-envelope" style="color: white; font-size: 24px;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Row -->
    <div class="row">
        <!-- Pending Approvals -->
        @if(in_array(auth()->user()->role, ['admin', 'editor']) && $pendingApprovals->count() > 0)
            <div class="col-xl-12 mb-4">
                <div class="widget-content-area br-8">
                    <div class="d-flex justify-content-between align-items-center mb-3" style="padding: 20px 20px 0 20px;">
                        <h5>⏳ Pending Approvals</h5>
                        <a href="{{ route('admin.posts.index') }}?status=pending" class="btn btn-sm btn-primary">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Categories</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($pendingApprovals as $post)
                                <tr>
                                    <td>{{ $post->title_en ?? $post->title_bn }}</td>
                                    <td>{{ $post->user->name ?? 'N/A' }}</td>
                                    <td>
                                        @foreach($post->categories as $category)
                                            <span class="badge bg-primary">{{ $category->name_en }}</span>
                                        @endforeach
                                    </td>
                                    <td>{{ $post->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Review
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    @endif

    <!-- Recent Posts -->
        <div class="col-xl-6 mb-4">
            <div class="widget-content-area br-8">
                <div class="d-flex justify-content-between align-items-center mb-3" style="padding: 20px 20px 0 20px;">
                    <h5>📝 Recent Posts</h5>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-secondary">View All</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($recentPosts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post) }}">{{ Str::limit($post->title_en ?? $post->title_bn, 40) }}</a>
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($post->status === 'pending')
                                        <span class="badge badge-warning">Pending</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($post->status) }}</span>
                                    @endif
                                </td>
                                <td>{{ $post->created_at->format('M d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Popular Posts -->
        <div class="col-xl-6 mb-4">
            <div class="widget-content-area br-8">
                <div class="d-flex justify-content-between align-items-center mb-3" style="padding: 20px 20px 0 20px;">
                    <h5>🔥 Popular Posts</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($popularPosts as $post)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post) }}">{{ Str::limit($post->title_en ?? $post->title_bn, 40) }}</a>
                                </td>
                                <td>
                                    <span class="badge badge-success">Published</span>
                                </td>
                                <td>{{ $post->created_at->format('M d') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Floating Button -->
    <div style="position: fixed; bottom: 30px; right: 30px; z-index: 999;">
        <div class="btn-group dropup">
            <button type="button" class="btn btn-primary btn-lg" style="border-radius: 50%; width: 60px; height: 60px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);">
                <i class="fas fa-plus"></i>
            </button>
            <button type="button" class="btn btn-primary btn-lg dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" style="border-radius: 50%; width: 60px; height: 60px; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.5);">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.posts.create') }}"><i class="fas fa-file-alt me-2"></i> New Post</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.categories.create') }}"><i class="fas fa-folder me-2"></i> New Category</a></li>
                <li><a class="dropdown-item" href="{{ route('admin.tags.create') }}"><i class="fas fa-tag me-2"></i> New Tag</a></li>
            </ul>
        </div>
    </div>
@endsection
