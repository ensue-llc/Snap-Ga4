<?php

namespace Ensue\GA4\Repositories;

use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\System\ArgBuilder\ArgBuilder;
use Ensue\GA4\System\BaseAnalyticsClient;
use Google\Analytics\Data\V1beta\RunReportResponse;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Protobuf\Internal\RepeatedField;
use Illuminate\Support\Str;

class GA4Repository implements GA4Interface
{
    /**
     * @var BaseAnalyticsClient
     */
    protected BaseAnalyticsClient $client;

    /**
     * @throws ValidationException|\JsonException
     */
    public function __construct()
    {
        $this->client = new BaseAnalyticsClient([
            'credentials' => json_decode(file_get_contents(config('ga4.service_account_credentials_json')), true, 512, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws ApiException
     */
    public function runReport(array $args): array
    {
        $response = $this->client->runReport($this->getCompiledArguments($args));

        return $this->parseReportResult($args, $response);
    }

    /**
     * @param array $args
     * @return array
     */
    protected function getCompiledArguments(array $args): array
    {
        $arg = new ArgBuilder();
        foreach ($args as $key => $value) {
            $camelKey = Str::camel($key);
            if (empty($value)) {
                continue;
            }
            if (method_exists($arg, $camelKey)) {
                call_user_func_array([$arg, $camelKey], array_filter([$value]));
            }
        }

        return $arg->getArgs();
    }

    /**
     * @param array $inputs
     * @param RunReportResponse $response
     * @return array
     */
    protected function parseReportResult(array $inputs, RunReportResponse $response): array
    {
        $result = [];
        foreach ($response->getRows() as $row) {
            $arr = [];
            foreach ($row->getDimensionValues() as $index => $dimensionValue) {
                $arr[$inputs['dimensions'][$index]] = $dimensionValue->getValue();
            }
            foreach ($row->getMetricValues() as $index => $metricValue) {
                $arr[$inputs['metrics'][$index]] = $metricValue->getValue();
            }
            $result[] = $arr;
        }

        return $result;
    }

    /**
     * @throws ApiException|\ErrorException
     */
    public function runBatchReport(array $args): array
    {
        $requests = [];
        foreach ($args as $arg) {
            $requests[] = $this->client->getRunReportRequest($this->getCompiledArguments($arg));
        }
        $response = $this->client->batchRunReports([
            'requests' => $requests,
            'property' => 'properties/' . config('ga4.property_id'),
        ]);

        return $this->parseBatchReportResult($args, $response->getReports());
    }

    /**
     * @param array $inputs
     * @param RepeatedField $response
     * @return array
     * @throws \ErrorException
     */
    protected function parseBatchReportResult(array $inputs, RepeatedField $response): array
    {
        $result = [];
        for ($i = 0; $i < $response->count(); $i++) {
            $result[$inputs[$i]['title']] = $this->parseReportResult($inputs[$i], $response->offsetGet($i));
        }

        return $result;
    }
}
