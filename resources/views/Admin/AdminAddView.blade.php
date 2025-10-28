@extends('Layouts.vuexy')

@section('title', 'Admin Accounts')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Admin Accounts</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered text-center" id="admin-accounts-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>User Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                <tr>
                    <td>{{ $admin->employee ? $admin->employee->employee_number : 'N/A' }}</td>
                    <td>{{ $admin->fullname }}</td>
                    <td>
                        <span class="badge bg-primary">{{ $admin->role }}</span>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="actionDropdown{{ $admin->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $admin->id }}">
                                <li>
                                    <button class="dropdown-item">
                                        <i class="ti ti-eye"></i> View Details
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



@endsection

@push('page-scripts')
<script>
    $(document).ready(function() {
        $('#admin-accounts-table').DataTable({
            "order": [[1, "asc"]]
        });
    });
</script>
@endpush
