<?php

namespace Reefki\Geoip\Tests;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use Reefki\Geoip\Geoip;
use Reefki\Geoip\GeoipData;

class GeoipTest extends TestCase
{
    #[Test]
    public function it_can_get_ipv4_information()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
    }

    #[Test]
    public function it_returns_cached_true_on_cache_hit()
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

        Cache::flush();

        // First call - should not be cached
        $first = Geoip::get($ip);
        $this->assertFalse($first->cached);

        // Second call - should be cached
        $second = Geoip::get($ip);
        $this->assertTrue($second->cached);
    }

    #[Test]
    public function it_can_get_ipv6_information()
    {
        $ip = '2001:4860:4860::';
        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
    }

    #[Test]
    public function it_can_get_realtime_ip_information()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::get($ip, false);

        $this->assertFalse($geoip->cached);
    }

    #[Test]
    public function it_can_get_ip_information_using_geojs_driver()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::driver('geojs')->get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
    }

    #[Test]
    public function it_can_get_ip_information_using_ip_data_driver()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'api.ipdata.co/*' => Http::response([
                'ip' => $ip,
                'city' => 'Mountain View',
                'region' => 'California',
                'country_name' => 'United States',
                'country_code' => 'US',
                'continent_code' => 'NA',
                'time_zone' => ['name' => 'America/Los_Angeles'],
                'latitude' => 37.4056,
                'longitude' => -122.0775,
            ]),
        ]);

        config()->set('geoip.services.ip-data.key', 'test-api-key');

        $geoip = Geoip::driver('ip-data')->get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
        $this->assertEquals('Mountain View', $geoip->city);
        $this->assertEquals('US', $geoip->country_code);
    }

    #[Test]
    public function it_returns_minimal_data_on_api_failure()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'get.geojs.io/*' => Http::response(null, 500),
        ]);

        Cache::flush();

        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
        $this->assertEquals('geojs', $geoip->driver);
        $this->assertNull($geoip->city);
        $this->assertNull($geoip->country);
    }

    #[Test]
    public function it_returns_minimal_data_on_ip_data_api_failure()
    {
        $ip = '8.8.8.8';

        Http::fake([
            'api.ipdata.co/*' => Http::response(['message' => 'Invalid API key'], 401),
        ]);

        config()->set('geoip.services.ip-data.key', 'invalid-key');

        Cache::flush();

        $geoip = Geoip::driver('ip-data')->get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
        $this->assertEquals('ip-data', $geoip->driver);
        $this->assertNull($geoip->city);
    }
}
