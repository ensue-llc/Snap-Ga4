<?php

namespace Ensue\GA4\System\OrderByMaker;

use Google\Analytics\Data\V1beta\OrderBy\DimensionOrderBy;

class DimensionOrderByMaker extends BaseOrderByMaker
{
    public function __construct()
    {
        $this->orderBy = new DimensionOrderBy();
    }

    public function setExpression(array $data): DimensionOrderByMaker
    {
        $this->orderBy->setDimensionName($data['name']);
        if (isset($data['type'])) {
            $this->orderBy->setOrderType(DimensionOrderBy\OrderType::value($data['type']));
        }

        return $this;
    }
}
