@extends('Layouts.vuexy')

@section('title', 'Create Employee')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        {{-- Neutral Header --}}
        <div class="card-header">
            <h5 class="mb-0">Create New Employee</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-4">
                    {{-- Left Column: Form --}}
                    <div class="col-md-6">

                        {{-- Employee Number --}}
                        <div class="mb-3">
                            <label for="employee_number" class="form-label">Employee No.</label>
                            <input type="text" 
                                   class="form-control @error('employee_number') is-invalid @enderror" 
                                   id="employee_number" 
                                   name="employee_number" 
                                   value="{{ old('employee_number', $employeeNumber) }}" 
                                   readonly>
                            @error('employee_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Full Name --}}
                        <div class="mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('full_name') is-invalid @enderror" 
                                   id="full_name" 
                                   name="full_name" 
                                   value="{{ old('full_name') }}" 
                                   required>
                            @error('full_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Position --}}
                        <div class="mb-3">
                            <label for="position" class="form-label">Position</label>
                            <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                                <option value="">Select Position</option>
                                @php $userRole = Auth::user()->getRoleNames()->first(); @endphp

                                @if($userRole === 'hr-officer')
                                    <option value="Head Guard" {{ old('position') == 'Head Guard' ? 'selected' : '' }}>Head Guard</option>
                                    <option value="Security Guard" {{ old('position') == 'Security Guard' ? 'selected' : '' }}>Security Guard</option>
                                @else
                                    <option value="Admin" {{ old('position') == 'Admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="HR Officer" {{ old('position') == 'HR Officer' ? 'selected' : '' }}>HR Officer</option>
                                    <option value="Head Guard" {{ old('position') == 'Head Guard' ? 'selected' : '' }}>Head Guard</option>
                                    <option value="Security Guard" {{ old('position') == 'Security Guard' ? 'selected' : '' }}>Security Guard</option>
                                @endif
                            </select>
                            @error('position')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Date Hired --}}
                        <div class="mb-3">
                            <label for="date_hired" class="form-label">Date Hired</label>
                            <input type="date" 
                                   class="form-control @error('date_hired') is-invalid @enderror" 
                                   id="date_hired" 
                                   name="date_hired" 
                                   value="{{ old('date_hired') }}" 
                                   max="{{ date('Y-m-d') }}" 
                                   required>
                            @error('date_hired')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Status (hidden) --}}
                        <input type="hidden" name="status" value="Active">

                        {{-- Upload Image --}}
                        <div class="mb-3">
                            <label for="employee_image" class="form-label">Upload Image</label>
                            <input type="file" 
                                   class="form-control @error('employee_image') is-invalid @enderror" 
                                   id="employee_image" 
                                   name="employee_image" 
                                   accept="image/*">
                            @error('employee_image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Submit & Cancel --}}
                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary me-2">Create Employee</button>
                            <a href="{{ route('employee.list') }}" class="btn btn-secondary">Cancel</a>
                        </div>

                    </div>

                    {{-- Right Column: Image Preview --}}
                    <div class="col-md-6 d-flex justify-content-center align-items-center">
                        <div id="image_preview_container"
                             class="border border-dashed rounded-3 d-flex justify-content-center align-items-center"
                             style="width: 200px; height: 200px; background-color: #f8f9fa; overflow: hidden;">
                            {{-- Placeholder Icon --}}
                            <i id="placeholder_icon" class="ti ti-photo" style="font-size: 60px; color: #aaa;"></i>

                            {{-- Image Preview --}}
                            <img id="preview_image" 
                                 src="" 
                                 alt="Preview" 
                                 style="display: none; width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('page-scripts')
<script>
    // Image preview
    const inputImage = document.getElementById('employee_image');
    const previewImage = document.getElementById('preview_image');
    const placeholderIcon = document.getElementById('placeholder_icon');

    inputImage.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                placeholderIcon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.src = '';
            previewImage.style.display = 'none';
            placeholderIcon.style.display = 'block';
        }
    });
</script>
@endpush
