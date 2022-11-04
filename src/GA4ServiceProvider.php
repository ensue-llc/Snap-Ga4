<?php

namespace Ensue\GA4;

use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\Repositories\GA4Repository;
use Ensue\GA4\System\ArgBuilder\ArgBuilderInterface;
use Ensue\GA4\System\ArgBuilder\ArgBuilderRepository;
use Illuminate\Support\ServiceProvider;

class GA4ServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ga4.php', 'ga4');
        $this->app->bind(GA4Interface::class, GA4Repository::class);
        $this->app->bind(ArgBuilderInterface::class, ArgBuilderRepository::class);
    }

    public function boot(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'ga4');
        $this->publishes([
            __DIR__ . '/../config/ga4.php' => config_path('ga4.php'),
            __DIR__ . '/../lang' => $this->app->langPath(),
        ], 'ga4');
    }
}
