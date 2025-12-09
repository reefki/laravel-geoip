# Laravel GeoIP

[![tests](https://github.com/reefki/laravel-geoip/actions/workflows/tests.yml/badge.svg)](https://github.com/reefki/laravel-geoip/actions/workflows/tests.yml)

A Laravel package to get geographical location information from IP addresses.

## Requirements

- PHP 8.1 or higher
- Laravel 9, 10, 11, or 12

## Installation

Install the package via Composer:

```bash
composer require reefki/laravel-geoip
```

Optionally, publish the config file:

```bash
php artisan vendor:publish --provider="Reefki\Geoip\GeoipServiceProvider" --tag="config"
```

## Usage

### Using the Facade

```php
use Reefki\Geoip\Geoip;

$geoip = Geoip::get('8.8.8.8');

$geoip->driver;         // geojs
$geoip->ip;             // 8.8.8.8
$geoip->city;           // Mountain View
$geoip->region;         // California
$geoip->country;        // United States
$geoip->country_code;   // US
$geoip->continent_code; // NA
$geoip->timezone;       // America/Los_Angeles
$geoip->latitude;       // 37.751
$geoip->longitude;      // -97.822
$geoip->cached;         // true or false
```

IPv6 addresses are also supported:

```php
$geoip = Geoip::get('2001:4860:4860::8888');
```

### Disabling Cache

By default, results are cached. To get realtime data:

```php
Geoip::get('8.8.8.8', cache: false);
```

### Using with Request

Get GeoIP data for the current request's IP address:

```php
$geoip = $request->geoip();
```

With anonymized IP (for GDPR compliance):

```php
$geoip = $request->geoip(anonymize: true);
```

Get just the anonymized IP address:

```php
$anonymizedIp = $request->anonymizedIp();
// 8.8.8.8 → 8.8.8.0
// 2001:4860:4860::8888 → 2001:4860:4860::
```

## Configuration

### Default Driver

Set the default driver in your `.env` file:

```
GEOIP_DEFAULT_DRIVER=geojs
```

### Cache Settings

Configure caching behavior:

```
GEOIP_CACHE_STORE=redis
GEOIP_CACHE_TTL=86400
```

### HTTP Settings

Configure timeout and retry for API requests:

```
GEOIP_TIMEOUT=10
GEOIP_RETRY=3
```

## Drivers

### GeoJS (Default)

[GeoJS](https://www.geojs.io) is a free service with no API key required.

```php
Geoip::driver('geojs')->get('8.8.8.8');
```

### IPData

[IPData](https://ipdata.co) requires an API key. [Register](https://dashboard.ipdata.co/sign-up.html) for an account and add your key to `.env`:

```
IPDATA_API_KEY=your_api_key
```

Then use the driver:

```php
Geoip::driver('ip-data')->get('8.8.8.8');
```

> **Note:** IPData offers 1,500 free requests per day. Higher usage requires a paid plan.

## Testing

```bash
vendor/bin/phpunit
```

## Credits

- [Rifki Aria Gumelar](https://github.com/reefki)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

