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
                            <div class="btn-group" role="group">
                                <a href="{{ route('job_postings.applications', $job->id) }}" class="btn btn-sm btn-info">View Applications</a>
                                <div class="form-check form-switch custom-toggle d-inline-block ms-2">
                                    <input class="form-check-input toggle-status"
                                           type="checkbox"
                                           id="toggle-{{ $job->id }}"
                                           {{ $job->status === 'active' ? 'checked' : '' }}
                                           data-job-id="{{ $job->id }}">
                                    <label for="toggle-{{ $job->id }}"></label>
                                </div>
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
                            <option value="HR Officer" {{ old('position') == 'HR Officer' ? 'selected' : '' }}>HR Officer</option>
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

@push('page-styles')
<style>
/* ✅ Custom Toggle Switch with Smooth Slide */
.custom-toggle {
    position: relative;
    display: inline-block;
    width: 52px;
    height: 28px;
}

.custom-toggle .form-check-input {
    opacity: 0;
    width: 0;
    height: 0;
}

.custom-toggle label {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #d9534f; /* red by default */
    transition: all 0.4s ease;
    border-radius: 34px;
    box-shadow: 0 0 3px rgba(0,0,0,0.2);
}

.custom-toggle label::before {
    position: absolute;
    content: "";
    height: 22px;
    width: 22px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    border-radius: 50%;
    transition: all 0.4s ease;
}

/* When checked (ON) */
.custom-toggle .form-check-input:checked + label {
    background-color: #28a745; /* green when active */
    box-shadow: 0 0 10px rgba(40, 167, 69, 0.5);
}

/* Slide knob */
.custom-toggle .form-check-input:checked + label::before {
    transform: translateX(24px);
}

/* Hover glow */
.custom-toggle label:hover {
    box-shadow: 0 0 8px rgba(0,0,0,0.3);
}
</style>
@endpush

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#job-postings-table').DataTable({
        "order": [[5, "desc"], [6, "desc"]]
    });

    // Handle toggle status
    $(document).on('change', '.toggle-status', function() {
        var toggle = $(this);
        var jobId = toggle.data('job-id');
        var isChecked = toggle.is(':checked');
        var statusText = isChecked ? 'activate' : 'deactivate';

        Swal.fire({
            title: 'Are you sure?',
            text: `Do you want to ${statusText} this job posting?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: `Yes, ${statusText} it!`
        }).then((result) => {
            if (result.isConfirmed) {
                toggle.prop('disabled', true);
                $.ajax({
                    url: '{{ route("job_postings.toggle-status", ":id") }}'.replace(':id', jobId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'POST'
                    },
                    success: function(response) {
                        var row = toggle.closest('tr');
                        var badge = row.find('.badge');

                        if (isChecked) {
                            badge.removeClass('bg-secondary').addClass('bg-success').text('Active');
                        } else {
                            badge.removeClass('bg-success').addClass('bg-secondary').text('Inactive');
                        }

                        toggle.prop('disabled', false);

                        Swal.fire({
                            title: 'Success!',
                            text: `Job posting has been ${statusText}d.`,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong while updating status.',
                            icon: 'error'
                        });
                        toggle.prop('checked', !isChecked).prop('disabled', false);
                    }
                });
            } else {
                toggle.prop('checked', !isChecked);
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
                Toastify({
                    text: "Job posting created successfully!",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "#4caf50",
                    close: true
                }).showToast();

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
