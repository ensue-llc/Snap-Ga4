<?php

namespace Ensue\GA4\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ensue\GA4\Repositories\GA4Repository
 */
class GA4 extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ga4';
    }
}
