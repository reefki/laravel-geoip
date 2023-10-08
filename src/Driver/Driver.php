<?php

namespace Reefki\Geoip\Driver;

use Illuminate\Cache\Repository;
use Reefki\Geoip\GeoipData;

abstract class Driver
{
    /**
     * Create a new driver instance.
     *
     * @param  \Illuminate\Cache\Repository  $cache
     * @param  int  $config
     * @param  array<mixed>  $config
     */
    public function __construct(
        protected Repository $cache,
        protected int $cacheTtl,
        protected array $config = [],
    ) {
    }

    /**
     * Get the geoip information for the given IP address.
     *
     * @param  string  $ipAddress
     * @param  bool  $cache
     * @return \Reefki\Geoip\GeoipData
     */
    public function get(string $ipAddress, bool $cache = true): GeoipData
    {
        if (!$cache) {
            $data = $this->lookup($ipAddress);
            $cached = false;
        } else {
            $data = $this->cache->remember(
                key: implode(':', ['geoip', static::class, $ipAddress]),
                ttl: $this->cacheTtl,
                callback: fn () => $this->lookup($ipAddress)
            );
            $cached = !is_null($data);
        }

        return GeoipData::make(
            $data ? [...$data, ...['cached' => $cached]] : ['ip' => $ipAddress, 'cached' => false]
        );
    }

    /**
     * Lookup the geoip information for the given IP address.
     *
     * @param  string  $ipAddress
     * @return array<string, mixed>|null
     */
    abstract public function lookup(string $ipAddress): ?array;
}
