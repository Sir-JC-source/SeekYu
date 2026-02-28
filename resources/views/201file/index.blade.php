@extends('layouts.vuexy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>201 File Management</h4>
                </div>
                <div class="card-body">
                    <p>Welcome to the 201 File Management System.</p>
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Applicants</h5>
                                    <p class="card-text">View and manage applicant records</p>
                                    <a href="{{ route('201file.applicants') }}" class="btn btn-light">View Applicants</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Employees</h5>
                                    <p class="card-text">View and manage employee records</p>
                                    <a href="{{ route('201file.employees') }}" class="btn btn-light">View Employees</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h5 class="card-title">My 201 File</h5>
                                    <p class="card-text">View and update your personal 201 file</p>
                                    <a href="{{ route('my201file.show') }}" class="btn btn-light">View My File</a>
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
