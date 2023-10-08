<?php

namespace Reefki\Geoip;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpFoundation\IpUtils;

class GeoipServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geoip.php', 'geoip');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/geoip.php' => config_path('geoip.php'),
            ], 'config');
        }

        $this->app->singleton(GeoipManager::class, fn ($app) => new GeoipManager($app));

        Request::macro('anonymizedIp', function () {
            /** @var \Illuminate\Http\Request $this */
            return $this->ip() ? IpUtils::anonymize($this->ip()) : null;
        });

        Request::macro('geoip', function (bool $anonymize = false, bool $cache = true) {
            /** @var \Illuminate\Http\Request $this */
            /** @phpstan-ignore-next-line */
            $ipAddress = $anonymize ? $this->anonymizedIp() : $this->ip();
            return app(GeoipManager::class)->get($ipAddress, $cache);
        });
    }
}
