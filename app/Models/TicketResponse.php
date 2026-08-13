<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketResponse extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'attachment',
        'is_read',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /**
     * Tiket yang dibalas.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * User yang memberikan balasan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
