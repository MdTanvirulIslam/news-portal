@extends('admin.layouts.layout')

@section('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<style>
    .newsletter-header {
        background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
        border-radius: 12px;
        padding: 25px;
        color: white;
        margin-bottom: 25px;
    }
    .stats-card {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 32px;
        font-weight: 700;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>
@endsection

@section('content')
<div class="newsletter-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h4 class="mb-1">📧 Newsletter Subscribers</h4>
            <p class="mb-0" style="opacity: 0.9; font-size: 14px;">Manage your email subscribers</p>
        </div>
        <div>
            <a href="{{ route('admin.newsletter.subscribers.export') }}" class="btn btn-light me-2">
                <i class="fas fa-download"></i> Export CSV
            </a>
            <button onclick="bulkDelete()" class="btn btn-danger" id="bulk-delete-btn" style="display: none;">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-muted">Total Subscribers</h6>
            <div class="stats-number">{{ number_format($totalSubscribers) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-muted">Active & Verified</h6>
            <div class="stats-number">{{ number_format($activeSubscribers) }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h6 class="text-muted">Unverified</h6>
            <div class="stats-number">{{ number_format($unverifiedSubscribers) }}</div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-12">
        <div class="widget-content widget-content-area br-8">
            <div class="table-responsive">
                <table id="subscribers-table" class="table table-hover" style="width:100%">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>#</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Subscribed Date</th>
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
    var table = $('#subscribers-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('admin.newsletter.subscribers.index') }}',
        columns: [
            { data: 'select', name: 'select', orderable: false, searchable: false },
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'email', name: 'email' },
            { data: 'status', name: 'status' },
            { data: 'subscribed', name: 'subscribed' },
            { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[4, 'desc']]
    });

    // Select all checkbox
    $('#select-all').on('click', function() {
        $('.subscriber-checkbox').prop('checked', this.checked);
        toggleBulkDelete();
    });

    // Individual checkbox
    $(document).on('change', '.subscriber-checkbox', function() {
        toggleBulkDelete();
    });

    function toggleBulkDelete() {
        var checked = $('.subscriber-checkbox:checked').length;
        if (checked > 0) {
            $('#bulk-delete-btn').show();
        } else {
            $('#bulk-delete-btn').hide();
        }
    }
});

function deleteSubscriber(id) {
    Swal.fire({
        title: 'Delete Subscriber?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/admin/newsletter/subscribers/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success');
                    $('#subscribers-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON.message, 'error');
                }
            });
        }
    });
}

function bulkDelete() {
    var selected = [];
    $('.subscriber-checkbox:checked').each(function() {
        selected.push($(this).val());
    });

    if (selected.length === 0) {
        Swal.fire('Error!', 'Please select subscribers to delete', 'error');
        return;
    }

    Swal.fire({
        title: 'Delete ' + selected.length + ' subscribers?',
        text: "This action cannot be undone!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Yes, delete all!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route('admin.newsletter.subscribers.bulk-delete') }}',
                type: 'POST',
                data: { ids: selected },
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire('Deleted!', response.message, 'success');
                    $('#subscribers-table').DataTable().ajax.reload();
                    $('#bulk-delete-btn').hide();
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
