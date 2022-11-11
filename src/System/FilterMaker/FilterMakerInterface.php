<?php

namespace Ensue\GA4\System\FilterMaker;

interface FilterMakerInterface
{
    public function getExpressionObject(array $data);

    public function get();
}
