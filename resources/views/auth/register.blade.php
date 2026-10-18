@extends('auth.layouts.master')

@section('title', 'Register')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h2 class="font-display">Create Your Account</h2>
        <p class="text-muted">Join Housing 4 Raajje to find or list properties.</p>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger small">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label fw-bold small">Full Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" required autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label fw-bold small">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold small">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label fw-bold small">Confirm Password</label>
            <input type="password" class="form-control" id="password_confirmation"
                   name="password_confirmation" required>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input @error('terms') is-invalid @enderror"
                   type="checkbox" name="terms" id="terms" {{ old('terms') ? 'checked' : '' }} required>
            <label class="form-check-label small" for="terms">
                I agree to the <a href="#" class="text-decoration-none">Terms of Service</a>
                and <a href="#" class="text-decoration-none">Privacy Policy</a>.
            </label>
            @error('terms')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-hr w-100">Create Account</button>

        <p class="text-center text-muted small mt-4 mb-0">
            Already have an account?
            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Log in</a>
        </p>
    </form>
</div>
@endsection