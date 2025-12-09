<?php

namespace Reefki\Geoip\Driver;

use Illuminate\Contracts\Cache\Repository;
use Reefki\Geoip\GeoipData;

abstract class Driver
{
    /**
     * The cache repository instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected Repository $cache;

    /**
     * The cache TTL in seconds.
     *
     * @var int
     */
    protected int $cacheTtl;

    /**
     * The HTTP request timeout in seconds.
     *
     * @var int
     */
    protected int $timeout;

    /**
     * The number of times to retry failed requests.
     *
     * @var int
     */
    protected int $retry;

    /**
     * The driver configuration.
     *
     * @var array<string, mixed>
     */
    protected array $config;

    /**
     * Create a new driver instance.
     *
     * @param  \Illuminate\Contracts\Cache\Repository  $cache
     * @param  int  $cacheTtl
     * @param  int  $timeout
     * @param  int  $retry
     * @param  array<string, mixed>  $config
     */
    public function __construct(
        Repository $cache,
        int $cacheTtl,
        int $timeout,
        int $retry,
        array $config = [],
    ) {
        $this->cache = $cache;
        $this->cacheTtl = $cacheTtl;
        $this->timeout = $timeout;
        $this->retry = $retry;
        $this->config = $config;
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
        $driver = $this->getDriverName();

        if (! $cache) {
            $data = $this->lookup($ipAddress);
            $cached = false;
        } else {
            $cacheKey = implode(':', ['geoip', static::class, $ipAddress]);
            $cached = $this->cache->has($cacheKey);

            /** @var array<string, mixed>|null $data */
            $data = $this->cache->remember(
                key: $cacheKey,
                ttl: $this->cacheTtl,
                callback: fn () => $this->lookup($ipAddress)
            );
        }

        $defaults = [
            'ip' => $ipAddress,
            'driver' => $driver,
            'cached' => $cached,
        ];

        return GeoipData::make(
            $data ? [...$defaults, ...$data] : $defaults
        );
    }

    /**
     * Lookup the geoip information for the given IP address.
     *
     * @param  string  $ipAddress
     * @return array<string, mixed>|null
     */
    abstract public function lookup(string $ipAddress): ?array;

    /**
     * Get the driver name.
     *
     * @return string
     */
    protected function getDriverName(): string
    {
        return str(class_basename(static::class))
            ->snake()
            ->replaceLast('_driver', '')
            ->slug('-');
    }
}
