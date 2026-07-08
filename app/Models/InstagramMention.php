<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InstagramMention extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender',
        'notification_text',
        'post_message',
        'post_link',
        'message_type',
        'is_read',
    ];
}
