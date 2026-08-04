<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Pelacakan Aduan - SIMODU KMC')</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=kmc">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=kmc">
    <link rel="apple-touch-icon" href="{{ asset('images/kmc-logo.png') }}?v=kmc">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --kmc-blue: #0d47a1;
            --kmc-blue-dark: #071f49;
            --kmc-orange: #f57c00;
            --kmc-orange-hover: #e65100;
        }
        body {
            background-color: #f8fafc;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1e293b;
        }
        .navbar {
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.03) !important;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            position: sticky;
            top: 0;
            z-index: 1020;
        }
        .navbar-brand {
            font-weight: 700;
            color: var(--kmc-blue) !important;
        }
        
        /* Premium segment control navigation pills */
        .nav-pills-wrapper {
            background-color: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 0;
        }
        .nav-pills {
            background-color: #f1f5f9;
            padding: 5px;
            border-radius: 50px;
            border: 1px solid #cbd5e1;
            display: inline-flex;
        }
        .nav-pills .nav-link {
            color: #475569 !important;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 8px 24px !important;
            border-radius: 50px !important;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            background-color: transparent !important;
            border: none !important;
            white-space: nowrap;
        }
        .nav-pills .nav-link:hover {
            color: var(--kmc-blue) !important;
        }
        .nav-pills .nav-link.active {
            background-color: var(--kmc-blue) !important;
            color: white !important;
            box-shadow: 0 4px 10px rgba(13, 71, 161, 0.25);
        }
        
        /* Overrides for public buttons and navs */
        .btn-primary {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 4px 10px rgba(13, 71, 161, 0.2);
        }
        .btn-primary:hover {
            background-color: var(--kmc-blue-dark) !important;
            border-color: var(--kmc-blue-dark) !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(13, 71, 161, 0.3);
        }
        .btn-outline-primary {
            color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            border-radius: 50px;
            font-weight: 600;
            padding: 10px 24px;
            transition: all 0.25s ease-in-out;
        }
        .btn-outline-primary:hover {
            background-color: var(--kmc-blue) !important;
            color: white !important;
            border-color: var(--kmc-blue) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(13, 71, 161, 0.25);
        }
        
        footer {
            background-color: #ffffff;
            border-top: 1px solid #e2e8f0;
            font-size: 0.85rem;
            color: #64748b;
        }

        /* Premium Brand Branding Styling */
        .brand-logo {
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        .navbar-brand:hover .brand-logo {
            transform: scale(1.08) rotate(3deg);
        }
        .brand-divider {
            width: 2px;
            height: 42px;
            background: linear-gradient(180deg, var(--kmc-blue) 0%, var(--kmc-orange) 100%);
            opacity: 0.25;
            margin-left: 1rem;
            margin-right: 1rem;
            border-radius: 2px;
        }
        .brand-title {
            font-size: 21px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.5px;
            color: var(--kmc-blue);
            margin-bottom: 2px;
        }
        .brand-title .text-orange {
            color: var(--kmc-orange) !important;
        }
        .brand-subtitle {
            font-size: 9.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #64748b;
            line-height: 1.2;
        }
        .brand-tagline {
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            line-height: 1.2;
        }

        /* ── Mobile Responsive ── */
        @media (max-width: 991.98px) {
            .nav-pills-wrapper { padding: 8px 0; }
            .nav-pills { width: 100%; justify-content: center; }
            .nav-pills .nav-link { padding: 8px 16px !important; font-size: 0.82rem; }
        }
        @media (max-width: 575.98px) {
            .brand-logo { width: 36px !important; height: 36px !important; }
            .brand-divider { height: 32px; margin-left: 0.6rem; margin-right: 0.6rem; }
            .brand-title { font-size: 17px; }
            .brand-subtitle { font-size: 8px; }
            .brand-tagline { font-size: 10px; }
            .navbar { padding-top: 10px !important; padding-bottom: 10px !important; }
            .nav-pills .nav-link { padding: 7px 12px !important; font-size: 0.78rem; }
            .nav-pills .nav-link i { display: none; }
            footer .row { text-align: center !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="d-flex flex-column min-vh-100">
    <nav class="navbar navbar-light bg-white shadow-sm py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="{{ route('ticketing.index') }}">
                <img src="{{ asset('images/kmc-logo.png') }}" alt="Logo KMC" style="width: 50px; height: 50px; object-fit: contain;" class="brand-logo">
                <div class="brand-divider"></div>
                <div class="d-flex flex-column justify-content-center">
                    <div class="brand-title">
                        SIMODU <span class="text-orange">KMC</span>
                    </div>
                    <div class="brand-subtitle">Sistem Monitoring Aduan Multi Channel</div>
                    <div class="brand-tagline">Ketapang Media Center</div>
                </div>
            </a>
            <div class="d-flex align-items-center gap-2">
                @unless(request()->routeIs('public.complaint.create'))
                    <a href="{{ route('public.complaint.create') }}" class="btn rounded-pill px-3 px-md-4 fw-bold" style="background-color: #f57c00; border-color: #f57c00; color: white; font-size: 0.85rem;">
                        <i class="fas fa-bullhorn me-1 d-none d-md-inline"></i>Lapor
                    </a>
                @endunless
                @if(request()->routeIs('ticketing.index'))
                    @auth
                        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('opd.dashboard') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold" style="border-color: #0D47A1; color: #0D47A1;">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-3 px-md-4 fw-bold" style="background-color: #0D47A1; border-color: #0D47A1; font-size: 0.85rem;">Login</a>
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    {{-- Tab Navigation — selalu visible, di luar navbar collapse --}}
    @if(request()->routeIs('ticketing.index'))
        <div class="nav-pills-wrapper">
            <div class="container d-flex justify-content-center">
                <ul class="nav nav-pills" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active px-4 rounded-pill fw-bold" id="pills-lacak-tab" data-bs-toggle="pill" data-bs-target="#pills-lacak" type="button" role="tab" aria-controls="pills-lacak" aria-selected="true">
                            <i class="fas fa-search me-1"></i> Lacak Laporan
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link px-4 rounded-pill fw-bold" id="pills-statistik-tab" data-bs-toggle="pill" data-bs-target="#pills-statistik" type="button" role="tab" aria-controls="pills-statistik" aria-selected="false">
                            <i class="fas fa-chart-pie me-1"></i> Statistik & Kinerja
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    @endif

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="footer bg-white border-top py-3 px-4 mt-auto">
        <div class="container">
            <div class="row align-items-center justify-content-between">
                <div class="col-12 col-md-6 text-center text-md-start mb-2 mb-md-0">
                    <p class="mb-0 text-secondary fw-semibold small">
                        &copy; {{ date('Y') }} <span class="text-primary fw-bold">SIMODU KMC</span>. Hak Cipta Dilindungi.
                    </p>
                </div>
                <div class="col-12 col-md-6 text-center text-md-end">
                    <span class="text-muted small fw-medium">
                        Sistem Monitoring Aduan Multi Channel - Ketapang Media Center
                    </span>
                </div>
            </div>
        </div>
    </footer>


    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.flash-toast')
    @stack('scripts')
</body>
</html>
