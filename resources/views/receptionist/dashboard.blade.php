@extends('receptionist.layouts.master')

@section('title', 'Receptionist Dashboard')

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
        <div class="eyebrow">Front Desk / Overview</div>

        <h1 class="page-title">Dashboard</h1>

        <p class="page-description">
            Manage inquiries, appointments, viewings, and client directory requests.
        </p>
    </div>

    {{-- Primary statistics --}}
    <section class="stats-grid" aria-label="Front desk overview">
        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">New Inquiries</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">contact_support</span>
                </span>
            </div>

            <div class="metric-value">42</div>

            <div class="metric-trend">
                <span class="status status-success">8 today</span>
            </div>

            <div class="metric-footer">
                <span>Pending response</span>
                <strong>6</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Appointments</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">calendar_today</span>
                </span>
            </div>

            <div class="metric-value">18</div>

            <div class="metric-trend">
                <span class="status status-success">
                    <span class="status-dot"></span>
                    5 scheduled today
                </span>
            </div>

            <div class="metric-footer">
                <span>Confirmed</span>
                <strong>14</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Viewings</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">visibility</span>
                </span>
            </div>

            <div class="metric-value">24</div>

            <div class="metric-trend">
                <span class="status status-success">+2 this week</span>
            </div>

            <div class="metric-footer">
                <span>Completed</span>
                <strong>19</strong>
            </div>
        </article>

        <article class="card metric-card">
            <div class="metric-heading">
                <span class="metric-label">Directory Contacts</span>
                <span class="metric-icon">
                    <span class="material-symbols-outlined">group</span>
                </span>
            </div>

            <div class="metric-value">126</div>

            <div class="metric-trend">
                <span class="status status-success">+5 this month</span>
            </div>

            <div class="metric-footer">
                <span>Clients & owners</span>
                <strong>98%</strong>
            </div>
        </article>
    </section>
@endsection