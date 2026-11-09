@extends('admin.layouts.master')

@section('title', 'User Management')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <div class="eyebrow">Administration</div>
        <h1 class="page-title">User Management</h1>
        <p class="page-description">Manage user accounts and approve profile requests.</p>
    </div>
    <div style="display: flex; gap: 0.5rem; align-items: center;">
        {{-- Requests Button with Badge (no icon) --}}
        <a href="{{ route('admin.users.requests') }}" class="btn btn-secondary" 
           style="border-color: #ffda6a; background: #fffdf5; color: #b45309; display: inline-flex; align-items: center; gap: 0.5rem; min-height: 42px;">
            Requests
            @if($pendingRequestsCount > 0)
                <span style="background: #b45309; color: #fff; padding: 0.1rem 0.5rem; border-radius: 999px; font-size: 10px; font-weight: 800; min-width: 20px; text-align: center;">
                    {{ $pendingRequestsCount }}
                </span>
            @endif
        </a>

        {{-- Add User Button --}}
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <span class="material-symbols-outlined">add</span> Add User
        </a>
    </div>
</div>

@if (session('status'))
    <div class="alert small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #e5f5ec; color: #33825a; border: 1px solid #46a773;">
        ✅ {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="alert small mb-4" style="padding: 1rem; border-radius: 0.5rem; background: #ffdad6; color: #ba1a1a; border: 1px solid #ba1a1a;">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- All Users Table --}}
<div class="card">
    <div class="card-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h2 class="card-title">All Users</h2>
        
        {{-- Search & Filter Form --}}
        <form method="GET" action="{{ route('admin.users.index') }}" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name or email..." 
                   class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd; min-width: 200px;">
            
            <select name="role" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">All Roles</option>
                @foreach (\App\Enums\UserRoles::cases() as $role)
                    <option value="{{ $role->value }}" {{ request('role') === $role->value ? 'selected' : '' }}>
                        {{ $role->label() }}
                    </option>
                @endforeach
            </select>

            {{-- Branch Filter --}}
            <select name="branch_id" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">All Branches</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                        {{ $branch->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; min-height: 38px; font-size: 12px;">Filter</button>
            
            @if(request('search') || request('role') || request('branch_id'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; min-height: 38px; font-size: 12px;">Clear</a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="border-bottom: 1px solid #dfe6ef;">
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Name</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Email</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Role</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Branch</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Status</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">MFA</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Actions</th> 
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr style="border-bottom: 1px solid #f1f4f8;">
                        <td style="padding: 0.6rem 0.75rem; font-weight: 600; color: #151e32;">{{ $user->name }}</td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">{{ $user->email }}</td>
                        
                        {{-- ROLE COLUMN WITH SUPER ADMIN LOGIC --}}
                        <td style="padding: 0.6rem 0.75rem;">
                            @if ($user->id === 1)
                                {{-- Super Admin Styling (Gold/Purple) --}}
                                <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.02em; background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;">
                                    Super Admin
                                </span>
                            @else
                                {{-- Normal Role Styling --}}
                                <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; background: #eee5da; color: #4a5568;">
                                    {{ $user->role->label() }}
                                </span>
                            @endif
                        </td>

                        {{-- BRANCH COLUMN --}}
                        <td style="padding: 0.6rem 0.75rem;">
                            @if ($user->branch)
                                <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; background: #e7f1ff; color: #0a58ca;">
                                    {{ $user->branch->name }}
                                </span>
                            @else
                                <span style="color: #a0aec0; font-size: 11px;">—</span>
                            @endif
                        </td>

                        <td style="padding: 0.6rem 0.75rem;">
                            @if ($user->status === 'active')
                                <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; background: #e5f5ec; color: #33825a;">
                                    Active
                                </span>
                            @else
                                <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em; background: #ffdad6; color: #ba1a1a;">
                                    {{ ucfirst($user->status) }}
                                </span>
                            @endif
                        </td>
                        <td style="padding: 0.6rem 0.75rem;">
                            @if ($user->mfa_enabled)
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #33825a;">check_circle</span>
                            @else
                                <span class="material-symbols-outlined" style="font-size: 18px; color: #a0aec0;">cancel</span>
                            @endif
                        </td>

                        {{-- Actions Column --}}
                        <td style="padding: 0.6rem 0.75rem;">
                            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                <a href="{{ route('admin.users.edit', $user) }}" 
                                   class="btn btn-secondary" 
                                   style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px;">
                                    Edit
                                </a>

                                @if ($user->status === 'active')
                                    <form method="POST" action="{{ route('admin.users.suspend', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary" 
                                                style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px; color: #b45309;"
                                                onclick="return confirm('Suspend {{ $user->name }}?')">
                                            Suspend
                                        </button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.users.reactivate', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-secondary" 
                                                style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px; color: #33825a;">
                                            Reactivate
                                        </button>
                                    </form>
                                @endif

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-secondary" 
                                            style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px; color: #ba1a1a;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="padding: 2rem; text-align: center; color: #a0aec0;">
                            No users found matching your search.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($users->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center" style="font-size: 12px;">
            <div style="color: #a0aec0;">
                Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }}
            </div>
            <div style="display: flex; gap: 0.25rem;">
                @if ($users->onFirstPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">« Prev</span>
                @else
                    <a href="{{ $users->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">« Prev</a>
                @endif
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8;">{{ $users->currentPage() }}</span>
                @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">Next »</a>
                @else
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">Next »</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection