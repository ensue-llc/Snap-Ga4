<?php

namespace Ensue\AnalyticsV4\System;

use Ensue\AnalyticsV4\System\OrderByMaker\DimensionOrderByMaker;
use Ensue\AnalyticsV4\System\OrderByMaker\MetricOrderByMaker;
use Ensue\AnalyticsV4\System\OrderByMaker\PivotOrderByMaker;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class OrderByFactory
{
    /**
     * @param $maker
     * @return PivotOrderByMaker|MetricOrderByMaker|DimensionOrderByMaker
     */
    public static function getOrderByMaker($maker): PivotOrderByMaker|MetricOrderByMaker|DimensionOrderByMaker
    {
        return match ($maker) {
            'metric' => new MetricOrderByMaker(),
            'dimension' => new DimensionOrderByMaker(),
            'pivot' => new PivotOrderByMaker(),
            default => throw new BadRequestException(),
        };
    }
}
