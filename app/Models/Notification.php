<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AIClassification;
use App\Models\Ticket;
use App\Models\FacebookPostMention;
use App\Models\FacebookCommentMention;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'sender',
        'message',
        'permalink',
        'comment_message',
        'is_read',
        'duplicate_of_id',
        'duplicate_similarity',
        'duplicate_status',
    ];

    /**
     * Notifikasi asli yang mirip dengan notifikasi ini.
     */
    public function duplicateOf()
    {
        return $this->belongsTo(Notification::class, 'duplicate_of_id');
    }

    /**
     * Notifikasi-notifikasi lain yang terdeteksi mirip dengan notifikasi ini.
     */
    public function duplicates()
    {
        return $this->hasMany(Notification::class, 'duplicate_of_id');
    }

    public function getDisplayMessageAttribute()
    {
        $message = $this->comment_message ?: $this->message ?: '';

        return trim(preg_replace('/@?simadu\s*kmc\s*/i', '', $message));
    }

    public function getSenderNameAttribute()
    {
        if (!empty($this->sender) && $this->sender !== 'Facebook') {
            return $this->sender;
        }

        if ($this->title === 'Facebook Mention') {
            $postMention = FacebookPostMention::where('post_link', $this->permalink)
                ->latest()
                ->first();
            if ($postMention && !empty($postMention->sender)) {
                return $postMention->sender;
            }
        } elseif ($this->title === 'Facebook Comment Mention') {
            $commentMention = FacebookCommentMention::where('comment_link', $this->permalink)
                ->latest()
                ->first();
            if ($commentMention && !empty($commentMention->notification_text)) {
                $parsed = explode(' mentioned', $commentMention->notification_text)[0];
                if (!empty($parsed) && $parsed !== 'Facebook') {
                    return $parsed;
                }
            }
        } elseif ($this->title === 'Instagram DM') {
            $igMention = \App\Models\InstagramMention::where('post_link', $this->permalink)
                ->latest()
                ->first();
            if ($igMention && !empty($igMention->sender)) {
                return $igMention->sender;
            }
        }

        return $this->sender ?? 'Facebook';
    }

    public function ai()
    {
        return $this->hasOne(
            AIClassification::class,
            'notification_id'
        );
    }

    public function aiClassification()
    {
        return $this->hasOne(
            AIClassification::class
        );
    }

    public function ticket()
    {
        return $this->hasOne(Ticket::class);
    }
}
