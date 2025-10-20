@extends('Layouts.vuexy')

@section('title', 'List of Guards')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>List of Guards</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered text-center mx-auto" id="guards-table" style="max-width: 1200px;">
                <thead>
                    <tr>
                        <th style="width: 8%;">Employee No.</th>
                        <th style="width: 22%;">Full Name</th>
                        <th style="width: 12%;">Position</th>
                        <th style="width: 22%;">Shift</th>
                        <th style="width: 12%;">Designation</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 14%;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guards as $guard)
                    <tr class="text-center">
                        <td>{{ $guard->employee_number }}</td>
                        <td>
                            <span title="{{ $guard->full_name }}" class="text-truncate d-inline-block" style="max-width: 180px;">
                                {{ $guard->full_name }}
                            </span>
                        </td>
                        <td>
                            @php
                                $positionColors = [
                                    'Admin' => 'badge bg-primary',
                                    'HR Officer' => 'badge bg-info',
                                    'Head Guard' => 'badge bg-warning text-dark',
                                    'Security Guard' => 'badge bg-secondary'
                                ];
                            @endphp
                            <span class="{{ $positionColors[$guard->position] ?? 'badge bg-light text-dark' }}">
                                {{ $guard->position }}
                            </span>
                        </td>
                        <td>
                            @if($guard->shift_in && $guard->shift_out)
                                <div class="d-flex justify-content-center gap-1 flex-wrap">
                                    <span class="px-3 py-1 rounded text-white" style="background-color: #28a745; min-width: 70px;">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', substr(trim($guard->shift_in),0,5))->format('h:i A') }}
                                    </span>
                                    <span class="px-3 py-1 rounded text-white" style="background-color: #dc3545; min-width: 70px;">
                                        {{ \Carbon\Carbon::createFromFormat('H:i', substr(trim($guard->shift_out),0,5))->format('h:i A') }}
                                    </span>
                                </div>
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ $guard->designation ?? '-' }}</td>
                        <td>
                            @php
                                $status = $guard->deployment_status ?? 'Not Deployed';
                                $statusClass = $status === 'Deployed' ? 'badge bg-success' : 'badge bg-secondary';
                            @endphp
                            <span class="{{ $statusClass }}">{{ $status }}</span>
                        </td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="actionDropdown{{ $guard->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $guard->id }}">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('employee.edit', $guard->id) }}">
                                            <i class="ti ti-edit"></i> Edit
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('security.deploy.form', $guard->id) }}">
                                            <i class="ti ti-shield-check"></i> Deploy
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('security.makeInactive', $guard->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to make this guard inactive?');">
                                            @csrf
                                            @method('PUT')
                                            <button class="dropdown-item text-danger" type="submit">
                                                <i class="ti ti-user-x"></i> Make Inactive
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
    $(document).ready(function() {
        $('#guards-table').DataTable({
            "order": [[1, "asc"]],
            "scrollX": false, // No horizontal scroll
            "autoWidth": false,
            "responsive": true,
            "columnDefs": [
                { "width": "8%", "targets": 0 },
                { "width": "22%", "targets": 1 },
                { "width": "12%", "targets": 2 },
                { "width": "22%", "targets": 3 },
                { "width": "12%", "targets": 4 },
                { "width": "10%", "targets": 5 },
                { "width": "14%", "targets": 6 } // Action buttons
            ]
        });

        // Hover effect for rows
        $('#guards-table tbody tr').hover(
            function() { $(this).addClass('table-primary').css('cursor','pointer'); },
            function() { $(this).removeClass('table-primary'); }
        );
    });
</script>
@endpush
