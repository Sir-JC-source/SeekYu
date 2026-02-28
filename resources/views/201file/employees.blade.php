@extends('layouts.vuexy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Employee Records</h4>
                    <a href="{{ route('201file.index') }}" class="btn btn-secondary">Back to 201 File</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Employee No.</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Date Hired</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($employees as $employee)
                                <tr>
                                    <td>{{ $employee->employee_number }}</td>
                                    <td>{{ $employee->full_name }}</td>
                                    <td>{{ $employee->position }}</td>
                                    <td>{{ $employee->date_hired }}</td>
                                    <td>
                                        <span class="badge bg-{{ $employee->status === 'Active' ? 'success' : 'danger' }}">
                                            {{ $employee->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('201file.show-employee', $employee->id) }}" class="btn btn-sm btn-primary">View Details</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No employee records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
