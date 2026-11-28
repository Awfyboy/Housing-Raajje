@extends('client.layouts.master')

@section('title', 'Find Properties')
@section('activeNav', 'properties')

@push('styles')
<style>
    /* Property search page */
    .search-panel,
    .property-card {
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
        background-color: var(--hr-surface);
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

    .availability-dot {
        display: inline-block;
        width: 7px;
        height: 7px;
        margin-right: 0.35rem;
        border-radius: 50%;
        background-color: #46a773;
    }

    .property-divider {
        border-color: #eee5da;
        opacity: 1;
    }

    .search-section {
        padding-top: 3rem;
        padding-bottom: 4rem;
    }

    @media (max-width: 575.98px) {
        .search-section {
            padding-top: 2rem;
            padding-bottom: 3rem;
        }

        .property-image {
            height: 200px;
        }
    }
</style>
@endpush

@section('content')
<main>
    {{-- Search and filters --}}
    <section class="container-xxl px-3 px-lg-4 pt-4 pt-lg-5">
        <form action="{{ route('client.properties.index') }}" method="GET" class="search-panel p-3 p-md-4">
            {{-- Keyword search --}}
            <div class="input-group input-group-lg mb-4">
                <span class="input-group-text bg-transparent border-end-0">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="7"></circle>
                        <path d="m20 20-4-4"></path>
                    </svg>
                </span>
                <input type="search" name="q" class="form-control border-start-0" placeholder="Search by property name, location or area" value="{{ request('q') }}">
            </div>

            {{-- Main filters --}}
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-6 col-xl">
                    <label for="location" class="filter-label">Location</label>
                    <select id="location" name="location" class="form-select filter-control">
                        <option value="">Any location</option>
                        <option value="Hulhumale" @selected(request('location') === 'Hulhumale')>Hulhumalé</option>
                        <option value="Male" @selected(request('location') === 'Male')>Malé</option>
                        <option value="Villingili" @selected(request('location') === 'Villingili')>Villingili</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl">
                    <label for="bedrooms" class="filter-label">Bedrooms</label>
                    <select id="bedrooms" name="bedrooms" class="form-select filter-control">
                        <option value="">Any</option>
                        <option value="1" @selected(request('bedrooms') === '1')>1+</option>
                        <option value="2" @selected(request('bedrooms') === '2')>2+</option>
                        <option value="3" @selected(request('bedrooms') === '3')>3+</option>
                        <option value="4" @selected(request('bedrooms') === '4')>4+</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 col-xl">
                    <label for="max_rent" class="filter-label">Maximum monthly rent</label>
                    <div class="input-group">
                        <span class="input-group-text">MVR</span>
                        <input type="number" id="max_rent" name="max_rent" class="form-control filter-control" min="0" step="500" placeholder="Any budget" value="{{ request('max_rent') }}">
                    </div>
                </div>

                <div class="col-6 col-xl-auto">
                    <button class="btn btn-outline-secondary w-100 py-3" type="button" data-bs-toggle="collapse" data-bs-target="#advancedFilters" aria-expanded="false" aria-controls="advancedFilters">
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
                        <label for="property_type" class="filter-label">Property type</label>
                        <select id="property_type" name="property_type" class="form-select filter-control">
                            <option value="">All types</option>
                            <option value="Apartment" @selected(request('property_type') === 'Apartment')>Apartment</option>
                            <option value="Flat" @selected(request('property_type') === 'Flat')>Flat</option>
                            <option value="Studio" @selected(request('property_type') === 'Studio')>Studio</option>
                            <option value="House" @selected(request('property_type') === 'House')>House</option>
                            <option value="Room" @selected(request('property_type') === 'Room')>Room</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <div class="container-xxl px-3 px-lg-4 search-section">
        <section aria-labelledby="recommended-heading" class="mb-5">
            <div class="d-flex flex-column flex-sm-row align-items-sm-end justify-content-between gap-2 mb-4">
                <div>
                    <h2 id="recommended-heading" class="section-heading mb-2">Available Properties</h2>
                    <p class="text-muted-custom small mb-0">Showing {{ $properties->total() }} properties</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse($properties as $property)
                    <div class="col-12 col-md-6 col-xl-4">
                        <article class="property-card h-100">
                            <div class="position-relative overflow-hidden">
                                <img src="https://www.gstatic.com/labs-code/stitch/stitch-placeholder-300x300.svg" alt="{{ $property->title }}" class="property-image" loading="lazy">
                                <span class="badge bg-light text-success position-absolute top-0 start-0 m-3">
                                    <span class="availability-dot"></span> Available now
                                </span>
                            </div>

                            <div class="p-4">
                                <p class="property-type mb-1">
                                    {{ $property->bedrooms }} bedrooms · {{ ucfirst($property->property_type) }}
                                </p>

                                <h3 class="h5 fw-bold mb-2">{{ $property->title }}</h3>

                                <p class="property-location mb-0">
                                    <span aria-hidden="true"></span> {{ $property->location }}
                                </p>

                                <hr class="property-divider my-4">

                                <div class="d-flex align-items-center justify-content-between gap-2">
                                    <p class="property-price mb-0">
                                        MVR {{ number_format($property->monthly_rent, 0) }}
                                        <small>/ month</small>
                                    </p>

                                    <a href="{{ route('client.properties.show', $property) }}" class="btn btn-hr btn-sm rounded-pill px-3">
                                        View
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h3 class="h5 fw-bold">No properties found</h3>
                        <p class="text-muted-custom">Try adjusting your search filters.</p>
                    </div>
                @endforelse
            </div>

            {{-- ---------- Pagination footer ---------- --}}
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 12px;">
                
                {{-- Info text --}}
                <div style="color: #a0aec0;">
                    @if ($properties->total() > 0)
                        <span style="color: #a0aec0;">Showing {{ $properties->firstItem() }}–{{ $properties->lastItem() }} of {{ $properties->total() }}</span>
                    @else
                        <span style="color: #a0aec0;">Showing 0 of 0</span>
                    @endif
                </div>

                {{-- Nav buttons --}}
                <div style="display: flex; gap: 0.25rem; align-items: center;">
                    @if ($properties->onFirstPage())
                        <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">« Prev</span>
                    @else
                        <a href="{{ $properties->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #ffffff; text-decoration: none; display: inline-block;">« Prev</a>
                    @endif

                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #111827;">{{ $properties->currentPage() }}</span>

                    @if ($properties->hasMorePages())
                        <a href="{{ $properties->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #ffffff; text-decoration: none; display: inline-block;">Next »</a>
                    @else
                        <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">Next »</span>
                    @endif
                </div>
            </div>
        </section>
    </div>
</main>
@endsection