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
    position: relative;
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
    background: #ffffff;
    font-size: 1.05rem;
    color: #0d47a1;
    overflow: hidden;
    flex-shrink: 0;
}
.chat-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-color: #ffffff;
}

/* Canvas — fills remaining space */
.chat-canvas {
    flex: 1 1 auto;
    min-height: 0;
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
    padding: 7px 22px 6px 11px;
    border-radius: 7.5px;
    box-shadow: 0 1px 1.5px rgba(15,23,42,.15);
    line-height: 1.42;
    position: relative;
}
.chat-message.mine {
    background: #0d47a1;
    color: #fff;
    border-top-right-radius: 0px !important;
}
.chat-message.mine::after {
    content: "";
    position: absolute;
    top: 0;
    right: -6px;
    width: 8px;
    height: 12px;
    background: #0d47a1;
    clip-path: polygon(0 0, 0 100%, 100% 0);
}
.chat-message.theirs {
    background: #fff;
    color: #1e293b;
    border-top-left-radius: 0px !important;
    border: 1px solid #e2e8f0;
}
.chat-message.theirs::after {
    content: "";
    position: absolute;
    top: 0;
    left: -6px;
    width: 8px;
    height: 12px;
    background: #fff;
    clip-path: polygon(100% 0, 100% 100%, 0 0);
}
.chat-meta {
    font-size: .68rem;
    text-align: right;
    margin-top: 3px;
    opacity: .72;
}
.chat-receipt {
    display: inline-block;
    width: 15px;
    height: 12px;
    margin-left: 2px;
    vertical-align: -2px;
    color: currentColor;
    background-color: currentColor;
    -webkit-mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 12'%3E%3Cpath d='M1 6.5 4 9.5 9 2.5' fill='none' stroke='%23000' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M6 6.5 9 9.5 15 2.5' fill='none' stroke='%23000' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") center / contain no-repeat;
    mask: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 12'%3E%3Cpath d='M1 6.5 4 9.5 9 2.5' fill='none' stroke='%23000' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3Cpath d='M6 6.5 9 9.5 15 2.5' fill='none' stroke='%23000' stroke-width='1.8' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E") center / contain no-repeat;
    opacity: 0.75;
}
.chat-receipt.sent {
    opacity: 0.75;
}
.chat-receipt.read { color: #f57c00; opacity: 1; }
.chat-scroll-latest {
    position: absolute; right: 18px; bottom: 82px;
    width: 42px; height: 42px; border: 0; border-radius: 50%;
    display: grid; place-items: center;
    background: #fff; color: #0d47a1;
    box-shadow: 0 5px 18px rgba(15,23,42,.2); z-index: 3;
    transition: transform .15s, opacity .15s, bottom .2s ease;
}
.chat-scroll-latest:hover { transform: translateY(-2px); }
.chat-scroll-latest.d-none { display: none; }
.chat-card:has(#reply-preview:not(.d-none)) .chat-scroll-latest {
    bottom: 138px !important;
}
.chat-card:has(.composer-file-preview:not(.d-none)) .chat-scroll-latest {
    bottom: 145px !important;
}
.chat-card:has(#reply-preview:not(.d-none)):has(.composer-file-preview:not(.d-none)) .chat-scroll-latest {
    bottom: 195px !important;
}

/* Image & Video thumbnail hover download button */
.chat-img-wrap, .chat-video-wrap {
    position: relative;
    display: inline-block;
    border-radius: 8px;
    overflow: hidden;
    background: #000;
}
.chat-video-wrap {
    max-width: 280px;
    cursor: pointer;
}
.chat-video-wrap .chat-video-play-btn {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.55);
    backdrop-filter: blur(4px);
    transition: transform 0.2s ease, background 0.2s ease;
}
.chat-video-wrap:hover .chat-video-play-btn {
    transform: scale(1.1);
    background: rgba(13, 71, 161, 0.85);
}
.chat-img-wrap .chat-img-dl-btn, .chat-video-wrap .chat-img-dl-btn {
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.2s ease, visibility 0.2s ease, transform 0.2s ease;
    transform: translateY(4px);
}
.chat-img-wrap:hover .chat-img-dl-btn, .chat-video-wrap:hover .chat-img-dl-btn {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
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
    min-width: 170px;
    border-radius: 14px;
    border: 1px solid rgba(0, 0, 0, 0.08);
    padding: 6px 0;
    font-size: .88rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
}
.chat-ctx-menu .dropdown-item {
    padding: 9px 16px;
    font-weight: 500;
    color: #2d3748;
    display: flex;
    align-items: center;
    gap: 8px;
}
.chat-ctx-menu .dropdown-item i {
    font-size: 1rem;
    width: 20px;
    text-align: center;
    color: #64748b;
}
.chat-ctx-menu .dropdown-item:hover, .chat-ctx-menu .dropdown-item:focus {
    background: #f1f5f9;
    color: #0f172a;
}
.chat-ctx-menu .dropdown-item.text-danger i {
    color: #ef4444 !important;
}

@media (max-width: 767.98px) {
    .chat-msg-menu-btn {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
        transition: opacity 0.2s ease, visibility 0.2s ease;
    }
    .chat-bubble-wrap.show-menu-btn .chat-msg-menu-btn {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
        width: 26px !important;
        height: 26px !important;
        margin-left: 4px;
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
    }
}

