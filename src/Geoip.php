<?php

namespace Reefki\Geoip;

use Illuminate\Support\Facades\Facade;

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
