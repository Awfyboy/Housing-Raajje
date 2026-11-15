@extends('admin.layouts.master')

@section('title', 'Pending Requests')

@section('content')
<div class="page-heading d-flex justify-content-between align-items-center">
    <div>
        <div class="eyebrow">Administration</div>
        <h1 class="page-title">Pending Requests</h1>
        <p class="page-description">Approve or reject staff profile changes.</p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <span class="material-symbols-outlined">arrow_back</span> Back to Users
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
    <div class="card-heading">
        <h2 class="card-title">Pending Requests</h2>
        <span class="status status-warning">{{ $pendingRequests->count() }} pending</span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="border-bottom: 1px solid #dfe6ef;">
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; color: #71829e; font-weight: 700;">User</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; color: #71829e; font-weight: 700;">Request Type</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; color: #71829e; font-weight: 700;">Details</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; color: #71829e; font-weight: 700;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pendingRequests as $user)
                    <tr style="border-bottom: 1px solid #f1f4f8;">
                        <td style="padding: 0.6rem 0.75rem;">
                            <span style="font-weight: 600; color: #151e32;">{{ $user->name }}</span>
                            <br>
                            <small style="color: #a0aec0;">{{ $user->role->label() }} · {{ $user->email }}</small>
                        </td>
                        <td style="padding: 0.6rem 0.75rem;">
                            <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; background: #fff6df; color: #b45309;">
                                {{ str_replace('_', ' ', $user->admin_request_type) }}
                            </span>
                        </td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">
                            @if ($user->admin_request_type === 'email_change')
                                <strong>{{ $user->admin_request_note }}</strong>
                            @else
                                <em>Password reset request</em>
                            @endif
                        </td>
                        <td style="padding: 0.6rem 0.75rem;">
                            <div style="display: flex; gap: 0.4rem;">
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px;">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary" style="padding: 0.3rem 0.6rem; min-height: 28px; font-size: 10px; color: #ba1a1a;">Reject</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: #a0aec0;">
                            <span class="material-symbols-outlined" style="font-size: 48px; display: block; margin-bottom: 1rem;">check_circle</span>
                            No pending requests. All caught up!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection