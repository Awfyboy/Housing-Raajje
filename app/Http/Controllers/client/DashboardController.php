<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Entity\Property;
use App\Models\Entity\AuditLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $client = Auth::user()->clientProfile;

        if (!$client) {
            // User has client role but no profile yet — redirect to setup
            return redirect()->route('profile.edit')
                ->with('status', 'Please complete your Client profile first.');
        }

        // 1. Fetch recommended available properties
        $recommendedProperties = Property::where('status', Property::STATUS_AVAILABLE)
            ->latest()
            ->take(3)
            ->get();

        // 2. Application & Viewing counts
        $activeApplications = 0; // (Placeholder for now)
        $upcomingViewings = 0;  // (Placeholder for now)
        $currentLease = null;   // (Placeholder for now)

        // 3. Recent activity (Audit Logs related to this client)
        // (Placeholder — will populate once applications and viewing are established)
        $recentActivity = AuditLog::with('actor')
            ->where('entity_type', 'Client')
            ->where('entity_id', $client->id)
            ->latest()
            ->take(5)
            ->get();

        return view('client.home', compact(
            'client',
            'recommendedProperties',
            'activeApplications',
            'upcomingViewings',
            'currentLease',
            'recentActivity'
        ));
    }
}