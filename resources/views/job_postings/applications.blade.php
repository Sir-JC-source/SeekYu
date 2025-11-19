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

                                        </td>
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
<script>
$(document).ready(function() {
    $('#applications-table').DataTable();
});
</script>
@endpush
