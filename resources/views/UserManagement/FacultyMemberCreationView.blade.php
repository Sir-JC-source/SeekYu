    @extends('Layouts.vuexy')

    @section('title', 'Faculty Member Creation')

    @section('content')

    @push('page-styles')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/form-validation.css') }}" />
    
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/spinkit/spinkit.css') }}" />

    <style>
        #page-loader.hidden {
            display: none !important;
        }
    </style>   
    @endpush

    {{--  Success Notification --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-checks me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Notification --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Page Loader (Spinkit Circle) -->
    <div id="page-loader" class="d-flex justify-content-center align-items-center position-fixed top-0 start-0 w-100 h-100 hidden" style="z-index: 1050; background-color: rgba(0, 0, 0, 0.5);">
        <div class="sk-circle sk-primary">
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
            <div class="sk-circle-dot"></div>
        </div>
    </div>

    <div class="row">
        <!-- FormValidation -->
            <div class="col-12">
                <div class="card">
                    <h5 class="card-header"></h5>
                    <div class="card-body">
                        <form id="formValidationExamples" action="{{ isset($user->id) ? route('user-management.update' , $user->id) : route('user-management.faculty-creation.store') }}" method="POST" class="row g-3">
                            <!-- Account Details -->
                            
                            @csrf
                            @if(isset($user->id))
                                @method('PUT')
                            @endif

                            <div class="col-12">
                                <h6>1. Account Details 
                                    @if(isset($user))
                                        | {{ $user->role === 'Student' ? 'Update Student' : 'Update Faculty Member' }}
                                    @endif
                                </h6>
                                <hr class="mt-0" />
                            </div>

                            @if(isset($user) && $user->role === 'Student')
                                <div class="col-md-6">
                                    <label class="form-label" for="student_no">Student No.</label>
                                    <input
                                        type="text"
                                        id="student_no"
                                        class="form-control"
                                        placeholder="e.g. 20231234"
                                        name="student_no"
                                        value="{{ $user->student_no  ?? '' }}"
                                        readonly
                                    />
                                </div>
                            @endif

                            @if(isset($user) && $user->role === 'Faculty')
                                <div class="col-md-6">
                                    <label class="form-label" for="faculty_no">Faculty No.</label>
                                    <input
                                        type="text"
                                        id="faculty_no"
                                        class="form-control"
                                        placeholder="e.g. 43524"
                                        name="faculty_no"
                                        value="{{ $user->faculty_no ?? '' }}"
                                        readonly
                                    />
                                </div>
                            @endif


                            <div class="col-md-6">
                                <label class="form-label" for="fullname">Full Name</label>
                                <input
                                type="text"
                                id="fullname"
                                class="form-control"
                                placeholder="John Doe"
                                name="fullname"
                                value="{{ $user->fullname ?? '' }}" />
                            </div>
                            @error('fullname')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <div class="col-md-6">
                                <label class="form-label" for="address">Address</label>
                                <input
                                type="text"
                                id="address"
                                class="form-control"
                                placeholder="123 Main St, City, Country"
                                name="address"
                                value="{{ $user->address ?? '' }}" />
                            </div>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @if(!isset($user))
                                <div class="col-md-6">
                                    <label class="form-label" for="facultyNo">Faculty No.</label>
                                    <input
                                        type="text"
                                        id="facultyNo"
                                        class="form-control"
                                        placeholder="eg.43524"
                                        name="facultyNo"
                                        value="" />
                                </div>
                                @error('facultyNo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endif

                            <div class="col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input
                                class="form-control"
                                type="email"
                                id="email"
                                name="email"
                                placeholder="john.doe@gmail.com"
                                value="{{ $user->email ?? '' }}" />
                            </div>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror


                            <div class="col-12">
                                <button type="submit" name="submitButton" class="btn btn-primary">{{ isset($user) ? 'Update' : 'Create' }}</button>
                                <a href="{{ route('user-management.index') }}" class="btn btn-label-secondary waves-effect">Discard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <!-- /FormValidation -->
    </div>


    @endsection

    @push('page-scripts')

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        $(document).ready(function () {
            $('#page-loader').addClass('hidden');

            // Get the operation type from Blade
            const isUpdate = {{ isset($user) ? 'true' : 'false' }};

            $('#formValidationExamples').on('submit', function (e) {
                e.preventDefault(); // prevent normal submit

                $('#page-loader').removeClass('hidden'); // show loader

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {
                        $('#page-loader').addClass('hidden'); // hide loader

                        Swal.fire({
                            icon: 'success',
                            title: isUpdate ? 'Updated!' : 'Created!',
                            text: res.message,
                            confirmButtonClass: 'btn btn-success'
                        }).then(() => {
                            // Redirect after user clicks OK
                            window.location.href = "{{ route('user-management.index') }}";
                        });
                    },
                    error: function (xhr) {
                        $('#page-loader').addClass('hidden'); // hide loader

                        let errorMessage = 'Something went wrong!';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: errorMessage,
                            confirmButtonClass: 'btn btn-danger'
                        });
                    }
                });
            });
        });
    </script>

    @endpush