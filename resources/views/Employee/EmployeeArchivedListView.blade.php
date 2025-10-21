@extends('Layouts.vuexy')

@section('title', 'Terminated Employees')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header">
            <h5 class="mb-0">Terminated Employees</h5>
        </div>

        <div class="card-body">
            <table class="table table-striped table-bordered text-center" id="archived-employee-table">
                <thead>
                    <tr>
                        <th>Employee No.</th>
                        <th>Full Name</th>
                        <th>Position</th>
                        <th>Date Hired</th>
                        <th>Date of Termination</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($archivedEmployees as $employee)
                        <tr id="employee-row-{{ $employee->id }}">
                            <td>{{ $employee->employee_number }}</td>
                            <td>{{ $employee->full_name }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</td>
                            <td>
                                @if($employee->deleted_at)
                                    {{ \Carbon\Carbon::parse($employee->deleted_at)->format('M d, Y') }}
                                @else
                                    N/A
                                @endif
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
});
</script>
@endpush
