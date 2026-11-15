@extends('auth.layouts.master')

@section('title', 'Verify Code')

@section('content')
<div class="auth-card">
    <div class="text-center mb-4">
        <h2 class="font-display">Check Your Email</h2>
        <p class="text-muted">
            We sent a 6-digit code to your email address. Enter it below to finish logging in.
        </p>
    </div>

    <form method="POST" action="{{ route('mfa.verify') }}">
        @csrf
        <div class="mb-4">
            <label for="code" class="form-label fw-bold small">Verification Code</label>
            <input type="text" name="code" id="code" maxlength="6" inputmode="numeric"
                   pattern="[0-9]{6}" required autofocus
                   class="form-control text-center @error('code') is-invalid @enderror"
                   style="font-size: 1.5rem; letter-spacing: .4em;">
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-hr w-100">Verify & Continue</button>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        <a href="{{ route('login') }}" class="text-decoration-none">Back to login</a>
    </p>
</div>
@endsection