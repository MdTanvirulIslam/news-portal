@extends('admin.layouts.layout')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .users-header {
            background: linear-gradient(135deg, #8e2de2 0%, #4a00e0 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 10px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin: 10px 0;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
        }

        .badge-sm {
            padding: 3px 8px;
            font-size: 11px;
        }

        .table th {
            font-weight: 600;
            color: #495057;
            font-size: 14px;
        }

        .btn-group .btn.active {
            font-weight: 600;
        }

        .btn-group .btn.btn-primary.active {
            background-color: #007bff;
            color: white;
        }

        .btn-group .btn.btn-success.active {
            background-color: #28a745;
            color: white;
        }

        .btn-group .btn.btn-warning.active {
            background-color: #ffc107;
            color: #212529;
        }

        .dataTables_filter {
            float: right;
            text-align: right;
        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
        }

        .dataTables_filter input {
            margin-left: 10px;
        }

        #profile-filter {
            margin-left: 10px;
            display: inline-block;
            width: auto;
        }
    </style>
@endsection

@section('content')
    <div class="users-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">👥 Users Management</h4>
                <p class="mb-0" style="opacity: 0.9; font-size: 14px;">Manage system users and permissions</p>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-primary text-white">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number text-primary">{{ $stats['total'] }}</div>
                <div class="stat-label">Total Users</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-success text-white">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number text-success">{{ $stats['active'] }}</div>
                <div class="stat-label">Active Users</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-warning text-white">
                    <i class="fas fa-user-clock"></i>
                </div>
                <div class="stat-number text-warning">{{ $stats['pending'] }}</div>
                <div class="stat-label">Pending Approval</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-info text-white">
                    <i class="fas fa-envelope-circle-check"></i>
                </div>
                <div class="stat-number text-info">{{ $stats['verified'] }}</div>
                <div class="stat-label">Email Verified</div>
            </div>
        </div>
    </div>

    {{-- Profile Verification Statistics --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-success text-white">
                    <i class="fas fa-user-check"></i>
                </div>
                <div class="stat-number text-success">{{ $stats['profiles_verified'] ?? 0 }}</div>
                <div class="stat-label">Profiles Verified</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-warning text-white">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number text-warning">{{ $stats['profiles_not_verified'] ?? 0 }}</div>
                <div class="stat-label">Profiles Not Verified</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-secondary text-white">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-number text-secondary">{{ $stats['admins'] }}</div>
                <div class="stat-label">Administrators</div>
            </div>
        </div>

        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-12">
            <div class="stat-card">
                <div class="stat-icon bg-danger text-white">
                    <i class="fas fa-user-times"></i>
                </div>
                <div class="stat-number text-danger">{{ $stats['unverified'] }}</div>
                <div class="stat-label">Email Not Verified</div>
            </div>
        </div>
    </div>

    {{-- Users Table --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="widget-content widget-content-area br-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">All Users</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="filterTable('all')">
                            All Users
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="filterTable('verified')">
                            <i class="fas fa-check-circle me-1"></i> Verified Profiles
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="filterTable('not_verified')">
                            <i class="fas fa-clock me-1"></i> Not Verified
                        </button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="users-table" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Email Status</th>
                            <th>Profile Verified</th>
                            <th>Account Status</th>
                            <th>Joined</th>
                            <th class="text-center">Actions</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let dataTable;
        let currentFilter = 'all'; // Track current filter

        $(document).ready(function() {
            // Initialize DataTable
            dataTable = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.users.index') }}',
                    data: function(d) {
                        // Add filter parameter to AJAX request
                        d.profile_verified_filter = currentFilter;
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'email_status', name: 'email_status', orderable: false },
                    { data: 'profile_verified', name: 'profile_verified', orderable: false, searchable: false },
                    { data: 'status', name: 'status' },
                    { data: 'joined', name: 'joined' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[7, 'desc']],
                language: {
                    search: "Search users:",
                    lengthMenu: "Show _MENU_ users per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ users",
                    infoEmpty: "No users found",
                    infoFiltered: "(filtered from _MAX_ total users)",
                    zeroRecords: "No matching users found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                // Add custom search for profile verification
                initComplete: function() {
                    // Add custom search input
                    $('#users-table_filter').append(
                        '<div class="d-inline-block ms-2">' +
                        '<select id="profile-filter" class="form-control form-control-sm d-inline-block w-auto">' +
                        '<option value="all">All Profiles</option>' +
                        '<option value="verified">Verified Only</option>' +
                        '<option value="not_verified">Not Verified</option>' +
                        '</select>' +
                        '</div>'
                    );

                    // Handle filter change
                    $('#profile-filter').on('change', function() {
                        currentFilter = $(this).val();
                        dataTable.ajax.reload();
                        updateFilterButtons(currentFilter);
                    });

                    // Initialize filter buttons
                    updateFilterButtons(currentFilter);
                }
            });
        });

        // Update filter buttons active state
        function updateFilterButtons(filter) {
            // Remove active class from all buttons
            $('.btn-group .btn').removeClass('active').removeClass('btn-primary btn-success btn-warning');

            // Add appropriate class based on filter
            switch(filter) {
                case 'all':
                    $('.btn-group .btn:contains("All Users")').addClass('active btn-primary');
                    break;
                case 'verified':
                    $('.btn-group .btn:contains("Verified Profiles")').addClass('active btn-success');
                    break;
                case 'not_verified':
                    $('.btn-group .btn:contains("Not Verified")').addClass('active btn-warning');
                    break;
            }
        }

        // Filter table function (for button clicks)
        function filterTable(status) {
            currentFilter = status;
            $('#profile-filter').val(status);
            dataTable.ajax.reload();
            updateFilterButtons(status);
        }

        // Approve User
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
                            });
                            dataTable.ajax.reload();
                            // Reload page to update stats after a delay
                            setTimeout(() => location.reload(), 3000);
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

        // Delete User
        function deleteUser(id) {
            Swal.fire({
                title: 'Delete User?',
                text: "This will permanently remove the user from the system!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/users/' + id,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                title: 'Deleted!',
                                text: response.message,
                                icon: 'success',
                                timer: 2000
                            });
                            dataTable.ajax.reload();
                            // Reload page to update stats after a delay
                            setTimeout(() => location.reload(), 2000);
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        }

        // Quick verification from table (optional)
        function quickVerifyProfile(userId) {
            Swal.fire({
                title: 'Quick Verify Profile?',
                html: "This will mark the profile as verified without viewing details.<br><br><strong>Note:</strong> Please ensure documents are checked first.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#17a2b8',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-check-circle me-1"></i> Quick Verify',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return fetch(`/admin/users/${userId}/verify-profile`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ reason: 'Quick verification from users list' })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (!data.success) {
                                throw new Error(data.message);
                            }
                            return data;
                        })
                        .catch(error => {
                            Swal.showValidationMessage(`Verification failed: ${error}`);
                        });
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Profile Verified!',
                        text: 'Profile has been verified successfully.',
                        icon: 'success',
                        confirmButtonColor: '#28a745'
                    }).then(() => {
                        dataTable.ajax.reload();
                    });
                }
            });
        }
    </script>
@endsection
