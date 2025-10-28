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
@endsection


@push('page-scripts')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script>
$(function () {

    // Employees Table
    var employees_table = $('#employees-table');
    if (employees_table.length) {
        employees_table.DataTable({
            ajax: {
                url: '{{ route("user-management.json") }}',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.log('AJAX Error:', xhr, error, code);
                    alert('Error loading data: ' + xhr.responseText);
                }
            },
            columns: [
                { data: 'employee_no' },
                { data: 'login_id' },
                { data: 'fullname' },
                { data: 'user_type' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'last_login' },
                { data: 'action', orderable: false, searchable: false, render: function(data) { return data; } }
            ],
            dom: 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10
        });
    }

    // Non-Employees Table
    var non_employees_table = $('#non-employees-table');
    if (non_employees_table.length) {
        non_employees_table.DataTable({
            ajax: {
                url: '{{ route("user-management.json.non-employees") }}',
                type: 'GET',
                error: function(xhr, error, code) {
                    console.log('AJAX Error:', xhr, error, code);
                    alert('Error loading data: ' + xhr.responseText);
                }
            },
            columns: [
                { data: 'login_id' },
                { data: 'fullname' },
                { data: 'user_type' },
                { data: 'created_at' },
                { data: 'last_login' },
                { data: 'status' },
                { data: 'email_verified_at' },
                { data: 'action', orderable: false, searchable: false, render: function(data) { return data; } }
            ],
            dom: 't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
            responsive: true,
            processing: true,
            serverSide: true,
            pageLength: 10
        });
    }
});

// Reusable confirmation alert
const swalConfirm = (options) => {
    return Swal.fire({
        title: options.title || 'Are you sure?',
        text: options.text || '',
        icon: options.icon || 'warning',
        showCancelButton: false,
        confirmButtonText: options.confirmButtonText || 'Yes',
        cancelButtonText: options.cancelButtonText || 'No',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        buttonsStyling: true,
        allowOutsideClick: false
    });
};

// Delete user
function deleteUser(deleteUrl) {
    swalConfirm({
        title: 'Are you sure?',
        text: "You won't be able to revert this!"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: deleteUrl,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    Swal.fire('Deleted!', 'User has been deleted.', 'success');
                    $('#employees-table').DataTable().ajax.reload();
                    $('#non-employees-table').DataTable().ajax.reload();
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to delete user.', 'error');
                }
            });
        }
    });
}

// Reset password
function resetPassword(userId) {
    swalConfirm({
        title: 'Are you sure?',
        text: "This will generate a new password and send it to the user's email."
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("user-management.reset-password", ":id") }}'.replace(':id', userId),
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success');
                    $('#employees-table').DataTable().ajax.reload();
                    $('#non-employees-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to reset password.', 'error');
                }
            });
        }
    });
}

// Deactivate user
function deactivateUser(userId) {
    swalConfirm({
        title: 'Are you sure?',
        text: "This will deactivate the user account."
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '{{ route("user-management.deactivate", ":id") }}'.replace(':id', userId),
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    Swal.fire('Success!', response.message, 'success');
                    $('#employees-table').DataTable().ajax.reload();
                    $('#non-employees-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire('Error!', xhr.responseJSON?.message || 'Failed to deactivate user.', 'error');
                }
            });
        }
    });
}
</script>
@endpush
