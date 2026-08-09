<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'notification_id',
        'ticket_number',
        'tracking_number',
        'ticket_time',
        'platform',
        'reporter_name',
        'reporter_link',
        'category',
        'sub_category',
        'opd_related',
        'complaint',
        'status',
        'assigned_opd_id',
        'priority',
        'sla_deadline',
        'ai_confidence',
        'ai_reasoning',
        'escalated_at',
        'escalation_count',
    ];


    protected $casts = [
        'ticket_time'   => 'datetime',
        'sla_deadline'  => 'datetime',
        'escalated_at'  => 'datetime',
        'ai_confidence' => 'float',
    ];


    // ──────────────────────────────────────────────
    //  Accessors
    // ──────────────────────────────────────────────

    public function getComplaintAttribute($value)
    {
        if (!$value) return $value;
        // Hapus teks Simadu KMC case insensitive beserta "@" jika ada
        $cleaned = preg_replace('/@?simadu\s*kmc/iu', '', $value);
        // Hapus spasi dan tanda baca sisa di awal/akhir string
        return trim($cleaned, " \t\n\r\0\x0B,.:;");
    }

    // ──────────────────────────────────────────────
    //  Relasi
    // ──────────────────────────────────────────────

    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }


    public function assignedOpd()
    {
        return $this->belongsTo(Opd::class, 'assigned_opd_id');
    }


    public function statusLogs()
    {
        return $this->hasMany(TicketStatusLog::class);
    }

    public function responses()
    {
        return $this->hasMany(TicketResponse::class);
    }


    // ──────────────────────────────────────────────
    //  Update status dengan pencatatan log
    // ──────────────────────────────────────────────

    /**
     * Ubah status tiket dan catat perubahannya di log.
     *
     * @param  string       $newStatus
     * @param  int|null     $changedBy  ID user yang mengubah (null = sistem)
     * @param  string|null  $note       Catatan perubahan
     * @return TicketStatusLog
     */
    public function updateStatus(
        string $newStatus,
        ?int $changedBy = null,
        ?string $note = null,
        ?string $attachment = null
    ): TicketStatusLog {

        $fromStatus = $this->status;

        // --- LOGIKA AUTO-FILL STATUS YANG TERLEWATI ---
        $statusLevels = [
            'baru' => 1,
            'proses_disposisi' => 2,
            'diteruskan' => 2,
            'diterima' => 3,
            'diproses' => 4,
            'dijawab' => 5,
            'selesai' => 6,
            'eskalasi' => 6,
            'ditolak' => 6,
        ];
        
        $levelToStatus = [
            2 => 'diteruskan',
            3 => 'diterima',
            4 => 'diproses',
        ];

        $fromLevel = $statusLevels[$fromStatus] ?? 0;
        $toLevel = $statusLevels[$newStatus] ?? 0;

        // Jika status baru lebih jauh dari status saat ini (melompat)
        if ($fromLevel > 0 && $toLevel > 0 && $toLevel > $fromLevel + 1) {
            $currentStatus = $fromStatus;
            
            for ($lvl = $fromLevel + 1; $lvl < $toLevel; $lvl++) {
                if (isset($levelToStatus[$lvl])) {
                    $skippedStatus = $levelToStatus[$lvl];
                    
                    TicketStatusLog::create([
                        'ticket_id'   => $this->id,
                        'from_status' => $currentStatus,
                        'to_status'   => $skippedStatus,
                        'note'        => 'Status otomatis disesuaikan oleh sistem',
                        'changed_by'  => null, // Sistem
                        'attachment'  => null,
                    ]);
                    
                    $currentStatus = $skippedStatus;
                }
            }
            $fromStatus = $currentStatus;
        }
        // ----------------------------------------------

        $this->update([
            'status' => $newStatus,
        ]);

        $log = TicketStatusLog::create([
            'ticket_id'   => $this->id,
            'from_status' => $fromStatus,
            'to_status'   => $newStatus,
            'note'        => $note,
            'changed_by'  => $changedBy,
            'attachment'  => $attachment,
        ]);

        // --- Kirim notifikasi WhatsApp ke pelapor ---
        try {
            $phone = null;
            if ($this->reporter_link) {
                $phone = str_replace('wa.me/', '', $this->reporter_link);
            }

            if ($phone && $this->reporter_name) {
                $statusLabels = [
                    'baru' => 'Baru',
                    'diterima' => 'Diterima',
                    'proses_disposisi' => 'Proses Disposisi',
                    'diteruskan' => 'Diteruskan',
                    'diproses' => 'Diproses',
                    'dijawab' => 'Dijawab',
                    'selesai' => 'Selesai',
                    'eskalasi' => 'Eskalasi',
                    'ditolak' => 'Ditolak',
                ];

                $fromLabel = $statusLabels[$fromStatus] ?? ucfirst($fromStatus);
                $toLabel = $statusLabels[$newStatus] ?? ucfirst($newStatus);
                $trackingNumber = $this->tracking_number ?? $this->ticket_number;
                $linkCek = config('app.url') . "/ticketing/{$trackingNumber}";

                $pesan = "📢 *UPDATE STATUS LAPORAN*\n\n"
                    . "Halo *{$this->reporter_name}*, status laporan Anda *{$trackingNumber}* telah diperbarui:\n\n"
                    . "🔄 {$fromLabel} → *{$toLabel}*\n";

                if ($note) {
                    $pesan .= "\nCatatan: \"{$note}\"\n";
                }

                $pesan .= "\n🔍 Lacak: {$linkCek}\n\n"
                    . "Terima kasih atas kesabaran Anda. 🙏";

                \App\Services\FonnteService::send($phone, $pesan);
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Ticket::updateStatus WA notification gagal', [
                'ticket_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }

        return $log;
    }

}