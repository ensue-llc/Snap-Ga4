<?php

namespace Ensue\AnalyticsV4\System\OrderByMaker;

interface OrderByMakerInterface
{
    public function setExpression(array $data);

    public function get();
}
