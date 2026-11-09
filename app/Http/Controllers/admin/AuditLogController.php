<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity\AuditLog;
use App\Models\User;
use App\Enums\UserRoles;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        // Read all filter values from the URL
        $search = $request->input('search');       // name / description
        $actorFilter = $request->input('actor_id'); // specific user
        $roleFilter = $request->input('role');     // user role (or 'system')
        $actionFilter = $request->input('action'); // e.g. LOGIN
        $dateFrom = $request->input('date_from');  // YYYY-MM-DD
        $dateTo = $request->input('date_to');      // YYYY-MM-DD

        // Users who have performed actions (for the dropdown)
        $actors = User::whereHas('auditLogs')->orderBy('name')->get();

        // Distinct action types
        $actions = AuditLog::select('action')->distinct()->pluck('action');

        // Available roles (add "System" as a special option)
        $roles = UserRoles::cases();

        // Main query
        $logs = AuditLog::query()
            ->with('actor')
            ->when($search, function ($q, $s) {
                // Search both description AND actor's name
                $q->where(function ($sub) use ($s) {
                    $sub->where('description', 'like', "%{$s}%")
                        ->orWhereHas('actor', fn ($a) => $a->where('name', 'like', "%{$s}%"));
                });
            })
            ->when($actorFilter, fn ($q, $a) => $q->where('actor_id', $a))
            ->when($roleFilter, function ($q, $role) {
                if ($role === 'system') {
                    $q->whereNull('actor_id'); // System = no actor
                } else {
                    $q->whereHas('actor', fn ($a) => $a->where('role', $role));
                }
            })
            ->when($actionFilter, fn ($q, $a) => $q->where('action', $a))
            ->when($dateFrom, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
            ->when($dateTo, fn ($q, $d) => $q->whereDate('created_at', '<=', $d))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.auditlogs', compact(
            'logs', 'actors', 'actions', 'roles',
            'search', 'actorFilter', 'roleFilter', 'actionFilter',
            'dateFrom', 'dateTo'
        ));
    }
}