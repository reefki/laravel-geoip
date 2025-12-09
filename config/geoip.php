<?php

return [
    'default' => env('GEOIP_DEFAULT_DRIVER', 'geojs'),
    'cache_store' => env('GEOIP_CACHE_STORE'),
    'cache_ttl' => env('GEOIP_CACHE_TTL', 60 * 60 * 24),
    'timeout' => env('GEOIP_TIMEOUT', 10),
    'retry' => env('GEOIP_RETRY', 3),
    'services' => [
        'geojs' => [
            'url' => env('GEOJS_API_URL', 'https://get.geojs.io/v1'),
        ],
        'ip-data' => [
            'url' => env('IPDATA_API_URL', 'https://api.ipdata.co'),
            'key' => env('IPDATA_API_KEY'),
        ],
    ],
];
