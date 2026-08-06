@extends('layouts.app')

@section('title', 'Detail Notifikasi - SIMODU KMC')

@section('page-title')
    <a href="{{ route('notifications.index') }}" class="btn-back me-2"><i class="fas fa-arrow-left"></i></a> 
    Detail Notifikasi
@endsection

@section('content')
<style>
    .card-premium {
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
        background-color: #ffffff;
    }
    .info-cell {
        padding: 12px 16px;
        background: #f8fafc;
        border-radius: 14px;
        border: 1px solid #e2e8f0;
    }
    .info-cell .info-label {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 4px;
    }
    .info-cell .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }
    .complaint-box {
        padding: 1.5rem;
        background: #eff6ff;
        border-radius: 16px;
        border-left: 4px solid var(--kmc-blue);
        position: relative;
    }
    .complaint-box .quote-icon {
        position: absolute;
        top: 12px;
        left: 20px;
        font-size: 1.5rem;
        opacity: 0.15;
        color: var(--kmc-blue);
    }
    .attachment-preview {
        border-radius: 14px;
        overflow: hidden;
        border: 2px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .attachment-preview:hover {
        border-color: var(--kmc-blue);
        box-shadow: 0 8px 20px rgba(13, 71, 161, 0.15);
        transform: translateY(-2px);
    }
    .attachment-preview img,
    .attachment-preview video {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
    }
    .ai-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.82rem;
        font-weight: 600;
    }
</style>

@php
    // Platform detection
    $isWhatsApp = $notification->title === 'WhatsApp' || str_contains($notification->title ?? '', 'WhatsApp');
    $isWebReport = $notification->title === 'Laporan Web SIMADU' || str_contains($notification->title ?? '', 'Laporan Web');
    
    if ($isWhatsApp) {
        $platformLabel = 'WhatsApp';
        $platformIcon = 'fa-whatsapp';
        $platformBg = '#dcfce7';
        $platformColor = '#166534';
        $avatarBg = '#25D366';
    } elseif ($isWebReport) {
        $platformLabel = 'Laporan Web';
        $platformIcon = 'fa-globe';
        $platformBg = '#dbeafe';
        $platformColor = '#1e40af';
        $avatarBg = '#3b82f6';
    } elseif ($notification->title === 'Instagram DM') {
        $platformLabel = 'DM Instagram';
        $platformIcon = 'fa-instagram';
        $platformBg = '#FDF0F5';
        $platformColor = '#D6249F';
        $avatarBg = '#D6249F';
    } elseif (str_contains($notification->title ?? '', 'Comment')) {
        $platformLabel = 'Komentar Facebook';
        $platformIcon = 'fa-facebook-f';
        $platformBg = '#E7F0FF';
        $platformColor = '#1877F2';
        $avatarBg = '#1877F2';
    } else {
        $platformLabel = 'Postingan Facebook';
        $platformIcon = 'fa-facebook-f';
        $platformBg = '#E7F0FF';
        $platformColor = '#1877F2';
        $avatarBg = '#1877F2';
    }

    $ticket = $notification->ticket;
@endphp

