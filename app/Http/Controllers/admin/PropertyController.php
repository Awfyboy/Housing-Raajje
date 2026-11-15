<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity\AuditLog;
use App\Models\Entity\Branch;
use App\Models\Entity\Property;
use App\Models\Entity\PropertyRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query()->with(['owner.user', 'branch']);

        if ($term = $request->string('q')->toString()) {
            $query->where(fn ($w) => $w
                ->where('title', 'like', "%{$term}%")
                ->orWhere('address', 'like', "%{$term}%")
                ->orWhere('location', 'like', "%{$term}%"));
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($branchId = $request->integer('branch_id')) {
            $query->where('branch_id', $branchId);
        }

        $properties = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total'       => Property::count(),
            'pending'     => Property::where('status', Property::STATUS_PENDING)->count(),
            'available'   => Property::where('status', Property::STATUS_AVAILABLE)->count(),
            'rented'      => Property::where('status', Property::STATUS_RENTED)->count(),
            'unavailable' => Property::where('status', Property::STATUS_UNAVAILABLE)->count(),
        ];


        $branches = Branch::orderBy('name')->get(['id', 'name']);

        return view('admin.properties.index', compact(
            'properties', 'stats', 'branches'
        ));
    }

    public function show(Property $property)
    {
        $property->load([
            'owner.user',
            'branch',
            'registrations' => fn ($q) => $q->latest('submitted_at'),
            'registrations.submitter',
            'registrations.reviewer',
        ]);

        return view('admin.properties.show', compact('property'));
    }

    public function approve(Property $property)
    {
        $reg = $property->registrations()
            ->where('status', PropertyRegistration::STATUS_PENDING)
            ->latest('submitted_at')
            ->first();

        if (! $reg) {
            return back()->with('error', 'No pending registration found for this property.');
        }

        DB::transaction(function () use ($reg, $property) {
            $reg->update([
                'status'      => PropertyRegistration::STATUS_APPROVED,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            $property->update(['status' => Property::STATUS_AVAILABLE]);

            // Audit Log: Property Approved
            AuditLog::create([
                'actor_id'    => auth()->id(),
                'action'      => 'APPROVE_PROPERTY',
                'entity_type' => 'Property',
                'entity_id'   => $property->id,
                'description' => "Approved property registration #{$reg->id} for “{$property->title}” and set status to available.",
                'details'     => [
                    'registration_id' => $reg->id,
                    'owner_id'        => $property->owner_id,
                    'new_status'      => Property::STATUS_AVAILABLE,
                ],
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return back()->with('success', "Property “{$property->title}” approved and set to Available.");
    }

    public function reject(Request $request, Property $property)
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:500'],
        ]);

        $reg = $property->registrations()
            ->where('status', PropertyRegistration::STATUS_PENDING)
            ->latest('submitted_at')
            ->first();

        if (! $reg) {
            return back()->with('error', 'No pending registration found for this property.');
        }

        $reg->update([
            'status'           => PropertyRegistration::STATUS_REJECTED,
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $data['reason'],
        ]);

        // Audit Log: Property Rejected
        AuditLog::create([
            'actor_id'    => auth()->id(),
            'action'      => 'REJECT_PROPERTY',
            'entity_type' => 'Property',
            'entity_id'   => $property->id,
            'description' => "Rejected property registration #{$reg->id} for “{$property->title}”: {$data['reason']}",
            'details'     => [
                'registration_id'  => $reg->id,
                'owner_id'         => $property->owner_id,
                'rejection_reason' => $data['reason'],
            ],
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return back()->with('success', "Property “{$property->title}” rejected.");
    }

    public function destroy(Property $property)
    {
        $title    = $property->title;
        $propertyId = $property->id;
        $ownerId  = $property->owner_id;

        // Delete first, log after — same pattern as BranchController
        $property->delete();

        // Audit Log: Property Deleted
        AuditLog::create([
            'actor_id'    => auth()->id(),
            'action'      => 'DELETE_PROPERTY',
            'entity_type' => 'Property',
            'entity_id'   => $propertyId,
            'description' => "Deleted property: “{$title}”.",
            'details'     => ['owner_id' => $ownerId],
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Property deleted.');
    }
}