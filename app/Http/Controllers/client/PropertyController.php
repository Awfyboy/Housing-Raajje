<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Entity\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        // Only show 'available' properties to clients
        $query = Property::where('status', Property::STATUS_AVAILABLE)->with('branch');

        // Smart Search Engine filtering
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('location', 'like', "%{$searchTerm}%")
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('location')) {
            // Normalize misspellings to match DB values
            $location = str_replace('Hulhumale', 'Hulhumalé', $request->location);
            $location = str_replace('Male', 'Malé', $location);
            $query->where('location', 'like', "%{$location}%");
        }

        if ($request->filled('max_rent')) {
            $query->where('monthly_rent', '<=', $request->max_rent);
        }

        if ($request->filled('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', strtolower($request->property_type));
        }

        $properties = $query->latest()->paginate(12)->withQueryString();

        return view('client.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        // Ensure the client can only see available properties
        if ($property->status !== Property::STATUS_AVAILABLE) {
            abort(404, 'Property not found or no longer available.');
        }

        // Eager load relationships so the details page can display owner + branch without N+1 queries
        $property->load(['owner.user', 'branch']);

        return view('client.properties.show', compact('property'));
    }
}