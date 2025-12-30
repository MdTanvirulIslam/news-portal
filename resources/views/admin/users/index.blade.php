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
                            <th>Posts</th>
                            <th>Status</th>
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
            { data: 'posts_count', name: 'posts_count' },
            { data: 'status', name: 'status' },
            { data: 'joined', name: 'joined' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[6, 'desc']]
    });
});

function deleteUser(id) {
    Swal.fire({
        title: 'Delete User?',
        text: "This will remove the user from the system!",
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
                    Swal.fire('Deleted!', response.message, 'success');
                    $('#users-table').DataTable().ajax.reload();
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