<div class="row">
    {{-- Kolom Kiri: Detail Notifikasi --}}
    {{-- Untuk WhatsApp/Web: full width (tanpa Tiket Terkait & Riwayat). Untuk FB/IG: 7 kolom --}}
    <div class="{{ ($isWhatsApp || $isWebReport) ? 'col-lg-12' : 'col-lg-7' }} mb-4">
        <div class="card card-premium overflow-hidden">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 44px; height: 44px; background: {{ $avatarBg }};">
                        <i class="fa-brands {{ $platformIcon }}" style="font-size: 1.2rem;"></i>
                    </div>
                    <div>
                        <span class="fw-bold text-dark fs-5">{{ $notification->sender_name }}</span>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="ai-tag" style="background: {{ $platformBg }}; color: {{ $platformColor }};">
                                <i class="fa-brands {{ $platformIcon }}"></i> {{ $platformLabel }}
                            </span>
                            <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $notification->created_at->format('d M Y, H:i') }}</span>
                        </div>
                    </div>
                </div>
                @if(!$notification->is_read)
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold">Baru</span>
                @else
                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold border border-success">Terbaca</span>
                @endif
            </div>

            <div class="card-body px-4 pb-4">

                {{-- Info Pelapor --}}
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="info-cell h-100">
                            <div class="info-label"><i class="fas fa-user me-1"></i> Nama Pelapor</div>
                            <div class="info-value">{{ $notification->sender_name }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="info-cell h-100">
                            <div class="info-label"><i class="fas fa-hashtag me-1"></i> Sumber</div>
                            <div class="info-value">{{ $notification->sender ?? '-' }}</div>
                            @if($notification->permalink)
                                <a href="{{ $notification->permalink }}" target="_blank" class="small text-primary text-decoration-none mt-1 d-inline-block">
                                    <i class="fas fa-external-link-alt me-1"></i> Buka Sumber
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Isi Aduan --}}
                <div class="complaint-box mb-4">
                    <i class="fas fa-quote-left quote-icon"></i>
                    <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px; padding-left: 2rem;">Isi Aduan</div>
                    <p class="mb-0 text-dark fw-medium" style="white-space: pre-line; font-size: 1.05rem; line-height: 1.7; padding-left: 2rem;">{{ $notification->display_message }}</p>
                </div>

                {{-- Lampiran dari Notifikasi (untuk WhatsApp/Web form dengan multiple files) --}}
                @if($notification->attachments && is_array($notification->attachments) && count($notification->attachments) > 0)
                    <div class="mb-4">
                        <div class="text-muted small fw-bold text-uppercase mb-3" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fas fa-paperclip me-1"></i> Lampiran ({{ count($notification->attachments) }})
                        </div>
                        <div class="row g-3">
                            @foreach($notification->attachments as $index => $attachmentPath)
                                @php
                                    $ext = pathinfo($attachmentPath, PATHINFO_EXTENSION);
                                    $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi', '3gp', 'webm']);
                                    $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'webp', 'heic', 'heif', 'gif']);
                                @endphp
                                <div class="col-md-6">
                                    <div class="attachment-preview">
                                        @if($isVideo)
                                            <video controls class="w-100 rounded" style="max-height: 300px;">
                                                <source src="{{ asset('storage/' . $attachmentPath) }}" type="video/{{ $ext }}">
                                                Browser Anda tidak mendukung video.
                                            </video>
                                        @elseif($isImage)
                                            <a href="{{ asset('storage/' . $attachmentPath) }}" target="_blank" class="d-block">
                                                <img src="{{ asset('storage/' . $attachmentPath) }}" alt="Lampiran {{ $index + 1 }}" class="w-100 rounded" style="max-height: 300px; object-fit: cover;">
                                            </a>
                                        @else
                                            <a href="{{ asset('storage/' . $attachmentPath) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill">
                                                <i class="fas fa-file me-1"></i> Unduh File {{ $index + 1 }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Lampiran dari Ticket (legacy single attachment) --}}
                @if($ticket && $ticket->attachment && (!$notification->attachments || count($notification->attachments) == 0))
                    <div class="mb-4">
                        <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fas fa-paperclip me-1"></i> Lampiran
                        </div>
                        <div class="attachment-preview d-inline-block">
                            @php
                                $ext = pathinfo($ticket->attachment, PATHINFO_EXTENSION);
                                $isVideo = in_array(strtolower($ext), ['mp4', 'mov', 'avi', '3gp', 'webm']);
                            @endphp
                            @if($isVideo)
                                <video controls class="rounded">
                                    <source src="{{ asset('storage/' . $ticket->attachment) }}" type="video/{{ $ext }}">
                                    Browser Anda tidak mendukung video.
                                </video>
                            @else
                                <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $ticket->attachment) }}" alt="Lampiran" class="rounded">
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- AI Classification --}}
                @if($notification->ai)
                    <div class="p-3 rounded-4 border mb-3" style="background: #fefce8; border-color: #fde68a !important;">
                        <div class="text-muted small fw-bold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fas fa-robot me-1 text-warning"></i> Hasil Klasifikasi AI
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @if($notification->ai->suggested_category)
                                <span class="ai-tag" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
                                    <i class="fas fa-building"></i> {{ $notification->ai->suggested_category }}
                                </span>
                            @endif
                            @if($notification->ai->suggested_sub_category)
                                <span class="ai-tag" style="background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0;">
                                    <i class="fas fa-tag"></i> {{ $notification->ai->suggested_sub_category }}
                                </span>
                            @endif
                            @if($notification->ai->priority)
                                @php
                                    $priBg = '#f1f5f9'; $priColor = '#475569';
                                    if (strtolower($notification->ai->priority) === 'tinggi') { $priBg = '#fef2f2'; $priColor = '#dc2626'; }
                                    elseif (strtolower($notification->ai->priority) === 'sedang') { $priBg = '#fffbeb'; $priColor = '#d97706'; }
                                    else { $priBg = '#f0fdf4'; $priColor = '#16a34a'; }
                                @endphp
                                <span class="ai-tag" style="background: {{ $priBg }}; color: {{ $priColor }}; border: 1px solid currentColor;">
                                    <i class="fas fa-exclamation-circle"></i> {{ $notification->ai->priority }}
                                </span>
                            @endif
                            @if($notification->ai->confidence)
                                <span class="ai-tag" style="background: #ede9fe; color: #7c3aed; border: 1px solid #c4b5fd;">
                                    <i class="fas fa-chart-line"></i> {{ $notification->ai->confidence }}% confidence
                                </span>
                            @endif
                        </div>
                        @if($notification->ai->reasoning)
                            <div class="mt-2 small text-muted fst-italic" style="font-size: 0.82rem;">
                                <i class="fas fa-lightbulb me-1 text-warning"></i> {{ $notification->ai->reasoning }}
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Duplikasi Info --}}
                @if($notification->duplicate_status === 'terdeteksi')
                    <div class="alert alert-warning d-flex align-items-start gap-2 rounded-4 border-0" style="background: #fffbeb;">
                        <i class="fas fa-triangle-exclamation mt-1 text-warning"></i>
                        <div>
                            <strong>Terdeteksi Duplikat</strong> — {{ round($notification->duplicate_similarity) }}% mirip
                            @if($notification->duplicateOf)
                                dengan laporan dari <strong>{{ $notification->duplicateOf->sender_name }}</strong>
                            @endif
                            <div class="mt-2 d-flex gap-2">
                                <form method="POST" action="{{ route('notifications.not-duplicate', $notification->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3" onclick="return confirm('Yakin bukan duplikat?')">
                                        <i class="fas fa-check me-1"></i> Bukan Duplikat
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('notifications.is-duplicate', $notification->id) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="return confirm('Konfirmasi duplikat?')">
                                        <i class="fas fa-times me-1"></i> Duplikat
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @elseif($notification->duplicate_status === 'dikonfirmasi_duplikat')
                    <div class="alert alert-secondary d-flex align-items-center gap-2 rounded-4 border-0">
                        <i class="fas fa-copy text-muted"></i>
                        <span>Dikonfirmasi sebagai <strong>duplikat</strong> dan sudah diarsipkan.</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Tiket Terkait & Riwayat hanya untuk notifikasi media sosial --}}
    @if(!$isWhatsApp && !$isWebReport)
    <div class="col-lg-5 mb-4">
        @if($ticket)
            <div class="card card-premium overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                    <span class="fw-bold"><i class="fas fa-ticket-alt me-2 text-primary"></i> Tiket Terkait</span>
                    @php
                        $badgeClass = 'bg-secondary';
                        if(in_array($ticket->status, ['diteruskan', 'diterima'])) $badgeClass = 'bg-info text-dark';
                        elseif($ticket->status == 'proses_disposisi') $badgeClass = 'text-dark';
                        elseif($ticket->status == 'diproses') $badgeClass = 'bg-warning text-dark';
                        elseif($ticket->status == 'dijawab') $badgeClass = 'bg-primary';
                        elseif($ticket->status == 'selesai') $badgeClass = 'bg-success';
                        elseif($ticket->status == 'eskalasi') $badgeClass = 'bg-danger';
                    @endphp
                    <span class="badge {{ $badgeClass }} px-3 py-2 rounded-pill fw-bold" @if($ticket->status == 'proses_disposisi') style="background-color: rgba(245, 124, 0, 0.15); color: #E65100 !important;" @endif>
                        {{ $ticket->status == 'proses_disposisi' ? 'PROSES DISPOSISI' : strtoupper($ticket->status) }}
                    </span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="info-cell h-100">
                                <div class="info-label"><i class="fas fa-barcode me-1"></i> Tracking</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ $ticket->tracking_number }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-cell h-100">
                                <div class="info-label"><i class="fas fa-building me-1"></i> OPD</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ $ticket->assignedOpd->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-cell h-100">
                                <div class="info-label"><i class="fas fa-tag me-1"></i> Kategori</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ $ticket->category ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="info-cell h-100">
                                <div class="info-label"><i class="fas fa-exclamation-circle me-1"></i> Prioritas</div>
                                <div class="info-value" style="font-size: 0.85rem;">{{ ucfirst($ticket->priority ?? '-') }}</div>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('tickets.show', $ticket->id) }}" class="btn btn-primary w-100 rounded-pill fw-bold mt-3 py-2">
                        <i class="fas fa-external-link-alt me-2"></i> Lihat Detail Tiket
                    </a>
                </div>
            </div>

            {{-- Riwayat Terbaru --}}
            @if($ticket->responses->count() > 0 || $ticket->statusLogs->count() > 0)
                <div class="card card-premium overflow-hidden">
                    <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 fw-bold">
                        <h6 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-secondary"></i> Riwayat Terbaru</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="p-3" style="max-height: 350px; overflow-y: auto;">
                            @php
                                $events = collect();
                                foreach($ticket->responses->take(3) as $resp) {
                                    $events->push((object)['type' => 'response', 'date' => $resp->created_at, 'user' => $resp->user?->name ?? 'User', 'content' => $resp->message]);
                                }
                                foreach($ticket->statusLogs->sortByDesc('id')->take(3) as $log) {
                                    $events->push((object)['type' => 'status', 'date' => $log->created_at, 'user' => $log->user?->name ?? 'Sistem', 'old' => $log->from_status, 'new' => $log->to_status, 'notes' => $log->note]);
                                }
                                $events = $events->sortByDesc(fn($e) => $e->date->timestamp)->take(5);
                            @endphp

                            <div class="timeline position-relative ps-4 ms-2" style="border-left: 2px solid #e9ecef;">
                                @foreach($events as $event)
                                    <div class="timeline-item mb-3 position-relative">
                                        <div class="position-absolute bg-white border border-3 {{ $event->type == 'response' ? 'border-primary' : 'border-warning' }} rounded-circle" style="width: 14px; height: 14px; left: -31px; top: 3px;"></div>
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold small {{ $event->type == 'response' ? 'text-primary' : 'text-dark' }}">
                                                <i class="fas {{ $event->type == 'response' ? 'fa-comment' : 'fa-sync-alt' }} me-1"></i> {{ $event->user }}
                                            </span>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ $event->date->format('d M, H:i') }}</span>
                                        </div>
                                        @if($event->type == 'response')
                                            <div class="small text-muted" style="white-space: pre-line;">{{ Str::limit($event->content, 100) }}</div>
                                        @else
                                            <div class="d-flex align-items-center small gap-1">
                                                @if($event->old) <span class="badge bg-secondary bg-opacity-50 small">{{ $event->old }}</span> <i class="fas fa-arrow-right text-muted" style="font-size: 0.65rem;"></i> @endif
                                                <span class="badge bg-primary small">{{ $event->new }}</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @else
            <div class="card card-premium overflow-hidden">
                <div class="card-body text-center py-5 px-4">
                    <i class="fas fa-ticket-alt fa-3x text-muted opacity-25 mb-3"></i>
                    <h6 class="fw-bold text-muted">Belum Ada Tiket</h6>
                    <p class="small text-muted mb-0">Notifikasi ini belum dibuatkan tiket. Tiket akan dibuat otomatis setelah klasifikasi AI selesai, atau Anda bisa membuat tiket secara manual.</p>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>
@endsection
