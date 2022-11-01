<?php

namespace Ensue\AnalyticsV4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\BetweenFilter;
use Google\Analytics\Data\V1beta\Filter\InListFilter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;

abstract class BaseFilterMaker implements FilterMakerInterface
{
    protected StringFilter|InListFilter|NumericFilter|BetweenFilter $filter;

    /**
     * @return StringFilter|InListFilter|NumericFilter|BetweenFilter
     */
    public function get(): StringFilter|InListFilter|NumericFilter|BetweenFilter
    {
        return $this->filter;
    }


}
