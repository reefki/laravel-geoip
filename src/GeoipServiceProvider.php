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
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/geoip.php', 'geoip');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/geoip.php' => config_path('geoip.php'),
            ], 'config');
        }

        $this->app->singleton(GeoipManager::class, fn ($app) => new GeoipManager($app));

        Request::macro(
            'anonymizedIp',
            /**
             * Get the anonymized IP address.
             *
             * @return string|null
             */
            function (): ?string {
                /** @var Request $this */
                $ip = $this->ip();

                return $ip ? IpUtils::anonymize($ip) : null;
            }
        );

        Request::macro(
            'geoip',
            /**
             * Get the geoip information for the request's IP address.
             *
             * @param  bool  $anonymize
             * @param  bool  $cache
             * @return \Reefki\Geoip\GeoipData|null
             */
            function (bool $anonymize = false, bool $cache = true): ?GeoipData {
                /** @var Request $request */
                $request = $this;
                $ip = $request->ip();

                if ($ip === null) {
                    return null;
                }

                if ($anonymize) {
                    $ip = IpUtils::anonymize($ip);
                }

                return app(GeoipManager::class)->get($ip, $cache);
            }
        );
    }
}
