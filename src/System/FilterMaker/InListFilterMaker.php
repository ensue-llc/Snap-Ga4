<?php

namespace Ensue\AnalyticsV4\System\FilterMaker;

use Google\Analytics\Data\V1beta\Filter\InListFilter;

class InListFilterMaker extends BaseFilterMaker
{

    public function __construct()
    {
        $this->filter = new InListFilter();
    }

    public function setExpression(array $data): InListFilterMaker
    {
        $this->filter->setValues($data['values']);
        if(isset($data['case_sensitive'])) {
            $this->filter->setCaseSensitive($data['case_sensitive']);
        }

        return $this;
    }
}
