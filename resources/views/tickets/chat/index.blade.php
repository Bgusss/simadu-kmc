@extends('layouts.app')

@section('title', 'Live Chat Admin')
@section('page-title')
    <i class="fa-solid fa-comments text-primary me-2"></i> Live Chat
@endsection

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
    <div><div class="text-primary fw-bold small text-uppercase" style="letter-spacing:.08em">Koordinasi OPD</div><h3 class="fw-bold text-dark mb-1">Percakapan per tiket</h3><p class="text-muted mb-0">Pilih tiket untuk berkomunikasi dengan OPD terkait.</p></div>
    <form method="GET" class="d-flex gap-2" role="search"><input id="admin-chat-search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari tiket atau pelapor"><button class="btn btn-primary px-3" type="submit"><i class="fas fa-search"></i></button></form>
</div>
<div class="card card-premium overflow-hidden"><div class="list-group list-group-flush">
@forelse($tickets as $ticket)
@php $last=$ticket->responses->first(); $unread=$unreadByTicket[$ticket->id] ?? 0; @endphp
<a href="{{ route('tickets.chat.show',$ticket) }}" class="list-group-item list-group-item-action p-4 border-0 border-bottom"><div class="d-flex align-items-center gap-3"><div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width:46px;height:46px"><i class="fas fa-building"></i></div><div class="flex-grow-1 min-w-0"><div class="d-flex justify-content-between gap-3"><strong class="text-dark">{{ $ticket->tracking_number ?? $ticket->ticket_number }}</strong>@if($last)<small class="text-muted">{{ $last->created_at?->diffForHumans() }}</small>@endif</div><div class="small text-muted mt-1">{{ $ticket->assignedOpd?->name ?? 'OPD belum ditetapkan' }} · {{ $ticket->reporter_name ?? 'Anonim' }}</div><div class="small text-secondary text-truncate mt-2">{{ $last?->message ?? 'Belum ada percakapan dengan OPD.' }}</div></div>@if($unread)<span class="badge rounded-pill bg-danger">{{ $unread }}</span>@endif<i class="fas fa-chevron-right text-muted"></i></div></a>
@empty
<div class="text-center py-5 text-muted"><i class="far fa-comments fa-3x mb-3 opacity-50"></i><div>Belum ada tiket yang diteruskan ke OPD.</div></div>
@endforelse
</div></div><div class="mt-4">{{ $tickets->links() }}</div>
@endsection
