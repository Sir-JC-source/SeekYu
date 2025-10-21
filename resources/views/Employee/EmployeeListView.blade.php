@extends('Layouts.vuexy')

@section('title', 'Employee List')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Employee List</h5>
    </div>
    <div class="card-body">
        <table class="table table-striped table-bordered text-center" id="employee-table">
            <thead>
                <tr>
                    <th>Employee No.</th>
                    <th>Full Name</th>
                    <th>Position</th>
                    <th>Date Hired</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($employees as $employee)
                <tr class="employee-row" 
                    data-id="{{ $employee->id }}"
                    data-number="{{ $employee->employee_number }}"
                    data-name="{{ $employee->full_name }}"
                    data-position="{{ $employee->position }}"
                    data-date="{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}"
                    data-status="{{ strtolower($employee->status) }}"
                    data-image="{{ $employee->employee_image ? asset('storage/' . $employee->employee_image) : asset('assets/default-avatar.png') }}">
                    <td>{{ $employee->employee_number }}</td>
                    <td>{{ $employee->full_name }}</td>
                    <td>
                        @php
                            $positionColors = [
                                'Admin' => 'badge bg-primary',
                                'HR Officer' => 'badge bg-info',
                                'Head Guard' => 'badge bg-warning text-dark',
                                'Security Guard' => 'badge bg-secondary'
                            ];
                        @endphp
                        <span class="{{ $positionColors[$employee->position] ?? 'badge bg-light text-dark' }}">
                            {{ $employee->position }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}</td>
                    <td>
                        @if(strtolower($employee->status) == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-secondary">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="actionDropdown{{ $employee->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $employee->id }}">
                                <li>
                                    <button class="dropdown-item view-employee-btn" 
                                            data-id="{{ $employee->id }}"
                                            data-number="{{ $employee->employee_number }}"
                                            data-name="{{ $employee->full_name }}"
                                            data-position="{{ $employee->position }}"
                                            data-date="{{ \Carbon\Carbon::parse($employee->date_hired)->format('M d, Y') }}"
                                            data-status="{{ strtolower($employee->status) }}"
                                            data-image="{{ $employee->employee_image ? asset('storage/' . $employee->employee_image) : asset('assets/default-avatar.png') }}">
                                        <i class="ti ti-eye"></i> View Employee Card
                                    </button>
                                </li>
                                <li>
                                    <button class="dropdown-item text-danger delete-employee-btn" data-id="{{ $employee->id }}">
                                        <i class="ti ti-trash"></i> Terminate
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Compact Employee Modal --}}
<div class="modal fade" id="employeeModal" tabindex="-1" aria-labelledby="employeeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 380px;">
    <div class="modal-content p-3 shadow-sm border-0 rounded-3">
      <div class="modal-header border-0 pb-0 text-center d-block">
        <h6 class="modal-title fw-semibold" id="employeeModalLabel">Employee Details</h6>
      </div>
      <div class="modal-body text-center py-2">
        <div class="mb-2">
            <img id="modalImage" src="" alt="Employee Image" 
                 style="width:90px; height:90px; object-fit:cover; border-radius:50%; border:2px solid #ddd;">
        </div>
        <div class="text-start mx-auto" style="max-width: 280px;">
            <p class="mb-1"><strong>Employee No.:</strong> <span id="modalNumber"></span></p>
            <p class="mb-1"><strong>Full Name:</strong> <span id="modalName"></span></p>
            <p class="mb-1"><strong>Position:</strong> <span id="modalPosition"></span></p>
            <p class="mb-1"><strong>Date Hired:</strong> <span id="modalDate"></span></p>
            <p class="mb-0"><strong>Status:</strong> <span id="modalStatus"></span></p>
        </div>
      </div>
      <div class="modal-footer border-0 pt-1 pb-2 justify-content-center">
        <button type="button" class="btn btn-secondary btn-sm px-4" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- Employee Profile Modal Updated --}}
