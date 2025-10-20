@extends('Layouts.vuexy')

@section('title', 'Processed Leaves')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Processed Leave Requests</h5>
    </div>

    <div class="card-body">
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
                                @php
                                    $processorId = $leave->status === 'Approved' ? $leave->approved_by : $leave->rejected_by;
                                    $processor = $processorId ? \App\Models\User::find($processorId)->fullname : '-';
                                @endphp
                                {{ $processor }}
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
