<?php

namespace Ensue\AnalyticsV4\System\OrderByMaker;

use Google\Analytics\Data\V1beta\OrderBy\MetricOrderBy;

class MetricOrderByMaker extends BaseOrderByMaker
{
    public function __construct()
    {
        $this->orderBy = new MetricOrderBy();
    }

    public function setExpression(array $data): MetricOrderByMaker
    {
        $this->orderBy->setMetricName($data['name']);

        return $this;
    }
}
