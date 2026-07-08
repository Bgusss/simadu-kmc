<?php

namespace App\Services;

class WhatsAppService
{
    public function getMessages()
    {
        return [

            [
                'channel' => 'WhatsApp',
                'sender' => 'user_wa_1',
                'message' => 'Drainase tersumbat'
            ]

        ];
    }
}