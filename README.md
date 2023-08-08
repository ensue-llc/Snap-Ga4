# Google analytics 4: Data API v1

This package aims to provide methods for working with the Data API v1 of Google Analytics 4.

## Getting Started

### Installation
You can install the package using Composer. Run the following command:
```
composer require ensue/ga4
```

### Configuration and Language Files (Optional)
```
php artisan vendor:publish --tag=ga4
```

### Dependencies
Follow the step 1 and 2 provided in
[Google Analytics Data API (GA4) QuickStart](https://developers.google.com/analytics/devguides/reporting/data/v1/quickstart-client-libraries).
After downloading the service account credentials, store them in the **storage** directory of your Laravel project.

### Environment Changes
Add the following parameters to your **.env** file:
```
GA4_PROPERTY_ID=<YOUR-PROPERTY-ID>
GA4_CREDENTIALS_JSON_PATH=<FILE-STORED-IN-STORAGE-DIR>
```

# Reporting 
To grant access to view the data, provide the "client email" access in your Google Analytics account's user management through your admin settings.
## Provided methods
#### Run single report
```
GA::runReport($attributes);
```

#### Run Batch Report
You can run up to 5 reports simultaneously.
```
GA::runBatchReport($attributes);
```

## Request Body
### Run single report
Maximum Dimensions allowed: 9 <br/>
Maximum Metrics allowed: 10

```
$attributes = [
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
  "order_by": [],
  "currency_code": string,
  "return_property_quota": boolean
];
```

### Run Batch Report
```
$attributes = [
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
#### Filter options
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
#### Expression options
##### String filter
```
     "expression" => FilterExpression::STRING_FILTER,
     "expression_data" => [
        "match_type" => MatchType::options
        "value" => "{VALUE}",
        "case_sesitive" => boolean,
     ]
```
##### In list filter
```
     "expression" => FilterExpression::IN_LIST_FILTER,
     "expression_data" => [
        "values" => [VALUES],
        "case_sensitive" => boolean,
     ]
```
##### Numeric filter
```
     "expression" => FilterExpression::NUMERIC_FILTER,
     "expression_data" => [
        "operation" => Operation::options,
        "value" => [
            "int64" => integer,
            "double" => integer
        ]
     ]
```
##### Between Filter
```
     "expression" => FilterExpression::BETWEEN_FILTER,
     "expression_data" => [
        "from" => [
            "int64" => integer,
            "double" => integer
        ],
        "to" => [
            "int64" => integer,
            "double" => integer
        ]
     ]
```
#### Order by options
##### Order by dimension
```
    "expression" => OrderBy::DIMENSION,
    "expression_data" => [
        "name" => Dimensions::NAME
    ]
```
##### Order by metric
```
    "expression" => OrderBy::METRIC,
    "expression_data" => [
        "name" => Dimensions::NAME
    ]
```
##### Order by pivot
```
    "expression" => OrderBy::PIVOT,
    "expression_data" => [
        "name" => Dimensions::NAME,
        "pivotSelections" => [
            [
                "dimensionName" => string,
                "dimensionValue" => string
            ]
        ]
    ]
```
## Contributing
Feel free to contribute :)
