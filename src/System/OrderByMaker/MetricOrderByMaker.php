<?php

namespace Ensue\GA4\System\OrderByMaker;

use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;

class MetricOrderByMaker extends BaseOrderByMaker
{
    public function __construct()
    {
        $this->orderBy = new MetricOrderBy();
    }

    public function getExpressionObject(array $data): MetricOrderBy
    {
        $this->orderBy->setMetricName($data['name']);

        return $this->orderBy;
    }
}
