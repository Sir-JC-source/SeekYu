@extends('Layouts.vuexy')

@section('title', 'Employee Profile')

@section('content')
<div class="py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-10">Employee Profile</h4>
            <small class="text-muted"></small>
        </div>
        <div class="card-body">
            <form id="profileForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="profileAvatar"
                             src="{{ auth()->user()->employee && auth()->user()->employee->employee_image ? asset('storage/' . auth()->user()->employee->employee_image) : asset('assets/default-avatar.png') }}"
                             alt="Employee Avatar"
                             class="rounded-circle border"
                             style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                        <input type="file" name="employee_image" id="employeeImageInput" class="d-none" accept="image/*">
                        <small class="d-block mt-1 text-muted">Click avatar to change</small>
                        <h5 class="mt-3">{{ auth()->user()->employee ? auth()->user()->employee->full_name : '' }}</h5>
                        <p class="text-muted">{{ auth()->user()->employee ? auth()->user()->employee->position : '' }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <label for="fullName" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="fullName" name="full_name"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->full_name : '' }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary edit-btn" data-target="#fullName">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Employee Number</label>
                                    <input type="text" class="form-control"
                                           value="{{ auth()->user()->employee ? auth()->user()->employee->employee_number : '' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="text" class="form-control"
                                           value="{{ auth()->user()->employee ? auth()->user()->employee->position : '' }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <label for="contactNo" class="form-label">Contact Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="contactNo" name="contact_no"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->contact_no : '' }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary edit-btn" data-target="#contactNo">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Province</label>
                                    <input type="text" class="form-control"
                                           value="{{ auth()->user()->employee ? auth()->user()->employee->province : '' }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" class="form-control"
                                           value="{{ auth()->user()->employee ? auth()->user()->employee->city : '' }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" id="saveProfileBtn" class="btn btn-primary btn-lg" disabled>Save Changes</button>
                        </div>
                    </div>
                </div>
            </form>
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
