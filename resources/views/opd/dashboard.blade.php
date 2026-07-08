@extends('layouts.app')

@section('title', 'Dashboard OPD')
@section('page-title')
    <i class="fa-solid fa-chart-line text-primary me-2"></i> Dashboard OPD
@endsection

@section('content')
<style>
    .card-premium {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        transition: all 0.3s ease;
    }
    .card-premium:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
    }
    .icon-box {
        width: 56px;
        height: 56px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
</style>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl">
        <div class="card card-premium h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-info bg-opacity-10 text-info">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-2 fw-semibold">Menunggu Verifikasi</span>
                </div>
                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Diteruskan/Diterima</h6>
                <h3 class="mb-0 fw-bold text-dark" style="font-size: 2rem;">{{ $stats['diterima'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
        <div class="card card-premium h-100" style="border-left: 4px solid #F57C00;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box" style="background-color: rgba(245, 124, 0, 0.1); color: #F57C00;">
                        <i class="fas fa-clock"></i>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 fw-semibold" style="background-color: rgba(245, 124, 0, 0.1); color: #F57C00;">SLA Terlewati</span>
                </div>
                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Proses Disposisi</h6>
                <h3 class="mb-0 fw-bold" style="font-size: 2rem; color: #F57C00;">{{ $stats['proses_disposisi'] }}</h3>
            </div>
        </div>
    </div>
    
    <div class="col-12 col-sm-6 col-xl">
        <div class="card card-premium h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-spinner"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 fw-semibold text-dark">Sedang Ditangani</span>
                </div>
                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Diproses</h6>
                <h3 class="mb-0 fw-bold text-dark" style="font-size: 2rem;">{{ $stats['diproses'] }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl">
        <div class="card card-premium h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icon-box bg-success bg-opacity-10 text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">Selesai Ditangani</span>
                </div>
                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 0.8rem; letter-spacing: 0.5px;">Selesai</h6>
                <h3 class="mb-0 fw-bold text-dark" style="font-size: 2rem;">{{ $stats['selesai'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card card-premium overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3 px-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold" style="color: #1e293b;">
                    <i class="fas fa-clock me-2 text-primary"></i> Tiket Terbaru
                </h5>
                <a href="{{ route('opd.tickets.index') }}" class="btn btn-sm btn-light border shadow-sm rounded-pill px-4 fw-bold text-primary transition-all">
                    Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                            <tr class="text-center">
                                <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Nomor Tiket</th>
                                <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Tanggal/Waktu</th>
                                <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Aduan</th>
                                <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Pelapor</th>
                                <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Kategori</th>
                                <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Status</th>
                                <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTickets as $ticket)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td class="px-4 py-3"><span class="fw-bold text-primary">{{ $ticket->tracking_number ?? $ticket->ticket_number ?? '#' . $ticket->id }}</span></td>
                                    <td class="py-3 text-muted">
                                        <div class="fw-medium text-dark">{{ $ticket->created_at->format('d M Y') }}</div>
                                        <div class="small">{{ $ticket->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-truncate text-dark" style="max-width: 280px;" title="{{ $ticket->complaint }}">
                                            {{ Str::limit($ticket->complaint, 60) }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-medium text-dark">{{ $ticket->reporter_name ?? 'Anonim' }}</div>
                                        @if($ticket->reporter_link)
                                            <a href="{{ $ticket->reporter_link }}" target="_blank" class="text-decoration-none small text-primary fw-medium"><i class="fas fa-link"></i> Lihat Postingan</a>
                                        @endif
                                    </td>
                                    <td class="py-3">
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
                                    <td class="py-3">
                                        @php
                                            $badgeClass = 'bg-secondary text-white';
                                            if($ticket->status == 'diteruskan' || $ticket->status == 'diterima') $badgeClass = 'bg-info bg-opacity-10 text-info border border-info';
                                            elseif($ticket->status == 'proses_disposisi') $badgeClass = 'border border-warning text-dark'; 
                                            elseif($ticket->status == 'diproses') $badgeClass = 'bg-warning bg-opacity-10 text-warning border border-warning';
                                            elseif($ticket->status == 'dijawab') $badgeClass = 'bg-primary-subtle text-primary border border-primary';
                                            elseif($ticket->status == 'selesai') $badgeClass = 'bg-success bg-opacity-10 text-success border border-success';
                                            elseif($ticket->status == 'eskalasi') $badgeClass = 'bg-danger bg-opacity-10 text-danger border border-danger';
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2 fw-bold {{ $badgeClass }}" @if($ticket->status == 'proses_disposisi') style="background-color: rgba(245, 124, 0, 0.1); color: #F57C00 !important; border-color: #F57C00 !important;" @endif>{{ $ticket->status == 'proses_disposisi' ? 'Proses Disposisi' : ucfirst($ticket->status) }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-end">
                                        <a href="{{ route('opd.tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border shadow-sm text-primary rounded-pill px-3 fw-medium">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <div class="mb-3"><i class="fas fa-inbox fa-3x text-light"></i></div>
                                        Belum ada tiket terbaru yang diteruskan ke instansi Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
