@extends('layouts.app')

@section('title', 'Dashboard - SIMODU KMC')

@section('page-title')
    <i class="fa-solid fa-chart-line text-primary me-2"></i> Dashboard Overview
@endsection

@section('content')

    {{-- Statistik Card Grid --}}
    <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-5">
        <!-- Card 1: Total Tiket -->
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100">
                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: rgba(37, 99, 235, 0.1); color: #2563eb;">
                        <i class="fa-solid fa-ticket fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium mb-1">Total Tiket</div>
                        <h2 class="fw-bold mb-0 text-dark">{{ $totalTickets }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Baru/Diteruskan -->
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100" style="border-top: 4px solid #eab308 !important;">
                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: rgba(234, 179, 8, 0.1); color: #ca8a04;">
                        <i class="fa-solid fa-folder-plus fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium mb-1">Baru / Diteruskan</div>
                        <h2 class="fw-bold mb-0 text-warning">{{ $ticketsMenunggu }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Proses Disposisi -->
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100" style="border-top: 4px solid #f97316 !important;">
                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: rgba(249, 115, 22, 0.1); color: #ea580c;">
                        <i class="fa-solid fa-share-nodes fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium mb-1">Proses Disposisi</div>
                        <h2 class="fw-bold mb-0" style="color: #ea580c;">{{ $ticketsDisposisi }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Diproses -->
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100" style="border-top: 4px solid #06b6d4 !important;">
                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: rgba(6, 182, 212, 0.1); color: #0891b2;">
                        <i class="fa-solid fa-gears fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium mb-1">Diproses</div>
                        <h2 class="fw-bold mb-0 text-info">{{ $ticketsDiproses }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Selesai -->
        <div class="col">
            <div class="card border-0 shadow-sm rounded-4 hover-card h-100" style="border-top: 4px solid #10b981 !important;">
                <div class="card-body p-4 d-flex flex-column align-items-center text-center justify-content-between">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px; background: rgba(16, 185, 129, 0.1); color: #059669;">
                        <i class="fa-solid fa-circle-check fs-4"></i>
                    </div>
                    <div>
                        <div class="text-secondary small fw-medium mb-1">Selesai</div>
                        <h2 class="fw-bold mb-0 text-success">{{ $ticketsSelesai }}</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ========== Notifikasi Stacked Cards ========== --}}
    @php
        $titleLower2 = strtolower($highPriorityNotification->title ?? '');
        $isFb2 = str_contains($titleLower2, 'facebook');
        $isIg2 = str_contains($titleLower2, 'instagram');
        $isWa2 = str_contains($titleLower2, 'whatsapp');

        $titleLower1 = strtolower($latestNotification->title ?? '');
        $isFb1 = str_contains($titleLower1, 'facebook');
        $isIg1 = str_contains($titleLower1, 'instagram');
        $isWa1 = str_contains($titleLower1, 'whatsapp');
    @endphp
    {{-- Toggle buttons --}}
    <div class="d-flex gap-2 mt-3 mb-2">
        <button id="btn-show-terbaru" onclick="showCard('terbaru')" class="btn btn-sm rounded-pill fw-semibold px-3"
            style="font-size: 0.82rem; background: {{ $showPriorityFirst ? 'white' : '#3b82f6' }}; color: {{ $showPriorityFirst ? '#3b82f6' : 'white' }}; border: {{ $showPriorityFirst ? '1.5px solid #3b82f6' : 'none' }}; box-shadow: {{ $showPriorityFirst ? 'none' : '0 2px 8px rgba(59,130,246,0.3)' }}; transition: all 0.25s ease;">
            <i class="fa-solid fa-bell me-1"></i> Notifikasi Terbaru
        </button>
        <button id="btn-show-prioritas" onclick="showCard('prioritas')" class="btn btn-sm rounded-pill fw-semibold px-3"
            style="font-size: 0.82rem; background: {{ $showPriorityFirst ? '#ef4444' : 'white' }}; color: {{ $showPriorityFirst ? 'white' : '#ef4444' }}; border: {{ $showPriorityFirst ? 'none' : '1.5px solid #ef4444' }}; box-shadow: {{ $showPriorityFirst ? '0 2px 8px rgba(239,68,68,0.3)' : 'none' }}; transition: all 0.25s ease;">
            <i class="fa-solid fa-triangle-exclamation me-1"></i> Prioritas Tinggi
        </button>
    </div>

    <div id="notif-stack-outer">

    {{-- Card Prioritas Tinggi --}}
    <div id="notif-prioritas" class="notif-card-slot {{ $showPriorityFirst ? 'is-front' : 'is-back' }}" onclick="if(this.classList.contains('is-back')) showCard('prioritas')">
        @if ($highPriorityNotification)
            <div class="card border-0 shadow-sm rounded-4 hover-card" style="border-left: 5px solid #ef4444 !important;">
                <div class="card-header bg-white border-0 rounded-top-4 py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(241, 245, 249, 0.8) !important;">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center" style="font-size: 1rem;">
                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i> Prioritas Tinggi
                    </h6>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Urgent
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if ($isFb2)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: #1877F2;">
                                <i class="fa-brands fa-facebook-f" style="font-size: 1rem;"></i>
                            </div>
                        @elseif ($isIg2)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: linear-gradient(45deg, #f09433, #dc2743, #bc1888);">
                                <i class="fa-brands fa-instagram" style="font-size: 1rem;"></i>
                            </div>
                        @elseif ($isWa2)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: #25D366;">
                                <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>
                            </div>
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-secondary flex-shrink-0"
                                style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-bell" style="font-size: 1rem;"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $highPriorityNotification->sender_name ?? 'Pengirim Anonim' }}</div>
                            <small class="text-secondary" style="font-size: 0.82rem;"><i class="fa-regular fa-clock me-1"></i>{{ $highPriorityNotification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="rounded-3 p-3 border-start border-danger border-3 mb-3" style="background-color: #fef2f2;">
                        <p class="mb-0 text-dark" style="font-size: 0.92rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            "{{ $highPriorityNotification->display_message }}"
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        @if (isset($highPriorityNotification->suggested_category) || isset($highPriorityNotification->suggested_sub_category))
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-tags me-1"></i>
                                {{ $highPriorityNotification->suggested_category ?? '' }}{{ isset($highPriorityNotification->suggested_category) && isset($highPriorityNotification->suggested_sub_category) ? ' - ' : '' }}{{ $highPriorityNotification->suggested_sub_category ?? '' }}
                            </span>
                        @else
                            <span></span>
                        @endif
                        @if (!empty($highPriorityNotification->permalink))
                            <a href="/notification/{{ $highPriorityNotification->id }}/detail?url={{ urlencode($highPriorityNotification->permalink) }}"
                                target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4" style="border-left: 5px solid #e2e8f0 !important;">
                <div class="card-header bg-white border-0 rounded-top-4 py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(241, 245, 249, 0.8) !important;">
                    <h6 class="mb-0 fw-bold text-muted d-flex align-items-center" style="font-size: 1rem;">
                        <i class="fa-solid fa-triangle-exclamation me-2" style="opacity: 0.4;"></i> Prioritas Tinggi
                    </h6>
                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-circle-exclamation me-1"></i> Urgent
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #e2e8f0;">
                            <i class="fa-solid fa-bell text-muted" style="font-size: 1rem; opacity: 0.5;"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-bold text-muted" style="font-size: 1rem;">—</div>
                            <small class="text-muted" style="font-size: 0.82rem;">Belum ada data</small>
                        </div>
                    </div>
                    <div class="rounded-3 p-3 border-start border-3 mb-3" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                        <p class="mb-0 text-muted fst-italic" style="font-size: 0.92rem; line-height: 1.55;">
                            Tidak ada notifikasi prioritas tinggi saat ini. Semua aduan dalam kondisi normal.
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Card Notifikasi Terbaru --}}
    <div id="notif-terbaru" class="notif-card-slot {{ $showPriorityFirst ? 'is-back' : 'is-front' }}" onclick="if(this.classList.contains('is-back')) showCard('terbaru')">
        @if ($latestNotification)
            <div class="card border-0 shadow-sm rounded-4 hover-card" style="border-left: 5px solid #3b82f6 !important;">
                <div class="card-header bg-white border-0 rounded-top-4 py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(241, 245, 249, 0.8) !important;">
                    <h6 class="mb-0 fw-bold text-dark d-flex align-items-center" style="font-size: 1rem;">
                        <i class="fa-solid fa-bell text-primary me-2"></i> Notifikasi Terbaru
                    </h6>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-info me-1"></i> Terbaru
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        @if ($isFb1)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: #1877F2;">
                                <i class="fa-brands fa-facebook-f" style="font-size: 1rem;"></i>
                            </div>
                        @elseif ($isIg1)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: linear-gradient(45deg, #f09433, #dc2743, #bc1888);">
                                <i class="fa-brands fa-instagram" style="font-size: 1rem;"></i>
                            </div>
                        @elseif ($isWa1)
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0"
                                style="width: 44px; height: 44px; background: #25D366;">
                                <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>
                            </div>
                        @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-secondary flex-shrink-0"
                                style="width: 44px; height: 44px;">
                                <i class="fa-solid fa-bell" style="font-size: 1rem;"></i>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <div class="fw-bold text-dark" style="font-size: 1rem;">{{ $latestNotification->sender_name ?? 'Pengirim Anonim' }}</div>
                            <small class="text-secondary" style="font-size: 0.82rem;"><i class="fa-regular fa-clock me-1"></i>{{ $latestNotification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <div class="rounded-3 p-3 border-start border-primary border-3 mb-3" style="background-color: #f8fafc;">
                        <p class="mb-0 text-dark" style="font-size: 0.92rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            "{{ $latestNotification->display_message }}"
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        @if (isset($latestNotification->suggested_category) || isset($latestNotification->suggested_sub_category))
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill" style="font-size: 0.78rem;">
                                <i class="fa-solid fa-tags me-1"></i>
                                {{ $latestNotification->suggested_category ?? '' }}{{ isset($latestNotification->suggested_category) && isset($latestNotification->suggested_sub_category) ? ' - ' : '' }}{{ $latestNotification->suggested_sub_category ?? '' }}
                            </span>
                        @else
                            <span></span>
                        @endif
                        @if (!empty($latestNotification->permalink))
                            <a href="/notification/{{ $latestNotification->id }}/detail?url={{ urlencode($latestNotification->permalink) }}"
                                target="_blank" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4" style="border-left: 5px solid #e2e8f0 !important;">
                <div class="card-header bg-white border-0 rounded-top-4 py-2 px-3 d-flex justify-content-between align-items-center border-bottom" style="border-color: rgba(241, 245, 249, 0.8) !important;">
                    <h6 class="mb-0 fw-bold text-muted d-flex align-items-center" style="font-size: 1rem;">
                        <i class="fa-solid fa-bell me-2" style="opacity: 0.4;"></i> Notifikasi Terbaru
                    </h6>
                    <span class="badge bg-light text-muted border px-2 py-1 rounded-pill fw-semibold" style="font-size: 0.78rem;">
                        <i class="fa-solid fa-info me-1"></i> Terbaru
                    </span>
                </div>
                <div class="card-body p-3">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px; background: #e2e8f0;">
                            <i class="fa-solid fa-inbox text-muted" style="font-size: 1rem; opacity: 0.5;"></i>
                        </div>
                        <div class="min-w-0">
                            <div class="fw-bold text-muted" style="font-size: 1rem;">—</div>
                            <small class="text-muted" style="font-size: 0.82rem;">Belum ada data</small>
                        </div>
                    </div>
                    <div class="rounded-3 p-3 border-start border-3 mb-3" style="background-color: #f8fafc; border-color: #e2e8f0 !important;">
                        <p class="mb-0 text-muted fst-italic" style="font-size: 0.92rem; line-height: 1.55;">
                            Belum ada notifikasi terbaru yang masuk saat ini.
                        </p>
                    </div>
                    <div class="d-flex align-items-center justify-content-between">
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        @endif
    </div>

    </div>{{-- end notif-stack-outer --}}

    <style>
    /* ===== Stacked Card System ===== */
    #notif-stack-outer {
        display: grid;
        padding-bottom: 18px;   /* room for back card to peek */
        margin-bottom: 8px;
        overflow: visible;
    }
    #notif-stack-outer > .notif-card-slot {
        grid-column: 1;
        grid-row: 1;
        min-width: 0;
    }
    .notif-card-slot {
        transition:
            transform  0.45s cubic-bezier(.4,0,.2,1),
            opacity    0.45s cubic-bezier(.4,0,.2,1),
            box-shadow 0.45s cubic-bezier(.4,0,.2,1),
            filter     0.45s cubic-bezier(.4,0,.2,1);
        will-change: transform, opacity;
        transform-origin: top center;
    }

    /* ── Front card: lifted, full opacity ── */
    .notif-card-slot.is-front {
        transform: translateY(0) scale(1);
        opacity: 1;
        z-index: 2;
        cursor: default;
        pointer-events: all;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        filter: none;
    }

    /* ── Back card: peeking below, dimmed, slightly smaller ── */
    .notif-card-slot.is-back {
        transform: translateY(12px) scale(0.95);
        opacity: 0.5;
        z-index: 1;
        cursor: pointer;
        pointer-events: all;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
        filter: brightness(0.95);
    }
    .notif-card-slot.is-back:hover {
        opacity: 0.7;
        transform: translateY(16px) scale(0.96);
        filter: brightness(0.98);
    }
    .notif-card-slot.is-back .card {
        pointer-events: none; /* clicks go to wrapper */
    }

    /* During swap */
    .notif-card-slot.is-swapping {
        pointer-events: none;
    }
    </style>

    <script>
    var _currentCard = '{{ $showPriorityFirst ? "prioritas" : "terbaru" }}';
    var _isSwapping  = false;

    function showCard(which) {
        if (which === _currentCard || _isSwapping) return;
        _isSwapping = true;

        var p    = document.getElementById('notif-prioritas');
        var t    = document.getElementById('notif-terbaru');
        var btnP = document.getElementById('btn-show-prioritas');
        var btnT = document.getElementById('btn-show-terbaru');

        var front = which === 'prioritas' ? p : t;
        var back  = which === 'prioritas' ? t : p;

        // Lock during animation
        p.classList.add('is-swapping');
        t.classList.add('is-swapping');

        // Swap visual classes (CSS transition handles animation)
        front.classList.remove('is-back');  front.classList.add('is-front');
        back.classList.remove('is-front'); back.classList.add('is-back');

        // Swap z-index at mid-animation so depth looks correct
        setTimeout(function() {
            front.style.zIndex = '2';
            back.style.zIndex  = '1';
        }, 200);

        // Unlock after transition completes
        setTimeout(function() {
            p.classList.remove('is-swapping');
            t.classList.remove('is-swapping');
            _isSwapping = false;
        }, 450);

        // Update toggle buttons
        if (which === 'terbaru') {
            btnT.style.background = '#3b82f6'; btnT.style.color = 'white';
            btnT.style.border     = 'none';
            btnT.style.boxShadow  = '0 2px 8px rgba(59,130,246,0.3)';
            btnP.style.background = 'white';   btnP.style.color = '#ef4444';
            btnP.style.border     = '1.5px solid #ef4444';
            btnP.style.boxShadow  = 'none';
        } else {
            btnP.style.background = '#ef4444'; btnP.style.color = 'white';
            btnP.style.border     = 'none';
            btnP.style.boxShadow  = '0 2px 8px rgba(239,68,68,0.3)';
            btnT.style.background = 'white';   btnT.style.color = '#3b82f6';
            btnT.style.border     = '1.5px solid #3b82f6';
            btnT.style.boxShadow  = 'none';
        }

        _currentCard = which;
    }
    </script>

    {{-- Charts Section --}}
    <div class="row g-4 mt-2">
        <!-- Daily Trend Line Chart -->
        <div class="col-lg-8 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-chart-line text-primary me-2"></i>Tren Aduan Harian</h5>
                    <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small">7 Hari Terakhir</span>
                </div>
                <div class="card-body p-4">
                    <div style="position: relative; height: 220px;">
                        <canvas id="trendsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Platform Distribution Doughnut Chart -->
        <div class="col-lg-4 col-md-12">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="mb-0 fw-bold text-dark"><i class="fa-solid fa-circle-half-stroke text-primary me-2"></i>Distribusi Platform</h5>
                </div>
                <div class="card-body p-4 d-flex flex-column justify-content-center">
                    <div style="position: relative; height: 180px;" class="mb-3">
                        <canvas id="platformChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script ChartJS CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // --- Line Chart: Tren Harian ---
            const ctxTrends = document.getElementById('trendsChart').getContext('2d');

            const gradientBlue = ctxTrends.createLinearGradient(0, 0, 0, 300);
            gradientBlue.addColorStop(0, 'rgba(13, 71, 161, 0.25)');
            gradientBlue.addColorStop(1, 'rgba(13, 71, 161, 0.00)');

            new Chart(ctxTrends, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Jumlah Tiket Masuk',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#0d47a1',
                        borderWidth: 3,
                        backgroundColor: gradientBlue,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#0d47a1',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5,
                        pointHoverRadius: 7
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            padding: 12,
                            backgroundColor: '#071f49',
                            titleColor: '#fff',
                            bodyColor: '#cbd5e1',
                            usePointStyle: true,
                            boxPadding: 6
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { precision: 0, color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    }
                }
            });

            // --- Doughnut Chart: Distribusi Platform ---
            const ctxPlatform = document.getElementById('platformChart').getContext('2d');
            const platformData = {!! json_encode($platformStats) !!};

            const labels = Object.keys(platformData).map(k => {
                if(!k) return 'Manual';
                return k.charAt(0).toUpperCase() + k.slice(1);
            });
            const data = Object.values(platformData);

            const colorsMap = {
                'Facebook': '#1877F2',
                'Instagram': '#E1306C',
                'Whatsapp': '#25D366',
                'WhatsApp': '#25D366',
                'Manual': '#f57c00',
                'Web': '#f57c00'
            };
            const defaultColors = ['#0d47a1', '#071f49', '#64748b', '#8b5cf6'];
            const backgroundColors = labels.map((label, index) => colorsMap[label] || defaultColors[index % defaultColors.length]);

            new Chart(ctxPlatform, {
                type: 'doughnut',
                data: {
                    labels: labels.length > 0 ? labels : ['Belum Ada Data'],
                    datasets: [{
                        data: data.length > 0 ? data : [1],
                        backgroundColor: data.length > 0 ? backgroundColors : ['#e2e8f0'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: { boxWidth: 12, padding: 15, color: '#64748b', font: { family: 'Plus Jakarta Sans', size: 11 } }
                        },
                        tooltip: { padding: 12, backgroundColor: '#071f49', usePointStyle: true }
                    },
                    cutout: '75%'
                }
            });
        });
    </script>

    {{-- Real-time Polling Script --}}
    <script>
        let lastHighPriorityId = {{ $highPriorityNotification->id ?? 'null' }};
        let lastLatestId = {{ $latestNotification->id ?? 'null' }};
        let isPolling = false;

        // Polling setiap 10 detik
        setInterval(function() {
            if (isPolling) return;
            isPolling = true;

            fetch('{{ route('dashboard.poll') }}')
                .then(response => response.json())
                .then(data => {
                    // Update statistik badge
                    updateStats(data.stats);

                    // Cek apakah ada notifikasi baru
                    const highPriorityChanged = data.highPriorityNotification && 
                        (lastHighPriorityId === null || data.highPriorityNotification.id !== lastHighPriorityId);
                    
                    const latestChanged = data.latestNotification && 
                        (lastLatestId === null || data.latestNotification.id !== lastLatestId);

                    if (highPriorityChanged || latestChanged) {
                        // Update cards
                        if (highPriorityChanged && data.highPriorityNotification) {
                            updateHighPriorityCard(data.highPriorityNotification);
                            lastHighPriorityId = data.highPriorityNotification.id;
                        }
                        
                        if (latestChanged && data.latestNotification) {
                            updateLatestCard(data.latestNotification);
                            lastLatestId = data.latestNotification.id;
                        }

                        // Update tampilan card di depan jika perlu
                        if (data.showPriorityFirst !== (_currentCard === 'prioritas')) {
                            showCard(data.showPriorityFirst ? 'prioritas' : 'terbaru');
                        }

                        // Toast notification
                        showToast(highPriorityChanged, latestChanged);
                    }

                    isPolling = false;
                })
                .catch(error => {
                    console.error('Polling error:', error);
                    isPolling = false;
                });
        }, 10000); // 10 detik

        function updateStats(stats) {
            // Update badge angka di dashboard jika ada
            const badges = {
                'totalTickets': stats.totalTickets,
                'ticketsMenunggu': stats.ticketsMenunggu,
                'ticketsDisposisi': stats.ticketsDisposisi,
                'ticketsDiproses': stats.ticketsDiproses,
                'ticketsSelesai': stats.ticketsSelesai,
                'ticketsEskalasi': stats.ticketsEskalasi
            };
            // Implementasi update badge jika ada elemen yang perlu diupdate
        }

        function updateHighPriorityCard(notif) {
            const cardBody = document.querySelector('#notif-prioritas .card-body');
            if (!cardBody) return;

            const platform = getPlatformIcon(notif.title);
            const categoryBadge = (notif.suggested_category || notif.suggested_sub_category) 
                ? `<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 rounded-pill" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-tags me-1"></i>
                    ${notif.suggested_category || ''}${notif.suggested_category && notif.suggested_sub_category ? ' - ' : ''}${notif.suggested_sub_category || ''}
                   </span>`
                : '<span></span>';

            const permalink = notif.permalink 
                ? `<a href="/notification/${notif.id}/detail?url=${encodeURIComponent(notif.permalink)}" target="_blank" class="btn btn-danger btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat
                   </a>`
                : '';

            cardBody.innerHTML = `
                <div class="d-flex align-items-center gap-3 mb-3">
                    ${platform}
                    <div class="min-w-0">
                        <div class="fw-bold text-dark" style="font-size: 1rem;">${notif.sender_name}</div>
                        <small class="text-secondary" style="font-size: 0.82rem;"><i class="fa-regular fa-clock me-1"></i>${notif.created_at}</small>
                    </div>
                </div>
                <div class="rounded-3 p-3 border-start border-danger border-3 mb-3" style="background-color: #fef2f2;">
                    <p class="mb-0 text-dark" style="font-size: 0.92rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        "${notif.message}"
                    </p>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    ${categoryBadge}
                    ${permalink}
                </div>
            `;

            // Animasi blink
            const card = document.querySelector('#notif-prioritas .card');
            card.style.animation = 'blink 0.5s ease-in-out 3';
        }

        function updateLatestCard(notif) {
            const cardBody = document.querySelector('#notif-terbaru .card-body');
            if (!cardBody) return;

            const platform = getPlatformIcon(notif.title);
            const categoryBadge = (notif.suggested_category || notif.suggested_sub_category) 
                ? `<span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 rounded-pill" style="font-size: 0.78rem;">
                    <i class="fa-solid fa-tags me-1"></i>
                    ${notif.suggested_category || ''}${notif.suggested_category && notif.suggested_sub_category ? ' - ' : ''}${notif.suggested_sub_category || ''}
                   </span>`
                : '<span></span>';

            const permalink = notif.permalink 
                ? `<a href="/notification/${notif.id}/detail?url=${encodeURIComponent(notif.permalink)}" target="_blank" class="btn btn-primary btn-sm rounded-pill px-3 fw-semibold" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i> Lihat
                   </a>`
                : '';

            cardBody.innerHTML = `
                <div class="d-flex align-items-center gap-3 mb-3">
                    ${platform}
                    <div class="min-w-0">
                        <div class="fw-bold text-dark" style="font-size: 1rem;">${notif.sender_name}</div>
                        <small class="text-secondary" style="font-size: 0.82rem;"><i class="fa-regular fa-clock me-1"></i>${notif.created_at}</small>
                    </div>
                </div>
                <div class="rounded-3 p-3 border-start border-primary border-3 mb-3" style="background-color: #f8fafc;">
                    <p class="mb-0 text-dark" style="font-size: 0.92rem; line-height: 1.55; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        "${notif.message}"
                    </p>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                    ${categoryBadge}
                    ${permalink}
                </div>
            `;

            // Animasi blink
            const card = document.querySelector('#notif-terbaru .card');
            card.style.animation = 'blink 0.5s ease-in-out 3';
        }

        function getPlatformIcon(title) {
            if (title.includes('facebook')) {
                return `<div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: #1877F2;">
                    <i class="fa-brands fa-facebook-f" style="font-size: 1rem;"></i>
                </div>`;
            } else if (title.includes('instagram')) {
                return `<div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: linear-gradient(45deg, #f09433, #dc2743, #bc1888);">
                    <i class="fa-brands fa-instagram" style="font-size: 1rem;"></i>
                </div>`;
            } else if (title.includes('whatsapp')) {
                return `<div class="rounded-circle d-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 44px; height: 44px; background: #25D366;">
                    <i class="fa-brands fa-whatsapp" style="font-size: 1rem;"></i>
                </div>`;
            } else {
                return `<div class="rounded-circle d-flex align-items-center justify-content-center text-white bg-secondary flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-bell" style="font-size: 1rem;"></i>
                </div>`;
            }
        }

        function showToast(isHighPriority, isLatest) {
            const toast = document.createElement('div');
            toast.style.cssText = 'position: fixed; top: 80px; right: 20px; background: white; padding: 16px 20px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 9999; display: flex; align-items: center; gap: 12px; animation: slideInRight 0.3s ease-out;';
            
            const iconColor = isHighPriority ? '#ef4444' : '#3b82f6';
            const iconClass = isHighPriority ? 'fa-triangle-exclamation' : 'fa-bell';
            const text = isHighPriority ? 'Notifikasi Prioritas Tinggi Baru!' : 'Notifikasi Baru Masuk!';
            
            toast.innerHTML = `
                <i class="fa-solid ${iconClass}" style="color: ${iconColor}; font-size: 1.2rem;"></i>
                <span style="font-weight: 600; color: #1e293b;">${text}</span>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.animation = 'slideOutRight 0.3s ease-in';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>

    <style>
        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }
        @keyframes slideInRight {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOutRight {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(400px); opacity: 0; }
        }
    </style>

@endsection
