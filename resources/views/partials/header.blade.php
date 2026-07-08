<div class="topbar">

    <div class="d-flex justify-content-between align-items-center position-relative">

        {{-- Kiri: Page Title --}}
        <div class="mb-0 fw-bold d-flex align-items-center text-dark" style="letter-spacing: -0.5px; font-size: 22px;">
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