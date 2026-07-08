<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;

class CheckEscalation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:check-escalation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek SLA tiket: auto proses_disposisi setelah 1x24 jam, eskalasi setelah 2x24 jam';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai pengecekan SLA tiket...');

        $disposisiCount = $this->processAutoDisposisi();
        $escalatedCount = $this->processEscalation();

        $this->info("Selesai. Proses Disposisi: {$disposisiCount} tiket. Eskalasi: {$escalatedCount} tiket.");
        return Command::SUCCESS;
    }

    /**
     * Tahap 1: Auto Proses Disposisi
     * 
     * Tiket dengan status 'diteruskan' atau 'dibaca' yang sudah melewati SLA 1x24 jam
     * tanpa respon dari OPD → otomatis berubah ke 'proses_disposisi'.
     */
    private function processAutoDisposisi(): int
    {
        $overdueTickets = Ticket::whereIn('status', ['diteruskan', 'dibaca'])
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->get();

        $count = 0;

        foreach ($overdueTickets as $ticket) {
            // Ubah status ke proses_disposisi
            $ticket->updateStatus(
                'proses_disposisi',
                null,
                'Tiket otomatis masuk Proses Disposisi — OPD belum merespon dalam 1x24 jam.'
            );

            // Set SLA deadline baru untuk tahap eskalasi (1x24 jam berikutnya)
            $ticket->update([
                'sla_deadline' => now()->addHours(24),
            ]);

            $this->info("  → Tiket {$ticket->tracking_number} → Proses Disposisi");
            $count++;
        }

        return $count;
    }

    /**
     * Tahap 2: Eskalasi
     * 
     * Tiket dengan status 'proses_disposisi' yang sudah melewati SLA 1x24 jam lagi
     * → eskalasi (prioritas naik) lalu kembali ke 'proses_disposisi' dengan SLA baru.
     */
    private function processEscalation(): int
    {
        $overdueTickets = Ticket::where('status', 'proses_disposisi')
            ->whereNotNull('sla_deadline')
            ->where('sla_deadline', '<', now())
            ->get();

        $count = 0;

        foreach ($overdueTickets as $ticket) {
            // Naikkan prioritas
            $oldPriority = $ticket->priority;
            $newPriority = $this->escalatePriority($oldPriority);

            $ticket->priority = $newPriority;
            $ticket->escalated_at = now();
            $ticket->escalation_count += 1;
            $ticket->sla_deadline = now()->addHours(24);
            $ticket->save();

            // Catat eskalasi di log
            $escalationNote = "Eskalasi ke-{$ticket->escalation_count}: OPD belum merespon dalam 2x24 jam.";
            if ($oldPriority !== $newPriority) {
                $escalationNote .= " Prioritas dinaikkan dari " . ucfirst($oldPriority) . " ke " . ucfirst($newPriority) . ".";
            }

            // Status → eskalasi (tercatat di log)
            $ticket->updateStatus('eskalasi', null, $escalationNote);

            // Status → kembali ke proses_disposisi (dengan SLA baru)
            $ticket->updateStatus(
                'proses_disposisi',
                null,
                'Tiket dikembalikan ke Proses Disposisi dengan SLA baru (24 jam). Menunggu respon OPD.'
            );

            $this->info("  → Tiket {$ticket->tracking_number} dieskalasi (ke-{$ticket->escalation_count}). Prioritas: {$newPriority}");
            $count++;
        }

        return $count;
    }

    /**
     * Naikkan level prioritas tiket.
     */
    private function escalatePriority(string $currentPriority): string
    {
        return match ($currentPriority) {
            'rendah' => 'sedang',
            'sedang' => 'tinggi',
            default  => 'tinggi',
        };
    }
}
