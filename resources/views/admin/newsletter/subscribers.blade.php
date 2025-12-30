@extends('admin.layouts.layout')

@section('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <style>
        /* Header with gradient */
        .newsletter-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.12);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            margin-bottom: 15px;
        }

        .stats-number {
            font-size: 36px;
            font-weight: 700;
            margin: 10px 0;
        }

        .stats-label {
            color: #8492a6;
            font-size: 14px;
            font-weight: 500;
        }

        /* Beautiful gradient icon buttons */
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
            color: white;
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .btn-verify {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        }

        .btn-verify:hover {
            background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
        }

        .btn-delete {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #fee140 0%, #fa709a 100%);
        }

        .action-btn i {
            font-size: 16px;
            line-height: 1;
        }

        /* Checkbox styling */
        .subscriber-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Card table */
        .widget-content-area {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
    </style>
@endsection

@section('content')
    <!-- Beautiful Header -->
    <div class="newsletter-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <div>
                <h3 class="mb-1">📧 Newsletter Subscribers</h3>
                <p class="mb-0" style="opacity: 0.9;">Manage your email subscription list</p>
            </div>
            <div class="d-flex gap-2 mt-3 mt-md-0">
                <a href="{{ route('admin.newsletter.subscribers.export') }}" class="btn btn-light">
                    <i class="fas fa-download me-1"></i> Export CSV
                </a>
                <button onclick="bulkDelete()" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
                    <i class="fas fa-trash me-1"></i> Delete Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <!-- Total Subscribers -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stats-number" style="color: #667eea;">
                    {{ number_format($stats['total']) }}
                </div>
                <div class="stats-label">Total Subscribers</div>
            </div>
        </div>

        <!-- Active & Verified -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stats-number" style="color: #11998e;">
                    {{ number_format($stats['active']) }}
                </div>
                <div class="stats-label">Active & Verified</div>
            </div>
        </div>

        <!-- Unverified -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stats-number" style="color: #f093fb;">
                    {{ number_format($stats['unverified']) }}
                </div>
                <div class="stats-label">Unverified</div>
            </div>
        </div>

        <!-- Unsubscribed -->
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12 mb-3">
            <div class="stats-card">
                <div class="stats-icon" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stats-number" style="color: #fa709a;">
                    {{ number_format($stats['unsubscribed']) }}
                </div>
                <div class="stats-label">Unsubscribed</div>
            </div>
        </div>
    </div>

    <!-- Subscribers Table -->
    <div class="row">
        <div class="col-12">
            <div class="widget-content-area">
                <div class="table-responsive">
                    <table id="subscribers-table" class="table table-hover">
                        <thead>
                        <tr>
                            <th width="5%">
                                <input type="checkbox" id="select-all" class="subscriber-checkbox">
                            </th>
                            <th width="5%">#</th>
                            <th>Email</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Subscribed Date</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#subscribers-table').DataTable({

                serverSide: true,
                ajax: '{{ route('admin.newsletter.subscribers.index') }}',
                columns: [
                    { data: 'select', name: 'select', orderable: false, searchable: false },
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'email', name: 'email' },
                    { data: 'name', name: 'name' },
                    { data: 'status', name: 'status' },
                    { data: 'subscribed', name: 'subscribed_at' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[5, 'desc']], // Order by subscribed date
                pageLength: 25,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
                }
            });

            // Select all checkbox
            $('#select-all').on('click', function() {
                $('.subscriber-checkbox').not('#select-all').prop('checked', this.checked);
                toggleBulkDeleteButton();
            });

            // Individual checkbox
            $(document).on('change', '.subscriber-checkbox', function() {
                if (!$(this).is('#select-all')) {
                    toggleBulkDeleteButton();
                }
            });

            function toggleBulkDeleteButton() {
                var checked = $('.subscriber-checkbox:checked').not('#select-all').length;
                if (checked > 0) {
                    $('#bulk-delete-btn').show();
                } else {
                    $('#bulk-delete-btn').hide();
                }
            }
        });

        // Verify subscriber
        function verifySubscriber(id) {
            Swal.fire({
                title: 'Verify Subscriber?',
                text: 'Mark this subscriber as verified',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, Verify',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#11998e',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/newsletter/subscribers/' + id + '/verify',
                        type: 'POST',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Verified!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#subscribers-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Something went wrong'
                            });
                        }
                    });
                }
            });
        }

        // Delete subscriber
        function deleteSubscriber(id) {
            Swal.fire({
                title: 'Delete Subscriber?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/newsletter/subscribers/' + id,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#subscribers-table').DataTable().ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Something went wrong'
                            });
                        }
                    });
                }
            });
        }

        // Bulk delete
        function bulkDelete() {
            var selected = [];
            $('.subscriber-checkbox:checked').not('#select-all').each(function() {
                selected.push($(this).val());
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'No Selection',
                    text: 'Please select subscribers to delete'
                });
                return;
            }

            Swal.fire({
                title: 'Delete ' + selected.length + ' Subscriber(s)?',
                text: 'This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete All',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.newsletter.subscribers.bulk-delete') }}',
                        type: 'POST',
                        data: { ids: selected },
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#subscribers-table').DataTable().ajax.reload();
                            $('#bulk-delete-btn').hide();
                            $('#select-all').prop('checked', false);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON?.message || 'Something went wrong'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
