<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $notifications = DB::table('notifications')
            ->whereNull('sender')
            ->orWhere('sender', 'Facebook')
            ->get();

        foreach ($notifications as $notif) {
            $sender = null;

            if ($notif->title === 'Facebook Mention') {
                $post = DB::table('facebook_post_mentions')
                    ->where('post_link', $notif->permalink)
                    ->latest()
                    ->first();

                if ($post && !empty($post->sender)) {
                    $sender = $post->sender;
                }
            } elseif ($notif->title === 'Facebook Comment Mention') {
                $comment = DB::table('facebook_comment_mentions')
                    ->where('comment_link', $notif->permalink)
                    ->latest()
                    ->first();

                if ($comment && !empty($comment->notification_text)) {
                    $parsed = explode(' mentioned', $comment->notification_text)[0];
                    if (!empty($parsed) && $parsed !== 'Facebook') {
                        $sender = $parsed;
                    }
                }
            }

            if ($sender && $sender !== 'Facebook') {
                DB::table('notifications')
                    ->where('id', $notif->id)
                    ->update(['sender' => $sender]);
            }
        }
    }

    public function down(): void
    {
        // No rollback action needed for data backfill.
    }
};
