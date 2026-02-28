@extends('Layouts.auth')

@section('title', 'Register - SeekYu HRIS')

@section('content')

<style>
/* ===== Modern Two-Panel Register Design ===== */
body {
    background-color: #0f172a;
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
}

.register-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Left side (form panel) */
.register-form-panel {
    flex: 1;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem 2rem;
    overflow-y: auto;
}

.register-card {
    width: 100%;
    max-width: 520px;
}

.register-card h4 {
    font-weight: 600;
    color: #111827;
}

.register-card p {
    color: #6b7280;
}

.register-card .form-label {
    color: #374151;
    font-weight: 500;
}

.register-card .form-control {
    background-color: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.625rem 0.75rem;
    color: #111827;
}

.register-card .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.25);
}

.register-card .btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.register-card .btn-primary:hover {
    background-color: #4338ca;
}

.register-card a {
    color: #4f46e5;
    text-decoration: none;
}

.register-card a:hover {
    text-decoration: underline;
}

/* Right side (black hero panel) */
.register-hero {
    flex: 1;
    background-color: #000;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem;
    text-align: left;
}

.register-hero h1 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 1.5rem;
}

.register-hero p {
    font-size: 1.05rem;
    line-height: 1.6;
    color: #e5e5e5;
    max-width: 420px;
}

/* Form Section Grouping */
.form-section {
    margin-bottom: 1.5rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid #e5e7eb;
}

.form-section:last-of-type {
    border-bottom: none;
}

.form-section-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #4f46e5;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-section-title::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 16px;
    background-color: #4f46e5;
    border-radius: 2px;
}

/* Responsive design */
@media (max-width: 992px) {
    .register-wrapper {
        flex-direction: column-reverse;
    }
    .register-hero {
        align-items: center;
        text-align: center;
        padding: 2.5rem;
    }
    .register-hero p {
        max-width: 100%;
    }
}
</style>

<div class="register-wrapper">
    <!-- Left Panel (Form) -->
    <div class="register-form-panel">
        <div class="register-card mx-auto">
            <div class="text-center mb-4">
                <h4>Security Personnel/HR Officer Registration</h4>
                <p class="mb-0">Apply as Security Personnel/HR Officer </p>
            </div>

            {{-- Success / Error Messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}">
                @csrf

                {{-- Section 1: Personal Information --}}
                <div class="form-section">
                    <div class="form-section-title">Personal Information</div>
                    
                    {{-- Full Name --}}
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <div class="d-flex flex-wrap gap-2">
                            <input type="text"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   id="last_name"
                                   name="last_name"
                                   placeholder="Last Name"
                                   value="{{ old('last_name') }}"
                                   required>
                            <input type="text"
                                   class="form-control @error('middle_name') is-invalid @enderror"
                                   id="middle_name"
                                   name="middle_name"
                                   placeholder="Middle Name (Optional)"
                                   value="{{ old('middle_name') }}">
                            <input type="text"
                                   class="form-control @error('first_name') is-invalid @enderror"
                                   id="first_name"
                                   name="first_name"
                                   placeholder="First Name"
                                   value="{{ old('first_name') }}"
                                   required>
                        </div>
                        @error('last_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        @error('middle_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        @error('first_name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    {{-- Birthdate and Age --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="birthdate" class="form-label">Birthdate <span class="text-danger">*</span></label>
                            <input type="date"
                                   class="form-control @error('birthdate') is-invalid @enderror"
                                   id="birthdate"
                                   name="birthdate"
                                   value="{{ old('birthdate') }}"
                                   required>
                            @error('birthdate')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="age" class="form-label">Age <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('age') is-invalid @enderror"
                                   id="age"
                                   name="age"
                                   value="{{ old('age') }}"
                                   readonly
                                   placeholder="Auto-calculated">
                            @error('age')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if(!$errors->has('age'))
                                <small class="text-muted">Auto-calculated from birthdate</small>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Section 2: Contact Information --}}
                <div class="form-section">
                    <div class="form-section-title">Contact Information</div>
                    
                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               placeholder="Enter your email address"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Your login credentials will be sent to this email</small>
                    </div>

                    {{-- Contact Number --}}
                    <div class="mb-3">
                        <label for="contact_number" class="form-label">Contact Number <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">+63</span>
                            <input type="text"
                                   class="form-control @error('contact_number') is-invalid @enderror"
                                   id="contact_number"
                                   name="contact_number"
                                   placeholder="9123456789"
                                   value="{{ old('contact_number') }}"
                                   pattern="[0-9]{10}"
                                   maxlength="10"
                                   required>
                        </div>
                        @error('contact_number')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Enter 10 digits after +63</small>
                    </div>
                </div>

                {{-- Section 3: Account Security --}}
                <div class="form-section">
                    <div class="form-section-title">Account Security</div>
                    
                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Enter your password"
                               required>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Confirm your password"
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Section 4: Location Information --}}
                <div class="form-section">
                    <div class="form-section-title">Location Information</div>
                    
                    <div class="mb-3">
                        <label class="form-label">Region/Province <span class="text-danger">*</span></label>
                        <select class="form-select @error('province') is-invalid @enderror" id="province" name="province" required>
                            <option value="">Select Region/Province</option>
                        </select>
                        @error('province')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City/Municipality <span class="text-danger">*</span></label>
                        <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required disabled>
                            <option value="">Select City/Municipality</option>
                        </select>
                        @error('city')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barangay <span class="text-danger">*</span></label>
                        <select class="form-select @error('barangay') is-invalid @enderror" id="barangay" name="barangay" required disabled>
                            <option value="">Select Barangay</option>
                        </select>
                        @error('barangay')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="text-center mt-3">
                <span>Already have an account?</span>
                <a href="{{ route('login.index') }}" class="fw-semibold">Sign in instead</a>
            </p>
        </div>
    </div>

    <!-- Right Panel (Black Info Section) -->
    <div class="register-hero">
        <h1>Join SeekYu HRIS</h1>
        <p>Trusted personnel, modern systems. In partnership with Seekyu for streamlined recruitment and HRIS.</p>
    </div>
</div>

{{-- Philippines locations using PSGC API --}}
<script>
    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    const birthdateInput = document.getElementById('birthdate');
    const ageInput = document.getElementById('age');

    // Disable future dates in birthdate picker
    const today = new Date();
    const maxDate = today.toISOString().split('T')[0];
    birthdateInput.setAttribute('max', maxDate);

    // Calculate age from birthdate
    function calculateAge(birthdate) {
        const today = new Date();
        const birthDate = new Date(birthdate);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        return age;
    }

    // Show SweetAlert error for age below 20
    function showAgeError() {
        Swal.fire({
            icon: 'error',
            title: 'Age Requirement Not Met',
            text: 'You must be at least 20 years old to register.',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'OK'
        });
        ageInput.value = '';
        birthdateInput.value = '';
        birthdateInput.classList.add('is-invalid');
    }

    // Clear invalid class when user selects a valid date
    birthdateInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    // Update age when birthdate changes
    birthdateInput.addEventListener('change', function() {
        if (this.value) {
            const age = calculateAge(this.value);
            
            if (age < 20) {
                showAgeError();
            } else {
                ageInput.value = age;
                this.classList.remove('is-invalid');
            }
        } else {
            ageInput.value = '';
        }
    });

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
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Error loading provinces:', error);
        }
    }

    // Load cities/municipalities for selected province
    provinceSelect.addEventListener('change', async function() {
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;
        citySelect.classList.remove('is-invalid');
        barangaySelect.classList.remove('is-invalid');

        if (this.value) {
            try {
                if (this.value === 'Metro Manila (NCR)') {
                    const response = await fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/');
                    const cities = await response.json();
                    cities.sort((a, b) => a.name.localeCompare(b.name));
                    citySelect.disabled = false;
                    cities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } else {
                    const provinceResponse = await fetch('https://psgc.gitlab.io/api/provinces/');
                    const provinces = await provinceResponse.json();
                    const selectedProvince = provinces.find(p => p.name === this.value);
                    if (selectedProvince) {
                        const response = await fetch(`https://psgc.gitlab.io/api/provinces/${selectedProvince.code}/cities-municipalities/`);
                        const cities = await response.json();
                        cities.sort((a, b) => a.name.localeCompare(b.name));
                        citySelect.disabled = false;
                        cities.forEach(city => {
                            const option = document.createElement('option');
                            option.value = city.name;
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
        barangaySelect.classList.remove('is-invalid');

        if (this.value) {
            try {
                const provinceValue = provinceSelect.value;
                if (provinceValue === 'Metro Manila (NCR)') {
                    const regionResponse = await fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/');
                    const cities = await regionResponse.json();
                    const selectedCity = cities.find(c => c.name === this.value);
                    if (selectedCity) {
                        const response = await fetch(`https://psgc.gitlab.io/api/cities-municipalities/${selectedCity.code}/barangays/`);
                        const barangays = await response.json();
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
                            barangays.sort((a, b) => a.name.localeCompare(b.name));
                            barangaySelect.disabled = false;
                            barangays.forEach(barangay => {
                                const option = document.createElement('option');
                                option.value = barangay.name;
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
</script>

@endsection