<div class="modal fade" id="employeeProfileModal" tabindex="-1" aria-labelledby="employeeProfileLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-sm" style="border-radius:12px; overflow:hidden; max-width:600px;">
      <div style="background-color:#f7f7f7; padding:20px 25px 20px 20px; position:relative;">
          
          {{-- Close X Button in upper right --}}
          <button type="button" class="btn-close position-absolute top-2 end-0 mt-2 me-4" data-bs-dismiss="modal" aria-label="Close"></button>
          
          {{-- Logo --}}
          <div class="text-center mb-3">
              <img src="{{ asset('storage/logo.png') }}" alt="Company Logo" style="height:65px; object-fit:contain;">
          </div>

          {{-- Body --}}
          <div style="display:flex; gap:20px; align-items:flex-start;">
              <div style="flex-shrink:0;">
                  <img id="profileImage" src="" alt="Employee Image" 
                       style="width:150px; height:150px; object-fit:cover; border-radius:10px; border:2px solid #ddd;">
              </div>
              <div style="flex-grow:1; display:flex; flex-direction:column; justify-content:space-between; height:150px;">
                  <input type="text" class="form-control bg-light border-0 mb-1" id="profileName" readonly style="max-width:320px;">
                  <input type="text" class="form-control bg-light border-0 mb-1" id="profilePosition" readonly style="max-width:320px;">
                  <input type="text" class="form-control bg-light border-0 mb-1" id="profileNumber" readonly style="max-width:320px;">
                  <input type="text" class="form-control bg-light border-0" id="profileDate" readonly style="max-width:320px;">
              </div>
          </div>

          {{-- EMPLOYEE CARD Text --}}
          <div class="text-center mt-3 fw-bold" style="color:#555;">EMPLOYEE CARD</div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('page-scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    tr.employee-row:hover { background-color: #f0f8ff; cursor: pointer; }
    .modal-content { border-radius: 12px; background-color: #fff; }
    .modal-body p { font-size: 0.875rem; color: #333; }
    .form-control[readonly] {
        background-color: #f1f1f1;
        font-size: 0.9rem;
        color: #333;
        cursor: default;
    }
</style>

<script>
$(document).ready(function() {
    var table = $('#employee-table').DataTable({
        "order": [[ 1, "asc" ]]
    });

    // Clickable rows to open compact modal
    $('#employee-table tbody').on('click', 'tr.employee-row', function(e) {
        if($(e.target).closest('.dropdown, .delete-employee-btn').length) return;

        var row = $(this);
        $('#modalNumber').text(row.data('number'));
        $('#modalName').text(row.data('name'));
        $('#modalPosition').text(row.data('position'));
        $('#modalDate').text(row.data('date'));
        var status = row.data('status');
        var statusBadge = status === 'active' 
            ? '<span class="badge bg-success">Active</span>' 
            : '<span class="badge bg-secondary">Inactive</span>';
        $('#modalStatus').html(statusBadge);
        $('#modalImage').attr('src', row.data('image'));
        $('#employeeModal').modal('show');
    });

    // View button to open new profile modal
    $(document).on('click', '.view-employee-btn', function() {
        var btn = $(this);
        $('#profileImage').attr('src', btn.data('image'));
        $('#profileName').val('Full Name: ' + btn.data('name'));
        $('#profileNumber').val('Employee No: ' + btn.data('number'));
        $('#profilePosition').val('Position: ' + btn.data('position'));
        $('#profileDate').val('Date Hired: ' + btn.data('date'));
        $('#employeeProfileModal').modal('show');
    });

    // Delete employee with SweetAlert2
    $(document).on('click', '.delete-employee-btn', function() {
        var employeeId = $(this).data('id');
        var row = $(this).closest('tr');

        Swal.fire({
            title: 'Are you sure?',
            text: "Do you really want to terminate this employee?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No.'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("employee") }}/' + employeeId,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.row(row).remove().draw();
                        Swal.fire('Deleted!', 'Employee has been deleted.', 'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Something went wrong. Please try again.', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endpush
