<?php

namespace Ensue\GA4\System\ArgBuilder;

use Ensue\GA4\Exceptions\InvalidConfigurationException;
use Ensue\GA4\System\FilterFactory;
use Ensue\GA4\System\OrderByFactory;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;

class ArgBuilderRepository implements ArgBuilderInterface
{
    protected \stdClass $query;

    public function __construct()
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('ga4.property_id');
    }

    public function builder(): ArgBuilderRepository
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('ga4.property_id');

        return $this;
    }

    public function propertyId(string $propertyId): ArgBuilderRepository
    {
        $this->query->args['property'] = 'properties/' . $propertyId;

        return $this;
    }

    public function dateRange(string $startDate, string $endDate): ArgBuilderRepository
    {
        $this->query->args['dateRanges'] = [new DateRange([
            'start_date' => $startDate,
            'end_date' => $endDate,
        ])];

        return $this;
    }

    public function dimensions(array $dimensionNames): ArgBuilderRepository
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

    public function metrics(array $metricNames): ArgBuilderRepository
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

    public function dimensionsFilter(array $dimensionsFilter): ArgBuilderRepository
    {
        $this->query->args['dimensionFilter'] = $this->filter($dimensionsFilter);

        return $this;
    }

    private function filter(array $filter): FilterExpression
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

    public function keepEmptyRows(bool $isEmpty = false): static
    {
        $this->query->args['keepEmptyRows'] = $isEmpty;

        return $this;
    }

    public function getArgs(): array
    {
        return $this->query->args;
    }

    public function multipleDateRange(array $dateRanges): ArgBuilderRepository
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

    public function limit(int $limit): ArgBuilderRepository
    {
        $this->query->args['limit'] = $limit;

        return $this;
    }

    public function offset(int $offset): ArgBuilderRepository
    {
        $this->query->args['offset'] = $offset;

        return $this;
    }

    public function metricFilter(array $metricFilter): ArgBuilderRepository
    {
        $this->query->args['metricFilter'] = $this->filter($metricFilter);

        return $this;
    }

    public function metricAggregations(array $aggregations): ArgBuilderRepository
    {
        $this->query->args['metricAggregations'] = $aggregations;
    }

    public function orderBy(array $orderBy, bool $desc = false): ArgBuilderRepository
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

    public function currencyCode(string $currencyCode): ArgBuilderRepository
    {
        $this->query->args['currencyCode'] = $currencyCode;

        return $this;
    }

    public function returnPropertyQuota(bool $quota): ArgBuilderRepository
    {
        $this->query->args['returnPropertyQuota'] = $quota;

        return $this;
    }
}
