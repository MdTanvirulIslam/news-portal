@extends('admin.layouts.layout')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* Beautiful Gradient Icon Buttons */
        .action-btn {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            transition: all 0.3s ease;
            cursor: pointer;
            margin: 0 3px;
            text-decoration: none;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        /* Import Button - Purple Gradient */
        .btn-import {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-import:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            color: white;
        }

        /* Edit Button - Pink Gradient */
        .btn-edit {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%);
            color: white;
        }

        /* Delete Button - Orange-Yellow Gradient */
        .btn-delete {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
            color: white;
        }

        /* Icon styling */
        .action-btn i {
            font-size: 16px;
            line-height: 1;
        }

        /* Tooltip styling */
        .action-btn[title] {
            position: relative;
        }

        /* Actions column alignment */
        .actions-cell {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 5px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-rss"></i> RSS Feeds
                        </h3>
                        <div>
                            <button type="button" id="import-all-btn" class="btn btn-success me-2">
                                <i class="fas fa-sync-alt me-1"></i> Import All
                            </button>
                            <a href="{{ route('admin.rss-feeds.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Add RSS Feed
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="rss-feeds-table" class="table table-hover">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Feed URL</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Auto Import</th>
                                <th>Last Fetch</th>
                                <th>Stats</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery (if not already included) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Show success/error messages from session
            @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            @endif

            @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ session('error') }}',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            @endif

            @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<ul style="text-align: left;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
            });
            @endif

            // Initialize DataTable
            var table = $('#rss-feeds-table').DataTable({

                serverSide: true,
                ajax: '{{ route('admin.rss-feeds.index') }}',
                columns: [
                    { data: 'name', name: 'name' },
                    {
                        data: 'feed_url',
                        name: 'feed_url',
                        render: function(data) {
                            return '<a href="' + data + '" target="_blank" class="text-primary">' +
                                (data.length > 50 ? data.substring(0, 50) + '...' : data) +
                                '</a>';
                        }
                    },
                    { data: 'category', name: 'category', orderable: false, searchable: false },
                    { data: 'status', name: 'is_active' },
                    { data: 'auto_import_status', name: 'auto_import' },
                    { data: 'last_fetch', name: 'last_fetched_at' },
                    { data: 'stats', name: 'total_imported' },
                    { data: 'actions', name: 'actions', orderable: false, searchable: false }
                ],
                order: [[0, 'asc']],
                pageLength: 25,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            // Import single feed
            $(document).on('click', '.import-feed', function() {
                var feedId = $(this).data('id');
                var feedName = $(this).data('name');
                var btn = $(this);
                var originalHtml = btn.html();

                Swal.fire({
                    title: 'Import from RSS Feed?',
                    text: 'Import posts from: ' + feedName,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Import',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

                        $.ajax({
                            url: '/admin/rss-feeds/' + feedId + '/import',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Import Successful!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                table.ajax.reload();
                                btn.prop('disabled', false).html(originalHtml);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Import Failed',
                                    text: xhr.responseJSON?.message || 'An error occurred',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                btn.prop('disabled', false).html(originalHtml);
                            }
                        });
                    }
                });
            });

            // Import all feeds
            $('#import-all-btn').click(function() {
                var btn = $(this);
                var originalHtml = btn.html();

                Swal.fire({
                    title: 'Import from All Feeds?',
                    text: 'This will import from all active auto-import feeds',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Import All',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Importing...');

                        $.ajax({
                            url: '{{ route('admin.rss-feeds.import-all') }}',
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Bulk Import Successful!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                table.ajax.reload();
                                btn.prop('disabled', false).html(originalHtml);
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Import Failed',
                                    text: xhr.responseJSON?.message || 'An error occurred',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                                btn.prop('disabled', false).html(originalHtml);
                            }
                        });
                    }
                });
            });

            // Delete feed
            $(document).on('click', '.delete-feed', function() {
                var feedId = $(this).data('id');

                Swal.fire({
                    title: 'Delete RSS Feed?',
                    text: 'This action cannot be undone!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/rss-feeds/' + feedId,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                table.ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Failed to delete feed',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                });
                            }
                        });
                    }
                });
            });

            // Initialize Bootstrap tooltips if available
            if (typeof bootstrap !== 'undefined') {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        });
    </script>
@endsection
