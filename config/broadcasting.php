<?php
return [
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER', 'ap1'),
        'useTLS' => true, // Wajib true untuk keamanan modern
        'host' => 'api-'.env('PUSHER_APP_CLUSTER', 'ap1').'.pusher.com',
        'port' => 443,
        'scheme' => 'https'
    ],
]
];