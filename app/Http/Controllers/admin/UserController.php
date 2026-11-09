<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Entity\Admin;
use App\Models\Entity\Branch;
use App\Models\Entity\BranchManager;
use App\Models\Entity\Staff;
use App\Models\Entity\Receptionist;
use App\Models\Entity\Client;
use App\Models\Entity\Owner;
use App\Models\Entity\AuditLog; 
use App\Enums\UserRoles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //The Super Admin ID cannot be demoted, suspended, or deleted.
    private const SUPER_ADMIN_ID = 1;

    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $branchFilter = $request->input('branch_id');

        $pendingRequestsCount = User::where('admin_request_status', 'pending')
            ->where('id', '!=', auth()->id())
            ->count();

        $users = User::query()
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($roleFilter, function ($query, $roleFilter) {
                return $query->where('role', $roleFilter);
            })
            ->when($branchFilter, function ($query, $branchId) {
                //Currently only filters internal staff (Manager, Staff, Receptionist)
                $query->where('branch_id', $branchId);
                
                /*
                | TODO_PROPERTIES: Uncomment when Property & Application models exist
                | This will allow filtering Clients and Owners by the branch of
                | their related properties/applications.
                |
                | $query->orWhereHas('ownerProfile.properties', function ($prop) use ($branchId) {
                |     $prop->where('branch_id', $branchId);
                | });
                |
                | $query->orWhereHas('clientProfile.applications.property', function ($prop) use ($branchId) {
                |     $prop->where('branch_id', $branchId);
                | });
                */
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'pendingRequestsCount', 'search', 'roleFilter', 'branchFilter', 'branches'));
    }

    /**
     * Show the dedicated Pending Requests page.
     */
    public function requests()
    {
        $pendingRequests = User::where('admin_request_status', 'pending')
            ->where('id', '!=', auth()->id()) // Don't show own requests
            ->latest()
            ->get();

        return view('admin.users.requests', compact('pendingRequests'));
    }

    public function create()
    {
        $roles = [
            UserRoles::STAFF,
            UserRoles::RECEPTIONIST,
            UserRoles::BRANCH_MANAGER,
            UserRoles::CLIENT,
            UserRoles::PROPERTY_OWNER,
            UserRoles::ADMIN,
        ];

        $branches = Branch::orderBy('name')->get();

        return view('admin.users.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $isStaffRole = in_array($request->role, ['staff', 'receptionist', 'branch_manager']);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'in:staff,receptionist,branch_manager,client,property_owner,admin'],
            'branch_id' => [$isStaffRole ? 'required' : 'nullable', 'exists:branches,id'],
            'employee_number' => [$isStaffRole ? 'required' : 'nullable', 'string', 'max:50'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRoles::from($data['role']),
            'branch_id' => $data['branch_id'] ?? null,
            'status' => 'active',
            'mfa_enabled' => true,
            'email_verified_at' => now(),
        ]);

        match ($user->role) {
            UserRoles::ADMIN => Admin::create([
                'user_id' => $user->id,
                'employee_number' => $data['employee_number'] ?? 'ADM-' . str_pad(Admin::max('id') + 1, 3, '0', STR_PAD_LEFT),
                'appointed_at' => now(),
                'employment_status' => 'active',
            ]),
            UserRoles::BRANCH_MANAGER => BranchManager::create([
                'user_id' => $user->id,
                'employee_number' => $data['employee_number'],
                'appointed_at' => now(),
                'employment_status' => 'active',
            ]),
            UserRoles::STAFF => Staff::create([
                'user_id' => $user->id,
                'employee_number' => $data['employee_number'],
                'hired_at' => now(),
                'employment_status' => 'active',
            ]),
            UserRoles::RECEPTIONIST => Receptionist::create([
                'user_id' => $user->id,
                'employee_number' => $data['employee_number'],
                'hired_at' => now(),
                'employment_status' => 'active',
            ]),
            UserRoles::CLIENT => Client::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
            ]),
            UserRoles::PROPERTY_OWNER => Owner::create([
                'user_id' => $user->id,
                'national_id' => null,
                'bank_account' => null,
                'address' => null,
            ]),
            default => null,
        };

        //Audit Log: User Created
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'CREATE_USER',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Created new user: {$user->name} ({$user->role->label()}).",
            'ip_address' => request()->ip(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('status', "{$user->name} has been created as a " . $user->role->label() . ".");
    }

    public function edit(User $user)
    {
        $roles = [
            UserRoles::STAFF,
            UserRoles::RECEPTIONIST,
            UserRoles::BRANCH_MANAGER,
            UserRoles::CLIENT,
            UserRoles::PROPERTY_OWNER,
            UserRoles::ADMIN,
        ];
        $branches = Branch::orderBy('name')->get();

        return view('admin.users.edit', compact('user', 'roles', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        //The Super Admin cannot be demoted
        if ($user->id === self::SUPER_ADMIN_ID && $request->role !== 'admin') {
            return back()->withErrors(['role' => 'The Super Admin cannot be demoted.']);
        }

        // Prevent demoting another admin (unless you're the Super Admin)
        if (
            $user->role === UserRoles::ADMIN
            && $user->id !== auth()->id()
            && $request->role !== 'admin'
            && auth()->id() !== self::SUPER_ADMIN_ID
        ) {
            return back()->withErrors(['role' => 'You cannot demote another admin.']);
        }

        $isStaffRole = in_array($request->role, ['staff', 'receptionist', 'branch_manager']);
        $oldRole = $user->role->label();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:staff,receptionist,branch_manager,client,property_owner,admin'],
            'branch_id' => [$isStaffRole ? 'required' : 'nullable', 'exists:branches,id'],
        ]);

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => UserRoles::from($data['role']),
            'branch_id' => $data['branch_id'] ?? null,
        ]);

        //Audit Log: Role Change (if role actually changed)
        if ($user->wasChanged('role')) {
            AuditLog::create([
                'actor_id' => auth()->id(),
                'action' => 'CHANGE_ROLE',
                'entity_type' => 'User',
                'entity_id' => $user->id,
                'description' => "Changed role of {$user->name} from {$oldRole} to {$user->role->label()}.",
                'ip_address' => request()->ip(),
            ]);
        }

        //If admin demoted THEMSELVES, force logout
        if ($user->id === auth()->id() && $user->wasChanged('role')) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')
                ->with('status', 'Your role has been changed. Please log in again.');
        }

        return redirect()->route('admin.users.index')
            ->with('status', "{$user->name}'s details have been updated.");
    }

    public function suspend(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot suspend your own account.']);
        }

        //Super Admin cannot be suspended
        if ($user->id === self::SUPER_ADMIN_ID) {
            return back()->withErrors(['user' => 'The Super Admin cannot be suspended.']);
        }

        if ($user->role === UserRoles::ADMIN) {
            return back()->withErrors(['user' => 'You cannot suspend another admin.']);
        }

        $user->update([
        'status' => 'suspended',
        'admin_request_type' => null,
        'admin_request_note' => null,
        'admin_request_status' => null,
        ]);

        //Audit Log: Suspend
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'SUSPEND_USER',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Suspended user {$user->name} ({$user->email}).",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', "{$user->name} has been suspended.");
    }

    public function reactivate(User $user)
    {
        $user->update(['status' => 'active']);

        //Audit Log: Reactivate
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'REACTIVATE_USER',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Reactivated user {$user->name}.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', "{$user->name} has been reactivated.");
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['user' => 'You cannot delete your own account.']);
        }

        //Super Admin cannot be deleted
        if ($user->id === self::SUPER_ADMIN_ID) {
            return back()->withErrors(['user' => 'The Super Admin cannot be deleted.']);
        }

        if ($user->role === UserRoles::ADMIN) {
            return back()->withErrors(['user' => 'You cannot delete an admin account.']);
        }

        $name = $user->name;
        
        //Audit Log: Delete (Log before deleting the user)
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'DELETE_USER',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Deleted user {$name} ({$user->email}).",
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return back()->with('status', "{$name} has been permanently deleted.");
    }

    public function approveRequest(User $user)
    {
        if ($user->admin_request_status !== 'pending') {
            return back()->withErrors(['request' => 'This request is no longer pending.']);
        }

        $actionType = $user->admin_request_type;

        if ($actionType === 'password_change') {
            $user->update([
                'password' => $user->admin_request_note,
                'admin_request_type' => null,
                'admin_request_note' => null,
                'admin_request_status' => null,
            ]);
        }

        if ($actionType === 'email_change') {
            $user->update([
                'email' => $user->admin_request_note,
                'email_verified_at' => now(),
                'admin_request_type' => null,
                'admin_request_note' => null,
                'admin_request_status' => null,
            ]);
        }

        //Audit Log: Approve Request
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'APPROVE_REQUEST',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Approved " . str_replace('_', ' ', $actionType) . " for {$user->name}.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', "Request for {$user->name} approved.");
    }

    public function rejectRequest(User $user)
    {
        $actionType = $user->admin_request_type;

        $user->update([
            'admin_request_type' => null,
            'admin_request_note' => null,
            'admin_request_status' => null,
        ]);

        //Audit Log: Reject Request
        AuditLog::create([
            'actor_id' => auth()->id(),
            'action' => 'REJECT_REQUEST',
            'entity_type' => 'User',
            'entity_id' => $user->id,
            'description' => "Rejected " . str_replace('_', ' ', $actionType) . " for {$user->name}.",
            'ip_address' => request()->ip(),
        ]);

        return back()->with('status', "Request for {$user->name} rejected.");
    }
}