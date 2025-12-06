<?php

return [
    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],
    'ses'      => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'resend'   => [
        'key' => env('RESEND_KEY'),
    ],
    'slack'    => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel'              => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],
    'amazon'   => [
        'best_sellers' => [
            'US' => ['https://www.amazon.com/Best-Sellers/zgbs'],
            'UK' => ['https://www.amazon.co.uk/Best-Sellers/zgbs'],
            'DE' => ['https://www.amazon.de/gp/bestsellers'],
            'FR' => ['https://www.amazon.fr/gp/bestsellers'],
            'IT' => ['https://www.amazon.it/gp/bestsellers'],
            'ES' => ['https://www.amazon.es/gp/bestsellers'],
        ],
    ],
    'ali1688'  => [
        'endpoint' => env('ALI1688_API_ENDPOINT', 'http://localhost:9000/ali1688/search'),
    ],
];