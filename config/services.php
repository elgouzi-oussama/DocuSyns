<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'tesseract' => [
        'path' => env('TESSERACT_PATH'),
        'langs' => env('TESSERACT_LANGS')
    ],
    'google_cloud' => [
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'location' => env('DOCUMENT_AI_LOCATION', 'us'),
        'processor_id' => env('DOCUMENT_AI_PROCESSOR_ID'),
        'key_file' => storage_path('app/google/docusync-ai-474415-5746f7c6f051.json'),

    ],
    'google' => [
        'credentials_path' => storage_path('app/google/docusync-ai-474415-5746f7c6f051.json'),
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'location' => env('DOCUMENT_AI_LOCATION', 'us'),
        'processor_id' => env('DOCUMENT_AI_PROCESSOR_ID'),
    ],


];
