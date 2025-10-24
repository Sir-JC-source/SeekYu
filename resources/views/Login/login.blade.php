@extends('Layouts.auth')

@section('title', 'Login - SeekYu HRIS')

@section('content')

<style>
/* ===== Modern Two-Panel Login Design (Updated) ===== */
body {
    background-color: #0f172a;
    font-family: 'Inter', sans-serif;
    margin: 0;
    padding: 0;
}

.login-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Left side (black hero panel) */
.login-hero {
    flex: 1;
    background-color: #000;
    color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 4rem;
    text-align: left;
}

.login-hero h1 {
    font-size: 2.2rem;
    font-weight: 700;
    color: #ffffff;
    margin-bottom: 1.5rem;
}

.login-hero p {
    font-size: 1.05rem;
    line-height: 1.6;
    color: #e5e5e5;
    max-width: 420px;
}

/* Right side (login form) */
.login-container {
    flex: 1;
    background-color: #ffffff;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 3rem 2rem;
}

.login-card {
    width: 100%;
    max-width: 380px;
}

.login-card img {
    width: 100px;
    height: 100px;
    object-fit: contain;
    margin-bottom: 1rem;
}

.login-card h4 {
    font-weight: 600;
    color: #111827;
}

.login-card p {
    color: #6b7280;
}

.login-card .form-label {
    color: #374151;
    font-weight: 500;
}

.login-card .form-control {
    background-color: #f9fafb;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 0.625rem 0.75rem;
    color: #111827;
}

.login-card .form-control:focus {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99,102,241,0.25);
}

.login-card .btn-primary {
    background-color: #4f46e5;
    border-color: #4f46e5;
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.3s ease;
}

.login-card .btn-primary:hover {
    background-color: #4338ca;
}

.login-card a {
    color: #4f46e5;
    text-decoration: none;
}

.login-card a:hover {
    text-decoration: underline;
}

/* Responsive design */
@media (max-width: 992px) {
    .login-wrapper {
        flex-direction: column;
    }
    .login-hero {
        align-items: center;
        text-align: center;
        padding: 2.5rem;
    }
    .login-hero p {
        max-width: 100%;
    }
}
</style>

<div class="login-wrapper">
    <!-- Left Panel -->
    <div class="login-hero">
        <h1>Welcome to SeekYu HRIS</h1>
        <p>Trusted personnel, modern systems. In partnership with Seekyu for streamlined recruitment and HRIS.</p>
    </div>

    <!-- Right Panel -->
    <div class="login-container">
        <div class="login-card text-center">
            <img
                src="{{ asset('storage/logo.png') }}"
                onerror="this.onerror=null; this.src='{{ asset('assets/bgpicture/default-logo.png') }}';"
                alt="Logo"
            />
            <h4>Sign in to your account</h4>
            <p>Please enter your credentials to continue.</p>

            {{-- Notifications --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mt-3">
                    <i class="ti ti-checks me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mt-3">
                    <i class="ti ti-alert-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login.authenticate') }}" class="mt-4" novalidate>
                @csrf
                <div class="mb-3 text-start">
                    <label for="username" class="form-label">Login ID</label>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter Login ID" autofocus>
                </div>
                <div class="mb-3 text-start">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••">
                </div>
                <div class="mb-3 form-check text-start">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn btn-primary w-100">Sign In</button>
            </form>

            <p class="mt-4">
                <span>Seeking for a job?</span>
                <a href="{{ route('login.register') }}" class="fw-semibold">Click here to apply!</a>
            </p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function() {
        this.value = this.value.substring(0,20);
    });
</script>
@endpush
