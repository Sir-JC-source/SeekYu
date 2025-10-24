@extends('Layouts.vuexy')

@section('title', 'Job Postings List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Job Postings</h5>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createJobModal">Create New Job</button>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped table-bordered text-center" id="job-postings-table">
            <thead>
                <tr>
                    <th>Job Post ID</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Type of Employment</th>
                    <th>Location</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobPostings as $job)
                    <tr>
                        <td>{{ $job->job_post_id }}</td>
                        <td>{{ $job->title }}</td>
                        <td>{{ $job->position }}</td>
                        <td>{{ $job->type_of_employment }}</td>
                        <td>{{ $job->location }}</td>
                        <td>{{ $job->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No job postings found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

{{-- Create Job Modal --}}
<div class="modal fade" id="createJobModal" tabindex="-1" aria-labelledby="createJobModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createJobModalLabel">Create Job Posting</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createJobForm" action="{{ route('job_postings.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="job_post_id" class="form-label">Job Post ID</label>
                        <input type="text" class="form-control" id="job_post_id" value="{{ 'JOB-' . strtoupper(uniqid()) }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select name="position" id="position" class="form-select" required>
                            <option value="">Select Position</option>
                            <option value="Security Guard" {{ old('position') == 'Security Guard' ? 'selected' : '' }}>Security Guard</option>
                            <option value="Head Guard" {{ old('position') == 'Head Guard' ? 'selected' : '' }}>Head Guard</option>
                        </select>
                        @error('position')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type_of_employment" class="form-label">Type of Employment</label>
                        <select name="type_of_employment" id="type_of_employment" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Contractual" {{ old('type_of_employment') == 'Contractual' ? 'selected' : '' }}>Contractual</option>
                            <option value="Full-Time" {{ old('type_of_employment') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                        </select>
                        @error('type_of_employment')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required>
                        @error('location')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="createJobForm" class="btn btn-primary">Create Job Posting</button>
            </div>
        </div>
    </div>
</div>

@push('page-scripts')
<script>
$(document).ready(function() {
    $('#job-postings-table').DataTable({
        "order": [[0, "asc"]]
    });

    // Handle form submission via AJAX
    $('#createJobForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#createJobModal').modal('hide');
                Toastify({
                    text: "Job posting created successfully!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4caf50",
                    close: true
                }).showToast();
                // Reload the page to show the new job posting
                setTimeout(function() {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessage = "An error occurred.";
                if (errors) {
                    errorMessage = Object.values(errors).flat().join('\n');
                }
                Toastify({
                    text: errorMessage,
                    duration: 5000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#f44336",
                    close: true
                }).showToast();
            }
        });
    });
});
</script>
@endpush
