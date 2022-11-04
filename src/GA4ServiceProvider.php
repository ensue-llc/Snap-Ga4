<?php

namespace Ensue\GA4;

use Ensue\GA4\Exceptions\InvalidConfigurationException;
use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\Repositories\GA4Repository;
use Illuminate\Support\ServiceProvider;

class GA4ServiceProvider extends ServiceProvider
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
        $this->guardAgainstInvalidConfiguration();
    }

    protected function guardAgainstInvalidConfiguration(): void
    {
        $analyticsConfig = config('ga4');
        if (empty($analyticsConfig['property_id'])) {
            throw new InvalidConfigurationException();
        }

        if (is_array($analyticsConfig['service_account_credentials_json'])) {
            return;
        }

        if (!file_exists($analyticsConfig['service_account_credentials_json'])) {
            throw new InvalidConfigurationException();
        }
    }
}
