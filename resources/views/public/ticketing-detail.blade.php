@extends('public.layouts.app')

@section('title', 'Detail Pelacakan - ' . $ticket->tracking_number)

@push('styles')
<style>
    /* Timeline styles */
    .timeline {
        position: relative;
        padding-left: 3rem;
        margin-bottom: 0;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0;
        bottom: 0;
        left: 14px;
        width: 2px;
        background-color: #cbd5e1;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 2rem;
    }
    .timeline-item:last-child {
        margin-bottom: 0;
    }
    .timeline-icon {
        position: absolute;
        left: -3rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border: 2px solid var(--kmc-blue);
        color: var(--kmc-blue);
        z-index: 1;
        box-shadow: 0 0 0 5px #f8fafc;
        transition: all 0.3s ease;
    }
    .timeline-icon.success {
        border-color: #10b981;
        background-color: #10b981;
        color: white;
        box-shadow: 0 0 0 5px #f8fafc, 0 0 10px rgba(16, 185, 129, 0.3);
    }
    .timeline-icon.warning {
        border-color: var(--kmc-orange);
        background-color: var(--kmc-orange);
        color: white;
        box-shadow: 0 0 0 5px #f8fafc, 0 0 10px rgba(245, 124, 0, 0.3);
    }
    .timeline-icon.info {
        border-color: #0ea5e9;
        background-color: #0ea5e9;
        color: white;
        box-shadow: 0 0 0 5px #f8fafc, 0 0 10px rgba(14, 165, 233, 0.3);
    }
    .timeline-icon.primary {
        border-color: var(--kmc-blue);
        background-color: var(--kmc-blue);
        color: white;
        box-shadow: 0 0 0 5px #f8fafc, 0 0 10px rgba(13, 71, 161, 0.3);
    }
    .timeline-content {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02), 0 2px 4px -1px rgba(0,0,0,0.02);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .timeline-content:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 20px -8px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }
    .timeline-date {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
    }
    .card-custom { 
        border: none; 
        border-radius: 20px; 
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.02), 0 8px 10px -6px rgba(0, 0, 0, 0.02); 
    }
    @media (max-width: 575.98px) {
        .timeline { padding-left: 2.5rem; }
        .timeline-icon { left: -2.5rem; width: 28px; height: 28px; font-size: 0.75rem; }
        .timeline::before { left: 12px; }
        .timeline-content { padding: 1rem; border-radius: 14px; }
        .card-custom { border-radius: 14px; }
        .card-custom .card-body { padding: 1rem !important; }
    }
</style>
@endpush

