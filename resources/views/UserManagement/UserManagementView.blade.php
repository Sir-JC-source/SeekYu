@extends('Layouts.vuexy')

@section('title', 'User Management')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="ti ti-users-group me-2"></i>User Management
                    </h4>
                    <small class="text-muted">Manage all system users</small>
                </div>

                @push('page-styles')
                <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
                @endpush

                {{-- Success Notification --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-checks me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Error Notification --}}
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Nav tabs -->
                <div class="card-body">
                    <ul class="nav nav-pills nav-fill mb-4" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active d-flex align-items-center justify-content-center py-3" role="tab" data-bs-toggle="tab" data-bs-target="#employees-tab" aria-controls="employees-tab" aria-selected="true">
                                <i class="ti ti-users me-2 fs-4"></i>
                                <div class="d-flex flex-column align-items-start">
                                    <span class="fw-semibold">Employees</span>
                                    <small class="text-muted">Staff & Admin Users</small>
                                </div>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link d-flex align-items-center justify-content-center py-3" role="tab" data-bs-toggle="tab" data-bs-target="#non-employees-tab" aria-controls="non-employees-tab" aria-selected="false">
                                <i class="ti ti-user-check me-2 fs-4"></i>
                                <div class="d-flex flex-column align-items-start">
                                    <span class="fw-semibold">Non-Employees</span>
                                    <small class="text-muted">Applicants & External Users</small>
                                </div>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <!-- Employees Tab -->
                        <div class="tab-pane fade show active" id="employees-tab" role="tabpanel">
                            <div class="card shadow-none border">
                                <div class="card-header d-flex justify-content-between align-items-center bg-light-primary">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <i class="ti ti-users me-2"></i>Employee Accounts
                                        </h6>
                                        <small class="text-muted">Manage staff and administrative user accounts</small>
                                    </div>
                                    <div class="badge bg-primary rounded-pill">
                                        <i class="ti ti-users me-1"></i>Active Staff
                                    </div>
                                </div>
                                <div class="card-datatable table-responsive">
                                    <table class="datatables-basic table table-hover" id="employees-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-center">Employee No.</th>
                                                <th>Login ID</th>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th class="text-center">Status</th>
                                                <th>Created At</th>
                                                <th>Last Login</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Non-Employees Tab -->
                        <div class="tab-pane fade" id="non-employees-tab" role="tabpanel">
                            <div class="card shadow-none border">
                                <div class="card-header d-flex justify-content-between align-items-center bg-light-info">
                                    <div>
                                        <h6 class="card-title mb-1">
                                            <i class="ti ti-user-check me-1"></i>Non-Employee Accounts
                                        </h6>
                                        <small class="text-muted">Manage applicant and external user accounts</small>
                                    </div>
                                    <div class="badge bg-info rounded-pill">
                                        <i class="ti ti-user-check me-1"></i>External Users
                                    </div>
                                </div>
                                <div class="card-datatable table-responsive">
                                    <table class="datatables-basic table table-hover" id="non-employees-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Login ID</th>
                                                <th>Full Name</th>
                                                <th>User Type</th>
                                                <th>Created At</th>
                                                <th>Last Login</th>
                                                <th class="text-center">Status</th>
                                                <th>Email Verified</th>
                                                <th class="text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('UserManagement._user_view_modal')
@endsection

@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script>
$(function () {
    // Employees Table DataTable initialization
    $('#employees-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("user-management.json") }}',
            type: 'GET',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
            }
        },
        columns: [
            { data: 'employee_no', className: 'text-center' },
            { data: 'login_id' },
            { data: 'fullname' },
            { data: 'user_type' },
            { data: 'status', className: 'text-center' },
            { data: 'created_at' },
            { data: 'last_login' },
            { data: 'action', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[1, 'asc']],
        lengthChange: true,
        pageLength: 10
    });

    // Non-Employees Table DataTable initialization
    $('#non-employees-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("user-management.json.non-employees") }}',
            type: 'GET',
            data: function(d) {
                d._token = '{{ csrf_token() }}';
            }
        },
        columns: [
            { data: 'login_id' },
            { data: 'fullname' },
            { data: 'user_type' },
            { data: 'created_at' },
            { data: 'last_login' },
            { data: 'status', className: 'text-center' },
            { data: 'email_verified_at' },
            { data: 'action', className: 'text-center', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        lengthChange: true,
        pageLength: 10
    });

    // Handle View User button click
    $('body').on('click', '.view-user-btn', function() {
        let userId = $(this).data('user-id');

        // Show loading state in modal
        $('#userViewModal .modal-body').html('<p>Loading...</p>');
        $('#userViewModal').modal('show');

        $.ajax({
            url: '/user-management/user-info/' + userId,
            method: 'GET',
            success: function(data) {
                let userInfoHtml = '<ul class="list-group">';
                userInfoHtml += '<li class="list-group-item"><strong>Login ID:</strong> ' + data.login_id + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>Full Name:</strong> ' + data.fullname + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>User Type:</strong> ' + data.role + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>Status:</strong> ' + data.status + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>Email:</strong> ' + data.email + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>Created At:</strong> ' + data.created_at + '</li>';
                userInfoHtml += '<li class="list-group-item"><strong>Last Login:</strong> ' + data.last_login + '</li>';
                userInfoHtml += '</ul>';

                $('#userViewModal .modal-body').html(userInfoHtml);
            },
            error: function() {
                $('#userViewModal .modal-body').html('<p class="text-danger">Failed to load user information.</p>');
            }
        });
    });
});
</script>
@endpush
