# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2025-12-10

### Added

- Laravel 11 and 12 support
- PHP 8.2 and 8.3 support
- Configurable HTTP timeout via `GEOIP_TIMEOUT` environment variable
- Configurable retry count via `GEOIP_RETRY` environment variable
- Support for non-taggable cache stores (file, database, array)
- Comprehensive test coverage for cache, API failures, and edge cases

### Changed

- **Breaking:** Driver constructor signature now requires `timeout` and `retry` parameters
- **Breaking:** Driver cache type changed from `TaggedCache` to `Repository` interface
- **Breaking:** `timeout` and `retry` configuration moved from per-driver to root level
- **Breaking:** `$request->geoip()` now returns `null` when request IP is not available
- Upgraded to PHPStan level 9
- Switched to PHP 8 `#[Test]` attributes in test files
- Updated PHPUnit configuration for version 10/11 compatibility

### Fixed

- Cache tagging now gracefully falls back to regular cache for non-taggable stores
- `cached` flag now correctly indicates whether data was retrieved from cache
- Empty IP address no longer causes unnecessary API calls

### Removed

- Per-driver `retry` configuration (use root-level `GEOIP_RETRY` instead)

## [1.0.0] - 2024-01-01

### Added

- Initial release
- GeoJS driver
- IPData driver
- Request macros for `geoip()` and `anonymizedIp()`
- Cache support with tagging
