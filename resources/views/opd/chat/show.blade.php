@extends('layouts.app')

@section('title', 'Live Chat Tiket')

@section('page-title')
    <i class="fa-solid fa-comments me-2"></i>Live Chat Tiket
@endsection

@section('content')
<style>
/* Chat card layout */
.chat-card, .chat-side-card {
    border: 1px solid #e7edf4;
    border-radius: 18px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 10px 28px -18px rgba(15,23,42,.28);
}
.chat-card {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 200px);
    min-height: 500px;
}

/* Header */
.chat-head {
    background: #0d47a1;
    color: #fff;
    padding: 15px 18px;
    flex-shrink: 0;
}
.chat-avatar {
    width: 42px; height: 42px;
    border-radius: 50%;
    display: grid; place-items: center;
    background: rgba(255,255,255,.16);
    font-size: 1.05rem;
}

/* Canvas — fills remaining space */
.chat-canvas {
    flex: 1 1 auto;
    overflow-y: auto;
    padding: 24px;
    background-color: #f4f7fb;
    background-image: radial-gradient(rgba(13,71,161,.08) 1px, transparent 1px);
    background-size: 18px 18px;
}

/* Message bubbles */
.chat-bubble-wrap {
    position: relative;
    max-width: min(78%, 620px);
}
.chat-message {
    padding: 9px 28px 7px 11px;
    border-radius: 9px;
    box-shadow: 0 1px 1px rgba(15,23,42,.09);
    line-height: 1.5;
    position: relative;
}
.chat-message.mine {
    background: #0d47a1;
    color: #fff;
    border-top-right-radius: 2px;
}
.chat-message.theirs {
    background: #fff;
    color: #1e293b;
    border-top-left-radius: 2px;
}
.chat-meta {
    font-size: .68rem;
    text-align: right;
    margin-top: 3px;
    opacity: .72;
}

/* Context menu trigger (chevron) — appears on hover */
.chat-msg-actions {
    position: absolute;
    top: 4px;
    opacity: 0;
    transition: opacity .15s;
    z-index: 2;
}
.chat-bubble-wrap.mine .chat-msg-actions { right: 6px; }
.chat-bubble-wrap.theirs .chat-msg-actions { right: 6px; }
.chat-bubble-wrap:hover .chat-msg-actions { opacity: 1; }
.chat-msg-menu-btn {
    background: none;
    border: none;
    color: inherit;
    font-size: .7rem;
    padding: 2px 5px;
    border-radius: 4px;
    cursor: pointer;
    opacity: .7;
}
.chat-msg-menu-btn:hover { opacity: 1; background: rgba(0,0,0,.06); }
.chat-bubble-wrap.mine .chat-msg-menu-btn { color: rgba(255,255,255,.7); }
.chat-bubble-wrap.mine .chat-msg-menu-btn:hover { color: #fff; background: rgba(255,255,255,.12); }

/* Context dropdown */
.chat-ctx-menu {
    min-width: 160px;
    border-radius: 12px;
    border: 1px solid #e7edf4;
    padding: 6px 0;
    font-size: .85rem;
}
.chat-ctx-menu .dropdown-item {
    padding: 8px 14px;
    border-radius: 0;
}
.chat-ctx-menu .dropdown-item:hover {
    background: #f0f5ff;
}

/* Reply preview bar */
.reply-preview {
    background: #eef4ff;
    border-left: 3px solid #0d47a1;
    padding: 8px 12px;
    margin-bottom: 0;
    border-radius: 0;
}

/* Composer */
.chat-composer {
    background: #f7f9fc;
    border-top: 1px solid #e7edf4;
    padding: 12px;
    flex-shrink: 0;
}
.chat-composer-form {
    display: flex;
    align-items: flex-end;
    gap: 10px;
    width: 100%;
}
.chat-attachment, .chat-send {
    width: 42px; height: 42px; min-width: 42px;
    border-radius: 50%;
    display: grid; place-items: center;
    padding: 0;
}
.chat-send {
    background: #0d47a1; border-color: #0d47a1;
}
.chat-send:hover {
    background: #083b86; border-color: #083b86;
}
.chat-input {
    flex: 1 1 auto;
    min-width: 0;
    min-height: 42px;
    border: 1px solid #dce4ee;
    border-radius: 18px !important;
    resize: none;
    box-shadow: none !important;
    padding: 10px 14px;
    line-height: 1.3;
}
.chat-input:focus {
    border-color: #0d47a1;
    box-shadow: 0 0 0 .2rem rgba(13,71,161,.12) !important;
}

/* Side cards */
.chat-side-card .card-header {
    padding: 16px 18px;
    background: #fff;
    border-bottom: 1px solid #edf1f5;
}
.chat-side-card .card-body {
    padding: 18px;
}
.ticket-label {
    color: #64748b;
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 4px;
}
.ticket-info-tile {
    height: 100%;
    padding: 10px;
    border: 1px solid #edf1f5;
    border-radius: 12px;
    background: #f8fafc;
}
.complaint-preview {
    padding: 13px;
    border-left: 4px solid #0d47a1;
    border-radius: 0 12px 12px 0;
    background: #eef4ff;
}

/* Info modal */
.msg-info-modal .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
}

