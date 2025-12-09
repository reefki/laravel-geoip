<?php

namespace Reefki\Geoip\Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Reefki\Geoip\GeoipData;

class RequestTest extends TestCase
{
    #[Test]
    public function it_can_get_ip_address_information()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $geoip = $request->geoip();

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($geoip->ip, '8.8.8.8');
    }

    #[Test]
    public function it_can_get_anonymized_ip_address_information()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $geoip = $request->geoip(anonymize: true);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($geoip->ip, '8.8.8.0');
    }

    #[Test]
    public function it_returns_null_when_ip_is_not_available()
    {
        $request = Request::create('/');
        $request->server->remove('REMOTE_ADDR');

        $geoip = $request->geoip();

        $this->assertNull($geoip);
    }

    #[Test]
    public function it_can_get_anonymized_ip_address()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $anonymizedIp = $request->anonymizedIp();

        $this->assertEquals('8.8.8.0', $anonymizedIp);
    }

    #[Test]
    public function it_returns_null_anonymized_ip_when_ip_is_not_available()
    {
        $request = Request::create('/');
        $request->server->remove('REMOTE_ADDR');

        $anonymizedIp = $request->anonymizedIp();

        $this->assertNull($anonymizedIp);
    }

    #[Test]
    public function it_can_get_anonymized_ipv6_address()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '2001:4860:4860::8888']);

        $anonymizedIp = $request->anonymizedIp();

        $this->assertEquals('2001:4860:4860::', $anonymizedIp);
    }
}
