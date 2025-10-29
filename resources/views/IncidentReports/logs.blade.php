@extends('Layouts.vuexy')

@section('title', 'IR Logs')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-12 mb-4">
        <div class="row g-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <div class="avatar-initial bg-white text-primary rounded">
                                    <i class="ti ti-file-text ti-sm"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block mb-1">Total Reports</span>
                                <h3 class="card-title mb-0">{{ $totalReports }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <div class="avatar-initial bg-white text-success rounded">
                                    <i class="ti ti-check-circle ti-sm"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block mb-1">Resolved</span>
                                <h3 class="card-title mb-0">{{ $resolvedReports }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <div class="avatar-initial bg-white text-warning rounded">
                                    <i class="ti ti-clock ti-sm"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block mb-1">Pending</span>
                                <h3 class="card-title mb-0">{{ $pendingReports }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <div class="avatar-initial bg-white text-info rounded">
                                    <i class="ti ti-search ti-sm"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <span class="fw-semibold d-block mb-1">Investigating</span>
                                <h3 class="card-title mb-0">{{ $investigatingReports }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Filters & Search</h5>
                <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="ti ti-filter me-1"></i>Toggle Filters
                </button>
            </div>
            <div class="collapse show" id="filtersCollapse">
                <div class="card-body">
                    <form method="GET" action="{{ route('incident-reports.logs') }}" class="row g-3">
                        <!-- Search -->
                        <div class="col-md-6">
                            <label class="form-label">Search</label>
                            <input type="text" name="search" class="form-control" placeholder="Search incident name, description, or location..." value="{{ request('search') }}">
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <!-- Location Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Location</label>
                            <select name="location" class="form-select">
                                <option value="">All Locations</option>
                                @foreach($locations as $location)
                                    <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="investigating" {{ request('status') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            </select>
                        </div>

                        <!-- Quick Filters -->
                        <div class="col-md-4">
                            <label class="form-label">Quick Filters</label>
                            <div class="btn-group w-100" role="group">
                                <input type="radio" class="btn-check" name="quick_filter" id="today" value="today" {{ request('quick_filter') == 'today' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="today">Today</label>

                                <input type="radio" class="btn-check" name="quick_filter" id="week" value="week" {{ request('quick_filter') == 'week' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="week">This Week</label>

                                <input type="radio" class="btn-check" name="quick_filter" id="month" value="month" {{ request('quick_filter') == 'month' ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary btn-sm" for="month">This Month</label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ti ti-search me-1"></i>Apply Filters
                            </button>
                            <a href="{{ route('incident-reports.logs') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh me-1"></i>Clear Filters
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Incident Reports Cards -->
    <div class="col-12">
        <div class="row g-4">
            @forelse($reports as $report)
                <div class="col-xl-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-2">
                                    <div class="avatar-initial bg-label-warning rounded">
                                        <i class="ti ti-alert-triangle ti-sm"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="card-title mb-0">{{ Str::limit($report->incident_name, 30) }}</h6>
                                    <small class="text-muted">{{ $report->date_of_incident->format('M d, Y') }}</small>
                                </div>
                            </div>
                            @php
                                $statusClass = match($report->status ?? 'pending') {
                                    'resolved' => 'success',
                                    'investigating' => 'info',
                                    'pending' => 'warning',
                                    default => 'secondary'
                                };
                                $statusIcon = match($report->status ?? 'pending') {
                                    'resolved' => 'check-circle',
                                    'investigating' => 'search',
                                    'pending' => 'clock',
                                    default => 'circle'
                                };
                            @endphp
                            <span class="badge bg-label-{{ $statusClass }}">
                                <i class="ti ti-{{ $statusIcon }} me-1"></i>{{ ucfirst($report->status ?? 'pending') }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-map-pin text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $report->location }}</span>
                                </div>
                                <div class="d-flex align-items-center mb-2">
                                    <i class="ti ti-building text-info me-2"></i>
                                    <span>{{ $report->specific_area }}</span>
                                </div>
                            </div>

                            <p class="text-muted mb-3">{{ Str::limit($report->incident_description, 100) }}</p>

                            <div class="mb-3">
                                <small class="text-muted fw-semibold">Parties Involved ({{ $report->parties->count() }})</small>
                                @foreach($report->parties->take(2) as $party)
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="avatar avatar-xs me-2">
                                            <div class="avatar-initial bg-label-secondary rounded-circle">
                                                <i class="ti ti-user ti-xs"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="fw-semibold">{{ Str::limit($party->name, 20) }}</small>
                                            <br><small class="text-muted">{{ $party->role }}</small>
                                        </div>
                                    </div>
                                @endforeach
                                @if($report->parties->count() > 2)
                                    <small class="text-muted">+{{ $report->parties->count() - 2 }} more</small>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                <i class="ti ti-eye me-1"></i>View Details
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal for detailed view -->
                <div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $report->incident_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <strong>Date:</strong> {{ $report->date_of_incident->format('F d, Y') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Location:</strong> {{ $report->location }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Specific Area:</strong> {{ $report->specific_area }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <div class="d-flex align-items-center">
                                            <select id="status-select-{{ $report->id }}" class="form-select form-select-sm d-inline-block w-auto ms-2">
                                                <option value="pending" {{ ($report->status ?? 'pending') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="investigating" {{ ($report->status ?? 'pending') == 'investigating' ? 'selected' : '' }}>Investigating</option>
                                                <option value="resolved" {{ ($report->status ?? 'pending') == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                            </select>
                                            <button type="button" class="btn btn-sm btn-primary ms-2" onclick="updateStatus({{ $report->id }})">Update</button>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <p class="mt-2">{{ $report->incident_description }}</p>
                                    </div>
                                    <div class="col-12">
                                        <strong>Parties Involved:</strong>
                                        <div class="mt-2">
                                            @foreach($report->parties as $party)
                                                <div class="border rounded p-3 mb-2">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <strong>{{ $party->name }}</strong>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small class="text-muted">{{ $party->role }}</small>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <small>{{ $party->contact }}</small>
                                                        </div>
                                                        <div class="col-12 mt-2">
                                                            <em>"{{ $party->statement }}"</em>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="avatar avatar-xl mx-auto mb-3">
                                <div class="avatar-initial bg-label-secondary rounded">
                                    <i class="ti ti-file-x ti-lg"></i>
                                </div>
                            </div>
                            <h5 class="card-title">No Incident Reports Found</h5>
                            <p class="card-text text-muted">There are no incident reports matching your current filters.</p>
                            <a href="{{ route('incident-reports.logs') }}" class="btn btn-primary">Clear Filters</a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($reports->hasPages())
            <div class="col-12 mt-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $reports->firstItem() ?? 0 }} to {{ $reports->lastItem() ?? 0 }} of {{ $reports->total() }} entries
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="text-muted">Show:</span>
                        <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                            <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                        </select>
                        {{ $reports->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-group .btn {
    flex: 1;
}

@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 0.5rem;
    }

    .card-header .badge {
        align-self: flex-end;
    }
}
</style>
@endpush

@push('page-scripts')
<script>
function changePerPage(perPage) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', perPage);
    window.location.href = url.toString();
}

// Clear quick filter when other filters are used
document.querySelectorAll('input[name="quick_filter"]').forEach(radio => {
    radio.addEventListener('change', function() {
        if (this.checked) {
            // Clear date inputs when quick filter is selected
            document.querySelector('input[name="date_from"]').value = '';
            document.querySelector('input[name="date_to"]').value = '';
        }
    });
});

// Function to update status via AJAX
function updateStatus(reportId) {
    console.log('updateStatus called with reportId:', reportId);
    const statusSelect = document.getElementById(`status-select-${reportId}`);
    console.log('statusSelect element:', statusSelect);
    const status = statusSelect.value;
    console.log('Selected status:', status);

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    console.log('CSRF Token:', csrfToken);

    fetch(`/incident-reports/${reportId}/update-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Reload the page to reflect changes
            location.reload();
        } else {
            alert('Failed to update status: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while updating the status: ' + error.message);
    });
}
</script>
@endpush
