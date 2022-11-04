<?php

namespace Ensue\GA4\System\ArgsGenerator;

interface ArgsInterface
{
    public function builder(): ArgsInterface;

    public function propertyId(string $propertyId): ArgsInterface;

    public function dateRange(string $startDate, string $endDate): ArgsInterface;

    public function multipleDateRange(array $dateRanges): ArgsInterface;

    public function dimensions(array $dimensionNames): ArgsInterface;

    public function metrics(array $metricNames): ArgsInterface;

    public function dimensionsFilter(array $dimensionsFilter): ArgsInterface;

    public function metricFilter(array $metricFilter): ArgsInterface;

    public function metricAggregations(array $aggregations): ArgsInterface;

    public function limit(int $limit): ArgsInterface;

    public function offset(int $offset): ArgsInterface;

    public function keepEmptyRows(bool $isEmpty = false): ArgsInterface;

    public function orderBy(array $orderBy, bool $desc = false): ArgsInterface;

    public function currencyCode(string $currencyCode): ArgsInterface;

//TODO: will be implemented later if required
//    public function CohortSpec(array $cohortSpec): ArgsBuilder;

    public function returnPropertyQuota(bool $quota): ArgsInterface;

    public function getArgs(): array;
}
