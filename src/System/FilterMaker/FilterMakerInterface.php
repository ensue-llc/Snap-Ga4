<?php

namespace Ensue\GA4\System\FilterMaker;

interface FilterMakerInterface
{
    public function setExpression(array $data);

    public function get();
}
