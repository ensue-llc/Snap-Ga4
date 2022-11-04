<?php

namespace Ensue\GA4\Repositories;

use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\System\ArgBuilder\ArgBuilderInterface;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Support\Str;
use JsonException;

class GA4Repository implements GA4Interface
{
    /**
     * @var BetaAnalyticsDataClient
     */
    protected BetaAnalyticsDataClient $client;

    /**
     * @throws ValidationException|JsonException
     */
    public function __construct(private ArgBuilderInterface $args)
    {
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => json_decode(file_get_contents(config('ga4.service_account_credentials_json')), true, 512, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws ApiException
     */
    public function runReport(array $args): array
    {
        $result = $this->client->runReport($this->getCompiledArguments($args));

        return $this->parseResult($args, $result);
    }

    /**
     * @param array $args
     * @return array
     */
    private function getCompiledArguments(array $args): array
    {
        $this->args->builder()->dateRange($args['date_range']['start_date'], $args['date_range']['end_date']);
        foreach ($args as $key => $value) {
            if (method_exists($this->args, $key)) {
                call_user_func_array([$this->args, Str::camel($key)], array_filter([$value]));
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
     * @throws ApiException
     */
    public function runBatchReport(array $args): array
    {
        $inputs = [];
        foreach ($args as $key => $arg) {
            if (in_array($key, ['share', 'file_download', 'video'])) {
                $inputs[] = $this->getCompiledArguments($arg);
            }
        }

        $result = $this->client->batchRunReports($inputs);
        return [];
    }

}
