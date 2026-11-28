@extends('client.layouts.master')

@section('title', 'My Leases')
@section('activeNav', 'home') 

@section('content')
<div class="container-xxl px-3 px-lg-4 py-5">
    <div class="mb-4">
        <a href="{{ route('client.dashboard') }}" class="text-link mb-3">
            <span aria-hidden="true">←</span> Back to Dashboard
        </a>
        <h1 class="section-heading mb-0">My Leases</h1>
    </div>
    
    <div class="card p-5 text-center" style="background: var(--hr-surface); border-radius: 1rem; border: 1px solid var(--hr-border);">
        <h3 class="h5 fw-bold mb-2">(This is for the Leases page)</h3>
        <p class="text-muted-custom mb-0">Your active and past lease agreements will appear here.</p>
    </div>
</div>
@endsection