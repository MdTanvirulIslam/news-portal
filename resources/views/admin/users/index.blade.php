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

    {{-- Users Table --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="widget-content widget-content-area br-8">
                <div class="table-responsive">
                    <table id="users-table" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Email Status</th>
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
        $(document).ready(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'email_status', name: 'email_status', orderable: false },
                    { data: 'status', name: 'status' },
                    { data: 'joined', name: 'joined' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[6, 'desc']]
            });
        });

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
                            $('#users-table').DataTable().ajax.reload();
                            // Reload page to update stats
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
                            $('#users-table').DataTable().ajax.reload();
                            // Reload page to update stats
                            setTimeout(() => location.reload(), 2000);
                        },
                        error: function(xhr) {
                            Swal.fire('Error!', xhr.responseJSON.message, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endsection
