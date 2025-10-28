@extends('Layouts.vuexy')

@section('title', 'Attendance Information')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Attendance Information</h4>
                </div>
                <div class="card-body">
                    <p>Attendance tracking feature coming soon.</p>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Shift In</label>
                                <p class="form-control-plaintext">{{ $employee->shift_in ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->shift_in)->format('h:i A') : 'Not Set' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Current Shift Out</label>
                                <p class="form-control-plaintext">{{ $employee->shift_out ? \Carbon\Carbon::createFromFormat('H:i:s', $employee->shift_out)->format('h:i A') : 'Not Set' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