/* WhatsApp-style Contracting Side Panel Layout */
.chat-main-column {
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
    min-width: 0;
}
.chat-search-side-panel {
    width: 350px;
    max-width: 45%;
    flex-shrink: 0;
    background: #ffffff;
    border-left: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    height: 100%;
    animation: searchPanelSlideIn 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
@keyframes searchPanelSlideIn {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}
.chat-search-pill {
    transition: all 0.2s ease;
    border: 2px solid #e2e8f0 !important;
}
.chat-search-pill:focus-within {
    border-color: #059669 !important;
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.15) !important;
    background: #ffffff !important;
}
.search-result-item:hover {
    background-color: #f1f5f9 !important;
}
.chat-bubble-wrap.search-active {
    filter: drop-shadow(1px 0 0 #f57c00) drop-shadow(-1px 0 0 #f57c00) drop-shadow(0 1px 0 #f57c00) drop-shadow(0 -1px 0 #f57c00) drop-shadow(0 4px 14px rgba(245, 124, 0, 0.35)) !important;
    animation: searchPulse 1.4s ease-in-out infinite alternate !important;
}
.chat-bubble-wrap.search-active .chat-message {
    background: #fff7ed !important;
    color: #1e293b !important;
    border: none !important;
}
.chat-bubble-wrap.search-active .chat-message.mine {
    background: #0d47a1 !important;
    color: #ffffff !important;
}
.chat-bubble-wrap.search-active .chat-message.theirs::after {
    background: #fff7ed !important;
    top: 0 !important;
    left: -6px !important;
}
.chat-bubble-wrap.search-active .chat-message.mine::after {
    background: #0d47a1 !important;
    top: 0 !important;
    right: -6px !important;
}
@keyframes searchPulse {
    from {
        filter: drop-shadow(1px 0 0 #f57c00) drop-shadow(-1px 0 0 #f57c00) drop-shadow(0 1px 0 #f57c00) drop-shadow(0 -1px 0 #f57c00) drop-shadow(0 2px 8px rgba(245, 124, 0, 0.25));
    }
    to {
        filter: drop-shadow(1.5px 0 0 #ff9800) drop-shadow(-1.5px 0 0 #ff9800) drop-shadow(0 1.5px 0 #ff9800) drop-shadow(0 -1.5px 0 #ff9800) drop-shadow(0 6px 18px rgba(245, 124, 0, 0.55));
    }
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
    max-height: 140px;
    border: 1px solid #dce4ee;
    border-radius: 18px !important;
    resize: none;
    box-shadow: none !important;
    padding: 10px 14px;
    line-height: 1.35;
    overflow-y: auto;
    transition: height 0.1s ease-out;
}
.chat-input:focus {
    border-color: #0d47a1;
    box-shadow: 0 0 0 .2rem rgba(13,71,161,.12) !important;
}

/* Attachment preview in bubbles */
.chat-attach-preview { max-width: 280px; }
.chat-attach-img {
    width: 100%; max-height: 220px;
    object-fit: cover; border-radius: 8px;
    cursor: pointer; display: block;
}
.chat-attach-video {
    width: 100%; max-height: 220px;
    border-radius: 8px; display: block;
    background: #000;
}
.chat-attach-doc {
    display: flex; align-items: center; gap: 10px;
    padding: 10px 12px; border-radius: 8px;
    background: rgba(0,0,0,.06);
    text-decoration: none; color: #1e293b;
    transition: background .15s;
}
.chat-attach-doc:hover { background: rgba(0,0,0,.1); }
.chat-attach-doc.mine { background: rgba(255,255,255,.15); color: #fff; }
.chat-attach-doc.mine:hover { background: rgba(255,255,255,.22); }
.chat-attach-doc-icon { font-size: 1.6rem; opacity: .7; flex-shrink: 0; }
.chat-attach-doc-info { flex: 1; min-width: 0; }
.chat-attach-doc-name { font-size: .8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-attach-doc-meta { font-size: .68rem; opacity: .6; }
.chat-attach-doc-dl { font-size: .85rem; opacity: .5; flex-shrink: 0; }

/* Attachment preview before send */
.composer-file-preview {
    display: flex; align-items: center; gap: 10px;
    width: 100%; padding: 8px 10px;
    margin-bottom: 10px; border-radius: 10px;
    background: #eef4ff; border-left: 3px solid #0d47a1;
}
.composer-file-preview img {
    width: 48px; height: 48px; object-fit: cover;
    border-radius: 7px; flex-shrink: 0;
}
.composer-file-icon {
    width: 48px; height: 48px; border-radius: 7px;
    display: grid; place-items: center; flex-shrink: 0;
    background: #dfeaff; color: #0d47a1; font-size: 1.4rem;
}
.composer-file-info { min-width: 0; flex: 1; }
.composer-file-name { font-size: .78rem; font-weight: 700; color: #1e293b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.composer-file-meta { font-size: .68rem; color: #64748b; margin-top: 2px; }
.composer-file-remove { border: 0; background: transparent; color: #64748b; padding: 5px; }
.composer-file-remove:hover { color: #dc3545; }

/* Attachment dropdown */
.chat-attach-dropdown {
    position: relative;
    display: inline-block;
}
.chat-attach-dropdown .dropdown-menu {
    min-width: 180px;
    border-radius: 12px;
    border: 1px solid #e7edf4;
    padding: 6px 0;
    font-size: .85rem;
    bottom: 100%;
    top: auto;
    margin-bottom: 6px;
}
.chat-attach-dropdown .dropdown-item {
    padding: 9px 14px;
}
.chat-attach-dropdown .dropdown-item:hover {
    background: #f0f5ff;
}
.chat-attach-dropdown .dropdown-item i {
    width: 20px;
    text-align: center;
}
.chat-side-card {
    background: #ffffff;
    border-radius: 16px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 4px 20px rgba(15, 23, 42, 0.05) !important;
    overflow: hidden;
    transition: box-shadow 0.2s ease, transform 0.2s ease;
}
.chat-side-card:hover {
    box-shadow: 0 8px 28px rgba(15, 23, 42, 0.08) !important;
}
.chat-side-card .card-header {
    padding: 14px 18px;
    background: #ffffff;
    border-bottom: 1px solid #f1f5f9;
}
.chat-side-card .card-body {
    padding: 18px;
}
.chat-side-card .form-select,
.chat-side-card .form-control {
    border-radius: 10px !important;
    border: 1px solid #cbd5e1 !important;
    padding: 9px 13px !important;
    font-size: 0.88rem !important;
    box-shadow: none !important;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}
.chat-side-card .form-select:focus,
.chat-side-card .form-control:focus {
    border-color: #0d47a1 !important;
    box-shadow: 0 0 0 3px rgba(13, 71, 161, 0.12) !important;
}
.chat-side-card .btn {
    border-radius: 10px !important;
    padding: 9px 16px !important;
    font-size: 0.88rem !important;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.chat-side-card .btn:hover {
    transform: translateY(-1px);
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
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #f8fafc;
}
.complaint-preview {
    padding: 14px 16px;
    border-left: 4px solid #0d47a1;
    border-radius: 4px 12px 12px 4px;
    background: #f0f7ff;
}

/* Info modal */
.msg-info-modal .modal-content {
    border: 0;
    border-radius: 16px;
    overflow: hidden;
}

/* Responsive */
@media (max-width: 1199px) {
    .chat-card {
        height: calc(100dvh - 170px);
        height: calc(100vh - 170px);
        min-height: 400px;
    }
}
@media (max-width: 767px) {
    .chat-card {
        height: calc(100dvh - 215px) !important;
        height: calc(100vh - 215px) !important;
        max-height: calc(100dvh - 215px) !important;
        min-height: 280px !important;
        margin-bottom: 0 !important;
    }
    .chat-canvas { padding: 12px; }
    .chat-bubble-wrap { max-width: 90%; }
    .chat-head { padding: 10px 12px; }
    .chat-composer {
        padding: 8px 10px 10px 10px !important;
        background: #ffffff !important;
        border-top: 1px solid #e2e8f0 !important;
    }
    .chat-composer-form { gap: 6px; }
    .chat-attachment, .chat-send { width: 38px; height: 38px; min-width: 38px; }
    .chat-input { min-height: 38px; padding: 8px 12px; font-size: 0.9rem; }
}
</style>

<div class="d-flex align-items-center justify-content-between mb-3">
    <a href="{{ route('opd.tickets.index') }}" class="text-decoration-none small fw-semibold">
        <i class="fas fa-arrow-left me-1"></i>Kembali ke Daftar Tiket
    </a>
    <div class="d-flex align-items-center gap-2 d-xl-none">
        <button type="button" class="btn btn-primary rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="modal" data-bs-target="#mobileTicketInfoModal" style="width: 36px; height: 36px;" title="Informasi Aduan">
            <i class="fas fa-info-circle"></i>
        </button>
        <button type="button" class="btn btn-warning text-dark rounded-circle p-0 d-flex align-items-center justify-content-center shadow-sm" data-bs-toggle="modal" data-bs-target="#mobileUpdateStatusModal" style="width: 36px; height: 36px;" title="Berikan Tanggapan">
            <i class="fas fa-comment-dots"></i>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-xl-8 mb-4 mb-xl-0">
        <div class="chat-card">
            @php
                $adminUser = \App\Models\User::where('role', 'admin')->first();
                $adminPhoto = $adminUser?->profile_photo ? asset('storage/' . $adminUser->profile_photo) : null;
            @endphp
            <header class="chat-head d-flex align-items-center gap-3">
                <div class="chat-avatar p-0 overflow-hidden">
                    @if($adminPhoto)
                        <img src="{{ $adminPhoto }}" alt="Admin KMC" class="w-100 h-100" style="object-fit: cover; border-radius: 50%;">
                    @else
                        <i class="fas fa-user-shield"></i>
                    @endif
                </div>
                <div class="flex-grow-1 overflow-hidden">
                    <div class="fw-bold text-truncate">Admin KMC</div>
                    <div class="small text-white-50 text-truncate">Percakapan terkait {{ $ticket->tracking_number ?? $ticket->ticket_number }}</div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-sm text-white opacity-85 hover-opacity-100 p-1 border-0" onclick="toggleChatSearch()" title="Cari pesan">
                        <i class="fas fa-search fs-5"></i>
                    </button>
                    <button type="button" class="btn btn-sm text-white opacity-85 hover-opacity-100 p-1 border-0" data-bs-toggle="modal" data-bs-target="#chatSettingsModal" title="Pengaturan Chat">
                        <i class="fas fa-cog fs-5"></i>
                    </button>
                </div>
            </header>

            <!-- Main Flex Row Container -->
            <div class="d-flex flex-grow-1 overflow-hidden position-relative" style="min-height: 0;">
                
                <!-- Left Main Column (contracts to left in search mode) -->
                <div class="chat-main-column d-flex flex-column flex-grow-1 overflow-hidden position-relative" id="chat-main-column">
                    <div class="chat-canvas position-relative" id="opd-chat-messages">
                        @forelse($ticket->chatMessages as $message)
                            @php 
                                $mine = $message->sender_id === auth()->id(); 
                                $deliveredAtVal = $message->delivered_at ?? $message->created_at;
                                $readAtVal = $message->read_at ?? ($message->read_by_admin ? ($message->updated_at ?? $message->created_at) : null);
                                $sentAtStr = $message->created_at?->format('j/n/Y \p\u\k\u\l H.i') ?? '-';
                                $deliveredAtStr = $deliveredAtVal?->format('j/n/Y \p\u\k\u\l H.i') ?? '-';
                                $readAtStr = $readAtVal?->format('j/n/Y \p\u\k\u\l H.i') ?? '-';
                            @endphp
                            <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}" data-chat-message-id="{{ $message->id }}" data-created-date="{{ $message->created_at?->format('Y-m-d') }}" data-sent-at="{{ $sentAtStr }}" data-delivered-at="{{ $deliveredAtStr }}" data-read-at="{{ $readAtStr }}">
                                <div class="chat-bubble-wrap {{ $mine ? 'mine' : 'theirs' }}">
                                    <article class="chat-message {{ $mine ? 'mine' : 'theirs' }}">
                                        <div class="small fw-bold mb-1 {{ $mine ? 'text-white-50' : 'text-primary' }}">{{ $mine ? 'Anda' : ($message->sender?->name ?? 'Admin KMC') }}</div>
                                        @if($message->replyTo)
                                            <div class="chat-reply-quote mb-2 p-2 rounded-2 cursor-pointer" onclick="jumpToMessage('{{ $message->replyTo->id }}')" style="border-left: 3px solid {{ $mine ? '#ffa726' : '#0d47a1' }}; background: {{ $mine ? 'rgba(255,255,255,0.15)' : 'rgba(13,71,161,0.08)' }};">
                                                <div class="fw-bold small text-truncate" style="font-size: 0.75rem; color: {{ $mine ? '#ffe0b2' : '#0d47a1' }};">
                                                    {{ $message->replyTo->sender_id === auth()->id() ? 'Anda' : ($message->replyTo->sender?->name ?? 'Pesan') }}
                                                </div>
                                                <div class="small text-truncate" style="font-size: 0.78rem; opacity: 0.9;">
                                                    {{ $message->replyTo->message ?: ($message->replyTo->attachment ? '(Lampiran Media)' : '') }}
                                                </div>
                                            </div>
                                        @endif
                                        @if(filled($message->message))
                                            <div class="chat-msg-text" style="white-space:pre-line">{{ $message->message }}</div>
                                        @endif
                                        @if($message->attachment)
                                            @php
                                                $ext = strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION));
                                                $isImage = in_array($ext, ['jpg','jpeg','png','webp','gif']);
                                                $isVideo = in_array($ext, ['mp4','mov','avi','3gp','webm']);
                                                $isPdf = $ext === 'pdf';
                                            @endphp
                                            <div class="chat-attach-preview mt-2">
                                                @if($isImage)
                                                    <div class="chat-img-wrap">
                                                        <button type="button" class="p-0 border-0 bg-transparent d-block" onclick="openLightbox('{{ asset('storage/'.$message->attachment) }}')">
                                                            <img src="{{ asset('storage/'.$message->attachment) }}" alt="Lampiran" class="chat-attach-img lightbox-img">
                                                        </button>
                                                        <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" download class="chat-img-dl-btn btn btn-sm btn-dark bg-opacity-75 text-white border-0 position-absolute bottom-0 end-0 m-2 shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:32px; height:32px; backdrop-filter:blur(4px);" title="Unduh foto">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                @elseif($isVideo)
                                                    <div class="chat-video-wrap" onclick="openLightbox('{{ asset('storage/'.$message->attachment) }}', 'video')">
                                                        <video preload="metadata" class="chat-attach-video d-block w-100" style="max-height:240px; object-fit:cover; pointer-events:none;">
                                                            <source src="{{ asset('storage/'.$message->attachment) }}#t=0.1" type="video/{{ $ext }}">
                                                        </video>
                                                        <div class="position-absolute top-0 start-0 m-2 text-white opacity-90 small">
                                                            <i class="fas fa-video"></i>
                                                        </div>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <div class="chat-video-play-btn d-flex align-items-center justify-content-center text-white shadow-lg">
                                                                <i class="fas fa-play fa-lg ms-1"></i>
                                                            </div>
                                                        </div>
                                                        <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" download onclick="event.stopPropagation();" class="chat-img-dl-btn btn btn-sm btn-dark bg-opacity-75 text-white border-0 position-absolute bottom-0 end-0 m-2 shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:32px; height:32px; backdrop-filter:blur(4px);" title="Unduh video">
                                                            <i class="fas fa-download"></i>
                                                        </a>
                                                    </div>
                                                @elseif($isPdf)
                                                    <div class="chat-attach-doc {{ $mine ? 'mine' : '' }} d-flex align-items-center justify-content-between gap-2 p-2 rounded-3 border bg-white bg-opacity-75">
                                                        <div class="d-flex align-items-center gap-2 overflow-hidden" style="cursor: pointer;" onclick="openLightbox('{{ asset('storage/'.$message->attachment) }}', 'pdf')">
                                                            <i class="fas fa-file-pdf text-danger fs-3 flex-shrink-0"></i>
                                                            <div class="chat-attach-doc-info text-truncate">
                                                                <div class="chat-attach-doc-name fw-bold text-dark text-truncate small">{{ basename($message->attachment) }}</div>
                                                                <div class="chat-attach-doc-meta text-muted" style="font-size: 0.75rem;"><i class="fas fa-eye me-1"></i>Klik untuk pratinjau PDF</div>
                                                            </div>
                                                        </div>
                                                        <div class="d-flex align-items-center gap-1 flex-shrink-0">
                                                            <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" download class="btn btn-sm btn-light border-0 text-secondary py-1 px-2" title="Unduh PDF">
                                                                <i class="fas fa-download"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @else
                                                    <a href="{{ asset('storage/'.$message->attachment) }}" target="_blank" class="chat-attach-doc {{ $mine ? 'mine' : '' }}">
                                                        <i class="fas fa-file-alt chat-attach-doc-icon"></i>
                                                        <div class="chat-attach-doc-info">
                                                            <div class="chat-attach-doc-name">{{ basename($message->attachment) }}</div>
                                                            <div class="chat-attach-doc-meta">{{ strtoupper($ext) }}</div>
                                                        </div>
                                                        <i class="fas fa-download chat-attach-doc-dl"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                        <div class="chat-meta">{{ $message->created_at?->format('H:i') }}
                                            @if($mine)
                                                <span class="chat-receipt {{ $message->read_by_admin ? 'read' : 'sent' }}" title="{{ $message->read_by_admin ? 'Dibaca Admin KMC' : 'Terkirim' }}"></span>
                                            @endif
                                        </div>
                                    </article>
                                    <!-- WhatsApp-style dropdown chevron -->
                                    <div class="chat-msg-actions">
                                        <button class="chat-msg-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-chevron-down"></i></button>
                                        <ul class="dropdown-menu dropdown-menu-{{ $mine ? 'end' : 'start' }} chat-ctx-menu shadow-lg">
                                            @if($mine)
                                                <li><a class="dropdown-item chat-info-btn" href="#" onclick="showMsgInfoFromEl(this); return false;" data-sent-at="{{ $sentAtStr }}" data-sender="Anda" data-delivered-at="{{ $deliveredAtStr }}" data-read-at="{{ $readAtStr }}"><i class="fas fa-info-circle me-2 text-muted"></i>Info</a></li>
                                            @endif
                                            @php
                                                $replySender = $mine ? 'Anda' : ($message->sender?->name ?? 'Admin KMC');
                                                $replyText = $message->message ?? '';
                                                $replyAttachment = $message->attachment ?? '';
                                            @endphp
                                            <li><a class="dropdown-item" href="#" onclick="replyMsgFromEl(this); return false;" data-reply-id="{{ $message->id }}" data-reply-sender="{{ $replySender }}" data-reply-text="{{ $replyText }}" data-reply-attachment="{{ $replyAttachment }}"><i class="fas fa-reply me-2 text-muted"></i>Balas</a></li>
                                            @if($mine && filled($message->message))
                                                <li><a class="dropdown-item" href="#" onclick="editMsgFromEl(this); return false;" data-edit-id="{{ $message->id }}" data-edit-text="{{ $message->message }}"><i class="fas fa-edit me-2 text-muted"></i>Edit Pesan</a></li>
                                            @endif
                                            @if(filled($message->message))
                                                <li><a class="dropdown-item" href="#" onclick="copyMsg(this); return false;" data-msg="{{ $message->message }}"><i class="fas fa-copy me-2 text-muted"></i>Salin</a></li>
                                            @endif
                                            @if($mine)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('opd.chat.delete', $message) }}" method="POST" onsubmit="deleteMsgAjax(event, this)">
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
                    <button id="opd-scroll-latest" class="chat-scroll-latest d-none" type="button" title="Ke pesan terbaru" aria-label="Ke pesan terbaru"><i class="fas fa-chevron-down"></i></button>

                    <footer class="chat-composer">
                        <div id="reply-preview" class="reply-preview d-none mb-2 p-2 rounded-3 shadow-sm" style="border-left: 4px solid #f57c00; background-color: rgba(245, 124, 0, 0.08) !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="overflow-hidden pe-2" style="min-width: 0;">
                                    <div class="fw-bold small text-truncate mb-0" id="reply-preview-sender" style="font-size: 0.78rem; color: #f57c00;"></div>
                                    <div class="small text-dark text-truncate" id="reply-preview-text" style="font-size: 0.82rem;"></div>
                                </div>
                                <button type="button" class="btn-close btn-close-sm flex-shrink-0" onclick="cancelReply()" aria-label="Batal balas"></button>
                            </div>
                        </div>
                        <div id="edit-preview" class="reply-preview d-none mb-2 p-2 rounded-3 shadow-sm" style="border-left: 4px solid #0d47a1; background-color: rgba(13, 71, 161, 0.08) !important;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="overflow-hidden pe-2" style="min-width: 0;">
                                    <div class="fw-bold small text-truncate mb-0" style="font-size: 0.78rem; color: #0d47a1;"><i class="fas fa-edit me-1"></i>Edit Pesan</div>
                                    <div class="small text-dark text-truncate" id="edit-preview-text" style="font-size: 0.82rem;"></div>
                                </div>
                                <button type="button" class="btn-close btn-close-sm flex-shrink-0" onclick="cancelEdit()" aria-label="Batal edit"></button>
                            </div>
                        </div>
                        <div id="opd-file-preview" class="composer-file-preview d-none" aria-live="polite">
                            <div id="opd-file-body" class="d-flex align-items-center gap-2 flex-grow-1 overflow-hidden" style="cursor: pointer;" title="Klik untuk melihat pratinjau" onclick="previewAttachmentModal('opd')">
                                <div id="opd-file-visual" class="composer-file-icon"><i class="fas fa-file-alt"></i></div>
                                <div class="composer-file-info">
                                    <div id="opd-file-name" class="composer-file-name"></div>
                                    <div id="opd-file-meta" class="composer-file-meta"></div>
                                </div>
                            </div>
                            <button type="button" class="composer-file-remove" onclick="clearSelectedAttachment('opd')" aria-label="Hapus lampiran"><i class="fas fa-times"></i></button>
                        </div>
                        <form action="{{ route('opd.chat.send', $ticket) }}" method="POST" enctype="multipart/form-data" class="chat-composer-form" id="opd-chat-form">
                            @csrf
                            <input type="hidden" name="reply_to_id" id="reply_to_id" value="">
                            <div class="chat-attach-dropdown dropup">
                                <button class="btn btn-light border chat-attachment" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Lampiran"><i class="fas fa-paperclip"></i></button>
                                <ul class="dropdown-menu shadow-lg">
                                    <li><a class="dropdown-item" href="#" onclick="document.getElementById('opd-pick-doc').click(); return false;"><i class="fas fa-file-alt me-2" style="color:#7c4dff"></i>Dokumen</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="document.getElementById('opd-pick-media').click(); return false;"><i class="fas fa-image me-2" style="color:#00bfa5"></i>Foto & Video</a></li>
                                    <li><a class="dropdown-item" href="#" onclick="document.getElementById('opd-pick-camera').click(); return false;"><i class="fas fa-camera me-2" style="color:#ff1744"></i>Kamera</a></li>
                                </ul>
                            </div>
                            <input id="opd-pick-doc" type="file" class="d-none" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv">
                            <input id="opd-pick-media" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.gif,.mp4,.mov,.avi,.3gp">
                            <input id="opd-pick-camera" type="file" class="d-none" accept="image/*" capture="environment">
                            <textarea id="opd-chat-message" name="message" class="form-control chat-input" rows="1" maxlength="2000" placeholder="Kirim pesan chat untuk Admin KMC..."></textarea>
                            <button id="opd-chat-send" class="btn btn-primary chat-send" type="submit" title="Kirim pesan" disabled><i class="fas fa-paper-plane"></i></button>
                        </form>
                    </footer>
                </div>

                <!-- Right Search Side Panel Column -->
                <div id="chat-search-container" class="chat-search-side-panel d-none">
                    <div class="d-flex align-items-center justify-content-between p-3 border-bottom bg-light">
                        <div class="d-flex align-items-center gap-3">
                            <button type="button" class="btn btn-sm btn-link text-dark p-0 text-decoration-none" onclick="toggleChatSearch()" title="Tutup pencarian">
                                <i class="fas fa-times fs-5"></i>
                            </button>
                            <span class="fw-bold text-dark fs-6 mb-0">Cari Pesan</span>
                        </div>
                    </div>

                    <div class="p-3 pb-2">
                        <div class="chat-search-pill d-flex align-items-center px-3 py-1 bg-light rounded-pill border" id="chat-search-pill-box">
                            <i class="fas fa-search text-muted me-2"></i>
                            <input type="text" id="chat-search-input" class="form-control border-0 bg-transparent shadow-none p-1" placeholder="Cari kata atau frasa..." oninput="performChatSearch()">
                            <button type="button" class="btn btn-sm text-muted p-0 border-0" onclick="clearChatSearch()" title="Hapus pencarian">
                                <i class="fas fa-times-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div id="chat-search-results-list" class="chat-search-results-list px-3 pb-3" style="flex: 1 1 auto; overflow-y: auto; min-height: 0;">
                        <div class="text-center text-muted py-4 small">
                            <i class="fas fa-search fs-3 opacity-50 mb-2 d-block"></i>
                            Ketik kata kunci untuk mencari pesan dalam percakapan ini
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4 d-none d-xl-block mb-4">
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
                    <div class="col-12"><div class="ticket-info-tile"><div class="ticket-label"><i class="fas fa-user me-1"></i>Pelapor</div><div class="fw-semibold small text-dark text-truncate">{{ $ticket->reporter_name ?? 'Anonim' }}</div></div></div>
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
                <a href="{{ route('opd.tickets.show', $ticket) }}" class="btn btn-outline-primary btn-sm w-100 mt-3 fw-semibold">
                    <i class="fas fa-external-link-alt me-1"></i>Selengkapnya
                </a>
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

<!-- Mobile Ticket Info Modal -->
<div class="modal fade" id="mobileTicketInfoModal" tabindex="-1" aria-labelledby="mobileTicketInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <div class="d-flex align-items-center justify-content-between w-100 me-2">
                    <h5 class="modal-title fs-6 fw-bold mb-0" id="mobileTicketInfoModalLabel">
                        <i class="fas fa-info-circle me-2"></i>Informasi Aduan
                    </h5>
                    @php $chatStatusClass = match($ticket->status) { 'selesai' => 'bg-success', 'eskalasi' => 'bg-danger', 'diproses' => 'bg-warning text-dark', 'dijawab' => 'bg-light text-primary', default => 'bg-info text-dark' }; @endphp
                    <span class="badge {{ $chatStatusClass }} rounded-pill px-2.5 py-1">{{ $ticket->status === 'proses_disposisi' ? 'Disposisi' : ucfirst($ticket->status) }}</span>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="ticket-label text-muted small">Laporan</div>
                    <div class="fw-bold text-dark fs-6">#{{ $ticket->tracking_number ?? $ticket->ticket_number }}</div>
                    <div class="small text-muted mt-1"><i class="far fa-clock me-1"></i>{{ $ticket->created_at?->format('d M Y, H:i') }} WIB</div>
                </div>
                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <div class="ticket-info-tile p-2.5 bg-light rounded-3 border">
                            <div class="ticket-label text-muted small"><i class="fas fa-user me-1"></i>Pelapor</div>
                            <div class="fw-semibold small text-dark text-truncate">{{ $ticket->reporter_name ?? 'Anonim' }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="ticket-info-tile p-2.5 bg-light rounded-3 border">
                            <div class="ticket-label text-muted small"><i class="fas fa-tag me-1"></i>Kategori Masalah</div>
                            <div class="fw-semibold small text-dark">{{ $ticket->category }}</div>
                            @if($ticket->sub_category)<div class="small text-muted">{{ $ticket->sub_category }}</div>@endif
                        </div>
                    </div>
                </div>
                <div class="complaint-preview p-3 bg-light rounded-3 border">
                    <div class="ticket-label mb-2 fw-semibold text-primary"><i class="fas fa-quote-left me-1"></i>Isi Aduan</div>
                    <div class="small text-dark" style="white-space:pre-line">{{ $ticket->complaint }}</div>
                </div>
                @php $reportAttachments = $ticket->notification?->attachments ?? []; @endphp
                @if(is_array($reportAttachments) && count($reportAttachments))
                    <button type="button" class="btn btn-outline-primary btn-sm w-100 mt-3 fw-semibold py-2" data-bs-toggle="modal" data-bs-target="#ticketAttachmentsModal">
                        <i class="fas fa-paperclip me-1"></i>Lihat Lampiran ({{ count($reportAttachments) }})
                    </button>
                @endif
                <a href="{{ route('opd.tickets.show', $ticket) }}" class="btn btn-outline-primary w-100 mt-3 fw-semibold py-2">
                    <i class="fas fa-external-link-alt me-1.5"></i>Selengkapnya
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Update Status / Respond Modal -->
<div class="modal fade" id="mobileUpdateStatusModal" tabindex="-1" aria-labelledby="mobileUpdateStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
            <div class="modal-header bg-warning text-dark py-3">
                <h5 class="modal-title fs-6 fw-bold mb-0" id="mobileUpdateStatusModalLabel">
                    <i class="fas fa-comment-dots me-2"></i>Berikan Tanggapan Resmi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="small text-muted mb-3">Tanggapan resmi akan dicatat pada Riwayat Perjalanan Aduan dan memperbarui status tiket menjadi Dijawab.</div>
                <form action="{{ route('opd.tickets.respond', $ticket) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <label class="form-label fw-bold small text-secondary mb-1">Pesan Tanggapan Resmi</label>
                    <textarea name="response_text" class="form-control mb-3" rows="4" placeholder="Tulis tanggapan resmi untuk aduan ini..." required style="border-radius: 10px;"></textarea>
                    <label class="form-label fw-bold small text-secondary mb-1">Lampiran Tanggapan (Foto)</label>
                    <input name="attachment" type="file" class="form-control mb-2" accept=".jpg,.jpeg,.png" style="border-radius: 10px;">
                    <div class="form-text small mb-3">Format: JPG, JPEG, PNG — maksimal 5 MB.</div>
                    <button class="btn btn-warning w-100 fw-bold py-2.5 shadow-sm" type="submit" style="border-radius: 10px;">
                        <i class="fas fa-paper-plane me-1.5"></i>Kirim Tanggapan Resmi
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Chat Settings Modal -->
<div class="modal fade" id="chatSettingsModal" tabindex="-1" aria-labelledby="chatSettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="chatSettingsModalLabel"><i class="fas fa-cog text-primary me-2"></i>Pengaturan Chat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body py-4">
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 bg-light border">
                    <div>
                        <div class="fw-bold text-dark mb-1" style="font-size: 1rem;">Enter untuk Mengirim</div>
                        <div class="small text-muted" id="enterToSendHint">Tombol Enter akan Mengirim Pesan Anda</div>
                    </div>
                    <div class="form-check form-switch fs-4 mb-0 ps-0">
                        <input class="form-check-input ms-0" type="checkbox" role="switch" id="enterToSendSwitch" checked onchange="toggleEnterToSend(this.checked)" style="cursor: pointer;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Chat Action Sheet Modal -->
<div class="modal fade" id="mobileChatActionSheetModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog m-0 w-100 position-fixed bottom-0 start-0 end-0" style="max-width: 100%; z-index: 1070;">
        <div class="modal-content border-0 rounded-top-4 overflow-hidden shadow-lg">
            <div class="modal-header border-bottom-0 pb-1 pt-3 px-3">
                <div class="d-flex flex-column w-100 me-2 overflow-hidden">
                    <div class="small fw-bold text-primary text-truncate" id="mobile-sheet-sender"></div>
                    <div class="small text-secondary text-truncate" id="mobile-sheet-preview" style="font-size: 0.82rem;"></div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-3 bg-light">
                <div class="list-group list-group-flush rounded-3 overflow-hidden border">
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3 d-none" id="mobile-action-info" onclick="triggerSheetAction('info')">
                        <i class="fas fa-info-circle text-primary me-3 fs-5"></i>
                        <span class="fw-semibold text-dark">Info Pesan</span>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3" id="mobile-action-reply" onclick="triggerSheetAction('reply')">
                        <i class="fas fa-reply text-success me-3 fs-5"></i>
                        <span class="fw-semibold text-dark">Balas Pesan</span>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3 d-none" id="mobile-action-edit" onclick="triggerSheetAction('edit')">
                        <i class="fas fa-edit text-warning me-3 fs-5"></i>
                        <span class="fw-semibold text-dark">Edit Pesan</span>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3" id="mobile-action-copy" onclick="triggerSheetAction('copy')">
                        <i class="fas fa-copy text-info me-3 fs-5"></i>
                        <span class="fw-semibold text-dark">Salin Teks</span>
                    </button>
                    <button type="button" class="list-group-item list-group-item-action d-flex align-items-center py-2.5 px-3 text-danger d-none" id="mobile-action-delete" onclick="triggerSheetAction('delete')">
                        <i class="fas fa-trash-alt me-3 fs-5"></i>
                        <span class="fw-bold">Hapus Pesan</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-scroll to bottom and smart scroll-to-latest control
const chatCanvas = document.getElementById('opd-chat-messages');
const opdLatestButton = document.getElementById('opd-scroll-latest');
function opdIsNearBottom() {
    return chatCanvas.scrollHeight - chatCanvas.scrollTop - chatCanvas.clientHeight < 80;
}
function updateOpdLatestButton() {
    opdLatestButton.classList.toggle('d-none', opdIsNearBottom());
}
function scrollChatToBottom(instant = true) {
    if (!chatCanvas) return;
    if (instant) {
        chatCanvas.scrollTop = chatCanvas.scrollHeight;
    } else {
        chatCanvas.scrollTo({ top: chatCanvas.scrollHeight, behavior: 'smooth' });
    }
}

if (chatCanvas) {
    scrollChatToBottom(true);
    chatCanvas.addEventListener('scroll', updateOpdLatestButton);
    opdLatestButton.addEventListener('click', function() {
        scrollChatToBottom(false);
    });

    // Ensure scroll locks to bottom after images, fonts, and DOM layout finish loading
    setTimeout(() => scrollChatToBottom(true), 50);
    setTimeout(() => scrollChatToBottom(true), 250);
    setTimeout(() => scrollChatToBottom(true), 500);

    document.addEventListener('DOMContentLoaded', () => scrollChatToBottom(true));
    window.addEventListener('load', () => scrollChatToBottom(true));

    chatCanvas.querySelectorAll('img').forEach(img => {
        if (!img.complete) {
            img.addEventListener('load', () => scrollChatToBottom(true));
        }
    });
}
function updateOpdSendState() {
    const message = document.getElementById('opd-chat-message');
    const hasAttachment = ['opd-pick-doc','opd-pick-media','opd-pick-camera'].some(id => document.getElementById(id).files.length > 0);
    document.getElementById('opd-chat-send').disabled = !message.value.trim() && !hasAttachment;
}
function autoResizeChatInput(inputEl) {
    if (!inputEl) return;
    inputEl.style.height = 'auto';
    const newHeight = Math.min(inputEl.scrollHeight, 140);
    inputEl.style.height = Math.max(newHeight, 42) + 'px';
}
const opdChatMsgEl = document.getElementById('opd-chat-message');
if (opdChatMsgEl) {
    opdChatMsgEl.addEventListener('input', function() {
        updateOpdSendState();
        autoResizeChatInput(this);
    });
    opdChatMsgEl.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            if (isEnterToSendEnabled()) {
                e.preventDefault();
                document.getElementById('opd-chat-form').requestSubmit();
            }
        }
    });
}

// Enter to Send Setting
function isEnterToSendEnabled() {
    const saved = localStorage.getItem('simadu_enter_to_send');
    return saved === null ? true : saved === 'true';
}

function toggleEnterToSend(enabled) {
    localStorage.setItem('simadu_enter_to_send', enabled ? 'true' : 'false');
    const hint = document.getElementById('enterToSendHint');
    if (hint) {
        hint.textContent = enabled ? 'Tombol Enter akan Mengirim Pesan Anda' : 'Tombol Enter untuk baris baru (kirim via tombol plane)';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const switchEl = document.getElementById('enterToSendSwitch');
    if (switchEl) {
        const enabled = isEnterToSendEnabled();
        switchEl.checked = enabled;
        toggleEnterToSend(enabled);
    }
});

// Search in Chat (WhatsApp Web Style)
function toggleChatSearch() {
    const container = document.getElementById('chat-search-container');
    const input = document.getElementById('chat-search-input');
    if (!container) return;
    if (container.classList.contains('d-none')) {
        container.classList.remove('d-none');
        if (input) { input.focus(); input.select(); }
        performChatSearch();
    } else {
        container.classList.add('d-none');
        clearChatSearch();
    }
}

function clearSearchDate() {
    const dateInput = document.getElementById('chat-search-date');
    if (dateInput) dateInput.value = '';
    performChatSearch();
}

function clearChatSearch() {
    const input = document.getElementById('chat-search-input');
    const dateInput = document.getElementById('chat-search-date');
    if (input) input.value = '';
    if (dateInput) dateInput.value = '';
    clearChatSearchHighlights();
    performChatSearch();
}

function clearChatSearchHighlights() {
    if (!chatCanvas) return;
    chatCanvas.querySelectorAll('.chat-bubble-wrap.search-active').forEach(el => el.classList.remove('search-active'));
}

function escapeRegExp(string) {
    return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
}

function performChatSearch() {
    clearChatSearchHighlights();
    const query = (document.getElementById('chat-search-input')?.value || '').trim();
    const dateFilter = (document.getElementById('chat-search-date')?.value || '');
    const resultsContainer = document.getElementById('chat-search-results-list');
    const clearDateBtn = document.getElementById('clear-date-btn');

    if (clearDateBtn) {
        clearDateBtn.classList.toggle('d-none', !dateFilter);
    }

    if (!resultsContainer) return;

    if (!query && !dateFilter) {
        resultsContainer.innerHTML = `<div class="text-center text-muted py-4 small"><i class="fas fa-search fs-3 opacity-50 mb-2 d-block"></i>Ketik kata kunci untuk mencari pesan dalam percakapan ini</div>`;
        return;
    }

    const matches = [];
    const messageRows = chatCanvas.querySelectorAll('[data-chat-message-id]');

    messageRows.forEach(row => {
        const msgId = row.getAttribute('data-chat-message-id');
        const textEl = row.querySelector('.chat-msg-text');
        const metaEl = row.querySelector('.chat-meta');
        const senderEl = row.querySelector('.chat-message > div.small.fw-bold');
        const receiptEl = row.querySelector('.chat-receipt');
        const isMine = row.querySelector('.chat-bubble-wrap.mine') !== null;
        
        const fullText = textEl ? textEl.textContent : '';
        const senderName = senderEl ? senderEl.textContent : 'Pesan';
        const timeText = metaEl ? metaEl.textContent.replace(/[✓\s]+/g, ' ').trim() : '';
        const isRead = receiptEl ? receiptEl.classList.contains('read') : false;

        // Text query check
        const textMatches = !query || fullText.toLowerCase().includes(query.toLowerCase());
        
        // Date filter check
        let dateMatches = true;
        if (dateFilter) {
            const rowDate = row.getAttribute('data-created-date');
            if (rowDate) {
                dateMatches = rowDate === dateFilter;
            }
        }

        if (textMatches && dateMatches) {
            matches.push({
                id: msgId,
                element: row,
                sender: senderName,
                text: fullText,
                time: timeText,
                isMine: isMine,
                isRead: isRead
            });
        }
    });

    if (matches.length === 0) {
        resultsContainer.innerHTML = `<div class="text-center text-muted py-4 small"><i class="far fa-frown fs-3 opacity-50 mb-2 d-block"></i>Tidak ada pesan ditemukan</div>`;
        return;
    }

    let html = '';
    matches.forEach(m => {
        let snippet = escapeChatText(m.text || '(Lampiran Media)');
        if (query && m.text) {
            const regex = new RegExp('(' + escapeRegExp(query) + ')', 'gi');
            snippet = snippet.replace(regex, '<span class="fw-bold" style="color: #059669; background: #ecfdf5; padding: 0 2px; border-radius: 2px;">$1</span>');
        }

        let receiptIcon = '';
        if (m.isMine) {
            receiptIcon = `<span class="chat-receipt ${m.isRead ? 'read' : 'sent'} me-1" style="display:inline-block; width:14px; height:11px; vertical-align:-1px;"></span>`;
        } else {
            receiptIcon = `<i class="fas fa-comment-dots text-muted me-1" style="font-size:0.75rem;"></i>`;
        }

        html += `
            <div class="search-result-item p-2 mb-1 rounded-3 border-bottom border-light cursor-pointer" onclick="jumpToMessage('${m.id}')" style="cursor: pointer; transition: background 0.15s ease;">
                <div class="d-flex align-items-center justify-content-between mb-1">
                    <span class="small fw-semibold text-primary" style="font-size: 0.78rem;">${receiptIcon}${escapeChatText(m.sender)}</span>
                    <span class="small text-muted" style="font-size: 0.75rem;">${escapeChatText(m.time)}</span>
                </div>
                <div class="small text-dark text-truncate" style="font-size: 0.82rem; line-height: 1.35;">
                    ${snippet}
                </div>
            </div>
        `;
    });

    resultsContainer.innerHTML = html;
}

function jumpToMessage(msgId) {
    clearChatSearchHighlights();
    const row = chatCanvas.querySelector('[data-chat-message-id="' + msgId + '"]');
    if (row) {
        const bubbleWrap = row.querySelector('.chat-bubble-wrap');
        if (bubbleWrap) {
            bubbleWrap.classList.add('search-active');
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

document.getElementById('opd-chat-form').addEventListener('submit', async function(event) {
    event.preventDefault();
    if (editingMsgId) {
        const msgInput = document.getElementById('opd-chat-message');
        const text = (msgInput ? msgInput.value : '').trim();
        if (!text) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        const updateUrl = '/chat/message/' + editingMsgId;
        try {
            const response = await fetch(updateUrl, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ message: text }),
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Gagal mengedit pesan.');
            const resData = await response.json();

            const targetRow = chatCanvas.querySelector('[data-chat-message-id="' + editingMsgId + '"]');
            if (targetRow) {
                const textEl = targetRow.querySelector('.chat-msg-text');
                if (textEl) textEl.textContent = resData.data.message;

                const editBtn = targetRow.querySelector('.chat-ctx-menu a[onclick*="editMsgFromEl"]');
                if (editBtn) editBtn.setAttribute('data-edit-text', resData.data.message);
                const replyBtn = targetRow.querySelector('.chat-ctx-menu a[onclick*="replyMsgFromEl"]');
                if (replyBtn) replyBtn.setAttribute('data-reply-text', resData.data.message);

                const metaEl = targetRow.querySelector('.chat-meta');
                if (metaEl && !metaEl.querySelector('.edited-tag')) {
                    const tag = document.createElement('span');
                    tag.className = 'edited-tag text-white-50 ms-1';
                    tag.style.fontSize = '0.68rem';
                    tag.textContent = '(diedit)';
                    metaEl.insertBefore(tag, metaEl.querySelector('.chat-receipt') || null);
                }
            }
            cancelEdit();
            setTimeout(pollOpdChat, 200);
        } catch (err) {
            alert('Gagal memperbarui pesan.');
        }
        return;
    }

    updateOpdSendState();
    const sendButton = document.getElementById('opd-chat-send');
    if (sendButton.disabled) return;
    sendButton.disabled = true;
    try {
        const response = await fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            credentials: 'same-origin',
        });
        if (!response.ok) throw new Error('Pesan tidak dapat dikirim.');
        const data = await response.json();
        if (!chatCanvas.querySelector('[data-chat-message-id="' + data.message.id + '"]')) {
            chatCanvas.insertAdjacentHTML('beforeend', opdMessageMarkup(data.message));
        }
        opdChatMsgEl.value = '';
        autoResizeChatInput(opdChatMsgEl);
        clearSelectedAttachment('opd');
        cancelReply();
        chatCanvas.scrollTo({ top: chatCanvas.scrollHeight, behavior: 'smooth' });
        setTimeout(pollOpdChat, 200);
    } catch (error) {
        alert(error.message || 'Pesan tidak dapat dikirim. Coba lagi.');
    } finally {
        updateOpdSendState();
        autoResizeChatInput(opdChatMsgEl);
    }
});

// Copy message
function copyMsg(el) {
    const text = el.getAttribute('data-msg');
    navigator.clipboard.writeText(text).then(() => {
        const orig = el.innerHTML;
        el.innerHTML = '<i class="fas fa-check me-2 text-success"></i>Tersalin!';
        setTimeout(() => { el.innerHTML = orig; }, 1500);
    });
}

// Delete message via AJAX
function deleteMsgAjax(event, form) {
    event.preventDefault();
    if (typeof Swal === 'undefined') {
        if (!confirm('Hapus pesan ini?')) return;
        executeDelete();
        return;
    }
    Swal.fire({
        title: 'Hapus Pesan?',
        text: 'Pesan yang dihapus tidak dapat dikembalikan.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash-alt me-1"></i> Ya, Hapus',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4 shadow-lg border-0',
            confirmButton: 'px-4 py-2 rounded-3 fw-bold',
            cancelButton: 'px-4 py-2 rounded-3'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            executeDelete();
        }
    });

    async function executeDelete() {
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            });
            if (!response.ok) throw new Error('Gagal menghapus pesan.');
            const data = await response.json();
            if (data.success && data.deleted_id) {
                const msgEl = chatCanvas.querySelector('[data-chat-message-id="' + data.deleted_id + '"]');
                if (msgEl) {
                    msgEl.style.transition = 'all 0.25s ease';
                    msgEl.style.opacity = '0';
                    msgEl.style.transform = 'scale(0.9)';
                    setTimeout(() => msgEl.remove(), 250);
                }
            }
        } catch (error) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Gagal!', error.message || 'Gagal menghapus pesan.', 'error');
            } else {
                alert(error.message || 'Gagal menghapus pesan. Coba lagi.');
            }
        }
    }
}

