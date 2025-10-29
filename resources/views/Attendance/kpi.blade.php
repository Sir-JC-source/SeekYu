@extends('Layouts.vuexy')

@section('title', 'Attendance KPI')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Attendance KPI Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Total Guards -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Total Guards</h5>
                                            <h3 class="mb-0">{{ $kpiData['total_guards'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-users ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Total Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['total_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-calendar-time ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Completed Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Completed Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['completed_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-check-circle ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Late Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Late Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['late_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock-exclamation ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Undertime Shifts -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Undertime Shifts</h5>
                                            <h3 class="mb-0">{{ $kpiData['undertime_shifts'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock-minus ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Average Hours -->
                        <div class="col-md-4 mb-4">
                            <div class="card bg-secondary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5 class="card-title text-white">Average Hours</h5>
                                            <h3 class="mb-0">{{ $kpiData['average_hours'] }}</h3>
                                        </div>
                                        <div class="card-icon">
                                            <i class="ti ti-clock ti-lg"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Placeholder for future charts/graphs -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Attendance Trends</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Charts and detailed analytics will be implemented here.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
