@extends('layouts.app')

@section('title', 'Detail Tiket ' . ($ticket->tracking_number ?? $ticket->ticket_number))
@section('page-title')
    <a href="{{ route('tickets.index') }}" class="btn-back me-2"><i class="fas fa-arrow-left"></i></a> 
    Detail Tiket: {{ $ticket->tracking_number ?? $ticket->ticket_number }}
@endsection

@section('content')
<style>
    @media (max-width: 575.98px) {
        .card-premium .card-body { padding: 16px !important; }
        .card-premium .card-header { padding: 12px 16px !important; }
        .d-flex.gap-2.mb-4 { flex-direction: column; }
        .d-flex.gap-2.mb-4 .btn,
        .d-flex.gap-2.mb-4 form { width: 100%; }
        .d-flex.gap-2.mb-4 form button { width: 100%; }
    }
    .card-premium {
        border: 1px solid rgba(0,0,0,0.04);
        border-radius: 20px;
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.01);
        background-color: #ffffff;
    }
    .timeline-indicator {
        box-shadow: 0 0 0 4px #fff, 0 0 0 5px rgba(0,0,0,0.05);
    }
    .timeline-card {
        border-radius: 14px;
        border: 1px solid rgba(0,0,0,0.04) !important;
    }
</style>

<div class="row">
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
    <!-- Kolom Kiri: Info Tiket & Update Status -->
    <div class="col-lg-7 mb-4">
        <div class="card card-premium mb-4 overflow-hidden">
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
                        <span class="badge bg-light text-dark border px-2 py-1 me-2"><i class="fab fa-{{ strtolower($ticket->platform) }} text-primary"></i> {{ ucfirst($ticket->platform) }}</span>
                        Dikirim pada {{ $ticket->created_at->format('d M Y, H:i') }}
                    </div>
                </div>
                
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-4 border border-opacity-50 h-100 transition-all hover-shadow">
                            <div class="text-muted small fw-bold text-uppercase tracking-wide mb-1" style="font-size: 0.7rem;"><i class="fas fa-user-circle me-1"></i> Info Pelapor</div>
                            <div class="fw-bold text-dark fs-6">{{ $ticket->reporter_name ?? 'Anonim' }}</div>
                            @if($ticket->reporter_link)
                                <a href="{{ $ticket->reporter_link }}" target="_blank" class="small text-primary text-decoration-none mt-1 d-inline-block fw-semibold"><i class="fas fa-external-link-alt me-1"></i> Buka Sumber Aduan</a>
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
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-4 border border-opacity-50 h-100 transition-all hover-shadow">
                            <div class="text-muted small fw-bold text-uppercase tracking-wide mb-1" style="font-size: 0.7rem;"><i class="fas fa-building me-1"></i> Instansi Terkait</div>
                            <div class="fw-bold text-dark fs-6">{{ $ticket->assignedOpd->name ?? 'Belum Ditugaskan' }}</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 bg-light rounded-4 border border-opacity-50 h-100 transition-all hover-shadow">
                            <div class="text-muted small fw-bold text-uppercase tracking-wide mb-1" style="font-size: 0.7rem;"><i class="fas fa-exclamation-circle me-1"></i> Prioritas</div>
                            <div class="mt-1">
                                @if(strtolower($ticket->priority) == 'tinggi')
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-3 py-1 fw-bold"><i class="fa-solid fa-arrow-up me-1"></i> Tinggi</span>
                                @elseif(strtolower($ticket->priority) == 'sedang')
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill px-3 py-1 fw-bold text-dark"><i class="fa-solid fa-arrow-right me-1"></i> Sedang</span>
                                @else
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1 fw-bold"><i class="fa-solid fa-arrow-down me-1"></i> Rendah</span>
                                @endif
                            </div>
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

                @php($reportAttachments = $ticket->notification?->attachments ?? [])
                @if(is_array($reportAttachments) && count($reportAttachments))
                    <div class="mt-4">
                        <div class="text-muted small fw-bold text-uppercase tracking-wide mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fas fa-paperclip me-1"></i>Lampiran Pelapor ({{ count($reportAttachments) }})
                        </div>
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

        <div class="d-flex gap-2 mb-4">
            <a href="{{ route('tickets.edit', $ticket->id) }}" class="btn fw-bold rounded-pill py-3 shadow-sm card-premium flex-grow-1 text-white" style="background-color: #0D47A1; border: none; font-size: 1.1rem;">
                <i class="fas fa-edit me-2"></i> Edit Tiket
            </a>
            
            <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket ini secara permanen?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn fw-bold rounded-pill py-3 shadow-sm card-premium text-white px-4" style="background-color: #dc3545; border: none; font-size: 1.1rem;">
                    <i class="fas fa-trash me-2"></i> Hapus
                </button>
            </form>
        </div>

        {{-- Perbarui Status Tiket (Admin Only) --}}
        <div class="card card-premium overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4 fw-bold">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-sync-alt me-2 text-warning"></i> Perbarui Status Tiket</h5>
            </div>
            <div class="card-body px-4 pb-4">
                <form action="{{ route('tickets.status', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.3px;">Pilih Status Baru</label>
                        <select name="status" class="form-select py-2 px-3" style="border-radius: 12px; border: 1px solid #e2e8f0;" required>
                            @if(in_array($ticket->status, ['baru', 'diteruskan']))
                                <option value="" selected disabled>-- Pilih Status Baru --</option>
                            @endif
                            <option value="diterima" {{ $ticket->status == 'diterima' ? 'selected' : '' }}>Diterima (Sedang ditinjau)</option>
                            <option value="proses_disposisi" {{ $ticket->status == 'proses_disposisi' ? 'selected' : '' }}>Proses Disposisi</option>
                            <option value="diproses" {{ $ticket->status == 'diproses' ? 'selected' : '' }}>Diproses (Sedang ditindaklanjuti)</option>
                            <option value="dijawab" {{ $ticket->status == 'dijawab' ? 'selected' : '' }}>Dijawab</option>
                            <option value="selesai" {{ $ticket->status == 'selesai' ? 'selected' : '' }}>Selesai (Masalah terselesaikan)</option>
                            <option value="eskalasi" {{ $ticket->status == 'eskalasi' ? 'selected' : '' }}>Eskalasi (Butuh bantuan instansi lain)</option>
                            <option value="ditolak" {{ $ticket->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.3px;">Catatan (Opsional)</label>
                        <textarea name="notes" class="form-control py-2 px-3" style="border-radius: 12px; border: 1px solid #e2e8f0;" rows="2" placeholder="Catatan log perubahan status..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.3px;">Lampiran Bukti (Opsional)</label>
                        <input type="file" name="attachment" class="form-control" style="border-radius: 12px; border: 1px solid #e2e8f0;" accept=".jpg,.jpeg,.png">
                        <div class="form-text mt-1 small text-muted"><i class="fas fa-info-circle me-1 text-primary"></i> JPG, JPEG, PNG — Maks 5MB</div>
                    </div>
                    <button type="submit" class="btn text-dark fw-bold w-100 rounded-pill py-2" style="background-color: #ffc107; border: none; box-shadow: 0 4px 6px rgba(255, 193, 7, 0.2);">
                        <i class="fas fa-save me-2"></i> Simpan Status
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Timeline & Tanggapan -->
    <div class="col-lg-5">

        <div class="card card-premium overflow-hidden">
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
                            return $item->date->timestamp;
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
                                <span class="text-muted small bg-light px-2 py-1 rounded" style="font-size: 0.75rem;"><i class="far fa-clock me-1"></i> {{ $event->date->format('d M, H:i') }}</span>
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
                                            <a href="{{ asset('storage/' . $event->attachment) }}" target="_blank" class="d-inline-block rounded overflow-hidden border">
                                                <img src="{{ asset('storage/' . $event->attachment) }}" style="height: 80px; object-fit: cover;" alt="Lampiran">
                                            </a>
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
