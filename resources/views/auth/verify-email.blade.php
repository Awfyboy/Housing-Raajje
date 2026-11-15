@extends('auth.layouts.master')

@section('title', 'Verify Email')

@section('content')
<div class="auth-card">

    {{-- STATE 1: SUCCESS --}}
    @if (session('verified_success'))
        <div class="text-center mb-4">
            <div class="mb-3">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#33825a" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 auto;">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            <h2 class="font-display mb-3">Email Verified!</h2>
            <p class="text-muted mb-4">
                Your email address has been successfully verified. Your account is now active.
            </p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-hr w-100">Continue to Login</a>

    {{-- STATE 2: WAITING --}}
    @elseif (auth()->check())
        <div class="text-center mb-4">
            <div class="mb-3">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color: var(--hr-primary); margin: 0 auto;">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
            </div>
            <h2 class="font-display">Check Your Email</h2>
            <p class="text-muted">
                We sent a verification link to <strong>{{ Auth::user()->email }}</strong>. 
                Please click that link to activate your account.
            </p>
        </div>

        @if (session('message'))
            <div class="alert alert-success small">{{ session('message') }}</div>
        @endif

        @if ($errors->has('resend'))
            <div class="alert alert-warning small">
                ⏳ {{ $errors->first('resend') }}
            </div>
        @endif

        <div class="alert alert-info small text-center">
            Waiting for verification... This page will redirect to login in <strong><span id="timer">--:--</span></strong>.
        </div>

        <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-hr w-100">Resend Verification Email</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-outline-secondary w-100">Cancel and Log out</button>
        </form>

    {{-- STATE 3: FALLBACK --}}
    @else
        <div class="text-center mb-4">
            <h2 class="font-display">Session Expired</h2>
            <p class="text-muted">Please log in or register to continue.</p>
        </div>
        <a href="{{ route('login') }}" class="btn btn-hr w-100">Go to Login</a>
    @endif

</div>
@endsection

@push('scripts')
@if (auth()->check() && !session('verified_success'))
<script>
    const expiresAt = new Date("{{ auth()->user()->created_at->addMinutes(10)->toIso8601String() }}").getTime();
    const timerElement = document.getElementById('timer');

    function updateTimer() {
        const now = new Date().getTime();
        const distance = expiresAt - now;

        if (distance < 0) {
            clearInterval(countdown);
            window.location.reload(); 
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        timerElement.textContent = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
    }

    updateTimer();
    const countdown = setInterval(updateTimer, 1000);
</script>
@endif
@endpush