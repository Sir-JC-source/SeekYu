    @extends('Layouts.vuexy')

    @section('title', 'List')

    @section('content')

    @push('page-styles')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

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

    <div class="card">
        <div class="card-datatable table-responsive pt-0">
            <table class="datatables-basic table">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Role</th>
                        <th>Student no</th>
                        <th>Faculty no</th>
                        <th>Email</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    @endsection

    @push('page-scripts')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        $(function () {
            var dt_basic_table = $('.datatables-basic');

            if (dt_basic_table.length) {
                dt_basic_table.DataTable({
                    ajax: {
                        url: '{{ route("user-management.json") }}',
                        type: 'GET',
                        error: function(xhr, error, code) {
                            console.log('AJAX Error:', xhr, error, code);
                            alert('Error loading data: ' + xhr.responseText);
                        }
                    },
                    columns: [
                        { data: 'fullname' },
                        { data: 'role' },
                        { data: 'student_no' },
                        { data: 'faculty_no' },
                        { data: 'email' },
                        { data: 'address' },
                        { data: 'account_status' },
                        { data: 'action', orderable: false, searchable: false }
                    ],
                    dom: '<"card-header flex-column flex-md-row"<"head-label text-center"><"dt-action-buttons text-end"B>>' +
                        '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>' +
                        't<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                    buttons: [
                        {
                            extend: 'collection',
                            className: 'btn btn-label-primary dropdown-toggle me-2 waves-effect waves-light',
                            text: '<i class="ti ti-file-export me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span>',
                            buttons: ['excel']
                        },
                        {
                            text: '<i class="ti ti-plus me-sm-1"></i> <span class="d-none d-sm-inline-block">Add Faculty User</span>',
                            className: 'btn btn-primary waves-effect waves-light',
                            action: function () {
                                window.location.href = '{{ route("user-management.faculty-creation.index") }}';
                            }
                        }
                    ],
                    responsive: true,
                    processing: true,
                    serverSide: true,
                    pageLength: 10
                });

                $('div.head-label').html('<h5 class="card-title mb-0">Users</h5>');
            }
        });

    // Delete user function
    function deleteUser(deleteUrl) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(deleteUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: '_method=DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    Swal.fire('Deleted!', data.message, 'success');
                    $('.datatables-basic').DataTable().ajax.reload(null, false);
                })
                .catch(err => {
                    console.error('Delete error:', err);
                    Swal.fire('Error', 'Something went wrong.', 'error');
                });
            }
        });
    }




    </script>
    @endpush