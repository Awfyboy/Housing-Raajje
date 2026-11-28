@extends('client.layouts.master')

@section('title', $property->title)
@section('activeNav', 'properties')

@push('styles')
<style>
    .property-hero {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 1rem;
        background-color: #e3cdb8;
    }

    .property-detail-card {
        background: var(--hr-surface);
        border: 1px solid var(--hr-border);
        border-radius: 1rem;
        padding: 2rem;
    }

    .detail-label {
        color: #88796d;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 0.25rem;
    }

    .detail-value {
        color: var(--hr-ink);
        font-size: 1.15rem;
        font-weight: 800;
    }

    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem 0;
        border-bottom: 1px solid #eee5da;
    }

    .info-row:last-child {
        border-bottom: 0;
    }

    .property-price-large {
        color: var(--hr-ink);
        font-size: 2rem;
        font-weight: 800;
        line-height: 1;
    }

    .property-price-large small {
        color: #84786e;
        font-size: 0.9rem;
        font-weight: 500;
    }
</style>
@endpush

@section('content')
<div class="container-xxl px-3 px-lg-4 py-4 py-lg-5">

    {{-- Back Button (Styled like Apply) --}}
    <div class="mb-4">
        <a href="{{ route('client.properties.index') }}" class="btn btn-hr px-4 py-2 rounded-pill fw-bold" style="font-size: 0.875rem;">
            ← Back to Search
        </a>
    </div>

    {{-- Hero Image --}}
    <div class="mb-4 position-relative">
        <img
            src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
            alt="{{ $property->title }}"
            class="property-hero"
        >
        <span class="badge bg-light text-success position-absolute top-0 start-0 m-3" style="padding: 0.6rem 1rem; font-size: 0.75rem;">
            <span class="availability-dot" style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; background-color: #46a773; margin-right: 0.35rem;"></span>
            Available Now
        </span>
    </div>

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
        <div>
            <p class="eyebrow mb-2">{{ ucfirst($property->property_type) }} · {{ $property->location }}</p>
            <h1 class="section-heading mb-0">{{ $property->title }}</h1>
            <p class="text-muted-custom mt-2 mb-0">
                {{ $property->address }}
            </p>
        </div>
    </div>

    <div class="row g-4">
        {{-- Left Column: Description + Specs --}}
        <div class="col-12 col-lg-8">
            <div class="property-detail-card mb-4">
                <h3 class="hr-card-title mb-3">About this property</h3>
                <p class="text-muted-custom" style="line-height: 1.7;">
                    {{ $property->description ?? 'No description provided.' }}
                </p>

                <hr class="my-4">

                <h4 class="h6 fw-bold mb-3">Key Details</h4>
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="detail-label">Bedrooms</div>
                        <div class="detail-value">{{ $property->bedrooms }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="detail-label">Bathrooms</div>
                        <div class="detail-value">{{ $property->bathrooms ?? '-' }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="detail-label">Property Type</div>
                        <div class="detail-value">{{ ucfirst($property->property_type) }}</div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">{{ $property->location }}</div>
                    </div>
                </div>
            </div>

            {{-- Extra Info Rows --}}
            <div class="property-detail-card">
                <div class="info-row">
                    <div>
                        <div class="detail-label">Managed by</div>
                        <div class="fw-bold">{{ $property->branch->name ?? 'Housing 4 Raajje' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div>
                        <div class="detail-label">Property Owner</div>
                        <div class="fw-bold">{{ $property->owner->user->name ?? 'Verified Owner' }}</div>
                    </div>
                </div>
                <div class="info-row">
                    <div>
                        <div class="detail-label">Lease Duration</div>
                        <div class="fw-bold">Flexible (6 - 36 months)</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Price + CTA --}}
        <div class="col-12 col-lg-4">
            <div class="property-detail-card" style="position: sticky; top: 100px;">
                <div class="detail-label mb-1">Monthly Rent</div>
                <div class="property-price-large mb-4">
                    MVR {{ number_format($property->monthly_rent, 0) }}
                    <small>/ month</small>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <div class="detail-label mb-1">Deposit</div>
                    <div class="fw-bold">MVR {{ number_format($property->monthly_rent, 0) }}</div>
                </div>

                <div class="mb-4">
                    <div class="detail-label mb-1">Advance Payment</div>
                    <div class="fw-bold">MVR {{ number_format($property->monthly_rent, 0) }}</div>
                </div>

                <hr class="my-4">

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-hr py-3">
                        Apply for this Property
                    </button>
                    <button type="button" class="btn btn-outline-secondary py-3">
                        Schedule a Viewing
                    </button>
                </div>

                <p class="text-muted-custom small text-center mt-3 mb-0">
                    By applying, you agree to our terms and confirm that your profile information is accurate.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection