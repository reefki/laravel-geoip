<?php

namespace Reefki\Geoip\Tests;

use Illuminate\Http\Request;
use Reefki\Geoip\GeoipData;

class RequestTest extends TestCase
{
    /** @test */
    public function it_can_get_ip_address_information()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $geoip = $request->geoip();

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($geoip->ip, '8.8.8.8');
    }

    /** @test */
    public function it_can_get_anonymized_ip_address_information()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $geoip = $request->geoip(anonymize: true);

        $this->assertInstanceOf(GeoipData::class, $geoip);
        $this->assertEquals($geoip->ip, '8.8.8.0');
    }
}
