@extends('Layouts.vuexy')

@section('title', 'My Profile')

@section('content')
<div class="py-4">
    <div class="card shadow-sm">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h4 class="mb-0">My Profile</h4>
            <small class="text-muted"></small>
        </div>
        <div class="card-body">
            <form id="profileForm" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4 text-center">
                        <img id="profileAvatar"
                             src="{{ $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : asset('assets/default-avatar.png') }}"
                             alt="Profile Picture"
                             class="rounded-circle border"
                             style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                        <input type="file" name="profile_picture" id="profilePictureInput" class="d-none" accept="image/*">
                        <small class="d-block mt-1 text-muted">Click picture to change</small>
                        <h5 class="mt-3">{{ $profile->fullname }}</h5>
                        <p class="text-muted">{{ $profile->role }}</p>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <label for="fullname" class="form-label">Full Name</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="fullname" name="fullname"
                                               value="{{ $profile->fullname }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary edit-btn" data-target="#fullname">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Login ID</label>
                                    <input type="text" class="form-control"
                                           value="{{ $profile->login_id }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control"
                                           value="{{ ucfirst($profile->role) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                           value="{{ $profile->email }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3 position-relative">
                                    <label for="contactNo" class="form-label">Contact Number</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="contactNo" name="contact_no"
                                               value="{{ $profile->contact_no }}" readonly>
                                        <button type="button" class="btn btn-outline-secondary edit-btn" data-target="#contactNo">
                                            <i class="ti ti-pencil"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" rows="3" readonly>{{ $profile->province }}, {{ $profile->city }}, {{ $profile->barangay }}</textarea>
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
