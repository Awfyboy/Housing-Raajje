@extends('branch-manager.layouts.master')

@section('title', 'Branch Manager Dashboard')

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
        <div class="eyebrow">Branch Management / Overview</div>

        <h1 class="page-title">Dashboard</h1>

        <p class="page-description">
            Oversee branch operations, staff performance, applications, leases, and financials.
        </p>
    </div>

    {{-- Primary statistics --}}
    <section class="stats-grid" aria-label="Branch overview">
        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Total Staff</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">group</span>
                </span>
            </div>

            <div class="metric-value">8</div>

            <div class="metric-trend">
                <span class="status status-success">2 new this month</span>
            </div>

            <div class="metric-footer">
                <span>Active team members</span>
                <strong>100%</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Applications</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">assignment</span>
                </span>
            </div>

            <div class="metric-value">56</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    12 pending review
                </span>
            </div>

            <div class="metric-footer">
                <span>Approved this week</span>
                <strong>9</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Active Leases</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">history_edu</span>
                </span>
            </div>

            <div class="metric-value">142</div>

            <div class="metric-trend">
                <span class="status status-success">+4 this month</span>
            </div>

            <div class="metric-footer">
                <span>Expiring soon</span>
                <strong>6</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Payments & Finance</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">payments</span>
                </span>
            </div>

            <div class="metric-value">MVR 2.4M</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    Collected this month
                </span>
            </div>

            <div class="metric-footer">
                <span>Outstanding</span>
                <strong>MVR 180K</strong>
            </div>
        </article>
    </section>
@endsection