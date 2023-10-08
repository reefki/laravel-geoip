<?php

namespace Reefki\Geoip\Driver;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class IpDataDriver extends Driver
{
    /**
     * @inheritdoc
     */
    public function lookup(string $ipAddress): ?array
    {
        $response = $this->request()->get(
            url: $ipAddress,
        );

        if (!$response->ok()) {
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
     * Create aa new HTTP request for GeoJS API.
     *
     * @return PendingRequest The configured HTTP request.
     */
    protected function request(): PendingRequest
    {
        return Http::baseUrl($this->config['url'])
            ->withQueryParameters([
                'api-key' => $this->config['key'],
            ])
            ->accept('application/json')
            ->when(
                value: $this->config['retry'],
                callback: fn (PendingRequest $request, int $retry) => $request->retry($retry, 100, fn (Exception $exception) => $exception instanceof ConnectionException, false)
            );
    }
}
