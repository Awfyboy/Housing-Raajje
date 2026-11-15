<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entity\Branch;
use App\Models\User;
use App\Models\Entity\AuditLog;
use App\Enums\UserRoles;
use App\Enums\BranchLocations;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $branches = Branch::query()
            ->with('manager')
            ->when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.branches.index', compact('branches', 'search'));
    }

    public function create()
    {
        // Only unassigned Branch Managers can be picked
        $managers = User::where('role', UserRoles::BRANCH_MANAGER)
            ->whereNull('branch_id')
            ->orderBy('name')
            ->get();

        // Available islands (those that don't have a branch yet)
        $locations = array_filter(BranchLocations::cases(), function ($location) {
            return ! Branch::where('location', $location->value)->exists();
        });

        return view('admin.branches.create', compact('managers', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches,name'],
            'location' => ['required', Rule::enum(BranchLocations::class), Rule::unique('branches', 'location')], // one branch per island
            'address' => ['nullable', 'string', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        $branch = Branch::create($data);

        if ($branch->manager_id) {
            User::where('id', $branch->manager_id)->update(['branch_id' => $branch->id]);
        }

        // Audit Log: Branch Created
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'CREATE_BRANCH',
            'entity_type' => 'Branch',
            'entity_id' => $branch->id,
            'description' => "Created branch: {$branch->name}.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('status', "Branch '{$branch->name}' created successfully.");
    }

    public function edit(Branch $branch)
    {
        // Show unassigned managers + the current manager
        $managers = User::where('role', UserRoles::BRANCH_MANAGER)
            ->where(function ($query) use ($branch) {
                $query->whereNull('branch_id')
                      ->orWhere('id', $branch->manager_id);
            })
            ->orderBy('name')
            ->get();

        $locations = BranchLocations::cases();

        return view('admin.branches.edit', compact('branch', 'managers', 'locations'));
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('branches')->ignore($branch->id)],
            'location' => ['required', Rule::enum(BranchLocations::class), Rule::unique('branches', 'location')->ignore($branch->id)], // one branch per island
            'address' => ['nullable', 'string', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        // Handle manager replacement with a clear audit log
        if ($branch->manager_id !== $data['manager_id']) {
            $oldManagerId = $branch->manager_id;
            $oldManagerName = $oldManagerId ? User::find($oldManagerId)?->name : 'none';

            if ($oldManagerId) {
                User::where('id', $oldManagerId)->update(['branch_id' => null]);
            }

            $newManagerName = 'none';
            if ($data['manager_id']) {
                User::where('id', $data['manager_id'])->update(['branch_id' => $branch->id]);
                $newManagerName = User::find($data['manager_id'])?->name;
            }

            AuditLog::create([
                'actor_id' => auth()->id(),
                'action' => 'REPLACE_BRANCH_MANAGER',
                'entity_type' => 'Branch',
                'entity_id' => $branch->id,
                'description' => "Replaced manager of {$branch->name}: {$oldManagerName} → {$newManagerName}.",
                'ip_address' => request()->ip(),
            ]);
        }

        $branch->update($data);

        // Audit Log: Branch Updated
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'UPDATE_BRANCH',
            'entity_type' => 'Branch',
            'entity_id' => $branch->id,
            'description' => "Updated branch: {$branch->name}.",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.branches.index')
            ->with('status', "Branch '{$branch->name}' updated successfully.");
    }

    public function destroy(Branch $branch)
    {
        // Cannot delete if users are assigned
        if ($branch->users()->count() > 0) {
            return back()->withErrors(['branch' => 'Cannot delete this branch because it has staff/users assigned. Reassign them first.']);
        }

        $name = $branch->name;

        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'DELETE_BRANCH',
            'entity_type' => 'Branch',
            'entity_id' => $branch->id,
            'description' => "Deleted branch: {$name}.",
            'ip_address' => request()->ip(),
        ]);

        $branch->delete();

        return redirect()->route('admin.branches.index')
            ->with('status', "Branch '{$name}' deleted successfully.");
    }
}