<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookCommentMention extends Model
{
    protected $fillable = [

        'notification_text',

        'comment_message',

        'comment_link',

        'comment_id',

        'is_read',

    ];
}
