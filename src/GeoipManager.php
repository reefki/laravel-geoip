<?php

namespace Reefki\Geoip;

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
        return $this->config->get('geoip.default', 'geojs');
    }

    /**
     * Creates a new GeoJS driver.
     *
     * @return \Reefki\Geoip\Driver\Driver
     */
    public function createGeojsDriver(): Driver
    {
        return new GeojsDriver(...$this->getDriverParameters('geojs'));
    }

    /**
     * Creates a new GeoJS driver.
     *
     * @return \Reefki\Geoip\Driver\Driver
     */
    public function createIpDataDriver(): Driver
    {
        return new IpDataDriver(...$this->getDriverParameters('ip-data'));
    }

    /**
     * Get parameters for driver.
     *
     * @return array<string, mixed>
     */
    protected function getDriverParameters(string $name): array
    {
        return [
            /** @phpstan-ignore-next-line */
            'cache' => $this->container['cache']->store(config('lemmer-analytics.cache_store'))->tags("geoip:{$name}"),
            'cacheTtl' => $this->config->get('geoip.cache_ttl', 0),
            'config' => $this->config->get("geoip.services.{$name}"),
        ];
    }
}
