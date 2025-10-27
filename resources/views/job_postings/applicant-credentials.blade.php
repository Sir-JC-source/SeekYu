@extends('Layouts.vuexy')

@section('title', 'Applicant Credentials')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a href="{{ route('job_postings.applications', $application->job_posting_id) }}" class="btn btn-outline-primary">Back to Applications</a>
                    <div>
                        @if($application->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @elseif($application->status === 'shortlisted')
                            <span class="badge bg-success">Shortlisted</span>
                        @else
                            <form method="POST" action="{{ route('job_postings.applications.reject', $application->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Are you sure you want to reject this application?')">Reject</button>
                            </form>
                            <form method="POST" action="{{ route('job_postings.applications.shortlist', $application->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to shortlist this application?')">Add to Shortlist</button>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($application->user->applicantCredential)
                        <!-- Header Section -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <h4 class="mb-1">{{ $application->user->fullname }}</h4>
                                                <p class="mb-0 text-muted">{{ $application->jobPosting->title }} - {{ $application->jobPosting->position }}</p>
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">Applied on {{ $application->applied_at->format('M d, Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Information Grid -->
                        <div class="row g-3 mb-4">
                            <!-- Personal Info -->
                            <div class="col-xl-3 col-lg-6 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-user-circle text-primary fa-lg me-2"></i>
                                            <h6 class="card-title mb-0">Personal Info</h6>
                                        </div>
                                        <div class="info-items">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-envelope text-muted me-2"></i>
                                                    <span class="text-muted small">Email</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->email }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    <span class="text-muted small">Phone</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->contact_no ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                    <span class="text-muted small">Address</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->address ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Details -->
                            <div class="col-xl-3 col-lg-6 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="ti ti-certificate text-success ti-lg me-2"></i>
                                            <h6 class="card-title mb-0">Professional</h6>
                                        </div>
                                        <div class="info-items">
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-id-card text-muted me-2"></i>
                                                    <span class="text-muted small">License</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->applicantCredential->license_no ?? 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-calendar-alt text-muted me-2"></i>
                                                    <span class="text-muted small">Expires</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->applicantCredential->license_expiration_date ? $application->user->applicantCredential->license_expiration_date->format('M d, Y') : 'N/A' }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center py-1">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-clock text-muted me-2"></i>
                                                    <span class="text-muted small">Experience</span>
                                                </div>
                                                <span class="fw-semibold small">{{ $application->user->applicantCredential->years_of_experience ?? 'N/A' }} yrs</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills -->
                            <div class="col-xl-3 col-lg-6 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="ti ti-tools text-warning ti-lg me-2"></i>
                                            <h6 class="card-title mb-0">Skills</h6>
                                        </div>
                                        <div class="skill-tags">
                                            @if(is_array($application->user->applicantCredential->skills) && !empty($application->user->applicantCredential->skills))
                                                @foreach(array_slice($application->user->applicantCredential->skills, 0, 3) as $skill)
                                                    <span class="badge text-primary me-1 mb-1">{{ $skill }}</span>
                                                @endforeach
                                                @if(count($application->user->applicantCredential->skills) > 3)
                                                    <span class="badge bg-light text-muted">+{{ count($application->user->applicantCredential->skills) - 3 }} more</span>
                                                @endif
                                            @else
                                                <span class="text-muted small">No skills listed</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Certifications -->
                            <div class="col-xl-3 col-lg-6 col-md-6">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="ti ti-award text-info ti-lg me-2"></i>
                                            <h6 class="card-title mb-0">Certifications</h6>
                                        </div>
                                        <p class="text-muted small mb-0">{{ $application->user->applicantCredential->certifications ?? 'No certifications listed' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Experience Timeline -->
                        @if($application->user->applicantCredential->work_history && is_array($application->user->applicantCredential->work_history) && !empty($application->user->applicantCredential->work_history))
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-header bg-transparent border-0">
                                            <div class="d-flex align-items-center">
                                                <i class="ti ti-building text-secondary ti-lg me-2"></i>
                                                <h6 class="mb-0">Work Experience</h6>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="timeline timeline-borderless">
                                                @foreach($application->user->applicantCredential->work_history as $index => $work)
                                                    <div class="timeline-item">
                                                        <div class="timeline-marker bg-primary bg-opacity-25"></div>
                                                        <div class="timeline-content">
                                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                                <div>
                                                                    <h6 class="mb-1">{{ $work['company_name'] ?? 'Company Name' }}</h6>
                                                                    <p class="text-primary mb-1">{{ $work['position'] ?? 'Position' }}</p>
                                                                    <small class="text-muted">{{ $work['start_date'] ?? 'Start Date' }} - {{ $work['end_date'] ?? 'End Date' }}</small>
                                                                </div>
                                                                <span class="badge bg-light text-muted">Exp {{ $index + 1 }}</span>
                                                            </div>
                                                            @if(isset($work['responsibilities']) && !empty($work['responsibilities']))
                                                                <p class="text-muted small mb-0">{{ $work['responsibilities'] }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Documents Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-transparent border-0">
                                        <div class="d-flex align-items-center">
                                            <i class="ti ti-file-text text-danger ti-lg me-2"></i>
                                            <h6 class="mb-0">Documents</h6>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <!-- License Document -->
                                            <div class="col-lg-3 col-md-6">
                                                <div class="document-card text-center p-3 border rounded-2 h-100">
                                                    <i class="ti ti-license text-primary ti-lg mb-2"></i>
                                                    <h6 class="document-title mb-2">License</h6>
                                                    @if($application->user->applicantCredential->license_path)
                                                        <a href="{{ asset('storage/' . $application->user->applicantCredential->license_path) }}" target="_blank" class="btn btn-primary btn-sm px-3">Download</a>
                                                    @else
                                                        <span class="text-muted small">Not provided</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Training Certificate -->
                                            <div class="col-lg-3 col-md-6">
                                                <div class="document-card text-center p-3 border rounded-2 h-100">
                                                    <i class="ti ti-certificate text-success ti-lg mb-2"></i>
                                                    <h6 class="document-title mb-2">Training Cert</h6>
                                                    @if($application->user->applicantCredential->training_certificate_path)
                                                        <a href="{{ asset('storage/' . $application->user->applicantCredential->training_certificate_path) }}" target="_blank" class="btn btn-success btn-sm px-3">Download</a>
                                                    @else
                                                        <span class="text-muted small">Not provided</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- NBI Clearance -->
                                            <div class="col-lg-3 col-md-6">
                                                <div class="document-card text-center p-3 border rounded-2 h-100">
                                                    <i class="ti ti-shield-check text-info ti-lg mb-2"></i>
                                                    <h6 class="document-title mb-2">NBI Clearance</h6>
                                                    @if($application->user->applicantCredential->nbi_clearance_path)
                                                        <a href="{{ asset('storage/' . $application->user->applicantCredential->nbi_clearance_path) }}" target="_blank" class="btn btn-info btn-sm px-3">Download</a>
                                                    @else
                                                        <span class="text-muted small">Not provided</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Resume -->
                                            <div class="col-lg-3 col-md-6">
                                                <div class="document-card text-center p-3 border rounded-2 h-100">
                                                    <i class="ti ti-file-cv text-warning ti-lg mb-2"></i>
                                                    <h6 class="document-title mb-2">Resume</h6>
                                                    @if($application->user->applicantCredential->resume_path)
                                                        <a href="{{ asset('storage/' . $application->user->applicantCredential->resume_path) }}" target="_blank" class="btn btn-warning btn-sm px-3">Download</a>
                                                    @else
                                                        <span class="text-muted small">Not provided</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-file-x display-1 text-muted mb-3"></i>
                            <h4>No Credentials Available</h4>
                            <p class="text-muted">This applicant has not submitted their credentials yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
