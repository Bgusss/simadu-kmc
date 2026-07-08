<?php

return [

    'api_key' => env('OPENROUTER_API_KEY'),

    'base_url' => 'https://openrouter.ai/api/v1',

    'model' => env(
        'OPENROUTER_MODEL',
        'openai/gpt-oss-120b:free'
    ),

];
