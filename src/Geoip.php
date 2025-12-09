<?php

namespace Reefki\Geoip;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Reefki\Geoip\GeoipData get(string $ipAddress, bool $cache = true)
 * @method static \Reefki\Geoip\Driver\Driver driver(string|null $driver = null)
 *
 * @see \Reefki\Geoip\GeoipManager
 */
class Geoip extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return GeoipManager::class;
    }
}
