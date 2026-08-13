<div class="sidebar">

    <div class="d-flex align-items-center mb-5">

        <img
            src="{{ asset('images/kmc-logo.png') }}"
            alt="Logo KMC"
            style="
                width: 60px;
                height: 60px;
                object-fit: contain;
            ">

        <div class="ms-3">

            <div
                class="fw-bold"
                style="
                    color: white;
                    font-size: 22px;
                    line-height: 1.2;
                ">

                SIMODU KMC

            </div>

            <div
                style="
                    color: rgba(255,255,255,.85);
                    font-size: 11px;
                ">

                Sistem Monitoring Aduan Multi Channel

            </div>

        </div>

    </div>

    @if(Auth::check() && Auth::user()->role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('notifications.index') }}" class="{{ request()->is('notifications') ? 'active' : '' }}">
            <i class="fa-solid fa-bell"></i> Notifikasi
        </a>
        <a href="{{ route('tickets.index') }}" class="{{ request()->is('tickets') || request()->is('tickets/*') && !request()->is('tickets/chat*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket"></i> Daftar Tiket
        </a>
        <a href="{{ route('admin.opd.index') }}" class="{{ request()->is('admin/opd*') ? 'active' : '' }}">
            <i class="fa-solid fa-building"></i> Manajemen OPD
        </a>
    @elseif(Auth::check() && Auth::user()->role === 'opd')
        <a href="{{ route('opd.dashboard') }}" class="{{ request()->is('opd') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i> Dashboard
        </a>
        <a href="{{ route('opd.tickets.index') }}" class="{{ request()->is('opd/tickets*') ? 'active' : '' }}">
            <i class="fa-solid fa-ticket"></i> Daftar Tiket
        </a>
        <a href="{{ route('opd.chat.index') }}" class="{{ request()->is('opd/chat*') ? 'active' : '' }}">
            <i class="fa-solid fa-comments"></i> Live Chat
        </a>
    @else
        <a href="{{ route('ticketing.index') }}" class="{{ request()->routeIs('ticketing.*') ? 'active' : '' }}">
            <i class="fa-solid fa-search"></i> Pelacakan Aduan
        </a>
    @endif

    <style>
        .sidebar-user-dropdown .dropdown-toggle::after {
            display: inline-block;
            margin-left: auto;
            vertical-align: 0.255em;
            content: "";
            border-top: 0.3em solid;
            border-right: 0.3em solid transparent;
            border-bottom: 0;
            border-left: 0.3em solid transparent;
            color: rgba(255, 255, 255, 0.7);
        }
        .sidebar-user-dropdown .dropdown-item {
            display: flex !important;
            align-items: center;
            color: rgba(255, 255, 255, 0.85) !important;
            padding: 10px 16px !important;
            font-size: 0.85rem !important;
            transition: all 0.2s ease;
        }
        .sidebar-user-dropdown .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
        }
        .sidebar-user-dropdown .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.15) !important;
            color: #dc3545 !important;
        }
    </style>

    <div style="position: absolute; bottom: 20px; left: 20px; right: 20px;">
        <div class="dropdown sidebar-user-dropdown">
            <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle p-2 rounded-3" 
               href="#" 
               role="button" 
               id="sidebarUserDropdown" 
               data-bs-toggle="dropdown" 
               aria-expanded="false"
               style="background: rgba(255, 255, 255, 0.08); transition: background 0.2s; margin-bottom: 0 !important; gap: 0 !important; color: white !important;"
               onmouseover="this.style.background='rgba(255, 255, 255, 0.15)';"
               onmouseout="this.style.background='rgba(255, 255, 255, 0.08)';">
                
                <div class="bg-white text-primary border rounded-circle overflow-hidden flex-shrink-0" style="width: 38px; height: 38px; min-width: 38px;">
                    @if(Auth::user()->profile_photo)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" style="width: 100%; height: 100%; object-fit: cover;">
                    @else
                        <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                
                <div class="ms-2 text-truncate flex-grow-1" style="max-width: 150px;">
                    <div class="fw-semibold small text-white text-truncate">{{ Auth::user()->name ?? 'Administrator' }}</div>
                </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-dark shadow border-0 mt-2 rounded-3 w-100" aria-labelledby="sidebarUserDropdown" style="background-color: #071f49; border: 1px solid rgba(255, 255, 255, 0.1) !important;">
                <li>
                    <a class="dropdown-item py-2 text-white-50" href="{{ Auth::user()->role === 'admin' ? route('admin.profile') : route('opd.profile') }}" style="gap: 8px !important; border-radius: 6px !important; margin-bottom: 0 !important;">
                        <i class="fas fa-user-circle"></i> Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger py-2 w-100 text-start d-flex align-items-center gap-2" style="background: none; border: none; font-weight: 500; gap: 8px !important; border-radius: 6px !important; margin-bottom: 0 !important;">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>