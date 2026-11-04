<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Housing 4 Raajje | @yield('title', 'Welcome')</title>

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
            display: flex;
            flex-direction: column;
            background: var(--hr-background);
            color: var(--hr-text);
            font-family: "Manrope", sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .font-display {
            font-family: "DM Serif Display", serif;
        }

        /* --- BARE BONES HEADER --- */
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

        .auth-link {
            color: #b9a89a;
            font-size: .9rem;
            font-weight: 700;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .auth-link:hover {
            color: var(--hr-background);
        }

        /* --- MAIN CONTENT AREA --- */
        main {
            flex: 1 0 auto;
            display: flex;
            align-items: center; /* Centers the form vertically */
            justify-content: center;
            padding: 3rem 1rem;
        }

        /* --- BARE BONES FOOTER --- */
        .site-footer {
            flex-shrink: 0;
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

        /* Form styling helpers for your child views */
        .auth-card {
            width: 100%;
            max-width: 450px;
            background: var(--hr-surface);
            border: 1px solid var(--hr-border);
            border-radius: 1rem;
            padding: 2.5rem;
            box-shadow: 0 10px 30px rgba(39, 28, 23, 0.05);
        }

        .btn-hr {
            border-color: var(--hr-primary-dark);
            background: var(--hr-primary-dark);
            color: #fff;
            font-weight: 700;
            padding: 0.75rem 1rem;
        }

        .btn-hr:hover,
        .btn-hr:focus {
            border-color: var(--hr-primary);
            background: var(--hr-primary);
            color: #fff;
        }

        .form-control {
            border-color: var(--hr-border);
            padding: 0.75rem 1rem;
            background-color: #fff;
        }
        
        .form-control:focus {
            border-color: var(--hr-primary);
            box-shadow: 0 0 0 0.25rem rgba(91, 61, 46, 0.1);
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Bare Bones Header -->
    <header class="site-header">
        <div class="container-xxl px-3 px-lg-4 py-3 d-flex align-items-center justify-content-between">
            <a class="brand" href="{{ url('/') }}" aria-label="Housing 4 Raajje home">
                HOUSING <span>4</span> RAAJJE
            </a>
            
            <!-- Toggle between Login and Register -->
            <div class="d-flex gap-3 align-items-center">
                @if(Route::is('login'))
                    <span class="text-muted small d-none d-sm-inline">Don't have an account?</span>
                    <a href="{{ route('register') }}" class="auth-link">Register &rarr;</a>
                @else
                    <span class="text-muted small d-none d-sm-inline">Already have an account?</span>
                    <a href="{{ route('login') }}" class="auth-link">Log In &rarr;</a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content (Login / Register Forms go here) -->
    <main>
        @yield('content')
    </main>

    <!-- Bare Bones Footer -->
    <footer class="site-footer">
        <div class="container-xxl px-3 px-lg-4 py-4 d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
            <p class="mb-0">© {{ date('Y') }} Housing 4 Raajje</p>
            <nav class="d-flex gap-4" aria-label="Footer navigation">
                <a href="#">Help centre</a>
                <a href="#">Privacy</a>
                <a href="#">Terms</a>
            </nav>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>