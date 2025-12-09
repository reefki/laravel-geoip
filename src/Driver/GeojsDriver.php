<?php

namespace Reefki\Geoip\Driver;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class GeojsDriver extends Driver
{
    /**
     * Lookup the geoip information for the given IP address.
     *
     * @param  string  $ipAddress
     * @return array<string, mixed>|null
     */
    public function lookup(string $ipAddress): ?array
    {
        /** @var Response $response */
        $response = $this->request()->get(
            url: "/ip/geo/{$ipAddress}.json",
        );

        if (! $response->ok()) {
            return null;
        }

        /** @var array<string, mixed>|null */
        return $response->json();
    }

    /**
     * Create a new HTTP request for the GeoJS API.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function request(): PendingRequest
    {
        /** @var string $url */
        $url = $this->config['url'];

        return Http::baseUrl($url)
            ->timeout($this->timeout)
            ->accept('application/json')
            ->when(
                value: $this->retry,
                callback: fn (PendingRequest $request, mixed $retryCount): PendingRequest => $request->retry(
                    (int) $retryCount,
                    100,
                    fn (Exception $exception): bool => $exception instanceof ConnectionException,
                    false
                )
            );
    }
}
