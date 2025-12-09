<?php

namespace Reefki\Geoip;

use EventSauce\ObjectHydrator\ObjectMapperUsingReflection;
use EventSauce\ObjectHydrator\PropertyCasters\CastToType;

class GeoipData
{
    /**
     * Create a new GeoipData instance.
     *
     * @param  string  $ip
     * @param  string|null  $driver
     * @param  string|null  $city
     * @param  string|null  $region
     * @param  string|null  $country
     * @param  string|null  $country_code
     * @param  string|null  $continent_code
     * @param  string|null  $timezone
     * @param  float|null  $latitude
     * @param  float|null  $longitude
     * @param  bool  $cached
     */
    public function __construct(
        public readonly string $ip,
        public readonly ?string $driver = null,
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
    ) {}

    /**
     * Create a new GeoipData instance from an array payload.
     *
     * @param  array<string, mixed>  $payload
     * @return \Reefki\Geoip\GeoipData
     */
    public static function make(array $payload): GeoipData
    {
        $mapper = new ObjectMapperUsingReflection;

        return $mapper->hydrateObject(static::class, $payload);
    }
}
