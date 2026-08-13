@extends('opd.layouts.app')

@section('title', 'Chat Tiket')

@section('content')
<div class="mb-3"><a href="{{ route('opd.chat.index') }}" class="text-decoration-none small fw-semibold"><i class="fas fa-arrow-left me-1"></i>Kembali ke Live Chat</a></div>
<div class="card card-premium overflow-hidden">
    <div class="card-header bg-white px-4 py-3 d-flex align-items-center gap-3">
        <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" style="width:42px;height:42px"><i class="fas fa-comments"></i></div>
        <div class="flex-grow-1"><strong>{{ $ticket->tracking_number ?? $ticket->ticket_number }}</strong><div class="small text-muted">{{ $ticket->reporter_name ?? 'Anonim' }} · {{ $ticket->category }}</div></div>
        <span class="badge bg-light text-dark border">Admin KMC</span>
    </div>
    <div id="chat-messages" class="card-body bg-light p-4" style="height:58vh;min-height:380px;overflow-y:auto">
        @forelse($ticket->responses as $response)
            @php $mine = $response->user_id === auth()->id(); @endphp
            <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                <div class="rounded-4 px-3 py-2 shadow-sm {{ $mine ? 'bg-primary text-white' : 'bg-white text-dark' }}" style="max-width:76%">
                    <div class="small fw-bold mb-1 {{ $mine ? 'text-white-50' : 'text-primary' }}">{{ $mine ? 'Anda' : ($response->user?->name ?? 'Admin KMC') }}</div>
                    <div style="white-space:pre-line">{{ $response->message }}</div>
                    @if($response->attachment)
                        <a href="{{ asset('storage/'.$response->attachment) }}" target="_blank" class="d-block mt-2 small {{ $mine ? 'text-white' : 'text-primary' }}"><i class="fas fa-paperclip me-1"></i>Lihat lampiran</a>
                    @endif
                    <div class="small mt-1 {{ $mine ? 'text-white-50' : 'text-muted' }}" style="font-size:.7rem">{{ $response->created_at?->format('d M Y, H:i') }}</div>
                </div>
            </div>
        @empty
            <div class="h-100 d-flex align-items-center justify-content-center text-center text-muted"><div><i class="far fa-comment-dots fa-3x mb-3 opacity-50"></i><div>Belum ada pesan. Mulai percakapan dengan Admin KMC.</div></div></div>
        @endforelse
    </div>
    <div class="card-footer bg-white p-3">
        <form action="{{ route('opd.chat.send', $ticket) }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 align-items-end">
            @csrf
            <label for="chat-attachment" class="btn btn-light border mb-0" title="Lampiran"><i class="fas fa-paperclip"></i></label>
            <input id="chat-attachment" name="attachment" type="file" class="d-none" accept=".jpg,.jpeg,.png,.webp,.mp4,.mov,.avi,.3gp">
            <textarea id="chat-message" name="message" class="form-control" rows="2" maxlength="2000" placeholder="Tulis pesan untuk Admin KMC..." required></textarea>
            <button id="chat-send" class="btn btn-primary px-4" type="submit"><i class="fas fa-paper-plane me-1"></i>Kirim</button>
        </form>
        @error('message')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
        @error('attachment')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
    </div>
</div>
@push('scripts')
<script>const messages=document.getElementById('chat-messages'); if(messages) messages.scrollTop=messages.scrollHeight;</script>
@endpush
@endsection
