<?php

namespace Reefki\Geoip\Tests;

use Reefki\Geoip\Geoip;
use Reefki\Geoip\GeoipData;

class GeoipTest extends TestCase
{
    /** @test */
    public function it_can_get_ipv4_information()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
    }

    /** @test */
    public function it_can_get_ipv6_information()
    {
        $ip = '2001:4860:4860::';
        $geoip = Geoip::get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($ip, $geoip->ip);
    }

    /** @test */
    public function it_can_get_realtime_ip_information()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::get($ip, false);

        $this->assertFalse($geoip->cached);
    }

    /** @test */
    public function it_can_get_ip_information_using_geojs_driver()
    {
        $ip = '8.8.8.8';
        $geoip = Geoip::driver('geojs')->get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
    }

    /** @test */
    public function it_can_get_ip_information_using_ip_data_driver()
    {
        config()->set('geoip.services.ip-data.key', '384bfdd9bdb9ef8eead5e8218396c1d058284b1bc6c09bf22af3d3d2');

        $ip = '8.8.8.8';
        $geoip = Geoip::driver('ip-data')->get($ip);

        $this->assertInstanceOf(GeoipData::class, $geoip);
    }
}
