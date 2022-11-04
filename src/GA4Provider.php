<?php

namespace Ensue\GA4;

use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\Repositories\GA4Repository;
use Illuminate\Support\ServiceProvider;

class GA4Provider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ga4.php', 'ga4');
        $this->app->bind(GA4Interface::class, GA4Repository::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/ga4.php' => config_path('ga4.php'),
        ], 'ga4');
    }
}
