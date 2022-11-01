<?php

namespace Ensue\AnalyticsV4;

use Illuminate\Support\ServiceProvider;

class Provider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ga4.php', 'ga4');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ga4.php' => config_path('ga4.php'),
        ], 'ga4');
    }
}
