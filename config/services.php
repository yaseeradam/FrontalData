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

    'jannah_updates' => [
        'manifest_url' => env('JANNAH_UPDATE_MANIFEST_URL'),
        'ftp' => [
            'bookmark' => env('JANNAH_UPDATE_FTP_BOOKMARK', 'storage/update-packages/jannahvtu@updates.smedata.com.ng.duck'),
            'host' => env('JANNAH_UPDATE_FTP_HOST'),
            'username' => env('JANNAH_UPDATE_FTP_USERNAME'),
            'password' => env('JANNAH_UPDATE_FTP_PASSWORD'),
            'port' => env('JANNAH_UPDATE_FTP_PORT'),
            'remote_path' => env('JANNAH_UPDATE_FTP_REMOTE_PATH', '/'),
            'ssl' => env('JANNAH_UPDATE_FTP_SSL', false),
            'passive' => env('JANNAH_UPDATE_FTP_PASSIVE', true),
            'timeout' => env('JANNAH_UPDATE_FTP_TIMEOUT', 30),
        ],
    ],

];
