<?php

namespace App\Console\Commands;

use App\Models\Notification;
use App\Models\Ticket;
use App\Models\TicketStatusLog;
use App\Models\TicketResponse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupLastNotifications extends Command
{
    protected $signature = 'cleanup:last-notifications {count=15}';
    protected $description = 'Hapus N notifikasi terakhir dari WhatsApp beserta tiket terkait';

    public function handle()
    {
        $count = (int) $this->argument('count');

        $notifications = Notification::where('title', 'LIKE', '%WhatsApp%')
            ->orWhere('title', 'LIKE', '%Web SIMADU%')
            ->latest()
            ->take($count)
            ->get();

        if ($notifications->isEmpty()) {
            $this->info('Tidak ada notifikasi WhatsApp/Web yang ditemukan.');
            return 0;
        }

        $this->info("Ditemukan {$notifications->count()} notifikasi. Menghapus...");

        $notifIds = $notifications->pluck('id')->toArray();

        // Cari tiket terkait
        $tickets = Ticket::whereIn('notification_id', $notifIds)->get();
        $ticketIds = $tickets->pluck('id')->toArray();

        DB::transaction(function () use ($notifIds, $ticketIds) {
            // Hapus status logs
            $logsDeleted = TicketStatusLog::whereIn('ticket_id', $ticketIds)->delete();
            $this->info("  - {$logsDeleted} status logs dihapus");

            // Hapus responses
            if (class_exists(TicketResponse::class)) {
                $responsesDeleted = TicketResponse::whereIn('ticket_id', $ticketIds)->delete();
                $this->info("  - {$responsesDeleted} responses dihapus");
            }

            // Hapus tiket
            $ticketsDeleted = Ticket::whereIn('id', $ticketIds)->delete();
            $this->info("  - {$ticketsDeleted} tiket dihapus");

            // Hapus notifikasi
            $notifsDeleted = Notification::whereIn('id', $notifIds)->delete();
            $this->info("  - {$notifsDeleted} notifikasi dihapus");
        });

        $this->info('Selesai! Data berhasil dihapus.');
        Log::info("Cleanup: {$notifications->count()} notifikasi dan tiket terkait dihapus.");

        return 0;
    }
}
