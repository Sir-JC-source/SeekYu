@extends('Layouts.vuexy')

@section('title', 'Employee Profile')

@section('content')
<div class="container-xxl py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">My Profile</h5>
                    <small class="text-muted">Click pen icons to edit</small>
                </div>
                <div class="card-body">
                    <form id="profileForm" enctype="multipart/form-data">
                        @csrf
                        <div class="d-flex align-items-start">
                            {{-- Avatar --}}
                            <div class="me-4 text-center">
                                <img id="profileAvatar"
                                     src="{{ auth()->user()->employee && auth()->user()->employee->employee_image ? asset('storage/' . auth()->user()->employee->employee_image) : asset('assets/default-avatar.png') }}"
                                     alt="Employee Avatar"
                                     class="rounded-circle border"
                                     style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                                <input type="file" name="employee_image" id="employeeImageInput" class="d-none" accept="image/*">
                                <small class="d-block mt-1 text-muted">Click avatar to change</small>
                            </div>

                            {{-- Profile Info --}}
                            <div class="flex-grow-1">
                                <div class="row mb-3">
                                    <div class="col-md-6 position-relative">
                                        <label for="fullName" class="form-label">Full Name</label>
                                        <input type="text" class="form-control pe-5" id="fullName" name="full_name"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->full_name : '' }}" readonly>
                                        <button type="button"
                                                class="btn btn-sm position-absolute top-50 end-0 translate-middle-y edit-btn"
                                                data-target="#fullName">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Employee Number</label>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->employee_number : '' }}" readonly>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Position</label>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->position : '' }}" readonly>
                                    </div>
                                    <div class="col-md-6 position-relative">
                                        <label for="contactNo" class="form-label">Contact Number</label>
                                        <input type="text" class="form-control pe-5" id="contactNo" name="contact_no"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->contact_no : '' }}" readonly>
                                        <button type="button"
                                                class="btn btn-sm position-absolute top-50 end-0 translate-middle-y edit-btn"
                                                data-target="#contactNo">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Province</label>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->province : '' }}" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">City</label>
                                        <input type="text" class="form-control"
                                               value="{{ auth()->user()->employee ? auth()->user()->employee->city : '' }}" readonly>
                                    </div>
                                </div>

                                {{-- Save Button --}}
                                <div class="text-center mt-4">
                                    <button type="button" id="saveProfileBtn" class="btn btn-primary btn-lg">Save Changes</button>
                                </div>
                            </div>
                        </div>
                    </form>
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
    // Enable editing on click of pen icon
    $('.edit-btn').click(function() {
        let target = $($(this).data('target'));
        if(target.is('input')) {
            target.prop('readonly', false);
            target.focus();
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
        }
    });

    // Save changes
    $('#saveProfileBtn').on('click', function() {
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
