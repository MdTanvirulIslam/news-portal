@extends('admin.layouts.layout')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <style>
        .pages-header {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            border-radius: 12px;
            padding: 25px;
            color: white;
            margin-bottom: 25px;
        }
    </style>
@endsection

@section('content')
    <div class="pages-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-1">📄 Static Pages</h4>
                <p class="mb-0" style="opacity: 0.9; font-size: 14px;">Manage static pages like About, Contact, Privacy</p>
            </div>
            <a href="{{ route('admin.pages.create') }}" class="btn btn-light">
                <i class="fas fa-plus"></i> Add New Page
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="widget-content widget-content-area br-8">
                <div class="table-responsive">
                    <table id="pages-table" class="table table-hover" style="width:100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Title (English)</th>
                            <th>Title (বাংলা)</th>
                            <th>Status</th>
                            <th>Created</th>
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
        // Show success message
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        });
        @endif

        // Show error message
        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#e7515a'
        });
        @endif

        // Show validation errors
        @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach

        Swal.fire({
            icon: 'error',
            title: 'Validation Errors!',
            html: errorMessages.replace(/\n/g, '<br>'),
            confirmButtonColor: '#e7515a'
        });
        @endif

        $(document).ready(function() {
            $('#pages-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.pages.index') }}',
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'title_en', name: 'title_en' },
                    { data: 'title_bn', name: 'title_bn' },
                    { data: 'status', name: 'status' },
                    { data: 'created', name: 'created' },
                    { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                ],
                order: [[4, 'desc']]
            });
        });

        function toggleStatus(id) {
            $.ajax({
                url: '/admin/pages/' + id + '/toggle-status',
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                    $('#pages-table').DataTable().ajax.reload(null, false);
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: xhr.responseJSON?.message || 'Failed to toggle status',
                        confirmButtonColor: '#e7515a'
                    });
                }
            });
        }

        function deletePage(id) {
            Swal.fire({
                title: 'Delete Page?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e7515a',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Yes, delete!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/pages/' + id,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: response.message || 'Page deleted successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            $('#pages-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete page',
                                confirmButtonColor: '#e7515a'
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
