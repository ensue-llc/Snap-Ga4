<?php

namespace Ensue\GA4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\BetweenFilter;
use Google\Analytics\Data\V1beta\NumericValue;

class BetweenFilterMaker extends BaseFilterMaker
{
    public function __construct()
    {
        $this->filter = new BetweenFilter();
    }

    public function getExpressionObject(array $data): BetweenFilter
    {
        $this->filter->setFromValue($this->setNumericValue($data['from']['int64'], $data['from']['double']));
        $this->filter->setToValue($this->setNumericValue($data['to']['int64'], $data['to']['double']));

        return $this->filter;
    }

    private function setNumericValue(string $int64Value, int $doubleValue): NumericValue
    {
        $numeric = new NumericValue();
        $numeric->setInt64Value($int64Value);
        $numeric->setDoubleValue($doubleValue);

        return $numeric;
    }
}
