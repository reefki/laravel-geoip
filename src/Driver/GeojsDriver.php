<?php

namespace Reefki\Geoip\Driver;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class GeojsDriver extends Driver
{
    /**
     * @inheritdoc
     */
    public function lookup(string $ipAddress): ?array
    {
        $response = $this->request()->get(
            url: "/ip/geo/{$ipAddress}.json",
        );

        if (!$response->ok()) {
            return null;
        }

        return (array) $response->json();
    }

    /**
     * Create aa new HTTP request for GeoJS API.
     *
     * @return PendingRequest The configured HTTP request.
     */
    protected function request(): PendingRequest
    {
        return Http::baseUrl($this->config['url'])
            ->accept('application/json')
            ->when(
                value: $this->config['retry'],
                callback: fn (PendingRequest $request, int $retry) => $request->retry($retry, 100, fn (Exception $exception) => $exception instanceof ConnectionException, false)
            );
    }
}
