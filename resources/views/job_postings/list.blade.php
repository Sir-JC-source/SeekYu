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
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
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
                        <td>
                            <span class="badge {{ $job->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($job->status) }}
                            </span>
                        </td>
                        <td>{{ $job->created_at->format('Y-m-d') }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Actions
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('job_postings.applications', $job->id) }}">View Applications</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        @if($job->status === 'active')
                                            <a class="dropdown-item btn-toggle-status" href="javascript:void(0);" 
                                               data-job-id="{{ $job->id }}" 
                                               data-status="deactivate">
                                               <i class="ti ti-power me-1"></i> Deactivate
                                            </a>
                                        @else
                                            <a class="dropdown-item btn-toggle-status text-success" href="javascript:void(0);" 
                                               data-job-id="{{ $job->id }}" 
                                               data-status="activate">
                                               <i class="ti ti-check me-1"></i> Activate
                                            </a>
                                        @endif
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteJob({{ $job->id }})">Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center">No job postings found.</td>
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
                    </div>
                    <div class="mb-3">
                        <label for="position" class="form-label">Position</label>
                        <select name="position" id="position" class="form-select" required>
                            <option value="">Select Position</option>
                            <option value="Security Guard">Security Guard</option>
                            <option value="Head Guard">Head Guard</option>
                            <option value="HR Officer">HR Officer</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Job Description</label>
                        <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label for="type_of_employment" class="form-label">Type of Employment</label>
                        <select name="type_of_employment" id="type_of_employment" class="form-select" required>
                            <option value="">Select Type</option>
                            <option value="Contractual">Contractual</option>
                            <option value="Full-Time">Full-Time</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="location" class="form-label">Location</label>
                        <input type="text" name="location" id="location" class="form-control" value="{{ old('location') }}" required>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var table = $('#job-postings-table').DataTable({
        "columns": [
            null, null, null, null, null, null, null, { "orderable": false }
        ],
        "order": [[6, "desc"]]
    });

    // Handle status change via Dropdown Item click
    $(document).on('click', '.btn-toggle-status', function(e) {
        e.preventDefault();
        var btn = $(this);
        var jobId = btn.data('job-id');
        var action = btn.data('status'); // 'activate' or 'deactivate'

        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${action} this job posting?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: action === 'activate' ? '#28a745' : '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, ${action} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("job_postings.toggle-status", ":id") }}'.replace(':id', jobId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Success!',
                            text: `Job posting has been ${action}d.`,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong while updating status.', 'error');
                    }
                });
            }
        });
    });

    // Handle Create Job via AJAX
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
                location.reload();
            },
            error: function(xhr) {
                Swal.fire('Error!', 'Validation failed or server error.', 'error');
            }
        });
    });
});
</script>
@endpush