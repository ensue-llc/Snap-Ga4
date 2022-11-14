<?php

namespace Ensue\GA4\System\ArgBuilder;

use Ensue\GA4\System\FilterFactory;
use Ensue\GA4\System\OrderByFactory;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Filter;
use Google\Analytics\Data\V1beta\FilterExpression;
use Google\Analytics\Data\V1beta\FilterExpressionList;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\OrderBy;

class ArgBuilder implements ArgBuilderInterface
{
    protected \stdClass $query;

    public function __construct()
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('ga4.property_id');
    }

    public function builder(): ArgBuilder
    {
        $this->query = new \stdClass();
        $this->query->args['property'] = 'properties/' . config('ga4.property_id');

        return $this;
    }

    public function propertyId(string $propertyId): ArgBuilder
    {
        $this->query->args['property'] = 'properties/' . $propertyId;

        return $this;
    }

    public function dateRange(array $dateRange): ArgBuilder
    {
        $this->query->args['dateRanges'] = [new DateRange([
            'start_date' => $dateRange['start_date'],
            'end_date' => $dateRange['end_date'],
        ])];

        return $this;
    }

    public function dateRanges(array $dateRanges): ArgBuilder
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

    public function dimensions(array $dimensionNames): ArgBuilder
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

    public function metrics(array $metricNames): ArgBuilder
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

    public function dimensionFilter(array $dimensionFilter): ArgBuilder
    {
        $this->query->args['dimensionFilter'] = $this->filter($dimensionFilter);

        return $this;
    }

    private function filter(array $filter): FilterExpression
    {
        $filterExpression = new FilterExpression();
        if (isset($filter['filter'])) {
            $filterExpression->setFilter($this->createFilter($filter['filter']));
        }
        if (isset($filter['and_group'])) {
            $filterExpressionList = [];
            foreach ($filter['and_group'] as $expression) {
                $filterExpressionList[] = $this->createFilterExpression($expression);
            }
            $filterExpression->setAndGroup(new FilterExpressionList(['expressions' => $filterExpressionList]));
        }
        if (isset($filter['or_group'])) {
            $filterExpressionList = [];
            foreach ($filter['or_group'] as $expression) {
                $filterExpressionList[] = $this->createFilterExpression($expression);
            }
            $filterExpression->setOrGroup(new FilterExpressionList(['expressions' => $filterExpressionList]));
        }
        if (isset($filter['not_expression'])) {
            $filterExpression->setNotExpression($this->createFilterExpression($filter['not_expression']));
        }

        return $filterExpression;
    }

    private function createFilter(array $expression): Filter
    {
        return new Filter([
            'field_name' => $expression['field_name'],
            $expression['expression'] => FilterFactory::getFilterMaker($expression['expression'])
                ->getExpressionObject($expression['expression_data'])
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

    public function limit(int $limit): ArgBuilder
    {
        $this->query->args['limit'] = $limit;

        return $this;
    }

    public function offset(int $offset): ArgBuilder
    {
        $this->query->args['offset'] = $offset;

        return $this;
    }

    public function metricFilter(array $metricFilter): ArgBuilder
    {
        $this->query->args['metricFilter'] = $this->filter($metricFilter);

        return $this;
    }

    public function metricAggregations(array $aggregations): ArgBuilder
    {
        $this->query->args['metricAggregations'] = $aggregations;
    }

    public function orderBy(array $orderBy, bool $desc = false): ArgBuilder
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

    public function currencyCode(string $currencyCode): ArgBuilder
    {
        $this->query->args['currencyCode'] = $currencyCode;

        return $this;
    }

    public function returnPropertyQuota(bool $quota): ArgBuilder
    {
        $this->query->args['returnPropertyQuota'] = $quota;

        return $this;
    }
}
