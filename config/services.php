<?php

return [

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'max_tokens' => env('GEMINI_MAX_TOKENS', 1024),
        'temperature' => env('GEMINI_TEMPERATURE', 0.7),
        'timeout' => env('GEMINI_TIMEOUT', 30),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.3-70b-versatile'),
        'max_tokens' => env('GROQ_MAX_TOKENS', 1024),
        'temperature' => env('GROQ_TEMPERATURE', 0.7),
        'timeout' => env('GROQ_TIMEOUT', 30),
    ],

];