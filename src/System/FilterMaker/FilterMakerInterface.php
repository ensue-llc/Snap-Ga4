<?php

namespace Ensue\AnalyticsV4\System\FilterMaker;

interface FilterMakerInterface
{
    public function setExpression(array $data);

    public function get();
}
