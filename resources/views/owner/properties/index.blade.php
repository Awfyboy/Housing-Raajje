@extends('owner.layouts.master')

@section('title', 'My Properties')
@section('activeNav', 'properties')

@section('content')
<div class="page-container page-content">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 mb-4">
        <div>
            <p class="eyebrow mb-2">Portfolio</p>
            <h1 class="section-heading mb-0">My Properties</h1>
        </div>
        <a href="{{ route('owner.properties.create') }}" class="btn-hr">
            <span class="material-symbols-outlined">add_circle</span>
            Register Property
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
    @endif

    <!-- Filter Pills -->
    <div class="d-flex flex-wrap gap-2 mb-4">
        @php
            $currentStatus = request()->query('status'); // Gets the active status from the URL
            $activeClass = 'btn-dark';
            $inactiveClass = 'btn-light border';
        @endphp

        <a href="{{ route('owner.properties.index') }}" 
           class="btn btn-sm rounded-pill px-3 {{ !$currentStatus ? $activeClass : $inactiveClass }}">
            All Properties
        </a>
        
        <a href="{{ route('owner.properties.index', ['status' => 'available']) }}" 
           class="btn btn-sm rounded-pill px-3 {{ $currentStatus === 'available' ? $activeClass : $inactiveClass }}">
            Available
        </a>
        
        <a href="{{ route('owner.properties.index', ['status' => 'pending']) }}" 
           class="btn btn-sm rounded-pill px-3 {{ $currentStatus === 'pending' ? $activeClass : $inactiveClass }}">
            Pending
        </a>
        
        <a href="{{ route('owner.properties.index', ['status' => 'rented']) }}" 
           class="btn btn-sm rounded-pill px-3 {{ $currentStatus === 'rented' ? $activeClass : $inactiveClass }}">
            Rented
        </a>
    </div>

    <div class="row g-4">
        @forelse($properties as $property)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="hr-card h-100 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="eyebrow">{{ ucfirst($property->property_type) }}</span>
                        @php
                            $statusClass = match($property->status) {
                                'available' => 'text-success',
                                'pending' => 'text-warning',
                                'rented' => 'text-primary',
                                'unavailable' => 'text-danger',
                                default => 'text-muted'
                            };
                        @endphp
                        <span class="small fw-bold {{ $statusClass }}" style="text-transform: uppercase;">
                            {{ $property->status }}
                        </span>
                    </div>
                    
                    <h3 class="fs-5 fw-bold mb-2">{{ $property->title }}</h3>
                    <p class="small text-muted-custom mb-3">
                        <span class="material-symbols-outlined fs-6 align-middle">pin_drop</span>
                        {{ $property->location }}
                    </p>

                    <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center">
                        <div class="fw-bold">MVR {{ number_format($property->monthly_rent, 2) }}</div>
                        <a href="{{ route('owner.properties.show', $property) }}" class="text-link-hr">
                            Details <span class="material-symbols-outlined">arrow_forward</span>
                        </a>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <span class="material-symbols-outlined fs-1 text-muted-custom mb-3">home_work</span>
                <h3 class="h5 fw-bold">No properties found</h3>
                <p class="text-muted-custom">Click "Register Property" above to list your first apartment.</p>
            </div>
        @endforelse
    </div>
    
    <div class="mt-4">
        {{ $properties->links() }}
    </div>
</div>
@endsection