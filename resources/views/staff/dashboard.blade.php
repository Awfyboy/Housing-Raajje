@extends('staff.layouts.master')

@section('title', 'Staff Dashboard')

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
        <div class="eyebrow">Staff / Overview</div>

        <h1 class="page-title">Dashboard</h1>

        <p class="page-description">
            Monitor your assigned properties, viewings, leases, and client interactions.
        </p>
    </div>

    {{-- Primary statistics --}}
    <section class="stats-grid" aria-label="Staff overview">
        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">My Properties</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">apartment</span>
                </span>
            </div>

            <div class="metric-value">12</div>

            <div class="metric-trend">
                <span class="status status-success">2 new this week</span>
            </div>

            <div class="metric-footer">
                <span>Active listings</span>
                <strong>100%</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">My Viewings</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">calendar_today</span>
                </span>
            </div>

            <div class="metric-value">24</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    3 scheduled today
                </span>
            </div>

            <div class="metric-footer">
                <span>Pending confirmations</span>
                <strong>4</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">My Clients</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">group</span>
                </span>
            </div>

            <div class="metric-value">38</div>

            <div class="metric-trend">
                <span class="status status-success">+3 this month</span>
            </div>

            <div class="metric-footer">
                <span>Active applications</span>
                <strong>7</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">My Leases</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">history_edu</span>
                </span>
            </div>

            <div class="metric-value">15</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    2 expiring soon
                </span>
            </div>

            <div class="metric-footer">
                <span>Active tenants</span>
                <strong>14</strong>
            </div>
        </article>
    </section>
@endsection