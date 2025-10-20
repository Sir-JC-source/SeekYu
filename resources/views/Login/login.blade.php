@extends('Layouts.auth')

@section('title', 'Login - SeekYu HRIS')

@section('content')

<style>
    /* Center login form */
    .login-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 1rem;
        background: linear-gradient(135deg, #1e1e2f, #3a3a5c);
    }

    .login-card {
        max-width: 380px;
        width: 100%;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.3);
        background-color: #1f1f2f;
        color: #fff;
    }

    .login-card .form-label { color: #e0e0e0; }
    .login-card .form-control {
        background-color: #2a2a45;
        color: #fff;
        border: 1px solid #444;
    }
    .login-card .form-control:focus {
        background-color: #2a2a45;
        color: #fff;
        border-color: #5c5ce0;
        box-shadow: none;
    }
    .login-card .btn-primary {
        background-color: #5c5ce0;
        border-color: #5c5ce0;
    }
    .login-card .btn-primary:hover {
        background-color: #4a4ad1;
        border-color: #4a4ad1;
    }
    .login-card a { color: #5c5ce0; }
    .login-card .alert { color: #fff; }
</style>

<div class="login-container">
    <div class="login-card">
        <div class="text-center mb-4">
            <h4>Welcome to SeekYu HRIS</h4>
            <p class="mb-0">Please sign in to your account</p>
        </div>

        {{-- Success Notification --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="ti ti-checks me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Error Notification --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="ti ti-alert-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form method="POST" action="{{ route('login.authenticate') }}" novalidate>
            @csrf

            <div class="mb-3">
                <label for="username" class="form-label">Login ID</label>
                <input type="text"
                       id="username"
                       name="username"
                       class="form-control form-control-sm"
                       placeholder="Enter Login ID"
                       autofocus>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password"
                       id="password"
                       name="password"
                       class="form-control form-control-sm"
                       placeholder="••••••••">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Sign In</button>
        </form>

        <p class="text-center mt-3">
            <span>Seeking for a job??</span>
            <a href="{{ route('login.register') }}" class="link fw-semibold">Click here to apply!</a>
        </p>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Remove numeric-only restriction for Login ID
    // Only limit max length to 20 (optional)
    const usernameInput = document.getElementById('username');
    usernameInput.addEventListener('input', function() {
        this.value = this.value.substring(0,20); // allow letters, numbers, symbols
    });
</script>
@endpush
