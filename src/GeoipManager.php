<?php

namespace Reefki\Geoip;

use Illuminate\Support\Manager;
use Reefki\Geoip\Driver\Driver;
use Reefki\Geoip\Driver\GeojsDriver;

class GeoipManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver(): string
    {
        return 'geojs';
    }

    /**
     * Creates a new GeoJS driver.
     *
     * @return \Reefki\Geoip\Driver\Driver
     */
    public function createGeojsDriver(): Driver
    {
        return new GeojsDriver(
            /** @phpstan-ignore-next-line */
            cache: $this->container['cache']
                ->store(config('lemmer-analytics.cache_store'))
                ->tags('geoip:geojs'),
            cacheTtl: $this->config->get('geoip.cache_ttl', 0),
            config: $this->config->get('geoip.services.geojs'),
        );
    }
}
