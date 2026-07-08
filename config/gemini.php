<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Gemini API Configuration
    |--------------------------------------------------------------------------
    | API key dari Google AI Studio: https://aistudio.google.com/app/apikey
    | Model utama: gemini-2.5-flash (gratis, cepat, akurat)
    */

    'api_key' => env('GEMINI_API_KEY', ''),
    'model'   => env('GEMINI_MODEL', 'gemini-2.5-flash'),
    'base_url'=> 'https://generativelanguage.googleapis.com/v1beta/models',
    'timeout' => 30,
];
