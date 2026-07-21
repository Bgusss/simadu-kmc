<div class="topbar">

    <div class="d-flex justify-content-between align-items-center position-relative">

        <div class="topbar d-flex justify-content-between align-items-center">
            <div class="fw-bold text-dark fs-5 d-flex align-items-center">
                <button class="hamburger-btn me-2" onclick="toggleSidebar()" aria-label="Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                @yield('page-title')
            </div>

        {{-- Tengah: Tab Navigation (absolut agar benar-benar di tengah) --}}
        @hasSection('tab-nav')
            <div class="position-absolute start-50 translate-middle-x">
                @yield('tab-nav')
            </div>
        @endif

        {{-- Kanan: Actions --}}
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('ticketing.index') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold d-flex align-items-center gap-2" style="border-width: 2px; transition: all 0.2s ease-in-out;">
                <i class="fa-solid fa-globe"></i> Lihat Halaman Publik
            </a>
        </div>

    </div>

</div>