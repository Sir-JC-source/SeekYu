@extends('Layouts.vuexy')

@section('title', 'Create Job Posting')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Create Job Posting</h5>
    </div>
    <div class="card-body">

        <form action="{{ route('job_postings.store') }}" method="POST">
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

            <button type="submit" class="btn btn-primary">Create Job Posting</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    @if(session('success'))
        Toastify({
            text: "{{ session('success') }}",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#4caf50",
            close: true
        }).showToast();
    @endif
</script>
@endsection
