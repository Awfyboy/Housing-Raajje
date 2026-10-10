
@extends('client.layouts.master')

@section('title', 'Find Properties')

@section('activeNav', 'properties')

@push('styles')
<style>
    /* Property search page */
    .search-panel,
    .property-card,
    .property-list-item {
        background: var(--hr-surface);
        border: 1px solid var(--hr-border);
        border-radius: 1rem;
    }

    .search-panel {
        box-shadow: 0 12px 35px rgba(71, 47, 36, 0.07);
    }

    .filter-label {
        display: block;
        margin-bottom: 0.4rem;
        color: #88796d;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .filter-control {
        min-height: 48px;
        border-color: var(--hr-border);
        background-color: #f1e9de;
        color: var(--hr-ink);
        font-weight: 600;
    }

    .filter-control:focus {
        border-color: var(--hr-primary);
        box-shadow: 0 0 0 0.2rem rgba(91, 61, 46, 0.12);
    }

    .property-card {
        overflow: hidden;
        color: var(--hr-ink);
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .property-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 18px 45px rgba(39, 28, 23, 0.12);
    }

    .property-image {
        width: 100%;
        height: 220px;
        object-fit: cover;
        background-color: #e3cdb8;
    }

    .property-list-image {
        width: 100%;
        height: 100%;
        min-height: 160px;
        object-fit: cover;
        border-radius: 0.65rem;
        background-color: #e3cdb8;
    }

    .property-type {
        color: var(--hr-primary);
        font-size: 0.8rem;
        font-weight: 800;
    }

    .property-location {
        color: #786b61;
        font-size: 0.875rem;
    }

    .property-price {
        color: var(--hr-ink);
        font-size: 1.15rem;
        font-weight: 800;
    }

    .property-price small {
        color: #84786e;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .availability {
        color: var(--hr-success);
        font-size: 0.75rem;
        font-weight: 800;
    }

    .availability-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 0.35rem;
        border-radius: 50%;
        background-color: #46a773;
    }

    .save-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 0;
        border-radius: 50%;
        background-color: #eee5da;
        color: var(--hr-primary);
    }

    .save-button:hover {
        background-color: var(--hr-accent);
    }

    .property-divider {
        border-color: #eee5da;
        opacity: 1;
    }

    .search-section {
        padding-top: 3rem;
        padding-bottom: 4rem;
    }

    .results-count {
        color: #786b61;
        font-size: 0.875rem;
        font-weight: 700;
    }

    .pagination .page-link {
        margin: 0 0.2rem;
        border: 0;
        border-radius: 0.5rem;
        background-color: var(--hr-surface);
        color: #65554b;
        font-weight: 800;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--hr-primary-dark);
        color: #fff;
    }

    .pagination .page-link:hover {
        background-color: var(--hr-accent);
        color: var(--hr-ink);
    }

    @media (max-width: 575.98px) {
        .search-section {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .property-image {
            height: 200px;
        }

        .property-list-image {
            height: 190px;
        }
    }
</style>
@endpush

@section('content')
<main>
    {{-- Search and filters --}}
    <section class="container-xxl px-3 px-lg-4 pt-4 pt-lg-5">
        <form
            action="{{ url('/client/properties') }}"
            method="GET"
            class="search-panel p-3 p-md-4"
        >
            {{-- Keyword search --}}
            <div class="input-group input-group-lg mb-4">
                <span class="input-group-text bg-transparent border-end-0">
                    <svg width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.8"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>
                </span>

                <input
                    type="search"
                    name="q"
                    class="form-control border-start-0"
                    placeholder="Search by property name, location or area"
                    aria-label="Search properties"
                    value="{{ request('q') }}"
                >
            </div>

            {{-- Main filters --}}
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-xl">
                    <label for="location" class="filter-label">
                        Location
                    </label>

                    <select
                        id="location"
                        name="location"
                        class="form-select filter-control"
                    >
                        <option value="">Any location</option>
                        <option value="Hulhumale"
                            @selected(request('location') === 'Hulhumale')>
                            Hulhumalé
                        </option>
                        <option value="Male"
                            @selected(request('location') === 'Male')>
                            Malé
                        </option>
                        <option value="Villingili"
                            @selected(request('location') === 'Villingili')>
                            Villingili
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl">
                    <label for="bedrooms" class="filter-label">
                        Bedrooms
                    </label>

                    <select
                        id="bedrooms"
                        name="bedrooms"
                        class="form-select filter-control"
                    >
                        <option value="">Any</option>
                        <option value="1"
                            @selected(request('bedrooms') === '1')>
                            1+
                        </option>
                        <option value="2"
                            @selected(request('bedrooms', '2') === '2')>
                            2+
                        </option>
                        <option value="3"
                            @selected(request('bedrooms') === '3')>
                            3+
                        </option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl">
                    <label for="max_rent" class="filter-label">
                        Maximum monthly rent
                    </label>

                    <div class="input-group">
                        <span class="input-group-text">MVR</span>
                        <input
                            type="number"
                            id="max_rent"
                            name="max_rent"
                            class="form-control filter-control"
                            min="0"
                            step="500"
                            placeholder="Any budget"
                            value="{{ request('max_rent', '20000') }}"
                        >
                    </div>
                </div>

                <div class="col-6 col-xl-auto">
                    <button
                        class="btn btn-outline-secondary w-100 py-3"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#advancedFilters"
                        aria-expanded="false"
                        aria-controls="advancedFilters"
                    >
                        More filters
                    </button>
                </div>

                <div class="col-6 col-xl-auto">
                    <button type="submit" class="btn btn-hr w-100 py-3 px-xl-4">
                        Search properties
                    </button>
                </div>
            </div>

            {{-- Additional filters --}}
            <div class="collapse mt-3" id="advancedFilters">
                <div class="row g-3 border-top pt-3">
                    <div class="col-12 col-md-6">
                        <label for="property_type" class="filter-label">
                            Property type
                        </label>

                        <select
                            id="property_type"
                            name="property_type"
                            class="form-select filter-control"
                        >
                            <option value="">All types</option>
                            <option value="Apartment"
                                @selected(request('property_type') === 'Apartment')>
                                Apartment
                            </option>
                            <option value="Studio"
                                @selected(request('property_type') === 'Studio')>
                                Studio
                            </option>
                        </select>
                    </div>

                    <div class="col-12 col-md-6">
                        <label for="availability" class="filter-label">
                            Availability
                        </label>

                        <select
                            id="availability"
                            name="availability"
                            class="form-select filter-control"
                        >
                            <option value="">Any availability</option>
                            <option value="available"
                                @selected(request('availability') === 'available')>
                                Available now
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <div class="container-xxl px-3 px-lg-4 search-section">
        {{-- Recommended properties --}}
        <section aria-labelledby="recommended-heading">
            <div class="d-flex flex-column flex-sm-row
                        align-items-sm-end justify-content-between gap-2 mb-4">
                <div>
                    <h2 id="recommended-heading" class="section-heading mb-2">
                        Recommended for you
                    </h2>

                    <p class="text-muted-custom small mb-0">
                        Based on your preferences:
                        <strong class="text-dark">
                            Hulhumalé · Up to MVR 20,000 · 2+ bedrooms
                        </strong>
                    </p>
                </div>

                <span class="results-count">3 best matches</span>
            </div>

            <div class="row g-4">
                {{-- Recommended property 1 --}}
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="property-card h-100">
                        <div class="position-relative overflow-hidden">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Ocean View Residence interior"
                                class="property-image"
                                loading="lazy"
                            >

                            <span class="badge bg-light text-success position-absolute top-0 start-0 m-3">
                                <span class="availability-dot"></span>
                                Available now
                            </span>

                            <button
                                type="button"
                                class="save-button position-absolute top-0 end-0 m-3"
                                aria-label="Save Ocean View Residence"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.8" aria-hidden="true">
                                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l8.9 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <p class="property-type mb-1">
                                2 bedrooms · Apartment
                            </p>

                            <h3 class="h5 mb-2">
                                Ocean View Residence
                            </h3>

                            <p class="property-location mb-0">
                                <span aria-hidden="true">📍</span>
                                Hulhumalé · Phase 2
                            </p>

                            <hr class="property-divider my-4">

                            <div class="d-flex align-items-center
                                        justify-content-between gap-2">
                                <p class="property-price mb-0">
                                    MVR 18,000
                                    <small>/ month</small>
                                </p>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr btn-sm rounded-pill px-3">
                                    View
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Recommended property 2 --}}
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="property-card h-100">
                        <div class="position-relative overflow-hidden">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Palm Court Apartment interior"
                                class="property-image"
                                loading="lazy"
                            >

                            <span class="badge bg-light text-success position-absolute top-0 start-0 m-3">
                                <span class="availability-dot"></span>
                                Available now
                            </span>

                            <button
                                type="button"
                                class="save-button position-absolute top-0 end-0 m-3"
                                aria-label="Save Palm Court Apartment"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.8" aria-hidden="true">
                                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l8.9 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <p class="property-type mb-1">
                                3 bedrooms · Apartment
                            </p>

                            <h3 class="h5 mb-2">
                                Palm Court Apartment
                            </h3>

                            <p class="property-location mb-0">
                                <span aria-hidden="true">📍</span>
                                Hulhumalé · Phase 1
                            </p>

                            <hr class="property-divider my-4">

                            <div class="d-flex align-items-center
                                        justify-content-between gap-2">
                                <p class="property-price mb-0">
                                    MVR 19,500
                                    <small>/ month</small>
                                </p>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr btn-sm rounded-pill px-3">
                                    View
                                </a>
                            </div>
                        </div>
                    </article>
                </div>

                {{-- Recommended property 3 --}}
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="property-card h-100">
                        <div class="position-relative overflow-hidden">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Ocean Breeze Apartment interior"
                                class="property-image"
                                loading="lazy"
                            >

                            <span class="badge bg-light text-success position-absolute top-0 start-0 m-3">
                                <span class="availability-dot"></span>
                                Available now
                            </span>

                            <button
                                type="button"
                                class="save-button position-absolute top-0 end-0 m-3"
                                aria-label="Save Ocean Breeze Apartment"
                            >
                                <svg width="18" height="18" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor"
                                     stroke-width="1.8" aria-hidden="true">
                                    <path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l8.9 8.8 8.8-8.8a5.5 5.5 0 0 0 0-7.8z"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <p class="property-type mb-1">
                                2 bedrooms · Apartment
                            </p>

                            <h3 class="h5 mb-2">
                                Ocean Breeze Apartment
                            </h3>

                            <p class="property-location mb-0">
                                <span aria-hidden="true">📍</span>
                                Hulhumalé · Phase 2
                            </p>

                            <hr class="property-divider my-4">

                            <div class="d-flex align-items-center
                                        justify-content-between gap-2">
                                <p class="property-price mb-0">
                                    MVR 17,000
                                    <small>/ month</small>
                                </p>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr btn-sm rounded-pill px-3">
                                    View
                                </a>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        {{-- All available properties --}}
        <section class="mt-5 mt-lg-5" aria-labelledby="all-properties-heading">
            <div class="d-flex flex-column flex-sm-row
                        align-items-sm-end justify-content-between gap-2 mb-4">
                <div>
                    <p class="eyebrow mb-2">Explore more</p>

                    <h2 id="all-properties-heading" class="section-heading mb-0">
                        All available properties
                    </h2>
                </div>

                <span class="results-count">8 properties found</span>
            </div>

            <div class="d-flex flex-column gap-3">
                {{-- Property 1 --}}
                <article class="property-list-item p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-sm-4 col-lg-3">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Ocean View Residence interior"
                                class="property-list-image"
                                loading="lazy"
                            >
                        </div>

                        <div class="col-12 col-sm-8 col-lg">
                            <h3 class="h5 mb-2">Ocean View Residence</h3>

                            <p class="property-location mb-3">
                                <span aria-hidden="true">📍</span>
                                Hulhumalé · Phase 2
                            </p>

                            <div class="d-flex flex-wrap gap-3 small fw-bold">
                                <span>2 bedrooms</span>
                                <span>
                                    MVR 18,000
                                    <span class="text-muted fw-normal">/ month</span>
                                </span>
                            </div>

                            <p class="availability mt-3 mb-0">
                                <span class="availability-dot"></span>
                                Available now
                            </p>
                        </div>

                        <div class="col-12 col-lg-auto">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <button
                                    type="button"
                                    class="save-button"
                                    aria-label="Save Ocean View Residence"
                                >
                                    <span aria-hidden="true">♡</span>
                                </button>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr px-3 py-2">
                                    View property
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Property 2 --}}
                <article class="property-list-item p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-sm-4 col-lg-3">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Palm Court Apartment interior"
                                class="property-list-image"
                                loading="lazy"
                            >
                        </div>

                        <div class="col-12 col-sm-8 col-lg">
                            <h3 class="h5 mb-2">Palm Court Apartment</h3>

                            <p class="property-location mb-3">
                                <span aria-hidden="true">📍</span>
                                Hulhumalé · Phase 1
                            </p>

                            <div class="d-flex flex-wrap gap-3 small fw-bold">
                                <span>3 bedrooms</span>
                                <span>
                                    MVR 19,500
                                    <span class="text-muted fw-normal">/ month</span>
                                </span>
                            </div>

                            <p class="availability mt-3 mb-0">
                                <span class="availability-dot"></span>
                                Available now
                            </p>
                        </div>

                        <div class="col-12 col-lg-auto">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <button
                                    type="button"
                                    class="save-button"
                                    aria-label="Save Palm Court Apartment"
                                >
                                    <span aria-hidden="true">♡</span>
                                </button>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr px-3 py-2">
                                    View property
                                </a>
                            </div>
                        </div>
                    </div>
                </article>

                {{-- Property 3 --}}
                <article class="property-list-item p-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-12 col-sm-4 col-lg-3">
                            <img
                                src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg"
                                alt="Lagoon Residence interior"
                                class="property-list-image"
                                loading="lazy"
                            >
                        </div>

                        <div class="col-12 col-sm-8 col-lg">
                            <h3 class="h5 mb-2">Lagoon Residence</h3>

                            <p class="property-location mb-3">
                                <span aria-hidden="true">📍</span>
                                Malé · Maafannu
                            </p>

                            <div class="d-flex flex-wrap gap-3 small fw-bold">
                                <span>2 bedrooms</span>
                                <span>
                                    MVR 16,000
                                    <span class="text-muted fw-normal">/ month</span>
                                </span>
                            </div>

                            <p class="availability mt-3 mb-0">
                                <span class="availability-dot"></span>
                                Available Mar 12
                            </p>
                        </div>

                        <div class="col-12 col-lg-auto">
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <button
                                    type="button"
                                    class="save-button"
                                    aria-label="Save Lagoon Residence"
                                >
                                    <span aria-hidden="true">♡</span>
                                </button>

                                <a href="{{ url('/client/properties') }}"
                                   class="btn btn-hr px-3 py-2">
                                    View property
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            </div>

            {{-- Pagination placeholder --}}
            <nav class="mt-4" aria-label="Property pages">
                <ul class="pagination justify-content-center">
                    <li class="page-item active" aria-current="page">
                        <a class="page-link" href="?page=1">1</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=2">2</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=3">3</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=4">4</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" href="?page=2" aria-label="Next page">
                            &rsaquo;
                        </a>
                    </li>
                </ul>
            </nav>
        </section>
    </div>
</main>
@endsection