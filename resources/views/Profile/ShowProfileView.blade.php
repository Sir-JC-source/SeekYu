@extends('Layouts.vuexy')

@section('title', 'Employee Profile')

@section('content')
<div class="row">
    <!-- Profile Header Card -->
    <div class="col-12 mb-4">
        <div class="card bg-primary text-white">
            <div class="card-body text-center py-4">
                <div class="position-relative d-inline-block mb-3">
                    <img id="profileAvatar"
                         src="{{ auth()->user()->employee && auth()->user()->employee->employee_image ? asset('storage/' . auth()->user()->employee->employee_image) : asset('assets/img/avatars/1.png') }}"
                         alt="Employee Avatar"
                         class="rounded-circle border-3 border-white shadow"
                         style="width:100px; height:100px; object-fit:cover; cursor:pointer;">
                    <input type="file" name="employee_image" id="employeeImageInput" class="d-none" accept="image/*">
                    <button type="button" class="btn btn-sm btn-icon btn-circle position-absolute bottom-0 end-0 bg-white text-primary border border-white" onclick="$('#employeeImageInput').click()" style="width: 32px; height: 32px;">
                        <i class="ti ti-camera" style="font-size: 16px;"></i>
                    </button>
                </div>
                <h3 class="card-title text-white mb-1">{{ auth()->user()->employee ? auth()->user()->employee->full_name : 'Employee Name' }}</h3>
                <p class="card-text opacity-75 mb-0">{{ auth()->user()->employee ? auth()->user()->employee->position : 'Position' }}</p>
                <small class="text-white-50">Employee ID: {{ auth()->user()->employee ? auth()->user()->employee->employee_number : 'N/A' }}</small>
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
                    <div class="row g-4">
                        <!-- Personal Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-user me-2"></i>Personal Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="fullName" name="full_name"
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->full_name : '' }}" readonly
                                       placeholder="Full Name">
                                <label for="fullName">Full Name</label>
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill position-absolute top-50 end-0 translate-middle-y me-2 edit-btn" data-target="#fullName" style="z-index: 5;">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="contactNo" name="contact_no"
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->contact_no : '' }}" readonly
                                       placeholder="Contact Number" maxlength="11" pattern="^09\d{9}$">
                                <label for="contactNo">Contact Number</label>
                                <button type="button" class="btn btn-sm btn-icon btn-text-secondary rounded-pill position-absolute top-50 end-0 translate-middle-y me-2 edit-btn" data-target="#contactNo" style="z-index: 5;">
                                    <i class="ti ti-pencil ti-sm"></i>
                                </button>
                                <div class="invalid-feedback">
                                    Contact number must be exactly 11 digits starting with 09.
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-building me-2"></i>Employment Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->employee_number : '' }}"
                                       placeholder="Employee Number">
                                <label>Employee Number</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->position : '' }}"
                                       placeholder="Position">
                                <label>Position</label>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-map-pin me-2"></i>Location Information
                            </h6>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->province : '' }}"
                                       placeholder="Province">
                                <label>Province</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control bg-light" readonly
                                       value="{{ auth()->user()->employee ? auth()->user()->employee->city : '' }}"
                                       placeholder="City">
                                <label>City</label>
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
            <!-- Quick Stats -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Stats</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Status</small>
                                <div class="fw-bold text-success">Active</div>
                            </div>
                            <i class="ti ti-circle-check text-success" style="font-size: 24px;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <small class="text-muted">Last Login</small>
                                <div class="fw-bold">{{ auth()->user()->last_login ? auth()->user()->last_login->format('M d, Y') : 'Never' }}</div>
                            </div>
                            <i class="ti ti-clock text-info" style="font-size: 24px;"></i>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">Account Created</small>
                                <div class="fw-bold">{{ auth()->user()->created_at->format('M d, Y') }}</div>
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
                            <a href="{{ route('dashboard.index') }}" class="btn btn-outline-primary">
                                <i class="ti ti-dashboard me-2"></i>Go to Dashboard
                            </a>
                            <a href="{{ route('leaves.request') }}" class="btn btn-outline-success">
                                <i class="ti ti-calendar-plus me-2"></i>Request Leave
                            </a>
                            <a href="{{ route('incident-reports.submit') }}" class="btn btn-outline-warning">
                                <i class="ti ti-alert-triangle me-2"></i>Report Incident
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    let originalValues = {};
    let hasChanges = false;

    // Store original values
    $('#fullName, #contactNo').each(function() {
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
    $('#fullName, #contactNo').on('input', function() {
        let id = $(this).attr('id');
        if ($(this).val() !== originalValues[id]) {
            hasChanges = true;
            $('#saveProfileBtn').prop('disabled', false);
        } else {
            // Check if all fields are back to original
            let allOriginal = true;
            $('#fullName, #contactNo').each(function() {
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
        $('#employeeImageInput').click();
    });

    // Preview avatar immediately
    $('#employeeImageInput').change(function() {
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
