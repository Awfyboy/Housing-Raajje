
@extends('owner.layouts.master')

@section('title', 'Owner Dashboard')

@section('activeNav', 'dashboard')

@section('content')
<div class="page-container page-content">

    <!-- Welcome heading and primary action -->
    <section class="d-flex flex-column flex-md-row align-items-md-end justify-content-between gap-3 mb-4 mb-lg-5">
        <div>
            <p class="eyebrow mb-2">Overview &amp; Portfolio Pulse</p>
            <h1 class="section-heading mb-2">
                Good morning, {{ Auth::user()->name ?? 'Ahmed' }}
            </h1>
            <p class="text-muted-custom mb-0">
                Here's an overview of your properties and rental activity.
            </p>
        </div>

        <a href="{{ url('/owner/properties/create') }}" class="btn-hr align-self-start align-self-md-auto">
            <span class="material-symbols-outlined">add_circle</span>
            Register Property
        </a>
    </section>

    <!-- Key metrics -->
    <section class="row g-3 g-xl-4 mb-4 mb-lg-5">

        <!-- Total properties -->
        <div class="col-12 col-md-4">
            <article class="hr-card">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <span class="eyebrow">Total Properties</span>
                    <span class="material-symbols-outlined text-muted-custom fs-4">
                        apartment
                    </span>
                </div>

                <div class="mt-4 d-flex align-items-baseline gap-2">
                    <span class="metric-value">4</span>
                    <span class="small text-muted-custom">units listed</span>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                    <span class="material-symbols-outlined text-caption-custom">
                        pin_drop
                    </span>
                    <span class="small text-muted-custom">
                        Across Malé &amp; Hulhumalé
                    </span>
                </div>
            </article>
        </div>

        <!-- Active rentals -->
        <div class="col-12 col-md-4">
            <article class="hr-card">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <span class="eyebrow">Active Rentals</span>
                    <span class="status-pill">
                        <span class="status-dot"></span>
                        75% Occupancy
                    </span>
                </div>

                <div class="mt-4 d-flex align-items-baseline gap-2">
                    <span class="metric-value">3</span>
                    <span class="small text-muted-custom">active tenancies</span>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                    <span class="material-symbols-outlined text-caption-custom">
                        key
                    </span>
                    <span class="small text-muted-custom">
                        1 vacant unit ready for move-in
                    </span>
                </div>
            </article>
        </div>

        <!-- Monthly income -->
        <div class="col-12 col-md-4">
            <article class="hr-card">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <span class="eyebrow">Monthly Income</span>
                    <span class="small fw-bold text-success">
                        <span class="material-symbols-outlined align-middle fs-6">
                            trending_up
                        </span>
                        +8% vs Q2
                    </span>
                </div>

                <div class="mt-4">
                    <span class="metric-value fs-2">MVR 45,000</span>
                </div>

                <div class="d-flex align-items-center gap-2 mt-3 pt-3 border-top">
                    <span class="material-symbols-outlined text-caption-custom">
                        event_available
                    </span>
                    <span class="small text-muted-custom">
                        Next payout: 10th of this month
                    </span>
                </div>
            </article>
        </div>
    </section>

    <!-- Rental income and property status -->
    <section class="row g-3 g-xl-4 mb-4 mb-lg-5">

        <!-- Rental income chart -->
        <div class="col-12 col-lg-8">
            <article class="hr-card">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
                    <div>
                        <h2 class="hr-card-title">Rental Income</h2>
                        <p class="small text-muted-custom mb-0">
                            Historical collection across regular tenancies
                        </p>
                    </div>

                    <div class="btn-group btn-group-sm" role="group" aria-label="Income chart period">
                        <button type="button" class="btn btn-hr">Last 6 Months</button>
                        <button type="button" class="btn btn-hr-light">YTD</button>
                        <button type="button" class="btn btn-hr-light">1 Year</button>
                    </div>
                </div>

                <!-- Example chart; replace with real income data later -->
                <div class="mt-4">
                    <div class="chart-scroll">
                        <svg
                            class="income-chart"
                            viewBox="0 0 700 220"
                            role="img"
                            aria-label="Example monthly rental income from May to October"
                        >
                            <g stroke="#eee5da" stroke-dasharray="3 3">
                                <line x1="0" x2="700" y1="40" y2="40"/>
                                <line x1="0" x2="700" y1="100" y2="100"/>
                                <line x1="0" x2="700" y1="160" y2="160"/>
                            </g>

                            <g fill="#88796d" font-family="Manrope" font-size="10">
                                <text x="5" y="32">50k</text>
                                <text x="5" y="92">35k</text>
                                <text x="5" y="152">20k</text>
                            </g>

                            <g fill="#eee5da">
                                <rect x="55" y="70" width="55" height="110" rx="4"/>
                                <rect x="165" y="70" width="55" height="110" rx="4"/>
                            </g>

                            <g fill="#e3cdb8">
                                <rect x="275" y="52" width="55" height="128" rx="4"/>
                                <rect x="385" y="52" width="55" height="128" rx="4"/>
                            </g>

                            <rect x="495" y="52" width="55" height="128" rx="4" fill="#6b4937"/>
                            <rect x="605" y="52" width="55" height="128" rx="4" fill="#432d24"/>

                            <g fill="#5b3d2e" font-family="Manrope" font-size="11" font-weight="700" text-anchor="middle">
                                <text x="82" y="62">40k</text>
                                <text x="192" y="62">40k</text>
                                <text x="302" y="44">45k</text>
                                <text x="412" y="44">45k</text>
                                <text x="522" y="44">45k</text>
                                <text x="632" y="44">45k</text>
                            </g>

                            <g fill="#786f67" font-family="Manrope" font-size="11" font-weight="600" text-anchor="middle">
                                <text x="82" y="200">May</text>
                                <text x="192" y="200">Jun</text>
                                <text x="302" y="200">Jul</text>
                                <text x="412" y="200">Aug</text>
                                <text x="522" y="200">Sep</text>
                                <text x="632" y="200">Oct</text>
                            </g>
                        </svg>
                    </div>
                </div>

                <div class="soft-panel p-3 mt-3 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2">
                    <div class="small text-muted-custom">
                        <span class="material-symbols-outlined align-middle text-success me-1">
                            verified
                        </span>
                        Total collected YTD:
                        <strong class="text-dark">MVR 260,000</strong>
                    </div>

                    <span class="small fw-bold text-success">
                        100% on-time payment rate
                    </span>
                </div>
            </article>
        </div>

        <!-- Property status -->
        <div class="col-12 col-lg-4">
            <article class="hr-card">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <h2 class="hr-card-title">Property Status</h2>
                    <span class="eyebrow">4 Total</span>
                </div>

                <p class="small text-muted-custom mb-0">
                    Live occupancy breakdown
                </p>

                <div
                    class="d-flex gap-1 rounded-pill overflow-hidden p-1 mt-4"
                    style="height: 16px; background: var(--hr-surface-input);"
                    role="img"
                    aria-label="Three rented properties and one available property"
                >
                    <div class="rounded-pill" style="width: 75%; background: var(--hr-primary);"></div>
                    <div class="rounded-pill" style="width: 25%; background: var(--hr-success);"></div>
                </div>

                <div class="d-grid gap-2 mt-4">
                    <div class="soft-panel p-3 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 10px; height: 10px; background: var(--hr-primary);"></span>
                            <div>
                                <div class="fw-bold small">Rented</div>
                                <div class="text-muted-custom small">Active tenancy</div>
                            </div>
                        </div>
                        <span class="fw-bold fs-5">3</span>
                    </div>

                    <div class="soft-panel p-3 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 10px; height: 10px; background: var(--hr-success-dot);"></span>
                            <div>
                                <div class="fw-bold small">Available</div>
                                <div class="text-muted-custom small">Ready for inspection</div>
                            </div>
                        </div>
                        <span class="fw-bold fs-5">1</span>
                    </div>

                    <div class="soft-panel p-3 d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-circle" style="width: 10px; height: 10px; background: var(--hr-text-light);"></span>
                            <div>
                                <div class="fw-bold small">Maintenance</div>
                                <div class="text-muted-custom small">Out of order</div>
                            </div>
                        </div>
                        <span class="fw-bold fs-5">0</span>
                    </div>
                </div>
            </article>
        </div>
    </section>

    <!-- My properties -->
    <section class="mb-4 mb-lg-5">
        <div class="d-flex align-items-end justify-content-between gap-3 mb-3">
            <div>
                <p class="eyebrow mb-2">Portfolio</p>
                <h2 class="section-heading">My Properties</h2>
            </div>

            <a href="{{ url('/owner/properties') }}" class="text-link-hr">
                View All (4)
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        <article class="hr-card property-card">
            <div class="row g-4">

                <!-- Property image -->
                <div class="col-12 col-md-4 col-xl-3">
                    <div class="property-image-wrap">
                        <img
                            src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                            alt="Property image placeholder"
                            class="property-image"
                        >

                        <span class="image-label">AVAILABLE</span>
                    </div>
                </div>

                <!-- Property information -->
                <div class="col-12 col-md-8 col-xl-9 d-flex flex-column justify-content-between gap-3">
                    <div>
                        <p class="small text-caption-custom mb-2">
                            <span class="material-symbols-outlined align-middle fs-6">
                                pin_drop
                            </span>
                            Hulhumalé · Phase 2
                        </p>

                        <h3 class="font-display fs-4 mb-2">
                            H. Seaside Villa — Apt 301
                        </h3>

                        <p class="small text-muted-custom mb-0">
                            Elevated corner residence with lagoon views, modern hardwood
                            millwork, and a dedicated service entry.
                        </p>
                    </div>

                    <!-- Property specifications -->
                    <div class="d-flex flex-wrap gap-2">
                        <span class="detail-chip">
                            <span class="material-symbols-outlined">bed</span>
                            3 Bedrooms
                        </span>

                        <span class="detail-chip">
                            <span class="material-symbols-outlined">bathtub</span>
                            3 Bathrooms
                        </span>

                        <span class="detail-chip">
                            <span class="material-symbols-outlined">chair</span>
                            Furnished
                        </span>

                        <span class="detail-chip">
                            <span class="material-symbols-outlined">square_foot</span>
                            1,420 sq ft
                        </span>
                    </div>

                    <!-- Rental price and actions -->
                    <div class="d-flex flex-column flex-sm-row align-items-sm-end justify-content-between gap-3 pt-3 border-top">
                        <div>
                            <p class="eyebrow mb-1">Listed Rental Rate</p>
                            <div class="fs-5 fw-bold">
                                MVR 18,000
                                <span class="small fw-normal text-muted-custom">/ month</span>
                            </div>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ url('/owner/properties') }}" class="btn-hr btn-hr-light">
                                Manage Listing
                            </a>

                            <a href="{{ url('/owner/properties/H4R-884') }}" class="btn-hr">
                                View Property
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </article>
    </section>

    <!-- Upcoming alerts -->
    <section>
        <article class="hr-card">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="material-symbols-outlined text-caption-custom fs-4">
                        notification_important
                    </span>
                    <h2 class="hr-card-title mb-0">Upcoming &amp; Attention</h2>
                </div>

                <span class="alert-label alert-label-danger">3 Alerts</span>
            </div>

            <div class="d-grid gap-3">

                <!-- Viewing reminder -->
                <div class="alert-row">
                    <span class="alert-icon">
                        <span class="material-symbols-outlined">event</span>
                    </span>

                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h3 class="font-body fw-bold fs-6 mb-0">
                                Viewing — 12 October
                            </h3>
                            <span class="alert-label">Tomorrow</span>
                        </div>

                        <p class="small text-muted-custom mt-2 mb-0">
                            Prospective tenant viewing for H. Seaside Villa at 4:30 PM
                            with agent Mariyam.
                        </p>
                    </div>
                </div>

                <!-- Lease reminder -->
                <div class="alert-row">
                    <span class="alert-icon">
                        <span class="material-symbols-outlined">schedule</span>
                    </span>

                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h3 class="font-body fw-bold fs-6 mb-0">
                                Lease expires — 30 days
                            </h3>
                            <span class="alert-label alert-label-danger">Action required</span>
                        </div>

                        <p class="small text-muted-custom mt-2 mb-0">
                            Palm Court Apt 4B lease renewal agreement is due for
                            review and signature.
                        </p>
                    </div>
                </div>

                <!-- Maintenance reminder -->
                <div class="alert-row">
                    <span class="alert-icon">
                        <span class="material-symbols-outlined">build</span>
                    </span>

                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                            <h3 class="font-body fw-bold fs-6 mb-0">
                                Maintenance — In Progress
                            </h3>
                            <span class="alert-label">In progress</span>
                        </div>

                        <p class="small text-muted-custom mt-2 mb-0">
                            AC seasonal filter servicing scheduled for Ocean View
                            Residence #204.
                        </p>
                    </div>
                </div>

            </div>

            <a
                href="{{ url('/owner/notifications') }}"
                class="btn-hr btn-hr-light w-100 mt-4"
            >
                View All Reminders
            </a>
        </article>
    </section>

</div>
@endsection

@push('styles')
<style>
    .chart-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .income-chart {
        display: block;
        width: 100%;
        min-width: 500px;
        height: auto;
    }
</style>
@endpush