<?php

namespace Ensue\AnalyticsV4\System\OrderByMaker;

use Google\Analytics\Data\V1beta\OrderBy\PivotOrderBy;

class PivotOrderByMaker extends BaseOrderByMaker
{
    public function __construct()
    {
        $this->orderBy = new PivotOrderBy();
    }

    public function setExpression(array $data): PivotOrderByMaker
    {
        $this->orderBy->setMetricName($data['name']);
        if (isset($data['pivotSelections'])) {
            $pivotSelections = [];
            foreach ($data['pivotSelections'] as $selection) {
                $selection = new PivotOrderBy\PivotSelection();
                $selection->setDimensionName($selection['dimensionName']);
                $selection->setDimensionValue($selection['dimensionValue']);
                $pivotSelections[] = $selection;
            }
            $this->orderBy->setPivotSelections($pivotSelections);
        }

        return $this;
    }
}