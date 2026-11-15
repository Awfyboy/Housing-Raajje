<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Entity\Property;
use App\Models\Entity\AuditLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Change "owner" to "ownerProfile"
        $owner = Auth::user()->ownerProfile;

        if (!$owner) {
            // User has property_owner role but no profile yet — redirect to setup
            return redirect()->route('profile.edit')
                ->with('status', 'Please complete your Owner profile first.');
        }

        // 2. Counts for this specific owner
        // We use the owner's properties relationship to ensure data is restricted
        $properties = $owner->properties;
        
        $totalProperties = $properties->count();
        
        // Status counts
        $activeRentals = $properties->where('status', Property::STATUS_RENTED)->count();
        $availableUnits = $properties->where('status', Property::STATUS_AVAILABLE)->count();
        $maintenanceUnits = $properties->where('status', Property::STATUS_UNAVAILABLE)->count();
        $pendingUnits = $properties->where('status', Property::STATUS_PENDING)->count();

        // Calculate Occupancy Rate
        $occupancyRate = $totalProperties > 0 
            ? round(($activeRentals / $totalProperties) * 100) 
            : 0;

        // Calculate Monthly Income (Sum of rent for rented properties)
        $monthlyIncome = $properties->where('status', Property::STATUS_RENTED)->sum('monthly_rent');

        // 3. Recent activity (Audit Logs related to this owner's properties)
        // We filter the logs to only show actions performed on properties owned by this user
        $propertyIds = $properties->pluck('id')->toArray();
        
        $recentActivity = AuditLog::with('actor')
            ->where('entity_type', 'Property')
            ->whereIn('entity_id', $propertyIds)
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'properties',
            'totalProperties',
            'activeRentals',
            'availableUnits',
            'maintenanceUnits',
            'pendingUnits',
            'occupancyRate',
            'monthlyIncome',
            'recentActivity'
        ));
    }
}