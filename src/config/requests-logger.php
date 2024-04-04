<?php

use Dotsplatform\RequestsLogger\DTO\RequestLoggerChannel;

return [
    'enabled' => env('REQUESTS_LOGGER_ENABLED', false),

    'default' => env('REQUESTS_LOGGER_CHANNEL', RequestLoggerChannel::NULL),

    'channels' => [
        'file' => [
            'path' => env('REQUESTS_LOGGER_PATH', storage_path('logs/requests.log')),
        ],

        'opensearch' => [
            'hosts' => explode(',', env('OPENSEARCH_HOSTS', '')),
            'username' => env('OPENSEARCH_USERNAME', ''),
            'password' => env('OPENSEARCH_PASSWORD', ''),
            'indexes' => [
                'provider_requests' => env('OPENSEARCH_INDEX_PROVIDER_REQUESTS', 'provider_requests.locations.local'),
            ],
        ],
    ],

];
