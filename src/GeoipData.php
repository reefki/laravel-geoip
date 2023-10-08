<?php

namespace Reefki\Geoip;

use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use EventSauce\ObjectHydrator\PropertyCasters\CastToType;

class GeoipData
{
    /**
     * Constructor for the GeoipData class.
     *
     * @param  string  $ip
     * @param  string|null  $city
     * @param  string|null  $region
     * @param  string|null  $country
     * @param  string|null  $country_code
     * @param  string|null  $continent_code
     * @param  string|null  $timezone
     * @param  float|null  $latitude
     * @param  float|null  $longitude
     */
    public function __construct(
        public readonly string $ip,
        public readonly ?string $city = null,
        public readonly ?string $region = null,
        public readonly ?string $country = null,
        public readonly ?string $country_code = null,
        public readonly ?string $continent_code = null,
        public readonly ?string $timezone = null,
        #[CastToType('float')]
        public readonly ?float $latitude = null,
        #[CastToType('float')]
        public readonly ?float $longitude = null,
        public readonly bool $cached = true,
    ) {
    }

    /**
     * Create a new GeoipData instance.
     *
     * @return GeoipData A new instance of GeoipData.
     */
    public static function make()
    {
        $mapper = new ObjectMapperUsingReflection();

        return $mapper->hydrateObject(static::class, ...func_get_args());
    }
}
