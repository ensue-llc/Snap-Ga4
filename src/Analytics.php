<?php

namespace Ensue\GA4;

use Ensue\GA4\System\ArgsGenerator\RequestArgs;
use Google\Analytics\Data\V1beta\BatchRunReportsResponse;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use JsonException;

class Analytics
{
    /**
     * @var BetaAnalyticsDataClient
     */
    protected BetaAnalyticsDataClient $client;

    /**
     * @throws ValidationException|JsonException
     */
    public function __construct(private RequestArgs $args)
    {
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => json_decode(file_get_contents(config('analyticsV4.service_account_credentials_json')), true, 512, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws ApiException
     */
    public function runReport(array $args): RunReportResponse
    {
        return $this->client->runReport($this->generateArguments($args));
    }

    /**
     * @param array $args
     * @return array
     */
    private function generateArguments(array $args): array
    {
        $this->args->builder()
            ->setDateRange($args['date_range']['start_date'], $args['date_range']['end_date']);
        if (isset($args['property_id'])) {
            $this->args->setPropertyId($args['property_id']);
        }
        if (isset($args['dimensions'])) {
            $this->args->setDimensions($args['dimensions']);
        }
        if (isset($args['metrics'])) {
            $this->args->setMetrics($args['metrics']);
        }
        if (isset($args['dimensions_filter'])) {
            $this->args->setDimensionsFilter($args['dimensions_filter']);
        }
        if (isset($args['metrics_filter'])) {
            $this->args->setMetricFilter($args['metrics_filter']);
        }
        if (isset($args['offset'])) {
            $this->args->setOffset($args['offset']);
        }
        if (isset($args['limit'])) {
            $this->args->setLimit($args['limit']);
        }
        if (isset($args['metric_aggregations'])) {
            $this->args->setMetricAggregations($args['metric_aggregations']);
        }
        if (isset($args['order_by'])) {
            $this->args->setOrderBy($args['order_by']);
        }
        if (isset($args['currency_code'])) {
            $this->args->setCurrencyCode($args['currency_code']);
        }
        if (isset($args['keep_empty_rows'])) {
            $this->args->setKeepEmptyRows($args['keep_empty_rows']);
        }
        if (isset($args['returnPropertyQuota'])) {
            $this->args->setReturnPropertyQuota($args['returnPropertyQuota']);
        }

        return $this->args->getArgs();

    }

    public function attachArgs(array $args): void
    {
        foreach ($args as $key => $value) {
            if (method_exists($this, $key)) {
                call_user_func_array([$this, $key], array_filter([$value], 'strlen'));
            }
        }
    }

    /**
     * @throws ApiException
     */
    public function runBatchReport(array $args): BatchRunReportsResponse
    {
        $inputs = [];
        foreach ($args as $key => $arg) {
            if (in_array($key, ['share', 'file_download', 'video'])) {
                $inputs[] = $this->generateArguments($arg);
            }
        }

        return $this->client->batchRunReports($inputs);
    }

}
