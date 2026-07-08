<?php

namespace App\Http\Controllers\Opd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\TicketResponse;
use App\Models\TicketStatusLog; // Adjust based on your actual model name
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        return redirect()->back()->with('success', 'Tanggapan berhasil ditambahkan.');
    }

    public function updateStatus(Request $request, Ticket $ticket)
    {
        $user = Auth::user();

        if ($ticket->assigned_opd_id !== $user->opd_id) {
            return abort(403, 'Unauthorized.');
        }

        $request->validate([
            'status' => 'required|in:diterima,diproses,selesai,eskalasi,proses_disposisi',
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

        $ticket->updateStatus($newStatus, $user->id, $request->notes ?? 'Status diperbarui oleh OPD', $attachmentPath);

        return redirect()->back()->with('success', 'Status tiket berhasil diperbarui.');
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
