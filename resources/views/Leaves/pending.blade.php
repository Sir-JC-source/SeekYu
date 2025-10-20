@extends('Layouts.vuexy')

@section('title', 'Pending Leaves')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5>Pending Leave Requests</h5>
        <a href="{{ route('leaves.processed') }}" class="btn btn-outline-primary btn-sm">View Processed Leaves</a>
    </div>

    <div class="card-body">
        @if($leaves->where('status','Pending')->isEmpty())
            <div class="alert alert-info">There are no pending leave requests at the moment.</div>
        @else
            <table class="table table-striped table-bordered text-center" id="pending-leave-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Requestor</th>
                        <th>Position</th>
                        <th>Type</th>
                        <th>Reason</th>
                        <th>Duration</th>
                        <th>Date Requested</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($leaves->where('status','Pending') as $index => $leave)
                    <tr class="leave-row" 
                        data-id="{{ $leave->id }}"
                        data-requestor="{{ $leave->requestor }}"
                        data-position="{{ $leave->position }}"
                        data-type="{{ $leave->leave_type }}"
                        data-reason="{{ $leave->reason }}"
                        data-duration="@if($leave->date_from && $leave->date_to){{ \Carbon\Carbon::parse($leave->date_from)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->date_to)->format('F d, Y') }}@else{{ $leave->duration }}@endif"
                        data-date="{{ $leave->created_at->format('F d, Y') }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $leave->requestor }}</td>
                        <td>{{ $leave->position }}</td>
                        <td><span class="badge bg-info">{{ $leave->leave_type }}</span></td>
                        <td style="max-width:200px; overflow:hidden; text-overflow:ellipsis;" title="{{ $leave->reason }}">{{ $leave->reason }}</td>
                        <td style="word-wrap: break-word; white-space: normal;">
                            @if($leave->date_from && $leave->date_to)
                                {{ \Carbon\Carbon::parse($leave->date_from)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->date_to)->format('F d, Y') }}
                            @else
                                {{ $leave->duration }}
                            @endif
                        </td>
                        <td>{{ $leave->created_at->format('F d, Y') }}</td>
                        <td class="d-flex flex-column gap-1">
                            <button class="btn btn-success btn-sm approve-btn">Approve</button>
                            <button class="btn btn-danger btn-sm reject-btn">Reject</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

{{-- Leave Details Modal --}}
<div class="modal fade" id="leaveModal" tabindex="-1" aria-labelledby="leaveModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
    <div class="modal-content p-3 shadow-sm border-0 rounded-3">
      <div class="modal-header border-0 pb-0 text-center d-block">
        <h6 class="modal-title fw-semibold" id="leaveModalLabel">Leave Details</h6>
      </div>
      <div class="modal-body text-center py-2">
        <div class="text-start mx-auto" style="max-width: 280px;">
            <p class="mb-1"><strong>Requestor:</strong> <span id="modalRequestor"></span></p>
            <p class="mb-1"><strong>Position:</strong> <span id="modalPosition"></span></p>
            <p class="mb-1"><strong>Type:</strong> <span id="modalType"></span></p>
            <p class="mb-1"><strong>Reason:</strong> <span id="modalReason"></span></p>
            <p class="mb-1"><strong>Duration:</strong> <span id="modalDuration"></span></p>
            <p class="mb-0"><strong>Date Requested:</strong> <span id="modalDate"></span></p>
        </div>
      </div>
      <div class="modal-footer border-0 pt-1 pb-2 justify-content-center">
        <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    var table = $('#pending-leave-table').DataTable({
        "order": [[ 1, "asc" ]]
    });

    // Row click to open modal
    $('#pending-leave-table tbody').on('click', 'tr.leave-row', function(e) {
        if($(e.target).closest('.approve-btn, .reject-btn').length) return;

        var row = $(this);
        $('#modalRequestor').text(row.data('requestor'));
        $('#modalPosition').text(row.data('position'));
        $('#modalType').text(row.data('type'));
        $('#modalReason').text(row.data('reason'));
        $('#modalDuration').text(row.data('duration'));
        $('#modalDate').text(row.data('date'));
        $('#leaveModal').modal('show');
    });

    // Approve/Reject with proper quoted status
    function updateLeaveStatus(leaveId, action, row) {
        const url = action === 'approve'
            ? "{{ url('leaves/approve') }}/" + leaveId
            : "{{ url('leaves/reject') }}/" + leaveId;

        Swal.fire({
            title: `Are you sure you want to ${action} this leave request?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if(result.isConfirmed) {
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: new URLSearchParams({ _method: 'PUT' })
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire('Success', `Leave has been ${action}d successfully.`, 'success')
                        .then(() => table.row(row).remove().draw());
                    } else {
                        Swal.fire('Error', data.message, 'error');
                    }
                })
                .catch(err => {
                    Swal.fire('Error', 'Something went wrong.', 'error');
                    console.error(err);
                });
            }
        });
    }

    $('#pending-leave-table').on('click', '.approve-btn', function() {
        var row = $(this).closest('tr');
        updateLeaveStatus(row.data('id'), 'approve', row);
    });

    $('#pending-leave-table').on('click', '.reject-btn', function() {
        var row = $(this).closest('tr');
        updateLeaveStatus(row.data('id'), 'reject', row);
    });
});
</script>

<style>
#pending-leave-table tbody tr:hover { background-color: #f0f8ff; cursor: pointer; }
.modal-content { border-radius: 12px; background-color: #fff; }
.modal-body p { font-size: 0.875rem; color: #333; }
.modal-footer button { border-radius: 6px; }
.d-flex.flex-column.gap-1 button { width: 100%; }
</style>
@endpush
