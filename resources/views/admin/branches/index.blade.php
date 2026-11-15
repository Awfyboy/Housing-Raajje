@extends('admin.layouts.master')

@section('title', 'Branch Management')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <div class="eyebrow">Administration</div>
        <h1 class="page-title">Branch Management</h1>
        <p class="page-description">Manage the regional branches.</p>
    </div>
    <a href="{{ route('admin.branches.create') }}" class="btn btn-primary">
        <span class="material-symbols-outlined">add</span> Add Branch
    </a>
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

<div class="card">
    <div class="card-heading d-flex justify-content-between align-items-center flex-wrap gap-3">
        <h2 class="card-title">All Branches</h2>
        
        <form method="GET" action="{{ route('admin.branches.index') }}" style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search branch name..." 
                   class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd; min-width: 220px;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; min-height: 38px; font-size: 12px;">Search</button>
            @if(request('search'))
                <a href="{{ route('admin.branches.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; min-height: 38px; font-size: 12px;">Clear</a>
            @endif
        </form>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="border-bottom: 1px solid #dfe6ef;">
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Name</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Location</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Address</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Manager</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Staff</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Actions</th> 
                </tr>
            </thead>
            <tbody>
                @forelse ($branches as $branch)
                    <tr style="border-bottom: 1px solid #f1f4f8;">
                        <td style="padding: 0.6rem 0.75rem; font-weight: 600; color: #151e32;">{{ $branch->name }}</td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">
                            <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; background: #eee5da; color: #4a5568;">
                                {{ $branch->location->label() }}
                            </span>
                        </td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568; max-width: 250px;">
                            @if($branch->address)
                                <span style="font-size: 12px; color: #4a5568; line-height: 1.4; display: block;">
                                    {{ $branch->address }}
                                </span>
                            @else
                                <em style="color: #a0aec0; font-size: 11px;">No address</em>
                            @endif
                        </td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">
                            @if($branch->manager)
                                <span class="material-symbols-outlined" style="font-size: 14px; color: #33825a; vertical-align: middle;">person</span>
                                {{ $branch->manager->name }}
                            @else
                                <em style="color: #a0aec0;">Unassigned</em>
                            @endif
                        </td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">
                            {{ $branch->users()->count() }} users
                        </td>
                        <td style="padding: 0.6rem 0.75rem;">
                            <div style="display: flex; gap: 0.4rem; flex-wrap: wrap;">
                                <a href="{{ route('admin.branches.edit', $branch) }}" class="btn btn-secondary" 
                                   style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px;">
                                    Edit
                                </a>
                                
                                <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" 
                                      onsubmit="return confirm('Delete {{ $branch->name }}? This cannot be undone.')">
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
                        <td colspan="6" style="padding: 2rem; text-align: center; color: #a0aec0;">
                            No branches found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($branches->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center" style="font-size: 12px;">
            <div style="color: #a0aec0;">
                Showing {{ $branches->firstItem() }}–{{ $branches->lastItem() }} of {{ $branches->total() }}
            </div>
            <div style="display: flex; gap: 0.25rem;">
                @if ($branches->onFirstPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">« Prev</span>
                @else
                    <a href="{{ $branches->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">« Prev</a>
                @endif
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8;">{{ $branches->currentPage() }}</span>
                @if ($branches->hasMorePages())
                    <a href="{{ $branches->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">Next »</a>
                @else
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">Next »</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection