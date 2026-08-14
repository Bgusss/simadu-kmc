<?php

namespace App\Http\Controllers\Opd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\TicketStatusLog; // Adjust based on your actual model name
use App\Models\TicketChatMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class OpdController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $opdId = $user->opd_id;

        // Ensure user belongs to an OPD
        if (!$opdId) {
            return abort(403, 'Unauthorized. Anda tidak terkait dengan OPD manapun.');
        }

        $stats = [
            'diterima' => Ticket::where('assigned_opd_id', $opdId)->whereIn('status', ['diteruskan', 'diterima'])->count(),
            'proses_disposisi' => Ticket::where('assigned_opd_id', $opdId)->where('status', 'proses_disposisi')->count(),
            'diproses' => Ticket::where('assigned_opd_id', $opdId)->where('status', 'diproses')->count(),
            'selesai' => Ticket::where('assigned_opd_id', $opdId)->where('status', 'selesai')->count(),
            'eskalasi' => Ticket::where('assigned_opd_id', $opdId)->where('status', 'eskalasi')->count(),
        ];

        $recentTickets = Ticket::where('assigned_opd_id', $opdId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('opd.dashboard', compact('stats', 'recentTickets'));
    }

    public function tickets(Request $request)
    {
        $user = Auth::user();
        
        $query = Ticket::where('assigned_opd_id', $user->opd_id);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhere('ticket_number', 'like', "%{$search}%")
                  ->orWhere('reporter_name', 'like', "%{$search}%")
                  ->orWhere('complaint', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('platform')) {
            $query->where('platform', $request->platform);
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        $platforms = Ticket::where('assigned_opd_id', $user->opd_id)
            ->whereNotNull('platform')
            ->select('platform')
            ->distinct()
            ->pluck('platform');

        $categories = Ticket::where('assigned_opd_id', $user->opd_id)
            ->whereNotNull('category')
            ->select('category')
            ->distinct()
            ->pluck('category');

        return view('opd.tickets.index', compact('tickets', 'platforms', 'categories'));
    }

    public function showTicket(Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->assigned_opd_id !== $user->opd_id) {
            return abort(403, 'Unauthorized.');
        }

        $ticket->load(['statusLogs.user', 'responses.user']);

        return view('opd.tickets.show', compact('ticket'));
    }

    public function editTicket(Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->assigned_opd_id !== $user->opd_id) {
            return abort(403, 'Unauthorized.');
        }

        $ticket->load(['statusLogs.user', 'responses.user']);

        return view('opd.tickets.edit', compact('ticket'));
    }

    public function respond(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->assigned_opd_id !== $user->opd_id) {
            return abort(403, 'Unauthorized.');
        }

        $request->validate([
            'response_text' => 'required|string',
            'attachment' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('attachments', 'public');
        }

        TicketResponse::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->id,
            'message' => $request->response_text,
            'attachment' => $attachmentPath,
        ]);

        // Auto change status to dijawab if not already closed
        if (!in_array($ticket->status, ['selesai', 'ditolak'])) {
            $ticket->updateStatus('dijawab', $user->id, 'OPD memberikan tanggapan');
        }

        return redirect()->route('opd.tickets.show', $ticket->id)->with('success', 'Tanggapan resmi OPD berhasil dikirim dan ditambahkan ke riwayat aduan.');
    }

    public function chatShow(Ticket $ticket)
    {
        $user = Auth::user();
        if ($ticket->assigned_opd_id !== $user->opd_id) abort(403, 'Unauthorized.');

        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        TicketChatMessage::where('ticket_id', $ticket->id)->whereIn('sender_id', $adminIds)
            ->where('read_by_opd', false)->update([
                'read_by_opd' => true,
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        $ticket->load(['assignedOpd', 'notification', 'chatMessages.sender']);
        return view('opd.chat.show', compact('ticket'));
    }

    public function chatPoll(Ticket $ticket)
    {
        $user = Auth::user();
        if ($ticket->assigned_opd_id !== $user->opd_id) abort(403, 'Unauthorized.');

        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        TicketChatMessage::where('ticket_id', $ticket->id)
            ->whereIn('sender_id', $adminIds)
            ->where('read_by_opd', false)
            ->update([
                'read_by_opd' => true,
                'delivered_at' => now(),
                'read_at' => now(),
            ]);

        return response()->json([
            'messages' => TicketChatMessage::with('sender')->where('ticket_id', $ticket->id)->orderBy('id')->get()
                ->map(fn (TicketChatMessage $message) => $this->chatMessagePayload($message, $user->id)),
        ]);
    }

    private function chatMessagePayload(TicketChatMessage $message, int $currentUserId): array
    {
        $extension = $message->attachment ? strtolower(pathinfo($message->attachment, PATHINFO_EXTENSION)) : null;
        $isRead = (bool) $message->read_by_admin;
        $deliveredAt = $message->delivered_at ?? $message->created_at;
        $readAt = $message->read_at ?? ($isRead ? ($message->updated_at ?? $message->created_at) : null);

        return [
            'id' => $message->id,
            'mine' => $message->sender_id === $currentUserId,
            'sender_name' => $message->sender_id === $currentUserId ? 'Anda' : ($message->sender?->name ?? 'Admin KMC'),
            'sender_photo' => $message->sender?->profile_photo ? asset('storage/' . $message->sender->profile_photo) : null,
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

    public function chatSend(Request $request, Ticket $ticket)
    {
        $user = Auth::user();
        if ($ticket->assigned_opd_id !== $user->opd_id) abort(403, 'Unauthorized.');

        $validated = $request->validate([
            'message' => 'nullable|string|max:2000|required_without:attachment',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,3gp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv|max:20480|required_without:message',
        ]);

        $attachment = $request->hasFile('attachment')
            ? $request->file('attachment')->store('chat-attachments', 'public')
            : null;

        $message = TicketChatMessage::create([
            'ticket_id' => $ticket->id, 'sender_id' => $user->id,
            'message' => $validated['message'] ?? '', 'attachment' => $attachment,
            'read_by_admin' => false, 'read_by_opd' => true,
            'delivered_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json(['message' => $this->chatMessagePayload($message->load('sender'), $user->id)], 201);
        }

        return redirect()->route('opd.chat.show', $ticket)->with('success', 'Pesan berhasil dikirim ke Admin KMC.');
    }

    public function chatDelete(Request $request, TicketChatMessage $message)
    {
        $user = Auth::user();

        if ($message->sender_id !== $user->id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Anda hanya bisa menghapus pesan sendiri.'], 403);
            }
            abort(403, 'Anda hanya bisa menghapus pesan sendiri.');
        }

        $ticket = $message->ticket;

        if ($ticket->assigned_opd_id !== $user->opd_id) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Unauthorized.'], 403);
            }
            abort(403, 'Unauthorized.');
        }

        if ($message->attachment) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($message->attachment);
        }

        $deletedId = $message->id;
        $message->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dihapus.',
                'deleted_id' => $deletedId,
            ]);
        }

        return redirect()->route('opd.chat.show', $ticket)
            ->with('success', 'Pesan berhasil dihapus.');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('opd.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $path = $request->file('profile_photo')->store('profiles', 'public');
            $user->profile_photo = $path;
        }

        $user->save();

        return redirect()->route('opd.profile')->with('success', 'Profil berhasil diperbarui.');
    }
}
