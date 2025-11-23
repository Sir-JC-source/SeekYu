@extends('Layouts.vuexy')

@section('content')
<div class="container mt-3">
    <h2>Guard Requests</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Client Name</th>
                <th>Number of Guards</th>
                <th>Details</th>
                <th>Status</th>
                <th>Requested At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
            <tr>
                <td>{{ $request->client->full_name ?? 'Unknown' }}</td>
                <td>{{ $request->number_of_guards }}</td>
                <td>{{ $request->request_details }}</td>
                <td>{{ ucfirst($request->status) }}</td>
                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    @if($request->status === 'pending')
                    <form action="{{ route('guard-requests.updateStatus', $request->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('guard-requests.updateStatus', $request->id) }}" method="POST" class="d-inline ms-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                    @else
                        <span>No actions</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6">No guard requests found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
