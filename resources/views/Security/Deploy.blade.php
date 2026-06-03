@extends('Layouts.vuexy')

@section('title', 'Deployments')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Deployments Dashboard</h5>
                    {{-- Mechanism/logic: guard selection + filtering + status actions handled by existing buttons below --}}
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3 mb-3">
                            <div class="card border-start border-primary border-3">
                                <div class="card-body text-center">
<h3 class="card-title text-primary mb-1">{{ $activeDeployments }}</h3>
                                    <p class="card-text mb-0">Active Deployments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-start border-warning border-3">
                                <div class="card-body text-center">
            <h3 class="card-title text-warning mb-1">{{ $pendingDeployments }}</h3>
                                    <p class="card-text mb-0">Pending Deployments</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-start border-info border-3">
                                <div class="card-body text-center">
                                    <h3 class="card-title text-info mb-1">{{ $completedDeployments }}</h3>
                                    <p class="card-text mb-0">Completed</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card border-start border-danger border-3">
                                <div class="card-body text-center">
                                    <h3 class="card-title text-danger mb-1">{{ $cancelledDeployments }}</h3>
                                    <p class="card-text mb-0">Cancelled</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Guard</th>
                                    <th>Date</th>
                                    <th>Shift</th>
                                    <th>Head Guard</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deployments as $deployment)
                                <tr>
                                    <td>{{ $deployment->employee->full_name ?? 'N/A' }}</td>
                                    <td>{{ $deployment->deployment_date->format('M d, Y') }}</td>
                                    <td>{{ $deployment->shift_in }} - {{ $deployment->shift_out }}</td>
                                    <td>{{ $deployment->headGuard->full_name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $deployment->status == 'active' ? 'success' : ($deployment->status == 'pending' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($deployment->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($deployment->status == 'pending')
                                            <form action="{{ route('security.updateDeploymentStatus', $deployment->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="active">
                                                <button type="submit" class="btn btn-success">Activate</button>
                                            </form>
                                            <form action="{{ route('security.updateDeploymentStatus', $deployment->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-danger">Cancel</button>
                                            </form>
                                            @elseif($deployment->status == 'active')
                                            <form action="{{ route('security.updateDeploymentStatus', $deployment->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-primary">Complete</button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="ti ti-building-cottage-off ti-4x text-muted mb-3 d-block"></i>
                                        <p class="text-muted mb-0">No deployments found</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

