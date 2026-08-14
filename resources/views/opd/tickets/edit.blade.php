@extends('layouts.app')

@section('title', 'Perbarui Tiket ' . ($ticket->tracking_number ?? $ticket->ticket_number))
@section('page-title')
    <a href="{{ route('opd.tickets.index') }}" class="btn-back me-2" title="Kembali ke Daftar Tiket"><i class="fas fa-arrow-left"></i></a> 
    Perbarui Tiket: {{ $ticket->tracking_number ?? $ticket->ticket_number }}
@endsection

@section('content')
<style>
    .card-premium {
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
        background-color: #ffffff;
    }
    .form-premium {
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s ease;
        background-color: #f8fafc;
    }
    .form-premium:focus {
        border-color: #F57C00;
        box-shadow: 0 0 0 4px rgba(245, 124, 0, 0.15);
        background-color: #fff;
    }
    .timeline-indicator {
        box-shadow: 0 0 0 4px #fff, 0 0 0 5px rgba(0,0,0,0.05);
    }
    .timeline-card {
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,0.04) !important;
    }
    .form-label {
        font-size: 0.8rem;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }
</style>

<div class="row">

    @if(session('success'))
    <div class="col-12 mb-3">
        <div class="alert alert-success shadow-sm border-0 rounded-4 alert-dismissible fade show p-3" role="alert" style="background-color: #dcfce7; color: #15803d; border-left: 5px solid #22c55e !important;">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-3 fs-4 text-success"></i>
                <div>
                    <strong class="fs-6 d-block mb-0">Berhasil!</strong>
                    <span class="small">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="col-12 mb-3">
        <div class="alert alert-danger shadow-sm border-0 rounded-3 alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2 fa-lg"></i>
                <strong class="fs-6">{{ session('error') }}</strong>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    @if($errors->any())
    <div class="col-12 mb-3">
        <div class="alert alert-danger shadow-sm border-0 rounded-3">
            <div class="d-flex align-items-center mb-2">
                <i class="fas fa-exclamation-triangle me-2 fa-lg"></i>
                <strong class="fs-6">Terjadi Kesalahan!</strong>
            </div>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <!-- Baris 1: Informasi Aduan & Berikan Tanggapan -->
    <div class="col-lg-7 mb-4">
        <div class="card card-premium overflow-hidden h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 d-flex justify-content-between align-items-center">
                <span class="fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i> Informasi Aduan</span>
                @php
                    $badgeClass = 'bg-secondary';
                    if($ticket->status == 'diteruskan' || $ticket->status == 'diterima') $badgeClass = 'bg-info text-dark';
                    elseif($ticket->status == 'proses_disposisi') $badgeClass = 'text-dark';
                    elseif($ticket->status == 'diproses') $badgeClass = 'bg-warning text-dark';
                    elseif($ticket->status == 'dijawab') $badgeClass = 'bg-primary';
                    elseif($ticket->status == 'selesai') $badgeClass = 'bg-success';
                    elseif($ticket->status == 'eskalasi') $badgeClass = 'bg-danger';
                @endphp
                <span class="badge {{ $badgeClass }} px-4 py-2 fs-6 rounded-pill fw-bold border border-opacity-25 shadow-sm" @if($ticket->status == 'proses_disposisi') style="background-color: rgba(245, 124, 0, 0.15); color: #E65100 !important; border-color: #F57C00 !important;" @endif>{{ $ticket->status == 'proses_disposisi' ? 'PROSES DISPOSISI' : strtoupper($ticket->status) }}</span>
            </div>
            <div class="card-body px-4 pb-4">
                <div class="mb-4 border-bottom pb-3">
                    <h5 class="fw-bold mb-1 text-dark" style="letter-spacing: -0.5px;">Laporan #{{ $ticket->tracking_number ?? $ticket->ticket_number }}</h5>
                    <div class="text-muted small d-flex align-items-center">
                        <span class="badge bg-light text-dark border px-2 py-1 me-2"><i class="fab fa-{{ strtolower($ticket->platform ?? '') }} text-primary"></i> {{ ucfirst($ticket->platform ?? '') }}</span>
                        Dikirim pada {{ $ticket->created_at?->format('d M Y, H:i') }}
                    </div>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-4 border border-opacity-50 h-100 transition-all hover-shadow">
                            <div class="text-muted small fw-bold text-uppercase tracking-wide mb-1" style="font-size: 0.7rem;"><i class="fas fa-user-circle me-1"></i> Info Pelapor</div>
                            <div class="fw-bold text-dark fs-6">{{ $ticket->reporter_name ?? 'Anonim' }}</div>
                            @if($ticket->reporter_link)
                                @php
                                    $sourceLink = $ticket->reporter_link;
                                    if (strtolower($ticket->platform ?? '') === 'whatsapp') {
                                        preg_match('/(?:phone=|wa\.me\/)(\d+)/', $ticket->reporter_link, $phoneMatch);
                                        $sourceLink = !empty($phoneMatch[1])
                                            ? 'https://web.whatsapp.com/send?phone=' . $phoneMatch[1]
                                            : $ticket->reporter_link;
                                    }
                                @endphp
                                <a href="{{ $sourceLink }}" target="_blank" rel="noopener" class="small text-primary text-decoration-none mt-1 d-inline-block fw-semibold"><i class="fas fa-external-link-alt me-1"></i> Buka Sumber Aduan</a>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-4 border border-opacity-50 h-100 transition-all hover-shadow">
                            <div class="text-muted small fw-bold text-uppercase tracking-wide mb-1" style="font-size: 0.7rem;"><i class="fas fa-tag me-1"></i> Kategori Masalah</div>
                            <div class="fw-bold text-dark fs-6">{{ $ticket->category }}</div>
                            @if($ticket->sub_category)
                                <div class="small mt-1 text-secondary"><i class="fas fa-level-up-alt fa-rotate-90 me-1"></i> {{ $ticket->sub_category }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="position-relative mt-2">
                    <div class="position-absolute top-0 start-0 text-primary opacity-25" style="margin-left: 1.2rem; margin-top: 1rem;">
                        <i class="fas fa-quote-left fa-2x"></i>
                    </div>
                    <div class="p-4 bg-primary-subtle rounded-4 border-start border-4 border-primary" style="padding-left: 4rem !important;">
                        <div class="text-muted small fw-bold text-uppercase tracking-wide mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Isi Aduan Masyarakat</div>
                        <p class="mb-0 text-dark fw-medium" style="white-space: pre-line; font-size: 1.05rem; line-height: 1.6;">{{ $ticket->complaint }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card card-premium overflow-hidden h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 fw-bold">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-comments me-2 text-primary"></i> Berikan Tanggapan</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('opd.tickets.respond', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold">Pesan Tanggapan</label>
                        <textarea name="response_text" class="form-control form-premium py-3 px-3 shadow-none" rows="3" placeholder="Ketik tanggapan resmi OPD yang akan dilihat oleh pelapor..." required></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold">Lampiran Bukti (Opsional)</label>
                        <div class="d-flex align-items-center gap-2">
                            <label class="flex-grow-1 m-0">
                                <div class="form-control form-premium shadow-none d-flex align-items-center" style="cursor: pointer; padding: 0.4rem;">
                                    <div class="bg-secondary text-white px-3 py-2 rounded me-3 small fw-bold"><i class="fas fa-folder-open me-1"></i> Pilih File</div>
                                    <span class="text-muted small text-truncate" style="flex: 1;" id="filename-respond">Belum ada file...</span>
                                </div>
                                <input type="file" id="opd-respond-file" name="attachment" class="d-none" accept=".jpg,.jpeg,.png" onchange="previewImageInModal(this, 'filename-respond')">
                            </label>
                            <button type="button" id="opd-respond-file-btn-preview" class="btn btn-outline-primary btn-sm rounded-3 py-2 px-3 flex-shrink-0 d-none" onclick="openCurrentPreviewModal()" title="Lihat Pratinjau Foto">
                                <i class="fas fa-eye me-1"></i>Pratinjau
                            </button>
                        </div>

                        <div class="form-text mt-2 small text-muted"><i class="fas fa-info-circle me-1 text-primary"></i> Hanya format <strong>JPG, JPEG, PNG</strong>. Maksimal <strong>5MB</strong>.</div>
                    </div>
                    <button type="submit" class="btn btn-primary fw-bold w-100 rounded-pill py-2" style="box-shadow: 0 4px 6px rgba(13, 110, 253, 0.2);">
                        <i class="fas fa-paper-plane me-2"></i> Kirim Tanggapan
                    </button>
                    <div class="text-center mt-3"><span class="badge bg-light text-muted border px-3 py-1"><i class="fas fa-info-circle me-1"></i> Mengirim tanggapan akan mengubah status tiket menjadi "Dijawab".</span></div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Baris 2: Riwayat & Diskusi -->
    <div class="col-lg-12 mb-4">
        <div class="card card-premium overflow-hidden h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 fw-bold">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-history me-2 text-secondary"></i> Riwayat & Diskusi</h5>
            </div>
            <div class="card-body p-0">
                <div class="p-3" style="max-height: 500px; overflow-y: auto;">
                    
                    {{-- Combine Responses and Status Logs --}}
                    @php
                        $events = collect();
                        foreach($ticket->responses as $resp) {
                            $events->push((object)[
                                'type' => 'response',
                                'date' => $resp->created_at,
                                'user' => $resp->user?->name ?? 'User',
                                'content' => $resp->message,
                                'attachment' => $resp->attachment
                            ]);
                        }
                        foreach($ticket->statusLogs->sortBy('id') as $log) {
                            $events->push((object)[
                                'type' => 'status',
                                'date' => $log->created_at,
                                'user' => $log->user?->name ?? 'Sistem',
                                'old' => $log->from_status,
                                'new' => $log->to_status,
                                'notes' => $log->note,
                                'attachment' => $log->attachment
                            ]);
                        }
                        $events = $events->sortByDesc(function($item) {
                            return $item->date ? $item->date->timestamp : 0;
                        })->values();
                    @endphp

                    <div class="timeline position-relative ps-4 ms-2 mt-2" style="border-left: 2px solid #e9ecef;">
                    @forelse($events as $event)
                        <div class="timeline-item mb-4 position-relative">
                            <div class="timeline-indicator position-absolute bg-white border border-3 {{ $event->type == 'response' ? 'border-primary' : 'border-warning' }} rounded-circle" style="width: 16px; height: 16px; left: -33px; top: 2px;"></div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold small {{ $event->type == 'response' ? 'text-primary' : 'text-warning text-dark' }}">
                                    @if($event->type == 'response')
                                        <i class="fas fa-comment me-1"></i> {{ $event->user }}
                                    @else
                                        <i class="fas fa-sync-alt me-1"></i> {{ $event->user }}
                                    @endif
                                </span>
                                <span class="text-muted small bg-light px-2 py-1 rounded" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i> {{ $event->date?->format('d M, H:i') }}</span>
                            </div>
                            
                            <div class="card timeline-card shadow-sm {{ $event->type == 'response' ? 'bg-primary-subtle border-primary border-opacity-25' : 'bg-light' }}">
                                <div class="card-body p-3">
                                    @if($event->type == 'response')
                                        <div class="text-dark small" style="white-space: pre-line;">{{ $event->content }}</div>
                                    @else
                                        <div class="d-flex align-items-center small mb-1">
                                            @if($event->old && $event->old !== $event->new)
                                                <span class="badge bg-secondary opacity-75">{{ $event->old }}</span> 
                                                <i class="fas fa-arrow-right mx-2 text-muted"></i> 
                                            @endif
                                            <span class="badge bg-primary">{{ $event->new }}</span>
                                        </div>
                                        @if($event->notes)
                                            <div class="small text-muted fst-italic border-top pt-2 mt-2">"{{ $event->notes }}"</div>
                                        @endif
                                    @endif
                                    
                                    @if(isset($event->attachment) && $event->attachment)
                                        <div class="mt-3">
                                            <div class="d-inline-block rounded overflow-hidden border">
                                                <img src="{{ asset('storage/' . $event->attachment) }}" class="lightbox-img" style="height: 80px; object-fit: cover;" alt="Lampiran">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5 small bg-light rounded-3">
                            <i class="far fa-comment-dots fa-2x mb-2 opacity-50"></i>
                            <div>Belum ada riwayat aktivitas atau tanggapan.</div>
                        </div>
                    @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
