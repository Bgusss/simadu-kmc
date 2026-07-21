<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'OPD Portal') - SIMODU-KMC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
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
            overflow-x: hidden;
            color: #1e293b;
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        
        /* Override Bootstrap Primary and Accent colors with KMC Theme */
        .text-primary {
            color: var(--kmc-blue) !important;
        }
        .bg-primary {
            background-color: var(--kmc-blue) !important;
        }
        .border-primary {
            border-color: var(--kmc-blue) !important;
        }
        .btn-primary {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
            background-color: var(--kmc-blue-dark) !important;
            border-color: var(--kmc-blue-dark) !important;
            box-shadow: 0 4px 12px rgba(13, 71, 161, 0.2) !important;
        }
        .btn-outline-primary {
            color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            transition: all 0.2s ease-in-out;
        }
        .btn-outline-primary:hover, .btn-outline-primary:focus, .btn-outline-primary:active {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
            color: white !important;
        }
        .badge.bg-primary {
            background-color: var(--kmc-blue) !important;
        }
        .bg-primary-subtle {
            background-color: rgba(13, 71, 161, 0.08) !important;
        }
        .text-primary-subtle {
            color: var(--kmc-blue) !important;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--kmc-blue) !important;
            border-color: var(--kmc-blue) !important;
        }
        .pagination .page-link {
            color: var(--kmc-blue);
        }
        .form-control:focus {
            border-color: rgba(13, 71, 161, 0.5);
            box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.15);
        }
        
        .sidebar {
            min-width: 270px;
            max-width: 270px;
            background: linear-gradient(180deg, #0d47a1 0%, #071f49 100%);
            color: white;
            transition: all 0.3s;
            box-shadow: 4px 0 24px rgba(7, 31, 73, 0.15);
            min-height: 100vh;
            z-index: 100;
            padding: 25px 0;
        }
        
        .sidebar .sidebar-header {
            padding: 10px 25px 25px 25px;
            background: transparent;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .sidebar ul.components {
            padding: 25px 20px;
        }
        
        .sidebar ul p {
            color: #fff;
            padding: 10px;
        }
        
        .sidebar ul li a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #cbd5e1;
            text-decoration: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
            border-left: none;
        }
        
        .sidebar ul li a:hover {
            background: rgba(255, 255, 255, 0.08);
            color: white;
        }
        
        .sidebar ul li a i {
            font-size: 18px;
            margin-right: 0;
            width: 24px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .sidebar ul li a:hover i {
            transform: scale(1.1);
        }
        
        .sidebar ul li.active > a {
            background: var(--kmc-orange) !important;
            color: white !important;
            box-shadow: 0 4px 14px rgba(245, 124, 0, 0.4);
            font-weight: 600;
        }
        
        .content {
            width: 100%;
            padding: 30px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            border-radius: 16px;
            margin-bottom: 25px;
            padding: 18px 25px;
            border: 1px solid rgba(241, 245, 249, 0.8);
        }
        
        .card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.03);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #f1f5f9;
            padding: 15px 20px;
            font-weight: 600;
            border-radius: 16px 16px 0 0 !important;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
        }

        .hover-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -8px rgba(0, 0, 0, 0.08) !important;
        }
        
        /* ── Mobile Responsive ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 99;
            transition: opacity 0.3s;
        }
        .sidebar-overlay.show { display: block; }

        @media (max-width: 991.98px) {
            .sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 100;
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show { transform: translateX(0); }
            .content { padding: 16px !important; min-height: 100vh; }
            .navbar { padding: 12px 16px !important; border-radius: 12px; margin-bottom: 16px; }
        }

        @media (max-width: 575.98px) {
            .content { padding: 10px !important; }
            .navbar { padding: 10px 12px !important; margin-bottom: 12px; }
            body { font-size: 0.9rem; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar-overlay" id="sidebarOverlay"></div>
        <!-- Sidebar -->
        @include('opd.partials.sidebar')

        <!-- Page Content -->
        <div class="content">
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-outline-primary border-0 d-lg-none" style="font-size: 1.2rem;">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h5 class="mb-0 ms-2 text-dark fw-bold">@yield('header', 'OPD Portal')</h5>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle text-dark d-flex align-items-center" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-white text-primary border rounded-circle overflow-hidden shadow-sm flex-shrink-0 me-2" style="width: 35px; height: 35px; min-width: 35px;">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <span class="d-none d-md-inline">{{ Auth::user()->name ?? 'User OPD' }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="{{ route('opd.profile') }}"><i class="fas fa-id-card me-2 text-muted"></i> Profil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Keluar</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <div class="container-fluid px-0">
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('partials.flash-toast')
    <script>
        function toggleOpdSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
            document.body.style.overflow = document.querySelector('.sidebar').classList.contains('show') ? 'hidden' : '';
        }
        document.getElementById('sidebarCollapse').addEventListener('click', toggleOpdSidebar);
        document.getElementById('sidebarOverlay').addEventListener('click', toggleOpdSidebar);
        document.querySelectorAll('.sidebar a').forEach(a => {
            a.addEventListener('click', () => { if (window.innerWidth < 992) toggleOpdSidebar(); });
        });
    </script>
    @stack('scripts')
</body>
</html>
