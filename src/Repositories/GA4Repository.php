<?php

namespace Ensue\GA4\Repositories;

use Ensue\GA4\Constants\FilterExpression;
use Ensue\GA4\Interfaces\GA4Interface;
use Ensue\GA4\System\ArgBuilder\ArgBuilder;
use Ensue\GA4\System\ArgBuilder\ArgBuilderInterface;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JsonException;

class GA4Repository implements GA4Interface
{
    /**
     * @var BetaAnalyticsDataClient
     */
    protected BetaAnalyticsDataClient $client;

    private ArgBuilderInterface $args;

    /**
     * @throws ValidationException|JsonException
     */
    public function __construct()
    {
        $this->args = new ArgBuilder();
        $this->client = new BetaAnalyticsDataClient([
            'credentials' => json_decode(file_get_contents(config('ga4.service_account_credentials_json')), true, 512, JSON_THROW_ON_ERROR)
        ]);
    }

    /**
     * @throws ApiException
     */
    public function runReport(array $args): array
    {
        $inputs = $this->validateRequest($args);
        $result = $this->client->runReport($this->getCompiledArguments($inputs));

        return $this->parseResult($args, $result);
    }

    public function validateRequest(array $inputs): array
    {
        return Validator::validate($inputs, [
            'date_range' => 'required',
            'date_range.start_date' => 'required|date_format:Y-m-d',
            'date_range.end_date' => 'required|date_format:Y-m-d|after:date_range.start_date',

            'dimensions' => 'nullable|required_without:metrics|array|max:9',
            'dimensions.*' => 'nullable|string|distinct',

            'metrics' => 'nullable|required_without:dimensions|array|max:10',
            'metrics.*' => 'nullable|string|distinct',

            'dimension_filter' => 'nullable|array',

            'dimension_filter.filter' => 'nullable|array',
            'dimension_filter.filter.field_name' => 'required|string',
            'dimension_filter.filter.expression' => 'required|string|in:' . implode(',', FilterExpression::options()),
            'dimension_filter.filter.expression_data' => 'required|array',

            'dimension_filter.and_group' => 'nullable|array',
            'dimension_filter.and_group.*.field_name' => 'required|string',
            'dimension_filter.and_group.*.expression' => 'required|string|in:' . implode(',', FilterExpression::options()),
            'dimension_filter.and_group.*.expression_data' => 'required|array',

            'dimension_filter.or_group' => 'nullable|array',
            'dimension_filter.or_group.*.field_name' => 'required|string',
            'dimension_filter.or_group.*.expression' => 'required|string|in:' . implode(',', FilterExpression::options()),
            'dimension_filter.or_group.*.expression_data' => 'required|array',

            'dimension_filter.not_expression' => 'nullable|array',
            'dimension_filter.not_expression.field_name' => 'required|string',
            'dimension_filter.not_expression.expression' => 'required|string|in:' . implode(',', FilterExpression::options()),
            'dimension_filter.not_expression.expression_data' => 'required|array',

            'limit' => 'nullable|integer|max:1000000',
            'offset' => 'nullable|integer',
        ]);
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
