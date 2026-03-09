<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
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

    /*
    |--------------------------------------------------------------------------
    | Social Login Services
    |--------------------------------------------------------------------------
    */

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT_URI'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Services
    |--------------------------------------------------------------------------
    */

    'nokash' => [
        'base_url' => env('NOKASH_BASE_URL', 'https://api.nokash.app/lapas-on-trans/trans'),
        'i_space_key' => env('NOKASH_I_SPACE_KEY'),
        'app_space_key' => env('NOKASH_APP_SPACE_KEY'),
        'widget_key' => env('NOKASH_WIDGET_KEY'),
    ],

    'paymooney' => [
        'url' => env('PAYMOONEY_URL', 'https://www.paymooney.com/api/v1.0/payment_url'),
        'public_key' => env('PAYMOONEY_PUBLIC_KEY'),
        'secret_key' => env('PAYMOONEY_SECRET_KEY'),
        'environment' => env('PAYMOONEY_ENVIRONMENT', 'live'),
    ],

];
