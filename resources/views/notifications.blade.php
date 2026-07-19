@extends('layouts.app')

@section('title', 'Daftar Notifikasi - SIMODU KMC')

@section('page-title')
    <i class="fa-solid fa-bell text-primary me-2"></i> Daftar Notifikasi
@endsection

@section('content')

    <style>
        /* ── List Row Container ── */
        #notification-container {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            max-height: 57vh;
            overflow-y: auto;
        }

        /* ── Individual Notif Row ── */
        .notif-row {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 12px 20px;
            border-bottom: 1px solid #e2e8f0;
            background: white;
            position: relative;
            transition: background 0.15s ease;
        }
        .notif-row:last-child {
            border-bottom: none;
        }
        .notif-row:hover {
            background: #f8fafc;
        }

        /* Blue indicator left edge for unread */
        .notif-row.unread {
            border-left: 3.5px solid var(--kmc-blue);
            padding-left: 16.5px; /* compensate for left border */
        }

        /* Kolom Kiri: Avatar */
        .notif-left-col {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .platform-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .platform-avatar.instagram {
            background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%) !important;
        }
        .platform-avatar.facebook {
            background: #1877F2 !important;
        }
        .unread-dot {
            position: absolute; 
            top: -1px; 
            right: -1px;
            width: 11px; 
            height: 11px;
            background: #ef4444; 
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Kolom Tengah: Content */
        .notif-mid-col {
            flex: 1;
            min-width: 0;
        }
        .sender-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1e293b;
        }
        .platform-badge {
            font-size: 0.72rem;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 50px;
            white-space: nowrap;
        }
        .priority-badge-pill {
            font-size: 0.72rem;
            font-weight: 700;
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fecaca;
            padding: 1px 7px;
            border-radius: 50px;
        }
        .message-text {
            font-size: 0.875rem;
            color: #475569;
            line-height: 1.5;
            margin-top: 4px;
        }

        /* Category tags */
        .category-tag {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 2px 10px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .subcategory-tag {
            font-size: 0.75rem;
            font-weight: 500;
            padding: 2px 10px;
            border-radius: 6px;
            background: #f0fdf4;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        /* Duplicate bar inline */
        .dup-inline-bar {
            display: inline-flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            font-size: 0.7rem;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 2px 8px;
            border-radius: 6px;
            color: #d97706;
        }
        .btn-dup-action {
            font-size: 0.65rem;
            font-weight: 600;
            padding: 1px 6px;
            border-radius: 4px;
            border: 1px solid;
            cursor: pointer;
            background: white;
        }
        .btn-dup-ok {
            color: #16a34a;
            border-color: #bbf7d0;
        }
        .btn-dup-ok:hover {
            background: #16a34a;
            color: white;
            border-color: #16a34a;
        }
        .btn-dup-no {
            color: #dc2626;
            border-color: #fecaca;
        }
        .btn-dup-no:hover {
            background: #dc2626;
            color: white;
            border-color: #dc2626;
        }

        /* Kolom Kanan: Meta & Action */
        .notif-right-col {
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 240px;
            justify-content: flex-end;
        }
        .notif-time-text {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
            white-space: nowrap;
        }
        .status-badge {
            font-size: 0.75rem;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 50px;
            white-space: nowrap;
        }
        .status-badge-new {
            background: #fee2e2;
            color: #b91c1c;
        }
        .status-badge-read {
            background: #dcfce7;
            color: #15803d;
        }

        /* Webkit scrollbar for container */
        #notification-container::-webkit-scrollbar {
            width: 6px;
        }
        #notification-container::-webkit-scrollbar-track {
            background: transparent;
        }
        #notification-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        #notification-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ── Pagination Styling ── */
        .pagination {
            margin-bottom: 0;
            margin-top: 10px;
        }
        .pagination .page-link {
            border-radius: 8px !important;
            margin: 0 2px;
            color: var(--kmc-blue);
            min-width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            font-weight: 600;
        }
        .pagination .active .page-link {
            background-color: var(--kmc-blue);
            border-color: var(--kmc-blue);
            color: white;
        }
        .pagination .page-link:focus {
            box-shadow: none;
        }

        /* Form Controls */
        .form-control, .form-select {
            border: 1px solid #cbd5e1 !important;
            transition: all 0.15s ease-in-out;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--kmc-blue) !important;
            box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1) !important;
        }
    </style>

    {{-- Search & Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-3">
        <div class="card-body px-3 py-2">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.85rem;">
                    <i class="fa-solid fa-sliders me-2 text-primary"></i>Filter & Pencarian
                </h6>
                @if(request()->filled('search') || request()->filled('type') || request()->filled('status') || request()->filled('duplicate'))
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light border text-danger fw-semibold rounded-pill px-3 shadow-sm" style="font-size: 0.75rem;">
                        <i class="fa-solid fa-rotate-left me-1"></i>Reset
                    </a>
                @endif
            </div>

            <form method="GET" action="{{ route('notifications.index') }}">

                {{-- Baris 1: Search --}}
                <div class="position-relative mb-2">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="z-index: 5; left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                    <input type="text" name="search" class="form-control rounded-pill ps-5 pe-5"
                        style="height: 36px; font-size: 0.825rem; border-color: #cbd5e1; background: #f8fafc;"
                        placeholder="Cari nama pengirim, isi aduan, atau kata kunci..."
                        value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('notifications.index') }}" class="position-absolute text-muted text-decoration-none d-flex align-items-center" style="z-index: 5; right: 14px; top: 50%; transform: translateY(-50%);" title="Hapus Pencarian">
                            <i class="fa-solid fa-circle-xmark"></i>
                        </a>
                    @endif
                </div>

                {{-- Baris 2: Filter Dropdowns --}}
                <div class="row g-2 align-items-center">
                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-layer-group position-absolute text-muted" style="z-index: 5; left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.75rem;"></i>
                            <select name="type" class="form-select ps-5 rounded-pill" style="height: 36px; border-color: #cbd5e1; font-size: 0.78rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Jenis</option>
                                <option value="Facebook Mention" {{ request('type') == 'Facebook Mention' ? 'selected' : '' }}>Facebook Post</option>
                                <option value="Facebook Comment Mention" {{ request('type') == 'Facebook Comment Mention' ? 'selected' : '' }}>Facebook Komentar</option>
                                <option value="Instagram DM" {{ request('type') == 'Instagram DM' ? 'selected' : '' }}>DM Instagram</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-envelope-open-text position-absolute text-muted" style="z-index: 5; left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.75rem;"></i>
                            <select name="status" class="form-select ps-5 rounded-pill" style="height: 36px; border-color: #cbd5e1; font-size: 0.78rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Status</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Belum Dibaca</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Sudah Dibaca</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-copy position-absolute text-muted" style="z-index: 5; left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.75rem;"></i>
                            <select name="duplicate" class="form-select ps-5 rounded-pill" style="height: 36px; border-color: #cbd5e1; font-size: 0.78rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Duplikasi</option>
                                <option value="terdeteksi" {{ request('duplicate') === 'terdeteksi' ? 'selected' : '' }}>Perlu Verifikasi</option>
                                <option value="bukan_duplikat" {{ request('duplicate') === 'bukan_duplikat' ? 'selected' : '' }}>Bukan Duplikat</option>
                                <option value="dikonfirmasi_duplikat" {{ request('duplicate') === 'dikonfirmasi_duplikat' ? 'selected' : '' }}>Dikonfirmasi Duplikat</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-auto col-md-12 col-6">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm" style="height: 36px; font-size: 0.78rem;">
                            <i class="fa-solid fa-magnifying-glass me-1"></i>Terapkan
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    {{-- Notifikasi --}}
    <div id="notification-container">

        @include('notifications.partials.list')

    </div>

    {{-- Pagination --}}
    @if ($notifications->hasPages())
        <div class="d-flex justify-content-center mt-4">

            {{ $notifications->withQueryString()->links() }}

        </div>
    @endif

    <script>
        function refreshNotifications() {

            fetch(
                    "{{ route('notifications.partial') }}?" +
                    new URLSearchParams(
                        window.location.search
                    )
                )

                .then(response => response.text())

                .then(html => {

                    document.getElementById(
                        'notification-container'
                    ).innerHTML = html;

                });

        }

        /*
         * Refresh tiap 5 detik
         * tanpa reload halaman
         */
        setInterval(
            refreshNotifications,
            5000
        );
    </script>

@endsection
