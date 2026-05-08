@extends('Layouts.vuexy')

@section('title', 'Add Employee')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="card-title mb-0 d-flex align-items-center">
                        <i class="bx bx-user-plus me-2"></i>Add New Employee
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form id="employeeForm" action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Progress Indicator -->
                        <div class="progress mb-4" style="height: 8px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 33%" id="progressBar" aria-valuenow="33" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <!-- Tabs Navigation -->
                        <ul class="nav nav-pills nav-fill mb-4" id="employeeTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="true">
                                    <i class="bx bx-user me-1"></i>Profile
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="miscellaneous-tab" data-bs-toggle="tab" data-bs-target="#miscellaneous" type="button" role="tab" aria-controls="miscellaneous" aria-selected="false">
                                    <i class="bx bx-cog me-1"></i>Miscellaneous
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="account-creation-tab" data-bs-toggle="tab" data-bs-target="#account-creation" type="button" role="tab" aria-controls="account-creation" aria-selected="false">
                                    <i class="bx bx-key me-1"></i>Account Creation
                                </button>
                            </li>
                        </ul>

                        <!-- Tabs Content -->
                        <div class="tab-content" id="employeeTabsContent">
                            <!-- Profile Tab -->
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label fw-bold">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="first_name" name="first_name" placeholder="Enter first name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="middle_name" class="form-label fw-bold">Middle Name</label>
                                        <input type="text" class="form-control form-control-lg" id="middle_name" name="middle_name" placeholder="Enter middle name (optional)">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label fw-bold">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="last_name" name="last_name" placeholder="Enter last name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="age" class="form-label fw-bold">Age <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-lg" id="age" name="age" min="18" max="100" placeholder="Enter age" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="province" class="form-label fw-bold">Province <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="province" name="province" required>
                                            <option value="">Select Province</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="city" class="form-label fw-bold">City <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="city" name="city" required>
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="barangay" class="form-label fw-bold">Barangay <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="barangay" name="barangay" required disabled>
                                            <option value="">Select Barangay</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="Enter email address" required>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end mt-4">
                                    <button type="button" class="btn btn-primary btn-lg next-tab" data-next="miscellaneous">
                                        <i class="bx bx-chevron-right me-1"></i>Next
                                    </button>
                                </div>
                            </div>

                            <!-- Miscellaneous Tab -->
                            <div class="tab-pane fade" id="miscellaneous" role="tabpanel" aria-labelledby="miscellaneous-tab">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="date_hired" class="form-label fw-bold">Date Hired <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-lg" id="date_hired" name="date_hired" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="position" class="form-label fw-bold">Position <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="position" name="position" required>
                                            <option value="">Select Position</option>
                                            @if(auth()->user()->role === 'super-admin')
                                                <option value="Administrator">Administrator</option>
                                            @elseif(auth()->user()->role === 'admin')
                                                <option value="HR Officer">HR Officer</option>
                                                <option value="Security Guard">Security Guard</option>
                                                <option value="Head Guard">Head Guard</option>
                                            @elseif(auth()->user()->role === 'hr-officer')
                                                <option value="Security Guard">Security Guard</option>
                                                <option value="Head Guard">Head Guard</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="employee_number" class="form-label fw-bold">Employee No. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg" id="employee_number" name="employee_number" value="{{ $employeeNumber }}" readonly required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="employee_image" class="form-label fw-bold">Employee Image</label>
                                        <input type="file" class="form-control form-control-lg" id="employee_image" name="employee_image" accept="image/*">
                                        <div class="mt-3 text-center">
                                            <div class="border rounded p-3" style="width: 120px; height: 120px; margin: 0 auto; display: flex; align-items: center; justify-content: center;">
                                                <img id="preview_image" src="#" alt="Preview" style="max-width: 100%; max-height: 100%; display: none; border-radius: 8px;">
                                                <i id="placeholder_icon" class="bx bx-image-alt text-muted" style="font-size: 48px;"></i>
                                            </div>
                                            <small class="text-muted mt-1 d-block">Upload a profile image</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg prev-tab" data-prev="profile">
                                        <i class="bx bx-chevron-left me-1"></i>Previous
                                    </button>
                                    <button type="button" class="btn btn-primary btn-lg next-tab" data-next="account-creation">
                                        <i class="bx bx-chevron-right me-1"></i>Next
                                    </button>
                                </div>
                            </div>

                            <!-- Account Creation Tab -->
                            <div class="tab-pane fade" id="account-creation" role="tabpanel" aria-labelledby="account-creation-tab">
                                <div class="alert alert-info" role="alert">
                                    <i class="bx bx-info-circle me-1"></i>
                                    <strong>Note:</strong> Login credentials will be auto-generated and sent to the employee's email after submission.
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="login_id" class="form-label fw-bold">Login ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-lg bg-light" id="login_id" name="login_id" readonly required>
                                        <small class="form-text text-muted">Auto-generated from employee number</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="password" class="form-control form-control-lg bg-light" id="password" name="password" readonly required>
                                            <button class="btn btn-outline-secondary" type="button" id="toggleEmployeePassword" aria-label="Show password" title="Show/Hide password">
                                                <i class="bx bx-show"></i>
                                            </button>
                                        </div>
                                        <small class="form-text text-muted">Auto-generated secure password</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary btn-lg prev-tab" data-prev="miscellaneous">
                                        <i class="bx bx-chevron-left me-1"></i>Previous
                                    </button>
                                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                                        <i class="bx bx-check me-1"></i>Submit & Create Employee
                                    </button>
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
<script>
    // Generate random password
    function generatePassword() {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        let password = '';
        for (let i = 0; i < 10; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        return password;
    }

    // Auto-generate login ID and password
    function updateCredentials() {
        const employeeNumber = document.getElementById('employee_number').value;
        const loginIdField = document.getElementById('login_id');
        const passwordField = document.getElementById('password');

        if (employeeNumber) {
            loginIdField.value = employeeNumber;
            passwordField.value = generatePassword();
        }
    }

    // Show/Hide generated password
    (function () {
        const passwordInput = document.getElementById('password');
        const toggleBtn = document.getElementById('toggleEmployeePassword');
        if (!passwordInput || !toggleBtn) return;

        toggleBtn.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            // bx-show to bx-hide toggling
            this.innerHTML = isHidden ? '<i class="bx bx-hide"></i>' : '<i class="bx bx-show"></i>';
        });
    })();

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

    // Location Dropdown using PSGC API
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');

    // Load provinces on page load
    async function loadProvinces() {
        try {
            const response = await fetch('https://psgc.gitlab.io/api/provinces/');
            const provinces = await response.json();
            // Add Metro Manila as NCR
            provinces.push({ name: 'Metro Manila (NCR)', code: '130000000' });
            // Sort provinces alphabetically
            provinces.sort((a, b) => a.name.localeCompare(b.name));
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name; // Use name instead of code for simplicity
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }

    // Load cities/municipalities for selected province
    provinceSelect.addEventListener('change', async function() {
        citySelect.innerHTML = '<option value="">Select City</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;

        if (this.value) {
            try {
                if (this.value === 'Metro Manila (NCR)') {
                    // Load NCR cities from PSGC API using region endpoint
                    const response = await fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/');
                    const cities = await response.json();
                    // Sort cities alphabetically
                    cities.sort((a, b) => a.name.localeCompare(b.name));
                    citySelect.disabled = false;
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name; // Use name
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } else {
                    // Find province code by name
                    const provinceResponse = await fetch('https://psgc.gitlab.io/api/provinces/');
                    const provinces = await provinceResponse.json();
                    const selectedProvince = provinces.find(p => p.name === this.value);
                    if (selectedProvince) {
                        const response = await fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvince.code}/cities-municipalities/`);
                        const cities = await response.json();
                        // Sort cities alphabetically
                        cities.sort((a, b) => a.name.localeCompare(b.name));
                        citySelect.disabled = false;
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name; // Use name instead of code
                            option.textContent = city.name;
                            citySelect.appendChild(option);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading cities:', error);
                citySelect.disabled = true;
            }
        } else {
            citySelect.disabled = true;
        }
    });

    // Load barangays for selected city/municipality
    citySelect.addEventListener('change', async function() {
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';

        if (this.value) {
            try {
                const provinceValue = provinceSelect.value;
                if (provinceValue === 'Metro Manila (NCR)') {
                    // Load barangays for NCR cities
                    const regionResponse = await fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/');
                    const cities = await regionResponse.json();
                    const selectedCity = cities.find(c => c.name === this.value);
                    if (selectedCity) {
                        const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCity.code}/barangays/`);
                        const barangays = await response.json();
                        // Sort barangays alphabetically
                        barangays.sort((a, b) => a.name.localeCompare(b.name));
                        barangaySelect.disabled = false;
                        barangays.forEach(barangay => {
                            const option = document.createElement('option');
                            option.value = barangay.name;
                            option.textContent = barangay.name;
                            barangaySelect.appendChild(option);
                        });
                    }
                } else {
                    // Find city code by name
                    const provinceResponse = await fetch('https://psgc.gitlab.io/api/provinces/');
                    const provinces = await provinceResponse.json();
                    const selectedProvince = provinces.find(p => p.name === provinceValue);
                    if (selectedProvince) {
                        const citiesResponse = await fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvince.code}/cities-municipalities/`);
                        const cities = await citiesResponse.json();
                        const selectedCity = cities.find(c => c.name === this.value);
                        if (selectedCity) {
                            const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCity.code}/barangays/`);
                            const barangays = await response.json();
                            // Sort barangays alphabetically
                            barangays.sort((a, b) => a.name.localeCompare(b.name));
                            barangaySelect.disabled = false;
                            barangays.forEach(barangay => {
                                const option = document.createElement('option');
                                option.value = barangay.name; // Use name instead of code
                                option.textContent = barangay.name;
                                barangaySelect.appendChild(option);
                            });
                        }
                    }
                }
            } catch (error) {
                console.error('Error loading barangays:', error);
                barangaySelect.disabled = true;
            }
        } else {
            barangaySelect.disabled = true;
        }
    });

    // Initialize provinces on load
    loadProvinces();

    // Update progress bar
    function updateProgressBar(activeTab) {
        const progressBar = document.getElementById('progressBar');
        let progress = 33;
        if (activeTab === 'miscellaneous') {
            progress = 66;
        } else if (activeTab === 'account-creation') {
            progress = 100;
        }
        progressBar.style.width = progress + '%';
        progressBar.setAttribute('aria-valuenow', progress);
    }

    // Tab Navigation
    document.querySelectorAll('.next-tab').forEach(button => {
        button.addEventListener('click', function() {
            const nextTab = this.getAttribute('data-next');
            const currentTab = this.closest('.tab-pane').id;
            if (validateTab(currentTab)) {
                const tab = new bootstrap.Tab(document.getElementById(nextTab + '-tab'));
                tab.show();
                updateProgressBar(nextTab);
                if (nextTab === 'account-creation') {
                    updateCredentials();
                }
            }
        });
    });

    document.querySelectorAll('.prev-tab').forEach(button => {
        button.addEventListener('click', function() {
            const prevTab = this.getAttribute('data-prev');
            const tab = new bootstrap.Tab(document.getElementById(prevTab + '-tab'));
            tab.show();
            updateProgressBar(prevTab);
        });
    });

    // Tab Validation
    function validateTab(tabId) {
        const tab = document.getElementById(tabId);
        const requiredFields = tab.querySelectorAll('input[required], select[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });

        return isValid;
    }

    // Enable/Disable Next buttons based on validation
    document.querySelectorAll('input[required], select[required]').forEach(field => {
        field.addEventListener('input', function() {
            const tabId = this.closest('.tab-pane').id;
            const nextButton = document.querySelector(`[data-next="${tabId === 'profile' ? 'miscellaneous' : 'account-creation'}"]`);
            if (nextButton) {
                nextButton.disabled = !validateTab(tabId);
            }
        });
    });

    // Form submission with toast notification
    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Creating Employee...';

        // Show immediate toast notification
        Toastify({
            text: "Creating employee... Please wait.",
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: "#007bff",
        }).showToast();
    });

    // Toast Notifications for session messages
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
