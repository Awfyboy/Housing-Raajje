<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entity\Branch;
use App\Models\Entity\Property;
use App\Models\Entity\AuditLog;
use App\Enums\UserRoles;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // counts
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalStaff = User::where('role', UserRoles::STAFF)->count();
        $totalBranches = Branch::count();
        
        //count by role, not profile
        $totalClients = User::where('role', UserRoles::CLIENT)->count();
        $totalOwners = User::where('role', UserRoles::PROPERTY_OWNER)->count();

        //recent activity
        $recentActivity = AuditLog::with('actor')
            ->latest()
            ->take(5)
            ->get();

        //pending requests
        $pendingRequestsCount = User::where('admin_request_status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProperties',
            'totalStaff',
            'totalBranches',
            'totalClients',
            'totalOwners',
            'recentActivity',
            'pendingRequestsCount'
        ));
    }
}