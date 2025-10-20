@extends('Layouts.auth')

@section('title', 'Register - SeekYu HRIS')

@section('content')

<style>
    /* Left-side carousel styling */
    .auth-cover-bg {
        position: relative;
        width: 100%;
        height: 100vh;
        overflow: hidden;
    }

    #authCarousel,
    #authCarousel .carousel-inner,
    #authCarousel .carousel-item {
        height: 100%;
    }

    #authCarousel img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Register form styling */
    .register-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #1e1e2f, #3a3a5c);
    }

    .register-card {
        max-width: 400px;
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

<!-- Left-side carousel -->
<div class="d-none d-lg-flex col-lg-7 p-0">
    <div class="auth-cover-bg w-100">
        <div id="authCarousel" class="carousel slide carousel-fade w-100 h-100"
             data-bs-ride="carousel" data-bs-interval="4000">
            <div class="carousel-inner h-100">
                <div class="carousel-item active">
                    <img src="{{ asset('storage/assets/wallpaper.jpg') }}" alt="Slide 1">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('storage/assets/wallpaper2.jpg') }}" alt="Slide 2">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('storage/assets/wallpaper3.jpg') }}" alt="Slide 3">
                </div>
                <div class="carousel-item">
                    <img src="{{ asset('storage/assets/wallpaper4.jpg') }}" alt="Slide 4">
                </div>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#authCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#authCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </button>

            <div class="carousel-indicators">
                <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="2"></button>
                <button type="button" data-bs-target="#authCarousel" data-bs-slide-to="3"></button>
            </div>
        </div>
    </div>
</div>

<!-- Register form -->
<div class="d-flex col-12 col-lg-5 align-items-center p-sm-5 p-4">
    <div class="register-card mx-auto">
        <div class="text-center mb-4">
            <h4>Security Personnel Registration 👋</h4>
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

            {{-- Full Name --}}
            <div class="mb-3">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text"
                       class="form-control @error('fullname') is-invalid @enderror"
                       id="fullname"
                       name="fullname"
                       placeholder="Enter your full name"
                       value="{{ old('fullname') }}"
                       required>
                @error('fullname')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Email (Login ID) --}}
            <div class="mb-3">
                <label for="email" class="form-label">Login ID (Email)</label>
                <input type="email"
                       class="form-control @error('email') is-invalid @enderror"
                       id="email"
                       name="email"
                       placeholder="Enter your email"
                       value="{{ old('email') }}"
                       required>
                @error('email')
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
                <input type="text"
                       class="form-control @error('contact_number') is-invalid @enderror"
                       id="contact_number"
                       name="contact_number"
                       placeholder="Enter your contact number"
                       value="{{ old('contact_number') }}"
                       required>
                @error('contact_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Address --}}
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text"
                       class="form-control @error('address') is-invalid @enderror"
                       id="address"
                       name="address"
                       placeholder="Enter your address"
                       value="{{ old('address') }}"
                       required>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Position --}}
            <div class="mb-3">
                <label for="position" class="form-label">Position</label>
                <select class="form-select @error('position') is-invalid @enderror"
                        id="position"
                        name="position"
                        required>
                    <option value="">Select Position</option>
                    <option value="Security Guard" {{ old('position')=='Security Guard'?'selected':'' }}>Security Guard</option>
                    <option value="Head Guard" {{ old('position')=='Head Guard'?'selected':'' }}>Head Guard</option>
                </select>
                @error('position')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="text-center mt-3">
            <span>Already have an account?</span>
            <a href="{{ route('login.index') }}" class="link fw-semibold">Sign in instead</a>
        </p>
    </div>
</div>

@endsection
