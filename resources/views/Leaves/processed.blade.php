@extends('Layouts.vuexy')

@section('title', 'Processed Leaves')

@section('content')
@php
    $user = auth()->user();
    $leaveCredits = $user?->leave_credits;
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">Processed Leave Requests</h5>
            @if(!is_null($leaveCredits))
<div class="text-muted" style="font-size: 0.9rem;">
Your Leave Credits: <strong>{{ $leaveCredits }}</strong>
                <span class="badge ms-2 {{ $leaveCredits > 0 ? 'bg-success' : 'bg-danger' }}">
                    {{ $leaveCredits > 0 ? 'OK' : 'INSUFFICIENT' }}
                </span>
            </div>
            @endif
        </div>
        <a href="{{ route('leaves.processed.export-excel') }}" class="btn btn-outline-success btn-sm">Download Excel</a>
    </div>


    <div class="card-body">
        <form method="GET" action="{{ route('leaves.processed') }}" class="row g-3 mb-4">
            <div class="col-md-4">
                <label for="requestor" class="form-label">Requestor</label>
                <input type="text" class="form-control" id="requestor" name="requestor" value="{{ request('requestor') }}" placeholder="Search requestor" />
            </div>
            <div class="col-md-4">
                <label for="date_exact" class="form-label">Date Requested (Exact)</label>
                <input type="date" class="form-control" id="date_exact" name="date_exact" value="{{ request('date_exact') }}" />
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label">From</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}" />
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label">To</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}" />
            </div>
            <div class="col-12 d-flex gap-2 align-items-end">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('leaves.processed') }}" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>

        @if($leaves->whereIn('status',['Approved','Rejected'])->isEmpty())
            <div class="alert alert-info">There are no processed leave requests yet.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="processed-leave-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Requestor</th>
                            <th>Position</th>
                            <th>Type of Leave</th>
                            <th>Reason</th>
                            <th>Duration</th>
                            <th>Date Requested</th>
                            <th>Status</th>
                            <th>Processed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves->whereIn('status',['Approved','Rejected']) as $index => $leave)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $leave->requestor }}</td>
                            <td>{{ $leave->position }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td title="{{ $leave->reason }}">{{ Str::limit($leave->reason, 30) }}</td>
                            <td>
                                @if($leave->date_from && $leave->date_to)
                                    {{ \Carbon\Carbon::parse($leave->date_from)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($leave->date_to)->format('M d, Y') }}
                                @else
                                    {{ $leave->duration }}
                                @endif
                            </td>
                            <td>{{ $leave->created_at->format('F d, Y') }}</td>
                            <td>
                                @php
                                    $badgeClass = $leave->status === 'Approved' ? 'bg-success' : 'bg-danger';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $leave->status }}</span>
                            </td>
                            <td>
                                @if($leave->status === 'Approved')
                                    {{ $leave->approver?->fullname ?? '-' }}
                                @elseif($leave->status === 'Rejected')
                                    {{ $leave->rejecter?->fullname ?? '-' }}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
#processed-leave-table tbody tr:hover {
    background-color: #f2f2f2;
}
</style>
@endsection
