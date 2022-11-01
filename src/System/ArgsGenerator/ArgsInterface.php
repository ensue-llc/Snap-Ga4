<?php

namespace Ensue\AnalyticsV4\System\ArgsGenerator;

interface ArgsInterface
{
    public function builder(): ArgsInterface;

    public function setPropertyId(string $propertyId): ArgsInterface;

    public function setDateRange(string $startDate, string $endDate): ArgsInterface;

    public function setMultipleDateRange(array $dateRanges): ArgsInterface;

    public function setDimensions(array $dimensionNames): ArgsInterface;

    public function setMetrics(array $metricNames): ArgsInterface;

    public function setDimensionsFilter(array $dimensionsFilter): ArgsInterface;

    public function setMetricFilter(array $metricFilter): ArgsInterface;

    public function setMetricAggregations(array $aggregations): ArgsInterface;

    public function setLimit(int $limit): ArgsInterface;

    public function setOffset(int $offset): ArgsInterface;

    public function setKeepEmptyRows(bool $isEmpty = false): ArgsInterface;

    public function setOrderBy(array $orderBy, bool $desc = false): ArgsInterface;

    public function setCurrencyCode(string $currencyCode): ArgsInterface;

//TODO: will be implemented later if required
//    public function setCohortSpec(array $cohortSpec): ArgsBuilder;

    public function setReturnPropertyQuota(bool $quota): ArgsInterface;

    public function getArgs(): array;
}
