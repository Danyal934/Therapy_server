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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'app_store' => [
        'issuer_id' => env('APPLE_ISSUER_ID'),
        'key_id' => env('APPLE_KEY_ID'),
        'bundle_id' => env('APPLE_BUNDLE_ID'),
        'private_key' => env('APPLE_PRIVATE_KEY'),
        'private_key_path' => env('APPLE_PRIVATE_KEY_PATH'),
    ],

    'stripe' => [
        'key' => env('STRIPE_PUBLISHABLE_KEY'),
        'secret' => env('STRIPE_SECRET_KEY'),
    ],

    'mail' => [
        'admin_email' => env('MAIL_ADMIN_EMAIL'),
    ],

];
