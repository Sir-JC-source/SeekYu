@extends('Layouts.vuexy')

@section('title', 'Applicant Credentials')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <!-- Page Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="card-title mb-1">Applicant Credentials</h4>
                            <p class="card-subtitle text-muted mb-0">Complete your professional profile to enhance your job applications</p>
                        </div>
                        <div class="avatar avatar-xl">
                            <div class="avatar-initial bg-primary rounded">
                                <i class="ti ti-id ti-lg text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="ti ti-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('applicant.credentials.store') }}" method="POST" enctype="multipart/form-data" id="credentialsForm">
                @csrf

                <!-- Progress Indicator -->
        

                <!-- Enhanced Nav tabs -->
                <div class="card">
                    <div class="card-header border-bottom-0 bg-light">
                        <ul class="nav nav-pills nav-fill" id="credentials-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active d-flex align-items-center" id="role-specific-tab" data-bs-toggle="tab" data-bs-target="#role-specific" type="button" role="tab" aria-controls="role-specific" aria-selected="true">
                                    <i class="ti ti-certificate me-2"></i>
                                    <span>Professional Details</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="work-history-tab" data-bs-toggle="tab" data-bs-target="#work-history" type="button" role="tab" aria-controls="work-history" aria-selected="false">
                                    <i class="ti ti-building me-2"></i>
                                    <span>Work Experience</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">
                                    <i class="ti ti-tools me-2"></i>
                                    <span>Skills & Expertise</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link d-flex align-items-center" id="upload-documents-tab" data-bs-toggle="tab" data-bs-target="#upload-documents" type="button" role="tab" aria-controls="upload-documents" aria-selected="false">
                                    <i class="ti ti-file-upload me-2"></i>
                                    <span>Documents</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                        <!-- Tab panes -->
                        <div class="tab-content" id="credentials-tab-content">
                            <!-- Role Specific Details Tab -->
                            <div class="tab-pane fade show active" id="role-specific" role="tabpanel" aria-labelledby="role-specific-tab">
                                <div class="card-body">
                                    <div class="row g-4">
                                        <!-- License Information -->
                                        <div class="col-lg-6">
                                            <div class="card h-100 border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="ti ti-license text-primary ti-lg me-2"></i>
                                                        <h6 class="card-title mb-0">License Information</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="license_no" class="form-label fw-semibold">License Number</label>
                                                        <input type="text" class="form-control form-control-lg"
                                                               id="license_no" name="license_no"
                                                               value="{{ old('license_no', $credentials?->license_no ?? '') }}"
                                                               placeholder="Enter your license number"
                                                               inputmode="numeric"
                                                               pattern="[0-9-]*">
                                                        @error('license_no')
                                                            <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="license_expiration_date" class="form-label fw-semibold">License Expiration Date</label>
                                                        <input type="date" class="form-control form-control-lg" id="license_expiration_date" name="license_expiration_date"
                                                               value="{{ old('license_expiration_date', $credentials?->license_expiration_date?->format('Y-m-d') ?? '') }}">
                                                        @error('license_expiration_date')
                                                            <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Certifications & Experience -->
                                        <div class="col-lg-6">
                                            <div class="card h-100 border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="ti ti-award text-success ti-lg me-2"></i>
                                                        <h6 class="card-title mb-0">Certifications & Experience</h6>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="certifications" class="form-label fw-semibold">Certifications</label>
                                                        <textarea class="form-control form-control-lg" id="certifications" name="certifications" rows="3"
                                                                  placeholder="List your certifications (one per line)">{{ old('certifications', is_string($credentials?->certifications) ? $credentials->certifications : '') }}</textarea>
                                                        @error('certifications')
                                                            <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="years_of_experience" class="form-label fw-semibold">Years of Experience</label>
                                                        <input type="number" class="form-control form-control-lg" id="years_of_experience" name="years_of_experience" min="0"
                                                               value="{{ old('years_of_experience', $credentials?->years_of_experience ?? '') }}"
                                                               placeholder="Enter years of experience">
                                                        @error('years_of_experience')
                                                            <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Government IDs -->
                                        <div class="col-12 mt-4">
                                            <div class="card border-0 bg-light">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center mb-3">
                                                        <i class="ti ti-id text-info ti-lg me-2"></i>
                                                        <h6 class="card-title mb-0">Government IDs (SSS, PAG-IBIG, PhilHealth)</h6>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-lg-4">
                                                            <label for="sss_number" class="form-label fw-semibold">SSS Number</label>
                                                            <input type="text" class="form-control"
                                                                   id="sss_number" name="sss_number"
                                                                   value="{{ old('sss_number', $credentials?->sss_number ?? '') }}"
                                                                   placeholder="e.g., 01-1234567-8"
                                                                   inputmode="numeric"
                                                                   pattern="[0-9-]*">
                                                            @error('sss_number')
                                                                <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="pagibig_number" class="form-label fw-semibold">PAG-IBIG Number</label>
                                                            <input type="text" class="form-control"
                                                                   id="pagibig_number" name="pagibig_number"
                                                                   value="{{ old('pagibig_number', $credentials?->pagibig_number ?? '') }}"
                                                                   placeholder="e.g., 1234-5678-9012"
                                                                   inputmode="numeric"
                                                                   pattern="[0-9-]*">
                                                            @error('pagibig_number')
                                                                <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                        <div class="col-lg-4">
                                                            <label for="philhealth_number" class="form-label fw-semibold">PhilHealth Number</label>
                                                            <input type="text" class="form-control"
                                                                   id="philhealth_number" name="philhealth_number"
                                                                   value="{{ old('philhealth_number', $credentials?->philhealth_number ?? '') }}"
                                                                   placeholder="e.g., 123456789012"
                                                                   inputmode="numeric"
                                                                   pattern="[0-9-]*">
                                                            @error('philhealth_number')
                                                                <div class="text-danger small mt-1"><i class="ti ti-alert-circle me-1"></i>{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Work History Tab -->
                            <div class="tab-pane fade" id="work-history" role="tabpanel" aria-labelledby="work-history-tab">
                                <div class="mt-3">
                                    <div class="form-group">
                                        <h5>Work History</h5>
                                        <div id="work-history-container">
                                            @if(old('work_history') || ($credentials->work_history ?? false))
                                                @php
                                                    $workHistory = old('work_history', $credentials->work_history ?? []);
                                                @endphp
                                                @foreach($workHistory as $index => $work)
                                                    <div class="work-history-item border p-3 mb-3">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Company Name</label>
                                                                    <input type="text" class="form-control" name="work_history[{{ $index }}][company_name]" value="{{ is_string($work['company_name'] ?? '') ? $work['company_name'] : '' }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label>Position</label>
                                                                    <input type="text" class="form-control" name="work_history[{{ $index }}][position]" value="{{ is_string($work['position'] ?? '') ? $work['position'] : '' }}">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>Start Date</label>
                                                                    <input type="date" class="form-control" name="work_history[{{ $index }}][start_date]" value="{{ is_string($work['start_date'] ?? '') ? $work['start_date'] : '' }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label>End Date</label>
                                                                    <input type="date" class="form-control" name="work_history[{{ $index }}][end_date]" value="{{ is_string($work['end_date'] ?? '') ? $work['end_date'] : '' }}">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <button type="button" class="btn btn-danger btn-sm remove-work-history" style="margin-top: 32px;">Remove</button>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Responsibilities</label>
                                                            <textarea class="form-control" name="work_history[{{ $index }}][responsibilities]" rows="2">{{ is_string($work['responsibilities'] ?? '') ? $work['responsibilities'] : '' }}</textarea>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm" id="add-work-history">Add Work Experience</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Skills Tab -->
                            <div class="tab-pane fade" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                                <div class="mt-3">
                                    <div class="form-group">
                                        <h5>Skills</h5>
                                        <div id="skills-container">
                                            @if(old('skills') || ($credentials->skills ?? false))
                                                @php
                                                    $skills = old('skills', $credentials->skills ?? []);
                                                @endphp
                                                @foreach($skills as $index => $skill)
                                                    <div class="skill-item d-flex mb-2">
                                                        <input type="text" class="form-control" name="skills[{{ $index }}]" value="{{ is_string($skill) ? $skill : '' }}" placeholder="Enter skill">
                                                        <button type="button" class="btn btn-danger btn-sm ml-2 remove-skill">Remove</button>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                        <button type="button" class="btn btn-secondary btn-sm" id="add-skill">Add Skill</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Documents Tab -->
                            <div class="tab-pane fade" id="upload-documents" role="tabpanel" aria-labelledby="upload-documents-tab">
                                <div class="mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="license">License Document</label>
                                                <input type="file" class="form-control" id="license" name="license" accept=".pdf,.jpg,.jpeg,.png">
                                                @error('license')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                @if($credentials->license_path ?? false)
                                                    <small class="text-muted">Current file: {{ basename($credentials->license_path) }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="training_certificate">Training Certificate</label>
                                                <input type="file" class="form-control" id="training_certificate" name="training_certificate" accept=".pdf,.jpg,.jpeg,.png">
                                                @error('training_certificate')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                @if($credentials->training_certificate_path ?? false)
                                                    <small class="text-muted">Current file: {{ basename($credentials->training_certificate_path) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="resume">Resume</label>
                                                <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.doc,.docx">
                                                @error('resume')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                @if($credentials->resume_path ?? false)
                                                    <small class="text-muted">Current file: {{ basename($credentials->resume_path) }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="nbi_clearance">NBI Clearance Document</label>
                                                <input type="file" class="form-control" id="nbi_clearance" name="nbi_clearance" accept=".pdf,.jpg,.jpeg,.png">
                                                @error('nbi_clearance')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                                @if($credentials->nbi_clearance_path ?? false)
                                                    <small class="text-muted">Current file: {{ basename($credentials->nbi_clearance_path) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3" id="saveButton" disabled>Save Credentials</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let workHistoryIndex = {{ count(old('work_history', $credentials->work_history ?? [])) }};
    let skillIndex = {{ count(old('skills', $credentials->skills ?? [])) }};
    let hasChanges = false;
    const saveButton = document.getElementById('saveButton');
    const form = document.getElementById('credentialsForm');

    // Function to check if form has changes
    function checkForChanges() {
        const inputs = form.querySelectorAll('input, textarea, select');
        let changed = false;

        inputs.forEach(input => {
            if (input.type === 'file') {
                if (input.files.length > 0) {
                    changed = true;
                }
            } else if (input.value !== input.defaultValue) {
                changed = true;
            }
        });

        hasChanges = changed;
        saveButton.disabled = !hasChanges;
        saveButton.textContent = hasChanges ? 'Save Changes' : 'Save Credentials';
    }

    // Add event listeners to all form inputs
    form.addEventListener('input', checkForChanges);
    form.addEventListener('change', checkForChanges);

    // Add work history
    document.getElementById('add-work-history').addEventListener('click', function() {
        const container = document.getElementById('work-history-container');
        const html = `
            <div class="work-history-item border p-3 mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Company Name</label>
                            <input type="text" class="form-control" name="work_history[${workHistoryIndex}][company_name]">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Position</label>
                            <input type="text" class="form-control" name="work_history[${workHistoryIndex}][position]">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" class="form-control" name="work_history[${workHistoryIndex}][start_date]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" class="form-control" name="work_history[${workHistoryIndex}][end_date]">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button type="button" class="btn btn-danger btn-sm remove-work-history" style="margin-top: 32px;">Remove</button>
                    </div>
                </div>
                <div class="form-group">
                    <label>Responsibilities</label>
                    <textarea class="form-control" name="work_history[${workHistoryIndex}][responsibilities]" rows="2"></textarea>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        workHistoryIndex++;
        checkForChanges();
    });

    // Remove work history
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-work-history')) {
            e.target.closest('.work-history-item').remove();
            checkForChanges();
        }
    });

    // Add skill
    document.getElementById('add-skill').addEventListener('click', function() {
        const container = document.getElementById('skills-container');
        const html = `
            <div class="skill-item d-flex mb-2">
                <input type="text" class="form-control" name="skills[${skillIndex}]" placeholder="Enter skill">
                <button type="button" class="btn btn-danger btn-sm ml-2 remove-skill">Remove</button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        skillIndex++;
        checkForChanges();
    });

    // Remove skill
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-skill')) {
            e.target.closest('.skill-item').remove();
            checkForChanges();
        }
    });

    // Initialize completion progress
    function updateCompletionProgress() {
        const totalFields = 4; // license_no, license_expiration_date, certifications, years_of_experience
        let completedFields = 0;

        if (document.getElementById('license_no').value.trim()) completedFields++;
        if (document.getElementById('license_expiration_date').value) completedFields++;
        if (document.getElementById('certifications').value.trim()) completedFields++;
        if (document.getElementById('years_of_experience').value) completedFields++;

        const percentage = Math.round((completedFields / totalFields) * 100);
        document.getElementById('completionProgress').style.width = percentage + '%';
        document.getElementById('completionBadge').textContent = percentage + '% Complete';
    }

    // Update progress on input
    form.addEventListener('input', updateCompletionProgress);
    form.addEventListener('change', updateCompletionProgress);

    // Initial progress update
    updateCompletionProgress();
});
</script>
@endsection
