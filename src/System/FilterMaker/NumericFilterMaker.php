<?php

namespace Ensue\GA4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\NumericFilter;
use Google\Analytics\Data\V1beta\Filter\NumericFilter\Operation;
use Google\Analytics\Data\V1beta\NumericValue;

class NumericFilterMaker extends BaseFilterMaker
{
    public function __construct()
    {
        $this->filter = new NumericFilter();
    }

    public function getExpressionObject(array $data): NumericFilter
    {
        $this->filter->setOperation(Operation::value($data['operation']));
        $this->filter->setValue($this->setNumericValue($data['value']['int64'], $data['value']['double']));

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
