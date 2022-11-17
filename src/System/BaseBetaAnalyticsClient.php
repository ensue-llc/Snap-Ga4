<?php

namespace Ensue\GA4\System;

use Google\Analytics\Data\V1beta\Gapic\BetaAnalyticsDataGapicClient;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Str;

class BaseBetaAnalyticsClient extends BetaAnalyticsDataGapicClient
{

    public function getRunReportRequest(array $optionalArgs): RunReportRequest
    {
        $request = new RunReportRequest();
        foreach ($optionalArgs as $key => $value) {
            $camelKey = Str::camel('set_' . $key);
            if (empty($value)) {
                continue;
            }
            if (method_exists($request, $camelKey)) {
                call_user_func_array([$request, $camelKey], array_filter([$value]));
            }
        }

        return $request;
    }
}
