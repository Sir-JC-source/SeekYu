@extends('Layouts.vuexy')

@section('title', 'Pending Leaves')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Pending Leave Requests</h5>
        <div>
            <label for="statusFilter" class="form-label me-2">Filter by Status:</label>
            <select id="statusFilter" class="form-select form-select-sm d-inline-block w-auto">
                <option value="All" selected>All</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
    </div>

    <div class="card-body">
        @if($leaves->isEmpty())
            <div class="alert alert-info">There are no leave requests at the moment.</div>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-bordered" id="leave-table">
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($leaves as $index => $leave)
                        <tr class="table-row" data-id="{{ $leave->id }}" data-status="{{ $leave->status }}">
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
                                    $status = $leave->status;
                                    $badgeClass = match($status) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Approved' => 'bg-success',
                                        'Rejected' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                            </td>
                            <td>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('hr-officer') || auth()->user()->hasRole('super-admin'))
                                    @if($leave->status === 'Pending')
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">&#x22EE;</button>
                                        <ul class="dropdown-menu">
                                            <li><button class="dropdown-item text-success approve-btn" data-id="{{ $leave->id }}">Approve</button></li>
                                            <li><button class="dropdown-item text-danger reject-btn" data-id="{{ $leave->id }}">Reject</button></li>
                                        </ul>
                                    </div>
                                    @else
                                        <span class="text-muted">Action Taken</span>
                                    @endif
                                @else
                                    <span class="text-muted">No actions</span>
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

<!-- Modal -->
<div class="modal fade" id="leaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Leave Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tr><th>Requestor:</th><td id="modal-requestor"></td></tr>
                    <tr><th>Position:</th><td id="modal-position"></td></tr>
                    <tr><th>Type of Leave:</th><td id="modal-type"></td></tr>
                    <tr><th>Reason:</th><td id="modal-reason" style="word-break: break-word;"></td></tr>
                    <tr><th>Duration:</th><td id="modal-duration"></td></tr>
                    <tr><th>Date Requested:</th><td id="modal-date"></td></tr>
                    <tr><th>Status:</th><td id="modal-status"></td></tr>
                </table>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function(){
    const tableRows = document.querySelectorAll('.table-row');
    const statusFilter = document.getElementById('statusFilter');

    // Show modal
    tableRows.forEach(row=>{
        row.addEventListener('click', e=>{
            if(e.target.closest('.approve-btn') || e.target.closest('.reject-btn')) return;

            const modal = new bootstrap.Modal(document.getElementById('leaveModal'));
            document.getElementById('modal-requestor').textContent = row.querySelector('td:nth-child(2)').textContent;
            document.getElementById('modal-position').textContent = row.querySelector('td:nth-child(3)').textContent;
            document.getElementById('modal-type').textContent = row.querySelector('td:nth-child(4)').textContent;
            document.getElementById('modal-reason').textContent = row.querySelector('td:nth-child(5)').getAttribute('title');
            document.getElementById('modal-duration').textContent = row.querySelector('td:nth-child(6)').textContent;
            document.getElementById('modal-date').textContent = row.querySelector('td:nth-child(7)').textContent;
            document.getElementById('modal-status').textContent = row.querySelector('td:nth-child(8) .badge').textContent;
            modal.show();
        });
    });

    // Approve/Reject buttons
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.approve-btn, .reject-btn');
        if(!btn) return;

        const action = btn.classList.contains('approve-btn') ? 'approve' : 'reject';
        const leaveId = btn.dataset.id;
        const row = document.querySelector(`.table-row[data-id="${leaveId}"]`);
        const status = row.dataset.status;

        if(status !== 'Pending'){
            Swal.fire('Action not allowed', 'This leave has already been processed.', 'info');
            return;
        }

        Swal.fire({
            title: `Are you sure you want to ${action} this leave request?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then(result=>{
            if(result.isConfirmed){
                const formData = new FormData();
                formData.append('_method','PUT');

                fetch(`/leaves/${action}/${leaveId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success){
                        const badge = row.querySelector('td:nth-child(8) .badge');
                        badge.textContent = data.status;
                        badge.className = 'badge ' + (data.status==='Approved'?'bg-success':'bg-danger');
                        row.dataset.status = data.status;
                        applyFilter();
                        Swal.fire('Success', `Leave has been ${data.status.toLowerCase()}.`, 'success');
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err=>{
                    Swal.fire('Error', 'Something went wrong.', 'error');
                    console.error(err);
                });
            }
        });
    });

    statusFilter.addEventListener('change', applyFilter);
    function applyFilter(){
        const selected = statusFilter.value;
        tableRows.forEach(row=>{
            row.style.display = (selected==='All'||row.dataset.status===selected)?'':'none';
        });
    }
});
</script>

<style>
/* Remove hover highlight and header background */
#leave-table thead th {
    background-color: transparent !important;
    color: #333 !important;
}

#leave-table tbody tr {
    background-color: #fff !important;
}

#leave-table tbody tr:hover {
    background-color: #fff !important;
    cursor: default;
}
</style>
@endsection
