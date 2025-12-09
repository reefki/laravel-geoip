<?php

namespace Reefki\Geoip;

use Illuminate\Cache\CacheManager;
use Illuminate\Support\Manager;
use Reefki\Geoip\Driver\Driver;
use Reefki\Geoip\Driver\GeojsDriver;
use Reefki\Geoip\Driver\IpDataDriver;

class GeoipManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        /** @var string */
        return $this->config->get('geoip.default', 'geojs');
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
        /** @var Driver $driver */
        $driver = $this->driver();

        return $driver->get($ipAddress, $cache);
    }

    /**
     * Create a new GeoJS driver instance.
     *
     * @return \Reefki\Geoip\Driver\Driver
     */
    public function createGeojsDriver(): Driver
    {
        return new GeojsDriver(...$this->getDriverParameters('geojs'));
    }

    /**
     * Create a new IPData driver instance.
     *
     * @return \Reefki\Geoip\Driver\Driver
     */
    public function createIpDataDriver(): Driver
    {
        return new IpDataDriver(...$this->getDriverParameters('ip-data'));
    }

    /**
     * Get the parameters for initializing a driver.
     *
     * @param  string  $name
     * @return array{cache: \Illuminate\Contracts\Cache\Repository, cacheTtl: int, timeout: int, retry: int, config: array<string, mixed>}
     */
    protected function getDriverParameters(string $name): array
    {
        /** @var CacheManager $cacheManager */
        $cacheManager = $this->container->make('cache');

        /** @var string|null $cacheStoreName */
        $cacheStoreName = config('geoip.cache_store');

        /** @var int $cacheTtl */
        $cacheTtl = $this->config->get('geoip.cache_ttl', 0);

        /** @var int $timeout */
        $timeout = $this->config->get('geoip.timeout', 10);

        /** @var int $retry */
        $retry = $this->config->get('geoip.retry', 3);

        /** @var array<string, mixed> $serviceConfig */
        $serviceConfig = $this->config->get("geoip.services.{$name}", []);

        /** @var \Illuminate\Cache\Repository $cacheRepository */
        $cacheRepository = $cacheManager->store($cacheStoreName);

        // Use tagged cache if supported, otherwise fall back to regular cache
        if (method_exists($cacheRepository->getStore(), 'tags')) {
            $cache = $cacheRepository->tags("geoip:{$name}");
        } else {
            $cache = $cacheRepository;
        }

        return [
            'cache' => $cache,
            'cacheTtl' => $cacheTtl,
            'timeout' => $timeout,
            'retry' => $retry,
            'config' => $serviceConfig,
        ];
    }
}
