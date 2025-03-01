<?php

namespace Ensue\GA4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\BetweenFilter;
use Google\Analytics\Data\V1beta\Filter\InListFilter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter;

abstract class BaseFilterMaker implements FilterMakerInterface
{
    protected StringFilter|InListFilter|NumericFilter|BetweenFilter $filter;

}
