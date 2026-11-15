@extends('admin.layouts.master')

@section('title', $property->title)

@push('styles')
<style>
    /* ---------- Detail definition list (grid of label/value pairs) ---------- */
    .detail-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:16px 24px; margin:0; }
    .detail-grid dt { font-size:11px; color:var(--text-muted); text-transform:uppercase; letter-spacing:.08em; font-weight:800; }
    .detail-grid dd { margin:4px 0 0; font-weight:700; }

    /* ---------- Registration history table ---------- */
    .history-table { width:100%; border-collapse:collapse; font-size:12px; }
    .history-table th,
    .history-table td { padding:8px 10px; text-align:left; border-bottom:1px solid var(--border); }
    .history-table th { font-size:10px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; color:var(--text-muted); background:var(--surface-soft); }
    .history-table tr:last-child td { border-bottom:0; }
</style>
@endpush

@section('content')
    {{-- ---------- Page header: title + address/location as description ---------- --}}
    <div class="page-heading">
        <span class="eyebrow">Properties</span>
        <h1 class="page-title">{{ $property->title }}</h1>
        <p class="page-description">{{ $property->address }} · {{ $property->location }}</p>
    </div>

    {{-- Back link to the properties list --}}
    <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary" style="margin-bottom:18px;">
        <span class="material-symbols-outlined" style="font-size:16px;">arrow_back</span>
        Back to properties
    </a>

    {{-- ---------- Flash messages (success / error from approve/reject/delete) ---------- --}}
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

    {{-- ---------- Main listing card ---------- --}}
    <div class="card">
        <div class="card-heading">
            <div>
                <div class="card-title">Listing details</div>
                <div class="card-subtitle">Created {{ $property->created_at?->format('d M Y') }}</div>
            </div>
            {{-- Status pill: pending / available / rented / unavailable --}}
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
        </div>

        {{-- Grid of key facts about the property --}}
        <dl class="detail-grid">
            {{-- Owner name resolved via owner → user → name chain --}}
            <div>
                <dt>Owner</dt>
                <dd>{{ $property->owner?->user?->name ?? '—' }}</dd>
            </div>
            {{-- Branch responsible for managing this property --}}
            <div>
                <dt>Branch</dt>
                <dd>{{ $property->branch?->name ?? 'Unassigned' }}</dd>
            </div>
            {{-- Monthly rent formatted as MVR --}}
            <div>
                <dt>Monthly rent</dt>
                <dd>MVR {{ number_format((float) $property->monthly_rent, 2) }}</dd>
            </div>
            {{-- Property type (apartment / flat / studio / house / room) --}}
            <div>
                <dt>Type</dt>
                <dd style="text-transform:capitalize;">{{ $property->property_type }}</dd>
            </div>
            {{-- Room counts --}}
            <div>
                <dt>Bedrooms</dt>
                <dd>{{ $property->bedrooms }}</dd>
            </div>
            <div>
                <dt>Bathrooms</dt>
                <dd>{{ $property->bathrooms ?? '—' }}</dd>
            </div>
        </dl>

        {{-- Free-text description, only shown if present --}}
        @if ($property->description)
            <hr style="margin:20px 0; border:0; border-top:1px solid var(--border);">
            <div class="card-title" style="margin-bottom:8px;">Description</div>
            <p style="margin:0; color:var(--text-body); line-height:1.7;">{{ $property->description }}</p>
        @endif
    </div>

    {{-- ---------- Registration history (one row per submission attempt) ---------- --}}
    @if ($property->registrations->isNotEmpty())
        <div class="card" style="margin-top:18px;">
            <div class="card-heading">
                <div class="card-title">Registration history</div>
            </div>
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Submitted</th>
                        <th>By</th>
                        <th>Status</th>
                        <th>Reviewed by</th>
                        <th>Reviewed at</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($property->registrations as $reg)
                        <tr>
                            {{-- When the owner first submitted this listing --}}
                            <td>{{ optional($reg->submitted_at)->format('d M Y') ?? '—' }}</td>
                            {{-- The user (owner) who submitted it --}}
                            <td>{{ $reg->submitter?->name ?? '—' }}</td>
                            {{-- pending / approved / rejected --}}
                            <td style="text-transform:capitalize;">{{ $reg->status }}</td>
                            {{-- Admin/staff user who reviewed it (null if still pending) --}}
                            <td>{{ $reg->reviewer?->name ?? '—' }}</td>
                            {{-- When the review happened (null if still pending) --}}
                            <td>{{ optional($reg->reviewed_at)->format('d M Y') ?? '—' }}</td>
                            {{-- Rejection reason if rejected --}}
                            <td style="color:var(--text-muted);">{{ $reg->rejection_reason ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- ---------- Pending approval actions ---------- --}}
    {{-- Find the pending registration (if any) for this property --}}
    @php
        $pending = $property->registrations->firstWhere('status', 'pending');
    @endphp

    @if ($pending)
        <div class="card" style="margin-top:18px; border-left:4px solid var(--warning);">
            <div class="card-title" style="margin-bottom:8px;">Pending registration review</div>
            <p style="margin:0 0 12px; color:var(--text-muted); font-size:12px;">
                Submitted {{ optional($pending->submitted_at)->diffForHumans() }}
                by {{ $pending->submitter?->name ?? '—' }}.
            </p>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                {{-- Approve: flips reg → approved and property → available --}}
                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit">Approve &amp; publish</button>
                </form>

                {{-- Reject: flips reg → rejected with a required reason --}}
                <form method="POST" action="{{ route('admin.properties.reject', $property) }}"
                      style="display:flex; gap:8px; align-items:center;">
                    @csrf
                    <input type="text" name="reason" placeholder="Reason for rejection…" required maxlength="500"
                           style="height:38px; padding:0 12px; border:1px solid var(--border); border-radius:8px; background:var(--surface-input); font-size:12px;">
                    <button class="btn btn-secondary" type="submit" style="color:var(--danger);">Reject</button>
                </form>
            </div>
        </div>
    @endif

    {{-- ---------- Danger zone: delete property (irreversible) ---------- --}}
    <div style="display:flex; gap:10px; margin-top:18px;">
        <form method="POST" action="{{ route('admin.properties.destroy', $property) }}"
              onsubmit="return confirm('Delete this property permanently?');" style="margin-left:auto;">
            @csrf @method('DELETE')
            <button class="btn btn-secondary" type="submit" style="color:var(--danger);">Delete property</button>
        </form>
    </div>
@endsection