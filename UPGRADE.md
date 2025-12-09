# Upgrade Guide

## Upgrading from 1.x to 2.0

### Breaking Changes

#### 1. Driver Constructor Signature

If you have created custom drivers extending the base `Driver` class, you need to update the constructor to accept the new `timeout` and `retry` parameters.

**Before:**

```php
public function __construct(
    Repository $cache,
    int $cacheTtl,
    array $config = [],
) {
    // ...
}
```

**After:**

```php
public function __construct(
    Repository $cache,
    int $cacheTtl,
    int $timeout,
    int $retry,
    array $config = [],
) {
    // ...
}
```

#### 2. Cache Type Changed

The cache property type changed from `TaggedCache` to `Repository`. This allows the package to work with cache stores that don't support tagging (file, database, array).

If you have custom drivers that type-hint `TaggedCache`, update them to use `Repository`:

```php
use Illuminate\Contracts\Cache\Repository;

protected Repository $cache;
```

#### 3. Configuration Changes

The `retry` option has been moved from per-driver configuration to the root level, and `timeout` has been added.

**Before (config/geoip.php):**

```php
'services' => [
    'geojs' => [
        'url' => '...',
        'retry' => 3,
    ],
],
```

**After (config/geoip.php):**

```php
'timeout' => env('GEOIP_TIMEOUT', 10),
'retry' => env('GEOIP_RETRY', 3),
'services' => [
    'geojs' => [
        'url' => '...',
    ],
],
```

If you have published the config file, update it accordingly or republish:

```bash
php artisan vendor:publish --provider="Reefki\Geoip\GeoipServiceProvider" --tag="config" --force
```

#### 4. Request `geoip()` Return Value

The `$request->geoip()` method now returns `null` when the request IP address is not available, instead of making an API call with an empty IP.

**Before:**

```php
$geoip = $request->geoip(); // Always returned GeoipData
```

**After:**

```php
$geoip = $request->geoip(); // Returns GeoipData|null

// Update your code to handle null
if ($geoip = $request->geoip()) {
    // Use $geoip
}
```

### New Features

#### HTTP Timeout Configuration

You can now configure the HTTP request timeout:

```
GEOIP_TIMEOUT=10
```

#### Retry Configuration

Configure the number of retry attempts for failed requests:

```
GEOIP_RETRY=3
```

#### Non-Taggable Cache Support

The package now works with all cache stores, including those that don't support tagging (file, database, array). No configuration changes needed.
