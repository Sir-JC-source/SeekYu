@extends('Layouts.auth')

@section('title', 'Register - SeekYu HRIS')

@section('content')

<style>
    /* Centered register form styling */
    .register-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #1e1e2f, #3a3a5c);
    }

    .register-card {
        max-width: 500px;
        width: 100%;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        background-color: #1f1f2f;
        color: #fff;
    }

    .register-card .form-label { color: #e0e0e0; }
    .register-card .form-control {
        background-color: #2a2a45;
        color: #fff;
        border: 1px solid #444;
    }
    .register-card .form-control:focus {
        background-color: #2a2a45;
        color: #fff;
        border-color: #5c5ce0;
        box-shadow: none;
    }
    .register-card .btn-primary {
        background-color: #5c5ce0;
        border-color: #5c5ce0;
    }
    .register-card .btn-primary:hover {
        background-color: #4a4ad1;
        border-color: #4a4ad1;
    }
    .register-card a { color: #5c5ce0; }
    .register-card .alert { color: #fff; }
</style>

<div class="register-container">
    <div class="register-card mx-auto">
        <div class="text-center mb-4">
            <h4>Security Personnel Registration </h4>
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
                <div class="d-flex gap-2">
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

            {{-- Login ID (Email) --}}
            <div class="mb-3">
                <label for="email_login" class="form-label">Login ID (Email)</label>
                <input type="email"
                       class="form-control @error('email_login') is-invalid @enderror"
                       id="email_login"
                       name="email_login"
                       placeholder="Enter your email"
                       value="{{ old('email_login') }}"
                       required>
                @error('email_login')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email Address --}}
            <div class="mb-3">
                <label for="email_address" class="form-label">Email Address</label>
                <input type="email"
                       class="form-control @error('email_address') is-invalid @enderror"
                       id="email_address"
                       name="email_address"
                       placeholder="Enter your email address"
                       value="{{ old('email_address') }}"
                       required>
                @error('email_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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
                <label class="form-label">Location</label>
                <select class="form-select mb-2" id="province" required>
                    <option value="">Select Province</option>
                </select>
                <select class="form-select mb-2" id="city" required disabled>
                    <option value="">Select City/Municipality</option>
                </select>
                <select class="form-select" id="barangay" name="location" required disabled>
                    <option value="">Select Barangay</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3">
            <span>Already have an account?</span>
            <a href="{{ route('login.index') }}" class="link fw-semibold">Sign in instead</a>
        </p>
    </div>
</div>

{{-- Philippines locations JSON --}}
<script>
    const phData = {
        "Metro Manila": {
            "Quezon City": ["Barangay 1", "Barangay 2", "Barangay 3"],
            "Makati": ["Barangay A", "Barangay B"]
        },
        "Cebu": {
            "Cebu City": ["Barangay X", "Barangay Y"],
            "Lapu-Lapu": ["Barangay Z"]
        }
        // Add more provinces/cities/barangays as needed
    };

    const provinceSelect = document.getElementById('province');
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');

    // Load provinces
    Object.keys(phData).forEach(province => {
        const option = document.createElement('option');
        option.value = province;
        option.textContent = province;
        provinceSelect.appendChild(option);
    });

    provinceSelect.addEventListener('change', function() {
        citySelect.innerHTML = '<option value="">Select City/Municipality</option>';
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        barangaySelect.disabled = true;

        const cities = phData[this.value];
        if(cities) {
            citySelect.disabled = false;
            Object.keys(cities).forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
        } else {
            citySelect.disabled = true;
        }
    });

    citySelect.addEventListener('change', function() {
        barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
        const province = provinceSelect.value;
        const barangays = phData[province][this.value];
        if(barangays) {
            barangaySelect.disabled = false;
            barangays.forEach(barangay => {
                const option = document.createElement('option');
                option.value = `${province}, ${this.value}, ${barangay}`;
                option.textContent = barangay;
                barangaySelect.appendChild(option);
            });
        } else {
            barangaySelect.disabled = true;
        }
    });
</script>

@endsection
