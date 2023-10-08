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
}
