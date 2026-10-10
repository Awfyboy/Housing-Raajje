<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Admin Dashboard') | Housing 4 Raajje</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&family=Material+Symbols+Outlined" rel="stylesheet">

    <style>
        :root {
            --canvas: #f6f8fb;
            --surface: #ffffff;
            --surface-soft: #f4f7fb;
            --surface-input: #f1f4f8;

            --brand-dark: #281a15;
            --primary: #111827;
            --primary-hover: #273449;
            --accent: #d8b7a5;

            --text-primary: #151e32;
            --text-body: #34445e;
            --text-muted: #71829e;
            --text-light: #d4c0b5;

            --border: #dfe6ef;
            --border-dark: #493128;

            --success: #00875f;
            --success-bg: #e9fbf3;
            --warning: #b45309;
            --warning-bg: #fff6df;
            --danger: #ba1a1a;
            --danger-bg: #ffdad6;

            --sidebar-width: 256px;
            --radius: 12px;
            --shadow: 0 1px 3px rgba(20, 35, 60, 0.05);
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            background: var(--canvas);
            color: var(--text-body);
            font-family: 'Manrope', sans-serif;
            font-size: 14px;
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
        }

        button, input, select, textarea {
            font: inherit;
        }

        button, a {
            -webkit-tap-highlight-color: transparent;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button {
            cursor: pointer;
        }

        h1, h2, h3, h4 {
            margin: 0;
        }

        h1, h2, h3 {
            font-family: 'DM Serif Display', serif;
            font-weight: 400;
        }

        .material-symbols-outlined {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 20px;
            line-height: 1;
            display: inline-flex;
            white-space: nowrap;
            direction: ltr;
            font-feature-settings: 'liga';
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar */

        .admin-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 100;
            display: flex;
            flex-direction: column;
            width: var(--sidebar-width);
            overflow-y: auto;
            overflow-x: hidden;
            background: var(--brand-dark);
            color: var(--text-light);
            transition: transform 0.25s ease;
        }

        .sidebar-brand {
            display: flex;
            flex-direction: column;
            justify-content: center;
            flex-shrink: 0;
            min-height: 72px;
            padding: 12px 20px;
            background: #352019;
            border-bottom: 1px solid var(--border-dark);
        }

        .brand-name {
            color: var(--accent);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.12em;
            line-height: 1.4;
            text-transform: uppercase;
        }

        .brand-role {
            margin-top: 0;
            color: #fffdf8;
            font-size: 19px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.04em;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 14px 8px;
        }

        .nav-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-heading {
            padding: 0 8px 4px;
            color: #a99588;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.12em;
            line-height: 1.4;
            text-transform: uppercase;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            min-height: 34px;
            padding: 7px 8px;
            border-radius: 7px;
            color: #d8c3ae;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.3;
            transition: background 0.2s, color 0.2s;
        }

        .nav-link .material-symbols-outlined {
            font-size: 17px;
            flex-shrink: 0;
        }

        .nav-link:hover {
            background: rgba(227, 205, 184, 0.10);
            color: #fffdf8;
        }

        .nav-link.active {
            background: var(--primary);
            color: #fffdf8;
            box-shadow: var(--shadow);
            font-weight: 800;
        }

        .nav-link.active .material-symbols-outlined {
            color: var(--accent);
        }

        .sidebar-bottom {
            flex-shrink: 0;
            margin-top: auto;
            padding: 8px;
            background: rgba(0, 0, 0, 0.12);
            border-top: 1px solid var(--border-dark);
        }

        .sidebar-bottom .nav-link {
            min-height: 34px;
        }

        .nav-link.logout:hover {
            background: rgba(186, 26, 26, 0.2);
            color: #ffdad6;
        }

        /* Main layout */

        .admin-main {
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            margin-left: var(--sidebar-width);
        }

        .admin-topbar {
            position: sticky;
            top: 0;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 64px;
            padding: 10px 32px;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
        }

        .topbar-label {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .topbar-title {
            margin-top: 2px;
            color: var(--text-primary);
            font-size: 14px;
            font-weight: 700;
        }

        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .admin-content {
            flex: 1;
            width: 100%;
            max-width: 1600px;
            margin: 0 auto;
            padding: 24px 32px 28px;
        }

        .page-heading {
            margin-bottom: 20px;
        }

        .eyebrow {
            color: var(--text-muted);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .page-title {
            margin-top: 5px;
            color: var(--text-primary);
            font-size: clamp(28px, 3vw, 36px);
            line-height: 1.2;
        }

        .page-description {
            margin: 8px 0 0;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* Buttons */

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 16px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            transition: background 0.2s, border-color 0.2s;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
        }

        .btn-secondary {
            background: var(--surface);
            border-color: var(--border);
            color: var(--text-strong, #4d4038);
        }

        .btn-secondary:hover {
            background: var(--surface-soft);
            border-color: var(--border-dark);
        }

        .icon-button {
            display: inline-grid;
            place-items: center;
            width: 40px;
            height: 40px;
            padding: 0;
            border: 1px solid var(--border);
            border-radius: 50%;
            background: var(--surface);
            color: var(--primary);
        }

        .icon-button:hover {
            background: var(--surface-soft);
        }

        .mobile-menu-button {
            display: none;
        }

        .sidebar-backdrop {
            display: none;
        }

        /* Cards and shared components */

        .card {
            min-width: 0;
            padding: 22px;
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: var(--surface);
            box-shadow: var(--shadow);
        }

        .card-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 18px;
        }

        .card-title {
            color: var(--text-primary);
            font-family: 'Manrope', sans-serif;
            font-size: 15px;
            font-weight: 800;
        }

        .card-subtitle {
            margin-top: 3px;
            color: var(--text-muted);
            font-size: 12px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            width: fit-content;
            padding: 5px 10px;
            border-radius: 9999px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 0.04em;
            text-transform: uppercase;
        }

        .status-success {
            background: var(--success-bg);
            color: var(--success);
        }

        .status-warning {
            background: var(--warning-bg);
            color: var(--warning);
        }

        .status-neutral {
            background: #eee5da;
            color: var(--text-muted);
        }

        .status-danger {
            background: var(--danger-bg);
            color: var(--danger);
        }

        .status-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Footer */

        .admin-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 0;
            padding: 14px 32px;
            background: var(--brand-dark);
            color: var(--text-light);
            font-size: 11px;
        }

        .footer-links {
            display: flex;
            gap: 20px;
        }

        .footer-links a:hover {
            color: #fffdf8;
        }

        /* Responsive layout */

        @media (max-width: 1100px) {
            :root {
                --sidebar-width: 232px;
            }

            .admin-topbar {
                padding-right: 24px;
                padding-left: 24px;
            }

            .admin-content {
                padding: 28px 24px 40px;
            }
        }

        @media (max-width: 760px) {
            .admin-sidebar {
                width: 270px;
                max-width: 85vw;
                transform: translateX(-100%);
            }

            body.sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            .sidebar-backdrop {
                position: fixed;
                inset: 0;
                z-index: 90;
                background: rgba(39, 28, 23, 0.45);
            }

            body.sidebar-open .sidebar-backdrop {
                display: block;
            }

            .admin-main {
                margin-left: 0;
            }

            .admin-topbar {
                min-height: 68px;
                gap: 12px;
                padding: 12px 18px;
            }

            .mobile-menu-button {
                display: inline-grid;
                flex-shrink: 0;
            }

            .admin-content {
                padding: 26px 18px 36px;
            }

            .admin-footer {
                align-items: flex-start;
                flex-direction: column;
                padding: 22px 18px;
            }

            .topbar-label {
                font-size: 9px;
            }

            .topbar-title {
                font-size: 12px;
            }
        }

        @media (max-width: 420px) {
            .admin-topbar {
                padding-right: 12px;
                padding-left: 12px;
            }

            .admin-content {
                padding-right: 12px;
                padding-left: 12px;
            }

            .topbar-actions {
                gap: 5px;
            }

            .topbar-actions .btn {
                padding: 8px 10px;
                font-size: 11px;
            }

            .footer-links {
                flex-wrap: wrap;
                gap: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <aside class="admin-sidebar" id="adminSidebar">
        <a href="{{ url('/admin/dashboard') }}" class="sidebar-brand">
            <span class="brand-name">Housing 4 Raajje</span>
            <span class="brand-role">ADMIN</span>
        </a>

        <nav class="sidebar-nav" aria-label="Administrator navigation">

            <div class="nav-group">
                <span class="nav-heading">Main</span>

                <a href="{{ url('/admin/dashboard') }}"
                   class="nav-link @if(request()->is('admin/dashboard')) active @endif"
                   @if(request()->is('admin/dashboard')) aria-current="page" @endif>
                    <span class="material-symbols-outlined">grid_view</span>
                    Dashboard
                </a>
            </div>

            <div class="nav-group">
                <span class="nav-heading">Management</span>

                <a href="{{ url('/admin/users') }}"
                   class="nav-link @if(request()->is('admin/users*')) active @endif">
                    <span class="material-symbols-outlined">group</span>
                    Users
                </a>

                <a href="{{ url('/admin/roles') }}"
                   class="nav-link @if(request()->is('admin/roles*')) active @endif">
                    <span class="material-symbols-outlined">badge</span>
                    Roles
                </a>

                <a href="{{ url('/admin/branches') }}"
                   class="nav-link @if(request()->is('admin/branches*')) active @endif">
                    <span class="material-symbols-outlined">holiday_village</span>
                    Branches
                </a>

                <a href="{{ url('/admin/properties') }}"
                   class="nav-link @if(request()->is('admin/properties*')) active @endif">
                    <span class="material-symbols-outlined">apartment</span>
                    Properties
                </a>
            </div>

            <div class="nav-group">
                <span class="nav-heading">Operations</span>

                <a href="{{ url('/admin/applications') }}"
                   class="nav-link @if(request()->is('admin/applications*')) active @endif">
                    <span class="material-symbols-outlined">contract_edit</span>
                    Applications
                </a>

                <a href="{{ url('/admin/viewings') }}"
                   class="nav-link @if(request()->is('admin/viewings*')) active @endif">
                    <span class="material-symbols-outlined">calendar_today</span>
                    Viewings
                </a>

                <a href="{{ url('/admin/leases') }}"
                   class="nav-link @if(request()->is('admin/leases*')) active @endif">
                    <span class="material-symbols-outlined">history_edu</span>
                    Leases
                </a>

                <a href="{{ url('/admin/payments') }}"
                   class="nav-link @if(request()->is('admin/payments*')) active @endif">
                    <span class="material-symbols-outlined">payments</span>
                    Payments
                </a>

                <a href="{{ url('/admin/maintenance') }}"
                   class="nav-link @if(request()->is('admin/maintenance*')) active @endif">
                    <span class="material-symbols-outlined">handyman</span>
                    Maintenance
                </a>
            </div>

            <div class="nav-group">
                <span class="nav-heading">System</span>

                <a href="{{ url('/admin/audit-logs') }}"
                   class="nav-link @if(request()->is('admin/audit-logs*')) active @endif">
                    <span class="material-symbols-outlined">receipt_long</span>
                    Audit Logs
                </a>

                <a href="{{ url('/admin/backups') }}"
                   class="nav-link @if(request()->is('admin/backups*')) active @endif">
                    <span class="material-symbols-outlined">cloud_sync</span>
                    Backups
                </a>

                <a href="{{ url('/admin/settings') }}"
                   class="nav-link @if(request()->is('admin/settings*')) active @endif">
                    <span class="material-symbols-outlined">settings</span>
                    Settings
                </a>
            </div>
        </nav>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ url('/logout') }}">
                @csrf
                <button type="submit" class="nav-link logout"
                        style="width: 100%; border: 0; background: transparent; text-align: left;">
                    <span class="material-symbols-outlined">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <div class="admin-main">
        <header class="admin-topbar">
            <div style="display:flex; align-items:center; gap:12px;">
                <button type="button"
                        class="icon-button mobile-menu-button"
                        id="sidebarToggle"
                        aria-label="Toggle navigation"
                        aria-controls="adminSidebar"
                        aria-expanded="false">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <div>
                    <div class="topbar-label">Central Console · Malé HQ</div>
                    <div class="topbar-title">
                        Signed in as {{ auth()->user()?->name ?? 'Administrator' }}
                    </div>
                </div>
            </div>

            <div class="topbar-actions">
                @yield('header-actions')
            </div>
        </header>

        <main class="admin-content">
            @yield('content')
        </main>

        <footer class="admin-footer">
            <span>© {{ date('Y') }} Housing 4 Raajje</span>

            <nav class="footer-links" aria-label="Footer navigation">
                <a href="{{ url('/help') }}">Help centre</a>
                <a href="{{ url('/privacy') }}">Privacy</a>
                <a href="{{ url('/terms') }}">Terms</a>
            </nav>
        </footer>
    </div>

    <script>
        (() => {
            const toggle = document.getElementById('sidebarToggle');
            const backdrop = document.getElementById('sidebarBackdrop');

            function setSidebar(open) {
                document.body.classList.toggle('sidebar-open', open);

                if (toggle) {
                    toggle.setAttribute('aria-expanded', String(open));
                }
            }

            toggle?.addEventListener('click', () => {
                setSidebar(!document.body.classList.contains('sidebar-open'));
            });

            backdrop?.addEventListener('click', () => setSidebar(false));

            document.querySelectorAll('.admin-sidebar .nav-link').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 760) setSidebar(false);
                });
            });

            document.addEventListener('keydown', event => {
                if (event.key === 'Escape') setSidebar(false);
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>