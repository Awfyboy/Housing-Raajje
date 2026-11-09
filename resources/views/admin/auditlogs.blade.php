@extends('admin.layouts.master')

@section('title', 'Audit Logs')

@section('content')
<div class="page-heading">
    <div class="eyebrow">System</div>
    <h1 class="page-title">Audit Logs</h1>
    <p class="page-description">A complete history of administrative and account changes.</p>
</div>

{{-- Filters --}}
<div class="card mb-4">
    <form method="GET" action="{{ route('admin.audit-logs.index') }}" style="display: flex; flex-wrap: wrap; gap: 0.75rem; align-items: end;">
        <div style="flex: 1; min-width: 180px;">
            <label class="form-label fw-bold small">Search</label>
            <input type="text" name="search" value="{{ $search }}" placeholder="Name or description..." class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div style="min-width: 140px;">
            <label class="form-label fw-bold small">Role</label>
            <select name="role" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">All Roles</option>
                <option value="system" {{ $roleFilter === 'system' ? 'selected' : '' }}>System</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->value }}" {{ $roleFilter === $role->value ? 'selected' : '' }}>{{ $role->label() }}</option>
                @endforeach
            </select>
        </div>

        <div style="min-width: 140px;">
            <label class="form-label fw-bold small">Action</label>
            <select name="action" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
                <option value="">All Actions</option>
                @foreach ($actions as $action)
                    <option value="{{ $action }}" {{ $actionFilter == $action ? 'selected' : '' }}>{{ str_replace('_', ' ', $action) }}</option>
                @endforeach
            </select>
        </div>

        <div style="min-width: 130px;">
            <label class="form-label fw-bold small">From</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div style="min-width: 130px;">
            <label class="form-label fw-bold small">To</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control" style="padding: 0.5rem 0.75rem; border-radius: 0.5rem; border: 1px solid #ddd;">
        </div>

        <div style="display: flex; gap: 0.5rem;">
            <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1rem; min-height: 38px;">Filter</button>
            @if($search || $roleFilter || $actionFilter || $dateFrom || $dateTo)
                <a href="{{ route('admin.audit-logs.index') }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; min-height: 38px;">Clear</a>
            @endif
        </div>
    </form>
</div>

{{-- Logs Table --}}
<div class="card">
    <div class="card-heading">
        <h2 class="card-title">Activity History</h2>
        <span class="status status-neutral">{{ $logs->total() }} entries</span>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <thead>
                <tr style="border-bottom: 1px solid #dfe6ef;">
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Date</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">User</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Action</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">Description</th>
                    <th style="padding: 0.5rem 0.75rem; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: 0.04em; color: #71829e; font-weight: 700;">IP</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr style="border-bottom: 1px solid #f1f4f8;">
                        <td style="padding: 0.6rem 0.75rem; white-space: nowrap; color: #34445e;">
                            {{ $log->created_at->format('M d, Y') }}
                            <br>
                            <small style="color: #a0aec0;">{{ $log->created_at->format('h:i A') }}</small>
                        </td>
                        
                        {{--USER COLUMN WITH SUPER ADMIN CHECK --}}
                        <td style="padding: 0.6rem 0.75rem;">
                            @if ($log->actor)
                                <span style="font-weight: 600; color: #151e32;">{{ $log->actor->name }}</span>
                                <br>
                                
                                @if ($log->actor->id === 1)
                                    <small style="font-size: 9px; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 800; text-transform: uppercase; background: #f3e8ff; color: #6b21a8; border: 1px solid #d8b4fe;">
                                        Super Admin
                                    </small>
                                @else
                                    <small style="font-size: 9px; padding: 0.1rem 0.4rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; background: #eee5da; color: #4a5568;">
                                        {{ $log->actor->role->label() }}
                                    </small>
                                @endif
                            @else
                                <em style="color: #a0aec0;">System</em>
                            @endif
                        </td>

                        <td style="padding: 0.6rem 0.75rem;">
                            <span style="font-size: 10px; padding: 0.2rem 0.5rem; border-radius: 4px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.02em;
                                background: {{ str_starts_with($log->action, 'DELETE') ? '#ffdad6' : (str_starts_with($log->action, 'SUSPEND') || str_starts_with($log->action, 'FAILED') ? '#fff6df' : '#e5f5ec') }};
                                color: {{ str_starts_with($log->action, 'DELETE') ? '#ba1a1a' : (str_starts_with($log->action, 'SUSPEND') || str_starts_with($log->action, 'FAILED') ? '#b45309' : '#33825a') }};">
                                {{ str_replace('_', ' ', $log->action) }}
                            </span>
                        </td>
                        <td style="padding: 0.6rem 0.75rem; color: #4a5568;">{{ $log->description }}</td>
                        <td style="padding: 0.6rem 0.75rem; font-family: monospace; font-size: 11px; color: #a0aec0;">{{ $log->ip_address }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 2rem; text-align: center; color: #a0aec0;">
                            No audit log entries found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($logs->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center" style="font-size: 12px;">
            <div style="color: #a0aec0;">
                Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ $logs->total() }}
            </div>
            <div style="display: flex; gap: 0.25rem;">
                @if ($logs->onFirstPage())
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">« Prev</span>
                @else
                    <a href="{{ $logs->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">« Prev</a>
                @endif
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8;">{{ $logs->currentPage() }}</span>
                @if ($logs->hasMorePages())
                    <a href="{{ $logs->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #fff; text-decoration: none;">Next »</a>
                @else
                    <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">Next »</span>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection