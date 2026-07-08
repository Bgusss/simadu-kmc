<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketStatusLog extends Model
{
    /**
     * Tabel ini hanya menggunakan created_at, tanpa updated_at.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'ticket_id',
        'from_status',
        'to_status',
        'changed_by',
        'note',
        'attachment',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Tiket yang terkait dengan log ini.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * User yang mengubah status (bisa null jika perubahan oleh sistem).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
