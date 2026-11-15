<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Entity\Property;
use App\Models\Entity\PropertyRegistration;
use App\Models\Entity\Branch;
use App\Models\Entity\AuditLog; // Added for Audit Logging
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    // Show list of owner's properties
    public function index(Request $request)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner) {
            abort(403, 'negative on Owner profile.');
        }

        $properties = Property::where('owner_id', $owner->id)
            // Filter based on the pill clicked
            ->when($request->filled('status'), function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Keeps the filter active when paginating

        return view('owner.properties.index', compact('properties'));
    }

    // Show the registration form
    public function create()
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner) {
            abort(403, 'You do not have an Owner profile.');
        }

        return view('owner.properties.create');
    }

    // Handle the form submission
    public function store(Request $request)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner) {
            abort(403, 'You do not have an Owner profile.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,flat,studio,house,room',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'nullable|integer|min:1',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'monthly_rent' => 'required|numeric|min:0',
        ]);

        // --- AUTOMATIC BRANCH MATCHING ---
        $locationString = strtolower($data['location']);
        
        $branch = Branch::where(function($query) use ($locationString) {
            if (str_contains($locationString, 'male') || str_contains($locationString, 'malé')) {
                $query->where('name', 'like', '%Malé%');
            } elseif (str_contains($locationString, 'hulhumale') || str_contains($locationString, 'hulhumalé')) {
                $query->where('name', 'like', '%Hulhumalé%');
            } elseif (str_contains($locationString, 'villingili')) {
                $query->where('name', 'like', '%Villingili%');
            }
        })->first();

        if (!$branch) {
            $branch = Branch::where('name', 'like', '%Malé%')->first() ?? Branch::first();
        }

        try {
            DB::beginTransaction();

            // 1. Create the Property (Status: Pending)
            $property = Property::create([
                'owner_id' => $owner->id,
                'branch_id' => $branch->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'property_type' => $data['property_type'],
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'address' => $data['address'],
                'location' => $data['location'],
                'monthly_rent' => $data['monthly_rent'],
                'status' => Property::STATUS_PENDING,
            ]);

            // 2. Create the Registration Record for Admin Approval
            PropertyRegistration::create([
                'property_id' => $property->id,
                'submitted_by' => Auth::id(),
                'status' => PropertyRegistration::STATUS_PENDING,
                'submitted_at' => now(),
            ]);

            // Audit Log: Property Submitted
            AuditLog::create([
                'actor_id' => auth()->id(),
                'action' => 'SUBMIT_PROPERTY',
                'entity_type' => 'Property',
                'entity_id' => $property->id,
                'description' => "Submitted new property for approval: {$property->title}.",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('owner.properties.index')
                ->with('success', 'Property submitted successfully! It is now pending admin approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Show single property details (including status)
    public function show(Property $property)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner || $property->owner_id !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }

        $property->load('registrations');

        return view('owner.properties.show', compact('property'));
    }

    // Show the edit form for rejected/pending properties
    public function edit(Property $property)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner || $property->owner_id !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }

        return view('owner.properties.edit', compact('property'));
    }

    // Handle the edit form submission
    public function update(Request $request, Property $property)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner || $property->owner_id !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|in:apartment,flat,studio,house,room',
            'bedrooms' => 'required|integer|min:1',
            'bathrooms' => 'nullable|integer|min:1',
            'address' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'monthly_rent' => 'required|numeric|min:0',
        ]);

        // Re-run the branch matching
        $locationString = strtolower($data['location']);
        $branch = Branch::where(function($query) use ($locationString) {
            if (str_contains($locationString, 'male') || str_contains($locationString, 'malé')) {
                $query->where('name', 'like', '%Malé%');
            } elseif (str_contains($locationString, 'hulhumale') || str_contains($locationString, 'hulhumalé')) {
                $query->where('name', 'like', '%Hulhumalé%');
            } elseif (str_contains($locationString, 'villingili')) {
                $query->where('name', 'like', '%Villingili%');
            }
        })->first() ?? Branch::where('name', 'like', '%Malé%')->first() ?? Branch::first();

        try {
            DB::beginTransaction();

            // 1. Update the Property
            $property->update([
                'branch_id' => $branch->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'property_type' => $data['property_type'],
                'bedrooms' => $data['bedrooms'],
                'bathrooms' => $data['bathrooms'],
                'address' => $data['address'],
                'location' => $data['location'],
                'monthly_rent' => $data['monthly_rent'],
                'status' => Property::STATUS_PENDING, // Reset to pending
            ]);

            // 2. Reset the Registration status
            $registration = $property->registrations()->latest()->first();
            if ($registration) {
                $registration->update([
                    'status' => PropertyRegistration::STATUS_PENDING,
                    'submitted_at' => now(),
                    'reviewed_at' => null,
                    'reviewed_by' => null,
                    'rejection_reason' => null,
                ]);
            }

            // Audit Log: Property Resubmitted
            AuditLog::create([
                'actor_id' => auth()->id(),
                'action' => 'RESUBMIT_PROPERTY',
                'entity_type' => 'Property',
                'entity_id' => $property->id,
                'description' => "Updated and resubmitted property for approval: {$property->title}.",
                'ip_address' => request()->ip(),
            ]);

            DB::commit();

            return redirect()->route('owner.properties.index')
                ->with('success', 'Property updated and resubmitted for approval!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    // Delete the property
    public function destroy(Property $property)
    {
        $owner = Auth::user()->ownerProfile;

        if (!$owner || $property->owner_id !== $owner->id) {
            abort(403, 'Unauthorized action.');
        }

        $title = $property->title;
        $propertyId = $property->id;

        // Audit Log: Property Deleted (Log before deleting so we have the ID)
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'DELETE_PROPERTY',
            'entity_type' => 'Property',
            'entity_id' => $propertyId,
            'description' => "Deleted property: {$title}.",
            'ip_address' => request()->ip(),
        ]);

        $property->delete();

        return redirect()->route('owner.properties.index')
            ->with('success', 'Property deleted successfully.');
    }
}