// Reply to message
function replyMsgFromEl(el) {
    if (!el) return;
    const msgId = el.getAttribute('data-reply-id');
    const sender = el.getAttribute('data-reply-sender') || '';
    const text = el.getAttribute('data-reply-text') || '';
    const attach = el.getAttribute('data-reply-attachment') || '';
    replyMsg(msgId, sender, text, attach);
}
function replyMsg(msgId, senderName, text, attachment) {
    const box = document.getElementById('reply-preview');
    const senderEl = document.getElementById('reply-preview-sender');
    const textEl = document.getElementById('reply-preview-text');
    
    let contentHtml = '';
    if (attachment) {
        const ext = attachment.split('.').pop().toLowerCase();
        let icon = 'fa-file-alt text-secondary';
        let typeName = 'Dokumen';
        if (['jpg','jpeg','png','webp','gif'].includes(ext)) {
            icon = 'fa-camera text-primary';
            typeName = 'Foto';
        } else if (['mp4','mov','avi','3gp','webm'].includes(ext)) {
            icon = 'fa-video text-primary';
            typeName = 'Video';
        } else if (ext === 'pdf') {
            icon = 'fa-file-pdf text-danger';
            typeName = 'PDF';
        }

        const fileName = attachment.split('/').pop();
        if (text && text.trim() !== '') {
            contentHtml = `<i class="fas ${icon} me-1"></i> <span class="fw-semibold">${typeName}:</span> ${escapeChatText(text)}`;
        } else {
            contentHtml = `<i class="fas ${icon} me-1"></i> <span class="fw-semibold">${typeName}</span> ${typeName === 'Foto' || typeName === 'Video' ? '' : '<span class="text-muted small ms-1">(' + escapeChatText(fileName) + ')</span>'}`;
        }
    } else {
        contentHtml = escapeChatText(text || 'Pesan');
    }

    if (senderEl) senderEl.textContent = senderName || 'Pesan';
    if (textEl) textEl.innerHTML = contentHtml;

    const isOpponent = senderName !== 'Anda';
    if (isOpponent) {
        box.style.borderLeft = '4px solid #f57c00';
        box.style.backgroundColor = 'rgba(245, 124, 0, 0.08)';
        if (senderEl) senderEl.style.color = '#f57c00';
    } else {
        box.style.borderLeft = '4px solid #0d47a1';
        box.style.backgroundColor = 'rgba(13, 71, 161, 0.06)';
        if (senderEl) senderEl.style.color = '#0d47a1';
    }

    box.classList.remove('d-none');
    const replyInput = document.getElementById('reply_to_id');
    if (replyInput) replyInput.value = msgId;
    const scrollBtn = document.getElementById('opd-scroll-latest');
    if (scrollBtn) scrollBtn.style.bottom = '138px';
    const msgInput = document.getElementById('opd-chat-message');
    if (msgInput) msgInput.focus();
}
function cancelReply() {
    const box = document.getElementById('reply-preview');
    if (box) box.classList.add('d-none');
    const replyInput = document.getElementById('reply_to_id');
    if (replyInput) replyInput.value = '';
    const scrollBtn = document.getElementById('opd-scroll-latest');
    if (scrollBtn) scrollBtn.style.bottom = '82px';
}

// WhatsApp-style Message Info modal with chat bubble preview
function showMsgInfoFromEl(el, time, sender, deliveredAt, readAt) {
    const bubbleWrap = el.closest('.chat-bubble-wrap');
    const msgRow = el.closest('[data-chat-message-id]');

    const sentVal = (el && el.getAttribute('data-sent-at')) || (msgRow && msgRow.getAttribute('data-sent-at')) || time || '-';
    const senderVal = (el && el.getAttribute('data-sender')) || sender || 'Anda';
    const deliveredVal = (el && el.getAttribute('data-delivered-at')) || (msgRow && msgRow.getAttribute('data-delivered-at')) || deliveredAt || '-';
    const readVal = (el && el.getAttribute('data-read-at')) || (msgRow && msgRow.getAttribute('data-read-at')) || readAt || '-';

    let bubbleHtml = '';
    let isMine = true;
    if (bubbleWrap) {
        isMine = bubbleWrap.classList.contains('mine');
        const article = bubbleWrap.querySelector('.chat-message');
        if (article) bubbleHtml = article.outerHTML;
    }
    const old = document.getElementById('msgInfoModal');
    if (old) old.remove();

    const html = `
    <div class="modal fade msg-info-modal" id="msgInfoModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
            <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg" style="background: #ffffff;">
                <div class="modal-header border-0 text-white px-3 py-3" style="background: #0d47a1;">
                    <div class="d-flex align-items-center gap-3">
                        <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal" style="font-size: 0.85rem;"></button>
                        <h6 class="modal-title fw-bold mb-0" style="font-size: 1.1rem; letter-spacing: -0.2px;">Info pesan</h6>
                    </div>
                </div>
                
                <div class="p-3 py-4" style="background-color: #f4f7fb; background-image: radial-gradient(rgba(13,71,161,.08) 1px, transparent 1px); background-size: 18px 18px; display: flex; justify-content: ${isMine ? 'flex-end' : 'flex-start'}; border-bottom: 1px solid #edf2f7;">
                    <div class="chat-bubble-wrap ${isMine ? 'mine' : 'theirs'}" style="max-width: 90%; pointer-events: none;">
                        ${bubbleHtml}
                    </div>
                </div>

                <div class="p-3 px-4 bg-white">
                    <div class="d-flex align-items-start gap-3 mb-3">
                        <div style="width: 24px; text-align: center; margin-top: 2px;">
                            <span class="chat-receipt read" style="display: inline-block; width: 18px; height: 14px;"></span>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Dibaca</div>
                            <div class="text-secondary mt-0.5" style="font-size: 0.84rem;">${readVal}</div>
                        </div>
                    </div>

                    <div class="d-flex align-items-start gap-3">
                        <div style="width: 24px; text-align: center; margin-top: 2px;">
                            <span class="chat-receipt sent" style="display: inline-block; width: 18px; height: 14px;"></span>
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 0.95rem;">Tersampaikan</div>
                            <div class="text-secondary mt-0.5" style="font-size: 0.84rem;">${deliveredVal}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>`;

    document.body.insertAdjacentHTML('beforeend', html);
    new bootstrap.Modal(document.getElementById('msgInfoModal')).show();
}

