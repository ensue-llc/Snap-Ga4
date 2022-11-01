<?php

namespace Ensue\AnalyticsV4\System\ArgsGenerator;

use Ensue\AnalyticsV4\System\FilterFactory;
use Ensue\AnalyticsV4\System\OrderByFactory;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;

class RequestArgs implements ArgsInterface
{
    protected \stdClass $query;

    public function __construct()
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('analyticsV4.property_id');
    }

    public function builder(): ArgsInterface
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('analyticsV4.property_id');

        return $this;
    }

    public function setPropertyId(string $propertyId): ArgsInterface
    {
        $this->query->args['property'] = 'properties/' . $propertyId;

        return $this;
    }

    public function setDateRange(string $startDate, string $endDate): RequestArgs
    {
        $this->query->args['dateRanges'] = [new DateRange([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])];

        return $this;
    }

    public function setDimensions(array $dimensionNames): RequestArgs
    {
        $dimensions = [];
        foreach ($dimensionNames as $dimensionName) {
            $dimensions[] = new Dimension(['name' => $dimensionName]);
        }
        if (!empty($dimensions)) {
            $this->query->args['dimensions'] = $dimensions;
        }

        return $this;
    }

    public function setMetrics(array $metricNames): RequestArgs
    {
        $metrics = [];
        foreach ($metricNames as $metricName) {
            $metrics[] = new Metric(['name' => $metricName]);
        }
        if (!empty($metrics)) {
            $this->query->args['metrics'] = $metrics;
        }

        return $this;
    }

    public function setDimensionsFilter(array $dimensionsFilter): RequestArgs
    {
        $this->query->args['dimensionFilter'] = $this->setFilter($dimensionsFilter);

        return $this;
    }

    private function setFilter(array $filter): FilterExpression
    {
        $filterExpression = new FilterExpression();
        if (isset($filter['filter'])) {
            $filterExpression->setFilter($this->createFilter($filter['filter']));
        }
        if (isset($filter['andGroup'])) {
            $filterExpressionList = [];
            foreach ($filter['andGroup'] as $expression) {
                $filterExpressionList[] = $this->createFilterExpression($expression);
            }
            $filterExpression->setAndGroup(new FilterExpressionList(['expressions' => $filterExpressionList]));
        }
        if (isset($filter['orGroup'])) {
            $filterExpressionList = [];
            foreach ($filter['orGroup'] as $expression) {
                $filterExpressionList[] = $this->createFilterExpression($expression);
            }
            $filterExpression->setOrGroup(new FilterExpressionList(['expressions' => $filterExpressionList]));
        }
        if (isset($filter['notExpression'])) {
            $filterExpression->setNotExpression($this->createFilterExpression($filter['notExpression']));
        }

        return $filterExpression;
    }

    private function createFilter(array $expression): Filter
    {
        return new Filter([
            'field_name' => $expression['field_name'],
            $expression['expression'] => FilterFactory::getFilterMaker($expression['expression'])
                ->setExpression($expression['expression_data'])
                ->get()
        ]);
    }

    private function createFilterExpression(array $expression): FilterExpression
    {
        return new FilterExpression([
            'filter' => $this->createFilter($expression)
        ]);
    }

    public function setKeepEmptyRows(bool $isEmpty = false): static
    {
        $this->query->args['keepEmptyRows'] = $isEmpty;

        return $this;
    }

    public function getArgs(): array
    {
        return $this->query->args;
    }

    public function setMultipleDateRange(array $dateRanges): ArgsInterface
    {
        $dateRangeList = [];
        foreach ($dateRanges as $dateRange) {
            $dateRangeList[] = new DateRange([
                'start_date' => $dateRange['start_date'],
                'end_date' => $dateRange['end_date'],
            ]);
        }
        $this->query->args['dateRanges'] = $dateRangeList;

        return $this;
    }

    public function setLimit(int $limit): ArgsInterface
    {
        $this->query->args['limit'] = $limit;

        return $this;
    }

    public function setOffset(int $offset): ArgsInterface
    {
        $this->query->args['offset'] = $offset;

        return $this;
    }

    public function setMetricFilter(array $metricFilter): ArgsInterface
    {
        $this->query->args['metricFilter'] = $this->setFilter($metricFilter);

        return $this;
    }

    public function setMetricAggregations(array $aggregations): ArgsInterface
    {
        $this->query->args['metricAggregations'] = $aggregations;
    }

    public function setOrderBy(array $orderBy, bool $desc = false): ArgsInterface
    {
        //order by can be one of the following: metric, dimension, pivot
        $order = new OrderBy([
            'desc' => $desc,
            $orderBy['expression'] => OrderByFactory::getOrderByMaker($orderBy['expression'])
                ->setExpression($orderBy['expression_data'])
                ->get()
        ]);
        $order->setDesc($desc);

        $this->query->args['orderBys'][] = $order;
    }

    public function setCurrencyCode(string $currencyCode): ArgsInterface
    {
        $this->query->args['currencyCode'] = $currencyCode;

        return $this;
    }

    public function setReturnPropertyQuota(bool $quota): ArgsInterface
    {
        $this->query->args['returnPropertyQuota'] = $quota;

        return $this;
    }
}
