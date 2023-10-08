<?php

return [
    'cache_store' => env('GEOIP_CACHE_STORE'),
    'cache_ttl' => env('GEOIP_CACHE_TTL', 60 * 60 * 24),
    'services' => [
        'geojs' => [
            'url' => env('GEOJS_API_URL', 'https://get.geojs.io/v1'),
            'retry' => 3,
        ],
        'ip-data' => [
            'url' => env('IPDATA_API_URL', 'https://api.ipdata.co'),
            'key' => env('IPDATA_API_KEY'),
            'retry' => 3,
        ],
    ],
];
