@extends('owner.layouts.master')

@section('title', $property->title)
@section('activeNav', 'properties')

@section('content')
<div class="page-container page-content">
    <div class="mb-4">
        <a href="{{ route('owner.properties.index') }}" class="text-link-hr mb-3">
            <span class="material-symbols-outlined fs-6">arrow_back</span> Back to Properties
        </a>
        <div class="d-flex justify-content-between align-items-end mt-2">
            <div>
                <p class="eyebrow mb-1">{{ ucfirst($property->property_type) }} · {{ $property->location }}</p>
                <h1 class="section-heading mb-0">{{ $property->title }}</h1>
            </div>
            <span class="badge bg-secondary text-uppercase p-2">{{ $property->status }}</span>
        </div>
    </div>

    <!-- Alerts -->
    @php
        $latestReg = $property->registrations->sortByDesc('submitted_at')->first();
    @endphp
    
    @if($latestReg && $latestReg->status === 'rejected')
        <div class="alert alert-danger mb-4">
            <h5 class="alert-heading fw-bold">Registration Rejected</h5>
            <p class="mb-3">Reason: {{ $latestReg->rejection_reason ?? 'No reason provided.' }}</p>
            
            {{-- NEW: Action buttons for rejected properties --}}
            <div class="d-flex gap-2">
                <a href="{{ route('owner.properties.edit', $property) }}" class="btn btn-sm btn-dark fw-bold">
                    Edit & Resubmit
                </a>
                <form action="{{ route('owner.properties.destroy', $property) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this property?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger fw-bold">Delete Property</button>
                </form>
            </div>
        </div>
    @elseif($property->status === 'pending')
        <div class="alert alert-warning mb-4">
            <h5 class="alert-heading fw-bold">Pending Approval</h5>
            <p class="mb-0">This property is currently under review.</p>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-12 col-md-8">
            <div class="hr-card">
                <h3 class="hr-card-title mb-3">Property Details</h3>
                <p class="text-muted-custom">{{ $property->description ?? 'No description provided.' }}</p>
                
                <hr class="my-4">
                
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <p class="eyebrow mb-1">Rent</p>
                        <div class="fw-bold">MVR {{ number_format($property->monthly_rent, 2) }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="eyebrow mb-1">Bedrooms</p>
                        <div class="fw-bold">{{ $property->bedrooms }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="eyebrow mb-1">Bathrooms</p>
                        <div class="fw-bold">{{ $property->bathrooms ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <p class="eyebrow mb-1">Branch</p>
                        <div class="fw-bold">{{ $property->branch->name ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-4">
            <div class="hr-card">
                <h3 class="hr-card-title mb-3">Location</h3>
                <p class="small mb-1 fw-bold">{{ $property->address }}</p>
                <p class="small text-muted-custom">{{ $property->location }}</p>
            </div>
        </div>
    </div>
</div>
@endsection