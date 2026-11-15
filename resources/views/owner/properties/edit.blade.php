@extends('owner.layouts.master')

@section('title', 'Edit Property')
@section('activeNav', 'properties')

@section('content')
<div class="page-container page-content">
    <div class="mb-4">
        <a href="{{ route('owner.properties.show', $property) }}" class="text-link-hr mb-3">
            <span class="material-symbols-outlined fs-6">arrow_back</span> Back to Property
        </a>
        <h1 class="section-heading mt-2">Edit Property</h1>
        <p class="text-muted-custom">Update your details and resubmit for approval.</p>
    </div>

    <div class="hr-card">
        <form action="{{ route('owner.properties.update', $property) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row g-4">
                <!-- Title & Type -->
                <div class="col-12 col-md-8">
                    <label class="form-label fw-bold small">Listing Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $property->title) }}" required>
                    @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-4">
                    <label class="form-label fw-bold small">Property Type</label>
                    <select name="property_type" class="form-select" required>
                        <option value="apartment" {{ old('property_type', $property->property_type) === 'apartment' ? 'selected' : '' }}>Apartment</option>
                        <option value="flat" {{ old('property_type', $property->property_type) === 'flat' ? 'selected' : '' }}>Flat</option>
                        <option value="studio" {{ old('property_type', $property->property_type) === 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="house" {{ old('property_type', $property->property_type) === 'house' ? 'selected' : '' }}>House</option>
                        <option value="room" {{ old('property_type', $property->property_type) === 'room' ? 'selected' : '' }}>Room</option>
                    </select>
                    @error('property_type') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Address & Location -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">Exact Address</label>
                    <input type="text" name="address" class="form-control" value="{{ old('address', $property->address) }}" required>
                    @error('address') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">Location / Island</label>
                    <input type="text" name="location" class="form-control" value="{{ old('location', $property->location) }}" required>
                    <div class="form-text small text-muted-custom mt-1">The system will automatically assign the nearest branch.</div>
                    @error('location') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Rooms -->
                <div class="col-6 col-md-3">
                    <label class="form-label fw-bold small">Bedrooms</label>
                    <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms) }}" min="1" required>
                    @error('bedrooms') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-6 col-md-3">
                    <label class="form-label fw-bold small">Bathrooms</label>
                    <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms) }}" min="1">
                    @error('bathrooms') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Rent -->
                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small">Target Monthly Rent (MVR)</label>
                    <input type="number" name="monthly_rent" class="form-control" value="{{ old('monthly_rent', $property->monthly_rent) }}" min="0" step="0.01" required>
                    @error('monthly_rent') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Description -->
                <div class="col-12">
                    <label class="form-label fw-bold small">Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $property->description) }}</textarea>
                    @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>

                <!-- Submit -->
                <div class="col-12 mt-5">
                    <button type="submit" class="btn-hr w-100 w-md-auto">Update & Resubmit for Approval</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection