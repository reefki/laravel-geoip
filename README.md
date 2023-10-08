Laravel GeoIP
===

[![tests](https://github.com/reefki/laravel-geoip/actions/workflows/tests.yml/badge.svg)](https://github.com/reefki/laravel-geoip/actions/workflows/tests.yml)

A Laravel package to get IP addresses geographical location.

## Installation

This package can be installed through Composer.

```bash
composer require reefki/laravel-geoip
```

Optionally, you can publish the config file of this package with this command:

```bash
php artisan vendor:publish --provider="Reefki\Geoip\GeoipServiceProvider" --tag="config"
```

## Basic usage
Using facade to get information of an IP address:

```php
use Reefki\Geoip\Geoip;

$geoip = Geoip::get('8.8.8.8');
// Or
$geoip = Geoip::get('2001:4860:4860:0:0:0:0:8888');

$geoip->driver; // geojs.
$geoip->ip; // 8.8.8.8
$geoip->city; // Mountain View
$geoip->region; // California
$geoip->country; // United States
$geoip->country_code; // US
$geoip->continent_code; // NA
$geoip->timezone; // America/Chicago
$geoip->latitude; // 37.751
$geoip->longitude; // -97.822
$geoip->cached; // true or false
```

By default the result will be cached, to get realtime data you can pass a boolean value as the second parameter:

```php
use Reefki\Geoip\Geoip;

Geoip::get('8.8.8.8', false);
```

Get visitor's IP address via request:

```php
$geoip = $request->geoip(anonymize: true, cache: true);
```

Get visitor's anonymized IP address:

```php
// 8.8.8.8 = 8.8.8.0
// 2001:4860:4860:0:0:0:0:8888 = 2001:4860:4860::
$anonymizedIp = $request->anonymizedIp();
```

## Drivers

Laravel GeoIP support multiple drivers. By default, `geojs` driver will be used if you don't specify the driver when using the facade or when calling `geoip()` method via Laravel request instance.

You can change the default driver in your `.env` file as:

```
GEOIP_DEFAULT_DRIVER=geojs
```

Here are the drivers currently available for use.

### [GeoJS](https://www.geojs.io)

This is a completly free service, you don't have to do anything to get it to work. Simply pass `geojs` to the driver paremeter.

```php
use Reefki\Geoip\Geoip;

Geoip::driver('geojs')->get('8.8.8.8');
```

### [IPData](https://ipdata.co)

To use this driver, [register](https://dashboard.ipdata.co/sign-up.html) an account and put the API key in your `.env` file as:

```
IPDATA_API_KEY=YOUR_API_KEY
```

To get the IP information using this driver you can pass `ip-data` to the driver parameter:

```php
use Reefki\Geoip\Geoip;

Geoip::driver('ip-data')->get('8.8.8.8');
```

> Please note: IPData offers 1500 daily free requests. If your daily usage is more than 1500 you need to upgrade to one of their paid plan.

## Testing

Run the tests with:

``` bash
vendor/bin/phpunit
```

## Credits

- [Rifki Aria Gumelar](https://github.com/reefki)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

