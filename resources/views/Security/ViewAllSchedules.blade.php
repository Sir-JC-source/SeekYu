@extends('layouts.vuexy')

@section('title', 'View All Security Guard Schedules')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Security Guard Schedules</h5>
                   
                </div>
                <div class="card-body">
                    @if($guards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Guard Name</th>
                                        <th>Employee Number</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guards as $guard)
                                        <tr>
                                            <td>{{ $guard->full_name }}</td>
                                            <td>{{ $guard->employee_number }}</td>
                                            <td>
                                                <a href="{{ route('guard-scheduling.assign.guard', $guard->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bx bx-calendar me-1"></i> View Schedule
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bx bx-calendar-x display-1 text-muted"></i>
                            <h4 class="mt-3">No Security Guards Found</h4>
                            <p class="text-muted">There are currently no active security guards in the system.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
