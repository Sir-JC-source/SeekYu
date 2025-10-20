@extends('Layouts.vuexy')

@section('title', 'Pending Approval')

@section('content')

@push('page-styles')

<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

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


<div class="card">
    <div class="card-datatable table-responsive pt-0">
        <table class="datatables-basic table">
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Role</th>
                    <th>Student no</th>
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
    $(document).ready(function () {
        // Ensure loader is hidden initially
        $('#page-loader').addClass('hidden');
    });

    $(function () {
        var dt_basic_table = $('.datatables-basic');

        if (dt_basic_table.length) {
            dt_basic_table.DataTable({
                ajax: {
                    url: '{{ route("user-management.json.approval") }}',
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
                ],
                responsive: true,
                processing: true,
                serverSide: true,
                pageLength: 10
            });

            $('div.head-label').html('<h5 class="card-title mb-0">Pending Approval</h5>');
        }

        // Approve button click handler
        $(document).on('click', '.approve-btn', function () {
            var userId = $(this).data('id');
            approveUser(userId);
        });

        function approveUser(userId) {
            // Show loader
            $('#page-loader').removeClass('hidden');

            $.ajax({
                url: "{{ url('user-management/users/approve') }}/" + userId,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(res) {
                    // Hide loader
                    $('#page-loader').addClass('hidden');
                    
                    // Reload table data
                    $('.datatables-basic').DataTable().ajax.reload();
                    
                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Approved!',
                        text: res.message || 'User has been approved and credentials sent.',
                        confirmButtonClass: 'btn btn-success'
                    });
                },
                error: function(xhr) {
                    // Hide loader
                    $('#page-loader').addClass('hidden');
                    
                    let errorMessage = 'Something went wrong!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: errorMessage,
                        confirmButtonClass: 'btn btn-danger'
                    });
                }
            });
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