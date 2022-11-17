<?php

namespace Ensue\GA4\Repositories;

use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\System\ArgBuilder\ArgBuilder;
use Ensue\GA4\System\ArgBuilder\ArgBuilderInterface;
use Ensue\GA4\System\BaseBetaAnalyticsClient;
use ErrorException;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Support\Str;
use JsonException;

class GA4Repository implements GA4Interface
{
    /**
     * @var BaseBetaAnalyticsClient
     */
    protected BaseBetaAnalyticsClient $client;

    private ArgBuilderInterface $args;

    /**
     * @throws ValidationException|JsonException
     */
    public function __construct()
    {
        $this->args = new ArgBuilder();
        $this->client = new BaseBetaAnalyticsClient([
            'credentials' => json_decode(file_get_contents(config('ga4.service_account_credentials_json')), true, 512, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws ApiException
     */
    public function runReport(array $args): array
    {
        try {
            $response = $this->client->runReport($this->getCompiledArguments($args));
        } finally {
            $this->client->close();
        }

        return $this->parseResult($args, $response);
    }

    /**
     * @param array $args
     * @return array
     */
    private function getCompiledArguments(array $args): array
    {
        $this->args->builder();
        foreach ($args as $key => $value) {
            $camelKey = Str::camel($key);
            if (empty($value)) {
                continue;
            }
            if (method_exists($this->args, $camelKey)) {
                call_user_func_array([$this->args, $camelKey], array_filter([$value]));
            }
        }

        return $this->args->getArgs();
    }

    private function parseResult(array $inputs, $response): array
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
     * @throws ApiException|ErrorException
     */
    public function runBatchReport(array $args): array
    {
        $requests = [];
        foreach ($args as $arg) {
            $requests[] = $this->client->getRunReportRequest($this->getCompiledArguments($arg));
        }
        try {
            $response = $this->client->batchRunReports([
                'requests' => $requests,
                'property' => 'properties/' . config('ga4.property_id'),
            ]);
        } finally {
            $this->client->close();
        }
        $reports = $response->getReports();
        $result = [];
        for ($i = 0; $i < $reports->count(); $i++) {
            $result[$args[$i]['title']] = $this->parseResult($args[$i], $reports->offsetGet($i));
        }

        return $result;
    }
}
