<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\AIClassification;
use App\Models\Ticket;
use App\Models\Opd;
use App\Models\TicketStatusLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TicketingService
{
    public function createTicketFromClassification(Notification $notification, AIClassification $aiClassification): Ticket
    {
        return DB::transaction(function () use ($notification, $aiClassification) {
            // 1. Generate tracking_number: KMC-YYYYMMDD-XXXX
            $today = now()->format('Ymd');
            $countToday = Ticket::whereDate('created_at', now()->toDateString())->count();
            $sequence = str_pad($countToday + 1, 4, '0', STR_PAD_LEFT);
            $trackingNumber = "KMC-{$today}-{$sequence}";

            // 2. Resolve OPD
            $assignedOpdId = null;
            $opdName = null;
            $suggestedOpds = $aiClassification->suggested_opds ?? [];
            
            if (is_array($suggestedOpds) && count($suggestedOpds) > 0) {
                $opdName = $suggestedOpds[0];
                $opd = Opd::where('name', $opdName)->first();
                
                if (!$opd) {
                    // Fuzzy match fallback
                    $opds = Opd::all();
                    $bestMatch = null;
                    $highestPercent = 0;
                    
                    foreach ($opds as $o) {
                        similar_text(strtolower($opdName), strtolower($o->name), $percent);
                        if ($percent > 70 && $percent > $highestPercent) {
                            $highestPercent = $percent;
                            $bestMatch = $o;
                        }
                    }
                    
                    if ($bestMatch) {
                        $opd = $bestMatch;
                        $opdName = $opd->name;
                    }
                }
                
                if ($opd) {
                    $assignedOpdId = $opd->id;
                }
            }

            // 3. Map priority
            $priorityMap = [
                'Tinggi' => 'tinggi',
                'Sedang' => 'sedang',
                'Rendah' => 'rendah',
            ];
            $priority = $priorityMap[$aiClassification->priority ?? 'Sedang'] ?? 'sedang';

            // Platform
            $platform = 'Facebook';
            $logNote = 'Tiket otomatis dibuat dari notifikasi Facebook';
            
            if ($notification->title === 'Instagram DM') {
                $platform = 'Instagram';
                $logNote = 'Tiket otomatis dibuat dari notifikasi Instagram DM';
            }

            // 4. Create Ticket
            $ticket = Ticket::create([
                'notification_id' => $notification->id,
                'ticket_number' => $trackingNumber,
                'tracking_number' => $trackingNumber,
                'ticket_time' => now(),
                'platform' => $platform,
                'reporter_name' => $notification->sender_name,
                'reporter_link' => $notification->permalink,
                'category' => $aiClassification->suggested_category,
                'sub_category' => $aiClassification->suggested_sub_category,
                'opd_related' => $opdName,
                'complaint' => $notification->comment_message ?? $notification->message,
                'status' => 'diterima',
                'assigned_opd_id' => $assignedOpdId,
                'priority' => $priority,
                'sla_deadline' => now()->addHours(24),
                'ai_confidence' => $aiClassification->confidence,
                'ai_reasoning' => $aiClassification->reasoning,
            ]);

            // 5. Create initial status log manually
            TicketStatusLog::create([
                'ticket_id' => $ticket->id,
                'from_status' => null,
                'to_status' => 'diterima',
                'note' => $logNote,
                'created_at' => now(),
            ]);

            // 6. Immediately update to 'diteruskan'
            $ticket->updateStatus('diteruskan', null, 'Diteruskan ke OPD: ' . ($opdName ?? 'Belum ditentukan'));

            // (is_read update removed so it only becomes read when admin opens it)

            return $ticket;
        });
    }
}
