@extends('admin.layouts.master')

@section('title', 'Admin Dashboard')

@section('header-actions')
    <button type="button" class="btn btn-secondary" id="exportReport">
        <span class="material-symbols-outlined">file_download</span>
        <span class="desktop-label">Export Report</span>
    </button>

    <button type="button" class="btn btn-primary" id="newEntryButton">
        <span class="material-symbols-outlined">add</span>
        New Entry
    </button>
@endsection

@section('content')
    <div class="page-heading">
        <div class="eyebrow">Administration / Overview</div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-description">
            Monitor users, property activity, and system operations across all branches.
        </p>
    </div>

    {{--Pending Requests Alert --}}
    @if($pendingRequestsCount > 0)
        <div class="card mb-4" style="border-color: #ffda6a; background: #fffdf5; padding: 1rem 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <strong style="color: #b45309;">{{ $pendingRequestsCount }} pending request(s)</strong>
                    <p style="margin: 0; font-size: 12px; color: #a0aec0;">Staff members are waiting for approval.</p>
                </div>
                <a href="{{ route('admin.users.requests') }}" class="btn btn-primary" style="padding: 0.5rem 1rem; min-height: 38px; font-size: 12px;">
                    Review Requests
                </a>
            </div>
        </div>
    @endif

    {{-- Primary statistics (Real Data) --}}
    <section class="stats-grid" aria-label="System overview">
        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Total Users</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">group</span>
                </span>
            </div>
            <div class="metric-value">{{ $totalUsers }}</div>
            <div class="metric-trend">
                <span class="status status-success">Registered accounts</span>
            </div>
            <div class="metric-footer">
                <span>Includes all roles</span>
                <strong>System wide</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Properties</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">apartment</span>
                </span>
            </div>
            <div class="metric-value">{{ $totalProperties }}</div>
            <div class="metric-trend">
                <span class="status status-neutral">
                    <span class="status-dot"></span>
                    Total registered
                </span>
            </div>
            <div class="metric-footer">
                <span>Across all branches</span>
                <strong>{{ $totalBranches }} branches</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Staff</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">badge</span>
                </span>
            </div>
            <div class="metric-value">{{ $totalStaff }}</div>
            <div class="metric-trend">
                <span class="status status-neutral">{{ $totalBranches }} branches</span>
            </div>
            <div class="metric-footer">
                <span>Property Specialists</span>
                <strong>Active</strong>
            </div>
        </article>
    </section>

    {{-- Client and owner summaries (Real Data) --}}
    <section class="registry-grid" aria-label="Client and owner registry">
        <article class="card registry-card">
            <div class="registry-heading">
                <div>
                    <div class="metric-label">Clients</div>
                    <div class="registry-description">Registered tenants</div>
                </div>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">person</span>
                </span>
            </div>
            <div class="registry-value">{{ $totalClients }}</div>
            <div class="registry-footer">
                <span>Client portal</span>
                <strong>Active accounts</strong>
            </div>
        </article>

        <article class="card registry-card">
            <div class="registry-heading">
                <div>
                    <div class="metric-label">Property Owners</div>
                    <div class="registry-description">Registered landlords</div>
                </div>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">key</span>
                </span>
            </div>
            <div class="registry-value">{{ $totalOwners }}</div>
            <div class="registry-footer">
                <span>Branch coverage</span>
                <strong>All branches</strong>
            </div>
        </article>
    </section>

    {{-- Recent activity (Real Data) --}}
    <section class="card activity-card">
        <div class="card-heading activity-heading">
            <div>
                <h2 class="card-title">Recent Activity</h2>
                <p class="card-subtitle">Recent administrative and operational events</p>
            </div>
            <a href="{{ route('admin.audit-logs.index') }}" class="text-link">
                View all
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        <div class="activity-list">
            @forelse ($recentActivity as $activity)
                <article class="activity-item">
                    <div class="activity-icon activity-icon-{{ str_contains($activity->action, 'DELETE') ? 'warning' : (str_contains($activity->action, 'LOGIN') ? 'success' : 'neutral') }}">
                        <span class="material-symbols-outlined">
                            {{ str_contains($activity->action, 'USER') ? 'person_add' : (str_contains($activity->action, 'LOGIN') ? 'login' : 'info') }}
                        </span>
                    </div>

                    <div class="activity-details">
                        <div class="activity-title">
                            <strong>{{ str_replace('_', ' ', $activity->action) }}</strong>
                            <span class="activity-separator">·</span>
                            <span>{{ $activity->actor?->name ?? 'System' }}</span>
                        </div>
                        <p>{{ $activity->description }}</p>
                    </div>

                    <time class="activity-time">
                        {{ $activity->created_at->diffForHumans() }}
                    </time>
                </article>
            @empty
                <div style="padding: 2rem; text-align: center; color: var(--text-muted);">
                    No activity yet. Actions will appear here as they happen.
                </div>
            @endforelse
        </div>

        <div class="activity-footer">
            <span>Showing the latest 5 events</span>
            <span class="live-indicator">
                <span class="status-dot"></span>
                Live from Audit Log
            </span>
        </div>
    </section>

    <style>
        /* ... (Keep all your existing CSS from your pasted file here) ... */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 20px;
        }

        .metric-card {
            display: flex;
            flex-direction: column;
            min-height: 205px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .metric-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(71, 47, 36, 0.08);
        }

        .metric-heading,
        .registry-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .metric-label {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.10em;
            text-transform: uppercase;
        }

        .metric-icon {
            display: inline-grid;
            place-items: center;
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--surface-soft);
            color: var(--primary-hover);
        }

        .metric-value,
        .registry-value {
            margin-top: 16px;
            color: var(--text-primary);
            font-family: 'Manrope', sans-serif;
            font-size: 36px;
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -0.04em;
        }

        .metric-trend {
            margin-top: 10px;
        }

        .metric-footer,
        .registry-footer {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            margin-top: auto;
            padding-top: 15px;
            color: var(--text-muted);
            font-size: 11px;
        }

        .metric-footer {
            margin-top: 18px;
            border-top: 1px solid var(--border);
        }

        .metric-footer strong,
        .registry-footer strong {
            color: var(--text-primary);
            text-align: right;
        }

        .registry-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .registry-card {
            background: var(--surface);
        }

        .registry-description {
            margin-top: 4px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .registry-value {
            margin-top: 12px;
        }

        .registry-footer {
            margin-top: 18px;
            border-top: 1px solid var(--border);
        }

        .text-warning {
            color: var(--warning) !important;
        }

        .activity-card {
            padding: 0;
            overflow: hidden;
        }

        .activity-heading {
            margin: 0;
            padding: 22px;
            border-bottom: 1px solid var(--border);
        }

        .text-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: var(--primary-hover);
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .text-link:hover {
            color: var(--primary);
        }

        .text-link .material-symbols-outlined {
            font-size: 17px;
        }

        .activity-item {
            display: grid;
            grid-template-columns: 40px minmax(0, 1fr) auto;
            align-items: center;
            gap: 14px;
            padding: 17px 16px;
            border-bottom: 1px solid var(--border);
        }

        .activity-item:hover {
            background: var(--surface-soft);
        }

        .activity-icon {
            display: grid;
            place-items: center;
            flex-shrink: 0;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--surface-soft);
            color: var(--primary-hover);
        }

        .activity-icon-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .activity-icon-warning {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .activity-icon-neutral {
            background: #eee5da;
            color: var(--text-muted);
        }

        .activity-details {
            min-width: 0;
        }

        .activity-title {
            display: flex;
            flex-wrap: wrap;
            align-items: baseline;
            gap: 5px 9px;
            overflow-wrap: anywhere;
        }

        .activity-title strong {
            color: var(--text-primary);
            font-size: 13px;
        }

        .activity-separator {
            color: var(--text-light);
        }

        .activity-details p {
            margin: 3px 0 0;
            color: var(--text-muted);
            font-size: 11px;
        }

        .activity-time {
            white-space: nowrap;
            align-self: center;
        }

        .activity-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 22px;
            background: var(--surface-soft);
            color: var(--text-muted);
            font-size: 10px;
        }

        .live-indicator {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-weight: 800;
        }

        .live-indicator .status-dot {
            background: var(--success);
        }

        @media (max-width: 1000px) {
            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .metric-card:last-child {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 600px) {
            .stats-grid,
            .registry-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .metric-card:last-child {
                grid-column: auto;
            }

            .metric-card {
                min-height: 185px;
            }

            .activity-heading {
                align-items: flex-start;
                padding: 18px;
            }

            .activity-item {
                grid-template-columns: 34px minmax(0, 1fr);
                gap: 10px;
            }

            .activity-icon {
                width: 34px;
                height: 34px;
            }

            .activity-icon .material-symbols-outlined {
                font-size: 17px;
            }

            .activity-time {
                grid-column: 2;
                justify-self: start;
            }

            .activity-footer {
                align-items: flex-start;
                flex-direction: column;
                padding: 13px 16px;
            }

            .desktop-label {
                display: none;
            }
        }
    </style>

    @push('scripts')
        <script>
            document.getElementById('newEntryButton')?.addEventListener('click', () => {
                window.location.href = "{{ url('/admin/users/create') }}";
            });

            document.getElementById('exportReport')?.addEventListener('click', () => {
                window.alert('Report export has not been connected yet.');
            });
        </script>
    @endpush
@endsection