/* Responsive */
@media (max-width: 1199px) {
    .chat-card { height: calc(100vh - 220px); min-height: 420px; }
}
@media (max-width: 575px) {
    .chat-card { height: calc(100vh - 180px); min-height: 380px; }
    .chat-canvas { padding: 14px; }
    .chat-bubble-wrap { max-width: 88%; }
    .chat-head { padding: 12px; }
    .chat-composer { padding: 10px; }
    .chat-composer-form { gap: 8px; }
    .chat-attachment, .chat-send { width: 40px; height: 40px; min-width: 40px; }
    .chat-input { min-height: 40px; padding: 9px 12px; }
}
</style>

<div class="mb-3">
    <a href="{{ route('opd.tickets.index') }}" class="text-decoration-none small fw-semibold">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Tiket
    </a>
</div>

<div class="row">
    <div class="col-xl-8 mb-4 mb-xl-0">
        <div class="chat-card">
            <header class="chat-head d-flex align-items-center gap-3">
                <div class="chat-avatar"><i class="fas fa-landmark"></i></div>
                <div class="flex-grow-1">
                    <div class="fw-bold">Admin KMC</div>
                    <div class="small text-white-50">Percakapan terkait {{ $ticket->tracking_number ?? $ticket->ticket_number }}</div>
                </div>
                <span class="badge rounded-pill" style="background:rgba(255,255,255,.14)"><i class="fas fa-comments me-1"></i>Chat KMC</span>
            </header>

            <div class="chat-canvas" id="opd-chat-messages">
                @forelse($ticket->chatMessages as $message)
                    @php $mine = $message->sender_id === auth()->id(); @endphp
                    <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="chat-bubble-wrap {{ $mine ? 'mine' : 'theirs' }}">
                            <article class="chat-message {{ $mine ? 'mine' : 'theirs' }}">
                                <div class="small fw-bold mb-1 {{ $mine ? 'text-white-50' : 'text-primary' }}">{{ $mine ? 'Anda' : ($message->sender?->name ?? 'Admin KMC') }}</div>
                                <div class="chat-msg-text" style="white-space:pre-line">{{ $message->message }}</div>
                                @if($message->attachment)
                                    <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" class="d-block mt-2 small {{ $mine ? 'text-white' : 'text-primary' }}"><i class="fas fa-paperclip me-1"></i>Lihat lampiran</a>
                                @endif
                                <div class="chat-meta">{{ $message->created_at?->format('H:i') }}</div>
                            </article>
                            <!-- WhatsApp-style dropdown chevron -->
                            <div class="chat-msg-actions">
                                <button class="chat-msg-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-chevron-down"></i></button>
                                <ul class="dropdown-menu dropdown-menu-{{ $mine ? 'end' : 'start' }} chat-ctx-menu shadow-lg">
                                    <li><a class="dropdown-item" href="#" onclick="showMsgInfo({{ $message->id }}, '{{ $message->created_at?->format('d M Y, H:i') }}', '{{ $mine ? 'Anda' : addslashes($message->sender?->name ?? 'Admin KMC') }}'); return false;"><i class="fas fa-info-circle me-2 text-muted"></i>Info</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="replyMsg({{ $message->id }}, {{ json_encode(Str::limit($message->message, 60)) }}); return false;"><i class="fas fa-reply me-2 text-muted"></i>Balas</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="copyMsg(this); return false;" data-msg="{{ $message->message }}"><i class="fas fa-copy me-2 text-muted"></i>Salin</a></li>
                                    @if($mine)
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('opd.chat.delete', $message) }}" method="POST" onsubmit="return confirm('Hapus pesan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Hapus</button>
                                        </form>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="h-100 d-flex align-items-center justify-content-center text-center text-muted">
                        <div>
                            <i class="far fa-comment-dots fs-1 mb-3 text-primary opacity-50"></i>
                            <div class="fw-semibold">Belum ada percakapan</div>
                            <small>Mulai koordinasi dengan Admin KMC melalui kolom pesan di bawah.</small>
                        </div>
                    </div>
                @endforelse
            </div>

            <footer class="chat-composer">
                <div id="reply-preview" class="reply-preview d-none">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="small text-primary fw-semibold text-truncate" id="reply-preview-text"></div>
                        <button type="button" class="btn-close btn-close-sm" onclick="cancelReply()"></button>
                    </div>
                </div>
                <form action="{{ route('opd.chat.send', $ticket) }}" method="POST" enctype="multipart/form-data" class="chat-composer-form">
                    @csrf
                    <label for="opd-chat-attachment" class="btn btn-light border chat-attachment" title="Lampiran"><i class="fas fa-paperclip"></i></label>
                    <input id="opd-chat-attachment" name="attachment" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.3gp">
                    <textarea id="opd-chat-message" name="message" class="form-control chat-input" rows="1" maxlength="2000" placeholder="Kirim pesan chat untuk Admin KMC..." required></textarea>
                    <button class="btn btn-primary chat-send" type="submit" title="Kirim pesan"><i class="fas fa-paper-plane"></i></button>
                </form>
            </footer>
        </div>
    </div>

    <div class="col-xl-4">
        <aside class="chat-side-card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span class="fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Informasi Aduan</span>
                @php $chatStatusClass = match($ticket->status) { 'selesai' => 'bg-success', 'eskalasi' => 'bg-danger', 'diproses' => 'bg-warning text-dark', 'dijawab' => 'bg-primary', default => 'bg-info text-dark' }; @endphp
                <span class="badge {{ $chatStatusClass }} rounded-pill px-2 py-1">{{ $ticket->status === 'proses_disposisi' ? 'Disposisi' : ucfirst($ticket->status) }}</span>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="ticket-label">Laporan</div>
                    <div class="fw-bold text-dark">#{{ $ticket->tracking_number ?? $ticket->ticket_number }}</div>
                    <div class="small text-muted mt-1"><i class="far fa-clock me-1"></i>{{ $ticket->created_at?->format('d M Y, H:i') }} WIB</div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-6"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-user me-1"></i>Pelapor</div><div class="fw-semibold small text-dark text-truncate">{{ $ticket->reporter_name ?? 'Anonim' }}</div></div></div>
                    <div class="col-6"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-building me-1"></i>OPD</div><div class="fw-semibold small text-dark text-truncate">{{ $ticket->assignedOpd?->name ?? '-' }}</div></div></div>
                    <div class="col-12"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-tag me-1"></i>Kategori Masalah</div><div class="fw-semibold small text-dark">{{ $ticket->category }}</div>@if($ticket->sub_category)<div class="small text-muted">{{ $ticket->sub_category }}</div>@endif</div></div>
                </div>
                <div class="complaint-preview">
                    <div class="ticket-label mb-2"><i class="fas fa-quote-left me-1"></i>Isi Aduan</div>
                    <div class="small text-dark" style="white-space:pre-line">{{ $ticket->complaint }}</div>
                </div>
                @php $reportAttachments = $ticket->notification?->attachments ?? []; @endphp
                @if(is_array($reportAttachments) && count($reportAttachments))
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3 fw-semibold" data-bs-toggle="modal" data-bs-target="#ticketAttachmentsModal">
                        <i class="fas fa-paperclip me-1"></i>Lihat Lampiran ({{ count($reportAttachments) }})
                    </button>
                    <div class="modal fade" id="ticketAttachmentsModal" tabindex="-1" aria-labelledby="ticketAttachmentsModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                            <div class="modal-content border-0 rounded-4 overflow-hidden">
                                <div class="modal-header bg-primary text-white">
                                    <h5 class="modal-title fs-6 fw-bold" id="ticketAttachmentsModalLabel"><i class="fas fa-paperclip me-2"></i>Lampiran Aduan</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body bg-light">
                                    <div class="row g-3">
                                        @foreach($reportAttachments as $index => $path)
                                            @php $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION)); $isVideo = in_array($extension, ['mp4', 'mov', 'avi', '3gp', 'webm']); @endphp
                                            <div class="col-12">
                                                <div class="border rounded-3 overflow-hidden bg-white p-2">
                                                    @if($isVideo)
                                                        <video controls class="w-100 rounded-2" style="max-height:420px"><source src="{{ asset('storage/' . $path) }}" type="video/{{ $extension }}">Browser Anda tidak mendukung video.</video>
                                                    @else
                                                        <img src="{{ asset('storage/' . $path) }}" alt="Lampiran aduan {{ $index + 1 }}" class="w-100 rounded-2" style="max-height:420px;object-fit:contain">
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </aside>

        <aside class="chat-side-card">
            <div class="card-header fw-bold"><i class="fas fa-comment-dots text-warning me-2"></i>Berikan Tanggapan</div>
            <div class="card-body">
                <div class="small text-muted mb-3">Tanggapan resmi akan dicatat pada Riwayat Perjalanan Aduan dan memperbarui status tiket menjadi Dijawab.</div>
                <form action="{{ route('opd.tickets.respond', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <textarea id="official-response" name="response_text" class="form-control mb-3" rows="4" placeholder="Tulis tanggapan resmi untuk aduan ini..." required></textarea>
                    <input id="official-response-attachment" name="attachment" type="file" class="form-control mb-3" accept=".jpg,.jpeg,.png">
                    <div class="form-text small mb-3">Lampiran tanggapan: JPG, JPEG, PNG — maksimal 5 MB.</div>
                    <button class="btn btn-warning w-100 fw-bold" type="submit"><i class="fas fa-paper-plane me-1"></i>Kirim Tanggapan Resmi</button>
                </form>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-scroll to bottom
