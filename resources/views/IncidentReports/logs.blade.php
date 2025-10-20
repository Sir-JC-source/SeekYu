@extends('Layouts.vuexy')

@section('title', 'IR Logs')

@section('content')
<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0">Incident Report Logs</h4>
    </div>
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table id="ir-logs-table" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Incident Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Specific Area</th>
                    <th>Description</th>
                    <th>Parties Involved</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $report->incident_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($report->date_of_incident)->format('F d, Y') }}</td>
                        <td>{{ $report->location }}</td>
                        <td>{{ $report->specific_area }}</td>
                        <td>{{ $report->incident_description }}</td>
                        <td>
                            @foreach($report->parties as $party)
                                <div class="mb-2 p-2 border rounded">
                                    <strong>{{ $party->name }}</strong> - {{ $party->role }} - {{ $party->contact }}<br>
                                    <em>{{ $party->statement }}</em>
                                </div>
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">No incident reports found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#ir-logs-table').DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        columnDefs: [
            { orderable: false, targets: 6 } // Parties column not sortable
        ]
    });
});
</script>
@endpush
