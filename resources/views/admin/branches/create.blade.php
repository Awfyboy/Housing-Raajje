@extends('admin.layouts.master')

@section('title', 'Add Branch')

@section('content')
<div class="page-heading">
    <div class="eyebrow">Branch Management</div>
    <h1 class="page-title">Add Branch</h1>
    <p class="page-description">Create a new regional branch.</p>
</div>

<div class="card" style="max-width: 600px;">
    <form method="POST" action="{{ route('admin.branches.store') }}">
        @csrf

        @if ($errors->any())
            <div class="alert small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #ffdad6; color: #ba1a1a;">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <label class="form-label fw-bold small">Branch Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                   placeholder="e.g., Malé West Branch"
                   style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Location</label>
            <select name="location" required class="form-control"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">Select location...</option>
                @foreach ($locations as $location)
                    <option value="{{ $location->value }}" {{ old('location') === $location->value ? 'selected' : '' }}>
                        {{ $location->label() }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small">Specific Address / Neighborhood (Optional)</label>
            <textarea name="address" class="form-control" rows="3" 
                    placeholder="e.g., Henveiru District, Boduthakurufaanu Magu, 20026"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">{{ old('address') }}</textarea>
            
            <small class="text-muted">
                Include District, Goalhi/Road name, and Postal Code. 
                (The Island is selected above).
            </small>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small">Assign Branch Manager (Optional)</label>
            <select name="manager_id" class="form-control"
                    style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">-- No manager yet --</option>
                @foreach ($managers as $manager)
                    <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                        {{ $manager->name }} ({{ $manager->email }})
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Only unassigned users with the Branch Manager role are shown.</small>
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary">Create Branch</button>
            <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection