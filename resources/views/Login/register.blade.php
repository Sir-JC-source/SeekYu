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
                <h4>Security Personnel Registration</h4>
                <p class="mb-0">Apply as Security Guard or Head Guard</p>
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

                {{-- Name Fields --}}
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
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
                    @error('last_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('middle_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    @error('first_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- Login ID --}}
                <div class="mb-3">
                    <label for="login_id" class="form-label">Login ID</label>
                    <input type="text"
                           class="form-control"
                           id="login_id"
                           name="login_id"
                           value=""
                           readonly
                           required>
                    <small class="text-muted">Auto-generated 4-digit login ID</small>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="Enter your password"
                           required>
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Contact Number --}}
                <div class="mb-3">
                    <label for="contact_number" class="form-label">Contact Number</label>
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
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Enter 10 digits after +63</small>
                </div>

                {{-- Location Dropdown --}}
                <div class="mb-3">
                    <label class="form-label">Province</label>
                    <select class="form-select mb-2" id="province" name="province" required>
                        <option value="">Select Province</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">City/Municipality</label>
                    <select class="form-select mb-2" id="city" name="city" required disabled>
                        <option value="">Select City/Municipality</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Barangay</label>
                    <select class="form-select" id="barangay" name="barangay" required disabled>
                        <option value="">Select Barangay</option>
                    </select>
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

    // Generate 4-digit login ID
    function generateLoginId() {
        const loginId = Math.floor(1000 + Math.random() * 9000); // 1000-9999
        document.getElementById('login_id').value = loginId;
    }

    // Load provinces on page load
    async function loadProvinces() {
        try {
            const response = await fetch('https://psgc.gitlab.io/api/provinces/');
            const provinces = await response.json();
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
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;

        if (this.value) {
            try {
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
                // Find city code by name
                const provinceValue = provinceSelect.value;
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
            } catch (error) {
                console.error('Error loading barangays:', error);
                barangaySelect.disabled = true;
            }
        } else {
            barangaySelect.disabled = true;
        }
    });

    // Initialize provinces and generate login ID on load
    loadProvinces();
    generateLoginId();
</script>

@endsection
