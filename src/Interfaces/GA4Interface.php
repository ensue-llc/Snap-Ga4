<?php

namespace Ensue\GA4\Interfaces;

interface GA4Interface
{

    public function runReport(array $args): array;

    public function runBatchReport(array $args): array;
}