const chatCanvas = document.getElementById('opd-chat-messages');
if (chatCanvas) chatCanvas.scrollTop = chatCanvas.scrollHeight;

// Copy message
function copyMsg(el) {
    const text = el.getAttribute('data-msg');
    navigator.clipboard.writeText(text).then(() => {
        const orig = el.innerHTML;
        el.innerHTML = '<i class="fas fa-check me-2 text-success"></i>Tersalin!';
        setTimeout(() => { el.innerHTML = orig; }, 1500);
    });
}

// Reply to message
function replyMsg(msgId, preview) {
    const box = document.getElementById('reply-preview');
    const text = document.getElementById('reply-preview-text');
    box.classList.remove('d-none');
    text.textContent = preview;
    document.getElementById('opd-chat-message').focus();
}
function cancelReply() {
    document.getElementById('reply-preview').classList.add('d-none');
}

// Message info modal
function showMsgInfo(id, time, sender) {
    const old = document.getElementById('msgInfoModal');
    if (old) old.remove();
    
    const html = `
    <div class="modal fade msg-info-modal" id="msgInfoModal" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white py-2">
                    <h6 class="modal-title fw-bold"><i class="fas fa-info-circle me-2"></i>Info Pesan</h6>
                    <button type="button" class="btn-close btn-close-white btn-sm" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2"><span class="text-muted small text-uppercase fw-bold">Pengirim</span><div class="fw-semibold">${sender}</div></div>
                    <div><span class="text-muted small text-uppercase fw-bold">Waktu</span><div class="fw-semibold">${time}</div></div>
                </div>
            </div>
        </div>
    </div>`;
    document.body.insertAdjacentHTML('beforeend', html);
    const modal = new bootstrap.Modal(document.getElementById('msgInfoModal'));
    modal.show();
}
</script>
@endpush