// Native attachment selection with immediate local preview.
['opd-pick-doc','opd-pick-media','opd-pick-camera'].forEach(function(id) {
    document.getElementById(id).addEventListener('change', function() {
        if (!this.files.length) return;
        ['opd-pick-doc','opd-pick-media','opd-pick-camera'].forEach(function(pickerId) {
            document.getElementById(pickerId).removeAttribute('name');
        });
        this.name = 'attachment';
        showAttachmentPreview('opd', this.files[0]);
        updateOpdSendState();
    });
});
let selectedAttachmentUrls = { admin: null, opd: null };

function showAttachmentPreview(role, file) {
    var preview = document.getElementById(role + '-file-preview');
    var visual = document.getElementById(role + '-file-visual');
    var name = document.getElementById(role + '-file-name');
    var meta = document.getElementById(role + '-file-meta');
    var label = document.getElementById(role + '-attach-label');

    if (selectedAttachmentUrls[role]) {
        URL.revokeObjectURL(selectedAttachmentUrls[role]);
    }
    const fileUrl = URL.createObjectURL(file);
    selectedAttachmentUrls[role] = fileUrl;

    const isImage = file.type.indexOf('image/') === 0;
    const isVideo = file.type.indexOf('video/') === 0;
    const isPdf = file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf');

    preview.dataset.fileUrl = fileUrl;
    preview.dataset.fileType = isImage ? 'image' : (isVideo ? 'video' : (isPdf ? 'pdf' : 'doc'));

    name.textContent = file.name;
    meta.textContent = (file.type || 'File') + ' · ' + (file.size / 1024 / 1024).toFixed(file.size >= 1024 * 1024 ? 1 : 2) + ' MB (Klik untuk melihat)';
    visual.innerHTML = isImage
        ? '<img src="' + fileUrl + '" alt="Pratinjau lampiran">'
        : '<i class="fas ' + (isVideo ? 'fa-video' : (isPdf ? 'fa-file-pdf text-danger' : 'fa-file-alt')) + '"></i>';

    if (label) {
        label.textContent = file.name;
        label.classList.remove('d-none');
    }
    preview.classList.remove('d-none');
}

