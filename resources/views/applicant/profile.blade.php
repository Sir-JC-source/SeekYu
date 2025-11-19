@extends('Layouts.vuexy')

@section('title', 'My Profile')

@section('content')
<div class="row">
    <!-- Profile Header Card -->
    <div class="col-12 mb-4">
        <div class="card bg-gradient-primary text-white">
            <div class="card-body text-center py-4">
                <div class="position-relative d-inline-block mb-3">
                    <img id="profileAvatar"
                         src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('assets/img/avatars/1.png') }}"
                         alt="Profile Picture"
                         class="rounded-circle border-3 border-white shadow"
                         style="width:100px; height:100px; object-fit:cover; cursor:pointer;">
                    <button type="button" class="btn btn-sm btn-icon btn-circle position-absolute bottom-0 end-0 bg-white text-primary border border-white" onclick="$('#profilePictureInput').click()" style="width: 32px; height: 32px;">
                        <i class="ti ti-camera" style="font-size: 16px;"></i>
                    </button>
                </div>
                <h3 class="card-title text-white mb-1">{{ $profile->fullname }}</h3>
                <p class="card-text opacity-75 mb-2">{{ ucfirst($profile->role) }}</p>
                <div class="d-flex justify-content-center gap-3">
                    <div class="text-center">
                        <small class="text-white-50 d-block">Login ID</small>
                        <span class="fw-bold">{{ $profile->login_id }}</span>
                    </div>
                    <div class="vr bg-white opacity-25"></div>
                    <div class="text-center">
                        <small class="text-white-50 d-block">Email</small>
                        <span class="fw-bold">{{ $profile->email }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Details -->
    <div class="col-xl-8 col-lg-7 col-md-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Profile Information</h5>
                <small class="text-muted">Click edit icons to modify</small>
            </div>
            <div class="card-body">
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="profile_picture" id="profilePictureInput" class="d-none" accept="image/*">
                    <div class="row g-4">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-user me-2"></i>Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fullname" name="fullname"
                                       value="{{ $profile->fullname }}" readonly
                                       placeholder="Full Name">
                                <label for="fullname">Full Name</label>
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill position-absolute top-50 end-0 translate-middle-y me-2 edit-btn" data-target="#fullname" style="z-index: 5;">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="contactNo" name="contact_no"
                                       value="{{ $profile->contact_no }}" readonly
                                       placeholder="Contact Number">
                                <label for="contactNo">Contact Number</label>
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill position-absolute top-50 end-0 translate-middle-y me-2 edit-btn" data-target="#contactNo" style="z-index: 5;">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-shield-check me-2"></i>Account Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ $profile->login_id }}"
                                       placeholder="Login ID">
                                <label>Login ID</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ ucfirst($profile->role) }}"
                                       placeholder="Role">
                                <label>Role</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="email" class="form-control bg-light" readonly
                                       value="{{ $profile->email }}"
                                       placeholder="Email">
                                <label>Email</label>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-map-pin me-2"></i>Location Information
                            </h6>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control bg-light" rows="3" readonly
                                          placeholder="Address">{{ $profile->province }}, {{ $profile->city }}, {{ $profile->barangay }}</textarea>
                                <label>Address</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <button type="button" id="saveProfileBtn" class="btn btn-primary btn-lg px-5" disabled>
                                <i class="ti ti-device-floppy me-2"></i>Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Stats/Quick Actions -->
    <div class="col-xl-4 col-lg-5 col-md-5">
        <div class="row g-4">
            <!-- Application Status -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Application Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Profile Completion</small>
                                <div class="fw-bold text-success">100%</div>
                            </div>
                            <i class="ti ti-circle-check text-success" style="font-size: 24px;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Applications Submitted</small>
                                <div class="fw-bold">{{ $profile->applications_count ?? 0 }}</div>
                            </div>
                            <i class="ti ti-file-text text-info" style="font-size: 24px;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Member Since</small>
                                <div class="fw-bold">{{ $profile->created_at->format('M d, Y') }}</div>
                            </div>
                            <i class="ti ti-calendar text-warning" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Actions</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('applicant.jobs') }}" class="btn btn-outline-primary">
                                <i class="ti ti-briefcase me-2"></i>Browse Jobs
                            </a>
                            <a href="{{ route('applicant.applications') }}" class="btn btn-outline-success">
                                <i class="ti ti-file-check me-2"></i>My Applications
                            </a>
                            <a href="{{ route('applicant.credentials') }}" class="btn btn-outline-info">
                                <i class="ti ti-id me-2"></i>Update Credentials
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Recent Activity</h6>
                    </div>
                    <div class="card-body">
                        <div class="timeline timeline-border-primary">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Profile updated</small>
                                    <p class="mb-0">{{ $profile->updated_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <small class="text-muted">Account created</small>
                                    <p class="mb-0">{{ $profile->created_at->diffForHumans() }}</p>
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

@push('page-styles')
<style>
.form-check-input:checked {
    background-color: #28a745;
    border-color: #28a745;
    transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-check-input {
    transition: background-color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-switch .form-check-input {
    width: 2em;
    margin-left: -2.5em;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255,255,255,0.25%29'/%3e%3c/svg%3e");
    background-position: left center;
    border-radius: 2em;
    transition: background-position .15s ease-in-out;
}

.form-switch .form-check-input:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255,255,255,0.25%29'/%3e%3c/svg%3e");
}

.form-switch .form-check-input:checked {
    background-position: right center;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='rgba%28255,255,255,0.25%29'/%3e%3c/svg%3e");
}
</style>
@endpush

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let originalValues = {};
    let hasChanges = false;

    // Store original values
    $('#fullname, #contactNo').each(function() {
        originalValues[$(this).attr('id')] = $(this).val();
    });

    // Enable editing on click of pen icon
    $('.edit-btn').click(function() {
        let target = $($(this).data('target'));
        if(target.is('input')) {
            target.prop('readonly', false);
            target.focus();
            $(this).find('i').removeClass('text-muted').addClass('text-success'); // Turn pencil green
        }
    });

    // Track changes
    $('#fullname, #contactNo').on('input', function() {
        let id = $(this).attr('id');
        if ($(this).val() !== originalValues[id]) {
            hasChanges = true;
            $('#saveProfileBtn').prop('disabled', false);
        } else {
            // Check if all fields are back to original
            let allOriginal = true;
            $('#fullname, #contactNo').each(function() {
                if ($(this).val() !== originalValues[$(this).attr('id')]) {
                    allOriginal = false;
                }
            });
            hasChanges = !allOriginal;
            $('#saveProfileBtn').prop('disabled', allOriginal);
        }
    });

    // Avatar click triggers file input
    $('#profileAvatar').click(function() {
        $('#profilePictureInput').click();
    });

    // Preview avatar immediately
    $('#profilePictureInput').change(function() {
        const file = this.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = e => $('#profileAvatar').attr('src', e.target.result);
            reader.readAsDataURL(file);
            hasChanges = true;
            $('#saveProfileBtn').prop('disabled', false);
        }
    });

    // Save changes
    $('#saveProfileBtn').on('click', function() {
        if (!hasChanges) return;
        var formData = new FormData($('#profileForm')[0]);

        $.ajax({
            url: '{{ route("employee.update-profile") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            success: function(response) {
                Swal.fire('Success!', response.message, 'success').then(() => {
                    location.reload();
                });
            },
            error: function(xhr) {
                let msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Something went wrong';
                Swal.fire('Error!', msg, 'error');
            }
        });
    });
});
</script>
@endpush
