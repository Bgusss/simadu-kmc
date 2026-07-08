<?php

namespace App\Services;

class InstagramService
{
    public function getMessages()
    {
        return [

            [
                'channel' => 'Instagram',
                'sender' => 'user_ig_1',
                'message' => 'Lampu jalan mati'
            ],

            [
                'channel' => 'Instagram',
                'sender' => 'user_ig_2',
                'message' => 'Jalan berlubang'
            ]

        ];
    }
}