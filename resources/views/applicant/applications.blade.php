@extends('Layouts.vuexy')

@section('title', 'My Applications')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Applications</h5>
                    <a href="{{ route('applicant.jobs') }}" class="btn btn-outline-primary">Browse Jobs</a>
                </div>
                <div class="card-body">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Job Title</th>
                                        <th>Position</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $application->jobPosting->title }}</h6>
                                                   
                                                </div>
                                            </td>
                                            <td>{{ $application->jobPosting->position }}</td>
                                            <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($application->status === 'rejected' && $application->rejection_notes)
                                                    <div>
                                                        <span class="badge bg-danger me-1">Rejected</span>
                                                        <button class="btn btn-sm btn-link p-0 text-danger" data-bs-toggle="modal" data-bs-target="#rejectReason{{ $application->id }}">Reason</button>
                                                    </div>
                                                    <div class="modal fade" id="rejectReason{{ $application->id }}" tabindex="-1">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title">Rejection Reason</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    {{ $application->rejection_notes }}
                                                                    @if($application->rejected_at)
                                                                        <small class="text-muted d-block mt-2">{{ $application->rejected_at->format('M d, Y H:i') }}</small>
                                                                    @endif
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif($application->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif($application->status === 'shortlisted')
                                                    <span class="badge bg-success">Shortlisted</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('applicant.credentials') }}" class="btn btn-sm btn-outline-primary">Update Credentials</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted mb-3"></i>
                            <h4>No Applications Yet</h4>
                            <p class="text-muted">You haven't applied for any jobs yet.</p>
                            <a href="{{ route('applicant.jobs') }}" class="btn btn-primary">Browse Available Jobs</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
