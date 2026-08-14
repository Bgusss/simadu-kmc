@extends('layouts.app')

@section('title', 'Daftar Tiket')
@section('page-title')
    <i class="fa-solid fa-ticket text-primary me-2"></i> Daftar Tiket
@endsection

@section('content')
<style>
    .card-premium {
        border: none;
        border-radius: 16px;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
    }
</style>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark">
                    <i class="fa-solid fa-filter text-primary me-2"></i> Filter & Pencarian
                </h6>
                @if(request()->filled('search') || request()->filled('status') || request()->filled('platform') || request()->filled('category'))
                    <a href="{{ route('opd.tickets.index') }}" class="btn btn-sm btn-light border text-danger fw-semibold rounded-pill px-3 shadow-sm">
                        <i class="fa-solid fa-rotate-left me-1"></i> Reset Filter
                    </a>
                @endif
            </div>

            <form action="{{ route('opd.tickets.index') }}" method="GET" class="row g-3 align-items-center">
                <div class="col-lg-4 col-md-12">
                    <div class="position-relative d-flex align-items-center search-input-wrapper">
                        <i class="fa-solid fa-magnifying-glass position-absolute text-muted ms-3" style="z-index: 5;"></i>
                        <input type="text" name="search" class="form-control ps-5 pe-5 rounded-pill" placeholder="Cari tiket aduan..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('opd.tickets.index') }}" class="position-absolute end-0 text-muted me-3 text-decoration-none d-flex align-items-center" title="Clear Search" style="z-index: 5;">
                                <i class="fa-solid fa-circle-xmark fs-5"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-lg-2 col-md-4">
                    <select name="status" class="form-select rounded-pill" style="height: 42px; border-color: #cbd5e1; cursor: pointer;">
                        <option value="">Semua Status</option>
                        <option value="diteruskan" {{ request('status') == 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                        <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="dijawab" {{ request('status') == 'dijawab' ? 'selected' : '' }}>Dijawab</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="eskalasi" {{ request('status') == 'eskalasi' ? 'selected' : '' }}>Eskalasi</option>
                        <option value="proses_disposisi" {{ request('status') == 'proses_disposisi' ? 'selected' : '' }}>Proses Disposisi</option>
                    </select>
                </div>

                <div class="col-lg-2 col-md-4">
                    <select name="platform" class="form-select rounded-pill" style="height: 42px; border-color: #cbd5e1; cursor: pointer;">
                        <option value="">Semua Platform</option>
                        @foreach($platforms as $platform)
                            <option value="{{ $platform }}" {{ request('platform') == $platform ? 'selected' : '' }}>
                                {{ ucfirst($platform) }}
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

                <div class="col-lg-2 col-md-12">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-semibold" style="height: 42px;">
                        <i class="fa-solid fa-magnifying-glass me-2"></i> Cari
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card card-premium overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead style="background-color: #f8fafc; border-top: 1px solid #f1f5f9; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th class="px-4 py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">No. Tiket</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Aduan</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Pelapor</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Kategori</th>
                            <th class="py-3 text-uppercase text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Tanggal</th>
                            <th class="py-3 text-uppercase text-muted text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Status</th>
                            <th class="px-4 py-3 text-uppercase text-muted text-center" style="font-size: 0.75rem; letter-spacing: 0.5px; font-weight: 600;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td class="px-4 py-3 fw-bold text-primary">{{ $ticket->tracking_number ?? $ticket->ticket_number ?? '#' . $ticket->id }}</td>
                                <td class="py-3">
                                    <div class="text-truncate text-dark" style="max-width: 250px;" title="{{ $ticket->complaint }}">{{ Str::limit($ticket->complaint, 60) }}</div>
                                </td>
                                <td class="py-3">
                                    <div class="fw-medium text-dark">{{ $ticket->reporter_name ?? 'Anonim' }}</div>
                                    @if($ticket->reporter_link && strtolower($ticket->platform ?? '') !== 'whatsapp' && !str_contains($ticket->reporter_link, 'instagram.com/direct/'))
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
                                <td class="py-3 text-muted">
                                    <div class="fw-medium text-dark">{{ $ticket->created_at?->format('d M Y') }}</div>
                                    <div class="small">{{ $ticket->created_at?->format('H:i') }} WIB</div>
                                </td>
                                <td class="py-3 text-center">
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
                                <td class="px-4 py-3 text-center text-nowrap">
                                    <a href="{{ route('opd.tickets.show', $ticket->id) }}" class="btn btn-sm btn-light border shadow-sm text-primary rounded-pill px-3 fw-medium me-1" title="Lihat Detail">
                                        <i class="fas fa-eye me-1"></i> Detail
                                    </a>
                                    <a href="{{ route('opd.tickets.edit', $ticket->id) }}" class="btn btn-sm shadow-sm rounded-pill px-3 fw-medium text-white" style="background-color: #F57C00; border-color: #F57C00;" title="Berikan Tanggapan">
                                        <i class="fas fa-comment-dots me-1"></i> Berikan Tanggapan
                                    </a>
                                    <a href="{{ route('opd.chat.show', $ticket) }}" class="btn btn-sm btn-light border shadow-sm text-primary rounded-pill px-3 fw-medium ms-1" title="Live Chat dengan Admin KMC">
                                        <i class="fas fa-comments me-1"></i> Live Chat
                                    </a>
                                </td>
                            </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <div class="mb-3"><i class="fas fa-inbox fa-3x text-light"></i></div>
                                <h5>Belum Ada Tiket</h5>
                                <p>Belum ada tiket aduan yang masuk atau ditugaskan ke Instansi Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white py-3">
        {{ $tickets->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
