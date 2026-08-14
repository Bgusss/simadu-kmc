@extends('layouts.app')

@section('title', 'Daftar Tiket - SIMODU KMC')
@section('page-title')
    <i class="fa-solid fa-ticket text-primary me-2"></i> Daftar Tiket
@endsection

@section('content')

    <style>
        .pagination {
            margin-bottom: 0;
        }

        .pagination .page-link {
            border-radius: 12px !important;
            margin: 0 3px;
            min-width: 42px;
            text-align: center;
        }

        .pagination .active .page-link {
            background: var(--kmc-blue);
            border-color: var(--kmc-blue);
        }

        .pagination .page-link:focus {
            box-shadow: none;
        }

        .ticket-table td {
            vertical-align: middle;
        }

        /* Focus expand transition for search input */
        .search-input-wrapper input {
            width: 100%;
            height: 42px;
            border-color: #cbd5e1;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        @media (min-width: 992px) {
            .search-input-wrapper input {
                width: 320px;
            }
            .search-input-wrapper input:focus {
                width: 400px;
                border-color: var(--kmc-blue) !important;
                box-shadow: 0 0 0 0.25rem rgba(13, 71, 161, 0.15) !important;
            }
        }
        .table-hover tbody tr {
            transition: all 0.2s ease-in-out;
        }
        .table-hover tbody tr:hover {
            background-color: #f8fafc;
        }

        .ticket-priority-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.6rem;
            border: 1.5px solid;
            border-radius: 8px;
            background-color: #ffffff;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.02em;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease-in-out;
        }

        .ticket-priority-badge:hover {
            transform: translateY(-1px);
            box-shadow: 0 3px 5px rgba(0, 0, 0, 0.08);
            filter: brightness(0.98);
        }

        .ticket-priority-low {
            border-color: #3b82f6;
            color: #1d4ed8;
            background-color: rgba(59, 130, 246, 0.02);
        }

        .ticket-priority-medium {
            border-color: #eab308;
            color: #a16207;
            background-color: rgba(234, 179, 8, 0.02);
        }

        .ticket-priority-high {
            border-color: #ef4444;
            color: #b91c1c;
            background-color: rgba(239, 68, 68, 0.02);
        }

        .priority-legend {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem 1.25rem;
            margin-top: 1rem;
            padding: 0.9rem 1rem;
            border: 1.5px dashed #e2e8f0;
            border-radius: 14px;
            background: #f8fafc;
        }

        .priority-legend-title {
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
        }

        .priority-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
        }

        .priority-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 4px;
            border: 1.5px solid transparent;
        }

        .priority-legend-low { border-color: #3b82f6; background-color: rgba(59, 130, 246, 0.1); }
        .priority-legend-medium { border-color: #eab308; background-color: rgba(234, 179, 8, 0.1); }
        .priority-legend-high { border-color: #ef4444; background-color: rgba(239, 68, 68, 0.1); }
    </style>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            {{-- Search & Filter --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark">
                            <i class="fa-solid fa-filter text-primary me-2"></i> Filter & Pencarian
                        </h6>
                        @if(request()->filled('search') || request()->filled('platform') || request()->filled('category') || request()->filled('opd'))
                            <a href="{{ route('tickets.index') }}" class="btn btn-sm btn-light border text-danger fw-semibold rounded-pill px-3 shadow-sm">
                                <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                            </a>
                        @endif
                    </div>

                    <form method="GET" action="{{ route('tickets.index') }}" class="row g-3 align-items-center">
                        <div class="col-lg-3 col-md-12">
                            <div class="position-relative d-flex align-items-center search-input-wrapper">
                                <i class="fa-solid fa-magnifying-glass position-absolute text-muted ms-3" style="z-index: 5;"></i>
                                <input type="text" name="search" class="form-control ps-5 pe-5 rounded-pill" placeholder="Cari nomor tiket, pelapor, aduan..." value="{{ request('search') }}">
                                @if(request('search'))
                                    <a href="{{ route('tickets.index') }}" class="position-absolute end-0 text-muted me-3 text-decoration-none d-flex align-items-center" title="Clear Search" style="z-index: 5;">
                                        <i class="fa-solid fa-circle-xmark fs-5"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <select name="platform" class="form-select rounded-pill" style="height: 42px; border-color: #cbd5e1; cursor: pointer;">
                                <option value="">Semua Platform</option>
                                @foreach($platforms as $platform)
                                    <option value="{{ $platform }}" {{ request('platform') == $platform ? 'selected' : '' }}>
                                        {{ $platform }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <select name="category" class="form-select rounded-pill" style="height: 42px; border-color: #cbd5e1; cursor: pointer;">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <select name="opd" class="form-select rounded-pill" style="height: 42px; border-color: #cbd5e1; cursor: pointer;">
                                <option value="">Semua OPD</option>
                                @foreach($opds as $opd)
                                    <option value="{{ $opd }}" {{ request('opd') == $opd ? 'selected' : '' }}>
                                        {{ $opd }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-3 col-md-12">
                            <div class="d-flex gap-2">
                                <button class="btn btn-primary rounded-pill fw-semibold flex-fill d-inline-flex align-items-center justify-content-center gap-2" style="height: 42px;">
                                    <i class="fa-solid fa-magnifying-glass"></i> Cari
                                </button>
                                <a href="{{ route('tickets.create') }}" class="btn btn-outline-success rounded-pill fw-bold flex-fill d-inline-flex align-items-center justify-content-center gap-2" style="border-width: 2px; height: 42px; transition: all 0.2s ease-in-out;">
                                    <i class="fa-solid fa-plus-circle"></i> Tambah
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle ticket-table">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 180px;">Nomor Tiket</th>
                            <th style="width: 150px;">Waktu</th>
                            <th class="text-center" style="width: 90px;">Platform</th>
                            <th>Pelapor</th>
                            <th>Kategori / Sub</th>
                            <th>OPD Terkait</th>
                            <th>Aduan</th>
                            <th class="text-center" style="width: 120px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr>
                                <td>
                                    @php
                                        $priority = strtolower($ticket->priority ?? 'sedang');
                                        $priorityBadgeClass = match ($priority) {
                                            'tinggi' => 'ticket-priority-high',
                                            'rendah' => 'ticket-priority-low',
                                            default => 'ticket-priority-medium',
                                        };
                                        $priorityLabel = ucfirst($priority);
                                    @endphp
                                    <span class="ticket-priority-badge {{ $priorityBadgeClass }} font-monospace" title="Prioritas {{ $priorityLabel }}" data-bs-toggle="tooltip">
                                        {{ $ticket->ticket_number }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $ticket->ticket_time ? $ticket->ticket_time->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @php
                                        $platformLower = strtolower($ticket->platform ?? '');
                                    @endphp
                                    @if($platformLower == 'facebook')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" style="width: 32px; height: 32px; background-color: #1877F2;" title="Facebook" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </span>
                                    @elseif($platformLower == 'instagram')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" style="width: 32px; height: 32px; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%);" title="Instagram" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-instagram"></i>
                                        </span>
                                    @elseif($platformLower == 'whatsapp')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm" style="width: 32px; height: 32px; background-color: #25D366;" title="WhatsApp" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </span>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm bg-secondary" style="width: 32px; height: 32px;" title="{{ $ticket->platform }}" data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-globe"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold">
                                        {{ $ticket->reporter_name ?? '-' }}
                                    </div>
                                    @if($ticket->reporter_link && strtolower($ticket->platform ?? '') !== 'whatsapp')
                                        <a href="{{ $ticket->reporter_link }}"
                                           target="_blank"
                                           class="text-decoration-none small text-truncate d-inline-block"
                                           style="max-width: 150px;">
                                            <i class="fa-solid fa-link me-1"></i>
                                            Lihat Postingan
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    @if($ticket->category)
                                        <div class="mb-1">
                                            <span class="badge-category">
                                                {{ $ticket->category }}
                                            </span>
                                        </div>
                                    @endif
                                    @if($ticket->sub_category)
                                        <div>
                                            <span class="badge-subcategory">
                                                {{ $ticket->sub_category }}
                                            </span>
                                        </div>
                                    @endif
                                    @if(!$ticket->category && !$ticket->sub_category)
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded px-2 py-1">
                                        {{ $ticket->opd_related ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="text-truncate"
                                         style="max-width: 250px;"
                                         title="{{ $ticket->complaint }}">
                                        {{ $ticket->complaint ?? '-' }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border text-primary" title="Detail Tiket">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn btn-sm btn-light border text-warning" title="Edit Tiket">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($ticket->assigned_opd_id)
                                            <a href="{{ route('tickets.chat.show', $ticket) }}" class="btn btn-sm btn-light border text-primary" title="Live Chat dengan OPD">
                                                <i class="fas fa-comments"></i>
                                            </a>
                                        @endif
                                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" class="d-inline" onsubmit="return confirmAction(event, { title: 'Hapus Tiket?', text: 'Apakah Anda yakin ingin menghapus tiket ini?', confirmButtonText: 'Ya, Hapus!' });">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-light border text-danger" title="Hapus Tiket">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-inbox fs-1 mb-3 d-block text-secondary"></i>
                                    Belum ada tiket yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="priority-legend" role="note" aria-label="Keterangan warna prioritas tiket">
                <span class="priority-legend-title">
                    <i class="fa-solid fa-circle-info me-1"></i> Keterangan warna nomor tiket:
                </span>
                <span class="priority-legend-item">
                    <span class="priority-legend-dot priority-legend-low"></span> Biru: Prioritas Rendah
                </span>
                <span class="priority-legend-item">
                    <span class="priority-legend-dot priority-legend-medium"></span> Kuning: Prioritas Sedang
                </span>
                <span class="priority-legend-item">
                    <span class="priority-legend-dot priority-legend-high"></span> Merah: Prioritas Tinggi
                </span>
            </div>

            @if($tickets->hasPages())
                <div class="mt-4">
                    {{ $tickets->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

@endsection