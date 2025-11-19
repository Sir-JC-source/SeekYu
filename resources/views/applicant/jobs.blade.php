@extends('Layouts.vuexy')

@section('title', 'Job Postings')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-3">
        <div class="col-md-12">
            <form method="GET" action="{{ route('applicant.jobs') }}" class="d-flex align-items-center gap-2">
                <label for="position" class="mb-0">Filter by Position:</label>
                <select name="position" id="position" class="form-select w-auto" onchange="this.form.submit()">
                    <option value="">All</option>
                    <option value="Security Guard" {{ request('position') == 'Security Guard' ? 'selected' : '' }}>Security Guard</option>
                    <option value="Head Guard" {{ request('position') == 'Head Guard' ? 'selected' : '' }}>Head Guard</option>
                </select>
            </form>
        </div>
    </div>

    <div class="row">
        @if($jobPostings->count() > 0)
            @foreach($jobPostings as $job)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $job->title }}</h5>
                                <span class="badge {{ $job->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($job->status) }}
                                </span>
                            </div>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $job->position }}</h6>
                            <p class="card-text flex-grow-1">{{ Str::limit($job->description, 100) }}</p>
                            <div class="mt-auto">
                                <p class="mb-2"><strong>Type:</strong> {{ $job->type_of_employment }}</p>
                                <p class="mb-3"><strong>Location:</strong> {{ $job->location }}</p>
                                <p class="text-muted small">Posted: {{ $job->created_at->format('M d, Y') }}</p>
                                @if($job->applied_id)
                                    <span class="badge bg-success w-100">Application Submitted </span>
                                @elseif($job->status === 'active')
                                    <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#jobModal{{ $job->id }}">Apply Now</button>
                                @else
                                    <button class="btn btn-secondary w-100" disabled>Application Closed</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12 text-center py-5">
                <i class="ti ti-briefcase display-1 text-muted mb-3"></i>
                <h4>No Job Postings Available</h4>
                <p class="text-muted">There are currently no job postings available. Please check back later.</p>
            </div>
        @endif
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center mt-3">
        @if($jobPostings->hasPages())
            {{ $jobPostings->withQueryString()->links() }}
        @else
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item aactive"><span class="page-link">1</span></li>
                </ul>
            </nav>
        @endif
    </div>

    <!-- Job Application Modals -->
    @foreach($jobPostings as $job)
        <div class="modal fade" id="jobModal{{ $job->id }}" tabindex="-1" aria-labelledby="jobModalLabel{{ $job->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jobModalLabel{{ $job->id }}">{{ $job->title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-muted">{{ $job->position }}</h6>
                                <p><strong>Description:</strong></p>
                                <p>{{ $job->description }}</p>
                                <p><strong>Type of Employment:</strong> {{ $job->type_of_employment }}</p>
                                <p><strong>Location:</strong> {{ $job->location }}</p>
                                <p><strong>Posted:</strong> {{ $job->created_at->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="alert alert-info">
                                    <h6>Application Requirements</h6>
                                    <p>Please ensure you have completed your credentials before applying.</p>
                                    <a href="{{ route('applicant.credentials') }}" class="btn btn-sm btn-outline-primary">Update Credentials</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="applyForJob({{ $job->id }})">Proceed with Application</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

    <script>
function applyForJob(jobId) {
    if (confirm('Are you sure you want to apply for this job?')) {
        fetch(`{{ url('/applicant/jobs/apply') }}/${jobId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.redirect) {
                    // Redirect to assessment
                    window.location.href = data.redirect;
                } else {
                    alert(data.message);
                    // Close modal and reload page
                    document.querySelector(`#jobModal${jobId}`).querySelector('[data-bs-dismiss="modal"]').click();
                    location.reload();
                }
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while submitting your application.');
        });
    }
}
</script>
@endsection
