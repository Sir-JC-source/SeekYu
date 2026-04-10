@extends('Layouts.vuexy')

@section('title', 'Job Applications')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Applications for: {{ $job->title }}</h5>
                    <a href="{{ route('job_postings.list') }}" class="btn btn-secondary">Back to Job Postings</a>
                </div>
                <div class="card-body">
                    @if($job->applications->count() > 0)
                        <table class="table table-striped table-bordered" id="applications-table">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Email</th>
                                    <th>Applied At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($job->applications as $application)
                                    <tr>
                                        <td>{{ $application->user->fullname }}</td>
                                        <td>{{ $application->user->email }}</td>
                                        <td>{{ $application->applied_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('job_postings.applicant-credentials', $application->id) }}" class="btn btn-sm btn-primary me-1">View Credentials</a>
                                            @if($application->status === 'pending')
                                                <button class="btn btn-sm btn-danger reject-btn" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $application->id }}" data-id="{{ $application->id }}">Reject</button>
                                                <button class="btn btn-sm btn-success shortlist-btn" onclick="shortlistApplication({{ $application->id }})">Shortlist</button>
                                            @elseif($application->status === 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @elseif($application->status === 'shortlisted')
                                                <span class="badge bg-success">Shortlisted</span>
                                            @endif
                                        </td>

                                        {{-- Reject Modal --}}
                                        <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Application</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Reject application for <strong>{{ $application->user->fullname }}</strong>?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Rejection Notes (Required)</label>
                                                            <textarea class="form-control" id="rejectNotes{{ $application->id }}" rows="4" placeholder="Enter reason for rejection..." required maxlength="1000"></textarea>
                                                            <div class="form-text">Notes will be visible to applicant.</div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger" onclick="rejectWithNotes({{ $application->id }})">Reject & Send Notes</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted mb-3"></i>
                            <h4>No Applications Yet</h4>
                            <p class="text-muted">This job posting has not received any applications.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#applications-table').DataTable();

    // Shortlist application
    window.shortlistApplication = function(applicationId) {
        Swal.fire({
            title: 'Shortlist Applicant?',
            text: 'This will notify the applicant.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Shortlist'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('job_postings.applications.shortlist', ':id') }}`.replace(':id', applicationId),
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function() {
                        location.reload();
                    }
                });
            }
        });
    }

    // Reject with notes
    window.rejectWithNotes = function(applicationId) {
        var notes = $('#rejectNotes' + applicationId).val();
        if (!notes.trim()) {
            Swal.fire('Error', 'Rejection notes required.', 'error');
            return;
        }

        $.ajax({
            url: `{{ route('job_postings.applications.reject', ':id') }}`.replace(':id', applicationId),
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                rejection_notes: notes
            },
            success: function() {
                $('#rejectModal' + applicationId).modal('hide');
                Swal.fire('Success', 'Application rejected with notes sent.', 'success');
                location.reload();
            },
            error: function() {
                Swal.fire('Error', 'Failed to reject application.', 'error');
            }
        });
    }
});
</script>
@endpush
