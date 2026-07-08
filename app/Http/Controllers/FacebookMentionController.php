<?php

namespace App\Http\Controllers;

use App\Models\FacebookMention;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class FacebookMentionController extends Controller
{
    public function sync()
    {
        $pageId = config('services.facebook.page_id');
        $token  = config('services.facebook.page_token');

        $response = Http::get(
            "https://graph.facebook.com/v25.0/me/tagged",
            [
                'access_token' => $token
            ]
        );

        $posts = $response->json('data', []);

        $saved = 0;

        foreach ($posts as $post) {

            $postId = $post['id'];

            // Ambil permalink postingan
            $detail = Http::get(
                "https://graph.facebook.com/v25.0/{$postId}",
                [
                    'fields' => 'permalink_url',
                    'access_token' => $token
                ]
            );

            $permalink = $detail->json('permalink_url');

            $mention = FacebookMention::updateOrCreate(
                [
                    'facebook_post_id' => $postId
                ],
                [
                    'message' => $post['message'] ?? null,
                    'permalink' => $permalink,
                    'facebook_created_at' => isset($post['tagged_time'])
                        ? Carbon::parse($post['tagged_time'])
                        : now(),
                ]
            );

            if ($mention->wasRecentlyCreated) {

                Notification::create([
                    'title' => 'Facebook Mention',
                    'message' => $post['message'] ?? 'Halaman ditandai'
                ]);

                $saved++;
            }
        }

        return response()->json([
            'success' => true,
            'total_from_facebook' => count($posts),
            'new_saved' => $saved
        ]);
    }
}
