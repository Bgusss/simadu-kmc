<div class="topbar d-flex justify-content-between align-items-center position-relative">

    {{-- Kiri: Hamburger + Page Title --}}
    <div class="fw-bold text-dark fs-5 d-flex align-items-center" style="min-width: 0;">
        <button class="hamburger-btn me-2" onclick="toggleSidebar()" aria-label="Menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="text-truncate">@yield('page-title')</span>
    </div>

    {{-- Tengah: Tab Navigation (absolut agar benar-benar di tengah) --}}
    @hasSection('tab-nav')
        <div class="position-absolute start-50 translate-middle-x d-none d-lg-block">
            @yield('tab-nav')
        </div>
    @endif

    {{-- Kanan: Actions --}}
    <div class="d-flex align-items-center gap-3 flex-shrink-0">
        <a href="{{ route('ticketing.index') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold d-none d-md-flex align-items-center gap-2" style="border-width: 2px; white-space: nowrap;">
            <i class="fa-solid fa-globe"></i> Lihat Halaman Publik
        </a>
        <a href="{{ route('ticketing.index') }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-circle d-flex d-md-none align-items-center justify-content-center" style="width: 34px; height: 34px; border-width: 2px;" title="Halaman Publik">
            <i class="fa-solid fa-globe"></i>
        </a>
    </div>

</div>
