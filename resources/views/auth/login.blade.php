@extends('auth.layouts.master')
@section('title', 'Login')

@section('content')
<div class="auth-card">

    {{-- RESET PASSWORD (Token is present) --}}
    @if (isset($token))
        <div class="text-center mb-4">
            <h2 class="font-display">Set New Password</h2>
            <p class="text-muted">Choose a strong new password for your account.</p>
        </div>

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf
            
            {{-- Hidden token from the email link --}}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-bold small">New Password</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="form-label fw-bold small">Confirm New Password</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required autocomplete="new-password">
            </div>

            <button type="submit" class="btn btn-hr w-100">Reset Password</button>
        </form>


    {{-- FORGOT PASSWORD (Hit the route) --}}
    @elseif (request()->routeIs('password.request'))
        <div class="text-center mb-4">
            <h2 class="font-display">Reset Password</h2>
            <p class="text-muted">Enter your email and we'll send you a link to reset your password.</p>
        </div>

        {{-- Password reset link sent --}}
        @if (session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-4">
                <label for="email" class="form-label fw-bold small">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-hr w-100">Email Password Reset Link</button>

            <p class="text-center text-muted small mt-4 mb-0">
                Remembered it?
                <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Back to login</a>
            </p>
        </form>


    {{-- NORMAL LOGIN --}}
    @else
        <div class="text-center mb-4">
            <h2 class="font-display">Welcome Back</h2>
            <p class="text-muted">Log in to your Housing 4 Raajje account.</p>
        </div>

        {{-- Password reset link sent --}}
        @if (session('status'))
            <div class="alert alert-success small">{{ session('status') }}</div>
        @endif

        {{-- Validation errors --}}
        @if ($errors->any())
            <div class="alert alert-danger small">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-bold small">Email Address</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <label for="password" class="form-label fw-bold small mb-0">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot password?</a>
                    @endif
                </div>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-4">
                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                       {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label small" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-hr w-100">Log In</button>

            @if (Route::has('register'))
                <p class="text-center text-muted small mt-4 mb-0">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Create one</a>
                </p>
            @endif
        </form>
    @endif

</div>
@endsection