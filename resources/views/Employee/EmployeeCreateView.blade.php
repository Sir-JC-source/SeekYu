@extends('Layouts.vuexy')

@section('title', 'Add Employee')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm rounded-3">
        <div class="card-header">
            <h5 class="mb-0">Add Employee</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Left Column: Fields --}}
                    <div class="col-md-8">
                        <div class="row g-2">
                            {{-- Employee No --}}
                            <div class="col-md-6">
                                <label for="employee_number" class="form-label">Employee No.</label>
                                <input type="text" class="form-control @error('employee_number') is-invalid @enderror" 
                                       id="employee_number" name="employee_number" 
                                       value="{{ old('employee_number', $employeeNumber) }}" readonly>
                                @error('employee_number')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Full Name --}}
                            <div class="col-md-6">
                                <label for="full_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('full_name') is-invalid @enderror" 
                                       id="full_name" name="full_name" value="{{ old('full_name') }}" required>
                                @error('full_name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Position --}}
                            <div class="col-md-6">
                                <label for="position" class="form-label">Position</label>
                                <select class="form-select @error('position') is-invalid @enderror" 
                                        id="position" name="position" required>
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
                            <div class="col-md-6">
                                <label for="date_hired" class="form-label">Date Hired</label>
                                <input type="date" class="form-control @error('date_hired') is-invalid @enderror" 
                                       id="date_hired" name="date_hired" value="{{ old('date_hired') }}" 
                                       max="{{ date('Y-m-d') }}" required>
                                @error('date_hired')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Contact No --}}
                            <div class="col-md-6">
                                <label for="contact_no" class="form-label">Contact No.</label>
                                <input type="text" class="form-control @error('contact_no') is-invalid @enderror" 
                                       id="contact_no" name="contact_no" value="{{ old('contact_no') }}" 
                                       maxlength="11" placeholder="09XXXXXXXXX" required>
                                @error('contact_no')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Province --}}
                            <div class="col-md-6">
                                <label for="province" class="form-label">Province</label>
                                <select class="form-select @error('province') is-invalid @enderror" 
                                        id="province" name="province" required>
                                    <option value="">Select Province</option>
                                </select>
                                @error('province')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- City --}}
                            <div class="col-md-6">
                                <label for="city" class="form-label">City / Municipality</label>
                                <select class="form-select @error('city') is-invalid @enderror" 
                                        id="city" name="city" required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Upload Image --}}
                            <div class="col-md-6">
                                <label for="employee_image" class="form-label">Upload Image</label>
                                <input type="file" class="form-control @error('employee_image') is-invalid @enderror" 
                                       id="employee_image" name="employee_image" accept="image/*">
                                @error('employee_image')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- Submit Button --}}
                            <div class="col-12 mt-2">
                                <input type="hidden" name="status" value="Active">
                                <button type="submit" class="btn btn-primary me-2">Create Employee</button>
                                <a href="{{ route('employee.list') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column: Image Preview --}}
                    <div class="col-md-4 d-flex justify-content-center align-items-start">
                        <div class="border border-dashed rounded-3 d-flex justify-content-center align-items-center" 
                             style="width: 150px; height: 150px; background-color: #f8f9fa; overflow: hidden;">
                            <i id="placeholder_icon" class="ti ti-photo" style="font-size: 50px; color: #aaa;"></i>
                            <img id="preview_image" src="" alt="Preview" 
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
    // Image Preview
    const inputImage = document.getElementById('employee_image');
    const previewImage = document.getElementById('preview_image');
    const placeholderIcon = document.getElementById('placeholder_icon');

    inputImage.addEventListener('change', function(event){
        const file = event.target.files[0];
        if(file){
            const reader = new FileReader();
            reader.onload = e => {
                previewImage.src = e.target.result;
                previewImage.style.display = 'block';
                placeholderIcon.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.style.display = 'none';
            placeholderIcon.style.display = 'block';
        }
    });

    // Contact number only
    const contactInput = document.getElementById('contact_no');
    contactInput.addEventListener('input', function(){
        this.value = this.value.replace(/\D/g,'').slice(0,11);
    });

    // Provinces & Cities
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');

    fetch('{{ asset("js/philippines/provinces.json") }}')
        .then(res => res.json())
        .then(data => {
            data.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name;
                option.text = province.name;
                provinceSelect.appendChild(option);
            });
        });

    provinceSelect.addEventListener('change', function(){
        const selected = this.value;
        citySelect.innerHTML = '<option value="">Select City</option>';
        fetch('{{ asset("js/philippines/cities.json") }}')
            .then(res => res.json())
            .then(data => {
                data.filter(city => city.province === selected)
                    .forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.text = city.name;
                        citySelect.appendChild(option);
                    });
            });
    });

    // Toast Notifications
    @if(session('success'))
    Toastify({
        text: "{{ session('success') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "#28a745",
    }).showToast();
    @endif
    @if(session('error'))
    Toastify({
        text: "{{ session('error') }}",
        duration: 3000,
        gravity: "top",
        position: "right",
        backgroundColor: "#dc3545",
    }).showToast();
    @endif
</script>
@endpush
