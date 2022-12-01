<?php

namespace Ensue\GA4\Interfaces;

interface GA4Interface
{
    /**
     * Returns a customized report of your Google Analytics event data. Reports
     * contain statistics derived from data collected by the Google Analytics
     * tracking code. The data returned from the API is as a table with columns
     * for the requested dimensions and metrics. Metrics are individual
     * measurements of user activity on your property, such as active users or
     * event count. Dimensions break down metrics across some common criteria,
     * such as country or event name.
     *
     * @param array $args
     * @return array
     */
    public function runReport(array $args): array;

    /**
     * Returns multiple reports in a batch. All reports must be for the same
     * GA4 Property.
     *
     * @param array $args
     * @return array
     */
    public function runBatchReport(array $args): array;
}
