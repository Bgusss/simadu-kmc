<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket;

class TicketingController extends Controller
{
    public function index(Request $request)
    {
        // 1. Exact track by ID
        if ($request->has('track_id') && !empty($request->track_id)) {
            $ticket = Ticket::where('tracking_number', $request->track_id)
                            ->orWhere('ticket_number', $request->track_id)->first();
            if ($ticket) {
                return redirect()->route('ticketing.show', $ticket->tracking_number ?? $ticket->ticket_number);
            }
            return redirect()->route('ticketing.index')->with('error', 'Tiket dengan nomor pelacakan tersebut tidak ditemukan.');
        }

        // 2. Global stats query (unfiltered)
        $allTickets = Ticket::with('assignedOpd')->get();
        
        $totalLaporan = $allTickets->count();
        $baru = $allTickets->where('status', 'diterima')->count();
        $disposisi = $allTickets->whereIn('status', ['diteruskan', 'dibaca', 'proses_disposisi'])->count();
        $tindakLanjut = $allTickets->whereIn('status', ['diproses', 'dijawab'])->count();
        $selesai = $allTickets->where('status', 'selesai')->count();

        $now = now();
        $outSla = $allTickets->filter(function ($t) use ($now) {
            return $t->sla_deadline && $t->sla_deadline < $now && !in_array($t->status, ['selesai', 'dijawab']);
        })->count();
        $inSla = $totalLaporan - $outSla;

        $kategoriData = $allTickets->groupBy('category')->map->count();
        $subKategoriData = $allTickets->groupBy('sub_category')->map->count();

        $opdData = $allTickets->groupBy(function($t) {
            return $t->assignedOpd ? $t->assignedOpd->name : ($t->opd_related ?? 'Tidak Diketahui');
        })->map(function($group) use ($now) {
            return [
                'total' => $group->count(),
                'eskalasi' => $group->where('status', 'eskalasi')->count(),
                'out_sla' => $group->filter(function($t) use ($now) {
                    return $t->sla_deadline && $t->sla_deadline < $now && !in_array($t->status, ['selesai', 'dijawab']);
                })->count(),
                'in_sla' => $group->filter(function($t) use ($now) {
                    return !($t->sla_deadline && $t->sla_deadline < $now && !in_array($t->status, ['selesai', 'dijawab']));
                })->count(),
            ];
        });

        // 3. Table query (filtered and paginated)
        $tableQuery = Ticket::with('assignedOpd');

        if ($request->filled('search')) {
            $search = $request->search;
            $tableQuery->where(function($q) use ($search) {
                $q->where('complaint', 'like', "%{$search}%")
                  ->orWhere('reporter_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $tableQuery->where('status', $request->status);
        }

        $sortOrder = $request->input('sort', 'desc');
        if (!in_array($sortOrder, ['asc', 'desc'])) $sortOrder = 'desc';
        
        $tableQuery->orderBy('created_at', $sortOrder);
        
        $tickets = $tableQuery->paginate(5)->withQueryString();

        return view('public.ticketing', compact('tickets', 'totalLaporan', 'baru', 'disposisi', 'tindakLanjut', 'selesai', 'outSla', 'inSla', 'kategoriData', 'subKategoriData', 'opdData', 'now'));
    }

    public function show($tracking_number)
    {
        $ticket = Ticket::where('tracking_number', $tracking_number)
            ->with(['statusLogs' => function($q) {
                $q->orderBy('created_at', 'asc');
            }, 'responses' => function($q) {
                $q->orderBy('created_at', 'asc');
            }])
            ->firstOrFail();

        return view('public.ticketing-detail', compact('ticket'));
    }
}
