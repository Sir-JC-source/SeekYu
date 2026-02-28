@extends('layouts.vuexy')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>Applicant Records</h4>
                    <a href="{{ route('201file.index') }}" class="btn btn-secondary">Back to 201 File</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Applied Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($applicants as $applicant)
                                <tr>
                                    <td>{{ $applicant->id }}</td>
                                    <td>{{ $applicant->user->first_name ?? '' }} {{ $applicant->user->last_name ?? '' }}</td>
                                    <td>{{ $applicant->user->email ?? 'N/A' }}</td>
                                    <td>{{ $applicant->user->contact_no ?? 'N/A' }}</td>
                                    <td>{{ $applicant->applied_position ?? 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('201file.show-applicant', $applicant->id) }}" class="btn btn-sm btn-primary">View Details</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No applicant records found.</td>
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
