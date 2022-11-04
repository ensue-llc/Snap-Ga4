<?php

namespace Ensue\GA4\System\ArgBuilder;

interface ArgBuilderInterface
{
    public function builder(): ArgBuilderInterface;

    public function propertyId(string $propertyId): ArgBuilderInterface;

    public function dateRange(string $startDate, string $endDate): ArgBuilderInterface;

    public function multipleDateRange(array $dateRanges): ArgBuilderInterface;

    public function dimensions(array $dimensionNames): ArgBuilderInterface;

    public function metrics(array $metricNames): ArgBuilderInterface;

    public function dimensionsFilter(array $dimensionsFilter): ArgBuilderInterface;

    public function metricFilter(array $metricFilter): ArgBuilderInterface;

    public function metricAggregations(array $aggregations): ArgBuilderInterface;

    public function limit(int $limit): ArgBuilderInterface;

    public function offset(int $offset): ArgBuilderInterface;

    public function keepEmptyRows(bool $isEmpty = false): ArgBuilderInterface;

    public function orderBy(array $orderBy, bool $desc = false): ArgBuilderInterface;

    public function currencyCode(string $currencyCode): ArgBuilderInterface;

//TODO: will be implemented later if required
//    public function CohortSpec(array $cohortSpec): ArgsBuilder;

    public function returnPropertyQuota(bool $quota): ArgBuilderInterface;

    public function getArgs(): array;
}
