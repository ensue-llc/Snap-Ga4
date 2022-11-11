<?php

namespace Ensue\GA4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\InListFilter;

class InListFilterMaker extends BaseFilterMaker
{

    public function __construct()
    {
        $this->filter = new InListFilter();
    }

    public function getExpressionObject(array $data): InListFilter
    {
        $this->filter->setValues($data['values']);
        if(isset($data['case_sensitive'])) {
            $this->filter->setCaseSensitive($data['case_sensitive']);
        }

        return $this->filter;
    }
}
