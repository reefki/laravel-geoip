<?php

return [
    'cache_store' => env('GEOIP_CACHE_STORE'),
    'cache_ttl' => env('GEOIP_CACHE_TTL', 60 * 60 * 24),
    'services' => [
        'geojs' => [
            'url' => env('GEOJS_API_URL', 'https://get.geojs.io/v1'),
            'retry' => 3,
        ],
    ],
];
