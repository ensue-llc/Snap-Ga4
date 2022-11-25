# Google analytics 4: Data API v1

This package intends to provide analytics methods for Data API v1 [Beta Release].

## Getting Started

### Install
Run the following command:
```
composer require ensue/ga4
```

### Publish config and lang (optional)
```
php artisan vendor:publish --tag=ga4
```

### Dependencies
Follow the step 1 and 2 provided in
[Google Analytics Data API (GA4) QuickStart](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries).
Once you download the service account credentials, store it in **storage** directory.

### ENV changes
Add this parameters in your env file
```
GA4_PROPERTY_ID=<YOUR-PROPERTY-ID>
GA4_CREDENTIALS_JSON_PATH=<FILE-STORED-IN-STORAGE-DIR>
```

# Reporting 
Provide the "client email" the viewer access in your account user management from your admin setting

## Methods
#### Run Report
```
GA::runReport($inputs);
```

#### Run Batch Report
Upto 5 reports can be run at a time
```
GA::runBatchReport($inputs);
```

## Request Body
### Run single report
Maximum Dimensions allowed: 9 <br/>
Maximum Metrics allowed: 10

```
$inputs = [
  "date_range" => [
    "start_date" => "Y-m-d"
    "end_date" => "Y-m-d"
  ],
  "dimensions" => [],
  "metrics" => [],
  "dimension_filter" => [FILTER_OPTIONS],
  "metric_filter" => [FILTER_OPTIONS],
  "offset" => integer,
  "limit" => integer,
  "metric_aggregations": [],
  "order_bys": [],
  "currency_code": string,
  "cohort_spec": [],
  "keep_empty_rows": boolean,
  "return_property_quota": boolean
];
```

### Run Batch Report
```
$inputs = [
    [
        "title" => string,
        <key value pair of single report>
    ],
    [
        "title" => string,
        <key value pair of single report>
    ],
]
```
#### FILTER OPTIONS:
```
    "filter" => [
        "field_name" => "{DIMENSION_NAME}"
        "expression" => "{FILTER_EXPRESSION}"
        "expression_data" => []
    ],
    "and_group" => [
        [
            "field_name" => "{DIMENSION_NAME}"
            "expression" => "{FILTER_EXPRESSION}"
            "expression_data" => []
        ]
    ],
    "or_group" => [
        [
            "field_name" => "{DIMENSION_NAME}"
            "expression" => "{FILTER_EXPRESSION}"
            "expression_data" => []
        ]
     ],
     "not_expression" => [
        "field_name" => "{DIMENSION_NAME}"
        "expression" => "{FILTER_EXPRESSION}"
        "expression_data" => []
    ],
```
#### EXPRESSION DATA OPTIONS

#### OrderBy