@section('content')
<div class="container pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card card-custom mb-4 mt-2">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 pb-3 border-bottom">
                        <h4 class="fw-bold mb-3 mb-md-0" style="color: var(--kmc-blue);">
                            <i class="fa-solid fa-ticket me-2"></i>
                            {{ $ticket->tracking_number }}
                        </h4>
                        <div>
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
                                $sLabel = $statusLabels[$ticket->status] ?? ucfirst($ticket->status);
                                
                                $badgeStyle = 'background-color: rgba(100, 116, 139, 0.12); color: #475569; border: 1px solid rgba(100, 116, 139, 0.25);';
                                if(in_array($ticket->status, ['diteruskan', 'dibaca', 'proses_disposisi'])) {
                                    $badgeStyle = 'background-color: rgba(245, 124, 0, 0.12); color: #b45309; border: 1px solid rgba(245, 124, 0, 0.25);';
                                } elseif(in_array($ticket->status, ['diproses', 'dijawab'])) {
                                    $badgeStyle = 'background-color: rgba(6, 182, 212, 0.12); color: #0891b2; border: 1px solid rgba(6, 182, 212, 0.25);';
                                } elseif($ticket->status === 'eskalasi') {
                                    $badgeStyle = 'background-color: rgba(239, 68, 68, 0.12); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.25);';
                                } elseif($ticket->status === 'selesai') {
                                    $badgeStyle = 'background-color: rgba(16, 185, 129, 0.12); color: #15803d; border: 1px solid rgba(16, 185, 129, 0.25);';
                                }
                            @endphp
                            <span class="badge fs-6 rounded-pill px-4 py-2" style="{{ $badgeStyle }}">{{ $sLabel }}</span>
                        </div>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <div class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Kategori</div>
                            <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $ticket->category ?? '-' }}</div>
                            <div class="text-muted">{{ $ticket->sub_category }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Instansi / PD Terkait</div>
                            <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $ticket->assignedOpd->name ?? 'Belum Ditugaskan' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Prioritas</div>
                            <div>
                                @if(strtolower($ticket->priority) == 'tinggi')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-arrow-up me-1"></i> Tinggi</span>
                                @elseif(strtolower($ticket->priority) == 'sedang')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-2 fw-bold text-dark"><i class="fa-solid fa-arrow-right me-1"></i> Sedang</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-2 fw-bold"><i class="fa-solid fa-arrow-down me-1"></i> Rendah</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted small mb-1 fw-medium text-uppercase tracking-wider">Waktu Pelaporan</div>
                            <div class="fw-bold text-dark" style="font-size: 1.1rem;">
                                {{ $ticket->created_at->translatedFormat('d F Y') }} <span class="text-muted fw-normal ms-2">{{ $ticket->created_at->format('H:i') }} WIB</span>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-4 p-4" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="text-muted small mb-3 fw-bold text-uppercase"><i class="fa-solid fa-quote-left me-2 text-primary"></i>Isi Laporan</div>
                        <p class="mb-0 fs-5" style="color: #334155; line-height: 1.6;">{{ $ticket->complaint ?? 'Detail laporan tidak tersedia.' }}</p>
                    </div>

                    @php($reportAttachments = $ticket->notification?->attachments ?? [])
                    @if(is_array($reportAttachments) && count($reportAttachments))
                        <div class="mt-4">
                            <div class="text-muted small mb-2 fw-bold text-uppercase"><i class="fa-solid fa-paperclip me-2 text-primary"></i>Lampiran Pelapor ({{ count($reportAttachments) }})</div>
                            <div class="row g-3">
                                @foreach($reportAttachments as $index => $path)
                                    @php
                                        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                        $isVideo = in_array($extension, ['mp4', 'mov', 'avi', '3gp', 'webm']);
                                    @endphp
                                    <div class="col-md-6">
                                        <div class="border rounded-4 overflow-hidden bg-light p-2">
                                            @if($isVideo)
                                                <video controls class="w-100 rounded-3" style="max-height: 360px;"><source src="{{ asset('storage/' . $path) }}" type="video/{{ $extension }}">Browser Anda tidak mendukung video.</video>
                                            @else
                                                <a href="{{ asset('storage/' . $path) }}" target="_blank"><img src="{{ asset('storage/' . $path) }}" alt="Lampiran pelapor {{ $index + 1 }}" class="w-100 rounded-3" style="max-height: 360px; object-fit: contain;"></a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <h5 class="fw-bold mb-4 mt-5"><i class="fa-solid fa-route me-2 text-primary"></i>Riwayat Perjalanan Aduan</h5>
            
            <div class="timeline">
                @php
                    $events = collect();
                    
                    $events->push((object)[
                        'type' => 'created',
                        'date' => $ticket->created_at,
                    ]);

                    // Tentukan bobot/urutan logis dari setiap status
                    $statusWeight = [
                        'baru' => 1, 'diterima' => 2, 'diteruskan' => 3, 'dibaca' => 4,
                        'diproses' => 5, 'dijawab' => 6, 'selesai' => 7, 'ditolak' => 7, 'eskalasi' => 8, 'proses_disposisi' => 3
                    ];

                    $validLogs = collect();
                    $sortedLogs = $ticket->statusLogs->sortBy('id');
                    
                    foreach($sortedLogs as $log) {
                        $currentWeight = $statusWeight[$log->to_status] ?? 0;
                        
                        // Jika status "mundur" atau diulang, hapus riwayat status yang ada di depannya (reset)
                        $validLogs = $validLogs->reject(function($existing) use ($currentWeight, $statusWeight) {
                            $existingWeight = $statusWeight[$existing->to_status] ?? 0;
                            return $existingWeight >= $currentWeight;
                        });
                        
                        $validLogs->push($log);
                    }

                    foreach($validLogs as $log) {
                        $events->push((object)[
                            'type' => 'status',
                            'date' => $log->created_at,
                            'to_status' => $log->to_status,
                            'note' => $log->note,
                            'attachment' => $log->attachment,
                        ]);
                    }

                    foreach($ticket->responses as $resp) {
                        $events->push((object)[
                            'type' => 'response',
                            'date' => $resp->created_at,
                            'content' => $resp->message,
                            'attachment' => $resp->attachment,
                            'user' => $resp->user->name ?? 'OPD'
                        ]);
                    }

                    $events = $events->sortBy(function($item) {
                        return $item->date->timestamp;
                    })->values();
                @endphp

                @foreach($events as $event)
                    @if($event->type === 'created')
                        <div class="timeline-item">
                            <div class="timeline-icon info">
                                <i class="fa-solid fa-asterisk"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ $event->date->translatedFormat('d M Y, H:i') }}</div>
                                <h6 class="fw-bold mb-1">Aduan Dibuat</h6>
                                <p class="mb-0 text-muted small">Aduan telah dicatat di sistem.</p>
                            </div>
                        </div>
                    @elseif($event->type === 'status')
                        @php
                            $iconClass = 'info';
                            $iconName = 'fa-arrow-right';
                            if($event->to_status === 'diproses') {
                                $iconClass = 'warning';
                                $iconName = 'fa-gears';
                            } elseif($event->to_status === 'selesai') {
                                $iconClass = 'success';
                                $iconName = 'fa-check';
                            }
                        @endphp
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $iconClass }}">
                                <i class="fa-solid {{ $iconName }}"></i>
                            </div>
                            <div class="timeline-content">
                                <div class="timeline-date">{{ $event->date->translatedFormat('d M Y, H:i') }}</div>
                                @php
                                    $friendlyStatus = [
                                        'baru' => 'Menunggu verifikasi admin',
                                        'diterima' => 'Sedang ditinjau',
                                        'diteruskan' => 'Diteruskan ke instansi',
                                        'dibaca' => 'Telah dibaca instansi',
                                        'diproses' => 'Sedang ditindaklanjuti',
                                        'dijawab' => 'Telah ditanggapi',
                                        'selesai' => 'Masalah terselesaikan',
                                        'ditolak' => 'Laporan ditolak',
                                        'eskalasi' => 'Butuh bantuan instansi lain',
                                        'proses_disposisi' => 'Sedang didisposisikan',
                                    ];
                                    $displayStatus = $friendlyStatus[$event->to_status] ?? $event->to_status;
                                @endphp
                                <h6 class="fw-bold mb-2">
                                    Status diubah menjadi: <span class="text-primary">{{ $displayStatus }}</span>
                                </h6>
                                @if($event->note)
                                    <div class="p-3 bg-light rounded-3 small border mb-2">
                                        {{ $event->note }}
                                    </div>
                                @endif
                                @if($event->attachment)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $event->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $event->attachment) }}" class="img-thumbnail shadow-sm rounded" style="max-height: 200px;" alt="Bukti Dokumentasi">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif($event->type === 'response')
                        <div class="timeline-item">
                            <div class="timeline-icon bg-primary text-white" style="border-color: var(--kmc-blue);">
                                <i class="fa-solid fa-comment"></i>
                            </div>
                            <div class="timeline-content border-primary" style="border-width: 2px;">
                                <div class="timeline-date">{{ $event->date->translatedFormat('d M Y, H:i') }}</div>
                                <h6 class="fw-bold mb-2 text-primary">Tanggapan dari {{ $event->user }}</h6>
                                <div class="p-3 bg-primary-subtle rounded-3 small border border-primary border-opacity-25 mb-2" style="white-space: pre-line;">
                                    {{ $event->content }}
                                </div>
                                @if($event->attachment)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $event->attachment) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $event->attachment) }}" class="img-thumbnail shadow-sm rounded" style="max-height: 200px;" alt="Bukti Dokumentasi">
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Tombol Kembali di bagian paling bawah -->
            <div class="mt-5 mb-3 text-center">
                <a href="{{ route('ticketing.index') }}" class="btn btn-light border shadow-sm rounded-pill px-5 py-2 fw-bold text-dark text-decoration-none" style="font-size: 1rem;">
                    <i class="fa-solid fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
