<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Gemini API Configuration
    |--------------------------------------------------------------------------
    | API key dari Google AI Studio: https://aistudio.google.com/app/apikey
    | Model utama: gemma-4-31b-it (gratis)
    */

    'api_key' => env('GEMINI_API_KEY', ''),
    'model'   => env('GEMINI_MODEL', 'gemma-4-31b-it'),
    'base_url'=> 'https://generativelanguage.googleapis.com/v1beta/models',
    'timeout' => 30,
];
