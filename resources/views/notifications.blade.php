@extends('layouts.app')

@section('title', 'Daftar Notifikasi - SIMADU KMC')

@section('page-title')
    <i class="fa-solid fa-bell text-primary me-2"></i> Daftar Notifikasi
@endsection

@section('content')

    <style>
        .notification-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .08);
            transition: .2s;
        }

        .notification-card:hover {
            transform: translateY(-2px);
        }

        .facebook-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #1877F2;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
        }

        .platform-badge {
            background: #E7F0FF;
            color: #2F5FE3;
            font-size: 12px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 10px;
        }

        .message-box {
            background: #F3F5F8;
            border-radius: 16px;
            padding: 22px;
            font-size: 16px;
            line-height: 1.7;
        }

        .priority-badge {
            border: 1px solid #ef4444;
            color: #dc2626;
            background: #fef2f2;
            border-radius: 20px;
            padding: 6px 16px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-new {
            background: #EAF8EE;
            color: #28A745;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .status-unread {
            background: #FFE5E5;
            color: #D9534F;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .status-read {
            background: #EAF8EE;
            color: #198754;
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .category-badge {
            background: #EEF2F7;
            color: #6C757D;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
        }

        .subcategory-badge {
            background: #EAF7EC;
            color: #4F9B58;
            border-radius: 10px;
            padding: 6px 14px;
            font-size: 13px;
        }

        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 12px !important;
            margin: 0 3px;
            color: var(--kmc-blue);
            min-width: 42px;
            text-align: center;
        }

        .pagination .active .page-link {
            background-color: var(--kmc-blue);
            border-color: var(--kmc-blue);
            color: white;
        }

        .pagination .page-link:focus {
            box-shadow: none;
        }

        /* Search input focus */
        .form-control:focus, .form-select:focus {
            border-color: var(--kmc-blue) !important;
            box-shadow: 0 0 0 0.2rem rgba(13, 71, 161, 0.1) !important;
        }
    </style>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-5 me-2"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Search & Filter --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body px-4 py-3">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">
                    <i class="fa-solid fa-sliders me-2 text-primary"></i>Filter & Pencarian
                </h6>
                @if(request()->filled('search') || request()->filled('type') || request()->filled('status') || request()->filled('duplicate'))
                    <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-light border text-danger fw-semibold rounded-pill px-3 shadow-sm" style="font-size: 0.8rem;">
                        <i class="fa-solid fa-rotate-left me-1"></i>Reset
                    </a>
                @endif
            </div>

            <form method="GET" action="{{ route('notifications.index') }}">

                {{-- Baris 1: Search --}}
                <div class="position-relative mb-3">
                    <i class="fa-solid fa-magnifying-glass position-absolute text-muted" style="z-index: 5; left: 16px; top: 50%; transform: translateY(-50%);"></i>
                    <input type="text" name="search" class="form-control rounded-pill ps-5 pe-5"
                        style="height: 44px; border-color: #e2e8f0; background: #f8fafc;"
                        placeholder="Cari nama pengirim, isi aduan, atau kata kunci..."
                        value="{{ request('search') }}">
                    @if(request('search'))
                        <a href="{{ route('notifications.index') }}" class="position-absolute text-muted text-decoration-none d-flex align-items-center" style="z-index: 5; right: 16px; top: 50%; transform: translateY(-50%);" title="Hapus Pencarian">
                            <i class="fa-solid fa-circle-xmark fs-5"></i>
                        </a>
                    @endif
                </div>

                {{-- Baris 2: Filter Dropdowns --}}
                <div class="row g-2 align-items-center">
                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-layer-group position-absolute text-muted" style="z-index: 5; left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                            <select name="type" class="form-select ps-5 rounded-pill" style="height: 40px; border-color: #e2e8f0; font-size: 0.85rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Jenis</option>
                                <option value="Facebook Mention" {{ request('type') == 'Facebook Mention' ? 'selected' : '' }}>Facebook Post</option>
                                <option value="Facebook Comment Mention" {{ request('type') == 'Facebook Comment Mention' ? 'selected' : '' }}>Facebook Komentar</option>
                                <option value="Instagram DM" {{ request('type') == 'Instagram DM' ? 'selected' : '' }}>DM Instagram</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-envelope-open-text position-absolute text-muted" style="z-index: 5; left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                            <select name="status" class="form-select ps-5 rounded-pill" style="height: 40px; border-color: #e2e8f0; font-size: 0.85rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Status</option>
                                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Belum Dibaca</option>
                                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Sudah Dibaca</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg col-md-4 col-6">
                        <div class="position-relative">
                            <i class="fa-solid fa-copy position-absolute text-muted" style="z-index: 5; left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.8rem;"></i>
                            <select name="duplicate" class="form-select ps-5 rounded-pill" style="height: 40px; border-color: #e2e8f0; font-size: 0.85rem; cursor: pointer; background-color: #f8fafc;">
                                <option value="">Semua Duplikasi</option>
                                <option value="terdeteksi" {{ request('duplicate') === 'terdeteksi' ? 'selected' : '' }}>Perlu Verifikasi</option>
                                <option value="bukan_duplikat" {{ request('duplicate') === 'bukan_duplikat' ? 'selected' : '' }}>Bukan Duplikat</option>
                                <option value="dikonfirmasi_duplikat" {{ request('duplicate') === 'dikonfirmasi_duplikat' ? 'selected' : '' }}>Dikonfirmasi Duplikat</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-lg-auto col-md-12 col-6">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold shadow-sm" style="height: 40px; font-size: 0.85rem;">
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
