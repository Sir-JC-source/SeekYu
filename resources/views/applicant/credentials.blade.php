@extends('Layouts.vuexy')

@section('title', 'Applicant Credentials')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Applicant Credentials</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('applicant.credentials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="credentials-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="role-specific-tab" data-bs-toggle="tab" data-bs-target="#role-specific" type="button" role="tab" aria-controls="role-specific" aria-selected="true">Role Specific Details</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="work-history-tab" data-bs-toggle="tab" data-bs-target="#work-history" type="button" role="tab" aria-controls="work-history" aria-selected="false">Work History</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="skills-tab" data-bs-toggle="tab" data-bs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="false">Skills</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="upload-documents-tab" data-bs-toggle="tab" data-bs-target="#upload-documents" type="button" role="tab" aria-controls="upload-documents" aria-selected="false">Upload Documents</button>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content" id="credentials-tab-content">
                            <!-- Role Specific Details Tab -->
                            <div class="tab-pane fade show active" id="role-specific" role="tabpanel" aria-labelledby="role-specific-tab">
                                <div class="mt-3">
                                    <div class="row">
                                        <!-- License Information -->
                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="license_no">License Number</label>
                                                <input type="text" class="form-control" id="license_no" name="license_no"
                                                       value="{{ old('license_no', $credentials?->license_no ?? '') }}">
                                                @error('license_no')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="form-group">
                                                <label for="license_expiration_date">License Expiration Date</label>
                                                <input type="date" class="form-control" id="license_expiration_date" name="license_expiration_date"
                                                       value="{{ old('license_expiration_date', $credentials?->license_expiration_date?->format('Y-m-d') ?? '') }}">
                                                @error('license_expiration_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <!-- Certifications -->
                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="certifications">Certifications</label>
                                                <textarea class="form-control" id="certifications" name="certifications" rows="3">{{ old('certifications', is_string($credentials?->certifications) ? $credentials->certifications : '') }}</textarea>
                                                @error('certifications')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Experience -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5></h5>
                                            <div class="form-group">
                                                <label for="years_of_experience">Years of Experience</label>
                                                <input type="number" class="form-control" id="years_of_experience" name="years_of_experience" min="0"
                                                       value="{{ old('years_of_experience', $credentials?->years_of_experience ?? '') }}">
                                                @error('years_of_experience')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
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

                        <button type="submit" class="btn btn-primary mt-3">Save Credentials</button>
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
    });

    // Remove work history
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-work-history')) {
            e.target.closest('.work-history-item').remove();
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
    });

    // Remove skill
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-skill')) {
            e.target.closest('.skill-item').remove();
        }
    });
});
</script>
@endsection
