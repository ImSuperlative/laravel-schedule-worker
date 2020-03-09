<?php

return [
    'enabled' => env('SCHEDULE_WORKER_ENABLED', false),

    'log' => [
        'enabled' => env('SCHEDULE_WORKER_LOG_ENABLED', false),
        'channel' => env('SCHEDULE_WORKER_LOG_CHANNEL', 'cron'),
    ],

    'default' => [
        'connection'      => env('SCHEDULE_WORKER_CONNECTION', 'sync'),
        'numproc'         => 1,
        'stop-when-empty' => false,
        'delay'           => 600,
        'memory'          => 128,
        'sleep'           => 3,
        'timeout'         => 60,
        'tries'           => 3,
        'force'           => false,
        'once'            => false,
    ],

    'queues' => [
        /*
        'default'  => [
            'queue'   => 'default',
            'numproc' => 4,
        ],
        'sitemap' => [
            'connection' => 'database',
            'queue' => 'newsletter',
            'timeout' => 120,
        ],
        */
    ],
];
