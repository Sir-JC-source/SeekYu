@extends('Layouts.vuexy')

@section('title', 'All Applications')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">All Applications</h5>
                    <div>
                        <a href="{{ route('applications.rejected') }}" class="btn btn-outline-danger me-2">Rejected</a>
                        <a href="{{ route('applications.shortlist') }}" class="btn btn-outline-success">Shortlisted</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($applications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Applicant</th>
                                        <th>Job Position</th>
                                        <th>Applied Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($applications as $application)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <h6 class="mb-0">{{ $application->user->fullname }}</h6>
                                                        <small class="text-muted">{{ $application->user->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $application->jobPosting->title }}</h6>
                                                    <small class="text-muted">{{ $application->jobPosting->position }}</small>
                                                </div>
                                            </td>
                                            <td>{{ $application->applied_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($application->status === 'rejected')
                                                    <span class="badge bg-danger">Rejected</span>
                                                @elseif($application->status === 'shortlisted')
                                                    <span class="badge bg-success">Shortlisted</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('job_postings.applicant-credentials', $application->id) }}" class="btn btn-sm btn-outline-primary">View Credentials</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted mb-3"></i>
                            <h4>No Applications Found</h4>
                            <p class="text-muted">There are no job applications at the moment.</p>
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
function showGameScores(applicationId) {
    fetch(`{{ route("applications.game-scores", ":id") }}`.replace(':id', applicationId), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            let content = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Game</th><th>Score</th><th>Percentage</th><th>Time</th></tr></thead><tbody>';
            data.scores.forEach(score => {
                content += `<tr><td>${score.game_type.replace('_', ' ').toUpperCase()}</td><td>${score.score}/${score.total}</td><td>${score.percentage}%</td><td>${score.time_taken}</td></tr>`;
            });
            content += '</tbody></table></div>';

            Swal.fire({
                title: 'Assessment Scores',
                html: content,
                width: 600,
                showCloseButton: true,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Error', 'Unable to load assessment scores.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'An error occurred.', 'error');
    });
}
</script>
@endpush
