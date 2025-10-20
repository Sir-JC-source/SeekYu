@extends('Layouts.vuexy')

@section('title', 'Add Admin Account')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Add Admin Account</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered text-center" id="admin-employees-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->employee_number }}</td>
                    <td>{{ $admin->full_name }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $admin->position }}</span>
                    </td>
                    <td>
                        @php
                            $imagePath = $admin->employee_image ? asset('storage/' . $admin->employee_image) : asset('assets/default-avatar.png');
                        @endphp
                        <img src="{{ $imagePath }}" alt="Employee Image" style="width:60px; height:60px; object-fit:cover;">
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="actionDropdown{{ $admin->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $admin->id }}">
                                <li>
                                    <button class="dropdown-item create-admin-btn" 
                                            data-id="{{ $admin->id }}"
                                            data-employee-number="{{ $admin->employee_number }}"
                                            data-full-name="{{ $admin->full_name }}">
                                        <i class="ti ti-user-plus"></i> Create Admin Account
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="createAdminModal" tabindex="-1" aria-labelledby="createAdminModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.store') }}" method="POST" id="createAdminForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAdminModalLabel">Create Admin Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="employee_id" id="modal_employee_id">

                    <div class="mb-3">
                        <label for="modal_employee_number" class="form-label">Employee No.</label>
                        <input type="text" class="form-control" id="modal_employee_number" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="modal_full_name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="modal_full_name" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="modal_email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control" name="email" id="modal_email" required placeholder="Enter email to send login credentials">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create Account</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Toast Notifications -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <!-- Success Toast -->
    <div id="successToast" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Admin account created successfully. Credentials sent via email!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>

    <!-- Error Toast -->
    <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Failed to create admin account!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

@endsection

@push('page-scripts')
<script>
    $(document).ready(function() {
        $('#admin-employees-table').DataTable({
            "order": [[1, "asc"]]
        });

        // Open modal with employee info
        $('.create-admin-btn').click(function() {
            const id = $(this).data('id');
            const employeeNumber = $(this).data('employee-number');
            const fullName = $(this).data('full-name');

            $('#modal_employee_id').val(id);
            $('#modal_employee_number').val(employeeNumber);
            $('#modal_full_name').val(fullName);
            $('#modal_email').val('');

            $('#createAdminModal').modal('show');
        });

        // Show toast notifications based on session
        @if(session('success'))
            var toastEl = document.getElementById('successToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        @endif

        @if(session('error'))
            var toastEl = document.getElementById('errorToast');
            var toast = new bootstrap.Toast(toastEl);
            toast.show();
        @endif
    });
</script>
@endpush
