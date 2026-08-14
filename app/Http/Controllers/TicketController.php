<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Notification;
use App\Models\Opd;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\TicketChatMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::query();

        /*
     * Search
     */
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('ticket_number', 'like', "%{$search}%")
                    ->orWhere('reporter_name', 'like', "%{$search}%")
                    ->orWhere('complaint', 'like', "%{$search}%");
            });
        }

        /*
     * Filter Platform
     */
        if ($request->filled('platform')) {

            $query->where(
                'platform',
                $request->platform
            );
        }

        /*
     * Filter OPD
     */
        if ($request->filled('opd')) {

            $query->where(
                'opd_related',
                $request->opd
            );
        }

        /*
     * Filter Kategori
     */
        if ($request->filled('category')) {

            $query->where(
                'category',
                $request->category
            );
        }

        $tickets = $query

            ->latest()

            ->paginate(5)

            ->withQueryString();

        $platforms = Ticket::select('platform')
            ->distinct()
            ->pluck('platform');

        $categories = Ticket::select('category')
            ->distinct()
            ->pluck('category');

        $opds = Ticket::select('opd_related')
            ->distinct()
            ->pluck('opd_related');

        return view(
            'tickets.index',
            compact(
                'tickets',
                'platforms',
                'categories',
                'opds'
            )
        );
    }

    public function create(Request $request)
    {
        $notification = null;
        if ($request->filled('notification_id')) {
            $notification = Notification::with('ai')
                ->findOrFail($request->notification_id);
        }

        $today = now()->format('Ymd');
        $countToday = Ticket::whereDate('created_at', now()->toDateString())->count();
        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $ticketNumber = "KMC-{$today}-{$sequence}";

        $ticketTime = now();
        $opds = Opd::orderBy('name')->get();
        $categories = Category::with(['subCategories' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();

        return view('tickets.create', compact(
            'notification',
            'ticketNumber',
            'ticketTime',
            'opds',
            'categories'
        ));
    }

    public function store(Request $request)
    {
        $notification = null;
        if ($request->filled('notification_id')) {
            $existingTicket = Ticket::where('notification_id', $request->notification_id)->first();

            if ($existingTicket) {
                return redirect()
                    ->route('tickets.index')
                    ->with('error', 'Notifikasi ini sudah pernah dibuat tiket.');
            }
            $notification = Notification::findOrFail($request->notification_id);
        }

        $validated = $request->validate([
            'notification_id' => ['nullable', 'exists:notifications,id'],
            'platform' => ['required', 'string', 'max:255'],
            'reporter_name' => ['required', 'string', 'max:255'],
            'reporter_link' => ['nullable', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'sub_category' => ['required', 'string', 'max:255'],
            'opd_related' => ['required', 'string', 'max:255'],
            'complaint' => ['required', 'string'],
            'priority' => ['required', 'in:rendah,sedang,tinggi'],
        ]);

        $today = now()->format('Ymd');
        $countToday = Ticket::whereDate('created_at', now()->toDateString())->count();
        $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
        $trackingNumber = "KMC-{$today}-{$sequence}";

        $opd = Opd::where('name', $validated['opd_related'])->first();

        $ticket = null;

        DB::transaction(function () use (&$ticket, $validated, $notification, $trackingNumber, $opd) {
            $ticket = Ticket::create([
                'notification_id' => $notification ? $notification->id : null,
                'ticket_number' => $trackingNumber,
                'tracking_number' => $trackingNumber,
                'ticket_time' => now(),
                'platform' => $validated['platform'],
                'reporter_name' => $validated['reporter_name'],
                'reporter_link' => $validated['reporter_link'] ?? null,
                'category' => $validated['category'],
                'sub_category' => $validated['sub_category'],
                'opd_related' => $validated['opd_related'],
                'assigned_opd_id' => $opd ? $opd->id : null,
                'priority' => $validated['priority'],
                'complaint' => $validated['complaint'],
                'status' => 'diterima',
                'sla_deadline' => now()->addHours(24),
            ]);

            // (is_read update removed so it only becomes read when admin opens it)

            // Create initial status log
            \App\Models\TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'from_status' => null,
                'to_status' => 'diterima',
                'note' => $notification ? 'Tiket dibuat dari notifikasi ' . $validated['platform'] : 'Tiket manual dibuat oleh Admin',
            ]);
        });

        return redirect()
            ->route('tickets.index')
            ->with('success', 'Tiket berhasil disimpan.');
    }

    private function generateTicketNumber(): string
    {
        $lastTicket = Ticket::orderByDesc('id')->first();

        $nextNumber = 1;

        if ($lastTicket && preg_match('/-X-(\d+)$/', $lastTicket->ticket_number, $matches)) {
            $nextNumber = ((int) $matches[1]) + 1;
        }

        $sequence = str_pad((string) $nextNumber, 3, '0', STR_PAD_LEFT);

        return Carbon::now()->format('Ymd-His') . '-X-' . $sequence;
    }

    public function chatShow(Ticket $ticket)
    {
        abort_unless($ticket->assigned_opd_id, 404);
        $opdUserIds = \App\Models\User::where('role', 'opd')->pluck('id');
        TicketChatMessage::where('ticket_id', $ticket->id)->whereIn('sender_id', $opdUserIds)
            ->where('read_by_admin', false)->update([
                'read_by_admin' => true,
                'delivered_at' => now(),
                'read_at' => now(),
            ]);
        $ticket->load(['assignedOpd', 'notification', 'chatMessages.sender', 'statusLogs.user']);

        return view('tickets.chat.show', compact('ticket'));
    }

    public function chatPoll(Ticket $ticket)
    {
        abort_unless($ticket->assigned_opd_id, 404);

        $opdUserIds = \App\Models\User::where('role', 'opd')->pluck('id');
        TicketChatMessage::where('ticket_id', $ticket->id)
            ->whereIn('sender_id', $opdUserIds)
            ->where('read_by_admin', false)
            ->update([
                'read_by_admin' => true,
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => TicketChatMessage::with('sender')->where('ticket_id', $ticket->id)->orderBy('id')->get()
                ->map(fn (TicketChatMessage $message) => $this->chatMessagePayload($message)),
        ]);
    }

    private function chatMessagePayload(TicketChatMessage $message): array
    {
        $extension = $message->attachment ? strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION)) : null;
        $isRead = (bool) $message->read_by_opd;
        $deliveredAt = $message->delivered_at ?? $message->created_at;
        $readAt = $message->read_at ?? ($isRead ? ($message->updated_at ?? $message->created_at) : null);

        return [
            'id' => $message->id,
            'mine' => $message->sender_id === auth()->id(),
            'sender_name' => $message->sender_id === auth()->id() ? 'Admin KMC' : ($message->sender?->name ?? 'OPD'),
            'message' => $message->message,
            'attachment_url' => $message->attachment ? asset('storage/' . $message->attachment) : null,
            'attachment_name' => $message->attachment ? basename($message->attachment) : null,
            'attachment_type' => $extension,
            'created_at' => $message->created_at?->format('H:i'),
            'read' => $isRead,
            'delivered_at' => $deliveredAt?->format('j/n/Y \p\u\k\u\l H.i'),
            'read_at' => $readAt?->format('j/n/Y \p\u\k\u\l H.i'),
        ];
    }

    public function show(Ticket $ticket)
    {
        $opdUserIds = \App\Models\User::where('role', 'opd')->pluck('id');
        TicketResponse::where('ticket_id', $ticket->id)->whereIn('user_id', $opdUserIds)
            ->where('is_read', false)->update(['is_read' => true]);
        $ticket->load(['statusLogs.user', 'responses.user']);
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $opds = Opd::orderBy('name')->get();
        $categories = Category::with(['subCategories' => function ($q) {
            $q->orderBy('name');
        }])->orderBy('name')->get();
        return view('tickets.edit', compact('ticket', 'opds', 'categories'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'sub_category' => 'required|string|max:255',
            'opd_related' => 'required|string|max:255',
            'priority' => 'required|in:rendah,sedang,tinggi',
        ]);

        $opd = Opd::where('name', $validated['opd_related'])->first();

        $ticket->update([
            'category' => $validated['category'],
            'sub_category' => $validated['sub_category'],
            'opd_related' => $validated['opd_related'],
            'assigned_opd_id' => $opd ? $opd->id : $ticket->assigned_opd_id,
            'priority' => $validated['priority'],
        ]);

        return redirect()->route('tickets.index')
            ->with('success', 'Detail tiket berhasil diperbarui.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Tiket berhasil dihapus.');
    }

    public function sendChat(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'message' => 'nullable|string|max:2000|required_without:attachment',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,3gp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv|max:20480|required_without:message',
        ]);

        $attachment = $request->hasFile('attachment')
            ? $request->file('attachment')->store('chat-attachments', 'public')
            : null;

        $message = TicketChatMessage::create([
            'ticket_id' => $ticket->id,
            'sender_id' => auth()->id(),
            'message' => $validated['message'] ?? '',
            'attachment' => $attachment,
            'read_by_admin' => true,
            'read_by_opd' => false,
            'delivered_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->chatMessagePayload($message->load('sender'))], 201);
        }

        return redirect()->route($request->boolean('return_to_chat') ? 'tickets.chat.show' : 'tickets.show', $ticket)
            ->with('success', 'Pesan berhasil dikirim ke OPD.');
    }

    public function deleteChat(TicketChatMessage $message)
    {
        if ($message->sender_id !== auth()->id()) {
            abort(403, 'Anda hanya bisa menghapus pesan sendiri.');
        }

        $ticket = $message->ticket;

        if ($message->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return redirect()->route('tickets.chat.show', $ticket)
            ->with('success', 'Pesan berhasil dihapus.');
    }

    /**
     * Admin memperbarui status tiket.
     * (Dipindahkan dari OpdController — sekarang hanya admin yang bisa update status)
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status' => 'required|in:diterima,diproses,dijawab,selesai,eskalasi,proses_disposisi,ditolak',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $oldStatus = $ticket->status;
        $newStatus = $request->status;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        if ($oldStatus === $newStatus && empty($request->notes) && !$attachmentPath) {
            return redirect()->back()->with('error', 'Tidak ada perubahan status atau catatan yang disimpan.');
        }

        $ticket->updateStatus($newStatus, auth()->id(), $request->notes ?? 'Status diperbarui oleh Admin KMC', $attachmentPath);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
    }
}
