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

    {{-- Primary statistics --}}
    <section class="stats-grid" aria-label="System overview">
        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Total Users</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">group</span>
                </span>
            </div>

            <div class="metric-value">126</div>

            <div class="metric-trend">
                <span class="status status-success">+8 this month</span>
            </div>

            <div class="metric-footer">
                <span>Verified accounts</span>
                <strong>98.2%</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Properties</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">apartment</span>
                </span>
            </div>

            <div class="metric-value">348</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    14 newly active
                </span>
            </div>

            <div class="metric-footer">
                <span>Occupancy rate</span>
                <strong>91.4%</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Staff</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">badge</span>
                </span>
            </div>

            <div class="metric-value">19</div>

            <div class="metric-trend">
                <span class="status status-neutral">3 branches</span>
            </div>

            <div class="metric-footer">
                <span>On duty today</span>
                <strong>16 active</strong>
            </div>
        </article>
    </section>

    {{-- Client and owner summaries --}}
    <section class="registry-grid" aria-label="Client and owner registry">
        <article class="card registry-card">
            <div class="registry-heading">
                <div>
                    <div class="metric-label">Clients</div>
                    <div class="registry-description">
                        Registered tenants
                    </div>
                </div>

                <span class="metric-icon">
                    <span class="material-symbols-outlined">person</span>
                </span>
            </div>

            <div class="registry-value">87</div>

            <div class="registry-footer">
                <span>Lease renewals pending</span>
                <strong class="text-warning">5 due this week</strong>
            </div>
        </article>

        <article class="card registry-card">
            <div class="registry-heading">
                <div>
                    <div class="metric-label">Property Owners</div>
                    <div class="registry-description">
                        Registered landlords
                    </div>
                </div>

                <span class="metric-icon">
                    <span class="material-symbols-outlined">key</span>
                </span>
            </div>

            <div class="registry-value">42</div>

            <div class="registry-footer">
                <span>Branch coverage</span>
                <strong>All 3 branches</strong>
            </div>
        </article>
    </section>

    {{-- Recent activity --}}
    <section class="card activity-card">
        <div class="card-heading activity-heading">
            <div>
                <h2 class="card-title">Recent Activity</h2>
                <p class="card-subtitle">
                    Recent administrative and operational events
                </p>
            </div>

            <a href="{{ url('/admin/audit-logs') }}" class="text-link">
                View all
                <span class="material-symbols-outlined">arrow_forward</span>
            </a>
        </div>

        @php
            // Sample activity for the dashboard prototype.
            // Replace this array with database records when audit logging is implemented.
            $activities = [
                [
                    'icon' => 'person_add',
                    'type' => 'User created',
                    'subject' => 'Ahmed Ibrahim',
                    'detail' => 'Malé branch',
                    'time' => '10:32 AM',
                    'status' => 'success',
                ],
                [
                    'icon' => 'check_circle',
                    'type' => 'Property approved',
                    'subject' => '#PROP-1024 · Ocean View Phase 2',
                    'detail' => 'Approved by Administrator',
                    'time' => '10:12 AM',
                    'status' => 'success',
                ],
                [
                    'icon' => 'badge',
                    'type' => 'Staff account created',
                    'subject' => 'Hassan Naseem',
                    'detail' => 'Senior Officer',
                    'time' => '09:45 AM',
                    'status' => 'warning',
                ],
                [
                    'icon' => 'history_edu',
                    'type' => 'Lease created',
                    'subject' => '#LEASE-0456 · Palm Court Apt 4B',
                    'detail' => 'Hulhumalé branch',
                    'time' => '09:20 AM',
                    'status' => 'neutral',
                ],
                [
                    'icon' => 'payments',
                    'type' => 'Payment verified',
                    'subject' => '#PAY-8821 · MVR 18,000',
                    'detail' => 'Bank transfer',
                    'time' => '08:50 AM',
                    'status' => 'success',
                ],
            ];
        @endphp

        <div class="activity-list">
            @foreach ($activities as $activity)
                <article class="activity-item">
                    <div class="activity-icon activity-icon-{{ $activity['status'] }}">
                        <span class="material-symbols-outlined">
                            {{ $activity['icon'] }}
                        </span>
                    </div>

                    <div class="activity-details">
                        <div class="activity-title">
                            <strong>{{ $activity['type'] }}</strong>
                            <span class="activity-separator">·</span>
                            <span>{{ $activity['subject'] }}</span>
                        </div>

                        <p>{{ $activity['detail'] }}</p>
                    </div>

                    <time class="activity-time">
                        {{ $activity['time'] }}
                    </time>
                </article>
            @endforeach
        </div>

        <div class="activity-footer">
            <span>Showing the latest 5 sample events</span>

            <span class="live-indicator">
                <span class="status-dot"></span>
                Sample activity
            </span>
        </div>
    </section>

    <style>
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
                // Replace with a route or entry-selection modal when implemented.
                window.location.href = "{{ url('/admin/users/create') }}";
            });

            document.getElementById('exportReport')?.addEventListener('click', () => {
                // Connect to a server-generated CSV/PDF report endpoint.
                window.alert('Report export has not been connected yet.');
            });
        </script>
    @endpush
@endsection