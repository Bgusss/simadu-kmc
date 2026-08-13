@extends('layouts.app')

@section('title', 'Live Chat Tiket')
@section('page-title')
    <i class="fa-solid fa-comments text-primary me-2"></i> Live Chat
@endsection

@section('content')
<style>
    .chat-card, .chat-side-card { border: 1px solid #e7edf4; border-radius: 18px; overflow: hidden; background: #fff; box-shadow: 0 10px 28px -18px rgba(15,23,42,.28); }
    .chat-head { background: #0d47a1; color: #fff; padding: 15px 18px; }
    .chat-avatar { width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; background: rgba(255,255,255,.16); font-size: 1.05rem; }
    .chat-canvas { height: 62vh; min-height: 430px; overflow-y: auto; padding: 24px; background-color: #f4f7fb; background-image: radial-gradient(rgba(13,71,161,.08) 1px, transparent 1px); background-size: 18px 18px; }
    .chat-message { max-width: min(78%, 620px); padding: 9px 11px 7px; border-radius: 9px; box-shadow: 0 1px 1px rgba(15,23,42,.09); line-height: 1.5; }
    .chat-message.mine { background: #0d47a1; color: #fff; border-top-right-radius: 2px; }
    .chat-message.theirs { background: #fff; color: #1e293b; border-top-left-radius: 2px; }
    .chat-meta { font-size: .68rem; text-align: right; margin-top: 3px; opacity: .72; }
    .chat-composer { background: #f7f9fc; border-top: 1px solid #e7edf4; padding: 12px; display: flex; gap: 9px; align-items: end; }
    .chat-input { border: 1px solid #dce4ee; border-radius: 18px !important; resize: none; box-shadow: none !important; padding: 10px 14px; }
    .chat-send { height: 42px; width: 42px; border-radius: 50%; display: grid; place-items: center; padding: 0; }
    .chat-side-card .card-header { padding: 16px 18px; background: #fff; border-bottom: 1px solid #edf1f5; }
    .chat-side-card .card-body { padding: 18px; }
    .ticket-label { color: #64748b; font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 4px; }
    .ticket-info-tile { height: 100%; padding: 10px; border: 1px solid #edf1f5; border-radius: 12px; background: #f8fafc; }
    .complaint-preview { padding: 13px; border-left: 4px solid #0d47a1; border-radius: 0 12px 12px 0; background: #eef4ff; }
    @media (max-width: 1199px) { .chat-canvas { height: 55vh; min-height: 360px; } }
    @media (max-width: 575px) { .chat-canvas { height: 52vh; min-height: 320px; padding: 14px; } .chat-message { max-width: 88%; } .chat-head { padding: 12px; } .chat-composer { padding: 9px; } }
</style>

<div class="mb-3"><a href="{{ route('opd.tickets.index') }}" class="text-decoration-none small fw-semibold"><i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Tiket</a></div>

<div class="row g-4">
    <div class="col-xl-8">
        <section class="chat-card h-100">
            <header class="chat-head d-flex align-items-center gap-3">
                <div class="chat-avatar"><i class="fas fa-landmark"></i></div>
                <div class="flex-grow-1"><div class="fw-bold">Admin KMC</div><div class="small text-white-50">Percakapan terkait {{ $ticket->tracking_number ?? $ticket->ticket_number }}</div></div>
                <span class="badge rounded-pill" style="background:rgba(255,255,255,.14)"><i class="fas fa-comments me-1"></i>Chat KMC</span>
            </header>
            <main id="opd-chat-messages" class="chat-canvas">
                @forelse($ticket->chatMessages as $message)
                    @php $mine = $message->sender_id === auth()->id(); @endphp
                    <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                        <article class="chat-message {{ $mine ? 'mine' : 'theirs' }}">
                            <div class="small fw-bold mb-1 {{ $mine ? 'text-white-50' : 'text-primary' }}">{{ $mine ? 'Anda' : ($message->sender?->name ?? 'Admin KMC') }}</div>
                            <div style="white-space:pre-line">{{ $message->message }}</div>
                            @if($message->attachment)<a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" class="d-block mt-2 small {{ $mine ? 'text-white' : 'text-primary' }}"><i class="fas fa-paperclip me-1"></i>Lihat lampiran</a>@endif
                            <div class="chat-meta">{{ $message->created_at?->format('H:i') }}</div>
                        </article>
                    </div>
                @empty
                    <div class="h-100 d-flex align-items-center justify-content-center text-center text-muted"><div><i class="far fa-comment-dots fs-1 mb-3 text-primary opacity-50"></i><div class="fw-semibold">Belum ada percakapan</div><small>Mulai koordinasi dengan Admin KMC melalui kolom pesan di bawah.</small></div></div>
                @endforelse
            </main>
            <footer class="chat-composer">
                <label for="opd-chat-attachment" class="btn btn-light border rounded-circle mb-0" title="Lampiran"><i class="fas fa-paperclip"></i></label>
                <form action="{{ route('opd.chat.send', $ticket) }}" method="POST" enctype="multipart/form-data" class="d-flex flex-grow-1 gap-2 align-items-end">
                    @csrf
                    <input id="opd-chat-attachment" name="attachment" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.3gp">
                    <textarea name="message" class="form-control chat-input" rows="1" maxlength="2000" placeholder="Kirim pesan chat untuk Admin KMC..." required></textarea>
                    <button class="btn btn-primary chat-send" type="submit" title="Kirim pesan"><i class="fas fa-paper-plane"></i></button>
                </form>
            </footer>
        </section>
    </div>
    <div class="col-xl-4">
        <aside class="chat-side-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Aduan</span>
                @php $chatStatusClass = match($ticket->status) { 'selesai' => 'bg-success', 'eskalasi' => 'bg-danger', 'diproses' => 'bg-warning text-dark', 'dijawab' => 'bg-primary', default => 'bg-info text-dark' }; @endphp
                <span class="badge {{ $chatStatusClass }} rounded-pill px-2 py-1">{{ $ticket->status === 'proses_disposisi' ? 'Disposisi' : ucfirst($ticket->status) }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom"><div class="ticket-label">Laporan</div><div class="fw-bold text-dark">#{{ $ticket->tracking_number ?? $ticket->ticket_number }}</div><div class="small text-muted mt-1"><i class="far fa-clock me-1"></i>{{ $ticket->created_at?->format('d M Y, H:i') }} WIB</div></div>
                <div class="row g-2 mb-3"><div class="col-6"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-user me-1"></i>Pelapor</div><div class="fw-semibold small text-dark text-truncate">{{ $ticket->reporter_name ?? 'Anonim' }}</div></div></div><div class="col-6"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-building me-1"></i>OPD</div><div class="fw-semibold small text-dark text-truncate">{{ $ticket->assignedOpd?->name ?? '-' }}</div></div></div><div class="col-12"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-tag me-1"></i>Kategori Masalah</div><div class="fw-semibold small text-dark">{{ $ticket->category }}</div>@if($ticket->sub_category)<div class="small text-muted">{{ $ticket->sub_category }}</div>@endif</div></div></div>
                <div class="complaint-preview"><div class="ticket-label mb-2"><i class="fas fa-quote-left me-1"></i>Isi Aduan</div><div class="small text-dark" style="white-space:pre-line">{{ $ticket->complaint }}</div></div>
                @php $reportAttachments = $ticket->notification?->attachments ?? []; @endphp
                @if(is_array($reportAttachments) && count($reportAttachments))
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#ticketAttachmentsModal"><i class="fas fa-paperclip me-1"></i>Lihat Lampiran ({{ count($reportAttachments) }})</button>
                    <div class="modal fade" id="ticketAttachmentsModal" tabindex="-1" aria-labelledby="ticketAttachmentsModalLabel" aria-hidden="true"><div class="modal-dialog modal-lg modal-dialog-scrollable"><div class="modal-content border-0 rounded-4 overflow-hidden"><div class="modal-header bg-primary text-white"><h5 class="modal-title fs-6 fw-bold" id="ticketAttachmentsModalLabel"><i class="fas fa-paperclip me-2"></i>Lampiran Aduan</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button></div><div class="modal-body bg-light"><div class="row g-3">@foreach($reportAttachments as $index => $path)@php $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION)); $isVideo = in_array($extension, ['mp4', 'mov', 'avi', '3gp', 'webm']); @endphp<div class="col-12"><div class="border rounded-3 overflow-hidden bg-white p-2">@if($isVideo)<video controls class="w-100 rounded-2" style="max-height:420px"><source src="{{ asset('storage/' . $path) }}" type="video/{{ $extension }}">Browser Anda tidak mendukung video.</video>@else<img src="{{ asset('storage/' . $path) }}" alt="Lampiran aduan {{ $index + 1 }}" class="w-100 rounded-2" style="max-height:420px;object-fit:contain">@endif</div></div>@endforeach</div></div></div></div></div>
                @endif
            </div>
        </aside>
    </div>
</div>

@push('scripts')<script>const messages=document.getElementById('opd-chat-messages');if(messages)messages.scrollTop=messages.scrollHeight;</script>@endpush
@endsection
