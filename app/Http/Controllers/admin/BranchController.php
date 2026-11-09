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
                // Search by name only (location is an enum, not a string)
                return $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.branches.index', compact('branches', 'search'));
    }

    public function create()
    {
        // Only users with the BRANCH_MANAGER role can be assigned as managers
        $managers = User::where('role', UserRoles::BRANCH_MANAGER)
            ->whereNull('branch_id')
            ->orderBy('name')
            ->get();

        // Enum values for the location dropdown
        $locations = BranchLocations::cases();

        return view('admin.branches.create', compact('managers', 'locations'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:branches,name'],
            'location' => ['required', Rule::enum(BranchLocations::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        $branch = Branch::create($data);

        // Two-way sync: Update the manager's branch_id
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
        // Show unassigned managers, PLUS the current manager (if any)
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
            'location' => ['required', Rule::enum(BranchLocations::class)],
            'address' => ['nullable', 'string', 'max:255'],
            'manager_id' => ['nullable', 'exists:users,id'],
        ]);

        // Handle manager change (free old manager, assign new one)
        if ($branch->manager_id !== $data['manager_id']) {
            if ($branch->manager_id) {
                User::where('id', $branch->manager_id)->update(['branch_id' => null]);
            }
            if ($data['manager_id']) {
                User::where('id', $data['manager_id'])->update(['branch_id' => $branch->id]);
            }
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
        // 🛡️ SAFETY RULE: Cannot delete a branch that has users or properties
        if ($branch->users()->count() > 0) {
            return back()->withErrors(['branch' => 'Cannot delete this branch because it has staff/users assigned. Reassign them first.']);
        }
        
        // Future safety: if ($branch->properties()->count() > 0) { ... }

        $name = $branch->name;

        // Audit Log: Branch Deleted
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