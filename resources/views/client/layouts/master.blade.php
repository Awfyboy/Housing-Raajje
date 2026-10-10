<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Housing 4 Raajje | @yield('title', 'Client Portal')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --hr-background: #f4efe6;
            --hr-surface: #fffdf8;
            --hr-ink: #271c17;
            --hr-text: #2f2924;
            --hr-primary: #5b3d2e;
            --hr-primary-dark: #432d24;
            --hr-border: #e7ddd1;
            --hr-muted: #778292;
            --hr-accent: #e3cdb8;
            --hr-success: #33825a;
        }

        body {
            min-height: 100vh;
            background: var(--hr-background);
            color: var(--hr-text);
            font-family: "Manrope", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .font-display {
            font-family: "DM Serif Display", serif;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: rgba(39, 28, 23, 0.97);
            border-bottom: 1px solid #6d5548;
        }

        .brand {
            color: var(--hr-background);
            font-size: .95rem;
            font-weight: 800;
            letter-spacing: .12em;
            text-decoration: none;
            white-space: nowrap;
        }

        .brand span {
            color: var(--hr-accent);
            font-size: 1.2em;
        }

        .navbar .nav-link {
            position: relative;
            padding: 1.65rem .2rem;
            color: #b9a89a;
            font-size: .9rem;
            font-weight: 700;
        }

        .navbar .nav-link:hover,
        .navbar .nav-link:focus,
        .navbar .nav-link.active {
            color: var(--hr-background);
        }

        .navbar .nav-link.active::after {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            height: 3px;
            border-radius: 3px 3px 0 0;
            background: var(--hr-accent);
            content: "";
        }

        .icon-button {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid #6d5548;
            border-radius: 50%;
            background: transparent;
            color: var(--hr-accent);
        }

        .icon-button:hover {
            border-color: var(--hr-background);
            color: var(--hr-background);
        }

        .notification-dot {
            position: absolute;
            top: 8px;
            right: 9px;
            width: 8px;
            height: 8px;
            border: 2px solid var(--hr-ink);
            border-radius: 50%;
            background: var(--hr-accent);
        }

        .profile-button {
            display: flex;
            align-items: center;
            gap: .65rem;
            padding: .35rem .8rem .35rem .35rem;
            border: 1px solid #6d5548;
            border-radius: 50rem;
            background: transparent;
            color: var(--hr-background);
        }

        .profile-avatar {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--hr-accent);
            color: var(--hr-ink);
            font-size: .75rem;
            font-weight: 800;
        }

        .eyebrow {
            color: var(--hr-primary);
            font-size: .75rem;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
        }

        .section-heading {
            font-family: "Manrope", sans-serif;
            font-size: clamp(1.5rem, 2vw, 2rem);
            font-weight: 800;
            letter-spacing: -.03em;
        }

        .text-muted-custom {
            color: var(--hr-muted);
        }

        .btn-hr {
            border-color: var(--hr-primary-dark);
            background: var(--hr-primary-dark);
            color: #fff;
        }

        .btn-hr:hover,
        .btn-hr:focus {
            border-color: var(--hr-primary);
            background: var(--hr-primary);
            color: #fff;
        }

        .text-link {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: 0;
            border: 0;
            background: transparent;
            color: var(--hr-primary);
            font-size: .875rem;
            font-weight: 800;
            text-decoration: none;
        }

        .text-link:hover {
            color: var(--hr-primary-dark);
        }

        .site-footer {
            border-top: 1px solid #6d5548;
            background: var(--hr-ink);
            color: #b9a89a;
            font-size: .75rem;
        }

        .site-footer a {
            color: inherit;
            text-decoration: none;
        }

        .site-footer a:hover {
            color: var(--hr-background);
        }

        @media (max-width: 991.98px) {
            .navbar .nav-link {
                padding: .75rem 0;
            }

            .navbar .nav-link.active::after {
                right: auto;
                width: 2rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Shared client header -->
    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-dark">
            <div class="container-xxl px-3 px-lg-4">
                <a class="brand me-lg-4" href="{{ url('/client') }}" aria-label="Housing 4 Raajje home">
                    HOUSING <span>4</span> RAAJJE
                </a>

                <div class="d-flex align-items-center gap-2 order-lg-3">
                    <div class="dropdown">
                        <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="View notifications">
                            <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"></path>
                                <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                            </svg>
                            <span class="notification-dot"></span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow" style="min-width: 280px;">
                            <h6 class="mb-2">Notifications</h6>
                            <a class="dropdown-item rounded py-2" href="{{ url('/client/schedule') }}">Your viewing has been confirmed</a>
                            <a class="dropdown-item rounded py-2" href="{{ url('/client/applications') }}">An application status was updated</a>
                            <a class="dropdown-item rounded py-2" href="{{ url('/client/notifications') }}">View all notifications</a>
                        </div>
                    </div>

                    <div class="dropdown d-none d-sm-block">
                        <button class="profile-button dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="profile-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'Aishath', 0, 1)) }}{{ strtoupper(substr(strstr(Auth::user()->name ?? 'Aishath', ' ') ?: 'M', 1, 1)) }}</span>
                            <span class="small fw-bold">{{ Auth::user()->name ?? 'Aishath' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><a class="dropdown-item" href="{{ url('/client/profile') }}">My profile</a></li>
                            <li><a class="dropdown-item" href="{{ url('/client/settings') }}">Preferences &amp; security</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ url('/logout') }}">
                                    @csrf
                                    <button class="dropdown-item" type="submit">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </div>

                    <button class="navbar-toggler ms-1" type="button" data-bs-toggle="collapse" data-bs-target="#clientNavbar" aria-controls="clientNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>

                <div class="collapse navbar-collapse order-lg-2 me-lg-4" id="clientNavbar">
                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-4">
                        <li class="nav-item">
                            <a class="nav-link @if(View::yieldContent('activeNav') === 'home') active @endif"
                            href="{{ url('/client') }}">
                                Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if(View::yieldContent('activeNav') === 'properties') active @endif"
                            href="{{ url('/client/properties') }}">
                                Properties
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if(View::yieldContent('activeNav') === 'schedule') active @endif"
                            href="{{ url('/client/schedule') }}">
                                Schedule
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link @if(View::yieldContent('activeNav') === 'messages') active @endif"
                            href="{{ url('/client/messages') }}">
                                Messages
                            </a>
                        </li>

                        <li class="nav-item d-sm-none">
                            <a class="nav-link" href="{{ url('/client/profile') }}">My profile</a>
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

    <!-- Child views replace this section with @section('content') -->
    <main>
        @yield('content')
    </main>

    <!-- Shared client footer -->
    <footer class="site-footer">
        <div class="container-xxl px-3 px-lg-4 py-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <p class="mb-0">© {{ date('Y') }} Housing 4 Raajje</p>
            <nav class="d-flex gap-4" aria-label="Footer navigation">
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
