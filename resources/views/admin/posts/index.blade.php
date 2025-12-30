@extends('admin.layouts.layout')

@section('styles')

@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Posts</h3>
                        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-1"></i> Create Post
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters Row -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select id="filter-status" class="form-select">
                                <option value="">All Status</option>
                                <option value="published">Published</option>
                                <option value="pending">Pending</option>
                                <option value="draft">Draft</option>
                                <option value="rejected">Rejected</option>
                                <option value="scheduled">Scheduled</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Post Type</label>
                            <select id="filter-type" class="form-select">
                                <option value="">All Types</option>
                                <option value="article">Article</option>
                                <option value="gallery">Gallery</option>
                                <option value="video">Video</option>
                                <option value="audio">Audio</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <select id="filter-category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name_en }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Filters</label>
                            <div class="d-flex gap-2">
                                <button id="filter-featured" class="btn btn-outline-success flex-fill" data-active="false">
                                    <i class="fas fa-star"></i> Featured
                                </button>
                                <button id="filter-breaking" class="btn btn-outline-danger flex-fill" data-active="false">
                                    <i class="fas fa-bolt"></i> Breaking
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <table id="posts-table" class="table table-hover table-bordered" style="width:100%">
                            <thead>
                            <tr>
                                <th width="5%">ID</th>
                                <th width="23%">Title</th>
                                <th width="10%">Category</th>
                                <th width="10%">Author</th>
                                <th width="10%">Status</th>
                                <th width="8%">Flags</th>
                                <th width="7%">Views</th>
                                <th width="10%">Published</th>
                                <th width="17%" class="text-center">Actions</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/table/datatable/datatables.js') }}"></script>
    <script src="{{ asset('admin/plugins/sweetalerts2/sweetalerts2.min.js') }}"></script>
    <script src="{{ asset('admin/plugins/moment/moment.min.js') }}"></script>
    <script>
        // Toast function
        function showToast(type, message) {
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
                Toast.fire({ icon: type, title: message });
            }
        }

        // Show session messages
        @if(session('success'))
        showToast('success', '{{ session('success') }}');
        @endif
        @if(session('error'))
        showToast('error', '{{ session('error') }}');
        @endif
        @if($errors->any())
        showToast('error', '{{ $errors->first() }}');
        @endif

        // DataTable
        $(document).ready(function() {
            const table = $('#posts-table').DataTable({
                processing: false,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.posts.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.post_type = $('#filter-type').val();
                        d.category_id = $('#filter-category').val();
                        d.is_featured = $('#filter-featured').data('active') === true ? 'true' : '';
                        d.is_breaking = $('#filter-breaking').data('active') === true ? 'true' : '';
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: 'title',
                        name: 'title_en',
                        render: function(data, type, row) {
                            let html = '<div>';
                            html += '<div class="fw-bold mb-1">' + (data || 'Untitled') + '</div>';
                            if (row.type && row.type !== 'article') {
                                let badgeColor = {
                                    'gallery': 'info',
                                    'video': 'warning',
                                    'audio': 'primary'
                                }[row.type] || 'secondary';
                                html += '<span class="badge bg-' + badgeColor + '">' + row.type.toUpperCase() + '</span>';
                            }
                            html += '</div>';
                            return html;
                        }
                    },
                    {
                        data: 'category',
                        name: 'category',
                        render: function(data) {
                            return data || '<span class="text-muted">—</span>';
                        }
                    },
                    {
                        data: 'author',
                        name: 'author',
                        render: function(data) {
                            return '<span class="badge bg-secondary">' + (data || 'Unknown') + '</span>';
                        }
                    },
                    {
                        data: 'status',
                        name: 'status',
                        render: function(data) {
                            const colors = {
                                'published': 'success',
                                'pending': 'warning',
                                'draft': 'secondary',
                                'rejected': 'danger',
                                'scheduled': 'info'
                            };
                            const color = colors[data] || 'secondary';
                            return '<span class="badge bg-' + color + '">' +
                                (data ? data.charAt(0).toUpperCase() + data.slice(1) : 'Unknown') + '</span>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            let html = '<div style="font-size: 18px;">';
                            if (row.is_featured) {
                                html += '<i class="fas fa-star text-warning me-2" title="Featured"></i>';
                            }
                            if (row.is_breaking) {
                                html += '<i class="fas fa-bolt text-danger" title="Breaking"></i>';
                            }
                            if (!row.is_featured && !row.is_breaking) {
                                html += '<span class="text-muted">—</span>';
                            }
                            html += '</div>';
                            return html;
                        }
                    },
                    {
                        data: 'views',
                        name: 'views_count',
                        render: function(data) {
                            return '<span class="badge bg-info">' + (data || 0) + '</span>';
                        }
                    },
                    {
                        data: 'published_at',
                        name: 'published_at',
                        render: function(data) {
                            if (!data || data === 'Not published' || data === null) {
                                return '<span class="text-muted">—</span>';
                            }
                            if (typeof moment !== 'undefined') {
                                return moment(data).format('MMM DD, YYYY');
                            }
                            return data;
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'text-center',
                        render: function(data, type, row) {
                            let html = '<div class="action-btns d-flex gap-2 justify-content-center">';

                            // ✅ APPROVE BUTTON (Only for Admin/Editor on pending posts)
                            if (row.can_approve) {
                                html += `
                                    <button onclick="approvePost(${row.id})" class="btn btn-sm btn-icon btn-success" title="Approve & Publish">
                                        <i class="fas fa-check me-1"></i>
                                    </button>
                                `;
                            }

                            // Edit button
                            html += `
                                <a href="${row.edit_url}" class="btn btn-sm btn-icon btn-primary" title="Edit">
                                    <i class="fas fa-edit me-1"></i>
                                </a>
                            `;

                            // Delete button
                            html += `
                                <button onclick="deletePost(${row.id})" class="btn btn-sm btn-icon btn-danger" title="Delete">
                                    <i class="fas fa-trash me-1"></i>
                                </button>
                            `;

                            html += '</div>';
                            return html;
                        }
                    }
                ],
                order: [[0, 'desc']],
                pageLength: 25,
                language: {
                    paginate: {
                        previous: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        next: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    info: "Showing page _PAGE_ of _PAGES_",
                    search: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>',
                    searchPlaceholder: 'Search posts...',
                    lengthMenu: 'Results :  _MENU_'
                }
            });

            // Filters
            $('#filter-status, #filter-type, #filter-category').on('change', function() {
                table.ajax.reload();
            });

            $('#filter-featured, #filter-breaking').on('click', function() {
                const $btn = $(this);
                const newState = !$btn.data('active');
                $btn.data('active', newState);

                if (newState) {
                    if ($btn.attr('id') === 'filter-featured') {
                        $btn.removeClass('btn-outline-success').addClass('btn-success');
                    } else {
                        $btn.removeClass('btn-outline-danger').addClass('btn-danger');
                    }
                } else {
                    if ($btn.attr('id') === 'filter-featured') {
                        $btn.removeClass('btn-success').addClass('btn-outline-success');
                    } else {
                        $btn.removeClass('btn-danger').addClass('btn-outline-danger');
                    }
                }

                table.ajax.reload();
            });
        });

        // ✅ NEW: Approve function
        function approvePost(id) {
            if (typeof Swal === 'undefined') {
                if (confirm('Approve and publish this post?')) performApprove(id);
                return;
            }

            Swal.fire({
                title: 'Approve Post?',
                text: "This post will be published immediately!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) performApprove(id);
            });
        }

        function performApprove(id) {
            $.ajax({
                url: '/admin/posts/' + id + '/approve',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    showToast('success', response.message || 'Post approved successfully');
                    $('#posts-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Error approving post');
                }
            });
        }

        // Delete function
        function deletePost(id) {
            if (typeof Swal === 'undefined') {
                if (confirm('Are you sure?')) performDelete(id);
                return;
            }

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-secondary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) performDelete(id);
            });
        }

        function performDelete(id) {
            $.ajax({
                url: '/admin/posts/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    showToast('success', response.message || 'Post deleted successfully');
                    $('#posts-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    showToast('error', xhr.responseJSON?.message || 'Error deleting post');
                }
            });
        }
    </script>
@endsection
