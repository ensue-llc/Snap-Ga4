<?php

namespace Ensue\GA4\System\OrderByMaker;

use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;
use Google\Analytics\Data\V1beta\OrderBy\PivotOrderBy;

abstract class BaseOrderByMaker implements OrderByMakerInterface
{
    protected PivotOrderBy|MetricOrderBy|DimensionOrderBy $orderBy;

}