function previewAttachmentModal(role) {
    var preview = document.getElementById(role + '-file-preview');
    if (!preview || !preview.dataset.fileUrl) return;
    const url = preview.dataset.fileUrl;
    const type = preview.dataset.fileType;
    if (type === 'image') {
        openLightbox(url, false);
    } else if (type === 'video') {
        openLightbox(url, true);
    } else if (type === 'pdf') {
        openLightbox(url, 'pdf');
    } else {
        window.open(url, '_blank');
    }
}

function clearSelectedAttachment(role) {
    [role + '-pick-doc', role + '-pick-media', role + '-pick-camera'].forEach(function(id) {
        var input = document.getElementById(id);
        if (input) {
            input.value = '';
            input.removeAttribute('name');
        }
    });
    var preview = document.getElementById(role + '-file-preview');
    if (preview) {
        preview.classList.add('d-none');
        delete preview.dataset.fileUrl;
        delete preview.dataset.fileType;
    }
    if (selectedAttachmentUrls[role]) {
        URL.revokeObjectURL(selectedAttachmentUrls[role]);
        selectedAttachmentUrls[role] = null;
    }
    const label = document.getElementById(role + '-attach-label');
    if (label) label.classList.add('d-none');
    if (role === 'admin') updateAdminSendState();
    else if (role === 'opd') updateOpdSendState();
}

