<?php

namespace Ensue\GA4\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array runReport(array $args)
 * @method static array runBatchReport(array $args)
 *
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
