@extends('layouts.vuexy')

@section('title', 'Gamification Dashboard')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Points and Level Card -->
        <div class="col-xl-4 col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <div class="avatar avatar-xl mx-auto mb-3">
                        <div class="avatar-initial bg-primary rounded">
                            <i class="ti ti-trophy ti-2x text-white"></i>
                        </div>
                    </div>
                    <h4 class="card-title mb-1">{{ $gamification['points'] }} Points</h4>
                    <p class="card-text text-muted mb-3">Level {{ $gamification['level'] }}</p>
                    <div class="progress mb-2" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                             style="width: {{ ($gamification['points'] % 100) }}%"
                             aria-valuenow="{{ $gamification['points'] % 100 }}"
                             aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <small class="text-muted">{{ $gamification['next_level_points'] - $gamification['points'] }} points to next level</small>
                </div>
            </div>
        </div>

        <!-- Badges Card -->
        <div class="col-xl-8 col-lg-6 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Your Badges</h5>
                    <span class="badge bg-primary">{{ count($gamification['badges']) }}</span>
                </div>
                <div class="card-body">
                    @if(count($gamification['badges']) > 0)
                        <div class="row g-3">
                            @foreach($gamification['badges'] as $badge)
                                <div class="col-md-6 col-lg-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            <div class="avatar-initial bg-warning rounded">
                                                <i class="ti ti-medal ti-sm text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $badge }}</h6>
                                            <small class="text-muted">Achievement Unlocked</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ti ti-medal-off display-1 text-muted mb-3"></i>
                            <p class="text-muted">No badges earned yet. Keep participating to unlock achievements!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Leaderboard</h5>
                    <small class="text-muted">Top Performers</small>
                </div>
                <div class="card-body">
                    @if(count($leaderboard) > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Name</th>
                                        <th>Points</th>
                                        <th>Level</th>
                                        <th>Badges</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leaderboard as $index => $user)
                                        <tr class="{{ $index < 3 ? 'table-warning' : '' }}">
                                            <td>
                                                @if($index == 0)
                                                    <i class="ti ti-crown text-warning"></i> 1st
                                                @elseif($index == 1)
                                                    <i class="ti ti-medal text-secondary"></i> 2nd
                                                @elseif($index == 2)
                                                    <i class="ti ti-award text-bronze"></i> 3rd
                                                @else
                                                    {{ $index + 1 }}th
                                                @endif
                                            </td>
                                            <td>{{ $user->fullname }}</td>
                                            <td><strong>{{ $user->points }}</strong></td>
                                            <td>Level {{ $user->level }}</td>
                                            <td>{{ count(json_decode($user->badges, true) ?? []) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-users display-1 text-muted mb-3"></i>
                            <p class="text-muted">No leaderboard data available yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- How to Earn Points -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">How to Earn Points</h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial bg-success rounded">
                                        <i class="ti ti-file-plus ti-sm text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Submit Application</h6>
                                    <small class="text-muted">+10 points</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial bg-info rounded">
                                        <i class="ti ti-id ti-sm text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Upload Credentials</h6>
                                    <small class="text-muted">+25 points</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial bg-primary rounded">
                                        <i class="ti ti-user-check ti-sm text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Complete Profile</h6>
                                    <small class="text-muted">+50 points</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <div class="avatar-initial bg-warning rounded">
                                        <i class="ti ti-star ti-sm text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-0">Get Shortlisted</h6>
                                    <small class="text-muted">+100 points</small>
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