// Refresh-free chat polling
let opdPolling = false;
function escapeChatText(value) {
    const node = document.createElement('div');
    node.textContent = value || '';
    return node.innerHTML;
}
function opdMessageMarkup(message) {
    const mineClass = message.mine ? 'mine' : 'theirs';
    const alignment = message.mine ? 'justify-content-end' : 'justify-content-start';
    let attachment = '';
    if (message.attachment_url) {
        const ext = (message.attachment_type || '').toLowerCase();
        const isImage = ['jpg','jpeg','png','webp','gif'].includes(ext);
        const isVideo = ['mp4','mov','avi','3gp','webm'].includes(ext);
        const isPdf = ext === 'pdf';
        if (isImage) {
            attachment = `<div class="chat-attach-preview mt-2"><div class="chat-img-wrap"><button type="button" class="p-0 border-0 bg-transparent d-block" onclick="openLightbox('${message.attachment_url}')"><img src="${message.attachment_url}" alt="Lampiran" class="chat-attach-img lightbox-img"></button><a href="${message.attachment_url}" target="_blank" download class="chat-img-dl-btn btn btn-sm btn-dark bg-opacity-75 text-white border-0 position-absolute bottom-0 end-0 m-2 shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:32px; height:32px; backdrop-filter:blur(4px);" title="Unduh foto"><i class="fas fa-download"></i></a></div></div>`;
        } else if (isVideo) {
            attachment = `<div class="chat-attach-preview mt-2"><div class="chat-video-wrap" onclick="openLightbox('${message.attachment_url}', 'video')"><video preload="metadata" class="chat-attach-video d-block w-100" style="max-height:240px; object-fit:cover; pointer-events:none;"><source src="${message.attachment_url}#t=0.1" type="video/${ext}"></video><div class="position-absolute top-0 start-0 m-2 text-white opacity-90 small"><i class="fas fa-video"></i></div><div class="position-absolute top-50 start-50 translate-middle"><div class="chat-video-play-btn d-flex align-items-center justify-content-center text-white shadow-lg"><i class="fas fa-play fa-lg ms-1"></i></div></div><a href="${message.attachment_url}" target="_blank" download onclick="event.stopPropagation();" class="chat-img-dl-btn btn btn-sm btn-dark bg-opacity-75 text-white border-0 position-absolute bottom-0 end-0 m-2 shadow-sm rounded-circle p-0 d-flex align-items-center justify-content-center" style="width:32px; height:32px; backdrop-filter:blur(4px);" title="Unduh video"><i class="fas fa-download"></i></a></div></div>`;
        } else if (isPdf) {
            attachment = `<div class="chat-attach-preview mt-2"><div class="chat-attach-doc ${mineClass} d-flex align-items-center justify-content-between gap-2 p-2 rounded-3 border bg-white bg-opacity-75"><div class="d-flex align-items-center gap-2 overflow-hidden" style="cursor: pointer;" onclick="openLightbox('${message.attachment_url}', 'pdf')"><i class="fas fa-file-pdf text-danger fs-3 flex-shrink-0"></i><div class="chat-attach-doc-info text-truncate"><div class="chat-attach-doc-name fw-bold text-dark text-truncate small">${escapeChatText(message.attachment_name)}</div><div class="chat-attach-doc-meta text-muted" style="font-size: 0.75rem;"><i class="fas fa-eye me-1"></i>Klik untuk pratinjau PDF</div></div></div><div class="d-flex align-items-center gap-1 flex-shrink-0"><a href="${message.attachment_url}" target="_blank" download class="btn btn-sm btn-light border-0 text-secondary py-1 px-2" title="Unduh PDF"><i class="fas fa-download"></i></a></div></div></div>`;
        } else {
            attachment = `<div class="chat-attach-preview mt-2"><a href="${message.attachment_url}" target="_blank" class="chat-attach-doc ${mineClass}"><i class="fas fa-file-alt chat-attach-doc-icon"></i><div class="chat-attach-doc-info"><div class="chat-attach-doc-name">${escapeChatText(message.attachment_name)}</div><div class="chat-attach-doc-meta">${ext.toUpperCase()}</div></div><i class="fas fa-download chat-attach-doc-dl"></i></a></div>`;
        }
    }
    const receipt = message.mine ? `<span class="chat-receipt ${message.read ? 'read' : 'sent'}"></span>` : '';
    const infoDelivered = message.delivered_at || '-';
    const infoRead = message.read_at || '-';
    const infoMenuItem = message.mine ? `<li><a class="dropdown-item chat-info-btn" href="#" onclick="showMsgInfoFromEl(this); return false;" data-sent-at="${escapeChatText(message.created_at)}" data-sender="Anda" data-delivered-at="${escapeChatText(infoDelivered)}" data-read-at="${escapeChatText(infoRead)}"><i class="fas fa-info-circle me-2 text-muted"></i>Info</a></li>` : '';
    const replySender = message.mine ? 'Anda' : message.sender_name;
    const replyText = message.message || '';
    const replyAttachment = message.attachment_url || message.attachment_name || '';
    const replyMenuItem = `<li><a class="dropdown-item" href="#" onclick="replyMsgFromEl(this); return false;" data-reply-id="${message.id}" data-reply-sender="${escapeChatText(replySender)}" data-reply-text="${escapeChatText(replyText)}" data-reply-attachment="${escapeChatText(replyAttachment)}"><i class="fas fa-reply me-2 text-muted"></i>Balas</a></li>`;
    const editMenuItem = (message.mine && message.message) ? `<li><a class="dropdown-item" href="#" onclick="editMsgFromEl(this); return false;" data-edit-id="${message.id}" data-edit-text="${escapeChatText(message.message)}"><i class="fas fa-edit me-2 text-muted"></i>Edit Pesan</a></li>` : '';
    const copyMenuItem = (message.message && message.message.trim() !== '') ? `<li><a class="dropdown-item" href="#" onclick="copyMsg(this); return false;" data-msg="${escapeChatText(message.message)}"><i class="fas fa-copy me-2 text-muted"></i>Salin</a></li>` : '';
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const deleteMenuItem = message.mine ? `<li><hr class="dropdown-divider"></li><li><form action="/chat/message/${message.id}" method="POST" onsubmit="deleteMsgAjax(event, this)"><input type="hidden" name="_token" value="${csrfToken}"><input type="hidden" name="_method" value="DELETE"><button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash-alt me-2"></i>Hapus</button></form></li>` : '';
    const createdDateAttr = message.created_at_date ? `data-created-date="${escapeChatText(message.created_at_date)}"` : '';
    const editedBadge = message.is_edited ? `<span class="edited-tag text-white-50 ms-1" style="font-size:0.68rem;">(diedit)</span>` : '';
    let replyQuoteMarkup = '';
    if (message.reply_to) {
        const isMine = message.mine;
        const accentColor = isMine ? '#ffe0b2' : '#0d47a1';
        const borderColor = isMine ? '#ffa726' : '#0d47a1';
        const bgColor = isMine ? 'rgba(255,255,255,0.15)' : 'rgba(13,71,161,0.08)';
        const rSender = escapeChatText(message.reply_to.sender_name);
        const rText = escapeChatText(message.reply_to.message || (message.reply_to.attachment ? '(Lampiran Media)' : ''));
        replyQuoteMarkup = `<div class="chat-reply-quote mb-2 p-2 rounded-2 cursor-pointer" onclick="jumpToMessage('${message.reply_to.id}')" style="border-left: 3px solid ${borderColor}; background: ${bgColor};"><div class="fw-bold small text-truncate" style="font-size: 0.75rem; color: ${accentColor};">${rSender}</div><div class="small text-truncate" style="font-size: 0.78rem; opacity: 0.9;">${rText}</div></div>`;
    }

    return `<div class="d-flex mb-3 ${alignment}" data-chat-message-id="${message.id}" ${createdDateAttr} data-sent-at="${escapeChatText(message.created_at)}" data-delivered-at="${escapeChatText(infoDelivered)}" data-read-at="${escapeChatText(infoRead)}"><div class="chat-bubble-wrap ${mineClass}"><article class="chat-message ${mineClass}"><div class="small fw-bold mb-1 ${message.mine ? 'text-white-50' : 'text-primary'}">${escapeChatText(message.sender_name)}</div>${replyQuoteMarkup}${message.message ? `<div class="chat-msg-text" style="white-space:pre-line">${escapeChatText(message.message)}</div>` : ''}${attachment}<div class="chat-meta">${escapeChatText(message.created_at)}${editedBadge}${receipt}</div></article><div class="chat-msg-actions"><button class="chat-msg-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-chevron-down"></i></button><ul class="dropdown-menu dropdown-menu-${message.mine ? 'end' : 'start'} chat-ctx-menu shadow-lg">${infoMenuItem}${replyMenuItem}${editMenuItem}${copyMenuItem}${deleteMenuItem}</ul></div></div></div>`;
}
let opdKnownMessageIds = new Set();
document.querySelectorAll('#opd-chat-messages [data-chat-message-id]').forEach(function(el) {
    const id = parseInt(el.getAttribute('data-chat-message-id'));
    if (id) opdKnownMessageIds.add(id);
});

