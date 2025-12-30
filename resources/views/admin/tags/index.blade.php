@extends("admin.layouts.layout")

@section('content')
    <div class="layout-px-spacing">
        <div class="middle-content container-xxl p-0">

            <!-- Header -->
            <div class="page-meta">
                <nav class="breadcrumb-style-one" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tags</li>
                    </ol>
                </nav>
            </div>

            <div class="row layout-top-spacing">
                <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                    <div class="widget-content widget-content-area br-8">

                        <!-- Top Bar -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h5 class="mb-2">All Tags</h5>
                                <p class="text-muted mb-0">Manage your tags</p>
                            </div>
                            <a href="{{ route('admin.tags.create') }}" class="btn btn-primary _effect--ripple waves-effect waves-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-circle">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="8" x2="12" y2="16"></line>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                                Add New Tag
                            </a>
                        </div>

                        <!-- Filters & Actions -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select id="bulk-action" class="form-select">
                                    <option value="">Bulk Actions</option>
                                    <option value="delete">Delete Selected</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button onclick="applyBulkAction()" class="btn btn-secondary w-100">Apply</button>
                            </div>
                            <div class="col-md-3">
                                <select id="status-filter" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="1">Published</option>
                                    <option value="0">Draft</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" id="search-input" class="form-control" placeholder="Search tags...">
                            </div>
                        </div>

                        <!-- Table -->
                        <table id="tags-table" class="table dt-table-hover" style="width:100%">
                            <thead>
                            <tr>
                                <th class="checkbox-column">
                                    <label class="new-control new-checkbox checkbox-primary">
                                        <input type="checkbox" class="new-control-input" id="select-all">
                                        <span class="new-control-indicator"></span>
                                    </label>
                                </th>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Posts</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th class="no-content">Actions</th>
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
    <script>
        // Cork-style SweetAlert Toast
        function showToast(type, message) {
            const toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            toast.fire({
                icon: type,
                title: message
            });
        }

        // Show success message from session
        @if(session('success'))
        showToast('success', '{{ session('success') }}');
        @endif

        @if(session('error'))
        showToast('error', '{{ session('error') }}');
        @endif

        let table;
        let selectedIds = [];

        $(document).ready(function() {
            table = $('#tags-table').DataTable({
                processing: false,  // Disable default processing indicator
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.tags.index') }}',
                    type: 'GET',
                    data: function(d) {
                        d.status = $('#status-filter').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.error('DataTables Error:', xhr, error, thrown);
                        showToast('error', 'Failed to load data');
                    }
                },
                columns: [
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `
                        <label class="new-control new-checkbox checkbox-primary">
                            <input type="checkbox" class="new-control-input row-checkbox" value="${data}">
                            <span class="new-control-indicator"></span>
                        </label>
                    `;
                        }
                    },
                    { data: 'id', name: 'id' },
                    {
                        data: null,
                        name: 'name_en',
                        render: function(data, type, row) {
                            return row.name_en || row.name_bn || 'N/A';
                        }
                    },
                    {
                        data: 'posts_count',
                        name: 'posts_count',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<span class="badge badge-light-primary">${data || 0}</span>`;
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function(data) {
                            return data == 1
                                ? '<span class="badge badge-light-success">Published</span>'
                                : '<span class="badge badge-light-secondary">Draft</span>';
                        }
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        render: function(data) {
                            return moment(data).format('MMM DD, YYYY');
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                        <div class="action-btns d-flex gap-2 justify-content-center">
                            <a href="/admin/tags/${row.id}/edit" class="btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                                </svg>
                            </a>
                            <button onclick="deleteTag(${row.id})" class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" title="Delete">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </div>
                    `;
                        }
                    }
                ],
                dom: "<'dt--top-section'<'row'<'col-12 col-sm-6 d-flex justify-content-sm-start justify-content-center'l><'col-12 col-sm-6 d-flex justify-content-sm-end justify-content-center mt-sm-0 mt-3'>>>" +
                    "<'table-responsive'tr>" +
                    "<'dt--bottom-section d-sm-flex justify-content-sm-between text-center'<'dt--pages-count mb-sm-0 mb-3'i><'dt--pagination'p>>",
                oLanguage: {
                    oPaginate: {
                        sPrevious: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>',
                        sNext: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>'
                    },
                    sInfo: "Showing page _PAGE_ of _PAGES_",
                    sSearch: '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                    sSearchPlaceholder: "Search...",
                    sLengthMenu: "Results :  _MENU_"
                },
                lengthMenu: [7, 10, 20, 50],
                pageLength: 10,
                drawCallback: function() {
                    feather.replace();
                    $('[data-bs-toggle="tooltip"]').tooltip();
                }
            });

            // Custom search
            $('#search-input').on('keyup', function() {
                table.search(this.value).draw();
            });

            // Status filter
            $('#status-filter').on('change', function() {
                table.ajax.reload();
            });

            // Select all
            $('#select-all').on('change', function() {
                $('.row-checkbox').prop('checked', this.checked);
                updateSelectedIds();
            });

            // Row checkbox
            $(document).on('change', '.row-checkbox', function() {
                updateSelectedIds();
            });
        });

        function updateSelectedIds() {
            selectedIds = [];
            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });
        }

        function applyBulkAction() {
            const action = $('#bulk-action').val();

            if (!action) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Action Selected',
                    text: 'Please select a bulk action',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false
                });
                return;
            }

            if (selectedIds.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Selection',
                    text: 'Please select at least one tag',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-warning'
                    },
                    buttonsStyling: false
                });
                return;
            }

            if (action === 'delete') {
                bulkDelete();
            }
        }

        function bulkDelete() {
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete ${selectedIds.length} selected tag(s)?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-dark'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route('admin.tags.bulk-delete') }}',
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: { ids: selectedIds },
                        success: function(response) {
                            showToast('success', response.message);
                            table.ajax.reload();
                            $('#select-all').prop('checked', false);
                            selectedIds = [];
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete tags',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }

        function deleteTag(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                customClass: {
                    confirmButton: 'btn btn-danger',
                    cancelButton: 'btn btn-outline-dark'
                },
                buttonsStyling: false,
                padding: '2em'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/admin/tags/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            showToast('success', response.message);
                            table.ajax.reload();
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: xhr.responseJSON?.message || 'Failed to delete tag',
                                confirmButtonText: 'OK',
                                customClass: {
                                    confirmButton: 'btn btn-danger'
                                },
                                buttonsStyling: false
                            });
                        }
                    });
                }
            });
        }
    </script>
@endsection
