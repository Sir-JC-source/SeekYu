@extends('layouts.vuexy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Employee 201 File</h4>
                    <a href="{{ route('201file.employees') }}" class="btn btn-secondary">Back to Employees</a>
                </div>
                <div class="card-body">
                    @if($employee)
                    <form action="{{ route('201file.update-employee', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h5>Personal Information</h5>
                                <div class="mb-3">
                                    <label class="form-label">Employee Number</label>
                                    <input type="text" class="form-control" value="{{ $employee->employee_number }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" value="{{ $employee->full_name }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="text" class="form-control" value="{{ $employee->position }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Date Hired</label>
                                    <input type="date" class="form-control" value="{{ $employee->date_hired }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <input type="text" class="form-control" value="{{ $employee->status }}" readonly>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h5>Government IDs</h5>
                                <div class="mb-3">
                                    <label class="form-label">SSS Number</label>
                                    <input type="text" name="sss_number" class="form-control" value="{{ $employee->sss_number ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Pag-IBIG Number</label>
                                    <input type="text" name="pagibig_number" class="form-control" value="{{ $employee->pagibig_number ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">PhilHealth Number</label>
                                    <input type="text" name="philhealth_number" class="form-control" value="{{ $employee->philhealth_number ?? '' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Address Information</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Barangay</label>
                                    <input type="text" name="barangay" class="form-control" value="{{ $employee->barangay ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" value="{{ $employee->city ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Province</label>
                                    <input type="text" name="province" class="form-control" value="{{ $employee->province ?? '' }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Zip Code</label>
                                    <input type="text" name="zip_code" class="form-control" value="{{ $employee->zip_code ?? '' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <h5>Emergency Contact</h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Contact Name</label>
                                    <input type="text" name="emergency_contact_name" class="form-control" value="{{ $employee->emergency_contact_name ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Contact Number</label>
                                    <input type="text" name="emergency_contact_number" class="form-control" value="{{ $employee->emergency_contact_number ?? '' }}">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Relationship</label>
                                    <input type="text" name="emergency_contact_relationship" class="form-control" value="{{ $employee->emergency_contact_relationship ?? '' }}">
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">Update 201 File</button>
                        </div>
                    </form>
                    @else
                    <p class="text-center">Employee not found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
