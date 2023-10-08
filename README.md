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

## Usage
Using facade to get information of an IP address:

```php
use Reefki\Geoip\Geoip;

Geoip::get('8.8.8.8');
Geoip::get('2001:4860:4860:0:0:0:0:8888');
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

