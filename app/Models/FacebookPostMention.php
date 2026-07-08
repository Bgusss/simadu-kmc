<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookPostMention extends Model
{
    protected $fillable = [

        'post_link',

        'notification_text',

        'post_message',

        'sender',

        'is_read'

    ];
}