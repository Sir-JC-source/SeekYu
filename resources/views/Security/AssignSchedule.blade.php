@extends('layouts.vuexy')

@section('title', 'Assign Schedule')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Assign Schedule</h5>
                </div>
                <div class="card-body">
                    @if($guards->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee Number</th>
                                        <th>Full Name</th>
                                        <th>Position</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guards as $guard)
                                        <tr>
                                            <td>{{ $guard->employee_number }}</td>
                                            <td>{{ $guard->full_name }}</td>
                                            <td>{{ $guard->position }}</td>
                                            <td>
                                                <span class="badge bg-{{ $guard->status === 'Active' ? 'success' : 'secondary' }}">
                                                    {{ $guard->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('guard-scheduling.assign.guard', $guard->id) }}" class="btn btn-sm btn-primary">
                                                    Assign Schedule
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <strong>No Guards Found:</strong> There are no active security guards or head guards available for scheduling.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
