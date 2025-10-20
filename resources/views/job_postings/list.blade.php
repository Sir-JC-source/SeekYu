@extends('Layouts.vuexy')

@section('title', 'Job Postings List')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Job Postings</h5>
        <a href="{{ route('job_postings.create') }}" class="btn btn-primary">Create New Job</a>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Job Post ID</th>
                    <th>Title</th>
                    <th>Position</th>
                    <th>Employment Type</th>
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
                        <td>{{ $job->employment_type }}</td>
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