async function pollOpdChat() {
    if (opdPolling || document.hidden) return;
    opdPolling = true;
    const wasNearBottom = opdIsNearBottom();
    try {
        const response = await fetch('{{ route('opd.chat.poll', $ticket) }}', { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
        if (!response.ok) return;
        const data = await response.json();

        // Sync deleted messages in real-time
        const activeIds = new Set(data.messages.map(m => m.id));
        const renderedElements = chatCanvas.querySelectorAll('[data-chat-message-id]');
        renderedElements.forEach(function(el) {
            const id = parseInt(el.getAttribute('data-chat-message-id'));
            if (id && !activeIds.has(id)) {
                el.style.transition = 'all 0.25s ease';
                el.style.opacity = '0';
                el.style.transform = 'scale(0.9)';
                setTimeout(() => el.remove(), 250);
            }
        });

        data.messages.forEach(function(message) {
            if (!opdKnownMessageIds.has(message.id)) {
                opdKnownMessageIds.add(message.id);
                const existing = chatCanvas.querySelector('[data-chat-message-id="' + message.id + '"]');
                if (!existing) {
                    chatCanvas.insertAdjacentHTML('beforeend', opdMessageMarkup(message));
                    if (!message.mine) {
                        if (typeof window.showGlobalChatToast === 'function') {
                            window.showGlobalChatToast({
                                id: message.id,
                                ticket_id: {{ $ticket->id }},
                                tracking_number: '{{ $ticket->tracking_number ?? $ticket->ticket_number }}',
                                sender_name: message.sender_name,
                                sender_photo: message.sender_photo,
                                message: message.message,
                                created_at: message.created_at || 'baru saja'
                            });
                        }
                    }
                }
            } else {
                const existing = chatCanvas.querySelector('[data-chat-message-id="' + message.id + '"]');
                if (existing) {
                    if (message.message) {
                        const textEl = existing.querySelector('.chat-msg-text');
                        if (textEl && textEl.textContent !== message.message) {
                            textEl.textContent = message.message;
                            const editBtn = existing.querySelector('.chat-ctx-menu a[onclick*="editMsgFromEl"]');
                            if (editBtn) editBtn.setAttribute('data-edit-text', message.message);
                            const replyBtn = existing.querySelector('.chat-ctx-menu a[onclick*="replyMsgFromEl"]');
                            if (replyBtn) replyBtn.setAttribute('data-reply-text', message.message);
                        }
                    }
                    if (message.is_edited) {
                        const metaEl = existing.querySelector('.chat-meta');
                        if (metaEl && !metaEl.querySelector('.edited-tag')) {
                            const tag = document.createElement('span');
                            tag.className = 'edited-tag text-white-50 ms-1';
                            tag.style.fontSize = '0.68rem';
                            tag.textContent = '(diedit)';
                            metaEl.insertBefore(tag, metaEl.querySelector('.chat-receipt') || null);
                        }
                    }
                    if (message.read_at) {
                        existing.setAttribute('data-read-at', message.read_at);
                        const infoBtn = existing.querySelector('.chat-info-btn');
                        if (infoBtn) infoBtn.setAttribute('data-read-at', message.read_at);
                    }
                    if (message.delivered_at) {
                        existing.setAttribute('data-delivered-at', message.delivered_at);
                        const infoBtn = existing.querySelector('.chat-info-btn');
                        if (infoBtn) infoBtn.setAttribute('data-delivered-at', message.delivered_at);
                    }
                    if (message.mine) {
                        const receipt = existing.querySelector('.chat-receipt');
                        if (receipt && message.read) {
                            receipt.className = 'chat-receipt read';
                            receipt.innerHTML = '';
                        }
                    }
                }
            }
        });
        if (wasNearBottom) chatCanvas.scrollTop = chatCanvas.scrollHeight;
        updateOpdLatestButton();
        attachAllTouchEvents();
    } catch (error) {
        console.warn('Chat polling failed.', error);
    } finally {
        opdPolling = false;
    }
}
setInterval(pollOpdChat, 3000);
document.addEventListener('visibilitychange', function() { if (!document.hidden) pollOpdChat(); });

// --- Edit Message & Touch Gestures (Swipe to Reply / Long-Press Sheet) ---
let editingMsgId = null;

function editMsgFromEl(el) {
    const id = el ? el.getAttribute('data-edit-id') : null;
    const row = el ? el.closest('[data-chat-message-id]') : null;
    const currentTextEl = row ? row.querySelector('.chat-msg-text') : null;
    const text = currentTextEl ? currentTextEl.textContent : (el ? el.getAttribute('data-edit-text') || '' : '');
    if (!id || !text) return;

    cancelReply();
    editingMsgId = id;
    const editPreview = document.getElementById('edit-preview');
    const editPreviewText = document.getElementById('edit-preview-text');
    const msgInput = document.getElementById('opd-chat-message');

    if (editPreviewText) editPreviewText.textContent = text;
    if (editPreview) editPreview.classList.remove('d-none');

    if (msgInput) {
        msgInput.value = text;
        msgInput.focus();
        if (typeof autoResizeChatInput === 'function') autoResizeChatInput(msgInput);
    }
}

function cancelEdit() {
    editingMsgId = null;
    const editPreview = document.getElementById('edit-preview');
    const msgInput = document.getElementById('opd-chat-message');
    if (editPreview) editPreview.classList.add('d-none');
    if (msgInput) {
        msgInput.value = '';
        if (typeof autoResizeChatInput === 'function') autoResizeChatInput(msgInput);
    }
}

// Global touch click suppression to prevent long-press release from closing dropdowns
let suppressNextClick = false;
let isLongPressActive = false;

document.addEventListener('click', function(e) {
    if (e.target.closest('.chat-ctx-menu')) {
        suppressNextClick = false;
        return;
    }
    if (suppressNextClick) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        suppressNextClick = false;
    }
}, true);

// Mobile Action Sheet
let currentSheetData = null;

function openMobileActionSheet(row) {
    if (!row) return;
    const btn = row.querySelector('.chat-msg-menu-btn');
    if (btn) {
        if (navigator.vibrate) navigator.vibrate(35);
        const dropdown = bootstrap.Dropdown.getOrCreateInstance(btn);
        dropdown.show();
    }
}

function triggerSheetAction(action) {
    if (!currentSheetData) return;
    const { row, msgText } = currentSheetData;

    if (action === 'info') {
        const btn = row.querySelector('.chat-ctx-menu a.chat-info-btn');
        if (btn) showMsgInfoFromEl(btn);
    } else if (action === 'reply') {
        const btn = row.querySelector('.chat-ctx-menu a[onclick*="replyMsgFromEl"]');
        if (btn) replyMsgFromEl(btn);
    } else if (action === 'edit') {
        const btn = row.querySelector('.chat-ctx-menu a[onclick*="editMsgFromEl"]');
        if (btn) editMsgFromEl(btn);
    } else if (action === 'copy') {
        if (msgText) {
            navigator.clipboard.writeText(msgText).then(() => {
                alert('Teks pesan tersalin!');
            });
        }
    } else if (action === 'delete') {
        const form = row.querySelector('.chat-ctx-menu form');
        if (form) {
            const event = { preventDefault: () => {} };
            deleteMsgAjax(event, form);
        }
    }
}

// Context menu delegation (long-press on mobile / right-click on desktop)
chatCanvas?.addEventListener('contextmenu', function(e) {
    const row = e.target.closest('[data-chat-message-id]');
    if (row) {
        e.preventDefault();
        e.stopPropagation();
        suppressNextClick = true;
        isLongPressActive = true;
        openMobileActionSheet(row);
        setTimeout(() => { isLongPressActive = false; }, 300);
        return false;
    }
});

// Swipe to Reply & Long Press Gestures & Tap to Toggle Chevron
let activeTouchRow = null;
let touchStartX = 0;
let touchStartY = 0;
let currentDeltaX = 0;
let touchWasMoved = false;
let longPressTimer = null;

document.addEventListener('click', function(e) {
    if (!e.target.closest('.chat-bubble-wrap')) {
        document.querySelectorAll('.chat-bubble-wrap.show-menu-btn').forEach(b => b.classList.remove('show-menu-btn'));
    }
});

function initTouchEventsForBubble(row) {
    if (!row || row.dataset.touchInitialized) return;
    row.dataset.touchInitialized = 'true';

    const bubbleWrap = row.querySelector('.chat-bubble-wrap');
    if (!bubbleWrap) return;

    bubbleWrap.addEventListener('touchstart', function(e) {
        if (e.touches.length !== 1) return;
        const touch = e.touches[0];
        touchStartX = touch.clientX;
        touchStartY = touch.clientY;
        currentDeltaX = 0;
        touchWasMoved = false;
        activeTouchRow = row;

        clearTimeout(longPressTimer);
        longPressTimer = setTimeout(function() {
            if (Math.abs(currentDeltaX) < 15) {
                suppressNextClick = true;
                isLongPressActive = true;
                openMobileActionSheet(row);
                setTimeout(() => { isLongPressActive = false; }, 400);
            }
        }, 380);
    }, { passive: true });

    bubbleWrap.addEventListener('touchmove', function(e) {
        if (!activeTouchRow || activeTouchRow !== row) return;
        const touch = e.touches[0];
        const deltaX = touch.clientX - touchStartX;
        const deltaY = touch.clientY - touchStartY;

        if (Math.abs(deltaY) > 10 || Math.abs(deltaX) > 10) {
            touchWasMoved = true;
            clearTimeout(longPressTimer);
        }

        if (!isLongPressActive && deltaX > 8 && Math.abs(deltaX) > Math.abs(deltaY)) {
            currentDeltaX = Math.min(deltaX, 75);
            bubbleWrap.style.transition = 'none';
            bubbleWrap.style.transform = `translateX(${currentDeltaX}px)`;
        }
    }, { passive: true });

    const resetTouch = function(e) {
        clearTimeout(longPressTimer);
        if (activeTouchRow === row) {
            bubbleWrap.style.transition = 'transform 0.25s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
            if (!isLongPressActive && currentDeltaX >= 42) {
                if (navigator.vibrate) navigator.vibrate(30);
                const replyBtn = row.querySelector('.chat-ctx-menu a[onclick*="replyMsgFromEl"]');
                if (replyBtn) replyMsgFromEl(replyBtn);
            } else if (!isLongPressActive && !touchWasMoved && Math.abs(currentDeltaX) < 10) {
                if (e.target && !e.target.closest('.chat-msg-actions') && !e.target.closest('.chat-reply-quote')) {
                    const isShown = bubbleWrap.classList.contains('show-menu-btn');
                    document.querySelectorAll('.chat-bubble-wrap.show-menu-btn').forEach(b => b.classList.remove('show-menu-btn'));
                    if (!isShown) {
                        bubbleWrap.classList.add('show-menu-btn');
                    }
                }
            }
            bubbleWrap.style.transform = 'translateX(0)';
            setTimeout(() => { bubbleWrap.style.transition = ''; }, 250);
        }
        activeTouchRow = null;
        currentDeltaX = 0;
    };

    bubbleWrap.addEventListener('touchend', resetTouch, { passive: true });
    bubbleWrap.addEventListener('touchcancel', resetTouch, { passive: true });
}

function attachAllTouchEvents() {
    if (!chatCanvas) return;
    chatCanvas.querySelectorAll('[data-chat-message-id]').forEach(initTouchEventsForBubble);
}

document.addEventListener('DOMContentLoaded', function() {
    attachAllTouchEvents();
});
</script>
@endpush
