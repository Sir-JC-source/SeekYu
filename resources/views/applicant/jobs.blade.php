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
                            <h5 class="card-title">{{ $job->title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">{{ $job->position }}</h6>
                            <p class="card-text flex-grow-1">{{ Str::limit($job->description, 100) }}</p>
                            <div class="mt-auto">
                                <p class="mb-2"><strong>Type:</strong> {{ $job->type_of_employment }}</p>
                                <p class="mb-3"><strong>Location:</strong> {{ $job->location }}</p>
                                <p class="text-muted small">Posted: {{ $job->created_at->format('M d, Y') }}</p>
                                <a href="#" class="btn btn-primary w-100">View Details</a>
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
</div>
@endsection
