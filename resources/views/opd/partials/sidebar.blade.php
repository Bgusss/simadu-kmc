<nav class="sidebar">
    <div class="sidebar-header">
        <h4 class="mb-0 fw-bold">SIMODU-KMC</h4>
        <small class="text-white-50">Portal OPD</small>
    </div>

    <ul class="list-unstyled components">
        <li class="{{ request()->routeIs('opd.dashboard') ? 'active' : '' }}">
            <a href="{{ route('opd.dashboard') }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </li>
        <li class="{{ request()->routeIs('opd.tickets.*') ? 'active' : '' }}">
            <a href="{{ route('opd.tickets.index') }}">
                <i class="fas fa-ticket-alt"></i> Daftar Tiket
            </a>
        </li>
        <li class="{{ request()->routeIs('opd.chat.*') ? 'active' : '' }}">
            <a href="{{ route('opd.chat.index') }}">
                <i class="fas fa-comments"></i> Live Chat
            </a>
        </li>
        <li class="{{ request()->routeIs('opd.profile') ? 'active' : '' }}">
            <a href="{{ route('opd.profile') }}">
                <i class="fas fa-user-circle"></i> Profil
            </a>
        </li>
    </ul>

    <div class="px-3 mt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Keluar
            </button>
        </form>
    </div>
</nav>
