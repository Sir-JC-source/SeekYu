@extends('Layouts.vuexy')

@section('title', 'Archived Employees')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header">
            <h5 class="mb-0">Archived Employees</h5>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered text-center" id="archived-employee-table">
                <thead>
                    <tr>
                        <th>Employee No.</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Date Hired</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivedEmployees as $employee)
                        <tr id="employee-row-{{ $employee->id }}">
                            <td>{{ $employee->employee_number }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</td>
                            <td><span class="badge bg-secondary">Archived</span></td>
                            <td>
                                <button class="btn btn-sm btn-success restore-btn" data-id="{{ $employee->id }}">
                                    <i class="ti ti-refresh"></i> Restore
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
$(document).ready(function() {
    $('#archived-employee-table').DataTable({
        "order": [[1, "asc"]]
    });

    // AJAX Restore with SweetAlert
    $(document).on('click', '.restore-btn', function() {
        var employeeId = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This employee will be restored!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Restore',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/employee/restore/' + employeeId,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire(
                            'Restored!',
                            response.message,
                            'success'
                        ).then(() => {
                            // Redirect to Employee List after restore
                            window.location.href = '{{ route("employee.list") }}';
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            xhr.responseJSON?.message || 'Something went wrong.',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>
@endpush
