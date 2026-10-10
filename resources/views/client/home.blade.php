@extends('client.layouts.master')

@section('title', 'Client Home')

@section('activeNav', 'home')

@push('styles')
<style>
    .hero {
        position: relative;
        overflow: hidden;
        background: #e3d8ca;
        color: var(--hr-ink);
    }

    .hero-image {
        position: absolute;
        top: 2rem;
        right: max(2rem, calc((100vw - 1320px) / 2));
        bottom: 2rem;
        width: 34%;
        overflow: hidden;
        border-radius: 1.25rem;
    }

    .hero-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: .85;
    }

    .hero-image::after {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(227, 216, 202, .6), transparent);
        content: "";
    }

    .hero h1 {
        max-width: 650px;
        font-size: clamp(2.5rem, 5vw, 4.5rem);
        line-height: 1.08;
    }

    .hero-copy {
        max-width: 520px;
        color: #6d5548;
        line-height: 1.8;
    }

    .property-card,
    .activity-card {
        height: 100%;
        overflow: hidden;
        border: 0;
        border-radius: 1rem;
        background: var(--hr-surface);
        color: var(--hr-ink);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 45px rgba(10, 6, 4, .12);
    }

    .property-image-wrap {
        position: relative;
        overflow: hidden;
        aspect-ratio: 16 / 10;
        background: #e8dfd3;
    }

    .property-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
    }

    .property-card:hover .property-image {
        transform: scale(1.04);
    }

    .availability-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        padding: .45rem .75rem;
        border-radius: 50rem;
        background: rgba(255, 253, 248, .96);
        color: var(--hr-success);
        font-size: .7rem;
        font-weight: 800;
    }

    .save-button {
        position: absolute;
        top: .75rem;
        right: .75rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 50%;
        background: rgba(255, 253, 248, .96);
        color: var(--hr-muted);
    }

    .save-button.is-saved,
    .save-button:hover {
        color: var(--hr-primary);
    }

    .property-label {
        color: var(--hr-primary);
        font-size: .75rem;
        font-weight: 800;
    }

    .property-location {
        display: flex;
        align-items: center;
        gap: .4rem;
        color: var(--hr-muted);
        font-size: .85rem;
    }

    .property-price {
        font-size: 1.1rem;
        font-weight: 800;
    }

    .property-price small {
        color: #8490a0;
        font-size: .72rem;
        font-weight: 500;
    }

    .activity-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        border-radius: .8rem;
        background: #eee3d5;
        color: #6b4937;
    }

    .activity-card .card-body {
        padding: 1.5rem;
    }

    .lease-card {
        background: var(--hr-accent);
    }

    .status-pill {
        padding: .35rem .6rem;
        border-radius: 50rem;
        background: #eee6db;
        color: var(--hr-muted);
        font-size: .65rem;
        font-weight: 800;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .status-pill.success {
        background: #e5f5ec;
        color: var(--hr-success);
    }

    .lease-progress {
        height: 6px;
        overflow: hidden;
        border-radius: 50rem;
        background: rgba(67, 45, 36, .15);
    }

    .lease-progress-bar {
        width: 83%;
        height: 100%;
        border-radius: inherit;
        background: var(--hr-primary);
    }

    @media (max-width: 991.98px) {
        .hero-image {
            display: none;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero -->
    <section class="hero">
        <div class="hero-image" aria-hidden="true">
            <img src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" alt="">
        </div>

        <div class="container-xxl position-relative px-3 px-lg-4 py-5">
            <div class="col-lg-7 py-lg-4">
                <p class="eyebrow mb-3">Find your space</p>
                <h1 class="fw-bold mb-4">A home that fits your life.</h1>
                <p class="hero-copy fs-6 mb-0">
                    Discover verified homes across Malé, Hulhumalé and Villingili, with every step of your rental in one place.
                </p>
                <a class="btn btn-hr rounded-pill px-4 py-3 mt-4" href="{{ url('/client/properties') }}">Explore properties</a>
            </div>
        </div>
    </section>

    <div class="container-xxl px-3 px-lg-4 py-5">
        <!-- Recommended properties -->
        <section aria-labelledby="recommended-heading">
            <div class="d-flex align-items-end justify-content-between gap-3 mb-4">
                <div>
                    <h2 class="section-heading mb-1" id="recommended-heading">Recommended properties</h2>
                    <p class="text-muted-custom mb-0 small">Homes you might like</p>
                </div>
                <a class="text-link d-none d-sm-inline-flex" href="{{ url('/client/properties') }}">
                    View all homes <span aria-hidden="true">→</span>
                </a>
            </div>

            <div class="row g-4">
                <!-- Property 1 -->
                <div class="col-md-6 col-xl-4">
                    <article class="card property-card">
                        <div class="property-image-wrap">
                            <img class="property-image" src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" alt="Ocean View Residence interior">
                            <span class="availability-badge">Available now</span>
                            <button class="save-button" type="button" aria-label="Save Ocean View Residence" aria-pressed="false" data-save-button>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <p class="property-label mb-1">2 bedrooms</p>
                            <h3 class="h5 fw-bold mb-2">Ocean View Residence</h3>
                            <p class="property-location mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"></path><circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                                Hulhumalé, Phase 2
                            </p>
                            <div class="d-flex align-items-center justify-content-between gap-3 border-top mt-4 pt-3">
                                <p class="property-price mb-0">MVR 18,000 <small>/ month</small></p>
                                <a class="btn btn-hr rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" href="{{ url('/client/properties/ocean-view-residence') }}" aria-label="View Ocean View Residence">→</a>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Property 2 -->
                <div class="col-md-6 col-xl-4">
                    <article class="card property-card">
                        <div class="property-image-wrap">
                            <img class="property-image" src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" alt="Reefside Studio interior">
                            <span class="availability-badge">Available soon</span>
                            <button class="save-button is-saved" type="button" aria-label="Remove Reefside Studio from saved properties" aria-pressed="true" data-save-button>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <p class="property-label mb-1">1 bedroom</p>
                            <h3 class="h5 fw-bold mb-2">Reefside Studio</h3>
                            <p class="property-location mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"></path><circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                                Henveiru, Malé
                            </p>
                            <div class="d-flex align-items-center justify-content-between gap-3 border-top mt-4 pt-3">
                                <p class="property-price mb-0">MVR 12,000 <small>/ month</small></p>
                                <a class="btn btn-hr rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" href="{{ url('/client/properties/reefside-studio') }}" aria-label="View Reefside Studio">→</a>
                            </div>
                        </div>
                    </article>
                </div>

                <!-- Property 3 -->
                <div class="col-md-6 col-xl-4">
                    <article class="card property-card">
                        <div class="property-image-wrap">
                            <img class="property-image" src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" alt="Palm Court Apartment interior">
                            <span class="availability-badge">Available now</span>
                            <button class="save-button" type="button" aria-label="Save Palm Court Apartment" aria-pressed="false" data-save-button>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78L12 21.23l8.84-8.84a5.5 5.5 0 0 0 0-7.78z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="card-body p-4">
                            <p class="property-label mb-1">3 bedrooms</p>
                            <h3 class="h5 fw-bold mb-2">Palm Court Apartment</h3>
                            <p class="property-location mb-0">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"></path><circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                                Hulhumalé, Phase 1
                            </p>
                            <div class="d-flex align-items-center justify-content-between gap-3 border-top mt-4 pt-3">
                                <p class="property-price mb-0">MVR 25,000 <small>/ month</small></p>
                                <a class="btn btn-hr rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" href="{{ url('/client/properties/palm-court-apartment') }}" aria-label="View Palm Court Apartment">→</a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>

            <a class="text-link d-inline-flex d-sm-none mt-3" href="{{ url('/client/properties') }}">
                View all homes <span aria-hidden="true">→</span>
            </a>
        </section>

        <!-- Client activity -->
        <section class="mt-5 pt-lg-4" aria-labelledby="activity-heading">
            <div class="mb-4">
                <h2 class="section-heading mb-1" id="activity-heading">Your activity</h2>
                <p class="text-muted-custom small mb-0">A quick look at your applications, viewings and lease.</p>
            </div>

            <div class="row g-4">
                <!-- Applications -->
                <div class="col-lg-3 col-md-6">
                    <article class="card activity-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="activity-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"></path>
                                    </svg>
                                </span>
                                <span class="status-pill">Active</span>
                            </div>
                            <p class="text-muted-custom small fw-bold mt-4 mb-1">Applications</p>
                            <p class="display-6 fw-bold mb-0">2</p>
                            <a class="text-link mt-4" href="{{ url('/client/applications') }}">View applications <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                </div>

                <!-- Viewing -->
                <div class="col-lg-4 col-md-6">
                    <article class="card activity-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="activity-icon" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                                    </svg>
                                </span>
                                <span class="status-pill success">Confirmed</span>
                            </div>
                            <p class="text-muted-custom small fw-bold mt-4 mb-1">Upcoming viewing</p>
                            <p class="h5 fw-bold mb-2">Tomorrow, 4:00 PM</p>
                            <p class="property-location mb-0">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0z"></path><circle cx="12" cy="10" r="2.5"></circle>
                                </svg>
                                Ocean View Residence, Apt 204
                            </p>
                            <a class="text-link mt-4" href="{{ url('/client/schedule') }}">View schedule <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                </div>

                <!-- Current lease -->
                <div class="col-lg-5">
                    <article class="card activity-card lease-card">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between">
                                <span class="activity-icon" style="background: rgba(67, 45, 36, .1); color: var(--hr-primary);" aria-hidden="true">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="m3 11 9-8 9 8"></path><path d="M5 10v10h14V10M9 20v-6h6v6"></path>
                                    </svg>
                                </span>
                                <span class="status-pill success">Paid</span>
                            </div>

                            <div class="row g-3 align-items-end mt-3">
                                <div class="col-sm">
                                    <p class="small fw-bold mb-1" style="color: #6d5548;">Current lease</p>
                                    <h3 class="h5 fw-bold mb-2">Ocean View Residence</h3>
                                    <p class="small mb-0" style="color: #6d5548;">MVR 18,000 / month · Apt 204</p>
                                </div>
                                <div class="col-sm-auto">
                                    <p class="small fw-semibold mb-1" style="color: #6d5548;">Next payment</p>
                                    <p class="small fw-bold mb-0">Due March 8</p>
                                </div>
                            </div>

                            <div class="lease-progress mt-4" role="progressbar" aria-label="Lease progress" aria-valuenow="83" aria-valuemin="0" aria-valuemax="100">
                                <div class="lease-progress-bar"></div>
                            </div>
                            <a class="text-link mt-4" href="{{ url('/client/leases') }}">Manage lease <span aria-hidden="true">→</span></a>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script>
    // Prototype interaction only. Connect saved properties to Laravel routes/controllers later.
    document.querySelectorAll('[data-save-button]').forEach((button) => {
        button.addEventListener('click', () => {
            const isSaved = button.getAttribute('aria-pressed') === 'true';

            button.setAttribute('aria-pressed', String(!isSaved));
            button.classList.toggle('is-saved', !isSaved);
            button.setAttribute(
                'aria-label',
                !isSaved ? 'Remove from saved properties' : 'Save property'
            );
        });
    });
</script>
@endpush