<?php

namespace Ensue\GA4\System\FilterMaker;


use Google\Analytics\Data\V1beta\Filter\StringFilter;
use Google\Analytics\Data\V1beta\Filter\StringFilter\MatchType;

class StringFilterMaker extends BaseFilterMaker
{
    public function __construct()
    {
        $this->filter = new StringFilter();
    }

    public function setExpression(array $data): StringFilterMaker
    {
        $this->filter->setMatchType(MatchType::value($data['match_type']));
        $this->filter->setValue($data['value']);
        if(isset($data['case_sensitive'])) {
            $this->filter->setCaseSensitive($data['case_sensitive']);
        }

        return $this;
    }
}
