<?php

namespace Reefki\Geoip\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Reefki\Geoip\Geoip;
use Reefki\Geoip\GeoipData;

class CacheTest extends TestCase
{
    #[Test]
    public function it_works_with_non_taggable_cache_store()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'get.geojs.io/*' => Http::response([
                'ip' => $ip,
                'city' => 'Mountain View',
                'country' => 'United States',
                'country_code' => 'US',
            ]),
        ]);

        // Use file cache which doesn't support tags
        config()->set('geoip.cache_store', 'file');
        config()->set('cache.default', 'file');

        Cache::flush();

        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
        $this->assertEquals('Mountain View', $geoip->city);
    }

    #[Test]
    public function it_works_with_array_cache_store()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'get.geojs.io/*' => Http::response([
                'ip' => $ip,
                'city' => 'Mountain View',
                'country' => 'United States',
                'country_code' => 'US',
            ]),
        ]);

        // Use array cache which doesn't support tags
        config()->set('geoip.cache_store', 'array');
        config()->set('cache.default', 'array');

        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
    }

    #[Test]
    public function it_caches_data_correctly_with_non_taggable_store()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'get.geojs.io/*' => Http::response([
                'ip' => $ip,
                'city' => 'Mountain View',
                'country' => 'United States',
                'country_code' => 'US',
            ]),
        ]);

        config()->set('geoip.cache_store', 'array');
        config()->set('cache.default', 'array');

        // First call - not cached
        $first = Geoip::get($ip);
        $this->assertFalse($first->cached);

        // Second call - should be cached
        $second = Geoip::get($ip);
        $this->assertTrue($second->cached);
    }
}
