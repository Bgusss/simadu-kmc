@extends('public.layouts.app')

@section('title', 'Dashboard Monitoring Publik - SIMADU KMC')

@push('styles')
<style>
    body { background-color: #f8fafc; }
    
    /* Premium Scorecards */
    .scorecard { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 15px -3px rgba(13, 71, 161, 0.05), 0 4px 6px -2px rgba(13, 71, 161, 0.02); 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
        position: relative;
    }
    .scorecard:hover { 
        transform: translateY(-4px); 
        box-shadow: 0 20px 25px -5px rgba(13, 71, 161, 0.1), 0 10px 10px -5px rgba(13, 71, 161, 0.04); 
    }
    .scorecard-title { 
        font-size: 0.85rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        opacity: 0.85; 
    }
    .scorecard-value { 
        font-size: 2.4rem; 
        font-weight: 800; 
        line-height: 1.1; 
    }
    
    /* Primary Gradient Scorecards */
    .bg-gradient-primary { 
        background: linear-gradient(135deg, var(--kmc-blue-dark) 0%, var(--kmc-blue) 100%); 
        color: white; 
        border-bottom: 4px solid var(--kmc-orange);
    }
    .bg-gradient-success { 
        background: linear-gradient(135deg, #065f46 0%, #10b981 100%); 
        color: white; 
        border-bottom: 4px solid #34d399;
    }
    .bg-gradient-danger { 
        background: linear-gradient(135deg, #991b1b 0%, #ef4444 100%); 
        color: white; 
        border-bottom: 4px solid #f87171;
    }
    
    /* Secondary Scorecards (White with left border) */
    .card-stat { 
        border: none; 
        border-radius: 16px; 
        border-left: 6px solid transparent; 
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02); 
        transition: all 0.25s ease-in-out;
        background-color: white;
    }
    .card-stat:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
    }
    .card-stat-baru { border-left-color: var(--kmc-blue); background-color: rgba(13, 71, 161, 0.01); }
    .card-stat-disposisi { border-left-color: var(--kmc-orange); background-color: rgba(245, 124, 0, 0.01); }
    .card-stat-proses { border-left-color: #0891b2; background-color: rgba(8, 145, 178, 0.01); }
    .card-stat-selesai { border-left-color: #10b981; background-color: rgba(16, 185, 129, 0.01); }
    
    .card-stat .scorecard-title { color: #64748b; font-size: 0.8rem; }
    .card-stat .scorecard-value { color: #1e293b; font-size: 2rem; }

    /* Custom Tables */
    .card-custom { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02); 
        overflow: hidden;
    }
    .card-header-custom { 
        background-color: white; 
        border-bottom: 1px solid #e2e8f0; 
        padding: 1.5rem 1.75rem; 
    }
    
    .table-kmc {
        border-collapse: separate;
        border-spacing: 0;
    }
    .table-kmc thead th { 
        background-color: var(--kmc-blue-dark); 
        color: white; 
        border: none; 
        font-size: 0.8rem; 
        font-weight: 700; 
        text-transform: uppercase; 
        letter-spacing: 1px; 
        padding: 1.1rem 1rem;
    }
    .table-kmc thead th:first-child {
        border-top-left-radius: 12px;
    }
    .table-kmc thead th:last-child {
        border-top-right-radius: 12px;
    }
    .table-kmc tbody td { 
        font-size: 0.9rem; 
        vertical-align: middle; 
        padding: 1.1rem 1rem; 
        border-bottom: 1px solid #f1f5f9; 
    }
    .table-hover tbody tr {
        transition: background-color 0.2s ease-in-out;
    }
    .table-hover tbody tr:hover { 
        background-color: #f8fafc; 
    }
    
    /* SLA Status Badges */
    .status-out-sla { 
        background-color: rgba(239, 68, 68, 0.12); 
        color: #dc2626; 
        font-weight: 700; 
        border-radius: 8px; 
        padding: 6px 14px; 
        display: inline-block; 
        font-size: 0.75rem; 
        border: 1px solid rgba(239, 68, 68, 0.25); 
        letter-spacing: 0.5px;
    }
    .status-in-sla { 
        background-color: rgba(16, 185, 129, 0.12); 
        color: #15803d; 
        font-weight: 700; 
        border-radius: 8px; 
        padding: 6px 14px; 
        display: inline-block; 
        font-size: 0.75rem; 
        border: 1px solid rgba(16, 185, 129, 0.25); 
        letter-spacing: 0.5px;
    }
    .status-neutral { 
        background-color: #f1f5f9; 
        color: #64748b; 
        font-weight: 600; 
        border-radius: 8px; 
        padding: 6px 14px; 
        display: inline-block; 
        font-size: 0.75rem; 
    }

    /* Hero Search Pill Layout */
    .hero-search-wrapper {
        border-radius: 50px;
        background: white;
        padding: 4px;
        border: 2px solid rgba(255, 255, 255, 0.15);
        display: flex;
        align-items: center;
        width: 100%;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .hero-search-input {
        border: none !important;
        background-color: transparent !important;
        padding: 0.75rem 1.5rem;
        font-size: 1.05rem;
        font-weight: 500;
        color: #334155;
        flex-grow: 1;
        outline: none !important;
        width: 100%;
        box-shadow: none !important;
    }
    .hero-search-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    .hero-search-btn {
        background-color: var(--kmc-orange);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .hero-search-btn:hover {
        background-color: var(--kmc-orange-hover);
        color: white;
    }
    
    /* Table Filters & Free Search Layouts */
    .table-filter-wrapper {
        border-radius: 50px;
        background: white;
        border: 1px solid #cbd5e1;
        padding: 3px 6px;
        display: flex;
        align-items: center;
        min-width: 180px;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .table-filter-wrapper:focus-within {
        border-color: var(--kmc-blue);
        box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
    }
    .table-filter-select {
        border: none !important;
        background-color: transparent !important;
        padding-top: 0.25rem;
        padding-bottom: 0.25rem;
        padding-left: 0.5rem;
        font-size: 0.85rem;
        color: #334155;
        flex-grow: 1;
        outline: none !important;
        cursor: pointer;
        box-shadow: none !important;
        height: 32px;
    }
    .table-filter-select:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .table-search-wrapper {
        border-radius: 50px;
        background: white;
        border: 1px solid #cbd5e1;
        padding: 3px;
        display: flex;
        align-items: center;
        width: 100%;
        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .table-search-wrapper:focus-within {
        border-color: var(--kmc-blue);
        box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.1);
    }
    .table-search-input {
        border: none !important;
        background-color: transparent !important;
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
        color: #334155;
        flex-grow: 1;
        outline: none !important;
        width: 100%;
        box-shadow: none !important;
    }
    .table-search-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    .table-search-btn {
        background-color: var(--kmc-orange);
        color: white;
        border: none;
        font-weight: 700;
        border-radius: 50px;
        padding: 0.375rem 1.25rem;
        font-size: 0.8rem;
        transition: all 0.2s ease-in-out;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .table-search-btn:hover {
        background-color: var(--kmc-orange-hover);
        color: white;
    }
    
    /* Responsive Adjustments */
    @media (max-width: 576px) {
        .hero-search-wrapper {
            padding: 3px;
        }
        .hero-search-input {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .hero-search-btn {
            padding: 0.5rem 1.25rem;
            font-size: 0.85rem;
        }
        .table-filter-wrapper {
            min-width: 100%;
        }
        .table-search-input {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        .table-search-btn {
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="container pt-4 pb-5">

    <div class="tab-content" id="pills-tabContent">
        
        <!-- TAB 1: Lacak Laporan -->
        <div class="tab-pane fade show active" id="pills-lacak" role="tabpanel" aria-labelledby="pills-lacak-tab">
            <!-- Hero Search Section (Most User Friendly) -->
    <div class="card border-0 shadow rounded-4 mb-4" style="background: linear-gradient(135deg, var(--kmc-blue-dark) 0%, #0a3d8c 50%, var(--kmc-blue-dark) 100%); color: white; border-bottom: 5px solid var(--kmc-orange) !important; overflow: hidden; position: relative;">
        <!-- Glowing highlight layer -->
        <div style="position: absolute; top: -100px; right: -100px; width: 300px; height: 300px; background: radial-gradient(circle, rgba(245, 124, 0, 0.15) 0%, rgba(245, 124, 0, 0) 70%); pointer-events: none;"></div>
        <div style="position: absolute; bottom: -80px; left: -80px; width: 250px; height: 250px; background: radial-gradient(circle, rgba(13, 71, 161, 0.4) 0%, rgba(13, 71, 161, 0) 70%); pointer-events: none;"></div>
        
        <div class="card-body p-4 p-md-5 text-center position-relative" style="z-index: 2;">
            <h2 class="fw-bold mb-3"><i class="fa-solid fa-magnifying-glass me-2" style="color: var(--kmc-orange);"></i> Lacak Status Laporan Anda</h2>
            <p class="lead mb-4 opacity-90" style="font-size: 1.05rem; letter-spacing: 0.2px;">Masukkan Nomor Tiket yang Anda dapatkan saat melapor untuk mengetahui status penanganan terkini.</p>
            
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <form action="{{ route('ticketing.index') }}" method="GET">
                        <div class="hero-search-wrapper">
                            <input type="text" name="track_id" class="hero-search-input" placeholder="Contoh: KMC-12345678-0000" required value="{{ request('track_id') }}">
                            <button class="hero-search-btn" type="submit">Lacak Laporan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> <!-- End of Hero Search -->

    <!-- Ticket Search & Details Table -->
    <div class="card card-custom mb-5" id="data-laporan">
        <div class="card-header-custom p-4 border-bottom">
            <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center mb-3">
                <h5 class="fw-bold mb-0 text-dark" style="font-size: 1.1rem;"><i class="fas fa-list-check text-primary me-2"></i> Data Laporan Detail</h5>
            </div>
            
            <div class="p-3 bg-light rounded-4 border" style="border-color: #e2e8f0 !important; background-color: #f8fafc !important;">
                <form action="{{ route('ticketing.index') }}#data-laporan" method="GET" class="row g-3 align-items-end">
                    <div class="col-12 col-md-3 col-lg-auto">
                        <label class="text-secondary fw-bold small mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Filter Status</label>
                        <div class="table-filter-wrapper">
                            <span class="ps-2 text-muted"><i class="fas fa-filter text-primary"></i></span>
                            <select name="status" class="form-select table-filter-select" onchange="this.form.submit()">
                                <option value="">Semua Status</option>
                                <option value="diteruskan" {{ request('status') == 'diteruskan' ? 'selected' : '' }}>Diteruskan</option>
                                <option value="diterima" {{ request('status') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                <option value="diproses" {{ request('status') == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                <option value="dijawab" {{ request('status') == 'dijawab' ? 'selected' : '' }}>Dijawab</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="proses_disposisi" {{ request('status') == 'proses_disposisi' ? 'selected' : '' }}>Proses Disposisi</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-3 col-lg-auto">
                        <label class="text-secondary fw-bold small mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Urutkan</label>
                        <div class="table-filter-wrapper">
                            <span class="ps-2 text-muted"><i class="fas fa-sort-amount-down text-primary"></i></span>
                            <select name="sort" class="form-select table-filter-select" onchange="this.form.submit()">
                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terbaru (Desc)</option>
                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terlama (Asc)</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg flex-grow-1">
                        <label class="text-secondary fw-bold small mb-2 text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pencarian Bebas</label>
                        <div class="table-search-wrapper">
                            <span class="ps-3 text-muted"><i class="fas fa-search text-primary"></i></span>
                            <input type="text" name="search" class="table-search-input" placeholder="Cari nama pelapor atau teks aduan..." value="{{ request('search') }}">
                            <button class="table-search-btn" type="submit">Cari</button>
                        </div>
                    </div>
                    
                    @if(request()->has('search') || request()->has('status') || request()->has('sort'))
                        <div class="col-12 col-lg-auto mt-3 mt-lg-0">
                            <a href="{{ route('ticketing.index') }}#data-laporan" class="btn btn-sm btn-outline-danger shadow-none w-100 fw-bold d-flex align-items-center justify-content-center" style="height: 40px; border-radius: 50px;" title="Reset Filter">
                                <i class="fas fa-times me-1"></i> Reset
                            </a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
        
        <div class="card-body p-0">
            @if(session('error'))
                <div class="alert alert-danger mx-4 my-3 rounded-3 border-0 bg-danger bg-opacity-10 text-danger">
                    <i class="fa-solid fa-circle-exclamation me-2"></i> {{ session('error') }}
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover table-kmc mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4 text-nowrap text-center">Nomor Tiket</th>
                            <th class="text-nowrap text-center">Waktu</th>
                            <th class="text-center">Pelapor</th>
                            <th class="text-center">Instansi / OPD</th>
                            <th class="text-center" style="max-width: 300px;">Permasalahan</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Status SLA</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $t)
                        <tr>
                            <td class="ps-4 fw-bold text-nowrap" style="color: #0D47A1;">
                                {{ $t->tracking_number ?? $t->ticket_number }}
                            </td>
                            <td class="text-muted small text-nowrap">
                                <div>{{ $t->created_at->format('d M Y') }}</div>
                                <div>{{ $t->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $platformLower = strtolower($t->platform ?? '');
                                    @endphp
                                    @if($platformLower == 'facebook')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm me-2" style="width: 24px; height: 24px; font-size: 0.75rem; background-color: #1877F2; flex-shrink: 0;" title="Facebook" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-facebook-f"></i>
                                        </span>
                                    @elseif($platformLower == 'instagram')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm me-2" style="width: 24px; height: 24px; font-size: 0.75rem; background: radial-gradient(circle at 30% 107%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); flex-shrink: 0;" title="Instagram" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-instagram"></i>
                                        </span>
                                    @elseif($platformLower == 'whatsapp')
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm me-2" style="width: 24px; height: 24px; font-size: 0.75rem; background-color: #25D366; flex-shrink: 0;" title="WhatsApp" data-bs-toggle="tooltip">
                                            <i class="fa-brands fa-whatsapp"></i>
                                        </span>
                                    @else
                                        <span class="d-inline-flex align-items-center justify-content-center rounded-circle text-white shadow-sm bg-secondary me-2" style="width: 24px; height: 24px; font-size: 0.75rem; flex-shrink: 0;" title="{{ $t->platform ?? 'Web/Manual' }}" data-bs-toggle="tooltip">
                                            <i class="fa-solid fa-globe"></i>
                                        </span>
                                    @endif
                                    <div>
                                        <div class="fw-medium text-dark">{{ $t->reporter_name ?? 'Anonim' }}</div>
                                        @if($t->reporter_link && in_array($platformLower, ['facebook', 'instagram']) && !str_contains($t->reporter_link, 'instagram.com/direct/'))
                                            <a href="{{ $t->reporter_link }}" target="_blank" class="text-decoration-none small" style="color: #0288D1;"><i class="fas fa-link"></i> Lihat Postingan</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="fw-medium text-dark">{{ $t->assignedOpd->name ?? ($t->opd_related ?? '-') }}</td>
                            <td style="max-width: 300px;">
                                <div class="text-truncate text-muted" title="{{ $t->complaint }}">{{ $t->complaint }}</div>
                            </td>
                            <td>
                                @php
                                    $statusLabels = [
                                        'diterima' => 'Laporan Diterima',
                                        'diteruskan' => 'Proses Disposisi',
                                        'dibaca' => 'Diterima Instansi',
                                        'diproses' => 'Proses Penanganan',
                                        'dijawab' => 'Proses Penanganan',
                                        'eskalasi' => 'Eskalasi Lanjutan',
                                        'selesai' => 'Selesai Ditangani',
                                        'proses_disposisi' => 'Proses Disposisi (SLA)',
                                    ];
                                    $sLabel = $statusLabels[$t->status] ?? ucfirst($t->status);
                                    
                                    $badgeStyle = 'background-color: rgba(100, 116, 139, 0.12); color: #475569; border: 1px solid rgba(100, 116, 139, 0.25);';
                                    if(in_array($t->status, ['diteruskan', 'dibaca'])) {
                                        $badgeStyle = 'background-color: rgba(245, 124, 0, 0.12); color: #b45309; border: 1px solid rgba(245, 124, 0, 0.25);';
                                    } elseif($t->status === 'proses_disposisi') {
                                        $badgeStyle = 'background-color: rgba(245, 124, 0, 0.12); color: #b45309; border: 1px solid rgba(245, 124, 0, 0.25);';
                                    } elseif(in_array($t->status, ['diproses', 'dijawab'])) {
                                        $badgeStyle = 'background-color: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.25);';
                                    } elseif($t->status === 'eskalasi') {
                                        $badgeStyle = 'background-color: rgba(239, 68, 68, 0.12); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.25);';
                                    } elseif($t->status === 'selesai') {
                                        $badgeStyle = 'background-color: rgba(16, 185, 129, 0.12); color: #15803d; border: 1px solid rgba(16, 185, 129, 0.25);';
                                    }
                                @endphp
                                <span class="badge px-3 py-2 rounded-pill fw-bold" style="{{ $badgeStyle }}">{{ $sLabel }}</span>
                            </td>
                            <td>
                                @php
                                    $isOutSla = $t->sla_deadline && $t->sla_deadline < $now && !in_array($t->status, ['selesai', 'dijawab']);
                                @endphp
                                @if(in_array($t->status, ['selesai', 'dijawab']))
                                    <span class="text-muted fw-bold">-</span>
                                @elseif($isOutSla)
                                    <span class="status-out-sla">Out SLA</span>
                                @else
                                    <span class="status-in-sla">In SLA</span>
                                @endif
                            </td>
                            <td class="text-center pe-4">
                                @if($t->tracking_number)
                                <a href="{{ route('ticketing.show', $t->tracking_number) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-medium">Detail</a>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fa-regular fa-folder-open fa-3x text-muted opacity-25 mb-3"></i>
                                <div class="text-muted fw-medium">Belum ada data tiket laporan.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="px-4 py-3 border-top">
                {{ $tickets->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div> <!-- End of Tab 1 -->

<!-- TAB 2: Statistik & Kinerja -->
<div class="tab-pane fade" id="pills-statistik" role="tabpanel" aria-labelledby="pills-statistik-tab">

    <!-- Primary Scorecards -->
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card scorecard bg-gradient-primary h-100 p-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="scorecard-title mb-1">Total Laporan</div>
                        <div class="scorecard-value">{{ number_format($totalLaporan) }}</div>
                    </div>
                    <div class="opacity-50">
                        <i class="fa-solid fa-file-lines fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card scorecard bg-gradient-success h-100 p-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="scorecard-title mb-1">In SLA</div>
                        <div class="scorecard-value">{{ number_format($inSla) }}</div>
                    </div>
                    <div class="opacity-50">
                        <i class="fa-solid fa-check-circle fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card scorecard bg-gradient-danger h-100 p-2">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <div class="scorecard-title mb-1">Out SLA</div>
                        <div class="scorecard-value">{{ number_format($outSla) }}</div>
                        <div class="small mt-1 opacity-75"><i class="fa-solid fa-circle-info me-1"></i> Target Penanganan = 1x24 jam</div>
                    </div>
                    <div class="opacity-50">
                        <i class="fa-solid fa-triangle-exclamation fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Scorecards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card card-stat card-stat-baru h-100 p-1">
                <div class="card-body text-center" title="Laporan yang baru saja masuk dan sedang diverifikasi">
                    <div class="scorecard-title mb-1">Laporan Diterima</div>
                    <div class="scorecard-value">{{ number_format($baru) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat card-stat-disposisi h-100 p-1">
                <div class="card-body text-center" title="Laporan telah dikirim ke dinas/instansi terkait">
                    <div class="scorecard-title mb-1">Proses Disposisi</div>
                    <div class="scorecard-value text-orange" style="color: #F57C00;">{{ number_format($disposisi) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat card-stat-proses h-100 p-1">
                <div class="card-body text-center" title="Laporan sedang dalam proses pengerjaan oleh instansi">
                    <div class="scorecard-title mb-1">Proses Penanganan</div>
                    <div class="scorecard-value" style="color: #0288D1;">{{ number_format($tindakLanjut) }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card card-stat card-stat-selesai h-100 p-1">
                <div class="card-body text-center" title="Laporan telah selesai ditangani">
                    <div class="scorecard-title mb-1">Selesai Ditangani</div>
                    <div class="scorecard-value" style="color: #388E3C;">{{ number_format($selesai) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and OPD Table -->
    <div class="row g-4 mb-5">
        <!-- Charts Column -->
        <div class="col-lg-4">
            <div class="card card-custom mb-4">
                <div class="card-header-custom">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-list text-primary me-2"></i> Kategori Aduan</h6>
                </div>
                <div class="card-body p-4">
                    <div style="height: 250px;">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header-custom">
                    <h6 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-tags text-primary me-2"></i> Sub Kategori</h6>
                </div>
                <div class="card-body p-4">
                    <div style="height: 250px;">
                        <canvas id="subKategoriChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- OPD Table Column -->
        <div class="col-lg-8">
            <div class="card card-custom h-100">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0" style="color: #0D47A1;">Performa Perangkat Daerah</h6>
                    <span class="badge bg-light text-muted border">Top Instansi</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height: 620px; overflow-y: auto;">
                        <table class="table table-hover table-kmc mb-0">
                            <thead style="position: sticky; top: 0; z-index: 1;">
                                <tr>
                                    <th class="ps-4">Perangkat Daerah</th>
                                    <th class="text-center">Total Laporan</th>
                                    <th class="text-center">Out SLA</th>
                                    <th class="text-center">In SLA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($opdData as $opdName => $data)
                                <tr>
                                    <td class="ps-4 fw-medium text-dark">{{ $opdName }}</td>
                                    <td class="text-center fw-bold">{{ $data['total'] }}</td>
                                    <td class="text-center">
                                        @if($data['out_sla'] > 0)
                                            <span class="text-danger fw-bold">{{ $data['out_sla'] }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center text-success fw-bold">{{ $data['in_sla'] }}</td>
                                </tr>
                                @endforeach
                                @if(count($opdData) === 0)
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">Belum ada data perangkat daerah.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div> <!-- End of Tab 2 -->

</div> <!-- End of Tab Content -->

</div> <!-- End of Container -->
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Premium KMC Theme Palette for Charts
    const kmcColors = [
        '#0d47a1', // KMC Blue
        '#f57c00', // KMC Orange
        '#0288d1', // KMC Sky Blue
        '#10b981', // Emerald
        '#f59e0b', // Amber
        '#8b5cf6', // Violet
        '#ec4899', // Pink
        '#14b8a6', // Teal
        '#f43f5e', // Rose
        '#64748b'  // Slate
    ];

    // Kategori Chart
    const katCtx = document.getElementById('kategoriChart').getContext('2d');
    const katData = @json($kategoriData);
    new Chart(katCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(katData),
            datasets: [{
                data: Object.values(katData),
                backgroundColor: kmcColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { 
                    position: 'right', 
                    labels: { 
                        boxWidth: 10, 
                        boxHeight: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { 
                            size: 10,
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '500'
                        },
                        color: '#475569'
                    } 
                }
            }
        }
    });

    // Sub Kategori Chart
    const subKatCtx = document.getElementById('subKategoriChart').getContext('2d');
    const subKatData = @json($subKategoriData);
    new Chart(subKatCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(subKatData),
            datasets: [{
                data: Object.values(subKatData),
                backgroundColor: kmcColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: { 
                    position: 'right', 
                    labels: { 
                        boxWidth: 10, 
                        boxHeight: 10,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: { 
                            size: 10,
                            family: "'Plus Jakarta Sans', sans-serif",
                            weight: '500'
                        },
                        color: '#475569'
                    } 
                }
            }
        }
    });
});
</script>
@endpush
