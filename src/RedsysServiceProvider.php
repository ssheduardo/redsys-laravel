<?php

namespace Ssheduardo\Redsys;

use Sermepa\Tpv\Tpv;
use Illuminate\Support\ServiceProvider;

class RedsysServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes(
            [
                __DIR__ . '/config/config.php' => config_path('redsys.php'),
            ], 'config'
        );
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('tpv', function () {
            return new Tpv();
        });

    }
}