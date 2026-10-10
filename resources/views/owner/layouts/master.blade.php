
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Housing 4 Raajje | @yield('title', 'Owner Portal')</title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Material Symbols -->
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap"
        rel="stylesheet"
    >

    <style>
        /* ========================================
           1. Design system: colors and variables
        ======================================== */
        :root {
            --hr-background: #f4efe6;
            --hr-surface: #fffdf8;
            --hr-surface-subtle: #eee5da;
            --hr-surface-input: #f1e9de;
            --hr-surface-hero: #e3d8ca;

            --hr-ink: #271c17;
            --hr-text: #2f2924;
            --hr-text-strong: #4d4038;
            --hr-muted: #786f67;
            --hr-caption: #88796d;
            --hr-text-light: #b9a89a;

            --hr-primary: #432d24;
            --hr-primary-dark: #271c17;
            --hr-primary-accent: #5b3d2e;
            --hr-terracotta: #6b4937;
            --hr-accent: #e3cdb8;

            --hr-border: #eee5da;
            --hr-border-dark: #6d5548;

            --hr-success: #33825a;
            --hr-success-bg: #e5f5ec;
            --hr-success-dot: #46a773;
            --hr-danger: #ba1a1a;
            --hr-danger-bg: #ffdad6;

            --hr-radius-sm: .5rem;
            --hr-radius-md: .75rem;
            --hr-radius-lg: 1rem;
            --hr-radius-xl: 1.5rem;

            --hr-shadow-card: 0 1px 3px rgba(71, 47, 36, .05);
            --hr-shadow-raised: 0 18px 45px rgba(10, 6, 4, .14);
            --hr-shadow-panel: 0 12px 35px rgba(71, 47, 36, .07);
        }

        /* ========================================
           2. Base typography and page styles
        ======================================== */
        body {
            min-height: 100vh;
            margin: 0;
            background: var(--hr-background);
            color: var(--hr-text);
            font-family: "Manrope", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, h5, h6,
        .font-display {
            font-family: "DM Serif Display", serif;
        }

        .font-body {
            font-family: "Manrope", sans-serif;
        }

        .page-container {
            width: 100%;
            max-width: 1440px;
            margin-inline: auto;
            padding-inline: 1.25rem;
        }

        .page-content {
            padding-block: 2rem;
        }

        .section-heading {
            margin-bottom: .25rem;
            color: var(--hr-ink);
            font-family: "Manrope", sans-serif;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .eyebrow {
            color: var(--hr-terracotta);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .text-muted-custom {
            color: var(--hr-muted) !important;
        }

        .text-caption-custom {
            color: var(--hr-caption);
        }

        /* ========================================
           3. Shared header and navigation
        ======================================== */
        .site-header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(39, 28, 23, .97);
            border-bottom: 1px solid var(--hr-border-dark);
            backdrop-filter: blur(12px);
        }

        .owner-navbar {
            min-height: 80px;
            padding-block: 0;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            color: var(--hr-background);
            font-size: .95rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-decoration: none;
            white-space: nowrap;
        }

        .brand:hover {
            color: var(--hr-accent);
        }

        .brand-number {
            color: var(--hr-accent);
            font-size: 1.2em;
        }

        .portal-badge {
            padding: .25rem .5rem;
            border-radius: .25rem;
            background: var(--hr-primary);
            color: #f5dfc9;
            font-size: .625rem;
            font-weight: 800;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .owner-navbar .navbar-collapse {
            align-self: stretch;
        }

        .owner-navbar .navbar-nav {
            height: 100%;
            gap: 1.5rem;
        }

        .owner-navbar .nav-item {
            display: flex;
            align-items: stretch;
        }

        .owner-navbar .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            padding: 0.15rem;
            color: var(--hr-text-light);
            font-size: .875rem;
            font-weight: 600;
            transition: color .2s ease;
        }

        .owner-navbar .nav-link:hover,
        .owner-navbar .nav-link:focus {
            color: var(--hr-background);
        }

        /* Manually selected active item.
           The underline touches the header's bottom edge. */
        .owner-navbar .nav-link.active {
            color: var(--hr-accent);
        }

        .owner-navbar .nav-link.active::after {
            position: absolute;
            right: 0;
            bottom: -0.8rem;
            left: 0;
            height: 3px;
            border-radius: 3px 3px 0 0;
            background: var(--hr-accent);
            content: "";
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .icon-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            flex-shrink: 0;
            border: 1px solid var(--hr-border-dark);
            border-radius: 50%;
            background: transparent;
            color: var(--hr-accent);
            text-decoration: none;
            transition: border-color .2s, color .2s;
        }

        .icon-button:hover {
            border-color: var(--hr-background);
            color: var(--hr-background);
        }

        .notification-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 8px;
            height: 8px;
            border: 2px solid var(--hr-ink);
            border-radius: 50%;
            background: var(--hr-accent);
        }

        .profile-button {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .25rem .75rem .25rem .25rem;
            border: 1px solid var(--hr-border-dark);
            border-radius: 50rem;
            background: transparent;
            color: var(--hr-background);
        }

        .profile-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            border-radius: 50%;
            background: var(--hr-accent);
            color: var(--hr-ink);
            font-size: .75rem;
            font-weight: 800;
        }

        .owner-navbar .navbar-toggler {
            border-color: var(--hr-border-dark);
        }

        .owner-navbar .navbar-toggler:focus {
            box-shadow: 0 0 0 .2rem rgba(227, 205, 184, .2);
        }

        /* ========================================
           4. Reusable buttons and surfaces
        ======================================== */
        .btn-hr {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            min-height: 44px;
            padding: .65rem 1.25rem;
            border: 1px solid var(--hr-primary);
            border-radius: var(--hr-radius-sm);
            background: var(--hr-primary);
            color: #fff;
            font-size: .875rem;
            font-weight: 800;
            text-decoration: none;
            transition: background .2s, box-shadow .2s;
        }

        .btn-hr:hover,
        .btn-hr:focus {
            border-color: var(--hr-primary-accent);
            background: var(--hr-primary-accent);
            color: #fff;
        }

        .btn-hr-light {
            border-color: var(--hr-border);
            background: var(--hr-surface-subtle);
            color: var(--hr-ink);
        }

        .btn-hr-light:hover {
            border-color: var(--hr-accent);
            background: var(--hr-accent);
            color: var(--hr-ink);
        }

        .hr-card {
            height: 100%;
            padding: 1.5rem;
            border: 1px solid rgba(211, 195, 190, .35);
            border-radius: var(--hr-radius-md);
            background: var(--hr-surface);
            box-shadow: var(--hr-shadow-card);
        }

        .hr-card-title {
            margin-bottom: .25rem;
            color: var(--hr-ink);
            font-family: "Manrope", sans-serif;
            font-size: 1.125rem;
            font-weight: 800;
        }

        .metric-value {
            color: var(--hr-ink);
            font-size: clamp(1.8rem, 3vw, 2.25rem);
            font-weight: 800;
            letter-spacing: -.03em;
            line-height: 1.2;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .35rem .65rem;
            border-radius: 50rem;
            background: var(--hr-success-bg);
            color: var(--hr-success);
            font-size: .6875rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--hr-success-dot);
        }

        .soft-panel {
            border-radius: var(--hr-radius-sm);
            background: var(--hr-surface-subtle);
        }

        .detail-chip {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            padding: .45rem .7rem;
            border-radius: .375rem;
            background: rgba(238, 229, 218, .6);
            color: var(--hr-text-strong);
            font-size: .8rem;
            font-weight: 600;
        }

        .detail-chip .material-symbols-outlined {
            color: var(--hr-primary-accent);
            font-size: 18px;
        }

        .text-link-hr {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            color: var(--hr-primary);
            font-size: .875rem;
            font-weight: 800;
            text-decoration: none;
        }

        .text-link-hr:hover {
            color: var(--hr-primary-accent);
        }

        /* ========================================
           5. Property card and alerts
        ======================================== */
        .property-card {
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hr-shadow-raised);
        }

        .property-image {
            width: 100%;
            height: 100%;
            min-height: 220px;
            object-fit: cover;
            border-radius: var(--hr-radius-sm);
        }

        .property-image-wrap {
            position: relative;
            min-height: 220px;
        }

        .image-label {
            position: absolute;
            top: .75rem;
            left: .75rem;
            padding: .35rem .7rem;
            border-radius: 50rem;
            background: rgba(255, 253, 248, .95);
            color: var(--hr-success);
            font-size: .6875rem;
            font-weight: 800;
        }

        .image-reference {
            position: absolute;
            bottom: .75rem;
            left: .75rem;
            padding: .3rem .55rem;
            border-radius: .25rem;
            background: rgba(39, 28, 23, .85);
            color: var(--hr-background);
            font-size: .75rem;
        }

        .alert-row {
            display: flex;
            align-items: flex-start;
            gap: .75rem;
            padding: 1rem;
            border-radius: var(--hr-radius-sm);
            background: var(--hr-surface-container, #f8f3ea);
        }

        .alert-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            flex-shrink: 0;
            border-radius: .5rem;
            background: var(--hr-surface-subtle);
            color: var(--hr-primary);
        }

        .alert-label {
            padding: .25rem .5rem;
            border-radius: .25rem;
            background: var(--hr-accent);
            color: var(--hr-ink);
            font-size: .6875rem;
            font-weight: 800;
            white-space: nowrap;
        }

        .alert-label-danger {
            background: var(--hr-danger-bg);
            color: var(--hr-danger);
        }

        /* ========================================
           6. Footer
        ======================================== */
        .site-footer {
            border-top: 1px solid var(--hr-border-dark);
            background: var(--hr-ink);
            color: var(--hr-text-light);
            font-size: .75rem;
        }

        .site-footer a {
            color: inherit;
            text-decoration: none;
        }

        .site-footer a:hover {
            color: var(--hr-background);
        }

        /* ========================================
           7. Responsive layout
        ======================================== */
        @media (min-width: 768px) {
            .page-container {
                padding-inline: 2rem;
            }

            .page-content {
                padding-block: 2.5rem;
            }
        }

        @media (min-width: 1200px) {
            .page-container {
                padding-inline: 3rem;
            }

            .page-content {
                padding-block: 3rem;
            }
        }

        @media (max-width: 991.98px) {
            .owner-navbar {
                padding-block: .75rem;
            }

            .owner-navbar .navbar-nav {
                gap: 0;
                padding-top: .5rem;
            }

            .owner-navbar .nav-item {
                display: block;
            }

            .owner-navbar .nav-link {
                min-height: 42px;
                padding: .65rem .25rem;
            }

            .owner-navbar .nav-link.active::after {
                right: auto;
                bottom: 0;
                width: 2.5rem;
            }

            .header-actions {
                margin-left: auto;
            }
        }

        @media (max-width: 575.98px) {
            .page-container {
                padding-inline: 1rem;
            }

            .hr-card {
                padding: 1.15rem;
            }

            .profile-name,
            .portal-badge {
                display: none;
            }

            .property-image-wrap {
                min-height: 190px;
            }
        }
        
        @media (min-width: 992px) {
            .owner-navbar .navbar-collapse {
                margin-right: 1.5rem;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Shared Owner header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark owner-navbar">
            <div class="container-fluid page-container">

                <!-- Brand -->
                <div class="d-flex align-items-center gap-3">
                    <a class="brand" href="{{ url('/owner') }}">
                        HOUSING <span class="brand-number">4</span> RAAJJE
                    </a>

                    <span class="portal-badge">Owner</span>
                </div>

                <!-- Header actions -->
                <div class="header-actions order-lg-3">
                    <div class="dropdown">
                        <button
                            class="icon-button"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            aria-label="Notifications"
                        >
                            <span class="material-symbols-outlined">notifications</span>
                            <span class="notification-dot"></span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow mt-2">
                            <li>
                                <h6 class="dropdown-header">Notifications</h6>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/viewings') }}">
                                    Upcoming property viewing
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/leases') }}">
                                    Lease renewal reminder
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/notifications') }}">
                                    View all notifications
                                </a>
                            </li>
                        </ul>
                    </div>

                    <a
                        class="icon-button d-none d-sm-inline-flex"
                        href="{{ url('/owner/messages') }}"
                        aria-label="Messages"
                    >
                        <span class="material-symbols-outlined">chat_bubble</span>
                    </a>

                    <div class="dropdown d-none d-sm-block">
                        <button
                            class="profile-button dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            @php
                                $ownerName = Auth::user()->name ?? 'Ahmed';
                                $ownerInitials = collect(
                                    preg_split('/\s+/', trim($ownerName))
                                )->filter()->take(2)->map(
                                    fn ($part) => strtoupper(substr($part, 0, 1))
                                )->implode('');
                            @endphp

                            <span class="profile-avatar">
                                {{ $ownerInitials ?: 'O' }}
                            </span>

                            <span class="profile-name small fw-bold">
                                {{ $ownerName }}
                            </span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow mt-2">
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/profile') }}">
                                    My profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/settings') }}">
                                    Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">
                                        Log out
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <!-- Mobile navigation toggle -->
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#ownerNavbar"
                        aria-controls="ownerNavbar"
                        aria-expanded="false"
                        aria-label="Toggle navigation"
                    >
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <!-- Manual active navigation -->
                <div class="collapse navbar-collapse order-lg-2" id="ownerNavbar">
                    <ul class="navbar-nav ms-lg-auto">
                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'dashboard' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'dashboard') aria-current="page" @endif
                                href="{{ url('/owner') }}"
                            >
                                Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'properties' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'properties') aria-current="page" @endif
                                href="{{ url('/owner/properties') }}"
                            >
                                My Properties
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'viewings' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'viewings') aria-current="page" @endif
                                href="{{ url('/owner/viewings') }}"
                            >
                                Viewings
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'leases' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'leases') aria-current="page" @endif
                                href="{{ url('/owner/leases') }}"
                            >
                                Leases
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'finances' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'finances') aria-current="page" @endif
                                href="{{ url('/owner/finances') }}"
                            >
                                Finances
                            </a>
                        </li>

                        <li class="nav-item">
                            <a
                                class="nav-link {{ trim($__env->yieldContent('activeNav')) === 'maintenance' ? 'active' : '' }}"
                                @if (trim($__env->yieldContent('activeNav')) === 'maintenance') aria-current="page" @endif
                                href="{{ url('/owner/maintenance') }}"
                            >
                                Maintenance
                            </a>
                        </li>

                        <li class="nav-item d-sm-none">
                            <a class="nav-link" href="{{ url('/owner/profile') }}">
                                My profile
                            </a>
                        </li>

                        <li class="nav-item d-sm-none">
                            <form method="POST" action="{{ url('/logout') }}">
                                @csrf
                                <button class="nav-link border-0 bg-transparent" type="submit">
                                    Log out
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>

            </div>
        </nav>
    </header>

    <!-- Child pages insert their content here -->
    <main>
        @yield('content')
    </main>

    <!-- Shared Owner footer -->
    <footer class="site-footer">
        <div class="container-fluid page-container py-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <p class="mb-0">
                &copy; {{ date('Y') }} Housing 4 Raajje
            </p>

            <nav class="d-flex flex-wrap gap-4" aria-label="Footer navigation">
                <a href="{{ url('/help') }}">Help centre</a>
                <a href="{{ url('/privacy') }}">Privacy</a>
                <a href="{{ url('/terms') }}">Terms</a>
            </nav>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>