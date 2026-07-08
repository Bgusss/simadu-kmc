<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacebookMention extends Model
{
    protected $fillable = [
        'facebook_post_id',
        'message',
        'permalink',
        'facebook_created_at',
    ];
}
