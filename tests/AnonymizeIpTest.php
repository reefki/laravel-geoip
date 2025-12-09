<?php

namespace Reefki\Geoip\Tests;

use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;

class AnonymizeIpTest extends TestCase
{
    #[Test]
    public function it_can_anonymize_ipv4()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '8.8.8.8']);

        $this->assertEquals($request->anonymizedIp(), '8.8.8.0');
    }

    #[Test]
    public function it_can_anonymize_ipv6()
    {
        $request = Request::create('/');
        $request->server->add(['REMOTE_ADDR' => '2001:4860:4860:0:0:0:0:8888']);

        $this->assertEquals($request->anonymizedIp(), '2001:4860:4860::');
    }
}
