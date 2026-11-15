@extends('admin.layouts.master')

@section('title', 'Properties')

@push('styles')
<style>
    /* ---------- Stats cards ---------- */
    .stats-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:20px; }
    .stat-card  { padding:16px 18px; border:1px solid var(--border); border-radius:var(--radius); background:var(--surface); box-shadow:var(--shadow); }
    .stat-label { color:var(--text-muted); font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
    .stat-value { margin-top:4px; color:var(--text-primary); font-family:'DM Serif Display',serif; font-size:26px; line-height:1.1; }

    /* ---------- Filter bar (search + dropdowns) ---------- */
    .filter-bar { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; padding:14px 16px; border:1px solid var(--border); border-radius:var(--radius); background:var(--surface); box-shadow:var(--shadow); }
    .filter-bar input, .filter-bar select { height:38px; padding:0 12px; border:1px solid var(--border); border-radius:8px; background:var(--surface-input); color:var(--text-body); font-size:12px; }
    .filter-bar input[type="search"] { flex:1; min-width:200px; }

    /* ---------- Data table ---------- */
    .table-wrap { overflow-x:auto; border:1px solid var(--border); border-radius:var(--radius); background:var(--surface); box-shadow:var(--shadow); }
    table.data-table { width:100%; border-collapse:collapse; font-size:13px; }
    table.data-table th { padding:12px 16px; background:var(--surface-soft); color:var(--text-muted); font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; text-align:left; white-space:nowrap; border-bottom:1px solid var(--border); }
    table.data-table td { padding:14px 16px; border-bottom:1px solid var(--border); color:var(--text-body); vertical-align:middle; }
    table.data-table tr:last-child td { border-bottom:0; }
    table.data-table tr:hover td { background:var(--surface-soft); }

    /* ---------- Cell typography ---------- */
    .prop-title { color:var(--text-primary); font-weight:700; }
    .prop-sub   { color:var(--text-muted); font-size:11px; margin-top:2px; }
    .prop-rent  { color:var(--text-primary); font-weight:800; white-space:nowrap; }

    /* ---------- Small action buttons ---------- */
    .btn-sm { min-height:30px; padding:5px 10px; font-size:11px; border-radius:6px; }

    /* ---------- Empty state ---------- */
    .empty-state { padding:48px 20px; text-align:center; color:var(--text-muted); }
    .empty-state .material-symbols-outlined { font-size:42px; opacity:.5; display:block; margin-bottom:8px; }
</style>
@endpush

@section('content')
    {{-- ---------- Page header ---------- --}}
    <div class="page-heading">
        <span class="eyebrow">Management</span>
        <h1 class="page-title">Properties</h1>
        <p class="page-description">Review property submissions and manage the operational inventory.</p>
    </div>

    {{-- ---------- Flash messages (success from approve/reject/delete, error if no pending reg found) ---------- --}}
    @if (session('success'))
        <div class="card" style="margin-bottom:18px; border-left:4px solid var(--success); padding:12px 18px;">
            <span style="color:var(--success); font-weight:700;">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="card" style="margin-bottom:18px; border-left:4px solid var(--danger); padding:12px 18px;">
            <span style="color:var(--danger); font-weight:700;">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ---------- Overview stats (total / pending / available / rented / unavailable) ---------- --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total listings</div>
            <div class="stat-value">{{ $stats['total'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Pending</div>
            <div class="stat-value" style="color:var(--warning);">{{ $stats['pending'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Available</div>
            <div class="stat-value" style="color:var(--success);">{{ $stats['available'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rented</div>
            <div class="stat-value" style="color:var(--text-primary);">{{ $stats['rented'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Unavailable</div>
            <div class="stat-value" style="color:var(--warning);">{{ $stats['unavailable'] }}</div>
        </div>
    </div>

    {{-- ---------- Filters: search term, status, branch ---------- --}}
    <form method="GET" action="{{ route('admin.properties.index') }}" class="filter-bar">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Search title, address, location…">

        {{-- Filter by operational status (pending = awaiting approval) --}}
        <select name="status">
            <option value="">All statuses</option>
            <option value="pending"     @selected(request('status') === 'pending')>Pending</option>
            <option value="available"   @selected(request('status') === 'available')>Available</option>
            <option value="rented"      @selected(request('status') === 'rented')>Rented</option>
            <option value="unavailable" @selected(request('status') === 'unavailable')>Unavailable</option>
        </select>

        {{-- Filter by branch (Malé / Hulhumalé / Villingili) --}}
        <select name="branch_id">
            <option value="">All branches</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected((int) request('branch_id') === $branch->id)>
                    {{ $branch->name }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary btn-sm">Filter</button>

        {{-- Show Reset only if any filter is active --}}
        @if (request()->anyFilled(['q','status','branch_id']))
            <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary btn-sm">Reset</a>
        @endif
    </form>

    {{-- ---------- Properties table ---------- --}}
    <div class="table-wrap">
        @if ($properties->isEmpty())
            {{-- Empty state: no rows match current filter --}}
            <div class="empty-state">
                <span class="material-symbols-outlined">apartment</span>
                <p>No properties match your filters.</p>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Property</th>
                        <th>Owner</th>
                        <th>Branch</th>
                        <th>Rent</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($properties as $property)
                        <tr>
                            {{-- Title + short address/location line --}}
                            <td>
                                <div class="prop-title">{{ $property->title }}</div>
                                <div class="prop-sub">{{ $property->address }} · {{ $property->location }}</div>
                            </td>

                            {{-- Owner name via owner → user → name chain --}}
                            <td>{{ $property->owner?->user?->name ?? '—' }}</td>

                            {{-- Branch name or "Unassigned" if null --}}
                            <td>{{ $property->branch?->name ?? 'Unassigned' }}</td>

                            {{-- Monthly rent formatted as MVR --}}
                            <td class="prop-rent">MVR {{ number_format((float) $property->monthly_rent, 2) }}</td>

                            {{-- Property type (apartment/flat/studio/house/room) --}}
                            <td style="text-transform:capitalize;">{{ $property->property_type }}</td>

                            {{-- Status pill: color depends on operational status --}}
                            <td>
                                @php
                                    $statusClass = match ($property->status) {
                                        'available'   => 'status-success',
                                        'rented'      => 'status-neutral',
                                        'unavailable' => 'status-warning',
                                        'pending'     => 'status-warning',
                                        default       => 'status-neutral',
                                    };
                                @endphp
                                <span class="status {{ $statusClass }}">
                                    <span class="status-dot"></span>{{ $property->status }}
                                </span>
                            </td>

                            {{-- View button → detail page (Approve / Reject / Delete live there) --}}
                            <td>
                                <a href="{{ route('admin.properties.show', $property) }}"
                                   class="btn btn-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- ---------- Pagination footer  ---------- --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 16px; font-size: 12px;">
        
        {{-- Info text --}}
        <div style="color: #a0aec0;">
            @if ($properties->total() > 0)
                <span style="color: #a0aec0;">Showing {{ $properties->firstItem() }}–{{ $properties->lastItem() }} of {{ $properties->total() }}</span>
            @else
                <span style="color: #a0aec0;">Showing 0 of 0</span>
            @endif
        </div>

        {{-- Nav buttons --}}
        <div style="display: flex; gap: 0.25rem; align-items: center;">
            @if ($properties->onFirstPage())
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">« Prev</span>
            @else
                <a href="{{ $properties->previousPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #ffffff; text-decoration: none; display: inline-block;">« Prev</a>
            @endif

            <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #111827;">{{ $properties->currentPage() }}</span>

            @if ($properties->hasMorePages())
                <a href="{{ $properties->nextPageUrl() }}" style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #111827; color: #ffffff; text-decoration: none; display: inline-block;">Next »</a>
            @else
                <span style="padding: 0.4rem 0.75rem; border-radius: 0.375rem; background: #f1f4f8; color: #ccc;">Next »</span>
            @endif
        </div>
    </div>
@endsection