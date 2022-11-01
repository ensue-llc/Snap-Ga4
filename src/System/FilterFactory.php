<?php

namespace Ensue\AnalyticsV4\System;

use Ensue\AnalyticsV4\System\FilterMaker\BetweenFilterMaker;
use Ensue\AnalyticsV4\System\FilterMaker\InListFilterMaker;
use Ensue\AnalyticsV4\System\FilterMaker\NumericFilterMaker;
use Ensue\AnalyticsV4\System\FilterMaker\StringFilterMaker;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class FilterFactory
{
    /**
     * @param $maker
     * @return InListFilterMaker|NumericFilterMaker|BetweenFilterMaker|StringFilterMaker
     */
    public static function getFilterMaker($maker): InListFilterMaker|NumericFilterMaker|BetweenFilterMaker|StringFilterMaker
    {
        return match ($maker) {
            'string_filter' => new StringFilterMaker(),
            'in_list_filter' => new InListFilterMaker(),
            'numeric_filter' => new NumericFilterMaker(),
            'between_filter' => new BetweenFilterMaker(),
            default => throw new BadRequestException(),
        };
    }
}
