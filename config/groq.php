<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Groq API Configuration
    |--------------------------------------------------------------------------
    | API key dari Groq Console: https://console.groq.com
    | Model utama: llama-3.3-70b-versatile
    | Limit gratis: 14.400 request/hari, respons < 1 detik
    */

    'api_key'  => env('GROQ_API_KEY', ''),
    'model'    => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
    'base_url' => 'https://api.groq.com/openai/v1/chat/completions',
    'timeout'  => 30,
];
