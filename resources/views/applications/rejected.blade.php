@extends('Layouts.vuexy')

@section('title', 'Rejected Applicants')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Rejected Applicants</h5>
                    <a href="{{ route('applications.list') }}" class="btn btn-secondary">Back to Applications</a>
                </div>
                <div class="card-body">
                    @if($applications->count() > 0)
                        <table class="table table-striped table-bordered" id="rejected-table">
                            <thead>
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Email</th>
                                    <th>Job Position</th>
                                    <th>Applied At</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($applications as $application)
                                    <tr>
                                        <td>{{ $application->user->fullname }}</td>
                                        <td>{{ $application->user->email }}</td>
                                        <td>{{ $application->jobPosting->title }} - {{ $application->jobPosting->position }}</td>
                                        <td>{{ $application->applied_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <span class="badge bg-danger">{{ ucfirst($application->status) }}</span>
                                        </td>
                                        <td>
                                            <a href="{{ route('job_postings.applicant-credentials', $application->id) }}" class="btn btn-sm btn-primary">View Credentials</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted mb-3"></i>
                            <h4>No Rejected Applicants</h4>
                            <p class="text-muted">There are no rejected applicants at this time.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
$(document).ready(function() {
    $('#rejected-table').DataTable();
});
</script>
@endpush
