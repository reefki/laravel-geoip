<?php

namespace Reefki\Geoip\Driver;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class IpDataDriver extends Driver
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
            url: $ipAddress,
        );

        if (! $response->ok()) {
            return null;
        }

        $data = $response->json();

        return [
            'ip' => data_get($data, 'ip'),
            'city' => data_get($data, 'city'),
            'region' => data_get($data, 'region'),
            'country' => data_get($data, 'country_name'),
            'country_code' => data_get($data, 'country_code'),
            'continent_code' => data_get($data, 'continent_code'),
            'timezone' => data_get($data, 'time_zone.name'),
            'latitude' => data_get($data, 'latitude'),
            'longitude' => data_get($data, 'longitude'),
        ];
    }

    /**
     * Create a new HTTP request for the IPData API.
     *
     * @return \Illuminate\Http\Client\PendingRequest
     */
    protected function request(): PendingRequest
    {
        /** @var string $url */
        $url = $this->config['url'];

        /** @var string $key */
        $key = $this->config['key'];

        return Http::baseUrl($url)
            ->timeout($this->timeout)
            ->withQueryParameters([
                'api-key' => $key,
            ])
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
