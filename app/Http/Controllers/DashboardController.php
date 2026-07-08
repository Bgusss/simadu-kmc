<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\AIClassification;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        /*
         * Statistik Tiket
         */
        $totalTickets = Ticket::count();

        $ticketsMenunggu = Ticket::whereIn('status', ['diterima', 'diteruskan'])->count();

        $ticketsDisposisi = Ticket::where('status', 'proses_disposisi')->count();

        $ticketsDiproses = Ticket::whereIn('status', ['dibaca', 'diproses', 'dijawab'])->count();

        $ticketsSelesai = Ticket::where('status', 'selesai')->count();
        
        $ticketsEskalasi = Ticket::where('status', 'eskalasi')->count();

        /*
         * Notifikasi Prioritas Tinggi Terbaru
         */
        $highPriorityNotification = Notification::query()
            ->join('ai_classifications', 'notifications.id', '=', 'ai_classifications.notification_id')
            ->where('ai_classifications.priority', 'Tinggi')
            ->select('notifications.*', 'ai_classifications.priority', 'ai_classifications.suggested_category', 'ai_classifications.suggested_sub_category')
            ->latest('notifications.created_at')
            ->first();

        /*
         * Notifikasi Terbaru (selain prioritas Tinggi)
         */
        $latestNotification = Notification::whereNotExists(function ($query) {
                $query->select('id')
                      ->from('ai_classifications')
                      ->whereColumn('ai_classifications.notification_id', 'notifications.id')
                      ->where('priority', 'Tinggi');
            })
            ->latest()
            ->first();

        if ($latestNotification) {
            $latestNotification->ai = AIClassification::where('notification_id', $latestNotification->id)->first();
            $latestNotification->suggested_category = $latestNotification->ai?->suggested_category;
            $latestNotification->suggested_sub_category = $latestNotification->ai?->suggested_sub_category;
            $latestNotification->priority = $latestNotification->ai?->priority;
        }

        /*
         * Tren Aduan Harian (7 Hari Terakhir)
         */
        $ticketTrends = Ticket::selectRaw('DATE(created_at) as date, count(*) as count')
            ->where('created_at', '>=', now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $label = now()->subDays($i)->isoFormat('D MMM');
            $chartLabels[] = $label;
            $chartData[] = $ticketTrends[$date] ?? 0;
        }

        /*
         * Distribusi Platform Aduan
         */
        $platformStats = Ticket::selectRaw('platform, count(*) as count')
            ->groupBy('platform')
            ->get()
            ->pluck('count', 'platform')
            ->toArray();

        /*
         * Tentukan card mana yang ditampilkan di depan berdasarkan waktu terbaru
         */
        $showPriorityFirst = false;
        if ($highPriorityNotification && $latestNotification) {
            // Bandingkan waktu created_at, jika prioritas tinggi lebih baru, tampilkan di depan
            $showPriorityFirst = $highPriorityNotification->created_at > $latestNotification->created_at;
        } elseif ($highPriorityNotification && !$latestNotification) {
            // Jika hanya ada prioritas tinggi, tampilkan di depan
            $showPriorityFirst = true;
        }

        return view('dashboard', compact(
            'totalTickets',
            'ticketsMenunggu',
            'ticketsDisposisi',
            'ticketsDiproses',
            'ticketsSelesai',
            'ticketsEskalasi',
            'latestNotification',
            'highPriorityNotification',
            'showPriorityFirst',
            'chartLabels',
            'chartData',
            'platformStats'
        ));
    }

    public function pollNotifications()
    {
        /*
         * Notifikasi Prioritas Tinggi Terbaru
         */
        $highPriorityNotification = Notification::query()
            ->join('ai_classifications', 'notifications.id', '=', 'ai_classifications.notification_id')
            ->where('ai_classifications.priority', 'Tinggi')
            ->select('notifications.*', 'ai_classifications.priority', 'ai_classifications.suggested_category', 'ai_classifications.suggested_sub_category')
            ->latest('notifications.created_at')
            ->first();

        /*
         * Notifikasi Terbaru (selain prioritas Tinggi)
         */
        $latestNotification = Notification::whereNotExists(function ($query) {
                $query->select('id')
                      ->from('ai_classifications')
                      ->whereColumn('ai_classifications.notification_id', 'notifications.id')
                      ->where('priority', 'Tinggi');
            })
            ->latest()
            ->first();

        if ($latestNotification) {
            $latestNotification->ai = AIClassification::where('notification_id', $latestNotification->id)->first();
            $latestNotification->suggested_category = $latestNotification->ai?->suggested_category;
            $latestNotification->suggested_sub_category = $latestNotification->ai?->suggested_sub_category;
            $latestNotification->priority = $latestNotification->ai?->priority;
        }

        /*
         * Tentukan card mana yang ditampilkan di depan berdasarkan waktu terbaru
         */
        $showPriorityFirst = false;
        if ($highPriorityNotification && $latestNotification) {
            $showPriorityFirst = $highPriorityNotification->created_at > $latestNotification->created_at;
        } elseif ($highPriorityNotification && !$latestNotification) {
            $showPriorityFirst = true;
        }

        /*
         * Statistik Tiket untuk update badge
         */
        $totalTickets = Ticket::count();
        $ticketsMenunggu = Ticket::whereIn('status', ['diterima', 'diteruskan'])->count();
        $ticketsDisposisi = Ticket::where('status', 'proses_disposisi')->count();
        $ticketsDiproses = Ticket::whereIn('status', ['dibaca', 'diproses', 'dijawab'])->count();
        $ticketsSelesai = Ticket::where('status', 'selesai')->count();
        $ticketsEskalasi = Ticket::where('status', 'eskalasi')->count();

        return response()->json([
            'highPriorityNotification' => $highPriorityNotification ? [
                'id' => $highPriorityNotification->id,
                'sender_name' => $highPriorityNotification->sender_name ?? 'Pengirim Anonim',
                'message' => trim(preg_replace('/@?simadu\s*kmc\s*/i', '', $highPriorityNotification->message)),
                'suggested_category' => $highPriorityNotification->suggested_category,
                'suggested_sub_category' => $highPriorityNotification->suggested_sub_category,
                'created_at' => $highPriorityNotification->created_at->diffForHumans(),
                'permalink' => $highPriorityNotification->permalink,
                'title' => strtolower($highPriorityNotification->title ?? ''),
            ] : null,
            'latestNotification' => $latestNotification ? [
                'id' => $latestNotification->id,
                'sender_name' => $latestNotification->sender_name ?? 'Pengirim Anonim',
                'message' => trim(preg_replace('/@?simadu\s*kmc\s*/i', '', $latestNotification->message)),
                'suggested_category' => $latestNotification->suggested_category,
                'suggested_sub_category' => $latestNotification->suggested_sub_category,
                'created_at' => $latestNotification->created_at->diffForHumans(),
                'permalink' => $latestNotification->permalink,
                'title' => strtolower($latestNotification->title ?? ''),
            ] : null,
            'showPriorityFirst' => $showPriorityFirst,
            'stats' => [
                'totalTickets' => $totalTickets,
                'ticketsMenunggu' => $ticketsMenunggu,
                'ticketsDisposisi' => $ticketsDisposisi,
                'ticketsDiproses' => $ticketsDiproses,
                'ticketsSelesai' => $ticketsSelesai,
                'ticketsEskalasi' => $ticketsEskalasi,
            ]
        ]);
    }
}
